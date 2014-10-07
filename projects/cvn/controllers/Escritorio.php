<?php
require_once (realpath(dirname(__FILE__) . '/Restriccion.php'));

class Escritorio {
	public function redireccionarAction(){
		switch ($_GET['action']) {			
			case 'autenticar'		: $this->autenticar();break;						
			default: echo "No se encuentra la pagina especificada";break;
		}
	}
	/***action para mostrar pagina de login**/
	public function autenticar(){
		session_start();
		if (!isset($_SESSION['usuario'])){
			$this->showView('escritorio','login.php');die;
		}else{
			header('Location: /cvn/personas/consultar');				
		}
	}
	
	public function showView($controller,$page,$data=''){
		include_once (realpath(dirname(__FILE__) . "/../views/$controller/$page"));
	}
}

$esc=new Escritorio();	
$esc->redireccionarAction();
/*
$res = new Restriccion();
if($res->bloquearIp()){
	$esc=new Escritorio();	
	$esc->redireccionarAction();
}*/
