<?php
/************************************************************************************
*************************************************************************************
*******             Clasificaciones
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Clasificacion.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/****************************** listado de Unidades activas  ************************/
if($funcion=='getUnidades'){
    $clasificacion = new Clasificacion();
    $unidades = $clasificacion->getUnidades();    
    echo json_encode($unidades);
}

/*****************************************************************************************/

/****************************** listado de aseguradoras   ************************/
if($funcion=='getCompanias'){
    $clasificacion = new Clasificacion();
    $companias = $clasificacion->getCompanias();    
    echo json_encode($companias);
}
/*****************************************************************************************/

/****************************** listado de    ************************/
if($funcion=='buscaParametros'){
	$uni=$_GET['uni'];
	$postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);     
    $clasificacion = new Clasificacion();
    $busqueda = $clasificacion->buscaParametros($datos,$uni);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/

/****************************** listado de folios para reporte   ************************/
if($funcion=='buscaParametrosReporte'){
    $uni=$_GET['uni'];
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);     
    $clasificacion = new Clasificacion();
    $busqueda = $clasificacion->buscaParametrosReporte($datos,$uni);    
    echo json_encode($busqueda);
}
/*****************************************************************************************/
/****************************** listado de folios sin documentacion   ************************/
if($funcion=='listadoSinDocumentacion'){
    $usr=$_GET['usr'];
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);     
    $clasificacion = new Clasificacion();
    $listadoSinDoc = $clasificacion->buscaFolSinDoc($datos,$usr);    
    echo json_encode($listadoSinDoc);
}
/*****************************************************************************************/
/****************************** listado de    ************************/
if($funcion=='verLocalidad'){
    $uni=$_GET['uni'];
    $clasificacion = new Clasificacion();
    $busqueda = $clasificacion->getLocalidad($uni);    
    echo $busqueda;
}
/*****************************************************************************************/
?>
