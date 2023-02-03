<?php
/************************************************************************************
*************************************************************************************
*******             guardar nota soap 
*******             by:  Erick
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/Soap.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];



/******************************  GUARDAR NOTA SOAP ************************/

if($funcion=='guardarSoap'){
	try {
		$uni = $_GET['unidad']; 
		$usr = $_GET['usr'];
		$fol = $_GET['fol'];
	    $postdata = file_get_contents("php://input");
	    $data = json_decode($postdata);

	    $insercionSOAP = new Soap();	    
	    $resultado = $insercionSOAP->guardarSoap($uni,$usr,$fol,$data);
	    echo json_encode($resultado);
		
	} catch (Exception $e) {
		echo $e;
	}

}

if($funcion=='guardarSoapRH'){
	try {
		$uni = $_GET['unidad']; 
		$usr = $_GET['usr'];
		$fol = $_GET['fol'];
	    $postdata = file_get_contents("php://input");
	    $data = json_decode($postdata);

	    $insercionSOAP = new Soap();	    
	    $resultado = $insercionSOAP->guardarSoapRH($uni,$usr,$fol,$data);
	    echo json_encode($resultado);
		
	} catch (Exception $e) {
		echo $e;
	}

}

if($funcion=='notasSOAP'){
	try {
		$fol = $_GET['fol'];	     
	    $verNotasSOAP = new Soap();	    
	    $resultado = $verNotasSOAP->verSoap($fol);
	    echo json_encode($resultado);
		
	} catch (Exception $e) {
		echo $e;
	}

}


/***************************************************************************/

?>
