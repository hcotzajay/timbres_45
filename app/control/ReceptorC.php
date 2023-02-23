<?php
include_once dirname(__FILE__).'/../querys/TimbresBDQuery.php';
include_once dirname(__FILE__).'/../model/TimbresBDItem.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WebComm.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/DataGrid2.php';

class ReceptorC extends WebComm{
    public function __construct($data = null) {
        $this->__tabla = "receptor_documento";
        $this->__objeto = "TimbresBDItem";
    }
    public function ListaReceptor(){
        $grid=new DataGrid2();
        $grid->cabecera=array('Nombre', 'Apellido', 'Correo','DPI','Cedula');
        $grid->campos=array('nombre','apellido','correo','dpi','cedula');
        $grid->keys=array('dpi');
        $grid->porPaginas = 10;
        $grid->actual=  $this->__data['page'];
        $grid->enTotal=  TimbresBDQuery::Consultar('ReceptorQ', 'CuentaReceptor', $this->__data);
        $this->__data['inicio']=$grid->porPaginas*($grid->actual-1);
        $this->__data['cant']=$grid->porPaginas;
        $grid->addActionCol('Seleccionar', 'seleccion', 'images/icon-check.png');
        $grid->dataset= TimbresBDQuery::Consultar('ReceptorQ', 'ListadoReceptor', $this->__data);
        return $grid->bind()->saveXML();
    }
    public function Recibo(){
        $dataset = TimbresBDQuery::Consultar("ReceptorQ", "Imprimir", $this->__data);
        $dom = new DOMDocument();
        $datos = $dom->appendChild($dom->createElement("datos"));
        foreach ($dataset->data as $row){
            $fila = $datos->appendChild($dom->createElement('fila'));
            $campo = $fila->appendChild($dom->createElement('documento'));
            $campo->appendChild($dom->createTextNode($row['numero_documento']));
            $campo = $fila->appendChild($dom->createElement("sticker"));
            $campo->appendChild($dom->createTextNode($row["sticker"]));
            $campo = $fila->appendChild($dom->createElement("valor"));
            $campo->appendChild($dom->createTextNode($row["valor"]));
            $campo = $fila->appendChild($dom->createElement("notario"));
            $campo->appendChild($dom->createTextNode($row["nombre_notario"]." ".$row["apellido_notario"]));
            $campo = $fila->appendChild($dom->createElement("colegiado"));
            $campo->appendChild($dom->createTextNode($row["colegiado"]));
            $campo = $fila->appendChild($dom->createElement("egresor"));
            $campo->appendChild($dom->createTextNode($row["nombre"]));
            $campo = $fila->appendChild($dom->createElement("identificacion"));
            $campo->appendChild($dom->createTextNode($row["cedula"].$row["carnet"].$row["dpi"]));
            $campo = $fila->appendChild($dom->createElement("fecha"));
            $campo->appendChild($dom->createTextNode($row["fecha_ingreso"]));
            $campo = $fila->appendChild($dom->createElement("usuario"));
            $campo->appendChild($dom->createTextNode($row["colaborador"]));
        }
        return $dom->saveXML();
    }

    public function SelectR(){
        $dataset =  TimbresBDQuery::Consultar("ReceptorQ", "Imprimir", $this->__data);
        $dom = new DOMDocument();
        $datos = $dom->appendChild($dom->createElement("datos"));
        foreach ($dataset->data as $row){
            $fila = $datos->appendChild($dom->createElement("fila"));
            $campo = $fila->appendChild($dom->createElement("id"));
            $campo->appendChild($dom->createTextNode($row["id"]));
            $campo = $fila->appendChild($dom->createElement("documento"));
            $campo->appendChild($dom->createTextNode($row["numero_documento"]));
            $campo = $fila->appendChild($dom->createElement("sticker"));
            $campo->appendChild($dom->createTextNode($row["sticker"]));
            $campo = $fila->appendChild($dom->createElement("notario"));
            $campo->appendChild($dom->createTextNode($row["nombre_notario"]." ".$row["apellido_notario"]));
            $campo = $fila->appendChild($dom->createElement("egresor"));
            $campo->appendChild($dom->createTextNode($row['nombre']." ".$row['apellido']));
        }
        return $dom->saveXML();
    }
}
?>