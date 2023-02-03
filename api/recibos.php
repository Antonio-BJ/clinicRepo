<?php
/************************************************************************************
*************************************************************************************
*******             Clase para generación de recibos
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Recibos.php');

try{
set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];


/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getValuesItem'){
    $convenio = new Recibos();
    $cveItem = $_GET['cveItem'];
    //$datos = json_decode($postdata);           
    $infoItem = $convenio->getItem($cveItem);    
    echo json_encode($infoItem);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='setMembresia'){
    $convenio = new Convenio();
    $fol=$_GET['fol'];  
    $uni=$_GET['uni']; 
    $membresia = $convenio->setMembresia($fol,$uni);    
    echo json_encode($membresia);
}
/***************************************************************************************************/

/******************************  Agreghar membresia sin folio ************************/
if($funcion=='setMembresiaSinFol'){
    $convenio = new Convenio();
    $nombre=$_GET['nombre'];  
    $uni=$_GET['uni']; 
    $membresia = $convenio->setMembresiaSinFol($nombre,$uni);    
    echo json_encode($membresia);
}
/***************************************************************************************************/

/******************************  Agreghar membresia sin folio ************************/
if($funcion=='guardaRecibo'){
    $postdata = file_get_contents("php://input");
    $datosRecibo = new Recibos();
    $fol=$_GET['fol'];
    $usr=$_GET['usr'];  
    $tip=$_GET['tipoCobro']; 
    if(empty($tip)){
        $tip=0;
    }   
    $datos = json_decode($postdata);
    $folioRecibo = $datosRecibo->saveTicket($fol,$usr,$datos,$tip);    
    echo json_encode($folioRecibo);
}
/***************************************************************************************************/
/******************************  ver recibo si está creado ************************/
if($funcion=='modifRecibo'){ 

    $datosRecibo = new Recibos();
    $fol    =$_GET['folrecibo'];           
    $recibo = $datosRecibo->InsertItems_recibo($fol);    
    echo json_encode($recibo);
}
/***************************************************************************************************/
}catch(Exception $e){
    return $e->getMessage();
}
?>
