<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include ('myClasses/Layout.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
// error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='creaArchivo'){
    $folRecibo = $_GET['folRecibo'];
    $folio = $_GET['fol'];    
    $solicitud = new Layout();
    $existeSoliciutd = $solicitud->createFile($folio, $folRecibo);    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='prueba'){
    $folRecibo = $_GET['folRecibo'];
    $folio = $_GET['fol'];    
    $solicitud = new Layout();
    $existeSoliciutd = $solicitud->checa($folio, $folRecibo);    
    echo json_encode($existeSoliciutd);
}

if($funcion=='guardarPases'){    
    $solicitud = new Layout();
    $existeSoliciutd = $solicitud->guardarPasesFolios();    
}

if($funcion=='crearArchivoR'){    
    $folRecibo = $_GET['folRecibo'];
    $folio = $_GET['fol'];    
    $solicitud = new Layout();
    $existeSoliciutd = $solicitud->createFile($folio, $folRecibo, 'R');    
    echo json_encode($existeSoliciutd);
}
if($funcion = 'checarRecibo'){
    $folRecibo=$_GET['folRecibo'];
    $solicitud = new Layout();
    $existeSoliciutd = $solicitud->enviaRecibo($folRecibo);    
}
/***************************************************************************************************/
?>
