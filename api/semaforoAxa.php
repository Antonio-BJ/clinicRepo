<?php 
/************************************************************************************
*************************************************************************************
*******             SEMAFORO AXA - CUIDADOS ESPECIALES
*******             Septiembre 2016 / Samuel Ramirez
*******
*************************************************************************************
*************************************************************************************/

include 'myClasses/SemaforoAxa.php';

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];


/****************************** TRAEMOS LOS DATOS DE LAS UNIDADES ************************/
if($funcion=='getRegistros'){  
    $semaforoAxa = new SemaforoAxa();
    $busqueda = $semaforoAxa->getRegistros();    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS ************************/
if($funcion=='verDetalles'){
    $postdata = file_get_contents("php://input");    
    $folio = $_GET['folio'];
    //print_r($folio);
    $semaforoAxa = new SemaforoAxa();
    $busqueda = $semaforoAxa->verDetalles($folio); 
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** BUSCAMOS UN REGISTRO EN PARTICULAR ************************/
if($funcion=='buscaFolio'){
	$postdata = file_get_contents("php://input");
	$datos    = json_decode($postdata);
	//print_r($datos);
    $semaforoAxa = new SemaforoAxa();
    $busqueda = $semaforoAxa->buscaFolio($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

### TERMINA TODO ###
?>