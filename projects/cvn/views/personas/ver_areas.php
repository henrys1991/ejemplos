<link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
<link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_apps.css" rel="stylesheet"/> 
<link type="text/css" href="/cvn/public/css/si_listas.css" rel="stylesheet"/>
<link type="text/css" href="/cvn/public/css/si_popups.css" rel="stylesheet"/>
<script type="text/javascript" src="/cvn/public/js/si-custom.js"></script>
<style>
.titulo_areas{
    font-size: 13px;
    margin: 15px 0 8px 16px;
}

table.formulario th {
    width:208px;
}
</style>

<h3 class="titulo_areas">&Aacute;reas de Inter&eacute;s</h3>
<table class="formulario" style="margin: 5px 0 5px 16px; width:94%;">
    <?foreach ($data['areas'] as $area){?>
    <tr>
        <th style="font-weight: bold;"><?echo $area['area_nombre']?></th>
        <td>
	        <?foreach ($area['temas'] as $tema){
	             echo $tema['tema_nombre'] . '<br />';
	        }?>
        </td>
    </tr>
    <?}?>
</table>