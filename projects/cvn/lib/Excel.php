<?php
require_once(dirname(__FILE__) . '/PHPExcel/PHPExcel.php');
//define ('URL_TEMPORAL', realpath(dirname(__FILE__) . "/../public/doc/temporal"));

//define('TMP_DOWNLOAD_URL', base_url('public/files/tmp/'));
//define('TMP_PATH', $system_path . 'public/files/tmp/');

//define('TMP_DOWNLOAD_URL', URL_TEMPORAL);
//define('TMP_PATH', RUTA_TEMPORAL);

define('TMP_DOWNLOAD_URL', '/cvn/public/doc/temporal/'); 
//define('TMP_PATH', RUTA_TEMPORAL);
define('TMP_PATH', realpath(dirname(__FILE__) . "/../public/doc/temporal/")); 

$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
//$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

class Excel extends PHPExcel{
	private $excel_path = TMP_PATH;
	private $excel_absolute_path;
	private $excel_download_path = TMP_DOWNLOAD_URL;
	private $excel_download_url;
	var $excel_name;

	static function &newExcel(){
		$Excel = new Excel;
		$Excel->getProperties()->setCreator("Fundacion Universitaria Iberoamericana")
			 ->setLastModifiedBy("Maarten Balliauw")
			 ->setTitle("Office 2007 XLSX Test Document")
			 ->setSubject("Office 2007 XLSX Test Document")
			 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Test result file");
		$Excel->setActiveSheetIndex(0);
		$Excel->getActiveSheet()->setTitle('Hoja 1');
		return new Excel;
	}

	/**
	 * Generar automaticamente un archivo excel en la ruta temporal del sistema de gestion
	 *
	 * @return url de descarga externa
	 */
	function generarExcel($formato = 'Excel2007'){
	  // echo $this->excel_name;
		if($this->excel_name == ''){
			return false;
		}
		$this->generarRutasDeAcceso();
		$save_name = $this->getAbsolutePath();
       // echo $save_name;
		$objWriter = PHPExcel_IOFactory::createWriter($this, $formato);
		$objWriter->save($save_name);
		return $this->getAbsoluteURL();
	}

	/**
	 * Genera el absolutePath para el archivo Excel
	 */
	function setAbsolutePath(){
		$this->excel_absolute_path = $this->excel_path .'/'. $this->excel_name;
	}

	/**
	 * @return Ruta en filesistem donde se encuenta el fichero
	 */
	function getAbsolutePath(){
		return $this->excel_absolute_path;
	}

	/**
	 * Generar la ruta de descarga del archivo Excel
	 */
	function setAbosuluteURL(){
		$this->excel_download_url = $this->excel_download_path . $this->excel_name;
	}

	/**
	 * @return Ruta de descarga del fichero
	 */
	function getAbsoluteURL(){
		return $this->excel_download_url;
	}

	/**
	 * Generar las rutas de absolutePath y downloadUrl
	 */
 	function generarRutasDeAcceso(){
 		$this->setAbosuluteURL();
 		$this->setAbsolutePath();
 	}
}