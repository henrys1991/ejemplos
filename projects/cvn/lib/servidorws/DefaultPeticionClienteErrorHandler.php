<?php
class DefaultPeticionClienteErrorHandler{


/**
  * Debe retornar lo que ira en datos
  *
  *
  */
function on_error_http($codigo, $mensaje){
	if($codigo!=404){ trigger_error("(".$codigo.")".$mensaje,E_USER_ERROR);}
	return NULL;
}

/**
  * Debe retornar lo que ira en datos
  *
  *
  */
function on_error_curl($codigo, $mensaje){
	switch($codigo){
	    case 1:
	    case 3: trigger_error("Error de desarrollador: URI INVALIDA ".$url, E_USER_ERROR);
	    case 2: trigger_error("Error en libreria CURL", E_USER_ERROR);
	    case 5:
	    case 6:
	    case 7: trigger_error("Error: No se pudo realizar conexion con servidor ".$url, E_USER_ERROR);
	}
	return NULL;
}

}
?>
