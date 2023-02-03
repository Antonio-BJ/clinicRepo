<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('classes/AutoriacionesMedicas.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getAutorizacionesZima'){
	$fol=$_GET['fol'];  	
    $convenio = new AutoriacionesMedicas();   
    $autoZima = $convenio->getAutZima($fol);    
    echo json_encode($autoZima);
}
/***************************************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getAutorizacionesMV'){
	$fol=$_GET['fol'];  	
    $auto = new AutoriacionesMedicas();   
    $autoMv = $auto->getAutMV($fol);    
    echo json_encode($autoMv);
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

?>
