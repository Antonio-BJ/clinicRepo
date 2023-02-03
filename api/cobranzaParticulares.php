<?php
/************************************************************************************
*************************************************************************************
*******             INFORME DE REHABILITACION
*******             by: SAMUEL RAMIREZ
* *****             by: ANA DC
*******
*************************************************************************************
*************************************************************************************/


include ('myClasses/CobranzaParticulares.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

$funcion=$_GET['funcion'];


/****************************** RECUPERA DATOS DE LOS RECIBOS ************************/
if($funcion=='buscaRecibo'){
    $postdata = file_get_contents("php://input");    
    $datos = json_decode($postdata);  
    //print_r($datos);

    $infRecibo = new CobranzaParticulares();
    $busqueda = $infRecibo->buscaRecibo($datos);    
    echo json_encode($busqueda);
}

if($funcion=='listadoCobros'){

    $listaCobro = new CobranzaParticulares();  
    $postdata = file_get_contents("php://input");     
    $datos = json_decode($postdata);  

    $uni = $_REQUEST['unidad'];    

    $lista = $listaCobro->listadoCobro($uni);    
    echo json_encode($lista);
}


if($funcion=='detalleRec'){

    $detalle = new CobranzaParticulares();  
    $postdata = file_get_contents("php://input");     
    $datos = json_decode($postdata);      

    $recibo = $_REQUEST['recibo'];

    $lista = $detalle->detalleRecibo($recibo);    
    echo json_encode($lista);
}

if($funcion=='buscacobro'){

    $detalle = new CobranzaParticulares();  
    $postdata = file_get_contents("php://input");     
    $datos = json_decode($postdata);   

    $uniclave = $_REQUEST['unidad'];   

    $lista = $detalle->buscacobro($datos, $uniclave);    
    echo json_encode($lista);
}
/*****************************************************************************************/

?>
