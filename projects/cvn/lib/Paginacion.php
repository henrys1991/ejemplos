<?php

class Paginacion{
	
	public function generarNumeracion($rowsTotal,$rowsPage,$pageCurrent){
		$paginas="";
		$totalPages = ($rowsTotal/$rowsPage);
		$totalPages = floor($totalPages);//toma la parte entera
		(fmod($rowsTotal, $rowsPage)>0)?$totalPages++:$totalPages;
		
		$puntosSuspensivos='';
		if($totalPages>10){
			if($pageCurrent<=6){
				$puntosSuspensivos='soloFinal';
			}elseif($pageCurrent>=$totalPages-6){
				$puntosSuspensivos='soloInicio';			
			}else{
				$puntosSuspensivos='inicioFinal';
			}
		}	
		
		$pageCurrent==1 ? $paginas.="<a class='navigation left disabled' href='javascript:void(0)'> < </a>" :"";
		$anterior = $pageCurrent-1;
		$pageCurrent>1  ? $paginas.="<a class='navigation left' onclick='persona.listado.paginacion(\"ConsultarPersonas\",$anterior)'><</a>":'';		 
				
		//for($i=1;$i<=$totalPages;$i++){		
		$contDosLados=0;
		for($i=1;$i<=$totalPages;$i++){
			$current="";
			($i==$pageCurrent)?$current="class='current'":$current="";
			
			switch ($puntosSuspensivos) {
				case 'soloInicio':		  
						if($i==1){
							$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>...";
							$i=$totalPages-9;
						}else{
							$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";
						}						
						break;
				case 'soloFinal':						
						if($i==9){
							$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";
							$i=$totalPages-1;
						}elseif($i==$totalPages){
							$paginas.="...<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";							
						}else{
							$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";
						}											
						break;	
				case 'inicioFinal':
						if($i==1){							
							$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>...";
							$i=$pageCurrent-4;
							$contDosLados++;
						}elseif($i==$totalPages){
							$paginas.="...<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";							
						}else{
							if($contDosLados<=8){
								$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";
								$contDosLados++;	
							}else{
								$i=$totalPages-1;
							}						
						}
						break;
								
				default:$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";
						break;
			}
			//if($flagPagina){
				//$paginas.="<a $current onclick='persona.listado.paginacion(\"ConsultarPersonas\",$i)'>$i</a>";	
			//}			
		}
				
		$pageCurrent==$totalPages ? $paginas.="<a class='navigation right disabled' href='javascript:void(0)'> > </a>" :"";
		$siguiente = $pageCurrent+1;
		$pageCurrent<$totalPages  ? $paginas.="<a class='navigation right' onclick='persona.listado.paginacion(\"ConsultarPersonas\",$siguiente)'> > </a>":'';		 
			
		return $paginas;
	}
		

}
