<?php 
require_once "Modelo.php";
/**
*  clase para editar los estatus de folios
*/
class EdicionFolios extends Modelo
{	
	function __construct()
	{
		 parent::__construct();
	}
    /************************************************************************************************************************************/
/************************       función para Activar folios cancelados                                                     **************/
/************************************************************************************************************************************/
    public function updateStatusFolio($folio)
    {              
                try{
                    $query="Update Expediente set Exp_cancelado=0 where Exp_folio='".$folio."'";
                    $result = $this->_db->query($query);
                    }catch(Exception $e){
                      $error='error';
                    }                       
        if($error=='error'){
            return $error;
        }else{
            return 'exito';
        }
    }

    public function verificaValidacion($folio,$usuario)
    {              
               if(($folio==19 && $usuario==6)||($folio==6)){

                return 'si entro a la validacion';
                }else{
                    return 'No entró a la validacion';
                }
    }

    public function actualizaFolioFactura($folio,$folioFact)
    {              
        try{
            $query="Update Expediente Set Fact_folio='".$folioFact."' Where Exp_folio='".$folio."' and Exp_cancelado=0";
            $result = $this->_db->query($query);
        }catch(Exception $e){
          $error='error';
        }                       
        if($error=='error'){
            return $error;
        }else{
            return 'exito';
        }
    }

    public function listaLesion($opcion)
    {              
        try{
            $query="SELECT LES_clave as id, LES_nombre as label from LesionMV where TLE_claveint=".$opcion;
            $result = $this->_db->query($query);
             $rs = $result->fetchAll(PDO::FETCH_OBJ);
            return $rs;
        }catch(Exception $e){
          $error='error';
          return $error;
        }                               
    }

    public function nombreLesion($clave)
    {              
        try{
            $query="SELECT LES_nombre as nombre from LesionMV where LES_clave='".$clave."'";
            $result = $this->_db->query($query);
             $rs = $result->fetch(PDO::FETCH_OBJ);
            return $rs;
        }catch(Exception $e){
          $error='error';
          return $error;
        }                               
    }

    public function nombresLesion($datos)
    {                      
        $nombres='';        
        foreach($datos as $clave ){           
            try{               
                $query="SELECT LES_nombre as nombre from LesionMV where LES_clave='".$clave->id."'";
                $result = $this->_db->query($query);
                 $rs = $result->fetch();
                 if($nombres==''){
                    $nombres=$rs['nombre'];
                 }else{
                    $nombres.=' -- '.$rs['nombre'];
                 }                
            }catch(Exception $e){
              $error='error';
              return $error;
            }                              
        }              
         $nombres=str_replace('-','/',$nombres);     
         return $nombres;        
    }
}
 ?>