<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';

require_once '../../asesorias/admin/twilio-php-5.42.2/src/Twilio/autoload.php'; 
include ('http://atencionmedicainmediata.com/reportes/envioCorreoDocs.php');
use Twilio\Rest\Client;
/**
*  classe para agregar addendums a documentos


*/


class ClassMovil extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function getlistadoMovil($usr)
    {                
    $sql="SELECT
        ClavesAsesorias.CA_clave clave,
        ClavesAsesorias.Exp_folio folio,
    IF
        ( HA_nombre != '', HA_nombre, CA_nombreLes ) AS lesionado,
        CA_edad edad,
        CA_sexo sexo,
    IF
        ( HA_telAdicional != '', HA_telAdicional, CA_telefono ) telefono,
    IF
        ( HA_correo != '', HA_correo, CA_correo ) correro,
        CONCAT( Med_nombre, ' ', Med_paterno, ' ', Med_materno ) Medico,
        Cia_nombrecorto Empresa,
        Medico.Usu_login usuario,
        NotaMedica.Exp_folio AS folioNota,
        IF(CA_medio=1, 'VIDEOLLAMADA', 'LLAMADA') AS medio,
        CONCAT(
        IF
            ( TIMESTAMPDIFF( DAY, Log_fecreg, now())> 0, TIMESTAMPDIFF( DAY, Log_fecreg, now()), ' ' ),
        IF
            ( TIMESTAMPDIFF( DAY, Log_fecreg, now())> 0, ' días, ', ' ' ),
        IF
            (
                MOD ( TIMESTAMPDIFF( HOUR, Log_fecreg, now()), 24 )> 0,
                MOD ( TIMESTAMPDIFF( HOUR, Log_fecreg, now()), 24 ),
                ' ' 
            ),
        IF
            ( MOD ( TIMESTAMPDIFF( HOUR, Log_fecreg, now()), 24 )> 0, ' horas y ', ' ' ),
            MOD ( TIMESTAMPDIFF( MINUTE, Log_fecreg, now()), 60 ),
            ' minutos ' 
        ) tiempoEspera,
        TIMESTAMPDIFF(
            MINUTE,
            Log_fecreg,
        now()) minutos,
    CASE
            
            WHEN TIMESTAMPDIFF(
                MINUTE,
                Log_fecreg,
                now()) <= 3 THEN
                '#4FC254' 
                WHEN TIMESTAMPDIFF(
                    MINUTE,
                    Log_fecreg,
                    now()) <= 5 AND TIMESTAMPDIFF( MINUTE, Log_fecreg, now()) >= 4 THEN
                    '#DCAB62' ELSE '#FF8C8C' 
                END color ,
                CA_atencionMedico antecion,
                CA_fin
    FROM
        ClavesAsesorias
        LEFT JOIN Medico ON ClavesAsesorias.Med_clave = Medico.Med_clave
        LEFT JOIN Usuario ON Medico.Usu_login = Usuario.Usu_login
        LEFT JOIN Expediente ON ClavesAsesorias.Exp_folio = Expediente.Exp_folio
        LEFT JOIN Compania ON Expediente.Cia_clave = Compania.Cia_clave
        LEFT JOIN NotaMedica ON Expediente.Exp_folio = NotaMedica.Exp_folio
        LEFT JOIN HistoriaAsesorias ON ClavesAsesorias.Exp_folio = HistoriaAsesorias.Exp_folio
        LEFT JOIN LogsAsesorias ON ClavesAsesorias.Exp_folio = LogsAsesorias.Exp_folio 
    WHERE
        CA_activa = 'S' 
        AND Usuario.Usu_login = '".$usr."'
        AND EVE_clave = 3 
        ";

        $result = $this->_db->query($sql);
        $respuesta = $result->fetchAll();
         return $respuesta;  
         $this->db=null;     
    }

    public function getlistadoMovilAtendidos()
    {                
        $sql="SELECT
                CA_clave clave,
                ClavesAsesorias.Exp_folio folio,
                HA_nombre lesionado,
                HA_edad edad,
                HA_sexo sexo,
                HA_telefono telefono,
                HA_correo correro,
                CONCAT( Med_nombre, ' ', Med_paterno, ' ', Med_materno ) Medico,
                Cia_nombrecorto Empresa,
                Medico.Usu_login usuario 
            FROM
                ClavesAsesorias
                INNER JOIN HistoriaAsesorias ON ClavesAsesorias.Exp_folio = HistoriaAsesorias.Exp_folio
                LEFT JOIN Medico ON ClavesAsesorias.Med_clave = Medico.Med_clave
                LEFT JOIN Expediente ON ClavesAsesorias.Exp_folio = Expediente.Exp_folio
                LEFT JOIN Compania ON Expediente.Cia_clave = Compania.Cia_clave 
            WHERE
                CA_activa = 'S'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetchAll();
         return $respuesta;  
         $this->db=null;     
    }

    public function getUnidad($folio)
    {                
        $sql="SELECT
                Uni_clave unidad
            FROM
                Expediente
            WHERE
                Exp_folio = '".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
         return $respuesta;  
         $this->db=null;     
    }

    public function getDatosHeredo($folio, $cont)
    {        
        
        
        $sql="SELECT
                * 
            FROM
                FamEnfermedad
            WHERE
                Exp_folio = '".$folio."' AND FamE_clave=".$cont;
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
         return $respuesta;  
         $this->db=null;     
    }

    public function getDatosAlergias($folio, $cont)
    {        
        
        
        $sql="SELECT
                * 
            FROM
            HistoriaAlergias
            WHERE
                Exp_folio = '".$folio."' AND HistA_clave=".$cont;
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
         return $respuesta;  
         $this->db=null;     
    }

    public function getDatosPadecimiento($folio, $cont)
    {        
        
        
        $sql="SELECT
                * 
            FROM
            HistoriaPadecimiento
            WHERE
                Exp_folio = '".$folio."' AND Hist_clave=".$cont;
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
         return $respuesta;  
         $this->db=null;     
    }

    public function editaDatosHeredo($datos)
    {        
        $enfermedad     = $datos->enfermedad;
        $familiar       =$datos->familiar;
        $estatus        = $datos->estatus;
        $observaciones  = $datos->observaciones;
        $id             = $datos->idDato;
        
        $sql="UPDATE FamEnfermedad SET Enf_clave=".$enfermedad.", Fam_clave=".$familiar.",Est_clave=".$estatus.", FamE_obs='".$observaciones."'
                WHERE FamE_clave=".$id;
        $result = $this->_db->query($sql);
         return 'exito';  
         $this->db=null;     
    }

    public function editaDatosAlergia($datos)
    {        
        $alergia     =      $datos->alergia;
        $observaciones  = $datos->observaciones;
        $id             = $datos->idDato;
      
        $sql="UPDATE HistoriaAlergias SET Ale_clave=".$alergia.", Ale_obs='".$observaciones."'
                WHERE HistA_clave=".$id;       
        $result = $this->_db->query($sql);
         return 'exito';  
         $this->db=null;     
    }

    public function editaDatosPadecimiento($datos)
    {        
        $personal           = $datos->personal;
        $observaciones      = $datos->observaciones;
        $id                 = $datos->idDato;
      
        $sql="UPDATE HistoriaPadecimiento SET Pad_clave=".$personal.", Pad_obs='".$observaciones."'
                WHERE Hist_clave=".$id;       
        $result = $this->_db->query($sql);
         return 'exito';  
         $this->db=null;     
    }

    public function envioMensaje($folio,$tel)
    {     
        
        //ID de la cuenta creada en twilio
        $sid    = "AC5eba9747d03842d4d44dfa38066baa6f";  
        //TOKEN de la cuenta creada en twilio
        $token  = "b7dc2efd7ef91614cbee7e56d0c5bd28"; 
        //Llama a la clase Client para enviar un nuevo mensaje
        $twilio = new Client($sid, $token); 
        //Llena el contenido del mensaje 
        $message = $twilio->messages 
                        ->create("+52".$tel, // A quién va dirigido el mensaje (Con código del país del destinatario)
                                array( 
                                    "from" => "+12568294218",    //Número desde donde se envía el sms
                                    "body" => "Para ver los documentos generados en tu atencion, ingresa al siguiente link con tu clave: https://medicavial.net/archivosMovil/login.html" , //Cuerpo del mensaje
                                ) 
                        ); 
        // print($message->sid); //Muestra resultado
        return 'exito';

    }

    public function enviowhatsapp($folio,$tel)
    {    

        $sql="SELECT
                        IF
                        ( HA_nombre != '', HA_nombre, CA_nombreLes ) AS HA_nombre
                FROM
                    ClavesAsesorias
                    LEFT JOIN HistoriaAsesorias ON ClavesAsesorias.Exp_folio = HistoriaAsesorias.Exp_folio 
                WHERE
                    ClavesAsesorias.Exp_folio = '".$folio."'"; 
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();

        $nombre =$respuesta['HA_nombre'];
        
        // Update the path below to your autoload.php,
        // see https://getcomposer.org/doc/01-basic-usage.md
    
        //ID de la cuenta creada en twilio
        $sid    = "AC5eba9747d03842d4d44dfa38066baa6f"; 
        
        //TOKEN de la cuenta creada en twilio
        $token  = "b7dc2efd7ef91614cbee7e56d0c5bd28"; 
        
        $twilio = new Client($sid, $token);

        $liga = "http://medicavial.net/archivosMovil/index.php?fol=".$folio;

        $texto = $nombre.", a continuación encontrará la documentación generada en su videoasesoría.\n\n
        ".$liga."\n\n
        Estamos atentos a las dudas que le pudiesen surgir. Nuestro contacto es:\n
        8003633422\n
        558117003\n
        ami@atencionmedicainmediata.com";
        
        $message = $twilio->messages
                            ->create("whatsapp:+52.$tel", // to
                                    [
                                        "from" => "whatsapp:+12568294218",
                                        
                                        "body" => $texto
                                    ]
                            );
        
        return 'exito';

    }

    public function guardaDocsRequeridosMovil($datos, $folio)
    {        
        $diagnostico    = $datos[0]->diagnostico;
        $obs            = $datos[0]->obs;
        $pronostico     = $datos[0]->pronostico;
        //$kit            = $datos[0]->kit;
        $kit=0;
        $obskit=0;
        $receta         = $datos[0]->receta;
        $indicaciones   = $datos[0]->indicaciones;
        $pase           = $datos[0]->pase;
        //$recetaInt      = $datos[0]->recetaInterna;
        $recetaInt=0;
        $recetaExt      = $datos[0]->recetaExterna;
        //$obskit         = $datos[0]->obsKit;


        $sql = "SELECT count(*) contador FROM DocumentosRequeridosMovil WHERE EXP_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        $cont = $respuesta['contador'];
        $ejercicios = array();
        foreach($datos[1] as $dato){
            if($dato->variable==1){
                $ejercicios[$dato->nom] = $dato->variable;
            }
        }

        if($cont>0){
            $query = "UPDATE DocumentosRequeridosMovil SET DRM_kit= ".$kit.",DRM_recetaInterna=".$recetaInt.", DRM_recetaExterna=".$recetaExt.", DRM_indicaciones=".$indicaciones.", DRM_pase=".$pase.",DRM_ObsKit='".$obskit."'
            WHERE EXP_folio='".$folio."'";                                    
            if($this->_db->query($query)){
                    foreach ($ejercicios as $key => $value) {
                    $queryEjer = "UPDATE DocumentosRequeridosMovil SET ".$key."=".$value." WHERE EXP_folio='".$folio."'";                   
                    $result = $this->_db->query($queryEjer);
                    }
            }
            $sqlGet="SELECT * FROM DocumentosRequeridosMovil WHERE EXP_folio='".$folio."'";
            $result = $this->_db->query($sqlGet);
            $resp = $result->fetch(); 
            $respuesta = array();
            $miArray =array();
            $miArrayEje= array();
            foreach($resp as $clave => $valor){
                if($valor==1){
                    
                    if($clave == 'DRM_kit'){
                        $miArray[$clave]['nombre']='Kit Ajustador';
                    }elseif($clave == 'DRM_recetaInterna'){
                        $miArray[$clave]['nombre']='Receta Interna';
                    }elseif($clave == 'DRM_recetaExterna'){
                        $miArray[$clave]['nombre']='Receta Externa';
                    }elseif($clave == 'DRM_indicaciones'){
                        $miArray[$clave]['nombre']='Indicaciones Generales';
                    }elseif($clave == 'DRM_Eje_caderaRodilla'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Cadera Rodilla';
                    }elseif($clave == 'DRM_Eje_Hcolumna'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Higiene de Columna';
                    }elseif($clave == 'DRM_Eje_CVertebral'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Columna Vertebral';
                    }elseif($clave == 'DRM_Eje_hombro'){
                        $miArrayEje[$clave]['nombre']='Ejercicios de Hombro';
                    }elseif($clave == 'DRM_Eje_cmm'){
                        $miArrayEje[$clave]['nombre']='Ejercicios codo, mano y muñeca ';
                    }elseif($clave == 'DRM_Eje_TP'){
                        $miArrayEje[$clave]['nombre']='Ejercicios tobillo y pie';
                    }elseif($clave == 'DRM_Eje_CDorsolumbar'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Columna Dorsolumbar';
                    }elseif($clave == 'DRM_pase'){
                        $miArray[$clave]['nombre']='Pase de Atención';
                    }
                }
            }
            $respuesta['listado'] =  $miArray;
            $respuesta['Ejercicios']= $miArrayEje;
            $respuesta['estatus'] = 'update';
            return $respuesta;
        }else{
            $query = "INSERT INTO DocumentosRequeridosMovil(EXP_folio,DRM_kit,DRM_recetaInterna,DRM_recetaExterna, DRM_indicaciones, DRM_pase, DRM_ObsKit)
                                    VALUES('".$folio."',".$kit.",".$recetaInt.",".$recetaExt.",".$indicaciones.",".$pase.",'".$obskit."')";                                    
            if($this->_db->query($query)){
                foreach ($ejercicios as $key => $value) {
                    $queryEjer = "UPDATE DocumentosRequeridosMovil SET ".$key."=".$value." WHERE EXP_folio='".$folio."'";                   
                    $result = $this->_db->query($queryEjer);
                }
                
            }   
            $sqlGet="SELECT * FROM DocumentosRequeridosMovil WHERE EXP_folio='".$folio."'";
            $result = $this->_db->query($sqlGet);
            $resp = $result->fetch(); 
            $respuesta = array();
            $miArray =array();
            $miArrayEje= array();
            foreach($resp as $clave => $valor){
                if($valor==1){
                    
                    if($clave == 'DRM_kit'){
                        $miArray[$clave]['nombre']='Kit Ajustador';
                    }elseif($clave == 'DRM_recetaInterna'){
                        $miArray[$clave]['nombre']='Receta Interna';
                    }elseif($clave == 'DRM_recetaExterna'){
                        $miArray[$clave]['nombre']='Receta Externa';
                    }elseif($clave == 'DRM_indicaciones'){
                        $miArray[$clave]['nombre']='Indicaciones Generales';
                    }elseif($clave == 'DRM_Eje_caderaRodilla'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Cadera Rodilla';
                    }elseif($clave == 'DRM_Eje_Hcolumna'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Higiene de Columna';
                    }elseif($clave == 'DRM_Eje_CVertebral'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Columna Vertebral';
                    }elseif($clave == 'DRM_Eje_hombro'){
                        $miArrayEje[$clave]['nombre']='Ejercicios de Hombro';
                    }elseif($clave == 'DRM_Eje_cmm'){
                        $miArrayEje[$clave]['nombre']='Ejercicios codo, mano y muñeca ';
                    }elseif($clave == 'DRM_Eje_TP'){
                        $miArrayEje[$clave]['nombre']='Ejercicios tobillo y pie';
                    }elseif($clave == 'DRM_Eje_CDorsolumbar'){
                        $miArrayEje[$clave]['nombre']='Ejercicios Columna Dorsolumbar';
                    }elseif($clave == 'DRM_pase'){
                        $miArray[$clave]['nombre']='Pase de Atención';
                    }
                }
            }
            $respuesta['listado'] =  $miArray;
            $respuesta['Ejercicios']= $miArrayEje;
            $respuesta['estatus'] = 'insert';
            return $respuesta;               
        }
        
        $this->db=null;          
    }

    public function guardaDiagnosticoMovil($datos,$folio)
    {        
        $diagnostico        = $datos->diagnostico;
        $obs                = $datos->obs;
        $pronostico         = $datos->pronostico;
        $respuesta          = array();

        require('classes/generaCDB_resp.php');        
        $genera=new generaCDB_resp();
        $resp=$genera->generaCodigo($folio);
        
        $sql="UPDATE ObsNotaMed SET ObsNot_diagnosticoRx='".$diagnostico."', ObsNot_obs='".$obs."', ObsNot_pron='".$pronostico."'
                WHERE Exp_folio='".$folio."'";
        if($this->_db->query($sql)){
            $respuesta = array('respuesta'=>'exito','diag'=>$diagnostico);
        }
         return $respuesta;  

         $this->db=null;     
    }

    public function veIndicaciones($folio)
    {     
         
        $sql="SELECT count(*) contador FROM NotaInd WHERE Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        return $respuesta['contador'];  
         
         $this->db=null;     
    }

    public function veReceta($folio)
    {        
        $recetas = array();
        $sql="SELECT count(*) contador FROM RecetaMedica WHERE Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();

        $recetas['interna']=$respuesta['contador'];

        $sql1="SELECT count(*) contador FROM recetaExterna WHERE Exp_folio='".$folio."'";
        $result1 = $this->_db->query($sql1);
        $respuesta1 = $result1->fetch();
        $recetas['externa']=$respuesta1['contador'];  
         
        return $recetas;
         $this->db=null;     
    }

    public function vePase($folio)
    {        
        $sql="SELECT count(*) contador FROM Pase WHERE Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        return $respuesta['contador'];  
         
         $this->db=null;     
    }

    public function envioCorreo($folio, $correo, $correoAlt)
    {        
        $sqlGet="SELECT DocumentosRequeridosMovil.*, ClavesAsesorias.CA_nombreLes FROM DocumentosRequeridosMovil 
                INNER JOIN ClavesAsesorias on DocumentosRequeridosMovil.Exp_folio = ClavesAsesorias.Exp_folio
                WHERE DocumentosRequeridosMovil.EXP_folio='".$folio."'";
        $result = $this->_db->query($sqlGet);
        $resp = $result->fetch();
        $nombre = $resp['CA_nombreLes'];       
        $respuesta = array();
        $miArray =array();
        $miArrayEje= array();
        foreach($resp as $clave => $valor){
            if($valor==1){
                
                if($clave == 'DRM_kit'){
                    $miArray[$clave]['nombre']='Kit Ajustador';
                }elseif($clave == 'DRM_recetaInterna'){
                    $miArray[$clave]['nombre']='Receta Interna';
                }elseif($clave == 'DRM_recetaExterna'){
                    $miArray[$clave]['nombre']='Receta Externa';
                }elseif($clave == 'DRM_indicaciones'){
                    $miArray[$clave]['nombre']='Indicaciones Generales';
                }elseif($clave == 'DRM_Eje_caderaRodilla'){
                    $miArrayEje[$clave]['nombre']='Ejercicios Cadera Rodilla';
                }elseif($clave == 'DRM_Eje_Hcolumna'){
                    $miArrayEje[$clave]['nombre']='Ejercicios Higiene de Columna';
                }elseif($clave == 'DRM_Eje_CVertebral'){
                    $miArrayEje[$clave]['nombre']='Ejercicios Columna Vertebral';
                }elseif($clave == 'DRM_Eje_hombro'){
                    $miArrayEje[$clave]['nombre']='Ejercicios de Hombro';
                }elseif($clave == 'DRM_Eje_cmm'){
                    $miArrayEje[$clave]['nombre']='Ejercicios codo, mano y muñeca ';
                }elseif($clave == 'DRM_Eje_TP'){
                    $miArrayEje[$clave]['nombre']='Ejercicios tobillo y pie';
                }elseif($clave == 'DRM_Eje_CDorsolumbar'){
                    $miArrayEje[$clave]['nombre']='Ejercicios Columna Dorsolumbar';
                }elseif($clave == 'DRM_pase'){
                    $miArray[$clave]['nombre']='Pase de Atención';
                }
            }
        }
        $respuesta['listado'] =  $miArray;
        $respuesta['Ejercicios']= $miArrayEje;


        
        $mimemail = new nomad_mimemail();  
        $contenido='<HTML>
        <HEAD>
        </HEAD>
        <BODY>
        <br> 
                   
        <img src="logomv.gif"> 
        <br><br>    
        <img src="codigo.png">
        <br>
        <p><h3> <b>'.$nombre.' </b> ('.$folio.')<br><br>
        Agradecemos su preferencia,
        a continuaci&oacute;n encontrar&aacute; los documentos derivados de su asesoria en l&iacute;nea.</h3></p>
        <br>

        ';
        $query= "SELECT Uni_clave FROM Pase where Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $respuesta = $result->fetch();
        if($respuesta){


            $contenido.='
            <p> <h4> Con el siguiente c&oacute;digo QR podr&aacute; obtener la ubicaci&oacute;n de nuestra cl&iacute;nica a la que acudir&aacute; pr&oacute;ximamente junto con el mapa.</h4><p>
            <br><img src="qr.png">
        <img src="uni.png">
        </BODY>
        </HTML>         
        ';
        }else{
            $contenido.='
        </BODY>
        </HTML>         
        ';
        }
        $mimemail->set_from('noReply@medicavial.com.mx');   
        $mimemail->set_to(ltrim($correo));
        if($correoAlt!=''){
            $mimemail->add_cc($correoAlt);
        }
        
        $mimemail->set_subject("- Documentos de su asesoria en linea");
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        $mimemail->add_attachment("codigos/".$folio.'.png', "codigo.png");
        $query= "SELECT Uni_clave FROM Pase where Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $respuesta = $result->fetch();
        if($respuesta){
            $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/img/maps/qr".$respuesta['Uni_clave'].".png", "qr.png");
            $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/img/maps/".$respuesta['Uni_clave'].".png", "uni.png");
        }

        $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].'/registro/DocumentosMovil/'.$folio.'/NM_'.$folio.'.pdf','Nota_Medica.pdf');
        foreach($resp as $clave => $valor){
            if($valor==1){
                if($clave == 'DRM_kit'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/archivos/kit_medicamentos.pdf", "Indicaciones_kit.pdf");
                }elseif($clave == 'DRM_recetaInterna'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].'/registro/DocumentosMovil/'.$folio.'/RecetaInterna_'.$folio.'.pdf','Receta_en_Clínica.pdf');
                }elseif($clave == 'DRM_recetaExterna'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].'/registro/DocumentosMovil/'.$folio.'/RC_farmacia_'.$folio.'.pdf','Receta_Farmacia.pdf');
                }elseif($clave == 'DRM_indicaciones'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].'/registro/DocumentosMovil/'.$folio.'/Indicaciones_Generales_'.$folio.'.pdf','Indicaciones_Generales.pdf');
                }elseif($clave == 'DRM_Eje_caderaRodilla'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Cadera_Rodilla.pdf", "Cadera_Rodilla.pdf");
                }elseif($clave == 'DRM_Eje_Hcolumna'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Higiene_Columna.pdf", "Higiene_Columna.pdf");
                }elseif($clave == 'DRM_Eje_CVertebral'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Columna_Cervical.pdf", "Columna_Cervical.pdf");
                }elseif($clave == 'DRM_Eje_hombro'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Hombro.pdf", "Hombro.pdf");
                }elseif($clave == 'DRM_Eje_cmm'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Codo_Mano_Muneca.pdf", "Codo_Mano_Muneca.pdf");
                }elseif($clave == 'DRM_Eje_TP'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Tobillo_Pie.pdf", "Tobillo_Pie.pdf");
                }elseif($clave == 'DRM_Eje_CDorsolumbar'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/mvnuevo/api/classes/pdf/ejercicios/Columna_Dorsolumbar.pdf", "Columna_Dorsolumbar.pdf");
                }elseif($clave == 'DRM_pase'){
                    $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].'/registro/DocumentosMovil/'.$folio.'/PASE_'.$folio.'.pdf','Pase_Atencion.pdf');
                }
            }
        }
    
        if ($mimemail->send()){
            return 'exito';
        
        }else {
            return 'exito';
        }      
        $this->db=null;     
    }


    public function guardaLote($datos, $usr)
    {        
        $sql="SELECT MAX(LOT_id) id FROM LotesAMI";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        $id= $respuesta['id'];  
        if($id==0){$id=1;}
        else{$id++;}
        $cia        = $datos->compania;
        $noKits     = $datos->noKits;

        $ciaQuery = "SELECT SUBSTRING(Cia_nombrecorto,0,3) ciaPre FROM Compania where Cia_clave=".$cia;
        $result1 = $this->_db->query($ciaQuery);
        $re = $result1->fetch();
        $preCia= $re['ciaPre'];
        
        $idRelleno = str_pad($id, 4, "0", STR_PAD_LEFT);

        $claveLote = 'L-'.$preCia.$idRelleno;

        $query = "INSERT INTO LotesAMI(LOT_noKits,CIA_clave,LOT_fecreg,LOT_clave) VALUES(".$noKits.",".$cia.", now(),'".$claveLote."')";
        if($this->_db->query($query)){
            $sql1="SELECT MAX(KIT_id) idkit FROM kitsAMI WHERE CIA_clave=".$cia;
            $result1 = $this->_db->query($sql1);
            $respuesta1 = $result1->fetch();
            $idKit= $respuesta1['idkit'];  
            for ($i=1; $i <= $noKits; $i++) { 
                if($idKit==0){$idKit=1;}
                else{$idKit++;}
                $claveKit = $claveLote.'-'.$idKit;
                $queryKit = "INSERT INTO kitsAMI(LOT_id,KIT_clave) VALUES(".$id.",'".$claveKit."')";
                $this->_db->query($queryKit);
            }
          $queryFinal = "SELECT * FROM LotesAMI 
                        inner join Compania ON LotesAMI.CIA_clave = Compania.Cia_clave
                        ";
            $result2 = $this->_db->query($queryFinal);
            $respuestaFin = $result2->fetchAll();
            return $respuestaFin;
        }
         
         $this->db=null;     
    }

    public function listadoLotes()
    {  
            $queryFinal = "SELECT * FROM LotesAMI
                            inner join Compania ON LotesAMI.CIA_clave = Compania.Cia_clave
                            ";
            $result2 = $this->_db->query($queryFinal);
            $respuestaFin = $result2->fetchAll();
            return $respuestaFin;
    }

    public function listarKits($lote)
    {  
        $queryFinal = "SELECT * FROM LotesAMI 
        INNER JOIN kitsAMI ON LotesAMI.LOT_id = kitsAMI.LOT_id
        inner join Compania ON LotesAMI.CIA_clave = Compania.Cia_clave
        WHERE LotesAMI.LOT_clave='".$lote."'";
        $result2 = $this->_db->query($queryFinal);
        $respuestaFin = $result2->fetchAll();
        return $respuestaFin;
    }

    public function guardaKitUsado($datos, $usr)
    { 
        $ajustador          = $datos->ajustador;
        $claveAsesoria      = $datos->claveAsesoria;
        $mailAjustador      = $datos->mailAjustador;
        $nombreAjustador    = $datos->nombreAjustador;
        $telAjustador       = $datos->telAjustador;
        $claveKit           = $datos->noKit;

       
        

        if($ajustador){
            $query      =  "UPDATE kitsAMI SET KIT_fechaUtilizo = now(), KIT_utilizado=1, KIT_AjuUtilizo=".$ajustador.", KIT_UsuUtilizo ='".$usr."', CVE_asesoria='".$claveAsesoria."' WHERE KIT_clave='".$claveKit."'";
            $this->_db->query($query);
        }else{

            $sql1 = "SELECT CIA_clave FROM LotesAMI 
                INNER JOIN kitsAMI ON LotesAMI.LOT_id = kitsAMI.LOT_id
                WHERE   KIT_clave='".$claveKit."'";
            $result1 = $this->_db->query($sql1);
            $compa = $result1->fetch();
            $cia = $compa['CIA_clave'];
            
            
            $sql = "INSERT INTO AjustadorAMI(AJU_nombre, CIA_clave, AJU_telefono, AJU_correo) VALUES('".$nombreAjustador."',".$cia.",'".$telAjustador."','".$mailAjustador."')";
            $this->_db->query($sql);
            $query1 = "SELECT MAX(AJU_id) cont FROM AjustadorAMI";
            $result = $this->_db->query($query1);
            $cont = $result->fetch();
            $contador = $cont['cont'];
            $query      =  "UPDATE kitsAMI SET KIT_fechaUtilizo = now(), KIT_AjuUtilizo=".$contador.", KIT_utilizado=1, KIT_UsuUtilizo ='".$usr."', CVE_asesoria='".$claveAsesoria."' WHERE KIT_clave='".$claveKit."'";
            $this->_db->query($query);
        }
        $sqlLote = "SELECT LOT_clave FROM LotesAMI 
        INNER JOIN kitsAMI ON LotesAMI.LOT_id = kitsAMI.LOT_id
        WHERE   KIT_clave='".$claveKit."'";

        $resLote = $this->_db->query($sqlLote);
        $compa = $resLote->fetch();
        $lote = $compa['LOT_clave'];

        $respuesta = $this->listarKits($lote);

        return $respuesta;

    }

    public function modificaAtencion($fol)
    {  
        $queryFinal = "UPDATE ClavesAsesorias set CA_atencionMedico=1, CA_fecAtencionMedico=now() WHERE Exp_folio='".$fol."'";
        if($this->_db->query($queryFinal)){
            $respuestaFin='exito';
        }else{
            $respuestaFin='error';
        }
        return $respuestaFin;
    }

    public function terminarAtencion($fol)
    {  
        $queryFinal = "UPDATE ClavesAsesorias set CA_fin='S' WHERE Exp_folio='".$fol."'";
        if($this->_db->query($queryFinal)){
            // $queryMax = ""
            // $query = "INSERT INTO LogsAsesorias(Ex_folio, EVE_clave, Log_fecreg) VALUES('".$fol."', 8, now())";
            // $this->_db->query($query);
            $respuestaFin='exito';
        }else{
            $respuestaFin='error';
        }
        return $respuestaFin;
    }


    
    /********************************************************************************************************************/


    public function envioCorreoDocs($folio, $datos, $usr)
    {        

        $ejercicios = $datos[1];
        $dato = $datos[0];

        $nota     = $dato->notaMedica;
        $receta     = $dato->receta;
        $correo     = $dato->correo;

        $cr = $ejercicios[0]->variable;
        $hc = $ejercicios[1]->variable;
        $cc = $ejercicios[2]->variable;
        $hm = $ejercicios[3]->variable;
        $cmm = $ejercicios[4]->variable;
        $tp = $ejercicios[5]->variable;
        $cd = $ejercicios[6]->variable;

        $query0="INSERT INTO CorreosVerificados (CV_correo, Exp_folio, CV_fecreg, Usu_login) VALUES ('$correo', '$folio', now(), '$usr')";
        $result0 = $this->_dbAMI->query($query0);
        
        $query10="SELECT * FROM CorreosVerificados WHERE Exp_folio= '$folio' order by CV_fecreg asc limit 1";
        $result10 = $this->_dbAMI->query($query10);
        if($result10->num_rows>0){
            $row= $result->fetch();
            $fecha= $row['CV_fecreg'];
            $completa=explode("-", $fecha);
            $anio=$completa[0];
            $mes=$completa[1];
        }else{
            $anio=date("Y");
            $mes=date("m");
        }

        $url="http://atencionmedicainmediata.com/Digitales/EnviadosCorreo/$anio/$mes/".$folio;
        $direc=is_dir("http://atencionmedicainmediata.com/Digitales/EnviadosCorreo/$anio/$mes/".$folio);

        if($nota){

            if($direc!=1){
                mkdir("http://atencionmedicainmediata.com/Digitales/EnviadosCorreo/$anio/$mes/".$folio);
            }
            $origen1=$_SERVER['DOCUMENT_ROOT']."/registro/DigitalesSistema/".$folio."/NM_".$folio.".pdf";
            $destino1=$url."/NM_".$folio.".pdf";

            if(!is_dir($destino1)){
                copy($origen1,$destino1);
            }
            
            $query1="UPDATE CorreosVerificados SET CV_nota=1 WHERE Exp_folio='$folio'";
            $result1 = $this->_dbAMI->query($query1);

        }if($receta){
            if($direc!=1){
                mkdir("http://atencionmedicainmediata.com/Digitales/EnviadosCorreo/$anio/$mes/".$folio);
            }

            $origen2=$_SERVER['DOCUMENT_ROOT']."/registro/DigitalesSistema/".$folio."/Receta_".$folio.".pdf";
            $destino2=$url."/Receta_".$folio.".pdf";
            
            if(!is_dir($destino2)){
                copy($origen2,$destino2);
            }

            $query2="UPDATE CorreosVerificados SET CV_receta=1 WHERE Exp_folio='$folio'";
            $result2 = $this->_dbAMI->query($query2);

        }if($cr){
            $query3="UPDATE CorreosVerificados SET CV_caderaRod=1 WHERE Exp_folio='$folio'";
            $result3 = $this->_dbAMI->query($query3);
        }if($hc){
            $query4="UPDATE CorreosVerificados SET CV_higieneCol=1 WHERE Exp_folio='$folio'";
            $result4 = $this->_dbAMI->query($query4);
        }if($cc){
            $query5="UPDATE CorreosVerificados SET CV_columnaCerv=1 WHERE Exp_folio='$folio'";
            $result5 = $this->_dbAMI->query($query5);
        }if($hm){
            $query6="UPDATE CorreosVerificados SET CV_hombro=1 WHERE Exp_folio='$folio'";
            $result6 = $this->_dbAMI->query($query6);
        }if($cmm){
            $query7="UPDATE CorreosVerificados SET CV_codoMano=1 WHERE Exp_folio='$folio'";
            $result7 = $this->_dbAMI->query($query7);
        }if($tp){
            $query8="UPDATE CorreosVerificados SET CV_tobilloPie=1 WHERE Exp_folio='$folio'";
            $result8 = $this->_dbAMI->query($query8);
        }if($cd){
            $query9="UPDATE CorreosVerificados SET CV_columnaDorso=1 WHERE Exp_folio='$folio'";
            $result9 = $this->_dbAMI->query($query9);
        }

        $query="UPDATE Expediente SET Exp_mail='$correo', Exp_correoVerificado=1, Exp_correoVeriFecha=now() WHERE Exp_folio='$folio'";
        $result = $this->_db->query($query);
    
        /*if ($mimemail->send()){
            return 'exito';
        }else {
            return 'exito';
        } */

        $movil = new ClassMovil2();    
        $mail = $movil->envioCorreoDocs2($folio);
        return $mail;
        $this->db=null;     
    }

}
?>