<?php
include_once dirname(__FILE__).'/../querys/TimbresBDQuery.php';
include_once dirname(__FILE__).'/../model/TimbresBDItem.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WebComm.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/DataGrid2.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/Notarios.php';

class NotarioC extends WebComm{
    public function __construct($data = null) {
        $this->__tabla = "notario";
        $this->__objeto = "TimbresBDItem";
    }
    public function ListaNotarios(){
        $grid=new DataGrid2();
        $grid->cabecera=array('Colegiado','Nombre','Apellido');
        $grid->campos=array('colegiado','nombre_notario','apellido_notario');
        $grid->keys=array('colegiado');
        $grid->porPaginas = 10;
        $grid->actual=$this->__data['page'];
        $grid->enTotal= TimbresBDQuery::Consultar('NotarioQ', 'CuentaNotario', $this->__data);
        $this->__data['inicio']=$grid->porPaginas*($grid->actual-1);
        $this->__data['cant']=$grid->porPaginas;
        $grid->addActionCol('Seleccionar', 'seleccion', 'images/icon-check.png');
        $grid->dataset=  TimbresBDQuery::Consultar('NotarioQ', 'ListaNotario', $this->__data);
        return $grid->bind()->saveXML();
    }
    public function GBM_Consulta(){
        $datos['colegiado'] = $this->__data['colegiado'];
        $data['no_colegiado'] = $this->__data['colegiado'];
        $data['nombre_notario'] = '';
        $resul = Notarios::obtenerNotario($data);
        $notario=new DOMDocument();
        $notario->loadXML($resul);
        $nombres=$notario->getElementsByTagName('nombre_notario');
        $datos['nombre_notario'] = $nombres->item(0)->nodeValue;
        $apellidos=$notario->getElementsByTagName('apellido_notario');
        $datos['apellido_notario'] = $apellidos->item(0)->nodeValue;
        $direccion=$notario->getElementsByTagName('direccion_notario');
        $datos['direccion'] = $direccion->item(0)->nodeValue;
        $email=$notario->getElementsByTagName('email_notario');
        $datos['correo_electronico'] = $email->item(0)->nodeValue;
        $identificacion=$notario->getElementsByTagName('identificacion_notario');
        $datos['identificacion_notario'] = $identificacion->item(0)->nodeValue;
        $obj_notario = new TimbresBDItem('notario');
        $obj_notario->save($datos);
        $grid=new DataGrid2();
        $grid->cabecera=array('Colegiado','Nombre','Apellido','Identificación');
        $grid->campos=array('colegiado','nombre_notario','apellido_notario','identificacion_notario');
        $grid->keys=array('colegiado','nombre_notario');
        $grid->addActionCol('Seleccionar', 'seleccion', 'images/icon-check.png');
        $grid->dataset=  TimbresBDQuery::Consultar('NotarioQ', 'ListaNotario', Array('colegiado' => $this->__data['colegiado']));
        return $grid->bind()->saveXML();
    }
    public function GBM_Consulta2(){
        $back=TimbresBDQuery::Consultar('NotarioQ', 'ListaNotario', $this->__data);
        foreach ($back->data as $row){            
            $datos['colegiado']=$row['colegiado'];
            $datos['colegiado'] = $row['colegiado'];
            $data['no_colegiado'] = $row['colegiado'];
            $data['nombre_notario'] = '';
            $resul = Notarios::obtenerNotario($data);
            $notario=new DOMDocument();
            $notario->loadXML($resul);
            $nombres=$notario->getElementsByTagName('nombre_notario');
            $datos['nombre_notario'] = $nombres->item(0)->nodeValue;
            $apellidos=$notario->getElementsByTagName('apellido_notario');
            $datos['apellido_notario'] = $apellidos->item(0)->nodeValue;
            $direccion=$notario->getElementsByTagName('direccion_notario');
            $datos['direccion'] = $direccion->item(0)->nodeValue;
            $email=$notario->getElementsByTagName('email_notario');
            $datos['correo_electronico'] = $email->item(0)->nodeValue;
            $identificacion=$notario->getElementsByTagName('identificacion_notario');
            $datos['identificacion_notario'] = $identificacion->item(0)->nodeValue;
            $obj_notario = new TimbresBDItem('notario');
            $obj_notario->Update($datos);
        }
    }
}
?>
