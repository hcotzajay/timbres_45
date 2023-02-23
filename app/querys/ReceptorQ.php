<?php
include_once 'TimbresBDQuery.php';

class ReceptorQ extends TimbresBDQuery{
    function ListadoReceptor(){
        $query="select * from receptor_documento where 1=1 %s %s %s %s";

        if(isset($this->__params['inicio'])){
            $query .=" limit ".$this->__params['inicio'].", ".$this->__params['cant'];
        }
        $nombre='';
        $apellido='';
        $dpi='';
        $cedula='';
        if(isset($this->__params['nombre']))
            $nombre = $this->__params['nombre'] == "" ? "" : "and nombre like'%".$this->__params['nombre']."%'";
        if(isset($this->__params['apellido']))
            $apellido = $this->__params['apellido'] == "" ? "" : "and apellido like'%".$this->__params['apellido']."%'";
        if(isset($this->__params['dpi']))
            $dpi = $this->__params['dpi'] == "" ? "" : "and dpi like'%".$this->__params['dpi']."%'";
        if(isset($this->__params['cedula']))
            $cedula = $this->__params['cedula'] == "" ? "" : "and cedula like'%".$this->__params['cedula']."%'";

        $query =  sprintf($query, $nombre, $apellido, $dpi, $cedula);
        return $this->toDataSet($query);
    }
    function CuentaReceptor(){
        $query = "select count(*) cuantos from receptor_documento where 1=1 %s %s %s %s";

        $nombre='';
        $apellido='';
        $dpi='';
        $cedula='';
        if(isset($this->__params['nombre']))
            $nombre= $this->__params['nombre'] == "" ? "" : "and nombre like'%".$this->__params['nombre']."%'";
        if(isset($this->__params['apellido']))
            $apellido= $this->__params['apellido'] == "" ? "" : "and apellido like'%".  $this->__params['apellido']."%'";
        if(isset($this->__params['dpi']))
            $dpi=  $this->__params['dpi'] == "" ? "" : "and dpi like'%".  $this->__params['dpi']."%'";
        if(isset($this->__params['cedula']))
            $cedula=  $this->__params['cedula'] == "" ? "" : "and cedula like'%".$this->__params['cedula']."%'";
        $query=  sprintf($query, $nombre, $apellido, $dpi, $cedula);
        return $this->howMany($query);
    }

function Imprimir()
        {
            $query = "SELECT colaborador.id_usuario 'colaborador' ,doc.*, re.*, nota.* from documento doc
                            LEFT JOIN receptor_documento re on (re.dpi = doc.receptor_documento_dpi)
                            LEFT JOIN notario nota on (nota.colegiado = doc.notario_colegiado)
                            LEFT JOIN (
                                        select valor_actual, id_usuario
                                        from r_timbres.log_bitacora
                                        where fecha = ( select min(fecha)
                                                        from r_timbres.log_bitacora
                                                        where valor_actual = valor_anterior
                                                            and valor_actual = (
                                                                    select valor_actual 'id_doc'
                                                                    from r_timbres.log_bitacora
                                                                    where fecha = ( select min(fecha)
                                                                                    from r_timbres.log_bitacora
                                                                                    where valor_actual = '%s')
                                                                    and id_estructura=14
                                                                )
                                                            and id_estructura=14)
                                        group by id_usuario
                                        ) colaborador on colaborador.valor_actual = doc.id
                    WHERE 1=1 and fecha_salida is not null %s %s %s";

            $docNumber = "";
            $id = "";
            $sticker = "";
            $valor_actual = "";

            if (isset($this->__params['documento'])) {
                $docNumber =  $this->__params['documento'];
                $valor_actual = $docNumber;
                $docNumber = $docNumber == "" ? "" : "and doc.numero_documento = '".$docNumber."'";
            }
            if (isset($this->__params['id'])) {
                $id =  $this->__params['id'];
                $valor_actual = $id;
                $id = $id == "" ? "" : "and doc.id = '".$id."'";
            }
            if (isset($this->__params['sticker'])) {
                $sticker = $this->__params['sticker'];
                $valor_actual = $sticker;
                $sticker = $sticker == "" ? "" : "and doc.sticker = '".$sticker."'";
            }

            if ($docNumber != "" || $id != "" || $sticker != "") {
                $query = sprintf($query, $valor_actual, $docNumber, $id, $sticker);
            } else {
                $query = '';
            }

            return $this->toDataSet($query);
        }
}
?>
