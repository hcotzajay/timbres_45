<?php
include_once dirname(__FILE__).'/../querys/TimbresBDQuery.php';
include_once dirname(__FILE__).'/../model/TimbresBDItem.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WebComm.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/DataGrid2.php';

class TimbresC extends WebComm{
    public function __construct($data = null) {
        $this->__tabla = "documento";
        $this->__objeto = "TimbresBDItem";
    }
    public function ListaTimbres(){
        $grid=new DataGrid2();
        $grid->cabecera=array('No. Documento','Sticker','Colegiado','Notario','Valor','Año de ingreso','Estado del Documento');
        $grid->campos=array('numero_documento','sticker','colegiado','nombre_notario','valor','fecha','salida');
        $grid->keys=array('id');
        $grid->porPaginas = 10;
        $grid->actual=  $this->__data['page'];
        $grid->enTotal= TimbresBDQuery::Consultar('TimbresQ', 'CuentaTimbres', $this->__data);
        $this->__data['inicio']=$grid->porPaginas*($grid->actual-1);
        $this->__data['cant']=$grid->porPaginas;
        if(isset($this->__data['editar']))
            $grid->addActionCol('Editar', 'editar', 'images/icon_edit.png');
        if(isset($this->__data['selec']))
            $grid->addActionCol('Seleccionar', 'seleccion', 'images/icon-check.png');
        $grid->dataset=  TimbresBDQuery::Consultar('TimbresQ', 'ListaTimbres',  $this->__data);
        return $grid->bind()->saveXML();
    }

    public function Load3(){
        $documento = new TimbresBDItem('documento');
        $documento->load($this->__data);

        $notario = new TimbresBDItem('notario');
        $notario->load(Array('colegiado' => $documento->notario_colegiado));

        $documento->addExtraXML('notariostr', $notario->nombre_notario);
        return $documento->toXML2()->saveXML();
    }

    public function Insert3($subclase = null){
        $this->__data['fecha_ingreso'] = date('d/m/Y H:i:s');
        $item = new $this->__objeto($this->__tabla, $subclase);
        try{
            if($item->save($this->__data, $this->__webuser))
            return Utiles::generaMensaje ($this->__DEFAULT_INSERT_OK, "ok");
            else
                return Utiles::generaMensaje ($this->__DEFAULT_INSERT_ERR, "error");
        }catch(MyBDException $ex){
            return Utiles::generaMensaje($ex->getMessage(), $ex->getClase());
        }
    }

    public function Update2($subclase = null){
        $this->__data['fecha_salida'] = date('d/m/Y H:i:s');
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
