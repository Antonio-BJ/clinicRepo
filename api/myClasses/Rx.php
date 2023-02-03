<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class Rx extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

    public function getSinCuestionario($uni){     
        $sql="SELECT Expediente.Exp_folio,Exp_completo, if(SRX_etapa=1,'Primera Etapa','Segunda Etapa') as Etapa,SRX_fecreg, Exp_fecreg FROM SolicitudRx
            INNER JOIN Expediente ON SolicitudRx.Exp_folio = Expediente.Exp_folio
            WHERE SolicitudRx.Uni_clave=8 AND Exp_fecreg > '2016-11-01' AND
                        (SELECT count(*) FROM RxSolicitados WHERE SolicitudRx.Exp_folio=RxSolicitados.Exp_folio and Rxs_digitalizado=0)>0";        
        $result = $this->_db->query($sql);
        $listado = $result->fetchAll(PDO::FETCH_ASSOC); 
        return $listado;               
    }

    #mandar solicitar Estudios
    public function solicitarEstudios($fol,$usr,$uni){ 
        try{

            $sql="SELECT count(*) as cont FROM SolicitudRx WHERE Exp_folio='".$fol."' and SRX_etapa=1";
            $result = $this->_db->query($sql);
            $conteo = $result->fetch();
            $cont = $conteo['cont'];
            if($cont==0){ 
                $aut=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);    
                $sql="  INSERT INTO SolicitudRx(SRX_noAut,SRX_fecreg,Usr_login,Exp_folio,SRX_etapa,SRX_contEtapa,Uni_clave)
                        VALUES('".$aut."',now(),'".$usr."','".$fol."',1,1,".$uni.")";        
                $result = $this->_db->query($sql);
                $respuesta=array('respuesta'=>'exito');
            }else{
                $respuesta=array('respuesta'=>'existente');
            }
        }catch(Exception $e){
            $respuesta=array('respuesta'=>$e->getmessage());
        }
        return $respuesta;               
    }

    public function listadoRxSol($fol){ 
        try{
             $sql="  SELECT Fecha_solicita, RXZ_zona,(SELECT GROUP_CONCAT(TRX_nombre SEPARATOR ', ') as tipo  FROM TipoRxZona INNER JOIN TipoRx ON TipoRxZona.TRX_id = TipoRx.TRX_id  WHERE TipoRxZona.Rxs_clave=RxSolicitados.Rxs_clave) AS Tipo, Rxs_obs, Rxs_desc, Rxs_clave, Exp_folio, 0 as 'barra',Rxs_digitalizado  FROM RxSolicitados
                    INNER JOIN RxZona on RxSolicitados.RXZ_id = RxZona.RXZ_id
                 WHERE Exp_folio='".$fol."'";
                 
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll();
            
        }catch(Exception $e){
            $respuesta=array('respuesta'=>$e->getmessage());
        }
        return $respuesta;               
    }

    public function buscaRXSolicitados($fol){ 
        try{
             $sql="  SELECT Fecha_solicita, RXZ_zona,(SELECT GROUP_CONCAT(TRX_nombre SEPARATOR ', ') as tipo  FROM TipoRxZona INNER JOIN TipoRx ON TipoRxZona.TRX_id = TipoRx.TRX_id  WHERE TipoRxZona.Rxs_clave=RxSolicitados.Rxs_clave) AS Tipo, Rxs_obs, Rxs_desc, Rxs_clave, Exp_folio, 0 as 'barra',Rxs_digitalizado  FROM RxSolicitados
                    INNER JOIN RxZona on RxSolicitados.RXZ_id = RxZona.RXZ_id
                 WHERE Exp_folio='".$fol."'";
                 
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll();
            
        }catch(Exception $e){
            $respuesta=array('respuesta'=>$e->getmessage());
        }
        return $respuesta;               
    }

    /********************************************************************************************************************/

}
?>