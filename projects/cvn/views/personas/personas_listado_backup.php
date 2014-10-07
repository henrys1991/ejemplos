<?php
require_once(realpath(dirname(__FILE__) . '/db/Persona.php'));
  
buscar();
function buscar(){
		$request = !empty($_POST) ? $_POST : $_GET;
		$persona = new Persona();
		$res = $persona->buscar($request);
		mostrar($res);
}

function printArray($array){
	echo "<pre>".print_r($array,true)."</pre>";
}

?>
<!--<table  border="1px solid #BDBDBD" cellspacing="0" style="width:100%;font-size: 11px; font-family: Lucida Grande,Lucida Sans Unicode;">
	<tr>
		<td>Dato</td>
		<td>&Aacute;reas</td>
		<td>Presentaci&oacute;n</td>
		<td>PDF</td>
		<td>Datos de identificaci&oacute;n y contacto</td>
		<td>Situaci&oacute;n profesional</td>
		<td>Formaci&oacute;n acad&eacute;mica recibida</td>
		<td>Actividad docente</td>
		<td>Actividad en el campo de la sanidad</td>
		<td>Exp. cient&iacute;fica y tecnol&oacute;gica</td>
		<td>Actividades. cient&iacute;ficas y tecnol&oacute;gicas</td>
	</tr>-->
<?php
	function mostrar($data){
		//printArray($data);
		/*echo "<br/>";
		echo "<div style='width:100%;font-size: 11px; font-family: Lucida Grande,Lucida Sans Unicode;'>";
			echo "<div style='border-bottom:1px solid #C4EFFE;width:100%;'>";
				echo "<div class='listadoHeader' style='width:15%'>Datos</div>";
				echo "<div class='listadoHeader' style='width:6%;'>&Aacute;reas</div>";
				echo "<div class='listadoHeader' style='width:8%;'>Presentaci&oacute;n</div>";
				echo "<div class='listadoHeader' style='width:7%;'>PDF</div>";
				echo "<div class='listadoHeader' style='width:8%;'>Datos de identificaci&oacute;n y contacto</div>";
				echo "<div class='listadoHeader' style='width:8%;'>Situaci&oacute;n profesional</div>";
				echo "<div class='listadoHeader' style='width:7%;'>Formaci&oacute;n acad&eacute;mica recibida</div>";
				echo "<div class='listadoHeader' style='width:7%;'>Actividad docente</div>";
				echo "<div class='listadoHeader' style='width:8%;'>Actividad en el campo de la sanidad</div>";
				echo "<div class='listadoHeader' style='width:7%;'>Exp. cient&iacute;fica y tecnol&oacute;gica</div>";
				echo "<div class='listadoHeader' style='width:7%;'>Actividades. cient&iacute;ficas y tecnol&oacute;gicas</div>";
			echo "</div>";
		
		echo "<div style='clear:both'></div>";	
		foreach ($data as $key => $value) {
				echo "<div style='border-bottom:1px solid #A4A4A4;'>";
					$filas = $value['datos'];
					$nombre 		= 	$filas['nombre'];
					$apellidos 		= 	$filas['apellidos'];
					$email 			= 	$filas['email'];
					$movil 			= 	$filas['telefono_movil'];
					$fijo 			= 	$filas['telefono_fijo'];
					$presentacion 	= 	$filas['presentacion'];
					$id_descarga 	= 	$filas['id'];
					echo "<div style='float:left; text-align:left; width:15%;'>";
						echo "$apellidos $nombre<br/>";
						echo "<b>Tel&eacute;fono m&oacute;vil:</b> $movil<br/>";
						echo "<b>Tel&eacute;fono fijo:</b> $fijo<br/>";
						echo "<b>Email:</b> $email<br/>";
					echo "</div>";
					
					echo "<div style='width:6%;text-align:center; float:left;'>";
						foreach ($value['areas'] as $k => $area) {						
							echo $area['descripcion']."<br/>";								
						}
					echo "</div>";
					
					echo "<div style='width:7%; float:left;text-align:left;padding-left:30px;overflow:visible;'>&nbsp;".$presentacion."</div>";	
					echo "<div style='width:9%;float:left;text-align:center;'><a target='_blank' href='__descargar_pdf.php?id=$id_descarga'>Descargar</a></div>";
					foreach ($value['codigos'] as $k => $codigo) {
						echo "<div style='width:8.2%;float:left;text-align:center;font-size:9px;'>";
							echo "&nbsp;";	
							foreach ($codigo as $i => $cod) {
								$subcat = substr($cod,0,7);								
								echo "<a class='enlaceCodigo' id='codigo_".$id_descarga."_".$cod."' onmouseover='persona.popup.crear(this.id,".$filas['id'].", \"".$subcat."\")' onmouseout='persona.popup.eliminar(this.id);' href='javascript:persona.datos.load(".$id_descarga.",\"".$subcat."\")'>".$cod."</a></br>";
							}						
						echo "</div>";
					}			
				echo "</div>";
				echo "<div style='clear:both'></div>";	
			}
			echo "</div>";
		
		*/
		echo "<br/>";
		echo "<table border='1px solid #BDBDBD' cellspacing='0' style='width:100%;font-size: 11px; font-family: Lucida Grande,Lucida Sans Unicode;'>";
		echo "<tr>";
		echo "<td>Datos</td>";
		echo "<td>&Aacute;reas</td>";
		echo "<td>Presentaci&oacute;n</td>";
		echo "<td>PDF</td>";
		echo "<td>(0) Datos de identificaci&oacute;n y contacto</td>";
		echo "<td>(1) Situaci&oacute;n profesional</td>";
		echo "<td>(2) Formaci&oacute;n acad&eacute;mica recibida</td>";
		echo "<td>(3) Actividad docente</td>";
		echo "<td>(4) Actividad en el campo de la sanidad</td>";
		echo "<td>(5) Exp. cient&iacute;fica y tecnol&oacute;gica</td>";
		echo "<td>(6) Actividades. cient&iacute;ficas y tecnol&oacute;gicas</td>";
		echo "</tr>";			
		//echo "<div class='pagination'>paginacion<div>";	
		foreach ($data as $key => $value) {
				//printArray($value);				
				echo "<tr>";
				$filas = $value['datos'];
				$nombre 		= 	$filas['nombre'];
				$apellidos 		= 	$filas['apellidos'];
				$email 			= 	$filas['email'];
				$movil 			= 	$filas['telefono_movil'];
				$fijo 			= 	$filas['telefono_fijo'];
				$presentacion 	= 	$filas['presentacion'];
				$id_descarga 	= 	$filas['id'];
				echo "<td style='text-align:left; width:15%;'>";
				echo "$apellidos $nombre<br/>";
				echo "<b>Tel&eacute;fono m&oacute;vil:</b> $movil<br/>";
				echo "<b>Tel&eacute;fono fijo:</b> $fijo<br/>";
				echo "<b>Email:</b> $email<br/>";
				echo "</td>";
				echo "<td style='text-align:left; padding-left:10px;'>";									
				foreach ($value['areas'] as $k => $area) {						
					echo isset($area['descripcion'])? $area['descripcion']."<br/>": ' - ';
				}
				
				/***proceso para verificar si es demasiado texto**/
				$maxCaracteres = 50;				
				if(strlen($presentacion)>$maxCaracteres){					
					$eventMousePresentacion = "onmouseover='persona.popup.mostrarInfoPresentacion(this.id, $id_descarga,\"$presentacion\")' onmouseout='persona.popup.eliminar(this.id)'";
					$presentacion = substr($presentacion, 0, $maxCaracteres)."<a class='enlaceCodigo' href='#' id='presentacion_".$id_descarga."' ".$eventMousePresentacion.">...</a>";
					 
				}
				echo "<td><div class='textoAjustable'>";
				echo $presentacion;
				echo "</div></td>";
					
				echo "<td><a target='_blank' href='__descargar_pdf.php?id=$id_descarga'>Descargar</a></td>";
				foreach ($value['codigos'] as $k => $codigo) {
					echo "<td>";
					foreach ($codigo as $i => $cod) {
						//$subcat = substr($cod,0,7);//esto estaba							
						$d = explode(';', $cod);						
						$code 	= $d[0];
						$subcat = $d[1];						
						//$code=$cod;						
						//__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($cod,0,7)."			
						//echo "<a class='enlaceCodigo' id='codigo_".$id_descarga."_".$cod."' onmouseover='persona.popup.crear(this.id,".$filas['id'].", \"".$subcat."\")' onmouseout='persona.popup.eliminar(this.id);' href='javascript:persona.datos.load(".$id_descarga.",\"".$subcat."\")'>".$cod."</a></br>";
						echo "<a class='enlaceCodigo' id='codigo_".$id_descarga."_".$cod."' onmouseover='persona.popup.mostrarInfoCode(this.id,".$filas['id'].", \"".$subcat."\")' onmouseout='persona.popup.eliminar(this.id);' href='javascript:persona.datos.load(".$id_descarga.",\"".$subcat."\")'>".$code."</a>&nbsp;&nbsp;&nbsp;";
					}
					echo "</td>";
				}			
				echo "</tr>";
			} 
		 
			/*echo "<td>$apellidos $nombre</td>";			
			
			echo "<td>";
			$quer="SELECT * FROM r_personas_interes WHERE id_persona='$id_descarga'";
			$enlac=$conexion->query($quer);

			while($fila=$conexion->fetch_array($enlac)){
				$cod=$fila['cod_descripcion'];
				$que="SELECT * FROM areas_interes WHERE cod_descripcion='$cod'";
				$enla=$conexion->query($que);
				while($fil=$conexion->fetch_array($enla))echo $fil['descripcion']."<br/>";
			}
			
			echo "</td>";
			echo "<td>$presentacion</td>";
			echo "<td><a target='_blank' href='__descargar_pdf.php?id=$id_descarga'>Descargar</a></td>";*/
		
		
	}
	//primero se recorre la tabla de personas 
	//if(!isset($_GET['buscar']))$query ="SELECT * FROM persona";
	/*if(!isset($_POST['nom_ape']))$query ="SELECT * FROM persona";
	else{
		//$busc = $_GET['buscar'];
		$busc = $_POST['nom_ape'];
		$query = "SELECT * FROM persona WHERE nombre LIKE '%$busc%' OR apellidos LIKE '%$busc%'";
	}
	$enlace = $conexion->query($query);

	while($filas=$conexion->fetch_array($enlace)){
		$nombre 		= 	$filas['nombre'];
		$apellidos 		= 	$filas['apellidos'];
		$email 			= 	$filas['email'];
		$movil 			= 	$filas['telefono_movil'];
		$fijo 			= 	$filas['telefono_fijo'];
		$presentacion 	= 	$filas['presentacion'];
		$id_descarga 	= 	$filas['id'];
		$procesado 		= 	$filas['procesado'];

		echo "<tr>";
			echo "<td>$apellidos $nombre</td>";			
			
			echo "<td>";
			$quer="SELECT * FROM r_personas_interes WHERE id_persona='$id_descarga'";
			$enlac=$conexion->query($quer);

			while($fila=$conexion->fetch_array($enlac)){
				$cod=$fila['cod_descripcion'];
				$que="SELECT * FROM areas_interes WHERE cod_descripcion='$cod'";
				$enla=$conexion->query($que);
				while($fil=$conexion->fetch_array($enla))echo $fil['descripcion']."<br/>";
			}
			
			echo "</td>";
			echo "<td>$presentacion</td>";
			echo "<td><a target='_blank' href='__descargar_pdf.php?id=$id_descarga'>Descargar</a></td>";
*/
			/*
			Si se ha procesado el XML, esto está indicado en la columna procesado de cada persona, se recorrerá
			la tabla categorias_generales según el id de la persona actual en el while

			Se necesitará una expresión regular para detectar a que categoría pertenecen las subcategorías
			en $datox se almacenará la cadena que se escribirá al final, el array almacenará las subcategorias para evitar su repetición
			el estado indicará si existe redundancia en el array y el contador facilita el indice a manipular.
			*/
		
		/*	if($procesado){
				$grupo1 = "/^000./";	$dato1 ="";		$subcats1[]="";		$contador1=0;	$estado1=0;
				$grupo2 = "/^010./";	$dato2 ="";		$subcats2[]="";		$contador2=0;	$estado2=0;
				$grupo3 = "/^020./";	$dato3 ="";		$subcats3[]="";		$contador3=0;	$estado3=0;
				$grupo4 = "/^030./";	$dato4 ="";		$subcats4[]="";		$contador4=0;	$estado4=0;
				$grupo5 = "/^040./";	$dato5 ="";		$subcats5[]="";		$contador5=0;	$estado5=0;
				$grupo6 = "/^050./";	$dato6 ="";		$subcats6[]="";		$contador6=0;	$estado6=0;
				$grupo7 = "/^060./";	$dato7 ="";		$subcats7[]="";		$contador7=0;	$estado7=0;

				$query2 = "SELECT * FROM categorias_generales WHERE id_persona='$id_descarga'";
				$enlace2 = $conexion->query($query2);

				while($fila2=$conexion->fetch_array($enlace2)){
					if(preg_match($grupo1,$fila2['code'])){
						foreach ($subcats1 as $key) if($key==substr($fila2['code'], 4,-8)) $estado1=1; 
						if($estado1==0){
							$subcats1[$contador1]=substr($fila2['code'], 4,-8); 
							$dato1 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";
						}
						$contador1++;$estado1=0;
					} 

					//desglosar para indentar como el modelo anterior
					if(preg_match($grupo2,$fila2['code'])){foreach ($subcats2 as $key) if($key==substr($fila2['code'], 4,-8)) $estado2=1; if($estado2==0){$subcats2[$contador2]=substr($fila2['code'], 4,-8); $dato2 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador2++;$estado2=0;} 
					if(preg_match($grupo3,$fila2['code'])){foreach ($subcats3 as $key) if($key==substr($fila2['code'], 4,-8)) $estado3=1; if($estado3==0){$subcats3[$contador3]=substr($fila2['code'], 4,-8); $dato3 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador3++;$estado3=0;} 
					if(preg_match($grupo4,$fila2['code'])){foreach ($subcats4 as $key) if($key==substr($fila2['code'], 4,-8)) $estado4=1; if($estado4==0){$subcats4[$contador4]=substr($fila2['code'], 4,-8); $dato4 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador4++;$estado4=0;} 
					if(preg_match($grupo5,$fila2['code'])){foreach ($subcats5 as $key) if($key==substr($fila2['code'], 4,-8)) $estado5=1; if($estado5==0){$subcats5[$contador5]=substr($fila2['code'], 4,-8); $dato5 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador5++;$estado5=0;} 
					if(preg_match($grupo6,$fila2['code'])){foreach ($subcats6 as $key) if($key==substr($fila2['code'], 4,-8)) $estado6=1; if($estado6==0){$subcats6[$contador6]=substr($fila2['code'], 4,-8); $dato6 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador6++;$estado6=0;} 
					if(preg_match($grupo7,$fila2['code'])){foreach ($subcats7 as $key) if($key==substr($fila2['code'], 4,-8)) $estado7=1; if($estado7==0){$subcats7[$contador7]=substr($fila2['code'], 4,-8); $dato7 .= "<a href='__consultar_datos_personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador7++;$estado7=0;} 
				}
				unset($subcats1);	unset($subcats2);	unset($subcats3);	unset($subcats4);
				unset($subcats5);	unset($subcats6);	unset($subcats7);	

			}
			echo "<td>$dato1</td><td>$dato2</td><td>$dato3</td><td>$dato4</td><td>$dato5</td><td>$dato6</td><td>$dato7</td>";
		echo "</tr>";
	}*/
?>		
</table>

