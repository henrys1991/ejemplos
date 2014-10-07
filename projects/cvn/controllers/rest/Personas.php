<?php
require_once (realpath(dirname(__FILE__) . '/../../models/Area.php'));
require_once (realpath(dirname(__FILE__) . '/../../models/Tema.php'));
require_once (realpath(dirname(__FILE__) . '/../../models/Persona.php'));


$verbo = $_SERVER['REQUEST_METHOD'];
//sendResponse($_REQUEST);die;


switch ($verbo){  
	case 'GET': 	$id = $_GET['id'];
					getPersona($id);						
					break;
					
	case 'POST': 	//if(isset($_REQUEST['form_data']) && !empty($_REQUEST['form_data'])){
					if(isset($_REQUEST['form_data']) && !empty($_REQUEST['form_data']) && !empty($_FILES[0]['tmp_name'])){
						$request = unserialize($_REQUEST['form_data']);	//se obtiene la data serializada.						
						guardar($request);
					}else{
						$res = array('status'=>false, 'code'=>500,'message'	=>'NOT Save', 'url'=>'');
						sendResponse($res);
					}			
					break;				
	default:break;
}

/***funcion para guardar persona y luego las areas de interes**/
function guardar($request){	
	//$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$res = array();
	$dataPersona = $request['Persona'];
	$persona = new Persona();
	
	$email = trim($dataPersona['email']);		
	$resPersona = $persona->buscarPorEmail($email);//devuelve 0 sino encuentra persona
	//$idPersona = $persona->buscarPorEmail($email);//devuelve 0 sino encuentra persona
	$idPersona = $resPersona['id'];//si no hay devolverá null	
	if(empty($idPersona)){
		$data = $persona->guardar($dataPersona);
		//verificar si devolvio status truepara empezar a guardar la relacion entre areas y persona
		if($data['status']){			
			//$idAreas = $dataPersona['Areas'];	
			//$res = guardarAreas($idAreas, $data['id']);//llamar a function guardarAreas
			$idTemas = $dataPersona['Temas'];	
			$res = guardarTemas($idTemas, $data['id']);//llamar a function guardarTemas						
		}
		else{
			$res =array('status'=>false,'code'=>500,'message'=>'NOT Saved','url'=>'');
		}
	}else{//caso contrario actualizar
		$estadoProcesado = $resPersona['estado_procesado'];
		if($estadoProcesado!='_PROCESADO'){//SOLO ACTUALIZAR SI está en estado diferente a procesado
			$data = $persona->actualizar($dataPersona, $idPersona);
			if($data['status']){
				//$area = new Area();
				//$area->eliminar($idPersona);
				//$idAreas = $dataPersona['Areas'];
				//$res = guardarAreas($idAreas, $idPersona, false);//llmar a function guardarAreas
				$tema = new Tema();
				$tema->eliminar($idPersona);				
				$idTemas = $dataPersona['Temas'];
				$res = guardarTemas($idTemas, $idPersona, false);//llmar a function guardarTemas				
							
			}else{
				$res = array('status' =>false,'code' =>500,'message'	=>'NOT Updated','url'	=>'');			
			}
		}	
	}	
	//enviar correo al postulante si todo fue correcto
	if($res['status']){
		enviarEmailRecepcion($email);
	}	
	sendResponse($res);
} 

/***funcion para ir guardando areas de acuerdo a un array de id de areas**/
function guardarAreas($idAreas,$idPersona, $guardar=true){
	$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$flagStatusArea=true;		
	$area = new Area();  
	foreach ($idAreas as $id) {											
		$dataArea = $area->guardarAreasInteres($idPersona,$id);
		if(!$dataArea['status']){
			($guardar)?rollback($idPersona):''; //si no se guarda bien un area hacer rollback(eliminar areas y persona creada) 
			$flagStatusArea=false;
			break;
		}
	}
	if($flagStatusArea){
		$res = array('status'=>true,'code'=>200,'message'=>'Saved','url'=>$url.'/'.$idPersona);	
	}else{
		$res = array('status'=>false,'code'=>500,'message'=>'Areas NOT Save','url'=>'');
	}
	return $res;
}


/***funcion para ir guardando temas de acuerdo a un array de id de temas**/
function guardarTemas($idTemas,$idPersona, $guardar=true){
	$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$flagStatusTema=true;		
	$tema = new Tema();  
	foreach ($idTemas as $id) {											
		$dataTema = $tema->guardarTemas($idPersona,$id);
		if(!$dataTema['status']){		 
			($guardar)?rollbackTemas($idPersona):''; //si no se guarda bien un tema hacer rollback(eliminar temas y persona creada)
			$flagStatusTema=false;
			break;
		}
	}
	if($flagStatusTema){
		$res = array('status'=>true,'code'=>200,'message'=>'Saved','url'=>$url.'/'.$idPersona);	
	}else{
		$res = array('status'=>false,'code'=>500,'message'=>'Temas NOT Save','url'=>'');
	}
	return $res;
}




/**funcion para eliminar areas de inteeres y persona en caso de haber un error*/
function rollback($idPersona){
	$area = new Area();
	$area->eliminar($idPersona);
	$persona = new Persona();
	$persona->eliminar($idPersona);		
}


/**funcion para eliminar temas y persona en caso de haber un error*/
function rollbackTemas($idPersona){
	$tema = new Tema();
	$tema->eliminar($idPersona);
	$persona = new Persona();
	$persona->eliminar($idPersona);		
}


/**Funcion para enviar correo de recepcion de CVN*/
function enviarEmailRecepcion($email){
	$mensaje= "
				Estimado/a, <br/><br/>
				
				Muchas gracias por el inter&eacute;s mostrado en la Universidad Europea del Atl&aacute;ntico. 
				Le confirmamos que hemos recibido su CV y que ser&aacute; tratado de conformidad con lo 
				dispuesto por la LeyOrg&aacute;nica 15/1999, de 13 de diciembre, de Protecci&oacute;n de Datos de Car&aacute;cter Personal.
				
				<br/><br/>

				El proceso de selecci&oacute;n depende exclusivamente de los responsables del  equipo de Recursos Humanos de la Universidad,
				y la respuesta a su candidatura partir&aacute; siempre de ese departamento. Por lo tanto, si su perfil se adecua a alguno 
				de los puestos que se necesiten cubrir en nuestra instituci&oacute;n acad&eacute;mica, ser&aacute;n estos profesionales
				quienes se pongan en contacto con usted para programar una entrevista de trabajo. 
				
				<br/><br/>
				
				Le agradecemos que no contacte directamente con la Universidad para conocer el estado de su candidatura 
				puesto que, como le indicamos, ser&aacute;n las personas responsables del proceso de selecci&oacute;n las 
				encargadas de trasladar esa informaci&oacute;n tan pronto como se tome alguna decisi&oacute;n al respecto.  

				<br/><br/>
				Gracias por su colaboración,
				<br/><br/> 

				Un cordial saludo.";		
				
	//$enviar['remitente'] 		= 'ctfuniber+uneatlantico@funiber.org';	
	$enviar['remitente'] 		= 'info@uneatlantico.es';
	$enviar['destinatarios'] 	= $email;
	//$enviar['copias'] 			= 'david.lolin@funiber.org';	
	$enviar['asunto'] 			= 'Recepci&oacute;n CVN';
	$enviar['mensaje'] 			= $mensaje;			
	$persona = new Persona();
	$persona->enviarMail($enviar);
} 

/***obtener datos de una persona de acuerdo a id de persona**/
function getPersona($id){	
	$persona = new Persona();
	$data = $persona->buscarPersona($id);
	$area = new Area();
	$data['Persona']['Areas'] = $area->getAreasForIdPersona($id);	
	printArray($data);
}

/**enviar respuesta al cliente***/
function sendResponse($data){	
	ob_end_clean();
	header("HTTP/1.1 ".$data['code'],true,$data['code']);
	header('Content-Type: text/html; charset=iso-8859-1');	
	header("Content-Length: ".strlen($data['message']),true);			
	echo (serialize($data));
}
/***imprimir un array formateado**/
function printArray($data){
	echo "<pre>".print_r($data,true)."</pre>";
}





/*
$flagStatusArea=true;
$area = new Area();  
foreach ($idAreas as $id) {											
	$dataArea = $area->guardarAreasInteres($data['id'],$id);
	if(!$dataArea['status']){
		rollback($data['id']); //si no se guarda bien un area hacer rollback(eliminar areas y persona creada) 
		$flagStatusArea=false;
		break;
	}		
}
if($flagStatusArea){
	$res = array('status'=>true,'code'=>200,'message'	=>'Saved','url'=>$url.'/'.$data['id']);	
}else{
	$res = array('status'=>false,'code'=>500,'message'=>'Areas NOT Saved','url'=>'');
}*/		

