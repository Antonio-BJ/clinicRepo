<?php 

require_once "Modelo.php";

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------         class Nota Soap Corta Est           --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Samuel                                            --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 27-10-2016                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class SoapCortaEst extends Modelo
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
    

	public function guardarSoap($uni,$usr,$fol,$data){   
        //print_r($data);
        $appSoap        = $data->appSoap;
        $apxSoap        = $data->apxSoap;
        $subjetivo      = $data->subjetivo;
        $objetivo       = $data->objetivo;
        $analisis       = $data->analisis;
        $pronostico     = $data->pronostico;
        $usuario        = $data->usu_login;
        $unidad         = $data->uni_clave;
        $fecha          = $data->fechaRegistro;

        try{     
            $query="INSERT INTO notaSOAP_cortaEst(id_notSOAP, Exp_folio, app_notSOAP, 
                                                  aqx_notSOAP, sub_notSOAP, obj_notSOAP, 
                                                  analisis_notSOAP, pronos_notSOAP, Usu_login, 
                                                  Uni_clave, fecha_notSOAP)
                                        VALUES(DEFAULT, '".$fol."', '".$appSoap."',
                                               '".$apxSoap."', '".$subjetivo."', '".$objetivo."',
                                               '".$analisis."', '".$pronostico."', '".$usuario."',
                                               ".$unidad.", now())";

            $result = $this->_db->query($query);
            $respuesta= array('respuesta' => "exito",'contador'=>$contador);
            
        }catch(Exception $e){
            $respuesta =array('respuesta'=> $e->getMessage()) ;
        }                
        return $respuesta;        
    }

    public function verSoap($fol)
    {   try{
            $query="SELECT id_notSOAP, fecreg_notSOAP FROM notasoap_cortaest WHERE Exp_folio='".$fol."'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();
        }catch(Exception $e){
            $respuesta=array('respuesta'=> $e->getMessage());
        }
        return $respuesta;
    }

    
}
?>