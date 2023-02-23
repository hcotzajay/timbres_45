<?php
    date_default_timezone_set('America/Guatemala');
    /**
     * Created by PhpStorm.
     * User: lorozco
     * Date: 18/10/2016
     * Time: 03:24 PM
     */
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/PHPExcel/PHPExcel.php';

    class PlantillaExcel
    {
        private $objPHPExcel         = '';
        private $nameSystem          = '';
        private $nameReport          = '';
        private $styleTituloReporte  = [];
        private $styleTituloColumnas = [];
        private $leyendaRGP          = 'REGISTRO GENERAL DE LA PROPIEDAD';
        private $nameDepto           = '';

        function __construct()
        {
            $this->objPHPExcel = new PHPExcel;
        }

        function setParams($nameSystem = null, $nameDepto = null, $nameReport = null)
        {
            $this->nameSystem = $nameSystem;
            $this->nameDepto = $nameDepto;
            $this->nameReport = $nameReport;

            $this->setProperties();
        }

        /**
         * Se asignan las propiedades del libro
         */
        private function setProperties()
        {
            $this->objPHPExcel->getProperties()->setCreator("Registro General de la Propiedad"); // Nombre del autor
            $this->objPHPExcel->getProperties()->setLastModifiedBy("Registro General de la Propiedad"); //Ultimo usuario que lo modificó
            $this->objPHPExcel->getProperties()->setTitle("Reporte del Systema de ".$this->nameSystem); // Titulo
            $this->objPHPExcel->getProperties()->setSubject($this->nameDepto); //Asunto
            $this->objPHPExcel->getProperties()->setDescription($this->nameReport); //Descripción
            $this->objPHPExcel->getProperties()->setKeywords("reporte rgp"); //Etiquetas
            $this->objPHPExcel->getProperties()->setCategory("Reporte excel"); //Categorias
        }

        private function setStyle($rangeLimit)
        {
            $this->styleTituloReporte = [
                'font'      => [
                    'name'   => 'Verdana',
                    'bold'   => true,
                    'italic' => false,
                    'strike' => false,
                    'size'   => 16,
                    'color'  => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill'      => [
                    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => [
                        'argb' => '013B5E'
                    ]
                ],
                'borders'   => [
                    'allborders' => [
                        'style' => PHPExcel_Style_Border::BORDER_NONE
                    ]
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'rotation'   => 0,
                    'wrap'       => true
                ]
            ];
            $this->styleTituloColumnas = [
                'font'      => [
                    'name'  => 'Arial',
                    'bold'  => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill'      => [
                    'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation'   => 90,
                    'startcolor' => [
                        'rgb' => '6098C8'
                    ],
                    'endcolor'   => [
                        'argb' => 'FF431a5d'
                    ]
                ],
                'borders'   => [
                    'top'    => [
                        'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => [
                            'rgb' => '143860'
                        ]
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => [
                            'rgb' => '143860'
                        ]
                    ]
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap'       => true
                ]
            ];

            $rangeTituloReporte = "A1:$rangeLimit"."6";
            $rangeTituloColumnas = "A7:$rangeLimit"."7";
            $this->objPHPExcel->getActiveSheet()->getStyle($rangeTituloReporte)->applyFromArray($this->styleTituloReporte);
            $this->objPHPExcel->getActiveSheet()->getStyle($rangeTituloColumnas)->applyFromArray($this->styleTituloColumnas);

        }

        private function setHeader()
        {
            $time = time();
            $this->leyendaRGP = 'REGISTRO GENERAL DE LA PROPIEDAD';
            $date = date('d/m/Y H:i:s', $time);

            $this->objPHPExcel->getActiveSheet()
                ->setCellValue('A1', $this->leyendaRGP)
                ->setCellValue('A2', $this->nameDepto)// departamento, unidad, dirección, etc.
                ->setCellValue('A3', $this->nameSystem)
                ->setCellValue('A4', $this->nameReport)
                ->setCellValue('A5', $date);

            $this->setLogo();
        }

        private function setLogo()
        {
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('logo_rgp');
            $objDrawing->setDescription('Logo del RGP');
            $objDrawing->setPath(__DIR__.'/../images/logoHD200_white.png');
            $objDrawing->setHeight(150);
            $objDrawing->setCoordinates('A1');
            $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
        }

        function setTitlesColumns(array $columns, array $formatCell, $blankSpace = 0)
        {
            $range = range('A', 'Z');
            $rangeLimit = '';
            $blanks = '';

            for ($i = 0; $i < $blankSpace; $i++) {
                $blanks .= ' ';
            }

            foreach ($columns as $key => $column) {
                //$object->setCellValue($char."3", $columns[$key]);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension($range[$key])->setAutoSize(true);
                $this->objPHPExcel->getActiveSheet()->setCellValue($range[$key]."7", $column.$blanks);
                $rangeLimit = $range[$key];
            }

            $this->cellCombination($rangeLimit);
            $this->setStyle($rangeLimit);
            $this->setHeader();

            foreach ($formatCell as $formatColumn) {
                foreach ($columns as $key => $column) {
                    if (strtoupper($formatColumn) == strtoupper($column)) {
                        $this->objPHPExcel->getActiveSheet()->getStyle($range[$key])->getNumberFormat()->setFormatCode('Q '.'#,##0.00');
                    }
                }
            }

            // Inmovilizar paneles
            // 'A1' to 'A7'
            $this->objPHPExcel->getActiveSheet()->freezePaneByColumnAndRow(0, 8);

        }

        private function cellCombination($rangeLimit)
        {
            // Se combinan las celdas A1 hasta el límite del rango, para colocar ahí el titulo del reporte
            for ($i = 1; $i <= 6; $i++) {
                $range = "A$i:$rangeLimit"."$i";
                $this->objPHPExcel->getActiveSheet()->mergeCells($range);
            }
        }

        function setNameSheet($name)
        {
            // Se asigna el nombre a la hoja
            $this->objPHPExcel->getActiveSheet()->setTitle($name);
        }

        function setActiveSheet($index = 0)
        {
            if ($index != 0) {
                for ($i = 0; $i < $index; $i++) {
                    // Add new sheet
                    $this->objPHPExcel->createSheet();
                }
            }
            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $this->objPHPExcel->setActiveSheetIndex($index);
        }

        function getObjectPHPExcel()
        {
            return $this->objPHPExcel;
        }

        function setData(array $data)
        {
            $range = range('A', 'Z');

            foreach ($data as $i => $row) {
                foreach ($row as $keyColumn => $column) {
                    //echo $range[$keyColumn].($i+8).$column.'<br>';
                    $this->objPHPExcel->getActiveSheet()->setCellValue($range[$keyColumn].($i + 8), $column);
                    //echo $range[$keyColumn].'---'.$formatCell.'<br>';

                }
            }
        }

    }
