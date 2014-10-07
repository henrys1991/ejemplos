<?php
class Restriccion{
		
	public function bloquearIp(){				
		$ipValidas = array(//'IP_HENRY'		=>'192.168.0.91',
						   //'IP_LUISA'		=>'192.168.0.132',						   
						   //'IP_DAVID'		=>'192.168.0.12',
						   //'IP_RONALD'	=>'192.168.0.25',
						   'IP_PUBLICA_1'	=>'200.124.243.186',
						   'IP_DAVID_CASA'  =>'190.130.141.54',
						   'IP_PUBLICA_2'	=>'190.57.137.102',
						);
		//permitir todas las ips locales.		
		for($i=0;$i<255;$i++){
			$ipValidas[] = '192.168.0.'.$i;
		}
		if(!in_array($this->getRealIP(),$ipValidas)){			
			echo "SITIO EN CONSTRUCCION";die;
		}
		else{			
			return true;
		}
	}
	
	private function getRealIP() {
	    if (!empty($_SERVER['REMOTE_ADDR'])){
	    	return $_SERVER['REMOTE_ADDR'];    	
	    }   
	    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	    	return $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }             
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	    	return $_SERVER['HTTP_CLIENT_IP'];
	    }           
	    echo "No se ha podido obtener la direccion IP";die;
	}
}
