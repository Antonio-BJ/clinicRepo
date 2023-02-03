<?php 

require_once "Modelo.php";

class reporteParticulares extends Modelo
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

//////////////////// RECUPERAMOS EL LISTADO DE COMPAÑIAS //////////////////////////
  public function getCompanias($datos)
    {
        try{
              $query ="SELECT * from Compania
                        where Cia_clave=51 or Cia_clave=54 or Cia_clave=53
                        order by Cia_nombrecorto
                       ";

            //print_r($query);
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
            //$respuesta =array('membresias'=>$respuesta);
        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }

////////////////////BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS//////////////////////////
	public function buscaParametrosReporte($datos)
    {
      $fechaIni 		=	$datos->fechaIni;
  		$fechaFin			=	$datos->fechaFin;
  		$compania			=	$datos->compania;
  		$unidad				=	$datos->unidad;

        try{
              $query ="SELECT Expediente.Exp_folio as FOLIO, Exp_completo as NOMBRE, Exp_fecreg as FECHA_REGISTRO, 
              Cia_nombrecorto as COMPANIA,
              Uni_nombrecorto as UNIDAD,
              concat('---') as MEDICO,
              Recibo_cont as FOLIO_RECIBO, Recibo_fecExp as FECHA_RECIBO, Recibo_total as TOTAL, metodo as METODO_PAGO, 
              Recibo_facturado as FACTURADO, UPPER(Recibo_banco) as BANCO, Recibo_terminacion as TERMINACION, Recibo_aplicado as RECIBO_APLICADO
              from Expediente
              left join reciboParticulares on Expediente.Exp_folio=reciboParticulares.Exp_folio
              inner join Compania on Expediente.Cia_clave = Compania.Cia_clave
              inner join Unidad on Expediente.Uni_ClaveActual = Unidad.Uni_clave              
              inner join metodoPagoPar on reciboParticulares.Recibo_mpago=metodoPagoPar.id_metodo
              where Recibo_fecExp between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59'
              and (reciboParticulares.Recibo_doc=0 or reciboParticulares.Recibo_doc=null) ";
              if ($unidad!='') {
                  $query.="and Unidad.Uni_clave='".$unidad."' ";
                  if ($compania!='') {
                    $query.="and Expediente.Cia_clave='".$compania."' ";
                  } else {
                    $query.="and (Expediente.Cia_clave=51 or Expediente.Cia_clave=54 or Expediente.Cia_clave=53) ";
                  }
                  $query.=" ";
              } else {
                  $query.="and Uni_propia='S' and Exp_cancelado<>1 and Unidad.Uni_clave<>8 ";
                  if ($compania!='') {
                    $query.="and Expediente.Cia_clave='".$compania."' ";
                  } else {
                    $query.="and (Expediente.Cia_clave=51 or Expediente.Cia_clave=54 or Expediente.Cia_clave=53) ";
                  }
                  $query.=" ";
              };

              $query.="union ";

              $query.="
              SELECT Expediente.Exp_folio as FOLIO, Exp_completo as NOMBRE, Exp_fecreg as FECHA_REGISTRO, 
              Cia_nombrecorto as COMPANIA,
              Uni_nombrecorto as UNIDAD,
              UPPER( CONVERT( CONCAT( Medico.Med_nombre,' ', Medico.Med_paterno,' ', Medico.Med_materno) USING utf8)) as MEDICO,
              Recibo_cont as FOLIO_RECIBO, Recibo_fecExp as FECHA_RECIBO, Recibo_total as TOTAL, metodo as METODO_PAGO, 
              Recibo_facturado as FACTURADO, UPPER(Recibo_banco) as BANCO, Recibo_terminacion as TERMINACION, Recibo_aplicado as RECIBO_APLICADO
              from Expediente
              left join reciboParticulares on Expediente.Exp_folio=reciboParticulares.Exp_folio
              inner join Compania on Expediente.Cia_clave = Compania.Cia_clave
              inner join Unidad on Expediente.Uni_ClaveActual = Unidad.Uni_clave
              left join Medico on reciboParticulares.Recibo_doc=Medico.Med_clave
              inner join metodoPagoPar on reciboParticulares.Recibo_mpago=metodoPagoPar.id_metodo
              where Recibo_fecExp between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59' ";
              if ($unidad!='') {
                  $query.="and Unidad.Uni_clave='".$unidad."' ";
                  if ($compania!='') {
                    $query.="and Expediente.Cia_clave='".$compania."' ";
                  } else {
                    $query.="and (Expediente.Cia_clave=51 or Expediente.Cia_clave=54 or Expediente.Cia_clave=53) ";
                  }
                  $query.=" ";
              } else {
                  $query.="and Uni_propia='S' and Exp_cancelado<>1 and Unidad.Uni_clave<>8 ";
                  if ($compania!='') {
                    $query.="and Expediente.Cia_clave='".$compania."' ";
                  } else {
                    $query.="and (Expediente.Cia_clave=51 or Expediente.Cia_clave=54 or Expediente.Cia_clave=53) ";
                  }
                  $query.=" ";
              }
              
              $query.="
              order by FECHA_RECIBO";

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