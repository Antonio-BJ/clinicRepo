<?php

/************************************************************************************

*************************************************************************************

*******             control de flujo de pacientes 

*******             by:  Erick

*******

*************************************************************************************

*************************************************************************************/





include ('myClasses/Digitales.php');



set_time_limit(3600);

//sin limite me memoria 

ini_set('memory_limit', '-1');

//ocultar los errores

error_reporting(0);



date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria



$funcion=$_GET['funcion'];



/******************************  verificar si tiene agregada una solicitud ************************/

if($funcion=='guardaRx'){    

    $postdata = file_get_contents("php://input");

    $datos = json_decode($postdata);        

    $fol 		= $_GET['fol'];

    $noPlac 	= $_GET['noPlac'];

    $usr 		= $_GET['usr'];

    $inter      = $_GET['inter'];    

    $archivo    = $_FILES['file'];    

    $rayosX 	= new Digitales(); 



    $respRx=$rayosX->guardaRx($fol,$noPlac,$usr,$inter,$archivo);

    echo json_encode($respRx);    

}

/***************************************************************************************************/

/******************************  verificar si tiene agregada una solicitud ************************/

if($funcion=='guardaFoto'){    

    $postdata = file_get_contents("php://input");

    $datos = json_decode($postdata);        

    $fol 		= $_GET['fol'];

    $noPlac 	= $_GET['noPlac'];

    $usr 		= $_GET['usr'];

    $inter      = $_GET['inter'];    

    $archivo    = $_FILES['file'];    

    $rayosX 	= new Digitales(); 



    $respRx=$rayosX->guardaFoto($fol,$noPlac,$usr,$inter,$archivo);

    echo json_encode($respRx);    

}

/***************************************************************************************************/

/******************************  verificar si tiene agregada una solicitud ************************/

if($funcion=='guardaRxSol'){    

    $postdata = file_get_contents("php://input");

    $datos = json_decode($postdata);        

    $fol        = $_GET['fol'];

    $noPlac     = $_GET['noPlac'];

    $usr        = $_GET['usr'];

    $inter      = $_GET['inter'];

    $rxCve      = $_GET['rxCve'];    

    $archivo    = $_FILES['file'];    

    $rayosX     = new Digitales(); 



    $respRx=$rayosX->guardaRxSol($fol,$noPlac,$usr,$inter,$rxCve,$archivo);

    echo json_encode($respRx);    

}

/***************************************************************************************************/

/******************************listado RX digitalizados************************/

if($funcion=='listadoRX'){        

    $fol=$_GET['fol'];    

    $rayosX     = new Digitales();           

    $respRx=$rayosX->listadoRX($fol);

    echo json_encode($respRx);    

}

/***************************************************************************************************/

/******************************  verificar si tiene agregada una solicitud ************************/

if($funcion=='guardaRhZima'){

    $postdata = file_get_contents("php://input");

    $datos = json_decode($postdata);

    $fol        = $_GET['fol'];

    $usr        = $_GET['usr'];

    $inter      = $_GET['inter'];

    $archivo    = $_FILES['file'];

    $rayosX     = new Digitales();



    $respRx=$rayosX->guardaRhZima($fol,$usr,$inter,$archivo);

    echo json_encode($respRx);

}

/******************************listado RX digitalizados************************/

if($funcion=='datosPaciente'){        

    $fol=$_GET['fol'];    
    $rayosX     = new Digitales();           

    $respRx=$rayosX->datosPaciente($fol);

    echo json_encode($respRx);    

}

if($funcion=='enviarRxCorreo'){        
  
    $fol=$_GET['fol']; 
    
    $correo = $_GET['correo'];
    $correon = $_GET['correon'];  
    $rayosX     = new Digitales();           

    $respRx=$rayosX->enviarRxCorreo($fol,$correo, $correon);

 $respRx;    

}

?>

