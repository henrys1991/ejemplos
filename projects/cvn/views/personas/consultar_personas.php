<!DOCTYPE HTML>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="/cvn/public/js/generico.js"></script> 
<script type="text/javascript" src="/cvn/public/js/prototype.js"></script>
<script type="text/javascript" src="/cvn/public/js/effects.js"></script>  
<script type="text/javascript" src="/cvn/public/js/persona.js"></script>
<script type="text/javascript" src="/cvn/public/js/usuario.js"></script>
<script type="text/javascript" src="/cvn/public/js/codigo.js"></script> 
<script type="text/javascript" src="/cvn/public/js/si-custom.js"></script>  

<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_listas.css" rel="stylesheet"/> 
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
	
?>

<style>
.space label{
    vertical-align: text-top;
}
.menu_slider{
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #ccc;
    width: 173px;
    height: 20px;
    float:left;
    margin-bottom: 5px;
}
.menu_slider_down{
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #ccc;
    width: 16px;
    height: 20px;
    float:left;
    margin-right: 8px;
    margin-bottom: 5px;
}
.menu_slider_listado{
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #ccc;
    width: 191px;
    height: 132px;
    position:absolute;
    margin-left: -141px;
    margin-top: 8px;
}
.efecto_combo{
    float:right;
    border-left: 1px solid #ccc;
}

</style>

<div class="dialogo">
	<!--<h4>Búsqueda de personas:</h4>-->
	<!--<div class="pestanas"></div>-->
    <div class="botonera" >
           <div class="" style="float: left;" >            	
            	<a class="activo recoger" onclick="persona.index.recoger_busqueda(this, $('panelBusqueda'))"></a>
            	<span>B&uacute;squeda</span>
           </div>           
            <div class="" style="float: right;">
            	<span>Bienvenid@: <span class="usuario"></span><?=$_SESSION['username']?></span>&nbsp; |&nbsp; 
            	<a style="margin: 0 9px 0 0;" class="" onclick="usuario.autenticar.logout();">Cerrar sesi&oacute;n</a>                	
            </div>
    </div>

    <div id="panelBusqueda" class="botoneraSlider">
        <div class="mini_dialogo">
        	<form id="ConsultarPersonas" method="post" onsubmit="persona.listado.buscar(this,1); return false;" action="">
        		<div class="PanelIzquierdo">       		
            		<div style="clear: both"></div><br/>
            		<div style="margin-bottom: 12px;">
                        <table class="mini_formulario space">
                            <tr>
                                <th style="width: 35%;">Nombres:</th>
                                 <td style="width: 65%;"><input type="text" name="nom_ape"/></td>
                            </tr>
                             <tr>
                                <th style="width: 35%;">Apellidos:</th>
                                 <td style="width: 65%;"><input type="text" name="apellido"/></td>
                            </tr>
                            <tr>
                                <th style="width: 35%;">Teléfono:</th>
                                <td style="width: 65%;"><input type="text" name="telefono"/></td>
                            </tr>
                        </table>
            		</div>	
                    
                    <!--  Areas   -->
                    <fieldset >
            			<legend>Áreas de interés</legend>
                        <table class="mini_formulario space" style="margin-left:12px;margin-top: 7px; width:100%;">	
            				<tr>
                                <td style="width: 100%;">
                					<?php
                					foreach ($data['areas'] as $fila) {?>
                                    <div style="margin-right: 10px;">
                                    <div>
                                        <div class="menu_slider" >
    										<?
                                            $id 	= $fila['id'];
    	        							$desc 	= $fila['desc'];
                                            ?>
    	        							<input type="checkbox" class="area" id="area_<?=$id?>" name="area_<?=$id?>" value="<?=$desc?>" onclick="persona.formulario.habilitar(<?=$id?>)"/>
    	        							<span> <label for="area_<?=$id?>" style="vertical-align: text-top;color: rgb(0, 0, 0);"><?=$desc?></label></span>
                                        </div>                                         
                                        <div class="menu_slider_down"><a id="btnRecoger_<?=$id?>" style="padding-top: -10px;margin-left: 2px;margin-top: 3px; position: absolute;" class="dropDown" onclick="persona.formulario.cargarAreas(<?=$id?>,this)">&nbsp;</a> </div>
                                    </div>                                     
                                    <div style="display: none; " id="tipo_area_<?=$id?>" class="menu_slider_listado">
                                        <div style="overflow: auto; height: 127px;">
										<?php										 
										foreach ($fila['temas'] as $tema) {
											$idTema = 'tema_'.$tema['id'];
											$nombreTema = $tema['nombre'];?>
                                            <input type="checkbox" class="clase_tema_<?=$id?>" id="<?=$idTema.'_'.$id?>" name="<?=$idTema?>" value="<?=$nombreTema?>"/>
											<label for="<?=$idTema?>" style="vertical-align: text-top;"><?=$nombreTema?></label><br/>	
										<?}?>
                                        </div>
                                    </div> 
                                    </div>
									<?}?>									 
                					
                                </td>
            				</tr>
                         </table>
            		</fieldset>
                
        			<fieldset >
        				<legend>020 Formación académica recibida</legend>
                            <h3>010 Titulaci&oacute;n universitaria</h3>
            				<table class="mini_formulario space">
                                <tr>
                                    <td style="width: 11%;"><label for="cod_020_010_010">010</label><input type="checkbox" name="cod_020_010_010" id="cod_020_010_010"/></td >
                                    <td style="width: 89%;"><label for="cod_020_010_010" style="vertical-align: text-bottom;">Diplomados, licenciaturas e ingenier&iacute;as, grados y m&aacute;sters</label></td>
                                 </tr>
                                 <tr>
                                    <td style="width: 11%;"><label for="cod_020_010_020">020</label><input type="checkbox" name="cod_020_010_020" id="cod_020_010_020"/></td >
                                    <td style="width: 89%;"><label for="cod_020_010_020" style="vertical-align: text-bottom;">Doctorados</label></td>
                                </tr>
                            </table>
        			</fieldset>	
        			<fieldset >
        				<legend>050 Exp. científica y tecnol&oacute;gica</legend>				
        					<table class="mini_formulario space" >
                                <tr> 
                                    <td style="width: 11%;"><label for="cod_050_010">010</label><input type="checkbox" name="cod_050_010" id="cod_050_010"/></td><td style="width: 89%;"><label for="cod_050_010" style="vertical-align: text-bottom;">Participaci&oacute;n en grupos de investigaci&oacute;n</label></td>
                                </tr>
                                <tr>
                                    <td style="width: 11%;"><label for="cod_050_020">020</label><input type="checkbox" name="cod_050_020" id="cod_050_020"/></td><td style="width: 89%;"><label for="cod_050_020" style="vertical-align: text-bottom;">Actividad cient&iacute;fica o tecnol&oacute;gica</label></td>
                                </tr>                              
                            </table>
        			</fieldset>
    
        		</div>

        		<div class="PanelDerecho">
        			<fieldset>
        				<legend>030 Actividad Docente</legend>
                        	<table class="mini_formulario space">	
                                <tr>
                                    <td style="width: 11%;"><label for="cod_030_010">010</label><input type="checkbox" name="cod_030_010" id="cod_030_010"/></td><td style="width: 89%;" class="margen"><label for="cod_030_010" style="vertical-align: text-bottom;">Docencia impartida</label><br/></td>
            					</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_030_040">040</label><input type="checkbox" name="cod_030_040" id="cod_030_040"/></td><td style="width: 89%;" class="margen"><label for="cod_030_040" style="vertical-align: text-bottom;">Direcci&oacute;n de tesis doctorales</label><br/></td>
           						</tr>
                                <tr>
                                    <td style="width: 11%;"><label for="cod_030_050">050</label><input type="checkbox" name="cod_030_050" id="cod_030_050"/></td><td style="width: 89%;" class="margen"><label for="cod_030_050" style="vertical-align: text-bottom;">Tutor&iacute;a acad&eacute;mica de estudiantes</label><br/></td>	
            					</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_030_060">060</label><input type="checkbox" name="cod_030_060" id="cod_030_060"/></td><td style="width: 89%;" class="margen"><label for="cod_030_060" style="vertical-align: text-bottom;">Cursos y seminarios impartidos</label><br/></td>
            					</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_030_070">070</label><input type="checkbox" name="cod_030_070" id="cod_030_070"/></td><td style="width: 89%;" class="margen"><label for="cod_030_070" style="vertical-align: text-bottom;">Publicaciones docentes o de car&aacute;cter pedag&oacute;gico<label><br/></td>
                                </tr>	
                            </table>	
        			</fieldset>
                    
        			<fieldset >
        				<legend>060 Actividades cient&iacute;ficas y tecnol&oacute;gicas</legend>				
        					<h3>010 Producci&oacute;n cient&iacute;fica</h3>
                            <table class="mini_formulario space">
                                <tr>
                                    <td style="width: 11%;"><label for="cod_060_010_010">010</label><input type="checkbox" name="cod_060_010_010" id="cod_060_010_010"/></td><td style="width: 89%;" class="margen"><label for="cod_060_010_010" style="vertical-align: text-bottom;">Publicaciones, documentos cient&iacute;ficos y t&eacute;cnicos</label><br/></td>
    							</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_060_010_020">020</label><input type="checkbox" name="cod_060_010_020" id="cod_060_010_020"/></td><td style="width: 89%;" class="margen"><label for="cod_060_010_020" style="vertical-align: text-bottom;">Trabajos presentados en congresos nacionales o internacionales</label><br/></td>
                                </tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_060_010_030">030</label><input type="checkbox" name="cod_060_010_030" id="cod_060_010_030"/></td><td style="width: 89%;" class="margen"><label for="cod_060_010_030" style="vertical-align: text-bottom;">Trabajos presentados en jornadas, seminarios, talleres de trabajo y/o cursos nacionales o internacionales</label><br/></td>
   								</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_060_010_040">040</label><input type="checkbox" name="cod_060_010_040" id="cod_060_010_040"/></td><td style="width: 89%;" class="margen"><label for="cod_060_010_040" style="vertical-align: text-bottom;">Otras actividades de divulgaci&oacute;n</label><br/></td>								
   								</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_060_010_050">050</label><input type="checkbox" name="cod_060_010_050" id="cod_060_010_050"/></td><td style="width: 89%;" class="margen"><label for="cod_060_010_050" style="vertical-align: text-bottom;">Estancias en centros de I+D+I p&uacute;blicos o privados</label><br/></td>
				                </tr>
                            </table>
                            <h3>030 Otros m&eacute;ritos</h3>	
                            <table class="mini_formulario space">
                                <tr>  
 						            <td style="width: 11%;"><label for="cod_060_030_010">010</label><input type="checkbox" name="cod_060_030_010" id="cod_060_030_010"/></td><td style="width: 89%;" class="margen"><label for="cod_060_030_010" style="vertical-align: text-bottom;">Ayudas y becas obtenidas</label><br/>
        						</tr>
                                <tr>	
                                    <td style="width: 11%;"><label for="cod_060_030_020">020</label><input type="checkbox" name="cod_060_030_020" id="cod_060_030_020"/></td><td style="width: 89%;" class="margen"><label for="cod_060_030_020" style="vertical-align: text-bottom;">Pertenencia a sociedad científicas y asociaciones profesionales</label><br/>							
                                 </tr>						
        					</table> 
        			</fieldset>			
        		</div>        		
        		<div class="toolbarBusqueda">
                    <div style="float: left; width:50%" >
                		<input type="submit" value="Buscar"/><span> o </span>
                		<a onclick="Form.reset('ConsultarPersonas'); return false;" class="reset" type="reset" >Limpiar</a>
                        <span class="busy" id="busqueda_busy" style="display: none;">&nbsp;</span>
                        <br/><br/>
                    </div>
                    <div style="width:50%; float: left;  ">
                        <a style="float:right;height: 20px;margin-top: 5px;" class="accion xls" onclick="persona.listado.descargarExcel();">Generar Excel</a>
                    </div>
                </div>
        		
        	</form>
         </div>
    </div>
</div>


<!--resultado de busqueda-->
<div id="listado" class="lista_dinamica">
</div>