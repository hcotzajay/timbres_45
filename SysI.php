<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Seguridad.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Utiles.php';

    if(isset($_POST['login'])){
	
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
   		elseif (isset($_SERVER['HTTP_VIA'])) 
			$ip = $_SERVER['HTTP_VIA'];
		elseif (isset($_SERVER['REMOTE_ADDR'])) 
			$ip = $_SERVER['REMOTE_ADDR'];
		else 
			$ip = "0.0.0.0";
                
		$dato = Seguridad::DatosLogin($_POST['usuario'], $_POST['pass'], $ip, "1");
                
		if($dato['clase'] == "ok"){
			$dato = $dato['mensaje'];
			$extras = Seguridad::DatosUsuario($dato);
			$_SESSION['idusr'] = $dato;
			$_SESSION['usrname'] = $_POST['usuario'];
			$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['REMOTE_ADDR'] = $ip;
			$_SESSION['nombre'] = $extras['nombre'];
			$_SESSION['puesto'] = $extras['puesto'];
			$_SESSION['unidad'] = $extras['unidad'];
			$_SESSION['codigoRH'] = $extras['codigo'];
            $_SESSION['agencia'] = $extras['agencia'];
			echo Utiles::generaMensaje("Ingresando" ,"ok");
		}else
			echo Utiles::generaMensaje($dato['mensaje'], $dato['clase']);
		
	}elseif(isset($_POST['about'])){
		echo Seguridad::DatosSistemaXML($_POST['sis']);
		
	}elseif(isset($_POST['ayuda'])){
        echo Seguridad::GetAyuda($_POST['url'], $_POST['alias']);
        
    }
?>