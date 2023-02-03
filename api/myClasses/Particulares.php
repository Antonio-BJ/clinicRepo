<?php 



require_once "Modelo.php";

// clase para el envío de correos

require_once 'nomad_mimemail.inc.php';

/**

*  clase para el control de incidencias 

*/

class Particulares extends Modelo

{	

    public $mimemail;

	function __construct()

	{

		 parent::__construct();

         

	}

/************************************************************************************************************************************/



    public function sendSolCancel($datos,$fol,$uni,$user,$folRec)

    {        

        $motivo= $datos->motivoC;

        $observaciones= $datos->observaciones;

        $sustituto= $datos->sustituto;



        $fecha = date('d-m-Y');

        $hora  = date('h:i a');       



        $mimemail = new nomad_mimemail();



        $query="select Usu_nombre, Uni_nombrecorto from Usuario

                inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave 

                where Usuario.Usu_login='".$user."'";

        $result = $this->_db->query($query);

        $rs = $result->fetch();

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

                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;

                                           text-align: left;

                                           vertical-align: top;

                                           border: 1px solid #000;

                                           border-collapse: collapse;

                                           padding: 0.3em;

                                           caption-side: bottom;">

                                            SOLICITUD DE CANCELACI&Oacute;N DEL RECIBO '.$folRec.'

                                        </th>

                                    </tr>

                                    <br>

                                    

                                    <tr>

                                        <td>

                                            Motivo de cancelaci&oacute;n: <b>'.utf8_decode($motivo).'</b>

                                        </td>

                                        <td >

                                            Usuario que envi&oacute;: <b>'.utf8_decode($rs['Usu_nombre']).'</b>

                                        </td>   

                                         <td >

                                            Unidad: <b>'.$rs['Uni_nombrecorto'].'</b>

                                        </td>                                     

                                    </tr>

                                    <tr>

                                        <td colspan="2">

                                            Observaciones: <b>'.utf8_decode($observaciones).'</b>

                                        </td> 

                                        <td colspan="2">

                                            Recibo Sustituye: <b>'.utf8_decode($sustituto).'</b>

                                        </td>                                        

                                    </tr>                                    

                                </table>

                                </BODY>

                                </HTML>         

                ';         

            $mimemail->set_from("seguimiento_NoReply@medicavial.com.mx");

            //$mimemail->set_to("facparticulares@medicavial.com.mx");

        

            $mimemail->set_to("lmorales@medicavial.com.mx");

            $mimemail->add_cc("adominguez@medicavial.com.mx");

            

         

        //$mimemail->add_cc('enriqueerick@gmail.com');        

        $mimemail->set_subject("- Solicitud cancelación recibo - ".$folRec);

        $mimemail->set_html($contenido);

        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

       

        if ($mimemail->send()){

            $query="UPDATE reciboParticulares SET Recibo_solCancel=1 where Recibo_cont=".$folRec;

            $result = $this->_db->query($query);            

             $query="SELECT * FROM reciboParticulares WHERE Exp_folio='".$fol."'";

            $result = $this->_db->query($query);

            $rs = $result->fetchAll();

            return $rs;

           

        }else {

            return 'error';

        }                

    }



    public function getRecibo($cveRecibo)

    {        

        try{

            $query="SELECT Recibo_serie, Recibo_cont, Recibo_fecExp,Usu_login,Recibo_total,Recibo_aplicado  FROM reciboParticulares where Recibo_cont=".$cveRecibo;

            $result = $this->_db->query($query);

            $respuesta = $result->fetch(PDO::FETCH_OBJ);    

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }

    public function SetAplicacion($cveRecibo,$aplicacion,$cobro,$serie,$usr)

    {        



        $monto          = $aplicacion->monto;

        $referencia     = $aplicacion->referencia;

        $observaciones  = $aplicacion->observaciones; 

        $fecPago        = $aplicacion->fecCobro;

        $comision       = $aplicacion->comision; 

        $totalapp       = $aplicacion->totalapp;                   



        // print_r($cobro);

        try{



            $sql="select Recibo_aplicado, Recibo_total, Recibo_total-Recibo_aplicado as restanteRec from reciboParticulares where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

            $result = $this->_db->query($sql);

            $respuesta  = $result->fetch();

            $yaAplicado = $respuesta['Recibo_aplicado']; 

            $totalRec   = $respuesta['Recibo_total']; 

            $restante   = $respuesta['restanteRec']; 



            if($restante == 0){



                $respuesta = array('respuesta' =>'Recibo ya Aplicado');     

            }else{



                if($totalapp <= $restante){



                    $sql="UPDATE reciboParticulares SET Recibo_aplicado=".$totalapp." where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

                    $result = $this->_db->query($sql); 



                    $sql="UPDATE reciboParticulares SET Recibo_aplicado=".$totalapp." where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

                    $result = $this->_db->query($sql); 



                    $sql="select Recibo_aplicado from reciboParticulares where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

                    $result = $this->_db->query($sql);

                    $respuesta = $result->fetch();

                    $apl = $respuesta['Recibo_aplicado']; 



                        foreach ($cobro as  $value) {



                            $claveCobro = $value->COB_claveint;



                            $sql="SELECT COB_saldoinicial, COB_saldo from CobroxAplicar where COB_claveint=".$claveCobro."";

                            $result = $this->_db->query($sql);

                            $respuesta = $result->fetch();

                            $saldoinicial = $respuesta['COB_saldoinicial'];  

                            $saldo    = $respuesta['COB_saldo'];  



                            $totalDesIni = $saldoinicial-$totalapp;

                            $totalDesFin = $saldo-$totalapp;



                            $sql="UPDATE CobroxAplicar SET  COB_saldoinicial = ".$totalDesIni.", COB_saldo=".$totalDesFin." 

                                 where COB_claveint=".$claveCobro."";

                            $result = $this->_db->query($sql); 

                            // print_r($value->COB_claveint);



                            $sql="INSERT INTO ReciboParticularAplicacion( RPA_monto, RPA_referencia, RPA_obs, Usu_login, RPA_fecreg, RPA_saldo, Recibo_cont, Recibo_serie,RPA_aplicado,RPA_fecPago,COB_claveint,RPA_comision)

                                              VALUES(:RPA_monto, :RPA_referencia, :RPA_obs, :Usu_login, now(), :RPA_saldo, :Recibo_cont, :Recibo_serie,:RPA_aplicado,:RPA_fecPago,:COB_claveint,:RPA_comision)";

                            $temporal= $this->_db->prepare($sql);            

                            $temporal->bindParam("RPA_monto",$monto);              

                            $temporal->bindParam("RPA_referencia",$referencia);                         

                            $temporal->bindParam("RPA_obs",$observaciones);                

                            $temporal->bindParam("Usu_login",$usr);                

                            $temporal->bindParam("RPA_saldo",$totalRec);       

                            $temporal->bindParam("Recibo_cont",$cveRecibo);                

                            $temporal->bindParam("Recibo_serie",$serie);

                            $temporal->bindParam("RPA_aplicado",$apl); 

                            $temporal->bindParam("RPA_fecPago",$fecPago); 

                            $temporal->bindParam("COB_claveint",$claveCobro);

                            $temporal->bindParam("RPA_comision",$comision);                                 

                            if($temporal->execute()){                 

                                $sql="select * from ReciboParticularAplicacion where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

                                $result = $this->_db->query($sql);

                                $respuesta = $result->fetchAll(PDO::FETCH_OBJ);   

                            }else{

                                $respuesta = array('respuesta' =>'error');       

                            } 

                        }



                                          





                }else{

                    $respuesta = array('respuesta' =>'No puedes aplicar mas del total del Recibo');  

                }

                }







   

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



     public function getPagos($cveRecibo,$serie)

    {               

       

        try{           

                $sql="select * from ReciboParticularAplicacion where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetchAll(PDO::FETCH_OBJ);               

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



     public function getMonto($cveRecibo,$serie)

    {               

       

        try{           

                $sql="select Recibo_aplicado, Recibo_total-Recibo_aplicado as restanteRec  from reciboParticulares where Recibo_cont=".$cveRecibo." and Recibo_serie='".$serie."'";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetch();               

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }







    public function getControlParticulares()

    {            

        $mes=date("n");

        $anio = date("Y");  

        ; 

       

        try{           

                $sql="Select Uni_nombrecorto as unidad, concat(Med_nombre,' ',Med_paterno,' ',Med_materno) as medico, Medico.Usu_login,

                               (select count(*) from Expediente 

                               inner join NotaMedica on Expediente.Exp_folio=NotaMedica.Exp_folio

                               where Cia_clave in (54,53,51) and DATE(Not_fechaReg)=curdate() and NotaMedica.Usu_nombre=Medico.Usu_login) as POR_DIA, 

                               (select count(*) from Expediente 

                               inner join NotaMedica on Expediente.Exp_folio=NotaMedica.Exp_folio

                               where Cia_clave in (54,53,51) and year(Not_fechaReg)=2016 and MONTH(Not_fechaReg)=6 and NotaMedica.Usu_nombre=Medico.Usu_login) as POR_MES,

                               (select count(*) from Expediente 

                               inner join NotaMedica on Expediente.Exp_folio=NotaMedica.Exp_folio

                               where Cia_clave in (54,53,51) and year(Not_fechaReg)=2016 and NotaMedica.Usu_nombre=Medico.Usu_login) as POR_ANIO from Medico

                               inner join Unidad on Medico.Uni_clave=Unidad.Uni_clave

                    where Med_activo='S'

                    order by Unidad.Uni_clave, Medico.Usu_login";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetchAll();               

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



    public function getPacientesMedico($med)

    {            

        $mes=date("n");

        $anio = date("Y");  

        ; 

       

        try{           

                $sql="select Expediente.Exp_folio, Exp_completo, Not_fechaReg from Expediente 

                               inner join NotaMedica on Expediente.Exp_folio=NotaMedica.Exp_folio

                               where Cia_clave in (54,53,51) and DATE(Not_fechaReg)=curdate() and NotaMedica.Usu_nombre='".$med."'";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetchAll();               

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }

    

    public function getRecibosFolio($fol)

    {            

        $mes=date("n");

        $anio = date("Y");  

        ; 

       

        try{           

                $sql="select * from reciboParticulares where Exp_folio='".$fol."'";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetchAll();               

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



    public function delMontoRecibo($cve)

    {            

        $mes=date("n");

        $anio = date("Y");  

        ; 

       

        try{    

                $sql="SELECT reciboParticulares.Recibo_cont, reciboParticulares.Recibo_serie, RPA_saldo, Recibo_aplicado FROM ReciboParticularAplicacion

                      INNER JOIN reciboParticulares ON ReciboParticularAplicacion.Recibo_cont = reciboParticulares.Recibo_cont WHERE RPA_id='".$cve."'"; 

                $result = $this->_db->query($sql);

                $respuesta = $result->fetch();

                $cveRecibo = $respuesta['Recibo_cont'];

                $serie     = $respuesta['Recibo_serie'];

                $aplicado  = $respuesta['RPA_saldo'];

                $total     = $respuesta['Recibo_aplicado'];

                $descuento = (float)$total-(float)$aplicado;



                $sql="DELETE FROM ReciboParticularAplicacion where RPA_id='".$cve."'";

                $result = $this->_db->query($sql);   



                $sql="UPDATE reciboParticulares SET Recibo_aplicado=".$descuento." WHERE Recibo_cont=".$cveRecibo." AND Recibo_serie='".$serie."'";

                $result = $this->_db->query($sql); 

                $respuesta = array('cve_recibo' =>$cveRecibo,'serie'=>$serie,'descuento'=>$descuento);              

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



    public function getMembresiaConvenio($fol)

    {                    

       

        try{   

                $sql="SELECT Cia_clave, CON_cve from Expediente where Exp_folio='".$fol."'";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetch();

                $cveParticular  = $respuesta['Cia_clave'];

                $cveConvenio   = $respuesta['CON_cve'];



                if($cveParticular==54){

                    $sql="SELECT Par_tipo, Par_convenio, CON_nombre FROM ObsParticulares 

                      INNER JOIN Convenio ON ObsParticulares.Par_convenio = Convenio.CON_cve

                      WHERE Exp_folio='".$fol."'";

                    $result = $this->_db->query($sql);

                    $respuesta = $result->fetch();

                    $tipParticular= $respuesta['Par_tipo'];

                    $convenio= $respuesta['Par_convenio'];

                    $nomConvenio = $respuesta['CON_nombre']; 



                    if($tipParticular==4){                                      

                        if( $cveConvenio==$convenio){

                            $respuesta=array('respuesta' =>'igual','convenio'=>$cveConvenio);

                        }else{

                            $respuesta=array('membresia'=>$cveConvenio,'convenio'=>$convenio,'nomConvenio'=>$nomConvenio);

                        }

                    }elseif(!$tipParticular){

                        $respuesta=array('respuesta' =>'sinObs','convenio'=>$cveConvenio);

                    }

                }elseif($cveParticular==51){

                    $respuesta=array('membresia'=>0,'particular'=>'individual');

                }elseif($cveParticular==53){

                    $respuesta=array('membresia'=>'empleado');

                }            

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



    public function getDoctosFol($fol)

    {                    

        // try{           

        //         $sql="SELECT Med_activo, Medico.Med_clave as claveUsuario, concat(Med_nombre,' ',Med_paterno,' ',Med_materno) as nombreUsuario FROM  NotaMedica

        //               INNER JOIN Medico ON NotaMedica.Usu_nombre = Medico.Usu_login

        //               WHERE Exp_folio='".$fol."'";

        //         $result = $this->_db->query($sql);

        //         $respuesta = $result->fetch();                                

        // }catch(Exception $e){

        //     //$respuesta=$e->getMessage();

        //     $respuesta = array('respuesta' =>$e->getMessage());       

        // }    

        //  return $respuesta;    



        $miArray=array();

        $sql1="select Usu_login usuario, fecreg_notSOAP fecha from NotaSOAP where Exp_folio='".$fol."' order by fecreg_notSOAP desc";

        $result = $this->_db->query($sql1);

        $respuesta1 = $result->fetch();



        $miArray[$respuestra1['usuario']]=$respuestra1['fecha'];

        $sql2="select Usu_nombre usuario,Not_fechareg fecha from NotaMedica where Exp_folio='".$fol."' order by Not_fechareg desc";

        $result = $this->_db->query($sql2);

        $respuesta2 = $result->fetch();                  

        array_push($miArray, $respuesta2); 

           

        $sql3="select Usu_registro usuario, CONCAT(Sub_fecha,' ',Sub_hora) fecha  from Subsecuencia where Exp_folio='".$fol."' order by Sub_fecha desc";

        $result = $this->_db->query($sql3);

        $respuesta3 = $result->fetch();         

        array_push($miArray, $respuesta3); 



        foreach ($miArray as $key => $row) {

            $aux[$key] = $row['fecha'];

        }

        array_multisort($aux, SORT_DESC, $miArray);

        $user = $miArray[0]['usuario'];        

       

        try{           

                $sql="SELECT Med_activo, Medico.Med_clave as claveUsuario, concat(Med_nombre,' ',Med_paterno,' ',Med_materno) as nombreUsuario FROM  NotaMedica

                      INNER JOIN Medico ON NotaMedica.Usu_nombre = Medico.Usu_login

                      WHERE Usu_login='".$user."'";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetch();                                

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;  

    }



    public function getNotaDigital($fol)

    {                    

       

        try{           

                $sql="SELECT  count(*) AS contador

                FROM DocumentosDigitales                

                WHERE REG_folio='".$fol."' and Arc_tipo=18";

                $result = $this->_db->query($sql);

                $respuesta = $result->fetch();                                

        }catch(Exception $e){

            //$respuesta=$e->getMessage();

            $respuesta = array('respuesta' =>$e->getMessage());       

        }    

         return $respuesta;    

    }



    public function deleteRepetidos(){



        $fp = fopen ("repetidosParticularesProd.csv","r");



        while ($data = fgetcsv ($fp, 1000, ";")) {

            $var=explode(',',$data[0]);

            echo $var[0].'->'.$var[1].'<br>';

            $limite=$var[1]-1;

            $sql="DELETE FROM ObsParticulares where Exp_folio='".$var[0]."' limit ".$limite;

            $result = $this->_db->query($sql);

        }

        fclose ($fp);        

    }



    public function listadoRecibosCobranza($datos){

        $fechaIni = $datos ->fechaIni;

        $fechaFin = $datos ->fechaFin;

        $recibo   = $datos ->folioRecibo;

        $folio    = $datos ->folioMV;

        $nombre   = $datos ->nombre;



            $query="SELECT CONCAT(Recibo_serie,Recibo_cont) as RECIBO, reciboParticulares.Exp_folio as FOLIO, Exp_completo AS NOMBRE, Recibo_fecExp AS FECHA, Recibo_total AS TOTAL, Uni_nombrecorto AS UNIDAD, metodoPagoPar.metodo AS METODOPAGO, Recibo_aplicado AS APLICADO,

            'REGISTRO' AS TIPO, Recibo_cancelado AS CANCELADO, Recibo_cobranza as COBRANZA, Recibo_total as TOTAL, Recibo_serie as SERIE, Recibo_cont as CONT

            FROM reciboParticulares 

            INNER JOIN Expediente ON reciboParticulares.Exp_folio = Expediente.Exp_folio

            INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave

            INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo

                                where Recibo_fecExp between '".$fechaIni."' AND '".$fechaFin." 23:59:59'

            UNION



            SELECT CONCAT(Recibo_serie,Recibo_cont) as RECIBO, reciboParticulares.Exp_folio as FOLIO, CONCAT(sinReg_nombre,' ',sinReg_apPaterno,' ',sinReg_apMaterno) AS NOMBRE, Recibo_fecExp AS FECHA, Recibo_total AS TOTAL, Uni_nombrecorto AS UNIDAD, metodoPagoPar.metodo AS METODOPAGO, Recibo_aplicado AS APLICADO,

            'SIN REGISTRO' AS TIPO, Recibo_cancelado AS CANCELADO, Recibo_cobranza as COBRANZA, Recibo_total as TOTAL, Recibo_serie as SERIE, Recibo_cont as CONT

            FROM reciboParticulares 

            INNER JOIN ventas_sin_registro ON reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id

            INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave

            INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo

                               where Recibo_fecExp between '".$fechaIni."' AND '".$fechaFin." 23:59:59'

            ORDER BY FECHA DESC";           

            $result = $this->_db->query($query);

            $respuesta = $result->fetchAll(); 

       

        return $respuesta;

    }



    public function listadoRecibosCobranzaAvanzada($datos){

        $fechaIni = $datos ->fechaIni;

        $fechaFin = $datos ->fechaFin;

        $recibo   = $datos ->folioRecibo;

        $folio    = $datos ->folioMV;

        $nombre   = $datos ->nombre;

        try {

            

        



            if($recibo||$folio||$nombre){

                $fechaIni = '';

                $fechaFin = '';



                if($recibo&&$folio&&$nombre){

                    $query1 = " reciboParticulares.Recibo_cont=".$recibo." and reciboParticulares.Exp_folio='".$folio."' and Expediente.Exp_completo like '%".$nombre."%'";

                    $query2 = " reciboParticulares.Recibo_cont=".$recibo." and reciboParticulares.Exp_folio='".$folio."' and sinReg_nombre like '%".$nombre."%'";



                }elseif ($recibo&&$folio&&!$nombre) {

                    $query1 = " reciboParticulares.Recibo_cont=".$recibo." and reciboParticulares.Exp_folio='".$folio."'";

                    $query2 = " reciboParticulares.Recibo_cont=".$recibo." and sinReg_nombre like='".$folio."'";

                }elseif ($recibo&&!$folio&&!$nombre) {

                    $query1 = " reciboParticulares.Recibo_cont=".$recibo;

                    $query2 = " reciboParticulares.Recibo_cont=".$recibo;

                }elseif ($recibo&&!$folio&&$nombre) {

                    $query1 = " reciboParticulares.Exp_folio='".$folio."' and Expediente.Exp_completo like '%".$nombre."%'";

                    $query2 = " reciboParticulares.Exp_folio='".$folio."' and sinReg_nombre  like '%".$nombre."%'";

                }elseif (!$recibo&&!$folio&&$nombre) {

                    $query1 = " Expediente.Exp_completo like '%".$nombre."%'";

                    $query2 = " sinReg_nombre  like '%".$nombre."%'";

                }elseif (!$recibo&&$folio&&!$nombre) {

                    $query1 = " reciboParticulares.Exp_folio='".$folio."'";

                    $query2 = " reciboParticulares.Exp_folio='".$folio."'";

                }elseif (!$recibo&&$folio&&$nombre) {

                    $query1 = " reciboParticulares.Exp_folio='".$folio."' and Expediente.Exp_completo like '%".$nombre."%'";

                    $query2 = " reciboParticulares.Exp_folio='".$folio."' and sinReg_nombre like '%".$nombre."%'";

                }



                $query="SELECT CONCAT(Recibo_serie,Recibo_cont) as RECIBO, reciboParticulares.Exp_folio as FOLIO, Exp_completo AS NOMBRE, Recibo_fecExp AS FECHA, Recibo_total AS TOTAL, Uni_nombrecorto AS UNIDAD, metodoPagoPar.metodo AS METODOPAGO, Recibo_aplicado AS APLICADO,

                'REGISTRO' AS TIPO, Recibo_cancelado AS CANCELADO, Recibo_cobranza as COBRANZA, Recibo_total as TOTAL, Recibo_serie as SERIE, Recibo_cont as CONT

                FROM reciboParticulares 

                INNER JOIN Expediente ON reciboParticulares.Exp_folio = Expediente.Exp_folio

                INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave

                INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo

                                    where ".$query1."

                UNION



                SELECT CONCAT(Recibo_serie,Recibo_cont) as RECIBO, reciboParticulares.Exp_folio as FOLIO, CONCAT(sinReg_nombre,' ',sinReg_apPaterno,' ',sinReg_apMaterno) AS NOMBRE, Recibo_fecExp AS FECHA, Recibo_total AS TOTAL, Uni_nombrecorto AS UNIDAD, metodoPagoPar.metodo AS METODOPAGO, Recibo_aplicado AS APLICADO,

                'SIN REGISTRO' AS TIPO, Recibo_cancelado AS CANCELADO, Recibo_cobranza as COBRANZA, Recibo_total as TOTAL, Recibo_serie as SERIE, Recibo_cont as CONT

                FROM reciboParticulares 

                INNER JOIN ventas_sin_registro ON reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id

                INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave

                INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo

                                   where ".$query2."

                ORDER BY FECHA DESC";  



                $result = $this->_db->query($query);

                $respuesta = $result->fetchAll(); 





            }else{

                $query="SELECT CONCAT(Recibo_serie,Recibo_cont) as RECIBO, reciboParticulares.Exp_folio as FOLIO, Exp_completo AS NOMBRE, Recibo_fecExp AS FECHA, Recibo_total AS TOTAL, Uni_nombrecorto AS UNIDAD, metodoPagoPar.metodo AS METODOPAGO, Recibo_aplicado AS APLICADO,

                'REGISTRO' AS TIPO, Recibo_cancelado AS CANCELADO, Recibo_cobranza as COBRANZA, Recibo_total as TOTAL, Recibo_serie as SERIE, Recibo_cont as CONT

                FROM reciboParticulares 

                INNER JOIN Expediente ON reciboParticulares.Exp_folio = Expediente.Exp_folio

                INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave

                INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo

                                    where Recibo_fecExp between '".$fechaIni."' AND '".$fechaFin." 23:59:59'

                UNION



                SELECT CONCAT(Recibo_serie,Recibo_cont) as RECIBO, reciboParticulares.Exp_folio as FOLIO, CONCAT(sinReg_nombre,' ',sinReg_apPaterno,' ',sinReg_apMaterno) AS NOMBRE, Recibo_fecExp AS FECHA, Recibo_total AS TOTAL, Uni_nombrecorto AS UNIDAD, metodoPagoPar.metodo AS METODOPAGO, Recibo_aplicado AS APLICADO,

                'SIN REGISTRO' AS TIPO, Recibo_cancelado AS CANCELADO, Recibo_cobranza as COBRANZA, Recibo_total as TOTAL, Recibo_serie as SERIE, Recibo_cont as CONT

                FROM reciboParticulares 

                INNER JOIN ventas_sin_registro ON reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id

                INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave

                INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo

                                   where Recibo_fecExp between '".$fechaIni."' AND '".$fechaFin." 23:59:59'

                ORDER BY FECHA DESC";           

                $result = $this->_db->query($query);

                $respuesta = $result->fetchAll(); 

            }

            return $respuesta;



        



        } catch (Exception $e) {

                $respuesta = array('respuesta' => $e->getMessage() );

                return $respuesta;

        }



    }



    public function getDatosRecibo($serie,$recibo){

        try{



            $query = "SELECT Recibo_tipo FROM reciboParticulares WHERE Recibo_cont=".$recibo." and Recibo_serie='".$serie."'";

            $result = $this->_db->query($query);        

            $respuesta = $result->fetch();

            $tipoR = $respuesta['Recibo_tipo'];

            if($tipoR==1){

                $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,

                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,

                              Expediente.Exp_completo as nombre, metodo, Usu_nombre, Recibo_aplicado

                          from reciboParticulares 

                          inner join Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio

                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago

                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login

                          where Recibo_cont=".$recibo." AND Recibo_serie='".$serie."' and Recibo_cancelado<>1

                      ";

            }else{

                $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,

                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,

                              concat(sinReg_nombre,' ',sinReg_apPaterno,' ', sinReg_apMaterno) as nombre, metodo, Usu_nombre, Recibo_aplicado

                          from reciboParticulares 

                          inner join ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id

                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago

                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login

                          where Recibo_cont=".$recibo." AND Recibo_serie='".$serie."' and Recibo_cancelado<>1";

            }



           

           $result = $this->_db->query($query);

           $respuesta = $result->fetch(); 

           return $respuesta;

        }catch(Exception $e){

            $respuesta = array('respuesta' => $e->getMessage());

            return $respuesta;

        }

    }

    public function setEstatusCob($color,$serie,$recibo){

        try{

           $query="UPDATE reciboParticulares SET Recibo_cobranza=".$color." WHERE Recibo_cont=".$recibo." AND Recibo_serie='".$serie."'";

           $result = $this->_db->query($query);

           $respuesta = array('respuesta' => 'exito');

           return $respuesta;

        }catch(Exception $e){

            $respuesta = array('respuesta' => 'error');

            return $respuesta;

        }

    }



     public function getEnfermeras($uni){

        $query = "SELECT * from Enfermera where Uni_clave in (0,".$uni.") order by ENF_id";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();

        return $respuesta;

    }



    public function getRhDomicilio($uni){

        $query = "SELECT * from Usuario where Uni_clave in (0,".$uni.") and Domicilio=1 order by Usu_nombre";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();

        return $respuesta;

    }



    public function buscaRecibo($datos,$uni,$usr){

        $recibo   = $datos ->recibo;

        $folio    = $datos ->folio;

        $lesionado= $datos ->lesionado;

        

            if($recibo!=''){

                $query1="concat( Recibo_serie, Recibo_cont ) LIKE '%$recibo%' AND Recibo_fecExp >='2019-01-01 00:00:00'";

            }
            if($folio!=''){

                $query1="AND reciboParticulares.Exp_folio = '$folio'";

            }



            if($lesionado!=""){

                $query1.=" AND (CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) LIKE '%$lesionado%' || Exp_completo LIKE '%$lesionado%')";

            }

            $query = "SELECT Uni_nombrecorto UNIDAD, IFNULL(CONCAT( Medico.Med_nombre, ' ', Medico.Med_paterno, ' ', Medico.Med_materno ),'') as medico,Usu_nombre USUARIO, concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_aplicado,Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_total-Recibo_aplicado as restanteRec, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE,
                IF(FAP_folRec is null,'Recibo no facturado','Recibo Facturado') as estatus, 
                if(FAP_global = 1,'Publico en General','Cliente') as tipoFact, Recibo_soporte, Recibo_cancelado,Recibo_motCancel, COR_nombre as coodinador, ItemProyecto.ITP_nombreCla 
                FROM reciboParticulares 
                INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave 
                INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login 
                INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo 
                LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio 
                LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id 
                LEFT JOIN FacturaProveedor on reciboParticulares.Recibo_cont = FacturaProveedor.FAP_folRec
                LEFT JOIN Medico ON reciboParticulares.Recibo_doc = Medico.Med_clave
                LEFT JOIN ItemProyecto ON reciboParticulares.ITP_id = ItemProyecto.ITP_lugar
                LEFT JOIN CoordinadorProyecto ON reciboParticulares.ITP_id = CoordinadorProyecto.COR_proyecto
                WHERE ".$query1;





        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();



        return $respuesta;



    }



    public function enviaCancelacionRecibo($datos,$usr){

        $fol=       $datos->folio;

        $motivo =   $datos->motivo;

        $folioSus = $datos->folioSus;

        $obs    =   $datos->Obs;

        $solicitado    =   $datos->solicitado;

        $query="UPDATE reciboParticulares SET Recibo_cancelado=1, Recibo_Fcancelado=now(), Recibo_folSustituye='$folioSus', Recibo_CancNota='$obs', Recibo_motCancel='$motivo', Recibo_obsCancel='SOLICITADO POR $solicitado. CANCELADO POR $usr' WHERE Recibo_cont=$fol";


        $result= $this->_db->query($query);

        //echo $query;



        if($result){

            $respuesta= 0;

        }else{

            $respuesta= 1;

        }



        return $respuesta;

    }



    public function detalleCancelacion($recibo){

        $query = "SELECT Recibo_Fcancelado, Recibo_folSustituye, Recibo_CancNota, Recibo_motCancel, Recibo_obsCancel from reciboParticulares where CONCAT(Recibo_serie,Recibo_cont)='$recibo'";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetch();

        return $respuesta;

    }





    /// ********** BY ANA DC ****





    public function listaRecibos($datos,$uni,$usr){

        $recibo   = $datos ->recibo;

        $uniEleg  = $datos ->unidad; 

        $folio    = $datos ->folio;

        $lesionado= $datos ->lesionado;



        $query = "SELECT Recibo_mpago,IFNULL(CONCAT( Medico.Med_nombre, ' ', Medico.Med_paterno, ' ', Medico.Med_materno ),'') as medico, Recibo_aplicado,Uni_nombrecorto UNIDAD, Usu_nombre USUARIO, concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, Recibo_total-Recibo_aplicado as restanteRec ,metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE,COR_nombre as coodinador, ItemProyecto.ITP_nombreCla  
            FROM reciboParticulares 
            INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave 
            INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login 
            INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo 
            LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio 
            LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id 
            LEFT JOIN Medico ON reciboParticulares.Recibo_doc = Medico.Med_clave
            LEFT JOIN ItemProyecto ON reciboParticulares.ITP_id = ItemProyecto.ITP_lugar
            LEFT JOIN CoordinadorProyecto ON reciboParticulares.ITP_id = CoordinadorProyecto.COR_proyecto
            ORDER BY Recibo_fecExp DESC  limit 50";



        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();



        return $respuesta;



    }





    public function tarjetaRec($recibo){

        $query = "SELECT Recibo_banco,Recibo_terminacion from reciboParticulares where CONCAT(Recibo_serie,Recibo_cont)='$recibo'";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetch();

        return $respuesta;

    }





    public function detaleFol($folio){

        $query = "SELECT Recibo_serie, Recibo_cont, Recibo_fecExp, Recibo_total, if(Recibo_cancelado = 1, 'Cancelado' ,'Vigente') as estatus  from reciboParticulares where Exp_folio = '$folio' order by Recibo_fecExp DESC";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();

        return $respuesta;

    }





    public function listadoCobro(){

        $query = "SELECT COB_id,

                            COB_claveint,

                            COB_fecha,

                            COB_saldoinicial,

                            COB_relacionBancaria,

                            COB_saldo,

                            COB_fechaRegistroCobro,

                            Recibo_serie,

                            Recibo_cont,

                            COB_fechaAct,

                            COB_fechaimportacion

                    FROM CobroxAplicar WHERE COB_Saldo > 0 limit 30";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();

        return $respuesta;

    }



    public function detalleApp($recibo,$serie){

       $query = "SELECT * FROM ReciboParticularAplicacion 

                  WHERE Recibo_serie = '$serie' and Recibo_cont = $recibo";

        $result = $this->_db->query($query);        

        $respuesta = $result->fetchAll();

        return $respuesta;

    }





}

 ?>