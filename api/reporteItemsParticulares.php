<?php 
/************************************************************************************
*************************************************************************************
*******             REPORTES DE PARTICULARES (ITEMS)
*******             Agosto 2016 / Samuel Ramirez
*******
*************************************************************************************
*************************************************************************************/

include 'myClasses/ReporteItemsParticulares.php';

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];


/****************************** TRAEMOS LOS DATOS DE LAS UNIDADES ************************/
/*if($funcion=='getUnidades'){  
    $reporteParticulares = new ReporteParticulares();
    $busqueda = $reporteParticulares->getUnidades();    
    echo json_encode($busqueda);
}*/
/*****************************************************************************************/

/****************************** BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS ************************/
if($funcion=='buscaParametrosReporte'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);  
    //print_r($datos);
    $reporteItemsParticulares = new ReporteItemsParticulares();
    $busqueda = $reporteItemsParticulares->buscaParametrosReporte($datos); 
    echo json_encode($busqueda);
}
/*****************************************************************************************/


### TERMINA TODO ###
?>