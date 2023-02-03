<?php

// error_reporting(E_ALL);
// ini_set('display_errors', true);
// ini_set('display_startup_errors', true);


    // Conexion al web service
    // 
    // 
    error_reporting(0);
    $hoy  = date("Y-m-d");
    $dia  = date("d");
    $mes  = date("m");
    $anio = date("Y");

    $dia = 01;
    $mes  = 10;


    $fecha = $anio."-".$mes."-".$dia;


    ini_set('default_socket_timeout', 5000);

	$xml='
         <gmqXmlRequest>
                <xmlRequest pid="1" token="ad8787769969818cc9f4f9a656298f58">
                                           <fecha_reporte>2020-06-27</fecha_reporte>
                            </xmlRequest>
            </gmqXmlRequest>
      ';

    $servicio="https://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL"; //url del servicio
	$client = new SoapClient($servicio);

    $parameters=array('parameters'=>array('xml_consulta'=> $xml));

    $result = $client->__call("consultaFolios" ,$parameters);//llamamos al métdo que nos interesa con los parámetros
	
    //echo var_dump($result);
    $xml_result= $result->consultaFoliosResult;
    // echo "<pre>";
    // echo htmlentities($xml_result);
    // echo "</pre>";
    

	$result = xml2array($xml_result);



    // print_r($result);
	
	$respuesta = array();

    // print_r($result);
    $db = conectarMySQL();
    $contadorTodos = 0;
	
	foreach ($result as $key => $value) {
        $miArray =  array(); 
        if(count($value)>0){       
    		foreach ($value as $llave => $valor) {
    			
    			if($valor=='REGISTRO'){

    			}else{
    				
                    if($valor['tag']){
                        echo $valor['tag'].'-----'.$valor['value'].'<br>';
                        $miArray[$valor['tag']]=$valor['value'];
                    }
    			}
    			// if($valor[''])			
    		}
        }
        // print_r($miArray);
        if($miArray){
            $query="SELECT count(*) as contador FROM QualitasWS_resp WHERE QWS_folioElectronico='".$miArray['FOLIO']."'";
            $result = $db->query($query);
            $datos = $result->fetch();
        }
        if($datos['contador']>0){
            $queryUpdate = "UPDATE QualitasWS_resp SET QWS_estatus=".$miArray['STATUS'].", QWS_folioAutorizacion='".$miArray['FOLIO_AUTORIZACION']."', QWS_fechaActualizacion=now(),Exp_folio='".$miArray['FOLIO_PROVEEDOR']."',QWS_riesgo='".$miArray['COBERTURA']."', QWS_observaciones='".$miArray['OBSERVACIONES']."', QWS_lesionado='".$miArray['LESIONADO']."' WHERE QWS_folioElectronico='".$miArray['FOLIO']."'";
             $result = $db->query($queryUpdate);
             $contadorTodos++;

        }else{    
            if($miArray['FOLIO']!=''){        
                $sql = "INSERT INTO QualitasWS_resp(QWS_folioElectronico,QWS_poliza,QWS_siniestro,QWS_reporte,QWS_fechaReporte,QWS_cveProveedor,QWS_lesionado,QWS_estatus,QWS_observaciones,QWS_fechaRegistro,QWS_folioAutorizacion,QWS_fechaActualizacion,Exp_folio,QWS_riesgo) VALUES('".$miArray['FOLIO']."','".$miArray['POLIZA']."','".$miArray['SINIESTRO']."','".$miArray['REPORTE']."','".$miArray['FECHA_REPORTE']."','".$miArray['CVE_PROVEEDOR']."','".$miArray['LESIONADO']."','".$miArray['STATUS']."','".$miArray['OBSERVACIONES']."',now(),'".$miArray['FOLIO_AUTORIZACION']."',now(),'".$miArray['FOLIO_PROVEEDOR']."','".$miArray['COBERTURA']."')";
                $result = $db->query($sql);
                $contadorTodos++;
            }
        }

	}

    echo 'El conteo de todos los folios es: '.$contadorTodos;


function obj2array($obj) {
  $out = array();
  foreach ($obj as $key => $val) {
    switch(true) {
        case is_object($val):
         $out[$key] = obj2array($val);
         break;
      case is_array($val):
         $out[$key] = obj2array($val);
         break;
      default:
        $out[$key] = $val;
    }
  }
  return $out;
}


function xml2array($xml){ 
    $opened = array(); 
    $opened[1] = 0; 
    $xml_parser = xml_parser_create(); 
    xml_parse_into_struct($xml_parser, $xml, $xmlarray); 
    $array = array_shift($xmlarray); 
    unset($array["level"]); 
    unset($array["type"]); 
    $arrsize = sizeof($xmlarray); 
    for($j=0;$j<$arrsize;$j++){ 
        $val = $xmlarray[$j]; 
        switch($val["type"]){ 
            case "open": 
                $opened[$val["level"]]=0; 
            case "complete": 
                $index = ""; 
                for($i = 1; $i < ($val["level"]); $i++) 
                    $index .= "[" . $opened[$i] . "]"; 
                $path = explode('][', substr($index, 1, -1)); 
                $value = &$array; 
                foreach($path as $segment) 
                    $value = &$value[$segment]; 
                $value = $val; 
                unset($value["level"]); 
                unset($value["type"]); 
                if($val["type"] == "complete") 
                    $opened[$val["level"]-1]++; 
            break; 
            case "close": 
                $opened[$val["level"]-1]++; 
                unset($opened[$val["level"]]); 
            break; 
        } 
    } 
    return $array; 
} 

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