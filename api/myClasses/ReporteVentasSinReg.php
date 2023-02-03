<?php 

require_once "Modelo.php";
class reporteVentasSinReg extends Modelo
{
	function __construct()
	{
		parent::__construct();
	}

//////////////////// RECUPERAMOS EL LISTADO DE UNIDADES //////////////////////////
  public function getUnidades($datos)
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
    }

////////////////////BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS//////////////////////////
	public function buscaParametrosReporte($datos,$usr,$unidadLoc)
    {
      $fechaIni 		=	$datos->fechaIni;
  		$fechaFin			=	$datos->fechaFin;
  		$unidad				=	$datos->unidad;
      if($unidad==''){
        if($usr=='chernandez' || $usr=='algo' || $usr=='alozano'){
          $unidad = '';
        }else{
          $unidad = $unidadLoc;
        }
      }

        try{
              $query ="SELECT sinReg_id as FOLIO, concat(sinReg_nombre,' ',sinReg_apPaterno,' ',sinReg_apMaterno) as NOMBRE, 
                        sinReg_fecha as FECHA_REGISTRO,
                        Uni_nombrecorto as UNIDAD,
                        Recibo_cont as FOLIO_RECIBO, Recibo_fecExp as FECHA_RECIBO, Recibo_total as TOTAL, metodo as METODO_PAGO,
                        Recibo_facturado as FACTURADO, UPPER(Recibo_banco) as BANCO, Recibo_terminacion as TERMINACION,
                        Recibo_aplicado as RECIBO_APLICADO, Recibo_serie as SERIE
                        from ventas_sin_registro
                        left join reciboParticulares on ventas_sin_registro.sinReg_id=reciboParticulares.Exp_folio
                        inner join Unidad on ventas_sin_registro.Uni_clave = Unidad.Uni_clave
                        inner join metodoPagoPar on reciboParticulares.Recibo_mpago=metodoPagoPar.id_metodo
                        where Recibo_fecExp between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59' ";
                            if ($unidad!='') {
                                $query.="and Unidad.Uni_clave='".$unidad."' ";
                            } else {
                                $query.="and Uni_propia='S' and Unidad.Uni_clave<>8 ";
                            }
                        $query.="and Recibo_cancelado<>1 order by Recibo_fecExp
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