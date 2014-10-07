<?php
include_once(dirname(__FILE__).'/servidorws/PeticionServidor.php');
class PeticionServidorWrapper extends PeticionServidor{

	function __construct($error_handler){
		parent::PeticionServidor();
	}
}
?>
