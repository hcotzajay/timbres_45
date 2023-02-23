<?php
include_once 'TimbresBDQuery.php';

class HistoricoQ extends TimbresBDQuery{

    function Listado2(){
        $query = "SELECT *, date_format(fechaingreso,'%s') mes, if(nombrerecibe is not null,'Inactivo','Activo') egresados, date_format(fechaingreso,'%s') fechaingreso
                  FROM historico
                  WHERE 1=1 %s %s %s %s %s %s";

        $mess = "%m";
        $ffecha="%d-%m-%Y";
        $documento = "";
        $estadofecha = "";
        $fecha = "";
        $notario = "";
        $valor = "";
        $estados = "";
         if(isset($this->__params['inicio1'])){
               $this->__params['inicio'] = $this->__params['inicio1'] - 1;
           }
        if(isset($this->__params['inicio'])){

            $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }
        if(isset($this->__params['reporte']))
            $documento= $this->__params['reporte'] == "" ? "" : "and id = '".$this->__params['reporte']."'";
        if(isset($this->__params['id']))
            $documento= $this->__params['id'] == "" ? "" : "and id = '".$this->__params['id']."'";
        if(isset($this->__params['documento']))
            $documento= $this->__params['documento'] == "" ? "" : "and nodocumento like '%".$this->__params['documento']."%'";
        if(isset($this->__params['fecha1']) && isset($this->__params['fecha2']))
            $estadofecha = $this->__params['fecha2'] == "" ? "" :"|| fechaingreso >= ".$this->__params['fecha2'];
        if(isset($this->__params['fecha1']) && isset($this->__params['fecha2']))
            $fecha= $this->__params['fecha2'] == "" ? "" : "and date_format(fechaingreso,'%d-%m-%Y') between '".$this->__params['fecha1']."' and '".$this->__params['fecha2']."'";
        if(isset($this->__params['notario']))
            $notario= $this->__params['notario'] == "" ? "" : "and colegiado like'%".$this->__params['notario']."%'";
        if(isset($this->__params['valor']) && isset($this->__params['valora']))
            $valor=  $this->__params['valor'] == "" ? "" : "and valor < ".$this->__params['valor']." and valor >".$this->__params['valora'];
        /*if(isset($this->__params['estados']))
            $estados = $this->__params['estados'] == "" ? "" : "and egresado = '".$this->__params['estados']."'";*/
        if(isset($this->__params['estados'])){
            //$estados = $this->__params['estados'] == "" ? "" : "and (nombrerecibe is not null and egresado = '".$this->__params['estados']."') or (nombrerecibe is null and egresado = 'si')";
            $param = $this->__params['estados'];
            if ($param!='') {
                if ($param=='si') {
                    $estados = 'and nombrerecibe is null';
                }elseif ($param=='no') {
                    $estados = 'and nombrerecibe is not null';
                }
            }
        }

        $query = sprintf($query, $mess, $ffecha, $documento, $estadofecha, $fecha, $notario, $valor, $estados);
        return $this->toDataSet($query);
        //echo $query;
    }

    function Listado(){
        $query = "SELECT *, date_format(fechaingreso,'%s') mes, if(nombrerecibe is not null,'Inactivo','Activo') egresados, date_format(fechaingreso,'%s') fechaingreso
                  FROM historico
                  WHERE 1=1 %s %s %s %s %s %s";

        $mess = "%m";
        $ffecha="%d-%m-%Y";
        $documento = "";
        $estadofecha = "";
        $fecha = "";
        $notario = "";
        $valor = "";
        $estados = "";
         if(isset($this->__params['inicio1'])){
               $this->__params['inicio'] = $this->__params['inicio1'] - 1;
           }
        if(isset($this->__params['inicio'])){

            $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }
        if(isset($this->__params['reporte']))
            $documento= $this->__params['reporte'] == "" ? "" : "and id = '".$this->__params['reporte']."'";
        if(isset($this->__params['id']))
            $documento= $this->__params['id'] == "" ? "" : "and id = '".$this->__params['id']."'";
        if(isset($this->__params['documento']))
            $documento= $this->__params['documento'] == "" ? "" : "and nodocumento like '%".$this->__params['documento']."%'";
        if(isset($this->__params['fecha1']) && isset($this->__params['fecha2']))
            $estadofecha = $this->__params['fecha2'] == "" ? "" :"|| fechaingreso >= ".$this->__params['fecha2'];
        if(isset($this->__params['fecha1']) && isset($this->__params['fecha2']))
            $fecha= $this->__params['fecha2'] == "" ? "" : "and date_format(fechaingreso,'%d-%m-%Y') between '".$this->__params['fecha1']."' and '".$this->__params['fecha2']."'";
        if(isset($this->__params['notario']))
            $notario= $this->__params['notario'] == "" ? "" : "and colegiado like'%".$this->__params['notario']."%'";
        if(isset($this->__params['valor']) && isset($this->__params['valora']))
            $valor=  $this->__params['valor'] == "" ? "" : "and valor < ".$this->__params['valor']." and valor >".$this->__params['valora'];
        /*if(isset($this->__params['estados']))
            $estados = $this->__params['estados'] == "" ? "" : "and egresado = '".$this->__params['estados']."'";*/
        if(isset($this->__params['estados'])){
            //$estados = $this->__params['estados'] == "" ? "" : "and (nombrerecibe is not null and egresado = '".$this->__params['estados']."') or (nombrerecibe is null and egresado = 'si')";
            $param = $this->__params['estados'];
            if ($param!='') {
                if ($param=='si') {
                    $estados = 'and nombrerecibe is null';
                }elseif ($param=='no') {
                    $estados = 'and nombrerecibe is not null';
                }
            }
        }

        $query = sprintf($query, $mess, $ffecha, $documento, $estadofecha, $fecha, $notario, $valor, $estados);
        return $this->toDataSet($query);
        //echo $query;
    }
    function Cuantos(){
        $query = "select count(*) cuantos from historico where 1=1 %s %s %s %s %s %s";

        $documento = "";
        $estadofecha = "";
        $fecha = "";
        $notario = "";
        $valor = "";
        $estados = "";

        if(isset($this->__params['inicio'])){
            if(isset($this->__params['inicio1'])){
                $this->__params['inicio'] = $this->__params['inicio1'] -1;
            }
            $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }
        if(isset($this->__params['reporte']))
            $documento= $this->__params['reporte'] == "" ? "" : "and id = '".$this->__params['reporte']."'";
        if(isset($this->__params['documento']))
            $documento= $this->__params['documento'] == "" ? "" : "and nodocumento like '%".$this->__params['documento']."%'";
        if(isset($this->__params['fecha1']) && isset($this->__params['fecha2']))
            $estadofecha = $this->__params['fecha2'] == "" ? "" :"|| fechaingreso >= ".$this->__params['fecha2'];
        if(isset($this->__params['fecha1']) && isset($this->__params['fecha2']))
            $fecha= $this->__params['fecha2'] == "" ? "" : "and date_format(fechaingreso,'%d-%m-%Y') between '".$this->__params['fecha1']."' and '".$this->__params['fecha2']."'";
        if(isset($this->__params['notario']))
            $notario= $this->__params['notario'] == "" ? "" : "and colegiado like'%".$this->__params['notario']."%'";
        if(isset($this->__params['valor']) && isset($this->__params['valora']))
            $valor=  $this->__params['valor'] == "" ? "" : "and valor < ".$this->__params['valor']." and valor >".$this->__params['valora'];
        if(isset($this->__params['estados'])){
            //$estados = $this->__params['estados'] == "" ? "" : "and (nombrerecibe is not null and egresado = '".$this->__params['estados']."') or (nombrerecibe is null and egresado = 'si')";
            $param = $this->__params['estados'];
            if ($param!='') {
                if ($param=='si') {
                    $estados = 'and nombrerecibe is null';
                }elseif ($param=='no') {
                    $estados = 'and nombrerecibe is not null';
                }
            }
        }

        $query = sprintf($query, $documento, $estadofecha, $fecha, $notario, $valor, $estados);
        return $this->howMany($query);
    }

}
?>
