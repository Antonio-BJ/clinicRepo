
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
    public function saveTicket($fol,$usr,$datos,$tip)
    {     
        $descuento=0;
        $total=0;
        $Par_cve=0;
        if(!$tip) $tip='';
        if($tip==''){
          if($tip==0){
            $Par_cve=1;
          }elseif($tip=='empleado'){
            $Par_cve=3;
          }else{
            if($tip==3){
              $Par_cve=5;
            }else{
              $Par_cve=4;
            }
          }
        }else{
          $Par_cve='';
        }
        
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
              $descuento = $descuento+round($porcentaje,2);
              $total = round($subtotal,2) - $descuento;               

              $sql="SELECT ite_clave, ite_item, ite_descripcion, ite_presentacion, Tip_clave FROM ItemOrtho_nuevo where ite_cons=".$key->cveItem."";
              $result =   $this->_db->query($sql);
              $rs =       $result->fetch();
              $fecha= date("Y-m-d H:i:s");

              if($key->precio>0){
                $precioTotal= round($key->precio-($key->precio*$key->descuento)/100,2);
                $sql="INSERT INTO Item_particulares(it_cons,Exp_folio,it_codReg,it_codMV,it_prod,it_desc,it_pres,it_fecReg,it_folRecibo,it_precio,it_descuento,Tip_clave,it_serie, it_precioTotal) 
                  Values (:it_cons,:Exp_folio,:it_codReg,:it_codMV,:it_prod,:it_desc,:it_pres,:it_fecReg,:it_folRecibo,:it_precio,:it_descuento,:Tip_clave,'P', :it_precioTotal)";

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
                $stmt->bindParam('it_precioTotal', $precioTotal);

                $stmt->execute();
              }              
            }      

            $query="Select Exp_completo, Exp_fecreg, Uni_clave From Expediente where Exp_folio='".$fol."'";
            $result =   $this->_db->query($query);
            $rs =       $result->fetch();
            $nombreLes=$rs['Exp_completo'];
            $fecRegistro= $rs['Exp_fecreg'];
            $unidad = $rs['Uni_clave'];
            
            if($datosRecibo->medico){
              $query="Select Med_nombre,Med_paterno, Med_materno, Med_cedula from Medico where Med_clave=". $datosRecibo->medico;
              $result =   $this->_db->query($query);
              $rs =       $result->fetch();
              $medNombre= $rs['Med_nombre']." ".$rs['Med_paterno']." ".$rs['Med_materno'];
              $medCedula= $rs['Med_cedula'];
            }
            $terminacion= $datosRecibo->terminacion;
            $lnTer = strlen($terminacion);            
            if($lnTer==3){
              $terminacion='0'.$terminacion; 
            }elseif($lnTer==2){
              $terminacion='00'.$terminacion; 
            }

            if(!$datosRecibo->observaciones) $datosRecibo->observaciones='-';
            
            
            $sql="INSERT INTO reciboParticulares (Recibo_cont,Recibo_serie,Usu_login,Exp_folio,Recibo_fecExp,Exp_fechAtencion,Recibo_total,Recibo_mpago,Recibo_doc, Recibo_Tipo,Recibo_banco,Recibo_terminacion,Recibo_obsMultiple,Uni_clave,CON_cve,Par_tipo) 
                  Values (:Recibo_cont,'P',:Usu_login,:Exp_folio,now(),:Exp_fechAtencion,:Recibo_total,:Recibo_mpago,:Recibo_doc, 1,:Recibo_banco,:Recibo_terminacion,:Recibo_obsMultiple,:Uni_clave,:CON_cve,:Par_tipo)";
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam('Recibo_cont', $cveRec);                                  
            $stmt->bindParam('Usu_login', $usr);
            $stmt->bindParam('Exp_folio', $fol);                   
            $stmt->bindParam('Exp_fechAtencion', $fecRegistro);    
            $stmt->bindParam('Recibo_total', round($total,2));
            // use PARAM_STR although a number  
            $stmt->bindParam('Recibo_mpago', $datosRecibo->fPago); 
            $stmt->bindParam('Recibo_doc', $datosRecibo->medico);                                   
            $stmt->bindParam('Recibo_banco', $datosRecibo->banco); 
            $stmt->bindParam('Recibo_terminacion', $terminacion);
            $stmt->bindParam('Recibo_obsMultiple', $datosRecibo->observaciones);
            $stmt->bindParam('Uni_clave', $unidad);
            $stmt->bindParam('CON_cve',$tip);
            $stmt->bindParam('Par_tipo',$Par_cve);

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
     public function InsertItems_recibo($noRecibo)
    {
      
       $sql="SELECT * FROM Item_particulares where it_folRecibo=".$noRecibo." and it_serie='P'";
                $result = $this->_db->query($sql);
                $respuesta = $result->fetchAll();                
                foreach($respuesta as $row){

                            $query="SELECT  Max(it_cons)+1 as clave From Item_particulares Where it_folRecibo=".$noRecibo;
                            $result =  $this->_db->query($query);
                            $rs1 =       $result->fetch();
                            $cveItem=   $rs1['clave']; 
                            if($cveItem==''||$cveItem==NULL){$cveItem=1;}     
                            $precioTotal= round($row['it_precio']-($row['it_precio']*$row['it_descuento'])/100);

                            $sql="INSERT INTO Item_particulares(it_cons,Exp_folio,it_codReg,it_codMV,it_prod,it_desc,it_pres,it_fecReg,it_folRecibo,it_serie,it_precio,it_descuento,Tip_clave, It_precioTotal) 
                                Values (:it_cons,:Exp_folio,:it_codReg,:it_codMV,:it_prod,:it_desc,:it_pres,:it_fecReg,:it_folRecibo,'R',:it_precio,:it_descuento,:Tip_clave,:It_precioTotal)";

                                if(!$row['it_codReg']){
                                    $row['it_codReg']='-';
                                }


                            $stmt = $this->_db->prepare($sql);
                            $stmt->bindParam('it_cons', $cveItem);                                  
                            $stmt->bindParam('Exp_folio', $row['Exp_folio']);
                            $stmt->bindParam('it_codReg', $row['It_codReg']);       
                            $stmt->bindParam('it_codMV', $row['it_codMV']);    
                            $stmt->bindParam('it_prod', $row['it_prod']);    
                            $stmt->bindParam('it_desc', $row['it_desc']);
                            // use PARAM_STR although a number  
                            $stmt->bindParam('it_pres', $row['it_pres']); 
                            $stmt->bindParam('it_fecReg',$row['it_fecReg']);                       
                            $stmt->bindParam('it_folRecibo', $noRecibo); 
                            $stmt->bindParam('it_precio', $row['it_precio']); 
                            $stmt->bindParam('it_descuento', $row['it_descuento']);           
                            $stmt->bindParam('Tip_clave', $row['Tip_clave']);
                            $stmt->bindParam('It_precioTotal', $precioTotal);

                            $stmt->execute();              
                } 
    }   
}
?>