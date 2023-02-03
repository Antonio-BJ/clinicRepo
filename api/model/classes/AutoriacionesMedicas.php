
<?php 
require_once "Modelo.php";

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class Convenio                    --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 10-11-2015                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class AutoriacionesMedicas extends Modelo
{	
	function __construct()
	{
		 parent::__construct(); 
     
     
	}


    public function getAutZima($folio)
    {     
        try{ 
          $sql="SELECT Fol_ZIMA from RegMVZM where Fol_MedicaVial='".$folio."'";     
          $result = $this->_db->query($sql);
          $respuesta = $result->fetch();     
          $folZima= $respuesta['Fol_ZIMA'];    
           $sql="SELECT * From PUAutorizacionesMedicas
            Inner Join PUTipoAutorizacion On PUTipoAutorizacion.TipAut_clave=PUAutorizacionesMedicas.TipAut_clave
            Inner Join PUDetalleAutorizacion On PUAutorizacionesMedicas.PUdetAut_clave=PUDetalleAutorizacion.PUdetAut_clave
            Inner Join PURespuesta On PUAutorizacionesMedicas.Res_clave=PURespuesta.Res_clave
            Where REG_folio='".$folZima."' and Aut_cancelado='' 
            Order by Aut_fecreg DESC";
           $result = $this->_db_zima->query($sql);
           $respuesta = $result->fetchAll(PDO::FETCH_OBJ); 
           return $respuesta;
       }catch(Exception $e){
         print_r($e->getMessage());
       }
    }    

    public function getAutMV($folio)
    {     
        try{ 
          
           $sql="SELECT * FROM AutorizacionMedica a
                INNER JOIN MovimientoAut b on a.AUM_clave = b.AUM_clave
                INNER JOIN Expediente c on a.AUM_folioMV = c.Exp_folio
                Inner join TipoMovimiento on b.TIM_claveint = TipoMovimiento.TIM_claveint
                WHERE Exp_folio='".$folio."'";
           $result = $this->_db->query($sql);
           $respuesta = $result->fetchAll(PDO::FETCH_OBJ); 
           return $respuesta;
       }catch(Exception $e){
         print_r($e->getMessage());
       }
    }    
}
?>