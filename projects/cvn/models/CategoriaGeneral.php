<?php
require_once(realpath(dirname(__FILE__) . '/../db/conexion.ini.php'));

class CategoriaGeneral{
	
	public function eliminar($idPersona){
		try{
			$conexion = $GLOBALS['conexion'];	
			$sql = "DELETE FROM categorias_generales WHERE id_persona=$id";
			$conexion->query($sql);
			$data['status']=true;
			$data['mensaje']='Registros eliminados con exito...';	
		}catch(Exception $ex){
			$data['status']=false;
			$data['mensaje']='Error al eliminar. Error: '.$ex->getMessage();
		}
		return $data;
		
	}	
}

?>