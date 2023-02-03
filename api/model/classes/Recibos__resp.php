
<?php 
require_once "Modelo.php";

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class Convenio                    --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 10-11-2015                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class Recibos extends Modelo
{	
	function __construct()
	{
		 parent::__construct(); 
     
     
	}


    public function getItem($cveItem)
    {     
        try{ 
          
           $sql="select * from ItemOrtho_nuevo where Ite_cons='".$cveItem."'";
           $result = $this->_db->query($sql);
           $respuesta = $result->fetch(PDO::FETCH_OBJ); 
           return $respuesta;
       }catch(Exception $e){
         print_r($e->getMessage());
       }
    }    
    public function saveTicket($fol,$usr,$datos)
    {     
        $descuento=0;
        $total=0;
        try{ 
          
              $sql="SELECT MAX(Recibo_cont)+1 AS contador FROM reciboParticulares WHERE Recibo_serie='P'";
              $result = $this->_db->query($sql);
              $respuesta = $result->fetch();
              $cveRec = $respuesta['contador'];
              $datosRecibo = $datos[0];
              $items = $datos[1];

              $query="Select Max(it_cons)+1 as clave From Item_particulares Where Exp_folio='".$fol."' ";
              $result =  $this->_db->query($query);
              $rs1 =       $result->fetch();
              $cveItem=   $rs1['clave']; 
              if($cveItem==''||$cveItem==NULL){$cveItem=1;}     

           foreach ($items as $key) {   

              $subtotal=$subtotal+$key->precio;
              $porcentaje=($key->descuento*$key->precio)/100;
              $descuento = $descuento+$porcentaje;
              $total = $subtotal - $descuento;  

              $sql="SELECT ite_clave, ite_item, ite_descripcion, ite_presentacion, Tip_clave FROM ItemOrtho_nuevo where ite_cons=".$key->cveItem."";
              $result =   $this->_db->query($sql);
              $rs =       $result->fetch();
              $fecha= date("Y-m-d H:i:s");

              $sql="INSERT INTO Item_particulares(it_cons,Exp_folio,it_codReg,it_codMV,it_prod,it_desc,it_pres,it_fecReg,it_folRecibo,it_precio,it_descuento,Tip_clave) 
                Values (:it_cons,:Exp_folio,:it_codReg,:it_codMV,:it_prod,:it_desc,:it_pres,:it_fecReg,:it_folRecibo,:it_precio,:it_descuento,:Tip_clave)";

              $stmt = $this->_db->prepare($sql);
              $stmt->bindParam('it_cons', $cveItem);                                  
              $stmt->bindParam('Exp_folio', $fol);
              $stmt->bindParam('it_codReg', $key->cveItem);       
              $stmt->bindParam('it_codMV', $rs['ite_clave']);    
              $stmt->bindParam('it_prod', $rs['ite_item']);    
              $stmt->bindParam('it_desc', $rs['ite_descripcion']);
              // use PARAM_STR although a number  
              $stmt->bindParam('it_pres', $rs['ite_presentacion']); 
              $stmt->bindParam('it_fecReg', $fecha);                       
              $stmt->bindParam('it_folRecibo', $cveRec); 
              $stmt->bindParam('it_precio', $key->precio); 
              $stmt->bindParam('it_descuento', $key->descuento);           
              $stmt->bindParam('Tip_clave', $rs['Tip_clave']);

              $stmt->execute();              
            }      

            $query="Select Exp_completo, Exp_fecreg From Expediente where Exp_folio='".$fol."'";
            $result =   $this->_db->query($query);
            $rs =       $result->fetch();
            $nombreLes=$rs['Exp_completo'];
            $fecRegistro= $rs['Exp_fecreg'];
            
            $query="Select Med_nombre,Med_paterno, Med_materno, Med_cedula from Medico where Med_clave=". $datosRecibo->medico;
            $result =   $this->_db->query($query);
            $rs =       $result->fetch();
            $medNombre= $rs['Med_nombre']." ".$rs['Med_paterno']." ".$rs['Med_materno'];
            $medCedula= $rs['Med_cedula'];
            $terminacion= $datosRecibo->terminacion;
            $lnTer = strlen($terminacion);            
            if($lnTer==3){
              $terminacion='0'.$terminacion; 
            }elseif($lnTer==2){
              $terminacion='00'.$terminacion; 
            }

            
            
            $sql="INSERT INTO reciboParticulares (Recibo_cont,Recibo_serie,Usu_login,Exp_folio,Recibo_fecExp,Exp_fechAtencion,Recibo_total,Recibo_mpago,Recibo_doc, Recibo_Tipo,Recibo_banco,Recibo_terminacion) 
                  Values (:Recibo_cont,'P',:Usu_login,:Exp_folio,now(),:Exp_fechAtencion,:Recibo_total,:Recibo_mpago,:Recibo_doc, 1,:Recibo_banco,:Recibo_terminacion)";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam('Recibo_cont', $cveRec);                                  
            $stmt->bindParam('Usu_login', $usr);
            $stmt->bindParam('Exp_folio', $fol);                   
            $stmt->bindParam('Exp_fechAtencion', $fecRegistro);    
            $stmt->bindParam('Recibo_total', $total);
            // use PARAM_STR although a number  
            $stmt->bindParam('Recibo_mpago', $datosRecibo->fPago); 
            $stmt->bindParam('Recibo_doc', $datosRecibo->medico);                                   
            $stmt->bindParam('Recibo_banco', $datosRecibo->banco); 
            $stmt->bindParam('Recibo_terminacion', $terminacion);

            if($stmt->execute()){
              $respuesta=  array('respuesta' =>'exito', 'folRecibo'=>$cveRec,'folio'=>$fol);
            }else{
              $respuesta=  array('respuesta' => 'error');
            }

           //return $folRecibo;
       }catch(Exception $e){
          $respuesta=  array('respuesta' => $e->getMessage() );
       }
       return $respuesta;
    }    
}
?>