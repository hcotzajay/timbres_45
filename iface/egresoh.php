<?php
	session_start();
	include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WSLib.php';

	if(isset($_SESSION['idusr'])){

	    if(isset($_POST['listar'])){
	        echo WSLib::Invocar('timbres', 'EgresohC', 'Listado', $_POST, $_SESSION['idusr']);
	    }else if(isset($_POST['bus_doc'])){
	        echo WSLib::Invocar('timbres', 'EgresohC', 'Load2', $_POST);
	    }else if(isset($_POST['egreso'])){
	        echo WSLib::Invocar('timbres', 'EgresohC', 'Update2', $_POST, $_SESSION['idusr']);
	    }
	}else{
	    header("Location: ../");
	}
