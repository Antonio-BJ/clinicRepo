<?php  

// error_reporting(0);

set_time_limit(0);

ini_set ('memory_limit', '-1');

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

        private function logMessage( $message )

        {

            return $this->messageArray[] = $message;

        }

        public function getMessages()

        {

            return $this->messageArray;

        }

        public function leerDirectorioFuente($ruta, $banArch =  false){

            if($dir = opendir($ruta)){ 

                chdir($ruta);

                //Creamos los array de archivos y carpetas 

                $archivos=array();

                $carpetas=array();

                //Leemos todos los elementos del directorio 

                while (($leerDir = readdir($dir)) !== false){

                    if(!is_dir ($leerDir)) array_push($archivos,$leerDir);

                    else array_push($carpetas,$leerDir);

                }

                //Mostrar carpetas 

                foreach($carpetas as $aux){

                    if ( $aux == '.' || $aux == '..' ) {

                        # nada

                    } else {

                        $new[] = $aux;

                    }

                } 

                //Mostrar Archivos 

                if ( $banArch ) {

                    foreach($archivos as $aux){

                        $new[] .= $aux;

                    }

                }

                //Cerrar directorio b     

                closedir($dir);

            }

            return array($new);

        }

        public function conectar($server, $usuario, $password, $con_pasivo = false)

        {

            $this->server = $server;

            $this->usuario = $usuario;

            $this->password = $password;

            $this->conexion = ftp_connect( $this->server );

            $login = ftp_login( $this->conexion, $this->usuario, $this->password);

            

            ftp_pasv(  $this->conexion, $con_pasivo);

            if (!$this->conexion || !$login) { 

                $this->logMessage('La conexion FTP ha fallado'); 

            }else{

                $this->logMessage('Conexion exitosa'); 

                $this->loginOk = true;

            }

            return true;

        }

        public function subirDirectorios( $destino, $fuente, $mode = FTP_BINARY ){

            

            $upload = ftp_put($this->conexion, $destino, $fuente, $mode);

            if (!$upload) { 

                $this->logMessage('La subida ha fallado con el archivo ' . $fuente . ' ***'); 

                $temp = false;

            }else{

                $this->logMessage('Up: ' . $fuente . ' --> ' . $destino . ' done' );

                $temp = true;

            }

            return $temp;

        }

        public function crearDirectorio( $carpeta )

        {

            if (ftp_mkdir( $this->conexion , $carpeta)) {

                $this->logMessage( 'El directorio ' . $carpeta . ' se ha creado' );

                $temp = true;

            } else {

                $this->logMessage('No se pudo crear el directorio ' . $carpeta );

                $temp = false;

            }

            return $temp;

            

        }

        public function cargandoCarpeta( $carpeta )

        {

            if ( ftp_chdir( $this->conexion, $carpeta) ) {

                $this->logMessage( 'Se esta cargando el contenido...' );

                $temp = true;

            } else {

                $this->logMessage( 'No se pudo cargar el contenido' );

                $temp = false;

            }

            return $temp;

            

        }

        public function getListaContenido( $carpeta = '.', $parametro = '')

        {

            $contenidoArray = ftp_nlist($this->conexion,$carpeta );

            return $contenidoArray;

        }

        public function descargar( $fuente, $destino, $modo = FTP_ASCII )

        {

            if ( ftp_get($this->conexion, $destino, $fuente, $modo, 0) ) {

                $this->logMessage( 'Se ha descargado el archivo: ' . $destino );

                $temp = true;

            } else {

                $this->logMessage( 'Ha fallado la descarga: ' . $destino );

                $temp = false;

            }

            return $temp;

            

        }

        public function comprobar($directorio,$archivo){       

            if (ftp_get($this->conexion, $directorio, $archivo, FTP_BINARY)){

                $existe=1;

                unlink($directorio);

            }else{

                $existe=0;

            }   

            return ($existe);

        }

        public function desconectar()

        {

            if ( $this->conexion ) {

                $this->logMessage( 'Se ha desconectado del FTP' );

                ftp_close( $this->conexion );

            }

        }

        public function crearCarpeta($carpeta)

        {

           if (ftp_nlist($this->conexion, $carpeta) == false) {

                ftp_mkdir($this->conexion, $carpeta);

                return 1;

            }else{

                return 0;

            }

        }

        public function findfile($location='',$fileregex='') {

            if (!$location or !is_dir($location) or !$fileregex) {

               return false;

            }

            $matchedfiles = array();

            $all = opendir($location);

            while ($file = readdir($all)) {

               if (is_dir($location.'/'.$file) and $file <> ".." and $file <> ".") {

                  $subdir_matches = $this->findfile($location.'/'.$file,$fileregex);

                  $matchedfiles = array_merge($matchedfiles,$subdir_matches);

                  unset($file);

               }

               elseif (!is_dir($location.'/'.$file)) {

                  if (preg_match($fileregex,$file)) {

                     array_push($matchedfiles,$location.'/'.$file);

                  }

               }

            }

            closedir($all);

            unset($all);

            return $matchedfiles;

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

$proc->conectar('medicavial.net','account@medicavial.net','ofe929kyp30gv');

$db = $proc->conectarMySQL();

$ayer = date("Y-m-d", strtotime("yesterday"));

$query = " SELECT

Exp_folio,

Exp_completo,

Exp_fecreg,

Exp_RegCompania,

Pro_clave,

Expediente.Uni_clave,

LOC_claveint,

Uni_propia,

Exp_sexo,

Exp_edad,

Uni_claveQ 

FROM

Expediente

INNER JOIN Unidad ON Expediente.Uni_clave = Unidad.Uni_clave 

WHERE

Exp_fecreg BETWEEN '".$ayer."'

AND '".$ayer." 23:59:59' 

AND Exp_cancelado <> 1 

AND Cia_clave = 19 

UNION

SELECT

Expediente.Exp_folio,

Exp_completo,

Exp_fecreg,

Exp_RegCompania,

Pro_clave,

Expediente.Uni_clave,

LOC_claveint,

Uni_propia,

Exp_sexo,

Exp_edad,

Uni_claveQ 

FROM

Expediente

INNER JOIN FaltantesQualitasWS ON Expediente.Exp_folio = FaltantesQualitasWS.Exp_folio

INNER JOIN Unidad ON Expediente.Uni_clave = Unidad.Uni_clave 

WHERE

FQW_error = 'Sin Coincidencias en el WS'";

$res = $db->query($query);

$listado = $res->fetchAll();

// Exp_fecreg BETWEEN '".$ayer."'

// AND '".$ayer." 23:59:59' 

echo "<table>";

foreach ($listado as $key => $value) {

echo "<tr>";



    $query = "SELECT QWS_lesionado, QWS_poliza, QWS_siniestro, QWS_reporte, QWS_folioElectronico, QWS_cveProveedor  FROM QualitasWS_resp where QWS_lesionado like '%".$value['Exp_completo']."%' and QWS_folioAutorizacion=''";

    $res = $db->query($query);

    $respuesta = $res->fetch(); 

    if($respuesta['QWS_lesionado']){        

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

        $poliza         = $respuesta['QWS_poliza'];

        $siniestro      = $respuesta['QWS_siniestro'];

        $reporte        = $respuesta['QWS_reporte'];

        $folElec        = $respuesta['QWS_folioElectronico'];

        $cveProv        = $respuesta['QWS_cveProveedor'];

        /************************************** calculo de importe  ********************************************************/

            $diagnostico='T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS';

           

            $fingreso=$fechaRegistro;

            $fegreso=$fechaRegistro;

            $triage='AMBULATORIO CON PAQUETE';

            $clave = 0;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://api.medicavial.mx/api/mv/tabulador/U_ECG2/".$fol);

            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);

            curl_close($ch);

            $res = json_decode($output,true);

           echo $clave = $res['claveTabulador'];

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

            // $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio      



            $servicio="https://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL";

            $client = new SoapClient($servicio);              

            

            $parameters=array('parameters'=>array('xml_solicitud'=> $xml));

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al métdo que nos interesa con los parámetros

            $xml_result= $result->solicitudAutorizacionResult;

            $resulta = $proc->xml2array($xml_result);

            $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)

                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     

            $result=$db->query($queryIn);

        

        echo "<td>".$fol."<td>";

        echo "<td>".$resulta[0][0]['value']."<td>";

        echo "<td>".$resulta[0][1]['value']."<td>";       

    }else{

       

       $query = "SELECT QWS_lesionado, QWS_poliza, QWS_siniestro, QWS_reporte, QWS_folioElectronico, QWS_cveProveedor  FROM QualitasWS_resp where QWS_folioElectronico='".$value['Exp_RegCompania']."'";

        $res = $db->query($query); 

        $respuesta1 = $res->fetch(); 

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

        $poliza         = $respuesta1['QWS_poliza'];

        $siniestro      = $respuesta1['QWS_siniestro'];

        $reporte        = $respuesta1['QWS_reporte'];

        $folElec        = $respuesta1['QWS_folioElectronico'];

        $cveProv        = $respuesta1['QWS_cveProveedor'];

        if($respuesta1['QWS_lesionado'] && $respuesta1['QWS_folioAutorizacion']==''){

            /************************************** calculo de importe  ********************************************************/

            $diagnostico='T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS';

           

            $fingreso=$fechaRegistro;

            $fegreso=$fechaRegistro;

            $triage='AMBULATORIO CON PAQUETE';

            $clave = 0;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://api.medicavial.mx/api/mv/tabulador/U_ECG2/".$fol);

            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);

            curl_close($ch);

            $res = json_decode($output,true);

            $clave = $res['claveTabulador'];

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

            // $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio      

            $servicio="https://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL";



            $client = new SoapClient($servicio);              

            

            $parameters=array('parameters'=>array('xml_solicitud'=> $xml));

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al métdo que nos interesa con los parámetros

            $xml_result= $result->solicitudAutorizacionResult;

            $resulta = $proc->xml2array($xml_result);

            $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)

                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     

            $result=$db->query($queryIn);

            

            echo "<td>".$fol."<td>";

            echo "<td>".$resulta[0][0]['value']."<td>";

            echo "<td>".$resulta[0][1]['value']."<td>";





           

        }else{

            

        echo "<td>".$fol."<td>";

        echo "<td>Sin coincidencias en el WS<td>";

        echo "<td>/<td>";

       

        }

    }

echo "<tr>";

}

echo '</table>';

exit;

foreach ($listado as $key => $value) {   

    $fol            = '';

    $nombre         = '';

    $sexo           = '';

    $edad           = '';

    $unidad         = '';

    $fechaRegistro  = '';

    $producto       = '';

    $localidad      = '';

    $unidadPropia   = '';

    $poliza         = '';

    $siniestro      = '';

    $reporte        = '';

    $folElec        = '';

    $cveProv        = '';

    $query = "SELECT QWS_lesionado, QWS_poliza, QWS_siniestro, QWS_reporte, QWS_folioElectronico, QWS_cveProveedor  FROM QualitasWS_resp where QWS_lesionado like '%".$value['Exp_completo']."%' and QWS_folioAutorizacion=''";

    $res = $db->query($query);

    $respuesta = $res->fetch(); 

    if($respuesta['QWS_lesionado']){ 

      

        try {

                    

        $queryEx = "SELECT COUNT(*) as contador FROM RegistroQualitasWS WHERE Exp_folio='".$value['Exp_folio']."' and  (RQW_error='El folio ya cuenta con alguna AUTORIZACION' or RQW_resultado='El folio se encolo' or RQW_error='Pase fuera de vigencia')";

        $resp = $db->query($queryEx);

        $envio = $resp->fetch();

        $enviado = $envio['contador'];

        

        if($enviado==0){

        $fol            = $value['Exp_folio'];

        $nombre         = $value['Exp_completo'];

        $sexo           = $value['Exp_sexo'];

        $edad           = $value['Exp_edad'];

        $unidad         = $value['Uni_clave'];

        $fechaRegistro  = $value['Exp_fecreg'];

        $producto       = $value['Pro_clave'];

        $localidad      = $value['LOC_claveint'];

        $unidadPropia   = $value['Uni_propia'];

        $poliza         = $respuesta['QWS_poliza'];

        $siniestro      = $respuesta['QWS_siniestro'];

        $reporte        = $respuesta['QWS_reporte'];

        $folElec        = $respuesta['QWS_folioElectronico'];

        $cveProv        = $value['Uni_claveQ'];

        /************************************** calculo de importe  ********************************************************/

            $diagnostico='T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS';

           

            $fingreso=$fechaRegistro;

            $fegreso=$fechaRegistro;

            $triage='AMBULATORIO CON PAQUETE';

            $clave = 0;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://api.medicavial.mx/api/mv/tabulador/U_ECG2/".$fol);

            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);

            curl_close($ch);

            $res = json_decode($output,true);

            $clave = $res['claveTabulador'];

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

            // $servicio="http://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio      

            $servicio="https://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL";



            $client = new SoapClient($servicio);              

            

            $parameters=array('parameters'=>array('xml_solicitud'=> $xml));

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al métdo que nos interesa con los parámetros

            $xml_result= $result->solicitudAutorizacionResult;

            $resulta = $proc->xml2array($xml_result);

            $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)

                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     

            $result=$db->query($queryIn);

            echo 'el folio '.$fol.' '.$resulta[0][0]['value'].'---'.$resulta[0][1]['value'].'<br>';

           

        }

         } catch (Exception $e) {

                print_r($e->getmessage());     

            }

        /*******************************************************************************************************************/

    }else{

        $query = "SELECT QWS_lesionado, QWS_poliza, QWS_siniestro, QWS_reporte, QWS_folioElectronico, QWS_cveProveedor  FROM QualitasWS_resp where QWS_folioElectronico = '".$value['Exp_RegCompania']."'";

        $res = $db->query($query);

        $respuesta = $res->fetch();

        if($respuesta['QWS_lesionado']){

            

             $queryEx = "SELECT COUNT(*) as contador FROM RegistroQualitasWS WHERE Exp_folio='".$value['Exp_folio']."' and  RQW_error='El folio ya cuenta con alguna AUTORIZACION'";

            $resp = $db->query($queryEx);

            $envio = $resp->fetch();

            $enviado = $envio['contador'];

            if($enviado==0){

             $fol            = $value['Exp_folio'];

            $nombre         = $value['Exp_completo'];

            $sexo           = $value['Exp_sexo'];

            $edad           = $value['Exp_edad'];

            $unidad         = $value['Uni_clave'];

            $fechaRegistro  = $value['Exp_fecreg'];

            $producto       = $value['Pro_clave'];

            $localidad      = $value['LOC_claveint'];

            $unidadPropia   = $value['Uni_propia'];

            $poliza         = $respuesta['QWS_poliza'];

            $siniestro      = $respuesta['QWS_siniestro'];

            $reporte        = $respuesta['QWS_reporte'];

            $folElec        = $respuesta['QWS_folioElectronico'];

            $cveProv        = $respuesta['QWS_cveProveedor'];

            /************************************** calculo de importe  ********************************************************/

                $diagnostico='T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS';

               

                $fingreso=$fechaRegistro;

                $fegreso=$fechaRegistro;

                $triage='AMBULATORIO CON PAQUETE';

                $clave = 0;

                /***************************************************************************************************************************************/

                /***************************************************************************************************************************************/

                /***************************************************************************************************************************************/

                /***************************************************************************************************************************************/

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "http://api.medicavial.mx/api/mv/tabulador/U_ECG2/".$fol);

                curl_setopt($ch, CURLOPT_HEADER, 0);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    

                $output = curl_exec($ch);

                curl_close($ch);

                $res = json_decode($output,true);

    

                $clave = $res['claveTabulador'];   

                /***************************************************************************************************************************************/

                /***************************************************************************************************************************************/

                /***************************************************************************************************************************************/

                /***************************************************************************************************************************************/

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

                                    <folio>18MC00345491</folio>

                                    <reporte>04180753081</reporte>

                                    <siniestro>04180662239</siniestro>

                                    <lesionado>ROGER RODRIGO CHABLE ESCAMILLA</lesionado>

                                    <diagnostico>T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS</diagnostico>

                                    <sexo>M</sexo>

                                    <edad>29</edad>

                                    <fingreso>2018-07-03 14:56:57</fingreso>

                                    <fegreso>2018-07-03 14:56:57</fegreso>

                                    <triage>AMBULATORIO CON PAQUETE</triage>

                                    <gestimado> 1675.00</gestimado>

                                    <medico>STAFF MEDICO</medico>

                                    <proveedor>18601</proveedor>

                                    <folioproveedor>HMAY004499</folioproveedor>

                                    <observaciones></observaciones>

                                    <subtotal>1675.00</subtotal>

                                </xmlRequestData>

                            </xmlRequest>

                        </gmqXmlRequest>

                        ';                       

                // $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio    

                $servicio="https://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL";

  

                $client = new SoapClient($servicio);              

                

                $parameters=array('parameters'=>array('xml_solicitud'=> $xml));

                $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al métdo que nos interesa con los parámetros

                $xml_result= $result->solicitudAutorizacionResult;

                $resulta = $proc->xml2array($xml_result);

                $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)

                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')"; 

                $result=$db->query($queryIn);

                echo 'el folio '.$fol.' '.$resulta[0][0]['value'].'---'.$resulta[0][1]['value'].'<br>';

            /*******************************************************************************************************************/

        }

        }

        

    }   

}

    

?>