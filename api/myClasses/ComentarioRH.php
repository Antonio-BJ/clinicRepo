<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class ComentarioRH extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function setComentario($fol,$cuerpo,$usr,$tipDoc)
    {


    	try{
        $sql = "Select MAX(Add_clave)+1 as clave From Addendum";
        $result = $this->_db->query($sql);
        $rs = $result->fetch();
        $cveAdd=$rs['clave'];
        if($cveAdd==''){
            $cveAdd=1;
        }

        $sql = "Select MAX(Add_cont)+1 as clave From Addendum where Exp_folio='".$fol."' and Add_tipoDoc=".$tipDoc;
        $result = $this->_db->query($sql);
        $rs = $result->fetch();
        $cveCont=$rs['clave'];
        if($cveCont==''){
            $cveCont=1;
        }        

    	$query= "INSERT INTO Addendum (
                        Add_clave
                        ,Add_cont
                        ,Exp_folio
                        ,Add_tipoDoc
                        ,Add_fecha
                        ,Usu_reg
                        ,Add_comentario                       
                ) VALUES (".$cveAdd.",".$cveCont.",'".$fol."',".$tipDoc.",now(),'".$usr."','".$cuerpo."')";
        $result = $this->_db->query($query);	 	     
        $respuesta = array('respuesta' => "exito");        
    }catch(Exception $e){
    	$respuesta=array('respuesta'=> $e->getMessage());
    }    
     return $respuesta;  
     $this->_db=null;        
    }
}

 ?>