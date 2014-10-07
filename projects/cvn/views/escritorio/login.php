<!DOCTYPE HTML>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="/cvn/public/js/generico.js"></script> 
	<script type="text/javascript" src="/cvn/public/js/prototype.js"></script> 
	<script type="text/javascript" src="/cvn/public/js/usuario.js"></script>
	
   <link type="text/css" href="/cvn/public/css/si_formularios.css" rel="stylesheet"/>   
    <link type="text/css" href="/cvn/public/css/si_botoneras.css" rel="stylesheet"/>  
    <link type="text/css" href="/cvn/public/css/si_common.css" rel="stylesheet"/>
    <link type="text/css" href="/cvn/public/css/si_iconos.css" rel="stylesheet"/>
    <link type="text/css" href="/cvn/public/css/si_boxes.css" rel="stylesheet"/>
</head>
<style>
    .login_dialogo {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #CCCCCC;
    margin: 60px auto 20px;
    min-width: inherit;
    padding: 10px;
    width: 358px;
}
form{
    padding: 15px;
}
table td input[type=text] {
    width:84%;
}
table td input[type=password]{
    width:84%;
}

table .miniformulario td .mensaje{
    color:#red;
}
.mensaje{
    color: #FF0000;
}

</style>
<body>
	<div class='login_dialogo' align="center" style="margin-top: 50px;">			
			<form id="login" method="post" onsubmit="usuario.autenticar.login(this); return false;" action="" >		
				<table class="mini_formulario">
					<tr>
						<th>Usuario:</th>
						<td><input type="text" id="user" name="user" style="width: 84%;"/><span class="obligatorio" style="vertical-align: text-bottom;"> </span></td>
					</tr>
					<tr>
						<th>Contrase&ntilde;a:</th>
						<td><input type="password" id="password" name="password"/><span class="obligatorio" style="vertical-align: text-bottom;"> </span></td>
					</tr>
                    <tr>
                        <th> </th>
	    				<td class="mensaje">
	    					<div id='mensaje' class='mensaje' style="display: none;"><br /></div>
	    				</td>			
	    			</tr>
				</table>
                <table style="width:100%;" class="mini_formulario">
                    <tr>
						<td class="buttons">
                            <input style="margin-left: 105px; margin top: 15px;" type="submit" value="Entrar" /> o <a class="reset" onclick="Form.reset('login'); return false;">Limpiar</a>
                            <span id="login_busy" class="busy" style="display:none;"> </span>
                        </td> 
					</tr>
                </table>		
			</form>
           
	</div>
</body>