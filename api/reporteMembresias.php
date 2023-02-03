<?php 
/************************************************************************************
*************************************************************************************
*******             REPORTES DE MEMBRESIAS
*******             Abril 2016 / Samuel Ramirez
*******
*************************************************************************************
*************************************************************************************/

include 'myClasses/reporteMembresias.php';

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];



/****************************** BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS  ************************/
if($funcion=='buscaParametrosReporte'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);  
    //print_r($datos);
    $reporteMembresias = new reporteMembresias();
    $busqueda = $reporteMembresias->buscaParametrosReporte($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/


### TERMINA TODO ###
?>