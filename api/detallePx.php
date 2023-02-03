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

/******************************  nombre y fecha de registro de PX ************************/

if($funcion=='getDatosPersonalesMovil'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];

    $dPersonales = $datosPersonales->getDatosPersonalesMovil($fol);    

    echo json_encode($dPersonales);

}

/*****************************************************************************************/

/****************************** OBTIENE EL MEDICO QUE ATENDIÓ AL PACIENTE ************************/

if($funcion=='getMedico'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];

    $dPersonales = $datosPersonales->getMedico($fol);    

    echo json_encode($dPersonales);

}

/*****************************************************************************************/

/****************************** OBTIENE EL ULTIMO DIAGNOSTICO ************************/

if($funcion=='getDiagActual'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];

    $dPersonales = $datosPersonales->getDiagActual($fol);    

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



if($funcion=='registraAutoSubs'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol']; 

    $uni    =$_GET['uni'];   

    $usr    =$_GET['usr'];

    $con    =$_GET['cont'];

    $modificador = $datosPersonales->registraAutoSubs($fol,$uni,$usr,$con);    

    echo $modificador;

}

if($funcion=='infoFolio'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];  

    $modificador = $datosPersonales->infoFolio($fol);    

    echo json_encode($modificador);

}

if($funcion=='verVigenciaFolio'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];  

    $usr    =$_GET['usr'];

    $modificador = $datosPersonales->vigenciaFolio($fol,$usr);    

    echo json_encode($modificador);

}

if($funcion=='getPerfil'){   

    $datosPersonales = new DetallePx();

    $usr    =$_GET['usr'];  

    $modificador = $datosPersonales->checaPerfil($usr);    

    echo $modificador;

}



if($funcion=='mandarCorreoextemporaneo'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];  

    $dias    =$_GET['dias'];

    $tipo   =$_GET['tipo'];

    $usr    =$_GET['usr'];

    $modificador = $datosPersonales->mandarCorreoextemporaneo($fol,$dias,$tipo,$usr);    

    echo json_encode($modificador);

}



if($funcion=='getComentarios'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];  

    $modificador = $datosPersonales->checaComentarios($fol);    

    echo json_encode($modificador);

}

if($funcion=='setComentario'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];  

    $comen = $_GET['comentarios'];

    $usr = $_GET['usr'];

    $modificador = $datosPersonales->guardaComentario($fol,$comen,$usr);    

    echo json_encode($modificador);

}



if($funcion=='catItmesCarrito'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];     

    $modificador = $datosPersonales->catItmesCarrito($fol);    

    echo json_encode($modificador);

}

if($funcion=='agregaItem'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];    

    $item   =$_GET['cveItem']; 

    $usr    =$_GET['usr'];

    $modificador = $datosPersonales->agregaItem($fol,$item,$usr);    

    echo json_encode($modificador);

}

if($funcion=='getCarrito'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];    

    $modificador = $datosPersonales->getCarritFolio($fol);    

    echo $modificador;

}

if($funcion=='cerrarRecibo'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];    

    $modificador = $datosPersonales->closeRecibo($fol);    

    echo $modificador;

}

if($funcion=='eliminarItemCarrito'){   

    $datosPersonales = new DetallePx();

    $fol    =$_GET['fol'];

    $idItem =$_GET['idItem'];    

    $modificador = $datosPersonales->eliminarItemCarrito($fol,$idItem);    

    echo json_encode($modificador);

}

if($funcion=='getDocumentos'){   
    $datosPersonales = new DetallePx();
    $fol    =$_GET['fol'];    
    $modificador = $datosPersonales->getDocumentos($fol);    
    echo json_encode($modificador);
}

/*****************************************************************************************/

?>

