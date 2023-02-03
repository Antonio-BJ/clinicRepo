<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';

require('fpdf.php');
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
            $sql="SELECT
            Expediente.Exp_folio,
            Exp_completo,
            Exp_triageActual,
            Expediente.Cia_clave,
            Exp_edad,
            Exp_sexo,
            Exp_fecreg,
            Expediente.Pro_clave,
            Pro_nombre,
            Cia_nombrecorto,
            Exp_telefono,
            Exp_mail,
            Rel_clave,
            Exp_fechaNac,
            Exp_deducible,
            Exp_coaseguro,
        IF
            ( Total_sesiones IS NULL, 0, Total_sesiones ) Total_sesiones,
            ( SELECT count(*) FROM Rehabilitacion WHERE Expediente.Exp_folio = Rehabilitacion.Exp_folio ) contador,
            IF
                ( CAST(Total_sesiones AS UNSIGNED) > (SELECT count(*) FROM Rehabilitacion WHERE Expediente.Exp_folio = Rehabilitacion.Exp_folio) , 'correcto','error' ) compara,
            Uni_nombrecorto,
            ( SELECT count(*) FROM CarritoCompras WHERE Expediente.Exp_folio = CarritoCompras.Exp_folio ) contCarrito,
            TIMESTAMPDIFF(
                DAY,
            IF
                (
                    isnull((
                        SELECT
                            Rehab_fecha 
                        FROM
                            Rehabilitacion 
                        WHERE
                            Rehab_cons = contador 
                            AND Expediente.Exp_folio = Rehabilitacion.Exp_folio 
                        )),
                    Exp_fecreg,
                ( SELECT Rehab_fecha FROM Rehabilitacion WHERE Rehab_cons = contador AND Expediente.Exp_folio = Rehabilitacion.Exp_folio )),
            NOW()) AS dias_transcurridos 
        FROM
            Expediente
            INNER JOIN Producto ON Expediente.Pro_clave = Producto.Pro_clave
            INNER JOIN Compania ON Expediente.Cia_clave = Compania.Cia_clave
            INNER JOIN Unidad ON Expediente.Uni_clave = Unidad.Uni_clave
            LEFT JOIN Solo_rehabilitacion ON Expediente.Exp_folio = Solo_rehabilitacion.Exp_folio
            LEFT JOIN Hospitalario ON Expediente.Exp_folio = Hospitalario.Exp_folio 
        WHERE
            Expediente.Exp_fecreg BETWEEN '".$datos->fechaIni."' 
            AND '".$datos->fechaFin." 23:59:59' 
            AND Expediente.Pro_clave = 4 
            AND Exp_cancelado <>1";
          
            
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


    public function juegofacturacion($folio,$datos)
    {         
        try {
            
        $pdf = new FPDF();
        $pa= $datos->pa;
        $im= $datos->im;
        $id= $datos->id;
        $cu= $datos->cu;
        $rh= $datos->rh;
        $ir= $datos->ir;
       

        $tipoPa= '';
        $tipoIm= '';
        $tipoId= '';
        $tipoCu= '';
        $tipoRh= '';
        $tipoIr= '';

        $tipoDocs ='';
        if($pa==1){
            $tipoDocs.='1,'; 
        }
        if($im==1) {
            $tipoDocs.='18,'; 
        }
        if($id==1){
            $tipoDocs.='16,'; 
        };
        if($cu==1){
            $tipoDocs.='15,'; 
        };
        if($rh==1){
            $tipoDocs.='28,'; 
        };
        if($ir==1){
            $tipoDocs.='23,'; 
        };

        $tipoDocs.=0;
        
        $sql = "SELECT * FROM DocumentosDigitales where REG_folio='".$folio."' AND  Arc_tipo in (".$tipoDocs.")";

        $result = $this->_db->query($sql);
        $respuesta = $result->fetchAll();  
        
        
        foreach ($respuesta as $key => $value) {
                     
                //Primera página
                $pdf->AddPage();
            
                // $pdf->Image('prueba.jpg' , 80 ,22, 35 , 38,'JPG', 'http://www.desarrolloweb.com');

                $pdf->Image('../../registro/'.$value['Arc_archivo'], 0, 0, 200, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
        }
                      
        // //Primera página
        // $pdf->AddPage();
       
        // // $pdf->Image('prueba.jpg' , 80 ,22, 35 , 38,'JPG', 'http://www.desarrolloweb.com');

        // $pdf->Image('myClasses/prueba.jpg', 10, 10, 200, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

        $url="JuegosFacturacion";
        $direc=is_dir($url);
        if($direc!=1){
        mkdir($url);
        }

        $pdf->Output($url.'/juego_facturacion_'.$folio.'.pdf','F');
        exit;
        return $respuesta;   
         $this->_db=null;   
         //code...
        } catch (Exception $e) {
            echo $e->getmessage();
        }
    }
}
?>