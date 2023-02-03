<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class Addendum extends Modelo
{
    
    function __construct()
    {
         parent::__construct();
    }

    public function setAddendum($fol,$datos,$usr)
    {
        $tipDoc= $datos->doc;
        $cuerpo= $datos->cuerpo;
        $noSub = $datos->noSub;
        $usu   = $datos->usr;

        if($usu=='M'){
            $usr='scisnerosP';
        }

        if($noSub==''){$noSub=0;}

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
                        ,Add_sub                   
                ) VALUES (".$cveAdd.",".$cveCont.",'".$fol."',".$tipDoc.",now(),'".$usr."','".$cuerpo."',".$noSub.")";
        $result = $this->_db->query($query);             
        $respuesta = 1;        
    }catch(Exception $e){
        $respuesta=$e->getMessage();
    }    
     return $respuesta; 
     $this->_db=null;        
    }

    public function getSubsecuencias($fol)
    {
        try{        
            $sql = "Select count(*) from Subsecuencia  where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $rs = $result->fetch();
            return $rs[0];    
        }catch(Exception $e){
            $respuesta="error";
        }        
        $this->_db=null;        
    }
}

 ?>