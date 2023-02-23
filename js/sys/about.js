	$(document).ready(function(){
        ShowOne('forma', 'forma1');
		$.ajax({
			type: "POST",
			url: "SysI.php",
			data: "about=&sis=1",
			success: function(msg){
				if(window.DOMParser){
					var parser = new DOMParser();
					var xmlDoc = parser.parseFromString(msg, "text/xml");
					var objeto = xmlDoc.getElementsByTagName("sistema");
					var id = objeto[0].getElementsByTagName("id")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("id")[0].childNodes[0].nodeValue;
					var nombre = objeto[0].getElementsByTagName("nombre")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("nombre")[0].childNodes[0].nodeValue;
					var version_sistema = objeto[0].getElementsByTagName("version_sistema")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("version_sistema")[0].childNodes[0].nodeValue;
					var descripcion = objeto[0].getElementsByTagName("descripcion")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("descripcion")[0].childNodes[0].nodeValue;
					var desarrollado_por = objeto[0].getElementsByTagName("desarrollado_por")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("desarrollado_por")[0].childNodes[0].nodeValue;
					var contacto_tecnico = objeto[0].getElementsByTagName("contacto_tecnico")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("contacto_tecnico")[0].childNodes[0].nodeValue;
					var telefono_soporte = objeto[0].getElementsByTagName("telefono_soporte")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("telefono_soporte")[0].childNodes[0].nodeValue;
					var email_contacto = objeto[0].getElementsByTagName("email_contacto")[0].childNodes[0] == undefined ? "" : objeto[0].getElementsByTagName("email_contacto")[0].childNodes[0].nodeValue;
				}else{
					var xmlDoc = new ActiveXObject("Msxml2.DOMDocument.6.0");
					xmlDoc.async="false";
					xmlDoc.loadXML(msg);
					var objeto = xmlDoc.getElementsByTagName("m_sistema")[0];
					var id = objeto.getElementsByTagName("id")[0].text;
					var nombre = objeto.getElementsByTagName("nombre")[0].text;
					var version_sistema = objeto.getElementsByTagName("version_sistema")[0].text;
					var descripcion = objeto.getElementsByTagName("descripcion")[0].text;
					var desarrollado_por = objeto.getElementsByTagName("desarrollado_por")[0].text;
					var contacto_tecnico = objeto.getElementsByTagName("contacto_tecnico")[0].text;
					var telefono_soporte = objeto.getElementsByTagName("telefono_soporte")[0].text;
					var email_contacto = objeto.getElementsByTagName("email_contacto")[0].text;
				}
				$('#sisName').html(nombre);
				
				var txt = "<p>"+descripcion+"</p>";
				txt += "<p>Version actual: "+version_sistema+"</p>";
				txt += "<p>Desarrollado por: "+desarrollado_por+"</p>";
				txt += "<p>Contacto técnico: "+contacto_tecnico+"</p>";
				txt += "<p>Telefono de soporte: "+telefono_soporte+"</p>";
				txt += "<p>Correo electrónico: "+email_contacto+"</p>";
				
				$('#descripcion').html(txt);
			}
		});
	});