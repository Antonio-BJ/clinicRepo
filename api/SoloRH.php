<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';
/**
*  classe hacer el detalle general de Px
*/
class SoloRH extends Modelo
{
	public $mimemail;
	function __construct()
	{
		 parent::__construct();
	}

	public function getSoloRh($datos)
    {                
    	try{
            $sql = "Select Expediente.Exp_folio,Exp_completo,Exp_triageActual,Expediente.Cia_clave, Exp_edad,Exp_sexo, Exp_fecreg,Expediente.Pro_clave, Pro_nombre, Cia_nombrecorto, Exp_telefono, Exp_mail, Rel_clave, Exp_fechaNac,
             Exp_deducible, Exp_coaseguro, if(Total_sesiones is null,0,Total_sesiones) Total_sesiones, (Select count(*) from Rehabilitacion where Expediente.Exp_folio = Rehabilitacion.Exp_folio) contador, Uni_nombrecorto
            from Expediente
            inner join Producto on Expediente.Pro_clave= Producto.Pro_clave
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave 
            inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  
            left join Solo_rehabilitacion on Expediente.Exp_folio = Solo_rehabilitacion.Exp_folio         
            left join Hospitalario on Expediente.Exp_folio = Hospitalario.Exp_folio            
             where Expediente.Exp_fecreg between '".$datos->fechaIni."' and '".$datos->fechaFin."' and Expediente.Pro_clave=4 and Exp_cancelado<>1";
            
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        }catch(Exception $e){        	
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }

    public function getReciboCarrito($folio)
    {                
    	try{
            $sql = "SELECT * from CarritoCompras 
            INNER JOIN ItemCarritoCompras ON CarritoCompras.CCO_id = ItemCarritoCompras.CCO_id
            INNER JOIN CatItemsCarrito ON ItemCarritoCompras.CIC_id = CatItemsCarrito.CIC_id
            where Exp_folio='".$folio."' and CCO_cerrado=0";
                $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll();    
            return $respuesta;                                
        }catch(Exception $e){        	
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;   
    }
}
?>