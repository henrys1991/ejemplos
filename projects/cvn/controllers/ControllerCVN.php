<?php

abstract class ControllerCVN
{
	public function __construct(){
		ini_set('display_errors', '0');
		session_start();
		$this->__autorize();		
	}
	
	protected function __autorize(){
		//if (!isset($_SESSION['usuario']) || !is_object($_SESSION['usuario']))
		if (!isset($_SESSION['usuario'])&& $_REQUEST['action']!='login')
		{
			//header('HTTP/1.1 403 Forbidden');die;			
		 	//include_once (realpath(dirname(__FILE__) . '/../views/templates/error/403.php'));		 	
			//header('login');
			//echo "no tengo sesion activa";die;			
			header('Location: /cvn/login');
			//die($this->Loader->view('templates/error/403'));
		}		
		return true;
	}
}
?>