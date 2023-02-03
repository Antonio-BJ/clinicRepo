<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


//include ('myClasses/FlujoPX.php');
include ('myClasses/PasesClass.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='guardaPase'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);   
    $pases = new PasesClass();
    $pase = $pases->setPase($datos);    
    echo json_encode($pase);
}

?>
