<?php 

require_once "Modelo.php";

class reporteItemsParticulares extends Modelo
{
	function __construct()
	{
		parent::__construct();
	}

//////////////////// RECUPERAMOS EL LISTADO DE UNIDADES //////////////////////////
/*  public function getUnidades($datos)
    {
        try{
              $query ="SELECT * from Unidad
                        where Uni_propia='S'
                        and Uni_activa='S'
                        order by Uni_nombrecorto
                       ";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }*/

////////////////////BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS//////////////////////////
	public function buscaParametrosReporte($datos)
    {
      $fechaIni 		=	$datos->fechaIni;
  		$fechaFin			=	$datos->fechaFin;
  		//$unidad				=	$datos->unidad;

        try{
              $query ="SELECT Exp_folio as FOLIO_PACIENTE, convert(it_prod using utf8) as PRODUCTO, 
                       upper( convert(it_desc using utf8)) as DESCRIPCION, it_fecReg as FECHA_VENTA,
                       it_folRecibo as FOLIO_RECIBO, it_precio as PRECIO, it_descuento as DESCUENTO 
                       from Item_particulares 
                       where it_fecreg between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59'
                       ";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            print_r($e);
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }

### TERMINA TODO ###
}
?>