<?php
/************************************************************************************
*************************************************************************************
*******             
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Estatus.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getRegistro'){
    $estatus = new Estatus();
    $unidad     = $_GET['uni'];
    $usuario    = $_GET['usr'];
    
    $mensaje = $estatus->getRegistro($unidad,$usuario);    
	echo json_encode($mensaje); ;
}
/***************************************************************************************************/

/******************************  Guarda inicio de turno del médico ************************/
if($funcion=='inicioTurno'){

    $estatus = new Estatus();
    $unidad     = $_GET['uni'];
    $usuario    = $_GET['usr'];    
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);        
    $mensaje = $estatus->inicioTurno($unidad,$usuario,$datos);    
    echo json_encode($mensaje);
}
/***************************************************************************************************/
/******************************  Guarda inicio de pausa del médico ************************/
if($funcion=='inicioPausa'){
    $estatus = new Estatus();
    $unidad     = $_GET['uni'];
    $usuario    = $_GET['usr'];
    $idEstatus  = $_GET['idEstatus'];
    $idPausa    = $_GET['idPausa']; 
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);        
    $mensaje = $estatus->inicioPausa($unidad,$usuario,$idEstatus,$idPausa,$datos);    
    echo json_encode($mensaje);
}
/***************************************************************************************************/
/******************************  Guarda fin de pausa del médico ************************/
if($funcion=='finPausa'){
    $estatus = new Estatus();
    $unidad     = $_GET['uni'];
    $usuario    = $_GET['usr'];
    $idEstatus  = $_GET['idEstatus'];
    $idPausa    = $_GET['idPausa']; 
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);        
    $mensaje = $estatus->finPausa($unidad,$usuario,$idEstatus,$idPausa,$datos);    
    echo json_encode($mensaje);
}
/***************************************************************************************************/
/******************************  Guarda fin de pausa del médico ************************/
if($funcion=='finTurno'){
    $estatus = new Estatus();
    $unidad     = $_GET['uni'];
    $usuario    = $_GET['usr'];
    $idEstatus  = $_GET['idEstatus'];
    $idPausa    = $_GET['idPausa']; 
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);        
    $mensaje = $estatus->finTurno($unidad,$usuario,$idEstatus,$idPausa,$datos);    
    echo $mensaje;
}
/***************************************************************************************************/
/******************************  Guarda fin de pausa del médico ************************/
if($funcion=='enviaReseteo'){
    $estatus = new Estatus();
    $unidad     = $_GET['uni'];
    $usuario    = $_GET['usr'];
    $idEstatus  = $_GET['idEstatus'];
    $idPausa    = $_GET['idPausa']; 
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);        
    $mensaje = $estatus->resetearTurno($unidad,$usuario,$idEstatus,$idPausa,$datos);    
    echo $mensaje;
}
/***************************************************************************************************/
/****************************** OBTIENE EL LISTADO DE MEDICOS DISPONIBLES ************************/
if($funcion=='getListado'){
    $estatus = new Estatus();
    $unidad = $_GET['uni'];
    
    $mensaje = $estatus->listadoMedicosDisponibles($unidad);    
    echo json_encode($mensaje);
}
/***************************************************************************************************/
/****************************** Nota interna para folios ************************/
if($funcion=='notaInterna'){
    $estatus = new Estatus();
    $folio =        $_GET['fol'];
    $comentario =   $_GET['comentario'];
    $usr =          $_GET['usr'];     
    $mensaje = $estatus->guardaNotaInterna($folio, $comentario, $usr);    
    echo json_encode($mensaje);
}
/***************************************************************************************************/
?>
