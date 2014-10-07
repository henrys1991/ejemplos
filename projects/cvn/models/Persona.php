<?php
require_once(realpath(dirname(__FILE__) . '/../db/conexion.ini.php'));
require_once(realpath(dirname(__FILE__) . '/../lib/cteMailer.php'));  
require_once(realpath(dirname(__FILE__) . '/Area.php'));
require_once(realpath(dirname(__FILE__) . '/Tema.php'));

class Persona{
	
	/*public function guardarLocal($req){
		$conexion = $GLOBALS['conexion'];
		if(isset($req['nombre'])){
			$nombre 			= $_POST['nombre'];
			$apellidos 			= $_POST['apellido'];
			$email 				= $_POST['mail'];
			$movil 				= $_POST['movil'];
			$fijo 				= $_POST['fijo'];
			$presentacion 		= $_POST['presentacion'];
			$fecha_alta 		= date("Y-m-d");  
			$fecha_modificacion = date("Y-m-d");
			$CVNpdf 			= file_get_contents($_FILES['pdf']['tmp_name']);
			try{
				//$query = "INSERT INTO persona (nombre, apellidos, email, telefono_movil, telefono_fijo, presentacion, CVNPdf,fecha_alta,fecha_modificacion,procesado) 
				// 	  	  VALUES ('$nombre','$apellidos','$email','$movil','$fijo', '$presentacion', '".addslashes($CVNpdf)."','$fecha_alta','$fecha_modificacion','0');";
				
				$query = "INSERT INTO persona (nombre, apellidos, email, telefono_movil, telefono_fijo, presentacion, CVNPdf,fecha_alta,fecha_modificacion,estado) 
				 	  	  VALUES ('$nombre','$apellidos','$email','$movil','$fijo', '$presentacion', '".addslashes($CVNpdf)."','$fecha_alta','$fecha_modificacion','_PENDIENTE')";	
				$status = $conexion->query($query);//devuelve true or false
				$id = mysqli_insert_id($conexion->link_id); //devuelve el id de la ultima persona insertada.
				$mensaje = "Los datos fueron registrados satisfactoriamente";
			}catch(Exception $ex){
				$status = false;
				$mensaje = "Error al guardar. Mensaje: ".$ex->getMessage();
			}				
		}
		$data = array('id'=>$id,'status'=>$status, 'mensaje'=>$mensaje);
		return $data;
	} */

//htmlentities($string);
	public function guardar($req){
		$conexion = $GLOBALS['conexion'];		
		if(isset($req['nombres']) && !empty($_FILES[0]['tmp_name'])){
			/*$nombre 			= $conexion->real_escape_string(strip_tags($req['nombres']));
			$apellidos 			= $conexion->real_escape_string(strip_tags($req['apellidos']));
			$email 				= $conexion->real_escape_string(strip_tags($req['email']));
			$movil 				= $conexion->real_escape_string(strip_tags($req['telefono_movil']));
			$fijo 				= $conexion->real_escape_string(strip_tags($req['telefono_fijo']));			
			$presentacion 		= $conexion->real_escape_string(strip_tags($req['presentacion']));*/
			
			$nombre 			= addslashes(strip_tags($req['nombres']));
			$apellidos 			= addslashes(strip_tags($req['apellidos']));
			$email 				= addslashes(strip_tags($req['email']));
			$movil 				= addslashes(strip_tags($req['telefono_movil']));
			$fijo 				= addslashes(strip_tags($req['telefono_fijo']));			
			$presentacion 		= addslashes(strip_tags(($req['presentacion'])));			
			$fecha_alta 		= addslashes(date("Y-m-d"));  
			//$fecha_modificacion = addslashes(date("Y-m-d"));
			//$CVNpdf 			= file_get_contents($_FILES['pdf']['tmp_name']);
			$CVNpdf 			= file_get_contents($_FILES[0]['tmp_name']);
			
			try{
				/*$query = "INSERT INTO persona (nombre, apellidos, email, telefono_movil, telefono_fijo, presentacion, CVNPdf,fecha_alta,fecha_modificacion,estado_procesado, estado) 
				 	  	  VALUES ('$nombre','$apellidos','$email','$movil','$fijo', '$presentacion', '".addslashes($CVNpdf)."','$fecha_alta','$fecha_modificacion','_PENDIENTE', 'publico');";	
				*/
				$query = "INSERT INTO persona (nombre, apellidos, email, telefono_movil, telefono_fijo, presentacion, CVNPdf,fecha_alta,estado_procesado, estado) 
				 	  	  VALUES ('$nombre','$apellidos','$email','$movil','$fijo', '$presentacion', '".addslashes($CVNpdf)."','$fecha_alta','_PENDIENTE', 'publico');";				
				
				$status = $conexion->query($query);//devuelve true or false
				$id = mysqli_insert_id($conexion->link_id); //devuelve el id de la ultima persona.
				$mensaje = "Los datos fueron registrados satisfactoriamente";
				
				
				/*
				
				$q= sprintf("INSERT INTO persona (nombre, apellidos, email, telefono_movil, telefono_fijo, presentacion, CVNPdf,fecha_alta,fecha_modificacion,estado) 
										VALUES (?,?,?,?,?,?,?,?,?,?)");
				$query = sprintf("SELECT * FROM users WHERE id=%d", mysqli_real_escape_string($id));
  			    $query = htmlentities($query);
				*/
					 
				/*if($stmt = mysqli_prepare($q)){
				    	$stmt->bind_param("ss", $nombre,$apellidos,$email,$movil, $fijo, $presentacion,$CVNpdf, $fecha_alta, $fecha_modificacion,'_PENDIENTE'); 
				    	$stmt->execute();
				    	$stmt->close();				 
				}
				//$status = $conexion->query($query);//devuelve true or false
				$id = $conexion->insert_id(); //devuelve el id de la ultima persona.
				$mensaje = "Los datos fueron registrados satisfactoriamente";*/
				
			}catch(Exception $ex){
				$status = false;
				$mensaje = "Error al guardar. Mensaje: ".$ex->getMessage();
			}				
		}
		$data = array('id'=>$id,'status'=>$status, 'mensaje'=>$mensaje);
		return $data;		
	} 

	/*public function eliminar($id){
		$conexion = $GLOBALS['conexion'];
		$sql = "DELETE FROM persona WHERE id=$id";
		return $conexion->query($sql);
	}*/
	
	//eliminado fisico
	public function eliminar($id){
		$conexion = $GLOBALS['conexion'];
		try{			
			$sql = "DELETE FROM persona WHERE id=$id";
			$conexion->query($sql);	
			$data['status']=true;
			$data['mensaje']='Registro eliminado con exito...';
		}catch(Exception $ex){
			$data['status']=false;
			$data['mensaje']='Error al eliminar. Error: '.$ex->getMessage();
		}
		return $data;		
	}

	//eliminado logico - actualizar estado
	public function actualizarEstado($idPersona, $nuevoEstado){
		$conexion = $GLOBALS['conexion'];
		try{			
			$sql = "UPDATE persona SET estado='$nuevoEstado' WHERE id=$idPersona";
			$conexion->query($sql);	
			$data['status']=true;
			$data['mensaje']='Registro actualizado con exito...';
		}catch(Exception $ex){
			$data['status']=false;
			$data['mensaje']='Error al actualizar. Error: '.$ex->getMessage();
		}
		return $data;		
	}
	
	
	public function buscarPersona($id){
		$conexion = $GLOBALS['conexion'];
		$sql = "SELECT * FROM persona WHERE id=$id";
		$enlace = $conexion->query($sql);
		$data = array();	
		while($filas=$conexion->fetch_array($enlace)){
			$data['Persona']=array(
				'id'				=>$id,
				'nombres'			=>$filas['nombre'],
				'apellidos'			=>$filas['apellidos'],
				'email'				=>$filas['email'],
				'telefono_movil'	=>$filas['telefono_movil'],
				'telefono_fijo'		=>$filas['telefono_fijo'],
				'presentacion'		=>$filas['presentacion'],
			);
		}
		return $data;
	}

	 
	//*Funcion para buscar una persona dado un conjunto de parametros que vienen por post*/	
	public function buscar($req,$pagina=1,$max=0){		
		$conexion = $GLOBALS['conexion'];
		$c = $this->getCodeKey($req);
		$codigosReq = array();		
		foreach ($c as $value) {
			$codigosReq[]="cg.code LIKE '$value%'";
		}
		$codigosReq = '('.implode(' OR ', $codigosReq).')';
		$areas = $this->getAreaCode($req);
		$codigosAreas = "rpi.cod_descripcion IN (".implode(',', $areas).")";	
		$flagCode = false;$flagArea = false;	
		$codAreas = array();
		$codigos = array();	
		$sql = array();
		$joins = array(); 
		$where = array();
		$having = array();
		$order = array();
		$sql[] = "SELECT SQL_CALC_FOUND_ROWS DISTINCT pers.* ";			
		$from = "FROM persona pers ";		
		/*campo nombres**/
		if(isset($req['nom_ape']) && !empty($req['nom_ape'])){			
			$nom_ape = addslashes(trim($req['nom_ape']));
			$where[] = "(pers.nombre LIKE '%$nom_ape%')";
			//$where[] = "(CONCAT(pers.apellidos,' ',pers.nombre) LIKE '%$nom_ape%' OR CONCAT(pers.nombre,' ',pers.apellidos) LIKE '%$nom_ape%')";			 
			//$where[] = "(pers.apellidos LIKE '%$nom_ape%' OR pers.nombre LIKE '%$nom_ape%' OR CONCAT(pers.apellidos,' ',pers.nombre) LIKE '%$nom_ape%' OR CONCAT(pers.nombre,' ',pers.apellidos) LIKE '%$nom_ape%')";
			//$where[] = "(pers.apellidos LIKE '%$nom_ape%' OR pers.nombre LIKE '%$nom_ape%')";
		}		
		/*campo apellidos**/
		if(isset($req['apellido']) && !empty($req['apellido'])){			
			$apellido = addslashes(trim($req['apellido']));						 
			$where[] = "(pers.apellidos LIKE '%$apellido%')";
		}	
		
		if(isset($req['telefono']) && !empty($req['telefono'])){			
			$telefono = addslashes(trim($req['telefono']));
			$where[] = "(pers.telefono_movil LIKE '%$telefono%' OR pers.telefono_fijo LIKE '%$telefono%')";
		}
		
		/**seccion codigos**/	
		if(isset($req['cod_020_010'])){
			$flagCode = true;
			$codigos[] = "code LIKE '%020.010.%;'";			
		}		
		if(isset($req['cod_020_010_010'])){
			$flagCode = true;
			$codigos[] = "code LIKE '%020.010.010.%;'";			
		}	
		if(isset($req['cod_020_010_020'])){
			$flagCode = true;
			$codigos[] = "code LIKE '%020.010.020.%;'";			
		}
		if(isset($req['cod_030_010'])){
			$flagCode = true;		
			$codigos[] = "code LIKE '%030.010.%;'";			
		}
		if(isset($req['cod_030_040'])){
			$flagCode = true;								
			$codigos[] = "code LIKE '030.040.%;'";			
		}
		if(isset($req['cod_030_050'])){
			$flagCode = true;								
			$codigos[] = "code LIKE '030.050.%;'";			
		}
		if(isset($req['cod_030_060'])){
			$flagCode = true;
			$codigos[] = "code LIKE '030.060.%;'";			
		}
		if(isset($req['cod_030_070'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '030.070.%;'";			
		}
		if(isset($req['cod_050_010'])){
			$flagCode = true;
			$codigos[] = "code LIKE '050.010.%;'";			
		}
		if(isset($req['cod_050_020'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '050.020.%;'";
		}
		if(isset($req['cod_060_010_010'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.010.010.%;'";			
		}
		if(isset($req['cod_060_010_020'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.010.020.%;'";			
		}
		if(isset($req['cod_060_010_030'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.010.030.%;'";
		}
		if(isset($req['cod_060_010_040'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.010.040.%;'";		
		}
		if(isset($req['cod_060_010_050'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.010.050.%;'";
		}
		if(isset($req['cod_060_030_010'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.030.010.%;'";			
		}
		if(isset($req['cod_060_030_020'])){
			$flagCode = true;			
			$codigos[] = "code LIKE '060.030.020.%;'";			
		}
		if($flagCode){
			$sql[] = "GROUP_CONCAT(DISTINCT SUBSTR(cg.code,1,11) ORDER BY cg.code ASC SEPARATOR ';') as code";
			$joins [] = "INNER JOIN categorias_generales cg ON cg.id_persona=pers.id ";
			$where[] = "cg.code=ANY(SELECT code FROM categorias_generales WHERE categorias_generales.id_persona=pers.id AND $codigosReq)";
			$having[] = "code  LIKE '".implode('%;', $c)."%'";
		}
		/****fin seccion codigos***/		
		
		
		
		/**al ser las areas dinamicas se debe consultar en base**/
		/*$objArea = new Area();
		$dataAreas = $objArea->getAllAreas();
		foreach ($dataAreas as $area) {
			$cod = $area['id'];							
			if(isset($req['area_'.$cod])){					
				$flagArea = true;
				$codAreas[] = $cod;
			}
		}
		if($flagArea){
			$sql[] = "GROUP_CONCAT(DISTINCT cod_descripcion ORDER BY cod_descripcion ASC SEPARATOR ';') as area";	
			$joins [] = "INNER JOIN r_personas_interes rpi ON rpi.id_persona=pers.id ";
			$where[] = "rpi.cod_descripcion=ANY(SELECT cod_descripcion FROM r_personas_interes WHERE r_personas_interes.id_persona=pers.id AND $codigosAreas)";
			$having[] = "area='".implode(';', $areas)."'";
		}*/
		
		
		/**al ser los temas dinamicos se debe consultar en base**/
		/*$flagTema = false;
		//$codTemas = array();
		
		$objTema = new Tema();
		$dataTemas = $objTema->getAllTemas();//todos los temas
		foreach ($dataTemas as $tema) {
			$idTema = $tema['id'];							
			if(isset($req['tema_'.$idTema])){					
				$flagTema = true;
				//$codTemas[] = $idTema;//aqui estarian los codigos de los temas que se enviaron
			}
		}*/
		
		
		$temas = $this->getTemaCode($req);//obtener codigos de temas		
		//if($flagTema){
		if(count($temas)>0){
			$temas = $this->getTemaCode($req);//obtener codigos de temas
			$codigosTemas = "pt.id_tema IN (".implode(',', $temas).")";			
			//$sql[] = "GROUP_CONCAT(DISTINCT pt.id_tema ORDER BY pt.id_tema ASC SEPARATOR ';') as tema";	
			$joins [] = "INNER JOIN r_personas_temas pt ON pt.id_persona=pers.id ";
		    $where[] = $codigosTemas;
			//$where[] = "pt.id_tema=ANY(SELECT id FROM r_personas_temas WHERE r_personas_temas.id_persona=pers.id AND $codigosTemas)";
			//$having[] = "tema='".implode(';', $temas)."'";
		}
		
		
		//listar solo personas con estado publico
		$where[] = "pers.estado='publico'";
		
		$order[] = "pers.apellidos";				
		$sql = implode(',',$sql).' ';
		(!empty($where))?$where=' WHERE '.implode(' AND ', $where):$where='';		
		$joins = array_unique($joins);
		$joins = implode(' ', $joins);	
		$group = " GROUP BY id ";
		(!empty($having))?$having= " HAVING ".implode(' AND ', $having):$having='';			
		(!empty($order))?$order =  " ORDER BY ".implode(' , ', $order):$order='';
		
		($max!=0)?$limit = " LIMIT $pagina,$max ": $limit = '';
						
		$sql.= $from.$joins.$where.$group.$having.$order.$limit;
		$data = $conexion->query($sql);//consultar 
		$total = $conexion->fetch_row($conexion->query("SELECT FOUND_ROWS()"));//devuelve un array con el total de registros sin limit de la ultima consulta		
		$res = $this->generarArrayEnvio($data);
		$res['rows_total'] = $total[0];
		return $res;
	}
	
	
	/***obtener solo los valores que sean codigos enviados por post**/
	private function getCodeKey($data){
		$res=array();
		foreach ($data as $i => $value) {
			 if(substr($i,0,4)=='cod_'){			 	
				$res[] = str_replace('_', '.', substr($i,4));	
			 }
		}		
		sort($res);
		return $res;			
	}
	
	/***obtener solo los valores que sean areas enviados por post**/
	public function getAreaCode($data){
		$res=array();
		foreach ($data as $i => $value) {
			 if(substr($i,0,5)=='area_'){				 	
				 $res [] = substr($i,5);	
			 }
		}
		sort($res);		
		return $res;		
	}
	
	/***obtener solo los valores que sean temas enviados por post**/
	public function getTemaCode($data){
		$res=array();
		foreach ($data as $i => $value) {
			 if(substr($i,0,5)=='tema_'){				 	
				 $res [] = substr($i,5);	
			 }
		}
		sort($res);		
		return $res;		
	}
	
	
	/**generar un array para enviar a vista de busqueda**/
	private function generarArrayEnvio($data){
		//echo "<pre>".print_r($data,true)."</pre>";
		$conexion = $GLOBALS['conexion'];
		$resultado = array();
		$cont=0;	
		while($filas=$conexion->fetch_array($data)){
			/*$estado = "";
			switch ($filas['estado']) {
				case '_PENDIENTE': $estado = "aviso";break;
				case '_PROCESADO': $estado = "aceptar";break;
				case '_ERROR': 	   $estado = "cancelar";break;				
				default:break;
			}*/			
			$resultado[$cont]['datos'] = array('id'				=>$filas['id'],
										  	   'nombre'			=>$filas['nombre'],
										  	   'apellidos'		=>$filas['apellidos'],
										       'email'			=>$filas['email'],
										  	   'telefono_fijo'	=>$filas['telefono_fijo'],
										       'telefono_movil'	=>$filas['telefono_movil'],													 
										       'presentacion'	=>$filas['presentacion'],
										       'estado_procesado'=>$filas['estado_procesado'],
											   'estado'			=>$filas['estado']);
			$codigos = $this->getCodes($filas['id']);//se consulta los códigos
			if($codigos->num_rows>0){				
				while($row=$conexion->fetch_array($codigos)){
					$value = $row['code'];
					$resultado[$cont]['codigos'][substr($value,1,1)][] = $this->getValueShowCode($value);
				}				
				//for para verificar que no se envien valores repetidos.			
				for($j=0;$j<=6;$j++){
					if(isset($resultado[$cont]['codigos'][$j])){
						$resultado[$cont]['codigos'][$j] = array_unique($resultado[$cont]['codigos'][$j]);
					}else{
						$resultado[$cont]['codigos'][$j]=array();
					}
				}
				ksort($resultado[$cont]['codigos']);//se ordena el array.		
			}
			else{
				//$resultado[$cont]['codigos'][] = array();
				// se envia un array de arrays vacios
				$resultado[$cont]['codigos']= array(array(),array(),array(),array(), array(),array(), array());
			}					
			//proceso para obtener las areas
			$area = new Area();
			$dataAreas = $area->getAreasForIdPersona($filas['id']);
			if(count($dataAreas)>0){
				foreach ($dataAreas as $fila) {
					$resultado[$cont]['areas'][] = array('codigo_area'=>$fila['id'],
														 'descripcion'=>$fila['desc']);
				}							
			}
			else{
				$resultado[$cont]['areas'][] = array();
			}											
			$cont++;
		}		
		return $resultado;
	}
	
	/**consultar y obtener los codigos de acuerdo al id de persona***/
	private function getCodes($idPersona){
		$conexion = $GLOBALS['conexion'];
		$sql = "SELECT DISTINCT cg.code   
		        FROM categorias_generales cg 
				WHERE cg.id_persona=$idPersona
				ORDER BY cg.code";
		return $conexion->query($sql);
	}

	/***funcion que permite obtener el valor a mostrar de un codigo**/
	private function getValueShowCode($value){
		$res;
		if($this->searchCodeThreeLevels(substr($value,0,11)))
			$res = substr($value,5,1).'.'.substr($value,9,1).';'.substr($value,0,11);
		else{
			//$res = substr($value,5,1).';'.substr($value,0,7); 
			$c = (substr($value,4,2))+0;//se toman los dos primeros numeros del segundo bloque y se suma un cero para tratarlo como entero
			$res = $c.';'.substr($value,0,7);
		}		
		return $res;
	}
	
	/**funcion que permite determinar si un codigo dado es de tres niveles**/
	 private function searchCodeThreeLevels($code){
	 	$codigosTresNiveles = array('020.010.010','020.010.020','020.010.030','050.020.010','050.020.020',
									'050.020.030','050.030.010','050.030.020','060.010.010','060.010.020',
									'060.010.030','060.010.040','060.010.050','060.020.010','060.020.020',
									'060.020.030','060.020.040','060.020.050','060.020.060','060.030.010',
									'060.030.020');
		return in_array($code, $codigosTresNiveles);
	}
	
	/**extraer pdf **/
	public function buscarPdf($id){
		$conexion = $GLOBALS['conexion'];
		$query="SELECT * FROM persona WHERE id='$id'";
		$enlace = $conexion->query($query);		
		while($filas=$conexion->fetch_array($enlace)){
			$pdf=$filas['CVNPdf'];
		}		
		$data['pdf'] = $pdf;
		return $data;
	}
	
	/**buscar informacion de una persona segun un codigo**/
	public function buscarDetalle($id, $subcat){
		$conexion = $GLOBALS['conexion'];		
		$query="SELECT * FROM persona WHERE id='$id'";
		$enlace=$conexion->query($query);
		while($fila=$conexion->fetch_array($enlace)){
			$xml = $fila['xml'];
		}
		$data['xml'] = $xml;
		return $data;
	}

	/***buscar persona por email
	 	@email .- Correo electronico de la persona
	 	return array con id de la persona y los estados
	 ****/
	public function buscarPorEmail($email){
		$conexion = $GLOBALS['conexion'];		
		$query="SELECT * FROM persona WHERE email='$email'";
		$enlace=$conexion->query($query);
		$dataPersona=array();
		//$id = 0;
		while($fila=$conexion->fetch_array($enlace)){
			//$id = $fila['id'];
			$dataPersona = array(
								'id'=>$fila['id'],
								'estado_procesado'=>$fila['estado_procesado'],
								'estado'=>$fila['estado'],
							);
		}
		return $dataPersona;		
		//return $id;
	}
	
	
	/***actualizar datos de persona**/
	public function actualizar($req,$id){
		//$stringCamposActualizar = "";
		$conexion = $GLOBALS['conexion'];		
		//if(isset($req['nombres'])){
		if(isset($req['nombres']) && !empty($_FILES[0]['tmp_name'])){			
			$nombre 			= addslashes(strip_tags($req['nombres']));
			$apellidos 			= addslashes(strip_tags($req['apellidos']));
			$email 				= addslashes(strip_tags($req['email']));
			$movil 				= addslashes(strip_tags($req['telefono_movil']));
			$fijo 				= addslashes(strip_tags($req['telefono_fijo']));			
			$presentacion 		= addslashes(strip_tags($req['presentacion']));
			$fecha_modificacion = addslashes(date("Y-m-d"));			
			$CVNpdf 			= addslashes(file_get_contents($_FILES[0]['tmp_name']));
			
			try{
				//Actualizar de acuerdo al id y solo si es diferente de procesado. Es decir si está en pendiente o error			
				$query = "UPDATE persona SET nombre='$nombre',
											 apellidos='$apellidos', 
											 email='$email', 
											 telefono_movil='$movil', 
											 telefono_fijo='$fijo', 
											 presentacion='$presentacion', 
											 CVNPdf='$CVNpdf',
											 fecha_modificacion='$fecha_modificacion',
											 estado_procesado='_PENDIENTE', 
											 estado='publico'
										WHERE id=$id ";						
				$status = $conexion->query($query);//devuelve true or false				
				$mensaje = "Los datos fueron actualizados satisfactoriamente";
				
			}catch(Exception $ex){
				$status = false;
				$mensaje = "Error al actualizar. Mensaje: ".$ex->getMessage();
			}				
		}
		$data = array('status'=>$status, 'mensaje'=>$mensaje);
		return $data;		
	}
	
	/**imprimir un array formateado*/
	private function printArray($array){
		echo "<pre>".print_r($array,true)."</pre>";
	}
	
	
	/***enviar un mail***/
    public function enviarMail($data){    	
		$mailer = new cteMailer;
		$mailer->IsHTML(true);
		$mailer->ClearAttachments();
		$mailer->ClearAllRecipients();
		$mailer->ClearCustomHeaders();		
		
		//destinatarios
		$destinatarios = explode(',',$data['destinatarios']); 
		$mailValido = false;
		foreach($destinatarios as $destinatario){
			if( !empty($destinatario) && ((preg_match("/[^\@]*@[^\@\.]*.(.*)/", $destinatario))) ){
				$mailer->AddAddress(trim($destinatario));				
				$mailValido = true;
			}
		}
		if($mailValido == false){			
			die('Error: Direccion de correo electronico invalido');
		}
		
		$mensaje = $data['mensaje'];
		$mailer->Body = $mensaje;

		// REMITENTE
		$remitente = $data['remitente'];		
		$remitente_nombre = utf8_decode(html_entity_decode('Universidad Europea del Atl&aacute;ntico'));				
		$mailer->From = $remitente;
		$mailer->FromName = $remitente_nombre;

		// ASUNTO
		$asunto = $data['asunto']; 
		$asunto = ( !empty($asunto) ) ? $asunto : 'Asunto';
		$mailer->Subject = utf8_decode(html_entity_decode($asunto));
		
		// CON COPIA : CC		
		$copias = explode(',', $data['copias']);		
		foreach($copias as $copia){
			if( !empty($copia) )
				$mailer->AddCC(trim($copia));
		}

		$status_send = $mailer->Send();
		$mailer->SmtpClose();		

		if( $status_send ){
			echo 'Email Enviado';
		}else{
			die('Error al enviar email');
		}
	}
		
}
