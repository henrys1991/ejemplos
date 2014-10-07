<?php
include_once(dirname(__FILE__) ."/XMLFactory.php");
include_once(dirname(__FILE__) ."/Encoding.php");
//echo "incluyendo ".dirname(__FILE__)."/Encoding.php";
include_once(dirname(__FILE__) ."/JSON.php");
Class FormatoPeticion{

/**  TODO $charset_local podria volverse opcional si pudiesemos detectar automaticamente el 
  *      charset de $datos
  *
  * Espera que $datos sea una estructura php e intenta
  * convertirlo a $formato.
  *
  * @param $datos estructura de datos php que sera convertida
  * @param $formato el formato al que se intentara convertir la esctructura
  * @param $charset_remoto el charset en que se convertira los $datos
  * @param $charset_local el charset en que vienen los $datos
  * @return los $datos convertidos al formato solicitado, si la conversion
  *         falla o $formato no esta soportado devuelve FALSE
  */
function formatearSalida($datos, $formato, $charset_remoto, $charset_local){

    switch($formato){
	case "application/php" :
		if($charset_local != $charset_remoto){
			$datos = convertir_charset_mix($datos, $charset_remoto, $charset_local);
    		}            
		return serialize($datos);
	case "text/xml":
		//$datos = utf8_encode_mix($datos);
		$factory = new XMLFactory();
		$xml_parser = $factory->getArrayToXMLInstance();
		/*if($charset_local != "utf-8"){
			$datos = convertir_charset_mix($datos, "utf-8", $charset_local);
		}*/
		if($charset_local != $charset_remoto){
			$datos = convertir_charset_mix($datos, $charset_remoto, $charset_local);
		}
		$xml_parser->setCharsetSalida($charset_remoto);
		$datos_xml = $xml_parser->toXml($datos);
		return $datos_xml;
	case "text/html":
		if($charset_local != $charset_remoto){
			$datos = convertir_charset_mix($datos, $charset_remoto, $charset_local);
    		}
		return "<pre>".print_r($datos, TRUE)."</pre>";
	case "application/json" :
		$charset_remoto = "utf-8";//obligatorio
                if($charset_local != $charset_remoto){
                       $datos = convertir_charset_mix($datos, $charset_remoto, $charset_local);
                }
                return json_encode($datos);
        default:
		return FALSE;
    }
}

/** TODO $charset_remoto podria volverse opcional si pudiesemos detectar automaticamente el 
  *      charset de $datos
  *
  * Espera que $datos este en formato $formato e intenta
  * convertirlo a php.
  *
  * @param $datos los datos que seran convertidos a estructura php
  * @param $formato el formato en que se espera que esten los datos
  * @param $charset_local el charset en que se convertira los $datos
  * @param $charset_remoto el charset en que vienen los $datos
  * @return una estructura php a partir de $datos, si la conversion
  *         falla o $formato no esta soportado devuelve FALSE
  */
function formatearEntrada($datos, $formato, $charset_local, $charset_remoto){

    switch($formato){
	case "application/php" :
		$datos_php = unserialize(trim($datos));
		if($charset_local != $charset_remoto){
			$datos_php = convertir_charset_mix($datos_php, $charset_local, $charset_remoto);
    		}
		return $datos_php;
	case "text/xml":
		/** La documentacion dice que XMLParser detecta automaticamente el encoding*/
		$factory = new XMLFactory();
		$xml_parser = $factory->getXMLToArrayInstance();
		$datos_php = $xml_parser->toArray($datos);
		if($charset_local != $charset_remoto){
			$datos_php = convertir_charset_mix($datos_php, $charset_local, $charset_remoto);
		}
		//echo "Datos traidos en XML <pre>".print_r($datos_php, TRUE)."</pre>";
		return $datos_php;
        case "application/x-www-form-urlencoded":
		parse_str($datos, $datos_php);
		if($charset_local != $charset_remoto){
			$datos_php = convertir_charset_mix($datos_php, $charset_remoto, $charset_local);
		}
		return $datos_php;
	case "application/json" :
		/**codificacion remota obligatoria: utf-8 o ASCII*/
		//error_log("[".date("r")."] get datos".print_r($datos, TRUE)."\n",3,"error.log");
		$datos_php = json_decode($datos, true);
		//error_log("[".date("r")."] get datos".print_r($datos_php, TRUE)."\n",3,"error.log");
		if($charset_local != $charset_remoto){
		        $datos_php = convertir_charset_mix($datos_php, $charset_local, $charset_remoto);
		}
		return $datos_php;
        default:
		return FALSE;
    }
}
}
?>
