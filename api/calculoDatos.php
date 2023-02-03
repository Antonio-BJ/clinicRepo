<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/RFC.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='calculoRFC'){    
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);       
    $nombre = $datos->nombre;
    $pat 	= $datos->paterno; 
    $mat 	= $datos->materno;
    $fecnac = $datos->fecNac;    
    $getRfc = new RFC();
    $rfc = $getRfc->CalculaRFC($nombre,$pat,$mat,$fecnac);    
    echo $rfc;
}
/***************************************************************************************************/

?>
