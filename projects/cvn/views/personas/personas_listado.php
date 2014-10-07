<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script type="text/javascript" src="/cvn/public/js/si-custom.js"></script>
<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_listas.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_popups.css" rel="stylesheet"/>  
<style>
    .lista_personas{
        width: 100%;
        min-height: 20%;
        
        /*border-bottom: 1px solid #D4D4D4;*/
    }
    .lista_personas:hover{
        background-color: #ffd;
    }
    .lista_personas td{
       border: 1px solid #D4D4D4; 
    }
    .lista_personas_filas{
       /* border-bottom: 1px solid #f0f0f0;
       border-bottom: 1px solid #D4D4D4;*/
    }
    .lista_cabecera{
        width: 100%;
        overflow: visible;
        color: #0092C7;
       /* float: left;*/
        font-weight: normal;
        background: none repeat scroll 0 0 #F0FBFF;
    }
    .lista_cabecera td{
        height: 45px;
        border: 1px solid #C4EFFE;
        /*text-align:left;
        font-size: 10px;*/
    }
    .code_margin{
        padding-left: 2px;
        float: left;
        text-align: left;
    }
    .listado{
        font-size: 11px;
        margin-top: 14px;
    }
    .listado tr, .listado td{
        min-height: 30px;
        padding: 5px 5px;
    }
    .verMas{
        color: #148aaf;
        font-size: 17px;
        margin-left: 80%;
        font-style: normal;
        font-family: cursive;
    }
    #info_area_persona{
        position: fixed !important; 
        
        top: 50px !important;
    }
</style>


<div id="toolbar_totales_superior" class="toolbar_paginas">
	<?=$data['total_registros'];?>		
</div>

<?php if($data['paginacion']){?>	
	<div class='pagination'><?=$data['paginacion']?></div>
<?php } ?>

<!-- Listado Personas -->
<div>
    <table style="width:100%;border-collapse: collapse;float: left;" class="listado">
        <!-- Header -->
        <tr class="lista_cabecera" >
            <td style="width:11.5%; border-right:none;"><span style="margin: 0%;">Datos</span></td>
            <td style="width:3%;border-left: none; border-right:none;"> &nbsp;</td>
            <td style="width:5%;">&Aacute;reas</td>
            <!--<td style="width:13%;">Presentaci&oacute;n</td>-->
            <td style="width: 3%;">PDF</td>
            <td style="width: 4%;">Estado</td>
            <td style="width:7.2%;"><label style="font-size:9px; ">(0)</label><label>Datos de identificaci&oacute;n y contacto</label></td>
            <td style="width:7.2%;"><label style="font-size:9px;">(1)</label><label>Situaci&oacute;n profesional</label></td>
            <td style="width:7.2%;"><label style="font-size:9px;">(2)</label><label>Formaci&oacute;n acad&eacute;mica recibida</label></td>
            <td style="width:7.2%;"><label style="font-size:9px;">(3)</label><label>Actividad docente</label></td>
            <td style="width:7.2%;"><label style="font-size:9px;">(4)</label><span style="height: 43px; padding:1%; overflow: hidden;" title="Actividad en el campo de la sanidad">Actividad en el campo de la sanidad</span></td>
            <td style="width:7.2%;"><label style="font-size:9px;">(5)</label><span style="height: 43px; padding:1%; overflow: hidden;" title="Exp. cient&iacute;fica y tecnol&oacute;gica">Exp. cient&iacute;fica y tecnol&oacute;gica</span></td>
            <td style="width:7.2%;"><label style="font-size: 9px;">(6)</label><span style=" text-overflow: clip; height:40px; padding:1%; overflow:hidden;" title="Actividades cient&iacute;ficas y tecnol&oacute;gicas">Actividades cient&iacute;ficas y tecnol&oacute;gicas</span></td>
            <td style="width:4%;display:<?=$data['tiene_permiso']?' ': 'none'?>;"><span style=" text-overflow: clip; height:40px; padding:1%; overflow:hidden;" title="Acci&oacute;n">Acci&oacute;n</span>
            </td>
        </tr>
    	<?php foreach ($data['datos'] as $key => $value){
    	    $filas = $value['datos'];
    		$nombre 		= 	$filas['nombre'];
    		$apellidos 		= 	$filas['apellidos'];
    		$email 			= 	$filas['email'];
    		$movil 			= 	$filas['telefono_movil'];
    		$fijo 			= 	$filas['telefono_fijo'];
    		$presentacion 	= 	$filas['presentacion'];
    		$id_descarga 	= 	$filas['id'];
    		$estado_procesado	= 	$filas['estado_procesado'];			
        ?>        
        	<tr class="lista_personas">
                <!-- Datos -->
                <td style="width:13%;border-right:white">
                    <span class="persona" style="overflow: hidden; "><?=$apellidos.' '.$nombre;?></span>
                </td>
                <td style="width:5%;border-left:white">
                    <div id="buble_persona_info" style="float: left;" >
                        <a onclick="persona.datos.info_persona('persona_<?=$id_descarga?>',this);"><span class="tarjetaDirecciones"></span></a>
                        <div id="persona_<?=$id_descarga?>" style="display: none; width: 10%; position: absolute; left: 90%;">
                            <div class="bubble _top" style="min-height: 15p%; min-width: 225%;top: 10px;">
                                <b>Tel&eacute;fono m&oacute;vil: </b><?=$movil?><br />
                                <b>Tel&eacute;fono fijo: </b><?=$fijo?><br />
                                <b>Email: </b><?=$email?><br />
                            </div>
                        </div>
                    </div>
                    <!--<div id="slider_persona_presentacion" style="float: right;">
                        <a class="" onclick="personas.datos.presentacion('persona_<?//=$id_descarga?>',this);"><span class="persona"></span></a>
                        <div id="slider_persona_presentacion" style="max-height: 400px; overflow: hidden;"><?//=$presentacion?>
                        </div>
                    </div>-->
                            
                    <div id="buble_persona_presentacion" style="float: left;"><!--class="persona_seguimiento"-->
                        <a title="Presentaci&oacute;n" onclick="persona.datos.presentacion('persona_<?=$id_descarga?>_presentacion',this);"><span class="presentacion_persona"></span></a>
                        <div id="persona_<?=$id_descarga?>_presentacion" style="display: none; width: 20%; position: absolute; left: ; padding-top:4px;" class="">
                            <div class="bubble_presentacion_top" style="top: ;z-index: 1; left:28px;"></div>
                            <div class="bubble_presentacion" style="min-height: 15px; min-width: 263px;top: ;">
                                <div style="overflow:auto;max-height:120px; "><?=$presentacion?> </div>
                            </div>
                                                        
                        </div>
                    </div>
                </td>
                <!-- Areas -->
                <td style="width:9%;">
                        <?foreach ($value['areas'] as $k => $area) {						
        			      echo isset($area['descripcion'])? '-&nbsp;'.$area['descripcion']."<br/>": ' - ';
                          //echo "<a class='Area' onclick='persona.listado.toggleArea()'>&nbsp;</a>";
                          ?>
                          
                          
        		      <?}?>
                      <?echo isset($area['descripcion'])? "<a title='Ver m&aacute;s' class='verMas' onclick='persona.mostrarAreas.load($id_descarga)'>...</a>": ' ';?>
                      <div style="display: none;" id="areas_persona_<?=$id_descarga?>">
                      </div>
                </td>
                
                <!-- Presentacion -->
                <!--<td style="width:13%;">
                    <div style="height: 3.5em;width: 90%;overflow: hidden; float: left;" class="" title="<?//=$presentacion?>"><?//=$presentacion?></div>
                </td>-->
                <!-- PDF -->
                
                <td style="width: 3%; text-align:center">
                        <a target="_blank" href="/cvn/controllers/Personas.php?action=descargar&id=<?=$id_descarga?>"><span class="descargar"> </span></a>
                </td>
                <!-- ESTADO -->
                <td style="width: 4%;text-align:center">                    
                    <? switch ($estado_procesado) {
            				case '_PENDIENTE': 
                                $title = "Pendiente"; $span= "aviso"; break;
            				case '_PROCESADO': 
                                $title = "Procesado"; $span="aceptar"; break;
            				case '_ERROR': 	   
                                $title = "Error"; $span="cancelar"; break;				
            				default:break;
      			       }
                    ?>
                    <a title="<?=$title?>"><span class="<?=$span?>"></span></a>
                </td>                
                <!-- Columnas (0-6) -->                                 
                <? foreach ($value['codigos'] as $k => $codigo) {?>
                <td style="width:7.2%;">
                        <? foreach ($codigo as $i => $cod){
                        $d = explode(';', $cod);						
        				$code 	= $d[0];
        				$subcat = $d[1];
                        $fil =explode('.',$subcat);	
                        $detalle = $data['controllerPersona']->detalle_codigo($subcat);
                        ?>
                        <a class="code_margin" id="codigo_<?=$id_descarga?>_<?= $code?>" title="<?=$detalle?>" href="javascript:persona.info_codigo.load('<?=$id_descarga?>','<?=$subcat?>','<?=$fil[0]?>')"><?=$code?>&nbsp;</a>
                        <?}?>
                        <span id="codigo_info_busy_<?=$id_descarga?>_<?=$fil[0]?>" class="busy" style="display:none;"> </span>
   
                </td>                
                <?}?>
                <td style="width:4%;display: <?=$data['tiene_permiso']?' ':'none'?>;" >
                	<?php if($data['tiene_permiso']){?>                		
                        <a title="Eliminar" onclick="persona.vista_fila.eliminar('<?=$id_descarga?>');" ><span class="eliminar"> </span></a>
                        <span class="busy" id="eliminar_persona_<?=$id_descarga?>" style="display: none;"></span>        		
                	<?php } ?>
                </td>
            </tr>
        <?php } ?>							
    </table>
</div>

<!-- Contenedor Slider -->
<div id="infoPersonasSlide" class="slider_persona">
</div>

<?php if($data['paginacion']){?>	
	<div class='pagination'><?=$data['paginacion']?></div>
<?php } ?>
