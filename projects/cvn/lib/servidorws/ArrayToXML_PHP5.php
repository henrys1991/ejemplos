<?php
/**De momento se utiliza la version para PHP5 de XML_Parser pero la idea es utilizar SimpleXML que debe ser mas eficiente en el uso de memoria
  *Esto sera totalmente transparente al cliente siempre y cuando se respete la interfaz definida aqui
  */
include_once(dirname(__FILE__) ."/XMLParser_PHP5.php");
class ArrayToXML
{
var $doc;

var $charset_salida="iso-8859-1";

	function setCharsetSalida($charset){
		$this->charset_salida = $charset;
	}

	/**
	 * TODO devolver FALSE si falla
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data data que sera convertida a XML, se asume codificada en utf-8
	 * @return string XML
	 */
	function toXml($data)
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		/*if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}*/
		
		foreach($data as $key => $value){ $rootNodeName=$key; break;}
		
		
		if(is_numeric($rootNodeName)){
			$this->doc = new XMLTag("xml");
			$rootNodeName = "xml";
		}else{
			$this->doc = new XMLTag($rootNodeName);
			$temporal = $data[$rootNodeName];
			$data = $temporal;
		}
		
		return $this->toXmlRecursivo($data, $rootNodeName, $this->doc);
		
	}

	/**
	 * TODO devolver FALSE si falla
	 * private recursive function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data data que sera convertida a XML, se asume codificada en utf-8
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param SimpleXMLElement $xml - should only be used recursively
	 * @return string XML
	 */
	function toXmlRecursivo($data, $rootNodeName, &$xml){
		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				$key = $rootNodeName."_".$key;
			}
			
			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$xml->AddChild($key, NULL, NULL);
				$ref_xml = $xml->$key;
				$this->toXmlRecursivo($value, $key, $ref_xml[0]);
			}
			else
			{
				
				$xml->AddChild($key, NULL, NULL);
				$ref_xml = $xml->$key;
				$ref_xml[0]->tagData = $value;
			}
		}

		$xml_string = $this->doc->GetXML();
		//$xml_string = $xml->GetXML();
		//$xml_string = utf8_decode($xml_string_utf);
		return "<?xml version='1.0' encoding='ISO-8859-1'?>".$xml_string;
	}
}
?>