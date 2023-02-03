<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Cancelacion.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='buscaParametros'){
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $busqueda = new Cancelacion();    
    $listadoBusqueda = $busqueda->getFolioNombre($datos);    
    echo json_encode($listadoBusqueda);
}
/*****************************************************************************************/

if($funcion=='enviaDatosCancelacion'){
    $usr = $_GET['usr'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $busqueda = new Cancelacion();    
    $listadoBusqueda = $busqueda->setCancelacion($datos,$usr);    
    echo $listadoBusqueda;
}

if($funcion=='enviaDatosActivacion'){
    $usr = $_GET['usr'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $busqueda = new Cancelacion();    
    $listadoBusqueda = $busqueda->setActivacion($datos,$usr);    
    echo $listadoBusqueda;
}

?>
