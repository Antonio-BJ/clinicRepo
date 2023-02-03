<?php 

require_once "Modelo.php";
require_once 'nomad_mimemail.inc.php';
require_once 'PHPExcel/Classes/PHPExcel.php';

/*----------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------
-------------------------------           class CortaEstancia               --------------------------
------------------------------------------------------------------------------------------------------
-------------------   By: Erick                                             --------------------------
-------------------                                                         -------------------------- 
-------------------   fecha: 04-11-2015                                     --------------------------
------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------*/

class Insercion extends Modelo
{
	public $mimemail;
	function __construct()
	{
		 parent::__construct();         
	}

	public function insertFolios()
    {        
    	$registros = array();
        if (($fichero = fopen("insert.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            echo $num_campos;
            // Lee los registros
            while (($datos = fgetcsv($fichero, 0, ",", "\"", "\"")) !== FALSE) {
                // Crea un array asociativo con los nombres y valores de los campos
                for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                    $registro[$nombres_campos[$icampo]] = $datos[$icampo];
                }

                print_r($registro);
                echo '<br>';
                // Añade el registro leido al array de registros
                $registros[] = $registro;
                //print_r($registros);
            }
            fclose($fichero);
         
            echo "Leidos " . count($registros) . " registros <br>";

            echo "<table>";
         
            for ($i = 0; $i < count($registros); $i++) {


                $nombre = $registros[$i]["nombre"].' '.$registros[$i]["apPaterno"].' '.$registros[$i]["apMaterno"];

                $query ="SELECT * FROM Expediente where Exp_completo='".$nombre."' and Cia_clave=".$registros[$i]["compania"];
                $result = $this->_db->query($query);
                $nombreEnc = $result->fetch();
                // if(!$nombreEnc){
             
                $sql = "Select Pre_prefijo as prefijo From Prefijo Where Uni_clave='".$registros[$i]["unidad"]."'";
                $result = $this->_db->query($sql);
                $ultimo = $result->fetch();     
                
                $prefijo=$ultimo['prefijo'];              
                $sql = "Select MAX(EXP_cons)+1 as contador From Expediente Where Exp_prefijo='".$prefijo."'";
                $result =  $this->_db->query($sql);                       
                $consecutivo = $result->fetch();     
                
                $cons=$consecutivo['contador'];
                $completo = $registros[$i]["nombre"].' '.$registros[$i]["apPaterno"].' '.$registros[$i]["apMaterno"];
                //echo $completo;
              
                if ($cons==null) {$cons=1;}
                $c="000000".$cons;
                $c=substr($c,-6,6);
                $folio=$prefijo.$c;
                $unidad = $registros[$i]["unidad"];

                $fecha = date("Y-m-d H:i:s");
                //echo "folio: " . $folio . "<br>";
            

                $query="Insert into Expediente(Exp_folio, Exp_prefijo, Exp_cons, Uni_clave, Usu_registro, Exp_poliza, Exp_siniestro, Exp_paterno, Exp_materno, Exp_nombre, Exp_completo, Exp_fecreg,Cia_clave, RIE_clave,Pro_clave,Uni_ClaveActual,Exp_triageOrigen, Exp_triageActual, Exp_edad, Exp_sexo)
                               Values('".$folio."','".$prefijo."',".$c.",'".$unidad."','algo','".$registros[$i]["poliza"]."','".$registros[$i]["siniestro"]."','".$registros[$i]["apPaterno"]."','".$registros[$i]["apMaterno"]."','".$registros[$i]["nombre"]."','".$completo."',now(),".$registros[$i]["compania"].",".$registros[$i]["rAfectado"].",".$registros[$i]["producto"].",".$unidad.",1,".$registros[$i]["triageActual"].",".$registros[$i]["Edad"].",'".$registros[$i]["Sexo"]."')";
                // echo $query;              
                $result = $this->_db->query($query);
                $sqlF= "Select Exp_fecreg from Expediente where Exp_folio='".$folio."'";
                $result =  $this->_db->query($sqlF);      
                $ultimo = $result->fetch();
                $fecRegistrada = $ultimo['Exp_fecreg'];

                $query1 = "INSERT INTO RegMVZM(Fol_MedicaVial, Fol_ZIMA, Cia_Clave)
                                    VALUES('".$folio."','".$registros[$i]["fZima"]."',".$registros[$i]["compania"].")";
                $result = $this->_db->query($query1);
                echo "<tr><td>".$folio."</td><td>".$fecRegistrada.'</td></tr>'; 
                // }else{
                //   echo "<tr><td>".$registros[$i]["fZima"]."</td><td>Nombre Duplicado</td></tr>";    
                // }               
            }
            echo "</table>";
        }else{
            echo 'error';
        }
    }

    public function insertBorrados()
    {        
        $sql = "select * from Item_particulares where it_folRecibo in (4462,4463,4479,4497,4507,4508,4524,4525,4526,4557)";
                $result = $this->_db->query($sql);    
        $ultimo = $result->fetchAll();
        try {
            
        
        foreach ($ultimo as $key => $value) {
                   echo $value['it_cons'].'<br>';
                   $query="INSERT INTO Item_particulares(it_cons,It_codReg,it_prod,it_desc,Exp_folio,it_fecReg,it_folRecibo,
                        it_serie,it_precio,Tip_clave,it_descuento,It_precioTotal) 
                        VALUES(".$value['it_cons'].",'".$value['It_codReg']."','".$value['it_prod']."','".$value['it_desc']."','".$value['Exp_folio']."','".$value['it_fecReg']."',".$value['it_folRecibo'].",'R',".$value['it_precio'].",".$value['Tip_clave'].",".$value['it_descuento'].",".$value['It_precioTotal'].")";
                    //echo $query;
                    $result = $this->_db->query($query);
                    echo 'insertado <br>';
               } 
        } catch (Exception $e) {
            echo $e->getmessage();
        }      
    }

    public function insertFacts()
    {        
        $registros = array();
        if (($fichero = fopen("insertFact.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            // Lee los registros
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
         
            echo "Leidos " . count($registros) . " registros <br>";
         
            for ($i = 0; $i < count($registros); $i++) {
                 echo "folio: " . $registros[$i]["Folio"] . " ";
                
                
                 $sql = "Select FAC_folio as clave,FAC_serie,  FAC_importe, FAC_iva, FAC_total, FAC_saldo, FAC_pagada, FAC_FPago from FacturaExpediente 
                        inner join Factura on FacturaExpediente.FAC_clave=Factura.FAC_clave 
                 where Exp_folio='".$registros[$i]["Folio"]."'";
                 $result =  $this->_db->query($sql);                       
                 $consecutivo = $result->fetch();  
                 $clave =  $consecutivo['clave'];
                 echo $clave.'-'.$consecutivo['FAC_importe'].'-'.$consecutivo['FAC_iva'].'-'.$consecutivo['FAC_serie'].'-'.$consecutivo['FAC_total'].'-'.$consecutivo['FAC_saldo'].'-'.$consecutivo['FAC_pagada'].'-'.$consecutivo['FAC_FPago'].'<br>';  
                 try{
                 $sql = "UPDATE ExpedienteInfo SET FAC_serie='".$consecutivo['FAC_serie']."', FAC_folio=".$clave.", FAC_fecha='".$consecutivo['FAC_fecha']."', FAC_importe='".$consecutivo['FAC_importe']."', FAC_iva='".$consecutivo['FAC_iva']."', FAC_total='".$consecutivo['FAC_total']."', FAC_saldo='".$consecutivo['FAC_saldo']."', FAC_pagada = ".$consecutivo['FAC_pagada'].", FAC_fechaPago='".$consecutivo['FAC_FPago']."' where Exp_folio='".$registros[$i]["Folio"]."'"; 

                 $result =  $this->_db->query($sql);  
             }catch(Exception $e){
                echo $e->getmessage();
             }
                                            
            }
        }else{
            echo 'error';
        }
    }

    public function updateAplicaciones()
    {        
        $registros = array();
        if (($fichero = fopen("aplicados.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            // Lee los registros
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
         
            echo "Leidos " . count($registros) . " registros <br>";
           

            echo "<table>";
         
            for ($i = 0; $i < count($registros); $i++) {
                // echo "Unidad: " . $registros[$i]["unidad"] . "   ";
                // echo "Compañia: " . $registros[$i]["compania"] . "   ";
                // echo "Producto: " . $registros[$i]["producto"] . "   ";
                // echo "Nombre: " . $registros[$i]["nombre"] . "   ";
                // echo "Ap Paterno: " . $registros[$i]["apPaterno"] . "   ";
                // echo "Ap Materno: " . $registros[$i]["apMaterno"] . "   ";
                // echo "Poliza: " . $registros[$i]["poliza"] . "   ";
                // echo "Siniestro: " . $registros[$i]["siniestro"] . "   ";
                // echo "Reporte: " . $registros[$i]["reporte"] . "   ";
                // echo "Triage: " . $registros[$i]["triageActual"] . "   ";
                // echo "rAfectado: " . $registros[$i]["rAfectado"] . "   ";

                 $sql = "UPDATE reciboParticulares SET Recibo_aplicado='".$registros[$i]['Aplicado']."' where Recibo_serie='".$registros[$i]['Serie']."' and Recibo_cont=".$registros[$i]['Recibo']; 

                 $result =  $this->_db->query($sql);  

                echo "<tr><td>".$registros[$i]['Aplicado']."</td><td>".$registros[$i]['Recibo']."</td><td>".$registros[$i]['Serie']."</td></tr>";                
            }
            echo "</table>";
        }else{
            echo 'error';
        }
    }



    public function listaFolio()
    {        
        $registros = array();
        if (($fichero = fopen("cedulas.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            // Lee los registros
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
         
            echo "Leidos " . count($registros) . " registros <br>";

            echo "<table>";
                
                foreach ($registros as $key => $value) {
                    echo $value['FOLAUT'].'<br>';

                    $query="INSERT INTO CartasQualitas(CQ_folioautorizacion,CQ_folioelectronico,CQ_poliza,CQ_siniestro,CQ_reporte,CQ_paciente,CQ_fechareg,
                        CQ_correodestino,CQ_estatus) 
                        VALUES('".$value['FOLAUT']."','".$value['FOLREP']."','".$value['POLIZA']."','".$value['SINIESTRO']."','".$value['REPORTE']."','".$value['NOMBRE']."',now(),'',1)";
                    //echo $query;
                    $result = $this->_db->query($query);
                    echo $value['FOLAUT'].' insertado <br>';


                }
               
            echo "</table>";
        }else{
            echo 'error';
        }
    }

    public function listaExcel()
    {        
        $archivo = "cedulas.xlsx";
        $inputFileType = PHPExcel_IOFactory::identify($archivo);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($archivo);
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        echo "<table>";
        for ($row = 2; $row <= $highestRow; $row++){ 
                $folAut = $sheet->getCell("A".$row)->getValue();
                $folEle = $sheet->getCell("B".$row)->getValue();
                $poliza = $sheet->getCell("C".$row)->getValue();
                $reporte = $sheet->getCell("D".$row)->getValue();
                $siniestro = $sheet->getCell("E".$row)->getValue();
                $nombre = $sheet->getCell("F".$row)->getValue();
                echo "<tr><td>";
                echo $folAut;
                echo "</td><td>";
                echo $folEle;
                echo "</td><td>";
                echo $poliza;
                echo "</td><td>";
                echo $reporte;
                echo "</td><td>";
                echo $siniestro;
                echo "</td><td>";
                echo $nombre;
                echo "</td></tr>";                
        }
        echo "</table>";
    }

    public function insertFoliosZima()
    {        
        $registros = array();
        if (($fichero = fopen("insertFolZima.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            // Lee los registros
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
         
            echo "Leidos " . count($registros) . " registros <br>";

            echo "<table>";
         
            for ($i = 0; $i < count($registros); $i++) {

                $query = "SELECT Fol_MedicaVial FROM RegMVZM WHERE Fol_MedicaVial='".$registros[$i]['folioMV']."'";
                $result =  $this->_db->query($query);      
                $folio = $result->fetch();

                if($folio){
                    $query1 = "UPDATE RegMVZM SET Fol_ZIMA='".$registros[$i]['folioZima']."' WHERE Fol_MedicaVial='".$registros[$i]['folioMV']."'";    
                }else{
                    $query1 = "INSERT INTO RegMVZM(Fol_MedicaVial, Fol_ZIMA, Cia_Clave)
                                     VALUES('".$folio."','".$registros[$i]["fZima"]."',4)";                    
                }
                $result = $this->_db->query($query1);

                // $query1 = "INSERT INTO RegMVZM(Fol_MedicaVial, Fol_ZIMA, Cia_Clave)
                //                     VALUES('".$folio."','".$registros[$i]["fZima"]."',".$registros[$i]["compania"].")";
                // $result = $this->_db->query($query1);              
                
                echo "<tr><td>Folio</td><td>".$registros[$i]['folioMV'].'</td></tr>'; 
                               
            }
            echo "</table>";
        }else{
            echo 'error';
        }
    }

    public function insertFoliosAxa()
    {        
        $registros = array();
        if (($fichero = fopen("insertAxa.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            // Lee los registros
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
         
            echo "Leidos " . count($registros) . " registros <br>";

            echo "<table>";
                
                foreach ($registros as $key => $value) {
                    echo $value['folio'].'<br>';

                    $query="UPDATE Expediente SET Exp_fecreg='".$value['fecha']."' WHERE Exp_folio='".$value['folio']."'";
                    //echo $query;
                    $result = $this->_db->query($query);
                    echo 'FOLIO: '.$value['folio'].' modificado con fecha '.$value['fecha'].' <br>';
                }
               
            echo "</table>";
        }else{
            echo 'error';
        }
    }

    public function insertCapturaAut()
    {        
        $registros = array();
        if (($fichero = fopen("capturaAutomatica.csv", "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);
            // Lee los registros
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
         
            echo "Leidos " . count($registros) . " registros <br>";

            echo "<table>";
                
                foreach ($registros as $key => $value) {
                    echo $value['folio'].'<br>';

                    // $query="UPDATE Expediente SET Exp_fecreg='".$value['fecha']."' WHERE Exp_folio='".$value['folio']."'";
                    // //echo $query;
                    // $result = $this->_db->query($query);
                    // echo 'FOLIO: '.$value['folio'].' modificado con fecha '.$value['fecha'].' <br>';
                }
               
            echo "</table>";
        }else{
            echo 'error';
        }
    }

}
?>