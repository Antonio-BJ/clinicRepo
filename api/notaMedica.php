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
// error_reporting(0);

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
    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Headers: *");
        
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
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

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
/****************************** Guarda insumos de Corta Estancia ************************/
if($funcion=='guardarInsumosCortaEstancia'){
    $fol           = $_GET['fol']; 
    $usr           = $_GET['usr'];
    $uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];
       
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->guardarInsumosCortaEstancia($fol,$usr,$uni,$datos,$tipoReceta);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Guarda insumos de Corta Estancia ************************/
if($funcion=='getItemsCortaEstancia'){
    $fol = $_GET['fol'];

    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->getItemsCortaEstancia($fol);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Guarda insumos de Corta Estancia ************************/
if($funcion=='saveInsumosCortaEstancia'){
    $fol           = $_GET['fol']; 
    $usr           = $_GET['usr'];
    $uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];
       
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->saveInsumosCortaEstancia($fol,$usr,$uni,$tipoReceta,$datos);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Elimina insumos de corta estancia ************************/
if($funcion=='eliminaInsumosCortaEstancia'){
    $cveitem    = $_GET['cveItemReceta'];
    $usr        = $_GET['usr'];
    $suministros = new NotaMedica();          
    $resultado = $suministros->eliminaInsumosCortaEstancia($cveitem, $usr);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Guarda indicaciones de Corta Estancia ************************/
if($funcion=='saveIndicacionesCE'){
    $fol = $_GET['fol'];
    $usr = $_GET['usr'];
    $uni = $_GET['uni'];

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $suministros = new NotaMedica();
    $resultado = $suministros->saveIndicacionesCE($fol, $usr, $uni, $datos);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Recupera indicaciones de Corta Estancia ************************/
if($funcion=='getIndicacionesCE'){
    $fol = $_GET['fol'];

    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->getIndicacionesCE($fol);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Elimina indicaciones de corta estancia ************************/
if($funcion=='deleteIndicacionCE'){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    
    $fol            = $_GET['fol'];
    $idIndicacion   = $_GET['idIndicacion'];

    $suministros = new NotaMedica();          
    $resultado = $suministros->deleteIndicacionCE($fol, $idIndicacion);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/******************************  Verifica si existe receta complementaria para un folio en específico***/
if($funcion=='getRecComp'){
    $fol = $_GET['fol'];    
    try {
        $suministros = new NotaMedica();          
        $resultado = $suministros->checaRecetaComplementaria($fol);    
        echo json_encode($resultado);    
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
}
/*****************************************************************************************/

/****************************** Items del botiquin de particulares ************************/
if($funcion=='guardaItemsParticulares'){
    $fol           = $_GET['fol']; 
    $usr           = $_GET['usr'];
    $uni           = $_GET['uni'];
    $tipoReceta    = $_GET['tipoReceta'];
       
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->guardaItemsParticulares($fol,$usr,$uni,$tipoReceta,$datos);    
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** trae listado de receta particulares ************************/
if($funcion=='getItemsRecetaInterna'){
    $fol = $_GET['fol'];

    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->getItemsRecetaInterna($fol);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Elimina item de receta particulares ************************/
if($funcion=='eliminaItemParticulares'){
    $cveitem    = $_GET['cveItemReceta'];
    $usr        = $_GET['usr'];
    $suministros = new NotaMedica();          
    $resultado = $suministros->eliminaItemParticulares($cveitem, $usr);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Guarda indicaciones de receta particulares ************************/
if($funcion=='saveIndicacionesParticulares'){
    $fol = $_GET['fol'];
    $usr = $_GET['usr'];
    $uni = $_GET['uni'];

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $suministros = new NotaMedica();
    $resultado = $suministros->saveIndicacionesParticulares($fol, $usr, $uni, $datos);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Recupera indicaciones Receta particulares ************************/
if($funcion=='getIndicacionesParticulares'){
    $fol = $_GET['fol'];

    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);          
    $suministros = new NotaMedica();    
    $resultado = $suministros->getIndicacionesParticulares($fol);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Guarda items receta externa ************************/
if($funcion=='saveItemRE'){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    $fol = $_GET['fol'];
    $usr = $_GET['usr'];
    $uni = $_GET['uni'];

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $suministros = new NotaMedica();
    $resultado = $suministros->saveItemRE($fol, $usr, $uni, $datos);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Recupera indicaciones Receta particulares ************************/
if($funcion=='getItemsRecetaExterna'){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    $fol = $_GET['fol'];
       
    $suministros = new NotaMedica();    
    $resultado = $suministros->getItemsRecetaExterna($fol);
    echo json_encode($resultado);
}
/*****************************************************************************************/

/****************************** Guarda items receta externa ************************/
if($funcion=='eliminaItemRE'){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    $fol        = $_GET['fol'];
    $idItemExt  = $_GET['idItemExt'];

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $suministros = new NotaMedica();
    $resultado = $suministros->eliminaItemRE($fol, $idItemExt);
    echo json_encode($resultado);
}
/*****************************************************************************************/
/****************************** Verifica si existe receta particulares para un folio en específico***/
if($funcion=='getRecPart'){
    $fol = $_GET['fol'];    
    try {
        $suministros = new NotaMedica();          
        $resultado = $suministros->getRecPart($fol);    
        echo json_encode($resultado);    
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
}
/*****************************************************************************************/
/******************************  confirma datos receta ***/
if($funcion=='confirmaReceta'){
    $fol = $_GET['fol'];        
    try {        
        $datosReceta = new NotaMedica();          
        $resultado = $datosReceta->getDatosReceta($fol);    
        echo json_encode($resultado);    
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
}
/*****************************************************************************************/

/******************************  confirma datos receta ***/
if($funcion=='getMedico'){
    $usr = $_GET['usr'];        
    try {        
        $datosReceta = new NotaMedica();          
        $resultado = $datosReceta->getMedico($usr);    
        echo json_encode($resultado);    
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
}
/*****************************************************************************************/

if($funcion=="guardaEjerciciosMovil"){
    $fol = $_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $ejercicios = new NotaMedica();    
    $resultado = $ejercicios->guardaEjerciciosMovil($fol, $datos);
    echo json_encode($resultado);
}

?>
