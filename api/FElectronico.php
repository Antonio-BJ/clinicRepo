<?php

// error_reporting(E_ALL);
// ini_set('display_errors', true);
// ini_set('display_startup_errors', true);


    // Conexion al web service
    // 
    // 
    // error_reporting(0);
    $hoy  = date("Y-m-d");
    $dia  = date("d");
    $mes  = date("m");
    $anio = date("Y");

    $dia = 01;
    $mes  = 10;


    $fecha = $anio."-".$mes."-".$dia;
    $arrayFolio = array(
        '21MG00010823',
'21MA00108908',
'21MD00108434',
'21MI00108412',
'21MA00051344',
'21MB00110349',
'21MF00108418',
'21MC00057349',
'21MH00108438',
'21ME00108444',
'21MA00063756',
'21ME00063750',
'21MA00109097',
'21MB00110799',
'21MG00108554',
'21MD00101765',
'21MA00101690',
'21MH00102678',
'21ME00101694',
'21MF00101821',
'21MC00101818',
'21MB00101817',
'21ME00101820',
'21MD00101819',
'21MH00108771',
'21MA00108773',
'21MG00105818',
'21MB00105822',
'21MI00089467',
'21MB00105318',
'20MF00562880',
'21MC00106732',
'21MD00106598',
'21MF00106735',
'21MD00105842',
'21ME00105843',
'21MF00105844',
'21MI00103750',
'21MI00106855',
'21MF00107554',
'21MD00107660',
'21MC00107641',
'21MG00107636',
'19MA00380639',
'21ME00107607',
'21MG00107906',
'21MA00107774',
'21MH00106161',
'21MI00107908',
'21MH00104343',
'21MI00108466',
'21MH00107736',
'21MA00107730',
'21MC00108847',
'21MF00108472',
'21MC00108982',
'21MA00109800',
'21MC00109801',
'21MA00109799',
'21MF00109966',
'21MC00110341',
'21MF00110236',
'21MF00111091',
'21ME00109722',
'21MI00111562',
'21MH00111903',
'21MH00112200',
'21MH00112236',
'21MB00112239',
'21ME00112197',
'21MF00112009',
'21MA00112139',
'21MI00109537',
'21MB00110538',
'21MD00110549',
'21MB00112473',
'21MG00112793',
'21MD00112610',
'21MD00112241',
'21MB00113202',
'21MH00112362',
'21MF00113035',
'21MH00112866',
'21MC00113032',
'21MF00113647',
'21MC00112870',
'21MI00112309',
'21MC00113419',
'21MH00113748',
'21ME00113754',
'21MF00113917',
'21MD00114167',
'21MI00114235',
'21MG00114350',
'21MC00114364',
'21MB00114363',
'21MI00114370',
'21MB00113283',
'21MC00102565',
'21MA00113859',
'21MF00102550',
'21MD00114806',
'21MA00114812',
'21MD00114698',
'21MH00109176',
'21MA00114948',
'21MB00114822',
'21MG00114773',
'21MD00114770',
'21MH00113820'     
                            
    );

    echo '<table>';
    echo '<tr><th>Folio Electro</th><th>Estatus</th><th>Carta</th><th>Estatus</th></tr>';

    foreach($arrayFolio as $fol){

        $folio = '';

        $folio = $fol;
        ini_set('default_socket_timeout', 5000);

	$xml='
    <gmqXmlRequest>
    <xmlRequest pid="1" token="ad8787769969818cc9f4f9a656298f58">
                <folio_ele>'.$fol.'</folio_ele>
    </xmlRequest>
</gmqXmlRequest>

      ';

    $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio
	$client = new SoapClient($servicio);

    $parameters=array('parameters'=>array('xml_consulta'=> $xml));

    $result = $client->__call("datosFolio" ,$parameters);//llamamos al métdo que nos interesa con los parámetros
	
    //echo var_dump($result);
    $xml_result= $result->datosFolioResult;
    // echo "<pre>";
    // echo htmlentities($xml_result);
    // echo "</pre>";
    

	$result = xml2array($xml_result);


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
                        // echo $valor['tag'].'-----'.$valor['value'].'<br>';
                        $miArray[$valor['tag']]=$valor['value'];
                    }
    			}
    			// if($valor[''])			
    		}
        }
  
        // if($miArray){

        //     $query="SELECT count(*) as contador FROM QualitasWS_resp WHERE QWS_folioElectronico='".$miArray['FOLIO']."'";
        //     $result = $db->query($query);
        //     $datos = $result->fetch();
        // }
        if($miArray['FOLIO']){
            if($datos['contador']>0){
                
                $queryUpdate = "UPDATE QualitasWS_resp SET QWS_estatus=".$miArray['STATUS'].", QWS_folioAutorizacion='".$miArray['FOLIO_AUTORIZACION']."', QWS_fechaActualizacion=now(),Exp_folio='".$miArray['FOLIO_PROVEEDOR']."',QWS_riesgo='".$miArray['COBERTURA']."', QWS_observaciones='".$miArray['OBSERVACIONES']."', QWS_lesionado='".$miArray['LESIONADO']."' WHERE QWS_folioElectronico='".$miArray['FOLIO']."'";
                $result = $db->query($queryUpdate);
                $contadorTodos++;

                echo '<tr><td>'.$folio.'</td><td>Actualizado</td><td>'.$miArray['FOLIO_AUTORIZACION'].'</td><td>'.$miArray['STATUS'].'</td></tr>';

            }else{    
                if($miArray['FOLIO']!=''){        
                    $sql = "INSERT INTO QualitasWS_resp(QWS_folioElectronico,QWS_poliza,QWS_siniestro,QWS_reporte,QWS_fechaReporte,QWS_cveProveedor,QWS_lesionado,QWS_estatus,QWS_observaciones,QWS_fechaRegistro,QWS_folioAutorizacion,QWS_fechaActualizacion,Exp_folio,QWS_riesgo) VALUES('".$miArray['FOLIO']."','".$miArray['POLIZA']."','".$miArray['SINIESTRO']."','".$miArray['REPORTE']."','".$miArray['FECHA_REPORTE']."','".$miArray['CVE_PROVEEDOR']."','".$miArray['LESIONADO']."','".$miArray['STATUS']."','".$miArray['OBSERVACIONES']."',now(),'".$miArray['FOLIO_AUTORIZACION']."',now(),'".$miArray['FOLIO_PROVEEDOR']."','".$miArray['COBERTURA']."')";
                    $result = $db->query($sql);
                    $contadorTodos++;
                    echo '<tr><td>'.$folio.'</td><td>Insertado</td><td>'.$miArray['FOLIO_AUTORIZACION'].'</td><td>'.$miArray['STATUS'].'</td></tr>';
                }
            }
        }elseif($miArray['CODIGOERROR']){
            echo '<tr><td>'.$folio.'</td><td>Error</td><td>'.$miArray['CODIGOERROR'].'</td><td>-</td></tr>';
        }

    }
    }
    echo '</table>';



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