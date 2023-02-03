<?php 

error_reporting(0);

class Procesos
    {
        
        private $server;
        private $usuario;
        private $password;
        private $mode;
        private $conexion;
        private $messageArray = array();
        private $loginOk = false;
        function __construct()
        {
            
        }        
         public function conectarMySQL(){

            $this->dbhost="medicavial.net";
            $this->dbuser="medica_webusr";
            $this->dbpass="tosnav50";
            $this->dbname="medica_registromv";
            $this->conn = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            date_default_timezone_set('America/Mexico_City');
            return $this->conn;
        }

        public function xml2array($xml){ 
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
 }

$proc = new Procesos();
$db = $proc->conectarMySQL();

$query = " SELECT Exp_folio, Exp_completo, Exp_fecreg, Exp_RegCompania, Pro_clave, Expediente.Uni_clave, LOC_claveint, Uni_propia, Exp_sexo, Exp_edad, Uni_claveQ  FROM Expediente 
            INNER JOIN Unidad on Expediente.Uni_clave = Unidad.Uni_clave
            where Exp_fecreg>='2019-01-25' and Exp_cancelado<>1
          and Cia_clave=19 and Exp_folio in ('CEMS022243',
'CEMS022245',
'MZMA008456',
'MZMA008457',
'OXME000601',
'MZMA008458',
'CHSS000194',
'MZMA008459',
'OXME000602',
'OXME000603',
'MZMA008460',
'IXHS001005',
'GDSG003149',
'GDSG003150',
'MZMA008461',
'MZMA008462',
'MZMA008463',
'ENRL001971',
'ENRL001973',
'CUBC001716',
'ENRL001974',
'ENRL001975',
'ENRL001976',
'MZMA008464',
'ENRL001977',
'ENRL001978',
'OXME000604',
'OXME000605',
'ENRL001979',
'ENRL001980',
'ENRL001982',
'SACO002283',
'CEMS022254',
'SACO002284',
'MZMA008465',
'TOUC004827',
'MZMA008466',
'TOUC004828',
'MZMA008467',
'UMEG014037',
'JOCE001803',
'TESA002081',
'MZMA008468',
'GDSG003151',
'MZMA008469',
'MZMA008470',
'MZMA008471',
'MZMA008472',
'JOCE001804',
'GDSG003152',
'GDSG003153',
'FREN009048',
'TOUC004829',
'MESA004052',
'TOUC004830',
'TOUC004831',
'TOUC004832',
'MESA004053',
'MESA004054',
'OXME000607',
'CEMS022255',
'CHSS000195',
'CHSS000196',
'CHSS000197',
'CHSS000198')";
$res = $db->query($query);
$listado = $res->fetchAll();



$query1 = "select * from QualitasWS_resp WHERE QWS_fechaRegistro>='2018-12-01'";

$res1 = $db->query($query1);
$listado1 = $res1->fetchAll();


echo "<table>";
foreach ($listado as $key => $value) {	
	$percent=0;
	foreach ($listado1 as $key1 => $value1) {		
		similar_text($value['Exp_completo'], $value1['QWS_lesionado'], $percent); 	
		if($percent>=83){

		 	$fol            = $value['Exp_folio'];
	        $nombre         = $value['Exp_completo'];
	        $sexo           = $value['Exp_sexo'];
	        $edad           = $value['Exp_edad'];
	        $unidad         = $value['Uni_clave'];
	        $fechaRegistro  = $value['Exp_fecreg'];
	        $producto       = $value['Pro_clave'];
	        $localidad      = $value['LOC_claveint'];
	        $unidadPropia   = $value['Uni_propia'];
	        $cveProv        = $value['Uni_claveQ'];

	        $poliza         = $value1['QWS_poliza'];
	        $siniestro      = $value1['QWS_siniestro'];
	        $reporte        = $value1['QWS_reporte'];
	        $folElec        = $value1['QWS_folioElectronico'];
	        $cveProv        = $value1['QWS_cveProveedor'];



        /************************************** calculo de importe  ********************************************************/


            $diagnostico='T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS';
           
            $fingreso=$fechaRegistro;
            $fegreso=$fechaRegistro;
            $triage='AMBULATORIO CON PAQUETE';
            $clave = 0;

            if ($producto == 1){
                //mv puebla
                if ($unidad == 4) {                    
                    if(date($fechaRegistro) >= date('2016-12-05 00:00:00')){
                        $clave = 28;
                    }else{
                        $clave = 7;
                    }
                //mv monterrey
                }elseif (($unidad == 76 || $unidad == 113 || $unidad == 183)&& date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tlaxcala                    
                    $clave = 122;
                //Sasc Ixtapaluca y es mayor a 1 de noviembre 2016
                }elseif ($unidad == 5) {                    
                    $clave = 28;
                //Sasc Ixtapaluca y es mayor a 1 de noviembre 2016
                }elseif ( ($unidad == 110 || $unidad == 266 || $unidad == 65 || $unidad == 281) && date($fechaRegistro) >= date('2016-11-01 00:00:00')) {
                    $clave = 108; //sames
                }elseif ($localidad == 167  && date($fechaRegistro) >= date('2017-10-26 00:00:00')) { //guadalajara zm
                    $clave = 40;
                }elseif ($localidad == 53  && date($fechaRegistro) >= date('2017-05-08 00:00:00')) { //villahermosa
                    $clave = 11;
                }elseif ($localidad == 103  && date($fechaRegistro) >= date('2017-06-05 00:00:00')) { //cordoba
                    $clave = 116;
                }elseif ($localidad == 121  && date($fechaRegistro) >= date('2016-12-05 00:00:00')) { //iguala
                    $clave = 116;
                }elseif ($localidad == 155  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //lagos de moreno
                    $clave = 122;
                }elseif ($localidad == 128  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //atotonilco
                    $clave = 122;
                }elseif ($localidad == 19  ) { //durango
                    if(date($fechaRegistro) >= date('2019-03-03 00:00:00')){
                        $clave = 146;
                    }else{
                        $clave = 122;
                    }                      
                }elseif ($localidad == 149  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //mazatlan
                    $clave = 122;
                }elseif ($localidad == 169  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //monterrey ZM
                    $clave = 122;
                }elseif ($localidad == 208  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tuxpan
                    $clave = 122;
                }elseif ($localidad == 43  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //queretaro
                    $clave = 122;
                }elseif ($localidad == 131  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //reynosa
                    $clave = 122;
                }elseif ($localidad == 157  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //san juan del río
                    $clave = 122;
                }elseif ($localidad == 57  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tlaxcala
                    $clave = 122;
                }elseif ($localidad == 117  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tijuana
                    $clave = 11;
                }elseif ($localidad == 59  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //jalapa
                    $clave = 11;
                }elseif ($localidad == 29  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //toluca
                    $clave = 11;
                }elseif ($localidad == 14  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tuxtla
                    $clave = 11;
                }elseif ($localidad == 150  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //ciudad valles
                    $clave = 124;
                }elseif ($localidad == 80  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //irapuato
                    $clave = 125;
                }elseif ($localidad == 109  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tehuacan
                    $clave = 122;
                }elseif ($localidad == 168  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //apizaco
                    $clave = 122;
                }elseif ($localidad == 33  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //irapuato
                    $clave = 110;
                }elseif ($localidad == 49  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //culiacan
                    $clave = 126;
                }elseif ($localidad == 25  && date($fechaRegistro) >= date('2018-09-01 00:00:00')) { //Pachuca
                    $clave = 110;
                }elseif ($localidad == 39  && date($fechaRegistro) >= date('2018-10-11 00:00:00')) { //oaxaca
                    $clave = 142;
                }elseif ($localidad == 100  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //torreo
                    $clave = 110;
                }elseif ($localidad == 12  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //Oaxaca
                    $clave = 52;
                }elseif ($localidad == 33  && date($fechaRegistro) >= date('2018-08-01 00:00:00')) { //cuernavaca
                    $clave = 110;
                }elseif ($localidad == 214  && date($fechaRegistro) >= date('2018-08-01 00:00:00')) { //oaxaca
                    $clave = 32;
                }elseif ($localidad == 147) { //tehuantepec
                    if( $unidad == 120){
                        if(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                            $clave = 122;
                        }else{
                            $clave = 63;
                        }
                    }else{
                        if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
                            $clave = 124;    
                        }elseif(date($fechaRegistro) >= date('2017-06-26 00:00:00')){
                            $clave = 118;    
                        }else{
                            $clave = 63;    
                        }   
                    }                
                }elseif ($localidad == 140 ) { //delicias
                    if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
                        $clave = 124;    
                    }elseif(date($fechaRegistro) >= date('2017-04-11 00:00:00')){
                        $clave = 52;    
                    }else{
                        $clave = 63;    
                    }      
                }elseif ($localidad == 162) { //nuevo laredo
                    if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
                        $clave = 124;    
                    }elseif(date($fechaRegistro) >= date('2017-01-09 00:00:00')){
                        $clave = 52;    
                    }else{
                        $clave = 63;    
                    }   
                }elseif ($localidad == 146 ) { //ensenada
                    if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
                        $clave = 124;    
                    }elseif(date($fechaRegistro) >= date('2017-09-18 00:00:00')){
                        $clave = 52;    
                    }else{
                        $clave = 63;    
                    }   
                }elseif ($localidad == 3) { //mexicali
                    if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
                        $clave = 123;    
                    }elseif(date($fechaRegistro) >= date('2017-08-04 00:00:00')){
                        $clave = 119;    
                    }else{
                        $clave = 63;    
                    }   
                }elseif ($localidad == 63) { //zacatecas
                    if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
                        $clave = 124;    
                    }elseif(date($fechaRegistro) >= date('2017-08-15 00:00:00')){
                        $clave = 118;    
                    }else{
                        $clave = 63;    
                    }   
                }
                else{                    
                    $clave = 63;
                }

            //No Qx
            }elseif($producto == 9){

                //si son unidades de red especificos o propias de DF 
                if (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301  || ( $localidad == 18 && $unidadPropia == 'S') ) ) ) { 
                    if(date($fechaRegistro) >= date('2017-04-01 00:00:00')){
                        $clave = 111;    
                    }elseif (date($fechaRegistro) >= date('2016-11-01 00:00:00') && date($fechaRegistro) < date('2017-04-01 00:00:00')) {
                        $clave = 107;
                    } else{                    
                        $clave = 63;
                    }                              
                }
                else{                    
                    $clave = 63;
                }
            }

             $sql="SELECT TAD_importe FROM TabuladorDetalle WHERE TAB_clave=".$clave;
             $result=$db->query($sql);
             $datImporte = $result->fetch();
             $importe = $datImporte['TAD_importe'];



            $gestimado=$importe;
            $medico ='STAFF MEDICO';
            $proveedor= $cveProv;
            $folioProveedor =$fol;
            $observaciones ='';
            $subtotal = $gestimado;
 
            
            $xml='<gmqXmlRequest>
                        <xmlRequest pid="1" token="ad8787769969818cc9f4f9a656298f58">
                            <xmlRequestData>
                                <folio>'.$folElec.'</folio>
                                <reporte>'.$reporte.'</reporte>
                                <siniestro>'.$siniestro.'</siniestro>
                                <lesionado>'.$nombre.'</lesionado>
                                <diagnostico>T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS</diagnostico>
                                <sexo>'.$sexo.'</sexo>
                                <edad>'.$edad.'</edad>
                                <fingreso>'.$fingreso.'</fingreso>
                                <fegreso>'.$fegreso.'</fegreso>
                                <triage>'.$triage.'</triage>
                                <gestimado>'.$gestimado.'</gestimado>
                                <medico>'.$medico.'</medico>
                                <proveedor>'.$proveedor.'</proveedor>
                                <folioproveedor>'.$folioProveedor.'</folioproveedor>
                                <observaciones>'.$observaciones.'</observaciones>
                                <subtotal>'.$subtotal.'</subtotal>
                            </xmlRequestData>
                        </xmlRequest>
                    </gmqXmlRequest>
                    ';

            $servicio="http://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio      
            $client = new SoapClient($servicio);              
            
            $parameters=array('parameters'=>array('xml_solicitud'=> $xml));

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al métdo que nos interesa con los parámetros

            $xml_result= $result->solicitudAutorizacionResult;

            $resulta = $proc->xml2array($xml_result);

            $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)
                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     
            $result=$db->query($queryIn);


        echo "<tr>";
        echo "<td>".$fol."<td>";
        echo "<td>".$resulta[0][0]['value']."<td>";
        echo "<td>".$resulta[0][1]['value']."<td>";
        echo "<tr>"; 

		}else{
			similar_text($value['Exp_completo'], $value1['QWS_lesionado'], $percent);
			if($percent>70 && $percent<85){
				echo "<tr>";		        
		        echo "<td>".$value['Exp_completo']."<td>";
		        echo "<td>".$value1['QWS_lesionado']."<td>";
		        echo "<td>".$percent."<td>";		    
		        echo "<tr>";
			}				 	
			 	 
		}
	}	
}
echo "</table>";
?>