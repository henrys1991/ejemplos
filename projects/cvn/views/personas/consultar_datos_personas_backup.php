<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_listas.css" rel="stylesheet"/>
<script type="text/javascript" src="/cvn/public/js/si-custom.js"></script>

<style>
    h3{
        font-size: 13px;
        margin: 15px 0 17px 16px;
    }
    table{
        font-size: 11px;
    }
    table th{
        width:40%;
    }
    table td{
        width:60%;
    }
    .dialogo{
        max-height: 400px;
        overflow-y: auto;
    }
</style>

<?php
/*
 * Se consultan los codigos de la tabla categorias_generales de acuerdo al id de persona
 * y solo se extraen aquellos que coincidan con los 2 primeros bloques del código.
 * Luego se consulta en el SpecificationManual.xml
*/
header('Content-Type: text/html; charset=UTF-8');
//require_once (realpath(dirname(__FILE__) . '/../../db/conexion.ini.php'));	
//ini_set("memory_limit","512M");
//global $conexion;

startSearch($data['id_detalle'],$data['xml'], $data['subcat']);	

/**la primera parte se obtiene los parámetros enviados por GET y se hace la consulta a db*/
/*if(isset($_GET['id'])&&isset($_GET['subcat'])){	
	$id = $_GET['id'];
	$subcat = $_GET['subcat'];
	$query="SELECT * FROM persona WHERE id='$id'";
	$enlace=$conexion->query($query);
	while($fila=$conexion->fetch_array($enlace)){
		$xml = $fila['xml'];
	}	
	//build($xml);		
	startSearch($xml, $subcat);			
}
*/

/***funcion para inicial todo el proceso de lectura y comparacion de .xml**/
function startSearch($id,$xml,$subcat){
	//devuelve un array con los codigos hijos y su descripcion dada el xml y el códigoPadre(subcat)
	
	$codigoDescripcion = searchLabelForCode($xml, $subcat);
	
	//showInformation($id,$codigoDescripcion);	
}

/**Muestra información**/
function showInformation($id,$data){
	$urlDoc = $_SERVER['SERVER_NAME'].'/cvn/public/doc';
	
    echo "<div class='dialogo'>"; 
	echo "<div >"; 
    echo "<h3>Informaci&oacute;n de c&oacute;digos</h3>";
    echo "<table class='formulario' style='margin: 5px 0 5px 16px; width:94%'>";
	foreach ($data as $key => $value) {
		$descripcion = $value['descripcion'];
		
		//array con los q son de tipo base64. por ahora la fotografia
		$base64Codes = array('fotografia_digital'=>'000.010.000.130');
		$flag = false;
	 	in_array($value['code'], $base64Codes)?$flag=true:'';
	 	if($flag){	 			 
	 		$file = 'imagen.png';  
	 		$descripcion = base64_decode($value['descripcion']);			
			$temp = realpath(dirname(__FILE__) . "/../../public/doc/$file");
			file_put_contents($temp, $descripcion);			
			$descripcion="<img width=80 height=100 src='../public/doc/$file'>";						 	
	 	}else{	 		
			$data = searchCodeData($value['code']);	 	
	 		$name = $data[0];
	 		$reference = $data[1];	
	 		$descripcion = searchCodeAux($reference,$descripcion);
	 	}
		echo "<tr><th>$name:</th><td>$descripcion</td></tr>";	 		
	}
    echo "</table>";
	echo "</div>";
    echo "<div ><br/><a class='accion discard' onclick='$(\"info_codigo_persona\").remove();' style='margin: 203px;'>Cerrar</a></div><br/>";
	echo "</div>";
}

//function searchCodeData($code, $xml, $lang='spa'){
/***Busca los titulos de acuerdo a un codigo en el Manual de especificaciones***/
function searchCodeData($code, $lang='spa'){
	//$file_xml= "tools/SpecificationManual.xml";		
	$file_xml = realpath(dirname(__FILE__) . '/../../tools/SpecificationManual.xml');	
	$xp = simplexml_load_file($file_xml);
	$item = $xp->xpath("//Manual/Item[@code='$code']");
	$itemName = $xp->xpath("//Manual/Item[@code='$code']/Name/NameDetail[@lang='$lang']");
	$name = $itemName[0]->Name."";
	$reference = $item[0]->ReferenceTable."";	
	return array($name,$reference);	
}

/****Permite buscar información de acuerdo a la tabla de un código auxiliar***/
function searchCodeAux($reference,$descripcion,$lang='spa'){			
	if(!empty($reference)){		
		//$fileXMLReference= "tools/ReferenceTables.xml";	
		$fileXMLReference = $file_xml = realpath(dirname(__FILE__) . '/../../tools/ReferenceTables.xml');		
		$xmlReference = simplexml_load_file($fileXMLReference);		
		$tablaReferencia = $xmlReference->xpath("//Table[@name='$reference']/Item");		
		if($tablaReferencia!=null){
			$codigo = trim($descripcion);
			foreach ($tablaReferencia as $key => $value) {								
				if($value->Code==$codigo){										
					foreach ($value->Name->NameDetail as $k => $val) {
						$atributos = $val->attributes();
						if($atributos['lang']==$lang){
							return $val->Name;
						}
					}
				}
			}
		}
	}
	return $descripcion;
}


/****Busca las etiquetas de acuerdo a un codigo padre***/
function searchLabelForCode($xml,$codePadre){	
	$lengthCodePadre = strlen(trim($codePadre));	
	$xmlPersona = simplexml_load_string($xml);
	$items = $xmlPersona->xpath("//cvnRootResultBean/CvnRootBean/object");
	$labels = array();		
	
	
	showHeader();
	foreach ($items[0]->CvnItemBean as $itemBean) {		
				
		if(substr($itemBean->Code,0,$lengthCodePadre)==$codePadre){
				
			//echo"<div style='border:1px solid #000000'>hola";			
									
			foreach ($itemBean as $elemento) {					
				if(!empty($elemento) && $elemento->getName()!='Code' && !is_array($elemento)){
					$resLabel = getDataFromObject($elemento);
					if(is_array($resLabel[0])){
						foreach ($resLabel as $value) {
							$labels[] = $value; 		
						}
					}else{
						$labels[] = $resLabel;	
					}				
				}			
			}
			
			//echo"</div>";	
		}		
		
	}
	
	showFooter();		
	//return $labels;	
}

function showHeader(){
	$header = "<div class='dialogo'>"; 
	$header.= "<div >"; 
    $header.= "<h3>Informaci&oacute;n de c&oacute;digos</h3>";
    $header.= "<table class='formulario' style='margin: 5px 0 5px 16px; width:94%'>";
    echo $header;
}

function showFooter(){
	$footer = "</table>";
	$footer.= "</div>";
    $footer.= "<div ><br/><a class='accion discard' onclick='$(\"info_codigo_persona\").remove();' style='margin: 203px;'>Cerrar</a></div><br/>";
	$footer.= "</div>";
	echo $footer;	
}


/*function searchOrder($code, $lang='spa'){			
	$file_xml = realpath(dirname(__FILE__) . '/../../tools/SpecificationManual.xml');	
	$xp = simplexml_load_file($file_xml);
	$item = $xp->xpath("//Manual/Item[@code='$code']");//buscar el item en el codigo
	$itemName = $xp->xpath("//Manual/Item[@code='$code']/Name/NameDetail[@lang='$lang']");//nombre del campo	
	$name = $itemName[0]->Name."";
	$reference = $item[0]->ReferenceTable."";
	$order = $item[0]->Order.""; 	
	return array($name,$reference);	
}
*/


/***Obtiene las etiquetas que tiene un objeto****/
function getDataFromObject($dataObject){	
	$code = "";
	$descripcion = "";
	$label=array();
	$labelPadre = $dataObject->getName();
		
	foreach ($dataObject as $k => $value) {
		($labelPadre=='object')? $labelPadre=$value->getName():'';		 		
		$labelsToShow = getLabelToShow($labelPadre);		
		if(isset($value->Code)){			
			$elemento = (object_array($value));					 
			$descripcion="";
			foreach ($elemento as $j=> $val) {				
				//($j=='Code')? $code = $val."": $descripcion.= $val."  ";
				if($j=='Code'){
					$code = $val."";
				}else{					
					if(in_array($j, $labelsToShow)){
						$descripcion.= $val."  ";	
					}	
				}								
			}
			$label[] = array('code'=>$code,'descripcion'=>$descripcion);					
		}else{
			//($k=='Code')? $code = $value."": $descripcion.= $value."  ";
			if($k=='Code'){
				$code=$value."";
			}else{
				if(in_array($value->getName(), $labelsToShow)){
					$descripcion.= $value."  ";	
				}
			}				
			$label=array('code'=>$code,'descripcion'=>$descripcion);
		}	
	}	
	
	return $label;
}

/**Transforma un objeto en array**/
function object_array($valor){
	if(!@is_array($valor) and !@is_object($valor)){
	  return $valor;
	}
	else{
	  foreach($valor as $key => $cadena){	   
	   	$valores[$key] = "".$cadena;
	  }
 	}       
 	return $valores;
}

function getLabelToShow($etiquetaPadre){
	$etiquetas = array();
	$etiquetas['CvnAuthorBean']			= array('CvnFamilyNameBean','Signature');
	$etiquetas['CvnFamilyNameBean']		= array('FirstFamilyName','SecondFamilyName');
	$etiquetas['CvnBoolean'] 			= array('Value');
	$etiquetas['CvnDateDayMonthYear'] 	= array('Value');
	$etiquetas['CvnDateMonthYear'] 		= array('Value');
	$etiquetas['CvnDateYear'] 			= array('Value');	
	$etiquetas['CvnDouble'] 			= array('Value');
	$etiquetas['CvnDuration']			= array('Value');
	$etiquetas['CvnEntityBean']			= array('Name');
	$etiquetas['CvnExternalPKBean']		= array('Value');
	$etiquetas['CvnPageBean']			= array('InitialPage','FinalPage');
	$etiquetas['CvnPhoneBean']			= array('Number');
	$etiquetas['CvnPhotoBean']			= array('BytesInBase64','MimeType');
	$etiquetas['CvnString']				= array('Value');
	$etiquetas['CvnTitleBean']			= array('Name');
	$etiquetas['CvnVolumeBean']			= array('Number','Volume');
	return $etiquetas[$etiquetaPadre];	
}

/****Muestra un array formateado***/
function printArray($data){
	echo "<pre>".print_r($data,true)."</pre>";
}

?>