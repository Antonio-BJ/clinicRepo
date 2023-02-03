<?php 

require_once "Modelo.php";
require_once 'nomad_mimemail.inc.php';

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class CortaEstancia               --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 04-11-2015                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class CortaEstancia extends Modelo
{
	public $mimemail;
	function __construct()
	{
		 parent::__construct();         
	}

	public function getSolicitud($fol)
    {        
    	try{
            $sql = "Select count(*) as contador from SolicitudCortaEstancia where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();

        }catch(Exception $e){
        	//$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;    
    }

    // public function setSolicitud($fol,$usr,$datos)
    // {    
    //     $mimemail1 = new nomad_mimemail();
    //     $mimemail2 = new nomad_mimemail();
    //     $mimemail3 = new nomad_mimemail();
    //     $mimemail4 = new nomad_mimemail();
    //     $motivo = $datos->motivo;
    //     $tiempo = $datos->tiempo;

    //     try{
    //         $sql = "SELECT MAX(CE_clave)+1 as cont FROM SolicitudCortaEstancia";
    //         $result = $this->_db->query($sql);
    //         $rs = $result->fetch();
    //         $cont = $rs['cont'];            
    //         if($cont==0||$cont==null){ $cont=1;}            
    //         $cadena1 = $this->RandomString(4);
    //         $cadena2 = $this->RandomString(4);
    //         $cadena3 = $this->RandomString(4);            
    //         $sql = "INSERT INTO SolicitudCortaEstancia(CE_clave, Exp_folio, CE_motivo, CE_bloqueo, CE_tiempo, CE_codigo1, CE_codigo2, CE_codigo3, Usu_login, Ce_fecreg)
    //                 VALUES(:CE_clave, :Exp_folio, :CE_motivo,'S', :CE_tiempo, :CE_codigo1, :CE_codigo2, :CE_codigo3, :Usu_login,NOW())";
    //         $temporal =$this->_db->prepare($sql);
    //         $temporal->bindParam("CE_clave", $cont);
    //         $temporal->bindParam("Exp_folio", $fol);
    //         $temporal->bindParam("CE_motivo", $motivo);
    //         $temporal->bindParam("CE_tiempo", $tiempo);
    //         $temporal->bindParam("CE_codigo1", $cadena1);
    //         $temporal->bindParam("CE_codigo2", $cadena2);
    //         $temporal->bindParam("CE_codigo3", $cadena3);            
    //         $temporal->bindParam("Usu_login", $usr);

            

    //         if ($temporal->execute()){
    //             $sql= " SELECT Exp_completo, Exp_edad, Exp_sexo, ObsNot_diagnosticoRx, ObsNot_edoG, Vit_talla, Vit_peso,Vit_ta,
    //                 Vit_fc, Vit_fr,Uni_nombrecorto, Cia_nombrecorto, Exp_fecreg FROM Expediente
    //                 LEFT JOIN ObsNotaMed on Expediente.Exp_folio=ObsNotaMed.Exp_folio
    //                 LEFT JOIN Vitales on Expediente.Exp_folio=Vitales.Exp_folio
    //                 INNER JOIN Unidad on Expediente.Uni_clave=Unidad.Uni_clave
    //                 INNER JOIN Compania on Expediente.Cia_clave = Compania.Cia_clave
    //                 WHERE Expediente.Exp_folio='".$fol."'";
    //             $result = $this->_db->query($sql);
    //             $rs = $result->fetch();
    //             $nombre=$rs['Exp_completo'];
    //             $edad = $rs['Exp_edad'];
    //             $sexo = $rs['Exp_sexo'];
    //             $diagnostico = $rs['ObsNot_diagnosticoRx'];
    //             $EdoGral = $rs['ObsNot_edoG'];
    //             $talla  = $rs['Vit_talla'];
    //             $peso = $rs['Vit_peso'];
    //             $ta   = $rs['Vit_ta'];
    //             $fc   = $rs['Vit_fc'];
    //             $fr   = $rs['Vit_fr'];
    //             $uni  = $rs['Uni_nombrecorto'];
    //             $cia  = $rs['Cia_nombrecorto'];
    //             $fec  = $rs['Exp_fecreg'];

    //             $contenido = $this->CorreoAutorizacion($motivo, $tiempo, $cadena1, $nombre, $edad, $sexo, $diagnostico, $EdoGral, $talla, $peso, $ta, $fc,$fr,$uni,$cia,$fec);
    //             $mimemail1->set_from("seguimiento_NoReply@medicavial.com.mx");
    //             //$mimemail->set_to("scisneros@medicavial.com.mx");       
    //             $mimemail1->set_to("scisneros@medicavial.com.mx");       
    //             $mimemail1->add_bcc("egutierrez@medicavial.com.mx");             
    //             $mimemail1->set_subject("- Autorización - ".$fol);
    //             $mimemail1->set_html($contenido);
    //             $mimemail1->add_attachment("../imgs/logomv.jpg", "logomv.gif");
       
    //             if ($mimemail1->send()){
    //                 $contenido1 = $this->CorreoAutorizacion($motivo, $tiempo,$cadena2, $nombre, $edad, $sexo, $diagnostico, $EdoGral, $talla, $peso, $ta, $fc,$fr,$uni,$cia,$fec);
    //                 $mimemail2->set_from("seguimiento_NoReply@medicavial.com.mx");
    //                 $mimemail2->set_to("scampos@medicavial.com.mx");
    //                 $mimemail2->add_bcc("egutierrez@medicavial.com.mx");                    
    //                 $mimemail2->set_subject("- Autorización - ".$fol);
    //                 $mimemail2->set_html($contenido1);
    //                 $mimemail2->add_attachment("../imgs/logomv.jpg", "logomv.gif");
    //                 if ($mimemail2->send()){
    //                     $contenido2 = $this->CorreoAutorizacion($motivo, $tiempo,$cadena3, $nombre, $edad, $sexo, $diagnostico, $EdoGral, $talla, $peso, $ta, $fc,$fr,$uni,$cia,$fec);
    //                     $mimemail3->set_from("seguimiento_NoReply@medicavial.com.mx");
    //                     $mimemail3->set_to("jlinares@medicavial.com.mx");
    //                     $mimemail3->add_bcc("egutierrez@medicavial.com.mx");                    
    //                     $mimemail3->set_subject("- Autorización - ".$fol);
    //                     $mimemail3->set_html($contenido2);
    //                     $mimemail3->add_attachment("../imgs/logomv.jpg", "logomv.gif");
    //                     if ($mimemail3->send()){
    //                         $contenido3 = $this->CorreoAutorizacionJuntos($motivo, $tiempo,$cadena1,$cadena2,$cadena3, $nombre, $edad, $sexo, $diagnostico, $EdoGral, $talla, $peso, $ta, $fc,$fr,$uni,$cia,$fec);
    //                         $mimemail4->set_from("seguimiento_NoReply@medicavial.com.mx");
    //                         $mimemail4->set_to("jsanchez@medicavial.com.mx");
    //                         $mimemail4->add_bcc("egutierrez@medicavial.com.mx");                    
    //                         $mimemail4->set_subject("- Autorización - ".$fol);
    //                         $mimemail4->set_html($contenido2);
    //                         $mimemail4->add_attachment("../imgs/logomv.jpg", "logomv.gif");
    //                         $respuesta = array('respuesta' => 'exito');
    //                         }
    //                         else {
    //                             $respuesta = array('respuesta' => 'error');
    //                         }   
    //                 }else {
    //                     $respuesta = array('respuesta' => 'error');
    //                 } 
                   
    //             }else {
    //                $respuesta = array('respuesta' => 'error');
    //             }                            
            
    //         }else{
    //             $respuesta = array('respuesta' => 'error');
    //         }
    //     }catch(Exception $e){
    //         //$respuesta=$e->getMessage();
    //         $respuesta = array('respuesta' =>$e->getMessage());       
    //     }    
    //      return $respuesta;
    //      $this->_db=null;    
    // }
    // 
     public function setSolicitud($fol,$usr,$datos)
    {    
        
        $motivo = $datos->motivo;
        $tiempo = $datos->tiempo;

        try{
            $sql = "SELECT MAX(CE_clave)+1 as cont FROM SolicitudCortaEstancia";
            $result = $this->_db->query($sql);
            $rs = $result->fetch();
            $cont = $rs['cont'];            
            if($cont==0||$cont==null){ $cont=1;}            
            $cadena1 = $this->RandomString(4);
            $cadena2 = $this->RandomString(4);
            $cadena3 = $this->RandomString(4);            
            $sql = "INSERT INTO SolicitudCortaEstancia(CE_clave, Exp_folio, CE_motivo, CE_bloqueo, CE_tiempo, CE_codigo1, CE_codigo2, CE_codigo3, Usu_login, Ce_fecreg,CE_codUsado)
                    VALUES(:CE_clave, :Exp_folio, :CE_motivo,'S', :CE_tiempo, :CE_codigo1, :CE_codigo2, :CE_codigo3, :Usu_login,NOW(),'SIST')";
            $temporal =$this->_db->prepare($sql);
            $temporal->bindParam("CE_clave", $cont);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("CE_motivo", $motivo);
            $temporal->bindParam("CE_tiempo", $tiempo);
            $temporal->bindParam("CE_codigo1", $cadena1);
            $temporal->bindParam("CE_codigo2", $cadena2);
            $temporal->bindParam("CE_codigo3", $cadena3);       
            $temporal->bindParam("Usu_login", $usr);            
            if($temporal->execute()){
                $respuesta = array('respuesta' =>'exito');          
            }
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;
         $this->_db=null;    
    }


    public function getAut($fol, $aut)
    {        
        try{
            $sql = "Select count(*) as contador from SolicitudCortaEstancia where Exp_folio='".$fol."' and 
            CONCAT( CE_codigo1, ' ' , CE_codigo2, ' ' , CE_codigo3)
            LIKE '%".$aut."%'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();   
            if($respuesta['contador']>=1){
                $sql="UPDATE SolicitudCortaEstancia SET CE_codUsado=:CE_codUsado where Exp_folio=:Exp_folio";
                $temporal =$this->_db->prepare($sql);
                $temporal->bindParam("CE_codUsado", $aut);
                $temporal->bindParam("Exp_folio", $fol);
                if ($temporal->execute()){
                    $respuesta = array('respuesta' => 'exito');
                }else{
                    $respuesta = array('respuesta' => 'error');
                }
            }         
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;
         $this->_db=null;    
    }

    public function getcheckValida($fol)
    {        
        try{
            $sql = "Select count(*) as contadorUsado from SolicitudCortaEstancia where Exp_folio='".$fol."' and (CE_codUsado<>'' or CE_codUsado<>0)";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();

        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;   
    }
    public function getfolioCE($fol)
    {        
        try{
            $sql = "Select CE_codUsado, Exp_folio  from SolicitudCortaEstancia where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();

        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }


    function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
    {
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($n==1) $source .= '1234567890';
        if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
        if($length>0){
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++){
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }
     
        }
        return $rstr;
        $this->_db=null;
    }

    function CorreoAutorizacion($motivo, $tiempo,$auto,$nombre, $edad, $sexo, $diagnostico, $EdoGral, $talla, $peso, $ta, $fc,$fr,$uni,$cia,$fec){

        $cont='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th align="center" colspan="5" bordercolor="#3f81ba" style="background:#3f81ba;font-size:40px; color:white;"\>
                                                Autorizaci&oacute;n para documentos de corta estancia
                                        </th>
                                    </tr>

                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                Motivo
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            Motivo:'.$motivo.'</b>
                                        </td>   
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="2">
                                            tiempo: <b>'.$tiempo.'</b>
                                        </td>                                                                                                        
                                    </tr> 
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                Datos Generales
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            Unidad:'.$uni.'</b>
                                        </td>   
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Compa&ntilde;&iacute;a: <b>'.$cia.'</b>
                                        </td>        
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Fecha registro: <b>'.$fec.'</b>
                                        </td>                                                                            
                                    </tr>   
  
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                Datos del paciente
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            Nombre:'.$nombre.'</b>
                                        </td>   
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Sexo: <b>'.$sexo.'</b>
                                        </td>        
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Edad: <b>'.$edad.'</b>
                                        </td>                                                                            
                                    </tr>   
                                                                        
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                         
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                Signos Vitales
                                        </th>
                                    </tr> 
                                    <tr>
                                        <td style="
                                           text-align: left;
                                           vertical-align: top;
                                            background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Talla: <b>'.$talla.'</b>
                                        </td>   
                                        <td style=" 
                                           text-align: left;
                                           vertical-align: top;
                                            background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Peso: <b>'.$peso.'</b>
                                        </td>        
                                        <td style=" 
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Tension Arterial: <b>'.$ta.'</b>
                                        </td>   
                                        <td style=" 
                                           text-align: left;
                                           vertical-align: top;
                                          background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Frecuencia Cardiaca: <b>'.$fc.'</b>
                                        </td>   
                                        <td style="
                                           text-align: left;
                                           vertical-align: top;
                                            background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Frecuencia Respiratoria: <b>'.$fr.'</b>
                                        </td>                                                                            
                                    </tr>  
                                     <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                Diagn&oacute;stico y Estado general y exploraci&oacute;n f&iacute;sica
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                             background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Diagn&oacute;stico: <b>'.$diagnostico.'</b>
                                        </td>                                                                            
                                    </tr>         
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Estado general y exploraci&oacute;n f&iacute;sica: '.$EdoGral.'
                                        </td>                                                                            
                                    </tr>
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                N&uacute;mero de Autorizaci&oacute;n
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 1em;
                                           caption-side: bottom; font-size:40px; color:#3f81ba;text-align: center;" colspan="5">
                                            <b>'.$auto.'</b>
                                        </td>                                                                            
                                    </tr>                                                                                                    
                                </table>
                                </BODY>
                                </HTML> ';

    return $cont;
    $this->_db=null;

    }

    function CorreoAutorizacionJuntos($motivo, $tiempo,$auto,$auto1,$auto2,$nombre, $edad, $sexo, $diagnostico, $EdoGral, $talla, $peso, $ta, $fc,$fr){

        $cont='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th align="center" colspan="5" bordercolor="#3f81ba" style="background:#3f81ba;font-size:40px; color:white;"\>
                                                Autorizaci&oacute;n para documentos de corta estancia
                                        </th>
                                    </tr>

                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                Motivo
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            Motivo:'.$motivo.'</b>
                                        </td>   
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="2">
                                            tiempo: <b>'.$tiempo.'</b>
                                        </td>                                                                                                        
                                    </tr>   
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                Datos del paciente
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            Nombre:'.$nombre.'</b>
                                        </td>   
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Sexo: <b>'.$sexo.'</b>
                                        </td>        
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Edad: <b>'.$edad.'</b>
                                        </td>                                                                            
                                    </tr>   
                                    
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                         
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                Signos Vitales
                                        </th>
                                    </tr> 
                                    <tr>
                                        <td style="
                                           text-align: left;
                                           vertical-align: top;
                                            background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Talla: <b>'.$talla.'</b>
                                        </td>   
                                        <td style=" 
                                           text-align: left;
                                           vertical-align: top;
                                            background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Peso: <b>'.$peso.'</b>
                                        </td>        
                                        <td style=" 
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Tension Arterial: <b>'.$ta.'</b>
                                        </td>   
                                        <td style=" 
                                           text-align: left;
                                           vertical-align: top;
                                          background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Frecuencia Cardiaca: <b>'.$fc.'</b>
                                        </td>   
                                        <td style="
                                           text-align: left;
                                           vertical-align: top;
                                            background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Frecuencia Respiratoria: <b>'.$fr.'</b>
                                        </td>                                                                            
                                    </tr>  
                                     <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                Diagn&oacute;stico y Estado general y exploraci&oacute;n f&iacute;sica
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                             background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Diagn&oacute;stico: <b>'.$diagnostico.'</b>
                                        </td>                                                                            
                                    </tr>         
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Estado general y exploraci&oacute;n f&iacute;sica: '.$EdoGral.'
                                        </td>                                                                            
                                    </tr>
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                N&uacute;mero de Autorizaci&oacute;n
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 1em;
                                           caption-side: bottom; font-size:40px; color:#3f81ba;text-align: center;" colspan="5">
                                            <b>Sergio Cisneros:  '.$auto.'</b><br>
                                            <b>Salomon Campos:   '.$auto1.'</b><br>
                                            <b>Jorge Linares:    '.$auto2.'</b><br>
                                        </td>                                                                            
                                    </tr>                                                                                                    
                                </table>
                                </BODY>
                                </HTML> ';

    return $cont;
    $this->_db=null;

    }

}
?>