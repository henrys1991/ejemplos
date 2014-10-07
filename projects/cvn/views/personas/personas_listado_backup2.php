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
        border-bottom: 1px solid #f0f0f0;
        overflow: visible;
        float:left; 
        padding: 4px 0;
    }
    .lista_personas:hover{
        background-color: #ffd;
    }
</style>
<?php if($data['paginacion']){?>	
	<div class='pagination'><?=$data['paginacion']?></div>
<?php } ?>

<div class="lista_header" style="text-align: left; font-size: 11px;margin-top:13px; ">
    <div style="float: left; width: 15%; padding: 0 0 0 8px;">Datos</div>
    <div style="float: left; width: 4%; ">&Aacute;reas</div>
    <div style="float: left; width: 17%; padding: 0 9px;">Presentaci&oacute;n</div>
    <div style="float: left; width: 3%; ">PDF</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(0) </label>Datos de identificaci&oacute;n y contacto</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(1) </label>Situaci&oacute;n profesional</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(2) </label>Formaci&oacute;n acad&eacute;mica recibida</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(3) </label>Actividad docente</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(4) </label>Actividad en el campo de la sanidad</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(5) </label>Exp. cient&iacute;fica y tecnol&oacute;gica</div>
    <div style="float: left; width: 8%; "><label style="font-size: 9px;">(6) </label>Actividades cient&iacute;ficas y tecnol&oacute;gicas</div>
</div>
    <div id="infoPersonasSlide" style="" class="">
    </div>
<div>
    <table style="clear:both; font-size: 11px; width:100%; padding: 4px 0;">
    	<?php		
    	foreach ($data['datos'] as $key => $value) {							
    		echo "<tr class='lista_personas'>";
    		$filas = $value['datos'];
    		$nombre 		= 	$filas['nombre'];
    		$apellidos 		= 	$filas['apellidos'];
    		$email 			= 	$filas['email'];
    		$movil 			= 	$filas['telefono_movil'];
    		$fijo 			= 	$filas['telefono_fijo'];
    		$presentacion 	= 	$filas['presentacion'];
    		$id_descarga 	= 	$filas['id'];
                    
    		echo "<td style='float:left;text-align:left; width:15%;padding-left: 5px;'>";
            
            echo "<div style='float:left; width:77% '>";
            echo "<span class='persona'> </span>";            
    		echo "$apellidos $nombre<br/>";
    		echo "</div>";
            
            echo "<div id='buble_persona_info' style='float:left'>";
            echo "<span class=' accion tarjetaDirecciones' onclick='persona.datos.info_persona(\"persona_$id_descarga\",this);'> </span>";
            echo "<div id='persona_$id_descarga' style='display:none;width: 250px;position:absolute;left: 90px;'>";
            echo "<div class='bubble _top' style='min-height: 15px; min-width: 225px;'>";
            /*buble _top left: 91px; top: 575px;*/
            echo "<b>Tel&eacute;fono m&oacute;vil:</b> $movil<br/>";
    		echo "<b>Tel&eacute;fono fijo:</b> $fijo<br/>";
    		echo "<b>Email:</b> $email<br/>";
            echo "</div>";
            
            echo "</div>";
            echo "</div>";
    		
            echo "</td>";
    		echo "<td style='float:left;text-align:left; width:5%;'>";									
    		foreach ($value['areas'] as $k => $area) {						
    			echo isset($area['descripcion'])? $area['descripcion']."<br/>": ' - ';
    		}				
    		
    		/* PRESENTACION*/
            echo "<td style='float:left;text-align:left;width:17%;'>";
            /*$maxCaracteres = 50;				
    		if(strlen($presentacion)>$maxCaracteres){					
    			$eventMousePresentacion = "onmouseover='persona.popup.mostrarInfoPresentacion(this.id, $id_descarga,\"$presentacion\")' onmouseout='persona.popup.eliminar(this.id)'";
    			$presentacion = substr($presentacion, 0, $maxCaracteres)."<a style='clear:both;' class='enlaceCodigo' href='#' id='presentacion_".$id_descarga."' ".$eventMousePresentacion.">...</a>";			 
    		}*/
    		echo "<div style='height: 3.5em;width: 14em;overflow: hidden;' class='' title='$presentacion'>";
    		echo $presentacion;
    		echo "</div></td>";
    		echo "<td style='float:left;width:3%;'><a target='_blank' href='/cvn/controllers/Personas.php?action=descargar&id=$id_descarga'><span class='descargar'> </span></a></td>";
    		foreach ($value['codigos'] as $k => $codigo) {
    			echo "<td style='float:left;width:8%;padding-left:2px;'>";
    			foreach ($codigo as $i => $cod) {												
    				$d = explode(';', $cod);						
    				$code 	= $d[0];
    				$subcat = $d[1];
                    $fil =explode('.',$subcat);	
                    //echo "<a id='codigo_".$id_descarga."_".$cod."' onmouseover='persona.popup.mostrarInfoCode(this.id,".$filas['id'].", \"".$subcat."\")' onmouseout='persona.popup.eliminar(this.id);' href='javascript:persona.info_codigo.load(".$id_descarga.",\"".$subcat."\")'>".$code."</a>&nbsp;&nbsp;&nbsp;";
                    $detalle = $data['controllerPersona']->detalle_codigo($subcat);
                    echo "<a id='codigo_".$id_descarga."_".$code."' title='$detalle' href='javascript:persona.info_codigo.load(".$id_descarga.",\"".$subcat."\",\"".$fil[0]."\")'>".$code."</a>&nbsp;&nbsp;&nbsp;";
                }
                echo "<span id='codigo_info_busy_".$id_descarga."_".$fil[0]."' class='busy' style='display: none;'> </span>";
    			echo "</td>";
    		}			
    		echo "</tr>";
    	}
    	?>	
        <script>
            
        </script>	
    </table>
</div>

<?php if($data['paginacion']){?>	
	<div class='pagination'><?=$data['paginacion']?></div>
<?php } ?>


