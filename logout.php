<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/timbres/DotEnv.php';

session_start();
session_unset();
session_destroy();
$env = new DotEnv();
header("location: http://" . $env->get_IP_SERVER() . "/timbres/");
?>
