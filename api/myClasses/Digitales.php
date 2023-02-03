<?php 



require_once "Modelo.php";

// clase para el envío de correos

require_once 'nomad_mimemail.inc.php';

/**

*  clase para el control de incidencias 

*/

class Digitales extends Modelo

{	

    public $mimemail;

	function __construct()

	{

		 parent::__construct();

         

	}

    public function guardaRx($fol,$noPlac,$usr,$inter,$archivo)

    {                

        $fechareg= date("Y-m-d H:i:s");

        list($fecha,$hora)= explode(" ", $fechareg);

        list($ano,$mes,$dia)= explode("-", $fecha);



        $pre    ='RX';

        $tipo   = 25;  



        switch ($mes){

             case '01':

             $mesd="January";//Enero

             break;



             case '02':

             $mesd="February";//Febrero

             break;



             case '03':

             $mesd="March";//Marzo

             break;



             case '04':

             $mesd="April";//Abril

             break;



             case '05':

             $mesd="May";//Mayo

             break;



             case '06':

             $mesd="June";//Junio

             break;



             case '07':

             $mesd="July";//Julio

             break;



             case '08':

             $mesd="August";//Agosto

             break;



             case '09':

             $mesd="September";//Septiembre

             break;



             case '10':

             $mesd="October";//Octubre

             break;



             case '11':

             $mesd="November";//Noviembre

             break;



             case '12':

             $mesd="December";//Diciembre

             break;

         }



        $direc="../../registro/Digitales/".$ano."/".$mesd."/".$fol; 

        $direc1="Digitales/".$ano."/".$mesd."/".$fol;





        if ($_FILES["file"]["size"] < 921600000){

            if ($_FILES['file']["error"] > 0){

                $respuesta = array('error'=>'si'); 

            }else{  



                if($archivo["type"] == "application/pdf" || $archivo["type"] == "image/jpeg" || $archivo["type"] == "image/gif" || $archivo["type"] == "image/png" || $archivo["type"] == "image/bmp" || $archivo["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $archivo["type"] =="application/msword" || $archivo["type"] == "image/pjpeg"){



                    if (file_exists($direc."/".$_FILES["file"]["name"])){

                        echo $_FILES["file"]["name"] . " <br><br>Error: Ya existe en el directorio. ";

                    }else{        

                        $_dir= is_dir("../../registro/Digitales");        

                        if($_dir==1){                        

                            $_dir1= is_dir("../../registro/Digitales/".$ano);                

                            if($_dir1==1){

                                $_dir2= is_dir("../../registro/Digitales/".$ano."/".$mesd);                                  

                                if($_dir2==1){

                                    $_dir3= is_dir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);    

                                    if($_dir3==1){                                                   

                                    }else{

                                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);            

                                    }                                               

                                }else{

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd);

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol); 

                                }                                                    

                            }else{

                              mkdir("../../registro/Digitales/".$ano);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);  

                            }                

                        }else{

                          mkdir("../../registro/Digitales");

                          mkdir("../../registro/Digitales/".$ano);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                        }                                        

                    }

                } 

                $query="SELECT MAX(Arc_cons)+1 As Cons FROM DocumentosDigitales WHERE REG_folio='".$fol."' and Arc_tipo=".$tipo;  

                $result =  $this->_db->query($query);

                $rs = $result->fetch();

                $cons=$rs['Cons'];  

                if($cons==0 || $cons==null)$cons=1;               

                $partes=explode(".",$archivo["name"]);                

                move_uploaded_file($archivo["tmp_name"], $direc."/".$cons."_".$pre."_".$fol.".".$partes[1]);

                $claveArch= $cons."_".$pre."_".$fol.".".$partes[1];

                $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1]; 

                try{

                    $query="INSERT INTO DocumentosDigitales(Arc_cons, Arc_clave, REG_folio, Arc_archivo, Arc_tipo, Arc_desde, USU_login, Arc_fecreg,Arc_noRx,Arc_interRX)

                                        VALUES(:Arc_cons, :Arc_clave, :REG_folio, :Arc_archivo, :Arc_tipo,'REGISTRO_MV', :USU_login, NOW(),:Arc_noRx,:Arc_interRX)";                    

                    $temporal= $this->_db->prepare($query);

                    $temporal->bindParam("Arc_cons",$cons);

                    $temporal->bindParam("Arc_clave",$claveArch);

                    $temporal->bindParam("REG_folio",$fol);             

                    $temporal->bindParam("Arc_archivo",$ruta1);

                    $temporal->bindParam("Arc_tipo",$tipo);                         

                    $temporal->bindParam("USU_login",$usr); 

                    $temporal->bindParam("Arc_noRx",$noPlac); 

                    $temporal->bindParam("Arc_interRX",$inter); 

                    if ($temporal->execute()){

                    /*$query="select Doc_clave, doc_ruta_archivo,doc_tipo,doc_fecreg, tipoDoc_nombre from subeDocumentos 

                            inner join tipoDoc on subeDocumentos.doc_tipo=tipoDoc.tipoDoc_id

                            where Exp_folio='".$fol."' order by doc_tipo, doc_clave asc";*/

                    $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg, Arc_interRX from       DocumentosDigitales

                            inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 

                            where REG_folio='".$fol."' and Arc_tipo=25";

                    $result = $this->_db->query($query);

                    $respuesta = $result->fetchAll(PDO::FETCH_OBJ);                                                                    

                    return $respuesta;

                    $db = null;

                    }else{

                        $respuesta = array('respuesta' => 'error');

                        return $respuesta;

                    }                               

                    $db = null;                     

                }catch(Exception $e){

                    $respuesta=  array('respuesta' => $e->getMessage() );

                    return $respuesta;

                } 

            }

       

        }

    }



    public function guardaFoto($fol,$noPlac,$usr,$inter,$archivo)

    {                

        $fechareg= date("Y-m-d H:i:s");

        list($fecha,$hora)= explode(" ", $fechareg);

        list($ano,$mes,$dia)= explode("-", $fecha);



        $pre    ='FT';

        $tipo   = 34;  



        switch ($mes){

             case '01':

             $mesd="January";//Enero

             break;



             case '02':

             $mesd="February";//Febrero

             break;



             case '03':

             $mesd="March";//Marzo

             break;



             case '04':

             $mesd="April";//Abril

             break;



             case '05':

             $mesd="May";//Mayo

             break;



             case '06':

             $mesd="June";//Junio

             break;



             case '07':

             $mesd="July";//Julio

             break;



             case '08':

             $mesd="August";//Agosto

             break;



             case '09':

             $mesd="September";//Septiembre

             break;



             case '10':

             $mesd="October";//Octubre

             break;



             case '11':

             $mesd="November";//Noviembre

             break;



             case '12':

             $mesd="December";//Diciembre

             break;

         }



        $direc="../../registro/Digitales/".$ano."/".$mesd."/".$fol; 

        $direc1="Digitales/".$ano."/".$mesd."/".$fol;





        if ($_FILES["file"]["size"] < 921600000){

            if ($_FILES['file']["error"] > 0){

                $respuesta = array('error'=>'si'); 

            }else{  



                if($archivo["type"] == "application/pdf" || $archivo["type"] == "image/jpeg" || $archivo["type"] == "image/gif" || $archivo["type"] == "image/png" || $archivo["type"] == "image/bmp" || $archivo["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $archivo["type"] =="application/msword" || $archivo["type"] == "image/pjpeg"){



                    if (file_exists($direc."/".$_FILES["file"]["name"])){

                        echo $_FILES["file"]["name"] . " <br><br>Error: Ya existe en el directorio. ";

                    }else{        

                        $_dir= is_dir("../../registro/Digitales");        

                        if($_dir==1){                        

                            $_dir1= is_dir("../../registro/Digitales/".$ano);                

                            if($_dir1==1){

                                $_dir2= is_dir("../../registro/Digitales/".$ano."/".$mesd);                                  

                                if($_dir2==1){

                                    $_dir3= is_dir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);    

                                    if($_dir3==1){                                                   

                                    }else{

                                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);            

                                    }                                               

                                }else{

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd);

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol); 

                                }                                                    

                            }else{

                              mkdir("../../registro/Digitales/".$ano);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);  

                            }                

                        }else{

                          mkdir("../../registro/Digitales");

                          mkdir("../../registro/Digitales/".$ano);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                        }                                        

                    }

                } 

                $query="SELECT MAX(Arc_cons)+1 As Cons FROM DocumentosDigitales WHERE REG_folio='".$fol."' and Arc_tipo=".$tipo;  

                $result =  $this->_db->query($query);

                $rs = $result->fetch();

                $cons=$rs['Cons'];  

                if($cons==0 || $cons==null)$cons=1;               

                $partes=explode(".",$archivo["name"]);                

                move_uploaded_file($archivo["tmp_name"], $direc."/".$cons."_".$pre."_".$fol.".".$partes[1]);

                $claveArch= $cons."_".$pre."_".$fol.".".$partes[1];

                $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1]; 

                try{

                    $query="INSERT INTO DocumentosDigitales(Arc_cons, Arc_clave, REG_folio, Arc_archivo, Arc_tipo, Arc_desde, USU_login, Arc_fecreg,Arc_noRx,Arc_interRX)

                                        VALUES(:Arc_cons, :Arc_clave, :REG_folio, :Arc_archivo, :Arc_tipo,'REGISTRO_MV', :USU_login, NOW(),:Arc_noRx,:Arc_interRX)";                    

                    $temporal= $this->_db->prepare($query);

                    $temporal->bindParam("Arc_cons",$cons);

                    $temporal->bindParam("Arc_clave",$claveArch);

                    $temporal->bindParam("REG_folio",$fol);             

                    $temporal->bindParam("Arc_archivo",$ruta1);

                    $temporal->bindParam("Arc_tipo",$tipo);                         

                    $temporal->bindParam("USU_login",$usr); 

                    $temporal->bindParam("Arc_noRx",$noPlac); 

                    $temporal->bindParam("Arc_interRX",$inter); 

                    if ($temporal->execute()){

                    /*$query="select Doc_clave, doc_ruta_archivo,doc_tipo,doc_fecreg, tipoDoc_nombre from subeDocumentos 

                            inner join tipoDoc on subeDocumentos.doc_tipo=tipoDoc.tipoDoc_id

                            where Exp_folio='".$fol."' order by doc_tipo, doc_clave asc";*/

                    $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg, Arc_interRX from       DocumentosDigitales

                            inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 

                            where REG_folio='".$fol."' and Arc_tipo=35";

                    $result = $this->_db->query($query);

                    $respuesta = $result->fetchAll(PDO::FETCH_OBJ);                                                                    

                    return $respuesta;

                    $db = null;

                    }else{

                        $respuesta = array('respuesta' => 'error');

                        return $respuesta;

                    }                               

                    $db = null;                     

                }catch(Exception $e){

                    $respuesta=  array('respuesta' => $e->getMessage() );

                    return $respuesta;

                } 

            }

       

        }

    }



    public function guardaRxSol($fol,$noPlac,$usr,$inter,$rxCve,$archivo)

    {                

        $fechareg= date("Y-m-d H:i:s");

        list($fecha,$hora)= explode(" ", $fechareg);

        list($ano,$mes,$dia)= explode("-", $fecha);



        $pre    ='RX';

        $tipo   = 25;  



        switch ($mes){

             case '01':

             $mesd="January";//Enero

             break;



             case '02':

             $mesd="February";//Febrero

             break;



             case '03':

             $mesd="March";//Marzo

             break;



             case '04':

             $mesd="April";//Abril

             break;



             case '05':

             $mesd="May";//Mayo

             break;



             case '06':

             $mesd="June";//Junio

             break;



             case '07':

             $mesd="July";//Julio

             break;



             case '08':

             $mesd="August";//Agosto

             break;



             case '09':

             $mesd="September";//Septiembre

             break;



             case '10':

             $mesd="October";//Octubre

             break;



             case '11':

             $mesd="November";//Noviembre

             break;



             case '12':

             $mesd="December";//Diciembre

             break;

         }



        $direc="../../registro/Digitales/".$ano."/".$mesd."/".$fol; 

        $direc1="Digitales/".$ano."/".$mesd."/".$fol;





        if ($_FILES["file"]["size"] < 921600000){

            if ($_FILES['file']["error"] > 0){

                $respuesta = array('error'=>'si'); 

            }else{  



                if($archivo["type"] == "application/pdf" || $archivo["type"] == "image/jpeg" || $archivo["type"] == "image/gif" || $archivo["type"] == "image/png" || $archivo["type"] == "image/bmp" || $archivo["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $archivo["type"] =="application/msword" || $archivo["type"] == "image/pjpeg"){



                    if (file_exists($direc."/".$_FILES["file"]["name"])){

                        echo $_FILES["file"]["name"] . " <br><br>Error: Ya existe en el directorio. ";

                    }else{        

                        $_dir= is_dir("../../registro/Digitales");        

                        if($_dir==1){                        

                            $_dir1= is_dir("../../registro/Digitales/".$ano);                

                            if($_dir1==1){

                                $_dir2= is_dir("../../registro/Digitales/".$ano."/".$mesd);                                  

                                if($_dir2==1){

                                    $_dir3= is_dir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);    

                                    if($_dir3==1){                                                   

                                    }else{

                                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);            

                                    }                                               

                                }else{

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd);

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol); 

                                }                                                    

                            }else{

                              mkdir("../../registro/Digitales/".$ano);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);  

                            }                

                        }else{

                          mkdir("../../registro/Digitales");

                          mkdir("../../registro/Digitales/".$ano);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                        }                                        

                    }

                } 

                $query="SELECT MAX(Arc_cons)+1 As Cons FROM DocumentosDigitales WHERE REG_folio='".$fol."' and Arc_tipo=".$tipo;  

                $result =  $this->_db->query($query);

                $rs = $result->fetch();

                $cons=$rs['Cons'];  

                if($cons==0 || $cons==null)$cons=1;               

                $partes=explode(".",$archivo["name"]);                

                move_uploaded_file($archivo["tmp_name"], $direc."/".$cons."_".$pre."_".$fol.".".$partes[1]);

                $claveArch= $cons."_".$pre."_".$fol.".".$partes[1];

                $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1]; 

                try{

                    $query="INSERT INTO DocumentosDigitales(Arc_cons, Arc_clave, REG_folio, Arc_archivo, Arc_tipo, Arc_desde, USU_login, Arc_fecreg,Arc_noRx,Arc_interRX)

                                        VALUES(:Arc_cons, :Arc_clave, :REG_folio, :Arc_archivo, :Arc_tipo,'REGISTRO_MV', :USU_login, NOW(),:Arc_noRx,:Arc_interRX)";                    

                    $temporal= $this->_db->prepare($query);

                    $temporal->bindParam("Arc_cons",$cons);

                    $temporal->bindParam("Arc_clave",$claveArch);

                    $temporal->bindParam("REG_folio",$fol);             

                    $temporal->bindParam("Arc_archivo",$ruta1);

                    $temporal->bindParam("Arc_tipo",$tipo);                         

                    $temporal->bindParam("USU_login",$usr); 

                    $temporal->bindParam("Arc_noRx",$noPlac); 

                    $temporal->bindParam("Arc_interRX",$inter); 

                    if ($temporal->execute()){

                        $query= "UPDATE RxSolicitados SET Rxs_digitalizado=1 WHERE Rxs_clave=".$rxCve;

                        $result = $this->_db->query($query);

                    /*$query="select Doc_clave, doc_ruta_archivo,doc_tipo,doc_fecreg, tipoDoc_nombre from subeDocumentos 

                            inner join tipoDoc on subeDocumentos.doc_tipo=tipoDoc.tipoDoc_id

                            where Exp_folio='".$fol."' order by doc_tipo, doc_clave asc";*/

                        $sql="  SELECT Rxs_clave, Exp_folio, Rx_nombre,0 as 'barra',Rxs_digitalizado FROM RxSolicitados 

                                INNER JOIN Rx ON RxSolicitados.Rx_clave = Rx.Rx_clave

                                WHERE Exp_folio='".$fol."'";

                        $result = $this->_db->query($sql);

                        $respuesta = $result->fetchAll();                                                                                

                    return $respuesta;

                    $db = null;

                    }else{

                        $respuesta = array('respuesta' => 'error');

                        return $respuesta;

                    }                               

                    $db = null;                     

                }catch(Exception $e){

                    $respuesta=  array('respuesta' => $e->getMessage() );

                    return $respuesta;

                } 

            }

       

        }

    }



    public function listadoRX($fol){

        $query="select DocumentosDigitales.Arc_tipo,Arc_clave,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg, Arc_interRX  from DocumentosDigitales

            inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 

            where REG_folio='".$fol."' and Arc_tipo=25";

        $result = $this->_db->query($query);

        $respuesta     = $result->fetchAll(PDO::FETCH_OBJ);  

        return $respuesta;

    }

    public function datosPaciente($fol){

        $query="select Exp_completo, Exp_mail  from Expediente

            where Exp_folio='".$fol."'";
        $result = $this->_db->query($query);

        $respuesta     = $result->fetch(PDO::FETCH_OBJ);  

        return $respuesta;

    }

    public function enviarRxCorreo($fol, $correo, $correon){

        $query="select Exp_completo, Exp_mail  from Expediente
            where Exp_folio='".$fol."'";
        $result = $this->_db->query($query);
        $respuesta     = $result->fetch();  

        $nombre = $respuesta['Exp_completo'];
        $correoO = $respuesta['Exp_mail'];



        $listRx  = $this->listadoRX($fol);

        if($correoO == $correo && $correoO!=''){
            $mimemail = new nomad_mimemail();  
            $contenido='<HTML>
            <HEAD>
            </HEAD>
            <BODY>
            <br> 
                       
            <img src="logomv.gif"> 
            <br><br>    
            <br>
            <p><h3> <b>'.$nombre.' </b> ('.$fol.')<br><br>
            Agradecemos su preferencia,
            a continuaci&oacute;n encontrar&aacute; las imagenes de los RX solicitados</h3></p>
            <br>
    
            ';
          
                $contenido.='
            </BODY>
            </HTML>         
            ';
        
            $mimemail->set_from('noReply@medicavial.com.mx');   
            $mimemail->set_to(ltrim($correo));
            // if($correoAlt!=''){
            //     $mimemail->add_cc($correoAlt);
            // }

            $mimemail->set_subject("- RX ");
            $mimemail->set_html($contenido);
            $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

            foreach($listRx as $clave => $valor){
                $ruta = $valor->Arc_Archivo; 
                $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/registro/".$ruta, $valor->Arc_clave);
            
            }

            $mimemail->send();

            return 'exito';

        }elseif($correoO != $correo){

            $mimemail = new nomad_mimemail();  
            
            $contenido='<HTML>
            <HEAD>
            </HEAD>
            <BODY>
            <br> 
                       
            <img src="logomv.gif"> 
            <br><br>    
            <br>
            <p><h3> <b>'.$nombre.' </b> ('.$fol.')<br><br>
            Agradecemos su preferencia,
            a continuaci&oacute;n encontrar&aacute; las imagenes de los RX solicitados</h3></p>
            <br>
    
            ';
          
                $contenido.='
            </BODY>
            </HTML>         
            ';
        

            $mimemail->set_from('noReply@medicavial.com.mx');   
            $mimemail->set_to(ltrim($correo));
            // if($correoAlt!=''){
            //     $mimemail->add_cc($correoAlt);
            // }

            $mimemail->set_subject("- RX ");
            $mimemail->set_html($contenido);
            $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");


            foreach($listRx as $clave => $valor){

                $ruta = $valor->Arc_Archivo; 
                $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/registro/".$ruta, $valor->Arc_clave);
            
            }

            if ($mimemail->send()){
                return 'exito';
            
            }else {
                return 'exito';
            }   

        }else{


            $mimemail = new nomad_mimemail();  
            $contenido='<HTML>
            <HEAD>
            </HEAD>
            <BODY>
            <br> 
                       
            <img src="logomv.gif"> 
            <br><br>    
            <br>
            <p><h3> <b>'.$nombre.' </b> ('.$fol.')<br><br>
            Agradecemos su preferencia,
            a continuaci&oacute;n encontrar&aacute; las imagenes de los RX solicitados</h3></p>
            <br>
    
            ';
          
                $contenido.='
            </BODY>
            </HTML>         
            ';
        

            $mimemail->set_from('noReply@medicavial.com.mx');   
            $mimemail->set_to(ltrim($correon));
            // if($correoAlt!=''){
            //     $mimemail->add_cc($correoAlt);
            // }

            $mimemail->set_subject("- RX ");
            $mimemail->set_html($contenido);
            $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");


            foreach($listRx as $clave => $valor){

                $ruta = $valor->Arc_Archivo; 
                $mimemail->add_attachment($_SERVER['DOCUMENT_ROOT']."/registro/".$ruta, $valor->Arc_clave);
            
            }

            if ($mimemail->send()){
                return 'exito';
            
            }else {
                return 'exito';
            }   

        }

    }



    protected function generateRandomString($length = 10) {

        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

    }



    public function guardaRhZima($fol,$usr,$inter,$archivo)

    {

        $fechareg= date("Y-m-d H:i:s");

        list($fecha,$hora)= explode(" ", $fechareg);

        list($ano,$mes,$dia)= explode("-", $fecha);



        $pre    ='RH';

        $tipo   = 28;



        switch ($mes){

             case '01':

             $mesd="January";//Enero

             break;



             case '02':

             $mesd="February";//Febrero

             break;



             case '03':

             $mesd="March";//Marzo

             break;



             case '04':

             $mesd="April";//Abril

             break;



             case '05':

             $mesd="May";//Mayo

             break;



             case '06':

             $mesd="June";//Junio

             break;



             case '07':

             $mesd="July";//Julio

             break;



             case '08':

             $mesd="August";//Agosto

             break;



             case '09':

             $mesd="September";//Septiembre

             break;



             case '10':

             $mesd="October";//Octubre

             break;



             case '11':

             $mesd="November";//Noviembre

             break;



             case '12':

             $mesd="December";//Diciembre

             break;

         }



        $direc="../../registro/Digitales/".$ano."/".$mesd."/".$fol;

        $direc1="Digitales/".$ano."/".$mesd."/".$fol;





        if ($_FILES["file"]["size"] < 921600000){

            if ($_FILES['file']["error"] > 0){

                $respuesta = array('error'=>'si');

            }else{



                if($archivo["type"] == "application/pdf" || $archivo["type"] == "image/jpeg" || $archivo["type"] == "image/gif" || $archivo["type"] == "image/png" || $archivo["type"] == "image/bmp" || $archivo["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $archivo["type"] =="application/msword" || $archivo["type"] == "image/pjpeg"){



                    if (file_exists($direc."/".$_FILES["file"]["name"])){

                        echo $_FILES["file"]["name"] . " <br><br>Error: Ya existe en el directorio. ";

                    }else{

                        $_dir= is_dir("../../registro/Digitales");

                        if($_dir==1){

                            $_dir1= is_dir("../../registro/Digitales/".$ano);

                            if($_dir1==1){

                                $_dir2= is_dir("../../registro/Digitales/".$ano."/".$mesd);

                                if($_dir2==1){

                                    $_dir3= is_dir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                                    if($_dir3==1){

                                    }else{

                                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                                    }

                                }else{

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd);

                                  mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                                }

                            }else{

                              mkdir("../../registro/Digitales/".$ano);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd);

                              mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                            }

                        }else{

                          mkdir("../../registro/Digitales");

                          mkdir("../../registro/Digitales/".$ano);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd);

                          mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);

                        }

                    }

                }

                $query="SELECT MAX(Arc_cons)+1 As Cons FROM DocumentosDigitales WHERE REG_folio='".$fol."' and Arc_tipo=".$tipo;

                $result =  $this->_db->query($query);

                $rs = $result->fetch();

                $cons=$rs['Cons'];

                if($cons==0 || $cons==null)$cons=1;

                $partes=explode(".",$archivo["name"]);

                move_uploaded_file($archivo["tmp_name"], $direc."/".$cons."_".$pre."_".$fol.".".$partes[1]);

                $claveArch= $cons."_".$pre."_".$fol.".".$partes[1];

                $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1];

                try{

                    $query="INSERT INTO DocumentosDigitales(Arc_cons, Arc_clave, REG_folio, Arc_archivo, Arc_tipo, Arc_desde, USU_login, Arc_fecreg,Arc_interRX)

                                        VALUES(:Arc_cons, :Arc_clave, :REG_folio, :Arc_archivo, :Arc_tipo,'REGISTRO_MV', :USU_login, NOW(),:Arc_interRX)";

                    $temporal= $this->_db->prepare($query);

                    $temporal->bindParam("Arc_cons",$cons);

                    $temporal->bindParam("Arc_clave",$claveArch);

                    $temporal->bindParam("REG_folio",$fol);

                    $temporal->bindParam("Arc_archivo",$ruta1);

                    $temporal->bindParam("Arc_tipo",$tipo);

                    $temporal->bindParam("USU_login",$usr);

                    $temporal->bindParam("Arc_interRX",$inter);

                    if ($temporal->execute()){

                    /*$query="select Doc_clave, doc_ruta_archivo,doc_tipo,doc_fecreg, tipoDoc_nombre from subeDocumentos

                            inner join tipoDoc on subeDocumentos.doc_tipo=tipoDoc.tipoDoc_id

                            where Exp_folio='".$fol."' order by doc_tipo, doc_clave asc";*/

                    // /***************** subir documentos a el servidor de zima *************/

                    // $ftp_server='pmzima.net';

                    // $ftp_user_name = 'zima';

                    // $ftp_user_pass = 'm3D$k4_100i';

                    // $destination_file = 'public_html/'.$claveArch;

                    // $source_file = "../../registro/".$direc1."/".$claveArch;

                    // // establecer una conexión básica

                    // $conn_id = ftp_connect($ftp_server);



                    // // iniciar una sesión con nombre de usuario y contraseña

                    // $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

                    // // verificar la conexión

                    // if ((!$conn_id) || (!$login_result)) {

                    //     // echo "¡La conexión FTP ha fallado!";

                    //     // echo "Se intentó conectar al $ftp_server por el usuario $ftp_user_name";

                    //     // exit;

                    //     $respuesta=array('respuesta'=>'error');

                    //     return $respuesta;

                    // }

                    // // subir un archivo

                    // $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);



                    // // comprobar el estado de la subida

                    // if (!$upload) {

                    //     $respuesta=array('respuesta'=>'error');

                    //     return $respuesta;

                    // } else {

                    //     $respuesta=array('respuesta'=>'exito');

                    // }



                    // // cerrar la conexión ftp

                    // ftp_close($conn_id);

                    // fclose($file);



                    /*****************************fin de subida de archivo al servidor zima ***********/

                    $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg, Arc_interRX from       DocumentosDigitales

                            inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint

                            where REG_folio='".$fol."' and Arc_tipo=28";

                    $result = $this->_db->query($query);

                    $respuesta = $result->fetchAll(PDO::FETCH_OBJ);

                    return $respuesta;

                    }else{

                        $respuesta = array('respuesta' => 'error');

                        return $respuesta;

                    }

                }catch(Exception $e){

                    $respuesta=  array('respuesta' => 'error' );

                    return $respuesta;

                }

            }



        }

    }



}

 ?>