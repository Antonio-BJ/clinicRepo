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
        public function descargar( $fuente, $destino, $modo = FTP_BINARY )
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
           // if (ftp_nlist($this->conexion, $carpeta) == false) {
                ftp_mkdir($this->conexion, $carpeta);
            //     return 1;
            // }else{
            //     return 0;
            // }
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
        public function conectarMySQLZima(){

            $this->dbhost="pmzima.net";
            $this->dbuser="zima_web2";
            $this->dbpass="W3dik@_0i12";
            $this->dbname="zima_sscp_3";
            $this->conn = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            date_default_timezone_set('America/Mexico_City');
            return $this->conn;
        }

        public function conectarSQLServer(){

            $this->db_server    = 'GEN';
            // $this->db_name      = 'MV2';
            $this->db_name      = 'MV';

            $this->connS = new PDO("sqlsrv:Server=$this->db_server;Database=$this->db_name", "accesoVPN", "AcC3s0$");
            $this->connS->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->connS;


        }

        function eliminar_directorio($dir){
            $result = false;
            if ($handle = opendir("$dir")){
                $result = true;
                while ((($file=readdir($handle))!==false) && ($result)){
                        if ($file!='.' && $file!='..'){
                        if (is_dir("$dir/$file")){
                        $result = eliminar_directorio("$dir/$file");
                        } else {
                        $result = unlink("$dir/$file");}
                    }
                }
                closedir($handle);
                if ($result){
                    $result = rmdir($dir);
                }
            }
            return $result;
        }


        private function checaCaptura($folio){
            $dbServ = $this->conectarSQLServer();
            $query = "SELECT count(*) contador FROM Pase WHERE PAS_folio='".$folio."'";
            $result = $dbServ->query($query);
            $rs = $result->fetch();
            if($rs['contador']>=1){
                return true;
            }else{
                return false;
            }
            $dbServ = NULL;
            
        }

        private function facturado($folio){
            $dbServ = $this->conectarSQLServer();
            $query = "SELECT count(*) contador FROM vw_FacturaActivaXPase WHERE PAS_folio='".$folio."'";
            $result = $dbServ->query($query);
            $rs = $result->fetch();
            if($rs['contador']>=1){
                return true;
            }else{
                return false;
            }
            $dbServ = NULL;
        }

        private function edicionDatos($folio){
            $db = $this->conectarMySQL();
            $dbServ = $this->conectarSQLServer();

            $query = "SELECT Exp_completo, Exp_poliza, Exp_siniestro, Exp_reporte, Exp_RegCompania, RIE_claveMV, ObsNot_diagnosticoRx,ObsNotaMed.Clave_lesionMV, LesionEquivalencia.Clave_lesionCia FROM Expediente
                        INNER JOIN ObsNotaMed ON Expediente.Exp_folio = ObsNotaMed.Exp_folio
                        INNER JOIN LesionEquivalencia on ObsNotaMed.Clave_lesionMV = LesionEquivalencia.Clave_lesionMV
                        INNER JOIN RiesgoAfectado ON Expediente.RIE_clave = RiesgoAfectado.RIE_clave
                        WHERE Expediente.Exp_folio='".$folio."'";
            $result         = $db->query($query);
            $datosWeb       = $result->fetch();
            $webPoliza      = $datosWeb['Exp_poliza'];
            $webSiniestro   = $datosWeb['Exp_siniestro'];
            $webReporte     = $datosWeb['Exp_reporte'];
            $webFolElec     = $datosWeb['Exp_RegCompania'];
            $webRiesgo      = $datosWeb['RIE_claveMV'];
            $webNombre      = $datosWeb['Exp_completo'];
            $webDiag        = $datosWeb['ObsNot_diagnosticoRx'];            
            $webLesMv       = $datosWeb['Clave_lesionMV'];
            $webLesCia      = $datosWeb['Clave_lesionCia'];



            $querySQL = "SELECT DAS_poliza, DAS_siniestro,DAS_reporte,DAS_autorizacion,DAS_folioElec, RIE_clave, AFE_nombre, Rep_DescMedica, REP_lesionUnidad, LES_primaria, LES_empresa, Pase.DAS_claveint, Pase.AFE_claveint, Reporte.REP_claveint FROM Pase 
                        INNER JOIN Etapa1 ON Pase.PAS_folio = Etapa1.PAS_folio
                        INNER JOIN Reporte ON Etapa1.REP_claveint = Reporte.REP_claveint
                        INNER JOIN datosSiniestro ON Pase.DAS_claveint = datosSiniestro.DAS_claveint
                        INNER JOIN Afectado ON Pase.AFE_claveint = Afectado.AFE_claveint
                        WHERE Pase.PAS_folio='".$folio."'";
            $result = $dbServ->query($querySQL);
            $capturado = $result->fetch();

            $capPoliza      = $capturado['DAS_poliza'];
            $capSiniestro   = $capturado['DAS_siniestro'];
            $capReporte     = $capturado['DAS_reporte'];
            $capAuto        = $capturado['DAS_autorizacion'];
            $capFolElec     = $capturado['DAS_folioElec'];
            $capRiesgo      = $capturado['RIE_clave'];
            $capAfectado    = $capturado['AFE_nombre'];
            $capDescMed     = $capturado['Rep_DescMedica'];
            $capLesPrim     = $capturado['LES_primaria'];
            $capLesEmp      = $capturado['LES_empresa'];
            $cveDato        = $capturado['DAS_claveint'];
            $cveAfe         = $capturado['AFE_claveint'];
            $cveRep         = $capturado['REP_claveint'];
            $contadoEdicion = 0;


            if($webPoliza !=$capPoliza){
                $sql= "UPDATE DatosSiniestro SET DAS_poliza='".$webPoliza."' WHERE DAS_claveint ='".$cveDato."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webSiniestro !=$capSiniestro){
                $sql= "UPDATE DatosSiniestro SET DAS_siniestro='".$webSiniestro."' WHERE DAS_claveint ='".$cveDato."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webReporte !=$capReporte){
                $sql= "UPDATE DatosSiniestro SET DAS_reporte='".$webReporte."' WHERE DAS_claveint ='".$cveDato."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webFolElec !=$capFolElec){
                $sql= "UPDATE DatosSiniestro SET DAS_folioElec='".$webFolElec."' WHERE DAS_claveint ='".$cveDato."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webRiesgo !=$capRiesgo){
                $sql= "UPDATE Pase SET Pase.RIE_clave=".$webRiesgo." WHERE PAS_folio ='".$folio."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webNombre !=$capAfectado){
                $sql= "UPDATE Afectado SET AFE_nombre='".$webNombre."' WHERE AFE_claveint ='".$cveAfe."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webDiag !=$capDescMed){
                $sql= "UPDATE Reporte SET Rep_DescMedica='".strtoupper($webDiag)."' WHERE REP_claveint ='".$cveRep."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
          
            if($webLesMv !=$capLesPrim){
                $sql= "UPDATE Reporte SET LES_primaria='".$webLesMv."' WHERE REP_claveint ='".$cveRep."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            if($webLesCia !=$capLesEmp){
                $sql= "UPDATE Reporte SET LES_empresa='".$webLesCia."' WHERE REP_claveint ='".$cveRep."'"; 
                $resultado = $dbServ->query($sql);
                $contadoEdicion++;
            }
            
            if($contadoEdicion>0){
                return 1;
            }else{
                return 0;
            }
           
            $dbServ = NULL;
        }

        function CapturaTest($folio,$conImagenes){

            $capturado = $this->checaCaptura($folio);
            if($capturado){
                $facturado = $this->facturado($folio);
                if($facturado){
                    echo '<tr>
                    <th>
                        '.$folio.'
                    </th>                    
                    <th>
                        SIN CAPTURA
                    </th>
                    <th> 
                        EL FOLIO ESTÁ CAPTURADO Y TIENE UNA FACTURA ACTIVA.                                            
                    </th>
                </tr>';
                return 0;                                
                }else{
                    $edicion = $this->edicionDatos($folio);
                    if($edicion==1){
                        echo '<tr>
                                <th>
                                    '.$folio.'
                                </th>                    
                                <th>
                                    CAPTURADO
                                </th>
                                <th> 
                                    LOS DATOS DEL FOLIO SE MODIFICARON CORRECTAMENTE.                                            
                                </th>
                            </tr>';   
                            return 1;            
                    }else{
                                    echo '<tr>
                                <th>
                                    '.$folio.'
                                </th>                    
                                <th>
                                    CAPTURADO
                                </th>
                                <th> 
                                    LOS DATOS NO SE EDITARON YA QUE SON IGUALES.                                            
                                </th>
                            </tr>';   
                            return 0;         
                    }
                }
                
            }else{
                $captura = $this->datosCaptura($folio,$conImagenes);
                return $captura;
            }
  
        }

        /***************************************************************************************************/
        /***************************************************************************************************/
        /*************
        /*************       FUNCION PARA OBTENER DATOS DE CAPTURA
        /*************
        /***************************************************************************************************/
        /***************************************************************************************************/

        function datosCaptura($folio,$conImagenes){

           
            $db = $this->conectarMySQL();

            $sqlCaptura =  "SELECT 
                        Expediente.Cia_clave as compania,
                        CIA_claveMV as EMPClave,
                        Expediente.PRO_clave as producto,
                        PRO_claveMV as PROClave,
                        EXP_nombre as Nombre,
                        EXP_paterno as Paterno,
                        EXP_materno as Materno,
                        Exp_completo as Nombre,
                        CASE WHEN DATE_FORMAT(EXP_fechaNac2, '%d/%m/%Y')  = '00-00-0000' THEN NULL ELSE DATE_FORMAT(EXP_fechaNac2, '%d/%m/%Y')  END as FNacimiento,
                        EXP_edad as Edad,
                        EXP_sexo as Sexo,
                        Expediente.RIE_clave as riesgoAf,
                        RIE_claveMV as RIEClave,
                        RIE_nombre as Riesgo,
                        POS_claveMV as POSClave,
                        opcion as Posicion,
                        EXP_poliza as Poliza,
                        EXP_reporte as Reporte,
                        EXP_siniestro as Siniestro,
                        Expediente.Uni_clave as unidad,
                        Uni1.UNI_claveMV as UNIClave,
                        Uni2.UNI_claveMV as UNIClaveActual,
                        Uni1.UNI_propia as propia,
                        Uni1.UNI_medSinReg as medico,
                        EXP_regCompania as RegCompania,
                        EXP_cancelado as Cancelado,
                        Exp_fecreg as fechaRegistro,
                        Expediente.PRO_cveint as ProTtl,
                        Exp_tipoPase as tipoPase,
                        CASE WHEN DATE_FORMAT(EXP_fecreg, '%d/%m/%Y %H:%i:%s')  = '00-00-0000' THEN NULL ELSE DATE_FORMAT(EXP_fecreg, '%d/%m/%Y %H:%i:%s')  END as FAtencion,
                        CASE WHEN DATE_FORMAT(Exp_fecPaseMed, '%d/%m/%Y')  = '00-00-0000' THEN NULL ELSE DATE_FORMAT(Exp_fecPaseMed, '%d/%m/%Y')  END as FExpedicion,
                        Uni1.LOC_claveint as Localidad,
                        EST_claveint estado,
                        Exp_inciso as Inciso,
                        Exp_orden as Orden,
                        '' as Lesion,
                        '' as Ajustador,
                        '' as Tipo,
                        '' as PagoUnidad,
                        '' as motivoNoPago,
                        REPLACE(REPLACE(ObsNot_expF,char(10),''),char(13),'') as ExpFis,
                        UPPER(MED_nombre + ' ' + MED_paterno + ' ' + MED_materno) as Medico,
                        REPLACE(REPLACE(LesE_clave,char(10),''),char(13),'') as LesRep,
                        REPLACE(REPLACE(ObsNot_obs,char(10),''),char(13),'') as ObsRep,
                        REPLACE(REPLACE(ObsNot_diagnosticoRx,char(10),''),char(13),'/') as Dx,
                        DATE_FORMAT(EXP_fecreg, '%H:%i:%s') as HAtencion,
                        REPLACE(REPLACE(CUR_curaciones ,char(10),''),char(13),'') as Curacion,
                        CASE WHEN Expediente.PRO_clave = 4 THEN (CASE Expediente.UNI_clave WHEN 1 THEN 9437 WHEN 2 THEN 9438 WHEN 3 THEN 9439 WHEN 4 THEN 9440 WHEN 5 THEN 9441 WHEN 6 THEN 9442 WHEN 7 THEN 9443 WHEN 86 THEN 9444 WHEN 184 THEN 9445 WHEN 186 THEN  9446  END)
                        WHEN ISNULL(MED_claveMV) THEN (CASE Expediente.UNI_clave WHEN 1 THEN 3138 WHEN 2 THEN 3145 WHEN 3 THEN 3140 WHEN 4 THEN 3139 WHEN 5 THEN 3146 WHEN 6 THEN 5082 WHEN 7 THEN 3141 WHEN 86 THEN 5373 WHEN 184 THEN 5343 WHEN 186 THEN  5417 END)
                        ELSE Med_claveMV END as MedicoMV,
                        TRI_claveMV as triage,
                        Clave_lesionMV as lesionMV,
                        Fol_ZIMA as folZima,
                        RMO_cveint as ramo
            FROM Expediente INNER JOIN Compania ON Compania.CIA_clave=Expediente.CIA_clave
                                          INNER JOIN Unidad Uni1  ON  Uni1.UNI_clave=Expediente.UNI_clave
                                          INNER JOIN Unidad Uni2  ON  Uni2.Uni_clave=Expediente.Uni_ClaveActual
                                          INNER JOIN Localidad ON Uni1.LOC_claveint = Localidad.LOC_claveint
                                          LEFT   JOIN RiesgoAfectado ON RiesgoAfectado.RIE_clave = Expediente.RIE_clave
                                          LEFT   JOIN NotaMedica ON NotaMedica.EXP_folio = Expediente.EXP_folio
                                          LEFT   JOIN PosicionAcc ON PosicionAcc.id = NotaMedica.Posicion_clave
                                          LEFT   JOIN ObsNotaMed ON ObsNotaMed.EXP_folio = Expediente.EXP_folio
                                          LEFT   JOIN Medico ON Medico.USU_login = NotaMedica.USU_nombre
                                          LEFT   JOIN Curaciones ON Curaciones.EXP_folio = Expediente.EXP_folio
                                          LEFT   JOIN Producto ON Producto.PRO_clave = Expediente.PRO_clave
                                          LEFT   JOIN TriageAutorizacion on TriageAutorizacion.Triage_id = Expediente.Exp_triageActual
                                          LEFT   JOIN RegMVZM ON Expediente.Exp_folio = RegMVZM.Fol_MedicaVial
            WHERE Expediente.EXP_folio = '$folio' ";

            
            $result = $db->query($sqlCaptura);
            $datosCaptura = $result->fetch();

            
            $compania  =  $datosCaptura['compania'];
            $producto  =  $datosCaptura['producto'];
            $localidad =  $datosCaptura['Localidad'];
            $fechaRegistro = $datosCaptura['fechaRegistro'];
            $unidadPropia =$datosCaptura['propia'];
            $riesgoWeb     = $datosCaptura['riesgoAf']; 


            $lesionado = $datosCaptura['Nombre'];
            $ajustador = $datosCaptura['Ajustador'];
            $unidad    = $datosCaptura['unidad'];
            $unidadMv = $datosCaptura['UNIClave'];
            $unidadActual = $datosCaptura['UNIClaveActual'];
            $cliente = $datosCaptura['EMPClave'];
            $productoMv = $datosCaptura['PROClave'];
            $proTTL = $datosCaptura['ProTtl'];
            $reporte = $datosCaptura['Reporte'];
            $estado = $datosCaptura['estado'];
            $folZima = $datosCaptura['folZima'];

            if(!$folZima|| $folZima=='') $folZima='NULL';
            $ramo = $datosCaptura['ramo'];
            if($ramo==3){
                $ramo=2;
            }elseif($ramo==4){
                $ramo=3;
            }elseif(!$ramo || $ramo=='' || $ramo==0){
                $ramo='NULL';
            }
            
            if($reporte==''&&$cliente==19){
                $reporte = 0;
            }
            $tipoPase = $datosCaptura['tipoPase'];
            if($compania==2){
                if($tipoPase!=1&&$tipoPase!=2&&$tipoPase==null){
                    $tipoPase=1;
                }
            }else{
                $tipoPase=0;
            }
            $poliza = $datosCaptura['Poliza'];
            if($poliza==''&&$cliente==19){
                $poliza = 0;
            }
            $inciso = $datosCaptura['Inciso'];
            $siniestro = $datosCaptura['Siniestro'];
            if($siniestro==''&&$cliente==19){
                $siniestro = 0;
            }
            $localidad = $datosCaptura['Localidad'];
            $noOrden = $datosCaptura['Orden'];
            $folioElect = $datosCaptura['RegCompania'];
            if($folioElect==''&&$cliente==19){
                $folioElect = 0;
            }
            $sexo = $datosCaptura['Sexo'];
            $edad = $datosCaptura['Edad'];
            $fechaNac = $datosCaptura['FNacimiento'];
            if($fechaNac=='0000-00-00' || $fechaNac=='00-00-0000' || $fechaNac=='0000/00/00' || $fechaNac=='00/00/0000' ){
                $fechaNac=NULL;
            }
            $fechaExpedicion = $datosCaptura['FExpedicion'];
            $riesgo = $datosCaptura['RIEClave'] == null ? 3 : $datosCaptura['RIEClave'];
            $posicion = $datosCaptura['POSClave'] == null ? 4 : $datosCaptura['POSClave'];
            $primaria = $datosCaptura['lesionMV'];
            // $lesempresa = $datosCaptura['Lesion']['LES_claveEmp'];
            // $tabUnidad = $datosCaptura['tabuladorUnidad'];////// se agrego manual
            // $tabEmpresa = $datosCaptura['claveTabulador'];
            $obsRep = $datosCaptura['ObsRep'];
            $tratante = $datosCaptura['MedicoMV'];
            if(!$tratante) $tratante = $datosCaptura['medico'];
            
            $fechaconsulta = $datosCaptura['FAtencion'];
            $descmed = $datosCaptura['Dx'];
            $lesionunidad = $datosCaptura['Dx'];
            $triage = $datosCaptura['triage'];
            $cedulaElectronica = isset($datosCaptura['cedulaElectronica']) ? $datosCaptura['cedulaElectronica'] : '';

            $tipo = 'Ticket';//////falta agregar html formato o tiket
            // $pagoUnidad = $datosCaptura['pagoUnidad'];//////falta agregar html
            $motivoNoPago = $datosCaptura['motivoNoPago'];//////falta agregar html

            $fecha = date('d/m/Y');

                    $miArray = array();
                    $i=0;
                    $claveLesion = $datosCaptura['lesionMV'];

                    if($claveLesion!=''){

                    if(strlen($descmed)<=225){

                    $queryLesion = "SELECT Clave_lesionCia FROM LesionEquivalencia where Clave_lesionMV='". $claveLesion ."'";

                    $result = $db->query($queryLesion);
                    $datosCaptura = $result->fetch();
                    $lesionCia = $datosCaptura['Clave_lesionCia'];
                    $lesempresa = $lesionCia;

                    // $datoLesion =  DB::connection('mysql')->select($queryLesion)[0];
                    // $lesionCia  = $datoLesion->Clave_lesionCia;

                    // $lesempresa = $lesionCia;

           

                        $miArray[$i]['folio'] = $folio;
                      
                        /********************************************************************************************************/
                        /********************************************************************************************************/
                        /*********************         VALIDACION DE TABULADORES
                        /********************************************************************************************************/
                        /********************************************************************************************************/

                        //AXA completo
                    
                        //AXA completo
       //AXA completo
        if ($compania == 7)
        {
            if ($producto == 12 || $producto == 9)
            {
                if ($localidad == 18) {
                    // if(date($fechaRegistro) >= date('2018-01-01 00:00:00')){
                    //     $clave = 130;   
                    // }else{
                        $clave = 95;
                    // }  
                }elseif (($localidad == 16 || $localidad==47 || $localidad == 77)&& date($fechaRegistro) >= date('2017-09-21 00:00:00')) {
                    $clave = 113;
                }elseif (($localidad == 108 || $localidad==116 || $localidad == 59)&& date($fechaRegistro) >= date('2017-10-02 00:00:00')) {
                    $clave = 114;
                }elseif (($localidad == 53) && date($fechaRegistro) >= date('2017-10-05 00:00:00')) {
                    $clave = 105;
                }elseif (($localidad == 117) && date($fechaRegistro) >= date('2017-10-10 00:00:00')) {
                    $clave = 105;
                }else {
                    $clave = 61; 
                }
            }
            elseif($producto == 15)
            {
                if($localidad == 1 || $localidad == 2 || $localidad == 3){ 
                    $clave = 46;
                }else {
                    $clave = 47;      
                }
            }      
            elseif ($producto == 1)
            {
                if ($unidad == 5){ 
                    $clave = 28;
                }elseif ($localidad != 18){ 
                    $clave = 52;
                }else {
                    $clave = 61;  
                }
            }else{
                $clave = 61;                
            }
        }

        
        //QUALITAS****************************************************************************************************
        //solo DF
        if($compania == 19){

            if ($producto == 1){
                if((date($fechaRegistro) >= date('2020-05-11 00:00:00')) && ($unidadPropia == 'S' || $localidad == 168 || $localidad == 150 || $localidad == 12 || $localidad == 49 || $localidad == 146 || $localidad == 167 || $localidad == 168 || $localidad == 51 || $localidad == 121 || $localidad == 149 || $localidad == 162 || $localidad == 25 || $localidad == 109 || $localidad == 57 || $localidad == 29 || $localidad == 59 || $localidad == 63)){
                    if($unidadPropia == 'S'){
                        $clave = 43; 
                    }elseif($localidad == 39){ //TTL OAXACA
                        $clave = 159; 
                    }elseif($localidad == 19){ //TTL DURANGO
                        $clave = 146;
                    }elseif($localidad == 100){ // TTL TORREON
                        $clave = 149;
                    }elseif($localidad == 105){ // HHNoQx Cancún
                        $clave = 22;
                    }elseif($localidad == 29 || $localidad == 167){ // HHNoQx TOLUCA, GLD. (Z. METRO)
                        $clave = 43;
                    }else{
                        $clave = 49;
                    }
                }
                
                
                //mv puebla
                elseif($localidad==18 && date($fechaRegistro) >= date('2018-10-01 00:00:00')){
                    $clave=52;
                }
                else{
                if ($unidad == 4) {                    
                    if(date($fechaRegistro) >= date('2016-12-05 00:00:00')){
                        $clave = 28;
                    }else{
                        $clave = 7;
                    }
                //mv monterrey
                }elseif ($localidad == 57 ) { //tlaxcala                    
                    if(date($fechaRegistro) >= date('2020-03-02 00:00:00')){
                        $clave = 76;
                    }elseif(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                        $clave = 122;
                    }
                }elseif ($unidad == 5) {                    
                    $clave = 28;
                //Sasc Ixtapaluca y es mayor a 1 de noviembre 2016
                }elseif ( ($unidad == 110 || $unidad == 266 || $unidad == 65 || $unidad == 281) && date($fechaRegistro) >= date('2016-11-01 00:00:00')) {
                    $clave = 108; //sames
                }elseif ($localidad == 167) { //guadalajara zm
                    if (date($fechaRegistro) >= date('2019-04-10 00:00:00')) {
                        $clave=145;
                    }elseif(date($fechaRegistro) >= date('2017-10-26 00:00:00')){
                        $clave = 40;
                    }                    
                }elseif ($localidad == 53  && date($fechaRegistro) >= date('2017-05-08 00:00:00')) { //villahermosa
                    $clave = 11;
                }elseif ($localidad == 51  && date($fechaRegistro) >= date('2017-05-08 00:00:00')) { //Hermosillo
                    $clave = 24;
                }elseif ($localidad == 103  && date($fechaRegistro) >= date('2017-06-05 00:00:00')) { //cordoba
                    $clave = 116;
                }elseif ($localidad == 121  && date($fechaRegistro) >= date('2016-12-05 00:00:00')) { //iguala
                    $clave = 116;
                }elseif ($localidad == 155  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //lagos de moreno
                    $clave = 122;
                }elseif ($localidad == 128) { //atotonilco
                    if(date($fechaRegistro) >= date('2020-03-02 00:00:00')){
                        $clave = 76;
                    }elseif(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                        $clave = 122;
                    }                    
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
                }elseif ($localidad == 157  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //san juan del río
                    $clave = 122;
                }elseif ($localidad == 57 ) { //tlaxcala                    
                    if(date($fechaRegistro) >= date('2020-03-02 00:00:00')){
                        $clave = 76;
                    }elseif(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                        $clave = 122;
                    }
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
                }elseif ($localidad == 49  && date($fechaRegistro) >= date('2018-01-15 00:00:00')) { //culiacan
                    $clave = 126;
                }elseif ($localidad == 39 ) { //oaxaca
                    if(date($fechaRegistro) >= date('2020-03-05 00:00:00')){
                        $clave = 159;
                    }elseif(date($fechaRegistro) >= date('2018-10-11 00:00:00')){
                        $clave = 142;
                    }                
                }elseif ($localidad == 25) { //Pachuca
                    if(date($fechaRegistro) >= date('2020-03-11 00:00:00')){
                        $clave = 126;
                    }elseif (date($fechaRegistro) >= date('2018-09-01 00:00:00')) {
                        $clave = 110;
                    }    
                }elseif ($localidad == 109) { //tehuacan
                    if(date($fechaRegistro) >= date('2020-03-02 00:00:00')){
                        $clave = 76;
                    }elseif(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                        $clave = 122;
                    }                    
                }elseif ($localidad == 168 ) { //apizaco
                    if(date($fechaRegistro) >= date('2020-03-02 00:00:00')){
                        $clave = 76;
                    }elseif(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                        $clave = 122;
                    }                    
                }elseif ($localidad == 100 ) { //torreon
                    if(date($fechaRegistro) >= date('2019-10-14 00:00:00')){
                        $clave = 149;
                    }elseif(date($fechaRegistro) >= date('2018-11-23 00:00:00')){
                        $clave = 110;
                    }else{
                        $clave = 61;
                    }
                }elseif ($localidad == 12  && date($fechaRegistro) >= date('2018-11-23 00:00:00')) { //Oaxaca
                    $clave = 52;
                }elseif ($localidad == 33  && date($fechaRegistro) >= date('2018-08-01 00:00:00')) { //cuernavaca
                    $clave = 110;
                }elseif ($localidad == 147) { //tehuantepec
                    if( $unidad == 120){
                        if(date($fechaRegistro) >= date('2018-01-15 00:00:00')){
                            $clave = 122;
                        }else{
                            $calve = 63;
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
                }elseif ($localidad == 35) { //Tepic
                    if (date($fechaRegistro) >= date('2020-04-01 00:00:00')) {
                        $clave = 165;    
                    }else{
                        $clave = 63;    
                    }   
                }
                else{                    
                    $clave = 63;
                }
            }

            //No Qx
            }elseif($producto == 9){
                if(date($fechaRegistro) >= date('2020-05-11 00:00:00')){
                    if($unidadPropia == 'S'){
                        $clave = 43; 
                    }elseif($localidad == 39){ //TTL OAXACA
                        $clave = 159; 
                    }elseif($localidad == 19){ //TTL DURANGO
                        $clave = 146;
                    }elseif($localidad == 100){ // TTL TORREON
                        $clave = 149;
                    }elseif($localidad == 105){ // HHNoQx Cancún
                        $clave = 22;
                    }elseif($localidad == 29 || $localidad == 167){ // HHNoQx TOLUCA, GLD. (Z. METRO)
                        $clave = 43;
                    }else{
                        $clave = 49;
                    }
                }
                //si son unidades de red especificos o propias de DF 
                elseif (  ( ( $unidad == 232 || $unidad == 125 || $unidad == 249 || $unidad == 301 || $unidad == 348  || ( $localidad == 18 && $unidadPropia == 'S') ) ) ) { 
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
        }


        // ABA tabulador completo
        if ($compania == 1)
        {
            if ($producto == 1) {                                
                
                if ($unidadPropia == 'S')
                {
                    if($unidad == 5){
                        if (date($fechaRegistro) >= date('2020-04-01 00:00:00')){
                            $clave=7;
                        }
                        elseif (date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
                            $clave=40;
                        }else{
                            $clave = 28;
                        }
                    }                     
                    else{
                        if (date($fechaRegistro) >= date('2020-04-01 00:00:00')){
                            $clave=124;
                        }
                        elseif (date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
                            $clave=100;
                        }else{
                            $clave = 82;    
                        }                        
                    }                           
                }else{
                    if (date($fechaRegistro) >= date('2020-04-01 00:00:00')){
                        $clave=61;
                    }
                    elseif(date($fechaRegistro) >= date('2019-06-01 00:00:00') && $unidad==337){
                        $clave=33;
                    }
                    if (date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
                        $clave = 61;
                    }else{
                        $clave = 102;           
                    }
                }                  
            }else{
                $clave = 61;
            }
        }



        //AFIRME completo
	   if ($compania == 2)
       {   
           
           if ($producto == 1){
               if(date($fechaRegistro) >= date('2019-06-12 00:00:00')&&$tipoPase==1){                    
                       if($localidad==18){
                           if($unidadPropia=='S'){
                               $clave=147;
                           }else{
                               $clave=12;
                           }
                       }elseif($localidad==29){
                           if($unidad==295 || $unidad == 218){
                               $clave =147;
                           }else{
                               $clave=12;
                           }
                       }elseif($localidad==77 || $localidad==41 || $localidad==61){
                           $clave=5;
                       }else{
                           if($localidad==105&&date($fechaRegistro) >= date('2019-09-18 00:00:00')){
                            $clave=148;
                           }elseif($unidad==5&&date($fechaRegistro) >= date('2019-07-13 00:00:00')){
                             $clave=28;
                           }else{
                             $clave=11;
                           }                           
                       }
               }elseif(date($fechaRegistro) >= date('2019-06-19 00:00:00') && $tipoPase==2){
                   if($localidad==18){
                       if($unidadPropia=='S'){
                           $clave=5;
                       }else{
                           $clave=37;
                       }
                   }elseif($localidad==29){
                       
                           $clave =15;
                       
                   }elseif($localidad==169){

                       if(date($fechaRegistro) >= date('2019-07-10 00:00:00')){
                        if($unidadPropia=='S'){
                            $clave=28;
                        }else{
                            $clave=11;
                        }
                       }else{
                        if($unidadPropia=='S'){
                            $clave=20;
                        }else{
                            $clave=38;
                        }

                       }                           
                        
                    }elseif($localidad==51||$localidad==43||$localidad==99){

                        if(date($fechaRegistro) >= date('2019-07-10 00:00:00')){                         
                             $clave=11;                     
                        }else{
                            if($unidadPropia=='S'){
                                $clave=20;
                            }else{
                                $clave=38;
                            }                   
                        }                           
                         
                    }elseif($localidad==47){

                        if(date($fechaRegistro) >= date('2019-07-10 00:00:00')&&$unidad==7){                         
                             $clave=11;                     
                        }else{
                            if($unidadPropia=='S'){
                                $clave=20;
                            }else{
                                $clave=38;
                            }                   
                        }                           
                         
                    }elseif($localidad==105 ){

                        if(date($fechaRegistro) >= date('2019-09-18 00:00:00')){                         
                             $clave=148;                     
                        }else{
                            if($unidadPropia=='S'){
                                $clave=20;
                            }else{
                                $clave=38;
                            }                   
                        }                           
                         
                    }elseif($localidad==12 || $localidad==49 || $localidad==100 || $localidad==117){

                        if(date($fechaRegistro) >= date('2019-08-22 00:00:00')){                         
                             $clave=11;                     
                        }else{
                            if($unidadPropia=='S'){
                                $clave=20;
                            }else{
                                $clave=38;
                            }                   
                        }                           
                         
                    }else{
                       if($unidadPropia=='S'){
                           $clave=20;    
                       }else{
                           $clave=38;
                       }
                   }                
               }elseif($tipoPase==2){
                   if($unidadPropia=='S'){
                       $clave=20;
                   }else{
                       $clave=38;
                   }
               }else{
                   if($tipoPase==1){
                    $clave=11;
                   }else{
                        if($unidadPropia=='S'){
                            $clave=20;
                        }else{
                            $clave=32;
                        }
                   }
                   
               }                                                                                           
           }else{
               $clave = 61;
           }
                       
       }



        // AGUILA completo
        if ($compania == 3)
        {
            if ($producto == 9)
            {
                if(($localidad==76 || $localidad == 18 || $localidad == 29 || $localidad == 81) && (date($fechaRegistro) >= date('2018-05-01 00:00:00'))){ 
                    $clave = 22;
                }
                elseif($localidad == 18 || $localidad == 29 || $localidad == 81){ 
                    $clave = 96;
                }elseif(($localidad == 105) && (date($fechaRegistro) >= date('2018-10-15 00:00:00'))){ 
                    $clave = 136;
                }else {
                    $clave = 61;
                }
            }
            elseif ($producto == 1)
            {     
                if(date($fechaRegistro) >= date('2018-05-01 00:00:00')){
                    $clave = 131;    
                }           
                elseif(date($fechaRegistro) >= date('2017-05-01 00:00:00')){
                    $clave = 37;    
                }else{
                    $clave = 4;     
                }                
            }else {
                $clave = 61;     
            }
        }

        // AIG completo
        if($compania == 4)
        {
            switch($localidad){
                case 1://Aguascalientes
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150;
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case 18://Ciudad de méxico
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150;
                    }else{
                        $clave = 96;
                    }
                break;
                case 16://Chihuahua
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case 19: //Durango
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case 29:  //Toluca
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150;
                    }else{
                        $clave = 96;
                    }
                break;
                case  74:  //León
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case 39:  //Oaxaca
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case  43:  //Querétaro
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case  167: //Gdl.
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case 169: //Mty.
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case  41:  //Puebla
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case  47:  //SLP
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case  77:  //Veracruz
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-09-01 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case  182:  //Tlaquepaque
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-11-11 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                case 85:  //Zapopan
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-11-11 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                case 102:  //Boca del Rio
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150; 
                    }elseif(date($fechaRegistro) >= date('2019-11-11 00:00:00')){
                        $clave = 96;
                    }else{
                        $clave = 106;
                    }
                break;
                default:
                    if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                        $clave=150;
                    }else{
                        $clave = 96;
                    }
                break;

            }
            
        }

        // ANA completo  TABULADOR TTL 115 13-11-2017
        if ($compania == 5)
        {
            if($proTTL!=0){
                if($proTTL==4&&date($fechaRegistro) >= date('2016-11-13 00:00:00')&&$localidad == 18){
                    $clave = 115;
                }
            }else{
                if ($producto == 1)
                {   
                    if (date($fechaRegistro) >= date('2020-04-01 00:00:00')) {
                        if ($unidadPropia == 'S') {
                            $clave=164;
                        } else {
                            $clave=163;
                        }                        
                    }elseif(date($fechaRegistro) >= date('2018-01-08 00:00:00')) {
                        if ($unidadPropia == 'S') {
                            $clave=43;
                        } else {
                            $clave=12;
                        }                        
                    } 
                    elseif ($unidadPropia == 'S')
                    {
                        if ($unidad == 5) {
                            $clave = 28;  
                        }
                        else {
                            $clave = 5;  
                        }
                    }
                    else
                    {
                        $clave = 6;  
                    }  
                }
                else{
                    $clave = 61;  
                }
            }
        }

        // ATLAS completo
        if ($compania == 6)
        {
            if ($producto == 2){
                    if (($unidad==1||$unidad==3||$unidad==184)&&(date($fechaRegistro) >= date('2018-01-01 00:00:00'))){ 
                        $clave = 162;
                    }elseif(date($fechaRegistro) < date('2018-01-01 00:00:00')){
                        $clave = 104;
                    }else{
                        $clave = 61;
                    }
            }elseif ($producto == 1){
                if ($unidadPropia == 'S'){
                    if(date($fechaRegistro) > date('2020-04-15 00:00:00')) {
                        if ($unidad==1 || $unidad==3 || $unidad==184 || $unidad==2) {
                            $clave = 40;
                        } else {
                            $clave = 166;
                        }
                    }elseif (date($fechaRegistro) > date('2018-07-16 00:00:00')) {
                        if ($unidad==1 || $unidad==3 || $unidad==184 || $unidad==2) {
                            $clave = 28;
                        } else {
                            $clave = 73;
                        }
                        
                    }elseif(date($fechaRegistro) > date('2018-02-01 00:00:00')) {
                        $clave = 73;
                    }else{
                        $clave = 73;
                    }                                         
                }else{
                    if (date($fechaRegistro) > date('2020-04-15 00:00:00')) {
                        if($localidad == 105){
                            $clave = 6;
                        }else{
                            $clave = 49;
                        }
                    }elseif ($localidad == 105 && date($fechaRegistro) > date('2019-04-16 00:00:00')) {
                        $clave = 6;
                    }elseif (date($fechaRegistro) < date('2018-02-01 00:00:00')) {
                        $clave = 15;
                    }else{
                        $clave = 74;
                    }                    
                }
            }else{
                $clave = 61;
            }
        }

        // FUTV SECC1
        if ($compania == 39)
        {
            if ($producto == 1)
            {
                $clave = 1;
            } 
            else {
                $clave = 61;
            }
        }

        // FUTV SECC2
        if ($compania == 40)
        {
            if ($producto == 1)
            {
                $clave = 1;
            } 
            else {
                $clave = 61;
            }
        }

        // GENERAL 
        if ($compania == 9)
        {
            if ($producto == 1){
                if(date($fechaRegistro) > date('2020-03-15 00:00:00')){
                    if($unidadPropia == 'S' && $localidad==18){
                        $clave = 2;
                    }elseif($unidadPropia == 'S' && $localidad!=18) {
                        $clave = 129;
                    }  else{
                        $clave = 158;
                    }
                }elseif(date($fechaRegistro) > date('2018-03-01 00:00:00')){
                    if ($unidadPropia == 'S' && $localidad==18) {
                        $clave = 11;
                    } else {
                        $clave = 129;
                    }                   
                }elseif($riesgoWeb == 3 || $riesgoWeb == 4 || $riesgoWeb == 5){

                    if ($unidadPropia == 'S'){ 
                        $clave = 98;
                    }else{
                        $clave = 99;
                    }

                }else{
                    $clave = 79;
                } 
            
            }else{ 
                $clave = 61; 
            }
        }

        // GNP todos
        if ($compania == 10)
        {
            if ($producto == 1)
            {
                if ($unidadPropia == 'S')
                {

                    if(date($fechaRegistro) >= date('2018-01-16 00:00:00')){
                        $clave = 61;
                    }elseif ($unidad == 4){ 
                        $clave = 28;
                    }elseif (($unidad==1||$unidad==184||$unidad==3||$unidad==2)&&date($fechaRegistro) >= date('2016-11-10 00:00:00')) {
                        $clave = 107;
                    }
                    else{ 
                        $clave = 100;
                    }

                }else{
                    if(date($fechaRegistro) >= date('2020-05-01 00:00:00') && $localidad!=100){
                            $clave=167;
                    }elseif(date($fechaRegistro) >= date('2019-12-23 00:00:00') && $localidad==100){
                            $clave=151;
                    }elseif(date($fechaRegistro) >= date('2018-01-16 00:00:00')){
                        if (($unidad==70||$unidad==90||$unidad==97||$unidad==220||$unidad==244)) {
                            $clave = 141;
                        }else{
                            $clave = 61;
                        }
                    }elseif(date($fechaRegistro) >= date('2018-01-16 00:00:00')){
                        if (($unidad==70||$unidad==90||$unidad==97||$unidad==220||$unidad==244)) {
                            $clave = 128;
                        }else{
                            $clave = 61;
                        }
                    }else{
                        $clave =  76;                 
                    }
                }            

            }else{

                $clave = 61;

            }
        }


        // HDI

        // if($compania == 12)
        // {
        //     if($producto == 1)
        //     {
        //         if($unidadPropia == 'S') {
        //             if (date($fechaRegistro) >= date('2020-05-01 00:00:00')) {
        //                 $clave=20;
        //             }elseif (date($fechaRegistro) >= date('2018-08-01 00:00:00')) {
        //                 $clave=132;
        //             }
        //             elseif (date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
        //                 $clave=40;
        //             }elseif(date($fechaRegistro) >= date('2017-03-15 00:00:00')){
        //                 if($unidad == 5){
        //                     $clave=109;
        //                 }else{
        //                     $clave=28;
        //                 }
        //             }else{
        //                 $clave = 76;
        //             }
        //         }else {
        //             if(date($fechaRegistro) >= date('2020-05-01 00:00:00')){
        //                 if($localidad==51){
        //                     $clave=74;
        //                 }else{
        //                     $clave=127;
        //                 }
        //             }elseif(date($fechaRegistro) >= date('2019-04-24 00:00:00') && $localidad==51){
        //                 $clave=74;
        //             }elseif (date($fechaRegistro) >= date('2018-08-01 00:00:00')) {
        //                 if ($unidad == 83 || $unidad == 310) {
        //                     $clave=109;
        //                 }else{
        //                     $clave=102;
        //                 }
                        
        //             }
        //             elseif (date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
        //                 $clave=102;
        //             }elseif(date($fechaRegistro) >= date('2017-03-15 00:00:00')){
        //                 $clave = 110;
        //             }else{                              
        //                 $clave = 55;                              
        //             }
        //         }

        //     }else {
        //        $clave = 61;  
        //     }
        // }


        //HDI Nuevo 

        if($compania == 12){
            if (date($fechaRegistro) >= date('2020-05-01 00:00:00')){
                switch($localidad){
                    case 105:
                        $clave = 12;   //04/05/2020 08:44
                    break;
                    case 115:
                        $clave = 12;   //04/05/2020 08:44
                    break;
                    case 18: //Cd. Mex. (Z. Metro)
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 16: //Chihuahua
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 33:              //Cuernavaca
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 49:              //Culiacán
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 19:              //Durango
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 146:          //Ensenada
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 167:          //Gdl. (Z. Metro)
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 51:              //Hermosillo
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 121:          //Iguala
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 80:              //Irapuato
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 74:              //León
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 149:          //Mazatlán
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 61:              //Mérida
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 3:                 //Mexicali
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 107:          //Minatitlán
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 99:              //Morelia
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case  169:          //Mty. (Z. Metro)
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 162:          //Nuevo Laredo
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 39:              //Oaxaca
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 116:          //Orizaba
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 41:              //Puebla
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 41:              //Puebla
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 43:              //Querétaro
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 131:          //Reynosa
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 47:              //San Luis Potosí
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 148:          //Tapachula
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 109:          //Tehuacán
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 35:              //Tepic
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 117:          //Tijuana
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 57:              //Tlaxcala
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 29:              //Toluca
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 100:          //Torreón
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 156:          //Tulancingo
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 14:              //Tuxtla Gutiérrez
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 77:              //Veracruz
                        if($unidadPropia == 'S'){
                            $clave = 43;   //04/05/2020 08:44
                        }else{
                            $clave = 12;   //04/05/2020 08:44  
                        }
                    break;
                    case 53:              //Villahermosa
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 59:              //Xalapa
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    case 63:              //Zacatecas
                        $clave = 12;   //04/05/2020 08:44  
                    break;
                    default:              //Zacatecas
                        if($unidadPropia == 'S'){
                            $clave = 20;   //04/05/2020 08:44
                        }else{
                            $clave = 127;   //04/05/2020 08:44  
                        }
                    break;                        
                }
            }elseif($unidadPropia == 'S'){
                if(date($fechaRegistro) >= date('2018-08-01 00:00:00')){
                    $clave = 132; 
                }elseif(date($fechaRegistro) >= date('2018-02-01 00:00:00')){
                    $clave = 140; 
                }elseif(date($fechaRegistro) >= date('2017-03-15 00:00:00')){
                    if($unidad == 105){
                        $clave = 109;   //04/05/2020 08:44
                    }else{
                        $clave = 28;   //04/05/2020 08:44  
                    }
                }elseif(date($fechaRegistro) >= date('2015-03-15 00:00:00')){
                    $clave = 76; 
                }elseif(date($fechaRegistro) >= date('2012-04-01 00:00:00')){
                    $clave = 11; 
                }else{
                    $clave = 2; 
                }
            }else{
                if(date($fechaRegistro) >= date('2019-04-24 00:00:00') && $localidad == 51){
                    $clave = 2;  
                }elseif(date($fechaRegistro) >= date('2018-08-01 00:00:00')){
                    if($unidad==173 || $unidad==359){
                        $clave = 109; 
                    }else{
                        $clave = 102; 
                    }
                }elseif(date($fechaRegistro) >= date('2018-02-01 00:00:00')){
                    $clave = 102; 
                }elseif(date($fechaRegistro) >= date('2017-03-15 00:00:00')){
                    $clave = 110; 
                }elseif(date($fechaRegistro) >= date('2015-03-15 00:00:00')){
                    $clave = 55; 
                }elseif(date($fechaRegistro) >= date('2012-04-01 00:00:00')){
                    $clave = 33; 
                }else{
                    $clave = 15; 
                }
            }
        }


        // GRUPO CALEB
        if($compania == 57)
        {
            if($producto == 1){
                $clave = 49; 
            }else{
                $clave = 61;
            }
        }


        // BANORTE
        if ($compania == 8)
        {
                // if(($localidad==100||$localidad==16||$localidad==19)&&date($fechaRegistro) >= date('2020-03-01 00:00:00')){
                //     $clave = 157;
                // }elseif($localidad==41&&date($fechaRegistro) >= date('2019-09-18 00:00:00')){
                if($localidad==41&&date($fechaRegistro) >= date('2019-09-18 00:00:00')){
                    $clave = 24;
                }elseif($localidad==77&&date($fechaRegistro) >= date('2017-12-04 00:00:00')&&date($fechaRegistro) <= date('2018-02-28 23:59:59')){
                    $clave = 117;
                }else{
                    if ($unidadPropia == 'S'){
                        if (date($fechaRegistro) >= date('2019-01-16 00:00:00')) {
                            $clave = 11;
                        }else{
                           $clave = 88;  
                        }              
                    }
                    else{ 
                       if (date($fechaRegistro) >= date('2019-01-16 00:00:00')) {
                            $clave = 33;
                        }else{
                            $clave = 76;
                        } 
                    }    
                
            }   
           
        }


        // HIR
        if ($compania == 31)
        {
            if($producto == 2)
            {
                if(date($fechaRegistro) >= date('2019-10-15 00:00:00')){
                    $clave = 49;                              
                }else{
                    $clave = 65;                              
                }
            }
            else{
                $clave = 61;                 
            }
        }

        // LATINO
        if ($compania == 14)
        {
            if($producto == 1)
            { 
                if($localidad==105 && date($fechaRegistro) >= date('2019-06-01 00:00:00') ){
                    $clave=22;
                }else{

                    if (date($fechaRegistro) >= date('2018-01-08 00:00:00')) {
                        if($unidadPropia == 'S'){
                            $clave=43;
                        }else{
                            $clave=12;
                        }    
                    }                
                    elseif($unidadPropia == 'S'){ $clave = 5;}
                    else{ $clave = 6;}
                }
            }
            elseif($producto == 2)
            { 
                if($unidadPropia == 'S' ){ 
                    if(date($fechaRegistro) >= date('2020-03-16 00:00:00')){
                        $clave = 52; 
                    }elseif(date($fechaRegistro) >= date('2019-02-13 00:00:00')){
                        $clave = 38;    
                    }else{
                        $clave =120;
                    }
                }
                else{
                    if(date($fechaRegistro) >= date('2020-03-16 00:00:00')){
                        if ($localidad==105) {
                            $clave=163;
                        }else{
                            $clave =162;
                        }
                    }elseif(date($fechaRegistro) >= date('2019-02-13 00:00:00')){
                        if ($localidad==105) {
                            $clave=12;
                        }else{
                            $clave =143;
                        }
                    }else{
                        $clave = 61;
                    }    
                }    
                    
            }
            else{
                $clave = 61;                 
            }
        }

        // MAPFRE
        if ($compania == 15)
        {
            if($producto == 1)
            { 
                if($unidadPropia == 'S'){ 

                    if($localidad==167){
                        if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-02 00:00:00')){
                            $clave = 133;
                        }
                        
                    }elseif ($localidad==41 ) {
                         if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-09 00:00:00')){
                            $clave = 134;
                        }                      
                    }elseif ($localidad==169) {
                         if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-23 00:00:00')){
                            $clave = 134;
                        }   
                    }elseif ($localidad==77) {
                         if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-16 00:00:00')){
                            $clave = 135;
                        }   
                    }elseif ($localidad==16 && date($fechaRegistro) >= date('2019-04-01 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==29 && date($fechaRegistro) >= date('2019-04-01 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==74 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==61 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==43 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==47 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==1 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }
                    else{
                        $clave = 21;
                    }                
                }
                else{ 
                     if($localidad==167){
                        if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-02 00:00:00')){
                            $clave = 133;
                        }
                        
                    }elseif ($localidad==41 ) {
                         if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-09 00:00:00')){
                            $clave = 134;
                        }                      
                    }elseif ($localidad==169) {
                         if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-23 00:00:00')){
                            $clave = 134;
                        }   
                    }elseif ($localidad==77) {
                         if(date($fechaRegistro) >= date('2019-04-01 00:00:00')){
                            $clave = 144;
                        }elseif(date($fechaRegistro) >= date('2018-07-16 00:00:00')){
                            $clave = 135;
                        }   
                    }elseif ($localidad==16 && date($fechaRegistro) >= date('2019-04-01 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==29 && date($fechaRegistro) >= date('2019-04-01 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==74 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==61 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==43 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==47 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }elseif ($localidad==1 && date($fechaRegistro) >= date('2019-04-05 00:00:00')) {
                         $clave=144;
                    }
                    else{
                        $clave = 24;
                    }                
                }                                            
             }                                               
        }



        // METLIFE
        if($compania == 55){

            if($producto == 2){
                $clave = 97;                              
            }
            else{
                $clave = 61;                 
            }
        }

        // MULTIASISTENCIA

        if ($compania == 35 || $compania == 36 || $compania == 37)
        {
            if($producto == 1)
            {
                
                if (date($fechaRegistro) >= date('2019-04-06 00:00:00')) {
                    $clave=2;
                }
                elseif(date($fechaRegistro) >= date('2018-04-09 00:00:00')){
                    $clave = 11;                                  
                }else{
                    $clave = 103;                                  
                }                      
            }
            else{
                $clave = 61;                 
            }

        }

        // MULTIVA  BX+                            
        if ($compania == 17)
        {
            if($producto == 1)
            {
                if($localidad==105 && date($fechaRegistro) >= date('2018-12-01 00:00:00') ){
                    $clave = 139;                              
                }elseif(date($fechaRegistro) >= date('2018-01-16 00:00:00')){
                    $clave = 12;                              
                }else{
                    $clave = 105;                              
                }
            }
            else
            {
                $clave = 61;                 
            }
        }
        // POTOSI                   
        if ($compania == 18)
        {
            if($producto == 1)
            {
                if($unidadPropia == 'S'){ $clave = 67;}
                else {$clave = 68;}
            }
            elseif ($producto == 2)
            {
                if($unidadPropia == 'S'){ $clave = 67;}
                else{ $clave = 68;}                              
            }  
            else
            {
               $clave = 61;  
            }
          
        }

        // PRIMERO               
        if ($compania == 22)
        {
            if($producto == 1)
            {
                if($unidadPropia == 'S')
                {
                    if($unidad==5 && date($fechaRegistro) >= date('2018-11-01 00:00:00')){
                        $clave = 7;
                    }elseif (date($fechaRegistro) >= date('2019-01-07 00:00:00')) {
                        $clave = 5;
                    }else{
                        $clave = 2;  
                    }                    
                }else{
                    if (date($fechaRegistro) >= date('2019-01-07 00:00:00')) {
                        $clave = 140;       
                    }else{
                        $clave = 6;    
                    }                    
                }                              
            }
            else
            {
                $clave = 61;                 
            }

        }



        // SURA                     

        if ($compania == 20)
        {
            if($producto == 1)
            {
                if(date($fechaRegistro) >= date('2018-01-01 00:00:00')){
                    if ($unidadPropia == 'S') {
                        $clave=5;
                    } else {
                        $clave=15;
                    }                    
                }
                elseif( $localidad == 18 || $localidad == 169 || $localidad == 41 || $localidad == 47 || $localidad == 61 )
                { 
                    $clave = 33;
                }
                else{
                    $clave = 43;                                
                } 
            }
            elseif ($producto == 2)
            {
                if(date($fechaRegistro) >= date('2018-01-01 00:00:00')){
                    $clave=38;
                }else{
                    $clave = 21;    
                }
                
            }
            else
            {
               $clave = 61;                 
            }

        }

        //SIAM
        if ($compania == 58 || $compania == 59)
        {
            if($producto == 16||$producto == 15)
            {
                $clave = 5;                              
            }
            else{
                $clave = 61;                 
            }

        }

        //SINERGIA
        if ($compania == 61)
        {
            if($producto == 1)
            {
                $clave = 18;                              
            }            
        }


        // SPT
        if ($compania == 32)
        {
            if($producto == 1)
            {
                $clave = 1;                              
            }
            else{
                $clave = 61;                 
            }

        }


        // TTRAVOL
        if ($compania == 34)
        {
            if($producto == 1)
            {
                $clave = 1;                              
            }
            else
            {
                $clave = 61;                 
            }
        }




        // INBURSA
        if ($compania == 45)
        {
            if($producto == 1)
            {
                
                if($unidadPropia == 'S'){ 
                    if (date($fechaRegistro) >= date('2020-03-15 00:00:00')) {
                        $clave= 161;  
                    }elseif(date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
                        $clave= 110;  
                    }else{                     
                        $clave= 75;
                    }
                }else{ 
                    if(date($fechaRegistro) >= date('2020-03-15 00:00:00')) {
                        $clave= 43;  
                    }elseif(date($fechaRegistro) >= date('2018-02-01 00:00:00')) {
                        $clave= 127;
                    }else{
                        $clave= 76;
                    }
                }
            }    
            elseif ($producto == 4)
            {
              // if (Sesiones > 1) {$clave= 89;}
              // if(Consulta) {$clave = 90;}
              // if(Num Placas = 1) {$clave=93;}
              // else {$clave=94;}
              // $clave=94;
            }      
            else{
               $clave = 61;                 
            }
          
        }

        // GOA
        if ($compania == 11)
        {
            if($producto == 1)
            {
                $clave = 84;                              
            }
            elseif (($producto==9&&$localidad==47)&& date($fechaRegistro) >= date('2017-09-08 00:00:00')) {
                $clave = 112;
            }
            else
            {
                $clave = 61;                 
            }
        }

        //ZURICH
        if ($compania == 21)
        {
            if($producto == 1)
            {
                if(date($fechaRegistro) >= date('2018-01-08 00:00:00')){
                    $clave = 33;
                }else{
                    $clave = 2;                                 
                }                
            }           
        }

        //ANAVE
        if ($compania == 62)
        {
            if($producto == 1)
            {
                $clave = 1;                              
            }else{
                $clave = 61;
            }           
        }

        //ACE
        if ($compania == 33)
        {
            if($producto == 1)
            {
                $clave = 1;                              
            }else{
                $clave = 61;
            }           
        }

        //anahuac
        if ($compania == 65)
        {
            if($producto == 2)
            {
                if(date($fechaRegistro) >= date('2020-01-01 00:00:00')){
                    if($localidad==105){
                        $clave=154;
                    }else{
                        $clave=153;
                    }
                }else{
                    $clave = 52;                              
                }
                
            }           
        }

        //flecha roja
        if($compania == 69){
            if($unidadPropia == 'S'){ 
                $clave=5;
            }else{
                $clave=12;
            }
        }

        //Crabi 
        if($compania == 67){
            if($unidadPropia == 'S'){ 
                $clave=2;
            }else{
                if($estado==14){
                    $clave=5;
                }else{
                    $clave=153;
                }
                
            }
        }

        //ATN MED SEG 
        if($compania == 63){
            $clave=5;
        }
       

                                // verificamos si entro a alga condicion
                                if ($clave == 0) {
                                                                        
                                    $respuesta = 'sinTabulador';
                                    $miArray[$i]['lesionCia']='sin tabulador';
                                }else{
                                    
                                    $miArray[$i]['lesionCia']=$lesionCia;

                                    $query1="SELECT TAD_importe FROM TabuladorDetalle WHERE TAB_clave = " . $clave . " AND LES_clave='".$lesionCia."'";
                                    
                                     $result = $db->query($query1);
                                        $Dimporte = $result->fetch();
                                        $importe = $Dimporte['TAD_importe'];

                                    // $importe = DB::connection('mysql')->select($query)[0]->TAD_importe;

                                    $miArray[$i]['importe']=$importe;
                                    $miArray[$i]['clave']= $clave;
                                    $miArray[$i]['producto']= $producto;
                                   

                                    
                                    
                                
                                }


                                $dbServ = $this->conectarSQLServer();
                                

                        /********************************************************************************************************/
                        /********************************************************************************************************/
                        /*********************         FIN VALIDACION DE TABULADORES
                        /********************************************************************************************************/
                        /********************************************************************************************************/

                                $sql = "EXEC MV_CAP_GuardaCapturaWeb
                                    @folio = '$folio',
                                    @folioZima = $folZima,
                                    @ramo = $ramo,
                                    @CapAut = 1,
                                    @lesionado = '$lesionado',
                                    @unidad = $unidadMv,
                                    @cliente = $cliente,
                                    @fechaRecep = '$fecha',
                                    @usuario = 1,
                                    @producto = $productoMv,
                                    @reporte = '$reporte',
                                    @poliza = '$poliza',
                                    @inciso = '$inciso',
                                    @siniestro = '$siniestro',
                                    @asegurado = '',
                                    @ajustador = '',
                                    @localidad = $localidad,
                                    @matricula = '',
                                    @ROC = 0,
                                    @autorizacion = '$cedulaElectronica',
                                    @noOrden = '$noOrden',
                                    @folioElect = '$folioElect',
                                    @sexo = '$sexo',
                                    @edad = $edad,
                                    @fechaNac = '$fechaNac',
                                    @fechaExpedicion = '$fechaExpedicion',
                                    @lesionesAparentes = '',
                                    @observaciones = '',
                                    @riesgo = $riesgo,
                                    @posicion = $posicion,
                                    @tipo = '$tipo',
                                    @clienteRef = '1',
                                    @ref = '',
                                    @factor = 1,
                                    @iva = 0.16,
                                    @primaria = '$primaria',
                                    @lesempresa = '$lesempresa',
                                    @tabUnidad = 21,
                                    @tabEmpresa = $clave,
                                    @deducible = 0,
                                    @identificacion = '',
                                    @obsRep = '$obsRep',
                                    @semanas = $unidadActual,
                                    @tratante = $tratante,
                                    @fechaconsulta = '$fechaconsulta',
                                    @secundaria = '$tipoPase',
                                    @terciaria = NULL,
                                    @descmed = '".strtoupper($descmed)."',
                                    @lesionunidad = '$lesionunidad',
                                    @lesionesPost = '',
                                    @expFisica = '',
                                    @pagoUnidad = 1,
                                    @motivoNoPago = '',
                                    @curacion = '',
                                    @facExpress = 0,
                                    @escolaridad = NULL,
                                    @colegio = NULL,
                                    @triage = $triage,
                                    @validado = 0,
                                    @bitacora = '',
                                    @tiempoCap = 0,
                                    @sesEnv = 0,
                                    @cant1 = 0,
                                    @tabEmp2 = 1,
                                    @cant2 = 0,
                                    @tabEmp3 = 1,
                                    @cant3 = 0,
                                    @consulta = 0,
                                    @facExp = 0,
                                    @errores = 0,
                                    @SE1Clave = 0
                                ";
                     

                 
                         

                                // DB::statement($sql);
                                $result = $dbServ->query($sql);
                                // $Dimporte = $result->fetch();
                                if($conImagenes==1){
                                    
                                echo '<tr>
                                        <th>
                                            '.$folio.'
                                        </th>                    
                                        <th>
                                            CAPTURA CON IMAGENES
                                        </th>
                                        <th>                                             
                                        </th>
                                    </tr>';                            

                                }else {
                                        
                                echo '<tr>
                                    <th>
                                        '.$folio.'
                                    </th>                    
                                    <th>
                                        CAPTURA SIN IMAGENES
                                    </th>
                                    <th>                                             
                                    </th>
                                </tr>';       
                                }

                        return 1;
                    // }else{
                    //  echo 'El folio: '.$folio.' no esta dentro de esta compañia <br>';
                    // }
                    }else{

                        echo '<tr>
                                <th>
                                    '.$folio.'
                                </th>                    
                                <th>
                                    SIN CAPTURA
                                </th>
                                <th> 
                                    DIAGNOSITCO MUY LARGO                                            
                                </th>
                            </tr>';                             
                        return 0;
                    }
                    }else{
                        echo '<tr>
                                <th>
                                    '.$folio.'
                                </th>                    
                                <th>
                                    SIN CAPTURA
                                </th>
                                <th> 
                                    NO CUENTA CON LESION TABULADA                                           
                                </th>
                            </tr>';       
                        return 0;
                    }                   
                    $i++;                   

        }


    }

/**************************************************************************************************************************/
/*******************                                                                                    *******************/
/*******************          Descargar LOS DOCUMENTOS de WEB Y PASAR A LOS DOCUMENTOS DE RESPALDO      *******************/
/*******************                                                                                    *******************/
/**************************************************************************************************************************/
