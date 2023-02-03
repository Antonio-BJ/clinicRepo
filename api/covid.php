<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/ClassCovid.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='guardaDatosCovid'){
    
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $folio = $_GET['fol'];
    $movil = new ClassCovid();    
    $diag = $movil->guardaDatosCovid($datos, $folio);    
    echo json_encode($diag);
}

if($funcion=='getHistoria'){
    $folio = $_GET['fol'];
    $movil = new ClassCovid();    
    $diag = $movil->getHistoria($folio);    
    echo json_encode($diag);
}



?>
