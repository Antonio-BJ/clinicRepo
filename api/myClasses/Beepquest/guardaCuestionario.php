<?
///// conexion mv /////

$host   ="www.medicavial.net";
$user   ="medica_webusr";
$pwd    ="tosnav50";
$db     ="medica_registromv";
$conn   =mysql_connect($host,$user,$pwd) or die('Error al conectar: ' . mysql_error());
mysql_select_db($db,$conn);

//// conexion pu ///////

$host1   ="www.pmzima.net";
$user1   ="zima_web3";
$pwd1   ="J6BvL07d.;Yh";
$db1     ="zima_sscp_3";
$connPU   =mysql_connect($host1,$user1,$pwd1) or die('Error al conectar: ' . mysql_error());
mysql_select_db($db1,$connPU);



    $cont=0;
    $fecha_hora=date("Y-m-d H:i:s");

    $datos      =  $_REQUEST['datoGeneral'];
    $tipoCues   =  $_REQUEST['tipoCues'];

    // print_r($datos[0]['inicio1']);

    // obtenemos los datos de la API BEEPQUEST

    $folioMv = $datos[0]['inicio1'][0]['folioMV'];
    if(isset($datos[0]['inicio2'][0]['telContacto'])){ $tel = ''; }else{ $tel = $datos[0]['inicio2'][0]['telContacto']; };

    $fechaRes = $datos[0]['inicio1'][0]['fechaRespondio'];
    $usuario  = $datos[0]['inicio2'][0]['usuCaptura'];
    $correo   = $datos[0]['inicio2'][0]['correo'];

    $respuestas =  $_REQUEST['resp'][0];

    // print_r($respuestas);


    $tel=$tel;
    $fol=$folioMv;
    $tipo=$tipoCues;
    $fec_resp=$fechaRes;
    // $abierta=utf8_decode($_POST['abierta']);
    $calificacion='';
    $usuario=$usuario;
    $mail = $correo;


    //// hacemos consulta para conseguir el folio de PU y guardarlo en PU-PaseCuestionario

    $obtenerFol = "SELECT * FROM Expediente WHERE EXP_folio = '$fol'";
    $result=mysql_query($obtenerFol,$conn);
    while($row=mysql_fetch_array($result)){

          $folioPu = $row['REG_folioZima'];


           $query="INSERT INTO PaseCuestionario (TIC_claveint, PAS_folio, USU_Captura, PCU_fechaCaptura, PCU_fechaRespondio, PCU_tipoComentario, PCU_telContacto, PCU_mailContacto) VALUES 
                ('".$tipo."','".$folioPu."','".$usuario."',now(),'".$fec_resp."','".$calificacion."','".$tel."','".$mail."')";
           $result1=mysql_query($query);

          $query2="SELECT * FROM PaseCuestionario WHERE PAS_folio='$folioPu'";
          $result2=mysql_query($query2,$connPU);
          $row2=mysql_fetch_assoc($result2);     
          $PCU=$row2['PCU_claveint'];
            
            $query3="SELECT MAX(PEG_claveint) as Maximo FROM Pregunta WHERE TIC_claveint='$tipo' ORDER BY PEG_orden ASC";
            $result3=mysql_query($query3,$connPU);
            $row3=mysql_fetch_assoc($result3);
            
            $maximo_preg=$row3['Maximo'].'<br>';
            
            $query4="SELECT MIN(PEG_claveint) as Minimo FROM Pregunta WHERE TIC_claveint='$tipo' ORDER BY PEG_orden ASC";
            $result4=mysql_query($query4,$connPU);
            $row4=mysql_fetch_assoc($result4);
            
            $minimo_preg=$row4['Minimo'];
            
            $query5="SELECT MAX(RES_claveint) as Maximo FROM Respuesta";
            $result5=mysql_query($query5,$connPU);
            $row5=mysql_fetch_assoc($result5);
            
            $maximo_res=$row5['Maximo'];
            
            $query7="SELECT * FROM Pregunta WHERE TIC_claveint='$tipo'  && SET_claveint=1";
            $result7=mysql_query($query7,$connPU);
            $row7=mysql_fetch_assoc($result7);
            
            $PEG_abierta=$row7['PEG_claveint'];  

       

    }

    $conta = count($respuestas);
    $pre = $minimo_preg-1;

    for ($i=1; $i <= $conta; $i++) { 
        // echo $minimo_preg;
        // echo $maximo_preg;
        // for($pre=$minimo_preg; $pre <= $maximo_preg; $pre++){
       $val = 'res'.$i;
       $textResp =  $respuestas[$val];

       // if ($textResp == '5 min (Excelente)'){

       //    $textResp = "5 min";

       // }elseif($textResp == '15 min (Bueno)'){

       //    $textResp = "15 min";

       // }elseif($textResp == '20 min (Regular)'){

       //    $textResp = "20 min";

       // }elseif($textResp == '30 min (Malo)'){ 
          
       //    $textResp = "30 min";

       // }elseif($textResp == '+30 min (Muy malo)'){
           
       //    $textResp = "+ 30 min";
       // }else{

       //    $textResp = $textResp;

       // }

       $clave_preg=$pre+1;

       $preg = "SELECT RES_claveint FROM Respuesta WHERE RES_texto like '%$textResp%'";
       $result8=mysql_query($preg,$connPU);
       $row8=mysql_fetch_assoc($result8);

        $claveRet = $row8['RES_claveint'];

           $query10="INSERT INTO PaseCuestionarioRespuesta VALUES ('".$PCU."','".$clave_preg."','".$claveRet."',NULL)";
            $result10=mysql_query($query10,$connPU);
            
            if ($result10) {

                $query11="UPDATE PURegistro SET REG_cuestionario='S' WHERE REG_folio='".$folioPu."'";
                $result11=mysql_query($query11,$connPU);


            }
                        
                    
        // }


       
       $pre++;
    }
// }
      ///////// indicamos valores de la pregunta




    

                    
    //     $query6="INSERT INTO PaseCuestionarioRespuesta VALUES ('".$PCU."','".$clave_preg."','".$clave_res."','".$abierta."')";
    //     $result6=mysql_query($query6,$connPU);

 
    ?>
    