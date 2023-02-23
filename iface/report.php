<?php
session_start();
date_default_timezone_set('America/Guatemala');
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WSLib.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/mpdf/mpdf.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/MPDF56/mpdf.php';
include_once 'PlantillaExcel.php';

    if (isset($_SESSION['idusr'])) {

        if (isset($_POST['bus_report'])) {

            echo WSLib::Invocar('timbres', 'ReportC', 'ListadoReporte', $_POST, $_SESSION['idusr']);

        } elseif (isset($_POST['repoIngresos'])) {

            echo WSLib::Invocar('timbres', 'ReportC', 'ListadoReporeIngresados', $_POST, $_SESSION['idusr']);

        } elseif (isset($_POST['repoEgresos'])) {

            echo WSLib::Invocar('timbres', 'ReportC', 'ListadoReporeEgresos', $_POST, $_SESSION['idusr']);

        } elseif (isset($_GET['generarcvr'])) {

            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Reporte.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo WSLib::Invocar('timbres', 'ReportC', 'ReportE', $_GET);

        } elseif (isset($_GET['generarpdf'])) {

            $mpdf=new mPDF('c','A5');
            $mpdf->SetFooter('{DATE j-m-Y}|{PAGENO}/{nb}|Sistema de Timbres');
            $mpdf->WriteHTML(WSLib::Invocar('timbres', 'ReportC', 'ReportP', $_GET));
            $mpdf->Output();

        } elseif (isset($_GET['getPDF'])) {

            $carta = [215.9, 279.4];//8.5x11 pulgadas
            $html = WSLib::Invocar('timbres', 'ReportC', $_GET['option'].'PDF', $_GET);

            $mpdf = new mPDF('c', $carta);
            $styleSheet = file_get_contents('../css/viewPDF.css');
            $mpdf->WriteHTML($styleSheet, 1);
            $mpdf->SetFooter('{DATE j-m-Y}|{PAGENO}/{nb}|Sistema de Timbres');
            $mpdf->WriteHTML($html);
            $mpdf->Output();

        } elseif (isset($_GET['getExcel'])) {

            $tipoTimbres = '';
            $titulosColumnas = [];

            if ($_GET['option'] == 'ingresos') {

                $tipoTimbres = 'Ingresados';
                $titulosColumnas = [
                    'No. Documento',
                    'No. Sticker',
                    'Valor',
                    'Notario',
                    'Colegiado',
                    'Fecha Ingreso',
                    'Estado',
                    'Colaborador que ingresó'
                ];

            } elseif ($_GET['option'] == 'egresos') {
                $tipoTimbres = 'Egresados';
                $titulosColumnas = [
                    'No. Documento',
                    'No. Sticker',
                    'Valor',
                    'Notario',
                    'Colegiado',
                    'Fecha Ingreso',
                    'Fecha Egreso',
                    'Usuario Egresor',
                    'Colaborador que egresó'
                ];
            }

            $nameSystem = 'Sistema de Timbres';
            $nameDepto = 'Departamento de Cajas';
            $nameReport = 'Reporte de Timbres '.$tipoTimbres;
            $nameSheet = 'Timbres '.$tipoTimbres;

            $time = time();
            $nameFile = $nameReport.date(' d-m-Y_His', $time);

            $formatoColumna = [
                'valor'
            ];

            $DBdata = WSLib::Invocar('timbres', 'ReportC', $_GET['option'].'Excel', $_GET);

            $phpExcel = new PlantillaExcel();

            $phpExcel->setParams($nameSystem, $nameDepto, $nameReport);

            $phpExcel->setActiveSheet(0);

            $phpExcel->setNameSheet($nameSheet);

            $phpExcel->setTitlesColumns($titulosColumnas, $formatoColumna, 5);

            $phpExcel->setData($DBdata);

            // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$nameFile.'.xlsx"');
            header('Cache-Control: max-age=0');

            $object = $phpExcel->getObjectPHPExcel();

            $objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            $objWriter->save('php://output');
            exit;

        }
    } else {
        header("Location: ../");
    }
