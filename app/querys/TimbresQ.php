<?php
include_once 'TimbresBDQuery.php';

class TimbresQ extends TimbresBDQuery{
    function ListaTimbres(){
        $query="Select *, doc.numero_documento, DATE_FORMAT(fecha_ingreso,'%s') fecha, if(fecha_salida is null,'Activo','No activo') salida
                from documento doc
                    left join notario nota on doc.notario_colegiado = nota.colegiado
                where 1=1 and fecha_salida is null %s %s %s %s %s %s %s";

        if(isset($this->__params['inicio'])){
            $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }

        $ffecha = "%d-%m-%Y";
        $orden = "order by fecha_ingreso desc";
        $documento='';
        $documento2='';
        $sticker='';
        $notario='';
        $fechas='';
        $egreso='';

        if(isset($this->__params['numero_documento']))
            $documento = $this->__params['numero_documento'] == "" ? "" : "AND doc.numero_documento like '%".$this->__params['numero_documento']."%'";
        if(isset($this->__params['numero_documento1']))
            $documento2 = $this->__params['numero_documento1'] == "" ? "" : "AND doc.numero_documento like '%".$this->__params['numero_documento1']."%'";
        if(isset($this->__params['sticker']))
            $sticker = $this->__params['sticker'] == "" ? "" : "AND sticker like '%".$this->__params['sticker']."%'";
        if(isset($this->__params['notario_colegiado']))
             $notario = $this->__params['notario_colegiado'] == "" ? "" : "and notario_colegiado='".$this->__params['notario_colegiado']."'";
        if((isset($this->__params['fecha1']) && (isset($this->__params['fecha2']))))
            $fechas = $this->__params['fecha1'] == "" ? "" : "and date_format(fecha_ingreso,'%d/%m/%Y') between '".$this->__params['fecha1']."' and '".$this->__params['fecha2']."'";
        if(isset($this->__params['egreso']))
            $documento = $this->__params['egreso'] == "" ? "" : "AND fecha_salida is null";

        $query = sprintf($query, $ffecha, $documento, $documento2, $sticker, $notario, $fechas, $egreso, $orden);

        return $this->toDataSet($query);
    }
    public function CuentaTimbres(){
        $query ="   select count(*) cuantos
                    from documento doc
                        left join notario nota on doc.notario_colegiado = nota.colegiado
                    where 1=1 and fecha_salida is null %s %s %s %s %s";

        $documento='';
        $orden = "order by fecha_ingreso desc";
        $sticker='';
        $notario='';
        $fechas='';

        if(isset($this->__params['numero_documento']))
    $documento = $this->__params['numero_documento'] == "" ? "" : "AND doc.numero_documento like '%".$this->__params['numero_documento']."%'";
        if(isset($this->__params['sticker']))
    $sticker = $this->__params['sticker'] == "" ? "" : "AND sticker like '%".$this->__params['sticker']."%'";
        if(isset($this->__params['notario']))
    $notario = $this->__params['notario'] == "" ? "" : "and nota.id='".$this->__params['notario']."'";
        if((isset($this->__params['fecha1']) && (isset($this->__params['fecha2']))))
    $fechas = $this->__params['fecha1'] == "" ? "" : "and date_format(fecha_ingreso,'%d/%m/%Y') between '".$this->__params['fecha1']."' and '".$this->__params['fecha2']."'";

        $query = sprintf($query, $documento, $sticker, $notario, $fechas, $orden);
        return $this->howMany($query);
    }
}
?>
