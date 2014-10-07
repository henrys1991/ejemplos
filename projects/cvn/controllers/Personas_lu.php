<?php
require_once (realpath(dirname(__FILE__) . '/Restriccion.php'));
require_once (realpath(dirname(__FILE__) . '/ControllerCVN.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Persona.php'));
require_once (realpath(dirname(__FILE__) . '/../models/Area.php'));
require_once (realpath(dirname(__FILE__) . '/../lib/Paginacion.php'));

class Personas extends ControllerCVN{	
	
    //public $codigo='holaaa';
	public function redireccionarAction(){
		switch ($_GET['action']) {			
			case 'registro'			: $this->registro();break;
			case 'guardar'			: $this->guardar();break;
			case 'consultar'		: $this->consultar();break;
			case 'buscar'			: $this->buscar();break;
			case 'descargar'		: $this->descargar_pdf();break;
			case 'buscar_detalle'	: $this->buscar_detalle();break;
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
		$this->showView('consultar_personas.php',$data);
	}
	
	public function guardar(){
		$flag= true;
		$request = !empty($_POST) ? $_POST : $flag=false;
		if(!flag){
			echo "[Error] No se puede guardar";die;
		}
		$persona = new Persona();		
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
		echo $data['mensaje'];
	}
    
    public function getOpciones(){
        $opciones =array (
            'listado'=> array(
                        'id' => 'lista_personas',
                        'columnas' => array(
                                 'datos'=> array(
                                        'titulo' => 'Datos',
                                        'atributos' => 'style="float:left;width:15%;padding:0 0 0 8px;"'   
                                    ),
                                 'areas'=> array(
                                        'titulo'=> 'Areas',
                                        'atributos' =>'style="float:left; width:4%;"'
                                 ),
                                 'presentacion'=> array(
                                        'titulo' => 'Presentacion',
                                        'atributos' =>'style="float: left; width:15%; padding: 0 9px;"'
                                 ),
                                 'pdf' => array(
                                        'titulo' => 'PDF',
                                        'atributos' => 'style="float:left; width:4%; "'
                                 ),
                        ),
                        'codigos' => array(
                                'identificacion_contacto'=> array(
                                    'titulo' => '(0)Datos de identificaci&oacute;n y contacto',
                                    'atributos' => 'style="float:left;width:8%; "'
                                 ),
                                 'situacion_profesional' => array(
                                        'titulo' => '(1)Situaci&oacute;n profesional',
                                        'atributos' => 'style="float:left;width:8%; "'
                                 ),
                                 'formacion_academica' => array(
                                        'titulo' => '(2)Formaci&oacute;n acad&eacute;mica recibida',
                                        'atributos' => 'style="float:left;width:8%; "'
                                 ),
                                 'actividad_docente' => array(
                                        'titulo' => '(3)Actividad docente',
                                        'atributos' => 'style="float:left;width: 8%; "'
                                 ),
                                 'actividad_sanidad' => array (
                                        'titulo' => '(4)Actividad en el campo de la sanidad',
                                        'atributos' => 'style="float:left;width: 8%; "'
                                 ),
                                 'experiencia_cientifica' => array(
                                        'titulo' => '(5)Exp. cient&iacute;fica y tecnol&oacute;gica',
                                        'atributos' => 'style="float:left; width:8%; "'
                                 ),
                                 'actividades_cientificas' => array(
                                        'titulo' => '(6)Actividades cient&iacute;ficas y tecnol&oacute;gicas',
                                        'atributos' => 'style="float:left; width:8%; "'
                                 ),
                        )
            ),
        );
        $opciones['vista_fila']['columnas'] =& $opciones['listado']['columnas'];
        $opciones['vista_fila']['columnas_codigos'] =& $opciones['listado']['codigos'];
        return $opciones;
    }
    
    public function vistaFila($id){
        $opciones = $this -> getOpciones();
        $data['opciones'] = $opciones['vista_fila'];
        $this->showView('personas_vista_fila.php',$data);
    }
    
    public function listado(){
        $max = 5;
		$pag = $_GET['pag'];		
		$request = !empty($_POST) ? $_POST : $_GET;
		$persona = new Persona();			
		$start = ($pag-1)*$max;
        $objPag = new Paginacion();	
        
        $opciones = $this -> getOpciones();
        $data['opciones'] = $opciones['listado']; 
        
        $data['controllerPersona']=& $this;
        $data['datos'] = $persona->buscar($request,$start,$max);
	
        $totalRegistros = $data['datos']['rows_total'];
        if($totalRegistros>$max){
			$data['paginacion'] = $objPag->generarNumeracion($totalRegistros,$max,$pag);	
		}		
		unset($data['datos']['rows_total']); 
        
		$this->showView('personas_listado_nuevo.php',$data);
    }
	
	public function buscar(){
		$max = 5;
		$pag = $_GET['pag'];
				
		$request = !empty($_POST) ? $_POST : $_GET;
		$persona = new Persona();			
		$start = ($pag-1)*$max;
		
        $data['datos'] = $persona->buscar($request,$start,$max);		
		$data['controllerPersona']=& $this;
		$objPag = new Paginacion();	
		$totalRegistros = $data['datos']['rows_total'];
		if($totalRegistros>$max){
			$data['paginacion'] = $objPag->generarNumeracion($totalRegistros,$max,$pag);	
		}		
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
	
	public function showView($page,$data=''){
		include_once (realpath(dirname(__FILE__) . '/../views/personas/'.$page));
	}	
}

$res = new Restriccion();
if($res->bloquearIp()){
	$pers = new Personas();
	$pers->redireccionarAction();
}

?>