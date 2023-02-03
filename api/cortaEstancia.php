<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/CortaEstancia.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getSolicitud'){
    $solicitud = new CortaEstancia();
    $fol    =$_GET['fol'];
    $existeSoliciutd = $solicitud->getSolicitud($fol);    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/

/******************************  agregar nueva solicitud de documentos CE ************************/
if($funcion=='setSolicitud'){
    $solicitud = new CortaEstancia();
    $fol    =$_GET['fol'];
    $usr    =$_GET['usr'];
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);       
    $solicitudSave = $solicitud->setSolicitud($fol,$usr,$datos);    
    echo json_encode($solicitudSave);
}
/***************************************************************************************************/
/****************************** validar si exste autorización  *************************************/
if($funcion=='validaAut'){
    $autorizacion = new CortaEstancia();
    $fol    =$_GET['fol'];
    $aut    =$_GET['aut'];    
    $autValidada = $autorizacion->getAut($fol,$aut);    
    echo json_encode($autValidada);
}
/***************************************************************************************************/
/****************************** validar si exste autorización  *************************************/
if($funcion=='getcheckValida'){
    $autorizacion = new CortaEstancia();
    $fol    =$_GET['fol'];   
    $autValidada = $autorizacion->getcheckValida($fol,$aut);    
    echo json_encode($autValidada);
}
/***************************************************************************************************/

/****************************** validar si exste autorización  *************************************/
if($funcion=='getfolioCE'){
    $autorizacion = new CortaEstancia();
    $fol    =$_GET['fol'];   
    $autValidada = $autorizacion->getfolioCE($fol);    
    echo json_encode($autValidada);
}
/***************************************************************************************************/
?>
