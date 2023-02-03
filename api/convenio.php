<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Convenio.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getMembresia'){
    $convenio = new Convenio();
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);       
    $existeMembresia = $convenio->getMembresia($datos);    
    echo json_encode($existeMembresia);
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
if($funcion=='reFacturar'){
    $convenio = new Convenio();
    $noRecibo = $_GET['noRecibo'];
    $usr      = $_GET['usr'];    
    $membresia = $convenio->refacturacion($noRecibo,$usr);    
    echo json_encode($membresia);
}
/***************************************************************************************************/
/******************************  Función para agregar membresías ************************/
if($funcion=='guardarMembresia'){
    $convenio = new Convenio();
    $uni = $_GET['uni'];
    $usr      = $_GET['usr'];  
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);         
    $membresia = $convenio->setMembresiaSinRegistro($usr,$uni,$datos);    
    echo json_encode($membresia);
}
/***************************************************************************************************/
/******************************  Función para agregar membresías ************************/
if($funcion=='guardarMembresiaFolio'){
    $convenio = new Convenio();
    $uni = $_GET['uni'];
    $usr      = $_GET['usr'];  
    $fol      = $_GET['fol'];
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);         
    $membresia = $convenio->setMembresiaSinRegistro($usr,$uni,$datos,$fol);    
    echo json_encode($membresia);
}
/***************************************************************************************************/
/******************************  Función para agregar membresías ************************/
if($funcion=='verDatosMembresia'){
    $convenio = new Convenio();
    $fol = $_GET['fol'];            
    $membresia = $convenio->verDatosMembresia($fol);    
    echo json_encode($membresia);
}
/***************************************************************************************************/
/******************************  Función para agregar membresías ************************/
if($funcion=='actualizaNavegador'){
    $convenio = new Convenio();
    $usr = $_GET['usr'];            
    $actualiza = $convenio->actualizaEstatus($usr);    
    echo json_encode($actualiza);
}
/***************************************************************************************************/

/******************************  Función para agregar membresías ************************/
if($funcion=='enviarCorreoRH'){
    $convenio = new Convenio();
    $fol = $_GET['fol'];        
    $correo = $convenio->enviarCorreoRH($fol);    
    echo json_encode($correo);
}

if($funcion=='modificarRegistro'){
    $convenio = new Convenio();      
    $correo = $convenio->modifcarRegistro();    
    echo json_encode($correo);
}

if($funcion=='enviarMembresia'){
    $convenio = new Convenio();      
    $correo = $convenio->enviarMembresia();    
    echo json_encode($correo);
}
/***************************************************************************************************/
?>
