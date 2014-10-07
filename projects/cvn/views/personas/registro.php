<!DOCTYPE HTML>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="/cvn/public/js/generico.js"></script> 
	<script type="text/javascript" src="/cvn/public/js/prototype.js"></script> 
	<script type="text/javascript" src="/cvn/public/js/persona.js"></script>  
	<link type="text/css" href="/cvn/public/css/registro.css" rel="stylesheet"/>
    <link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>  
    <link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
    <link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
    <link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
    <link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/>
</head>
<body>	
	<style>
        .obligatorio{
            font-size: 12px;
        }
        .pdf{
            font-size: 10px;
            width:70%;
        }
        .mini_formulario th{
            width: 25%;
        }
        .mini_formulario td{
            width: 75%;
        }
    </style>
    
    <div class="DashboardWidget box" style="margin: 10px 30px 10px 10px; width: 529px; border-bottom: medium none;">
	   <div class="headerArea ">
            <div class="headerLeft"><span class="persona_editar">Registro</span></div>
        </div>
    </div>
    <!--<div class="botoneraSlider">-->
        <div class="mini_dialogo" style="background: none repeat scroll 0 0 rgba(0, 0, 0, 0); clear:both; width:509px; margin: -14px 30px 0 10px;">
    	   <div class="panelRight">
    		<!--<form method="POST" action="__bd_conectar_bd.php" enctype="multipart/form-data" onsubmit="return persona.formulario.validar(this);return false;">-->
    		<form method="POST" action="/cvn/controllers/Personas.php?action=guardar" enctype="multipart/form-data" onsubmit="return persona.formulario.validar(this);return false;">
    		<div class="dialogoEdicionAvanzada">
                <table class="mini_formulario" style="width: 110%;">
        			<tr>
        				<th><label>Nombres:</label></th>
        				<td><input type="text" id="nombre" name="nombre"/><span class="obligatorio"></span></td>
        			</tr>
        			<tr>
        				<th><label>Apellidos:</label></th>
        				<td><input type="text" id="apellido" name="apellido"/><span class="obligatorio"></span></td>
        			</tr>
        			<tr>
        				<th><label>E-mail:</label></th>
        				<td><input type="mail" id="mail" style="width:69.5%" name="mail"/><span class="obligatorio"></span></td>
        			</tr>
        			<tr>
        				<th><label>Tel&eacute;fono m&oacute;vil:</label></th>
        				<td><input type="text" id="movil" name="movil"/></td>
        			</tr>		
        			<tr>
        				<th><label>Tel&eacute;fono fijo:</label></th>
        				<td><input type="text" id="fijo" name="fijo"/></td>
        			</tr>
        			<tr>
        				<th><label>PDF:</label></th>
        				<!--<td><input type="file" accept="application/pdf|*.pdf|MIME_type" id="pdf" name="pdf"/></td>-->
        				<td><input class="pdf" type="file" accept=".pdf;*.pdf;application/pdf;" onchange="persona.formulario.validarArchivo(this)" id="pdf" name="pdf"/>
                        <span class="obligatorio"></span>
                        </td>
        			</tr>	
        			<tr>
        				<th><label>&Aacute;reas:</label></th>
        				<td>
                            
        					<div id='contenedorAreas' name="contenedorAreas" style="float:left; width:16%">
        						<?php
        						foreach ($data['areas'] as $fila) {
									$id 	= $fila['id'];
        							$desc 	= $fila['desc'];
        							echo "<input type='checkbox' class='area' id='area_$id' name='area_$id' value='$desc'/>";
        							echo "<label for='area_$id'>$desc</label><br/>";
								}
        						?>
                            
        					</div>
                            <span style="float:left; margin: 4px 0 0 -17px;"class="obligatorio">&nbsp;</span>
        				</td>
        			</tr>
        			<tr>
        				<th><label>Presentaci&oacute;n:</label></th>
        				<td><textarea name="presentacion" rows="8" placeholder="Max. 1000 caracteres" style="width:70%; resize: none;"></textarea>
                        <span class="obligatorio"></span>
                        </td>
        			</tr>			
        			<tr>
        				<td colspan="2">
                            <div  style="margin-left: 138px;">
            					<input type="submit" text="Enviar" value="Enviar"/>
            					<input type="reset" text="Limpiar" value="Limpiar"/>
                            </div>
        				</td>
        			</tr>
        			<tr>
        				<td colspan="2">
        					<div id='mensaje' class='mensaje' style="display: none;"></div>
        				</td>			
        			</tr>
        		</table>
            </div>
    	</form>
    	</div>
     <!--</div>-->
     </div>
        
	</div>
</body>
