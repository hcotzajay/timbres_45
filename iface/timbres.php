<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WSLib.php';

    if (isset($_SESSION['idusr'])) {

        if(isset($_POST['ingresotimbres'])){
            echo WSLib::Invocar('timbres', 'TimbresC', 'Insert3', $_POST, $_SESSION['idusr']);
        }elseif(isset ($_POST['selectnotario'])){
            echo WSLib::Invocar('timbres', 'NotarioC', 'ListaNotarios', $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['lognotario'])){
            echo WSLib::Invocar('timbres', 'NotarioC', 'Load2', $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['bus_gbm'])){
            echo WSLib::Invocar('timbres', 'NotarioC', 'GBM_Consulta' , $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['bus_documento'])){
            echo WSLib::Invocar('timbres', 'TimbresC', 'ListaTimbres', $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['edit_document'])){
            echo WSLib::Invocar('timbres', 'TimbresC', 'Load3', $_POST, $_SESSION['idusr']);
        }elseif(isset ($_POST['editor_documento'])){
            echo WSLib::Invocar('timbres', 'TimbresC', 'Update', $_POST, $_SESSION['idusr']);
        }elseif(isset ($_POST['egre_document'])){
            echo WSLib::Invocar('timbres', 'TimbresC', 'Load2', $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['selectrecep'])){
            echo WSLib::Invocar('timbres', 'ReceptorC', 'ListaReceptor', $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['cargarreceptor'])){
            echo WSLib::Invocar('timbres', 'ReceptorC', 'Load2', $_POST, $_SESSION['idusr']);
        }elseif(isset($_POST['in_egresor'])){

            echo WSLib::Invocar('timbres', 'ReceptorC', 'InsertID', $_POST, $_SESSION['idusr']);

        }elseif(isset($_POST['in_egreso'])){
            echo WSLib::Invocar('timbres', 'TimbresC', 'Update2', $_POST, $_SESSION['idusr']);
        }elseif(isset($_GET['imprecion'])){
            echo WSLib::Invocar("timbres", "ReceptorC", "Recibo", $_GET);
        }elseif(isset($_GET['selectimp'])){
            echo WSLib::Invocar("timbres", "ReceptorC", "SelectR", $_GET);
        } elseif(isset($_GET['migrar'])){
            echo WSLib::Invocar('timbres', 'NotarioC', 'GBM_Consulta2',$_GET);
        }
    }else{
        echo '';
    }
