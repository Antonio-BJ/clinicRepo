<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Catalogos.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
// error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='getCatAjustador'){
    $compania       =$_GET['compania'];
    $unidad         =$_GET['unidad'];  
    $catAjustador = new Catalogos();    
    $listadoAjustador = $catAjustador->getlistadoAjustador($compania,$unidad);    
    echo json_encode($listadoAjustador);
}
/*****************************************************************************************/
/******************************  ver producto ************************/
if($funcion=='verProducto'){
   	$fol=$_GET['fol'];
    $producto = new Catalogos();    
    $cveProducto = $producto->getProducto($fol);    
    echo $cveProducto;
}
/*****************************************************************************************/

/******************************  ver compañía ************************/
if($funcion=='verProducto'){
    $fol=$_GET['fol'];
 $producto = new Catalogos();    
 $cveProducto = $producto->getCia($fol);    
 echo $cveCia;
}
/*****************************************************************************************/

/******************************  ver compañia y localidad ************************/
if($funcion=='verCiaLocalidad'){
   	$fol=$_GET['fol'];
    $producto = new Catalogos();    
    $ciaLocalidad = $producto->getCiaLocalidad($fol);    
    echo json_encode($ciaLocalidad);
}
/*****************************************************************************************/

/******************************  Catalogo de unidades activas ************************/
if($funcion=='catUnidades'){    
    $unidades = new Catalogos();    
    $listadoUnidades = $unidades->getListadoUnidades($fol);    
    echo json_encode($listadoUnidades);
}
/*****************************************************************************************/
/******************************  Catalogo de unidades activas ************************/
if($funcion=='unidadPropia'){
    $unidad=$_GET['unidad'];
    $unidades = new Catalogos();    
    $propia = $unidades->getUnidad($unidad);    
    echo $propia;
}
/*****************************************************************************************/
/****************************** ver zona para validacion de producto ************************/
if($funcion=='verZona'){
    $CveUnidadAlter=$_GET['CveUnidadAlter'];
    $buscaZona = new Catalogos();    
    $zona = $buscaZona->getZona($CveUnidadAlter);    
    echo $zona;
}
/*****************************************************************************************/

/****************************** listado de avisos de coordinacion medica*******************/
if($funcion=='avisosCoordinacion'){ 
    $fol=$_GET['fol'];   
    $avisos = new Catalogos();    
    $listadoAvisos = $avisos->getavisosCoordinacion($fol);    
    echo json_encode($listadoAvisos);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='getMembresia'){ 
    $fol=$_GET['fol'];   
    $avisos = new Catalogos();    
    $membresia = $avisos->getMembresia($fol);    
    echo json_encode($membresia);
}
/*****************************************************************************************/

/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='getCambioUnidad'){ 
    $fol=$_GET['fol'];   
    $avisos = new Catalogos();    
    $cambio = $avisos->getCambioUnidad($fol);    
    echo $cambio;
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='getRehabilitaciones'){ 
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    
    $fol=$_GET['fol'];       
    $avisos = new Catalogos();    
    $listado = $avisos->getRehabsFol($fol);    
    echo json_encode($listado);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='getCatCancelacion'){ 
    $catCancel = new Catalogos();    
    $catCancelados = $catCancel->getCatCancelados();    
    echo json_encode($catCancelados);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='getLocalidades'){ 
    $catLoc = new Catalogos();    
    $catLocalidades = $catLoc->getCatLocalidades();    
    echo json_encode($catLocalidades);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='verMedico'){ 
    $med = $_GET['usr'];
    $catCancel = new Catalogos();    
    $catCancelados = $catCancel->getMedico($med);    
    echo $catCancelados;
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='datosAccidente'){ 
    $fol = $_GET['fol'];
    $datosAccidente = new Catalogos();    
    $infDatAcc = $datosAccidente->datAccidente($fol);    
    echo json_encode($infDatAcc);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='diagnostico'){ 
    $fol = $_GET['fol'];
    $diag = new Catalogos();    
    $infDiag = $diag->diagnostico($fol);    
    echo json_encode($infDiag);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='pronostico'){ 
    $fol = $_GET['fol'];
    $pron = new Catalogos();    
    $pronostico = $pron->pronostico($fol);    
    echo json_encode($pronostico);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='lesionAdmin'){ 
    $fol = $_GET['fol'];
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->lesionAdmin($fol);    
    echo json_encode($LesAdmin);
}
/*****************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='LesionSelect'){ 
    $fol = $_GET['fol'];
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->lesionSelecc($fol);    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de las proyecciones, sustituyendo a la tabla rx ************************/
if($funcion=='Unidad'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->UnidadNombre($uni);    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='membresiasEmitidas'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->membresiasEmitidas($uni);    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='recibosEmitidos'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->recibosEmitidos($uni);    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='recibosMembresias'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->recibosMembresias($uni);    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='membresiasEmitidasAdmin'){ 
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->membresiasEmitidasAdmin();    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='recibosEmitidosAdmin'){ 
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->recibosEmitidosAdmin();    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='recibosMembresiasAdmin'){ 
    $lesion = new Catalogos();    
    $LesAdmin = $lesion->recibosMembresiasAdmin();    
    echo json_encode($LesAdmin);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='catCompania'){ 
    $lesion = new Catalogos();    
    $catCia = $lesion->catalogoCompania();    
    echo json_encode($catCia);
}
/***************************************************************************************************************/
/****************************** listado de lesiones codificadas ************************/
if($funcion=='lesionCodificada'){ 
    $op=$_GET['opcion'];   
    $lesion = new Catalogos();    
    $lesionCod = $lesion->lesionCodificada($op);    
    echo json_encode($lesionCod);
}
/*****************************************************************************************/
/****************************** listado de lesiones codificadas CIE10 ************************/
if($funcion=='lesionCodificadaLesion'){ 
    $op=$_GET['opcion'];   
    $lesion = new Catalogos();    
    $lesionCodificada = $lesion->lesionCodificadaLesion($op);    
    echo json_encode($lesionCodificada);
}
/*****************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='getSinCuestionario'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $sinCU = $lesion->getSinCuestionario($uni);    
    echo json_encode($sinCU);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='getConCuestionario'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $sinCU = $lesion->getConCuestionario($uni);    
    echo json_encode($sinCU);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='getTodoCuestionario'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $sinCU = $lesion->getTodoCuestionario($uni);    
    echo json_encode($sinCU);
}
/***************************************************************************************************************/
/****************************** monitor cuestionario ************************/
if($funcion=='getMonitorCuestionarios'){ 
    $uni = $_GET['uni'];
    $lesion = new Catalogos();    
    $sinCU = $lesion->getMonitorCuestionario($uni);    
    echo json_encode($sinCU);
}
/***************************************************************************************************************/
/****************************** catalogo de RX con tipo y zona     ************************/
if($funcion=='folioZima'){ 
    $fol = $_GET['fol'];
    $usr = $_GET['usr'];
    $catRX = new Catalogos();    
    $listadoRX = $catRX->busquedaFolioZima($fol,$usr);    
    echo json_encode($listadoRX);
}
/*****************************************************************************************/
/****************************** catalogo de RX con tipo y zona     ************************/
if($funcion=='listaRxZonaTipo'){ 
    $catRX = new Catalogos();    
    $listadoRX = $catRX->listaRxZonaTipo();    
    echo json_encode($listadoRX);
}
/*****************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='catEmpresas'){ 
    $lesion = new Catalogos(); 
    $op = $_GET['opcion'];   
    $catCia = $lesion->catalogoEmpresas($op);    
    echo json_encode($catCia);
}
/***************************************************************************************************************/
/****************************** listado de membresías emitidas por unidad ************************/
if($funcion=='masUsados'){ 
    $uni=$_GET['uni'];   
    $lesion = new Catalogos();    
    $catUsados = $lesion->masUsados($uni);    
    echo json_encode($catUsados);
}
/***************************************************************************************************************/
if($funcion=='validaDescuento'){ 
    $cat = new Catalogos();
    $item = $_GET['item'];    
    $cod = $_GET['codigo'];
    $aviso = $cat->validaDescuento($item,$cod);    
    echo json_encode($aviso);
}

if($funcion=='CatTwitter'){ 
    $cat = new Catalogos();    
    $aviso = $cat->catTwitter();    
    echo json_encode($aviso);
}
if($funcion=='getMedico'){ 
    $cat = new Catalogos();  
    $med = $_GET['Med'];      
    $aviso = $cat->esMedico($med);    
    echo json_encode($aviso);
}
if($funcion=='compania'){ 
    $cat = new Catalogos();  
    $aviso = $cat->compania();    
    echo json_encode($aviso);
}

if($funcion=='getDetalleCanelacion'){ 
    $cat = new Catalogos(); 
    $folio = $_GET['fol'];      
    $aviso = $cat->getDetalleCanelacion($folio);    
    echo json_encode($aviso);
}

?>
