<?php 



require_once "Modelo.php";

// clase para el envío de correos

require_once 'nomad_mimemail.inc.php';

/**

*  classe hacer el detalle general de Px

*/

class DetallePx extends Modelo

{

	public $mimemail;

	function __construct()

	{

		 parent::__construct();

	}



	public function getDatosPersonales($fol)

    {        

    	try{

            $sql = "Select Exp_completo,Exp_triageActual,Expediente.Cia_clave, Exp_edad,Exp_sexo, Exp_fecreg,Expediente.Pro_clave, Pro_nombre, Cia_nombrecorto, Exp_telefono, Exp_mail, Rel_clave, Exp_fechaNac, if(HOS_clave is null,'no','si') as hospitalario, Triage_nombre as triage, Exp_deducible, Exp_coaseguro, ObsNot_diagnosticoRx, Ref_nombre, if(Exp_particularMedico=1,'Sí','No') partMed

            from Expediente

            inner join Producto on Expediente.Pro_clave= Producto.Pro_clave

            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave            

            left join Hospitalario on Expediente.Exp_folio = Hospitalario.Exp_folio

            inner join TriageAutorizacion on Expediente.Exp_triageActual = TriageAutorizacion.Triage_id

            left join ObsNotaMed on Expediente.Exp_folio = ObsNotaMed.Exp_folio
                        
                        left join ObsParticulares on Expediente.Exp_folio=ObsParticulares.Exp_folio
                        
                        left join Referencia on ObsParticulares.Par_otroID=Referencia.Ref_clave

             where Expediente.Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch(PDO::FETCH_OBJ);        

        }catch(Exception $e){        	

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }



    public function getDatosPersonalesMovil($fol)

    {        

        $arrayResp = array();

    	try{

            $sql = "Select IF
            ( HA_nombre != '', HA_nombre, CA_nombreLes ) as 'Exp_completo',Exp_triageActual,Expediente.Cia_clave,IF(HA_edad!='',HA_edad,CA_edad) as 'Exp_edad',IF(HA_sexo!='',HA_sexo,CA_sexo) as 'Exp_sexo', Exp_fecreg,Expediente.Pro_clave, Pro_nombre, Cia_nombrecorto, Exp_telefono, CA_telefono, Exp_mail, Rel_clave, Exp_fechaNac,

            if(HOS_clave is null,'no','si') as hospitalario, Triage_nombre as triage, Exp_deducible, Exp_coaseguro,

            CA_telefono, HA_telAdicional, HA_dolencia, IF(CA_medio=1, 'VIDEOLLAMADA', 'LLAMADA')HA_medio

            from Expediente

            inner join Producto on Expediente.Pro_clave= Producto.Pro_clave

            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave            

            left join Hospitalario on Expediente.Exp_folio = Hospitalario.Exp_folio

            inner join TriageAutorizacion on Expediente.Exp_triageActual = TriageAutorizacion.Triage_id
            INNER JOIN ClavesAsesorias ON Expediente.Exp_folio = ClavesAsesorias.Exp_folio

            left join HistoriaAsesorias on Expediente.Exp_folio = HistoriaAsesorias.Exp_folio

             where Expediente.Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $res1 = $result->fetch(PDO::FETCH_OBJ);

            $arrayResp['datos']=$res1;       

        }catch(Exception $e){        	

            $arrayResp = array('respuesta' =>'error');       

        }    

        try{

            $sql = "select Exp_folio , Ale_nombre alergia, Ale_obs obs from HistoriaAlergias 

                    inner join Alergias on HistoriaAlergias.Ale_clave = Alergias.Ale_clave

                    where Exp_folio='".$fol."' order by Ale_orden";

            $result = $this->_db->query($sql);

            $res2 = $result->fetchAll(PDO::FETCH_OBJ);

            $arrayResp['alergias']=$res2;            

        }catch(Exception $e){        	

            $arrayResp = array('respuesta' =>'error');       

        }    

         return $arrayResp; 

         $this->_db=null;   

    }

/////////////////////////////////////////

    public function getMedico($fol)

    {        

        try{

            $sql = "SELECT Exp_folio, Usu_nombre, UPPER( CONCAT(Med_nombre,' ',Med_paterno,' ',Med_materno)) as Medico

                        from NotaMedica

                        inner join Medico on NotaMedica.Usu_nombre=Medico.Usu_login      

                        where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;    

    }

    public function getDiagActual($fol)

    {        

        try{

            $sql = "SELECT ObsNot_diagnosticoMomento 

                        from ObsNotaMed

                        where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;    

    }

/////////////////////////////////////////   

    public function getSignosVitales($fol)

    {        

        try{

            $sql = "select Vit_fecha as fecha from Vitales where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }

     public function getHistoriaClinica($fol)

    {        

        try{

            $sql = "select Con_fecha as fecha from Consulta where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }

     public function getNotaMedica($fol)

    {        

        try{

            $sql = "select Not_estatus as estatus,Not_fechareg as fecha from NotaMedica where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;   

         $this->_db=null; 

    }

     public function getsubsecuencias($fol)

    {        

        try{

            $sql = "select Sub_fecha as fecha, Sub_hora as hora from Subsecuencia where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }



    public function getRehabilitaciones($fol)

    {        

        try{

            $sql = "select Rehab_fecha as fecha from Rehabilitacion where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }



    public function getAviso($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeAviso where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }

    public function getConsMed($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeConsMed where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;

         $this->_db=null;    

    }

    public function getCuestionario($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeCuestionario where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }

    public function getFiniquito($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeFiniquito where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }



    public function getHistoria($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeHc where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;

         $this->_db=null;    

    }

    public function getIdentificacion($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeId where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }

    public function getInfMedico($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeIm where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta; 

         $this->_db=null;   

    }

    public function getInfAseg($fol)

    {        

        try{

            $sql = "select count(*) as contador from subeInfAse where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;

         $this->_db=null;    

    }

    public function getPaseMedico($fol)

    {        

        try{

            $sql = "select count(*) as contador from subePase where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;

         $this->_db=null;    

    }

    public function getAutorizacion($fol)

    {        

        try{

            $sql = "select AUM_clave from AutorizacionMedica where AUM_folioMV='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;

         $this->_db=null;    

    }

     public function getDiagnostico($fol)

    {        

        try{

            $sql = "select ObsNot_diagnosticoRx from ObsNotaMed where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

        }catch(Exception $e){           

            $respuesta = array('respuesta' =>'error');       

        }    

         return $respuesta;

         $this->_db=null;    

    }



    public function getModificador($folio)

    {        

        try{                                      

            $sql = "select Exp_modificador from Expediente where Exp_folio='".$folio."'";

            $result = $this->_db->query($sql);                                    

            $rs = $result->fetch();            

            if($rs){

                return $rs['Exp_modificador'];

            }else{

                return 0;

            }      

        }catch(Exception $e){

            return "error";

        } 

        $this->_db=null;       

    }



    public function registraAutoSubs($fol,$uni,$usr,$con)

    {        

        try{ 

            $sql = "SELECT count(*) as cont from AutorizacionSubsecuencia where Exp_folio='".$fol."' and Sub_cons=".$con;

            $result = $this->_db->query($sql);

            $rs = $result->fetch();

            if($rs['cont']==0){                                                                         

                $sql = "INSERT INTO AutorizacionSubsecuencia(Exp_folio,Sub_cons,Usu_registro,ASU_fecAut)

                        VALUES('".$fol."',".$con.",'".$usr."',now())";

                $result = $this->_db->query($sql);                                    

            }

            return 'exito';                  

        }catch(Exception $e){

            return "error";

        } 

        $this->_db=null;       

    }



    public function infoFolio($fol)

    {       

        $datos=array(); 

        try{ 

            $sql = "SELECT Expediente.Exp_folio as FOLIO, Exp_completo as NOMBRE, Exp_edad as EDAD, Exp_siniestro as SINIESTRO, Exp_reporte as REPORTE, Exp_poliza as POLIZA, Exp_mail as CORREO, Exp_telefono AS TELEFONO, if(Exp_sexo='F','FEMENINO','MASCULINO')  as SEXO, Exp_fechaNac2 as FECNAC, Exp_obs as OBS,

            (SELECT count(*) FROM NotaMedica WHERE NotaMedica.Exp_folio = Expediente.Exp_folio) as contNota,

            (SELECT count(*) as contaSub FROM Subsecuencia where  Subsecuencia.Exp_folio=Expediente.Exp_folio) as contaSub,

            (SELECT count(*) FROM Rehabilitacion where Rehabilitacion.Exp_folio=Expediente.Exp_folio) as contaRehab,

            Pro_nombre as PRODUCTO, Uni_nombrecorto as UNIDAD, Cia_nombrecorto as COMPANIA

            FROM Expediente 

            INNER JOIN Producto on Expediente.Pro_clave = Producto.Pro_clave

            INNER JOIN Unidad on Expediente.Uni_clave = Unidad.Uni_clave

            INNER JOIN Compania on Expediente.Cia_clave = Compania.Cia_clave

            where Exp_folio='".$fol."';";

            $result = $this->_db->query($sql);

            $rs = $result->fetch();

            $datos['datosPersonales']=$rs;

            $contNota = $rs['contNota'];

            $contSub= $rs['contaSub'];

            $contaRehab= $rs['contaRehab'];

            if($contNota>0){

                $sql = "SELECT  Not_fechareg,ObsNot_diagnosticoRx,(Select count(*) FROM NotaProcedimientos WHERE NotaProcedimientos.Exp_folio=NotaMedica.Exp_folio) as contProc, Usuario.Usu_nombre as MEDICO FROM NotaMedica 

                        INNER JOIN  ObsNotaMed ON NotaMedica.Exp_folio = ObsNotaMed.Exp_folio

                        INNER JOIN Usuario on NotaMedica.Usu_nombre = Usuario.Usu_login

                        where NotaMedica.Exp_folio='".$fol."';";

                $result = $this->_db->query($sql);

                $rs = $result->fetch();

                $datos['etapa1']=$rs;

            }

            if($rs['contProc']>0){

                $queryProc="SELECT Pro_nombre FROM NotaProcedimientos 

                            inner join Procedimientos on NotaProcedimientos.Pro_clave = Procedimientos.Pro_clave

                            WHERE Exp_folio='".$fol."'";

                $result = $this->_db->query($queryProc);

                $rs = $result->fetchAll();

                $datos['procedimientos']=$rs;

            } 

            if($contSub>0){  

                $querySub = "SELECT * FROM Subsecuencia 

                             INNER JOIN Usuario ON Subsecuencia.Usu_registro = Usuario.Usu_login

                             where Exp_folio='".$fol."'";

                $result = $this->_db->query($querySub);

                $rs = $result->fetchAll();

                $datos['subsecuencias']= $rs;

            }

            if($contaRehab>0){

                $querySub = "SELECT * FROM Rehabilitacion 

                             INNER JOIN Usuario ON Rehabilitacion.Usu_registro = Usuario.Usu_login

                             where Exp_folio='".$fol."'";

                $result = $this->_db->query($querySub);

                $rs = $result->fetchAll();

                $datos['rehabilitaciones']= $rs;  

            }



            return $datos;                  

        }catch(Exception $e){

            return $e->getMessage();

        } 

        $this->_db=null;       

    }

    public function vigenciaFolio($fol,$usr)

    {        

        try{ 

            $sql="SELECT Uni_clave, Exp_hc FROM Expediente where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $rs = $result->fetch();

            $uniFolio = $rs['Uni_clave'];

            $autorizado = $rs['Exp_hc']; 

            if($usr){            

                $sql="SELECT Uni_clave FROM Usuario where Usu_login='".$usr."'";

                $result = $this->_db->query($sql);

                $rs = $result->fetch();

                $uniUsr = $rs['Uni_clave'];  

                          

            }else{

                $uniUsr=$uniFolio;

            }



            if($uniFolio==$uniUsr){

                $sql = "SELECT DATEDIFF(now(),Exp_fecreg) as dias FROM Expediente WHERE Exp_folio='".$fol."';";

                $result = $this->_db->query($sql);

                $rs = $result->fetch();

                $diasRegistro = $rs['dias'];            



                if($diasRegistro>30){

                    $sql = "SELECT 

                            (SELECT count(*) FROM NotaMedica WHERE NotaMedica.Exp_folio = Expediente.Exp_folio) as contNota,

                            (SELECT count(*) as contaSub FROM Subsecuencia where  Subsecuencia.Exp_folio=Expediente.Exp_folio) as contaSub,

                            (SELECT count(*) FROM Rehabilitacion where Rehabilitacion.Exp_folio=Expediente.Exp_folio) as contaRehab

                            FROM Expediente                     

                            where Exp_folio='".$fol."';";

                    $result = $this->_db->query($sql);

                    $rs = $result->fetch();



                    $fecreg = $rs['registro'];

                    $nota   = $rs['contNota'];

                    $sub    = $rs['contaSub'];

                    $rehab  = $rs['contaRehab'];

                    if($rehab==0){

                        if($sub==0){

                            if($nota==0){

                                $respuesta = array('dias' => $diasRegistro, 'tipo'=>'registro','no'=>1,'autorizado'=>$autorizado);

                                return $respuesta;

                            }else{

                                $sql = "SELECT DATEDIFF(now(),Not_fechareg) as diasNota  FROM NotaMedica where Exp_folio='".$fol."'";

                                $result = $this->_db->query($sql);

                                $rs = $result->fetch();                                                    

                                $respuesta = array('dias' => $rs['diasNota'], 'tipo'=>'nota','no'=>1,'autorizado'=>$autorizado);                         

                                return $respuesta;

                            }

                        }else{

                            $sql = "SELECT DATEDIFF(now(),concat(Sub_fecha,' ',Sub_hora)) as diasSub  FROM Subsecuencia where Exp_folio='".$fol."' and Sub_cons=".$sub;

                            $result = $this->_db->query($sql);

                            $rs = $result->fetch();                        

                            $respuesta = array('dias' => $rs['diasSub'], 'tipo'=>'subsecuencia','no'=>$sub,'autorizado'=>$autorizado);

                            return $respuesta;

                        }

                    }else{

                        $sql = "SELECT DATEDIFF(now(),Rehab_fecha) as diasRehab  FROM Rehabilitacion where Exp_folio='".$fol."' and 

                        Rehab_cons=".$sub;

                        $result = $this->_db->query($sql);

                        $rs = $result->fetch();                        

                        return $rs['diasRehab'].'Rehabilitacion';                            

                        $respuesta = array('dias' => $rs['diasRehab'], 'tipo'=>'rehabilitacion','no'=>$rehab,'autorizado'=>$autorizado);

                        return $respuesta;

                    }

                }else{                

                    $respuesta = array('dias' => $diasRegistro, 'tipo'=>'registro1','no'=>1,'autorizado'=>$autorizado);

                    return $respuesta;

                }

            }else{

                $sql = "SELECT count(*) as contNota FROM NotaMedica WHERE NotaMedica.Exp_folio = '".$fol."';";

                $result = $this->_db->query($sql);

                $rs = $result->fetch();                  

                $nota   = $rs['contNota'];

                

                if($nota>0){

                    $tipo = 'reactivar';

                }else{

                    $tipo = 'registro1';

                }

                $respuesta = array('dias' => 0, 'tipo'=>$tipo,'no'=>1,'autorizado'=>$autorizado);

                return $respuesta;

            }

            

        }catch(Exception $e){

            return $e->getMessage();

        } 

        $this->_db=null;       

    }





     public function checaPerfil($usr)

    {                

        try{ 

            $sql = "SELECT Per_clave from Usuario where Usu_login='".$usr."'";

            $result = $this->_db->query($sql);

            $rs = $result->fetch();

            $perfil = $rs['Per_clave'];

            return $perfil;      

        }catch(Exception $e){

            return "error";

        } 

        $this->_db=null;       

    }



    public function checaComentarios($fol)

    {                

        try{ 

            $sql = "SELECT * from comentarioFolio 

                    INNER JOIN Usuario ON comentarioFolio.Usu_login = Usuario.Usu_login

                    where Exp_folio='".$fol."' order by CFO_id";

            $result = $this->_db->query($sql);

            $rs = $result->fetchAll();

            

            return $rs;      

        }catch(Exception $e){

            return "error";

        } 

        $this->_db=null;       

    }





    public function mandarCorreoextemporaneo($fol,$dias,$tipo,$usr)

    {    



        $mimemail = new nomad_mimemail();                        

        

        //if($dias>=30){



            // $query="SELECT Usu_nombre, Uni_nombrecorto FROM Usuario

            //         INNER JOIN  Unidad ON Usuario.Uni_clave = Unidad.Uni_clave                

            //         WHERE Usu_login='".$usr."'";

            // $result = $this->_db->query($query);

            // $rs = $result->fetch();  

            // $usuario = $rs['Usu_nombre'];

            // $unidad = $rs['Uni_nombrecorto'];

            // $query ="SELECT Cia_nombrecorto FROM Expediente 

            //         INNER JOIN Compania ON Expediente.Cia_clave = Compania.Cia_clave

            //         WHERE Exp_folio='".$fol."'";

            // $result = $this->_db->query($query);

            // $rs = $result->fetch();  

            // $compania = $rs['Cia_nombrecorto'];



            //  $contenido='<HTML>

            //                         <HEAD>

            //                         </HEAD>

            //                         <BODY>

            //                         <br>                

            //                         <img src="logomv.gif"> 

            //                         <br>

            //                         <br>

            //                         <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">

            //                             <tr>

            //                                 <th colspan="3" align="center" style=" width: 25%; background: #eee;

            //                                    text-align: left;

            //                                    vertical-align: top;

            //                                    border: 1px solid #000;

            //                                    border-collapse: collapse;

            //                                    padding: 0.3em;

            //                                    caption-side: bottom;">

            //                                      Atenci&oacute;n a extemporaneo

            //                                 </th>

            //                             </tr>

            //                             <tr>

            //                                 <td>

            //                                     Usuario: '.$usuario.'

            //                                 </td>

            //                                 <td>

            //                                     Unidad: '.$unidad.'

            //                                 </td>

            //                                 <td>

            //                                     Compa&ntilde;&iacute;a: '.$compania.'

            //                                 </td>

            //                             </tr>

            //                             <tr>

            //                                 <td colspan="3">

            //                                     Se ha ingresado a los documentos del folio: '.$fol.' extemporaneo con '.$dias .' d&iacute;as sin movimientos en su expediente, siendo el &uacute;ltimo movimiento en el modulo de '.$tipo.'

            //                                 </td>

            //                             </tr>

                                        

            //                         </table>

            //                         </BODY>

            //                         </HTML>         

            //         ';

            // $mimemail->set_from("seguimiento_NoReply@medicavial.com.mx");        

            

            // // $mimemail->set_to('egutierrez@medicavial.com.mx');   

            // $mimemail->set_to('scisneros@medicavial.com.mx');   

            // $mimemail->add_cc('agutierrez@medicavial.com.mx');        

            // $mimemail->add_cc('egutierrez@medicavial.com.mx');        

            // $mimemail->set_subject("Atencion a extemporaneo - ".$fol);

            // $mimemail->set_html($contenido);

            // $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

            

            // if ($mimemail->send()){

                $query="INSERT INTO AtencionExtemporaneo(USU_login,Exp_folio,AEX_fecreg) VALUES('".$usr."','".$fol."',now())";

                $result = $this->_db->query($query);

                $sql="UPDATE Expediente SET Exp_hc=1 WHERE Exp_folio='".$fol."'";

                $result = $this->_db->query($sql);

                return 'exito';           

            // }else {

            //     return 'error';

            // }  

        // }else{

        //     $query="INSERT INTO AtencionExtemporaneo(USU_login,Exp_folio,AEX_fecreg) VALUES('".$usr."','".$fol."',now())";

        //     $result = $this->_db->query($query);

        //     $sql="UPDATE Expediente SET Exp_hc=1 WHERE Exp_folio='".$fol."'";

        //     $result = $this->_db->query($sql);

        //     return 'exito';           

        // }      



    }



    public function guardaComentario($fol,$comen,$usr)

    {        

                



        try{ 

            $query = "INSERT INTO comentarioFolio(Exp_folio, CFO_comentario, Usu_login, CFO_fecha) VALUES('".$fol."', '".strtoupper ($comen)."', '".$usr."', now())";

            $result = $this->_db->query($query);



            $sql = "SELECT * from comentarioFolio 

                    INNER JOIN Usuario ON comentarioFolio.Usu_login = Usuario.Usu_login

                    where Exp_folio='".$fol."'  order by CFO_id";

            $result = $this->_db->query($sql);

            $rs = $result->fetchAll();

            

            return $rs;      

        }catch(Exception $e){

            return "error";

        } 

        $this->_db=null;       

    }



    public function catItmesCarrito($fol)

    {        

                



        try{ 



            $sql = "SELECT Cia_clave from Expediente where Exp_folio='".$fol."'";

            $result = $this->_db->query($sql);

            $ciaA = $result->fetch();

            $cia=$ciaA['Cia_clave'];



            $sql = "SELECT * from CatItemsCarrito where Cia_clave=".$cia;

            $result = $this->_db->query($sql);

            $rs1 = $result->fetchAll();



            $sql = "SELECT * from CarritoCompras 

            INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id

            INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id

            where Exp_folio='".$fol."' and CCO_cerrado=0";

            $result = $this->_db->query($sql);

            $rs2 = $result->fetchAll();



            $respuesta =  array('catalogo' => $rs1 , 'itemsCarrito' => $rs2 );

            

            return $respuesta;      

        }catch(Exception $e){

            return "error";

        } 

        $this->_db=null;       

    }



    public function agregaItem($fol,$item,$usr)

    {        

                



        try{ 



            $sql = "SELECT * from CarritoCompras 

                    INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id

                    INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id

                    where Exp_folio='".$fol."' and CCO_cerrado=0";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetchAll();



            $contadorItems = 0;



            foreach ($respuesta as $key => $value) {

                $total+= $value['CIS_precio'];

                $contadorItems++;

            }

            $queryP = "SELECT CIS_precio FROM CatItemsCarrito WHERE CIC_id=".$item;

            $result = $this->_db->query($queryP);

            $rs = $result->fetch();

            $precio = $rs['CIS_precio'];

            $total += $precio; 

            



            if($contadorItems==0){

                $sql = "INSERT INTO CarritoCompras(Exp_folio, CCO_fechaRegistro,Usu_login,CCO_total) VALUES('".$fol."',now(),'".$usr."',".$total.")";           

                $result = $this->_db->query($sql);

            }else{

                $sql = "UPDATE CarritoCompras SET CCO_total=".$total." WHERE Exp_folio='".$fol."' and CCO_cerrado=0;";           

                $result = $this->_db->query($sql);

            }

           



            $ultmo = "select  MAX(CCO_id) id  from  CarritoCompras where Exp_folio='".$fol."' and CCO_cerrado=0;";

            $result = $this->_db->query($ultmo);

            $rs = $result->fetch();

            $idUltimo = $rs['id'];



            if(!$idUltimo){

                $idUltimo=1;

            }



            $sqlItem = "INSERT INTO ItemCarritoCompras(CCO_id,CIC_id) VALUES(".$idUltimo.",".$item.")";

            $result = $this->_db->query($sqlItem);     

            

            

            $sql = "SELECT * from CarritoCompras 

                    INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id

                    INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id

                    where Exp_folio='".$fol."' and CCO_cerrado=0";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetchAll();



            

            return $respuesta;      

        }catch(Exception $e){

            return $e->getmessage();

        } 

        $this->_db=null;       

    }



    public function getCarritFolio($fol)

    {        

                



        try{ 



            $sql = "SELECT count(*) contador from CarritoCompras 

                    INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id

                    INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id

                    where Exp_folio='".$fol."' and CCO_cerrado=0";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetch();        

            

            return $respuesta['contador'];      

        }catch(Exception $e){

            return $e->getmessage();

        } 

        $this->_db=null;       

    }



    public function closeRecibo($fol)
    {        
        try{ 
            $sql = "UPDATE CarritoCompras SET CCO_cerrado=1
                    where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            return 'exito';      
        }catch(Exception $e){
            return $e->getmessage();
        } 
        $this->_db=null;       
    }

    public function eliminarItemCarrito($fol,$id)

    {        

                



        try{ 



            $query = "DELETE FROM ItemCarritoCompras WHERE ICC_id=".$id;

            $result = $this->_db->query($query);

            $sql = "SELECT * from CarritoCompras 

                    INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id

                    INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id

                    where Exp_folio='".$fol."' and CCO_cerrado=0";

            $result = $this->_db->query($sql);

            $respuesta = $result->fetchAll();



            $contadorItems = 0;



            foreach ($respuesta as $key => $value) {

                $total+= $value['CIS_precio'];

                $contadorItems++;

            }           



            $sql = "UPDATE CarritoCompras SET CCO_total=".$total." WHERE Exp_folio='".$fol."' and CCO_cerrado=0;";           

            $result = $this->_db->query($sql);

            

            $sql = "SELECT * from CarritoCompras 

            INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id

            INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id

            where Exp_folio='".$fol."' and CCO_cerrado=0";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetchAll();    

            return $respuesta;                         

        }catch(Exception $e){

            return 'error';

        } 

        $this->_db=null;       

    }


    public function getDocumentos($fol)
    {        
        try{ 
            $sql = "SELECT
                        ( SELECT count(*) FROM RecetaMedica WHERE RecetaMedica.Exp_folio = Expediente.Exp_folio ) receta,
                        Exp_mail correo, Exp_correoVerificado verif,
                        ( SELECT count(*) FROM NotaMedica WHERE NotaMedica.Exp_folio = Expediente.Exp_folio ) notaMedica
                    FROM
                        Expediente 
                    WHERE
                        Exp_folio = '".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll();    
            return $respuesta;      
        }catch(Exception $e){
            return $e->getmessage();
        } 
        $this->_db=null;       
    }

}

?>