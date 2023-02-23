<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/bitacora/BitacoraControl.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/timbres/DotEnv.php';

class BitacoraC extends BitacoraControl{
    public function __construct() {
        $env = new DotEnv();

        $this->__host = $env->get_DB_IP();
        $this->__user = $env->get_DB_USER();
        $this->__pass = $env->get_DB_PASS();
        $this->__db = $env->get_DB_NAME();
        $this->charset = "utf8";
    }
}
?>