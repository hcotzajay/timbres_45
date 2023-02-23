<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/myskrn2.1/MyBDItem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/timbres/DotEnv.php';

class TimbresBDItem extends MyBDItem{

    public function setConnection(){
        $env = new DotEnv();

        $this->__host = $env->get_DB_IP();
        $this->__user = $env->get_DB_USER();
        $this->__pass = $env->get_DB_PASS();
        $this->__db = $env->get_DB_NAME();
        $this->__charset = "utf8";
        
        $this->__mensajes = Array(
            'usuario' => Array(
                'tipo_id' => Array(
                    self::$NULL => Array('msg' => "Debe ingresar el tipo", 'clas
                        s' => 'info'),
                    self::$NOT_NUMBER => Array('msg' => "No es un numero valido"
                        , 'class' => 'warning')
                ),
                'profesor' => Array(
                    'titulo' => Array(
                        self::$NULL => Array('msg' => "Debe ingresar un titulo p
                            ara el profesor", 'class' => 'info')
                    )
                )
            )
        );
        
    }
    
    public function setDateFormat(){
        return "%d/%m/%Y";
    }
    
    public function setDateTimeFormat(){
        return "%d/%m/%Y %H:%i:%s";
    }
    
    public function getNombreEsquema(){
        return "r_timbres";
    }
    
    public function getNombreEsquemaLog(){
        return "r_timbres";
    }
}
?>
