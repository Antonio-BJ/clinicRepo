<?php

include ('myClasses/Addendum.php');

// include ('myClasses/Suministros.php');

include ('myClasses/Incidencias.php');

include ('myClasses/NotaMedicaParticulares.php');

include ('myClasses/EdicionFolios.php');





set_time_limit(3600);

//sin limite me memoria 

ini_set('memory_limit', '-1');

//ocultar los errores

error_reporting(0);



date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria



$funcion=$_GET['funcion'];







if($funcion=='agregarAddendum'){



    $usuarioModel = new Addendum();

    $fol    =$_GET['fol'];

    $cuerpo = $_GET['cuerpo'];

    $usr    = $_GET['usr'];

    $tipDoc = $_GET['tipDoc'];

    $postdata = file_get_contents("php://input");    

    $data = json_decode($postdata);    

    $a_users = $usuarioModel->setAddendum($fol,$data,$usr);    

    echo $a_users;

}



if($funcion=='buscaSubs'){



    $usuarioModel = new Addendum();

    $fol    =$_GET['fol'];

    $numSub = $usuarioModel->getSubsecuencias($fol);    

    echo $numSub;

}



if($funcion=='enviaComentarioRH'){



    $usuarioModel = new Addendum();

    $fol    =$_GET['fol'];

    $cuerpo = $_GET['cuerpo'];

    $usr    = $_GET['usr'];

    $tipDoc	= $_GET['tipDoc'];

    $a_users = $usuarioModel->setAddendum($fol,$cuerpo,$usr,$tipDoc);    

    echo json_encode($a_users);

}



// if($funcion=='recargaMedSymio'){



//    $cargaSuministros= new Suministros();



//     $uni    =$_GET['uni'];

//     $suministro = $cargaSuministros->setSuministros($uni);         

//     if($suministro=='exito'){

//         $respuesta = $cargaSuministros->getSuministros($uni);

//         echo json_encode($respuesta);        

//     }else{

//         echo json_encode('error');   

//     }

// }



// if($funcion=='guardaMedicamentosAlternativos'){ 

//     $folio    =$_GET['fol'];      

//     $guardaMedicAlter = new Suministros();

    

//     $postdata = file_get_contents("php://input");    

//     $data = json_decode($postdata);    

//     $suministro = $guardaMedicAlter->setSumAlter($folio, $data);     

//     if($suministro=='exito'){

//        $listadoSumAlter = $guardaMedicAlter->getSuministrosAlternativos($folio);

//        echo json_encode($listadoSumAlter); 

//     }else{

//         echo 'error';

//     }

// }



// if($funcion=='guardaOrtesisAlternativos'){ 

//     $folio    =$_GET['fol'];      

//     $guardaOrteAlter = new Suministros();    

//     $postdata = file_get_contents("php://input");    

//     $data = json_decode($postdata);    

//     $suministro = $guardaOrteAlter->setOrtAlter($folio, $data);     

//     if($suministro=='exito'){

//        $listadoOrtAlter = $guardaOrteAlter->getOrtesisAlternativos($folio);

//        echo json_encode($listadoOrtAlter); 

//     }else{

//         echo 'error';

//     }

// }



/****************** funcion para guardar incidencias **********************************/



if($funcion=='guardaIncidencia'){ 

    $usr    =$_GET['usr'];      

    $uni    =$_GET['uni'];       

    $guardaIncidencia = new Incidencias();    

    $postdata = file_get_contents("php://input");    

    $data = json_decode($postdata);        

    $incidencia = $guardaIncidencia->setIncidencia($usr,$uni,$data);

    if($incidencia!='error'){

        $correoEnviado=$guardaIncidencia->sendIncidencia($usr,$uni,$data,$incidencia);

        echo $correoEnviado;

    }         

}

/*********************fin de guardado de insidencias ***********************************/

/****************** funcion para guardar incidencias **********************************/

if($funcion=='guardaIncidenciaAdjunto'){

try{ 

    $usr        =$_GET['usr'];      

    $uni        =$_GET['uni'];

    $tipo       =$_GET['tipo'];

    $sev        =$_GET['severidad']; 

    $obs        =$_GET['observaciones'];

    $arc        =$_GET['archivo'];

    $tem        =$_GET['temporal'];

    $accion     =$_GET['acciones'];

    $uniInc     =$_GET['uniIncidencia'];



    $archivo    =$_FILES['file'];   



    $guardaIncidencia = new Incidencias();                   

    $correoEnviado=$guardaIncidencia->enviaIncidenciaAdjunto($usr,$uni,$tipo,$sev,$obs,$arc,$tem,$archivo,$accion,$uniInc);

    echo $correoEnviado;        

}catch(Exception $e){

    echo $e->getMessage();

}

}

/*********************fin de guardado de insidencias ***********************************/

/*********************funcion para listar  **************************/

if($funcion=='getRecibos'){ 

    $fol    =$_GET['fol'];    

    $postdata = file_get_contents("php://input");    

    $data = json_decode($postdata);    

    $guardaPad = new NotaMedicaParticulares();

    $resultado = $guardaPad->getRecibos($fol);

    echo json_encode($resultado);         

}



/*****************************************************************************************/

    

/********************* actualiza factura  **************************/

if($funcion=='actualizaFactura'){ 

    $fol    =$_GET['fol'];

    $folFact=$_GET['folFact'];       

    $validaFact = new EdicionFolios();        

    $validacionFact = $validaFact->actualizaFolioFactura($fol,$folFact);

    echo $validacionFact;         

}



/*****************************************************************************************/



/********************* get lesion Dianóstico  **************************/

if($funcion=='lesionDiagnostico'){ 

    $opcion    =$_GET['opcion'];    

    $lesion = new EdicionFolios();        

    $listadoLesion = $lesion->listaLesion($opcion);

    echo json_encode($listadoLesion);         

}



/*****************************************************************************************/

/********************* get nombre lesion  **************************/

if($funcion=='nombreLesion'){ 

    $clave    =$_GET['claveLesion'];    

    $lesion = new EdicionFolios();        

    $nombre = $lesion->nombreLesion($clave);

    echo json_encode($nombre);         

}



/*****************************************************************************************/

/********************* get nombre lesion  **************************/

if($funcion=='nombresLesion'){ 

    $postdata = file_get_contents("php://input");    

    $data = json_decode($postdata);     

    $lesionOtra = new EdicionFolios();        

    $nombre = $lesionOtra->nombresLesion($data);

    echo $nombre;         

}



/*****************************************************************************************/



/*********************Get Compañía  **************************/

if($funcion=='getCia'){ 

    $fol    =$_GET['fol'];    

    $postdata = file_get_contents("php://input");    

    $data = json_decode($postdata);    

    $cia = new EdicionFolios();

    $resultado = $cia->getCia($fol);

    echo $resultado;         

}



/*****************************************************************************************/



/****************** funcion para guardar incidencias **********************************/



if($funcion=='pruebaCorreo'){ 

    $correo = new Incidencias();    

    

    $incidencia = $correo->mandaCorreo();

             

}

/*********************fin de guardado de insidencias ***********************************/

/********************* Incidencia de inventarios  **************************/

if($funcion=='incidenciaInventario'){ 

    $fol    =$_GET['fol']; 

    $uni    =$_GET['uni'];

    $usr    =$_GET['usr'];    

    $enviaIncidenciaInventario = new Incidencias();               

    $resultado = $enviaIncidenciaInventario->mandaIncidenciaInvent($fol,$uni,$usr);

    echo $resultado;

}



/*****************************************************************************************/

/********************* Incidencia de inventarios  **************************/

if($funcion=='unidadSubsecuencia'){ 

      

    $enviaIncidenciaInventario = new Incidencias();               

    $resultado = $enviaIncidenciaInventario->UniSubsecuencia();

}



/*****************************************************************************************/

?>

