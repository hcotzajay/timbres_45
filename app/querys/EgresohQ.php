<?php

include_once 'TimbresBDQuery.php';

class EgresohQ extends TimbresBDQuery{
    
    function Listado(){
        $query = "select * from historico where nombrerecibe is null %s %s";
        
        if(isset($this->__params['inicio'])){
            
            $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }
        $documento = "";
        $sticker = "";
        
        if(isset($this->__params['documento']))
            $documento= $this->__params['documento'] == "" ? "" : "and nodocumento like '%".$this->__params['documento']."%'";
        if(isset($this->__params['sticker']))
            $sticker= $this->__params['sticker'] == "" ? "" : "and sticker like '%".$this->__params['sticker']."%'";
        
        $query = sprintf($query, $documento, $sticker);
        return $this->toDataSet($query);
    }
    
    function Cuantos() {
        $query = "select count(*) cuantos from historico where nombrerecibe is null %s %s";
        
        if(isset($this->__params['inicio'])){
            
            $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }
        $documento = "";
        $sticker = "";
        
        if(isset($this->__params['documento']))
            $documento= $this->__params['documento'] == "" ? "" : "and nodocumento like '%".$this->__params['documento']."%'";
        if(isset($this->__params['sticker']))
            $sticker= $this->__params['sticker'] == "" ? "" : "and sticker like '%".$this->__params['sticker']."%'";
        
        $query = sprintf($query, $documento, $sticker);
        return $this->howMany($query);
    }
}
?>
