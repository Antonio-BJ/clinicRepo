<?php 

require_once "Modelo.php";

class reporteMembresias extends Modelo
{
	function __construct()
	{
		parent::__construct();
	}

////////////////////BUSCAMOS DATOS DE ACUERDO A LOS PARAMETROS//////////////////////////
	public function buscaParametrosReporte($datos)
    {
      $fechaIni 			=	$datos->fechaIni;
  		$fechaFin			=	$datos->fechaFin;
  		$compania			=	$datos->compania;
  		$unidad				=	$datos->unidad;

        try{
        	if ($unidad=="") {
              $query ="SELECT mem_id, mem_folio, MembresiaMv.mem_serie, mem_nombre, mem_fecreg, Uni_nombrecorto, Usu_nombre
            						from MembresiaMv
            						inner join SerieMembresia on MembresiaMv.mem_serie=SerieMembresia.SEM_Serie
            						inner join Unidad on SerieMembresia.Uni_clave=Unidad.Uni_clave
                        inner join Usuario on MembresiaMv.Usu_login = Usuario.Usu_login
            						WHERE mem_fecreg between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59'
              			   ";
        	} elseif ($unidad&&$fechaIni&&$fechaFin) {
              $query ="SELECT mem_id, mem_folio, MembresiaMv.mem_serie, mem_nombre, mem_fecreg, Uni_nombrecorto, Usu_nombre
                        from MembresiaMv
                        inner join SerieMembresia on MembresiaMv.mem_serie=SerieMembresia.SEM_Serie
                        inner join Unidad on SerieMembresia.Uni_clave=Unidad.Uni_clave
                        inner join Usuario on MembresiaMv.Usu_login = Usuario.Usu_login
                        WHERE mem_fecreg between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59'
						            and SerieMembresia.Uni_clave=".$unidad."
              			   ";
        	}

            //print_r($query);
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);
            $respuesta =array('membresias'=>$respuesta);
        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');   
        }    
         return $respuesta;    
    }

### TERMINA TODO ###
}
?>