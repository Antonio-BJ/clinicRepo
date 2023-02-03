<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/

define("SERVER","www.e-facturate.com"); //IP o Nombre del Servidor
define("PORT",21); //Puerto
define("USER","Medicavial"); //Nombre de Usuario
define("PASSWORD","M3d1c4v14l$"); //Contraseña de acceso
define("PASV",true); //Activa modo pasivo

class Layout extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function createFile($folio, $folioRecibo)
    {   
        $sql = "select Uni_nombreCorto,Unidad.Uni_clave from Expediente
        inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
         where Exp_folio='".$folio."'"; 
         $result = $this->_db->query($sql);
        $rs = $result->fetch();
        $unidad= $rs['Uni_nombreCorto'];
        $uniClave = $rs['Uni_clave'];            
        //Creamos el archivo datos.txt
        //ponemos tipo 'a' para añadir lineas sin borrar
        $sql ="select * from reciboParticulares where Exp_folio='".$folio."' and Recibo_cont=".$folioRecibo;
        $result = $this->_db->query($sql);
        $rs = $result->fetch();
        $folRecibo=$rs['Recibo_cont'];
        $serie    = $rs['Recibo_serie'];
        
        $precioUnitario= $rs['Recibo_total'];
        $importe =round(($precioUnitario)/1.16,2);
        $ivaTotal=round($importe*.16, 2);
        $mPago = $rs['Recibo_mpago'];
        $terminacion=$rs['Recibo_terminacion'];

        if($terminacion==NULL||$terminacion==null||$terminacion=='') $terminacion='';
        
        switch ($mPago) {
            case '1':
                $pago='Efectivo';
                break;
            case '2':
                $pago='Tarj Credito '.$rs['Recibo_banco'];
                break;
            case '3':
                $pago='Tarj Debito '.$rs['Recibo_banco'];
                break;
            case '4':
                $pago='Trans';
                break;            
            default:
                # code...
                break;
        }
        $sql = "select count(*) as contador from Item_particulares where Exp_folio='".$folio."'  and it_folRecibo=".$folioRecibo;
        $result = $this->_db->query($sql);
        $rs = $result->fetch();
        
        $contador = $rs['contador'];
        $sql = "select MAX(Fac_id)+1 as noFactura from FacParticulares";
        $result = $this->_db->query($sql);
        $rs = $result->fetch();

        $noFactura = $rs['noFactura'];
        if($noFactura==NULL) $noFactura=10000;
        
        $factura = 'P_'.$noFactura;
        $sql = "select * from Item_particulares where Exp_folio='".$folio."' and it_folRecibo=".$folioRecibo;
        $result = $this->_db->query($sql);
        
        
        $nomArchivo="MEDICA_".$serie.$folRecibo."_".date("Y-m-d").".tck";
        $file=fopen($nomArchivo,"a") or die("Problemas");
          //vamos añadiendo el contenido
          fputs($file,"T|".$serie.$folRecibo."|MedicaVial|".$uniClave."|".$unidad."|".date("Y-m-d H:i:s.u")."|1|2|".$precioUnitario."|0.00|".$precioUnitario."|0.00|Pago(s)");
          fputs($file,"\n");
          if($mPago==2||$mPago==3){
              fputs($file,"P|".$serie.$folRecibo."||1|".$terminacion."||1|0.00|".$precioUnitario."|0.00|".$precioUnitario."|0.00|".$pago);          
          }else{
              fputs($file,"P|".$serie.$folRecibo."||1|||1|0.00|".$precioUnitario."|0.00|".$precioUnitario."|0.00|".$pago);          
          }
          fputs($file,"\n");
          $descuentoTotal =0; 
          $rs = $result->fetchAll(PDO::FETCH_ASSOC);
          fputs($file,"M|".$serie.$folRecibo."||01|||1|0|0|0|0|0.00|Fac: ".$factura." Fol: ".$folio." Rec: ".$folRecibo);
          fputs($file,"\n");  
          foreach ($rs as $key) {
                   $descuento= $key['it_precio']*($key['it_descuento']/100);                   
                   $descuentoTotal=$descuentoTotal+round($descuento);
                   $importeItem=round($key['it_precio']/1.16,2);
                   $importeItem1=($key['it_precio']/1.16);
                   $iva = round($importeItem1*.16,2);           
                  
                  fputs($file,"M|".$serie.$folRecibo."||01|||1|".$importeItem."|".$importeItem."|".$iva."|".round($key['it_precio'],2)."|0.00|".$key['it_prod']);                    
                   
                   fputs($file,"\n");
                  
                   } 
            $descuentoImporte=round($descuentoTotal/1.16,2);
            $descuentoImporte1=$descuentoTotal/1.16;
            $descIva=round($descuentoImporte1*.16,2);            
            fputs($file,"D|".$serie.$folRecibo."||01|||1|0.00|".$descuentoImporte."-|".$descIva."-|".$descuentoTotal."-|0.00|DESCUENTO"); 
            fputs($file,"\n");
            fputs($file,"I|".$serie.$folRecibo."||01|||16|0.00|".$ivaTotal."|0.00|".$ivaTotal."|0.00|IMPUESTO");
            fputs($file,"\n"); 
            try{
            $sql="INSERT INTO FacParticulares(Fac_id,Fac_numero,Exp_folio,Fol_recibo,Fac_fec)
                  VALUES(:Fac_id,:Fac_numero,:Exp_folio,:Fol_recibo,now())";
              $stmt = $this->_db->prepare($sql);
              $stmt->bindParam('Fac_id', $noFactura);                                  
              $stmt->bindParam('Fac_numero', $factura);
              $stmt->bindParam('Exp_folio', $folio);       
              $stmt->bindParam('Fol_recibo', $folioRecibo);        

              $stmt->execute();              
            }catch(Exception $e){
              echo $e->getMessage();
            }

            /*if($rs = $result->fetchAll()){
                do{
                    print_r($rs);
                   $descuento= $rs['it_precio']*($rs['it_descuento']/100);
                   echo $rs['it_precio'];
                   $descuentoTotal=$descuentoTotal+$descuento;
                   $precioBruto = $rs['it_precio']*.16;
                   $iva = $rs['it_precio']-$precioBruto; 
                   fputs($file,"M|".$folRecibo."||01|||1|".$precioBruto."|".$precioBruto."|".$iva."|".$rs['it_precio']."|0.00|".$rs['it_prod']); 
                   fputs($file,"\n");
                }while($rs = $result->fetchAll());           
            }*/

          //fclose($file);
          
            $this->_db=null;
          $ftp_server='www.e-facturate.com';
          $ftp_user_name = 'Medicavial';
          $ftp_user_pass = 'M3d1c4v14l$';
          $destination_file = $nomArchivo;
          $source_file = $nomArchivo;
          // establecer una conexión básica
          $conn_id = ftp_connect($ftp_server); 

          // iniciar una sesión con nombre de usuario y contraseña
          $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

          // verificar la conexión
          if ((!$conn_id) || (!$login_result)) {  
              echo "¡La conexión FTP ha fallado!";
              echo "Se intentó conectar al $ftp_server por el usuario $ftp_user_name"; 
              exit; 
          } else {
              echo "Conexión a $ftp_server realizada con éxito, por el usuario $ftp_user_name";
          }

          // subir un archivo
          $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);  

          // comprobar el estado de la subida
          if (!$upload) {  
              echo "¡La subida FTP ha fallado!";
          } else {
              echo "Subida de $source_file a $ftp_server como $destination_file";
          }

          // cerrar la conexión ftp 
          ftp_close($conn_id);
          fclose($file);
          
    }

    function ConectarFTP(){
    //Permite conectarse al Servidor FTP
    $id_ftp=ftp_connect(SERVER,PORT); //Obtiene un manejador del Servidor FTP
    ftp_login($id_ftp,USER,PASSWORD); //Se loguea al Servidor FTP
    ftp_pasv($id_ftp,MODO); //Establece el modo de conexión
    return $id_ftp; //Devuelve el manejador a la función
    }

    function SubirArchivo($archivo_local,$archivo_remoto){
    //Sube archivo de la maquina Cliente al Servidor (Comando PUT)
    $id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
    ftp_put($id_ftp,$archivo_remoto,$archivo_local,FTP_BINARY);
    //Sube un archivo al Servidor FTP en modo Binario
    ftp_quit($id_ftp); //Cierra la conexion FTP
    }

    function ObtenerRuta(){
    //Obriene ruta del directorio del Servidor FTP (Comando PWD)
    $id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
    $Directorio=ftp_pwd($id_ftp); //Devuelve ruta actual p.e. "/home/willy"
    ftp_quit($id_ftp); //Cierra la conexion FTP
    return $Directorio; //Devuelve la ruta a la función
    }

    function checa($folio, $folioRecibo){ 
        $sql = "select Uni_nombreCorto,Unidad.Uni_clave from Expediente
        inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
         where Exp_folio='".$folio."'"; 
         $result = $this->_db->query($sql);
        $rs = $result->fetch();
        $unidad= $rs['Uni_nombreCorto'];
        $uniClave = $rs['Uni_clave'];            
        //Creamos el archivo datos.txt
        //ponemos tipo 'a' para añadir lineas sin borrar
        $sql ="select * from reciboParticulares where Exp_folio='".$folio."' and Recibo_cont=".$folioRecibo;
        $result = $this->_db->query($sql);
        $rs = $result->fetch();
        $folRecibo=$rs['Recibo_cont'];
        $serie    = $rs['Recibo_serie'];
        
        $precioUnitario= $rs['Recibo_total'];
        $importe =round(($precioUnitario)/1.16,2);
        $ivaTotal=round($importe*.16, 2);
        $mPago = $rs['Recibo_mpago'];
        $terminacion=$rs['Recibo_terminacion'];

        $sql1 ="select Recibo_total as miTotal from reciboParticulares where Exp_folio='".$folio."' and Recibo_cont=".$folioRecibo;
        $result1 = $this->_db->query($sql1);
        $rs1 = $result1->fetch();
        $precioTotalBase= $rs1['miTotal'];
        if($terminacion==NULL||$terminacion==null||$terminacion=='') $terminacion='';
        
        switch ($mPago) {
            case '1':
                $pago='Efectivo';
                break;
            case '2':
                $pago='Tarj Credito '.$rs['Recibo_banco'];
                break;
            case '3':
                $pago='Tarj Debito '.$rs['Recibo_banco'];
                break;
            case '4':
                $pago='Trans';
                break;            
            default:
                # code...
                break;
        }
        $sql = "select count(*) as contador from Item_particulares where Exp_folio='".$folio."'  and it_folRecibo=".$folioRecibo;
        $result = $this->_db->query($sql);
        $rs = $result->fetch();
        
        $contador = $rs['contador'];
        $sql = "select MAX(Fac_id)+1 as noFactura from FacParticulares";
        $result = $this->_db->query($sql);
        $rs = $result->fetch();

        $noFactura = $rs['noFactura'];
        if($noFactura==NULL) $noFactura=10000;
        
        $factura = 'P_'.$noFactura;
        $sql = "select * from Item_particulares where Exp_folio='".$folio."' and it_folRecibo=".$folioRecibo;
        $result = $this->_db->query($sql);
        
        
        
          //vamos añadiendo el contenido
          echo "T|".$serie.$folRecibo."|MedicaVial|".$uniClave."|".$unidad."|".date("Y-m-d H:i:s.u")."|1|2|".$precioTotalBase."|0.00|".$precioTotalBase."|0.00|Pago(s)";
          echo "<br>";
          fputs($file,"\n");
          if($mPago==2||$mPago==3){
              echo "P|".$serie.$folRecibo."||1|".$terminacion."||1|0.00|".$precioTotalBase."|0.00|".$precioTotalBase."|0.00|".$pago;
              echo "<br>";          
          }else{
              echo "P|".$serie.$folRecibo."||1|||1|0.00|".$precioTotalBase."|0.00|".$precioTotalBase."|0.00|".$pago; 
              echo "<br>";         
          }
        
          $descuentoTotal =0; 
          $rs = $result->fetchAll(PDO::FETCH_ASSOC);
          echo "M|".$serie.$folRecibo."||01|||1|0|0|0|0|0.00|Fac: ".$factura." Fol: ".$folio." Rec: ".$folRecibo;
          echo "<br>";
            
          foreach ($rs as $key) {
                   $descuento= $key['it_precio']*($key['it_descuento']/100);                   
                   $descuentoTotal=$descuentoTotal+round($descuento);
                   $importeItem=round($key['it_precio']/1.16,2);
                   $importeItem1=($key['it_precio']/1.16);
                   $iva = round($importeItem1*.16,2);           
                  
                  echo "M|".$serie.$folRecibo."||01|||1|".$importeItem."|".$importeItem."|".$iva."|".round($key['it_precio'],2)."|0.00|".$key['it_prod'];                    
                   echo "<br>";
                  
                  
                   } 
            $descuentoImporte=round($descuentoTotal/1.16,2);
            $descuentoImporte1=$descuentoTotal/1.16;
            $descIva=round($descuentoImporte1*.16,2);            
            echo "D|".$serie.$folRecibo."||01|||1|0.00|".$descuentoImporte."-|".$descIva."-|".$descuentoTotal."-|0.00|DESCUENTO"; 
            echo "<br>";
            echo "I|".$serie.$folRecibo."||01|||16|0.00|".$ivaTotal."|0.00|".$ivaTotal."|0.00|IMPUESTO";
            echo "<br>"; 
              
          
  
    }

}
?>