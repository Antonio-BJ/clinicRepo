<?php 

require_once "Modelo.php";

class semaforoAxa extends Modelo
{
	function __construct()
	{
		parent::__construct();
	}

//////////////////// RECUPERAMOS EL LISTADO DE REGISTROS DE AXA CE //////////////////////////
  public function getRegistros($datos)
    {
        try{
              $query ="SELECT ObsParticulares.Exp_folio, ObsParticulares.Par_convenio, ObsParticulares.Par_noSesiones,
                        Expediente.Exp_completo, Expediente.Exp_fecreg
                        from ObsParticulares
                        inner join Expediente on ObsParticulares.Exp_folio=Expediente.Exp_folio
                        where ObsParticulares.Par_convenio=15
                        order by ObsParticulares.Exp_folio desc
                       ";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }


//////////////////// RECUPERAMOS LOS DATOS DEL PACIENTE ESPECIFICO //////////////////////////
  public function verDetalles($folio)
    {
        try{
              $query ="SELECT max(Rehab_cons) as SES_TOMADAS, max(Rehab_fecha) as ULTIMA_RH
                        from Rehabilitacion
                        where Exp_folio='".$folio."'
                       ";
            //print_r($query);
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }


//////////////////// BUSCAMOS UN REGISTRO EN PARTICULAR //////////////////////////
  public function buscaFolio($datos)
    {
      $folio = $datos->folio;
        try{
              $query ="SELECT ObsParticulares.Exp_folio, ObsParticulares.Par_convenio, ObsParticulares.Par_noSesiones,
                        Expediente.Exp_completo, Expediente.Exp_fecreg
                        from ObsParticulares
                        inner join Expediente on ObsParticulares.Exp_folio=Expediente.Exp_folio
                        where ObsParticulares.Par_convenio=15
                        and ObsParticulares.Exp_folio='".$folio."'
                       ";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }





### TERMINA TODO ###
}
?>