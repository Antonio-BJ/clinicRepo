<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('http://atencionmedicainmediata.com/reportes/envioCorreoDocs.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='envioCorreoDocs'){
    $folio = $_GET['fol'];
    $usr = $_GET['usr'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $movil = new ClassMovil2();    
    $mail = $movil->envioCorreoDocs2($folio,$datos,$usr);    
    echo $mail;
}
?>
