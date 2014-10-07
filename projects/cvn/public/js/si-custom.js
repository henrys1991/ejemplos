/*  Extensiones al Prototype JavaScript framework, version 1.7
 *  (c) 2005-2011 Rafael Nevarez
 *
 *--------------------------------------------------------------------------*/

/* Form
*-------------------------------------------------------------------------- */
Object.extend(Form, {

	// Descontinuada
    serializeEscaped: function(form) {
	 var elements = Form.getElements($(form));
    var queryComponents = new Array();
    
    for (var i = 0; i < elements.length; i++) {
      var queryComponent = Form.Element.serializeEscaped(elements[i]);
      if (queryComponent)
        queryComponents.push(queryComponent);
    }
    
    return queryComponents.join('&');
    },
  
	// Descontinuada
    chequearCamposObligatorios: function (form, textoAdvertencia){
		try{Form.clearErrors(form);} catch(e){};
		var elements = document.getElementsByClassName('obligatorio', form);
		var validForm = true;
		elements.each(function(container){
			if ( container.firstChild.value == '' ){
				new Insertion.After(container, textoAdvertencia);
				new Effect.Highlight(container.nextSibling);
				validForm = false;
			}													 
		});
		return validForm;
    },
  
	// Descontinuada
    eliminarMensajesAdvertencia: function (element, className){
		var elements = document.getElementsByClassName(className, element);
		elements.each(function(e){
			Element.remove(e);										 
		});
    },
  
    isValid: function(form){
        Form.clearErrors(form);
        var elements = Form.getElements(form);
        var invalid = elements.find(function(e){

            try{
                if (e.type == 'text'){
                    e.value = e.value.trim().replace(/ +/g," ");
                }
            }catch(ex){console.log(ex);}

            return (
                (Element.hasClassName(e.parentNode, 'obligatorio') && e.value == "" ) ||
                (Element.hasClassName(e.parentNode, 'numerico') && e.value != '' && isNaN(e.value) ) ||
                (Element.hasClassName(e.parentNode, 'positivo') && e.value != '' && (  parseInt(e.value) < 0) ) ||
                (Element.hasClassName(e.parentNode, 'mysql_date') && ( e.value != '' && !e.value.isValidMysqlDate())) ||
                (Element.hasClassName(e.parentNode, 'email_valido') && (e.value != '' && !e.value.isValidEmail()))  ||
                (Element.hasClassName(e.parentNode, 'texto') && (e.value != '' && !e.value.isTexto())) ||
                (Element.hasClassName(e.parentNode, 'texto_espacio') && (e.value != '' && !e.value.isValidTextoEspacio())) ||
                (Element.hasClassName(e.parentNode, 'notasobre100') && (e.value != '' && !e.value.isValidNotesOver100())) ||
                (Element.hasClassName(e.parentNode, 'notasobre10') && (e.value != '' && !e.value.isValidNotesOver10())) ||
                (Element.hasClassName(e.parentNode, 'alfanumerico_espacio') && (e.value != '' && !e.value.isValidAlfanumericoEspacio())) ||
                (Element.hasClassName(e.parentNode, 'formato_moneda') && (e.value != '' && !e.value.isValidFormatCoin())) ||
                (Element.hasClassName(e.parentNode, 'rango_0_100') && (e.value != '' && !( e.value <= 100 && e.value >= 0 ))) ||
                (Element.hasClassName(e.parentNode, 'fecha_actual_o_mayor') && (e.value != '' && !e.value.esFechaActualOMayor())) ||
                (Element.hasClassName(e.parentNode, 'formato_dni') && (e.value != '' && !e.value.esFormatoDni())) ||
                (Element.hasClassName(e.parentNode, 'phone_number') && (e.value != '' && !e.value.isValidPhoneNumber()))
            );

        });

		return ( invalid ? false : true );
    },
  
    showErrors: function(form, options){
  		Form.clearErrors(form);
		var options = Object.extend({
			obligatorio: '',
		    numerico: '',
            positivo: '',
            mysql_date: 'error de fecha',
            email_valido: 'formato de email invalido',
            texto: '',
            texto_espacio: '',
            notasobre100: 'Formato de nota sobre 100 invalido',
            notasobre10: 'Formato de nota sobre 10 invalido',
            formato_moneda: '',
            alfanumerico_espacio: '',
            rango_0_100: '',
            fecha_actual_o_mayor: 'Fecha debe ser la actual o mayor',
            formato_dni:'Formato DNI no es valido',
            phone_number: '',
		}, options || {});
		
    	var elements = $A(Form.getElements(form));
    	//alert(elements);
	  	elements.each(function(e){ 
//	  		console.log(e);
  			if (Element.hasClassName(e.parentNode, 'obligatorio') && e.value == "" ){
				Form.addErrorMsg(e, options.obligatorio);
				return;
  			}  			
			if ( Element.hasClassName(e.parentNode, 'numerico') && e.value != '' && isNaN(e.value) ){
				Form.addErrorMsg(e, options.numerico);
				return;
			}
            if ( Element.hasClassName(e.parentNode, 'positivo') && (e.value != '' && parseInt(e.value) < 0) ){
				Form.addErrorMsg(e, options.positivo);
				return;
			}		
            if ( Element.hasClassName(e.parentNode, 'mysql_date') && (e.value != '' && !e.value.isValidMysqlDate()) ){
				Form.addErrorMsg(e, options.mysql_date);
				return;
			}		
            if ( Element.hasClassName(e.parentNode, 'email_valido') && (e.value != '' && !e.value.isValidEmail()) ){
				Form.addErrorMsg(e, options.email_valido);
				return;
			}
            if ( Element.hasClassName(e.parentNode, 'texto_espacio') && (e.value != '' && !e.value.isValidTextoEspacio()) ){
                Form.addErrorMsg(e, options.texto_espacio);
                return;
            }
            if ( Element.hasClassName(e.parentNode, 'notasobre100') && ( e.value != '' && !e.value.isValidNotesOver100()) ){
                Form.addErrorMsg(e, options.notasobre100);
                return;
            }
            if ( Element.hasClassName(e.parentNode, 'notasobre10') && ( e.value != '' && !e.value.isValidNotesOver10()) ){
                Form.addErrorMsg(e, options.notasobre10);
                return;
            }
            if ( Element.hasClassName(e.parentNode, 'alfanumerico_espacio') && ( e.value != '' && !e.value.isValidAlfanumericoEspacio()) ){
                Form.addErrorMsg(e, options.alfanumerico_espacio);
                return;
            }
            if ( Element.hasClassName(e.parentNode, 'formato_moneda') && ( e.value != '' && !e.value.isValidFormatCoin()) ){
                Form.addErrorMsg(e, options.formato_moneda);
                return;
            }

            if ( Element.hasClassName(e.parentNode, 'texto') && ( e.value != '' && !e.value.isTexto()) ){ 
                Form.addErrorMsg(e, options.texto);
                return;
            }
            if ( Element.hasClassName(e.parentNode,'rango_0_100') && ( e.value != '' && !( e.value <= 100 && e.value >= 0 ))){
                Form.addErrorMsg(e, options.rango_0_100);
                return;
            }
            if ( Element.hasClassName(e.parentNode, 'fecha_actual_o_mayor') && ( e.value != '' && !e.value.esFechaActualOMayor() ) ){
             Form.addErrorMsg(e, options.fecha_actual_o_mayor);
             return;
             }
            if ( Element.hasClassName(e.parentNode, 'formato_dni') && ( e.value != '' && !e.value.esFormatoDni() ) ){
                Form.addErrorMsg(e, options.formato_dni);
                return;
            }
            if ( Element.hasClassName(e.parentNode, 'phone_number') && ( e.value != '' && !e.value.isValidPhoneNumber() ) ){
                Form.addErrorMsg(e, options.phone_number);
                return;
            }
  		});
  	},
  
/*
  addErrorMsg: function(e, msg){
		var errorMessage = $E({tag: 'span', className: '_error_' });
		Element.update(errorMessage, msg);
		e.parentNode.appendChild(errorMessage);
		new Effect.Highlight(errorMessage);  				
		return;	
  },
  
  clearErrors: function(form){
		var elements = $A(document.getElementsByClassName('_error_', form));
		elements.each(function(e){
			Element.remove(e);
		});
	},
*/
  addErrorMsg: function(e, msg){
		$(e).parentNode.addClassName('_error_');
		$(e).parentNode.setAttribute('data-tooltip', msg);
		return;	
  },
  
    clearErrors: function(form){
//        var elements = $A(document.getElementsByClassName('_error_', form));
        var elements = $A(form.getElementsByClassName('_error_'));
        elements.each(function(e){
            $(e).removeClassName('_error_');
            $(e).removeAttribute('data-tooltip');
        });
    },
  

//
//  toggle2: function(form){
//        var form = $(form);
//        var is_disabled = form.readAttribute('disabled');
//        if(is_disabled){
//            var inputs = form.select('input[type!="hidden"]');
//            inputs.each(function(element){
//                if(element.type=='checkbox'){
//                    var onclick = element.readAttribute('_onclick');
//                    if(onclick){
//                        element.writeAttribute('onclick',onclick);
//                        element.removeAttribute('_onclick');
//                    }
//                    var onkeydown = element.readAttribute('_onkeydown');
//                    if(onkeydown){
//                        element.writeAttribute('onkeydown',onkeydown);
//                        element.removeAttribute('_onkeydown');
//                    }
//                }
//                element.removeClassName('disable');                    
//                element.removeAttribute('disabled');                        
//                element.removeAttribute('readonly');
//            });
////            var acciones = form.select('a.accion');
//            var acciones = form.select('a');
//            acciones.invoke('enable');
//            form.removeAttribute('disabled');
//        }else{
//            var inputs = form.select('input[type!="hidden"]');
//            inputs.each(function(element){
//                if(element.type=='checkbox'){
//                    var onclick = element.readAttribute('onclick');
//                    if(onclick){
//                        element.writeAttribute('_onclick',onclick);                        
//                    }
//                    var onkeydown = element.readAttribute('onkeydown');
//                    if(onkeydown){
//                        element.writeAttribute('_onkeydown',onkeydown);  
//                    }                    
//                    element.writeAttribute('onclick','return false;');
//                    element.writeAttribute('onkeydown','return false;');
//                    element.addClassName('disable');
//                    element.writeAttribute('readonly','readonly');
//                }else if(element.type=='button' || element.type=='submit'){
//                    element.setAttribute('disabled','disabled');
//                }else{
//                    element.setAttribute('readonly','readonly');
//                }
//            });
////            var acciones = form.select('a.accion');
//            var acciones = form.select('a');
//            acciones.invoke('disable');
//            form.setAttribute('disabled','disabled');
//        }
//    },
            
   /*se debe probar mas este metodo, luego implementar con encendido y apagado de eventos*/
  /*
   * Deshabilita o habilita los inputs y acciones d un formulario
   */
    toggle: function(form){
        var form = $(form);
        var is_disabled = form.readAttribute('disabled');
//        alert("dis?"+is_disabled);
        
        var elements = form.getElements();
        if(is_disabled){
            elements.each(function(element){
                if((element instanceof HTMLInputElement) && (element.type =='hidden')){
                    return;
                }
                if((element instanceof HTMLInputElement) && (element.type =='submit' || element.type=='button')){
                    element.enable();
                    return;
                }   
                var onchange = element.readAttribute('_onchange');
                if(onchange){
                    element.writeAttribute('onchange',onchange);
                    element.removeAttribute('_onchange');
                }
                var onclick = element.readAttribute('_onclick');                
                if(onclick){
                    element.writeAttribute('onclick',onclick);
                    element.removeAttribute('_onclick');
                }
                if(element.type==='checkbox' || !onclick){
                    element.writeAttribute('onclick','');                    
                }
                var onkeydown = element.readAttribute('_onkeydown');
                if(onkeydown){
                    element.writeAttribute('onkeydown',onkeydown);
                    element.removeAttribute('_onkeydown');
                }
                element.removeClassName('disable');           
                element.removeAttribute('disabled');
                element.removeAttribute('readonly'); 
            });
            var acciones = form.select('a');
            acciones.invoke('enable');
            form.removeAttribute('disabled');
        }else{
            elements.each(function(element){
                if((element instanceof HTMLInputElement) && (element.type =='hidden')){
                    return;
                }                
                if((element instanceof HTMLInputElement) && (element.type =='submit' || element.type=='button')){
                    element.disable();
                    return;
                }      
                var onchange = element.readAttribute('onchange');
                if(onchange){
                    element.writeAttribute('_onchange',onchange);     
                    element.writeAttribute('onchange','return false;');
                }
                var onclick = element.readAttribute('onclick');
                if(onclick){
                    element.writeAttribute('_onclick',onclick);
                    element.writeAttribute('onclick','return false;');
                }
                if(element.type==='checkbox'){
                    if(!onclick)element.writeAttribute('_onclick','');
                    element.writeAttribute('onclick','return false;');
                }
                var onkeydown = element.readAttribute('onkeydown');
                if(onkeydown){
                    element.writeAttribute('_onkeydown',onkeydown);  
                    element.writeAttribute('onkeydown','return false;');
                }                    
                element.addClassName('disable');
                element.writeAttribute('readonly','readonly');
            });
            var acciones = form.select('a');
            acciones.invoke('disable');
            form.setAttribute('disabled','disabled');
        }
//        alert("rs");
    },
});

/* String
*-------------------------------------------------------------------------- */
Object.extend(String.prototype, {	
	trim: function(){
		return this.replace(/^\s*|\s*$/g,"");	
	},
	
	// Addon por Rafael Nevarez
	isValidEmail: function(){
//  Valida si un string es un email Valido
		var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/;
		return ( b.test(this) ? true : false );				
	},

	isValidIPAdress: function(){
		var b=/^(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])){3}$/;
		return ( b.test(this) ? true : false );				
	},

    isValidTextoEspacio: function(){
// ACEPTA CUALQUIER CARACTER EXCEPTO LOS SIGUIENTES:
//                   . ; : · " ¡ ! ¿ ? # € $ @ % & ¬ = \ | / + * ( ) { } [ ] 0 1 2 3 4 5 6 7 8 9
        var b=/^[^0-9.;:"·¡!¿/#€@%&¬=\\\?\+\*\|\$\(\)\{\}\[\]]*$/;
        return ( b.test(this) ? true : false );
    },

    isValidNotesOver100: function(){
// ACEPTA NUMEROS DEL 0 al 100, Y 2 DECIMALES SEPARADOS POR PUNTO [,.]
        var b=/^((100)$|[1-9]?[0-9](\.(0[1-9]|[1-9][0-9]?))?)$/;
        return ( b.test(this) ? true : false );
    },

    isValidNotesOver10: function(){
// ACEPTA NUMEROS DEL 0 al 10, Y 2 DECIMALES SEPARADOS POR PUNTO [,.]
        var b=/^((10)$|[0-9](\.(0[1-9]|[1-9][0-9]?))?)$/;
        return ( b.test(this) ? true : false );
    },

    isValidAlfanumericoEspacio: function(){
// ACEPTA CUALQUIER CARACTER EXCEPTO LOS SIGUIENTES:
//                   . ; : · " ¡ ! ¿ ? # € $ @ % & ¬ = \ | / + * ( ) { } [ ]
        var b=/^[^.;:"·¡!¿/#€@%&¬=\\\?\+\*\|\$\(\)\{\}\[\]]*$/;
        return ( b.test(this) ? true : false );
    },

    isValidFormatCoin: function(){
// ACEPTA NUMEROS ENTEROS, Y HASTA 2 DECIMALES SEPARADOS POR PUNTO [,.]
        var b=/^([1-9][0-9]*|0)(\.[0-9][0-9]?)?$/;
        return ( b.test(this) ? true : false );
    },

    isTexto: function(){
// ACEPTA CARACTERES DE LETRA A-Z (mayus/minus), PERO NO ACEPTA ESPACIOS
        var b=/^[A-Za-z|\u00d1\u00f1|ñÑ]+$/g;
        return (b.test(this) ? true : false);
    },

	isValidMysqlDate: function(){
//  Valida si una fecha string es una fecha mysql valida
		if((this.match(/^([\d]{4})(-{1})([\d]{2})(-{1})([\d]{2})/) == null) || (this == '0000-00-00' ))
			return false;
		else{
			var parts = this.split('-');
			var Fecha = new Date;
			Fecha.setFullYear(parts[0], parts[1] - 1, parts[2]);
			if(Fecha.getFullYear() != (parseInt(parts[0])) || Fecha.getMonth() != (parseInt(parts[1]) - 1) || Fecha.getDate() != (parseInt(parts[2])))
				return false;
			else
				return true;
		}		
	},

    esFormatoDni: function(){
        var b=/^[0-9A-Za-z .-]*$/;
        return ( b.test(this) ? true : false );
    },

    esFechaActualOMayor: function(){
        fecha_actual = window.getFechaActual();
        if ( this >= fecha_actual) {
            return true
        }else{
            return false
        }
    },

	isValidPhoneNumber: function(){
// VALIDA NUMEROS TELEFONICOS, TRABAJA EN PARALELO CON LA FUNCION validData (ESTA CASI AL FINAL DEL ARCHIVO)
//		var b=/[\(]?[\-,]?[0-9 ]+[\)]?/;
        var b=/^[^.;:"·¡!¿€@%&¬=\\\?\*\|\$\{\}\[\]qazwsdcrfvgbnhyujmkiolpñ]*$/i;
		return ( b.test(this) ? true : false );
	},
	
});

/* Form.Element
*-------------------------------------------------------------------------- */
Object.extend(Form.Element, {

  serializeEscaped: function(element) {
    element = $(element);
    var method = element.tagName.toLowerCase();
    var parameter = Form.Element.Serializers[method](element);

    if (parameter) {
      var key = escape(parameter[0]);
      if (key.length == 0) return;

      if (parameter[1].constructor != Array)
        parameter[1] = [parameter[1]];

      return parameter[1].map(function(value) {
        return key + '=' + escape(value);
      }).join('&');
    }
  },
  
  /* Editado por Rafael Nevarez */
   getValueEscaped: function(element) {
    var element = $(element);
    var method = element.tagName.toLowerCase();
    var parameter = Form.Element.Serializers[method](element);

		if (parameter) 
			return escape(parameter[1]);
	},
	
	/* Editado por Andr�s Sussmann creado por Andr�s Santiba�ez */
	isDate: function(element){
		if (typeof element == 'string')
			return(String.toDate(element));	
		var el = $(element);
		if (!el) return false;
		var fecha = el.value;
		return(String.toDate(fecha));
	},
            
    
	
});


/* Element
*-------------------------------------------------------------------------- */
Object.extend(Element, {
		
	centerInScreen: function(element, options){
	
		var options = Object.extend({
			offsetLeft: 0,
			offsetTop: 0,
			fixed: false,
			mode: ''
		},options || {});
		options = options;
		
		var element = $(element);
		
		var dimensions = Element.getDimensions(element);
		var windowCenter = window.getWidth() / 2;
		var windowMiddle = window.getHeight() / 2 ;	
			
		var L = windowCenter - dimensions.width / 2 - options.offsetLeft;
		var T = windowMiddle - dimensions.height / 2 - options.offsetTop;
		
		var styleAttrs = {
			position: options.fixed ? 'fixed' : 'absolute'
		};
		
		if ( options.mode == '' || options.mode == 'horizontal' ){
			styleAttrs.left = L + 'px';
		}
		
		if ( options.mode == '' || options.mode == 'vertical' ){
			styleAttrs.top = T + 'px';
		}		
		
		Element.setStyle(element, styleAttrs);		
	},
	
	toggleContext: function(element, options){
		element = $(element);
		if ( Element.visible(element) )
		  Element['hide'](element);
		else
		  Element['showContext'](element, options);
	},		
	
    /*
     * hideOnClick:         solo funciona si se especifica trigger, oculta element al dar click en cualquier lugar
     * hideOnClickOutside:  solo funciona si se espcifica trigger, sobreescribe comportamiento de hideOnclick(si se ha especificado)
     *                      oculta element solo si se da click fuera de él.
     * */
	showContext: function(element, options){
		var options = Object.extend({
			trigger: null,
			offsetLeft: 5,
			offsetTop: 15,
			hideOnClick: true,
			hideOnEsc: true,
			hideOnClickOutside: false,
			left: 0,
			top: 0,
		//	modal: false,
			fixed: false
		},options || {});
		options = options;
		element = $(element);
		var trigger = $(options.trigger);
		
		//if (options.modal)
		//	Element.toModal(element);
		
		if ( trigger ){
			var pos = Position.positionedOffset(trigger);
			var L = pos[0] + options.offsetLeft;
			var T = pos[1] + options.offsetTop;
		}
		else{
			var L = options.left;
			var T = options.top;	
		}
		var styleAttrs = {	top: T + 'px', position: 'absolute', zIndex: '50' };
		
      if ( options.right ){
			styleAttrs.right = options.right + 'px';				
		}
		else{
			styleAttrs.left	= L	 + 'px';	
		}
		
		if (options.fixed){
			styleAttrs.position = 'fixed';
		}		
		
		Element.setStyle(element, styleAttrs);
		Element.show(element);
		
        if (trigger && options.hideOnClick && !options.hideOnClickOutside){
            document.observe('click', function hideContext(event) {
                var origen = Event.findElement(event);
                if( !(origen==trigger || origen.descendantOf(trigger)) ){
                    document.stopObserving('click', hideContext);
                    Element.hide(element);
                }
            });	
		}
        
        if (trigger && options.hideOnClickOutside){            
			document.observe('click', function hideContext(event) {
                var origen = Event.findElement(event);
                if( !(origen==trigger || origen.descendantOf(trigger) || origen.descendantOf(element)) ){
					document.stopObserving('click', hideContext);
					Element.hide(element);
				}
			});	
		}       
		
		if (options.hideOnEsc){
			Event.observe(document, 'keyup', function hideContext(evt){	
				if ( evt.keyCode == Event.KEY_ESC ){
					Event.stopObserving(document, 'keyup', hideContext);					
					Element.hide(element);
				}					
			}, true);		
		}		
	},
	
/*
	toModal: function(element){
	
		var modal = $E({
			tag: 'div', 
			className: 'popupModalBg',
			id: element.id + '_modalBg'
		});
		
		element.parentNode.appendChild(modal);
	},
	
	toggleModal: function(element){
		var modal = $(element.id + '_modalBg');
		
		if (modal)
			Element.remove(modal)
		else
			Element.toModal(element);
	},
*/
	toggleModal: function(element){	
		$(element).toggleClassName('popupModal');
	},
	
	
	
	formatToMysqlDate: function(element){
	    var value = element.value;
	    element.value = value.replace(/^([\d]{4})(.*)([\d]{2})(.*)([\d]{2})$/,"$1-$3-$5");
		if(element.value.length == element.maxLength && (element.value.match(/^([\d]{4})(-{1})([\d]{2})(-{1})([\d]{2})/) == null)) 	
			element.value = '';		
	},
	
	isValidMysqlDate: function(element){
		if(!element.value.isValidMysqlDate()){
			element.value = '';
			return false;
		}
		return true;
	},
            
    disableA: function(element){
        if(element instanceof HTMLAnchorElement){
            var onclick = element.readAttribute('onclick');
            if(onclick){
                element.writeAttribute('_onclick',onclick);
                element.removeAttribute('onclick');
            }
            var href = element.readAttribute('href');
            if(href){
                element.writeAttribute('_href',href);
                element.removeAttribute('href');
            }
            element.addClassName('disabled');
            element.writeAttribute('disabled','disabled');
            return;
        }
       Element.disable(element);
	},
            
    enableA: function(element){		
        var onclick = element.readAttribute('_onclick');
        if(onclick){
            element.writeAttribute('onclick',onclick);
            element.removeAttribute('_onclick');
        }
        var href = element.readAttribute('_href');
        if(href){
            element.writeAttribute('href',href);
            element.removeAttribute('_href');
        }
        element.removeClassName('disabled');
        element.removeAttribute('disabled');
	}
});


/* Se a�adi� funci�n para detener el PeriodicalExecuter. Giannella Paredes */
/*--------------------------------------------------------------------------*/
Object.extend(PeriodicalExecuter.prototype, {
	registerCallback: function() {
    	this.interval = setInterval(this.onTimerEvent.bind(this), this.frequency * 1000);
  	},
  	stop: function() {
  		clearInterval(this.interval);
  	},
  	start: function() {
  		this.registerCallback();
  	}
});


/*
 * Document.createElement convenience wrapper
 *--------------------------------------------------------------------------
*/
function $E(data) {
	var el;
	if ('string'==typeof data) {
			el=document.createTextNode(data);
	} else {
		//create the element
		el=document.createElement(data.tag);
		delete(data.tag);

		//append the children
		if ('undefined'!=typeof data.children) {
			if ('string'==typeof data.children ||	'undefined'==typeof data.children.length) {
					//strings and single elements
					el.appendChild($E(data.children));
			} else {
					//arrays of elements
					for (var i=0, child=null; 'undefined'!=typeof (child=data.children[i]); i++) {
						el.appendChild($E(child));
					}
			}
			delete(data.children);
		}
		//any other data is attributes
		for (attr in data) {
			el[attr]=data[attr];
		}
	}
	return el;
}


Object.extend(window, {

	getWidth: function(){
		return Try.these(
			function() {return document.documentElement.clientWidth},
			function() {return document.body.clientWidth},
			function() {return window.innerWidth}
		) || false;
	},

	getHeight: function(){
		return Try.these(
			function() {return document.documentElement.clientHeight},
			function() {return document.body.clientHeight},
			function() {return window.innerHeight}
		) || false;
	},
	
	showDialog: function(parent, options){				
		var options = Object.extend({
			id: 'modalDialog',
			className: 'popupForm',
			containerClassName: '',
			width: 400,
			height: 500,
			autoHeight: false, //Permite un alto dinamico para el contenido de la venta
			hideOnEsc: false,
			showCloseBtn: true,
			url: '', pars: '',
			HTML: '',
			setFocus: true,
			centered: true,
			fixed: true,
			modal: true,
			onBusy: function(){},
			onComplete: function(){}
			 
		}, options || {});
		
		var parent = $(parent);		
		
		// Ya mostrandose
		if ( $(options.id) ){ return false; }
		
		// Dialogo Modal
		var modalDialog = $E({
			tag: 'div', 
			id: options.id, 
			className: options.className
		});		
	   
       modalDialog.style.width = options.width + 'px';
	   modalDialog.style.height = options.height + 'px';
		
		// Container
		var container = $E({
			tag: 'div', 
			className: options.containerClassName
		});	
		modalDialog.appendChild(container);	
		
		// Centro de la pantalla.	
		parent.appendChild(modalDialog);
		
		posLeft = ( window.getWidth() / 2 - options.width / 2 );
		posTop = ( window.getHeight() / 2 - options.height / 2 );	
		
		 // Para cargar en 2do plano
		Element.hide(modalDialog);
		
		if ( options.url ){
			new Ajax.Updater(container, options.url, {
				 parameters: options.pars, 
				 evalScripts: true , 
				 onComplete: function() {
				 	if (options.showCloseBtn){
						new Insertion.Top(modalDialog, '<a class="cerrar" onclick="Element.remove(this.parentNode)"></a>');
				 	}
				 	
					Element.showContext(modalDialog, {
						top: posTop, 
						left: posLeft, 
						hideOnClick: false, 
						hideOnEsc: options.hideOnEsc,						
						fixed: options.fixed
					});
					
					//Element.scrollTo(modalDialog);
						
					if (options.modal)
						$(modalDialog).addClassName('popupModal');
					
					if (options.autoHeight)
						$(modalDialog).setStyle({height:null});					
					options.onComplete(modalDialog);
				 }
			});
		}
	}
});

window.openModalDialog = function(url, windowName, options){
	var options = Object.extend({
		 width: '640',
		 height: '480',
		 scrollbars: 'yes'
	}, options || {});	
	//return window.open(url, windowName, 'modal=yes, width=' + options.width + ', height=' + options.height + ', scrollbars='+ options.scrollbars +', dialog=yes, dependent=yes');

	return Try.these(
		// PUERCO HACK PARA INTERNET EXPLORER
		function() {return window.showModalDialog(url, '', 'dialogwidth:' + options.width + '; dialogheight:' + options.height + '; scroll: '+options.scrollbars+'; center:yes');},
		function() {return window.open(url, windowName, 'modal=yes, width=' + options.width + ', height=' + options.height + ', scrollbars='+ options.scrollbars +', dialog=yes, dependent=yes');}
	) || false;
}


window.showBalloon = function(parent, options){
	var options = Object.extend({
		id: 'ballonPopup',
		className: 'tipBlack',
		width: 400,
		height: 300,
		hideOnEsc: true,
		showCloseBtn: true,
		hideOnClick: false,
		url: '', pars: '',
		HTML: '',
		trigger: null,
		offsetLeft: -35,
		offsetTop: 13,
		onComplete: function(){}
		 
	}, options || {});
	var parent = $(parent);
	
	var modalDialog = $E({
		tag: 'div', 
		id: options.id, 
		children: {
			tag: 'div',
			className: options.className + 'T'
		}
	});
	
	var modalDialogContent = $E({
		tag: 'div', 
		id: options.id + 'Content',
		className: options.className
	});
	modalDialogContent.style.width = options.width + 'px';
	modalDialogContent.style.height = options.height + 'px';
	
	Element.hide(modalDialog); // Para cargar en 2do plano
	

	modalDialog.appendChild(modalDialogContent);	
	parent.appendChild(modalDialog);
	
	if ( options.url ){
		new Ajax.Updater(modalDialogContent, options.url, {
			 parameters: options.pars, 
			 evalScripts: true , 
			 onComplete: function() {
			 	if (options.showCloseBtn){
					//new Insertion.Top(modalDialogContent, '<a class="cerrar" onclick="Element.remove(this.parentNode)"></a>');
			 	}
				Element.showContext(modalDialog, options);
				options.onComplete();
			 }
		});
	}
	
	if ( options.HTML ){
		Element.update(modalDialogContent, options.HTML);
		//modalDialogContent.appendChild(content);
		Element.showContext(modalDialog, options);
	}
}

window.compareElements = function(a, b){
	if (a.innerHTML.stripTags().toLowerCase() < b.innerHTML.stripTags().toLowerCase())
		return -1;		 
	return 1;
}


window.compareElementsNumeric = function(a, b){
	//return parseInt(a.innerHTML.stripTags()) - parseInt(b.innerHTML.stripTags());
	//return a - b;
	if ( parseInt(a.innerHTML.stripTags()) < parseInt(b.innerHTML.stripTags()) )
		return -1;		
	return 1;
}

window.getFechaActual = function(){
	var fecha = new Date; 
	var mes = fecha.getMonth() + 1; 
	var dia = fecha.getDate(); 
	var ano = fecha.getFullYear();
	
	dia = dia < 10 ? '0' + dia : dia;
	mes = mes < 10 ? '0' + mes : mes;
	
	return ano + '-' + mes + '-' + dia; 
}

//una vez movida al prototype... eliminarse
window.alterarFechaHora = function(fecha, tipo, valor, datoRetorno){
	if ( valor == 0 ){
		return fecha;
	}
	var fecha_array = fecha.split('-'); 
	var fecha_temporal = new Date(fecha_array[0],fecha_array[1] - 1,fecha_array[2]);
  	var fecha_alterada;
	var resultado;
	
	    switch (tipo.toLowerCase()) {
            case 'ms':
                  fecha_alterada = fecha_temporal.setMilliseconds(fecha_temporal.getMilliseconds() + valor);
                  fecha_temporal.setMilliseconds(fecha_temporal.getMilliseconds() - valor);
            break;

            case 's':
                  fecha_alterada = fecha_temporal.setSeconds(fecha_temporal.getSeconds() + valor);
                  fecha_temporal.setSeconds(fecha_temporal.getSeconds() - valor);
            break;

            case 'i':
                  fecha_alterada = fecha_temporal.setMinutes(fecha_temporal.getMinutes() + valor);
                  fecha_temporal.setMinutes(fecha_temporal.getMinutes() - valor);
            break;

            case 'h':
                  fecha_alterada = fecha_temporal.setHours(fecha_temporal.getHours() + valor);
                  fecha_temporal.setHours(fecha_temporal.getHours() - valor);
            break;

            case 'd':
                fecha_alterada = fecha_temporal.setDate(fecha_temporal.getDate() + (valor));
                fecha_temporal.setDate(fecha_temporal.getDate() - (valor));
            break;

            case 'm':
                  fecha_alterada = fecha_temporal.setMonth(fecha_temporal.getMonth() + valor);
                  fecha_temporal.setMonth(fecha_temporal.getMonth() - valor);
            break;

            case 'y':
                  fecha_alterada = fecha_temporal.setFullYear(fecha_temporal.getFullYear() + valor);
                  fecha_temporal.setFullYear(fecha_temporal.getFullYear() - valor);
            break;
	
		}
	
	fecha_resultado = new Date(fecha_alterada);	
		
	var ano = fecha_resultado.getFullYear();
	var mes = (fecha_resultado.getMonth()< 9) ? '0' + (fecha_resultado.getMonth()+1) : (fecha_resultado.getMonth()+1);
	var dia = (fecha_resultado.getDate()<10) ? '0' + fecha_resultado.getDate(): fecha_resultado.getDate();
	var hora = ( fecha_resultado.getHours() < 10 ) ? '0' + fecha_resultado.getHours() : fecha_resultado.getHours();
	var minuto = ( fecha_resultado.getMinutes() < 10 ) ? '0' + fecha_resultado.getMinutes() : ( fecha_resultado.getMinutes() == 60 ) ? '00' : fecha_resultado.getMinutes(); 		
	var segundo = '00';
	
	if(datoRetorno=='fechaHora'){
		resultado = (ano + '-' + mes + '-' + dia + ' ' + hora + ':' + minuto + ':' + segundo);
	}
	
	if(datoRetorno=='fecha'){
		resultado = (ano + '-' + mes + '-' + dia);
	}
	
	return resultado;
}


toggleElementsByClassName = function(className, container){
    //alert('llego '+className+' '+container);
	//var elements = document.getElementsByClassName(className, container);
	var children = $(container).childNodes;
	var elements = new Array;
    //alert(elements.length);
    //alert("no funciona");
    for ( var i = 0; i < children.length; i++ ) { 
        //alert(elements[i].value);
		if ( Element.hasClassName(children[i], className) ){
		    //alert(children[i]);
    		elements.push(children[i]);
    	}

    }
	Element.toggle.apply(null, elements);
    
}

var Eliminados = {
	toggle: function(){
		document.styleSheets[0].cssRules[0].styleSheet.cssRules[2].style.display = (document.styleSheets[0].cssRules[0].styleSheet.cssRules[2].style.display == '' ? 'none' : '');
	},
	
	show: function(){
		document.styleSheets[0].cssRules[0].styleSheet.cssRules[2].style.display = '';
	},
	
	hide: function(){
		document.styleSheets[0].cssRules[0].styleSheet.cssRules[2].style.display = 'none';
	}
}

var coolTextarea = {
	initialize:function(){
		Event.observe(this, 'keydown', this.insertTab, false);
	},
	
	insertTab: function(e){
		if(e.keyCode == 9){
			this.insertAtCaret('\t');
			Event.stop(e);
		}
	},
	
	insertAtCaret:function(text){
		if(document.selection) {
			this.focus();
			
			var orig = this.value.replace(/\r\n/g, "\n");
			var range = document.selection.createRange();

			if(range.parentElement() != this) {
				return false;
			}

			range.text = text;
			
			var actual = tmp = this.value.replace(/\r\n/g, "\n");

			for(var diff = 0; diff < orig.length; diff++) {
				if(orig.charAt(diff) != actual.charAt(diff)) break;
			}

			for(var index = 0, start = 0; 
				tmp.match(text) 
					&& (tmp = tmp.replace(text, "")) 
					&& index <= diff; 
				index = start + text.length
			) {
				start = actual.indexOf(text, index);
			}
		} 
		else if(this.selectionStart) {
			var start = this.selectionStart;
			var end   = this.selectionEnd;
			var textareaScroll = this.scrollTop;
			this.value = this.value.substr(0, start) 
				+ text 
				+ this.value.substr(end, this.value.length);
		}
		
		if(start != null)
			this.setCaretTo(start + text.length);
		else
			this.value += text;
			
		this.scrollTop = textareaScroll;
	},
	
	setCaretTo: function(pos){
		if(this.createTextRange) {
			var range = this.createTextRange();
			range.move('character', pos);
			range.select();
		} else if(this.selectionStart) {	
			this.focus();
			this.setSelectionRange(pos, pos);
		}
	}
}


/* Effects extensions
*-------------------------------------------------------------------------- */
Object.extend(Effect,{
	
	FadeInTime: function(element, miliseconds){
		if (!element){
			return false;
		}
		
		var ttl = (miliseconds ? miliseconds : 4000); // 4 Segundos por default
		Element.hide(element);
		Effect.Appear(element)
		
		setTimeout(function(){Element.hide(element)}, ttl);	
	},
	
	SlideUpAndDown: function(element, options) {
		var options = Object.extend({
			modal: false,		 
		}, options || {});		
		
		element = $(element);
	
		if (element.style.display != 'none') {
			//new Effect.SlideUp(element, {duration: 0.25});
			element.hide();
		}
		else {
			//new Effect.SlideDown(element, {duration: 0.25});
			element.show();
		}
		element.style.zIndex = '100';
		
		$(element).addClassName('popupModal');
		//Element.toggleModal(element);
	},

	BlindUpAndDown: function(element) {
		element = $(element);
		if(element.style.display != 'none') 
			new Effect.BlindUp(element);
		else{
			new Effect.BlindDown(element);		  
		}
	}	
});


/******************************************************/
validData = function(evt, element){

	if( Element.hasClassName(element.parentNode, 'phone_number') ){
//		var value = element.value;
//		var charCode = (evt.which) ? evt.which : event.keyCode;
//		var char = String.fromCharCode(charCode);
		//alert(charCode + " : " + char + " : " + value);

		// Allow only backspace and delete  => let it happen, don't do anything
//		return char.isValidPhoneNumber() || charCode == 40 || charCode == 41 || charCode == 46 || charCode == 8  ? value : false;

	}
}

Object.extend(HTMLAnchorElement.prototype, {
    
    disable: function(){
        Element.disableA(this);
    },
            
    enable: function(){
        Element.enableA(this);
	},
    
    toggleEnable: function(){
      var disabled = this.readAttribute('disabled');
      if(disabled) this.enable();
      else this.disable();
    }
});




