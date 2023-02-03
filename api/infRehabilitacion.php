<?php
/************************************************************************************
*************************************************************************************
*******             INFORME DE REHABILITACION
*******             by:  SAMUEL
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/InfRehabilitacion.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/****************************** NOMBRE DE PACIENTE ************************/
if($funcion=='buscaPaciente'){
    $folio=$_GET['fol'];
    #print_r($folio);

    $infRehabilitacion = new InfRehabilitacion();
    $busqueda = $infRehabilitacion->buscaPaciente($folio);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** BUSCA INFORME INCONCLUSO ************************/
if($funcion=='buscaInfRehab'){
    $folio=$_GET['fol'];
    $username=$_GET['med'];

    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);  

    $infRehabilitacion = new InfRehabilitacion();
    $busqueda = $infRehabilitacion->buscaInfRehab($folio,$username);  
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** GUARDA INFORME ************************/
if($funcion=='guardaInfRehab'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata); 
    #print_r($datos);

    $infRehabilitacion = new InfRehabilitacion();
    $busqueda = $infRehabilitacion->guardaInfRehab($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** TERMINA INFORME INCONCLUSO ************************/
if($funcion=='terminaInforme'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata); 
    #print_r($datos);

    $infRehabilitacion = new InfRehabilitacion();
    $busqueda = $infRehabilitacion->terminaInforme($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** RECUPERA LISTADO DE NOTAS DE  REHABILITACIONES ************************/
if($funcion=='getListaNotasRehab'){
    $folio=$_GET['fol'];
    #print_r($datos);

    $infRehabilitacion = new InfRehabilitacion();
    $busqueda = $infRehabilitacion->getListaNotasRehab($folio);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** BUSCA LA INFORMACIÓN SI EL PACEIENTE LLEGÓ POR ÓRDEN DE REHABILITACIÓN DE MÉDICO INTERNO (SISTEMA DE PASES)  ************************/
if($funcion=='medicoOrden'){
    $folio=$_GET['fol'];
    #print_r($datos);

    $infRehabilitacion = new InfRehabilitacion();
    $busqueda = $infRehabilitacion->medicoOrden($folio);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/
?>
