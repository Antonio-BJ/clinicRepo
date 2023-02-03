<?php
/************************************************************************************
*************************************************************************************
*******             LOG DE CAMBIOS AL SISTEMA
*******             by:  SAMUEL
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/LogDeCambios.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/****************************** RECUPERA LOS ULTIMOS CAMBIOS ************************/
if($funcion=='getCambios'){
    $logCambios = new LogDeCambios();
    $busqueda = $logCambios->getCambios();
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** GUARDA INFORME ************************/
if($funcion=='nuevoCambio'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata); 
    print_r($datos);

    $logCambios = new LogDeCambios();
    $busqueda = $logCambios->nuevoCambio($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

?>
