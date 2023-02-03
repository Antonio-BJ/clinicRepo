<?php
/************************************************************************************
*************************************************************************************
*******             Archivo para el control de acciones en la nota Médica
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/NotaMedica.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='modificaEstudioSol'){   
    $Interpretacion = new NotaMedica();
    $cveEst    =$_GET['cveEst'];
    $interpre  =$_GET['inter'];
    $fol       =$_GET['fol'];
    $resultado = $Interpretacion->setInterpretacioRx($cveEst,$interpre,$fol);    
    echo json_encode($resultado);
}
/*****************************************************************************************/

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='reporteQualitas'){   
    $reporteQualitas = new NotaMedica();
    $resultado = $reporteQualitas->reporteQualitas();    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='guardarMedicamentosNota'){
	$fol           = $_GET['fol']; 
	$usr           = $_GET['usr'];
	$uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];   
	$postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->guardarMedicamentos($fol,$usr,$uni,$datos,$tipoReceta);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='guardarMedicamentosSub'){
    $fol           = $_GET['fol']; 
    $usr           = $_GET['usr'];
    $uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];
    $contSub       = $_GET['contSub'];  
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->guardarMedicamentosSubsecuencia($fol,$usr,$uni,$datos,$tipoReceta,$contSub);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='eliminarMedicamentosNota'){
	$cveitem = $_GET['cveItemReceta']; 
    $usr = $_GET['usr'];
	$suministros = new NotaMedica();          
    $resultado = $suministros->eliminarMedicamentosNota($cveitem,$usr);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='guardarOrtesisNota'){
	$fol           = $_GET['fol']; 
	$usr           = $_GET['usr'];
	$uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];
       
	$postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->guardarOrtesis($fol,$usr,$uni,$datos,$tipoReceta);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='guardarOrtesisSub'){
    $fol           = $_GET['fol']; 
    $usr           = $_GET['usr'];
    $uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];
    $contSub       = $_GET['contSub'];
       
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->guardarOrtesisSubsecuencia($fol,$usr,$uni,$datos,$tipoReceta,$contSub);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='eliminarOrtesisNota'){
	$cveitem = $_GET['cveItemReceta'];
    $usr =  $_GET['usr'];
	$suministros = new NotaMedica();          
    $resultado = $suministros->eliminarOrtesisNota($cveitem,$usr);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  listado de medicamentos agregados ************************/
if($funcion=='listadoItems'){
	$fol 		= $_GET['fol']; 
	$tipoItem 	= $_GET['tipo'];
    $tipoReceta = $_GET['tipoReceta'];	
	$postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->listadoItems($fol,$tipoItem,$tipoReceta);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  listado de medicamentos agregados ************************/
if($funcion=='listadoRecetasSinSurtir'){
    
    $unidad = $_GET['uni'];  
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->listadoRecetasSinSurtir($unidad);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  checa si existe un addendum para esta nota medica de este folio***/
if($funcion=='checaAddendum'){
    $fol        = $_GET['fol'];          
    $addendum = new NotaMedica();    
    $resultado = $addendum->checaAddendum($fol);    
    echo $resultado;
}
/*****************************************************************************************/
?>
