<!DOCTYPE HTML>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="/cvn/public/js/prototype.js"></script> 
<script type="text/javascript" src="/cvn/public/js/generico.js"></script>
<script type="text/javascript" src="/cvn/public/js/persona.js"></script>
<script type="text/javascript" src="/cvn/public/js/codigo.js"></script>  
<link type="text/css" href="/cvn/public/css/persona.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
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
	//require ('tools/claseMySQLi.php');
	//$conexion = new db("localhost","root","funiber","cvn");
	//require_once(dirname(__FILE__) . '/conexion.ini.php');
	require_once (realpath(dirname(__FILE__) . '/../../db/conexion.ini.php'));	
	global $conexion;
	
	
	$query = "SELECT * FROM areas_interes;";
	$enlace = $conexion->query($query);
?>

<style>
    .mini_formulario th{
        width:30%;
    }
    .mini_formulario td{
        width:70%;
    }

</style>
	
    <div class="dialogo">
        <div class="DashboardWidget box" style="margin: 10px 30px 10px 10px; width: 97%; border-bottom: medium none;">
           <div class="headerArea">
            <div class="headerLeft"><span>B&uacute;squeda</span></div>
           </div>
        </div>
        <div class="botoneraSlider">
        <div class="mini_dialogo">
            <div class="PanelIzquierdo">
            	<form method="post" onsubmit="persona.listado.buscar(this); return false;" action="/cvn/controllers/Personas.php?action=buscar">
            		<div >
            			<fieldset>
            				<legend>020 Formación académica recibida</legend>
            				<table class="mini_formulario">
                            <tr>
                                <th style="width:35%"><label>010 Titulaci&oacute;n universitaria</label></th>
                                <td style="width:65%">
            						<div style="float: left; width:15%;"><label for="cod_020_010_010">010</label><input type="checkbox" name="cod_020_010_010" id="cod_020_010_010"/></div>
                                    <div style="float: left; width:85%;"><label for="cod_020_010_010">Diplomados, licenciaturas e ingenier&iacute;as, grados y m&aacute;sters</label></div>
            						<div style="float: left; width:15%"><label for="cod_020_010_020">020</label><input type="checkbox" name="cod_020_010_020" id="cod_020_010_020"/></div>
                                    <div style="float: left; width:85%;"><label for="cod_020_010_020" style="">Doctorados</label></div>
                                </td>
                            </tr>

                            </table>
            			</fieldset>	
            			
            			<fieldset>
            				<legend>050 Exp. científica y tecnol&oacute;gica</legend>
                            <table>	
                                <tr>	
            						<label for="cod_050_010">010</label><input type="checkbox" name="cod_050_010" id="cod_050_010"/><label for="cod_050_010">Participaci&oacute;n en grupos de investigaci&oacute;n</label><br/>
            						<label for="cod_050_020">020</label><input type="checkbox" name="cod_050_020" id="cod_050_020"/><label for="cod_050_020">Actividad cient&iacute;fica o tecnol&oacute;gica</label><br/>
                                </tr>	
                            </table>	
            			</fieldset>
            			
            			<fieldset>
            			<legend>Áreas de interés</legend>
                            <table>	
                                <tr>
                    				<div style="padding-left: 20px;">
                    					<?php
                    					while($fila= $conexion->fetch_array($enlace)){
                    						$id = $fila['cod_descripcion'];
                    						$desc = $fila['descripcion'];
                    						echo "<input type='checkbox' id='area_$id' name='area_$id' value='$desc'/>";
                    						echo "<label for='area_$id'>$desc</label><br/>";
                    					}
                    					?>
                                        
                    				</div>
                                </tr>
                            </table>	
            		</fieldset>
           		</div>
        	</div>
            
            <div class="PanelDerecho">	
        		<div style="clear: both"></div><br/>
        		<div style="border:0px solid black;">
        			<fieldset >
        				<legend>030 Actividad Docente</legend>				
        					<div style="padding-left: 20px;">
        						<label for="cod_030_010">010</label><input type="checkbox" name="cod_030_010" id="cod_030_010"/><label for="cod_030_010">Docencia impartida</label><br/>
        						<label for="cod_030_040">040</label><input type="checkbox" name="cod_030_040" id="cod_030_040"/><label for="cod_030_040">Direcci&oacute;n de tesis doctorales</label><br/>
        						<label for="cod_030_050">050</label><input type="checkbox" name="cod_030_050" id="cod_030_050"/><label for="cod_030_050">Tutor&iacute;a acad&eacute;mica de estudiantes</label><br/>	
        						<label for="cod_030_060">060</label><input type="checkbox" name="cod_030_060" id="cod_030_060"/><label for="cod_030_060">Cursos y seminarios impartidos</label><br/>
        						<label for="cod_030_070">070</label><input type="checkbox" name="cod_030_070" id="cod_030_070"/><label for="cod_030_070">Publicaciones docentes o de car&aacute;cter pedag&oacute;gico<label><br/>
        					</div>
        			</fieldset>
        			<fieldset >
        				<legend>060 Actividades cient&iacute;ficas y tecnol&oacute;gicas</legend>				
        					<div style="padding-left: 20px;">
        						010 Producci&oacute;n cient&iacute;fica</br>
        							<div style="padding-left: 20px;">
        								<label for="cod_060_010_010">010</label><input type="checkbox" name="cod_060_010_010" id="cod_060_010_010"/><label for="cod_060_010_010">Publicaciones, documentos cient&iacute;ficos y t&eacute;cnicos</label><br/>
        								<label for="cod_060_010_020">020</label><input type="checkbox" name="cod_060_010_020" id="cod_060_010_020"/><label for="cod_060_010_020">Trabajos presentados en congresos nacionales o internacionales</label><br/>
        								<label for="cod_060_010_030">030</label><input type="checkbox" name="cod_060_010_030" id="cod_060_010_030"/><label for="cod_060_010_030">Trabajos presentados en jornadas, seminarios, talleres de trabajo y/o cursos nacionales o internacionales</label><br/>
        								<label for="cod_060_010_040">040</label><input type="checkbox" name="cod_060_010_040" id="cod_060_010_040"/><label for="cod_060_010_040">Otras actividades de divulgaci&oacute;n</label><br/>								
        								<label for="cod_060_010_050">050</label><input type="checkbox" name="cod_060_010_050" id="cod_060_010_050"/><label for="cod_060_010_050">Estancias en centros de I+D+I p&uacute;blicos o privados</label><br/>
        							</div>
        						030 Otros m&eacute;ritos</br>
        							<div style="padding-left: 20px;">
        								<label for="cod_060_030_010">010</label><input type="checkbox" name="cod_060_030_010" id="cod_060_030_010"/><label for="cod_060_030_010">Ayudas y becas obtenidas</label><br/>
        								<label for="cod_060_030_020">020</label><input type="checkbox" name="cod_060_030_020" id="cod_060_030_020"/><label for="cod_060_030_020">Pertenencia a sociedad científicas y asociaciones profesionales</label><br/>							
        							</div>						
        					</div>
        			</fieldset>			
        		</div>			
        		</div>
        		<div style="clear: both"></div><br/>
        		<div style="float:left;border:0px solid black;"></label>
        			<input style="width: 200px;" type="text" placeholder="Buscar nombres o apellidos " name="nom_ape"/>
        			<input style="width: 150px;" type="text" placeholder="Buscar tel&eacute;fono " name="telefono"/>
        		</div>	
        		
        		<div style="clear: both"></div><br/>
        		<div style="float:left;"><input type="submit" value="Buscar"/></div>
        		<div style="float:left;"><input type="reset" value="Limpiar"/></div><br/><br/>
        		<br/><br/>
        	</form>
            </div>
            </div>
        </div>
  
</div>


<!--resultado de busqueda-->
<div id="listado" style="font-size: 10; font-family: Lucida Grande,Lucida Sans Unicode;">
</div>