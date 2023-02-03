<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Rx.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='getSolRx'){ 
    $uni = $_GET['uni'];
    $lesion = new Rx();    
    $sinCU = $lesion->getSinCuestionario($uni);    
    echo json_encode($sinCU);
}
/***************************************************************************************************************/
/****************************** Solicitar Estudios ************************/
if($funcion=='SolicitarEstudios'){ 
    $fol = $_GET['fol'];
    $usr = $_GET['usr'];
    $uni = $_GET['uni'];
    $rx = new Rx();    
    $respEst = $rx->solicitarEstudios($fol,$usr,$uni);    
    echo json_encode($respEst);
}
/***************************************************************************************************************/
/****************************** Solicitar Estudios ************************/
if($funcion=='listadoRxSol'){ 
    $fol = $_GET['fol'];    
    $rx = new Rx();    
    $respEst = $rx->listadoRxSol($fol);    
    echo json_encode($respEst);
}
/***************************************************************************************************************/

/****************************** Estudios solicitados************************/
if($funcion=='buscaRXSolicitados'){ 
    $fol = $_GET['fol'];    
    $rx = new Rx();    
    $respEst = $rx->buscaRXSolicitados($fol);    
    echo json_encode($respEst);
}
/***************************************************************************************************************/
?>
