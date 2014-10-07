<?php 
/*
Encargado de mostrar la tabla con las personas que han subido el curriculum
además también controla las subcategorias mostradas en las categorias de la tabla

-categorias_generales

también mostrará las areas de interés a través de las tablas

-areas_interes 
-r_personas_interes
*/

	header('Content-Type: text/html; charset=UTF-8'); 
	require ('tools/claseMySQLi.php');
	$conexion = new db("192.168.0.8","root","funi@mysqlMainAdmin#2013","cvn");
	$query = "SELECT * FROM areas_interes;";
	$enlace = $conexion->query($query);
?>

<style>td{width:120px;overflow: auto;text-align: center;}</style>
<div style="min-height:50px;border:1px solid black;text-align:center">
	Buscar:
	<form method="GET" action="__consultar_personas.php">
		<div style="float:left;margin-left:25px;border:1px solid black;"><input type="text" placeholder="buscar" name="buscar"/></div>
		<div style="float:left;margin-left:25px;border:1px solid black;">
		Áreas de interés<br/>


			<?php
			while($fila= $conexion->fetch_array($enlace)){
				$id = $fila['cod_descripcion'];
				$desc = $fila['descripcion'];
				echo "<input type='checkbox' id='$id' name='$desc' value='$desc'/>";
				echo "<label for='$id'>$desc</label>";
			}
			?>


		</div>
		<div style="float:left;margin-left:25px;border:1px solid black;">
		02 Formación académica<br/>
			<input type="checkbox" name="010" id="010"/><label for="010">010 Diplomatura<label>
			<input type="checkbox" name="010" id="010"/><label for="010">020 Doctorado<label>
		</div>
		<div style="float:left;margin-left:25px;border:1px solid black;">
		</div>
		<br/><br/><br/><input type="submit" value="Enviar"/>
	</form>
</div>
<table border="1px" cellspacing="0" style="width:100%"><tr><td>Dato</td><td>Areas</td><td>PDF</td><td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td></tr>


<?php
	//primero se recorre la tabla de personas 
	if(!isset($_GET['buscar']))$query ="SELECT * FROM persona";
	else{
		$busc = $_GET['buscar'];
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
			echo "<td><a href='__descargar_pdf.php?id=$id_descarga'>Descargar</a></td>";

			/*
			Si se ha procesado el XML, esto está indicado en la columna procesado de cada persona, se recorrerá
			la tabla categorias_generales según el id de la persona actual en el while

			Se necesitará una expresión regular para detectar a que categoría pertenecen las subcategorías
			en $datox se almacenará la cadena que se escribirá al final, el array almacenará las subcategorias para evitar su repetición
			el estado indicará si existe redundancia en el array y el contador facilita el indice a manipular.
			*/
			if($procesado){
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
							$dato1 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";
						}
						$contador1++;$estado1=0;
					} 

					//desglosar para indentar como el modelo anterior
					if(preg_match($grupo2,$fila2['code'])){foreach ($subcats2 as $key) if($key==substr($fila2['code'], 4,-8)) $estado2=1; if($estado2==0){$subcats2[$contador2]=substr($fila2['code'], 4,-8); $dato2 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador2++;$estado2=0;} 
					if(preg_match($grupo3,$fila2['code'])){foreach ($subcats3 as $key) if($key==substr($fila2['code'], 4,-8)) $estado3=1; if($estado3==0){$subcats3[$contador3]=substr($fila2['code'], 4,-8); $dato3 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador3++;$estado3=0;} 
					if(preg_match($grupo4,$fila2['code'])){foreach ($subcats4 as $key) if($key==substr($fila2['code'], 4,-8)) $estado4=1; if($estado4==0){$subcats4[$contador4]=substr($fila2['code'], 4,-8); $dato4 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador4++;$estado4=0;} 
					if(preg_match($grupo5,$fila2['code'])){foreach ($subcats5 as $key) if($key==substr($fila2['code'], 4,-8)) $estado5=1; if($estado5==0){$subcats5[$contador5]=substr($fila2['code'], 4,-8); $dato5 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador5++;$estado5=0;} 
					if(preg_match($grupo6,$fila2['code'])){foreach ($subcats6 as $key) if($key==substr($fila2['code'], 4,-8)) $estado6=1; if($estado6==0){$subcats6[$contador6]=substr($fila2['code'], 4,-8); $dato6 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador6++;$estado6=0;} 
					if(preg_match($grupo7,$fila2['code'])){foreach ($subcats7 as $key) if($key==substr($fila2['code'], 4,-8)) $estado7=1; if($estado7==0){$subcats7[$contador7]=substr($fila2['code'], 4,-8); $dato7 .= "<a href='__consultar_datos_Personas.php?id=$id_descarga&subcat=".substr($fila2['code'],0,-8)."'>".substr($fila2['code'],0,-8).".000.000</a><br/>";}$contador7++;$estado7=0;} 
				}
				unset($subcats1);	unset($subcats2);	unset($subcats3);	unset($subcats4);
				unset($subcats5);	unset($subcats6);	unset($subcats7);	

			}
			echo "<td>$dato1</td><td>$dato2</td><td>$dato3</td><td>$dato4</td><td>$dato5</td><td>$dato6</td><td>$dato7</td>";
		echo "</tr>";
	}
?>		
</table>