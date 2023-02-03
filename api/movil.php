<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/ClassMovil.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='getlistadoMovil'){
    
    $movil = new ClassMovil();    
    $usr = $_GET['usr'];
    $listadoMov = $movil->getlistadoMovil($usr);    
    echo json_encode($listadoMov);
}
if($funcion=='getUnidad'){

    $folio = $_GET['fol'];
    $movil = new ClassMovil();    
    $Unidad = $movil->getUnidad($folio);    
    echo json_encode($Unidad);
}
if($funcion=='getDatosHeredo'){

    $folio = $_GET['fol'];
    $con = $_GET['cont'];
    $movil = new ClassMovil();    
    $datoEnf = $movil->getDatosHeredo($folio,$con);    
    echo json_encode($datoEnf);
}

if($funcion=='getDatosPadecimiento'){
    $folio = $_GET['fol'];
    $con = $_GET['cont'];
    $movil = new ClassMovil();    
    $datoEnf = $movil->getDatosPadecimiento($folio,$con);    
    echo json_encode($datoEnf);
}

if($funcion=='getDatosAlergias'){
    $folio = $_GET['fol'];
    $con = $_GET['cont'];
    $movil = new ClassMovil();    
    $datoEnf = $movil->getDatosAlergias($folio,$con);    
    echo json_encode($datoEnf);
}

if($funcion=='editaDatosHeredo'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $movil = new ClassMovil();    
    $datoEnf = $movil->editaDatosHeredo($datos);    
    echo json_encode($datoEnf);
}

if($funcion=='editaDatosAlergia'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $movil = new ClassMovil();    
    $datoEnf = $movil->editaDatosAlergia($datos);    
    echo json_encode($datoEnf);
}

if($funcion=='editaDatosPadecimiento'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $movil = new ClassMovil();    
    $datoEnf = $movil->editaDatosPadecimiento($datos);    
    echo json_encode($datoEnf);
}

if($funcion=='guardaDocsRequeridosMovil'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $folio = $_GET['fol'];
    $movil = new ClassMovil();    
    $datoEnf = $movil->guardaDocsRequeridosMovil($datos, $folio);    
    echo json_encode($datoEnf);
}

if($funcion=='guardaDiagnostico'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $folio = $_GET['fol'];
    $movil = new ClassMovil();    
    $diag = $movil->guardaDiagnosticoMovil($datos, $folio);    
    echo json_encode($diag);
}

if($funcion=='veIndicaciones'){
    $folio = $_GET['fol'];
    $movil = new ClassMovil();    
    $contIndica = $movil->veIndicaciones($folio);    
    echo $contIndica;
}
if($funcion=='veReceta'){
    $folio = $_GET['fol'];
    $movil = new ClassMovil();    
    $contReceta = $movil->veReceta($folio);    
    echo json_encode($contReceta);
}
if($funcion=='vePase'){
    $folio = $_GET['fol'];
    $movil = new ClassMovil();    
    $contPase = $movil->vePase($folio);    
    echo $contPase;
}
if($funcion=='envioMensaje'){
    $folio = $_GET['fol'];
    $tel = $_GET['tel'];
    $movil = new ClassMovil();    
    $msj = $movil->envioMensaje($folio,$tel);    
    echo $msj;
}
if($funcion=='envioCorreo'){
    $folio = $_GET['fol'];
    $correo = $_GET['correo'];
    $correoAlt = $_GET['correoAlt'];
    $movil = new ClassMovil();    
    $mail = $movil->envioCorreo($folio,$correo, $correoAlt);    
    echo $mail;
}
if($funcion=='enviowhatsapp'){
    $folio = $_GET['fol'];
    $tel = $_GET['tel'];
    $movil = new ClassMovil();    
    $msj = $movil->enviowhatsapp($folio,$tel);    
    echo $msj;
}

if($funcion=='guardaLote'){
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $usr = $_GET['usr'];
    $movil = new ClassMovil();    
    $diag = $movil->guardaLote($datos, $usr);    
    echo json_encode($diag);
}
if($funcion=='listadoLotes'){
    $movil = new ClassMovil();    
    $diag = $movil->listadoLotes();    
    echo json_encode($diag);
}
if($funcion=='listarKits'){
    $movil = new ClassMovil(); 
    $noLote = $_GET['noLote'];   
    $diag = $movil->listarKits($noLote);    
    echo json_encode($diag);
}
if($funcion=='guardaKitUsado'){
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $usr = $_GET['usr'];
    $movil = new ClassMovil();    
    $diag = $movil->guardaKitUsado($datos, $usr);    
    echo json_encode($diag);
}

if($funcion=='modificaAtencion'){
    $fol = $_GET['fol'];
    $movil = new ClassMovil();    
    $diag = $movil->modificaAtencion($fol);    
    echo ($diag);
}

if($funcion=='terminarAtencion'){
    $fol = $_GET['fol'];
    $movil = new ClassMovil();    
    $diag = $movil->terminarAtencion($fol);    
    echo ($diag);
}
if($funcion=='envioCorreoDocs'){
    $folio = $_GET['fol'];
    $usr = $_GET['usr'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $movil = new ClassMovil();    
    $mail = $movil->envioCorreoDocs($folio,$datos,$usr);    
    echo $mail;
}


?>
