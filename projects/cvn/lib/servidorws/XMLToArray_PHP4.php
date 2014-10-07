<?php
include_once(dirname(__FILE__) ."/XMLParser.php");
class XMLtoArray
{
var $doc;
var $utf=false;
var $patron_llaves_numericas='/\w*_(\d+)$/';

	function setUTF($bool){
		$this->utf = $bool;
	}

       /** TODO devolver FALSE si falla
	 *
	 * La funcion que convierte un string xml a un array segun las convenciones de funiber
	 * Esto es la operacion inversa a ArrayToXML
	 *
	 * @param string $data - el xml en formato string que quiere convertirse
	 * @return Array php
	 */
	function toArray($data)
	{
		$agregar_root=false;
		//Si es la primera llamada
		if (is_string($data))
		{
			$data = trim($data);
			$parser = new XMLParser($data);
			$parser->Parse();
			$data = $parser->document;
			if($data->tagName=="xml"){
				$agregar_root=false;
			}else{
				$agregar_root=true;
			}
		}

		$rootNodeName = $data->tagName;

		for($i=0; $i<sizeof($data->tagChildren);$i++){
			$key = $data->tagChildren[$i]->tagName;
			if(preg_match($this->patron_llaves_numericas,$key,$matches)){
				$key = $matches[sizeof($matches)-1];
			}
			
			if(sizeof($data->tagChildren[$i]->tagChildren)>0){
				$php[$key] = $this->toArray($data->tagChildren[$i]);
			}else{
				if(strlen($data->tagChildren[$i]->tagData)!=0){
				    $php[$key] = $data->tagChildren[$i]->tagData;
				}else{
				    $php[$key] = NULL;
				}
			}
		}

		if($agregar_root){
			$root[$rootNodeName] = $php;
			$php = $root;
		}
		
		return $php;
	}
}
?>