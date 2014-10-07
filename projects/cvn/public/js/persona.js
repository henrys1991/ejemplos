var persona = {	
     formulario:{
        cargarAreas:function(id, trigger){            
            popup= 'tipo_area_' + id;
            Element.toggleContext(popup,  {trigger: trigger,offsetTop: 12, offsetLeft: -35, hideOnClick: false, hideOnEsc: false});
            if($('btnRecoger_'+id).className == 'dropDown'){$('btnRecoger_'+id).className = 'dropUp';}else $('btnRecoger_'+id).className = 'dropDown';
            
            //persona.formulario.recoger_catalogo();
        },
        habilitar: function(idArea){
            //var listado = document.getElementsByName(idArea);
            var listado = document.getElementsByClassName('clase_tema_'+idArea);
            for(var i=0; i< listado.length; i++){
                listado[i].checked = $('area_'+ idArea).checked;
                
            }
        },
        /*recoger_catalogo: function(element, container){
            if(element.className.indexOf('expandir') != -1)
                    element.className = 'activo recoger';
                else
                    element.className = 'activo expandir';
                Element.toggle(container);
        },*/
        /*activarCatalogo: function(id){
            
            $(id).checked = true;
        }*/
	    
		validar:function(form){			
			var flag=true;
			var mensaje = "";
			var elementos = new Array();
			//validar nombre
			valor = form.nombre.value;						
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor) ) {
				flag = false;
				mensaje+= '[Error] Campo obligatorio (Nombre) <br/>';
				elementos.push(form.nombre);				
				//persona.formulario.mostrarMensajeError('[Error] Campo obligatorio (Nombre)',form.nombre);				
				//return false;			  	
			}
			
			//validar apellido
			valor = form.apellido.value;						
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor) ) {
				flag = false;
				mensaje+= '[Error] Campo obligatorio (Apellido) <br/>';
				elementos.push(form.apellido);				
				//persona.formulario.mostrarMensajeError('[Error] Campo obligatorio (Apellido)',form.apellido);				
				//return false;			  	
			}			
			
			//validar email
			valor = form.mail.value;
			expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;    					
			//if(!(/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)/.test(valor))) {				
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor) || !expr.test(valor)) {
				flag=false;
				mensaje+= '[Error] Campo invalido (Email). El valor ingresado no es un e-mail <br/>';
				elementos.push(form.mail);
				//persona.formulario.mostrarMensajeError('[Error] Campo invalido (Email). El valor ingresado no es un e-mail',form.mail);				
				//return false;			  	
			}
			
			//validar telefono movil
			valor = form.movil.value;
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor)) {
				flag=true;
			}else{
				//if( !(/^\d{10}$/.test(valor)) ) {
				if( isNaN(valor) ) {			
					flag=false;
					mensaje+= '[Error] Campo invalido (Telefono movil). El valor ingresado no es un numero de telefono <br/>';
					elementos.push(form.movil);
					//persona.formulario.mostrarMensajeError('[Error] Campo invalido (Telefono movil). El valor ingresado no es un numero de telefono',form.movil);				
					//return false;			  	
				}	
			}	
		
			//validar telefono fijo		
			valor = form.fijo.value;
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor)) {
				flag=true;
			}else{
				//if( !(/^\d{10}$/.test(valor)) ) {
				if( isNaN(valor) ) {
					flag=false;
					mensaje+= '[Error] Campo invalido (Telefono fijo). El valor ingresado no es un numero de telefono <br/>';
					elementos.push(form.fijo);			
					//persona.formulario.mostrarMensajeError('[Error] Campo invalido (Telefono fijo). El valor ingresado no es un numero de telefono',form.fijo);				
					//return false;			  	
				}	
			}
			
			//validar pdf			
			valor = form.pdf.value;									
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor) ) {
				flag=false;
				mensaje+= '[Error] Campo obligatorio (PDF) <br/>';
				elementos.push(form.pdf);
				
				//persona.formulario.mostrarMensajeError('[Error] Campo obligatorio (PDF)',form.pdf);				
				//return false;			  	
			}
			
			//validar areas
			var areas = document.getElementsByClassName('area');
			var flagArea = false;
			for(var i=0;i<areas.length;i++){
				if(areas[i].checked){
					flagArea = true;break;
				}
			}
			if(!flagArea){
				flag=false;
				mensaje+= '[Error] Seleccione al menos una area <br/>';
				elementos.push(document.getElementById('contenedorAreas'));	
			}						
	
			//validar campo presentacion
			valor = form.presentacion.value;						
			if( valor == null || valor.length == 0 || /^\s+$/.test(valor) ) {
				flag=false;
				mensaje+= '[Error] Campo obligatorio (Presentacion) <br/>';
				elementos.push(form.presentacion);
				//persona.formulario.mostrarMensajeError('[Error] Campo obligatorio (Presentacion)',form.presentacion);				
				//return false;			  	
			}			
			if(!flag){
				persona.formulario.mostrarMensajeError(mensaje,elementos);
				return false;
			}										
			return flag;		
		},
		mostrarMensajeError:function(msj,elementos){			
			var contenedor = $('mensaje');			
            Element.update(contenedor, msj);
            Element.toggle(contenedor);            
            for(var i=0;i<elementos.length;i++){
            	var color = elementos[i].style.border;
            	elementos[i].style.border="1px solid #FF0000";
            	//var color = elemento.style.border;
            	//elemento.style.border="1px solid #FF0000";	
            }            
            setTimeout(function(){Element.toggle(contenedor);
            	 	for(var i=0;i<elementos.length;i++){
            	 		elementos[i].style.border=color;
            	 		//elemento.style.border=color;
            	 	}
            	}, 3000);                           
            //Effect.FadeInTime(contenedor);            
		},
		validarArchivo:function(inputFile){
			var elementos = new Array(inputFile);
			nameFile = inputFile.value;			
			data = nameFile.split('.');
			nroElementos = data.length;
			extension = data[nroElementos-1];
			if(extension!='pdf'){
				persona.formulario.mostrarMensajeError('Solo se permiten archivos con extension .pdf ', elementos);
				inputFile.value='';
			}		
		}
	},
	listado:{
		load:function(data, container){		  
			container.innerHTML = data;
		},
        toggleBusy: function(){
            Element.toggle('busqueda_busy');
        },
        toggleAreas: function(){
            
        },
		buscar:function(form,page){
			//url = data.url + 'personas_listado.php';
			//url = data.url + 'views/persona/personas_listado.php';
			url = data.url + 'controllers/Personas.php?action=buscar&pag='+page;
			container = $('listado');
			params = Form.serialize(form);
			$('busqueda_busy').toggle();			
			new Ajax.Request(url, {
				method: 'post', 
				parameters: params,
				evalScripts: true,                
				onSuccess: function(response) {					
					if(response.responseText.length > 0){						
						persona.listado.load(response.responseText, container);
						$('busqueda_busy').toggle();
					}
				},
				onFailure: function(response){	
    					alert(response.responseText);  
			    }
			});	
        },	
        paginacion:function(idForm,pag){        	
        	var form = $(idForm);
        	persona.listado.buscar(form,pag);        	
        },
        descargarExcel:function(){        				
			url = data.url + 'controllers/Personas.php?action=descargar_excel';
			var form = $('ConsultarPersonas'); 			
			params = Form.serialize(form);
			
			//$('busqueda_busy').toggle();			
			new Ajax.Request(url, {
				method: 'post', 
				parameters: params,                
				onSuccess: function(response) {
					var respuesta =  response.responseText.evalJSON();															
					if(response.responseText.length > 0){						
						//persona.listado.load(response.responseText, container);						
						location.href = respuesta.url;
						//alert(response.responseText);
						//$('busqueda_busy').toggle();
					}
				},
				onFailure: function(response){	
    					alert(response.responseText);  
			    }
			});	
        }
        
	},
	vista_fila:{
		eliminar:function(idPersona){
			//var nombre = prompt('Introduce tu nombre','[ nombre del usuario ]');
			//alert(nombre);
			$('eliminar_persona_'+idPersona).toggle();
			if(confirm('¿Está seguro de eliminar este registro?')){
				url = data.url + 'controllers/Personas.php?action=eliminar';
				params = {'id_persona':idPersona};							
				new Ajax.Request(url, {
					method: 'post', 
					parameters: params,                
					onSuccess: function(response) {
						var respuesta =  response.responseText.evalJSON();	
						if(response.responseText.length > 0){													
							var form = $('ConsultarPersonas'); 
							persona.listado.buscar(form,1);//refrescar vista							
							$('eliminar_persona_'+idPersona).toggle();
						}
					},
					onFailure: function(response){	
	    					alert(response.responseText);  
				    }
				});		
			}else{
				$('eliminar_persona_'+idPersona).toggle();
			}			
		}
	},	
	popup:{		
		load: function(idPersona, subcat){
			codigos = listaCodigo.listado.get();			
			cod = codigos[subcat];
            alert(cod);
            return cod;
		//	if(cod!=null){
				//content.innerHTML='<b>Descripci&oacute;n (click para ver más...)</b><br/>'+cod;
		//	}			
		},
        /*load: function(content, idPersona, subcat){
			codigos = listaCodigo.listado.get();			
			cod = codigos[subcat];
			if(cod!=null){
				content.innerHTML='<b>Descripci&oacute;n (click para ver más...)</b><br/>'+cod;
			}			
		},*/		
		mostrarInfoCode:function(id, idPersona, subcat){
			popup = persona.popup.crear(id);
			persona.popup.load(popup, idPersona, subcat);
		},
		mostrarInfoPresentacion:function(id, idPersona, data){			
			var p = persona.popup.crear(id);			
			p.innerHTML = '<b>Presentaci&oacute;n</b><br/>'+data;
		},
		crear:function(id){
			var popup = document.createElement('div');
			popup.id = 'popup'+id;
			popup.className = 'popup';
			ancho = document.getElementById(id).offsetLeft;
			alto = window.document.getElementById(id).offsetTop;		
			popup.style.left = ancho-228+'px';
			popup.style.top = alto+'px';
            popup.style.position= absolute;			
			document.body.appendChild(popup);
			return popup;			
		},			
		eliminar:function(id){
			var elemento = $('popup'+id); 
			elemento.parentNode.removeChild(elemento);			
		}	
	},
	datos:{
		load:function(idPersona, subcat){
			//window.open("__consultar_datos_personas.php?id="+idPersona+"&subcat="+subcat,"windowDatosPersonas", "status=1,width=700,height=500, scrollbars=1,");
			//window.open("__consultar_datos_personas.php?id="+idPersona+"&subcat="+subcat,"windowDatosPersonas", "status=no,width=700,height=500, scrollbars=1,resizable=no,titlebar=no,menubar=no,location=no,dialog=yes,");
			var url = data.url + "controllers/Personas.php?action=buscar_detalle&id="+idPersona+"&subcat="+subcat;
			window.open(url,"windowDatosPersonas", "width=700,height=500, menubar=no, toolbar=no, dialog=yes, dependent=yes, scrollbars=yes,resizable=no,titlebar=no,location=no");			
			//window.open("__consultar_datos_personas.php?id="+idPersona+"&subcat="+subcat,"windowDatosPersonas", "width=700,height=500, menubar=no, toolbar=no, dialog=yes, dependent=yes, scrollbars=yes,resizable=no,titlebar=no,location=no");
			
			//window.open("__consultar_datos_personas.php?id="+idPersona+"&subcat="+subcat);
		},
        info_persona: function(personaId, trigger){
			try{Element.hide('personaId');}catch(e){}
			var popup = personaId;
			Element.toggleContext(popup, {trigger: trigger,offsetTop: 12, offsetLeft: -35, hideOnClick: true, hideOnEsc: true});
            
        },
        presentacion: function(personaId, trigger){
                 
            try{element.hide(personaId);} catch(e){}
            var popup = personaId;
            Element.toggleContext(popup, {trigger: trigger, offsetTop: 12, offsetLeft: -35, hideOnclick: true, hideOnEsc: true});
            
            /*var url = data.url + "controllers/Personas.php";
            var container = $('slider_persona_presentacion');
            window.showDialog(container, {
                url: url,
                id: 'persona_presentacion'
                width: 300,
                top: 400,
                autoHeight: true,
                onComplete: function(){
                    
                }
                
            });*/
        }
        
	},
    
    info_codigo:{
    	load:function(idPersona, subcat, fil){
            var url = data.url + "controllers/Personas.php?action=buscar_detalle&id="+idPersona+"&subcat="+subcat;
            var container = $('infoPersonasSlide');
            //alert('codigo_info_busy_'+ idPersona +'_'+ fil);
            $('codigo_info_busy_'+idPersona+'_'+fil).toggle();            
            window.showDialog(container, {
                    url: url,
                    id: 'info_codigo_persona',
                    width: 600,
                    //height: 385,
                    containerClassName: 'class_prueba',
                    autoHeight: true,
                    onComplete: function(){                      
                        //container.show();
                        $('codigo_info_busy_'+ idPersona +'_'+ fil).toggle();                       
                    },
    		}); 
    	},
    },
    
    mostrarAreas:{
    	load:function(idPersona){
            var url = data.url + "controllers/Personas.php?action=mostrar_areas&id="+idPersona;
            var container = $('infoPersonasSlide');          
            window.showDialog(container, {
                    url: url,
                    id: 'info_area_persona',
                    width: 600,
                    //height: 385,
                    fixed: false,
                    autoHeight: true,
                    onComplete: function(){                      
                        //container.show();                 
                    },
    		}); 
    	},
    },
    	
     
    Slider:{
    show: function(){
    	element_id: 'infoPersonasSlide',
    	$(element_id).show();
    },
    },
    
    index:{
        recoger_busqueda: function(element, container){
            if(element.className.indexOf('expandir') != -1)
                    element.className = 'activo recoger';
                else
                    element.className = 'activo expandir';
                Element.toggle(container);
        },
    },
	
};
