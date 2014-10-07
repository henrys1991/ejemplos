<?php
require_once (realpath(dirname(__FILE__) . '/../../models/Area.php'));
require_once (realpath(dirname(__FILE__) . '/../../lib/PeticionServidorWrapper.php'));
   
$peticion = new PeticionServidorWrapper;
//$peticion->enviar_error(500, "El codigo del grupo no existe");
if($peticion->verbo=='GET'){
	$area = new Area();
	$listaAreas = $area->getAllAreas();				
	$peticion->enviar_respuesta($listaAreas);
}

function printArray($data){
	echo "<pre>".print_r($data,true)."</pre>";
}

/*
class Areas {	
	var $formato_entrada="application/php"; 
	var $formato_salida="application/php"; 
	var $charset="iso-8859-1"; 
	var $charset_cliente="iso-8859-1"; 
	
	public function listar(){
		$area = new Area();
		$listaAreas = $area->getAllAreas();				
		$this->enviar_respuesta_raw($listaAreas);
	}
	
	private function enviar_respuesta_raw($respuesta){
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
}*/
