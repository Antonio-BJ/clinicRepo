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
                //Cerrar directorio 
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
$proc->conectar('medicavial.net','medica','M@d1$4_01i0');
$db = $proc->conectarMySQL();

$query = " SELECT Exp_folio, Exp_completo, Exp_fecreg, Exp_RegCompania, Pro_clave, Expediente.Uni_clave, LOC_claveint, Uni_propia, Exp_sexo, Exp_edad, Uni_claveQ  FROM Expediente 
            INNER JOIN Unidad on Expediente.Uni_clave = Unidad.Uni_clave
            
            where Exp_fecreg BETWEEN '2020-03-15' and '2020-03-19 23:59:59' and CIA_clave=19 and Exp_cancelado<>1";
$res = $db->query($query);
$listado = $res->fetchAll();
$arraylimpio1 = array();
$arraylimpio2 = array();

foreach($listado as $folio => $valor){
    // echo $valor['Exp_folio'];
    $query1 = "SELECT count(*) contador from RegistroQualitasWS where Exp_folio='".$valor['Exp_folio']."' and (RQW_resultado='El folio se encolo' || RQW_error='El folio ya cuenta con alguna AUTORIZACION)'";
    $res = $db->query($query);
    $contadorArray = $res->fetch();
    $contador = $contadorArray['contador'];
    if($contador ==0){
        array_push($arraylimpio1 , $valor);
    }
}

echo "<table>";
foreach ($arraylimpio1 as $key => $value) {
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


            

            if ($producto == 1){



                if (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301 || $unidad == 266 || $unidad == 348 || $unidad == 281) ) ) { 

                    if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){

                        $clave = 52;    

                    }elseif(date($fechaRegistro) >= date('2017-04-01 00:00:00')){

                        $clave = 111;    

                    }elseif (date($fechaRegistro) >= date('2016-11-01 00:00:00') && date($fechaRegistro) < date('2017-04-01 00:00:00')) {

                        $clave = 107;

                    } else{                    

                        $clave = 63;

                    }                              

                }





                //mv puebla

                elseif ($unidad == 4) {                    

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

                }elseif ($localidad == 167  ) { //guadalajara zm

                    if (date($fechaRegistro) >= date('2019-04-10 00:00:00')) {

                        $clave=145;

                    }elseif(date($fechaRegistro) >= date('2017-10-26 00:00:00')){

                        $clave = 40;

                    } 

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

                    if(date($fechaRegistro) >= date('2019-06-03 00:00:00')){

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

                }elseif ($localidad == 157  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //san juan del r??o

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

                }elseif ($localidad == 25 ) { //Pachuca
                    if(date($fechaRegistro) >= date('2020-03-11 00:00:00')){
                        $clave = 126;
                    }elseif (date($fechaRegistro) >= date('2018-09-01 00:00:00')) {
                        $clave = 110;
                    }    
                }elseif ($localidad == 39 ) { //oaxaca
                    if(date($fechaRegistro) >= date('2020-03-05 00:00:00')){
                        $clave = 159;
                    }elseif(date($fechaRegistro) >= date('2018-10-11 00:00:00')){
                        $clave = 142;
                    }                
                }elseif ($localidad == 100 ) { //torreon
                    if(date($fechaRegistro) >= date('2019-10-14 00:00:00')){
                        $clave = 149;
                    }elseif(date($fechaRegistro) >= date('2018-11-23 00:00:00')){
                        $clave = 110;
                    }else{
                        $clave = 61;
                    }
                }elseif ($localidad == 12  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //colima

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

                    if(date($fechaRegistro) >= date('2020-03-13 00:00:00')) {

                        $clave = 160;    

                    }elseif(date($fechaRegistro) >= date('2018-01-15 00:00:00')) {

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

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al m??tdo que nos interesa con los par??metros

            $xml_result= $result->solicitudAutorizacionResult;

            $resulta = $proc->xml2array($xml_result);

            $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)
                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     
            $result=$db->query($queryIn);


        
        echo "<td>".$fol."<td>";
        echo "<td>".$resulta[0][0]['value']."<td>";
        echo "<td>".$resulta[0][1]['value']."<td>";  
        
        if($resulta[0][0]['value']=='El folio se encolo'){

        }else{
            if($resulta[0][1]['value']=='El folio ya cuenta con alguna AUTORIZACION'){

            }else{
                $queryFal = "INSERT INTO FaltantesQualitasWS(Exp_folio,FQW_error) VALUES('".$fol."','".$resulta[0][1]['value']."')";
                echo $queryFal;
                $db->query($queryFal);
            }
        }

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


            

            if ($producto == 1){



                if (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301 || $unidad == 266 || $unidad == 348 || $unidad == 281) ) ) { 

                    if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){

                        $clave = 52;    

                    }elseif(date($fechaRegistro) >= date('2017-04-01 00:00:00')){

                        $clave = 111;    

                    }elseif (date($fechaRegistro) >= date('2016-11-01 00:00:00') && date($fechaRegistro) < date('2017-04-01 00:00:00')) {

                        $clave = 107;

                    } else{                    

                        $clave = 63;

                    }                              

                }





                //mv puebla

                elseif ($unidad == 4) {                    

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

                }elseif ($localidad == 167  ) { //guadalajara zm

                    if (date($fechaRegistro) >= date('2019-04-10 00:00:00')) {

                        $clave=145;

                    }elseif(date($fechaRegistro) >= date('2017-10-26 00:00:00')){

                        $clave = 40;

                    } 

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

                    if(date($fechaRegistro) >= date('2019-06-03 00:00:00')){

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

                }elseif ($localidad == 157  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //san juan del r??o

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

                }elseif ($localidad == 25) { //Pachuca

                    if(date($fechaRegistro) >= date('2020-03-11 00:00:00')){
                        $clave = 126;
                    }elseif (date($fechaRegistro) >= date('2018-09-01 00:00:00')) {
                        $clave = 110;
                    }    

                }elseif ($localidad == 39  && date($fechaRegistro) >= date('2018-10-11 00:00:00')) { //oaxaca

                    $clave = 142;

                }elseif ($localidad == 100 ) { //torreon
                    if(date($fechaRegistro) >= date('2019-10-14 00:00:00')){
                        $clave = 149;
                    }elseif(date($fechaRegistro) >= date('2018-11-23 00:00:00')){
                        $clave = 110;
                    }else{
                        $clave = 61;
                    }
                }elseif ($localidad == 12  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //colima

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

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al m??tdo que nos interesa con los par??metros

            $xml_result= $result->solicitudAutorizacionResult;

            $resulta = $proc->xml2array($xml_result);

            $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)
                            VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     
            $result=$db->query($queryIn);

            
            echo "<td>".$fol."<td>";
            echo "<td>".$resulta[0][0]['value']."<td>";
            echo "<td>".$resulta[0][1]['value']."<td>";

            if($resulta[0][0]['value']=='El folio se encolo'){

            }else{
                if($resulta[0][1]['value']=='El folio ya cuenta con alguna AUTORIZACION'){

                }else{
                    $queryFal = "INSERT INTO FaltantesQualitasWS(Exp_folio,FQW_error) VALUES('".$fol."','".$resulta[0][1]['value']."')";
                    echo $queryFal;
                    $db->query($queryFal);
                }
            }
           
        }else{
            
        echo "<td>".$fol."<td>";
        echo "<td>Sin coincidencias en el WS<td>";
        echo "<td>/<td>";

        if($resulta[0][0]['value']=='El folio se encolo'){

        }else{
            if($resulta[0][1]['value']=='El folio ya cuenta con alguna AUTORIZACION'){

            }else{
                $queryFal = "INSERT INTO FaltantesQualitasWS(Exp_folio,FQW_error) VALUES('".$fol."','".$resulta[0][1]['value']."')";
                echo $queryFal;
                $db->query($queryFal);
            }
        }
       
        }
    }

echo "<tr>";

}

echo '</table>';


$query1 = "select * from QualitasWS_resp WHERE QWS_fechaRegistro>='2018-12-01'";

$res1 = $db->query($query1);
$listado1 = $res1->fetchAll();


foreach($arraylimpio1 as $folio => $valor){
    // echo $valor['Exp_folio'];
    $query1 = "SELECT count(*) contador from RegistroQualitasWS where Exp_folio='".$valor['Exp_folio']."' and (RQW_resultado='El folio se encolo' || RQW_error='El folio ya cuenta con alguna AUTORIZACION)'";
    $res = $db->query($query);
    $contadorArray = $res->fetch();
    $contador = $contadorArray['contador'];
    if($contador ==0){
        array_push($arraylimpio2 , $valor);
    }
}




echo "<table>";
foreach ($arraylimpio2 as $key => $value) {	
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



                if (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301 || $unidad == 266 || $unidad == 348 || $unidad == 281) ) ) { 

                    if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){

                        $clave = 52;    

                    }elseif(date($fechaRegistro) >= date('2017-04-01 00:00:00')){

                        $clave = 111;    

                    }elseif (date($fechaRegistro) >= date('2016-11-01 00:00:00') && date($fechaRegistro) < date('2017-04-01 00:00:00')) {

                        $clave = 107;

                    } else{                    

                        $clave = 63;

                    }                              

                }





                //mv puebla

                elseif ($unidad == 4) {                    

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

                }elseif ($localidad == 167  ) { //guadalajara zm

                    if (date($fechaRegistro) >= date('2019-04-10 00:00:00')) {

                        $clave=145;

                    }elseif(date($fechaRegistro) >= date('2017-10-26 00:00:00')){

                        $clave = 40;

                    } 

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

                    if(date($fechaRegistro) >= date('2019-06-03 00:00:00')){

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

                }elseif ($localidad == 157  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //san juan del r??o

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

                }elseif ($localidad == 25) { //Pachuca

                    if(date($fechaRegistro) >= date('2020-03-11 00:00:00')){
                        $clave = 126;
                    }elseif (date($fechaRegistro) >= date('2018-09-01 00:00:00')) {
                        $clave = 110;
                    }    

                }elseif ($localidad == 39  && date($fechaRegistro) >= date('2018-10-11 00:00:00')) { //oaxaca

                    $clave = 142;

                }elseif ($localidad == 100 ) { //torreon
                    if(date($fechaRegistro) >= date('2019-10-14 00:00:00')){
                        $clave = 149;
                    }elseif(date($fechaRegistro) >= date('2018-11-23 00:00:00')){
                        $clave = 110;
                    }else{
                        $clave = 61;
                    }
                }elseif ($localidad == 12  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //colima

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

            $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al m??tdo que nos interesa con los par??metros

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

        if($resulta[0][0]['value']=='El folio se encolo'){

        }else{
            if($resulta[0][1]['value']=='El folio ya cuenta con alguna AUTORIZACION'){

            }else{
                $queryFal = "INSERT INTO FaltantesQualitasWS(Exp_folio,FQW_error) VALUES('".$fol."','".$resulta[0][1]['value']."')";
                echo $queryFal;
                $db->query($queryFal);
            }
        }

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


$query1 = "update QualitasWS_resp set Exp_folioRelacion=SUBSTR(QWS_observaciones,1,10) where QWS_observaciones<>'' and Exp_folioRelacion is null and LENGTH(QWS_observaciones)=12";                     
$result=$db->query($query1);
$query2 = "update QualitasWS_resp set Exp_folioRelacion=SUBSTR(QWS_observaciones,1,10) where QWS_observaciones<>'' and Exp_folioRelacion ='' and LENGTH(QWS_observaciones)=12";                     
$result=$db->query($query2);
$query3 = "update QualitasWS_resp set Exp_folioRelacion=Exp_folio where Exp_folio<>'' and Exp_folioRelacion is null";                     
$result=$db->query($query3);
$query4 = "update QualitasWS_resp set Exp_folioRelacion=Exp_folio where Exp_folio<>'' and Exp_folioRelacion =''";                     
$result=$db->query($query4);


exit;
  
?>