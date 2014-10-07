<?php
require_once(realpath(dirname(__FILE__) . '/../db/conexion.ini.php'));

class Usuario{
	public function validarLogin($usuario,$password){
		$conexion = $GLOBALS['conexion'];
		$query = "SELECT * FROM usuario WHERE usuario='$usuario' AND password='$password'";
		$resultado = $conexion->query($query);
		$data = array();
		while($filas=$conexion->fetch_array($resultado)){
			$data['username'] = $filas['nombres'];
			$data['usertype'] = $filas['tipo'];
		}
		if($resultado->num_rows>0){
			$data['status'] = true;
		}else{
			$data['status'] = false;			
		}	
		return $data;	
	}
	
	public function verificarTipoUsuario(){			
		if($_SESSION['usertype']=='_ADMIN'){//preguntar sobre el tipo de usuario
			return true;
		}
		return false;		
	}	
}

?>