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

<!-- Paginacion-->
<?php if($data['paginacion']){?>	
	<div class='pagination'><?=$data['paginacion']?></div>
<?php } ?>

<!-- Listado -->
<div class="lista_dinamica" id="<?$opciones['id']?>"> 
    <!-- Lista Header -->
    <div class="lista_header" >
        <?php foreach ( $opciones['columnas'] as $columna => $opcion ) : ?>
    			<div <?=$opcion['atributos']?> class="">
    				<?=$opcion['titulo']?>
    			</div>
        <?php endforeach ?>
        <?php foreach ( $opciones['codigos'] as $columna => $opcion_codigos ) : ?>
    			<div <?=$opcion_codigos['atributos']?> class="">
    				<?=$opcion_codigos['titulo']?>
    			</div>
        <?php endforeach ?>
    </div>
    
    <!-- Contenedor Slider -->
    <div id="infoPersonasSlide" class="">
    </div>
    
    <!-- Vista fila -->
    <div id="<?$opciones['id']?>_filas">
        <?php foreach ( $data['datos']->items as $Persona ) :  ?>
			<div class="lista_nodo" id="persona_<?=$Persona['datos']['id']?>">
				<? $controllerPersona->vista_fila($Persona['datos']['id']);?>
			</div>
	   <?php endforeach ?>
    </div> 
</div>

<?php if($data['paginacion']){?>	
	<div class='pagination'><?=$data['paginacion']?></div>
<?php } ?>
