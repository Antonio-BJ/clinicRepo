<?php 

require_once "Modelo.php";
require_once 'nomad_mimemail.inc.php';

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class Convenio                    --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 10-11-2015                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class Convenio extends Modelo
{
	public $mimemail;
	function __construct()
	{
		 parent::__construct();   
            
	}


	public function getMembresia($datos)
    {        
        $folio  = $datos->folio;
        $nombre = $datos->nombre;
    	try{
            if($folio&&!$nombre){
                $sql = "Select * from MembresiaMv where mem_folio='".$folio."'";
                $result = $this->_db->query($sql);
                $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
            }elseif(!$folio&&$nombre){
                $sql = "Select * from MembresiaMv where mem_nombre like '%".$nombre."%'";
                $result = $this->_db->query($sql);
                $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
            }elseif ($folio&&$nombre) {
                $sql = "Select * from MembresiaMv where mem_folio='".$folio."' and mem_nombre like '%".$nombre."%'";
                $result = $this->_db->query($sql);
                $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
            }           
        }catch(Exception $e){
        	//$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;           
    }

    public function setMembresia($fol,$uni)
    {      
        
        try{
            
                $sql        = "Select Exp_completo, Exp_cveMemMV, Cia_clave from Expediente where Exp_folio='".$fol."'";
                $result     = $this->_db->query($sql);
                $respuesta  = $result->fetch();            
                $nombre     = $respuesta['Exp_completo'];
                $claveMemMV = $respuesta['Exp_cveMemMV'];
                $cia        = $respuesta['Cia_clave'];
                if($claveMemMV==null||$claveMemMV==''){
                   $claveMemMV='-';
                }
                $sql        = "select MAX(mem_id)+1 as contadorNum, MAX(mem_folio)+1 as contador from MembresiaMv";
                $result     = $this->_db->query($sql);
                $respuesta1  = $result->fetch();                    
                $contador   = $respuesta1['contador'];
                $contNum    = $respuesta1['contadorNum'];                                              
                if($contador<'003000'){
                    $contador='003000';
                }     
                $c="000000".$contador;
                $c=substr($c,-6,6);
                $contador = $c;           
                switch ($uni) {
                    case 1:
                        $serie='MFR';
                        break;
                    case 2:
                        $serie='MFS';
                        break;
                    case 3:
                        $serie='MFP';
                        break;
                    case 4:
                        $serie='MFB';
                        break;
                    case 5:
                        $serie='MFY';
                        break;
                    case 6:
                        $serie='MFM';
                        break;
                    case 7:
                        $serie='MFL';
                        break;
                    case 8:
                        $serie='MFO';
                        break;
                    case 86:
                        $serie='MFC';
                        break;
                    case 184:
                        $serie='MFI';
                        break;
                    case 186:
                        $serie='MFV';
                        break;
                    default:
                        # code...
                        break;
                }

        $sql="INSERT INTO MembresiaMv(mem_id, mem_folio, mem_serie, mem_nombre, mem_fecreg, mem_folioRecomienda, mem_vigencia, Exp_folio,Uni_clave)
                              VALUES(:mem_id, :mem_folio, :mem_serie, :mem_nombre,NOW(), :mem_folioRecomienda, 'dic15/dic18', :Exp_folio,:Uni_clave)";
        $temporal= $this->_db->prepare($sql);
        $temporal->bindParam("mem_id",$contNum);          
        $temporal->bindParam("mem_folio",$contador);              
        $temporal->bindParam("mem_serie",$serie);                         
        $temporal->bindParam("mem_nombre",$nombre);                
        $temporal->bindParam("mem_folioRecomienda",$claveMemMV);         
        $temporal->bindParam("Exp_folio",$fol);       
        $temporal->bindParam("Uni_clave",$uni);         
        if($temporal->execute()){
                $sql        = "Select Exp_completo, Exp_cveMemMV, Cia_clave from Expediente where Exp_folio='".$fol."'";
                $result     = $this->_db->query($sql);
                $respuesta  = $result->fetch();            
                $nombre     = $respuesta['Exp_completo'];
                $claveMemMV = $respuesta['Exp_cveMemMV'];
                $cia        = $respuesta['Cia_clave'];
                if($claveMemMV==null||$claveMemMV==''){
                   $claveMemMV='-';
                }
             if($cia==51||$cia==54){
                $sql="UPDATE Expediente set Cia_clave= 54, Exp_modificador='M.MV', CON_cve=3, Exp_cveMemMV='".$contador."' where Exp_folio='".$fol."'";
                $temporal= $this->_db->prepare($sql);
                $temporal->execute();
            } else{
                $sql="UPDATE Expediente set Exp_modificador='M.MV', CON_cve=3, Exp_cveMemMV='".$contador."' where Exp_folio='".$fol."'";
                $temporal= $this->_db->prepare($sql);
                $temporal->execute();

            }
            $sql="select mem_folio,mem_serie, mem_nombre, mem_vigencia, mem_fecreg from MembresiaMv where Exp_folio='".$fol."'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch(PDO::FETCH_OBJ);    
        }else{
            $respuesta = array('respuesta' =>'error');       
        }                                    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;  
         $this->_db=null;             
    }

    public function setMembresiaSinFol($nombre,$uni)
    {      
        
        try{
                            
                $sql        = "select MAX(mem_id)+1 as contadorNum, MAX(mem_folio)+1 as contador from MembresiaMv";
                $result     = $this->_db->query($sql);
                $respuesta1  = $result->fetch();                    
                $contador   = $respuesta1['contador'];
                $contNum    = $respuesta1['contadorNum'];                                              
                if($contador<'003000'){
                    $contador='003000';
                } 
                $c="000000".$contador;
                $c=substr($c,-6,6);
                $contador = $c;  
                $claveMemMV='-'; 
                $fol='-';              
                switch ($uni) {
                    case 1:
                        $serie='MFR';
                        break;
                    case 2:
                        $serie='MFS';
                        break;
                    case 3:
                        $serie='MFP';
                        break;
                    case 4:
                        $serie='MFB';
                        break;
                    case 5:
                        $serie='MFY';
                        break;
                    case 6:
                        $serie='MFM';
                        break;
                    case 7:
                        $serie='MFL';
                        break;
                    case 8:
                        $serie='MFO';
                        break;
                    case 86:
                        $serie='MFC';
                        break;
                    case 184:
                        $serie='MFI';
                        break;
                    case 186:
                        $serie='MFV';
                        break;
                    default:
                        # code...
                        break;
                }

        $sql="INSERT INTO MembresiaMv(mem_id, mem_folio, mem_serie, mem_nombre, mem_fecreg, mem_folioRecomienda, mem_vigencia, Exp_folio,Uni_clave)
                              VALUES(:mem_id, :mem_folio, :mem_serie, :mem_nombre,NOW(), :mem_folioRecomienda, 'Dic15/Dic18', :Exp_folio, :Uni_clave)";
        $temporal= $this->_db->prepare($sql);
        $temporal->bindParam("mem_id",$contNum);          
        $temporal->bindParam("mem_folio",$contador);              
        $temporal->bindParam("mem_serie",$serie);                         
        $temporal->bindParam("mem_nombre",$nombre);                
        $temporal->bindParam("mem_folioRecomienda",$claveMemMV);         
        $temporal->bindParam("Exp_folio",$fol);         
        $temporal->bindParam("Uni_clave",$uni);         
        if($temporal->execute()){

            $sql="select mem_folio,mem_serie, mem_nombre, mem_vigencia from MembresiaMv where mem_id=".$contNum;
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch(PDO::FETCH_OBJ);    
        }else{
            $respuesta = array('respuesta' =>'error1');       
        }                                    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
         $this->_db=null;         
    }

    public function refacturacion($noRecibo,$usr)
    {                 
        try{
            $sql="SELECT * FROM reciboParticulares WHERE Recibo_cont=".$noRecibo." and Recibo_serie='P'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();    
            $folio          =$respuesta['Exp_folio'];
            $fecAtencion    =$respuesta['Exp_fechAtencion'];
            $total          =$respuesta['Recibo_total'];
            $mpago          =$respuesta['Recibo_mpago'];
            $medico         =$respuesta['Recibo_doc'];
            $tipo           =$respuesta['Recibo_Tipo'];
            $banco          =$respuesta['Recibo_banco'];
            $terminacion    =$respuesta['Recibo_terminacion'];

            $sql="INSERT INTO reciboParticulares(Recibo_cont,Recibo_serie,Exp_folio,Recibo_fecExp,Usu_login,Exp_fechAtencion,Recibo_total,Recibo_mpago, Recibo_doc,Recibo_Tipo,Recibo_banco,Recibo_terminacion)
                VALUES(:Recibo_cont,'R',:Exp_folio,now(),:Usu_login,:Exp_fechAtencion,:Recibo_total,:Recibo_mpago, :Recibo_doc,:Recibo_Tipo,:Recibo_banco,:Recibo_terminacion)";
            $temporal= $this->_db->prepare($sql);
            $temporal->bindParam("Recibo_cont",$noRecibo);                      
            $temporal->bindParam("Exp_folio",$folio);          
            $temporal->bindParam("Usu_login",$usr);          
            $temporal->bindParam("Exp_fechAtencion",$fecAtencion);          
            $temporal->bindParam("Recibo_total",$total);          
            $temporal->bindParam("Recibo_mpago",$mpago);          
            $temporal->bindParam("Recibo_doc",$medico);          
            $temporal->bindParam("Recibo_Tipo",$tipo);          
            $temporal->bindParam("Recibo_banco",$banco);          
            $temporal->bindParam("Recibo_terminacion",$terminacion); 
            if($temporal->execute()){            
                $sql="SELECT * FROM Item_particulares where it_folRecibo=".$noRecibo." and it_serie='P'";
                $result = $this->_db->query($sql);
                $respuesta = $result->fetchAll();
                print_r($respuesta); 
                foreach($respuesta as $row){

                            $query="SELECT  Max(it_cons)+1 as clave From Item_particulares Where it_folRecibo=".$noRecibo;
                            $result =  $this->_db->query($query);
                            $rs1 =       $result->fetch();
                            $cveItem=   $rs1['clave']; 
                            if($cveItem==''||$cveItem==NULL){$cveItem=1;}     

                            $sql="INSERT INTO Item_particulares(it_cons,Exp_folio,it_codReg,it_codMV,it_prod,it_desc,it_pres,it_fecReg,it_folRecibo,it_serie,it_precio,it_descuento,Tip_clave) 
                                Values (:it_cons,:Exp_folio,:it_codReg,:it_codMV,:it_prod,:it_desc,:it_pres,:it_fecReg,:it_folRecibo,'R',:it_precio,:it_descuento,:Tip_clave)";

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

                            $stmt->execute();              
                } 
                $sql ="UPDATE reciboParticulares SET Recibo_cancelado=1, Recibo_FCancelado=now(), Recibo_CancNota='Refacturacion', Recibo_folSustituye='R".$noRecibo."' where Recibo_cont=".$noRecibo." and Recibo_serie='P'";
                $result = $this->_db->query($sql);  
            }else{
                $respuesta = array('respuesta' =>'error1');       
            }            

        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;
         $this->_db=null;       
    }

    public function setMembresiaSinRegistro($usr,$uni,$datos,$folio='-')
    {      
        $nombre     =$datos[0]->nombre;
        $apaterno   =$datos[0]->apanterno;
        $amaterno   =$datos[0]->amaterno;
        $correo     =$datos[0]->email;
        $tel        =$datos[0]->telefono;
        $codPos     =$datos[0]->codPos;
        $obs        =$datos[0]->obs;       

        $completo   =$nombre.' '.$apaterno.' '.$amaterno;

        $referencias = $datos[1];        
        try{
                            
                $sql        = "select MAX(mem_id)+1 as contadorNum, MAX(mem_folio)+1 as contador from MembresiaMv";
                $result     = $this->_db->query($sql);
                $respuesta1  = $result->fetch();                    

                $contador   = $respuesta1['contador'];
                $contNum    = $respuesta1['contadorNum'];                                              
                if($contador<'003000'){
                    $contador='003000';
                } 
                $c="000000".$contador;
                $c=substr($c,-6,6);
                $contador = $c;  
                $claveMemMV='-'; 
                $fol='-';   
                $anio = date('Y'); 
                $anioM = date('y');
                $mes = date('m');

                switch ($mes) {
                            case '1':
                                $vigencia='Ene'.$anioM.'/'.'Ene'.$anioM;
                                break;
                            case '2':
                                $vigencia='Feb'.$anioM.'/'.'Feb'.$anioM;
                                break;
                            case '3':
                                $vigencia='Mar'.$anioM.'/'.'Mar'.$anioM;
                                break;
                            case '4':
                                $vigencia='Abr'.$anioM.'/'.'Abr'.$anioM;
                                break;
                            case '5':
                                $vigencia='May'.$anioM.'/'.'May'.$anioM;
                                break;
                            case '6':
                                $vigencia='Jun'.$anioM.'/'.'Jun'.$anioM;
                                break;
                            case '7':
                                $vigencia='Jul'.$anioM.'/'.'Jul'.$anioM;
                                break;
                            case '8':
                                $vigencia='Ago'.$anioM.'/'.'Ago'.$anioM;
                                break;
                            case '9':
                                $vigencia='Sep'.$anioM.'/'.'Sep'.$anioM;
                                break;
                            case '10':
                                $vigencia='Oct'.$anioM.'/'.'Oct'.$anioM;
                                break;
                            case '11':
                                $vigencia='Nov'.$anioM.'/'.'Nov'.$anioM;
                                break;
                            case '12':
                                $vigencia='Dic'.$anioM.'/'.'Dic'.$anioM;
                                break;                             
                             default:
                                 # code...
                                 break;
                         }         
                switch ($uni) {
                    case 1:
                        $serie='MFR';
                        break;
                    case 2:
                        $serie='MFS';
                        break;
                    case 3:
                        $serie='MFP';
                        break;
                    case 4:
                        $serie='MFB';
                        break;
                    case 5:
                        $serie='MFY';
                        break;
                    case 6:
                        $serie='MFM';
                        break;
                    case 7:
                        $serie='MFL';
                        break;
                    case 8:
                        $serie='MFO';
                        break;
                    case 86:
                        $serie='MFC';
                        break;
                    case 184:
                        $serie='MFI';
                        break;
                    case 186:
                        $serie='MFV';
                        break;
                    default:
                        # code...
                        break;
                }

        $sql="INSERT INTO MembresiaMv(mem_id, mem_folio, mem_serie, mem_nombre,mem_apaterno,mem_amaterno,mem_completo, mem_correo, mem_telefono, mem_codPostal, mem_fecreg, mem_vigencia,mem_anio, Usu_login, mem_entero,Exp_folio,Uni_clave)
                              VALUES(:mem_id, :mem_folio, :mem_serie,:mem_nombre,:mem_apaterno,:mem_amaterno, :mem_completo,:mem_correo, :mem_telefono, :mem_codPostal,NOW(),:mem_vigencia,:mem_anio,:Usu_login,:mem_entero,:Exp_folio,:Uni_clave)";
        $temporal= $this->_db->prepare($sql);
        $temporal->bindParam("mem_id",$contNum);          
        $temporal->bindParam("mem_folio",$contador);              
        $temporal->bindParam("mem_serie",$serie);                         
        $temporal->bindParam("mem_nombre",$completo);                        
        $temporal->bindParam("mem_apaterno",$apaterno);                        
        $temporal->bindParam("mem_amaterno",$amaterno);                        
        $temporal->bindParam("mem_completo",$completo);                        
        $temporal->bindParam("mem_correo",$correo);                        
        $temporal->bindParam("mem_telefono",$tel);                        
        $temporal->bindParam("mem_codPostal",$codPos);
        $temporal->bindParam("mem_vigencia",$vigencia);                        
        $temporal->bindParam("mem_anio",$anio);                        
        $temporal->bindParam("Usu_login",$usr); 
        $temporal->bindParam("mem_entero",$obs);
        $temporal->bindParam("Exp_folio",$folio);                         
        $temporal->bindParam("Uni_clave",$uni);                         
                        
        if($temporal->execute()){            
            
            //if($referencias){
                foreach ($referencias as $key) {                                     
                    $sql="INSERT INTO MembresiaReferencias(mem_folio,MRE_nombre,MRE_correo,MRE_telefono,MRE_parentezco)
                          VALUES(:mem_folio,:MRE_nombre,:MRE_correo,:MRE_telefono,:MRE_parentezco)";
                    $temporal= $this->_db->prepare($sql);                    
                    $temporal->bindParam("mem_folio",$contador);
                    $temporal->bindParam("MRE_nombre",$key->nombre);
                    $temporal->bindParam("MRE_correo",$key->email);
                    $temporal->bindParam("MRE_telefono",$key->telefono);
                    $temporal->bindParam("MRE_parentezco",$key->parentezco);
                    $temporal->execute();
                }
            //}
            

            if($folio!='-'){
		 $sql="select Cia_clave from Expediente where Exp_folio='".$folio."'";
            $result = $this->_db->query($sql);
            $ciaArray = $result->fetch();
		 $ciaCve= $ciaArray['Cia_clave'];
		if($ciaCve==51){
		$sql="UPDATE Expediente set Exp_modificador='M.MV', 				Cia_clave=54,CON_cve=3, Exp_cveMemMV='".$contador."' where 				Exp_folio='".$folio."'";
                $temporal= $this->_db->prepare($sql);
                $temporal->execute();
		} else{  
		
                $sql="UPDATE Expediente set 					Exp_modificador='M.MV', CON_cve=3, Exp_cveMemMV='".$contador."' where Exp_folio='".$folio."'";
                $temporal= $this->_db->prepare($sql);
                $temporal->execute();
		}
            }

            $sql="select mem_folio,mem_serie,mem_completo,mem_anio from MembresiaMv where mem_id=".$contNum;
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch(PDO::FETCH_OBJ);    
        }else{
            $respuesta = array('respuesta' =>'error');       
        }                                    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;   
         $this->_db=null;   
    }

    public function verDatosMembresia($fol)
    {
        $sql="select Exp_nombre, Exp_paterno, Exp_materno, Exp_telefono, Exp_codPostal, Exp_mail from Expediente where Exp_folio='".$fol."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        return $respuesta;
    }

     public function actualizaEstatus($usr)
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
        try{
            $sql="UPDATE Usuario SET Actualizacion=0 where Usu_login='".$usr."'";
            $result = $this->_db->query($sql);
            $respuesta=array('respuesta' =>'exito');
        }catch(Exception $e){
            $respuesta=array('respuesta' =>'error');
        }
        return $respuesta;
    }

     public function enviarCorreoRH($fol)
    {        
        $mimemail = new nomad_mimemail();


        $query="SELECT Exp_completo, Exp_fechaNac, Uni_nombre, Cia_nombrecorto, Expediente.Pro_clave, Pro_nombre FROM Expediente inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  
                        inner join Compania on Expediente.Cia_clave = Compania.Cia_clave
                        inner join Producto on Expediente.Pro_clave = Producto.Pro_clave
                        Where Exp_folio='".$fol."'";
        $result = $this->_db->query($query);
        $datosFol = $result->fetch();    
        $nombre=$datosFol['Exp_completo'];
        $unidad=$datosFol['Uni_nombre'];
        $compania = $datosFol['Cia_nombrecorto'];
        $pro_clave = $datosFol['Pro_clave'];
        $producto = $datosFol['Pro_nombre'];        
        if($pro_clave==4||$pro_clave==5||$pro_clave==13||$pro_clave==14){

            $query="Select Max(Rehab_cons) As Cons From Rehabilitacion Where Exp_folio='".$fol."'";
            $result = $this->_db->query($query);
            $rehab = $result->fetch();  
            $noReha = $rehab['Cons'];
                if($noReha==1){
                    $titulo='INICIO DE BLOQUE DE REHABILITACION';
                }else{
                    $titulo='FIN DE BLOQUE DE REHABIITACION';
                }
                if($noReha==1||$noReha==10){
                $contenido='<HTML>
                        <HEAD>
                        </HEAD>
                        <BODY>
                        <br>                
                        <img src="logomv.gif"> 
                        <br>
                        <br>
                        <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                            <tr>
                                <th colspan="4" align="center" style=" width: 25%; background: #eee;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                    '.$titulo.' PARA PACIENTES SOLO REHABILITACION
                                </th>
                            </tr>
                            <br>
                            <tr>
                                <td style=" width: 25%;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                    Folio: <b>'.$fol.'</b>
                                </td>
                                <td colspan="2" style=" width: 50%;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                    Nombre: <b>'.utf8_decode($nombre).'</b>
                                </td>
                                <td style=" width: 25%;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                    Unidad: <b>'.utf8_decode($unidad).'</b>
                                </td>
                                <br>
                                <tr>
                                <td style=" width: 25%;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                    Compa&ntilde;ia: <b>'.utf8_decode($compania).'</b>
                                </td>
                                <td colspan="2" style=" width: 50%;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                    Producto: <b>'.utf8_decode($producto).'</b>
                                </td>                                        

                            </tr> ';
                            
                        $contenido.='</table>
                        </BODY>
                        </HTML>         
            ';
            $mimemail->set_from("rehabilitacion_noReply@medicavial.com.mx");
            // $mimemail->set_to("egutierrez@medicavial.com.mx");
            $mimemail->set_to("coordreh@medicavial.com.mx");                    
            $mimemail->add_bcc("egutierrez@medicavial.com.mx");
            $mimemail->set_subject($titulo." - ".$fol." - Solo rehabilitacion");
            $mimemail->set_html($contenido);
            $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

            if ($mimemail->send()){
                $respuesta=array("respuesta"=>'SI');
                echo json_encode($respuesta);
            }
            else {
                echo "error";
            }
        }else{
            echo 'sin correo';
        }

        }else{
           $respuesta=array("respuesta"=>'SI'); 
        }
    } 
    
    
    public function enviarMembresia($membresia=123465)
    {        


/************************************************************************************************************************************* */
/************************************************************************************************************************************* */
/************************************************************************************************************************************* */






if (($fichero = fopen("membresias.csv", "r")) !== FALSE) {
    // Lee los nombres de los campos
    $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
    $num_campos = count($nombres_campos);  
    while (($datos = fgetcsv($fichero, 0, ",", "\"", "\"")) !== FALSE) {
        // Crea un array asociativo con los nombres y valores de los campos
        for ($icampo = 0; $icampo < $num_campos; $icampo++) {
            $registro[$nombres_campos[$icampo]] = $datos[$icampo];
        }       
        // Añade el registro leido al array de registros
        $registros[] = $registro;
        //print_r($registros);
    }
    fclose($fichero);

}

foreach($registros as $registro){
    


    try{

        $sql        = "select MAX(mem_id)+1 as contadorNum, MAX(mem_folio)+1 as contador from MembresiaMv";
        $result     = $this->_db->query($sql);
        $respuesta1  = $result->fetch();                    
        $contador   = $respuesta1['contador'];
        $contNum    = $respuesta1['contadorNum'];                                              
        if($contador<'003000'){
            $contador='003000';
        }     
        $c="000000".$contador;
        $c=substr($c,-6,6);
        $contador = $c;    
        $serie='MFO';       
    
    $sql="INSERT INTO MembresiaMv(mem_id, mem_folio, mem_serie, mem_nombre, mem_fecreg, mem_folioRecomienda, mem_vigencia, Exp_folio,Uni_clave)
                      VALUES(:mem_id, :mem_folio, :mem_serie, :mem_nombre,NOW(), '-', 'sep2020', '-',8)";
    $temporal= $this->_db->prepare($sql);
    $temporal->bindParam("mem_id",$contNum);          
    $temporal->bindParam("mem_folio",$contador);              
    $temporal->bindParam("mem_serie",$serie);                         
    $temporal->bindParam("mem_nombre", utf8_encode($registro['nombre']));                                     
    if($temporal->execute()){
    
        
    $string = $serie.' - '.$contador;
    $string2 = $registro['nombre'];
    $texto2 = 'sep 2020';
    $font = 17;
    $font_path = 'myClasses/membresias/arialbd.ttf';

    $image = imagecreatefromjpeg('myClasses/membresias/membresiaDigital.jpg');
    
    $text_color = imagecolorallocate( $image, 0, 0, 0 ); // Color del texto en la imagen.
    
    imagettftext($image, 12, 0, 100, 720, $text_color, $font_path, $string);
    
    imagettftext($image, 12, 0, 100, 760, $text_color, $font_path, $string2);
    
    imagettftext($image, 10, 0, 550, 930, $text_color, $font_path, $texto2);
    
    imagejpeg($image,'myClasses/membresias/'. $string2.'.jpg');

    ImageDestroy($image);
    
    /************************************************************************************************************************************* */
    /************************************************************************************************************************************* */
    /************************************************************************************************************************************* */      
            $mimemail = new nomad_mimemail();
                    $contenido='<HTML>
                            <HEAD>
                            </HEAD>
                            <BODY>
                            <br>                                        
                            <br>
                            <br>
    
                            <img width="880" src="membresia.gif"> 
                            <br><br><br><br>
                           
                            </BODY>
                            </HTML>         
                ';
                $mimemail->set_from("membresia_noReply@medicavial.com.mx");
                // $mimemail->set_to("egutierrez@medicavial.com.mx");
                $mimemail->set_to($registro['correo']);                            
                $mimemail->set_subject("membresia MV");
                $mimemail->set_html($contenido);
                $mimemail->add_attachment("myClasses/membresias/".$string2.".jpg", "membresia.gif");
    
                if ($mimemail->send()){                    
                    echo 'la membresía de '.$registro['correo'].' se envió correctamente.<br/>';
                }
                else {
                    echo "error";
                }
                
       
    }else{
    $respuesta = array('respuesta' =>'error');       
    }                                    
    }catch(Exception $e){
    //$respuesta=$e->getMessage();
    $respuesta = array('respuesta' =>$e->getMessage());       
    } 











}


/************************************************************************************************************************************** */
/****************************** CREACIÓN DE LA MEMBRESÍA                                *********************************************** */
/************************************************************************************************************************************** */

$this->_db=null;             

/************************************************************************************************************************************** */
/************************************************************************************************************************************** */
/************************************************************************************************************************************** */





    } 

    public function modifcarRegistro()
    {
        try{
            $sql="select Exp_folio, Exp_completo, Exp_paterno,Exp_materno, Exp_nombre from Expediente where Exp_nombre ='Daniela Alejandra' and Exp_fecreg BETWEEN '2016-01-01' and '2016-01-31 23:59:59'";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll();

            foreach ($respuesta as $key => $value) {
                $apellidos =' '.$value['Exp_paterno'].' '.$value['Exp_materno'];
                $nombreSolo = str_replace($apellidos, "", $value["Exp_completo"]);
                echo $nombreSolo.'<br>';
                $sqlUpdate = "UPDATE Expediente SET Exp_nombre='".$nombreSolo."' where Exp_folio='".$value['Exp_folio']."'";
                $result = $this->_db->query($sqlUpdate);
            }
            
        }catch(Exception $e){
            $respuesta=array('respuesta' =>'error');
        }
       
    }

}
?>