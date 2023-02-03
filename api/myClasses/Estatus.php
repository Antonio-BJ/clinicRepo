<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class Estatus Medico              --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 25-01-2016                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class Estatus extends Modelo
{
	
	function __construct()
	{
	   parent::__construct();         
	}    

    public function getRegistro($unidad,$usuario)
    {   
        $fecha= date('Y-m-d');
        try{          
            $respuesta = $this->getEstatus($usuario);            
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
    }

	public function inicioTurno($unidad,$usuario,$datos)
    {   
        $estatus = $datos->estatus;
        try{
            $query="SELECT MAX(Estatus_id)+1 as contador FROM EstatusMedico";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador=$rs['contador'];
            if($contador==null||$contador==''){
                $contador=1;
            }                    
            $query="INSERT INTO EstatusMedico(Estatus_id,Usu_clave,Uni_clave, Estatus_fecha,Estatus_inicioTurno, Estatus_estatus,Estatus_obsEstatus)
                   VALUES(".$contador.",'".$usuario."',".$unidad.",now(),now(),1,'".$estatus."')";
            $result = $this->_db->query($query);            
            $respuesta = $this->getEstatus($usuario);
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
        $this->_db=null;
    }
    public function inicioPausa($unidad,$usuario,$idEstatus,$idPausa,$datos)
    {   
        $estatus = $datos->estatus;        
        try{    $query="SELECT MAX(EstP_id)+1 as contador FROM EstatusMedicoPausa";
                $result = $this->_db->query($query);
                $rs = $result->fetch();
                $contador=$rs['contador'];
                if($contador==null||$contador==''){
                    $contador=1;
                }                
                $sql = "INSERT INTO EstatusMedicoPausa(EstP_id,Estatus_id, EstP_fecInicio)
                        VALUES(".$contador.",".$idEstatus.",now())";
                $result = $this->_db->query($sql);
                $sql = "UPDATE EstatusMedico set Estatus_estatus=2, Estatus_obsEstatus='".$estatus."' where Estatus_id=".$idEstatus;
                $result = $this->_db->query($sql);
                $respuesta = $this->getEstatus($usuario);           
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
        $this->_db=null;
    }

     public function finPausa($unidad,$usuario,$idEstatus,$idPausa,$datos)
    {   
        $estatus = $datos->estatus;        
        try{    $sql ="UPDATE EstatusMedicoPausa set EstP_fecFin= now() where EstP_id=".$idPausa;
                $result = $this->_db->query($sql);
                $sql = "UPDATE EstatusMedico set Estatus_estatus=1, Estatus_obsEstatus='".$estatus."' where Estatus_id=".$idEstatus;
                $result = $this->_db->query($sql);
                $respuesta = $this->getEstatus($usuario);           
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
        $this->_db=null;
    }

    public function finTurno($unidad,$usuario,$idEstatus,$idPausa,$datos)
    {   
        $estatus = $datos->estatus;        
        try{                  
                $sql = "UPDATE EstatusMedico set Estatus_estatus=4, Estatus_obsEstatus ='".$estatus."', Estatus_finTurno=now() where Estatus_id=".$idEstatus;
                $result = $this->_db->query($sql);
                $respuesta = 'exito';           
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
        $this->_db=null;
    }

    public function resetearTurno($unidad,$usuario,$idEstatus,$idPausa,$datos)
    {   
        $motivo = $datos->motivo;        
        try{                  
                $sql = "UPDATE EstatusMedico set Estatus_estatus=4, Estatus_finTurno=now(),Estatus_motivoReset='".$motivo."' where Estatus_id=".$idEstatus;
                $result = $this->_db->query($sql);

                //envío de correo
                $query="select Usu_nombre, Uni_nombrecorto from Usuario
                inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave 
                where Usuario.Usu_login='".$usuario."'";
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
                                                <th colspan="6" align="center" style=" width: 25%; background: #eee;
                                                   text-align: left;
                                                   vertical-align: top;
                                                   border: 1px solid #000;
                                                   border-collapse: collapse;
                                                   padding: 0.3em;
                                                   caption-side: bottom;">
                                                   RESETEO DE TURNO
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
                                                    Unidad: <b>'.utf8_encode($rs['Uni_nombrecorto']).'</b>
                                                </td>
                                                <td style=" width: 60%;
                                                   text-align: left;
                                                   vertical-align: top;
                                                   border: 1px solid #000;
                                                   border-collapse: collapse;
                                                   padding: 0.3em;
                                                   caption-side: bottom;">
                                                    Usuario: <b>'.utf8_encode($rs['Usu_nombre']).'</b>
                                                </td>                                        
                                            </tr>                                            
                                            <tr>
                                                <td colspan="2">
                                                    Motivo de reseteo de turno: <b>'.utf8_encode($motivo).'</b>
                                                </td>                                        
                                            </tr>                                    
                                        </table>
                                        </BODY>
                                        </HTML>         
                        ';
                $mimemail->set_from("seguimiento_NoReply@medicavial.com.mx");
                $mimemail->set_subject("- Reseteo de Turno - ".$datosFolio['Uni_nombrecorto']." - ".$rs['Usu_nombre']);
                $mimemail->set_to("egutierrez@medicavial.com.mx");
                $mimemail->set_html($contenido);
                $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
                if ($mimemail->send()){
                    $respuesta = 'exito';
                   
                }else {
                    $respuesta = 'error';
                }

        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
        $this->_db=null;
    }

    //listado estatus medico 
    protected function getEstatus($usuario){
        try{
            $query="SELECT Estatus_obsEstatus,Estatus_inicioTurno,EstatusMedico.Estatus_id,Estatus_estatus, MAX(EstP_id) AS pausa FROM EstatusMedico
            LEFT JOIN EstatusMedicoPausa ON EstatusMedico.Estatus_id=EstatusMedicoPausa.Estatus_id 
            WHERE Usu_clave='".$usuario."' and Estatus_estatus >0 and Estatus_estatus<4";
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();
            return $respuesta;
        }catch(Exception $e){
            return 'error';
        }
        $this->_db=null;
    }

     //Consulta los médicos disponibles
    public function listadoMedicosDisponibles($unidad)
    {   
        $estatus = $datos->estatus;        
        try{    $query="SELECT * FROM EstatusMedico 
                        INNER JOIN Medico on EstatusMedico.Usu_clave = Medico.Usu_login
                        WHERE EstatusMedico.Uni_clave=".$unidad." and Estatus_estatus=1";
                $result = $this->_db->query($query);
                $respuesta = $result->fetchAll();
                
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
        $this->_db=null;
    }

    public function guardaNotaInterna($folio, $comentario, $usr){
        try{
            $query ="INSERT INTO ComentarioExpediente(COE_folio, COE_comentario, COE_fecha, COE_usuario)
                    VALUES('".$folio."', '".$comentario."', now(), '".$usr."')";
            $result = $this->_db->query($query);
            $respuesta= Array('respuesta'=>'exito');            
        }catch(Exception $e){
            $respuesta= Array('respuesta'=>$e->getMessage());
        }
        return $respuesta;
    }
}
?>