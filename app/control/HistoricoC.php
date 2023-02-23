<?php
include_once dirname(__FILE__).'/../querys/TimbresBDQuery.php';
include_once dirname(__FILE__).'/../model/TimbresBDItem.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WebComm.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/DataGrid2.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/timbres/DotEnv.php';

class HistoricoC extends WebComm{
    function __construct($data = null) {
        $this->__tabla = "historico";
        $this->__objeto = "TimbresBDItem";
    }
    function Listado(){
        $grid = new DataGrid2();
        $grid->cabecera = array('No. Documento','Sticker','Colegiado','Notario','Valor','Fecha de ingreso','Fecha de Egreso','Estado del Documento','Egresor');
        $grid->campos = array('nodocumento','sticker','colegiado','nombrenotario','valor','fechaingreso','fechaegreso','egresados','nombrerecibe');
        $grid->keys = array('id');
        $grid->porPaginas = 10;
        $grid->actual= $this->__data['page'];
        $grid->enTotal = TimbresBDQuery::Consultar('HistoricoQ', 'Cuantos', $this->__data);
        $this->__data['inicio']=$grid->porPaginas*($grid->actual-1);
        $this->__data['cant']=$grid->porPaginas;
        $grid->addActionCol('Reporte Exel', 'reporte', 'images/icon_report.png');
        $grid->addActionCol('Reporte PDF', 'reportep', 'images/icon_report.png');
        $grid->dataset=  TimbresBDQuery::Consultar('HistoricoQ', 'Listado', $this->__data);
        return $grid->bind()->saveXML();
    }
    function HistoricoE(){
    $Nmes="";
        $dataset = TimbresBDQuery::Consultar('HistoricoQ', 'Listado', $_GET);
        #$cuenta = TimbresBDQuery::Consultar('ReportQ', 'CuantosActivos', $_GET);
        $dom = new DOMDocument();
        $datos = $dom->appendChild($dom->createElement('datos'));
        foreach($dataset->data as $row){
            switch($row['mes']){
					case 1:$Nmes="Enero";
					break;
					case 2:$Nmes="Febrero";
					break;
					case 3:$Nmes="Marzo";
					break;
					case 4:$Nmes="Abril";
					break;
					case 5:$Nmes="Mayo";
					break;
					case 6:$Nmes="Junio";
					break;
					case 7:$Nmes="Julio";
					break;
					case 8:$Nmes="Agosto";
					break;
					case 9:$Nmes="Septiembre";
					break;
					case 10:$Nmes="Octubre";
					break;
					case 11:$Nmes="Noviembre";
					break;
					case 12:$Nmes="Diciembre";
					break;
				}
            $fila = $datos->appendChild($dom->createElement('fila'));
            $campo = $fila->appendChild($dom->createElement('Año'));
            $campo->appendChild($dom->createTextNode($row['inventario']));
            $campo = $fila->appendChild($dom->createElement('Mes'));
            $campo->appendChild($dom->createTextNode($Nmes));
            $campo = $fila->appendChild($dom->createElement('Documento'));
            $campo->appendChild($dom->createTextNode($row['nodocumento']));
            $campo = $fila->appendChild($dom->createElement('Sticker'));
            $campo->appendChild($dom->createTextNode($row['sticker']));
            $campo = $fila->appendChild($dom->createElement('Notario'));
            $campo->appendChild($dom->createTextNode($row['nombrenotario']));
            $campo = $fila->appendChild($dom->createElement('Colegiado'));
            $campo->appendChild($dom->createTextNode($row['colegiado']));
            $campo = $fila->appendChild($dom->createElement('Valor'));
            $campo->appendChild($dom->createTextNode($row['valor']));
            $campo = $fila->appendChild($dom->createElement('Fecha_Ingreso'));
            $campo->appendChild($dom->createTextNode($row['fechaingreso']));
            $campo = $fila->appendChild($dom->createElement('Estado_Documento'));
            $campo->appendChild($dom->createTextNode($row['inventario']));
            $campo = $fila->appendChild($dom->createElement('Egresor'));
            $campo->appendChild($dom->createTextNode($row['nombrerecibe']));
            $campo = $fila->appendChild($dom->createElement('Fecha_de_Egreso'));
            $campo->appendChild($dom->createTextNode($row['fechaegreso']));
        }
            #$campo = $fila->appendChild($dom->createElement('Cantidad_de_timbres_Activos'));
            #$campo->appendChild($dom->createTextNode($cuenta));
        return $dom->saveXML();
    }
    function HistoricoP(){
      $PDF= TimbresBDQuery::Consultar('HistoricoQ', 'Listado', $_GET);
        $datos="<style>
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
        $mes="";
        $año="";
        foreach ($PDF->data as $row){
            if($row['anuos'] != $año){
                $datos .="<tr class='headerrow'><th class='bpmTopnTail' colspan='9'>A&ntilde;o ".$row['anuos']."</th></tr>";$año=$row['anuos'];$mes="";
                }switch($row['mes']){
					case 1:$Nmes="Enero";
					break;
					case 2:$Nmes="Febrero";
					break;
					case 3:$Nmes="Marzo";
					break;
					case 4:$Nmes="Abril";
					break;
					case 5:$Nmes="Mayo";
					break;
					case 6:$Nmes="Junio";
					break;
					case 7:$Nmes="Julio";
					break;
					case 8:$Nmes="Agosto";
					break;
					case 9:$Nmes="Septiembre";
					break;
					case 10:$Nmes="Octubre";
					break;
					case 11:$Nmes="Noviembre";
					break;
					case 12:$Nmes="Diciembre";
					break;
				}
                        if($mes!=$row['mes']){$datos .="<tr class='Cheaderrow'><th class='bpmTopnTail' colspan='9'>Mes de ".$Nmes."</th></tr>
                            <tr class='evenrow'><td>No. Documento</td><td>No.Sticker</td><td>Valor</td>
                            <td>Notario</td><td>Colegiado</td><td>Fecha Ingreso</td>
                        <td>Fecha Entrega</td><td>Estado Timbre</td><td>Egresado por</td></tr>";$mes = $row['mes'];}
                        $mes1=("<tr class='oddrow'><td>".$row['nodocumento'].
                                "</td><td>".$row['sticker'].
                                "</td><td>".$row['valor'].
                                "</td><td>".$row['nombrenotario'].
                                "</td><td>".$row['colegiado'].
                                "</td><td>".$row['fechaingreso'].
                                "</td><td>".$row['fechaegreso'].
                                "</td><td>".$row['inventario'].
                                "</td><td>".$row['nombrerecibe']."</td></tr>");

                $datos .=$mes1;
        }
        $env = new DotEnv();
        $hola="<div class='contenedor'>
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

        $datos .="</table>";
        $datos= sprintf($datos,$datos);

        return $hola.$datos;
    }
    function SelectR(){
        $dataset = TimbresBDQuery::Consultar('HistoricoQ', 'Listado2', $this->__data);
        $dom = new DOMDocument();
        $datos = $dom->appendChild($dom->createElement("datos"));
        foreach ($dataset->data as $row){
            $fila = $datos->appendChild($dom->createElement('fila'));
            $campo = $fila->appendChild($dom->createElement('id'));
            $campo->appendChild($dom->createTextNode($row['id']));
            $campo = $fila->appendChild($dom->createElement('documento'));
            $campo->appendChild($dom->createTextNode($row['nodocumento']));
            $campo = $fila->appendChild($dom->createElement('sticker'));
            $campo->appendChild($dom->createTextNode($row['sticker']));
            $campo = $fila->appendChild($dom->createElement('notario'));
            $campo->appendChild($dom->createTextNode($row['nombrenotario']));
            $campo = $fila->appendChild($dom->createElement('egresor'));
            $campo->appendChild($dom->createTextNode($row['nombrerecibe']));
        }
        return $dom->saveXML();
    }
    function Recibo(){
       $dataset = TimbresBDQuery::Consultar('HistoricoQ', 'Listado2', $this->__data);
        $dom = new DOMDocument();
        $datos = $dom->appendChild($dom->createElement("datos"));
        foreach ($dataset->data as $row){
            $fila = $datos->appendChild($dom->createElement('fila'));
            $campo = $fila->appendChild($dom->createElement('documento'));
            $campo->appendChild($dom->createTextNode($row['nodocumento']));
            $campo = $fila->appendChild($dom->createElement('sticker'));
            $campo->appendChild($dom->createTextNode($row['sticker']));
            $campo = $fila->appendChild($dom->createElement('valor'));
            $campo->appendChild($dom->createTextNode($row['valor']));
            $campo = $fila->appendChild($dom->createElement('notario'));
            $campo->appendChild($dom->createTextNode($row['nombrenotario']));
            $campo = $fila->appendChild($dom->createElement('colegiado'));
            $campo->appendChild($dom->createTextNode($row['colegiado']));
            $campo = $fila->appendChild($dom->createElement('egresor'));
            $campo->appendChild($dom->createTextNode($row['nombrerecibe']));
            $campo = $fila->appendChild($dom->createElement('identificacion'));
            $campo->appendChild($dom->createTextNode($row['nocedula']));
            $campo = $fila->appendChild($dom->createElement('horaegreso'));
            $campo->appendChild($dom->createTextNode($row['fechaingreso']));
        }
        return $dom->saveXML();
    }
}
?>
