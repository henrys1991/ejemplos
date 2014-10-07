<?php
require_once (realpath(dirname(__FILE__) . '/Restriccion.php'));
require_once (realpath(dirname(__FILE__) . '/ControllerCVN.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Persona.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Usuario.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Area.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Tema.php'));
require_once (realpath(dirname(__FILE__) . '/../models/CategoriaGeneral.php'));
require_once (realpath(dirname(__FILE__) . '/../lib/Paginacion.php'));
require_once (realpath(dirname(__FILE__) . '/../lib/Excel.php'));

class Personas extends ControllerCVN{	
	
    
	public function redireccionarAction(){
		switch ($_GET['action']) {			
			case 'registro'			: $this->registro();break;
			case 'guardar'			: $this->guardar();break;
			case 'consultar'		: $this->consultar();break;
			case 'buscar'			: $this->buscar();break;
			case 'eliminar'			: $this->eliminar();break;
			case 'descargar'		: $this->descargar_pdf();break;			
			case 'buscar_detalle'	: $this->buscar_detalle();break;
			case 'descargar_excel'	: $this->descargar_excel();break;
            case 'mostrar_areas'	: $this->mostrar_areas();break;
			default: echo "No se encuentra la pagina especificada";break;
		}
	}
	
	/**Action de registro***/
	public function registro(){
		$area = new Area();
		$listaAreas = $area->getAllAreas();
		$data['areas'] = $listaAreas;
		$this->showView('registro.php',$data);
	}
	
	/**Action consultar.***/
	public function consultar(){
		$area = new Area();
		$listaAreas = $area->getAllAreas();
		$data['areas'] = $listaAreas;
		//echo "<pre>".print_r($data['areas'], true)."</pre>";
		$this->showView('consultar_personas.php',$data);
	}
	/**guardar**/
	public function guardar(){
		/*$flag= true;
		$request = !empty($_POST) ? $_POST : $flag=false;
		if(!flag){
			echo "[Error] No se puede guardar";die;
		}
		$persona = new Persona();
		$email = $request['email'];		
		$idPersona = $persona->buscarPorEmail($email);//devuelve 0 sino encuentra persona		
		if($idPersona==0){//si es cero no encuentra persona entonces hay que guardar
			$data = $persona->guardar($request);
			//verificar si devolvio status truepara empezar a guardar la relacion entre areas y persona
			if($data['status']){
				$idAreas = $persona->getAreaCode($request);
				$area = new Area();
				foreach ($idAreas as $id) {											
					$dataArea = $area->guardarAreasInteres($data['id'],$id);
					if(!$dataArea['status']){
						break;
					}				
				}			
				echo $dataArea['mensaje'];die;			
			}
		}else{//caso contrario actualizar
			$data = $persona->actualizar($request, $idPersona);	
		}
		echo $data['mensaje'];*/
	}
	
	
	public function buscar(){
		$max = 10;
		$pag = $_GET['pag'];
				
		$request = !empty($_POST) ? $_POST : $_GET;
		$persona = new Persona();			
		$start = ($pag-1)*$max;
		
        $data['datos'] = $persona->buscar($request,$start,$max);
		$usu = new Usuario();
		$permisoAdmin = $usu->verificarTipoUsuario();   
		$permisoAdmin?$data['tiene_permiso'] = true:$data['tiene_permiso']=false;//verificar si tiene permiso
				
		$data['controllerPersona']=& $this;			
		$totalRegistros = $data['datos']['rows_total'];
		if($totalRegistros>$max){
			$objPag = new Paginacion();
			$data['paginacion'] = $objPag->generarNumeracion($totalRegistros,$max,$pag);	
		}
		$data['total_registros'] = "Total de registros: ".$data['datos']['rows_total'];
		unset($data['datos']['rows_total']);		 
		$this->showView('personas_listado.php',$data);
	}


    public function detalle_codigo($subcat){
        $codigos= array();
            $codigos['000'] = 'Datos de identificaci&iacute;n y contacto.';
			$codigos['000.010'] = 'Identificaci&oacute;n CVN.';
			$codigos['000.020'] = 'Identificaci&oacute;n de curr&iacute;culo.';
			
			$codigos['010'] = 'Situaci&oacute;n profesional.';
			$codigos['010.010'] = 'Situaci&oacute;n profesional actual.';
			$codigos['010.020'] = 'Cargos y actividades desempe�ados con anterioridad.';
			
			$codigos['020'] 	= 'Formaci&oacute;n acad&eacute;mica recibida.';
			$codigos['020.010'] = 'Titulaci&oacute;n universitaria.';
			$codigos['020.010.010'] = 'Diplomaturas, licenciaturas e ingenier&iacute;as, grados y m&aacute;sters.';
			$codigos['020.010.020'] = 'Doctorados';
			$codigos['020.010.030'] = 'Otra formaci&oacute;n universitaria de postgrado.';
			$codigos['020.060'] = 'Conocimiento de idiomas.';
			
			$codigos['030'] = 'Actividad docente.';
			$codigos['030.010'] = 'Docencia impartida.';
			$codigos['030.040'] = 'Direcci&oacute;n de tesis doctorales y/o proyectos fin de carrera.';
			$codigos['030.050'] = 'Tutor&iacute;a acad&eacute;mica de estudiantes.';     
			$codigos['030.060'] = 'Cursos y seminarios impartidos orientados a la formaci&oacute;n decente universitaria.';
			$codigos['030.070'] = 'Publicaciones docentes o de car&aacute;ter pedag&oacute;gico, libros, art&iacute;culos, etc.';
			
			$codigos['040'] = 'Actividad en el campo de la sanidad.';
			$codigos['040.010'] = 'Actividad sanitaria en instituciones de la UE.';
			$codigos['040.020'] = 'Actividad sanitaria en la OMS.';
			$codigos['040.030'] = 'Actividad sanitaria en otros organismos internacionales.';
			$codigos['040.040'] = 'Actividad en cooperaci&oacute;n practicando la atenci&oacute;n de salud en pa&iacute;ses en desarrollo.';
			
			$codigos['050'] = 'Experiencia cient&iacute;fica y tecnol&oacute;gica.';
			$codigos['050.010'] = 'Participaci&oacute;n en grupos/equipos de investigaci&oacute;n, desarrollo e innovaci&oacute;n.';
			$codigos['050.020'] = 'Actividad cient&iacute;fica o tecnol&oacute;gica.';
			$codigos['050.020.010'] = 'Participaci&oacute;n en proyectos de I+D+i financiados en convocatorias competitivas de Administraciones o entidades p&uacute;blicas o privadas.';
			$codigos['050.020.020'] = 'Participaci&oacute;n en contratos, convenios o proyectos de I+D+i no competitivos con Administraciones o entidades p&uacute;blicas o privadas.';
			$codigos['050.020.030'] = 'Obras art&iacute;sticas dirigidas.'; 
			
			$codigos['060'] = 'Actividades cient&iacute;ficas y tecnol&oacute;gicas.'; 
			$codigos['060.010'] = 'Producci&oacute;n cient&iacute;fica.';
			$codigos['060.010.010'] = 'Publicaciones, documentos cient&iacute;ficos y t&eacute;cnicos.';
			$codigos['060.010.020'] = 'Trabajos presentados en congresos nacionales e internacionales';
			$codigos['060.010.030'] = 'Trabajos presentados en jornadas, seminarios, talleres de trabajo y/o cursos nacionales o internacionales.';
			$codigos['060.010.040'] = 'Otras actividades de divulgaci&oacute;n.';
			$codigos['060.010.050'] = 'Estancias en centros de I+D+i p&uacute;blicos o privados.';			
			$codigos['060.030'] = 'Otros m&eacute;ritos.';
			$codigos['060.030.010'] = 'Ayudas y becas obtenidas.';
			$codigos['060.030.020'] = 'Pertenencia a sociedades cient&iacute;ficas y asociaciones profesionales.';			
			$cod = $codigos[$subcat];            
            return $cod;		
    }
	public function mostrar_areas(){
	   $request = !empty($_POST) ? $_POST : $_GET;
	   $idPersona = $request['id'];
	   $tema = new Tema();
	   $areas = $tema->getTemasForPersona($idPersona);
	   $data['areas'] = $areas;
       $this->showView('ver_areas.php',$data);
       
	}
    
	public function descargar_pdf(){		
		$request = !empty($_POST) ? $_POST : $_GET;
		$id=$request['id'];		
		$persona = new Persona();
		$data = $persona->buscarPdf($id);
		$this->showView('descargar_pdf.php',$data);		
	}
	
	public function buscar_detalle(){	  
		$request = !empty($_POST) ? $_POST : $_GET;
		$id=$request['id'];
		$subcat=$request['subcat'];		
		$persona = new Persona();
		$data = $persona->buscarDetalle($id);
		$data['subcat'] = $subcat;   
        $data['id_detalle'] = $id;         		 	
		$this->showView('consultar_datos_personas.php',$data);		
	}
	/**eliminar registro de persona.Antes hay que eliminar categorias generales, y areas. Luego eliminar la persona**/
	public function eliminar(){
		$usu = new Usuario();
		$permisoAdmin = $usu->verificarTipoUsuario();
		if($permisoAdmin){
			$request = !empty($_POST) ? $_POST : $_GET;
			$idPersona	= $request['id_persona'];			
			$persona = new Persona();
			$data = $persona->actualizarEstado($idPersona, 'borrador');	
			/*$request = !empty($_POST) ? $_POST : $_GET;
			$idPersona	= $request['id_persona'];
			$categoria = new CategoriaGeneral();
			$resCategoria = $categoria->eliminar($idPersona);
			if($resCategoria['status']){
				$area = new Area();
				$resArea = $area->eliminar($idPersona);
				if($resArea['status']){
					$persona = new Persona();
					$data = $persona->eliminar($idPersona);	
				}else{
					$data = $resArea;
				}
					
			}else{
				$data = $resCategoria;
			}	*/
		}else{
			$data['status']=false;
			$data['mensaje']='No tiene permisos para eliminar';
		}
		echo json_encode($data);		
		exit();
	}
	
	/******action para descargar excel***/
	public function descargar_excel(){		
		$request = !empty($_POST) ? $_POST : $_GET;
		$persona = new Persona();
        $data = $persona->buscar($request);//no se le envia parametros puesto que queremos todos los registros
		$res['url'] = $this->generarExcel($data);
		echo json_encode($res); 
		exit();
	}
	
	/******function para generar archivo de excel******/
	private function generarExcel($data){
		$objExcel = new Excel();
		$objExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Apellidos')
            ->setCellValue('B1', 'Nombres')
            ->setCellValue('C1', 'Telefono movil')
			->setCellValue('D1', 'Telefono fijo')
			->setCellValue('E1', 'E-mail')
			->setCellValue('F1', '(0)Datos de identificacion y contacto')
			->setCellValue('G1', '(1)Situacion profesional')
			->setCellValue('H1', '(2)Formacion academica recibida')
			->setCellValue('I1', '(3)Actividad docente')
			->setCellValue('J1', '(4)Actividad en el campo de la sanidad')
			->setCellValue('K1', '(5)Experiencia cientifica y tecnologica')
			->setCellValue('L1', '(6)Actividades cientificas y tecnologicas');
		for ($col = 'A'; $col != 'M'; $col++) {
			$objExcel->getActiveSheet(0)->getColumnDimension($col)->setAutoSize(true);
		}		
		$styleArray = array(
		    'font' => array(
		        'bold' => true
		    )
		);
		$objExcel->getActiveSheet(0)->getStyle('A1:L1')->applyFromArray($styleArray);		
		
		unset($data['rows_total']);
		$letraParaCodigo = array('F','G','H','I','J','K','L'); 
		foreach ($data as $i => $fila) {
			$datos = $fila['datos'];			
			$pos = $i+2;		
			
			$objExcel->setActiveSheetIndex(0)->setCellValue('A'.$pos, $datos['apellidos']);
			$objExcel->setActiveSheetIndex(0)->setCellValue('B'.$pos, $datos['nombre']);
			
			$objExcel->getActiveSheet()->getStyle('C'.$pos)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$objExcel->setActiveSheetIndex(0)->setCellValue('C'.$pos, $datos['telefono_movil'].' ');
			$objExcel->getActiveSheet()->getStyle('D'.$pos)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);			
			$objExcel->setActiveSheetIndex(0)->setCellValue('D'.$pos, $datos['telefono_fijo'].' ');
			
			$objExcel->setActiveSheetIndex(0)->setCellValue('E'.$pos, $datos['email']);
			//foreach para codigos
			$codigos = $fila['codigos'];
			
			foreach ($codigos as $j => $codigo) {
				$mostraCodigo = " ";	
				foreach ($codigo as $k => $cod) {					
					$contenidoCodigo = explode(';', $cod);//separamos las 2 partes q son lo q se muestra en pantalla y el subcat
					$mostraCodigo.= " ( ".$contenidoCodigo[0]." ) ";										
				}				
				$celdaCodigo = $letraParaCodigo[$j].$pos;				
				$objExcel->setActiveSheetIndex(0)->setCellValue($celdaCodigo,$mostraCodigo);								
			}
			
			
			/*$objExcel->getActiveSheet()->getComment('E'.$pos)->setAuthor('Autor Henry');
			$objCommentRichText = $objExcel->getActiveSheet()->getComment('E'.$pos)->getText()->createTextRun('Comentario de Henry:');
			$objCommentRichText->getFont()->setBold(true);
			$objExcel->getActiveSheet()->getComment('E'.$pos)->getText()->createTextRun("\r\n");
			$objExcel->getActiveSheet()->getComment('E'.$pos)->getText()->createTextRun('Este es un mensaje de prueba...!!!');
			*/
			        
		}
		$objExcel->getActiveSheet()->setTitle('Personas');
		$objExcel->setActiveSheetIndex(0);		
		$objExcel->excel_name = 'listado_personas.xlsx';
		$download_name = $objExcel->generarExcel();
		return $download_name;				
	}
	
	/***function para mostrar una vista**/
	public function showView($page,$data=''){
		include_once (realpath(dirname(__FILE__) . '/../views/personas/'.$page));
	}
}

$pers = new Personas();
$pers->redireccionarAction();

/*$res = new Restriccion();
if($res->bloquearIp()){
	$pers = new Personas();
	$pers->redireccionarAction();
}*/

?>