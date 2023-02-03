<?php
/************************************************************************************
*************************************************************************************
*******             VENTAS SIN REGISTRO 
*******             Marzo 2016 / 
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/ventasSinRegistro.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];


/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='getValuesItem'){
	$cveItem = $_GET['cveItem'];
	$datos = json_decode($postdata);      
    $convenio = new VentasSinRegistro();
    $infoItem = $convenio->getItem($cveItem);    
    echo json_encode($infoItem);
}
/***************************************************************************************************/

/******************************  GUARDAR venta sin registro ************************/

if($funcion=='guardarNuevo'){
	try {
		$nombre 		=	$GET['nombre'];
		$apellidoPaterno=	$GET['apellidoPaterno'];
      	$apellidoMaterno=	$GET['apellidoMaterno'];
      	$email 			= 	$GET['email'];
      	$telefono 		=	$GET['telefono'];
      	$codigoPostal 	= 	$GET['codigoPostal'];
      	$enterado 		= 	$GET['enterado'];
      	$usuarioRegistro= 	$GET['usuarioRegistro'];
      	$folio			=	$GET['folio'];
      	$fechaRegistro 	=	$GET['fechaRegistro'];
      	$horaRegistro 	=	$GET['horaRegistro'];
	    $fam          	=   $GET['fam'];
	    $item         	=   $GET['item'];
	    $descuento    	=   $GET['descuento'];
	    $precio       	=   $GET['precio'];
	    $total       	=   $GET['total'];
	    $fPago        	=   $GET['fPago'];
	    $fec          	=   $GET['fec'];
	    $noRec        	=   $GET['noRec'];
	    $medico       	=   $GET['medico'];
	    $banco        	=   $GET['banco'];
	    $terminacion  	=   $GET['terminacion'];
	    $cuponDescuento          =   $GET['cuponDescuento'];
	    
	    $postdata = file_get_contents("php://input");
	    $data = json_decode($postdata);
	    $ventasSinRegistro = new VentasSinRegistro();	    
	    $resultado = $ventasSinRegistro->Nuevo($data);

		 echo json_encode($resultado);
	} catch (Exception $e) {
		$respuesta = array('respuesta' =>'error');       
		$respuesta=$e->getMessage();
		echo $e;
	}

}
/***************************************************************************/
/******************************  verificar si tiene agregada una solicitud ************************/
if($funcion=='verificaMem'){
	$postdata = file_get_contents("php://input");
	$datos = json_decode($postdata); 	
    $membresia = new VentasSinRegistro();
    $existe = $membresia->getMembresia($datos->mem);    
    echo json_encode($existe);
}
/***************************************************************************************************/
?>
