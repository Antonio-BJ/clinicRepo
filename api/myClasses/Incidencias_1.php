<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';
/**
*  clase para el control de incidencias 
*/
class Incidencias extends Modelo
{	
    public $mimemail;
	function __construct()
	{
		 parent::__construct();
         
	}
/************************************************************************************************************************************/
/************************       método para agregar incidencias dependiendo del usuario y la unuidad                   **************/
/************************************************************************************************************************************/
    public function setIncidencia($usuario, $unidad, $datos)
    {
        $tipo= $datos->tipo;
        $severidad= $datos->severidad;
        $observaciones= $datos->observaciones;
        $acciones     = $datos->acciones;
        $uniIncidencia= $datos->uniIncidencia;

        $cveIncidencia = $this->generateRandomString(5);

        $fecha = date('d-m-Y');
        $hora  = date('h:i a');
        try{
            $query="Insert into Incidente(Usu_login, Uni_clave, Tipin_clave, Inc_obs, Inc_fecha, Inc_hora, Inc_severidad,Inc_acciones, Inc_autIncidencia,uniIncidencia)
                               Values('".$usuario."',".$unidad.",".$tipo.",'".$observaciones."','".$fecha."','".$hora."',".$severidad.",'".$acciones."','".$cveIncidencia."',".$uniIncidencia.")";
            $result = $this->_db->query($query);
            return $cveIncidencia;
        }catch(Exception $e){
            return 'error';
        }
        $this->_db=null;
    }

/************************************************************************************************************************************/
/************************       método de envío de correo de incidencia dependiendo la severidad y el tipo             **************/
/************************************************************************************************************************************/

    public function sendIncidencia($usuario, $unidad, $datos,$cveIncidencia)
    {
        $mimemail = new nomad_mimemail();
        $tipo= $datos->tipo;
        $severidad= $datos->severidad;
        $observaciones= $datos->observaciones;
        $acciones     = $datos->acciones;
        $uniIncidencia= $datos->uniIncidencia;
        $fecha = date('d-m-Y');
        $hora  = date('h:i a');
        $correos=array();

        switch ($tipo) {
            case '1':
                $nombreTipo='Equipo Rx';
                $correos=array("egutierrez@medicavial.com.mx", "sistemasrep2@medicavial.com.mx");
                break;
            case '2':
                $nombreTipo='Personal';
                break;
            case '3':
                $nombreTipo='Equipo de Cómputo';
                break;
            case '4':
                $nombreTipo='Sistema';
                break;
            case '5':
                $nombreTipo='Medicamentos';
                break;
            case '6':
                $nombreTipo='Ortesis';
                break;              
            case '7':
                $nombreTipo='Médico';
                break;              
            case '8':
                $nombreTipo='Cabina';
                break;            
            case '9':
                $nombreTipo='Rehabilitación';
                break;              
            case '10':
                $nombreTipo='Normatividad';
                break; 
            case '11':
                $nombreTipo='Mantenimiento';
                break; 
            case '12':
                $nombreTipo='Otro';
                break;
            case '13':
                $nombreTipo='Coordinación Médica';
                break;
            case '14':
                $nombreTipo='Respuesta de Unidad';
                break;
            case '15':
                $nombreTipo='Cobranza';
                break;
            case '16':
                $nombreTipo='Atención Autorizada sin Pase Médico';
                break;
            case '17':
                $nombreTipo='Rehabilitación Innecesaria';
                break; 
            case '18':
                $nombreTipo='Receta';
                break;
             case '19':
                $nombreTipo='Cobranza Unidad';
                break;                                   
        }    

        switch ($severidad) {
            case '1':
                $severidadNom='baja';
                break;
            case '2':
                $severidadNom='regular';
                break;
            case '3':
                $severidadNom='alta';
                break;        
        }
         switch ($unidad) {
            case '1':
                $correoOrigen='mvroma@medicavial.com.mx';                
                break;
            case '2':
                $correoOrigen='mvsatelite@medicavial.com.mx';                
                break;
            case '3':
                $correoOrigen='mvperisur@medicavial.com.mx';                
                break;
            case '4':
                $correoOrigen='mvpuebla@medicavial.com.mx';                
                break;
            case '5':
                $correoOrigen='mvmonterrey@medicavial.com.mx';                
                break;
            case '6':
                $correoOrigen='mvmerida@medicavial.com.mx';                
                break;
            case '7':
                $correoOrigen='mvsanluis@medicavial.com.mx';                
                break;
            case '8':
                $correoOrigen='mvoperacion@medicavial.com.mx';                
                break;
            case '86':
                $correoOrigen='mvchihuahua@medicavial.com.mx';                
                break;
            case '184':
                $correoOrigen='mvinterlomas@medicavial.com.mx';                
                break;         
            case '186':
                $correoOrigen='mvveracruz@medicavial.com.mx';                
                break;         
        }
        try{
        $query="select Usu_nombre, Uni_nombrecorto from Usuario
                inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave 
                where Usuario.Usu_login='".$usuario."'";
        $result = $this->_db->query($query);
        $rs = $result->fetch();
         }catch(Exception $e){
            return $e->getMessage();
        }
        $contenido='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th  align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            INCIDENCIA
                                        </th>
                                        <th  align="center" style=" width: 25%; background: #eee;
                                           text-align: right;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            CLAVE DE INCIDENCIA: '.$cveIncidencia.'
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Unidad: <b>'.utf8_decode($rs['Uni_nombrecorto']).'</b>
                                        </td>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Usuario: <b>'.utf8_decode($rs['Usu_nombre']).'</b>
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            Tipo de incidencia: <b>'.$nombreTipo.'</b>
                                        </td>
                                        <td >
                                            Severidad de incidencia: <b>'.$severidadNom.'</b>
                                        </td>                                       
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Observaciones: <b>'.utf8_decode($observaciones).'</b>
                                        </td>                                        
                                    </tr>';                                        
                                        if($acciones){
                                            $contenido.='<tr><td colspan="2">
                                            Acciones tomadas (soluci&oacute;n): <b>'.utf8_decode($acciones).'</b>
                                        </td></tr>';
                                        }
                                        if($uniIncidencia){
                                             $query="select Uni_nombrecorto from Undiad                                                    
                                                    where Uni_clave='".$uniIncidencia."'";
                                            $result = $this->_db->query($query);
                                            $rs = $result->fetch();
                                            $uniNom = $rs['Uni_nombrecorto'];
                                            $contenido.='<tr><td colspan="2">
                                            Unidad : <b>'.utf8_decode($acciones).'</b>
                                        </td></tr>';
                                        }
                                $contenido.='                                       
                                </table>
                                </BODY>
                                </HTML>         
                ';
        $mimemail->set_from($correoOrigen);        
         if($tipo==1){
            $mimemail->set_to("jabraham@medicavial.com.mx");
            $mimemail->set_cc("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("elanderos@medicavial.com.mx");  
            $mimemail->add_cc("coordenf@medicavial.com.mx"); 
            $mimemail->add_cc("alozano@medicavial.com.mx");            
            $mimemail->add_cc("mcelaya@medicavial.com.mx");
            $mimemail->add_cc("egutierrez@medicavial.com.mx");        
         }elseif($tipo==2){
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("msanchez@medicavial.com.mx");
            $mimemail->add_cc("mvclinicas@medicavial.com.mx");
            $mimemail->add_cc("coordenf@medicavial.com.mx"); 
            $mimemail->add_cc("alozano@medicavial.com.mx");  
            
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==3) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("clinicasoporte@medicavial.com.mx");
            $mimemail->add_cc("soportemv@medicavial.com.mx");
            $mimemail->add_cc("mvclinicas@medicavial.com.mx");
            $mimemail->add_cc("coordenf@medicavial.com.mx");  
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("soportemv@agenciageek.com"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==4) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("mvclinicas@medicavial.com.mx");
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("enriqueerick@gmail.com");
            $mimemail->add_cc("soportemv@agenciageek.com"); 
            $mimemail->add_cc("enriqueerick@gmail.com");
         }elseif ($tipo==5) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx");           
            $mimemail->add_cc("coordenf@medicavial.com.mx");          
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==6) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx");
            $mimemail->add_cc("coordenf@medicavial.com.mx"); 
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==7) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("jlinares@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==8) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("dbermudez@medicavial.com.mx");
            $mimemail->add_cc("jlinares@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==9) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("coordreh@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==10) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");            
            $mimemail->add_cc("elanderos@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx");             
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==11) {
            mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->add_cc("mvclinicas@medicavial.com.mx");
            $mimemail->add_cc("coordenf@medicavial.com.mx"); 
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("mcelaya@medicavial.com.mx"); 
            // $mimemail->add_cc("scampos@medicavial.com.mx");  
            $mimemail->add_cc("mantenimiento@medicavial.com.mx");         
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }elseif ($tipo==12) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");            
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("mcelaya@medicavial.com.mx");         
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         } elseif ($tipo==13) {
            //$mimemail->set_to("jlinares@medicavial.com.mx");
            //$mimemail->add_cc("agutierrez@medicavial.com.mx");
            $mimemail->set_to("egutierrez@medicavial.com.mx");      
         } elseif ($tipo==14) {
            $mimemail->set_to("scisneros@medicavial.com.mx");
            // $mimemail->add_cc("scampos@medicavial.com.mx");  
            $mimemail->add_cc("agutierrez@medicavial.com.mx");            
            $mimemail->add_cc("dbermudez@medicavial.com.mx");
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         } elseif ($tipo==15) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");  
            $mimemail->add_cc("mvclinicas@medicavial.com.mx");            
            $mimemail->add_cc("chernandez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         } elseif ($tipo==16) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");  
            $mimemail->add_cc("mvclinicas@medicavial.com.mx"); 
            $mimemail->add_cc("alozano@medicavial.com.mx");      
            $mimemail->add_cc($correoOrigen);
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         } elseif ($tipo==17) {
            $mimemail->add_cc("scisneros@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");                          
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx");                    
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
         }  elseif ($tipo==18) {
            $mimemail->set_to("alozano@medicavial.com.mx");                            
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
            $mimemail->add_cc("mvcompras@medicavial.com.mx");
         }  elseif ($tipo==19) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("scisneros@medicavial.com.mx");  
            $mimemail->add_cc("mvclinicas@medicavial.com.mx");            
            $mimemail->add_cc("chernandez@medicavial.com.mx");
            $mimemail->add_cc("cnolasco@medicavial.com.mx");
            $mimemail->add_cc("auxtesoreria@medicavial.com.mx");
            $mimemail->add_cc("agutierrez@medicavial.com.mx");            
            $mimemail->add_cc("egutierrez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx"); 
         } elseif ($tipo==20) {
            $mimemail->set_to("jsanchez@medicavial.com.mx");
            $mimemail->add_cc("alozano@medicavial.com.mx");     
            $mimemail->add_cc("scisneros@medicavial.com.mx");              
            $mimemail->add_cc("agutierrez@medicavial.com.mx");            
            $mimemail->add_cc("egutierrez@medicavial.com.mx");  
            $mimemail->add_cc($uniRec);               
         }                   
        //$mimemail->set_cc('agutierrez@medicavial.com.mx');   
        //$mimemail->add_cc('egutierrez@medicavial.com.mx');        
        $mimemail->set_subject("- Incidencia - ".$rs['Uni_nombrecorto']." - ".$rs['Usu_nombre']);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        
        if ($mimemail->send()){
            return 'exito';
           
        }else {
            return 'error';
        }  
        $this->_db=null;      
    }


    /************************************************************************************************************************************/
/************************       método de envío de correo de incidencia dependiendo la severidad y el tipo             **************/
/************************************************************************************************************************************/

    public function mandaCorreo($usuario, $unidad, $datos)
    {
        $mimemail = new nomad_mimemail();
        $tipo= $datos->tipo;
        $severidad= $datos->severidad;
        $observaciones= $datos->observaciones;
        $fecha = date('d-m-Y');
        $hora  = date('h:i a');
        $correos=array();
        $noMembresia= '003804';
        $cve=$this->encriptar($noMembresia);
      
      
      
        $contenido='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                               <table  WIDTH=1000 style="border:1px;" align="center">
                               <tr>
                               <td align="center">
                                <table  WIDTH=800 style="font-size:20px">
                                    <tr>
                                        <td align="center" WIDTH=400>
                                        <p  align="justify" style="color:#636465;font-size;20px">
                                           Tu <b>Membres&iacute;a Familiar M&eacute;dicaVial</b> est&aacute; lista. Para hacer uso de ella ya solo resta activarla, para ello solo tienes que acudir a cualquiera de nuestras cl&iacute;nicas a recibir atenci&oacute;n m&eacute;dica u oprimir el siguiente enlace.<br><br>
                                                <a href="medicavial.net/mvnuevo/api/confirmacion.php?cve='.$cve.'">Activar membres&iacute;a</a>         
                                        </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td WIDTH="400">
                                        <img src="frentearriba.gif" width="400">
                                        <br>
                                           <span class="text_encima" style="padding: 0 0 0 50px;font-size: 15px;font-family: arial;"><b>002007 - MFO</b></span><br>
                                            <span class="text_encima" style="padding: 0 0 0 50px;font-size: 15px;font-family: arial;"><b>Enrique Erick Gutiérrez Rojas</b></span><br>
                                             <img src="frenteabajo.gif" width="400"><br>
                                            <span class="text_encima" style="padding: 0em 0 0 320px;font-size: 15px;font-family: arial;"><b>2016</b></span><br>
                                            <img src="frentefin.gif" width="400">
                                            <p align="justify"  style="font-size:18px; color:#636465"><b>TERMINOS Y CONDICIONES.</b><br><br>   
                                                Esta membres&iacute;a - salvo que haya sido cancelada por el otorgante MEDICAVIAL o la operadora de este servicio ORTHOFAM, S.A. DE C.V. - acreditan al tarjetahabiente y sus familiares directos, con descuentos permanentes en todas las instalaciones propiedad de MEDICAVIAL. Para dudas o comentarios relativos a su MEMBRESIA FAMILIAR MEDICAVIAL, favor de comunicarse a nuestros centros de atenci&oacute;n de clientes al 01-800-3 MEDICA (633422) o a www.medicavial.com  . Favor de presentar su tarjeta MEMBRESIA FAMILIAR MEDICAVIAL  o el n&uacute;mero de la misma, en la recepci&oacute;n de MEDICAVIAL antes de recibir tratamiento m&eacute;dico. Esto le generar&aacute; el descuento pactado en los paquetes y ventas por suministro antes de que se genere su factura. La asistencia M&eacute;dica Telef&oacute;nica y/o por chat y/o por medios electr&oacute;nicos es &uacute;nicamente una orientaci&oacute;n m&eacute;dica y no se emite diagn&oacute;stico remotamente. Esta MEMBRESIA FAMILIAR MEDICAVIAL es &uacute;nica y los descuentos permanentes y beneficios, no son acumulables con el tiempo ni con otras promociones. Los descuentos de la MEMBRESIA FAMILIAR MEDICAVIAL no aplican en paquetes, promociones, servicios, ni art&iacute;culos previamente rebajados. MEDICAVIAL SA DE CV Especialistas en Huesos y articulaciones, atiende &uacute;nicamente emergencias menores o padecimientos de ortopedia y traumatologiacute;a que no pongan en riesgo la vida ni la funci&oacute;n de las personas. No se atienden emergencias mayores ni otras especialidades m&eacute;dicas. Para conocer las condiciones generales completas, beneficios y exclusiones de la MEMBRESIA FAMILIAR MEDICAVIAL, as&iacute; como para conocer el aviso de privacidad de MEDICAVIAL y la operadora de membres&iacute;a ORTHOFAM, SA DE CV, favor de consultar estas condiciones en el sitio www.medicavial.com . </p>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td WIDTH="400" align="center">
                                                <img src="p1.gif" width="600"><br>
                                                <img src="p2.gif" width="600">
                                        </td>
                                    </tr>                                    
                                    
                                </table>
                                </td>
                                </tr>
                                </table>
                                </BODY>
                                </HTML>           
                ';
        $mimemail->set_from("seguimiento_NoReply@medicavial.com.mx");        
        
        $mimemail->set_to('egutierrez@medicavial.com.mx');   
        //$mimemail->add_cc('sistemasrep2@medicavial.com.mx');        
        $mimemail->set_subject("prueba imagen");
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/convenio/membresiaMV/frentearriba.png", "frentearriba.gif");        
        $mimemail->add_attachment("../imgs/convenio/membresiaMV/frenteabajo.png", "frenteabajo.gif");        
        $mimemail->add_attachment("../imgs/convenio/membresiaMV/frentefin.png", "frentefin.gif");        
        $mimemail->add_attachment("../imgs/convenio/membresiaMV/1.png", "p1.gif");
        $mimemail->add_attachment("../imgs/convenio/membresiaMV/2.png", "p2.gif");
        
        if ($mimemail->send()){
            return 'exito';
           
        }else {
            return 'error';
        }        
    }

    /************************************************************************************************************************************/
/************************       método de envío de correo de incidencia dependiendo la severidad y el tipo             **************/
/************************************************************************************************************************************/

    public function enviaIncidenciaAdjunto($usr,$uni,$tipo,$severidad,$obs,$arc,$tem,$archivo,$acciones)
    {
        $mimemail = new nomad_mimemail();  
        $cveIncidencia = $this->generateRandomString(5);

        $fecha = date('d-m-Y');
        $hora  = date('h:i a');
        try{
            $query="Insert into Incidente(Usu_login, Uni_clave, Tipin_clave, Inc_obs, Inc_fecha, Inc_hora, Inc_severidad,Inc_acciones, Inc_autIncidencia)
                               Values('".$usr."',".$uni.",".$tipo.",'".$obs."','".$fecha."','".$hora."',".$severidad.",'".$acciones."','".$cveIncidencia."')";
            $result = $this->_db->query($query);            
        }catch(Exception $e){
            return 'error';
        }            
        switch ($tipo) {
            case '1':
                $nombreTipo='Equipo Rx';                
                break;
            case '2':
                $nombreTipo='Personal';
                break;
            case '3':
                $nombreTipo='Equipo de Cómputo';
                break;
            case '4':
                $nombreTipo='Sistema';
                break;
            case '5':
                $nombreTipo='Medicamentos';
                break;
            case '6':
                $nombreTipo='Ortesis';
                break;              
            case '7':
                $nombreTipo='Médico';
                break;              
            case '8':
                $nombreTipo='Cabina';
                break;            
            case '9':
                $nombreTipo='Rehabilitación';
                break;              
            case '10':
                $nombreTipo='Normatividad';
                break; 
            case '11':
                $nombreTipo='Mantenimiento';
                break; 
            case '12':
                $nombreTipo='Otro';
                break; 
            case '13':
                $nombreTipo='Coordinación Médica';
                break;
            case '14':
                $nombreTipo='Respuesta de Unidad';
                break;  
            case '15':
                $nombreTipo='Cobranza';
                break;
            case '16':
                $nombreTipo='Atención Autorizada sin Pase Médico';
                break;
            case '17':
                $nombreTipo='Rehabilitación Innecesaria';
                break;                           
        }    

        switch ($severidad) {
            case '1':
                $severidadNom='baja';
                break;
            case '2':
                $severidadNom='regular';
                break;
            case '3':
                $severidadNom='alta';
                break;        
        }
        switch ($uni) {
            case '1':
                $correoOrigen='mvroma@medicavial.com.mx';                
                break;
            case '2':
                $correoOrigen='mvsatelite@medicavial.com.mx';                
                break;
            case '3':
                $correoOrigen='mvperisur@medicavial.com.mx';                
                break;
            case '4':
                $correoOrigen='mvpuebla@medicavial.com.mx';                
                break;
            case '5':
                $correoOrigen='mvmonterrey@medicavial.com.mx';                
                break;
            case '6':
                $correoOrigen='mvmerida@medicavial.com.mx';                
                break;
            case '7':
                $correoOrigen='mvsanluis@medicavial.com.mx';                
                break;
            case '8':
                $correoOrigen='mvoperacion@medicavial.com.mx';                
                break;
            case '86':
                $correoOrigen='mvchihuahua@medicavial.com.mx';                
                break;
            case '184':
                $correoOrigen='mvinterlomas@medicavial.com.mx';                
                break;         
            case '186':
                $correoOrigen='mvveracruz@medicavial.com.mx';                
                break;         
        }
        try{
        $query="select Usu_nombre, Uni_nombrecorto from Usuario
                inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave 
                where Usuario.Usu_login='".$usr."'";
        $result = $this->_db->query($query);
        $rs = $result->fetch();
         }catch(Exception $e){
            return $e->getMessage();
        }
        $contenido='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            INCIDENCIA
                                        </th>
                                        <th  align="center" style=" width: 25%; background: #eee;
                                           text-align: right;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            CLAVE DE INCIDENCIA: '.$cveIncidencia.'
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Unidad: <b>'.$rs['Uni_nombrecorto'].'</b>
                                        </td>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Usuario: <b>'.$rs['Usu_nombre'].'</b>
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            Tipo de incidencia: <b>'.utf8_decode($nombreTipo).'</b>
                                        </td>
                                        <td >
                                            Severidad de incidencia: <b>'.utf8_decode($severidadNom).'</b>
                                        </td>                                       
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Observaciones: <b>'.utf8_decode($obs).'</b>
                                        </td>                                        
                                    </tr>';                                        
                                        if($acciones!=undefined||$acciones!=''){
                                            $contenido.='<tr><td colspan="2">
                                            Acciones tomadas (soluci&oacute;n): <b>'.utf8_decode($acciones).'</b>
                                        </td></tr>';
                                        }
                                $contenido.='                                   
                                </table>
                                </BODY>
                                </HTML>         
                ';

        /********************************* adjuntos *************************************/
        if($tem!=''||$tem!=NULL){
            if ($_FILES['file']["error"] > 0){
                return 'error';
            }else{
                if($archivo["type"] == "application/pdf" || $archivo["type"] == "image/jpeg" || $archivo["type"] == "image/gif" || $archivo["type"] == "image/png" || $archivo["type"] == "image/bmp" || $archivo["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $archivo["type"] =="application/msword" || $archivo["type"] == "image/pjpeg"){

                    $partes=explode(".",$archivo["name"]);
                    move_uploaded_file($archivo["tmp_name"],"incidencias/".$archivo["name"]);
                     /********************************************************************************/
                    $mimemail->set_from($correoOrigen);        
                    //$mimemail->set_to("egutierrez@medicavial.com.mx"); 
                    if($tipo==1){
                        $mimemail->set_to("jabraham@medicavial.com.mx");
                        $mimemail->set_cc("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("elanderos@medicavial.com.mx");  
                        $mimemail->add_cc("coordenf@medicavial.com.mx"); 
                        $mimemail->add_cc("alozano@medicavial.com.mx");            
                        $mimemail->add_cc("mcelaya@medicavial.com.mx");
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");        
                     }elseif($tipo==2){
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("msanchez@medicavial.com.mx");
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
                        $mimemail->add_cc("coordenf@medicavial.com.mx"); 
                        $mimemail->add_cc("alozano@medicavial.com.mx");  
                        
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==3) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("clinicasoporte@medicavial.com.mx");
                        $mimemail->add_cc("soportemv@medicavial.com.mx");
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
                        $mimemail->add_cc("coordenf@medicavial.com.mx");  
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("soportemv@agenciageek.com"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==4) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("enriqueerick@gmail.com");
                        $mimemail->add_cc("soportemv@agenciageek.com"); 
                        $mimemail->add_cc("enriqueerick@gmail.com");
                     }elseif ($tipo==5) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx");           
                        $mimemail->add_cc("coordenf@medicavial.com.mx");          
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==6) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx");
                        $mimemail->add_cc("coordenf@medicavial.com.mx"); 
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==7) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("jlinares@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==8) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("dbermudez@medicavial.com.mx");
                        $mimemail->add_cc("jlinares@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==9) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("coordreh@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==10) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");            
                        $mimemail->add_cc("elanderos@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx");             
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==11) {
                        mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
                        $mimemail->add_cc("coordenf@medicavial.com.mx"); 
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("mcelaya@medicavial.com.mx"); 
                        // $mimemail->add_cc("scampos@medicavial.com.mx");  
                        $mimemail->add_cc("mantenimiento@medicavial.com.mx");         
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }elseif ($tipo==12) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");            
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("mcelaya@medicavial.com.mx");         
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     } elseif ($tipo==13) {
                        //$mimemail->set_to("jlinares@medicavial.com.mx");
                        //$mimemail->add_cc("agutierrez@medicavial.com.mx");
                        $mimemail->set_to("egutierrez@medicavial.com.mx");      
                     } elseif ($tipo==14) {
                        $mimemail->set_to("scisneros@medicavial.com.mx");
                        // $mimemail->add_cc("scampos@medicavial.com.mx");  
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");            
                        $mimemail->add_cc("dbermudez@medicavial.com.mx");
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     } elseif ($tipo==15) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");  
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");            
                        $mimemail->add_cc("chernandez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     } elseif ($tipo==16) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");  
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx"); 
                        $mimemail->add_cc("alozano@medicavial.com.mx");      
                        $mimemail->add_cc($correoOrigen);
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     } elseif ($tipo==17) {
                        $mimemail->add_cc("scisneros@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");                          
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx");                    
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                     }  elseif ($tipo==18) {
                        $mimemail->set_to("alozano@medicavial.com.mx");                            
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("mvcompras@medicavial.com.mx");
                     }  elseif ($tipo==19) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("scisneros@medicavial.com.mx");  
                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");            
                        $mimemail->add_cc("chernandez@medicavial.com.mx");
                        $mimemail->add_cc("cnolasco@medicavial.com.mx");
                        $mimemail->add_cc("auxtesoreria@medicavial.com.mx");
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");            
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx"); 
                     } elseif ($tipo==20) {
                        $mimemail->set_to("jsanchez@medicavial.com.mx");
                        $mimemail->add_cc("alozano@medicavial.com.mx");     
                        $mimemail->add_cc("scisneros@medicavial.com.mx");              
                        $mimemail->add_cc("agutierrez@medicavial.com.mx");            
                        $mimemail->add_cc("egutierrez@medicavial.com.mx");  
                        $mimemail->add_cc($uniRec);               
                     }                                  
                       
                    //$mimemail->set_cc('agutierrez@medicavial.com.mx');   
                    //$mimemail->add_cc('egutierrez@medicavial.com.mx');        
                    $mimemail->set_subject("- Incidencia - ".$rs['Uni_nombrecorto']." - ".$rs['Usu_nombre']);
                    $mimemail->set_html($contenido);
                    $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
                    $mimemail->add_attachment("incidencias/".$archivo["name"],$archivo["name"]);
                    
                    if ($mimemail->send()){
                        return 'exito';
                       
                    }else {
                        return 'error';
                    }        
                   
                } 

            }
        }else{
            echo "va sin adjunto";
        }
       $this->_db=null;
    }

    protected function encriptar($clave){
        $algorithm = MCRYPT_BLOWFISH;
        $key = 'That golden key that opens the palace of eternity.';        
        $mode = MCRYPT_MODE_CBC;

        $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode),
                               MCRYPT_DEV_URANDOM);

        $encrypted_data = mcrypt_encrypt($algorithm, $key, $clave, $mode, $iv);
        $plain_text = base64_encode($encrypted_data);
        return $plain_text;

       
    }
    protected function desencriptar($clave){
        $algorithm = MCRYPT_BLOWFISH;
        $key = 'That golden key that opens the palace of eternity.';        
        $mode = MCRYPT_MODE_CBC;

        $iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode),
                               MCRYPT_DEV_URANDOM);
        $encrypted_data = base64_decode($clave);
        $decoded = mcrypt_decrypt($algorithm, $key, $encrypted_data, $mode, $iv);
        return $decoded;

    }

    /************************************************************************************************************************************/
/************************       método de envío de correo de incidencia  de falla en conexión en el sistema de Inventarios             **************/
/************************************************************************************************************************************/

    public function mandaIncidenciaInvent($fol,$uni,$usr)
    {
        $mimemail = new nomad_mimemail();        
        $fecha = date('d-m-Y');
        $hora  = date('h:i a');               
        try{
        $query="select Usu_nombre, Uni_nombrecorto from Usuario
                inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave 
                where Usuario.Usu_login='".$usr."'";
        $result = $this->_db->query($query);
        $rs = $result->fetch();
         }catch(Exception $e){
            return $e->getMessage();
        }
      
    
        $contenido='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th colspan="6" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                             Incidencia de conexi&oacute;n con el sistema de inventario
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                        No se pudo conectar al sistema de inventario en la Unidad <b>'.$rs['Uni_nombrecorto'].' </b>con el Usuario <b>'.$rs['Usu_nombre'].'</b> y el Folio <b>'.$fol.'</b>
                                        </td>
                                    </tr>
                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';
        $mimemail->set_from("seguimiento_NoReply@medicavial.com.mx");        
        
        $mimemail->set_to('egutierrez@medicavial.com.mx');   
        //$mimemail->add_cc('egutierrez@medicavial.com.mx');        
        $mimemail->set_subject("Incidencia de Inventario");
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        
        if ($mimemail->send()){
            return 'exito';
           
        }else {
            return 'error';
        }        
    }


     /************************************************************************************************************************************/
/************************       método de envío de correo de incidencia dependiendo la severidad y el tipo             **************/
/************************************************************************************************************************************/

            
    public function UniSubsecuencia()
    {
        $query="select Usuario.Uni_clave, Exp_folio, Sub_cons from Subsecuencia
                inner join Usuario on Subsecuencia.Usu_registro=Usuario.Usu_login 
                ";
        $result = $this->_db->query($query);
        $rs = $result->fetchAll();  
        //print_r($rs); 
        foreach ($rs as $key => $value) {

        	echo $value['Uni_clave'].'--'.$value['Exp_folio'].'--'.$value['Sub_cons'];            
            echo "<br>";
         	try{
	            $query="Update Subsecuencia set Uni_clave=".$value['Uni_clave']." where Exp_folio='".$value['Exp_folio']."' and Sub_cons=".$value['Sub_cons'];
	            $result = $this->_db->query($query);
            }catch(Exception $e){
              	$error='error';
            }           
        }
    }
    // funcion para obtener una cadena aleatoria de n longitud
    protected function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
 ?>