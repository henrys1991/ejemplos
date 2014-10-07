<?php
require_once(realpath(dirname(__FILE__) . '/../db/conexion.ini.php'));
require_once (dirname(__FILE__) . '/Tema.php');

class Area{
	public function getAllAreas(){
		$conexion = $GLOBALS['conexion'];
		$query = "SELECT * FROM areas_interes;";
		$enlace = $conexion->query($query);
		$data = array();
		$objTema = new Tema();
		while($fila=$conexion->fetch_array($enlace)){
			$idArea = $fila['cod_descripcion'];
			$temas = $objTema->getTemasPorArea($idArea);
			$data[] = array('id'	=>$idArea,
			                'desc'	=>utf8_encode($fila['descripcion']),
							'temas' => $temas,
							);					
		}
		return $data;
	}
	
	/**consultar las areas de acuerdo al id de persona***/
	public function getAreasForIdPersona($idPersona){
		$conexion = $GLOBALS['conexion'];
		/*$sql = "SELECT DISTINCT ai.*  
		        FROM r_personas_interes rpi
				INNER JOIN areas_interes ai ON ai.cod_descripcion=rpi.cod_descripcion 
				WHERE rpi.id_persona=$idPersona 
				ORDER BY ai.descripcion";*/
		$sql = "SELECT DISTINCT ai.*   
		        FROM r_personas_temas rpt
		        INNER JOIN tema ON tema.id=rpt.id_tema
				INNER JOIN areas_interes ai ON ai.cod_descripcion=tema.area_id 
				WHERE rpt.id_persona=$idPersona 
				ORDER BY ai.descripcion";
		$enlace = $conexion->query($sql);
		$data = array();
		while($fila=$conexion->fetch_array($enlace)){																			
			$data[] = array('id'	=>$fila['cod_descripcion'],
							'desc'	=>$fila['descripcion']); 
		}
		return $data;				
	}

	/**guardar areas de interes***/
	public function guardarAreasInteres($idPersona,$idArea){
		$conexion = $GLOBALS['conexion'];		
		try{
			$query = "INSERT INTO r_personas_interes (id_persona,cod_descripcion) VALUES ('$idPersona','$idArea');";			
			$status = $conexion->query($query);
			$mensaje = "Los datos fueron registrados satisfactoriamente";			
		}catch(Exception $ex){			
			$status = false;
			$mensaje = "[Error] No se pudo guardar correctamente.";
		}	
		$data = array('status'=>$status,'mensaje'=>$mensaje);
		return $data;
	}
	
	public function eliminar($idPersona){
		$conexion = $GLOBALS['conexion'];	
		try{
			$sql = "DELETE FROM r_personas_interes WHERE id_persona=$idPersona";
			$conexion->query($sql);	
			$data['status']=true;
			$data['mensaje']='Registros eliminados con exito...';
		}catch(Exception $ex){
			$data['status']=false;
			$data['mensaje']='Error al eliminar. Error: '.$ex->getMessage();
		}
		return $data;		
	}
	
/*public function eliminar($idPersona){
		$conexion = $GLOBALS['conexion'];	
		$sql = "DELETE FROM r_personas_interes WHERE id_persona=$id";
		return $conexion->query($sql);
	}*/
	
}

?>