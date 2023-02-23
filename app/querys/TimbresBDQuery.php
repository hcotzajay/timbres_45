<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/myskrn2.1/MyBDQuery.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/timbres/DotEnv.php';

class TimbresBDQuery extends MyBDQuery{
    
    public function setConnection(){
        $env = new DotEnv();

        $this->__host = $env->get_DB_IP();
        $this->__user = $env->get_DB_USER();
        $this->__pass = $env->get_DB_PASS();
        $this->__db = $env->get_DB_NAME();
        $this->__charset = "utf8";
    }
    
    public static function Consultar($clase, $metodo, $param = null, $excepciones = null){
        include_once $clase.'.php';
        $quering = new $clase();
        if($param != null){
            $quering->__params = $param;
            $quering->sanitize($excepciones);
        }
        return $quering->$metodo();
    }
}
?>
