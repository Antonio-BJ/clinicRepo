<?php
/************************************************************************************
*************************************************************************************
*******             ENTREGA DE TURNO
*******             by:  SAMUEL
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/EntregaDeTurno.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/****************************** RECUPERA LOS ULTIMOS CAMBIOS ************************/
if($funcion=='getUsuarios'){
	$uniClave=$_GET['uni'];

    $entregaTurno = new EntregaDeTurno();
    $busqueda = $entregaTurno->getUsuarios($uniClave);
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** RECUPERA LOS ULTIMOS CAMBIOS ************************/
if($funcion=='getTurnos'){
    $entregaTurno = new EntregaDeTurno();
    $busqueda = $entregaTurno->getTurnos();
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** GUARDA INFORME ************************/
if($funcion=='cambioTurno'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata); 
    //print_r($datos);

    $entregaTurno = new EntregaDeTurno();
    $busqueda = $entregaTurno->cambioTurno($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

?>
