<?php
    include_once 'TimbresBDQuery.php';

    class ReportQ extends TimbresBDQuery
    {
        function ListadoReporte()
        {
            $query = "select *,doc.id, if(fecha_salida is null %s ,'Activo','Inactivo')
            estado, DATE_FORMAT(fecha_ingreso,'%s') fecha,
            DATE_FORMAT(fecha_salida,'%s') salida,
            date_format(fecha_ingreso,'%s') mes,
            date_format(fecha_ingreso,'%s') anuos
            from documento doc LEFT JOIN notario nota on (doc.notario_colegiado = nota.colegiado)
            left join receptor_documento re on (doc.receptor_documento_dpi = re.dpi)
            where 1=1 %s %s %s %s %s %s %s ";

            if (isset($this->__params['inicio1'])) {
                $this->__params['inicio'] = $this->__params['inicio1'] - 1;
            }
            if (isset($this->__params['inicio'])) {

                $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
            }
            $estadofecha = "";
            $orden = "order by anuos desc ,mes desc";
            $mess = "%m";
            $año = "%Y";
            $ffecha = "%d-%m-%Y";
            $ffecha2 = "%d-%m-%Y %H:%i:%s";
            $documento = "";
            $notario = "";
            $valor = "";
            $estados = "";
            $fecha = "";
            $egresor = "";

            if (isset($this->__params['fecha2'])) {
                $estadofecha = $this->__params['fecha2'] == "" ? "" : "|| fecha_salida > str_to_date('".$this->__params['fecha2']."','%d/%m/%Y')";
            }
            if (isset($this->__params['fecha1']) && isset($this->__params['fecha2'])) {
                $fecha = $this->__params['fecha2'] == "" ? "" : "and fecha_ingreso between str_to_date('".$this->__params['fecha1']."','%d/%m/%Y') and str_to_date('".$this->__params['fecha2']."','%d/%m/%Y')";
            }
            if (isset($this->__params['reporte'])) {
                $documento = $this->__params['reporte'] == "" ? "" : "and doc.id='".$this->__params['reporte']."'";
            }
            if (isset($this->__params['documento'])) {
                $documento = $this->__params['documento'] == "" ? "" : "and doc.numero_documento like '%".$this->__params['documento']."%'";
            }
            if (isset($this->__params['notario'])) {
                $notario = $this->__params['notario'] == "" ? "" : "and nota.colegiado like'%".$this->__params['notario']."%'";
            }
            if (isset($this->__params['valor']) && isset($this->__params['valora'])) {
                $valor = $this->__params['valor'] == "" ? "" : "and valor < ".$this->__params['valor']." and valor >".$this->__params['valora'];
            }
            if (isset($this->__params['egresor'])) {
                $egresor = $this->__params['egresor'] == "" ? "" : "and re.nombre like '%".$this->__params['egresor']."%'";
            }
            if (isset($this->__params['estados'])) {
                if ($this->__params['fecha2'] != "") {
                    $estados = $this->__params['estados'] == "" ? "" : "and (fecha_salida ".$this->__params['estados']." || date_format(fecha_salida,'%Y/%m/%d')  > str_to_date('".$this->__params['fecha2']."','%d/%m/%Y'))";
                } else {
                    $estados = $this->__params['estados'] == "" ? "" : "and fecha_salida ".$this->__params['estados'];
                }
            }

            $query = sprintf($query, $estadofecha, $ffecha, $ffecha2, $mess, $año, $documento, $notario, $valor, $estados, $fecha,
                $egresor, $orden);

            return $this->toDataSet($query);
        }

        function CuentaReport()
        {
            $query = "select count(*) cuantos from documento doc LEFT JOIN notario nota on (doc.notario_colegiado = nota.colegiado)
            left join receptor_documento re on (doc.receptor_documento_dpi = re.dpi) where 1=1 %s %s %s %s %s %s";

            $documento = "";
            $notario = "";
            $valor = "";
            $fecha = "";
            $egresor = "";
            $estados = "";

            if (isset($this->__params['fecha1']) && isset($this->__params['fecha2'])) {
                $fecha = $this->__params['fecha2'] == "" ? "" : "and fecha_ingreso between str_to_date('".$this->__params['fecha1']."','%d/%m/%Y') and str_to_date('".$this->__params['fecha2']."','%d/%m/%Y')";
            }
            if (isset($this->__params['documento'])) {
                $documento = $this->__params['documento'] == "" ? "" : "and doc.numero_documento like '%".$this->__params['documento']."%'";
            }
            if (isset($this->__params['notario'])) {
                $notario = $this->__params['notario'] == "" ? "" : "and nota.colegiado like '%".$this->__params['notario']."%'";
            }
            if (isset($this->__params['valor'])) {
                $valor = $this->__params['valor'] == "" ? "" : "and valor like '%".$this->__params['valor']."%'";
            }
            if (isset($this->__params['egresor'])) {
                $egresor = $this->__params['egresor'] == "" ? "" : "and re.nombre like '%".$this->__params['egresor']."%'";
            }
            if (isset($this->__params['estados'])) {
                $estados = $this->__params['estados'] == "" ? "" : "and fecha_salida ".$this->__params['estados'];
            }

            $query = sprintf($query, $documento, $notario, $valor, $estados, $fecha, $egresor);

            return $this->howMany($query);
        }

        function Ingresos()
        {
            $query = "SELECT numero_documento, sticker, notario_colegiado 'colegiado', nombre_notario, apellido_notario, valor,
                            DATE_FORMAT(fecha_ingreso, '%s') 'fecha',
                            if(fecha_salida is null ,'Activo','Inactivo') 'estado', colaborador.id_usuario 'colaborador'
                        FROM documento doc
                            left join notario nota on (doc.notario_colegiado = nota.colegiado)
                            left join receptor_documento re on (doc.receptor_documento_dpi = re.dpi)
                            left join ( select valor_actual, id_usuario
                                        from log_bitacora
                                        where id_estructura = 15
                                      ) colaborador on colaborador.valor_actual = numero_documento
                        WHERE %s %s %s %s %s %s";

            if (isset($this->__params['inicio'])) {
                $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
            }

            $doc = '';
            $sticker = '';
            $option = '';
            $groupBy = 'group by numero_documento';
            $orderBy = 'order by fecha_ingreso asc';
            $ffechaCompleta = '%d/%m/%Y %H:%i:%s';
            $ffecha = '%d/%m/%Y';
            $fecha = '';

            if (isset($this->__params['date'])) {
                $fecha = "DATE_FORMAT(fecha_ingreso, '%s') = %s";
                $cadena = $this->__params['date'] == '' ? "DATE_FORMAT(CURDATE(), '%d/%m/%Y')" : "'".$this->__params['date']."'";
                $fecha = sprintf($fecha, $ffecha, $cadena);
            }

            if (isset($this->__params['dateI']) && isset($this->__params['dateF'])) {
                $fecha = "fecha_ingreso between str_to_date('".$this->__params['dateI']." 00:00:00','%s') and str_to_date('".$this->__params['dateF']." 23:59:59','%s')";
                $fecha = sprintf($fecha, $ffechaCompleta, $ffechaCompleta);
            }

            if (isset($this->__params['opt'])) {
                $option = $this->__params['opt'] == 'a' ? " fecha_salida is null and " : " fecha_salida is not null and ";
            }

            if (isset($this->__params['doc'])) {
                $doc = "numero_documento like %s";
                $cadena = $this->__params['doc'] == '' ? "'%%'" : "'%".$this->__params['doc']."%'";
                $doc = sprintf($doc, $cadena);
            }

            if (isset($this->__params['sticker'])) {
                $sticker = "sticker like %s";
                $cadena = $this->__params['sticker'] == '' ? "'%%'" : "'%".$this->__params['sticker']."%'";
                $sticker = sprintf($sticker, $cadena);
            }

            $query = sprintf($query, $ffechaCompleta, $doc, $sticker, $option, $fecha, $groupBy, $orderBy);

            return $this->toDataSet($query);
        }

        function countIngresos()
        {
            $query = "SELECT count(*) cuantos
                        FROM documento doc
                        left join notario nota on (doc.notario_colegiado = nota.colegiado)
                        left join receptor_documento re on (doc.receptor_documento_dpi = re.dpi)
                        left join ( select valor_actual, id_usuario
                                    from log_bitacora
                                    where id_estructura = 15
                        ) colaborador on colaborador.valor_actual = numero_documento
                        WHERE %s %s %s %s";

            if (isset($this->__params['inicio'])) {
                $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
            }

            $sticker = '';
            $doc = '';
            $option = '';
            $ffechaCompleta = '%d/%m/%Y %H:%i:%s';
            $ffecha = '%d/%m/%Y';
            $fecha = '';

            if (isset($this->__params['date'])) {
                $fecha = "DATE_FORMAT(fecha_ingreso, '%s') = %s";
                $cadena = $this->__params['date'] == '' ? "DATE_FORMAT(CURDATE(), '%d/%m/%Y')" : "'".$this->__params['date']."'";
                $fecha = sprintf($fecha, $ffecha, $cadena);
            }

            if (isset($this->__params['dateI']) && isset($this->__params['dateF'])) {
                $fecha = "fecha_ingreso between str_to_date('".$this->__params['dateI']." 00:00:00','%s') and str_to_date('".$this->__params['dateF']." 23:59:59','%s')";
                $fecha = sprintf($fecha, $ffechaCompleta, $ffechaCompleta);
            }

            if (isset($this->__params['opt'])) {
                $option = $this->__params['opt'] == 'a' ? " fecha_salida is null and " : " fecha_salida is not null and ";
            }

            if (isset($this->__params['doc'])) {
                $doc = "numero_documento like %s";
                $cadena = $this->__params['doc'] == '' ? "'%%'" : "'%".$this->__params['doc']."%'";
                $doc = sprintf($doc, $cadena);
            }

            if (isset($this->__params['sticker'])) {
                $sticker = "sticker like %s";
                $cadena = $this->__params['sticker'] == '' ? "'%%'" : "'%".$this->__params['sticker']."%'";
                $sticker = sprintf($sticker, $cadena);
            }

            $query = sprintf($query, $doc, $sticker, $option, $fecha);

            return $this->howMany($query);
        }

        function Egresos()
        {
            $query = "SELECT numero_documento, sticker, notario_colegiado 'colegiado', nombre_notario,  apellido_notario, valor,
                            DATE_FORMAT(fecha_ingreso,'%s') 'ingreso',
                            DATE_FORMAT(fecha_salida,'%s') 'egreso',
                            if(fecha_salida is null ,'Activo','Inactivo') 'estado', nombre 'egresor', colaborador.id_usuario 'colaborador'
                        FROM documento doc
                            left join notario nota on (doc.notario_colegiado = nota.colegiado)
                            left join receptor_documento re on (doc.receptor_documento_dpi = re.dpi)
                            left join ( select f.valor_actual, b.id_usuario, b.id_estructura, b.fecha
                                        from log_bitacora b
                                        left join (
                                                    select id valor_actual, fecha_salida fecha
                                                    from documento
                                                    where fecha_salida is not null %s %s %s
                                                    ) f on f.valor_actual = b.valor_anterior
                                        where b.id_estructura=14 %s
                                        group by f.valor_actual
                            ) colaborador on colaborador.valor_actual = id
                        WHERE fecha_salida is not null %s %s %s %s";

            if (isset($this->__params['inicio'])) {
                $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
            }

            $doc = '';
            $sticker = '';
            $orderBy = 'order by fecha_salida asc';
            $ffechaCompleta = '%d/%m/%Y %H:%i:%s';
            $ffecha = '%d/%m/%Y';
            $fecha1 = '';
            $fecha2 = '';
            $fecha3 = '';

            if (isset($this->__params['date'])) {
                $fecha1 = "and DATE_FORMAT(fecha_salida, '%s') = %s";
                $fecha2 = "and DATE_FORMAT(b.fecha, '%s') = %s";
                $fecha3 = "and DATE_FORMAT(fecha_salida, '%s') = %s";
                $cadena = $this->__params['date'] == '' ? "DATE_FORMAT(CURDATE(), '%d/%m/%Y')" : "'".$this->__params['date']."'";
                $fecha1 = sprintf($fecha1, $ffecha, $cadena);
                $fecha2 = sprintf($fecha2, $ffecha, $cadena);
                $fecha3 = sprintf($fecha3, $ffecha, $cadena);
            }

            if (isset($this->__params['dateI']) && isset($this->__params['dateF'])) {
                $fecha1 = "and fecha_salida between str_to_date('".$this->__params['dateI']." 00:00:00','%s') and str_to_date('".$this->__params['dateF']." 23:59:59','%s')";
                $fecha2 = "and b.fecha between str_to_date('".$this->__params['dateI']." 00:00:00','%s') and str_to_date('".$this->__params['dateF']." 23:59:59','%s')";
                $fecha3 = "and fecha_salida between str_to_date('".$this->__params['dateI']." 00:00:00','%s') and str_to_date('".$this->__params['dateF']." 23:59:59','%s')";
                $fecha1 = sprintf($fecha1, $ffechaCompleta, $ffechaCompleta);
                $fecha2 = sprintf($fecha2, $ffechaCompleta, $ffechaCompleta);
                $fecha3 = sprintf($fecha3, $ffechaCompleta, $ffechaCompleta);
            }

            if (isset($this->__params['doc'])) {
                $doc = "and numero_documento like %s";
                $cadena = $this->__params['doc'] == '' ? "'%%'" : "'%".$this->__params['doc']."%'";
                $doc = sprintf($doc, $cadena);
            }

            if (isset($this->__params['sticker'])) {
                $sticker = "and sticker like %s";
                $cadena = $this->__params['sticker'] == '' ? "'%%'" : "'%".$this->__params['sticker']."%'";
                $sticker = sprintf($sticker, $cadena);
            }

            $query = sprintf($query, $ffecha, $ffechaCompleta, $fecha1, $doc, $sticker, $fecha2, $fecha3, $doc, $sticker, $orderBy);

            return $this->toDataSet($query);
        }

        function countEgresos()
        {
            $query = "SELECT count(*) cuantos
                        FROM documento doc
                            left join notario nota on (doc.notario_colegiado = nota.colegiado)
                            left join receptor_documento re on (doc.receptor_documento_dpi = re.dpi)
                        WHERE 1=1 %s %s %s %s";

            if (isset($this->__params['inicio'])) {
                $query .= " limit ".$this->__params['inicio'].", ".$this->__params['cant'];
            }

            $doc = '';
            $sticker = '';
            $orderBy = 'order by fecha_salida asc';
            $ffechaCompleta = '%d/%m/%Y %H:%i:%s';
            $ffecha = '%d/%m/%Y';
            $fecha = '';

            if (isset($this->__params['date'])) {
                $fecha = "and DATE_FORMAT(fecha_salida, '%s') = %s";
                $cadena = $this->__params['date'] == '' ? "DATE_FORMAT(CURDATE(), '%d/%m/%Y')" : "'".$this->__params['date']."'";
                $fecha = sprintf($fecha, $ffecha, $cadena);
            }

            if (isset($this->__params['dateI']) && isset($this->__params['dateF'])) {
                $fecha = "and fecha_salida between str_to_date('".$this->__params['dateI']." 00:00:00','%s') and str_to_date('".$this->__params['dateF']." 23:59:59','%s')";
                $fecha = sprintf($fecha, $ffechaCompleta, $ffechaCompleta);
            }

            if (isset($this->__params['doc'])) {
                $doc = "and numero_documento like %s";
                $cadena = $this->__params['doc'] == '' ? "'%%'" : "'%".$this->__params['doc']."%'";
                $doc = sprintf($doc, $cadena);
            }

            if (isset($this->__params['sticker'])) {
                $sticker = "and sticker like %s";
                $cadena = $this->__params['sticker'] == '' ? "'%%'" : "'%".$this->__params['sticker']."%'";
                $sticker = sprintf($sticker, $cadena);
            }

            $query = sprintf($query, $fecha, $doc, $sticker, $orderBy);

            return $this->howMany($query);
        }

    }
