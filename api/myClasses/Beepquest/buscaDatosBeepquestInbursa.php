<?php
/************************************************************************************
*************************************************************************************
*******             Busca datos de paciente para cuestionario de beepquest
*******             by:  Anny
*******
*************************************************************************************
*************************************************************************************/

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria

function conectarMySQL(){
    $dbhost="medicavial.net";
    $dbuser="medica_webusr";
    $dbpass="tosnav50";
    $dbname="medica_registromv";
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    date_default_timezone_set('America/Mexico_City');
    return $conn;
}

$conexion= conectarMySQL();
$postdata= file_get_contents("php://input");
$datos= json_decode($postdata);
$folio= $datos->folio;

$sql="SELECT Cia_nombrecorto, Exp_completo, Exp_siniestro, Exp_poliza, Exp_reporte FROM Expediente a INNER JOIN Compania b on a.Cia_clave=b.Cia_clave WHERE Exp_folio='$folio' and a.Cia_clave = 45 ";
$result = $conexion->query($sql);
$row = $result->fetch();

if($result){

    $respuesta= array('cia' => $row['Cia_nombrecorto'],
        'paciente' => $row['Exp_completo'],
        'siniestro' => $row['Exp_siniestro'],
        'poliza' => $row['Exp_poliza'],
        'reporte' => $row['Exp_reporte']
    );

}else{

    $respuesta=array('mensaje'=>'No existe coincidencia del folio con la aseguradora');
}


echo json_encode($respuesta);

?>