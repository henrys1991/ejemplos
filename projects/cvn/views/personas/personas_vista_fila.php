<script type="text/javascript" src="/cvn/public/js/si-custom.js"></script>
<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_listas.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_popups.css" rel="stylesheet"/> 

<?php $columnas = $opciones['columnas']; $columnas_codigos = $opciones['codigos']; ?>

<div class="lista_nodo_fila" id="nodo_persona_<?=$id;?>">
    
    <!-- Datos -->
    <?php if ( $columnas['datos'] ) { ?>
        <div <?=$columnas['datos']['atributos']?> class="">
    		<div style="float:left; width:77%">
                <span class="persona"> </span><?=$apellidos =$nombre;?>
                
                <div id="buble_persona_info" style="float: left;">
                    <span class="accion tarjetaDirecciones" onclick="persona.datos.info_persona('persona_<?=$id?>',this);"> </span>
                    <div id="persona_<?=$id?>" style="display: none; width: 250px; position: absolute; left: 90px;">
                        <div class="bubble _top" style="min-height: 15px; min-width: 225px;">
                            <b>Tel&eacute;fono m&oacute;vil: </b><?=$movil?><br />
                            <b>Tel&eacute;fono fijo: </b><?=$fijo?><br />
                            <b>Email: </b><?=$email?><br />
                        </div>
                    </div>
                </div>
             </div>
    	</div>
	<?php } ?>
    
    <!-- Areas -->
    <?php if ( $columnas['areas'] ) { ?>
        <?foreach ($Persona['areas'] as $k => $area) {						
		      echo isset($area['descripcion'])? $area['descripcion']."<br/>": ' - ';
        }?>
    <?php } ?>
    
    <!-- Presentacion -->
    <?php if ( $columnas['presentacion'] ) { ?>
        <div style="height:3.5em;width: 14em;overflow:hidden;" class="" title="<?=$presentacion?>"><?=$presentacion?></div>
    <?php } ?>
    
    <!-- PDF -->
    <?php if ( $columnas['pdf'] ){ ?>
        <a target="_blank" href="/cvn/controllers/Personas.php?action=descargar&id=<?=$id?>"><span class="descargar"> </span></a>
    <?php } ?>
    
    <!-- Columnas 0-6 -->
    <?php foreach ( $opciones['codigos'] as $columna => $titulo_codigos ) : ?>
        
        
    <?php endforeach ?>
</div>
