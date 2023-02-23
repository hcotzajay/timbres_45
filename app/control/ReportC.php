<?php
    include_once dirname(__FILE__).'/../querys/TimbresBDQuery.php';
    include_once dirname(__FILE__).'/../model/TimbresBDItem.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WebComm.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/DataGrid2.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Personal.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Empleados.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Utiles.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/timbres/DotEnv.php';

class ReportC extends WebComm{

        function __construct($data = null)
        {
            $this->__tabla = "documento";
            $this->__objeto = "TimbresBDItem";
        }

        function ListadoReporte()
        {
           $grid = new DataGrid2();
            $grid->cabecera = [
                'No. Documento',
                'Sticker',
                'Colegiado',
                'Notario',
                'Valor',
                'Fecha de ingreso',
                'Fecha de Egreso',
                'Estado del Documento',
                'Egresor'
            ];
            $grid->campos = [
                'numero_documento',
                'sticker',
                'colegiado',
                'nombre_notario',
                'valor',
                'fecha',
                'salida',
                'estado',
                'nombre'
            ];
            $grid->keys=array('id');
            $grid->porPaginas = 10;
            $grid->actual= $this->__data['page'];
            $grid->enTotal= TimbresBDQuery::Consultar('ReportQ', 'CuentaReport', $this->__data);
            $this->__data['inicio']=$grid->porPaginas*($grid->actual-1);
            $this->__data['cant']=$grid->porPaginas;
            $grid->addActionCol('Reporte Exel', 'reporte', 'images/icon_report.png');
            $grid->addActionCol('Reporte PDF', 'reportep', 'images/icon_report.png');
            $grid->dataset=  TimbresBDQuery::Consultar('ReportQ', 'ListadoReporte', $this->__data);

            return $grid->bind()->saveXML();
        }/*e3ece4*/

        function ListadoReporeIngresados()
        {
            $grid = new DataGrid2();
            $grid->cabecera = [
                'No. Documento',
                'Sticker',
                'Valor',
                'Notario',
                'Colegiado',
                'Fecha de ingreso',
                'Estado del Documento',
                'Colaborador que ingresó'
            ];
            $grid->campos = [
                'numero_documento',
                'sticker',
                'valor',
                'nombre_notario',
                'colegiado',
                'fecha',
                'estado',
                'colaborador'
            ];
            $grid->keys = ['id'];
            $grid->porPaginas = 15;
            $grid->actual = $this->__data['page'];
            $grid->enTotal = TimbresBDQuery::Consultar('ReportQ', 'countIngresos', $this->__data);
            $this->__data['inicio'] = $grid->porPaginas * ($grid->actual - 1);
            $this->__data['cant'] = $grid->porPaginas;
            $grid->dataset = TimbresBDQuery::Consultar('ReportQ', 'Ingresos', $this->__data);

            $idSS = $grid->dataset->getListComa('colaborador');

            $grid->dataset = $this->getColaborador($idSS, $grid->dataset);

            return $grid->bind()->saveXML();
        }

        function ListadoReporeEgresos()
        {
            $grid = new DataGrid2();
            $grid->cabecera = [
                'No. Documento',
                'Sticker',
                'Valor',
                'Notario',
                'Colegiado',
                'Fecha de ingreso',
                'Fecha de egreso',
                'Estado',
                'Usuario Egresor',
                'Colaborador que egresó'
            ];
            $grid->campos = [
                'numero_documento',
                'sticker',
                'valor',
                'nombre_notario',
                'colegiado',
                'ingreso',
                'egreso',
                'estado',
                'egresor',
                'colaborador'
            ];
            $grid->keys = ['id'];
            $grid->porPaginas = 15;
            $grid->actual = $this->__data['page'];
            $grid->enTotal = TimbresBDQuery::Consultar('ReportQ', 'countEgresos', $this->__data);
            $this->__data['inicio'] = $grid->porPaginas * ($grid->actual - 1);
            $this->__data['cant'] = $grid->porPaginas;
            $grid->dataset = TimbresBDQuery::Consultar('ReportQ', 'Egresos', $this->__data);

            $idSS = $grid->dataset->getListComa('colaborador');

            $grid->dataset = $this->getColaborador($idSS, $grid->dataset);

            return $grid->bind()->saveXML();
        }

        function ReportP()
        {
            $PDF = TimbresBDQuery::Consultar('ReportQ', 'ListadoReporte', $_GET);
            $datos = "<style>
                .bpmTopnTailC {
                background-color: #fff;
                topntail: 0.02cm solid #495b4a;
                }
                .headerrow td, .headerrow th {
                background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2;
                }
                .oddrow td, .oddrow th {
                background-color: #e3ece4;
                }
                .evenrow td, .evenrow th {
                background-color: #f5f8f5;
                }
                .time{
                        text-align: right;
                }
            </style><table class='bpmTopnTailC'>";
            $mes = "";
            $año = "";
            foreach ($PDF->data as $row) {
                if ($row['anuos'] != $año) {
                    $datos .= "<tr class='headerrow'><th class='bpmTopnTail' colspan='9'>A&ntilde;o ".$row['anuos']."</th></tr>";
                    $año = $row['anuos'];
                    $mes = "";
                }
                switch ($row['mes']) {
                    case 1:
                        $Nmes = "Enero";
                        break;
                    case 2:
                        $Nmes = "Febrero";
                        break;
                    case 3:
                        $Nmes = "Marzo";
                        break;
                    case 4:
                        $Nmes = "Abril";
                        break;
                    case 5:
                        $Nmes = "Mayo";
                        break;
                    case 6:
                        $Nmes = "Junio";
                        break;
                    case 7:
                        $Nmes = "Julio";
                        break;
                    case 8:
                        $Nmes = "Agosto";
                        break;
                    case 9:
                        $Nmes = "Septiembre";
                        break;
                    case 10:
                        $Nmes = "Octubre";
                        break;
                    case 11:
                        $Nmes = "Noviembre";
                        break;
                    case 12:
                        $Nmes = "Diciembre";
                        break;
                }
                if ($mes != $row['mes']) {
                    $datos .= "<tr class='Cheaderrow'><th class='bpmTopnTail' colspan='9'>Mes de ".$Nmes."</th></tr>
                            <tr class='evenrow'><td>No. Documento</td><td>No.Sticker</td><td>Valor</td>
                            <td>Notario</td><td>Colegiado</td><td>Fecha Ingreso</td>
                        <td>Fecha Entrega</td><td>Estado Timbre</td><td>Egresado por</td></tr>";
                    $mes = $row['mes'];
                }
                $mes1 = ("<tr class='oddrow'><td>".$row['numero_documento'].
                    "</td><td>".$row['sticker'].
                    "</td><td>".$row['valor'].
                    "</td><td>".$row['nombre_notario'].$row['apellido_notario'].
                    "</td><td>".$row['colegiado'].
                    "</td><td>".$row['fecha'].
                    "</td><td>".$row['salida'].
                    "</td><td>".$row['estado'].
                    "</td><td>".$row['nombre']."</td></tr>");

                $datos .= $mes1;
            }
            $env = new DotEnv();
            $hola = "<div class='contenedor'>
                    <div>
                        <table>
                            <tr>
                                <td>
                                    <img style='vertical-align: top' src='http://".$env->get_IP_SERVER()."/librerias/css/images/logito.png'width='80'/>
                                </td>
                                <td>
                                    REGISTRO GENERAL DE LA PROPIEDAD<BR/>
                                    REPORTE SISTEMA DE TIMBRES
                                </td>
                            </tr>
                       </table>
                   </div>
                <div class='time'> ".date('d-m-Y H:i:s')."</div>";

            $datos .= "</table>";
            $datos = sprintf($datos, $datos);

            return $hola.$datos;
        }

        public function ReportE()
        {
            $Nmes="";
            $dataset = TimbresBDQuery::Consultar('ReportQ', 'ListadoReporte',$_GET);
            #$cuenta = TimbresBDQuery::Consultar('ReportQ', 'CuantosActivos', $_GET);
            $dom = new DOMDocument();
            $datos = $dom->appendChild($dom->createElement('datos'));
            foreach($dataset->data as $row){
                switch ($row['mes']) {
                    case 1:
                        $Nmes = "Enero";
                        break;
                    case 2:
                        $Nmes = "Febrero";
                        break;
                    case 3:
                        $Nmes = "Marzo";
                        break;
                    case 4:
                        $Nmes = "Abril";
                        break;
                    case 5:
                        $Nmes = "Mayo";
                        break;
                    case 6:
                        $Nmes = "Junio";
                        break;
                    case 7:
                        $Nmes = "Julio";
                        break;
                    case 8:
                        $Nmes = "Agosto";
                        break;
                    case 9:
                        $Nmes = "Septiembre";
                        break;
                    case 10:
                        $Nmes = "Octubre";
                        break;
                    case 11:
                        $Nmes = "Noviembre";
                        break;
                    case 12:
                        $Nmes = "Diciembre";
                        break;
                }
                $fila = $datos->appendChild($dom->createElement('fila'));
                $campo = $fila->appendChild($dom->createElement('Año'));
                $campo->appendChild($dom->createTextNode($row['anuos']));
                $campo = $fila->appendChild($dom->createElement('Mes'));
                $campo->appendChild($dom->createTextNode($Nmes));
                $campo = $fila->appendChild($dom->createElement('Documento'));
                $campo->appendChild($dom->createTextNode($row['numero_documento']));
                $campo = $fila->appendChild($dom->createElement('Sticker'));
                $campo->appendChild($dom->createTextNode($row['sticker']));
                $campo = $fila->appendChild($dom->createElement('Notario'));
                $campo->appendChild($dom->createTextNode($row['nombre_notario']." ".$row['apellido_notario']));
                $campo = $fila->appendChild($dom->createElement('Colegiado'));
                $campo->appendChild($dom->createTextNode($row['colegiado']));
                $campo = $fila->appendChild($dom->createElement('Valor'));
                $campo->appendChild($dom->createTextNode($row['valor']));
                $campo = $fila->appendChild($dom->createElement('Fecha_Ingreso'));
                $campo->appendChild($dom->createTextNode($row['fecha']));
                $campo = $fila->appendChild($dom->createElement('Estado_Documento'));
                $campo->appendChild($dom->createTextNode($row['estado']));
                $campo = $fila->appendChild($dom->createElement('Egresor'));
                $campo->appendChild($dom->createTextNode($row['nombre']));
                $campo = $fila->appendChild($dom->createElement('Fecha_de_Egreso'));
                $campo->appendChild($dom->createTextNode($row['salida']));
            }
                #$campo = $fila->appendChild($dom->createElement('Cantidad_de_timbres_Activos'));
                #$campo->appendChild($dom->createTextNode($cuenta));
            return $dom->saveXML();
        }

        public function ingresosPDF()
        {
            $img = dirname(__FILE__).'/../../images/logoHD200.png';
            $html = '<div class="logo"><img src="'.$img.'"/></div>';
            $html .= '<div class="leyendaRGP">REGISTRO GENERAL DE LA PROPIEDAD</div>';

            $data = TimbresBDQuery::Consultar('ReportQ', 'Ingresos', $_GET);

            $idSS = $data->getListComa('colaborador');

            $data = $this->getColaborador($idSS, $data);

            $table = '<table style="width:100%; padding-top: 2%;" class="myTable">';
            $table .= '<tr><th colspan="9">Timbres ingresados el día '.$_GET['date'].'</th></tr>
                        <tr style="font-weight: bold;">
                            <th>No. Documento</th>
                            <th>No.Sticker</th>
                            <th>Valor</th>
                            <th>Notario</th>
                            <th>Colegiado</th>
                            <th>Fecha Ingreso</th>
                            <!--<th>Fecha Entrega</th>-->
                            <th>Estado Timbre</th>
                            <th>Colaborador que ingresó</th>
                        </tr>';

            foreach ($data->data as $row) {
                $table .= "<tr><td>".$row['numero_documento'].
                    "</td><td>".$row['sticker'].
                    "</td><td>Q ".number_format($row['valor'], 2).
                    "</td><td>".$row['nombre_notario'].' '.$row['apellido_notario'].
                    "</td><td>".$row['colegiado'].
                    "</td><td>".$row['fecha'].
                    /*"</td><td>".$row['ingreso'].*/
                    /*"</td><td>".$row['egreso'].*/
                    "</td><td>".$row['estado'].
                    "</td><td>".$row['colaborador']."</td></tr>";
            }
            $table .= '</table>';

            $time = '<div style="width: 100%; text-align: right;"> Reporte Generado el '.date("d-m-Y H:i:s").'</div>';

            $html = $html.$time.$table;

            return $html;
        }

        public function ingresosExcel()
        {
            $DBdata = TimbresBDQuery::Consultar('ReportQ', 'Ingresos', $_GET);

            $idSS = $DBdata->getListComa('colaborador');

            $DBdata = $this->getColaborador($idSS, $DBdata);

            $array = [];

            foreach ($DBdata->data as $key => $row) {
                $array[$key] = [
                    $row['numero_documento'],
                    $row['sticker'],
                    $row['valor'],
                    $row['nombre_notario'].' '.$row['apellido_notario'],
                    $row['colegiado'],
                    $row['fecha'],
                    $row['estado'],
                    $row['colaborador']
                ];
            }

            return $array;
        }

        public function egresosPDF()
        {
            $img = dirname(__FILE__).'/../../images/logoHD200.png';
            $html = '<div class="logo"><img src="'.$img.'"/></div>';
            $html .= '<div class="leyendaRGP">REGISTRO GENERAL DE LA PROPIEDAD</div>';

            $data = TimbresBDQuery::Consultar('ReportQ', 'Egresos', $_GET);

            $idSS = $data->getListComa('colaborador');

            $data = $this->getColaborador($idSS, $data);

            $table = '<table style="width:100%; padding-top: 2%;" class="myTable">';
            $table .= '<tr><th colspan="9">Timbres egresados el día '.$_GET['date'].'</th></tr>
                        <tr style="font-weight: bold;">
                            <th>No. Documento</th>
                            <th>No.Sticker</th>
                            <th>Valor</th>
                            <th>Notario</th>
                            <th>Colegiado</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Entrega</th>
                            <!--<th>Estado Timbre</th>-->
                            <th>Usuario Egresor</th>
                            <th>Colaborador que egresó</th>
                        </tr>';

            foreach ($data->data as $row) {
                $table .= "<tr><td>".$row['numero_documento'].
                    "</td><td>".$row['sticker'].
                    "</td><td>Q ".number_format($row['valor'], 2).
                    "</td><td>".$row['nombre_notario'].' '.$row['apellido_notario'].
                    "</td><td>".$row['colegiado'].
                    "</td><td>".$row['ingreso'].
                    "</td><td>".$row['egreso'].
                    "</td><td>".$row['egresor'].
                    "</td><td>".$row['colaborador']."</td></tr>";
            }
            $table .= '</table>';

            $time = '<div style="width: 100%; text-align: right;"> Reporte Generado el '.date("d-m-Y H:i:s").'</div>';

            $html = $html.$time.$table;

            return $html;
        }

        public function egresosExcel()
        {
            $DBdata = TimbresBDQuery::Consultar('ReportQ', 'Egresos', $_GET);

            $idSS = $DBdata->getListComa('colaborador');

            $DBdata = $this->getColaborador($idSS, $DBdata);

            $array = [];

            foreach ($DBdata->data as $key => $row) {
                $array[$key] = [
                    $row['numero_documento'],
                    $row['sticker'],
                    $row['valor'],
                    $row['nombre_notario'].' '.$row['apellido_notario'],
                    $row['colegiado'],
                    $row['ingreso'],
                    $row['egreso'],
                    $row['egresor'],
                    $row['colaborador']
                ];
            }

            return $array;
        }

        /**
         * @param $idSS     lista de ids de seguridad
         * @param $grid     grid con la data
         *
         * @return mixed    grid con la data actualizada
         */
        private function getColaborador($idSS, $grid)
        {
            /**
             * con una lista de id de usuarios del SS se sustituyen por nombres de los colaboradores
             */
            if ($idSS != '') {
                $listadoSS = Personal::DatosUsuarioSSImpl($idSS);
                $prefix = '';
                $idRH = '';
                foreach ($listadoSS as $codigo) {
                    $idRH .= $prefix.'"'.$codigo['codigorh'].'"';
                    $prefix = ', ';
                }

                if ($idRH != '') {
                    $listado = Empleados::IndexByCodigos($idRH);
                    Utiles::SustituirDatos($grid, $listadoSS, 'colaborador', 'codigorh');
                    Utiles::SustituirDatos($grid, $listado, 'colaborador', 'nombre');
                }
            }

            return $grid;
        }

    }
