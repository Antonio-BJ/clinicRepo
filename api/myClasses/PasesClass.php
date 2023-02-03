<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';
/**
*  clase para el control de incidencias 
*/
class PasesClass extends Modelo
{	
    public $mimemail;
	function __construct()
	{
		 parent::__construct();
         
	}
/************************************************************************************************************************************/

    
    public function setPase($datos)
    {        

        $folio          = $datos->folio;
        $motivo     = $datos->motivo;
        $diagnostico  = $datos->diagnostico; 
        $unidad        = $datos->unidad;  
        $correo        = $datos->correo;  
        $usr           = $datos->usr;   
        $msj           = $datos->msj;
        try{                  

            $sql="INSERT INTO Pase(EXP_folio,PAS_fecreg,UNI_clave,PAS_motivo,PAS_correo,PAS_diagnostico,Usu_login, PAS_msj)
                              VALUES(:EXP_folio,now(),:UNI_clave,:PAS_motivo,:PAS_correo,:PAS_diagnostico, :Usu_login, :PAS_msj)";
            $temporal= $this->_db->prepare($sql);            
            $temporal->bindParam("EXP_folio",$folio);              
            $temporal->bindParam("UNI_clave",$unidad);                         
            $temporal->bindParam("PAS_motivo",$motivo);                
            $temporal->bindParam("PAS_correo",$correo);                
            $temporal->bindParam("PAS_diagnostico",$diagnostico); 
            $temporal->bindParam("Usu_login",$usr);    
            $temporal->bindParam("PAS_msj",$msj);                                       
            if($temporal->execute()){                 
                
                $respuesta = array('respuesta' =>'exito');      
            }else{
                $respuesta = array('respuesta' =>'error');       
            }    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    
    }

     
}
 ?>