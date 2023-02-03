<?php 

require_once "Modelo.php";
/**
*  classe hacer el detalle general de Px
*/
class DetallePx extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function getDatosPersonales($fol)
    {        
    	try{
            $sql = "Select Exp_completo, Exp_edad,Exp_sexo, Exp_fecreg, Pro_nombre, Cia_nombrecorto, Exp_telefono, Exp_mail, Rel_clave from Expediente
            inner join Producto on Expediente.Pro_clave= Producto.Pro_clave
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave            
             where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch(PDO::FETCH_OBJ);        
        }catch(Exception $e){        	
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
    public function getSignosVitales($fol)
    {        
        try{
            $sql = "select Vit_fecha as fecha from Vitales where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
     public function getHistoriaClinica($fol)
    {        
        try{
            $sql = "select Con_fecha as fecha from Consulta where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch(PDO::FETCH_OBJ);        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
     public function getNotaMedica($fol)
    {        
        try{
            $sql = "select Not_estatus as estatus,Not_fechareg as fecha from NotaMedica where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch(PDO::FETCH_OBJ);        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;   
         $this->_db=null; 
    }
     public function getsubsecuencias($fol)
    {        
        try{
            $sql = "select Sub_fecha as fecha, Sub_hora as hora from Subsecuencia where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }

    public function getRehabilitaciones($fol)
    {        
        try{
            $sql = "select Rehab_fecha as fecha from Rehabilitacion where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }

    public function getAviso($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeAviso where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
    public function getConsMed($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeConsMed where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;    
    }
    public function getCuestionario($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeCuestionario where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
    public function getFiniquito($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeFiniquito where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }

    public function getHistoria($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeHc where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;    
    }
    public function getIdentificacion($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeId where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
    public function getInfMedico($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeIm where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
    public function getInfAseg($fol)
    {        
        try{
            $sql = "select count(*) as contador from subeInfAse where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;    
    }
    public function getPaseMedico($fol)
    {        
        try{
            $sql = "select count(*) as contador from subePase where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;    
    }
    public function getAutorizacion($fol)
    {        
        try{
            $sql = "select AUM_clave from AutorizacionMedica where AUM_folioMV='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;    
    }
     public function getDiagnostico($fol)
    {        
        try{
            $sql = "select ObsNot_diagnosticoRx from ObsNotaMed where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();        
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;
         $this->_db=null;    
    }

    public function getModificador($folio)
    {        
        try{                                      
            $sql = "select Exp_modificador from Expediente where Exp_folio='".$folio."'";
            $result = $this->_db->query($sql);                                    
            $rs = $result->fetch();            
            if($rs){
                return $rs['Exp_modificador'];
            }else{
                return 0;
            }      
        }catch(Exception $e){
            return "error";
        } 
        $this->_db=null;       
    }
}
?>