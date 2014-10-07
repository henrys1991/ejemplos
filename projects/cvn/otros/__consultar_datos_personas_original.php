<?php
/*
Se recorrerá los nodos del xml guardado en la columna xml de la tabla persona
según el id indicado y buscará las coincidencias con la subcategoría pasada
por parámetro la cual se concatena en un string para usarlo como expresión regular
*/
header('Content-Type: text/html; charset=UTF-8'); 
	//require ('tools/claseMySQLi.php');
	require ('tools/Serializer.php');
	//$conexion = new db("localhost","root","funiber","cvn");
	require_once(dirname(__FILE__) . '/conexion.ini.php');	
	global $conexion;
	
	
	if(isset($_GET['id'])&&isset($_GET['subcat'])){	
		$id = $_GET['id'];
		$subcat = $_GET['subcat'];

		$query="SELECT * FROM persona WHERE id='$id'";
		$enlace=$conexion->query($query);
		while($fila=$conexion->fetch_array($enlace)){
			$xml = $fila['xml'];
		}
		build($xml);
		//echo "<a href='__consultar_personas.php'>volver</a>";
	}



	
function build($xml){
	$xml = simplexml_load_string($xml);
	foreach($xml->children() as $cvnRootResultBean) {
		foreach($cvnRootResultBean->children() as $CvnRootBean) {
			foreach($CvnRootBean->children() as $object) {
				foreach($object->children() as $CvnItemBean) {
					$codePadre="";
					foreach($CvnItemBean->children() as $elemento){
						if($elemento->getName()=="Code"){
							$codePadre=$elemento;
						}else{
							$DOM = new DOMDocument('1.0', 'utf-8');
							$DOM->loadXML($elemento->asXML());
							$los_items = $DOM->getElementsByTagName('*');
							XMLoad($los_items,$codePadre);
						}
					}
				}
			}
		}
	}
	echo "</table>";
}

function XMLoad($_items,$codePadre){
	$patron = "/^".$_GET['subcat']."/";
	foreach($_items as $el_item){
		$code = $el_item->getElementsByTagName("Code")->item(0)->nodeValue;
		$valor = $el_item->getElementsByTagName("Value")->item(0)->nodeValue;
		if($code!="" && $valor!=""){
			if(preg_match($patron,$code)) echo busquedaCadena($code)." = $valor <br/>";
		}
	}
}

function busquedaCadena($code,$idioma="spa"){
	$file_xml="tools/SpecificationManual.xml";
	$xml = simplexml_load_file($file_xml);
	foreach($xml->children() as $nivelManual) {
		foreach($nivelManual->children() as $nivelItem) {
			$role = $nivelItem->attributes();
			if($code==$role["code"]){
				foreach($nivelItem->children() as $nivelElemento){
					if($nivelElemento->getName()=="Name"){
						foreach($nivelElemento->children() as $nivelSubElemento) {
							$codigo = $nivelSubElemento->attributes();
							if($codigo["lang"]==$idioma){
								foreach($nivelSubElemento->children() as $nivelSubElementoInterno) {
									if($nivelSubElementoInterno->getName()=="Name"){										
										return $nivelSubElementoInterno;										
									}
								}
							}
						}
					}
				}
			}
		}
	}
	return "";
}
?>