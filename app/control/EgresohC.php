<?php
include_once dirname(__FILE__).'/../querys/TimbresBDQuery.php';
include_once dirname(__FILE__).'/../model/TimbresBDItem.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WebComm.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/DataGrid2.php';

class EgresohC extends WebComm{
    function __construct($data = null) {
        $this->__tabla = "historico";
        $this->__objeto = "TimbresBDItem";
    }
    function Listado(){
        $grid = new DataGrid2();
        $grid->cabecera = array('No. Documento','Sticker','Colegiado','Notario','Valor','Fecha de ingreso');
        $grid->campos = array('nodocumento','sticker','colegiado','nombrenotario','valor','fechaingreso');
        $grid->keys = array('id');
        $grid->porPaginas = 10;
        $grid->actual= $this->__data['page'];
        $grid->enTotal = TimbresBDQuery::Consultar('EgresohQ', 'Cuantos', $this->__data);
        $this->__data['inicio']=$grid->porPaginas*($grid->actual-1);
        $this->__data['cant']=$grid->porPaginas;
        $grid->addActionCol('Seleccionar', 'selec', 'images/icon-check.png');
        $grid->dataset=  TimbresBDQuery::Consultar('EgresohQ', 'Listado', $this->__data);
        return $grid->bind()->saveXML();
    }
    public function Update2($subclase = null){
        $this->__data['fechaegreso'] = date('d/m/Y H:i:s');
        $item = new $this->__objeto($this->__tabla, $subclase);

        try{
            if($item->update($this->__data, $this->__webuser))
                return Utiles::generaMensaje ($this->__DEFAULT_UPDATE_OK, "ok");
            else
                return Utiles::generaMensaje ($this->__DEFAULT_UPDATE_ERR, "error");
        }catch(MyBDException $ex){
            return Utiles::generaMensaje($ex->getMessage(), $ex->getClase());
        }
    }
}
?>
