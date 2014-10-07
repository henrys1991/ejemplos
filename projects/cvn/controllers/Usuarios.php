<?php
//require_once (realpath(dirname(__FILE__) . '/../models/Persona.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Usuario.php'));
require_once (realpath(dirname(__FILE__) . '/Restriccion.php'));
require_once (realpath(dirname(__FILE__) . '/ControllerCVN.php'));

class Usuarios extends ControllerCVN {	
	
	/***funcion para redireccionar de acuerdo al action***/
	public function redireccionarAction(){
		switch ($_GET['action']) {
			case 'login'			: $this->login();break;
			case 'logout'			: $this->logout();break;				
			default: echo "No se encuentra la pagina especificada";break;
		}
	}
	
	/**funcion para realizar el login dado los campos que vienen desde el form**/
	public function login(){		
		$request = !empty($_POST) ? $_POST : die;			
		if($_POST['user'] && $_POST['password']){				
			$usuario 	= addslashes(trim($request['user']));//con addslashes para escapar caracteres como las comillas
			$password 	= addslashes(md5(trim($request['password'])));	
			$usu = new Usuario();
			$resLogin = $usu->validarLogin($usuario,$password);
			
			if ($resLogin['status']){
				session_destroy();
				session_start();				
				$_SESSION['usuario'] = $usuario;				
				$_SESSION['username'] = $resLogin['username'];//nombre real del usuario
				$_SESSION['usertype'] = $resLogin['usertype'];//tipo de usuario
							
				$data['status'] = true;
				$data['mensaje'] = "Login correcto";
				echo json_encode($data);
				//header('Location: /cvn/personas/consultar');die;				
			}else{
				$data['status'] = false;
				$data['mensaje'] = "Usuario y contrase&ntilde;a incorrectos";
				echo json_encode($data);				
			}
		}else{						
			$data['status'] = false;
			$data['mensaje'] = "Campos inv&aacute;lidos";
			echo json_encode($data);
			//header('Location: /cvn/login');die;
		}
		exit();		
	}		
	
	/***funcion para realizar el logout**/
	public function logout(){			
		if (isset($_SESSION['usuario'])){			
            unset($_SESSION['usuario']);
			unset($_SESSION['username']);
			unset($_SESSION['usertype']);
			
			session_destroy();
			$data['status'] = true;
			$data['mensaje'] = "Cierre de sesion satisfactorio";				
			echo json_encode($data);			
        }else{
        	$data['status'] = false;
			$data['mensaje'] = "Error al cerrar sesi&oacute;n";			
			echo json_encode($data);			
        }	
		exit();
	}
	
	public function showView($page,$data=''){
		include_once (realpath(dirname(__FILE__) . '/../views/usuarios/'.$page));
	}	
}

$usu=new Usuarios();
$usu->redireccionarAction();
/*
$res = new Restriccion();
if($res->bloquearIp()){
	$usu=new Usuarios();
	$usu->redireccionarAction();
}*/

?>