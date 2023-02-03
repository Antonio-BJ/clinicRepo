<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class FactExpress extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function getUnidadesPropias()
    {        
    	try{
            $query="Select Uni_nombrecorto, Uni_clave From Unidad where Uni_propia = 'S' AND UNI_clave <> 8";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
        	//$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }

    public function getFoliosUnidad($unidad)
    {        
        try{
            $query = "Select Exp_folio, Cia_nombrecorto, Date_Format(Exp_fecreg, '%d-%m-%Y') as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_siniestro, Fact_folio, Uni_nombrecorto
                            From Expediente
                            inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
                            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                            Where Exp_cancelado=0 ";

                            if ($unidad == 6) {
                                $query .= "and Expediente.Cia_clave in (4,15) ";
                            }else{
                                $query .= "and Expediente.Cia_clave = 4 ";
                            }

                            $query .= "and Expediente.Cia_clave in (4,15)
                            and Exp_fecreg > '2013-06-10 00:00:00'
                            and Expediente.Uni_clave =".$unidad." order by Exp_fecreg and Exp_folio and Cia_nombrecorto";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }

    
}
?>