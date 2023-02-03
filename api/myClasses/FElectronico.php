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

'22MA00357946',
'22MD00437824',
'22MA00728909',
'22MI00749274',
'22ME00769583',
'22MF00769593',
'22MD00771967',
'22MG00770575',
'22MH00770567',
'22MD00756109',
'22MF00778863',
'22MF00784164',
'22ME00786332',
'22MF00788016',
'22MC00788202',
'22MC00788193',
'22MH00788540',
'22MA00788525',
'22MF00788529',
'22MC00789381',
'22MA00788534',
'22MH00790016',
'22MH00791150',
'22MG00791149',
'22MG00302512',
'22MH00340313',
'22MH00591584',
'22MA00625633',
'22MF00646806',
'22MC00686592',
'22MC00689085',
'22MG00694840',
'22ME00733313',
'22MF00479838',
'22MI00741930',
'22MA00761182',
'22MD00762247',
'22MD00761554',
'22MD00762643',
'22MI00763098',
'22MH00762377',
'22MG00762844',
'22MF00792426',
'22MA00790774',
'22ME00785315',
'22MI00722490',
'22MB00783980',
'22MG00789781',
'22ME00791237',
'22ME00791255',
'22MA00791252',
'22ME00793109',
'22MA00794069',
'22MD00794566',
'22MG00795010',
'22MH00795839',
'22ME00795332',
'22MA00796633',
'22MA00797003',
'22MA00796021',
'22MI00794868',
'22MI00789261',
'22MC00794106',
'22MH00794102',
'22MG00794542',
'22MA00796868',
'22MA00791881',
'22MD00795493',
'22MC00795501',
'22MA00795517',
'22MD00796609',
'22MD00795529',
'22MA00798615',
'22MA00798416',
'22MA00798514',
'22MA00777429',
'22MB00780002',
'22MH00784769',
'22MD00799930',
'22MI00798144',
'22MI00798135',
'22MA00799244',
'22MH00798782',
'22MF00798771',
'22MI00797622',
'22MF00799356',
'22MA00799362',
'22ME00707834',
'22MD00800263',
'22MF00801534',
'22MF00793434',
'22MH00797675',
'22MH00799448',
'22MB00803015',
'22MC00801684',
'22ME00804602',
'22MC00804600',
'22MD00804223',
'22MG00803308',
'22MF00803703',
'22MD00799273',
'22MD00803935',
'22MB00803042',
'22MD00797896',
'22MA00803249',
'22MG00803245',
'22MI00803247',
'22MH00803633',
'22MG00803632'
);

    echo '<table>';
    echo '<tr><th>Folio Electro</th><th>Estatus</th><th>Carta</th><th>Estatus</th></tr>';

    foreach($arrayFolio as $fol){

        $folio = '';

        $folio = $fol;
        ini_set('default_socket_timeout', 5000);

	echo $xml='
    <gmqXmlRequest>
    <xmlRequest pid="1" token="ad8787769969818cc9f4f9a656298f58">
                <folio_ele>'.$fol.'</folio_ele>
    </xmlRequest>
</gmqXmlRequest>';

    // $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio
    $servicio="https://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL";
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
        echo $miArray['FOLIO'];
        if($miArray['FOLIO']){
            // echo $miArray['FOLIO_AUTORIZACION'];
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