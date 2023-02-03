<?php 
require_once "Modelo.php";

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class Venta sin registro          --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick  and Ana Dc                                     --------------------------
-------------------                                                         -------------------------- 
-------------------   Marzo 2016                                            --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class ventasSinRegistro extends Modelo
{
	
	function __construct()
	{
	   parent::__construct();         
	}    



    public function getItem($cveItem)
    {          
        try{
        $query="SELECT Ite_cons, ItemOrtho_nuevo.Ite_clave, Ite_item, Ite_descripcion, Ite_presentacion, Ite_precio, Item_particulares.it_descuento, ItemOrtho_nuevo.Tip_clave
                 FROM ItemOrtho_nuevo
                 LEFT JOIN Item_particulares ON Item_particulares.It_codReg=ItemOrtho_nuevo.Ite_cons WHERE Ite_cons='".$cveItem."';"; 
           #$query="select * from ItemOrtho_nuevo where Ite_cons='".$cveItem."';";
           $result = $this->_db->query($query);
           $respuesta = $result->fetch(PDO::FETCH_OBJ); 
           return $respuesta;
       }catch(Exception $e){
            $respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');     
       }
        print_r($data);
        print_r($respuesta);
    }    



	public function Nuevo($data)
    {   
        $registroGeneral=   $data[0];
        $items          =   $data[1];
        $nombre         =   $data[0]->nombre;
        $apellidoPaterno=   $data[0]->apellidoPaterno;
        $apellidoMaterno=   $data[0]->apellidoMaterno;
        $email          =   $data[0]->email;
        $telefono       =   $data[0]->telefono;
        $codigoPostal   =   $data[0]->codigoPostal;
        $enterado       =   $data[0]->enterado;
        $usuarioRegistro=   $data[0]->usuarioRegistro;
        $folio          =   $data[0]->folio;
        $fechaRegistro  =   $data[0]->fechaRegistro;
        $horaRegistro   =   $data[0]->horaRegistro;
        $fam            =   $data[0]->fam;
        $item           =   $data[0]->item;
        $descuento      =   $data[0]->descuento;
        $precio         =   $data[0]->precio;
        $total          =   $data[0]->total;
        $fPago          =   $data[0]->fPago;
        $fec            =   $data[0]->fec;
        $noRec          =   $data[0]->noRec;
        $medico         =   $data[0]->medico;
        $banco          =   $data[0]->banco;
        $terminacion    =   $data[0]->terminacion;
        $unidad         =   $data[0]->unidad;
        $cveMem         =   $data[0]->cveMembresia;
        $sexo           =   $data[0]->sexo;
        $edad           =   $data[0]->edad;
        $cuponDescuento           =   $data[0]->cuponDescuento;
        setcookie("Medico", $medico);

        if($cuponDescuento != '' || $cuponDescuento != null || $cuponDescuento != undefined){

           $cuponDescuento = $cuponDescuento;
           $bitcupon = 1;

        }else{

           $cuponDescuento = '';
           $bit = 0;
        }



          try{
              /*---------------------- RECUPERA EL ULTIMO ID DE RECIBO PARTICULARES ----------------------*/
              $query = "SELECT Recibo_cont from reciboParticulares where Recibo_cont=(select max(Recibo_cont) from reciboParticulares);";
              $result = $this->_db->query($query);
              $ultimoCont = $result->fetch();
              $nuevoCont= $ultimoCont[0] + 1;

              $query2="SELECT COUNT(*) cont FROM reciboParticulares WHERE Recibo_cont='$nuevoCont'";
              $result2 = $this->_db->query($query2);
              $respuesta2 = $result2->fetch();
              $repetido= $respuesta2['cont'];

              if($repetido>0){
                while ($repetido2>0){
                  $query3="SELECT MAX(Recibo_cont)+1 AS contador FROM reciboParticulares";
                  $result3 = $this->_db->query($query3);
                  $respuesta3 = $result3->fetch();
                  $nuevoCont = $respuesta3['contador'];

                  $query4="SELECT COUNT(*) cont FROM reciboParticulares WHERE Recibo_cont='$nuevoCont'";
                  $result4 = $this->_db->query($query4);
                  $respuesta4 = $result4->fetch();
                  $repetido2= $respuesta4['cont'];
                }
              }

              /*---------------------- INSERTA LOS DATOS DEL COMPRADOR ----------------------*/
              $query = "INSERT INTO ventas_sin_registro(sinReg_id,
                                                      sinReg_nombre,
                                                      sinReg_apPaterno,
                                                      sinReg_apMaterno,
                                                      sinReg_email,
                                                      sinReg_tel,
                                                      sinReg_codPostal,
                                                      sinReg_enterado,
                                                      sinReg_usuLogin,
                                                      sinReg_fecha,
                                                      Uni_Clave,
                                                      CVE_membresia,
                                                      sinREG_edad,
                                                      sinREG_sexo,
                                                      sinREG_cupon,
                                                      sinREG_claveCupon)

                                                VALUES(DEFAULT,
                                                       '".$nombre."',
                                                       '".$apellidoPaterno."',
                                                       '".$apellidoMaterno."',
                                                       '".$email."',
                                                       '".$telefono."',
                                                       '".$codigoPostal."',
                                                       '".$enterado."',
                                                       '".$usuarioRegistro."',
                                                       '".$fechaRegistro." ".$horaRegistro."',
                                                       ".$unidad.",
                                                       '".$cveMem."',
                                                       ".$edad.",
                                                       '".$sexo."',
                                                       '".$bitcupon."',
                                                       '".$cuponDescuento."'
                                                        );";
            $result = $this->_db->query($query);
            $respuesta ='exito';


            /*---------------------- RECUPERA EL ULTIMO ID DE RECIBO PARTICULARES ----------------------*/
            $query = "SELECT sinReg_id from ventas_sin_registro where sinReg_id=(select max(sinReg_id) from ventas_sin_registro);";
            $result = $this->_db->query($query);
            $idRecuperado = $result->fetch();
            $id= $idRecuperado[0]+0;

              $descuento=0;
              $total=0;
              $subtotal=0;

            /*---------------------- INSERTA LOS ITEMS VENDIDOS ----------------------*/
            $fechaCompleta=$fechaRegistro." ".$horaRegistro;
            $arrayPro = array();
            foreach ($items as $key) {   
              if ($key->descuento==null) {
                $descuento1=0;
              } else {
                $descuento1=$key->descuento;
              }

              if($cuponDescuento != '' || $cuponDescuento != null){


                $subtotal=$subtotal+$key->precio;
                $porcentaje=($descuento1*$key->precio)/100;
                $descuento = $descuento+round($porcentaje,2);
                $total0 = $subtotal - $descuento;  
                $total1= $total0*15/100;
                $total = $total0-$total1;


              }else{


                $subtotal=$subtotal+$key->precio;
                $porcentaje=($descuento1*$key->precio)/100;
                $descuento = $descuento+round($porcentaje,2);
                $total = $subtotal - $descuento;  


              }

              // $subtotal=$subtotal+$key->precio;
              // $porcentaje=($descuento1*$key->precio)/100;
              // $descuento = $descuento+round($porcentaje,2);
              // $total = $subtotal - $descuento;  

              if(empty($fam)){
                $query1="SELECT Tip_clave  FROM ItemOrtho_nuevo WHERE Ite_cons='".$key->cveItem."'";
                $result1 = $this->_db->query($query1);
                $tip = $result1->fetch();
                $fam= $tip['Tip_clave'];
              }

              /*------------------- ASIGNAMOS PROYECTO AL RECIBO -------------------*/

                $query2   ="SELECT ITP_id as proyecto  FROM ItemOrtho_nuevo WHERE Ite_cons='".$key->cveItem."'";
                $result2  = $this->_db->query($query2);
                $pro      = $result2->fetch();
                $pro_id   = $pro['proyecto'];

 
                array_push($arrayPro, $pro_id);
                

                           
                      $sql="INSERT INTO Item_particulares(it_cons,
                                                          Exp_folio,
                                                          it_codReg,
                                                          it_codMV,
                                                          it_prod,
                                                          it_desc,
                                                          it_pres,
                                                          it_fecReg,
                                                          it_folRecibo,
                                                          it_precio,
                                                          it_descuento,
                                                          Tip_clave,
                                                          it_serie)

                                                  Values (:it_cons,
                                                          :Exp_folio,
                                                          :it_codReg,
                                                          :it_codMV,
                                                          :it_prod,
                                                          :it_desc,
                                                          :it_pres,
                                                          :it_fecReg,
                                                          :it_folRecibo,
                                                          :it_precio,
                                                          :it_descuento,
                                                          :Tip_clave,
                                                          'P')";

                      $stmt = $this->_db->prepare($sql);
                      $stmt->bindParam('it_cons', $key->cont);                                  
                      $stmt->bindParam('Exp_folio', $id);
                      $stmt->bindParam('it_codReg', $key->cveItem);       
                      $stmt->bindParam('it_codMV', $key->cveMV);#$rs['cveMV']);    
                      $stmt->bindParam('it_prod', $key->item);#$rs['ite_item']);    
                      $stmt->bindParam('it_desc', $key->desc);#$rs['ite_descripcion']);
                      // use PARAM_STR although a number  
                      $stmt->bindParam('it_pres', $key->pres);#$rs['ite_presentacion']); 
                      $stmt->bindParam('it_fecReg', $fechaCompleta);                       
                      $stmt->bindParam('it_folRecibo', $nuevoCont); 
                      $stmt->bindParam('it_precio', $key->precio); 
                      $stmt->bindParam('it_descuento', $descuento1);
                      #$stmt->bindParam('it_descuento', $key->descuento);
                      $stmt->bindParam('Tip_clave', $fam);#$rs['Tip_clave']);                                     

                      $stmt->execute();              
            } 

                    $ide = $this->generateRandomString();
                    $identificador='P'.$nuevoCont.$unidad.round($total).$ide;

            // print_r($arrayPro);
            $valPro = max($arrayPro);

            if($valPro > 1){
                $varPro = $valPro;
            }else{
                $varPro = 1;

            }

            /*---------------------- INSERTA LOS DATOS DEL PAGO ----------------------*/
            $query = "INSERT INTO reciboParticulares(Recibo_cont,
                                                    Recibo_serie,
                                                    Exp_folio,
                                                    Recibo_fecExp,
                                                    Usu_login,
                                                    Exp_fechAtencion,
                                                    Recibo_total,
                                                    Recibo_mpago,
                                                    Recibo_banco,
                                                    Recibo_terminacion,
                                                    Uni_clave,
                                                    Recibo_tipo,
                                                    Recibo_tipoRec,
                                                    ESP_estatusMedico,
                                                    ITP_id)

                                              VALUES('".$nuevoCont."',
                                                      'P',
                                                     '".$id."',
                                                     '".$fechaRegistro." ".$horaRegistro."',
                                                     '".$usuarioRegistro."',
                                                     '".$fechaRegistro." ".$horaRegistro."',
                                                     '".$total."',
                                                     '".$fPago."',
                                                     '".$banco."',
                                                     '".$terminacion."',
                                                     ".$unidad.",
                                                     2,
                                                     2,
                                                     2,
                                                     '".$varPro."');";
            if($result = $this->_db->query($query)){
              $respuesta =array('folioRecibo'=>$nuevoCont,'folio'=>$id,'medico'=>$medico, 'cuponDescuento' => $cuponDescuento);
            }else{
              $query1="DELETE FROM Item_particulares WHERE Exp_folio='$fol' and it_folRecibo='$nuevoCont'";
              $result1 =  $this->_db->query($query1);
              $respuesta=  array('respuesta' => 'error');
            }
            
           
            

                }catch(Exception $e){
                    $respuesta = $e->getMessage();
                }
                #print_r($data);              
                #print_r($nuevoCont);
                
                return $respuesta;
            }

             public function getMembresia($cveMem)
            {          
                try{
                $query="SELECT count(*) as contador FROM MembresiaMv where mem_folio='".$cveMem."';"; 
                   #$query="select * from ItemOrtho_nuevo where Ite_cons='".$cveItem."';";
                   $result = $this->_db->query($query);
                   $respuesta = $result->fetch();                    
               }catch(Exception $e){                    
                    $respuesta = array('respuesta' =>$e->getMessage());     
               }
                return $respuesta;
            }    
/////////////////////////////////////////////////////////////////////////////////////////////////////

private function generateRandomString($length = 1) { 
  return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
} 

}
?>