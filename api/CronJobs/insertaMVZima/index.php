<?php

 error_reporting(0);
include  "DocsWebClass.php";

$ftpWeb = new Ftp();
$conexion = $ftpWeb->conectarMySQL();
$conexionZima = $ftpWeb->conectarMySQLZima();



        $registros = array();
        $sql = "SELECT
                    Exp_folio
                FROM
                    Expediente
                WHERE
                    date(EXP_fecReg) = date_sub(curdate(), INTERVAL 1 DAY) and Exp_cancelado<>1";
        $result=$conexion->query($sql);
        $registros = $result->fetchAll();

       
        $folios = $registros;
        $cont = 0;
        // foreach ($folios as $key => $value) {
        //     echo $value['Exp_folio'].'<br>';
        //     $cont++;
        // }

        // echo $cont;

        // $folios = $result->fetchAll();
            echo '<table>
                    <tr>
                        <td><b>FOLIO</b></td>
                        <td><b>ESTATUS</b></td> 
                        <td><b>FOLIO Z</b></td>       
                    </tr>  
            ';
        foreach ($folios as $key => $value) {
            //para subir documentos capturados
            $fol='';
            $fol = $value['Exp_folio'];
            $folioMin = strtolower($fol);

            $queryZ = "SELECT count(*) contador FROM PURegistro WHERE REG_folioMV='".$fol."'";
            $rs = $conexionZima->query($queryZ);
            $docs = $rs->fetch();
            $cont = $docs['contador'];

            if($cont>0){
                $queryZi = "SELECT REG_folio folio FROM PURegistro WHERE REG_folioMV='".$fol."'";
                $rs1 = $conexionZima->query($queryZi);
                $docs = $rs1->fetch();
                $folZima = $docs['folio'];
                echo '<tr>
                            <td><b>'.$fol.'</b></td>
                            <td><b>EXISTE</b></td>   
                            <td><b>'.$folZima.'</b></td>    
                        </tr>';
            }else{

/***************************                                                *************************************/                    
/***************************          inserción en la base de datos de Zima *************************************/
/***************************                                                *************************************/
    // $conexionZima = conectarMySQLZima();
    $query="select REG_folioMV from PURegistro where REG_folioMV='".$fol."'";
    $result=$conexionZima->query($query);
    $row = $result->fetch();
    $existe=$row[0];
    if(!$existe){             
	$sql = "SELECT
                    UNI_zima,
                    ClaveZIMA
                FROM
                    Expediente
                    INNER JOIN Unidad ON Expediente.Uni_clave = Unidad.Uni_clave
                    INNER JOIN Compania ON Expediente.Cia_clave = Compania.Cia_clave 
                WHERE
                    Exp_folio = '".$fol."'";
    $result=$conexion->query($sql);
    $uniZima = $result->fetch();
    $uniZ=$uniZima['UNI_zima']; 
    $aseZ=$uniZima['ClaveZIMA'];                     
    if(($uniZ!='' || $uniZ!=null)&&($aseZ!='' || $aseZ!=null)){                
    $query="select Exp_poliza, Exp_siniestro, Exp_reporte, Exp_paterno, Exp_materno, Exp_nombre, Exp_completo, RIE_clave, Exp_RegCompania, Usu_registro, Cia_clave, Exp_ajustador, Exp_cveAjustador, Exp_telAjustador, Exp_fecreg from Expediente where Exp_folio='".$fol."'";
    $result=$conexion->query($query);
    $datosFolio = $result->fetch();                
    $pol=$datosFolio['Exp_poliza'];
    $sin=$datosFolio['Exp_siniestro'];
    $rep=$datosFolio['Exp_reporte'];
    $pat=$datosFolio['Exp_paterno'];
    $mat=$datosFolio['Exp_materno'];
    $nom=$datosFolio['Exp_nombre'];
    $nomc=$datosFolio['Exp_completo'];
    $rie=$datosFolio['RIE_clave'];
    $usr=$datosFolio['Usu_registro'];
    $RegCia=$datosFolio['Exp_RegCompania'];
    $aseguradora=$datosFolio['Cia_clave'];
    $ajustador=$datosFolio['Exp_ajustador'];
    $cveAjustador=$datosFolio['Exp_cveAjustador'];
    $telAjustador=$datosFolio['Exp_telAjustador'];
    $fechaRegistro = $datosFolio['Exp_fecreg'];
    if($ajustador==''){
       $ilegible='S'; 
    }else{
       $ilegible=''; 
    }
    $query = "Select UNI_prefijo, Supervisor.SUP_clave, SUP_correo, UNI_corrnotificaciones From Unidad inner join Zona on Unidad.ZON_clave=Zona.ZON_clave inner join Supervisor on Zona.SUP_clave=Supervisor.SUP_clave Where UNI_clave=".$uniZ.";";    
    $result=$conexionZima->query($query);
    $row = $result->fetch();
    $prefijo=$row[0];
    $sup=$row[1];
    $correosup=$row[2];
    $correouni=$row[3];
    $query = "Select PAR_correoAdminNal, PAR_correoMedNal, PAR_correoResp, PAR_correoAdminNal_alPrimerReg, PAR_correoMedNal_alPrimerReg From Param Where PAR_clave=1;";
    $result=$conexionZima->query($query);
    $row = $result->fetch();
    $correoAdmin    =$row[0];
    $correoMed      =$row[1];
    $correoResp     =$row[2];
    $mandarAdmin    =$row[3];
    $mandarMed      =$row[4];
        $aju="Mv";
        $reporta="MV_WEB";
        $fecnac="Mv";
        $genero="-";
        $ocu="-";
        $txtedad="0";
        $tel1="0000000";
        $tel2="0000000";
        $carr="-";
        $conc="-";
        $cin="-";
        $sei="18";
        $amb="-";
        $emb="-";
        $usulog="Mv";
        $veh=-1;
 if ($rie==-1){$rie=7;}
                                
                $query="Select Max(REG_clave)+1 From PURegistro";                           
                $result=$conexionZima->query($query);
                $row = $result->fetch();
                $id =$row[0];
                $c="0000000".$id;
                $c=substr($c,-7,7);
                $folioZ=$prefijo.$c;
                $s=substr(md5($folioZ),5,1);
                $s=strtoupper($s);
                $folioZ=$folioZ.$s;
                try{
                $statement = $conexionZima->prepare("
                Insert into PURegistro (Reg_clave, Reg_folio, UNI_clave, UNI_reporto, ASE_clave, REG_poliza, REG_ajustador, REG_siniestro, REG_reporte, REG_orden, REG_apaterno, REG_amaterno, REG_nombre, REG_fecnac, REG_genero, REG_ocupacion, REG_edad, REG_tel1, REG_tel2, REG_observaciones, REG_carretera, REG_conciente, REG_cinturon, SEI_clave, REG_ambulancia, REG_embarazo, SUP_clave, USU_login, REG_desde, VEH_clave, REG_fechahora, REG_nombrecompleto, RIE_clave, REG_folioelectronico, REG_folioMV, Est_clave,REG_claveAjustador,REG_telAjustador,REG_ajustadorNoLegible)
                                values (".$id.",'".$folioZ."',".$uniZ.", '".$reporta."', ".$aseZ.",'".$pol."','".$ajustador."','".$sin."','".$rep."','".$orden."','".$pat."','".$mat."','".$nom."','".$fecnac."','".$sexo."','".$ocu."','".$edad."','".$tel1."','".$tel2."','".$obs."','".$carr."','".$conc."','".$cin."','11','".$amb."','".$emb."','".$sup."','".$usulog."','PMVautomatico', '".$veh."', '".$fechaRegistro."','".$nomc."',".$rie.",'".$RegCia."', '".$fol."',8,'".$cveAjustador."','".$telAjustador."','".$legible."');");
                $statement->execute();
             }catch(Exception $e){
                    $respuesta = array('respuesta' =>$e->getMessage(), 'paso'=>3);
                    echo json_encode($respuesta);
            }
                //*****trab 19082010
                $query="Select STA_clave From SegInicial where SEI_clave=".$sei;
                $result=$conexionZima->query($query);
                $row = $result->fetch();
                $parastatusactual=$row[0];        
                $statement = $conexionZima->prepare("Insert into PUStatusActual (REG_folio, STA_clave, STA_fechahora)
                                   Values('".$folioZ."', ".$parastatusactual.", now());");
                $statement->execute();
                if ($uniZ==17){$res='1450';}else{$res='1605';}
                $statement = $conexionZima->prepare("Insert into PUReserva(REG_folio, RES_reserva, RES_fecreg, Res_estatus, Usu_login)
                                            Values('".$folioZ."', ".$res.", now(),1,'".$usr."')");
                $statement->execute();
                $statement = $conexion->prepare("Insert into RegMVZM(Fol_MedicaVial, Fol_ZIMA, Cia_Clave)
                                    Values('".$fol."','".$folioZ."',".$aseguradora.")");
                $statement->execute();
             	$statement = $conexion->prepare("UPDATE Expediente SET REG_folioZima='".$folioZ."' WHERE Exp_folio='".$fol."'");
                $statement->execute();
               // }
            }
        }
   // }
/***************************                                                *************************************/                    
/***************************      Fin inserción en la base de datos de Zima *************************************/
/***************************                                                *************************************/


                echo '<tr>
                        <td><b>'.$fol.'</b></td>
                        <td><b>INSERTADO</b></td>
                        <td><b>'.$folioZ.'</b></td>    
                    </tr>';
            }  
        }
        echo '</table>';


