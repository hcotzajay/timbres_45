	function logear(){
		var usr = $('#usuario').val();
		var pass = $('#pass').val();
		pass = hex_md5(pass);
		var datos = "login=&usuario="+usr+"&pass="+pass;
		$.ajax({
			type: "POST",
			url: "SysI.php",
			data: datos,
            dataType: "XML",
			success: function(msg){
				resultado = setXMLMensaje(msg);
				if(resultado == "ok")
                    location.reload(true);
			}
		});
	}

	function cambiar(){
		$.ajax({
			type: "POST",
			url: "GestionUsuarioI.php",
			data: "redefinePass=&mail="+$('#mail').val()+"&usrname="+$('#usrname').val()+"&codigo="+$('#codigo').val(),
                        dataType: "XML",
			success: function(msg){
				setXMLMensaje(msg);
			}
		});
	}
    
    function ParaCambiarPass(){
        if(getUrlVars('recuperar') == 'recuperar')
            ShowOne('forma', 'forma2');
        else
            ShowOne('forma', 'forma1');
    }
	
	$(document).ready(function(){
        ParaCambiarPass();
		
		$('#usuario').focus();
		$("#forma1 a").click(function(){
            ShowHide('forma', 'forma1', 'forma2');
		});
		
		$("#back").click(function(){
            ShowHide('forma', 'forma2', 'forma1');
		});
		
		$('#logear').submit(function(){
			logear();
		});
		
		$('#send').submit(function(){
			cambiar();
		});
		
	});