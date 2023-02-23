<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WSLib.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/mpdf/mpdf.php';

if(isset($_SESSION['idusr'])){
    if(isset($_POST['listar'])){
        echo WSLib::Invocar('timbres', 'HistoricoC', 'Listado', $_POST, $_SESSION['idusr']);
    }elseif(isset($_GET['generarcvr'])){
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Historico.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo WSLib::Invocar('timbres', 'HistoricoC', 'HistoricoE', $_GET);
    }elseif(isset($_GET['generarpdf'])){
        $mpdf=new mPDF('c','A5');
        $mpdf->SetFooter('{DATE j-m-Y}|{PAGENO}/{nb}|Sistema de Timbres');
        $mpdf->WriteHTML(WSLib::Invocar('timbres', 'HistoricoC', 'HistoricoP', $_GET));
        $mpdf->Output();
    }else if(isset($_GET['pbus'])){
        echo WSLib::Invocar('timbres', 'HistoricoC', 'SelectR', $_GET);
    }else if(isset($_GET['ppri'])){
        echo WSLib::Invocar('timbres', 'HistoricoC', 'Recibo', $_GET);
    }
}else{
    header("Location: ../");
}
