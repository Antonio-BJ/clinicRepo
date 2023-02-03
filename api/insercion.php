<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Insercion.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='insertFolio'){
    echo 'hola';
    $solicitud = new Insercion();
    $fol    =$_GET['fol'];
    $existeSoliciutd = $solicitud->insertFolios();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/
/******************************  insertar error :( ************************/
if($funcion=='insertErrores'){	
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->insertBorrados();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/

/******************************  insertar error :( ************************/
if($funcion=='insertFacts'){	
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->insertFacts();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/

/******************************  insertar error :( ************************/
if($funcion=='insertAplicaciones'){    
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->updateAplicaciones();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='prueba'){
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->listaFolio();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='pruebaExcel'){
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->listaExcel();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='insertaFolioZima'){
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->insertFoliosZima();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='insertaAxa'){
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->insertFoliosAxa();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='insertaCapturaAutomatica'){
    $solicitud = new Insercion();    
    $existeSoliciutd = $solicitud->insertCapturaAut();    
    echo json_encode($existeSoliciutd);
}
/***************************************************************************************************/







?>
