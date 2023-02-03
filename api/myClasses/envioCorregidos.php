<?php  
error_reporting(1);
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
$proc->conectar('medicavial.net','account@medicavial.net','ofe929kyp30gv');
$db = $proc->conectarMySQL();
$query = " SELECT Exp_folio, Exp_completo, Exp_fecreg, Exp_RegCompania, Pro_clave, Expediente.Uni_clave, LOC_claveint, Uni_propia, Exp_sexo, Exp_edad, Uni_claveQ  FROM Expediente 
            INNER JOIN Unidad on Expediente.Uni_clave = Unidad.Uni_clave
            where Exp_fecreg>='2019-01-25' and Exp_cancelado<>1
          and Cia_clave=19 and Exp_folio in (
'CUSD000586',
'HUNC000003',
'CHSS004098',
'GDSG009156',
'CLSI011081',
'CLSI011082',
'GDSG009213',
'UMEG022212',
'UMEG022214',
'DUSJ000717',
'CLSI011101',
'ZASE005296',
'CEMS032133',
'CEMS032135',
'GDSG009269',
'GDSG009270',
'LEBR001558',
'LEBR001559',
'LEBR001560',
'TLSM003895',
'HUNC000005',
'UMEG022241',
'IXHS003497',
'IXHS003498',
'CEMS030603',
'CEMS030743',
'ZASE005146',
'TOUC011452',
'LEBR001411',
'CLSI010732',
'CLSI010753',
'ZASE005234',
'TOVI005076',
'HESA000407',
'PACM006243',
'CEMS032054',
'MZMA014931',
'CHSS004127',
'PACM006319',
'LEBR001510',
'GDSG009189',
'DURI006161',
'COCE001118',
'CEMS032141',
'CEMS032145',
'CLSI011156',
'PACM006431',
'LEBR001563',
'CUBC005205',
'CUBC005206',
'GDSG009281',
'GDSG009283',
'TOVI005338',
'COCE001119',
'COCE001121',
'CEMS032160',
'MZMA014986',
'MZMA014990',
'MZMA014992',
'CHSS004174',
'HUNC000006',
'HUNC000007',
'HUNC000008',
'HUNC000009',
'GDSG009290',
'IXHS003506',
'DURI006204',
'DURI006205',
'DURI006206',
'DURI006208',
'DURI006212',
'TOVI005342',
'CEMS032167',
'MZMA015001',
'CHSS004179',
'HMAY006707',
'HMAY006709',
'HMAY006713',
'PACM006453',
'HUNC000010',
'HUNC000011',
'GDSG009303',
'IXHS003508',
'IXHS003509',
'TESA005830',
'DURI006216',
'DURI006217',
'HESA000473',
'CEMS032176',
'CEMS032181',
'VIXA015518',
'VIXA015519',
'HUNC000012',
'GDSG009314',
'DURI006222',
'COCE001126',
'COCE001127',
'CEMS032183',
'CLSI011194',
'CHSS004184',
'LEBR001570',
'IXHS003517',
'DURI006223',
'DURI006224',
'HESA000479',
'HESA000480',
'HESA000481',
'DUSJ000723',
'DUSJ000724'

)";
$res = $db->query($query);
$listado = $res->fetchAll();
echo "<table>";
foreach ($listado as $key => $value) {
echo "<tr>";
    $query = "SELECT QWS_lesionado, QWS_poliza, QWS_siniestro, QWS_reporte, QWS_folioElectronico, QWS_cveProveedor  FROM QualitasWS_resp where QWS_lesionado like '%".$value['Exp_completo']."%' and QWS_folioAutorizacion=''";
    $res = $db->query($query);
    $respuesta = $res->fetch(); 
    // if($respuesta['QWS_lesionado']){        
    //     $fol            = $value['Exp_folio'];
    //     $nombre         = $value['Exp_completo'];
    //     $sexo           = $value['Exp_sexo'];
    //     $edad           = $value['Exp_edad'];
    //     $unidad         = $value['Uni_clave'];
    //     $fechaRegistro  = $value['Exp_fecreg'];
    //     $producto       = $value['Pro_clave'];
    //     $localidad      = $value['LOC_claveint'];
    //     $unidadPropia   = $value['Uni_propia'];
    //     $cveProv        = $value['Uni_claveQ'];
    //     $poliza         = $respuesta['QWS_poliza'];
    //     $siniestro      = $respuesta['QWS_siniestro'];
    //     $reporte        = $respuesta['QWS_reporte'];
    //     $folElec        = $respuesta['QWS_folioElectronico'];
    //     $cveProv        = $respuesta['QWS_cveProveedor'];
    //     /************************************** calculo de importe  ********************************************************/
    //         $diagnostico='T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS';
           
    //         $fingreso=$fechaRegistro;
    //         $fegreso=$fechaRegistro;
    //         $triage='AMBULATORIO CON PAQUETE';
    //         $clave = 0;
            
    //         if ($producto == 1){
    //             if((date($fechaRegistro) >= date('2020-05-11 00:00:00')) && ( $localidad == 168 || $localidad == 150 || $localidad == 12 || $localidad == 49 || $localidad == 146 || $localidad == 168 || $localidad == 121 || $localidad == 149 || $localidad == 162 || $localidad == 25 || $localidad == 109 || $localidad == 57 || $localidad == 59 || $localidad == 63)){
    //                 if($unidadPropia == 'S'){
    //                     $clave = 43; 
    //                 }elseif($localidad == 39){ //TTL OAXACA
    //                     $clave = 159; 
    //                 }elseif($localidad == 19){ //TTL DURANGO
    //                     $clave = 146;
    //                 }elseif($localidad == 100){ // TTL TORREON
    //                     $clave = 149;
    //                 }elseif($localidad == 105){ // HHNoQx Cancún
    //                     $clave = 22;
    //                 }elseif($localidad == 29) { // HHNoQx TOLUCA, GLD. (Z. METRO)
    //                     $clave = 43;
    //                 }else{
    //                     $clave = 49;
    //                 }
    //             }
    //             elseif (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301 || $unidad == 266 || $unidad == 348 || $unidad == 281) ) ) { 
    //                 if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
    //                     $clave = 52;    
    //                 }elseif(date($fechaRegistro) >= date('2017-04-01 00:00:00')){
    //                     $clave = 111;    
    //                 }elseif (date($fechaRegistro) >= date('2016-11-01 00:00:00') && date($fechaRegistro) < date('2017-04-01 00:00:00')) {
    //                     $clave = 107;
    //                 } else{                    
    //                     $clave = 63;
    //                 }                              
    //             }
    //             //mv puebla
    //             elseif ($unidad == 4) {                    
    //                 if(date($fechaRegistro) >= date('2016-12-05 00:00:00')){
    //                     $clave = 28;
    //                 }else{
    //                     $clave = 7;
    //                 }
    //             //mv monterrey
    //             }elseif (($unidad == 76 || $unidad == 113 || $unidad == 183)&& date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tlaxcala                    
    //                 $clave = 122;
    //             //Sasc Ixtapaluca y es mayor a 1 de noviembre 2016
    //             }elseif ($unidad == 5) {                    
    //                 $clave = 28;
    //             //Sasc Ixtapaluca y es mayor a 1 de noviembre 2016
    //             }elseif ( ($unidad == 110 || $unidad == 266 || $unidad == 65 || $unidad == 281) && date($fechaRegistro) >= date('2016-11-01 00:00:00')) {
    //                 $clave = 108; //sames
    //             }elseif ($localidad == 167  ) { //guadalajara zm
    //                 if (date($fechaRegistro) >= date('2019-04-10 00:00:00')) {
    //                     $clave=145;
    //                 }elseif(date($fechaRegistro) >= date('2017-10-26 00:00:00')){
    //                     $clave = 40;
    //                 } 
    //             }elseif ($localidad == 53  && date($fechaRegistro) >= date('2017-05-08 00:00:00')) { //villahermosa
    //                 $clave = 11;
    //             }elseif ($localidad == 103  && date($fechaRegistro) >= date('2017-06-05 00:00:00')) { //cordoba
    //                 $clave = 116;
    //             }elseif ($localidad == 121  && date($fechaRegistro) >= date('2016-12-05 00:00:00')) { //iguala
    //                 $clave = 116;
    //             }elseif ($localidad == 155  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //lagos de moreno
    //                 $clave = 122;
    //             }elseif ($localidad == 128  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //atotonilco
    //                 $clave = 122;
    //             }elseif ($localidad == 19  ) { //durango
    //                 if(date($fechaRegistro) >= date('2019-06-03 00:00:00')){
    //                     $clave = 146;
    //                 }else{
    //                     $clave = 122;
    //                 }                      
    //             }elseif ($localidad == 149  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //mazatlan
    //                 $clave = 122;
    //             }elseif ($localidad == 169  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //monterrey ZM
    //                 $clave = 122;
    //             }elseif ($localidad == 208  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tuxpan
    //                 $clave = 122;
    //             }elseif ($localidad == 43  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //queretaro
    //                 $clave = 122;
    //             }elseif ($localidad == 131  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //reynosa
    //                 $clave = 122;
    //             }elseif ($localidad == 157  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //san juan del río
    //                 $clave = 122;
    //             }elseif ($localidad == 57  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tlaxcala
    //                 $clave = 122;
    //             }elseif ($localidad == 117  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tijuana
    //                 $clave = 11;
    //             }elseif ($localidad == 59  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //jalapa
    //                 $clave = 11;
    //             }elseif ($localidad == 29  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //toluca
    //                 $clave = 11;
    //             }elseif ($localidad == 14  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tuxtla
    //                 $clave = 11;
    //             }elseif ($localidad == 150  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //ciudad valles
    //                 $clave = 124;
    //             }elseif ($localidad == 80  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //irapuato
    //                 $clave = 125;
    //             }elseif ($localidad == 109  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //tehuacan
    //                 $clave = 122;
    //             }elseif ($localidad == 168  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //apizaco
    //                 $clave = 122;
    //             }elseif ($localidad == 33  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //irapuato
    //                 $clave = 110;
    //             }elseif ($localidad == 49  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //culiacan
    //                 $clave = 126;
    //             }elseif ($localidad == 25  && date($fechaRegistro) >= date('2018-09-01 00:00:00')) { //Pachuca
    //                 $clave = 110;
    //             }elseif ($localidad == 39  && date($fechaRegistro) >= date('2018-10-11 00:00:00')) { //oaxaca
    //                 $clave = 142;
    //             }elseif ($localidad == 106  && date($fechaRegistro) >= date('2020-06-01 00:00:00')) { //acapulco
    //                 $clave = 142;
    //             }elseif ($localidad == 100  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //torreo
    //                 $clave = 110;
    //             }elseif ($localidad == 12  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //colima
    //                 $clave = 52;
    //             }elseif ($localidad == 33  && date($fechaRegistro) >= date('2018-08-01 00:00:00')) { //cuernavaca
    //                 $clave = 110;
    //             }elseif ($localidad == 214  && date($fechaRegistro) >= date('2018-08-01 00:00:00')) { //oaxaca
    //                 $clave = 32;
    //             }elseif ($localidad == 106  && date($fechaRegistro) >= date('2020-06-01 00:00:00')) { //acapulco
    //                 $clave = 142;
    //             }elseif ($localidad == 147) { //tehuantepec
    //                 if( $unidad == 120){
    //                     if(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
    //                         $clave = 122;
    //                     }else{
    //                         $clave = 63;
    //                     }
    //                 }else{
    //                     if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
    //                         $clave = 124;    
    //                     }elseif(date($fechaRegistro) >= date('2017-06-26 00:00:00')){
    //                         $clave = 118;    
    //                     }else{
    //                         $clave = 63;    
    //                     }   
    //                 }                
    //             }elseif ($localidad == 140 ) { //delicias
    //                 if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
    //                     $clave = 124;    
    //                 }elseif(date($fechaRegistro) >= date('2017-04-11 00:00:00')){
    //                     $clave = 52;    
    //                 }else{
    //                     $clave = 63;    
    //                 }      
    //             }elseif ($localidad == 162) { //nuevo laredo
    //                 if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
    //                     $clave = 124;    
    //                 }elseif(date($fechaRegistro) >= date('2017-01-09 00:00:00')){
    //                     $clave = 52;    
    //                 }else{
    //                     $clave = 63;    
    //                 }   
    //             }elseif ($localidad == 146 ) { //ensenada
    //                 if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
    //                     $clave = 124;    
    //                 }elseif(date($fechaRegistro) >= date('2017-09-18 00:00:00')){
    //                     $clave = 52;    
    //                 }else{
    //                     $clave = 63;    
    //                 }   
    //             }elseif ($localidad == 3) { //mexicali
    //                 if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
    //                     $clave = 123;    
    //                 }elseif(date($fechaRegistro) >= date('2017-08-04 00:00:00')){
    //                     $clave = 119;    
    //                 }else{
    //                     $clave = 63;    
    //                 }   
    //             }elseif ($localidad == 63) { //zacatecas
    //                 if (date($fechaRegistro) >= date('2018-01-15 00:00:00')) {
    //                     $clave = 124;    
    //                 }elseif(date($fechaRegistro) >= date('2017-08-15 00:00:00')){
    //                     $clave = 118;    
    //                 }else{
    //                     $clave = 63;    
    //                 }   
    //             }
    //             else{                    
    //                 $clave = 63;
    //             }
    //         //No Qx
    //         }elseif($producto == 9){
    //             //si son unidades de red especificos o propias de DF 
    //             if (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301  || ( $localidad == 18 && $unidadPropia == 'S') ) ) ) { 
    //                 if(date($fechaRegistro) >= date('2017-04-01 00:00:00')){
    //                     $clave = 111;    
    //                 }elseif (date($fechaRegistro) >= date('2016-11-01 00:00:00') && date($fechaRegistro) < date('2017-04-01 00:00:00')) {
    //                     $clave = 107;
    //                 } else{                    
    //                     $clave = 63;
    //                 }                              
    //             }
    //             else{                    
    //                 $clave = 63;
    //             }
    //         }
    //          $sql="SELECT TAD_importe FROM TabuladorDetalle WHERE TAB_clave=".$clave;
    //          $result=$db->query($sql);
    //          $datImporte = $result->fetch();
    //          $importe = $datImporte['TAD_importe'];
    //         $gestimado=$importe;
    //         $medico ='STAFF MEDICO';
    //         $proveedor= $cveProv;
    //         $folioProveedor =$fol;
    //         $observaciones ='';
    //         $subtotal = $gestimado;
 
            
    //         $xml='<gmqXmlRequest>
    //                     <xmlRequest pid="1" token="ad8787769969818cc9f4f9a656298f58">
    //                         <xmlRequestData>
    //                             <folio>'.$folElec.'</folio>
    //                             <reporte>'.$reporte.'</reporte>
    //                             <siniestro>'.$siniestro.'</siniestro>
    //                             <lesionado>'.$nombre.'</lesionado>
    //                             <diagnostico>T07 TRAUMATISMOS SIMPLES NO ESPECIFICADOS</diagnostico>
    //                             <sexo>'.$sexo.'</sexo>
    //                             <edad>'.$edad.'</edad>
    //                             <fingreso>'.$fingreso.'</fingreso>
    //                             <fegreso>'.$fegreso.'</fegreso>
    //                             <triage>'.$triage.'</triage>
    //                             <gestimado>'.$gestimado.'</gestimado>
    //                             <medico>'.$medico.'</medico>
    //                             <proveedor>'.$proveedor.'</proveedor>
    //                             <folioproveedor>'.$folioProveedor.'</folioproveedor>
    //                             <observaciones>'.$observaciones.'</observaciones>
    //                             <subtotal>'.$subtotal.'</subtotal>
    //                         </xmlRequestData>
    //                     </xmlRequest>
    //                 </gmqXmlRequest>
    //                 ';
    //         $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL"; //url del servicio      
    //         $client = new SoapClient($servicio);              
            
    //         $parameters=array('parameters'=>array('xml_solicitud'=> $xml));
    //         $result = $client->__call("solicitudAutorizacion" ,$parameters);//llamamos al métdo que nos interesa con los parámetros
    //         $xml_result= $result->solicitudAutorizacionResult;
    //         $resulta = $proc->xml2array($xml_result);
    //         $queryIn = "INSERT INTO RegistroQualitasWS(Exp_folio,RQW_fecreg,RQW_cveUnidad,RQW_importe,RQW_resultado,RQW_error,RQW_folioElectronico)
    //                         VALUES('".$fol."',now(),'".$proveedor."','".$gestimado."','".$resulta[0][0]['value']."','".$resulta[0][1]['value']."','".$folElec."')";                     
    //         $result=$db->query($queryIn);
        
    //     echo "<td>".$fol."<td>";
    //     echo "<td>".$resulta[0][0]['value']."<td>";
    //     echo "<td>".$resulta[0][1]['value']."<td>";       
    // }else{
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
            // $servicio="https://oplinea.qualitas.com.mx/gmq/wsServiciosHospitalarios.php?WSDL";
            //url del servicio     

            $servicio="http://gmq.qualitas.com.mx/wsServiciosHospitalarios.php?WSDL"; 
            // echo file_get_contents($servicio);
// die();
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
// }
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