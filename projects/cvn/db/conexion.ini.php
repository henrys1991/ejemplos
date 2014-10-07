<?
require_once(realpath(dirname(__FILE__).'/../tools/claseMySQLi.php'));
function __setConexion(){
	$file = realpath(dirname(__FILE__) . '/../config/config.ini');	
	if(file_exists($file)){
		$datosIniciales = parse_ini_file($file);		
		if (!isset($GLOBALS['conexion']) || !is_object($GLOBALS['conexion']) ){
			$servidor = $datosIniciales['SERVIDOR'];
			$userName = $datosIniciales['USERNAME'];
			$password = $datosIniciales['PASSWORD'];
			$db = $datosIniciales['DB'];
			$GLOBALS['conexion'] = new db($servidor,$userName,$password,$db);
			$GLOBALS['conexion']->debug = false;			
		}	
	}else{
		echo "Error al leer el archivo de configuracion"; die;
	}
}
__setConexion();
