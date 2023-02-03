<?php
/************************************************************************************
*************************************************************************************
*******             Facturación Express 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/FactExpress.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/****************************** listado de Unidades propias  ************************/
if($funcion=='getUnidadesPropias'){
    $FactExpress = new FactExpress();
    $unidades = $FactExpress->getUnidadesPropias();    
    echo json_encode($unidades);
}

/*****************************************************************************************/

/****************************** listado de Unidades propias  ************************/
if($funcion=='getFoliosUnidad'){
    $unidad = $_GET['unidad'];
    $FactExpress = new FactExpress();
    $unidades = $FactExpress->getFoliosUnidad($unidad);    
    echo json_encode($unidades);
}

/*****************************************************************************************/
?>
