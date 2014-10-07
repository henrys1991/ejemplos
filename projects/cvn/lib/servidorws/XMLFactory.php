<?php

class XMLFactory{
var $family;

	function XMLFactory(){
		$version = substr(PHP_VERSION, 0, 3);
		if($version>4.2 && $version<5){
			$this->family = 4;
		}
		if($version>5){
			$this->family = 5;
		}
		if($this->family == NULL){
			trigger_error("Librerias XML no soportadas para version ".PHP_VERSION." de PHP (testeado en mayor que 4.2 y menor a 6), pruebe comunicarse en otros formatos: application/json o application/php", E_USER_ERROR);
		}
	}

	function getArrayToXMLInstance(){
		if($this->family==4){
			include_once(dirname(__FILE__) ."/ArrayToXML_PHP4.php");
			return new ArrayToXML();
		}
		if($this->family==5){
			//trigger_error("Pendiente implementar ArrayToXML con SimpleXML para version ".PHP_VERSION." de PHP", E_USER_ERROR);
			include_once(dirname(__FILE__) ."/ArrayToXML_PHP5.php");
			return new ArrayToXML();
		}
	}

	function getXMLToArrayInstance(){
		if($this->family==4){
			include_once(dirname(__FILE__) ."/XMLToArray_PHP4.php");
			return new XMLToArray();
		}
		if($this->family==5){
			//trigger_error("Pendiente implementar XMLToArray con SimpleXML para version ".PHP_VERSION." de PHP", E_USER_ERROR);
			include_once(dirname(__FILE__) ."/XMLToArray_PHP5.php");
			return new XMLToArray();
		}
	}
}
?>