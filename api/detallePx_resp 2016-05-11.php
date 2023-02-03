<?php
/************************************************************************************
*************************************************************************************
*******             Detalle del todo el historial del paciente
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/DetallePx.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];

/******************************  nombre y fecha de registro de PX ************************/
if($funcion=='getDatosPersonales'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $dPersonales = $datosPersonales->getDatosPersonales($fol);    
    echo json_encode($dPersonales);
}
/*****************************************************************************************/
/************************** Si Existen registro de signos vitales ************************/
if($funcion=='getSignosVitales'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $signosVitales = $datosPersonales->getSignosVitales($fol);    
    echo json_encode($signosVitales);
}
/*****************************************************************************************/
/************************** Si Existen registro de historial clinica ************************/
if($funcion=='getHistoriaClinica'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $histClinica = $datosPersonales->getHistoriaClinica($fol);    
    echo json_encode($histClinica);
}
/*****************************************************************************************/
/************************** Si Existen registro de historial clinica ************************/
if($funcion=='getNotaMedica'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $NotMed = $datosPersonales->getNotaMedica($fol);    
    echo json_encode($NotMed);
}
/*****************************************************************************************/
/************************** Si Existen susbsecuencias dadas de alta***********************/
if($funcion=='getSubsecuencias'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $subs = $datosPersonales->getsubsecuencias($fol);    
    echo json_encode($subs);
}
/*****************************************************************************************/
/************************** Si Existen rehabilitaciones dadas de alta***********************/
if($funcion=='getRehabilitaciones'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $rehab = $datosPersonales->getRehabilitaciones($fol);    
    echo json_encode($rehab);
}
/*****************************************************************************************/
/************************** Si Existen documentos digitalizados ***********************/
if($funcion=='getAviso'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docAviso = $datosPersonales->getAviso($fol);    
    echo json_encode($docAviso);
}

if($funcion=='getConsMed'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docConsMed = $datosPersonales->getConsMed($fol);    
    echo json_encode($docConsMed);
}
if($funcion=='getCuestionario'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docCuestionario = $datosPersonales->getCuestionario($fol);    
    echo json_encode($docCuestionario);
}
if($funcion=='getFiniquito'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docFiniquito = $datosPersonales->getFiniquito($fol);    
    echo json_encode($docFiniquito);
}
if($funcion=='getHistoria'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docHistoria = $datosPersonales->getHistoria($fol);    
    echo json_encode($docHistoria);
}
if($funcion=='getIdentificacion'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docIdentificacion = $datosPersonales->getIdentificacion($fol);    
    echo json_encode($docIdentificacion);
}
if($funcion=='getInfMedico'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docInfMedico = $datosPersonales->getInfMedico($fol);    
    echo json_encode($docInfMedico);
}
if($funcion=='getInfAseg'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docInfAse = $datosPersonales->getInfAseg($fol);    
    echo json_encode($docInfAse);
}
if($funcion=='getPaseMedico'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $docPase = $datosPersonales->getPaseMedico($fol);    
    echo json_encode($docPase);
}
if($funcion=='getAutorizacion'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $Autorizacion = $datosPersonales->getAutorizacion($fol);    
    echo json_encode($Autorizacion);
}
if($funcion=='getDiagnostico'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];
    $Autorizacion = $datosPersonales->getDiagnostico($fol);    
    echo json_encode($Autorizacion);
}

if($funcion=='getModificador'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];    
    $modificador = $datosPersonales->getModificador($fol);    
    echo $modificador;
}
/*****************************************************************************************/
?>
