<?php
/************************************************************************************
*************************************************************************************
*******             Recibe Datos de Beetquest para guardarlos en PU
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
$fol= $datos->fol;

$sql="SELECT Exp_folio FROM Expediente a INNER JOIN Compania b on a.Cia_clave=b.Cia_clave WHERE Exp_folio='$fol'";
$result = $conexion->query($sql);
$row = $result->fetch();

$respuesta= array('fol' => $row['Exp_folio']
    
);

echo json_encode($respuesta);
?>