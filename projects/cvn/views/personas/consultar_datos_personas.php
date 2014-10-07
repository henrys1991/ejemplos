<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_listas.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_popups.css" rel="stylesheet"/>

<script type="text/javascript" src="/cvn/public/js/si-custom.js"></script>

<style> 
    .titulo_codigos{
        font-size: 13px;
        margin: 15px 0 8px 16px;
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
    .popup_codigos{
        max-height: 497px;
        overflow-y: auto;
    }
</style>

<?php

header('Content-Type: text/html; charset=UTF-8');
//desde el controller se envia el id de persona, el xml y el subcat
startSearch($data['id_detalle'],$data['xml'], $data['subcat']);

/***funcion para inicial todo el proceso de lectura y comparacion de .xml**/
function startSearch($id,$xml,$subcat){
	searchLabelForCode($xml, $subcat);
	//devuelve un array con los codigos hijos y su descripcion dada el xml y el códigoPadre(subcat)
	//$codigoDescripcion = searchLabelForCode($xml, $subcat);	
	//showInformation($id,$codigoDescripcion);	
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
			
			echo "<table class='formulario' style='margin: 5px 0 5px 16px; width:94%'>";
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
				//printArray($labels);
							
				foreach ($labels as $i => $label) {
					$codigo = $label['code'];
					$dataOrder = searchTitles($codigo);
					$labels[$i]['name'] = $dataOrder['name'];
					$labels[$i]['reference'] = $dataOrder['reference'];
					$labels[$i]['order'] = $dataOrder['order'];					
					$desc = $label['descripcion'];
					
					$desc = formatearTipos($desc, $dataOrder['type']);  
					$descripcion = searchCodeAux($dataOrder['reference'], $desc);					 
					$labels[$i]['descripcion'] = $descripcion."";
				}			
			$dataOrdenada = ordenar($labels, 'order');
			showBlockInformation($dataOrdenada);
			unset($labels);	
			echo "<br/> </table>";
		}		
	}	
	showFooter();
}


/****Permite buscar informacion de acuerdo a la tabla de un codigo auxiliar***/
function searchCodeAux($reference,$valor,$lang='spa'){			
	if(!empty($reference)){				
		$fileXMLReference = realpath(dirname(__FILE__) . '/../../tools/ReferenceTables.xml'); 
		$xmlReference = simplexml_load_file($fileXMLReference);		
		$tablaReferencia = $xmlReference->xpath("//Table[@name='$reference']/Item");		
		if($tablaReferencia!=null){
			$codigo = trim($valor);
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
	return $valor;
}


/**function para mostrar un bloque de informacion*/
function showBlockInformation($data){
	$urlDoc = $_SERVER['SERVER_NAME'].'/cvn/public/doc';	
	foreach ($data as $key => $value) {
		$name = $value['name'];
		$descripcion = $value['descripcion'];				
		//array con los q son de tipo base64. por ahora la fotografia
		$base64Codes = array('fotografia_digital'=>'000.010.000.130');		
	 	if(in_array($value['code'], $base64Codes)){	 			 
	 		$file = 'imagen.png';  
	 		$descripcion = base64_decode($value['descripcion']);			
			$temp = realpath(dirname(__FILE__) . "/../../public/doc/$file");
			file_put_contents($temp, $descripcion);			
			$descripcion="<img width=80 height=100 src='../public/doc/$file'>";						 	
	 	}
		echo "<tr><th><b>$name:</b></th><td>$descripcion</td></tr>";	
	}  
}

/**function para formatear tipos de datos*/
function formatearTipos($campo,$tipo){
	switch ($tipo) {
		case 'Date':$campo = substr($campo, 0, 10);  
					$campo = date('d-m-Y',strtotime($campo));					
			break;		
		default:			
			break;
	}
	return $campo;
}

/**function para mostrar la cabecera de un bloque de informacion*/
function showHeader(){
	$header = "<div class='popup_codigos'>"; 
	$header.= "<div >"; 
    $header.= "<h3 class='titulo_codigos'>Informaci&oacute;n de c&oacute;digos</h3>";    
    echo $header;
}

/**function para mostrar el pie de un bloque de informacion*/
function showFooter(){	
	$footer.= "</div>";
    $footer.= "<div ><br/><a class='accion discard' onclick='$(\"info_codigo_persona\").remove();' style='margin: 240px;'>Cerrar</a></div><br/>";
	$footer.= "</div>";
	echo $footer;	
}

/**function ordenar un array multidimensional de acuerdo a un key determinado*/
function ordenar($data, $campo){
    $cmp= array();
    foreach ($data as $key => $row) {
        $cmp[$key]  = $row[$campo];                
    }
    array_multisort($cmp, SORT_ASC, $data);    
    return $data;    
}

/**busca los titulos desde el manual de especificaciones*/
function searchTitles($code, $lang='spa'){			
	$file_xml = realpath(dirname(__FILE__) . '/../../tools/SpecificationManual.xml');	
	$xp = simplexml_load_file($file_xml);
	$item = $xp->xpath("//Manual/Item[@code='$code']");//buscar el item en el codigo
	$itemName = $xp->xpath("//Manual/Item[@code='$code']/Name/NameDetail[@lang='$lang']");//nombre del campo	
	
	$res['name'] = $itemName[0]->Name."";
	$res['reference'] = $item[0]->ReferenceTable."";
	$res['order'] = $item[0]->Order."";	
	$res['type'] = $item[0]->Type."";
	return $res;
}


/***Obtiene las etiquetas que tiene un objeto****/
function getDataFromObject($dataObject){	
	$code = "";
	$descripcion = "";
	$label=array();
	$labelPadre = $dataObject->getName();
			
	foreach ($dataObject as $k => $value) {
		($labelPadre=='CvnBoolean')?$descripcion='Si':'';		
		($labelPadre=='object')? $labelPadre=$value->getName():'';
		
		$labelsToShow = getLabelToShow($labelPadre);		
		if(isset($value->Code)){
			//$elemento = (object_array($value));			
			$elemento = $value;		
								 
			$descripcion="";
			foreach ($elemento as $j=> $val) {
				($labelPadre=='CvnBoolean')?$descripcion='Si':'';				
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

/**se obtiene la etiqueta de lo q se debe presentar al usuario*/
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

/**Transforma un objeto en array (No se utiliza actualmente)**/
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




/**Muestra información**/
/*function showInformation_old($id,$data){
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
}*/

/***Busca los titulos de acuerdo a un codigo en el Manual de especificaciones***/
/*function searchCodeData($code, $lang='spa'){			
	$file_xml = realpath(dirname(__FILE__) . '/../../tools/SpecificationManual.xml');	
	$xp = simplexml_load_file($file_xml);
	$item = $xp->xpath("//Manual/Item[@code='$code']");
	$itemName = $xp->xpath("//Manual/Item[@code='$code']/Name/NameDetail[@lang='$lang']");
	$name = $itemName[0]->Name."";
	$reference = $item[0]->ReferenceTable."";	
	return array($name,$reference);	
}*/

/****Permite buscar información de acuerdo a la tabla de un código auxiliar***/
/*function searchCodeAux($reference,$descripcion,$lang='spa'){			
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
}*/

?>