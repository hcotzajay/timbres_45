/////Esto es para agregar los campos en los input
function CargarCombos(direccion, variable, combo){
		$.ajax({
			type: "POST",
			url: direccion,
			data: variable+"=",
			success: function(msg){
				$("#"+combo).html(msg);
			}
		});
            }
/////Esto es otro metodo para agregar los campos en los input
            function CargarCombosSync(direccion, variable, combo){
                $.ajax({
                    type: "POST",
                            url: direccion,
                            data: variable+"=",
                            async: false,
                            success: function(msg){
                        $("#"+combo).html(msg);
                    }
                });
            }
function getUrlVars(){
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}

function ShowHide(clase, oculta, muestra){
    var partes = $("."+clase);
    $('#'+oculta).hide('fade', function(){
        for(var i = 0; i < partes.length; i++){
            if(partes[i].id != oculta || partes[i].id != muestra)
                $('#'+partes[i].id).hide();
        }
        $('#'+muestra).show('fade');
                
        var url = document.URL.replace('&', '|');
        url = url.substr(url.indexOf('/seguridad'), url.length);
        var datos = "&url="+url+"&alias="+muestra;
        $.ajax({
            type: "POST",
            url: "SysI.php",
            data: "ayuda="+datos,
            success: function(msg){
                $("#helper div.contenido").html(msg);
            }
        });
    });
}
	
function ShowOne(clase, muestra){
    var partes = $("."+clase);
    for(var i = 0; i < partes.length; i++)
        $('#'+partes[i].id).hide();
    $('#'+muestra).show();
            
    var url = document.URL.replace('&', '|');
    url = url.substr(url.indexOf('/seguridad'), url.length);
    var datos = "&url="+url+"&alias="+muestra;
    $.ajax({
        type: "POST",
        url: "SysI.php",
        data: "ayuda="+datos,
        success: function(msg){
            msg = transformar(msg);
            $("#helper div.contenido").html(msg);
        }
    });
}
    
	function getHrefVars(direccion){
	    var vars = [], hash;
	    var hashes = direccion.slice(direccion.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}
        function GetCampos(tabla, xml){
                var arreglo = new Array();
                var campos = xml.getElementsByTagName('campo');
                var i = 0;
                for(i = 0; i < campos.length; i++){
                    var dato = campos[i].getAttribute("nombre");
                    arreglo[dato] = campos[i].textContent;
                }

                return arreglo;
                }

        function CargarCombosData(direccion, variable, combo, data){
            $.ajax({
                type: "POST",
		url: direccion,
		data: variable+"="+data,
		success: function(msg){
			$("#"+combo).html(msg);
		}
            });
        }

	function CargarCombos(direccion, variable, combo){
		$.ajax({
			type: "POST",
			url: direccion,
			data: variable+"=",
			success: function(msg){
				$("#"+combo).html(msg);
			}
		});
	}
    
    
        
function CargarCombosSync(direccion, variable, combo){
    $.ajax({
        type: "POST",
		url: direccion,
		data: variable+"=",
        async: false,
		success: function(msg){
            $("#"+combo).html(msg);
        }
    });
}
        
function CargarCombosDataSync(direccion, variable, combo, data){
    $.ajax({
        type: "POST",
        url: direccion,
        data: variable+"="+data,
        async: false,
        success: function(msg){
        	$("#"+combo).html(msg);
        }
    });
}
	
    function ShowHide(clase, oculta, muestra){
        var partes = $("."+clase);
        $('#'+oculta).hide('fade', function(){
            for(var i = 0; i < partes.length; i++){
                if(partes[i].id != oculta || partes[i].id != muestra)
                    $('#'+partes[i].id).hide();
            }
            $('#'+muestra).show('fade');
            var url = document.URL.replace('&', '|');
            url = url.substr(url.indexOf('/seguridad'), url.length);
            var datos = "&url="+url+"&alias="+muestra;
            $.ajax({
                type: "POST",
                url: "SysI.php",
                data: "ayuda="+datos,
                success: function(msg){
                    msg = transformar(msg);
                    $("#helper div.contenido").html(msg);
                }
            });
        });
    }
    
	function ValidaPass(txt){
		var mayus = "abcdefghijklmnñopqrstuvwxyz";
		var minus = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
		var nums = "0123456789";
		var contMay = 0;
		var contMin = 0;
		var contNum = 0;
		
		if(txt.length >= 8){
			for(i = 0; i < txt.length; i++){
				var letra = txt.charAt(i);
				if(mayus.indexOf(letra) != -1)
					contMay++;
				else if(minus.indexOf(letra) != -1)
					contMin++;
				else if(nums.indexOf(letra) != -1)
					contNum++;
			}
			
			if(contMay > 1 && contMin > 1 && contNum > 1)
				return true;
			else{
				setMensaje("La contraseña no cumple con las políticas de seguridad", "info");
				return false;
			}
			
		}else
			setMensaje("El tamaño mínimo de la contraseña son 8 caracteres", "info");
		
		return false;
	}
    
	function cargando(){
		$("#mensajin").bind("ajaxSend", function(){
			$(this).html("Cargando...");
			$(this).show();
		}).bind("ajaxComplete", function(){
			$(this).hide();
		}).bind("ajaxError", function(){
			$(this).html("Ha ocurrido un error");
			$(this).show();
			$(this).css("background-color", "red");
		});
	}
	
	function esconderMensaje(){
		$('#mensaje').hide('flod', function(){
			$('#mensaje').removeClass();
			$('#mensaje').html("");
		});
	}
	
	function setMensaje(mensaje, clase){
        clearTimeout(tiempo);
		$('#mensaje').removeClass();
		$('#mensaje').html("");
		$('#mensaje').html(mensaje);
		$('#mensaje').addClass(clase);
		$('#mensaje').show('flod');
		tiempo = setTimeout("esconderMensaje()",10000);
	}
	
	function setXMLMensaje(xmlDoc){
            var mensaje = xmlDoc.getElementsByTagName("mensaje")[0].childNodes[0].nodeValue;
            var clase = xmlDoc.getElementsByTagName("mensaje").item(0).getAttribute("clase");
            var datos = new Array();
            setMensaje(mensaje, clase);
            if(xmlDoc.getElementsByTagName("mensaje").item(0).getAttribute("id")){
                datos['clase'] = clase;
                datos['id'] = xmlDoc.getElementsByTagName("mensaje").item(0).getAttribute("id");
                return datos;
            }
            return clase;
	}
        
    function setXMLMensajeText(msg){	
        var parser = new DOMParser();
		var xmlDoc = parser.parseFromString(msg, "text/xml");
		var mensaje = xmlDoc.getElementsByTagName("mensaje")[0].childNodes[0].nodeValue;
        var clase = xmlDoc.getElementsByTagName("mensaje").item(0).getAttribute("clase");
        var datos = new Array();
        setMensaje(mensaje, clase);
        if(xmlDoc.getElementsByTagName("mensaje").item(0).getAttribute("id")){
             datos['clase'] = clase;
             datos['id'] = xmlDoc.getElementsByTagName("mensaje").item(0).getAttribute("id");
             return datos;
        }
        return clase;
	}
	
    function marcar(id){
        $(id).pulsate({
            glow: false,
            reach: 50,
            color: "#09f",
            repeat: 2
        });
    }
    
    function transformar(texto){
        texto = texto.replace(/<¡/g, '<a href="javascript:void(0);" class="widgethelp" onclick=marcar("');
        texto = texto.replace(/!!!/g, '")>');
        texto = texto.replace(/!>/g, '</a>');
        return texto;
    }
    
	function reloj(){
	   var fecha=new Date();
	   var h=fecha.getHours();
	   var m=fecha.getMinutes();
	   var s=fecha.getSeconds();
	   $('#indexFechaHora').html("Hora: "+h+":"+m+":"+s);
	   setTimeout("reloj()",1000);
	}
	
	/*function cargarPedazo(arreglo){
		var datos = "modulo="+arreglo[0]+"&opcion="+arreglo[1];
		$.ajax({
			type: "POST",
			url: "Pagineo.php",
			data: datos,
			success: function(msg){
				
				
				try{
					setXMLMensaje(msg);
				}catch(e){
					$("#contenido").hide('fade',function(){
						$("#contenido").html('<p id= "mensaje"></p>'+msg);
						$("#contenido").show('fade');
					});
				}
			}
		});
	}
	
	onpopstate = function(event) {
		cargarPedazo(event.state);
	}
	
	onload = function(event) {
		var arreglo = new Array();
		arreglo[0] = getUrlVars(this.href)['m'];
		arreglo[1] = getUrlVars(this.href)['o'];
		cargarPedazo(arreglo);
	}*/
        $(document).ready(function(){
            $('div.apps').hide();
            $('#logo').click(function(){
                if(!$('div.apps').is(':visible'))
                    $('div.apps').show('fade');
                else
                    $('div.apps').hide('fade');
            });
        });
    
    
	$(document).ready(function(event) {
		cargando();
		$("#mensajin").hide();
		//reloj();
		/*pressX = null;
        pressY = null;
        dragX = null;
        dragY = null
        presionado = false;
        seleX = 5;
        seleY = 5;*/
        $('#helper').hide();
        tiempo = null;
        
        $('#openhelper').click(function(){
            if(!$('#helper').is(':visible'))
                $('#helper').show('fade');
            else
                $('#helper').hide('fade');
        });
        
        /*$('#helper').mousedown(function(event){
            pressX = event.clientX;
            pressY = event.clientY;
            presionado = true;
            
            var cospressX = $('#helper').css("left");
            var cospressY = $('#helper').css("top");
            $("#helper p").html("hola "+cospressX+" - "+cospressY);
        });
        
        $('#helper').mousemove(function(event){
            dragX = event.clientX;
            dragY = event.clientY;
            
            if(presionado){
                $('#helper').css("left", seleX+ (dragX - pressX));
                $('#helper').css("top", seleY+ (dragY - pressY));
                $("#helper h1").html(pressX+" - "+pressY+" - "+
                    dragX+" - "+dragY+" - "+
                    (dragX - pressX)+" - "+(dragY - pressY)+" - "+
                seleX+" - "+seleY);
            }
            
        });
        
        $('#helper').mouseup(function(event){
            dragX = event.clientX;
            dragY = event.clientY;
            presionado = false;
            seleX = $('#helper').css("left");
            seleY = $('#helper').css("top");
            $("#helper p").html("hola "+seleX+" - "+seleY);
        });*/
        
        
		/*$('.menulink').click(function(event){
			if(this.href != "http://localhost/seguridad/logout.php"){
			//if(this.href != "http://172.16.39.5/seguridad/logout.php"){
				event.preventDefault();
				var arreglo = new Array();
				arreglo[0] = getHrefVars(this.href)['m'];
				arreglo[1] = getHrefVars(this.href)['o'];
				window.history.pushState(arreglo, 'Titulo nuevo', this.href);
				cargarPedazo(arreglo);
			}else
				top.location = "http://localhost/seguridad/logout.php";
				//top.location = "http://172.16.39.5/seguridad/logout.php";
		});*/
		
		$('#mensaje').hide();
		$("#sidebar .menu").accordion({autoHeight: false});
		$("#contenido .formas").tabs();
		
		$(".pestania").hide();
		$("ul.tabs li:first").addClass("active").show();
		$(".pestania:first").show();

		$("ul.tabs li").click(function()
	       {
			$("ul.tabs li").removeClass("active");
			$(this).addClass("active");
			$(".pestania").hide();

			var activeTab = $(this).find("a").attr("href");
			$(activeTab).fadeIn();
			return false;
		});
	});