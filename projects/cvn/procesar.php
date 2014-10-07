<?php
require('tools/WS_CVN.php');
require('tools/Serializer.php');
require('db/conexion.ini.php');
require('models/Persona.php');
	
global $conexion;
//$conexion = $GLOBALS['conexion'];

/*
Encargado de procesar los datos del PDF realizando una conexión contra el webservice proporcionado
Este es independiente a los demás es decir se puede ejecutar cuando haga falta,
Actuará procesando todos los PDF almacenados en la tabla y cuya columna procesado sea igual a 0
*/
	//$conexion = new db("localhost","root","funiber","cvn");
	//$query = "SELECT * FROM persona WHERE procesado='0'"; //se consultan todas las personas q no se han procesado
	
	$query = "SELECT * FROM persona WHERE estado_procesado='_PENDIENTE' AND estado='publico'"; //se consultan todas las personas q no se han procesado
	
	//$query = "SELECT * FROM persona WHERE id=294"; //se consultan todas las personas q no se han procesado
	$enlace = $conexion->query($query);
	
	echo "<h3>Resultados</h3>";
	$flagError = false;
	$mensajeError = "";
	while($filas=$conexion->fetch_array($enlace)){		
		$id=$filas['id'];//ID DE PERSONA		
		$fichero=$filas['CVNPdf'];
		$email = $filas['email'];

		$input=new cvnPdf2CvnRootBean();
		$input->user="uneatlantico01";
		$input->passwd="jJDbWXaEOMRB";
		$input->pdfBytes=$fichero;

		$xml=new WS_CVN();
		$i=new cvnPdf2CvnRootBeanResponse();
		$i->result=$xml->cvnPdf2CvnRootBean($input);
		$su = XmlSerializer::toXml($i->result);

	    $xm = parseToXML(htmlspecialchars($su));
		
		//$nombre_temp = "cvnItemBean_".date("dmYhms").".xml";//nombre de archivo temporal			
		$nombre_temp = dirname(__FILE__) . "/public/doc/temporal/cvnItemBean.xml";
		$fecha_modificacion = date("Y-m-d");//fecha de modificacion
		file_put_contents($nombre_temp,$xm);//se agrega el xm al archivo temporal			
		$xml = $nombre_temp; //se asigna a otra variable de nombre xml
		
		$resEnvio = verificarEnvio($xml, $filas);
		$status = $resEnvio['status'];
		$mensaje = $resEnvio['mensaje'];
		$mensajePostulante = $resEnvio['mensaje_postulante'];
		
		if($status){			
			//se envia a construir todo el proceso de codigos
			build($xml,$id,$fecha_modificacion,$conexion);//ARCHIVO XML, ID PESONA, FECHA_MODIFICACION, VARIABLE DE CONEXION
			unlink($xml);//se elimina el archivo
			
			$xmGuardar = addslashes($xm);//se escapa caracteres especial para ser guardados en base
			//$sqlUpdate="UPDATE persona SET xml=\"$xmGuardar\", procesado='1' WHERE id='$id'";	
			//$sqlUpdate="UPDATE persona SET xml=\"$xmGuardar\", procesado='1', estado='PROCESADO' WHERE id='$id'";
			$sqlUpdate="UPDATE persona SET xml=\"$xmGuardar\", estado_procesado='_PROCESADO' WHERE id='$id'";
		    $conexion->query($sqlUpdate);
		} 
		else{
			$flagError=true;			
			$sqlUpdate="UPDATE persona SET estado_procesado='_ERROR' WHERE id='$id'";
			$conexion->query($sqlUpdate);
			$mensajeError.=$mensaje;	
			//en caso de error se debe noetificar al postulante
			$datosPostulantes[]=array('email'=>$email,'mensaje'=>$mensajePostulante);		
		}
		echo $mensaje;
	}
	//verificar si hubo error
	if($flagError){		
		enviarCorreo($mensajeError);
		enviarCorreoPostulante($datosPostulantes);			
	}	
	echo "<br/>Termino la ejecucion del script...";

function verificarEnvio($fileXml, $datosPersona){
	$idPersona = $datosPersona['id'];
	$persona = $datosPersona['apellidos'].' '.$datosPersona['nombre'];
			
	$xmlReference = simplexml_load_file($fileXml);		
	$errorCode = $xmlReference->xpath("//cvnRootResultBean/errorCode");
	$errorMessage = $xmlReference->xpath("//cvnRootResultBean/errorMessage");
	$errorCode = $errorCode[0].'';	
	$errorMessage = $errorMessage[0].'';
	//verificar si ha retornado un error	
	if($errorCode!='0' || $errorMessage!='ok'){
		$data['status']	= false;
		$data['mensaje']= " - Error al procesar persona: <b>$persona</b> (ID : $idPersona) , Detalle de error: $errorMessage <br/>";
		$data['mensaje_postulante']= " - Error al procesar CVN, Detalle de error: $errorMessage <br/>";
	}else{				
		$data['status']  = true;
		$data['mensaje'] = "Persona <b>$persona</b>: (ID: $idPersona) procesado con exito: Estado: $errorMessage <br/>";
		$data['mensaje_postulante']= " - Error al procesar CVN, Detalle de error: $errorMessage <br/>";
	}	
	return $data;
}

function enviarCorreo($msj){
	$mensaje = '<h3>Errores al ejecutar script.</h3>';
	$mensaje .= 'Durante la ejecuci&oacute;n del script de procesamiento de CV se produjeron algunos errores. A continuaci&oacute;n se detalla los errores:<br/><br/>';
	$mensaje .= $msj;
	$mensaje .= '<br/> Saludos';	
	//$enviar['remitente'] 		= 'ctfuniber+uneatlantico@funiber.org';
	$enviar['remitente'] 		= 'info@uneatlantico.es';
	$enviar['destinatarios'] 	= 'david.lolin@funiber.org';
	$enviar['copias'] 			= 'alicia.bustamante@uneatlantico.es,jesus.pena@uneatlantico.es';
		
	//$enviar['destinatarios'] 	= 'ignacio.alvaro@uneatlantico.es';
	//$enviar['copias'] 			= 'david.lolin@funiber.org,henry.salazar@funiber.org';
	
	//$enviar['destinatarios'] 	= 'david.lolin@funiber.org';
	//$enviar['destinatarios'] 	= 'henry.salazar@funiber.org';
	$enviar['asunto'] 			= 'Errores al procesar script CVN';
	$enviar['mensaje'] 			= $mensaje;
	$persona = new Persona();//instancia de modelo persona 
	$persona->enviarMail($enviar);
}

function enviarCorreoPostulante($datosPostulantes){
 	$mensaje = "
 			Estimado/a,<br/><br/>

			Muchas gracias por el inter&eacute;s mostrado en la Universidad Europea del Atl&aacute;ntico.
			<br/><br/> 
			
			Le informamos que existe alg&uacute;n problema en la tramitaci&oacute;n de su CV presentado, 
			le agradecer&iacute;amos que volviera a descargarse el CV de la p&aacute;gina del ministerio 
			(NO utilizar el mismo presentado) y lo vuelva a tramitar de la misma forma.
			<br/><br/>
			
			Esperamos que con esto sea suficiente, no obstante si se volviera a detectar alg&uacute;n 
			problema, nos pondr&iacute;amos en contacto de nuevo con usted.
			<br/><br/>
			
			Sentimos las molestias ocasionadas y le agradecemos su colaboraci&oacute;n,
			<br/><br/> 
			
			Un cordial saludo.
 	";	
	
	$persona = new Persona();
	//$enviar['remitente'] 		= 'ctfuniber+uneatlantico@funiber.org';
	$enviar['remitente'] 		= 'info@uneatlantico.es';
	//$enviar['copias'] 			= 'david.lolin@funiber.org';
	$enviar['asunto'] 			= 'Tramitacion CVN';
	$enviar['mensaje'] 			= $mensaje;	
	foreach ($datosPostulantes as $i => $fila) {
		$enviar['destinatarios'] 	= $fila['email'];
		$persona->enviarMail($enviar);
	}	
}


function printArray($data){
	echo "<pre>".print_r($data, true)."</pre>";
}

function build($xml_f,$id,$fecha_modificacion,$conexion){
	$file_xml=$xml_f;
	//$xml = simplexml_load_file($file_xml);
	$dom = new DOMDocument('1.0', 'utf-8');
	$dom->load($file_xml);
	$codes = $dom->getElementsByTagName('Code');
	$codigosGuardados = array();
	foreach($codes as $code){			
		$value = $code->nodeValue;
		$parteFinalCode = substr($value,12);
		//se compara si es codigo final y si nbo se ha guardado antes
		if($parteFinalCode!='000' && !in_array($value, $codigosGuardados)){//se pregunta si no es código padre (los códigos padres son los que terminan en 000)
			//$name = $code->nodeName;
	    	//echo $code->nodeValue.' -  '.$parteFinalCode.'<br/>';
			//recursiveXMLoad($_items,'', $id, $fecha_modificacion, $conexion);						
			saveCodes($value, $fecha_modificacion, $id, $conexion);
			$codigosGuardados[]=$value;
		}	    
	}
	
		
	/*
	
	echo "<table border='1'>";
	foreach($xml->children() as $cvnRootResultBean) {
		foreach($cvnRootResultBean->children() as $CvnRootBean) {
			foreach($CvnRootBean->children() as $object) {
				foreach($object->children() as $CvnItemBean) {
					//PROCESAR CADA ITEM
					$codePadre="";
					foreach($CvnItemBean->children() as $elemento){
						if($elemento->getName()=="Code"){
							$codePadre=$elemento;	
						}else{							
							//$DOM = new DOMDocument('1.0', 'utf-8');							
							//$DOM->loadXML($elemento->asXML());
							//$a = simplexml_load_string($elemento);																					
							//$los_items = $DOM->getElementsByTagName('*');
							//$los_items = $elemento->children();
							
							//$doc = new DOMDocument();
							//$doc->loadXML($elemento);
							
							//$x=new SimpleXMLElement($elemento);
							//echo $x->asXML();							
							//recursiveXMLoad($los_items,$codePadre,$id,$fecha_modificacion,$conexion);
							foreach ($elemento as $elem) {
									//echo $elem->getName()." hola - ".$elem->Code."<br/>";
									if($elem->getName()=='Code'){
												
									}
								
							}
						}
					}
				}
			}
		}
	}
	echo "</table>";
	  */
}

function saveCodes($code, $fecha_modificacion, $id, $conexion){				
	$query="INSERT INTO categorias_generales (code,fecha_modificacion,id_persona) VALUES ('$code','$fecha_modificacion','$id');";	
	$status = $conexion->query($query);	
}


function recursiveXMLoad($_items,$codePadre,$id,$fecha_modificacion,$conexion){
	
	foreach($_items as $el_item){
		echo $el_item->nodeValue.'<br/>';
		/*if($el_item->getName()=="Code"){
			echo $el_item->nodeValue;
		}*/						
		//$code=$el_item->getElementsByTagName("Code")->item(0)->nodeValue;  
		//$valor = $el_item->getElementsByTagName("Value")->item(0)->nodeValue;				
		if($code!=""){
			/*$query="INSERT INTO categorias_generales (code,fecha_modificacion,id_persona) VALUES ('$code','$fecha_modificacion','$id');";
			$conexion->query($query);*/		
		}
		
		/*if($code!="" && $valor!=""){
			$query="INSERT INTO categorias_generales (code,fecha_modificacion,id_persona) VALUES ('$code','$fecha_modificacion','$id');";
			$conexion->query($query);
			//echo $code.' - '.$valor."<br/>";
		}*/
	}
}


function parseToXML($htmlStr)
{
	$xmlStr=str_replace('&lt;','<',$htmlStr);
	$xmlStr=str_replace('&gt;','>',$xmlStr);
	$xmlStr=str_replace('&quot;','"',$xmlStr);
	$xmlStr=str_replace('&#39;',"'",$xmlStr);
	$xmlStr=str_replace('&amp;',"&",$xmlStr);
	$xmlStr=str_replace('&#13;', "", $xmlStr);
	return $xmlStr;
}

?>

