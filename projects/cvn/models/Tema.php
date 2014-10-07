<?php
require_once(realpath(dirname(__FILE__) . '/../db/conexion.ini.php'));

class Tema{
	/**funcion que permite extraer los temas de acuerdo al id de una area*/
	public function getTemasPorArea($idArea){
		$conexion = $GLOBALS['conexion'];
		$query = "SELECT * FROM tema WHERE area_id=$idArea";
		$enlace = $conexion->query($query);
		$data = array();		
		while($fila=$conexion->fetch_array($enlace)){
			$data[] = array('id'	=>$fila['id'],
			                'nombre'=>utf8_encode($fila['nombre']));					
		}
		return $data;
	}
	
	/***funcion para obtener todas los temas de la base de datos**/
	public function getAllTemas(){
		$conexion = $GLOBALS['conexion'];
		$query = "SELECT * FROM tema";
		$enlace = $conexion->query($query);
		$data = array();		
		while($fila=$conexion->fetch_array($enlace)){
			$data[] = array('id'	=>$fila['id'],
			                'nombre'=>utf8_encode($fila['nombre']));					
		}
		return $data;
	}
	
	/**consultar los temas de acuerdo al id de persona. Esta funcion permite consultar las areas
	 * y temas dado el id de una persona.  **
	 * @idPersona : El indentificador de la tabla persona
	 * return array formateado por areas y temas
	 * */	
	public function getTemasForPersona($idPersona){
		$conexion = $GLOBALS['conexion'];	
		$sql = "SELECT DISTINCT ai.*,tema.*     
		        FROM r_personas_temas rpt
		        INNER JOIN tema ON tema.id=rpt.id_tema
				INNER JOIN areas_interes ai ON ai.cod_descripcion=tema.area_id 
				WHERE rpt.id_persona=$idPersona   
				ORDER BY tema.area_id";
		$enlace = $conexion->query($sql);
		$data = array();		
		$areas = array();
		while($fila=$conexion->fetch_array($enlace)){
			$area_id = $fila['cod_descripcion'];
			if(!in_array($area_id, $areas)){				
				$areas[] = $area_id;	
				$data[$area_id] = array('area_id'		=>$area_id,
										'area_nombre'	=>$fila['descripcion']
									); 	
			}																			
			$data[$area_id]['temas'][] = array('tema_id'		=>$fila['id'], 
											   'tema_nombre'	=>$fila['nombre']); 
		}
		return $data;				
	}
	
	
	
	/**guardar tema
	 * @idPersona: el identificador de la tabla persona
	 * @idTema : identificador de la tabla tema
	 * 
	 * return data: array con status y mensaje de la transaccion***/
	public function guardarTemas($idPersona,$idTema){
		$conexion = $GLOBALS['conexion'];		
		try{
			$query = "INSERT INTO r_personas_temas (id_persona,id_tema) VALUES ('$idPersona','$idTema');";			
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
			$sql = "DELETE FROM r_personas_temas WHERE id_persona=$idPersona";
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