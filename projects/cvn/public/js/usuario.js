var usuario = {	
	autenticar:{
		load:function(data, container){
			container.innerHTML = $data;
		},
		login:function(form){			
			url = data.url+ 'controllers/Usuarios.php?action=login';			
			container = $('mensaje');
			var params = Form.serialize(form);
            $('login_busy').toggle();
			new Ajax.Request(url, {
				method: 'post', 
				parameters: params,
				onSuccess: function(response) {	
				    
					//alert(response.responseText);			
					if(response.responseText.length > 0){	
						var res = response.responseText.evalJSON();	
						if(res.status){
							location.href ="/cvn/personas/consultar";
                            
						}else{
							usuario.autenticar.mostrar_mensaje(res.mensaje, container);	
                            
						}						
					}
				},
				onFailure: function(response){
					var res = response.responseText.evalJSON();
					usuario.autenticar.mostrar_mensaje(res.mensaje, container);  
			    }
			});
		},	
		logout:function(){
			url = data.url + 'controllers/Usuarios.php?action=logout';
			new Ajax.Request(url, {
				onSuccess: function(response) {
					//alert(response.responseText);
					if(response.responseText.length > 0){	
						var res = response.responseText.evalJSON();	
						if(res.status){
							location.href ="/cvn/login";
						}else{
							alert(res.mensaje);	
						}						
					}
				},				
			});
		},
		mostrar_mensaje:function(msj, container){
            Element.update(container, msj);
            Element.toggle(container);
            Element.toggle('login_busy');               
            setTimeout(function(){Element.toggle(container);}, 3000);               
		},
			
	},	
};

