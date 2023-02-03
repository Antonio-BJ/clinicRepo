<?php 

require_once "Modelo.php";

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class Nota Soap              --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 25-01-2016                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class Soap extends Modelo
{
	
	function __construct()
	{
	   parent::__construct();         
	}    

/*    
    public function getRegistro($unidad,$usuario)
    {   

        ////
        $fecha= date('Y-m-d');
        try{
            $query="SELECT * FROM NotaSoap where fecreg_notSOAP='".$fecha."'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();
        }catch(Exception $e){
            $respuesta = $e->getMessage();
        }
        return $respuesta;
    }
*/
    

	public function guardarSoap($uni,$usr,$fol,$data)
    {   

        $appSoap = $data->appSoap;
        $apxSoap = $data->apxSoap;
        $subjetivo = $data->subjetivo;
        $objetivo = $data->objetivo;
        $analisis = $data->analisis;
        $pronostico = $data->pronostico;
        $usuario = $data->usu_login;
        $unidad = $data->uni_clave;
        $fecha = $data->fechaRegistro;



        try{
            $query="SELECT MAX(id_notSOAP)+1 as contador FROM NotaSOAP";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador=$rs['contador'];
            if($contador==null||$contador==''){
                $contador="1";
            }        
            

            $query="INSERT INTO NotaSOAP(id_notSOAP,
                                         Exp_folio,
                                         app_notSOAP,
                                         aqx_notSOAP,
                                         sub_notSOAP,
                                         obj_notSOAP,
                                         analisis_notSOAP, 
                                         pronos_notSOAP,
                                         Usu_login, 
                                         Uni_clave,
                                         fecreg_notSOAP)
                    VALUES(".$contador.",'".$fol."','".$appSoap."','".$apxSoap."','".$subjetivo."','".$objetivo."','".$analisis."','".$pronostico."','".$usuario."',".$unidad.",now())";

            $result = $this->_db->query($query);
            $respuesta= array('respuesta' => "exito",'contador'=>$contador);
            
        }catch(Exception $e){
            $respuesta =array('respuesta'=> $e->getMessage()) ;
        }                
        return $respuesta;        
    }

    public function guardarSoapRH($uni,$usr,$fol,$data)
    {   

        $appSoap = $data->appSoap;
        $apxSoap = $data->apxSoap;
        $subjetivo = $data->subjetivo;
        $objetivo = $data->objetivo;
        $analisis = $data->analisis;
        $pronostico = $data->pronostico;
        $usuario = $data->usu_login;
        $unidad = $data->uni_clave;
        $fecha = $data->fechaRegistro;
        $puede = $data->puede;
        $obs  =$data->observaciones;



        try{
            $query="SELECT MAX(id_notSOAP)+1 as contador FROM NotaSOAPRH";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador=$rs['contador'];
            if($contador==null||$contador==''){
                $contador="1";
            }        
            

            $query="INSERT INTO NotaSOAPRH(id_notSOAP,
                                         Exp_folio,
                                         app_notSOAP,
                                         aqx_notSOAP,
                                         sub_notSOAP,
                                         obj_notSOAP,
                                         analisis_notSOAP, 
                                         pronos_notSOAP,
                                         Usu_login, 
                                         Uni_clave,
                                         fecreg_notSOAP,                                         
                                         obs_notSOAP,
                                         puede_notSOAP)
                    VALUES(".$contador.",'".$fol."','".$appSoap."','".$apxSoap."','".$subjetivo."','".$objetivo."','".$analisis."','".$pronostico."','".$usuario."',".$unidad.",now(),'".$obs."',".$puede.")";

            $result = $this->_db->query($query);
            $respuesta= array('respuesta' => "exito",'contador'=>$contador);
            
        }catch(Exception $e){
            $respuesta =array('respuesta'=> $e->getMessage()) ;
        }                
        return $respuesta;        
    }

    public function verSoap($fol)
    {   try{
            $query="SELECT id_notSOAP, fecreg_notSOAP FROM NotaSOAP WHERE Exp_folio='".$fol."'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();
        }catch(Exception $e){
            $respuesta=array('respuesta'=> $e->getMessage());
        }
        return $respuesta;
    }

    
}
?>