<?php 
include_once dirname(__FILE__).'/app/control/BitacoraC.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WSLib.php';

	if(isset($_POST['tablas'])){
		echo WSLib::Invocar("timbres", "BitacoraC", "Tablas");
                
	}elseif(isset($_POST['campo'])){
		echo WSLib::Invocar("timbres", "BitacoraC", "Campo");
                
	}elseif(isset($_POST['reporte'])){
		echo WSLib::Invocar("timbres", "BitacoraC", "Reporte", $_POST);
		
	}elseif(isset($_GET['generareporte'])){
            header("Content-type: application/x-msdownload");
            header("Content-Disposition: attachment; filename=Bitacora.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo WSLib::Invocar("timbres", "BitacoraC", "GeneraReporte", $_GET);
            
	}elseif(isset($_POST['operacion'])){
		echo WSLib::Invocar("timbres", "BitacoraC", "Operaciones");

	}
?>
