<?php

/*
 clase para conectarse mediante ftp
 */
class Ftp
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




    }


    $arrayAsociado =  array();

    $proc = new Ftp();    
    $db = $proc->conectarMySQL();

    $detalleFolio = "SELECT CQ_folioautorizacion,CQ_paciente FROM CartasQualitas WHERE (CQ_folRelacion IS NULL OR CQ_folRelacion='') LIMIT 100";
    $res = $db->query($detalleFolio);
    $arrayCedulas = $res->fetchAll();
    // 
    echo "<table>";

    foreach ($arrayCedulas as $key => $value) {
        $nombreCedula   = $value['CQ_paciente'];
      

        $folioCedula    = $value['CQ_folioautorizacion'];               
        $detalleFolio   = "SELECT Exp_folio,Exp_completo FROM Expediente WHERE Exp_completo = '".$nombreCedula."' and Exp_fecreg > '2016-11-01' and Uni_clave in (45,153,182,216,218,295,30,27,28,285,289,293,37,38,87,100,217,81,90,228,205,1,2,3,4,184,249,301,125,232,281,266,65,260,
            310,114,119,131,187,51,113,14,93,183,76,19,120,97) and Cia_clave=19";
        $res      = $db->query($detalleFolio);
        $resultado = $res->fetchAll();
                
        if($resultado){
            $arrayFolio     = $resultado;
            $nombreMv       = $arrayFolio[0]['Exp_completo'];
            $folioMv        = $arrayFolio[0]['Exp_folio'];
            $cancelaFolio= "UPDATE CartasQualitas SET CQ_folRelacion='".$folioMv."' where CQ_folioautorizacion='".$folioCedula."'";
            $resultado = $db->query($cancelaFolio);             
        }else{
            $nombreMv       = '';
            $folioMv        = '-';  
            $cancelaFolio= "UPDATE CartasQualitas SET CQ_folRelacion='".$folioMv."' where CQ_folioautorizacion='".$folioCedula."'";
            $resultado = $db->query($cancelaFolio);               
        }
        
        $arrayfol       = array('nomCed' => $nombreCedula, 'folCed' => $folioCedula,'nomMv' => $nombreMv, 'folMv' => $folioMv);
        //array_push($arrayAsociado,$arrayfol);
        echo "<tr><td>".$folioCedula."</td>";
        echo "<td>".$folioMv."</td></tr>";          

        //print_r($arrayfol);
        //echo '<br>';
    }

    echo "</table>";

    //return $arrayAsociado;




/**************************************************************************************************************************/
/*******************                                                                                    *******************/
/*******************                            FIN DOCUMENTOS A WEB                                    *******************/
/*******************                                                                                    *******************/
/**************************************************************************************************************************/