<?php
/************************************************************************************
*************************************************************************************
*******             control de flujo de pacientes 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


//include ('myClasses/FlujoPX.php');
include ('myClasses/Particulares.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='getNombre'){
    $flujopx = new FlujoPX();
    $fol    =$_GET['fol'];
    $nombre = $flujopx->getNombre($fol);    
    echo json_encode($nombre);
}
if($funcion=='checarEstatus'){
    $flujopx = new FlujoPX();
    $fol    =$_GET['fol'];
    $estatus = $flujopx->getEstatus($fol);    
    echo json_encode($estatus);
}
if($funcion=='inicioFlujoArea'){
    $flujopx = new FlujoPX();
    $fol    =$_GET['fol'];
    $area   =$_GET['area'];
    $usr    =$_GET['usr'];
    $estatus = $flujopx->startArea($fol,$area,$usr);    
    echo $estatus;
}
if($funcion=='finFlujoArea'){
    $flujopx = new FlujoPX();
    $fol    =$_GET['fol'];
    $area   =$_GET['area'];
    $usr    =$_GET['usr'];
    $estatus = $flujopx->endArea($fol,$area,$usr);    
    echo $estatus;
}
if($funcion=='getAreas'){
    $flujopx = new FlujoPX();
    $areas = $flujopx->getAreas();    
    echo json_encode($areas);
}
if($funcion=='getTurno'){
    $fol=$_GET['fol'];
    $flujopx = new FlujoPX();
    $turno = $flujopx->getTurno($fol);    
    echo $turno;
}
if($funcion=='guardaTurno'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);   
    $flujopx = new FlujoPX();
    $turno = $flujopx->setTurno($fol,$datos);    
    echo $turno;
}
//@ envia solicitud cancelación recibo
//

if($funcion=='enviaSolCancelRecibo'){
    $fol=       $_GET['fol'];
    $uni=       $_GET['uni'];
    $user=      $_GET['usr'];
    $folRec=    $_GET['folRec'];
    
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);      
    $solCancel = new Particulares();    
    $envio = $solCancel->sendSolCancel($datos,$fol,$uni,$user,$folRec);
    echo json_encode($envio);
}
/*****************************************************************************************/
/****************************** listado de Unidades activas  ************************/
if($funcion=='getRecibo'){
    $recibo = new Particulares();
    $cveRecibo = $_GET['Recibo_cont'];
    $datosRecibo = $recibo->getRecibo($cveRecibo);    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='SetAplicacion'){
    $recibo = new Particulares();
    $postdata = file_get_contents("php://input");
    $datos     = json_decode($postdata);         
    $aplicacion =  $datos->aplicacion;
    $cobro      =  $datos->cobro;
    $cveRecibo = $_GET['FolRecibo'];
    $serie     = $_GET['serie'];
    $usr       = $_GET['usr'];   
    $datosRecibo = $recibo->SetAplicacion($cveRecibo,$aplicacion,$cobro,$serie,$usr);    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='getPagos'){
    $recibo = new Particulares();    
    $cveRecibo = $_GET['Recibo_cont'];
    $serie     = $_GET['serie'];    
    $datosRecibo = $recibo->getPagos($cveRecibo,$serie);    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='getMonto'){
    $recibo = new Particulares();    
    $cveRecibo = $_GET['Recibo_cont'];
    $serie     = $_GET['serie'];    
    $datosRecibo = $recibo->getMonto($cveRecibo,$serie);    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='getControlParticulares'){
    $recibo = new Particulares();        
    $datosRecibo = $recibo->getControlParticulares();    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='getPacientesMedico'){
    $med = $_GET['med'];
    $recibo = new Particulares();        
    $datosRecibo = $recibo->getPacientesMedico($med);    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='getRecibosFolio'){
    $fol = $_GET['fol'];
    $recibo = new Particulares();        
    $datosRecibo = $recibo->getRecibosFolio($fol);    
    echo json_encode($datosRecibo);
}
/****************************** listado de Unidades activas  ************************/
if($funcion=='DeleteAplicacion'){
    $cve = $_GET['cve'];
    $recibo = new Particulares();        
    $datosRecibo = $recibo->delMontoRecibo($cve);    
    echo json_encode($datosRecibo);
}
/****************************** cehcar si es convenio con membresía  ************************/
if($funcion=='checaConvenio'){
    $fol = $_GET['fol'];
    $cechaConvenio = new Particulares();        
    $convenio = $cechaConvenio->getMembresiaConvenio($fol);    
    echo json_encode($convenio);
}
/****************************** cehcar si es convenio con membresía  ************************/
if($funcion=='cechaDoctos'){
    $fol = $_GET['fol'];
    $cechaConvenio = new Particulares();        
    $convenio = $cechaConvenio->getDoctosFol($fol);    
    echo json_encode($convenio);
}
/****************************** cehcar si es convenio con membresía  ************************/
if($funcion=='notaDigital'){
    $fol = $_GET['fol'];
    $cechaConvenio = new Particulares();        
    $convenio = $cechaConvenio->getNotaDigital($fol);    
    echo json_encode($convenio);
}
if($funcion=='repetidos'){
    $cechaConvenio = new Particulares();        
    $convenio = $cechaConvenio->deleteRepetidos();    
    echo json_encode($convenio);
}
if($funcion=='listadoRecibosCobranza'){
    $cechaConvenio = new Particulares();  
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);         
    $convenio = $cechaConvenio->listadoRecibosCobranza($datos);    
    echo json_encode($convenio);
}
if($funcion=='listadoRecibosCobranzaAvanzada'){
    $cechaConvenio = new Particulares();  
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);            
    $convenio = $cechaConvenio->listadoRecibosCobranzaAvanzada($datos);    
    echo json_encode($convenio);
}
if($funcion=='getDatosRecibo'){
    $cechaConvenio = new Particulares();         
    $serie = $_GET['serie'];
    $recibo = $_GET['recibo'];
    $convenio = $cechaConvenio->getDatosRecibo($serie,$recibo);    
    echo json_encode($convenio);
}
if($funcion=='guardaEstatusCob'){
    $cechaConvenio = new Particulares();  
    
    $color = $_GET['color'];
    $serie = $_GET['serie'];
    $recibo = $_GET['recibo'];
    $convenio = $cechaConvenio->setEstatusCob($color,$serie,$recibo);    
    echo json_encode($convenio);
}
if($funcion=='getEnfermeras'){
    $uni = $_GET['uni'];
    $cechaConvenio = new Particulares();         
    $convenio = $cechaConvenio->getEnfermeras($uni);    
    echo json_encode($convenio);
}

if($funcion=='getRhDomicilio'){
    $uni = $_GET['uni'];
    $cechaConvenio = new Particulares();         
    $convenio = $cechaConvenio->getRhDomicilio($uni);    
    echo json_encode($convenio);
}

if($funcion=='consultaRecibos'){
    $buscaRecibo = new Particulares();  
    $postdata = file_get_contents("php://input");  
    $uni = $_GET['unidad'];
    $usr = $_GET['usr'];    
    $datos = json_decode($postdata);            
    $convenio = $buscaRecibo->buscaRecibo($datos,$uni,$usr);    
    echo json_encode($convenio);
}

if($funcion=='enviaCancelacionRecibo'){
    $recibo = new Particulares();  
    $postdata = file_get_contents("php://input");
    $usr = $_GET['usr'];    
    $datos = json_decode($postdata);            
    $convenio = $recibo->enviaCancelacionRecibo($datos,$usr);    
    echo json_encode($convenio);
}

if($funcion=='detalleCancelacion'){
    $recibo = new Particulares();  
    $cont = $_GET['recibo'];           
    $convenio = $recibo->detalleCancelacion($cont);    
    echo json_encode($convenio);
}
///************** BY ANA DC *******************

if($funcion=='cargaRecibos'){
    $listaRecibo = new Particulares();  
    $postdata = file_get_contents("php://input");  
    $uni = $_GET['unidad'];
    $usr = $_GET['usr'];     
    $datos = json_decode($postdata);         
    $lista = $listaRecibo->listaRecibos($datos,$uni,$usr);    
    echo json_encode($lista);
}

if($funcion=='tarjetaRecibo'){
    $rec = new Particulares();  
    $postdata = file_get_contents("php://input");  
    $recibo = $_GET['recibo'];     
    $datos = json_decode($postdata);         
    $dato = $rec->tarjetaRec($recibo);    
    echo json_encode($dato);
}
if($funcion=='detallefol'){
    $rec = new Particulares();  
    $postdata = file_get_contents("php://input");  
    $folio = $_GET['folio'];     
    $datos = json_decode($postdata);         
    $dato = $rec->detaleFol($folio);    
    echo json_encode($dato);
}

if($funcion=='listadoCobros'){

    $listaCobro = new Particulares();  
    $postdata = file_get_contents("php://input");     
    $datos = json_decode($postdata);         
    $lista = $listaCobro->listadoCobro();    
    echo json_encode($lista);
}
if($funcion=='verDetApp'){
    $detapp = new Particulares();  
    $postdata = file_get_contents("php://input");  
    $recibo   = $_GET['folioRecibo'];   
    $serie    = $_GET['serie'];     
    $datos    = json_decode($postdata);         
    $dato     = $detapp->detalleApp($recibo,$serie);    
    echo json_encode($dato);
}
?>
