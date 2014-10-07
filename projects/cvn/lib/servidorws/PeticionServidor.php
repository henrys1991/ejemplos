<?php
/**Servidores distribuidos: Panal Seguimiento, si/rest*/
/**Version: 5.3.2 Fecha 28 Oct 2010*/

if(!class_exists("FormatoPeticion")){ include_once(dirname(__FILE__) ."/FormatoPeticion.php"); }
include_once(dirname(__FILE__) ."/HTTPDigest.php");

class PeticionServidor{

var $region;
var $idioma="es"; //idioma del cliente que se asumira en caso el cliente no especifique uno
var $formato_entrada="application/php"; //formato en que se esperan los datos que envie el cliente en caso el cliente no especifique uno
var $formato_salida="application/php"; //formato en que se enviaran los datos al cliente en caso el cliente no especifique uno
var $verbo;
var $uri;
var $idcliente;

var $idrecurso;
var $entidad;
var $identidad;
var $parametros;
var $charset="iso-8859-1"; //charset que utiliza este servidor de servicios por defecto - utilizar la misma que la BD
var $charset_cliente="iso-8859-1"; //charset del cliente que se asumira en caso el cliente no especifique uno
var $datos;

var $filtro_ips = array(
			"ctb" => "62.57.153.71",
			"ctb2" => "79.147.73.45",
			"ctb3" => "88.19.149.34",
			"ctb4" => "88.19.147.193",
			"ctb5" => "88.2.17.118",
			"ctb6" => "81.184.73.42",
			"ctb7" => "62.57.152.230",
			//"copia_server" => "217.116.15.67",
			"cte" => "200.63.195.90",
			"cte2" => "192.168.0.63",
			"cte3" => "192.168.0.60",
			"cte4" => "192.168.0.1",
			"casa_lucho"=>"77.224.80.94",
			"casa_manolo"=>"62.57.143.179",
			"casa_daniela"=>"186.66.43.17",
			"casa_david"=>"190.214.69.9",
			"temporal"=>"200.48.13.129"
			);
var $idiomas_permitidos = array("es","en","pt","it");
var $charset_permitidas = array("utf-8","iso-8859-1");

var $access_log = "/logs/access.log";
var $error_log = "/logs/error.log";
var $debug = false;

function PeticionServidor(){

    $this->access_log = dirname(__FILE__)."".$this->access_log;
    $this->error_log = dirname(__FILE__)."".$this->error_log;

    /*si ya existen valores por defecto se actualizan los datos de este objeto con dichos valores*/
    $headers = apache_request_headers();
    
    $locale = $headers['Accept-Language'];
    if(strlen($locale)>0){
         $locale_array = split(";",$locale);
	 $locale_array = split(",",$locale_array[0]);
	 $locale_array = split("-",$locale_array[0]);
	 if(sizeof($locale_array)>1){
	     $this->region = $locale_array[1];
	 }
         if(in_array($locale_array[0], $this->idiomas_permitidos)){
              $this->idioma = $locale_array[0];
    	 }else{
	      $this->enviar_error(406,"Idioma ".$locale_array[0]." no permitido");
	 }
    }

    $formato_entrada = $headers['Content-Type'];	
    if(strlen($formato_entrada)>0){
          $formato_entrada_array = split(";",$formato_entrada);
	  $formato_entrada_array = split(",",$formato_entrada_array[0]);
          $this->formato_entrada = $formato_entrada_array[0];
    }

    $formato_salida = $headers['Accept'];
    if(strlen($formato_salida)>0){
          $formato_salida_array = split(";",$formato_salida);
          $formato_salida_array = split(",",$formato_salida_array[0]);
          $this->formato_salida = $formato_salida_array[0];
    }

    $charset_cliente = $headers['Accept-Charset'];

    if(strlen($charset_cliente)>0){
	  $charset_cliente_array = split(";",$charset_cliente);
	  $charset_cliente_array = split(",",$charset_cliente_array[0]);
	  if(in_array(strtolower($charset_cliente_array[0]), $this->charset_permitidas)){
	       $this->charset_cliente = strtolower($charset_cliente_array[0]);
	  }else{
	       $this->enviar_error(406,"Charset ".$charset_cliente_array[0]." no permitido");
	  }
    }

    $this->verbo = $_SERVER['REQUEST_METHOD'];
    $verbos_permitidos = array("GET","POST","PUT","DELETE","HEADER");
    if(!in_array($this->verbo,$verbos_permitidos)){
    	  $this->enviar_error(405, "Verbo ".$_SERVER['REQUEST_METHOD']." invalido");
	  DIE;
    }else{
	  if($this->verbo == "POST" || $this->verbo == "PUT"){
		$datos = file_get_contents("php://input");
		$this->datos = $datos;
	  }
    }
    $this->uri = $_SERVER['REQUEST_URI'];
    $this->idrecurso = $_GET['id'];
    $this->entidad = $_GET['entidad'];
    $this->identidad = $_GET['id_entidad'];
    foreach($_GET as $key => $value){
	if($key != "id" && $key != "entidad"){
             $this->parametros[$key] = $value;
	}
    }

    $this->idcliente = $this->validarAutenticacion();
    //error_log("[".date("r")."]".print_r(array("peticion"=>$this, "datos"=>$this->get_datos()), TRUE)."\n",3,"log.log");
}

/**
  * Envia la respuesta al cliente en el formato que el cliente haya solicitado.
  * Si el formato no esta soportado o hubo algun error convirtiendo la respuesta
  * se envia un error 406 al cliente.
  *
  * @param $respuesta la respuesta que se envia al cliente, los textos
  *		      son codificados como lo haya solicitado el cliente
  *
  */

function enviar_respuesta($respuesta){
    //$this->formato_salida = "text/xml";
    //$this->enviar_error(406,"intentado formatear a ".$this->formato_salida);
    $formateador = new FormatoPeticion;
    $respuesta = $formateador->formatearSalida($respuesta,$this->formato_salida, $this->charset_cliente, $this->charset);

    if($respuesta === FALSE){
		$this->enviar_error(406,"No se pudo formatear la respuesta en formato solicitado : ".$this->formato_salida);
    }else{
    	$this->enviar_respuesta_raw($respuesta);
    }
}

/**
  * TODO ob_end_clean destruye el buffer, no se si esto es lo deseado, por otro lado
  *      ob_clean tiene un comportamiento raro en cuanto a la compresion de la
  *	 respuesta enviada. Firefox no lo entiende.
  *
  * Envia la respuesta al cliente ignorando el formato que el cliente haya solicitado.
  * La respuesta se envia sin formato tal como sea pasada.
  *
  * @param string $respuesta la respuesta que se envia al cliente
  *
  */

function enviar_respuesta_raw($respuesta){

    if(is_string($respuesta)){
		ob_end_clean();
		header("HTTP/1.1 ",true,200);
		header("Content-type: ".$this->formato_salida.";charset=".$this->charset_cliente);
		header("Content-Length: ".strlen($respuesta));
		print($respuesta);
	}else{
		echo "<pre>";
		print_r($respuesta);
		echo "</pre>";
    }
}

/**
  * Intenta formatear a estructura PHP los datos enviados por el cliente y
  * los textos intenta codificarlos a $this->charset. El cliente debe haber
  * especificado en la cabecera Accept-Charset la codificacion de los datos
  * que esta enviando.
  *
  * Si falla la conversion automaticamente devuelve un error 400 al cliente 
  * diciendole que el formato en que envia los datos no es correcto.
  *
  * Si se espera que los datos enviados por el cliente no sean convertibles 
  * a php se deberia utilizar get_datos_raw en lugar de esta funcion.
  *
  * @return los datos enviados por el cliente en formato PHP
  */

function get_datos(){
	$formateador = new FormatoPeticion;
	$datos = $this->get_datos_raw();
	$datos_formateados = $formateador->formatearEntrada($datos, $this->formato_entrada, $this->charset, $this->charset_cliente);
	if($datos_formateados === FALSE){
		$this->enviar_error(400, "Datos enviados en formato errado ".$this->formato_entrada."<br>Datos:".$datos);
	}else{
		return $datos_formateados;
	}
}

/**
  * Retorna los datos enviados por el cliente sin formato.
  * A diferencia de get_datos, esta funcion no intenta realizar ninguna
  * conversion
  *
  * @return los datos enviados por el cliente sin formato
  */
function get_datos_raw(){
     return $this->datos;
}

/**
  * Envia el codigo de error $codigo al cliente con un mensaje opcional
  * adicional. Mata la ejecucion del script despues de enviar el error.
  *
  * @param $codigo el codigo de error HTTP que se envia al cliente
  * @param $mensaje_personalizado un mensaje adicional que se envia al cliente
				  para depuracion.
  * @param $cabecera_personalizado una cabecera personalizada que se envia al ciente
  */
function enviar_error($codigo, $mensaje_personalizado=NULL, $cabecera_personalizada=NULL){
    $estados = parse_ini_file(dirname(__FILE__)."/codigos_estado.ini");
    $mensaje = $estados[$codigo];
    if(!isset($mensaje)){
       $mensaje = $estados["DEFAULT"];
    }

    if(isset($mensaje_personalizado) && strlen(trim($mensaje_personalizado))>0){
       $mensaje = $mensaje.": ".$mensaje_personalizado;
    }
    if(isset($cabecera_personalizada) && strlen(trim($cabecera_personalizada))>0){
       header($cabecera_personalizada);
    }
    header("HTTP/1.1 ".$codigo);
    echo $mensaje;
    
    if($this->debug){
       error_log($_SERVER['REMOTE_ADDR']." - ".$this->idcliente." - [".date("r")."] ".$this->verbo." ".$this->uri." ".$codigo." ".print_r(apache_request_headers(), TRUE)."\n",3,$this->error_log);
    }else{
       error_log($_SERVER['REMOTE_ADDR']." - ".$this->idcliente." - [".date("r")."] ".$this->verbo." ".$this->uri." ".$codigo."\n",3,$this->error_log);
    }
    DIE;
}

function valida(){
    if(isset($this->idcliente)){
    	return true;
    }else{
	return false;
    }
}

function get_cabecera_authorization(){
	if (isset($_SERVER['Authorization'])) {
            $authorization = $_SERVER['Authorization'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                $authorization = $headers['Authorization'];
            }//Sino, es que el navegador no soporta Digest
        }
	return $authorization;
}
/**
  * Autentica mediante el sistema Digest contra el archivo usuarios.htdigest
  * que debe estar presente en este mismo directorio
  *
  * @returns
  *     - Llave_publica si se pudo validar la autenticacion. La llave
  *       publica puede ser utilizada como identificador unico del
  *       cliente
  *     - En caso de error se llama al metodo enviar_error, codigo 401.
  *       La ejecucion del codigo se corta
  * @triggers E_USER_ERROR si usuarios.htdigest no existe
  */
function validarAutenticacion()
{

$authorization = $this->get_cabecera_authorization();
//print_r($authorization);
if(!isset($authorization) || stristr($authorization,"digest")){
		
	/**Si no especifica metodo de autenticacion servidor propone Digest, o si el cliente ya envia Digest se procesa Digest***/
	$HTTPDigest =& new HTTPDigest();
	$filename = dirname(__FILE__) . "/usuarios.htdigest";
	$AuthDigestFile = file($filename);
	if(!$AuthDigestFile){ trigger_error("Archivo de autenticacion ".$filename." no existe o no puede ser leido"); DIE;}

	foreach($AuthDigestFile as $user){
		if(preg_match('/^\w.*:[\w-_.]+:\w+/',$user) > 0){
			$values = explode( ":", $user);
			$username = $values[0];
			$ha1 = rtrim($values[2]);
			$users[$username] = $ha1;
		}
	}

	if ($username = $HTTPDigest->authenticate($users)) {
		if($this->debug){
			error_log($_SERVER['REMOTE_ADDR']." - ".$username." - [".date("r")."] ".$this->verbo." ".$this->uri." ".print_r(apache_request_headers(), TRUE)."\n",3,$this->access_log);
		}else{
			error_log($_SERVER['REMOTE_ADDR']." - ".$username." - [".date("r")."] ".$this->verbo." ".$this->uri."\n",3,$this->access_log);
		}
		return $username;
	} else {
		$this->enviar_error(401, NULL, $HTTPDigest->get_header());
	}
}else{
	/**Soporte para autenticacion antigua funiAuthorization***/
	
	$headers = apache_request_headers();	
        $uri   = trim($this->uri);
        $fecha = trim($headers['Date']);
        $verbo = trim(strtoupper($this->verbo));

	if(!isset($fecha) || strlen($fecha)==0){
              $this->enviar_error(400, "Para autenticacion funiAuth la cabecera Date es obligatoria");
        }

        $array_authorization = split(":",$authorization);
        if(sizeof($array_authorization)!=2) $this->enviar_error(400, "Para autenticacion funiAuth cabecera Authorization invalida ".$authorization);

        $llave_publica = trim($array_authorization[0]);
        /*la llave privada se debera obtener a partir de BBDD*/

        switch($llave_publica){
                case "SISTEMA":  $llave_privada="FunAPI2011";break;
                case "FACEBOOK": $llave_privada="alklBASDFsio-biog98786W";break;
				//default: $llave_privada="";//sin autenticacion
        }
        //$llave_privada = "jlK093laswmm-oiwGW26598";       
        
        $firma_esperada = sha1($uri.$verbo.$fecha.$llave_privada);
		
        error_log("llave publica : ".$llave_publica.", privada ".$llave_privada."\n",3,dirname(__FILE__)."autenticaciones.log");
        error_log("uri: ".$uri.", verbo ".$verbo.", fecha ".$fecha.", auth ".$authorization.", firma_esperada ".$firma_esperada."\n",3,dirname(__FILE__)."autenticaciones.log");
				
        if($firma_esperada==trim($array_authorization[1])){
           return $llave_publica;
        }else{
          $this->enviar_error(401, "Autenticacion funiAuth fallo ".$authorization);
        }
}
}

}
?>
