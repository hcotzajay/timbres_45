<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/nusoaplib/nusoap.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/librerias/php/RGPApi/WSLib.php';

function Busqueda($xml){
    $dom = new DOMDocument();
    $dom->loadXML($xml);
    $params = $dom->getElementsByTagName("param");
    $data = Array();
    foreach($params as $param){
        $data[$param->getAttribute('nombre')] = $param->nodeValue;
    }
    
    return WSLib::Invocar('timbres', 'HistoricoC', 'SelectR', $data);
}

function Impresion($xml){
    $dom = new DOMDocument();
    $dom->loadXML($xml);
    $params = $dom->getElementsByTagName("param");
    $data = Array();
    foreach ($params as $param){
        $data[$param->getAttribute('nombre')] = $param->nodeValue;
    }
    return WSLib::Invocar('timbres', 'HistoricoC', 'Recibo', $data);
}

$server = new soap_server();
$ns="http://localhost/timbres/ws/impresionh.php";
//$ns="http://172.16.39.5/timbres/ws/impresionh.php";
$server->configurewsdl('Impresion de timbres',$ns);
$server->wsdl->schematargetnamespace=$ns;

$server->register('Busqueda',
                    Array('filtro' => 'xsd:string'),
                    Array('return' => 'xsd:string'), $ns);

$server->register('Impresion',
                    Array('filtro' => 'xsd:string'),
                    Array('return' => 'xsd:string'), $ns);

if (isset($HTTP_RAW_POST_DATA))
	$input = $HTTP_RAW_POST_DATA;
else
	$input = implode("\r\n", file('php://input'));

$server->service($input);
exit;
?>