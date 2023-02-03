<?php
/************************************************************************************
*************************************************************************************
*******             Autrizaciones zima
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/AutZima.php');

set_time_limit(3600);
//sin limite me memoria
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='getAutorizacion'){
    $aut       =$_GET['aut'];
    $folZima       =$_GET['folZima'];
    $autorizaciones = new AutZima();
    $autorizacion = $autorizaciones->getAutorizacion($aut,$folZima);
    echo json_encode($autorizacion);
}
/******************************  Guardar rehabilitacion AIG zima ************************/
if($funcion=='guardaRehabilitacion'){
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $folZima   =$_GET['folZima'];
    $usr       =$_GET['usr'];
    $uni       =$_GET['uni'];
    $autorizaciones = new AutZima();
    $autorizacion = $autorizaciones->setAutorizacion($folZima,$usr,$uni,$datos);
    echo json_encode($autorizacion);
}
?>
