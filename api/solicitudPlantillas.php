<?php
/************************************************************************************
*************************************************************************************
*******             SOLICITUD DE PLANTILLAS
*******             by:  SAMUEL
*******				Octubre 2016
*************************************************************************************
*************************************************************************************/


include ('myClasses/SolPlantillas.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];


/****************************** GUARDA SOLICITUD DE PLANTILLAS ************************/
if($funcion=='enviarSolicitud'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->enviarSolicitud($datos);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** RECUPERA LAS ULTIMAS SOLICITUDES ************************/
if($funcion=='getSolicitudes'){
	$unidad=$_GET['uni'];
    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->getSolicitudes($unidad);
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** MAARCA SOLICITUD COMO ENTREGADA ************************/
if($funcion=='marcaEntrega'){
	$idSolicitud=$_GET['idSolicitud'];
    $usrEntrega=$_GET['usr'];
    //$postdata = file_get_contents("php://input");    
    //$datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->marcaEntrega($idSolicitud, $usrEntrega);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** MARCA SOLICITUD COMO PAGADA ************************/
if($funcion=='registraPago'){
    $idSolicitud=$_GET['idSolicitud'];
    //$postdata = file_get_contents("php://input");    
    //$datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->registraPago($idSolicitud, $usrEntrega);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/


/****************************** REGISTRA SOLICITUD A PROVEEDOR ************************/
if($funcion=='solicitudProveedor'){
    $idSolicitud=$_GET['idSolicitud'];
    //$postdata = file_get_contents("php://input");    
    //$datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->solicitudProveedor($idSolicitud, $usrEntrega);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** REGISTRA PRODUCTO EN MV OFICINAS ************************/
if($funcion=='enMVoficinas'){
    $idSolicitud=$_GET['idSolicitud'];
    //$postdata = file_get_contents("php://input");    
    //$datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->enMVoficinas($idSolicitud, $usrEntrega);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** PRODUCTO EN CAMINO A CLINICA ************************/
if($funcion=='caminoClinica'){
    $idSolicitud=$_GET['idSolicitud'];
    //$postdata = file_get_contents("php://input");    
    //$datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->caminoClinica($idSolicitud, $usrEntrega);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** REGISTRA PRODUCTO EN CLINICA ************************/
if($funcion=='enClinica'){
    $idSolicitud=$_GET['idSolicitud'];
    //$postdata = file_get_contents("php://input");    
    //$datos = json_decode($postdata); 
    //print_r($datos);

    $solicitudPlantillas = new SolPlantillas();
    $busqueda = $solicitudPlantillas->enClinica($idSolicitud, $usrEntrega);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/
?>
