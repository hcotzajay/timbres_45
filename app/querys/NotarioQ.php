<?php
include_once 'TimbresBDQuery.php';

class NotarioQ extends TimbresBDQuery{
    function ListaNotario(){
        $query="SELECT * 
            FROM notario
            WHERE 1=1 %s %s %s";
        
        if(isset($this->__params['inicio'])){
            $query .= " limit ".$this->__params['inicio'].",".$this->__params['cant'];
        }
        
        $colegiado = "";
        $nombre_notario = "";
        $apellido_notario = "";
        if(isset($this->__params['colegiado']))
            $colegiado = $this->__params['colegiado'] == "" ? "" : "AND colegiado = '".$this->__params['colegiado']."'";
        if(isset($this->__params['nombre']))
            $nombre_notario = $this->__params['nombre'] == "" ? "" : "AND nombre_notario LIKE '%".$this->__params['nombre']."%'";
        if(isset($this->__params['apellido']))        
            $apellido_notario = $this->__params['apellido'] == "" ? "" : "AND apellido_notario LIKE '%".$this->__params['apellido']."%'";
        $query = sprintf($query, $colegiado, $nombre_notario, $apellido_notario);
        
        return $this->toDataSet($query);
    }
    
    public function CuentaNotario(){
        $query="select count(*) cuantos from notario WHERE 1=1 %s %s %s";
        if(isset($this->__params['inicio'])){
            $query .= " limit ".$this->__params['inicio'].",".$this->__params['cant'];
        }
        $colegiado = "";
        $nombre_notario = "";
        $apellido_notario = "";
        if(isset($this->__params['colegiado']))
            $colegiado = $this->__params['colegiado'] == "" ? "" : "AND colegiado = '".$this->__params['colegiado']."'";
        if(isset($this->__params['nombre']))
            $nombre_notario = $this->__params['nombre'] == "" ? "" : "AND nombre_notario LIKE '%".$this->__params['nombre']."%'";
        if(isset($this->__params['apellido']))        
            $apellido_notario = $this->__params['apellido'] == "" ? "" : "AND apellido_notario LIKE '%".$this->__params['apellido']."%'";
        $query = sprintf($query, $colegiado, $nombre_notario, $apellido_notario);
        
        return $this->howMany($query);
    }
    public function notarios1(){
        $query="select * from notario where colegiado <>0 and nombre_notario is null limit 15";
        return $this->toDataSet($query);
    }
}
?>
