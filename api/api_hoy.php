<?php
//tiempo de espera en caso de tardar mas de 30 segundos una consulta grande

include ('classes/nomad_mimemail.inc.php');

set_time_limit(3600);
//sin limite me memoria 
ini_set('memory_limit', '-1');
//ocultar los errores
error_reporting(0);

date_default_timezone_set('America/Mexico_City'); //Ajustando zona horaria


function ultimodiadelmes($mes) { 
      $month = date($mes);
      $year = date('Y');
      $day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
      return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
};
 
  /** Actual month first day **/
function primerdiadelmes($mes) {
  $month = date($mes);
  $year = date('Y');
  return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

function generar_clave(){ 

        $pares = '24680';
        $nones = '13579';
        $vocales = 'AEIOU';
        $consonantes = "BCDEFGHIJKLMNOPQRSTUVWXYZ";
        $todos = $vocales . $pares . $consonantes . $nones;
        $valor = "";

        $valor .= substr($vocales,rand(0,4),1);
        $valor .= substr($consonantes,rand(0,23),1);
        $valor .= substr($pares,rand(0,4),1);
        $valor .= substr($nones,rand(0,4),1);
        $valor .= substr($todos,rand(0,29),1);

        return $valor;

} 


function generar_numero(){ 

        $valor = '';

        $pares = '24680';
        $nones = '13579';
        $consonantes = "BCDEFGHIJKLMNOPQRSTUVWXYZ";
        $todos =  $pares . $consonantes . $nones;
       
        $valor .= substr($pares,rand(0,4),1);
        $valor .= substr($nones,rand(0,4),1);
        $valor .= substr($todos,rand(0,34),1);
        return $valor;

} 

function conectarMySQL(){

    $dbhost="medicavial.net";
    $dbuser="medica_webusr";
    $dbpass="tosnav50";
    $dbname="medica_registromv";
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    date_default_timezone_set('America/Mexico_City');
    return $conn;
}

function conectarMySQLZima(){

    $dbhost="www.pmzima.net";
    $dbuser="zima_web";
    $dbpass="W3dik@_0i12";
    $dbname="zima_sscp_3";
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    date_default_timezone_set('America/Mexico_City');
    return $conn;
}

//Obtenemos la funcion que necesitamos y yo tengo que mandar 
//la URL de la siguiente forma api/api.php?funcion=login

$funcion = $_GET['funcion'];


if($funcion == 'login'){
    
    //Obtenemos los datos que mandamos de angular
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $data = json_decode($postdata);
    $conexion = conectarMySQL();
        
    //Obtenemos los valores de usuario y contrase??a 
    $user = trim($data->user);
    $psw = trim($data->psw);
    
    $sql = "SELECT * FROM Usuario
            INNER JOIN Unidad ON Usuario.Uni_clave=Unidad.Uni_clave
            WHERE Usu_login = '".$user."' and Usu_pwd = '" . md5($psw) . "'
            AND Unidad.Uni_propia='S' and Usu_activo='S'";

    $result = $conexion->query($sql);
    $numero = $result->rowCount();
    
    if ($numero>0){

        $datos = $result->fetchAll(PDO::FETCH_OBJ);
        
    }else{

        $datos = array('respuesta' => 'El Usuario o contrase??a son incorrectos');
    }
    
    echo json_encode($datos);

    $conexion = null;

}

if($funcion == 'buscaExpedientes'){
    
   
}

if($funcion == 'permisos'){
    $postdata = file_get_contents("php://input");    
    $data = json_decode($postdata);
    $user = trim($data->user);
    $conexion = conectarMySQL();
    $sql = "SELECT * FROM Permiso where Usu_login='".$user."'";
    $result = $conexion->query($sql); 
    $datos = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datos);
    $conexion = null;
   
}

if($funcion == 'cancelados'){
            $conexion = conectarMySQL();             
             $handle = fopen('cancelados.csv', "r");
            
             while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
             {
                
                $sql = "UPDATE Expediente set Exp_cancelado=0 and Exp_motCancel='".$data[1]."' where Exp_folio='".$data[0]."'";
                $result = $conexion->query($sql); 
                $sql = "SELECT Exp_cancelado FROM Expediente where Exp_folio='".$data[0]."'";
               //$sql = "SELECT Exp_cancelado FROM Expediente where Exp_folio='".$data[0]."'";
                $result = $conexion->query($sql); 
                $datos = $result->fetch();
                echo $datos['Exp_cancelado'].'<br>';                
             }
             //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
             fclose($handle);
             echo "Importaci??n exitosa!";
             $conexion = null;
                             
   
}

if($funcion == 'zona'){    
   $postdata = file_get_contents("php://input");    
    $data = json_decode($postdata);
    $unidad = trim($data->unidad);
    $conexion = conectarMySQL();
    $sql = "SELECT Zon_clave FROM Unidad where Uni_clave=".$unidad;
    $result = $conexion->query($sql); 
    $datos = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datos);
    $conexion = null;
}

if($funcion == 'unidades'){

    $conexion = conectarMySQL();

    //$sql = "SELECT * FROM Unidad order BY Uni_nombre";

      $sql = " select Uni_clave, UNI_nombreMV as Uni_nombre from Unidad 
                inner join Localidad on Unidad.LOC_claveint=Localidad.LOC_claveint
                where Uni_activa='S' order by UNI_nombreMV";
    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}


if($funcion == 'usuario'){

    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $data = json_decode($postdata);
    $conexion = conectarMySQL();
    
    $nombre = $data->nombre;
    $usuario = $data->usuario;
    $psw = md5($data->psw);
    $admin = $data->admin;
    $correo = $data->correo;
    $empresa = $data->empresa;


    $sqlDet = "SELECT * FROM UsuarioInfoWeb 
                WHERE USU_login = '$usuario'";
    $result = $conexion->query($sqlDet);
    $numero = $result->rowCount();
    
    if ($numero>0){

        $respuesta = array('respuesta' => 'El Usuario ya existe');
        
    }else{

        $sql = "INSERT INTO UsuarioInfoWeb (
                        USU_nombre
                        ,USU_login
                        ,USU_password
                        ,USU_fechaReg
                        ,USU_activo
                        ,USU_administrador
                        ,USU_correo
                        ,Cia_clave
                ) VALUES (:nombre,:usuario,:psw,now(),1,:admin,:correo,:empresa)";

        $temporal = $conexion->prepare($sql);

        // $temporal->bindParam("clave", $clave, PDO::PARAM_INT);
        // $temporal->bindParam("nombre", $nombre, PDO::PARAM_STR);

        $temporal->bindParam("nombre", $nombre);
        $temporal->bindParam("usuario", $usuario);
        $temporal->bindParam("psw", $psw);
        $temporal->bindParam("admin", $admin);
        $temporal->bindParam("correo", $correo);
        $temporal->bindParam("empresa", $empresa);
        
        if ($temporal->execute()){
            $respuesta = array('respuesta' => "Los Datos se guardaron Correctamente");
        }else{
            $respuesta = array('respuesta' => "Los Datos No se Guardaron Verifique su Informaci??n");
        }
        
    }


    
    echo json_encode($respuesta);

    $conexion = null;

}


if($funcion == 'usuarios'){

    $conexion = conectarMySQL();

    $sql = "SELECT * FROM Usuario";

    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}

if($funcion == 'registra'){


    $postdata = file_get_contents("php://input");
    $mimemail = new nomad_mimemail();
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $usr=$_GET['usr'];
    $uniClave=$_GET['uniClave'];
    $uni    =   $uniClave;
    $ciaClave=$_GET['ciaClave'];
    $prod=$_GET['prod'];
    $convenio=$_GET['convenio'];
    $modificador='';
    //$anios=0;
    //$meses =0;

    $datos = json_decode($postdata);
    
    $nombre = $datos[0]->nombre;
    $apPat = $datos[0]->pat;
    $apMat = $datos[0]->mat;
    $fecharegistro = $datos[0]->fecnac;
    $tipTel = $datos[0]->tel;
    $tel = $datos[0]->numeroTel;
    $correo = $datos[0]->correo; 
    $codPostal= $datos[0]->codPostal;
    $folioMembresia = $datos[0]->folioMembresia;
    $subProducto=0;
    $claveEtapa = $datos[0]->claveEtapa;
    $confirmRFC    =$datos[0]->confirmRFC;
    $RFC    =$datos[0]->rfc;

    if($ciaClave==54){
        if($convenio==1){
            $modificador='T. INB';
        }elseif($convenio==2){
            $modificador='BIMBO';
        }elseif($convenio==3){
            $modificador='M.MV';
        }elseif($convenio==4){
            $modificador='Desclub';
        }elseif($convenio==5){
            $modificador='MetLife';
        }elseif($convenio==6){
            $modificador='Aseg';
        }elseif($convenio==7){
            $modificador='HAPAG';
        }elseif($convenio==8){
            $modificador='KEKEN';
        }elseif($convenio==13){
            $modificador='ASISS';
        }elseif($convenio==14){
            $modificador='T. MUL';
        }
    }

    if($ciaClave==8&&$prod==2){
        if($datos[0]->esChedraui==1){
            $modificador='Chedraui';
        }
    }elseif($ciaClave==6&&$prod==2){
        if($datos[0]->esChedraui==2){
            $modificador='APE';
            $convenio=12;
        }
    }  

    $codPostal=(string)$codPostal;
    if(strlen($codPostal)==4){
        $codPostal='0'.$codPostal;
    }elseif (strlen($codPostal)==3) {
        $codPostal='00'.$codPostal;
    }      

    $fecha = explode("-", $fecharegistro);
    

    $fechaNac2= date('Y-m-d', strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0])); 

    $edad = calculaEdad($fechaNac2);
    $edadArray=explode("/", $edad);
    $anios=$edadArray[0];
    $meses =$edadArray[1];

    $nomComp= $nombre.' '.$apPat.' '.$apMat;
    switch ($tipTel) {
    case 1:
        $tipoTel="Particular";
        break;
    case 2:
        $tipoTel="Oficina";
        break;
    case 3:
        $tipoTel="M??vil";
        break;
    case 4:
        $tipoTel="Otro";
        break;                    
    default:                      
        break;
    }  

    $telefono = $tipoTel.'-'.$tel;  

    $db = conectarMySQL();



    /*$sql = "Select Uni_clave From Usuario Where Usu_login='".$usr."'";
    $result = $db->query($sql);
    $uniClave = $result->fetchAll();    

    foreach ($uniClave as $key ) {
        $uni = $key['Uni_clave'];
    };*/    
    $sql = "Select Pre_prefijo From Prefijo Where Uni_clave='".$uni."'";
    $result = $db->query($sql);
    $ultimo = $result->fetchAll();     
    foreach ($ultimo as $key) {
        $prefijo=$key[0];
    }
    $sql = "Select MAX(EXP_cons)+1 From Expediente Where Exp_prefijo='".$prefijo."'";
    $result = $db->query($sql);
    $consecutivo = $result->fetchAll();     
    foreach ($consecutivo as $key) {
        $cons=$key[0];
    }    
    if ($cons==null) {$cons=1;}
    $c="000000".$cons;
    $c=substr($c,-6,6);
    $folio=$prefijo.$c;

    $fecha = date("Y-m-d H:i:s");

    try{

    $sql="Insert into Expediente (
                        Exp_folio, 
                        Exp_prefijo, 
                        Exp_cons, 
                        Uni_clave, 
                        Usu_registro, 
                        Exp_paterno, 
                        Exp_materno,  
                        Exp_nombre,Exp_completo, 
                        Exp_fechaNac, 
                        Exp_fechaNac2,
                        Exp_edad,
                        Exp_meses, 
                        Exp_mail,
                        Exp_tipoTel, 
                        Exp_telefono,
                        Exp_codPostal, 
                        Exp_fecreg, 
                        Cia_clave,                       
                        Pro_clave,
                        Uni_claveActual,
                        Exp_modificador,
                        CON_cve,
                        Exp_cveMemMV,
                        subPro_clave,
                        Exp_estatusReg,
                        Exp_triageOrigen,
                        Exp_triageActual)
            VALUES(:folio,:prefijo,:cons,:uni_clave,:usu_registro,:apPat,:apMat,:nombre,:nomComp,:fecNa,:fecNa1,:edad,:meses,:mail,:tipoTel,:telefono,:codPostal,:Exp_fecreg,:ciaClave,:proClave,:Uni_claveActual,:Exp_modificador,:CON_cve,:Exp_cveMemMV,:subPro_clave,:claveEtapa,1,1)";




    $temporal = $db->prepare($sql);

    $temporal->bindParam("folio", $folio);
    $temporal->bindParam("prefijo", $prefijo);
    $temporal->bindParam("cons", $cons);
    $temporal->bindParam("uni_clave", $uni);
    $temporal->bindParam("usu_registro", $usr);
    $temporal->bindParam("apPat", $apPat);
    $temporal->bindParam("apMat", $apMat);
    $temporal->bindParam("nombre", $nombre);
    $temporal->bindParam("nomComp", $nomComp);
    $temporal->bindParam("fecNa", $fecharegistro);
    $temporal->bindParam("fecNa1", $fechaNac2);
    $temporal->bindParam("edad", $anios);
    $temporal->bindParam("meses", $meses);
    $temporal->bindParam("mail", $correo);
    $temporal->bindParam("tipoTel", $tipTel);
    $temporal->bindParam("telefono", $telefono);
    $temporal->bindParam("codPostal", $codPostal);        
    $temporal->bindParam("Exp_fecreg", $fecha);        
    $temporal->bindParam("ciaClave", $ciaClave);
    $temporal->bindParam("proClave", $prod);
    $temporal->bindParam("Uni_claveActual", $uni);
    $temporal->bindParam("Exp_modificador", $modificador);
    $temporal->bindParam("CON_cve", $convenio);
    $temporal->bindParam("Exp_cveMemMV", $folioMembresia);
    $temporal->bindParam("subPro_clave", $convenio);
    $temporal->bindParam("claveEtapa", $claveEtapa);

    
    if ($temporal->execute()){
    	require('classes/generaCDB_resp.php');        
        $genera=new generaCDB_resp();
        $resp=$genera->generaCodigo($folio);
        
        if($datos[1]){
            foreach ($datos[1] as $key) {               
                $query="Insert into TelefonosLesionado(Exp_folio, Tel_tipo,Tel_numero,Tel_cont)
                        values(:Exp_folio,:Tel_tipo,:Tel_numero,:Tel_cont);";
                $temporal = $db->prepare($query);
                $temporal->bindParam("Exp_folio", $folio);
                $temporal->bindParam("Tel_tipo", $key->tip);
                $temporal->bindParam("Tel_numero", $key->tel);
                $temporal->bindParam("Tel_cont", $key->cont);
                $temporal->execute();        
            }
        }
        if($confirmRFC){
            if($confirmRFC=='SI'){
                $sql = "Select RFC_rfc From RFC Where RFC_rfc='".$RFC."'";
                $result = $db->query($sql);
                $ultimo = $result->fetch();   
                $rfcExist=$ultimo['RFC_rfc'];
                if($rfcExist){
                    $sql="UPDATE RFC_uso SET RFU_uso=RFU_uso+1  WHERE RFC_rfc='".$RFC."'";
                    $result = $db->query($sql);
                }else{
                    $sql="INSERT INTO RFC(RFC_rfc,RFC_FAlta,RFC_diferenciador,Exp_folio)
                          VALUES('".$RFC."', now(),".$uni.",'".$folio."')";
                    $result = $db->query($sql);
                    $sql1="INSERT INTO RFC_uso(RFC_rfc,RFU_uso,RFC_nuevo)
                          VALUES('".$RFC."',1,1)";
                    $result = $db->query($sql1);
                }
            }
        }
       $respuesta = array('respuesta' => 'Los datos Se Guardaron Correctamente', 'folio'=>$folio);
    }else{
        $respuesta = array('respuesta' => "Los Datos No se Guardaron Verifique su Informaci??n");
    }
    }catch(Exception $e){
        $respuesta=array('respuesta'=> $e->getMessage());
    }
    echo json_encode($respuesta);

    //////////////////////////// AVISO DE REGISTRO SIN NOTA MEDICA ////////////////////////////
    // fecha de inicio de la incidencia de atenciones
    $fechaInicioIncidencia= "2016-04-21 00:00:00";
    #print_r($fechaHoy);
        $sql="UPDATE EstatusRegistroNota SET ERN_contador=ERN_contador+1, ERN_fecActual=now() where Uni_clave=".$uni.";";
        $result = $db->query($sql);

        $sql="SELECT ERN_fecReset FROM EstatusRegistroNota where Uni_clave='".$uni."'";
        $result = $db->query($sql);
        $rs = $result->fetch();
        $fechaInicioIncidencia = $rs['ERN_fecReset'];

       /*$sql1="SELECT ERN_contador FROM EstatusRegistroNota WHERE Uni_clave=".$uni.";";
       $result1 = $db->query($sql1);
       $rs = $result1->fetch();
       $cont= $rs['ERN_contador'];*/

       $sql="   SELECT  count(*) AS contador
                FROM Expediente                                  
                left JOIN NotaMedica ON Expediente.Exp_folio=NotaMedica.Exp_folio
                left join reciboParticulares on Expediente.Exp_folio=reciboParticulares.Exp_folio
                inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
                left join DocumentosDigitales on Expediente.Exp_folio=DocumentosDigitales.REG_folio
                WHERE NotaMedica.Exp_folio is NULL
                AND reciboParticulares.Exp_folio is null
                and DocumentosDigitales.REG_folio is NULL
                AND Exp_fecreg>='".$fechaInicioIncidencia."'
                AND Expediente.Uni_clave=".$uni." and Exp_cancelado<>1 and Exp_solCancela is NULL
                AND Usu_registro<>'dsamano'
                AND (PRO_clave<>3 and PRO_clave<>4 and PRO_clave<>5 and PRO_clave<>13)
                and Uni_propia='S'
                order by Exp_fecreg desc";
        $result1 = $db->query($sql);
        $rs = $result1->fetch();
        $cont=$rs['contador'];

        $sql="UPDATE EstatusRegistroNota SET ERN_contador=".$cont.", ERN_fecActual=now() where Uni_clave=".$uni.";";
        $result = $db->query($sql);

       if($cont>=4 && $uni!=8){
         
                    $fecha = date('d-m-Y');
                    $hora  = date('h:i a');
                    $sql="SELECT Uni_nombrecorto FROM Unidad where Uni_clave='".$uni."'";
                    $result = $db->query($sql);
                    $rs = $result->fetch();
                    $Unidad = $rs['Uni_nombrecorto'];

                    $sql="SELECT  Expediente.Exp_folio, Exp_completo, Exp_fecreg, Compania.Cia_nombrecorto,  Uni_nombrecorto
                            FROM Expediente  
                            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                            inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
                            left JOIN NotaMedica ON Expediente.Exp_folio=NotaMedica.Exp_folio
                            left join reciboParticulares on Expediente.Exp_folio=reciboParticulares.Exp_folio
                            left join DocumentosDigitales on Expediente.Exp_folio=DocumentosDigitales.REG_folio
                            WHERE NotaMedica.Exp_folio is NULL
                            AND reciboParticulares.Exp_folio is null
                            and DocumentosDigitales.REG_folio is NULL
                            AND Exp_fecreg>='".$fechaInicioIncidencia."'
                            AND Expediente.Uni_clave=".$uni." and Exp_cancelado<>1 and Exp_solCancela is NULL
                            AND (PRO_clave<>3 and PRO_clave<>4 and PRO_clave<>5 and PRO_clave<>13)
                            order by Exp_fecreg desc";
                    $result1 = $db->query($sql);                    
                    $contenido='
                        <!DOCTYPE HTML>
                        <html>
                        <head>
                            <meta charset="utf-8">
                        </head>
                        <body>

                            <table style="font-family: sans-serif; margin: auto;">
                                <th colspan="6" height="40" style="background-color: orange; height: 12px; font-family: sans-serif">
                                    <h2>&iexcl;ALERTA!<h2>
                                </th>
                                <tr>
                                    <td colspan="2" style="text-align: left; font-size: 14px; font-family: sans-serif">
                                        <br>
                                        &nbsp;&nbsp;<img src="logomv.gif" width="80">
                                    </td>                                    
                                    <td colspan="4" style="text-align: right; font-size: 14px; font-family: sans-serif">
                                        <br>
                                        '.$fecha.' - '.$hora.'
                                    </td>
                                </tr>';


                                $contenido.=' <tr>
                                    <td colspan="6" style="text-align: justify; font-size: 18px; font-family: sans-serif">
                                        <p>
                                        Actualmente existen '.$cont.' pacientes registrados a quienes no se les ha capturado y/o digitalizado NOTA M&Eacute;DICA en la Unidad <b>'.$Unidad.'</b>
                                        </p>
                                    </td>
                                </tr>
                            <!--</table>-->                            
                            ';

                                 $contenido.='
                                <!--<table style="text-align: justify; font-size: 18px; font-family: sans-serif">-->
                                    <tr style="border: 1px solid black; border-collapse: collapse; background-color: #eee">
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">#</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Folio</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Nombre del paciente</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Fecha de registro</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Compa&ntilde;&iacute;a</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Unidad</th>
                                    </tr>';

                            $indiceTabla=1;
                            while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {

                                    $contenido.='
                                        <tr style="border: 1px solid black; border-collapse: collapse;">
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.$indiceTabla.'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Exp_folio']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_decode($row['Exp_completo']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Exp_fecreg']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Cia_nombrecorto']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Uni_nombrecorto']).'</td>
                                        </tr>
                                        ';

                            $indiceTabla++;
                            }                                        
                            
                            $contenido.='
                            </table>
                        </body>
                        </html>
                    ';

                        $mimemail->set_from("incidencias_NoReply@medicavial.com.mx");
                        //$mimemail->set_to("jabraham@medicavial.com.mx");
                        if($cont>=4&&$cont<=5){
                        	$mimemail->set_to("scisneros@medicavial.com.mx");
                        	$mimemail->add_cc("scampos@medicavial.com.mx");	
                        	//$mimemail->add_cc("egutierrez@medicavial.com.mx");
                        }elseif($cont>=6){
	                        //$mimemail->set_to("jsanchez@medicavial.com.mx"); 
	                        $mimemail->set_to("scisneros@medicavial.com.mx");
                        	$mimemail->add_cc("scampos@medicavial.com.mx");	                                               
	                        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
	                        //$mimemail->add_cc("agutierrez@medicavial.com.mx");
	                        $mimemail->add_cc("cmendez@medicavial.com.mx");
	                        $mimemail->add_cc("egutierrez@medicavial.com.mx");
                    	}
                        //$mimemail->add_bcc("enriqueerick@gmail.com");                                                
                        #$mimemail->add_bcc("oculto@algo.com");
                        $mimemail->set_subject("INCIDENCIA DE ATENCIONES - ".$Unidad);
                        $mimemail->set_html($contenido);
                        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
                        #$mimemail->add_attachment("../imgs/logomv.png");
                            
                        $mimemail->send();                               

       }
        //print_r("SIN NOTA MEDICA: ".$contador);

        #ESCRIBIR EN LA BASE
        /*if ($contador>=1) {
            try{
                //print_r("DENTRO ".$contador);
                $sql2="SELECT aviso_id, aviso_contador, Uni_clave, Exp_folio, aviso_fecha
                        FROM aviso_sin_nota
                        WHERE aviso_fecha<now()
                        AND Uni_clave='".$uni."'
                        order by aviso_fecha desc
                        limit 1;
                        ";

                $result = $db->query($sql2);
                $rs = $result->fetch();
                $tblConta=$rs[1];
                }catch(Exception $e){
                    return $e->getMessage();
                  }
                //print_r("LOS DE AHORA: ".$tblConta);

            if ($tblConta<4) {
                $nuevoConta=$tblConta+1;
                //print_r("REGISTRO DE HOY = ".$nuevoConta);
                try{
                    $sql="INSERT into aviso_sin_nota(aviso_id, 
                                                       aviso_contador, 
                                                       Uni_clave, 
                                                       Exp_folio, 
                                                       aviso_fecha)
                                        VALUES(DEFAULT,
                                               '".$nuevoConta."',
                                               '".$uni."',
                                               '".$folio."',
                                               '".$fecha."');
                            ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
            } elseif ($tblConta==4) {
                $nuevoConta=1;
                //print_r("HAY ".$tblConta." PACIENTES SIN NOTA MEDICA");
                try{
                    $sql="INSERT into aviso_sin_nota(aviso_id, 
                                                       aviso_contador, 
                                                       Uni_clave, 
                                                       Exp_folio, 
                                                       aviso_fecha)
                                        VALUES(DEFAULT,
                                               '".$nuevoConta."',
                                               '".$uni."',
                                               '".$folio."',
                                               '".$fecha."');
                            ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    #return $respuesta;
        
                #inicia de env??o de correo
                    $fecha = date('d-m-Y');
                    $hora  = date('h:i a');
                    
                    $contenido='
                        <!DOCTYPE HTML>
                        <html>
                        <head>
                            <meta charset="utf-8">
                        </head>
                        <body>

                            <table style="font-family: sans-serif; margin: auto;">
                                <th colspan="6" height="40" style="background-color: orange; height: 12px; font-family: sans-serif">
                                    <h2>&iexcl;ALERTA!<h2>
                                </th>
                                <tr>
                                    <td colspan="2" style="text-align: left; font-size: 14px; font-family: sans-serif">
                                        <br>
                                        &nbsp;&nbsp;<img src="http://www.medicavial.net/mvnuevo/imgs/logomv.png" width="60">
                                    </td>                                    
                                    <td colspan="4" style="text-align: right; font-size: 14px; font-family: sans-serif">
                                        <br>
                                        '.$fecha.' - '.$hora.'
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align: justify; font-size: 18px; font-family: sans-serif">
                                        <p>
                                        Actualmente existen pacientes registrados a quienes no se les ha capturado NOTA M&Eacute;DICA
                                        </p>
                                    </td>
                                </tr>
                            <!--</table>-->
                            <br>
                            ';
                            $sql="SELECT  Expediente.Exp_folio, Exp_completo, Exp_fecreg, Compania.Cia_nombrecorto, Uni_nombrecorto
                                  FROM Expediente  
                                  inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                                  inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
                                  left JOIN NotaMedica ON Expediente.Exp_folio=NotaMedica.Exp_folio
                                  WHERE NotaMedica.Exp_folio is NULL
                                  AND Exp_fecreg>='".$fechaHoy."'
                                  AND Expediente.Uni_clave='".$uni."'
                                  order by Exp_fecreg desc
                                  limit 4
                            ";

                            $result = $db->query($sql);
                            #$row = mysql_fetch_array($result);
                            #print_r($row);
                        
                            $contenido.='
                                <!--<table style="text-align: justify; font-size: 18px; font-family: sans-serif">-->
                                    <tr style="border: 1px solid black; border-collapse: collapse; background-color: #eee">
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">#</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Folio</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Nombre del paciente</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Fecha de registro</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Compa&ntilde;&iacute;a</th>
                                        <th style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">Unidad</th>
                                    </tr>';

                            $indiceTabla=1;
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                                    $contenido.='
                                        <tr style="border: 1px solid black; border-collapse: collapse;">
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.$indiceTabla.'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Exp_folio']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Exp_completo']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Exp_fecreg']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Cia_nombrecorto']).'</td>
                                            <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Uni_nombrecorto']).'</td>
                                        </tr>
                                        ';

                            $indiceTabla++;
                            }
                            $contenido.='
                            </table>
                        </body>
                        </html>
                    ';

                        $mimemail->set_from("incidencias_NoReply@medicavial.com.mx");
                        $mimemail->set_to("egutierrez@medicavial.com.mx");
                        $mimemail->add_cc("enriqueerick@gmail.com");
                        #$mimemail->add_bcc("oculto@algo.com");
                        $mimemail->set_subject("SE REQUIEREN NOTAS MEDICAS");
                        $mimemail->set_html($contenido);
                        #$mimemail->add_attachment("../imgs/logomv.png");
                            
                            $mimemail->send();                               
                #termina env??o de correo
             }
        }
        */
    ////////////////// TERMINA AVISO DE REGISTRO SIN NOTA MEDICA ///////////////////

    $db = null;
}

if($funcion == 'catCobertura'){

   	$db = conectarMySQL();
    $sql = "Select RIE_clave, RIE_nombre From RiesgoAfectado where RIE_activo = 'S'";
    $result = $db->query($sql);
    $datos = $result->fetchAll();    
    echo json_encode($datos);
    $db = null;
}

if($funcion == 'codExiste'){

    $codigo= $_GET['cod'];
    if(strlen($codigo)==4){
        $codigo='0'.$codigo;
    }elseif (strlen($codigo)==3) {
        $codigo='00'.$codigo;
    }   
    $db = conectarMySQL();
    $sql = "Select * From CodigoPostal where d_codigo = '".$codigo."'";
    $result = $db->query($sql);
    $rs = $result->fetch();  
    if($rs){
        echo 'existe';
    }else{
        echo 'noExiste';
    }
    $db = null;
}


if ($funcion == 'validaAut') {
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}

    $autorizacion =123456;
    $datos = json_decode($postdata);
    if($datos->aut==$autorizacion){
        $datosResp=array('respuesta'=>'correcto');
    }
    else{
        $datosResp=array('respuesta'=>'incorrecto');
    }
    echo json_encode($datosResp);
}

/*******************************    funci??n para checar duplicados **************************************/
if ($funcion == 'checaDuplicado') {
    $usr=$_GET['usr'];
    $uniClave=$_GET['uniClave'];
    $ciaClave=$_GET['ciaClave'];
    $prod=$_GET['prod'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $nombre = $datos[0]->nombre;
    $apPat = $datos[0]->pat;
    $apMat = $datos[0]->mat;
    $fecharegistro = $datos[0]->fecnac;
    $tipTel = $datos[0]->tel;
    $tel = $datos[0]->numeroTel;
    $correo = $datos[0]->correo; 
    $db = conectarMySQL();

    $sql="Select Expediente.Exp_folio,Exp_completo,Cia_nombrecorto,Uni_nombrecorto,Exp_fecreg,Exp_poliza, Exp_siniestro, Exp_reporte From Expediente
                inner join Unidad on Unidad.Uni_clave=Expediente.Uni_clave
                inner join Compania on Compania.Cia_clave=Expediente.Cia_clave 
            Where Exp_paterno='".$apPat."' 
            and Exp_materno='".$apMat."' 
            and Exp_nombre='".$nombre."'           
            and Expediente.Uni_clave=". $uniClave."            
            and LEFT(Exp_fecreg, 10) = LEFT(now(),10)";
    $result=$db->query($sql);
    $datoFol = $result->fetchAll(PDO::FETCH_OBJ);

    if($datoFol){               
        echo json_encode($datoFol);   
    }else{
        echo 'nada';
    }
    $db = null;
    
}

/*******************************  fin de funci??n para checar duplicados **************************************/

if($funcion == 'registraSin'){
      $fol=$_GET['fol'];
    $mimemail = new nomad_mimemail();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);

    $conexion = conectarMySQL();

    $deducible='';
    $obsDeducible='';
    $facExp = 0;

    $cobAfec    = $datos->cobAfec;
    $inciso     = $datos->inciso;
    $noCia      = $datos->noCia;
    $poliza     = $datos->poliza;
    $siniestro  = $datos->siniestro;
    $reporte    = $datos->reporte;
    $folPase    = $datos->folPase;
    $obs        = $datos->obs;
    $ajustador  = $datos->ajustador;
    $cveAjustador = $datos->cveAjustador;
    $telAjustador = $datos->telAjustador;
    $deducible    = $datos->deducibleMonto;
    $obsDeducible = $datos->obsDed;
    $orden        = $datos->orden;
    $bitacora     = $datos->bitacora;
    $fecExped     = $datos->fechaExpedicion;
    $autAtlasAE   = $datos->autorizacionAtlasAE;
    
    $cveAjustadorMV = $datos->claveAjustador;

     $fecha = explode("-", $fecExped);
    
     if(strlen($fecha[2])==4){
       $fecExped= date('Y-m-d', strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0])); 
     }
    


    if($cveAjustadorMV==''){
        if($ajustador==''){
            $ajustador='ILEGIBLE';
            $sql = "SELECT AJU_claveint From Cat_Ajustador where AJU_nombre='".$ajustador."'";
            $result = $conexion->query($sql);
            $consecutivo = $result->fetch(); 
        }
        $sql = "Select MAX(AJU_claveint)+1 From Cat_Ajustador";
        $result = $conexion->query($sql);
        $consecutivo = $result->fetch();            
        $cons=$consecutivo[0];          
        $sql="SELECT  Cia_clave,Uni_clave FROM Expediente where Exp_folio='".$fol."'";
        $result = $conexion->query($sql);
        $datosFol = $result->fetch();     
        $ciaClave = $datosFol['Cia_clave'];
        $uniClave = $datosFol['Uni_clave'];             
        $ciaNombreCorto = $datosCia['Cia_nombrecorto'];     
        $query = "SELECT LOC_claveint FROM Unidad where Uni_clave=".$uniClave;
        $result = $conexion->query($query);
        $datosCia = $result->fetch();     
        $claveLoc = $datosCia['LOC_claveint'];
        
        $query = "INSERT INTO Cat_Ajustador(AJU_claveint,AJU_clavease,AJU_nombre, AJU_tel, EMP_NombreCorto, EMP_claveint, AJU_activo,LOC_claveint)
                  VALUES(".$cons.",'".$cveAjustador."','".$ajustador."','".$telAjustador."','".$ciaNombreCorto."',".$ciaClave.",1,".$claveLoc.")";

        $statement = $conexion->prepare($query);
        $statement->execute();
        $cveAjustadorMV=$cons;                      
    }

    $query = "select Cia_clave, LOC_claveint, Uni_propia, Unidad.Uni_clave from Expediente 
              inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  
              where Exp_folio='".$fol."'";
    $result=$conexion->query($query);
    $ciaVal = $result->fetch();
    if($ciaVal['Cia_clave']==19){
        $poliza = trim($poliza);
        $cont = strlen($poliza);
        if($cont<10){
            $tope= 10-$cont;    
            for($i=1;$i<=$tope;$i++){
                $poliza='0'.$poliza;      
            }
        }
        if($ciaVal['Uni_clave']==1||$ciaVal['Uni_clave']==2||$ciaVal['Uni_clave']==3||$ciaVal['Uni_clave']==184||$ciaVal['Uni_clave']==8){
            $facExp = 1;    
        }
    }elseif($ciaVal['Uni_propia']=='S'){
        $facExp = 1;    
    }
    if(!$orden){
        $orden='--';
    }


    $sql = "UPDATE Expediente SET Exp_poliza = :Exp_poliza, 
            Exp_siniestro = :Exp_siniestro, 
            Exp_reporte = :Exp_reporte,  
            Exp_RegCompania = :Exp_RegCompania,  
            Exp_obs = :Exp_obs,
            RIE_clave = :RIE_clave,
            Exp_inciso = :Exp_inciso,
            Exp_folPase = :Exp_folPase,
           Exp_cveAjustadorMV= :Exp_cveAjustadorMV,
            Exp_deducible = :Exp_deducible,
            Exp_obsDeducible = :Exp_obsDeducible,
            Exp_orden = :Exp_orden,
            Exp_bitacora = :Exp_bitacora,
            Exp_triageOrigen = 1,
            Exp_triageActual = 1,
            Exp_fecPaseMed = :Exp_fecPaseMed,
            Exp_FE = :Exp_FE,
            Exp_ia = :Exp_ia, /* este campo guarda la autorizaci??n para Atlas Accidente Escolar */
            Exp_estatusReg = 2
            WHERE Exp_folio = :Exp_folio";

            $stmt = $conexion->prepare($sql);                                  
            $stmt->bindParam('Exp_poliza', $poliza);
            $stmt->bindParam('Exp_reporte', $reporte);       
            $stmt->bindParam('Exp_siniestro', $siniestro);    
            $stmt->bindParam('Exp_RegCompania', $noCia);
            // use PARAM_STR although a number  
            $stmt->bindParam('Exp_obs', $obs); 
            $stmt->bindParam('RIE_clave', $cobAfec);   
            $stmt->bindParam('Exp_inciso', $inciso);   
            $stmt->bindParam('Exp_folPase', $folPase);
            $stmt->bindParam('Exp_cveAjustadorMV', $cveAjustadorMV);  
            $stmt->bindParam('Exp_deducible', $deducible);   
            $stmt->bindParam('Exp_obsDeducible', $obsDeducible);
            $stmt->bindParam('Exp_orden', $orden); 
            $stmt->bindParam('Exp_bitacora', $bitacora); 
            $stmt->bindParam('Exp_fecPaseMed', $fecExped);                             
            $stmt->bindParam('Exp_FE', $facExp);
            $stmt->bindParam('Exp_ia', $autAtlasAE);
            $stmt->bindParam('Exp_folio', $fol);               

    if ($stmt->execute()){
        /****************   env??o de correo de registro *********************/
        $sql="select Cia_clave, Unidad.Zon_clave, Usu_registro, Expediente.Uni_clave, Pro_clave from Expediente 
              inner join Unidad on Unidad.uni_clave=Expediente.uni_clave
              where Exp_folio='".$fol."'";

        $result=$conexion->query($sql);
        $datoFol = $result->fetch();

        if($datoFol['Cia_clave']==45 && $datoFol['Pro_clave']==4){
            $primeros=substr($siniestro,0,4);
            if($primeros=='4900'){
              $sql="UPDATE Expediente set Exp_modificador='Autos' WHERE Exp_folio=:Exp_folio";
              $stmt = $conexion->prepare($sql); 
              $stmt->bindParam('Exp_folio', $fol);               
              $stmt->execute();
            }else{
              $sql="UPDATE Expediente set Exp_modificador='GMM' WHERE Exp_folio=:Exp_folio";
              $stmt = $conexion->prepare($sql); 
              $stmt->bindParam('Exp_folio', $fol);               
              $stmt->execute();
            }

        } 


/***************************                                                *************************************/                    
/***************************          inserci??n en la base de datos de Zima *************************************/
/***************************                                                *************************************/
    //if(($datoFol['Cia_clave']==19 && $datoFol['Zon_clave']==6)||($datoFol['Cia_clave']==6)){
    //if(($datoFol['Cia_clave']==19 && $datoFol['Zon_clave']==6)||($datoFol['Cia_clave']==6)){


    $conexionZima = conectarMySQLZima();
    $query="select REG_folioMV from PURegistro where REG_folioMV='".$fol."'";
    $result=$conexionZima->query($query);
    $row = $result->fetch();
    $existe=$row[0];
    if(!$existe){             

    $conexion = conectarMySQL();
    $query="Select Uni_zima, Ase_zima from UnidadMedZim where Uni_mv=".$datoFol['Uni_clave']." and Ase_mv=".$datoFol['Cia_clave']." and activo='S'";               
    $result=$conexion->query($query);
    $uniZima = $result->fetch();
    $uniZ=$uniZima['Uni_zima']; 
    $aseZ=$uniZima['Ase_zima'];                     


    if($uniZ!='' || $uniZ!=null){                

    $query="select Exp_poliza, Exp_siniestro, Exp_reporte, Exp_paterno, Exp_materno, Exp_nombre, Exp_completo, RIE_clave, Exp_RegCompania, Usu_registro, Cia_clave, Exp_ajustador, Exp_cveAjustador, Exp_telAjustador from Expediente where Exp_folio='".$fol."'";
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
        //$orden="Mv";
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
        //           }
        $amb="-";
        $emb="-";
        $usulog="Mv";
        //$rie=-1;
        $veh=-1;
        //$aseZ=10;
 if ($rie==-1){$rie=7;}
    /*$query="Insert into Registro (Reg_folio, UNI_clave, UNI_reporto, ASE_clave, REG_poliza, REG_ajustador, REG_siniestro, REG_reporte, REG_orden, REG_apaterno, REG_amaterno, REG_nombre, REG_fecnac, REG_genero, REG_ocupacion, REG_edad, REG_tel1, REG_tel2, REG_observaciones, REG_carretera, REG_conciente, REG_cinturon, SEI_clave, REG_ambulancia, REG_embarazo, SUP_clave, USU_login, VEH_clave, REG_fechahora, REG_nombrecompleto, RIE_clave,REG_folioelectronico,REG_folioMV)
           values (:Reg_folio, :UNI_clave, :UNI_reporto, :ASE_clave, :REG_poliza, :REG_ajustador, :REG_siniestro, :REG_reporte, :REG_orden, :REG_apaterno, :REG_amaterno, :REG_nombre, :REG_fecnac, :REG_genero, :REG_ocupacion, :REG_edad, :REG_tel1, :REG_tel2,'sdas', :REG_carretera, :REG_conciente, :REG_cinturon, :SEI_clave, :REG_ambulancia, :REG_embarazo, :SUP_clave, :USU_login, :VEH_clave, now(), :REG_nombrecompleto, :RIE_clave,:REG_folioelectronico,:REG_folioMV);";
                  
    $stmt = $conexionZima->prepare($query);                                  
            $stmt->bindParam('Reg_folio', $prefijo);
            $stmt->bindParam('UNI_clave', $uniZ);       
            $stmt->bindParam('UNI_reporto', $reporta);    
            $stmt->bindParam('ASE_clave', $aseZ);        
            $stmt->bindParam('REG_poliza', $pol); 
            $stmt->bindParam('REG_ajustador', $ajustador);   
            $stmt->bindParam('REG_siniestro', $sin);   
            $stmt->bindParam('REG_reporte', $rep);   
            $stmt->bindParam('REG_orden', $orden); 

            $stmt->bindParam('REG_apaterno', $pat);
            $stmt->bindParam('REG_amaterno', $mat);       
            $stmt->bindParam('REG_nombre', $nom);    
            $stmt->bindParam('REG_fecnac', $fecnac);        
            $stmt->bindParam('REG_genero', $genero); 
            $stmt->bindParam('REG_ocupacion', $ocu);   
            $stmt->bindParam('REG_edad', $txtedad);   
            $stmt->bindParam('REG_tel1', $tel1);   
            $stmt->bindParam('REG_tel2', $tel2); 
           
            $stmt->bindParam('REG_carretera', $carr);       
            $stmt->bindParam('REG_conciente', $conc);    
            $stmt->bindParam('REG_cinturon', $cin);        
            $stmt->bindParam('SEI_clave', $sei); 
            $stmt->bindParam('REG_ambulancia', $amb);   
            $stmt->bindParam('REG_embarazo', $emb);   
            $stmt->bindParam('SUP_clave', $sup);   
            $stmt->bindParam('USU_login', $usulog); 

            $stmt->bindParam('VEH_clave', $veh);        
            $stmt->bindParam('REG_nombrecompleto', $nomc);    
            $stmt->bindParam('RIE_clave', $rie);        
            $stmt->bindParam('REG_folioelectronico', $RegCia); 
            $stmt->bindParam('REG_folioMV', $fol);
            //$stmt->execute();
     
            if ($stmt->execute()){ */
                                     
                $conexionZima = conectarMySQLZima();
                
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
                                values (".$id.",'".$folioZ."',".$uniZ.", '".$reporta."', ".$aseZ.",'".$pol."','".$ajustador."','".$sin."','".$rep."','".$orden."','".$pat."','".$mat."','".$nom."','".$fecnac."','".$genero."','".$ocu."','".$txtedad."','".$tel1."','".$tel2."','".$obs."','".$carr."','".$conc."','".$cin."','11','".$amb."','".$emb."','".$sup."','".$usulog."','PMVautomatico', '".$veh."', now(),'".$nomc."',".$rie.",'".$RegCia."', '".$fol."',8,'".$cveAjustador."','".$telAjustador."','".$legible."');");
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

               // }
            }            
        }
   // }
/***************************                                                *************************************/                    
/***************************      Fin inserci??n en la base de datos de Zima *************************************/
/***************************                                                *************************************/

        /*$sql="  select Exp_folio, Exp_completo, Exp_fecreg, USU_registro, Exp_poliza, Exp_siniestro, Exp_reporte, Exp_RegCompania,Exp_obs, Unidad.Uni_nombrecorto, Compania.Cia_nombrecorto
                from Expediente 
                inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                where Exp_folio='".$fol."'";
        $result = $conexion->query($sql);
        $datosFolio = $result->fetch();

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
                                        <th colspan="6" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES DE REGISTRO
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$datosFolio['Exp_folio'].'</b>
                                        </td>
                                        <td colspan="2" style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.utf8_encode($datosFolio['Exp_completo']).'</b>
                                        </td>
                                        <td colspan="3" style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Unidad: <b>'.utf8_encode($datosFolio['Cia_nombrecorto']).'</b>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            P??liza: <b>'.$datosFolio['Exp_poliza'].'</b>
                                        </td>
                                        <td colspan="2">
                                            Reporte: <b>'.$datosFolio['Exp_reporte'].'</b>
                                        </td>
                                        <td colspan="3">
                                            Siniestro: <b>'.$datosFolio['Exp_siniestro'].'</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Observaciones: <b>'.$datosFolio['Exp_obs'].'</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

        //$mimemail->set_from("logistica_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("enriqueerick@gmail.com");
        //$mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- Registro -".$datosFolio['Uni_nombrecorto']);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
           
        }else {
            $respuesta = array('respuesta' =>'','folio'=>'');
        }*/
        /****************   fin de envio de correo      *********************/
         $respuesta = array('respuesta' =>'correcto','folio'=>$fol);
    }else{
        $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }
    echo json_encode($respuesta);

    $conexion = null;    
}


if($funcion == 'registraPart'){
    $fol=$_GET['fol'];
    $mimemail = new nomad_mimemail();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
    $conexion = conectarMySQL();
    $obs = $datos->observacion;
    $como = $datos->entero;
    $cualTar = $datos->cualTar;
    $quienEnvTar = $datos->quienEnvTar;
    $folioVale = $datos->folioVale;
    $cualPag  = $datos->cualPag;
    $quienEnvVal = $datos->quienEnvVal;

    $tipoRef    = $datos->tipoRef;
    $idMedico   = $datos->idMedico;
    $idOtras    = $datos->idOtras;

    $sql="INSERT INTO ObsParticulares(Exp_folio, 
                                      Par_tipo, 
                                      Par_tipoNom, 
                                      Par_fecreg,
                                      Par_entero, 
                                      Par_tarjetaCual,
                                      Par_quienEnvTar, 
                                      Par_pag, 
                                      Par_folioVale, 
                                      Par_quienEnvVal,
                                      Par_referenciaTipo,
                                      Par_medicoID,
                                      Par_otroID)
                            VALUES('".$fol."',
                                    1,
                                    'individual',
                                    now(),
                                    '".$como."',
                                    '".$cualTar."',
                                    '".$quienEnvTar."',
                                    '".$cualPag."',
                                    '".$folioVale."',
                                    '".$quienEnvVal."',
                                    '".$tipoRef."',
                                    '".$idMedico."',
                                    '".$idOtras."')";

/*
    $refNombre   = $datos->refNombre;
    $refCedula   = $datos->refCedula;
    $sql="INSERT INTO ObsParticulares(Exp_folio, Par_tipo, Par_tipoNom, Par_fecreg,Par_entero, Par_tarjetaCual,Par_quienEnvTar, Par_pag, Par_folioVale, Par_quienEnvVal,Par_refNombre, Par_refCedula)
          VALUES('".$fol."',1,'individual',now(),'".$como."','".$cualTar."','".$quienEnvTar."','".$cualPag."','".$folioVale."','".$quienEnvVal."','".$refNombre."','".$refCedula."')";
*/
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    $sql = "UPDATE Expediente SET 
            Exp_obs = :Exp_obs,
            ParMot = :ParMot,
            Exp_estatusReg = 2 /* INDICA QUE EL REGISTRO FUE COMPLETADO */
            WHERE Exp_folio = :Exp_folio";

            $stmt = $conexion->prepare($sql);                                                          
            $stmt->bindParam('Exp_obs', $obs);             
            $stmt->bindParam('Exp_folio', $fol); 
            $stmt->bindParam('ParMot', $como);                 
    if ($stmt->execute()){
         /****************   env??o de correo de registro *********************/

        $sql="  select Exp_folio, Exp_completo, Exp_fecreg, USU_registro,Exp_obs, Unidad.Uni_nombrecorto, Compania.Cia_nombrecorto
                from Expediente 
                inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                where Exp_folio='".$fol."'";
        $result = $conexion->query($sql);
        $datosFolio = $result->fetch();

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
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES DE REGISTRO
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$datosFolio['Exp_folio'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.utf8_decode($datosFolio['Exp_completo']).'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Compa??ia: <b>'.utf8_decode($datosFolio['Cia_nombrecorto']).'</b>
                                        </td>

                                    </tr>                                    
                                    <tr>
                                        <td colspan="3">
                                            Observaciones: <b>'.utf8_decode($datosFolio['Exp_obs']).'</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("logistica_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("chernandez@medicavial.com.mx");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- Registro de Particular - ".$datosFolio['Uni_nombrecorto']);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
           
        }else {
            $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }
        /****************   fin de envio de correo      *********************/
    }else{
        $respuesta = array('respuesta' =>'incorrecto');
    }
    echo json_encode($respuesta);

    $conexion = null;    
}

if($funcion == 'registraEmpleado'){
    $fol=$_GET['fol'];
    $mimemail = new nomad_mimemail();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
    $conexion = conectarMySQL();
    $noEmp = $datos->noEmp;
    $area = $datos->area;
    $jefeInm = $datos->jefeInm;
    $obs = $datos->obs;

    $sql = "INSERT INTO ObsParticulares(Exp_folio, Par_tipo, Par_tipoNom, Par_noEmpleado, Par_area, Par_jefeInm, Par_fecreg)
                values(:Exp_folio,3,'empleado', :Par_noEmpleado, :Par_area, :Par_jefeInm,now())"; 
            $stmt = $conexion->prepare($sql);                                                          
            $stmt->bindParam('Exp_folio', $fol);             
            $stmt->bindParam('Par_noEmpleado', $noEmp);               
            $stmt->bindParam('Par_area', $area);
            $stmt->bindParam('Par_jefeInm', $jefeInm);
            $stmt->execute();

    $sql = "UPDATE Expediente SET 
            Exp_obs = :Exp_obs,
            Exp_estatusReg = 2 /* indica que el registro esta completo */
            WHERE Exp_folio = :Exp_folio";

            $stmt = $conexion->prepare($sql);                                                          
            $stmt->bindParam('Exp_obs', $obs);             
            $stmt->bindParam('Exp_folio', $fol);                          
    if ($stmt->execute()){
         /****************   env??o de correo de registro *********************/

        $sql="  select Exp_folio, Exp_completo, Exp_fecreg, USU_registro,Exp_obs, Unidad.Uni_nombrecorto, Compania.Cia_nombrecorto
                from Expediente 
                inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                where Exp_folio='".$fol."'";
        $result = $conexion->query($sql);
        $datosFolio = $result->fetch();

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
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES DE REGISTRO
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$datosFolio['Exp_folio'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.utf8_decode($datosFolio['Exp_completo']).'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Compa&ntilde;ia: <b>'.$datosFolio['Cia_nombrecorto'].'</b>
                                        </td>

                                    </tr>                                    
                                    <tr>
                                        <td colspan="3">
                                            Observaciones: <b>'.utf8_decode($datosFolio['Exp_obs']).'</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("logistica_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("jsanchez@medicavial.com.mx");
        $mimemail->add_cc("chernandez@medicavial.com.mx");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- Registro de Empleado Medica Vial - ".$datosFolio['Uni_nombrecorto']);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
           
        }else {
            $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }
        /****************   fin de envio de correo      *********************/
    }else{
        $respuesta = array('respuesta' =>'incorrecto');
    }
    echo json_encode($respuesta);

    $conexion = null;    
}


if($funcion == 'registraConvenio'){
    $fol=$_GET['fol'];
    $convenio = $_GET['convenio'];
    $mimemail = new nomad_mimemail();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
    $conexion = conectarMySQL();
    $obs = $datos->obs;
    $refNombre = $datos->refNombre;
    $refCedula = $datos->refCedula;
    $noSesio   = $datos->noSesiones;

    $sql="  select count(*) as contador
                from ObsParticulares                
                where Exp_folio='".$fol."'";
    $result = $conexion->query($sql);
    $datosFolio = $result->fetch();
    $contador = $datosFolio['contador'];

    if($contador==0){

        $sql = "INSERT INTO ObsParticulares(Exp_folio, Par_tipo, Par_tipoNom, Par_fecreg, Par_convenio,Par_refNombre, Par_refCedula, Par_noSesiones)
                    values(:Exp_folio,4,'convenio',now(),:Par_convenio,:Par_refNombre, :Par_refCedula, :Par_noSesiones)"; 
                $stmt = $conexion->prepare($sql);                                                          
                $stmt->bindParam('Exp_folio', $fol);
                $stmt->bindParam('Par_convenio', $convenio); 
                $stmt->bindParam('Par_refNombre', $refNombre);                        
                $stmt->bindParam('Par_refCedula', $refCedula);                       
                $stmt->bindParam('Par_noSesiones', $noSesio);                       
                $stmt->execute();

        $sql = "UPDATE Expediente SET 
                Exp_obs = :Exp_obs,
                Exp_estatusReg = 2 /* indica que el registro esta completo */
                WHERE Exp_folio = :Exp_folio";
                $stmt = $conexion->prepare($sql);                                                          
                $stmt->bindParam('Exp_obs', $obs);                      
                $stmt->bindParam('Exp_folio', $fol);
    }                          
    if ($stmt->execute()){
         /****************   env??o de correo de registro *********************/
         switch ($convenio) {
            case '1':
                 $stringConvenio='Convenio tarjeta Inbursa';
                 break;
            case '2':
                 $stringConvenio='Convenio Grupo Bimbo';
                 break;
            case '3':
                 $stringConvenio='Membres??a M??dicaVial';
                 break;
            case '4':
                 $stringConvenio='Convenio Desclub (BBVA Bancomer)';
                 break;
            case '5':
                 $stringConvenio='Convenio MetLife';
                 break;
            case '6':
                 $stringConvenio='Convenio Peri??dico El asegurador';
                 break;
            case '7':
                 $stringConvenio='Convenio Hapag Lioyd';
                 break;
            case '8':
                 $stringConvenio='Convenio Kek??n';
                 break;
            case '13':
                 $stringConvenio='Convenio ASISS';
                 break;
             case '15':
                 $stringConvenio='AXA CUIDADOS ESPECIALES';
                 break;
         }
        $sql="  select Exp_folio, Exp_completo, Exp_fecreg, USU_registro,Exp_obs, Unidad.Uni_nombrecorto, Compania.Cia_nombrecorto
                from Expediente 
                inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                where Exp_folio='".$fol."'";
        $result = $conexion->query($sql);
        $datosFolio = $result->fetch();

        if($convenio==15){

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
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            REGISTRO AXA CUIDADOS ESPECIALES
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$datosFolio['Exp_folio'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.$datosFolio['Exp_completo'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Compa&ntilde;ia: <b>'.$datosFolio['Cia_nombrecorto'].'</b>
                                        </td>

                                    </tr>                                    
                                    <tr>
                                        <td colspan="3">
                                            Observaciones: <b>'.$datosFolio['Exp_obs'].'</b>
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            No. de sesiones: <b>'.$noSesio.'</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("logistica_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("saulhernandez@medicavial.com.mx");
        $mimemail->add_cc("scisneros@medicavial.com.mx");
        $mimemail->add_cc("chernandez@medicavial.com.mx");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- Registro de Convenio M??dicaVial - ".$datosFolio['Uni_nombrecorto'].' - '.$stringConvenio);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
           
        }else {
            $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }

        }else{

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
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES DE REGISTRO
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$datosFolio['Exp_folio'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.$datosFolio['Exp_completo'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Compa&ntilde;ia: <b>'.$datosFolio['Cia_nombrecorto'].'</b>
                                        </td>

                                    </tr>                                    
                                    <tr>
                                        <td colspan="3">
                                            Observaciones: <b>'.$datosFolio['Exp_obs'].'</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("logistica_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("chernandez@medicavial.com.mx");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- Registro de Convenio M??dicaVial - ".$datosFolio['Uni_nombrecorto'].' - '.$stringConvenio);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
           
        }else {
            $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }

    }
        /****************   fin de envio de correo      *********************/
    }else{
        $respuesta = array('respuesta' =>'incorrecto');
    }
    echo json_encode($respuesta);

    $conexion = null;    
}


if($funcion == 'registraCort'){
    $fol=$_GET['fol'];
    $mimemail = new nomad_mimemail();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
    $conexion = conectarMySQL();
    $obs = $datos->obs;
    $quien=$datos->autoriza;
    $quienmanda= $datos->manda;
    if (!$quienmanda) {
        $quienmanda='0';
    }
    $nombre='';
    $nombre1='';
    $correo='';
    switch ($quien) {
        case '1':
            $nombre='Jos&eacute; Abraham S&aacute;nchez';
            $nombre1='Jos?? Abraham S??nchez';
            $correo='jabraham@medicavial.com.mx';
            //$correo='egutierrez@medicavial.com.mx';
            break;
        case '2':
            $nombre='Jos&eacute; S&aacute;nchez';
            $nombre1='Jos?? S??nchez';
            $correo='jsanchez@medicavial.com.mx';
            break;

        case '3':
            $nombre='Xavier S&aacute;nchez';
            $nombre1='Xavier S??nchez';
            $correo='xsanchez@medicavial.com.mx';
            break;

        
    }
    switch ($quienmanda) {
         case '1':
            $nombreManda='Jos&eacute; Abraham S&aacute;nchez';
            //$correoManda='jabraham@medicavial.com.mx';
            $correoManda='egutierrez@medicavial.com.mx';
            break;
        case '2':
            $nombreManda='Jos&eacute; S&aacute;nchez';
            $correoManda='jsanchez@medicavial.com.mx';
            break;
        case '3':
            $nombreManda='Xavier S&aacute;nchez';
            $correoManda='xsanchez@medicavial.com.mx';
            break;
        case '4':
            $nombreManda='Alma Martinez';
            $correoManda='amartinez@medicavial.com.mx';
            break;
        case '5':
            $nombreManda='Alfredo Guerrero';
            $correoManda='aguerrero@medicavial.com.mx';
            break;

        case '6':
            $nombreManda='Alfredo Guti&eacute;rrez';
            $correoManda='agutierrez@medicavial.com.mx';
            break;
        case '7':
            $nombreManda='Sergio Cisneros';
            $correoManda='scisneros@medicavial.com.mx';
            break;

        case '8':
            $nombreManda='Sergio Romero';
            $correoManda='sromero@medicavial.com.mx';
            break;

        case '9':
            $nombreManda='Hugo Zarich';
            $correoManda='hzarich@medicavial.com.mx';
            break;
        case '10':
            $nombreManda='Enrique P&eacute;rez G&aacute;rate';
            $correoManda='perezgarate@yahoo.com';
            break;

        case '11':
            $nombreManda='Alejandra Meade';
            $correoManda='ameade@multiplicamexico.com';
            break;

        case '12':
            $nombreManda='Salvador Aurrecoechea';
            $correoManda='sam@medicavial.com.mx';
            break;        
    }

    $complemento = "Autoriza ".$nombre1.". Observaciones: ".$obs;


    $sql = "UPDATE Expediente SET 
            Exp_obs = :Exp_obs,
            CortId = :CortId,
            CortAcep = :CortAcep,
            Exp_estatusReg = 2 /* indica que el registro esta completo */
            WHERE Exp_folio = :Exp_folio";

            $stmt = $conexion->prepare($sql);                                                          
            $stmt->bindParam('Exp_obs', $complemento);             
            $stmt->bindParam('Exp_folio', $fol);
            $stmt->bindParam('CortId', $quien);                
            $stmt->bindParam('CortAcep', $quienmanda);                
    if ($stmt->execute()){
         /****************   env??o de correo de registro *********************/

        $sql="  select Exp_folio, Exp_completo, Exp_fecreg, USU_registro,Exp_obs, Unidad.Uni_nombrecorto, Compania.Cia_nombrecorto
                from Expediente 
                inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                where Exp_folio='".$fol."'";
        $result = $conexion->query($sql);
        $datosFolio = $result->fetch();

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
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES DE REGISTRO
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$datosFolio['Exp_folio'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.$datosFolio['Exp_completo'].'</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Unidad: <b>'.utf8_decode($datosFolio['Uni_nombrecorto']).'</b>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            Autoriza: <b>'.utf8_decode($nombre).'</b>
                                        </td>                                        
                                    </tr>                                        
                                    <tr>
                                        <td colspan="3">
                                            Acepta: <b>'.utf8_decode($nombreManda).'</b>
                                        </td>                                        
                                    </tr>   
                                    <tr>
                                        <td colspan="3">
                                            Observaciones: <b>'.utf8_decode($obs).'</b>
                                        </td>                                        
                                    </tr>                                                                                                         
                                </table>                                
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("logistica_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to($correo);
        $mimemail->add_cc($correoManda);
        $mimemail->add_cc("jsanchez@medicavial.com.mx");
        $mimemail->add_cc("chernandez@medicavial.com.mx");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- Registro de Cortes??a - ".$datosFolio['Uni_nombrecorto']);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
           
        }else {
            $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }
        /****************   fin de envio de correo      *********************/
    }else{
        $respuesta = array('respuesta' =>'incorrecto');
    }
    echo json_encode($respuesta);

    $conexion = null;    
}

if($funcion == 'listaTelefonos'){
    $folio=$_GET['folio'];
    $db = conectarMySQL();
    $sql= "SELECT TT_tipotelefono, Tel_cont, Tel_numero from TelefonosLesionado 
    inner join TipoTelefono on TelefonosLesionado.Tel_tipo=TipoTelefono.TT_clave
    where Exp_folio='".$folio."'";    
     $result = $db->query($sql);
    $datosFolio = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($datosFolio);
    $db = null;        
}

if($funcion == 'tarjetaDoc'){
    $folio=$_GET['fol'];
    $recibo=$_GET['recibo'];
    $db = conectarMySQL();
    $sql= "select Recibo_banco,Recibo_terminacion from reciboParticulares where Exp_folio='".$folio."' and Recibo_cont=".$recibo;    
    $result = $db->query($sql);
    $datosFolio = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datosFolio);
    $db = null;        
}

if($funcion == 'pruebaFuncion'){
    $folio=$_GET['fol'];
    $recibo=$_GET['recibo'];
    $db = conectarMySQL();
    $sql= "select Recibo_banco,Recibo_terminacion from reciboParticulares where Exp_folio='".$folio."' and Recibo_cont=".$recibo;    
    $result = $db->query($sql);
    $datosFolio = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datosFolio);
    $db = null; 
}

if($funcion == 'detalleCortesia'){
    $folio=$_GET['folio'];
    $db = conectarMySQL();
    $sql= "select CortId,CortAcep from Expediente where Exp_folio='".$folio."'";    
     $result = $db->query($sql);
    $datosFolio = $result->fetch();
    $quien=$datosFolio['CortId'];
    $quienmanda=$datosFolio['CortAcep'];

    switch ($quien) {
        case '1':           
            $nombre1='Jos?? Abraham S??nchez';                      
            break;
        case '2':           
            $nombre1='Jos?? S??nchez';          
            break;

        case '3':           
            $nombre1='Xavier S??nchez';          
            break;        
    }

    switch ($quienmanda) {
         case '1':
            $nombreManda='Jos??; Abraham S??nchez';
            //$correoManda='jabraham@medicavial.com.mx';
            $correoManda='egutierrez@medicavial.com.mx';
            break;
        case '2':
            $nombreManda='Jos??; S??nchez';
            $correoManda='jsanchez@medicavial.com.mx';
            break;
        case '3':
            $nombreManda='Xavier S??nchez';
            $nombreManda='xsanchez@medicavial.com.mx';
            break;
        case '4':
            $nombreManda='Alma Martinez';
            $correo='amartinez@medicavial.com.mx';
            break;
        case '5':
            $nombreManda='Alfredo Guerrero';
            $correoManda='aguerrero@medicavial.com.mx';
            break;

        case '6':
            $nombreManda='Alfredo Guti??rrez';
            $correoManda='agutierrez@medicavial.com.mx';
            break;
        case '7':
            $nombreManda='Sergio Cisneros';
            $correoManda='scisneros@medicavial.com.mx';
            break;

        case '8':
            $nombreManda='Sergio Romero';
            $correoManda='sromero@medicavial.com.mx';
            break;

        case '9':
            $nombreManda='Hugo Zarich';
            $correoManda='hzarich@medicavial.com.mx';
            break;
        case '10':
            $nombreManda='Enrique P??rez G??rate';
            $correoManda='perezgarate@yahoo.com';
            break;

        case '11':
            $nombreManda='Alejandra Meade';
            $correoManda='ameade@multiplicamexico.com';
            break;

        case '12':
            $nombreManda='Salvador Aurrecoechea';
            $correoManda='sam@medicavial.com.mx';
            break;        
    }

    $respuesta=  array('autoriza' => $nombre1, 'envia'=> $nombreManda);


    echo json_encode($respuesta);
    $db = null;        
}



/****************    envio de comentario a RH en directo *********************************/

if($funcion == 'enviaComentarioRH'){    
    $usr    =$_GET['usr'];    
    $mimemail = new nomad_mimemail();
    
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    //$db = conectarMySQL();
    $comentario = $datos->comentario;
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');
    $conexion = conectarMySQL();
    try{
     $sql=" SELECT Usu_nombre, Uni_nombrecorto from Usuario 
            inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave
            where Usuario.Usu_login='".$usr."'";
        $result = $conexion->query($sql);
        $datosFolio = $result->fetch();

}catch(Exception $e){
        $respuesta=  array('respuesta' => $e->getMessage() );
    }   
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
                                        <td  align="center" style=" width: 100%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Mensaje enviado desde en Directo RH por <b>'.$datosFolio['Usu_nombre'].'</b> ('.$usr.') de la cl&iacute;nica <b>'.$datosFolio['Uni_nombrecorto'].'</b> el d&iacute;a <b>'.$fecha.'</b> a las <b>'.$hora.'</b>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 100%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Mensaje: <b>'.utf8_decode($comentario).'</b>
                                        </td>                                        
                                    </tr>                                                                                                        
                                </table>
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("enDirecto_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("endirecto@medicavial.com.mx");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("- RH en Directo - ".$datosFolio['Uni_nombrecorto']." - ".$usr);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            $respuesta = array('respuesta' =>'correcto');       
           
        }else {
            $respuesta = array('respuesta' =>'incorrecto');
        }
        /****************   fin de envio de correo      *********************/
    echo json_encode($respuesta); 
    $conexion = null;   
}

/************************ fin de envio de comentarios ************************************/

/*************************      Guardar aviso RH            ******************************/
if($funcion == 'guardaAvisoRH'){    


    $titulo=$_POST['titulo'];
    $textCorto=$_POST['textCorto'];
    $temporal=$_POST['temporal'];
    $temporal1=$_POST['temporal1'];
    $archivo=$_POST['archivo'];
    $archivo1=$_POST['archivo1'];

    $usr=$_GET['usr'];

    $db = conectarMySQL();
    if (!$_FILES['file']) {
        $query="SELECT Max(Aviso_id)+1 as clave FROM Avisos";
        $result = $db->query($query);
        $rs = $result->fetch();
        $clave=$rs['clave'];
        if($clave==''){
            $clave=1;
        }
        $query="INSERT INTO Avisos(Aviso_id, Aviso_titulo, Aviso_textoCorto, Aviso_rutaPDF, Aviso_rutaAdjunto, Aviso_fecSub, Aviso_activo)
                   VALUES(:Aviso_id, :Aviso_titulo, :Aviso_textoCorto, :Aviso_rutaPDF, :Aviso_rutaAdjunto, now(), 1)";

        $rutaPDF='';
        $stmt = $db->prepare($query);
        $stmt->bindParam('Aviso_id', $clave);                                  
        $stmt->bindParam('Aviso_titulo', $titulo);
        $stmt->bindParam('Aviso_textoCorto', $textCorto);                                  
        $stmt->bindParam('Aviso_rutaPDF', $rutaPDF);
        $stmt->bindParam('Aviso_rutaAdjunto', $archivo1);                                                     
                                                  
        if ($stmt->execute()){                            
        $respuesta = array('respuesta' => 'exito');
        echo json_encode($respuesta);
        $db = null;
        }else{
        $respuesta = array('respuesta' => 'error');
        echo json_encode($respuesta);
        }                        
        
    }else{
    if ($_FILES['file']["error"] > 0){
        $respuesta = array('error'=>'si'); 
    }else{

    $ano=date("Y");
    $mes=date("m");               
        switch ($mes){
                 case '01':
                 $mesd="January";//Enero
                 break;

                 case '02':
                 $mesd="February";//Febrero
                 break;

                 case '03':
                 $mesd="March";//Marzo
                 break;

                 case '04':
                 $mesd="April";//Abril
                 break;

                 case '05':
                 $mesd="May";//Mayo
                 break;

                 case '06':
                 $mesd="June";//Junio
                 break;

                 case '07':
                 $mesd="July";//Julio
                 break;

                 case '08':
                 $mesd="August";//Agosto
                 break;

                 case '09':
                 $mesd="September";//Septiembre
                 break;

                 case '10':
                 $mesd="October";//Octubre
                 break;

                 case '11':
                 $mesd="November";//Noviembre
                 break;

                 case '12':
                 $mesd="December";//Diciembre
                 break;
             }

            $direc="Avisos/".$ano."/".$mesd; 
            $direc1="Avisos/".$ano."/".$mesd;

            if ($_FILES["file"]["size"] < 750000){
                if ($_FILES["file"]["error"] > 0){
                    $respuesta = array('respuesta' => 'error');
                    echo json_encode($respuesta);
              }else{
                if($_FILES["file"]["type"] == "application/pdf")
                {    
                  if (file_exists($direc."/".$_FILES["file"]["name"]))
                  {
                    $respuesta = array('respuesta' => 'doble');
                    echo json_encode($respuesta);
                  }
                  else
                  {        
                    $_dir= is_dir("Avisos");        
                    if($_dir==1){                        
                      $_dir1= is_dir("Avisos/".$ano);                
                    if($_dir1==1){
                      $_dir2= is_dir("Avisos/".$ano."/".$mesd);                                                                                      
                    }else{
                      mkdir("Avisos/".$ano);
                      mkdir("Avisos/".$ano."/".$mesd);                      
                    }                
                    }else{
                      mkdir("Avisos");
                      mkdir("Avisos/".$ano);
                      mkdir("Avisos/".$ano."/".$mesd);                
                    }                                                                             

                    $query="SELECT Max(Aviso_id)+1 as clave FROM Avisos";
                    $result = $db->query($query);
                    $rs = $result->fetch();
                    $clave=$rs['clave'];
                    if($clave==''){
                        $clave=1;
                    }



                    $query="INSERT INTO Avisos(Aviso_id, Aviso_titulo, Aviso_textoCorto, Aviso_rutaPDF, Aviso_rutaAdjunto, Aviso_fecSub, Aviso_activo)
                                       VALUES(:Aviso_id, :Aviso_titulo, :Aviso_textoCorto, :Aviso_rutaPDF, :Aviso_rutaAdjunto, now(), 1)";
                    $partes=explode(".",$_FILES["file"]["name"]); 
                    $rutaPDF=$ano.'/'.$mesd.'/Aviso_'.$clave.".".$partes[1];
                    $stmt = $db->prepare($query);
                    $stmt->bindParam('Aviso_id', $clave);                                  
                    $stmt->bindParam('Aviso_titulo', $titulo);
                    $stmt->bindParam('Aviso_textoCorto', $textCorto);                                  
                    $stmt->bindParam('Aviso_rutaPDF', $rutaPDF);
                    $stmt->bindParam('Aviso_rutaAdjunto', $archivo1);                                                     
                                                                                                                                                                
                        move_uploaded_file($_FILES["file"]["tmp_name"], $direc."/Aviso_".$clave.".".$partes[1]);                                
                    
                        $ruta1= $direc1."/".$partes[1];
                        $ruta= $direc."/".$partes[1];
                        $fecha = date("Y-m-d H:i:s");                            
                                                                      
                         if ($stmt->execute()){                            
                            $respuesta = array('respuesta' => 'exito');
                            echo json_encode($respuesta);
                            $db = null;
                        }else{
                            $respuesta = array('respuesta' => 'error');
                            echo json_encode($respuesta);
                        }                        
                
                  }
                }else{
                $respuesta = array('respuesta' => 'errorTipo');
                echo json_encode($respuesta);
                }// tipo de archivo
              }/// Si no hay error busca el directori
            }/// if Mide tamano de archivo
            else
            {
              $respuesta = array('respuesta' => 'error', 'tamanio' => $_FILES["file"]["size"]);
                echo json_encode($respuesta);
            }
   
    }
}
$db=null;
}

if($funcion == 'listaAvisos'){    
    $db = conectarMySQL();
    $sql= "SELECT * from Avisos where Aviso_activo=1 order by Aviso_fecSub desc";
    $result = $db->query($sql);
    $datosFolio = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($datosFolio);
    $db = null;    
}

/************************************          fin subir aviso ******************************************/


if($funcion == 'getFolio'){
    $folio=$_GET['folio'];
    $db = conectarMySQL();
    $sql= "SELECT Cia_clave from Expediente where Exp_folio='".$folio."'";
    $result = $db->query($sql);
    $cia = $result->fetch();

    if($cia['Cia_clave']==51||$cia['Cia_clave']==44||$cia['Cia_clave']==53||$cia['Cia_clave']==54){
       $sql = "Select Exp_folio,Expediente.Uni_claveActual, Exp_nombre, Exp_paterno, Exp_materno, Expediente.Cia_clave, Usu_registro, Exp_fecreg, Exp_obs, Uni_nombre, Uni_propia, Cia_nombrecorto,
            Pro_clave, CortId, CortAcep
            From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave            
            where Exp_cancelado=0 and Exp_folio='".$folio."';";
    }else{
        
         $sql = "Select Exp_folio,Expediente.Uni_claveActual, Exp_nombre, Exp_paterno, Exp_materno, Exp_siniestro, Exp_poliza, Exp_reporte, Exp_fecreg, Expediente.Cia_clave, Usu_registro, Exp_fecreg, USU_registro, Exp_obs, Uni_nombre, Uni_propia, Cia_nombrecorto, RIE_nombre, Exp_RegCompania,
            Pro_clave
            From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
            inner join RiesgoAfectado on Expediente.RIE_clave=RiesgoAfectado.RIE_clave
            where Exp_cancelado=0 and Exp_folio='".$folio."';";    
    }
    $result = $db->query($sql);
    $datosFolio = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datosFolio);
    $db = null;    
}

if($funcion == 'listadoFolios'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $uni = $_GET['uni'];
    $query="Select Exp_folio, Cia_nombrecorto,Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$uni." order by Exp_fecreg desc LIMIT 0 , 30";
    $result = $db->query($query);
    $datosFolio = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($datosFolio);
    $db = null;    
}

if($funcion == 'buscaParametros'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $db = conectarMySQL();
    $nombre = $datos->nombre;
    $folio = $datos->folio;
    $cveUnidad= $_GET['cveUnidad'];
    $db = conectarMySQL();

    if (empty($_GET['cveUnidad']) || $_GET['cveUnidad'] == 'null') {

        if($nombre && ($folio==''||$folio==null)){
            $query="Select Exp_folio, Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Exp_completo like '%".$nombre."%'";
        }
        elseif ($nombre && $folio) {
            $query="Select Exp_folio, Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where  Exp_folio like '%".$folio."%' and Exp_completo like '%".$nombre."%'";
        }
        elseif ($folio &&($nombre==''||$nombre==null)) {
           $query="Select Exp_folio, Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Exp_folio like '%".$folio."%'";
        }

       
    }else{

        if($nombre && ($folio==''||$folio==null)){
            $query="Select Exp_folio, Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad." and Exp_completo like '%".$nombre."%'";
        }
        elseif ($nombre && $folio) {
            $query="Select Exp_folio, Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad." and  Exp_folio like '%".$folio."%' and Exp_completo like '%".$nombre."%'";
        }
        elseif ($folio &&($nombre==''||$nombre==null)) {
           $query="Select Exp_folio, Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad." and Exp_folio like '%".$folio."%'";
        }
        
        
    }

    $result = $db->query($query);
    $datosFolio = $result->fetchAll(PDO::FETCH_OBJ);
    if(empty($datosFolio)){
        $datosFolio= array('respuesta' =>'error');
    }

    echo json_encode($datosFolio);
    $db = null;
       
}

if($funcion == 'getDatosPaciente'){
    $folio = $_GET['folio'];
    $db = conectarMySQL();
    $query="Select Exp_folio, Exp_paterno, Exp_materno, Exp_nombre, Exp_fechaNac,Exp_edad,Exp_meses, Exp_sexo, Ocu_clave,Edo_clave,Exp_telefono,Exp_mail, Rel_clave From Expediente  Where Exp_folio='".$folio."'";
    $result = $db->query($query);
    $datosPaciente = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datosPaciente);
    $db = null;    
}
if($funcion == 'getDatosPacienteRe'){
    $folio = $_GET['folio'];
    $db = conectarMySQL();
    $query="SELECT Exp_nombre, Exp_paterno, Expediente.Cia_clave, Exp_materno, Cia_nombrecorto, Exp_siniestro, Exp_reporte, Exp_poliza, Exp_telefono, Exp_mail, Exp_fechaNac, Exp_edad, Exp_meses, Exp_sexo, Ocu_clave, Edo_clave, Rel_clave, Exp_fecreg FROM Expediente Inner Join Compania On Expediente.Cia_clave=Compania.Cia_clave WHERE Exp_cancelado=0 and Exp_folio='".$folio."'";
    $result = $db->query($query);
    $datosPaciente = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($datosPaciente);
    $db = null;    
}

if($funcion == 'datosAcc'){
    $folio = $_GET['folio'];
    $db = conectarMySQL();
    $query="Select Not_fechaAcc,Not_fechareg,Usu_nombre FROM NotaMedica Where Exp_folio='".$folio."'";
    $result = $db->query($query);
    $datosPaciente = $result->fetch();

    $fecAcc=$datosPaciente['Not_fechaAcc'];
    $fecReg=$datosPaciente['Not_fechareg'];
    $Usuario=$datosPaciente['Usu_nombre'];
    $query="Select Med_nombre,Med_paterno,Med_materno FROM Medico Where Usu_login='".$Usuario."'";
    $result = $db->query($query);
    $datosDoc = $result->fetch();
    $nombre = $datosDoc['Med_nombre'].' '.$datosDoc['Med_paterno'].' '.$datosDoc['Med_materno'];
    $query="Select ObsNot_diagnosticoRx from ObsNotaMed where Exp_folio='".$folio."'";
    $result = $db->query($query);
    $datosDiag = $result->fetch();
    $diagn=$datosDiag['ObsNot_diagnosticoRx'];
    $datos = array('Not_fechaAcc' => $fecAcc, 'Not_fechareg'=>$fecReg,'Med_nombre'=>$nombre,'ObsNot_diagnosticoRx'=>$diagn);

    echo json_encode($datos);
    $db = null;    
}

if($funcion == 'datosExp'){
    $folio = $_GET['folio'];
    $db = conectarMySQL();
    $query="Select Not_clave From NotaMedica Where Exp_folio='".$folio."'";
    $result = $db->query($query);
    $datosPaciente = $result->fetch();
    $nota=$datosPaciente['Not_clave'];    
    if($nota){
        $not='SI';
    }else{$not='NO';}
    $query="SELECT Sub_cons FROM Subsecuencia where Exp_folio='".$folio."'";
    $result = $db->query($query);
    $datosSubs = $result->fetchAll(PDO::FETCH_OBJ);
    $datos = array('nota' => $not, 'Subs'=>$datosSubs);
    echo json_encode($datos);
    $db = null;    
}

if($funcion == 'particular'){
    $fol=$_GET['folio'];
    $db = conectarMySQL();
    $query="SELECT Cia_clave, Pro_clave FROM Expediente where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $ocupacion = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($ocupacion);
    $db = null;    
}

if($funcion == 'validaHistoriaClinica'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT * FROM Consulta where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $ocupacion = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($ocupacion);
    $db = null;    
}

if($funcion == 'datosReciboInf'){
    $fol=$_GET['folio'];
    $db = conectarMySQL();
    $query="Select Exp_completo, Exp_fecreg From Expediente where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $ocupacion = $result->fetch();
    $nombre=$ocupacion['Exp_completo'];
    $fechaReg=$ocupacion['Exp_fecreg'];
    $datos=array('nombre'=>$ocupacion['Exp_completo'],'fecReg'=>$ocupacion['Exp_fecreg']);
    $query="Select Max(Recibo_cont)+1 as clave From reciboParticulares";
    $result = $db->query($query);
    $rs = $result->fetch();
    $noRec=$rs['clave'];
    $fecha=date("d-m-Y H:i:s");
    $datos['noRec']=$noRec;
    $datos['fecExp']=$fecha;
    echo json_encode($datos);
    $db = null;    
}

if($funcion == 'familiaItem'){
    $db = conectarMySQL();
    $query="select Tip_clave, Tip_nombre from TipoItem where Tip_activo_nuevo='S'";
    $result = $db->query($query);
    $tipoItem = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($tipoItem);
    $db = null;    
}

if($funcion == 'listMedicos'){
    $uni=$_GET['uni'];
    $db = conectarMySQL();
    $query="select Med_clave, Med_nombre, Med_paterno, Med_materno from Medico
 where Uni_clave=".$uni." and Med_activo='S'";
    $result = $db->query($query);
    $medico = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($medico);
    $db = null;    
}

if($funcion == 'contadorRecibos'){
    $fol=$_GET['folio'];
    $db = conectarMySQL();
    $query="Select * from reciboParticulares where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $recibos = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($recibos);
    $db = null;    
}
if($funcion == 'nombreUsu'){
    $usr=$_GET['usr'];
    $db = conectarMySQL();
    $query="Select Usu_nombre from Usuario where Usu_login='".$usr."'";
    $result = $db->query($query);
    $recibos = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($recibos);
    $db = null;    
}

if($funcion == 'listadoItems'){
    $fol=$_GET['folio'];
    $cveRec=$_GET['noFol'];
    $db = conectarMySQL();
    $query="SELECT * FROM Item_particulares Where Exp_folio='".$fol."' and it_folRecibo=".$cveRec.";";
    $result = $db->query($query);    
    $itemsRec = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($itemsRec);
    $db = null;    
}

if($funcion == 'SelectItems'){
    $cveFam=$_GET['cveFam'];
    $fol   = $_GET['fol'];
    $tipoCobro = $_GET['tipoCobro'];   
    $db = conectarMySQL();
    $query = "select Cia_clave, PRO_clave, Exp_modificador from Expediente where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $cia = $result->fetch();
    
    if($cia['Cia_clave']==54){

        if($tipoCobro==0){
            if($cia['Exp_modificador']=='T. INB'){
                $campo='T_Inbursa';
            }elseif($cia['Exp_modificador']=='G. BIMBO'){
                $campo='G_BIMBO';
            }elseif($cia['Exp_modificador']=='M.MV'){
                $campo='M_MV';
            }elseif($cia['Exp_modificador']=='Desclub'){
                $campo='Desclub';
            }elseif($cia['Exp_modificador']=='MetLife'){
                $campo='MetLife';
            }elseif($cia['Exp_modificador']=='HAPAG'){
                $campo='hapag';
            }elseif($cia['Exp_modificador']=='KEKEN'){
                $campo='keken';
            }
        }elseif($tipoCobro==3){
            $campo='M_MV';
        }else{
            switch($tipoCobro){
                case '1':
                    $campo='T_Inbursa';
                    break;
                case '2':
                    $campo='G_BIMBO';
                    break;
                case '4':
                    $campo='Desclub';
                    break;               
                case '6':
                    $campo='MetLife';
                    break;
                case '7':
                    $campo='hapag';
                    break;
                case '8':
                    $campo='keken';
                    break;
                case '13':
                    $campo='assis';
                    break;
            }
        }
    }elseif($cia['Cia_clave']==51){
        $campo='individual';
    }elseif($cia['Cia_clave']==44){
        $campo='cortesia';
    }elseif($cia['Cia_clave']==53){
        $campo='Empleado';
    }       
    $query="select ItemOrtho_nuevo.ite_cons, ite_item, ite_precio,".$campo." as permiso from ItemOrtho_nuevo 
            inner join PermisosItems on ItemOrtho_nuevo.ite_cons = PermisosItems.Ite_cons
            where ite_activo='S' and Tip_clave=".$cveFam." and  ".$campo."<>'N' order by ite_orden;";
    $result = $db->query($query);
    $tipoItem = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($tipoItem);
    $db = null;    
}

/*---------------- PARA VENTAS SIN REGISTRO ----------------*/
if($funcion == 'SelectItems1'){
    $cveFam     =$_GET['cveFam'];
    $mem        =$_GET['mem'];  
    $db = conectarMySQL();  
    if($mem==1){
        $campo='M_MV';
    }else{
        $campo='individual';
    }       
    $query="select ItemOrtho_nuevo.ite_cons, ite_item, ite_precio,".$campo." as permiso from ItemOrtho_nuevo 
            inner join PermisosItems on ItemOrtho_nuevo.ite_cons = PermisosItems.Ite_cons
            where ite_activo='S' and Tip_clave=".$cveFam." and ".$campo."<>'N';";
    $result = $db->query($query);
    $tipoItem = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($tipoItem);
    $db = null;    
}

if($funcion == 'guardaItem'){
    $fol=$_GET['folio'];
    $cveRec= $_GET['cveRec'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $familia = $datos->fam;
    $item = $datos->item;
    $itemPrecio = explode('/', $item);
    $it= $itemPrecio[0];
    $precio = $itemPrecio[1];
    $descuento = $datos->descuento;

    $db = conectarMySQL();

    $query="Select Max(it_cons)+1 as clave From Item_particulares Where Exp_folio='".$fol."' ";

    $result =   $db->query($query);
    $rs =       $result->fetch();
    $cveItem=   $rs['clave'];


    if($descuento==''||$descuento==null)$descuento=0;
    if($cveItem==''||$cveItem==NULL){$cveItem=1;}
    try{
    $sql="SELECT ite_clave, ite_item, ite_descripcion, ite_presentacion, Tip_clave FROM ItemOrtho_nuevo where ite_cons=".$it."";
    $result =   $db->query($sql);
    $rs =       $result->fetch();
    $fecha= date("Y-m-d H:i:s");

    $sql="INSERT INTO Item_particulares(it_cons,Exp_folio,it_codReg,it_codMV,it_prod,it_desc,it_pres,it_fecReg,it_folRecibo,it_precio,it_descuento,Tip_clave) 
      Values (:it_cons,:Exp_folio,:it_codReg,:it_codMV,:it_prod,:it_desc,:it_pres,:it_fecReg,:it_folRecibo,:it_precio,:it_descuento,:Tip_clave)";

      $stmt = $db->prepare($sql);
            $stmt->bindParam('it_cons', $cveItem);                                  
            $stmt->bindParam('Exp_folio', $fol);
            $stmt->bindParam('it_codReg', $it);       
            $stmt->bindParam('it_codMV', $rs['ite_clave']);    
            $stmt->bindParam('it_prod', $rs['ite_item']);    
            $stmt->bindParam('it_desc', $rs['ite_descripcion']);
            // use PARAM_STR although a number  
            $stmt->bindParam('it_pres', $rs['ite_presentacion']); 
            $stmt->bindParam('it_fecReg', $fecha);                       
            $stmt->bindParam('it_folRecibo', $cveRec); 
            $stmt->bindParam('it_precio', $precio); 
            $stmt->bindParam('it_descuento', $descuento);           
            $stmt->bindParam('Tip_clave', $rs['Tip_clave']);

            if ($stmt->execute()){

                $query="SELECT * FROM reciboParticulares Where Exp_folio='".$fol."' and Recibo_cont=".$cveRec.";";
                $result =   $db->query($query);
                $existe =       $result->fetch();
                if($existe==''){
                    $query="INSERT INTO reciboParticulares(Exp_folio,Recibo_cont) 
                            values(:Exp_folio,:Recibo_cont)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam('Recibo_cont', $cveRec);                                  
                    $stmt->bindParam('Exp_folio', $fol);
                    $stmt->execute();
                }

                $query="SELECT * FROM Item_particulares Where Exp_folio='".$fol."' and it_folRecibo=".$cveRec.";";
                $result =   $db->query($query);
                $respuesta =       $result->fetchAll(PDO::FETCH_OBJ);                
                                
            }else{
                $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
                }
    }catch(Expediente $e){
        $respuesta=  array('respuesta' => $e->getMessage() );
    }
    echo json_encode($respuesta);  
    $db = null;    
}

if($funcion == 'eliminarItemRecibo'){
    $fol=       $_GET['folio'];
    $cons=      $_GET['cons'];
    $folRec=    $_GET['folRec'];
    $db = conectarMySQL();
    try{
    $query="Delete from Item_particulares where it_cons =:it_cons and Exp_folio= :Exp_folio and it_folRecibo=:it_folRecibo;";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('it_cons', $cons);
    $stmt->bindParam('it_folRecibo', $folRec);
    if ($stmt->execute()){
        $query="SELECT * FROM Item_particulares Where Exp_folio='".$fol."' and it_folRecibo=".$folRec.";";
        $result =   $db->query($query);
        $respuesta =       $result->fetchAll(PDO::FETCH_OBJ);
    }else{
        $respuesta = array('respuesta' =>'incorrecto');
        }
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');
    }    
    echo json_encode($respuesta);
    $db = null;    
}

if($funcion == 'validaItems'){
    $fol=       $_GET['folio'];
    $cveRec=      $_GET['cveRec'];
    $db = conectarMySQL();
    try{
   
        $query="SELECT * FROM Item_particulares Where Exp_folio='".$fol."' and it_folRecibo=".$cveRec.";";
        $result =   $db->query($query);
        $respuesta =       $result->fetchAll(PDO::FETCH_OBJ);
   
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');
    }    
    echo json_encode($respuesta);
    $db = null;    
}

if($funcion == 'getOcupacion'){
    $db = conectarMySQL();
    $query="SELECT Ocu_clave, Ocu_nombre FROM Ocupacion";
    $result = $db->query($query);
    $ocupacion = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($ocupacion);
    $db = null;    
}

if($funcion == 'getEdoCivil'){
    $db = conectarMySQL();
    $query="SELECT Edo_clave, Edo_nombre FROM EdoCivil";
    $result = $db->query($query);
    $edoCivil = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($edoCivil);
    $db = null;    
}

if($funcion == 'guardaDatos'){
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);

     $conexion = conectarMySQL();

    $fecNac = $datos->fecnac;
    $anios = $datos->anios;
    $meses = $datos->meses;
    $sexo = $datos->sexo;
    $ocu = $datos->ocu;
    $edoC = $datos->edoC;
    $mail = $datos->mail;
    $obs = $datos->obs;
    $folio = $datos->folio;


    $sql = "UPDATE Expediente SET Exp_fechaNac = :Exp_fechaNac, 
            Exp_edad = :Exp_edad, 
            Exp_meses = :Exp_meses,  
            Exp_sexo = :Exp_sexo,
            Ocu_clave = :Ocu_clave,  
            Edo_clave = :Edo_clave,
            Exp_mail = :Exp_mail,
            Rel_clave = :Rel_clave            
            WHERE Exp_folio = :Exp_folio";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam('Exp_fechaNac', $fecNac);                                  
            $stmt->bindParam('Exp_edad', $anios);
            $stmt->bindParam('Exp_meses', $meses);       
            $stmt->bindParam('Exp_sexo', $sexo);    
            $stmt->bindParam('Ocu_clave', $ocu);    
            $stmt->bindParam('Edo_clave', $edoC);
            // use PARAM_STR although a number  
            $stmt->bindParam('Exp_mail', $mail); 
            $stmt->bindParam('Rel_clave', $obs);           
            $stmt->bindParam('Exp_folio', $folio);               

    if ($stmt->execute()){
        $respuesta = array('respuesta' =>'correcto','folio'=>$fol);
    }else{
        $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
        }
    echo json_encode($respuesta);

    $conexion = null;    
}

if($funcion == 'getEnfermedad'){
    $db = conectarMySQL();
    $query="SELECT Enf_clave, Enf_nombre FROM Enfermedad";
    $result = $db->query($query);
    $edoCivil = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($edoCivil);
    $db = null;    
}

if($funcion == 'getFamiliar'){
    $db = conectarMySQL();
    $query="SELECT Fam_clave, Fam_nombre FROM Familia";
    $result = $db->query($query);
    $edoCivil = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($edoCivil);
    $db = null;    
}

if($funcion == 'getEstatus'){
    $db = conectarMySQL();
    $query="SELECT Est_clave, Est_nombre FROM EstatusFam";
    $result = $db->query($query);
    $edoCivil = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($edoCivil);
    $db = null;    
}

if($funcion == 'getListEnfHeredo'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT FamE_clave, Enf_nombre, Fam_nombre, Est_nombre, FamE_obs 
                               FROM FamEnfermedad 
                               Inner Join Enfermedad on FamEnfermedad.Enf_clave=Enfermedad.Enf_clave 
                               Inner Join Familia on FamEnfermedad.Fam_clave=Familia.Fam_clave 
                               Inner Join EstatusFam on FamEnfermedad.Est_clave=EstatusFam.Est_clave 
                               Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listEnfHeredo = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listEnfHeredo);
    $db = null;    
}

if($funcion == 'guardaEnfH'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);

    $enfermedad = $datos->enfermedad;
    $familiar = $datos->familiar;
    $estatus = $datos->estatus;
    $observaciones = $datos->observaciones;

    $db = conectarMySQL();    

    try{ $query1="Insert into FamEnfermedad(Exp_folio, Enf_clave, Fam_clave, Est_clave, FamE_obs) 
        Values(:Exp_folio,:Enf_clave,:Fam_clave,:Est_clave,:FamE_obs);";

    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);
    $temporal->bindParam("Enf_clave", $enfermedad);
    $temporal->bindParam("Fam_clave", $familiar);
    $temporal->bindParam("Est_clave", $estatus);
    $temporal->bindParam("FamE_obs", $observaciones);

    if ($temporal->execute()){
        //cuando se haya insertado el dato, consultamos el listado de enfermedades agregadas y se manda al controlador
         $query="SELECT FamE_clave, Enf_nombre, Fam_nombre, Est_nombre, FamE_obs 
                               FROM FamEnfermedad 
                               Inner Join Enfermedad on FamEnfermedad.Enf_clave=Enfermedad.Enf_clave 
                               Inner Join Familia on FamEnfermedad.Fam_clave=Familia.Fam_clave 
                               Inner Join EstatusFam on FamEnfermedad.Est_clave=EstatusFam.Est_clave 
                               Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listEnfHeredo = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listEnfHeredo);
        $db = null;            
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;    
}

if($funcion == 'borraEnfH'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM FamEnfermedad WHERE Exp_folio =  :Exp_folio and FamE_clave = :FamE_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('FamE_clave', $cont);
    if ($stmt->execute()){
       $query="SELECT FamE_clave, Enf_nombre, Fam_nombre, Est_nombre, FamE_obs 
                               FROM FamEnfermedad 
                               Inner Join Enfermedad on FamEnfermedad.Enf_clave=Enfermedad.Enf_clave 
                               Inner Join Familia on FamEnfermedad.Fam_clave=Familia.Fam_clave 
                               Inner Join EstatusFam on FamEnfermedad.Est_clave=EstatusFam.Est_clave 
                               Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listEnfHeredo = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listEnfHeredo);                
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'getPadecimientos'){
    $db = conectarMySQL();
    $query="SELECT Pad_clave, Pad_nombre FROM Padecimientos";
    $result = $db->query($query);
    $padecimientos = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($padecimientos);
    $db = null;    
}

if($funcion == 'validaNotaM'){
    $db = conectarMySQL();
    $fol=$_GET['fol'];
    $query="SELECT * FROM NotaMedica where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $existeNota = $result->fetch();
    if($existeNota){
        $respuesta = array('respuesta' => 'existe');        
    }else{
        $query="SELECT * FROM DocumentosDigitales where Arc_tipo=18 and REG_folio='".$fol."'";
        $result = $db->query($query);
        $existeDig = $result->fetch();
        if($existeDig){
            $respuesta = array('respuesta' => 'noExiste','digital'=>'existe');
        }else{
            $respuesta = array('respuesta' => 'noExiste','digital'=>'noExiste');
        }
    }
    echo json_encode($respuesta);
    $db = null;    
}

if($funcion == 'guardamotivoCons'){
    $db = conectarMySQL();
    $fol=$_GET['fol'];
    $motivo = $_GET['motivo'];
    $usr = $_GET['usr'];

    $query="select Exp_folio from Consulta where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $consulta = $result->fetch();
    
    if($consulta==NULL){
        $query = "insert into Consulta(Exp_folio,Con_fecha,Con_motivo,Usu_registro)
                          values(:Exp_folio, now(),:Con_motivo,:Usu_registro)";  
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Con_motivo", $motivo);
        $temporal->bindParam("Usu_registro", $usr);

        if ($temporal->execute()){        
            $respuesta = array('respuesta' => 'correcto');
            echo json_encode($respuesta);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }
    }else{
        $respuesta = array('respuesta' => 'correcto');
        echo json_encode($respuesta);
    }    
}


if($funcion == 'validaPaseMed'){
    $db = conectarMySQL();
    $fol=$_GET['fol'];
    $query="Select Exp_folio From RehabilitacionPase WHERE Exp_folio='".$fol."';";
    $result = $db->query($query);
    $existePase = $result->fetch();
    if($existePase){
        $respuesta = array('respuesta' => 'existe');        
    }else{
        $respuesta = array('respuesta' => 'noExiste');
    }
    echo json_encode($respuesta);
    $db = null;    
}
if($funcion == 'validaPaseMedic'){
    $db = conectarMySQL();
    $fol=$_GET['fol'];
    $query="Select * From RehabilitacionPase WHERE Exp_folio='".$fol."';";
    $result = $db->query($query);
    $pasRe = $result->fetch(PDO::FETCH_OBJ);
    if($pasRe){
        echo json_encode($pasRe);    
    }else{
        echo json_encode(array('respuesta'=>'NO'));  
    }   
    $db = null;    
    
}

if($funcion == 'verDatosPase'){
    $db = conectarMySQL();
    $fol=$_GET['fol'];
    $query="Select RPase_fecha, RPase_rehabilitacion,RPase_obs,RPase_diagnostico,Medico.Med_nombre, Medico.Med_paterno,Medico.Med_materno From RehabilitacionPase inner join Medico on RehabilitacionPase.Usu_registro=Medico.Usu_login WHERE Exp_folio='".$fol."';";
    $result = $db->query($query);
    $pasRe = $result->fetch(PDO::FETCH_OBJ);
    if($pasRe){ 
        echo json_encode($pasRe);    
    }else{
        echo json_encode(array('respuesta'=>'NO'));  
    }   
    $db = null;    
    
}

if($funcion == 'rehabNo'){
    $db = conectarMySQL();
    $fol=$_GET['fol'];
    $query="Select Max(Rehab_cons)+1 As Cons From Rehabilitacion Where Exp_folio='".$fol."';";
    $result = $db->query($query);
    $pasRe = $result->fetch();
    if($pasRe){ 
        if($pasRe['Cons']==null)$pasRe['Cons']=1; 
        echo json_encode(array('rehab'=>$pasRe['Cons']));    
    }else{
        echo json_encode(array('respuesta'=>'NO'));  
    }   
    $db = null;    
    
}


if($funcion == 'getOtrasEnf'){
    $db = conectarMySQL();
    $query="SELECT Otr_clave, Otr_nombre FROM Otras";
    $result = $db->query($query);
    $otrasEnf = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($otrasEnf);
    $db = null;    
}

if($funcion == 'getAlergias'){
    $db = conectarMySQL();
    $query="SELECT Ale_clave, Ale_nombre FROM Alergias";
    $result = $db->query($query);
    $alergias = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($alergias);
    $db = null;    
}

if($funcion == 'getListPadecimientos'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Hist_clave, Pad_nombre, Pad_obs 
           FROM HistoriaPadecimiento 
           Inner Join Padecimientos on HistoriaPadecimiento.Pad_clave=Padecimientos.Pad_clave 
           Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listPad = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listPad);
    $db = null;    
}

if($funcion == 'getListOtrasEnf'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistOt_clave, Otr_nombre, HistOt_obs 
           FROM HistoriaOtras 
           Inner Join Otras on HistoriaOtras.Otr_clave=Otras.Otr_clave 
           Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listOtras = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listOtras);
    $db = null;    
}

if($funcion == 'getListAlergias'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistA_clave, Ale_nombre, Ale_obs 
             FROM HistoriaAlergias
             Inner Join Alergias on HistoriaAlergias.Ale_clave=Alergias.Ale_clave 
             Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listAlergias = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listAlergias);
    $db = null;    
}

if($funcion == 'getListPadEsp'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistE_clave, Esp_estatus, Esp_obs 
            FROM HistoriaEspalda
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listPadEsp);
    $db = null;    
}

if($funcion == 'getListTratQuiro'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistoriaQ_clave, Quiro_estatus, Quiro_obs 
            FROM HistoriaQuiro 
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listPadEsp);
    $db = null;    
}

if($funcion == 'getListPlantillas'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistP_clave, Plantillas_estatus, Plantillas_obs 
            FROM HistoriaPlantillas
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listPadEsp);
    $db = null;    
}

if($funcion == 'getListTratamientos'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistT_clave, HistT_estatus, HistT_obs 
            FROM HistoriaTrat
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listPadEsp);
    $db = null;    
}

if($funcion == 'getListIntervenciones'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistO_clave, HistO_estatus, HistO_obs 
                   FROM HistoriaOperacion 
                   Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListDeportes'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistD_clave, Dep_estatus, Dep_obs 
            FROM HistoriaDeporte
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListAdicciones'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistA_clave, Adic_estatus, Adic_obs 
            FROM HistoriaAdiccion 
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getCatLugar'){
    $db = conectarMySQL();
    $query="SELECT Lug_clave, Lug_nombre FROM LugarRed";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListAccAnt'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT HistAcc_clave, Acc_estatus, Lug_nombre, Acc_obs FROM HistoriaAcc
              Inner Join Lugar on HistoriaAcc.Lug_clave=Lugar.Lug_clave
              Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListPacLlega'){
    $db = conectarMySQL();
    $query="SELECT Llega_clave, Llega_nombre FROM Llega_red";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListTipVehi'){
    $db = conectarMySQL();
    $query="SELECT id, opcion FROM TipoVehiculo_red";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListVitales'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="Select Vit_clave,Vit_temperatura, Vit_talla, Vit_peso, Vit_ta, Vit_fc, Vit_fr , Vit_imc , Vit_observaciones, Vit_fecha, Usu_registro, IMC_categoria, IMC_comentario From Vitales  Inner Join IMC on IMC.IMC_clave=Vitales.IMC_clave  Where Exp_folio='".$fol."' order by Vit_clave desc";
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}
if($funcion == 'getListVitalesSub'){
    $fol=$_GET['fol'];
    $sub=$_GET['sub'];
    $db = conectarMySQL();
    $query="Select VitSub_temperatura, VitSub_talla, VitSub_peso, VitSub_ta, VitSub_fc, VitSub_fr , VitSub_imc , VitSub_observaciones, VitSub_fecha, Usu_registro, IMC_categoria, IMC_comentario From VitalesSub  Inner Join IMC on IMC.IMC_clave=VitalesSub.IMC_clave  Where Exp_folio='".$fol."' and VitSub_subCons=".$sub;
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);
    $db = null;    
}

if($funcion == 'getListEmbarazo'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Emb_semGestacion, Emb_dolAbdominal, Emb_descripcion, Emb_movFetales, Emb_fcf, Emb_ginecologia, Emb_obs FROM Embarazo Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listEmbarazo = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listEmbarazo);
    $db = null;    
}

if($funcion == 'getListLesion'){
    $db = conectarMySQL();
    $query="SELECT LES_clave, LES_nombre FROM LesionRed";
    $result = $db->query($query);
    $listLesion = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listLesion);
    $db = null;    
}

if($funcion == 'getListLesiones'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Les_nombre, Cue_nombre, LesN_clave FROM LesionNota inner join Lesion on Lesion.Les_clave=LesionNota.Les_clave inner join Cuerpo on Cuerpo.Cue_clave=LesionNota.Cue_clave Where Exp_folio='".$fol."' ORDER BY LesN_clave ASC";
    $result = $db->query($query);
    $listLesiones = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listLesiones);
    $db = null;    
}

if($funcion == 'getListRX'){    
    $db = conectarMySQL();
    $query="SELECT Rx_clave, Rx_nombre, Rx_zona FROM Rx WHERE Rx_activo=1";
    $result = $db->query($query);
    $listRx = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listRx);
    $db = null;    
}
if($funcion == 'getListEstSol'){
    $fol=$_GET['fol'];    
    $db = conectarMySQL();
    $query="SELECT Rxs_clave, Rx_nombre, Rxs_Obs, Rxs_desc 
            FROM RxSolicitados inner Join Rx on Rx.Rx_clave=RxSolicitados.Rx_clave 
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listEstSol);
    $db = null;    
}

if($funcion == 'getlistEstSolSub'){ 
    $fol=$_GET['fol'];   
    $db = conectarMySQL();
    $query="SELECT Rxsub_clave, Rx_nombre, Rxsub_Obs, Rxsub_desc 
            FROM RxSubSolicitados inner Join Rx on Rx.Rx_clave=RxSubSolicitados.Rx_clave 
            Where Exp_folio='".$fol."' and Sub_cons=0";
    $result = $db->query($query);
    $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listEstSol);
    $db = null;    
}

if($funcion == 'getListProRea'){
    $fol=$_GET['fol'];    
    $db = conectarMySQL();
    $query="SELECT Nproc_clave, Pro_nombre, Nproc_obs  FROM NotaProcedimientos inner Join Procedimientos on Procedimientos.Pro_clave=NotaProcedimientos.Pro_clave Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listProcedimientos = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listProcedimientos);
    $db = null;    
}

if($funcion == 'getListProcedimientos'){    
    $db = conectarMySQL();
    $query="SELECT Pro_clave, Pro_nombre FROM Procedimientos";
    $result = $db->query($query);
    $listProce = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listProce);
    $db = null;    
}

if($funcion == 'getListDiagnostic'){    
    $db = conectarMySQL();
    $query="SELECT * FROM DxComunes";
    $result = $db->query($query);
    $listDiagnostic = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listDiagnostic);
    $db = null;    
}
if($funcion == 'getListDiag'){ 
    $diag=$_GET['diag'];   
    $db = conectarMySQL();
    $query="SELECT * FROM DxSub where Dx_clave=".$diag;
    $result = $db->query($query);
    $listDiagnostic = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listDiagnostic);
    $db = null;    
}

if($funcion == 'getListOtrosEst'){    
    $db = conectarMySQL();
    $query="SELECT Estu_clave, Estu_nombre FROM Estudios";
    $result = $db->query($query);
    $listOtrosEst = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listOtrosEst);
    $db = null;    
}
if($funcion == 'getListadoOtrosEst'){    
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT EstuS_clave, Estu_nombre, EstuS_Obs 
            FROM EstSolicitados  
            inner Join Estudios on EstSolicitados.Estu_clave=Estudios.Estu_clave 
            Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listOtrosEst = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listOtrosEst);
    $db = null;    
}



if($funcion == 'getListMedicamentos'){    
    $db = conectarMySQL();
    $query="SELECT Sum_clave, Sum_nombre, Sum_presentacion, Sum_indicacion  FROM Suministro where activo=1 order by Sum_presentacion asc";
    $result = $db->query($query);
    $listDiagnostic = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listDiagnostic);
    $db = null;    
}

if($funcion == 'getListOrtesis'){    
    $db = conectarMySQL();
    $query="SELECT Ort_clave, Ort_nombre FROM Ortesis where Ort_activo=0 order by cuadro desc, Ort_nombre";
    $result = $db->query($query);
    $listDiagnostic = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listDiagnostic);
    $db = null;    
}

if($funcion == 'getListIndicaciones'){    
    $db = conectarMySQL();
    $query="SELECT Ind_clave, Ind_nombre FROM IndicacionesGenerales order by Ind_nombre asc";
    $result = $db->query($query);
    $listDiagnostic = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listDiagnostic);
    $db = null;    
}

if($funcion == 'getListMedicamentosAgreg'){ 
    $fol=$_GET['fol'];   
    $db = conectarMySQL();
    $query="SELECT Nsum_clave, Sum_nombre, Nsum_obs, Nsum_Cantidad  FROM NotaSuministro inner Join Suministro on Suministro.Sum_clave =NotaSuministro.Sum_clave Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}

if($funcion == 'getListMedicamentosAgregSub'){    
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="SELECT Subsum_clave, Sum_nombre, Subsum_obs, Subsum_Cantidad  FROM SubSuministros inner Join Suministro on Suministro.Sum_clave =SubSuministros.Sum_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}

if($funcion == 'getListOrtesisAgreg'){    
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Notor_clave, Ort_nombre, Ortpre_nombre, Notor_cantidad, Notor_indicaciones FROM NotaOrtesis inner Join Ortesis on Ortesis.Ort_clave=NotaOrtesis.Ort_clave inner Join  Ortpresentacion on Ortpresentacion.Ortpre_clave=NotaOrtesis.Ortpre_clave Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}
if($funcion == 'getListOrtesisAgregSub'){    
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="SELECT Subort_clave, Ort_nombre, Ortpre_nombre, Subort_cantidad, Subort_indicaciones FROM SubOrtesis inner Join Ortesis on Ortesis.Ort_clave=SubOrtesis.Ort_clave inner Join  Ortpresentacion on Ortpresentacion.Ortpre_clave=SubOrtesis.Ortpre_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}
if($funcion == 'getListIndicAgreg'){    
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Nind_clave, Nind_obs FROM NotaInd Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}
if($funcion == 'getListIndicAgregComplementaria'){    
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Nind_clave, Nind_obs FROM NotaIndAlternativa Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}

if($funcion == 'getListIndicAgregSub'){    
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="SELECT Sind_clave, Sind_obs FROM SubInd Where Exp_folio='".$fol."' and Sub_cons=".$cont;
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}

if($funcion == 'validaSigVitales'){ 
    $fol=$_GET['fol'];   
    $db = conectarMySQL();
    $query="SELECT count(*) FROM Vitales Where Exp_folio ='".$fol."'";
    $result = $db->prepare($query); 
    $result->execute(); 
    $number_of_rows = $result->fetchColumn();
    $respuesta = array('respuesta' => 'correcto', 'noRowVit'=>$number_of_rows); 
    echo json_encode($respuesta);
    $db = null;    
}

if($funcion == 'vePosologia'){ 
    $cveMed=$_GET['cveMed'];   
    $db = conectarMySQL();
    $query="SELECT Sum_indicacion  FROM Suministro Where Sum_clave =".$cveMed;
    $result = $db->query($query);
    $posologia = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($posologia);
    $db = null;    
}

if($funcion == 'veIndicacion'){    
    $db = conectarMySQL();
    $query="SELECT Sum_indicacion  FROM Suministro Where Sum_clave =".$cveMed;
    $result = $db->query($query);
    $posologia = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($posologia);
    $db = null;    
}


if($funcion == 'selectPosicion'){
    $opcion=$_GET['opcion'];
    $db = conectarMySQL();
    $query="SELECT id, opcion FROM PosicionAcc WHERE relacion=".$opcion;
    $result = $db->query($query);
    $listInter = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listInter);   
    $db = null;    
}
if($funcion == 'validaSubsecuencia'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="Select Max(Sub_cons)+1 As Cons From Subsecuencia Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listInter = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($listInter);   
    $db = null;    
}

if($funcion == 'guardaPad'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);

    $padecimiento = $datos->nombre;
    $obs = $datos->obs;

    $db = conectarMySQL();
    
    try{ 
        $query1="Insert into HistoriaPadecimiento(Exp_folio, Pad_clave, Pad_obs) 
                              Values(:Exp_folio,:Pad_clave,:Pad_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);
    $temporal->bindParam("Pad_clave", $padecimiento);
    $temporal->bindParam("Pad_obs", $obs);

    if ($temporal->execute()){        
        $query="SELECT Hist_clave, Pad_nombre, Pad_obs 
           FROM HistoriaPadecimiento 
           Inner Join Padecimientos on HistoriaPadecimiento.Pad_clave=Padecimientos.Pad_clave 
           Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPad = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPad);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}   
    $db = null;    
}

if($funcion == 'borraPadec'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaPadecimiento WHERE Exp_folio = :Exp_folio and Hist_clave = :Hist_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('Hist_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT Hist_clave, Pad_nombre, Pad_obs 
           FROM HistoriaPadecimiento 
           Inner Join Padecimientos on HistoriaPadecimiento.Pad_clave=Padecimientos.Pad_clave 
           Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPad = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPad);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'guardaOtras'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $fol=$_GET['fol'];
    $datos = json_decode($postdata);

    $enfermedad = $datos->enf;
    $obs = $datos->obs;

    $db = conectarMySQL();
    try{ 
        $query1="Insert into HistoriaOtras(Exp_folio, Otr_clave, HistOt_obs) 
                                 Values(:Exp_folio,:Otr_clave,:HistOt_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);
    $temporal->bindParam("Otr_clave", $enfermedad);
    $temporal->bindParam("HistOt_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistOt_clave, Otr_nombre, HistOt_obs 
           FROM HistoriaOtras 
           Inner Join Otras on HistoriaOtras.Otr_clave=Otras.Otr_clave 
           Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listOtras = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listOtras);
        $db = null;    
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    
    $db = null;    
}

if($funcion == 'borraOtrasEnf'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaOtras WHERE Exp_folio = :Exp_folio and HistOt_clave = :HistOt_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistOt_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistOt_clave, Otr_nombre, HistOt_obs 
           FROM HistoriaOtras 
           Inner Join Otras on HistoriaOtras.Otr_clave=Otras.Otr_clave 
           Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listOtras = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listOtras);
        $db = null;    
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}

if($funcion == 'guardaAlergia'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);

    $alergia = $datos->alergia;
    $obs = $datos->obs;

    $db = conectarMySQL();
    try{ 
        $query1="Insert into HistoriaAlergias(Exp_folio, Ale_clave, Ale_obs) 
                                 Values(:Exp_folio,:Ale_clave,:Ale_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);
    $temporal->bindParam("Ale_clave", $alergia);
    $temporal->bindParam("Ale_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistA_clave, Ale_nombre, Ale_obs 
             FROM HistoriaAlergias
             Inner Join Alergias on HistoriaAlergias.Ale_clave=Alergias.Ale_clave 
             Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listAlergias = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listAlergias);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    
    $db = null;    
}


if($funcion == 'borraAlergia'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaAlergias WHERE Exp_folio = :Exp_folio and HistA_clave = :HistA_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistA_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistA_clave, Ale_nombre, Ale_obs 
             FROM HistoriaAlergias
             Inner Join Alergias on HistoriaAlergias.Ale_clave=Alergias.Ale_clave 
             Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listAlergias = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listAlergias);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'guardaPadEspalda'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;

    $db = conectarMySQL();
    
    try{ 
        $query1="Insert into HistoriaEspalda(Exp_folio, Esp_estatus, Esp_obs) 
                           Values(:Exp_folio,'S',:Esp_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("Esp_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistE_clave, Esp_estatus, Esp_obs 
            FROM HistoriaEspalda
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}   
    $db = null;    
}

if($funcion == 'borraPadEspalda'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaEspalda WHERE Exp_folio = :Exp_folio and HistE_clave = :HistE_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistE_clave', $cont);
    if ($stmt->execute()){
         $query="SELECT HistE_clave, Esp_estatus, Esp_obs 
            FROM HistoriaEspalda
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'guardaTratQuiro'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;

    $db = conectarMySQL();

    try{ 
        $query1="Insert into HistoriaQuiro(Exp_folio, Quiro_estatus, Quiro_obs) 
                              Values(:Exp_folio,'S',:Quiro_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("Quiro_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistoriaQ_clave, Quiro_estatus, Quiro_obs 
            FROM HistoriaQuiro 
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    
    $db = null;    
}

if($funcion == 'borraTratQui'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaQuiro WHERE Exp_folio = :Exp_folio and HistoriaQ_clave = :HistoriaQ_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistoriaQ_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistoriaQ_clave, Quiro_estatus, Quiro_obs 
            FROM HistoriaQuiro 
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'guardaPlantillas'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;

    $db = conectarMySQL();

    try{ 
        $query1="Insert into HistoriaPlantillas(Exp_folio, Plantillas_estatus, Plantillas_obs) 
                           Values(:Exp_folio,'S',:Plantillas_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("Plantillas_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistP_clave, Plantillas_estatus, Plantillas_obs 
            FROM HistoriaPlantillas
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    
    $db = null;   
} 

if($funcion == 'borraPlatillas'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaPlantillas WHERE Exp_folio = :Exp_folio and HistP_clave = :HistP_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistP_clave', $cont);
    if ($stmt->execute()){
         $query="SELECT HistP_clave, Plantillas_estatus, Plantillas_obs 
            FROM HistoriaPlantillas
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'guardaTratamiento'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;
    $db = conectarMySQL();
    try{ 
        $query1="Insert into HistoriaTrat(Exp_folio, HistT_estatus, HistT_obs) 
                           Values(:Exp_folio,'S',:HistT_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("HistT_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistT_clave, HistT_estatus, HistT_obs 
            FROM HistoriaTrat
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;   
} 

if($funcion == 'borraTratamiento'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaTrat WHERE Exp_folio = :Exp_folio and HistT_clave = :HistT_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistT_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistT_clave, HistT_estatus, HistT_obs 
            FROM HistoriaTrat
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listPadEsp = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listPadEsp);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'guardaIntervenciones'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;

    $db = conectarMySQL();
    try{ 
        $query1="Insert into HistoriaOperacion(Exp_folio, HistO_estatus, HistO_obs) 
                 Values(:Exp_folio,'S',:HistO_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("HistO_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistO_clave, HistO_estatus, HistO_obs 
                   FROM HistoriaOperacion 
                   Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;   
} 

if($funcion == 'borraIntervencion'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaOperacion WHERE Exp_folio = :Exp_folio and HistO_clave = :HistO_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistO_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistO_clave, HistO_estatus, HistO_obs 
                   FROM HistoriaOperacion 
                   Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}

if($funcion == 'guardaDeporte'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;

    $db = conectarMySQL();
    try{ 
        $query1="Insert into HistoriaDeporte(Exp_folio, Dep_estatus, Dep_obs) 
                   Values(:Exp_folio,'S',:Dep_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("Dep_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistD_clave, Dep_estatus, Dep_obs 
            FROM HistoriaDeporte
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
}   
    $db = null;   
} 

if($funcion == 'borraDeporte'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaDeporte WHERE Exp_folio = :Exp_folio and HistD_clave = :HistD_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistD_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistD_clave, Dep_estatus, Dep_obs 
            FROM HistoriaDeporte
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'guardaAdiccion'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
   
    $obs = $datos->obs;

    $db = conectarMySQL();

    try{ 
        $query1="Insert into HistoriaAdiccion(Exp_folio, Adic_estatus, Adic_obs) 
                   Values(:Exp_folio,'S',:Adic_obs);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("Adic_obs", $obs);

    if ($temporal->execute()){
        $query="SELECT HistA_clave, Adic_estatus, Adic_obs 
            FROM HistoriaAdiccion 
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;   
} 

if($funcion == 'borraAdiccion'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaAdiccion WHERE Exp_folio = :Exp_folio and HistA_clave = :HistA_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistA_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistA_clave, Adic_estatus, Adic_obs 
            FROM HistoriaAdiccion 
            Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'guardaAccAnt'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
    
    $opc=   $datos->opc;
    $lugar  =$datos->lugar;
    $obs = $datos->obs;
    $fecha  =$datos->fecha;

    $fechaFormato = explode('/', $fecha);
    $fechaCambiada=$fechaFormato[2].'-'.$fechaFormato[1].'-'.$fechaFormato[0];


    $db = conectarMySQL();

    try{ 
        $query1="Insert into HistoriaAcc(Exp_folio, Acc_estatus, Lug_clave, Acc_obs,Acc_fecha)
                                 Values(:Exp_folio,:Acc_estatus,:Lug_clave,:Acc_obs,:Acc_fecha);";
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Exp_folio", $fol);   
    $temporal->bindParam("Acc_estatus", $opc);   
    $temporal->bindParam("Lug_clave", $lugar);   
    $temporal->bindParam("Acc_obs", $obs);
    $temporal->bindParam("Acc_fecha", $fechaCambiada);

    if ($temporal->execute()){
        $query="SELECT HistAcc_clave, Acc_estatus, Lug_nombre, Acc_obs,Acc_fecha FROM HistoriaAcc
              Inner Join Lugar on HistoriaAcc.Lug_clave=Lugar.Lug_clave
              Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;   
} 

if($funcion == 'borraAccAnt'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="DELETE FROM HistoriaAcc WHERE Exp_folio = :Exp_folio and HistAcc_clave = :HistAcc_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('HistAcc_clave', $cont);
    if ($stmt->execute()){
        $query="SELECT HistAcc_clave, Acc_estatus, Lug_nombre, Acc_obs FROM HistoriaAcc
              Inner Join Lugar on HistoriaAcc.Lug_clave=Lugar.Lug_clave
              Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'guardaTels'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $tipo   =$datos->tipo;
    $numero =$datos->telefono;
    switch ($tipo) {
        case '1':
            $tipoTel='Particular';
            break;
        case '2':
            $tipoTel='Oficina';
            break;
        case '3':
            $tipoTel='M??vil';
            break;
        case '4':
            $tipoTel='Otro';
            break;
    }
    $db = conectarMySQL();
    $query="Select Max(Tel_cont)+1 As Cons From TelefonosLesionado Where Exp_folio='".$fol."';";
    $result = $db->query($query);
    $cont = $result->fetch();
    if($cont['Cons']==null)$cont['Cons']=1;
    $contador= $cont['Cons'];
    $query="INSERT INTO TelefonosLesionado(Exp_folio, Tel_tipo, Tel_numero, Tel_cont)
                        VALUES(:Exp_folio,:Tel_tipo,:Tel_numero,:Tel_cont)";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('Tel_tipo', $tipoTel);
    $stmt->bindParam('Tel_numero', $numero);
    $stmt->bindParam('Tel_cont', $contador);

    if ($stmt->execute()){
        $query="SELECT * FROM TelefonosLesionado        
              Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listnumeros = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listnumeros);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'borraTels'){
    $fol=$_GET['fol'];
    $cveTel=$_GET['cveTel'];
    $db = conectarMySQL();
    $query="DELETE FROM TelefonosLesionado WHERE Exp_folio = :Exp_folio and Tel_cont = :Tel_cont";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('Tel_cont', $cveTel);
    if ($stmt->execute()){
        $query="SELECT * FROM TelefonosLesionado        
              Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}



if($funcion == 'enviaSolicitudFac'){
    $mimemail = new nomad_mimemail();

    $postdata = file_get_contents("php://input");
    $datos      = json_decode($postdata);
    $calle      =$datos->facCalle;
    $codPos     =$datos->facCodP;
    $colonia    =$datos->facCol;
    $delegacion =$datos->facDel;
    $correo     =$datos->facEmail;
    $fol        =$datos->facFolio;
    $medico     =$datos->facMed;
    $mpago      =$datos->facMpago;
    $noEx       =$datos->facNoEx;
    $noInt      =$datos->facNoInt;
    if($noInt=='') $noInt = 0;
    $obs        =$datos->facObs;
    if($obs=='') $obs='--';
    $pcobra     =$datos->facPcobra;
    $facref     =$datos->facRef;
    if($facref=='') $facref='--';
    $rfc        =$datos->facRfc;
    $nom        =$datos->facSocNom;
    $noFolioRe  =$datos->facFolRec;
    $usr        =$datos->facUsu;
    $total      =$datos->facTotal;
    $fecha      =date("Y-m-d H:i:s");
    $db = conectarMySQL();
    $query="INSERT INTO Particulares_fac(Exp_folio,Recibo_cont,Par_rfc,Par_razSoc,Par_calle,Par_noExt,
                    Par_noInt,Par_colonia,Par_delMun,Par_codP,Par_referencia,Par_correo,Par_perCob,Par_medico,
                    Par_observ,Par_usuario,Par_mpago, Par_fecenvio,Par_monto)
                    VALUES(:Exp_folio,:Recibo_cont,:Par_rfc,:Par_razSoc,:Par_calle,:Par_noExt,
                    :Par_noInt,:Par_colonia,:Par_delMun,:Par_codP,:Par_referencia,:Par_correo,:Par_perCob,:Par_medico,
                    :Par_observ,:Par_usuario,:Par_mpago,:Par_fecenvio,:Par_monto)";

    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('Recibo_cont', $noFolioRe);
    $stmt->bindParam('Par_rfc', $rfc);
    $stmt->bindParam('Par_razSoc', $nom);
    $stmt->bindParam('Par_calle', $calle);
    $stmt->bindParam('Par_noExt', $noEx);
    $stmt->bindParam('Par_noInt', $noInt);
    $stmt->bindParam('Par_colonia', $colonia);
    $stmt->bindParam('Par_delMun', $delegacion);
    $stmt->bindParam('Par_codP', $codPos);
    $stmt->bindParam('Par_referencia', $facref);
    $stmt->bindParam('Par_correo', $correo);
    $stmt->bindParam('Par_perCob', $pcobra);
    $stmt->bindParam('Par_medico', $medico);
    $stmt->bindParam('Par_observ', $obs);
    $stmt->bindParam('Par_usuario', $usr);
    $stmt->bindParam('Par_mpago', $mpago);
    $stmt->bindParam('Par_fecenvio', $fecha);
    $stmt->bindParam('Par_monto', $total);

    if ($stmt->execute()){
        switch ($mpago) {
                case 1:
                    $pago='Efectivo';
                    break;
                case 2:
                    $pago='Tarjeta de cr&eacute;dito';
                    break;
                case 3:
                    $pago='Tarjeta de d&eacute;bito';
                    break;
                case 4:
                    $pago='Transferencia';
                    break;
                case 5:
                    $pago='Cheque';
                    break;
            } 
        $query="SELECT Exp_completo, Exp_fechaNac, Uni_nombre FROM Expediente inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $exp = $result->fetch();
        $nombre=$exp['Exp_completo'];
        $unidad=$exp['Uni_nombre'];

        if($mpago==2 || $mpago==3){
            $query ="SELECT Recibo_banco, Recibo_terminacion from reciboParticulares where Exp_folio='".$fol."' and Recibo_cont=".$noFolioRe.";";
            $result = $db->query($query);
            $bancoDatos = $result->fetch();    
            $banco=$bancoDatos['Recibo_banco'];
            $terminacion=$bancoDatos['Recibo_terminacion'];
        }   

        $query="SELECT * FROM Item_particulares Where Exp_folio='".$fol."' and it_folRecibo=".$noFolioRe.";";
        $result = $db->query($query);        
       
        /////////////////////////// html para env??o de correo electr??nico para solicitat factura /////////////////

            $items= "
                <tr>      
                       <th colspan=\"6\" style=\" width: 25%; background: #eee;
                   text-align: left;
                   vertical-align: top;
                   border: 1px solid #000;
                   border-collapse: collapse;
                   padding: 0.3em;
                   caption-side: bottom;\"><b>ITEMS</b></th>       
                  </tr>
                 <tr>      
                       <th width=\"10%\"><b>C&oacute;digo registro</b></th>
                       <th width=\"30%\"><b>C&oacute;digo MV</b></th>
                       <th width=\"20%\"><b>Producto</b></th>           
                       <th width=\"15%\"><b>Descuento</b></th>
                       <th width=\"20%\" colspan=\"2\" ><b>Precio</b></th>             
                  </tr>";
                  $precioMatCu=0;
                    $row = $result->fetchAll();
                    $longitud = count($row);
                    $subtotal=0;
                    $porcentaje=0;
                    $descuento=0;
                    $total=0;
                                            for($i=0; $i<$longitud; $i++){
                                              
                                              $subtotal=$subtotal+$row[$i]['it_precio'];
                                              $porcentaje=($row[$i]['it_descuento']*$row[$i]['it_precio'])/100;
                                              $descuento = $descuento+$porcentaje;
                                              $total = $subtotal - $descuento;  
                                              if($row[$i]['Tip_clave']!=5){                            
                                                  $items.= "  <tr>";                           
                                                  $items.= "                <td  align=\"center\">".utf8_encode($row[$i]['It_codReg'])."</td>";
                                                  $items.= "                <td>".utf8_encode($row[$i]['it_codMV'])."</td>"; 
                                                  $items.= "                <td>".utf8_encode($row[$i]['it_prod'])."</td>";                                                         
                                                  $items.= "                <td  align=\"center\">".utf8_encode($row[$i]['it_descuento'])."%</td>"; 
                                                  $items.="                 <td  align=\"center\" colspan=\"2\" >".$row[$i]['it_precio']."</td>";                            
                                                  $items.= " </tr>";
                                              }else{
                                                  $precioMatCu=$precioMatCu+$row[$i]['it_precio'];                                  
                                              }
                                              
                                             }
                                             if($precioMatCu!=0){
                                                  $items.= "  <tr>";                           
                                                  $items.= "                <td  align=\"center\">430</td>";
                                                  $items.= "                <td>CURA000001</td>"; 
                                                  $items.= "                <td>Material de Curaci&oacute;n</td>";                                                         
                                                  $items.= "                <td  align=\"center\">--</td>"; 
                                                  $items.="                 <td colspan=\"2\"  align=\"center\">".$precioMatCu."</td>";                            
                                                  $items.= " </tr>";
                                              } 
                                            $items.= "  <tr>";
                                            $items.="                 <td colspan=\"4\" align=\"right\">Subtotal</td>";
                                            $items.= "                <td colspan=\"2\" ><b>$ ".$subtotal."</b></td><td></td>";
                                            $items.= " </tr>";
                                            $items.= "  <tr>";
                                            $items.= "                 <td colspan=\"4\" align=\"right\">Descuento</td>";
                                            $items.= "                <td colspan=\"2\" >$ ".$descuento."</td><td></td>";
                                            $items.= " </tr>";
                                            $items.= "  <tr>";
                                            $items.= "                 <td colspan=\"4\" align=\"right\">Importe Total</td>";
                                            $items.= "                <td colspan=\"2\" ><b>$ ".$total."</b></td><td></td>";
                                            $items.= " </tr>";

                if($banco && $terminacion){
                    $datosTarjeta='<tr>
                                        <td colspan="2">
                                            Banco: <b>'.$banco.'</b>
                                        </td>
                                        <td colspan="3">
                                            Terminaci&oacute;n: <b>'.$terminacion.'</b>
                                        </td>                       
                                    </tr>';
                }else{
                    $datosTarjeta='';
                }

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
                                        <th colspan="6" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES PARA FACTURAR                 No. folio: '.$noFolioRe.'
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$fol.'</b>
                                        </td>
                                        <td colspan="2" style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.utf8_decode($nombre).'</b>
                                        </td>
                                        <td colspan="3" style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Unidad: <b>'.utf8_decode($unidad).'</b>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            RFC: <b>'.$rfc.'</b>
                                        </td>
                                        <td colspan="2">
                                            Raz&oacute;n social o nombre: <b>'.utf8_decode($nom).'</b>
                                        </td>
                                        <td colspan="3">
                                            Calle: <b>'.utf8_decode($calle).'</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            No. Exterior: <b>'.$noEx.'</b>
                                        </td>
                                        <td>
                                            No. Interior: <b>'.$noInt.'</b>
                                        </td>
                                        <td colspan="4">
                                            Colonia: <b>'.utf8_decode($colonia).'</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Delegaci&oacute;n: <b>'.utf8_decode($delegacion).'</b>
                                        </td>
                                        <td>
                                            C&oacute;digo Postal: <b>'.$codPos.'</b>
                                        </td>
                                        <td colspan="3">
                                            Referencia: <b>'.$facref.'</b>
                                        </td>
                                    </tr>
                                    '.$items.'
                                    <tr>                

                                        <td colspan="2">
                                            Correo: <b>'.$correo.'</b>
                                        </td>
                                        <td>
                                            Persona que Cobra: <b>'.utf8_decode($pcobra). '</b>
                                        </td>
                                        <td colspan="2">
                                            M&eacute;dico: <b>'.utf8_decode($medico).'</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            M&eacute;todo de Pago: <b>'.$pago.'</b>
                                        </td>
                                        <td colspan="3">
                                            Observaciones: <b>'.utf8_decode($obs).'</b>
                                        </td>                       
                                    </tr>
                                    '.$datosTarjeta.'
                                </table>
                                </BODY>
                                </HTML>         
                ';
            $mimemail->set_from("facparticulares_noReply@medicavial.com.mx");
            $mimemail->set_to("chernandez@medicavial.com.mx");
            //$mimemail->set_to("enriqueerick@gmail.com");
            $mimemail->add_bcc("egutierrez@medicavial.com.mx");
            $mimemail->set_subject("Solicitud de Factura ".$fol);
            $mimemail->set_html($contenido);
            $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
            if ($mimemail->send()){
                //$query="update Expediente SET Fact_sol=1, Fact_solfecha=now() where Exp_folio='".$fol."'";
                //$rs=mysql_query($query,$conn);
                /*$query="UPDATE Particulares_fac SET Par_fecEnvio=now(), Par_monto=".$total." where Exp_folio='".$fol."' and Recibo_cont=".$noFolioRe;
                $rs=mysql_query($query,$conn);*/
                $query="UPDATE reciboParticulares SET Recibo_facturado='S' where Exp_folio=:Exp_folio and Recibo_cont= :Recibo_cont";
                $temporal = $db->prepare($query);
                $temporal->bindParam("Exp_folio", $fol);
                $temporal->bindParam("Recibo_cont", $noFolioRe);   
                if ($temporal->execute()){
                    $respuesta = array('respuesta' => 'exito');
                }else{
                    $respuesta = array('respuesta' => 'error');
                }
            }else {
                echo "error";
            }

        ////////////////////////////////////////////////////// fin  //////////////////////////////////////////////
        

    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}



if($funcion == 'guardaVitales'){
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $db = conectarMySQL();    

    $tem        =$datos->tem;
    $talla      =$datos->talla;
    $peso       =$datos->peso;
    $frecResp   =$datos->frecResp;
    $frecCard   =$datos->frecCard;
    $TenArt     =$datos->sistole;
    $obs        =$datos->obs;

    try{ 
        
        $query="SELECT * FROM Nota_med_red where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $vitales = $result->fetch();
        if($vitales){
            $query1="UPDATE Nota_med_red SET Not_temperatura=:Not_temperatura,Not_talla=:Not_talla,Not_peso=:Not_peso,Not_fr=:Not_fr,Not_fc=:Not_fc,Not_ta=:Not_ta,Not_obs_vitales=:Not_obs_vitales
                where Exp_folio=:Exp_folio";   
        }else{
            $query1="INSERT INTO Nota_med_red (Not_temperatura,Not_talla,Not_peso,Not_fr,Not_fc,Not_ta,Not_obs_vitales,Exp_folio)
                 VALUES(:Not_temperatura,:Not_talla,:Not_peso,:Not_fr,:Not_fc,:Not_ta,:Not_obs_vitales,:Exp_folio)";             
        }
    $temporal = $db->prepare($query1);
    $temporal->bindParam("Not_temperatura", $tem);
    $temporal->bindParam("Not_talla", $talla);   
    $temporal->bindParam("Not_peso", $peso);   
    $temporal->bindParam("Not_fr", $frecResp);   
    $temporal->bindParam("Not_fc", $frecCard);
    $temporal->bindParam("Not_ta", $TenArt);
    $temporal->bindParam("Not_obs_vitales", $obs);
    $temporal->bindParam("Exp_folio", $fol);

    if ($temporal->execute()){
        $query="Select Vit_clave,Vit_temperatura, Vit_talla, Vit_peso, Vit_ta, Vit_fc, Vit_fr , Vit_imc , Vit_observaciones, Vit_fecha, Usu_registro, IMC_categoria, IMC_comentario From Vitales  Inner Join IMC on IMC.IMC_clave=Vitales.IMC_clave  Where Exp_folio='".$fol."' order by Vit_clave desc";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;   
} 

if($funcion == 'guardaVitalesP'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $db = conectarMySQL();    

    $fol=$_GET['fol'];
    $usr=$_GET['usr'];

    $tem        =$datos->tem;
    $talla      =$datos->talla;
    $peso       =$datos->peso;
    $frecResp   =$datos->frecResp;
    $frecCard   =$datos->frecCard;
    $sistole    =$datos->sistole;
    $astole     =$datos->astole;
    $obs        =$datos->obs;
    $fecha      =date("Y-m-d H:i:s");

    $TenArt= $sistole."/".$astole;

    $imc= $peso/(($talla/100)*($talla/100));
    $imc= number_format($imc,2);
    if($imc<16.00)                 $indice=1;
    if($imc>=16.00 && $imc<=16.99) $indice=2;
    if($imc>=17.00 && $imc<=18.49) $indice=3;
    if($imc>=18.50 && $imc<=24.99) $indice=4;
    if($imc>=25.00 && $imc<=29.99) $indice=5;
    if($imc>=30.00 && $imc<=34.99) $indice=6;
    if($imc>=35.00 && $imc<=39.99) $indice=7;
    if($imc>=40.00 && $imc<=44.99) $indice=8;
    if($imc>=45.00)                $indice=9;

    try{ 
        
        $query="Insert into Vitales (Exp_folio, Vit_temperatura, Vit_talla, Vit_peso, Vit_ta, Vit_fc, Vit_fr, Vit_imc , IMC_clave, Vit_observaciones, Vit_fecha, Usu_registro)
                    values(:Exp_folio, :Vit_temperatura,:Vit_talla,:Vit_peso,:Vit_ta,:Vit_fc,:Vit_fr,:Vit_imc,:IMC_clave,:Vit_observaciones,:Vit_fecha,:Usu_registro)";
        
    $temporal = $db->prepare($query);
    $temporal->bindParam("Vit_temperatura", $tem);
    $temporal->bindParam("Vit_talla", $talla);   
    $temporal->bindParam("Vit_peso", $peso);   
    $temporal->bindParam("Vit_fr", $frecResp);   
    $temporal->bindParam("Vit_fc", $frecCard);
    $temporal->bindParam("Vit_ta", $TenArt);
    $temporal->bindParam("Vit_imc", $imc);
    $temporal->bindParam("IMC_clave", $indice);
    $temporal->bindParam("Vit_observaciones", $obs);
    $temporal->bindParam("Vit_fecha", $fecha);
    $temporal->bindParam("Usu_registro", $usr);
     $temporal->bindParam("Exp_folio", $fol);

    if ($temporal->execute()){
        $query="Select Vit_clave,Vit_temperatura, Vit_talla, Vit_peso, Vit_ta, Vit_fc, Vit_fr , Vit_imc , Vit_observaciones, Vit_fecha, Usu_registro, IMC_categoria, IMC_comentario From Vitales  Inner Join IMC on IMC.IMC_clave=Vitales.IMC_clave  Where Exp_folio='".$fol."' order by Vit_clave desc";
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
}   
    $db = null;   
} 

if($funcion == 'guardaVitalesSub'){

    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $db = conectarMySQL();    

    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $sub=$_GET['sub'];

    $tem        =$datos->tem;
    $talla      =$datos->talla;
    $peso       =$datos->peso;
    $frecResp   =$datos->frecResp;
    $frecCard   =$datos->frecCard;
    $sistole    =$datos->sistole;
    $astole     =$datos->astole;
    $obs        =$datos->obs;

    $TenArt= $sistole."/".$astole;

    $fecha= date('Y-m-d');
    $hora= date('H:i:s');

    $imc= $peso/(($talla/100)*($talla/100));
    $imc= number_format($imc,2);
    if($imc<16.00)                 $indice=1;
    if($imc>=16.00 && $imc<=16.99) $indice=2;
    if($imc>=17.00 && $imc<=18.49) $indice=3;
    if($imc>=18.50 && $imc<=24.99) $indice=4;
    if($imc>=25.00 && $imc<=29.99) $indice=5;
    if($imc>=30.00 && $imc<=34.99) $indice=6;
    if($imc>=35.00 && $imc<=39.99) $indice=7;
    if($imc>=40.00 && $imc<=44.99) $indice=8;
    if($imc>=45.00)                $indice=9;

    try{         
        $query="Insert into VitalesSub (Exp_folio,VitSub_subCons, VitSub_temperatura, VitSub_talla, VitSub_peso, VitSub_ta, VitSub_fc, VitSub_fr, VitSub_imc , IMC_clave, VitSub_observaciones, VitSub_fecha, VitSub_hora, Usu_registro)
                    values(:Exp_folio, :VitSub_subCons, :VitSub_temperatura,:VitSub_talla,:VitSub_peso,:VitSub_ta,:VitSub_fc,:VitSub_fr,:VitSub_imc,:IMC_clave,:VitSub_observaciones,:VitSub_fecha,:VitSub_hora,:Usu_registro)";
        
    $temporal = $db->prepare($query);
    $temporal->bindParam("VitSub_subCons", $sub);
    $temporal->bindParam("VitSub_temperatura", $tem);
    $temporal->bindParam("VitSub_talla", $talla);   
    $temporal->bindParam("VitSub_peso", $peso);   
    $temporal->bindParam("VitSub_fr", $frecResp);   
    $temporal->bindParam("VitSub_fc", $frecCard);
    $temporal->bindParam("VitSub_ta", $TenArt);
    $temporal->bindParam("VitSub_imc", $imc);
    $temporal->bindParam("IMC_clave", $indice);
    $temporal->bindParam("VitSub_observaciones", $obs);
    $temporal->bindParam("VitSub_fecha", $fecha);
    $temporal->bindParam("VitSub_hora", $hora);
    $temporal->bindParam("Usu_registro", $usr);
     $temporal->bindParam("Exp_folio", $fol);

    if ($temporal->execute()){
        $query="Select VitSub_temperatura, VitSub_talla, VitSub_peso, VitSub_ta, VitSub_fc, VitSub_fr , VitSub_imc , VitSub_observaciones, VitSub_fecha, Usu_registro, IMC_categoria, IMC_comentario From VitalesSub  Inner Join IMC on IMC.IMC_clave=VitalesSub.IMC_clave  Where Exp_folio='".$fol."' and VitSub_subCons=".$sub;
        $result = $db->query($query);
        $listInter = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listInter);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
}   
    $db = null;   
} 


if($funcion == 'guardaDatAcc'){
    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    //En este caso lo que mando es este objeto JSON {user:username,psw:password}
    $datos = json_decode($postdata);
    $db = conectarMySQL();    

    $cefalea        =$datos->cefalea;
    $conocimiento   =$datos->conocimiento;
    $fecha          =$datos->fecha;
    $llega          =$datos->llega;
    $mareo          =$datos->mareo;
    $mecLesion      =$datos->mecLesion;
    $mecanismo      =$datos->mecanismo;
    $nauseas        =$datos->nauseas;
    $posicion       =$datos->posicion;
    if($posicion=='') $posicion=0;
    $seguridad      =$datos->seguridad;
    $vehiculo       =$datos->vehiculo;
    $vomito         =$datos->vomido;
    $estado         =$datos->estado;
    $glasgow        =$datos->glasgow;

    $stringMecanismo=split(',', $datos->stringMec);
    $stringSeguridad=split(',', $datos->stringSeg);
    $fecha =date("Y-m-d H:i:s");

    try{
    
        $query= "Select * From NotaMedica Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $nota = $result->fetch();
        if($nota){
            $query="UPDATE NotaMedica SET Llega_clave = :Llega_clave, 
                    Not_fechaAcc = :Not_fechaAcc, 
                    TipoVehiculo_clave = :TipoVehiculo_clave, 
                    Posicion_clave = :Posicion_clave, 
                    Not_vomito = :Not_vomito, 
                    Not_mareo = :Not_mareo,
                    Not_nauseas = :Not_nauseas,                      
                    Not_perdioConocimiento= :Not_perdioConocimiento,
                    Not_cefalea=:Not_cefalea,                    
                    Not_obs = :Not_obs,
                    Usu_nombre =:Usu_nombre             
                    WHERE  Exp_folio = :Exp_folio;";  

            $temporal = $db->prepare($query);
            $temporal->bindParam("Llega_clave", $llega);
            $temporal->bindParam("Not_fechaAcc", $fecha);   
            $temporal->bindParam("TipoVehiculo_clave", $vehiculo);   
            $temporal->bindParam("Posicion_clave", $posicion); 
            $temporal->bindParam("Not_vomito", $vomito); 
            $temporal->bindParam("Not_mareo", $mareo); 
            $temporal->bindParam("Not_nauseas", $nauseas);              
            $temporal->bindParam("Not_obs", $mecLesion);
            $temporal->bindParam("Not_perdioConocimiento", $conocimiento);
            $temporal->bindParam("Not_cefalea", $cefalea);
            $temporal->bindParam("Usu_nombre", $usr);
            $temporal->bindParam("Exp_folio", $fol);     

            if ($temporal->execute()){                 
                foreach ($stringSeguridad as $valor) {
                    if ($valor != "" || $valor != 0){
                        $query="Insert into SegNotaMed(Exp_folio, Not_clave, Equi_clave)
                                   Values(:Exp_folio,1,:Equi_clave)";
                        $temporal = $db->prepare($query);
                        $temporal->bindParam("Exp_folio", $fol);
                        $temporal->bindParam("Equi_clave", $valor);   
                        $temporal->execute();   
                    }
                }
                foreach ($stringMecanismo as $value) {
                    if($value !="" || $value!= 0){
                         $query="Insert into MecNotaMed(Exp_folio, Not_clave, Mec_clave)
                                    Values(:Exp_folio,1,:Mec_clave)";
                        $temporal = $db->prepare($query);
                        $temporal->bindParam("Exp_folio", $fol);
                        $temporal->bindParam("Mec_clave", $value);   
                        $temporal->execute();
                    }
                }      
                    $query="SELECT Exp_sexo FROM Expediente WHERE Exp_cancelado=0 and Exp_folio='".$fol."'";
                    $result = $db->query($query);
                    $sexo = $result->fetch();

                    $query= "Select * From ObsNotaMed Where Exp_folio='".$fol."'";
                    $result = $db->query($query);
                    $obsNot = $result->fetch();
                    if($obsNot){
                        $query = "UPDATE ObsNotaMed SET ObsNot_edoG='".$estado."', ObsNot_glasgow=".$glasgow." WHERE Exp_folio='".$fol."'";
                    }else{
                        $query = "INSERT INTO ObsNotaMed(Exp_folio,ObsNot_edoG, ObsNot_glasgow) VALUES('".$fol."','".$estado."', ".$glasgow.");";
                    }
                    $temporal = $db->prepare($query);
                    $temporal->execute();

                    $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol,'sexo'=> $sexo['Exp_sexo']);            

            
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
            }
                  
        }else{
            $query="Insert into NotaMedica(Exp_folio, Llega_clave, Not_fechaAcc, TipoVehiculo_clave, Posicion_clave, Not_vomito,Not_mareo,Not_nauseas, Not_perdioConocimiento, Not_cefalea, Not_obs, Usu_nombre, Not_fechareg)
                            Values(:Exp_folio,:Llega_clave,:Not_fechaAcc,:TipoVehiculo_clave,:Posicion_clave,:Not_vomito,:Not_mareo,:Not_nauseas,:Not_perdioConocimiento,:Not_cefalea,:Not_obs,:Usu_nombre,:Not_fechareg)";    

            $temporal = $db->prepare($query);
            $temporal->bindParam("Llega_clave", $llega);
            $temporal->bindParam("Not_fechaAcc", $fecha);   
            $temporal->bindParam("TipoVehiculo_clave", $vehiculo);   
            $temporal->bindParam("Posicion_clave", $posicion);   
            $temporal->bindParam("Not_vomito", $vomito);
            $temporal->bindParam("Not_mareo", $mareo);
            $temporal->bindParam("Not_nauseas", $nauseas);
            $temporal->bindParam("Not_perdioConocimiento", $conocimiento);
            $temporal->bindParam("Not_cefalea", $cefalea);
            $temporal->bindParam("Not_obs", $mecLesion);
            $temporal->bindParam("Usu_nombre", $usr);
            $temporal->bindParam("Not_fechareg", $fecha);
            $temporal->bindParam("Exp_folio", $fol);

            if ($temporal->execute()){ 
                foreach ($stringSeguridad as $valor) {
                    if ($valor != "" || $valor != 0){
                        $query="Insert into SegNotaMed(Exp_folio, Not_clave, Equi_clave)
                                   Values(:Exp_folio,1,:Equi_clave)";
                        $temporal = $db->prepare($query);
                        $temporal->bindParam("Exp_folio", $fol);
                        $temporal->bindParam("Equi_clave", $valor);   
                        $temporal->execute();   
                    }
                }
                foreach ($stringMecanismo as $value) {
                    if($value !="" || $value!= 0){
                         $query="Insert into MecNotaMed(Exp_folio, Not_clave, Mec_clave)
                                    Values(:Exp_folio,1,:Mec_clave)";
                        $temporal = $db->prepare($query);
                        $temporal->bindParam("Exp_folio", $fol);
                        $temporal->bindParam("Mec_clave", $value);   
                        $temporal->execute();
                    }
                }
                $query="SELECT Exp_sexo, Uni_clave FROM Expediente WHERE Exp_cancelado=0 and Exp_folio='".$fol."'";
                $result = $db->query($query);
                $sexo = $result->fetch();

                $query= "Select * From ObsNotaMed Where Exp_folio='".$fol."'";
                $result = $db->query($query);
                $obsNot = $result->fetch();
                if($obsNot){
                    $query = "UPDATE ObsNotaMed SET ObsNot_edoG='".$estado."', ObsNot_glasgow=".$glasgow." WHERE Exp_folio='".$fol."'";
                }else{
                    $query = "INSERT INTO ObsNotaMed(Exp_folio,ObsNot_edoG, ObsNot_glasgow) VALUES('".$fol."','".$estado."', ".$glasgow.");";
                }
                $temporal = $db->prepare($query);
                $temporal->execute();

                 $sql="UPDATE EstatusRegistroNota SET ERN_contador=ERN_contador-1, ERN_fecActual=now() where Uni_clave=".$sexo['Uni_clave'].";";
                $result = $db->query($sql);

                
                $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol,'sexo'=> $sexo['Exp_sexo']);
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
            }
        }
        
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null; 
} 

if($funcion=='guardaEmbarazo'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);

    $controlGine        =$datos->controlGine;
    $desc      =$datos->desc;
    $dolor       =$datos->dolor;
    $fcFet   =$datos->fcFet;
    $justif   =$datos->justif;
    $movFet    =$datos->movFet;
    $semanas     =$datos->semanas;    
    $db = conectarMySQL(); 
    try{
    $query="Select Exp_folio From Embarazo Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $embarazo = $result->fetch();
    $folioEmb=$embarazo['Exp_folio'];
    if($folioEmb==""|| $folioEmb==null){
        $query="Insert into Embarazo(Exp_folio, Emb_semGestacion, Emb_dolAbdominal, Emb_descripcion, Emb_fcf, Emb_movFetales, Emb_ginecologia, Emb_obs)
                         Values(:Exp_folio,:Emb_semGestacion,:Emb_dolAbdominal,:Emb_descripcion,:Emb_fcf,:Emb_movFetales,:Emb_ginecologia,:Emb_obs)";
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Emb_semGestacion", $semanas);  
        $temporal->bindParam("Emb_dolAbdominal", $dolor);  
        $temporal->bindParam("Emb_descripcion", $desc);  
        $temporal->bindParam("Emb_fcf", $fcFet);  
        $temporal->bindParam("Emb_movFetales", $movFet);  
        $temporal->bindParam("Emb_ginecologia", $controlGine);  
        $temporal->bindParam("Emb_obs", $justif); 
         if ($temporal->execute()){
            $query="SELECT Emb_semGestacion, Emb_dolAbdominal, Emb_descripcion, Emb_movFetales, Emb_fcf, Emb_ginecologia, Emb_obs FROM Embarazo Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listEmbarazo = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listEmbarazo);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }
    }else{
        $respuesta = array('respuesta' => 'lleno');
        echo json_encode($respuesta);
    }
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    
    $db = null;  
}

if($funcion=='guardaLesion'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $cuerpo        =$datos->cuerpo;
    $lesion      =$datos->lesion;   
    $db = conectarMySQL(); 
    try{    
        $query="Insert into LesionNota(Exp_folio, Les_clave, Cue_clave)
                       Values(:Exp_folio,:Les_clave,:Cue_clave)";
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Les_clave", $lesion);  
        $temporal->bindParam("Cue_clave", $cuerpo);         
         if ($temporal->execute()){
            $query="SELECT Les_nombre, Cue_nombre, LesN_clave FROM LesionNota inner join Lesion on Lesion.Les_clave=LesionNota.Les_clave inner join Cuerpo on Cuerpo.Cue_clave=LesionNota.Cue_clave Where Exp_folio='".$fol."' ORDER BY LesN_clave ASC";
            $result = $db->query($query);
            $listLesiones = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listLesiones);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    
    $db = null;  
}

if($funcion == 'eliminaLesion'){
    $fol=$_GET['fol'];
    $cveLes=$_GET['cveLes'];
    $db = conectarMySQL();
    $query="DELETE FROM LesionNota WHERE Exp_folio = :Exp_folio and LesN_clave = :LesN_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Exp_folio', $fol);
    $stmt->bindParam('LesN_clave', $cveLes);
    if ($stmt->execute()){
        $query="SELECT Les_nombre, Cue_nombre, LesN_clave FROM LesionNota inner join Lesion on Lesion.Les_clave=LesionNota.Les_clave inner join Cuerpo on Cuerpo.Cue_clave=LesionNota.Cue_clave Where Exp_folio='".$fol."' ORDER BY LesN_clave ASC";
        $result = $db->query($query);
        $listLesiones = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listLesiones);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion=='guardaEdoGral'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $estado        =$datos->estado;   
    $db = conectarMySQL(); 
    $query= "select * from ObsNotaMed where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $edoGral = $result->fetch();
    try{    
        if($edoGral){
            $query="UPDATE ObsNotaMed SET ObsNot_edoG=:ObsNot_edoG WHERE Exp_folio=:Exp_folio";                        
        }else{
            $query="INSERT INTO  ObsNotaMed( ObsNot_edoG,Exp_folio)
                             Values(:ObsNot_edoG,:Exp_folio)";                       
        }
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("ObsNot_edoG", $estado);          
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;  
}
if($funcion=='guardaEstudios'){
    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $uniClave=$_GET['uniClave'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $rx         =$datos->rx;
    $obs        =$datos->obs;
    $interp     =$datos->interp;
    $db = conectarMySQL(); 
    $query= "select * from ObsNotaMed where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $edoGral = $result->fetch();
    $fecha = date("Y-m-d H:i:s");
    try{    
       
            $query="Insert into RxSolicitados(Exp_folio, Rx_clave, Rxs_Obs, Rxs_desc, Usu_solicita, Fecha_solicita, Estatus_rx, Uni_clave, Rxs_desde)
                          Values(:Exp_folio,:Rx_clave,:Rxs_Obs,:Rxs_desc,:Usu_solicita,:Fecha_solicita,'PENDIENTE',:Uni_clave,'NOTA MEDICA')";                               
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Rx_clave", $rx);
        $temporal->bindParam("Rxs_Obs", $obs); 
        $temporal->bindParam("Rxs_desc", $interp);  
        $temporal->bindParam("Usu_solicita", $usr); 
        $temporal->bindParam("Fecha_solicita", $fecha); 
        $temporal->bindParam("Uni_clave", $uniClave);             
         if ($temporal->execute()){
            $query="SELECT Rxs_clave, Rx_nombre, Rxs_Obs, Rxs_desc 
            FROM RxSolicitados inner Join Rx on Rx.Rx_clave=RxSolicitados.Rx_clave 
            Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listEstSol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;  
}

if($funcion=='guardaOtrosEstudios'){
    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $uniClave=$_GET['uniClave'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $estudio    =$datos->estudio;
    $obs        =$datos->justObs;    
    $db = conectarMySQL();     
    try{    
       
        $query="Insert into EstSolicitados(Exp_folio, Estu_clave, EstuS_Obs)
                                Values(:Exp_folio,:Estu_clave,:EstuS_Obs)";
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Estu_clave", $estudio);
        $temporal->bindParam("EstuS_Obs", $obs);                
         if ($temporal->execute()){
            $query="SELECT EstuS_clave, Estu_nombre, EstuS_Obs 
            FROM EstSolicitados  
            inner Join Estudios on EstSolicitados.Estu_clave=Estudios.Estu_clave 
            Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listEstSol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;  
}

if($funcion=='guardaEstudiosSub'){
    $fol=$_GET['fol'];
    $cons=$_GET['subCons'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $rx         =$datos->rx;
    $obs        =$datos->obs;
    $interp     =$datos->interp;
    $db = conectarMySQL();     
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');
    try{    
       
            $query="Insert Into RxSubSolicitados(Exp_folio, Rx_clave, Rxsub_obs, Rxsub_desc, Rxsub_fecha, Rxsub_hora,Sub_cons)
                                  Values(:Exp_folio,:Rx_clave,:Rxsub_obs,:Rxsub_desc,:Rxsub_fecha,:Rxsub_hora,:Sub_cons)";                               
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Rx_clave", $rx);
        $temporal->bindParam("Rxsub_obs", $obs); 
        $temporal->bindParam("Rxsub_desc", $interp);  
        $temporal->bindParam("Rxsub_fecha", $fecha); 
        $temporal->bindParam("Rxsub_hora", $hora);             
        $temporal->bindParam("Sub_cons", $cons);             
         if ($temporal->execute()){            
            $query="SELECT Rxsub_clave, Rx_nombre, Rxsub_Obs, Rxsub_desc 
            FROM RxSubSolicitados inner Join Rx on Rx.Rx_clave=RxSubSolicitados.Rx_clave 
            Where Exp_folio='".$fol."' and Sub_cons=".$cons;
            $result = $db->query($query);
            $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listEstSol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');            
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );    
    echo json_encode($respuesta);
}
    $db = null;  
}


if($funcion == 'eliminaEstRealizado'){
    $fol=$_GET['fol'];
    $cveEst=$_GET['cveEst'];
    $db = conectarMySQL();
    $query="Delete from RxSolicitados where Rxs_clave = :Rxs_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Rxs_clave', $cveEst);
    if ($stmt->execute()){
        $query="SELECT Rxs_clave, Rx_nombre, Rxs_Obs, Rxs_desc 
            FROM RxSolicitados inner Join Rx on Rx.Rx_clave=RxSolicitados.Rx_clave 
            Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listEstSol);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'eliminaOtrosEstRealizado'){
    $fol=$_GET['fol'];
    $cveEst=$_GET['cveEst'];
    $db = conectarMySQL();
    $query="Delete from EstSolicitados where EstuS_clave = :EstuS_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('EstuS_clave', $cveEst);
    if ($stmt->execute()){
        $query="SELECT EstuS_clave, Estu_nombre, EstuS_Obs 
            FROM EstSolicitados  
            inner Join Estudios on EstSolicitados.Estu_clave=Estudios.Estu_clave 
            Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listEstSol);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'eliminaEstRealizadoSub'){    
    $fol=$_GET['fol'];
    $cveEst=$_GET['cveEst'];
    $db = conectarMySQL();
    $query="Delete from RxSubSolicitados where Rxsub_clave = :Rxsub_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Rxsub_clave', $cveEst);
    if ($stmt->execute()){
        $query="SELECT Rxsub_clave, Rx_nombre, Rxsub_Obs, Rxsub_desc 
        FROM RxSubSolicitados inner Join Rx on Rx.Rx_clave=RxSubSolicitados.Rx_clave 
        Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listEstSol = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listEstSol);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}

if($funcion=='guardaProcedimientos'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $procedimiento         =$datos->procedimiento;
    $obs        =$datos->obs;   
    $db = conectarMySQL(); 
    try{           
            $query="Insert into NotaProcedimientos(Exp_folio, Pro_clave, Nproc_obs)
                                         Values(:Exp_folio,:Pro_clave,:Nproc_obs)";                               
        $temporal = $db->prepare($query);
        $temporal->bindParam("Exp_folio", $fol);
        $temporal->bindParam("Pro_clave", $procedimiento);
        $temporal->bindParam("Nproc_obs", $obs);                 
         if ($temporal->execute()){
            $query="SELECT Nproc_clave, Pro_nombre, Nproc_obs  FROM NotaProcedimientos inner Join Procedimientos on Procedimientos.Pro_clave=NotaProcedimientos.Pro_clave Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listProcedimientos = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listProcedimientos);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;  
}

if($funcion=='guardaDiagnostico'){
 $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $diagnostico         =$datos->diagnostico;
    $glasgow    =$datos->glasgow;
    $obs        =$datos->obs; 
    $cron       =$datos->cron; 
    if($cron==null) $cron=''; 
    $db = conectarMySQL(); 
    try{    
       
            $query="Update ObsNotaMed Set ObsNot_diagnosticoRx=:ObsNot_diagnosticoRx,ObsNot_obs=:ObsNot_obs, ObsNot_glasgow=:ObsNot_glasgow, ObsNot_cronDegRele=:ObsNot_cronDegRele WHERE Exp_folio=:Exp_folio";                               
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("ObsNot_diagnosticoRx", $diagnostico);
            $temporal->bindParam("ObsNot_obs", $obs);
            $temporal->bindParam("ObsNot_glasgow", $glasgow);
            $temporal->bindParam("ObsNot_cronDegRele", $cron);                 
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;  
}

if($funcion=='guardaDiagnosticoSub'){
    $fol=$_GET['fol'];
    $noSubsec=$_GET['subCons'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $diagnostico         =$datos->diagnostico;
    $obs        =$datos->obs;   
    $db = conectarMySQL(); 
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');     
    try{
            $query="UPDATE Subsecuencia Set Sub_diagnostico=:Sub_diagnostico,Sub_obs=:Sub_obs,Sub_fecha=:Sub_fecha,Sub_hora=:Sub_hora  WHERE Exp_folio=:Exp_folio and Sub_cons=:Sub_cons; 
                    UPDATE ObsNotaMed SET ObsNot_diagnosticoMomento=:Sub_diagnostico WHERE Exp_folio=:Exp_folio";                               
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sub_diagnostico", $diagnostico);
            $temporal->bindParam("Sub_obs", $obs); 
            $temporal->bindParam("Sub_cons", $noSubsec); 
            $temporal->bindParam("Sub_fecha", $fecha);
            $temporal->bindParam("Sub_hora", $hora);                
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;  
}

/********************************   guardar lesion Aministrativa ******************************/

if($funcion=='guardaLesionAdministrativa'){
    $fol=$_GET['fol'];
    echo $fol;    
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $folio        =$datos->fol;
    $lesion             =$datos->cveLesion;
    $tipoLesion         =$datos->tipoLesion;
    $lesionUnica        =$datos->lesionUnica;
    $lesionMultiple     =$datos->lesionMultiple;
    $lesionOtro         =$datos->lesionOtro;
    $lesionSecuandaria  =$datos->example1model;
    $lesionCodificada   =$datos->lesionCodificada;
    $importeLesion      ='';

    if(!$lesionCodificada||$lesionCodificada==null){
        $lesionCodificada='-';
    }

    $lesionJunta='';
    if($tipoLesion==3){
        $lesionJunta='L_LEVE'; 
        $lesTipoCve = 1;     
    }elseif($tipoLesion==4){
        $lesionJunta='S_SMPL';      
        $lesTipoCve = 2;
    }elseif($tipoLesion==1){        
        $lesionJunta=$lesionUnica;
        $lesTipoCve = 3;
    }elseif($tipoLesion==2){        
        $lesionJunta=$lesionMultiple;       
        $lesTipoCve = 4;
    }elseif($tipoLesion==5){        
        $lesionJunta=$lesionOtro;       
         $lesTipoCve = 5;
    }
    if($producto==10){
        $lesionJunta='P_PART';  
    }
    $db = conectarMySQL();

    $query = "SELECT Clave_lesionCia FROM LesionEquivalencia where Clave_lesionMV='".$lesionJunta."'";
    $result = $db->query($query);
    $datoLesion = $result->fetch();
    $lesionCia  = $datoLesion['Clave_lesionCia'];

    $query = "SELECT PRO_clave, LOC_claveint, Cia_clave, Esc_clave from Expediente
              INNER JOIN Unidad on Expediente.Uni_clave = Unidad.Uni_clave 
              WHERE Exp_folio='".$fol."'";
    $result = $db->query($query);
    $datos = $result->fetch();
    $producto       = $datos['PRO_clave'];
    $localidad      = $datos['LOC_claveint'];
    $compania       = $datos['Cia_clave'];
    $escolaridad    = $datos['Esc_clave'];
    if($compania==7){
        if($producto==1){
            if($localidad == 27 || $localidad==17 || $localidad==41 || $localidad==47){
                $query="select TAD_importe from TabuladorDetalle where TAB_clave=1 and LES_clave='".$lesionCia."'";
                $result = $db->query($query);
                $rs = $result->fetch();
                $importeLesion = $rs['TAD_importe'];
            }else{
                if($localidad != 29 && $localidad!=18){

                    $query="select TAD_importe from TabuladorDetalle where TAB_clave=2 and LES_clave='".$lesionCia."'";
                    $result = $db->query($query);
                    $rs = $result->fetch();
                    $importeLesion = $rs['TAD_importe'];
                }   
            }
        }elseif($producto==2){          
            if($escolaridad==1||$escolaridad==2||$escolaridad==3){
                $query="select TAD_importe from TabuladorDetalle where TAB_clave=3 and LES_clave='".$lesionCia."'";
                $result = $db->query($query);
                $rs = $result->fetch();
                $importeLesion = $rs['TAD_importe'];        
            }elseif($escolaridad==4||$escolaridad==5||$escolaridad==7){
                $query="select TAD_importe from TabuladorDetalle where TAB_clave=3 and LES_clave='".$lesionCia."'";
                $result = $db->query($query);
                $rs = $result->fetch();
                $importeLesion = $rs['TAD_importe'];        
            }
                
        }elseif($producto==12&&($localidad == 29 || $localidad==18)){           
                $query="select TAD_importe from TabuladorDetalle where TAB_clave=5 and LES_clave='".$lesionCia."'";
                $result = $db->query($query);
                $rs = $result->fetch();
                $importeLesion = $rs['TAD_importe'];        
        }
    }
    if($tipoLesion==2){     
        foreach($lesionSecuandaria as $clave ){           
            try{                  
                $query="INSERT INTO LesionSecundaria(Exp_folio,LesionSec_id) 
                        VALUES('".$fol."','".$clave->id."')";
                $result = $db->query($query);                                                
            }catch(Exception $e){
              $error='error';
              return $error;
            }                              
        }            
    } 
    try{           
            $query="Update ObsNotaMed Set ObsNot_tipoLesion=:ObsNot_tipoLesion,Clave_lesionMV=:Clave_lesionMV,Clave_lesionCia=:Clave_lesionCia, TAD_importe=:TAD_importe,Clave_lesionCodificada=:Clave_lesionCodificada WHERE Exp_folio=:Exp_folio";                               
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);                       
            $temporal->bindParam("ObsNot_tipoLesion", $lesTipoCve);                 
            $temporal->bindParam("Clave_lesionMV", $lesionJunta);                 
            $temporal->bindParam("Clave_lesionCia", $lesionCia);
            $temporal->bindParam("Clave_lesionCodificada", $lesionCodificada);                 
            $temporal->bindParam("TAD_importe", $importeLesion);                 
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;  
}

/**********************************************************************************************/


if($funcion == 'eliminaProcedimiento'){
    $fol=       $_GET['fol'];
    $proClave=  $_GET['proClave'];
    $db = conectarMySQL();
    $query="Delete from NotaProcedimientos where Nproc_clave = :Nproc_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Nproc_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Nproc_clave, Pro_nombre, Nproc_obs  FROM NotaProcedimientos inner Join Procedimientos on Procedimientos.Pro_clave=NotaProcedimientos.Pro_clave Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listProcedimientos = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listProcedimientos);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

/**********************         suministros Symio          ***********************************/
if($funcion == 'listMedicSymio'){
    $uni=$_GET['uni'];

    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
        $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
    case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }

    $db = conectarMySQL();
    $query="select distinct ".$tabla.".Clave_producto, Stock, Descripcion, Sym_indicacion, Sym_forma_far  from ".$tabla."
            inner join SymioCuadroBasico on ".$tabla.".Clave_producto=SymioCuadroBasico.Clave_producto
            where ".$tabla.".Clave_producto like 'MED%' and Stock>0 order by Descripcion asc";
    $result = $db->query($query);
    $listProcedimientos = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listProcedimientos);
    $db = null;    
}
if($funcion == 'listOrtesisSymio'){
    $uni=$_GET['uni'];

    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
        $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
    case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }    
    $db = conectarMySQL();
    $query="select distinct  SymioCuadroBasico.Clave_producto,Sym_medicamento, ".$tabla.".Stock from ".$tabla."
            inner join SymioCuadroBasico on ".$tabla.".Clave_producto=SymioCuadroBasico.Clave_producto
            where ".$tabla.".Clave_producto like 'ORT%' and Stock>0";
    $result = $db->query($query);
    $listOrtesisSymio = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listOrtesisSymio);
    $db = null;    
}
if($funcion == 'listMed'){
    $clave=$_GET['clave'];
    $uni  =$_GET['uni'];
    
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
       $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
    case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }       
    $db = conectarMySQL();
    $query="select SymioCuadroBasico.Clave_producto,Sym_medicamento from SymioCuadroBasico 
            inner join ".$tabla." on SymioCuadroBasico.Clave_producto=".$tabla.".Clave_producto
            where clave_denominacion=".$clave." and ".$tabla.".Stock>0";
    $result = $db->query($query);
    $listMedic = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMedic);
    $db = null;    
}
if($funcion == 'detalleMed'){
    $clave=$_GET['clave'];
    $uni = $_GET['uni'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
        $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
    case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }
    $db = conectarMySQL();
    $query="select * from SymioCuadroBasico 
            inner join ".$tabla." on SymioCuadroBasico.Clave_producto=".$tabla.".Clave_producto
    where SymioCuadroBasico.Clave_producto='".$clave."'";
    $result = $db->query($query);
    $listMedic = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($listMedic);
    $db = null;    
}

if($funcion=='guardaMedicamentoSymio'){
    $fol= $_GET['fol'];
    $uni =  $_GET['uni'];    
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
       $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
    case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $cveMed             =$datos->sustAct;    
    $cantidad           =$datos->cantidad;
    $presentacion       =$datos->presentacion;
    $posolog??a          =$datos->posologia;
    $stock              =$datos->stock;
    $restaStock        = $stock - $cantidad;
    $fecha              = date("Y-m-d H:i:s");

    $db = conectarMySQL(); 
    try{    
       
            $query="Insert into NotaSuministro(Exp_folio, Sum_clave, Nsum_obs, Nsum_Cantidad, Nsum_fecha)
                             Values(:Exp_folio,:Sum_clave,:Nsum_obs,:Nsum_Cantidad,:Nsum_fecha)";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sum_clave", $cveMed);
            $temporal->bindParam("Nsum_obs", $posolog??a);
            $temporal->bindParam("Nsum_Cantidad", $cantidad);
            $temporal->bindParam("Nsum_fecha", $fecha);

         if ($temporal->execute()){
            $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Stock", $restaStock);
            $temporal->bindParam("Clave_producto", $cveMed);
            $temporal->execute();
            $query="SELECT Nsum_clave, Sym_medicamento, Nsum_obs, Nsum_Cantidad  FROM NotaSuministro inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =NotaSuministro.Sum_clave Where Exp_folio='".$fol."' ";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}  
    $db = null;  
}

if($funcion=='guardaMedicamentoSymioSub'){
    $fol= $_GET['fol'];
    $uni =  $_GET['uni'];
    $cont = $_GET['cont'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
    case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $cveMed             =$datos->sustAct;    
    $cantidad           =$datos->cantidad;
    $presentacion       =$datos->presentacion;
    $posolog??a          =$datos->posologia;
    $stock              =$datos->stock;
    $restaStock        = $stock - $cantidad;

    $fecha= date('Y-m-d');
    $hora= date('H:i:s');

    $db = conectarMySQL(); 
    try{    
       
            $query="Insert into SubSuministros(Exp_folio,Sub_cons, Sum_clave, Subsum_obs, Subsum_Cantidad, Subsum_fecha,Subsum_hora)
                             Values(:Exp_folio,:Sub_cons,:Sum_clave,:Subsum_obs,:Subsum_Cantidad,:Subsum_fecha,:Subsum_hora)";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sub_cons", $cont);
            $temporal->bindParam("Sum_clave", $cveMed);
            $temporal->bindParam("Subsum_obs", $posolog??a);
            $temporal->bindParam("Subsum_Cantidad", $cantidad);
            $temporal->bindParam("Subsum_fecha", $fecha);
            $temporal->bindParam("Subsum_hora", $hora);

         if ($temporal->execute()){
            $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Stock", $restaStock);
            $temporal->bindParam("Clave_producto", $cveMed);
            $temporal->execute();
            $query="SELECT Subsum_clave, Sym_medicamento, Subsum_obs, Subsum_Cantidad  FROM SubSuministros inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =SubSuministros.Sum_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}  
    $db = null;  
}

if($funcion == 'eliminarMedicamentoSymio'){

    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];    
    $uni=$_GET['uni'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
     case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }    
    $db = conectarMySQL();

    $query="select Nsum_cantidad, Sum_clave From NotaSuministro where Nsum_clave=".$proClave;    
    $result = $db->query($query);
    $cantidad = $result->fetch();
    $claveMed =$cantidad['Sum_clave'];    
    $query="select Stock from ".$tabla." where Clave_producto='".$claveMed."'";
    $result = $db->query($query);
    $stock = $result->fetch();
    $sumaStock =$cantidad['Nsum_cantidad']+$stock['Stock'];    
    $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
    $temporal = $db->prepare($query);
    $temporal->bindParam("Stock", $sumaStock);
    $temporal->bindParam("Clave_producto", $claveMed);
    if($temporal->execute()){
            $query="Delete from NotaSuministro where Nsum_clave = :Nsum_clave";
            $stmt = $db->prepare($query);
            $stmt->bindParam('Nsum_clave', $proClave);
            if ($stmt->execute()){
                $query="SELECT Nsum_clave, Sym_medicamento, Nsum_obs, Nsum_Cantidad  FROM NotaSuministro inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =NotaSuministro.Sum_clave Where Exp_folio='".$fol."'";
                    $result = $db->query($query);
                    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
                    echo json_encode($listMediAgre);
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
                echo json_encode($respuesta);
            } 
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    
    $db = null;    
}

if($funcion == 'eliminarMedicamentoSymioSub'){

    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];    
    $uni=$_GET['uni'];
    $cont = $_GET['cont'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
        $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
     case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }    
    $db = conectarMySQL();    
    $query="select Subsum_cantidad, Sum_clave From SubSuministros where Subsum_clave=".$proClave;    
    $result = $db->query($query);
    $cantidad = $result->fetch();
    $claveMed =$cantidad['Sum_clave'];     
    $query="select Stock from ".$tabla." where Clave_producto='".$claveMed."'";
    $result = $db->query($query);
    $stock = $result->fetch();

    $sumaStock =$cantidad['Subsum_cantidad']+$stock['Stock'];        
    $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
    $temporal = $db->prepare($query);
    $temporal->bindParam("Stock", $sumaStock);
    $temporal->bindParam("Clave_producto", $claveMed);
    if($temporal->execute()){
            $query="Delete from SubSuministros where Subsum_clave = :Subsum_clave";
            $stmt = $db->prepare($query);
            $stmt->bindParam('Subsum_clave', $proClave);
            if ($stmt->execute()){
                $query="SELECT Subsum_clave, Sym_medicamento, Subsum_obs, Subsum_Cantidad  FROM SubSuministros inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =SubSuministros.Sum_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
                    $result = $db->query($query);
                    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
                    echo json_encode($listMediAgre);
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
                echo json_encode($respuesta);
            } 
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    
    $db = null;    
}

if($funcion=='guardaOrtSymio'){
    $fol= $_GET['fol'];
    $uni =  $_GET['uni'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
     case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $cveOrt             =$datos->ortSymio;    
    $cantidad           =$datos->cantidad;
    $indicaciones       =$datos->indicaciones;    

    $db = conectarMySQL(); 
    $query="Select Stock from ".$tabla." where Clave_producto='".$cveOrt."'";
    $result = $db->query($query);
    $Stock = $result->fetch();
    $restaStock = $Stock['Stock']-$cantidad;    
    try{    
       
            $query="Insert into NotaOrtesis(Exp_folio, Ort_clave,Ortpre_clave, Notor_cantidad, Notor_indicaciones)
                             Values(:Exp_folio,:Ort_clave,1, :Notor_cantidad,:Notor_indicaciones)";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Ort_clave", $cveOrt);
            $temporal->bindParam("Notor_indicaciones", $indicaciones);
            $temporal->bindParam("Notor_cantidad", $cantidad);

         if ($temporal->execute()){
            $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Stock", $restaStock);
            $temporal->bindParam("Clave_producto", $cveOrt);
            $temporal->execute();
            $query="SELECT Notor_clave, Sym_medicamento, Notor_indicaciones, Notor_cantidad  FROM NotaOrtesis inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =NotaOrtesis.Ort_clave Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}  
    $db = null;  
}

if($funcion=='guardaOrtSymioSub'){
    $fol= $_GET['fol'];
    $uni =  $_GET['uni'];
    $cont   =$_GET['cont'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
     case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $cveOrt             =$datos->ortSymio;    
    $cantidad           =$datos->cantidad;
    $indicaciones       =$datos->indicaciones; 

    $fecha= date('Y-m-d');
    $hora= date('H:i:s');    

    $db = conectarMySQL(); 
    $query="Select Stock from ".$tabla." where Clave_producto='".$cveOrt."'";
    $result = $db->query($query);
    $Stock = $result->fetch();
    $restaStock = $Stock['Stock']-$cantidad;    
    try{    
       
            $query="Insert into SubOrtesis(Exp_folio, Sub_cons, Ort_clave,Ortpre_clave, Subort_cantidad, Subort_indicaciones,Subort_fecha, Subort_hora)
                             Values(:Exp_folio, :Sub_cons, :Ort_clave,1, :Subort_cantidad,:Subort_indicaciones,:Subort_fecha, :Subort_hora)";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sub_cons", $cont );
            $temporal->bindParam("Ort_clave", $cveOrt);
            $temporal->bindParam("Subort_cantidad", $cantidad);
            $temporal->bindParam("Subort_indicaciones", $indicaciones);
            $temporal->bindParam("Subort_fecha", $fecha);
            $temporal->bindParam("Subort_hora", $hora);

         if ($temporal->execute()){
            $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Stock", $restaStock);
            $temporal->bindParam("Clave_producto", $cveOrt);
            $temporal->execute();
            $query="SELECT Subort_clave, Sym_medicamento, Subort_indicaciones, Subort_cantidad  FROM SubOrtesis inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =SubOrtesis.Ort_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}  
    $db = null;  
}

if($funcion == 'eliminarOrtesisSymio'){

    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];    
    $uni=$_GET['uni'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
     case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }    
    $db = conectarMySQL();

    $query="select Notor_cantidad, Ort_clave From NotaOrtesis where Notor_clave=".$proClave;    
    $result = $db->query($query);
    $cantidad = $result->fetch();    
    $claveMed =$cantidad['Ort_clave'];    
    $query="select Stock from ".$tabla." where Clave_producto='".$claveMed."'";
    $result = $db->query($query);
    $stock = $result->fetch();
    $sumaStock =$cantidad['Notor_cantidad']+$stock['Stock'];    
    $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
    $temporal = $db->prepare($query);
    $temporal->bindParam("Stock", $sumaStock);
    $temporal->bindParam("Clave_producto", $claveMed);
    if($temporal->execute()){

            $query="Delete from NotaOrtesis where Notor_clave = :Notor_clave";
            $stmt = $db->prepare($query);
            $stmt->bindParam('Notor_clave', $proClave);
            if ($stmt->execute()){              
                $query="SELECT Notor_clave, Sym_medicamento, Notor_indicaciones, Notor_cantidad  FROM NotaOrtesis inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =NotaOrtesis.Ort_clave Where Exp_folio='".$fol."'";
                    $result = $db->query($query);
                    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
                    echo json_encode($listMediAgre);
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
                echo json_encode($respuesta);
            } 
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    
    $db = null;    
}


if($funcion == 'eliminarOrtesisSymioSub'){

    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];    
    $uni=$_GET['uni'];
    $cont   =$_GET['cont'];
    switch ($uni){
    case '1':
        $tabla="SymioBotRoma";
        $inventario=419;
        break;
    case '2':
        $tabla="SymioBotSatelite";
        $inventario=468;
        break;
    case '3':
        $tabla="SymioBotPerisur";
        $inventario=425;
        break;
    case '4':
        $tabla="SymioBotPuebla";
        $inventario=473;
        break;
    case '5':
        $tabla="SymioBotMonterrey";
        $inventario=544;
        break;
    case '6':
        $tabla="SymioBotMerida";
        $inventario=485;
        break;
    case '7':
         $tabla="SymioBotSanLuis";
        $inventario=531;
        break;
    case '8':
        $tabla="SymioBotPrueba";
        $inventario=419;
        break;
    case '86':
        $tabla="SymioBotChihuahua";
        $inventario=477;
        break;
     case '186':
        $tabla="SymioBotVeracruz";
        $inventario=840;
        break;
    case '184':
        $tabla="SymioBotInterlomas";
        $inventario=767;
        break;
    }    
    $db = conectarMySQL();

    $query="select Subort_cantidad, Ort_clave From SubOrtesis where Subort_clave=".$proClave;    
    $result = $db->query($query);
    $cantidad = $result->fetch();    
    $claveMed =$cantidad['Ort_clave'];    
    $query="select Stock from ".$tabla." where Clave_producto='".$claveMed."'";
    $result = $db->query($query);
    $stock = $result->fetch();
    $sumaStock =$cantidad['Subort_cantidad']+$stock['Stock'];    
    $query="UPDATE ".$tabla." set Stock=:Stock where Clave_producto=:Clave_producto";
    $temporal = $db->prepare($query);
    $temporal->bindParam("Stock", $sumaStock);
    $temporal->bindParam("Clave_producto", $claveMed);
    if($temporal->execute()){

            $query="Delete from SubOrtesis where Subort_clave = :Subort_clave";
            $stmt = $db->prepare($query);
            $stmt->bindParam('Subort_clave', $proClave);
            if ($stmt->execute()){              
                $query="SELECT Subort_clave, Sym_medicamento, Subort_indicaciones, Subort_cantidad  FROM SubOrtesis inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =SubOrtesis.Ort_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
                $result = $db->query($query);
                $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($listMediAgre);
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
                echo json_encode($respuesta);
            } 
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    
    $db = null;    
}


if($funcion == 'getListadoMedAgreSymio'){    
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Nsum_clave, Sym_medicamento, Nsum_obs, Nsum_Cantidad  FROM NotaSuministro inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =NotaSuministro.Sum_clave Where Exp_folio='".$fol."' ";
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}
if($funcion == 'getListadoOrtAgreSymio'){    
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="SELECT Notor_clave, Sym_medicamento, Notor_indicaciones, Notor_cantidad  FROM NotaOrtesis inner Join SymioCuadroBasico on SymioCuadroBasico.Clave_producto =NotaOrtesis.Ort_clave Where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($listMediAgre);
    $db = null;    
}


/****************************     ENVIO SOLICITUD DE CONSTANCIA ************************************/

if($funcion == 'enviaConstancia'){
    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $mimemail = new nomad_mimemail();
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $motivo               =$datos->motivo;
    $observaciones        =$datos->observaciones; 


    $db = conectarMySQL();
    $query="Select Exp_nombre, Exp_paterno, Exp_materno, Exp_fechaNac, Exp_edad, Exp_meses, Exp_sexo, Rel_clave, Ocu_clave, Edo_clave, Exp_mail, Exp_telefono, Cia_clave, Exp_poliza, Exp_siniestro, Exp_reporte, Rie_clave From Expediente Where Exp_cancelado=0 and Exp_folio='".$fol."'";
    $result = $db->query($query);
    $row = $result->fetch();
    $Nombre      =$row['Exp_nombre'];
    $Paterno     =$row['Exp_paterno'];
    $Materno     =$row['Exp_materno'];
    $completo    =$Nombre.' '.$Paterno.' '.$Materno;
    $FechaNac    =$row['Exp_fechaNac'];
    $Edad        =$row['Exp_edad'];
    $Meses       =$row['Exp_meses'];
    $Sexo        =$row['Exp_sexo'];
    $Religion    =$row['Rel_clave'];
    $Ocupacion   =$row['Ocu_clave'];
    $EdoCivil    =$row['Edo_clave'];
    $Mail        =$row['Exp_mail'];
    $Telefono    =$row['Exp_telefono'];
    $CiaClave    =$row['Cia_clave'];
    $pol         =$row['Exp_poliza'];
    $sin         =$row['Exp_siniestro'];
    $rep         =$row['Exp_reporte'];
    $rie         =$row['Rie_clave'];
    if ($rie==-1){$rie=7;}
    
    $query="Select Rie_nombre From RiesgoAfectado Where Rie_clave=".$rie;
    $result = $db->query($query);
    $row = $result->fetch();
    $RieNombre=$row['Rie_nombre'];
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');    
    $query ="Insert into ConstanciaMedica(Exp_folio, Cons_motivo, Cons_obs, Cons_fecha, Cons_hora, Usu_registro)
                               Values(:Exp_folio,:Cons_motivo,:Cons_obs,:Cons_fecha,:Cons_hora,:Usu_registro)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Cons_motivo", $motivo);
            $temporal->bindParam("Cons_obs", $observaciones);
            $temporal->bindParam("Cons_fecha", $fecha);                 
            $temporal->bindParam("Cons_hora", $hora);                 
            $temporal->bindParam("Usu_registro", $usr);                 
         if ($temporal->execute()){
            $text='Nos dirijimos a ustedes ya que uno de los lesionados direccionados por ustedes a MedicaVial ha solicitado se le extienda una constancia de atencion medica, externando los siguientes motivos: '.$motivo;
            if($observaciones){
                $text.='<br> Consideramos pertinente hacer las siguientes observaciones: '.$observaciones;
            }
            $contenido='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>'.$text.'
                                

                                <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Datos de expediente:
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>'.$fol.'</b>
                                        </td>
                                        <td colspan="2" style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>'.utf8_encode($completo).'</b>
                                        </td>                                        

                                    </tr>
                                    <tr>
                                        <td>
                                            P??liza: <b>'.$pol.'</b>
                                        </td>
                                        <td colspan="2">
                                            Reporte: <b>'.$rep.'</b>
                                        </td>
                                        <td colspan="3">
                                            Siniestro: <b>'.$sin.'</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Tel??fono: <b>'.$Telefono.'</b>
                                        </td>
                                        <td colspan="2">
                                            Correo: <b>'.$Mail.'</b>
                                        </td>
                                        <td colspan="3">
                                            Riesgo: <b>'.$RieNombre.'</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            Quedamos en espera de su valiosa respuesta.<br>
                                            Atentamente: Coordinacion Medica
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

                $mimemail->set_from("coordmed_noReply@medicavial.com.mx");
                //$mimemail->set_to("facparticulares@medicavial.com.mx");
                $mimemail->set_to("coordmed@medicavial.com.mx");
                //$mimemail->set_to("monserrat.lozano@abaseguros.com");
                //$mimemail->set_cc("departamentomedicomex@abaseguros.com");
                //$mimemail->set_bcc("coordmed@medicavial.com.mx");
                $mimemail->add_bcc("egutierrez@medicavial.com.mx");
                $mimemail->set_subject("Solicitud de constancia medica");
                $mimemail->set_html($contenido);
                $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
                if ($mimemail->send()){
                    $respuesta = array('respuesta' =>'correcto','folio'=>$fol);       
                   
                }else {
                    $respuesta = array('respuesta' =>'incorrecto','folio'=>'');
                }
         }
         echo json_encode($respuesta);
    $db = null;    
}

/****************************  FIN   ENVIO SOLICITUD DE CONSTANCIA *********************************/ 

/**********************************   DAtos para Receta  *******************************************/

if($funcion == 'listDatPacRec'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="select * from Expediente inner join Vitales on Expediente.Exp_folio=Vitales.Exp_folio where Expediente.Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listMedic = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($listMedic);
    $db = null;    
}

if($funcion == 'listaAlergiasRec'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="select Ale_nombre, Ale_obs from HistoriaAlergias inner join Alergias on HistoriaAlergias.Ale_clave=Alergias.Ale_clave where HistoriaAlergias.Exp_folio='".$fol."'";
    $result = $db->query($query);
    $listAler = $result->fetchAll();
    $lista='';  
    foreach($listAler as $clave =>$valor){
            if($lista==''){
                $lista=$valor['Ale_nombre'];
                if($valor['Ale_obs']){
                    $lista.="(".$valor['Ale_obs'].")";
                }
            }else{
                $lista.=', '.$valor['Ale_nombre'];
                if($valor['Ale_obs']){
                    $lista.="(".$valor['Ale_obs'].")";
                }
            }      
    }
    echo $lista;
    $db = null;    
}

if($funcion == 'datosDoc'){
    $usr=$_GET['usr'];
    $db = conectarMySQL();
    $query="select Med_nombre,Med_paterno, Med_materno,Med_cedula,Med_esp, Med_telefono  from Medico where Usu_login='".$usr."'";
    $result = $db->query($query);
    $datosDoc = $result->fetch(PDO::FETCH_OBJ);   
    echo json_encode($datosDoc);
    $db = null;    
}
if($funcion == 'datosMedicamentoRec'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="Select Sum_clave as clave, Nsum_Cantidad as cantidad, Sym_denominacion as sustancia, Sym_presentacion as medicamento, Sym_forma_far as presentacion, Nsum_obs as posologia from NotaSuministro 
inner join SymioCuadroBasico on NotaSuministro.Sum_clave=SymioCuadroBasico.Clave_producto
 where Exp_folio='".$fol."' order by Sum_clave asc";
    $result = $db->query($query);
    $datosDoc = $result->fetchAll(PDO::FETCH_OBJ);   
    echo json_encode($datosDoc);
    $db = null;    
}
if($funcion == 'datosOrtRec'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="Select Ort_clave as clave, Notor_Cantidad as cantidad, Sym_denominacion as sustancia, Sym_presentacion as medicamento, Sym_forma_far as presentacion, Notor_indicaciones as posologia from NotaOrtesis 
inner join SymioCuadroBasico on NotaOrtesis.Ort_clave=SymioCuadroBasico.Clave_producto
 where Exp_folio='".$fol."' order by Ort_clave asc";
    $result = $db->query($query);
    $datosDoc = $result->fetchAll(PDO::FETCH_OBJ);   
    echo json_encode($datosDoc);
    $db = null;    
}
if($funcion == 'datosIndicacionesRec'){
    $fol=$_GET['fol'];
    $db = conectarMySQL();
    $query="Select Nind_obs from NotaInd where Exp_folio='".$fol."'";
    $result = $db->query($query);
    $datosDoc = $result->fetchAll();
    $lista='';  
    foreach($datosDoc as $clave =>$valor){
            if($lista==''){
                $lista=$valor['Nind_obs'];
            }else{
                $lista.=', '.$valor['Nind_obs'];
            }      
    }
    echo $lista;   
    $db = null;    
}

if($funcion == 'cambioOrtesis'){    
    $db = conectarMySQL();
    $query="Select * from SymioCuadroBasico where Clave_producto like 'ORT%'";
    $result = $db->query($query);
    $datosDoc = $result->fetchAll();
    $lista='';  
    foreach($datosDoc as $clave =>$valor){            
                echo utf8_decode($valor['Sym_medicamento']).'<br>';               
                $query="UPDATE SymioCuadroBasico set Sym_denominacion=:Sym_denominacion where Clave_producto=:Clave_producto";
                $temporal = $db->prepare($query);
                $temporal->bindParam("Sym_denominacion", $valor['Sym_medicamento']);
                $temporal->bindParam("Clave_producto", $valor['Clave_producto']);
                $temporal->execute();

    }
    $db = null;    
}

if($funcion == 'generaFolio'){    
    $fol= $_GET['fol'];
    if (file_exists('codigos/'.$fol.'.png')) {
        echo "existe";
    }else{
        require('classes/generaCDB_resp.php');        
        $genera=new generaCDB_resp();
        $resp=$genera->generaCodigo($fol);
        echo $resp;
    }
}

if($funcion == 'datosUni'){    
    $Uni= $_GET['uni'];
    $db = conectarMySQL();
    $query="Select Uni_calleNum, Uni_colMun, Uni_tel From Unidad where Uni_clave=".$Uni;
    $result = $db->query($query);
    $datosUnidad = $result->fetch();
    echo json_encode($datosUnidad);
    $db = null;    
}




/**********************************  fin DAtos para Receta  *******************************************/

/*******************************  fin Sumnistros Symio    ***********************************/

if($funcion=='guardaMedicamento'){
    $fol= $_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $medica         =$datos->medica;
    $posologia        =$datos->posologia; 
    $cantidad        =$datos->cantidad;
    $fecha          = date("Y-m-d H:i:s");       
    $db = conectarMySQL(); 
    try{    
       
            $query="Insert into NotaSuministro(Exp_folio, Sum_clave, Nsum_obs, Nsum_Cantidad, Nsum_fecha)
                             Values(:Exp_folio,:Sum_clave,:Nsum_obs,:Nsum_Cantidad,:Nsum_fecha)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sum_clave", $medica);
            $temporal->bindParam("Nsum_obs", $posologia);
            $temporal->bindParam("Nsum_Cantidad", $cantidad);                 
            $temporal->bindParam("Nsum_fecha", $fecha);                 
         if ($temporal->execute()){
            $query="SELECT Nsum_clave, Sum_nombre, Nsum_obs, Nsum_Cantidad  FROM NotaSuministro inner Join Suministro on Suministro.Sum_clave =NotaSuministro.Sum_clave Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}    
    $db = null;  
}

if($funcion=='guardaMedicamentoSub'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $medica         =$datos->medica;
    $posologia        =$datos->posologia; 
    $cantidad        =$datos->cantidad; 
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');      
    $db = conectarMySQL(); 
    try{    
       
            $query="Insert Into SubSuministros(Exp_folio, Sum_clave, Subsum_obs, Subsum_cantidad, Subsum_fecha, Subsum_hora,Sub_cons)
                                Values(:Exp_folio,:Sum_clave,:Subsum_obs,:Subsum_cantidad,:Subsum_fecha,:Subsum_hora,:Sub_cons)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sum_clave", $medica);
            $temporal->bindParam("Subsum_obs", $posologia);
            $temporal->bindParam("Subsum_cantidad", $cantidad);                 
            $temporal->bindParam("Subsum_fecha", $fecha);                 
            $temporal->bindParam("Subsum_hora", $hora);   
            $temporal->bindParam("Sub_cons", $cont);                 
         if ($temporal->execute()){
            $query="SELECT Subsum_clave, Sum_nombre, Subsum_obs, Subsum_Cantidad  FROM SubSuministros inner Join Suministro on Suministro.Sum_clave =SubSuministros.Sum_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
} 
    $db = null;  
}

if($funcion == 'eliminaMedicamento'){
    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];
    $db = conectarMySQL();
    $query="Delete from NotaSuministro where Nsum_clave = :Nsum_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Nsum_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Nsum_clave, Sum_nombre, Nsum_obs, Nsum_Cantidad  FROM NotaSuministro inner Join Suministro on Suministro.Sum_clave =NotaSuministro.Sum_clave Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }
    
    $db = null;    
}

if($funcion == 'eliminaMedicamentoSub'){
    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="Delete from SubSuministros where Subsum_clave = :Subsum_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Subsum_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Subsum_clave, Sum_nombre, Subsum_obs, Subsum_Cantidad  FROM SubSuministros inner Join Suministro on Suministro.Sum_clave =SubSuministros.Sum_clave Where Exp_folio='".$fol."' and Sub_cons=0";
        $result = $db->query($query);
        $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}

if($funcion=='guardaOrtesis'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $ortesis         =$datos->ortesis;
    $presentacion        =$datos->presentacion; 
    $cantidad        =$datos->cantidad; 
    $indicaciones        =$datos->indicaciones;       
    $db = conectarMySQL(); 
    try{           
            $query="Insert into NotaOrtesis(Exp_folio, Ort_clave, Ortpre_clave, Notor_cantidad, Notor_indicaciones)
                             Values(:Exp_folio,:Ort_clave,:Ortpre_clave,:Notor_cantidad,:Notor_indicaciones)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Ort_clave", $ortesis);
            $temporal->bindParam("Ortpre_clave", $presentacion);
            $temporal->bindParam("Notor_cantidad", $cantidad);                 
            $temporal->bindParam("Notor_indicaciones", $indicaciones);                 
         if ($temporal->execute()){            
            $query="SELECT Notor_clave, Ort_nombre, Ortpre_nombre, Notor_cantidad, Notor_indicaciones FROM NotaOrtesis inner Join Ortesis on Ortesis.Ort_clave=NotaOrtesis.Ort_clave inner Join  Ortpresentacion on Ortpresentacion.Ortpre_clave=NotaOrtesis.Ortpre_clave Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
             echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
}   
    $db = null;  
}





if($funcion=='guardaOrtesisSub'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $ortesis         =$datos->ortesis;
    $presentacion        =$datos->presentacion; 
    $cantidad        =$datos->cantidad; 
    $indicaciones        =$datos->indicaciones; 
    $fecha= date('Y-m-d');
    $hora= date('H:i:s'); 
    //echo $fol.'---'.$ortesis.'---'.$presentacion.'---'.$cantidad.'---'.$indicaciones.'---'.$fecha.'---'.$hora;    
    $db = conectarMySQL(); 
    try{         

            $query="Insert into  SubOrtesis(Exp_folio, Ort_clave, OrtPre_clave, Subort_cantidad, Subort_indicaciones, Subort_fecha, Subort_hora, Sub_cons)
                           Values(:Exp_folio, :Ort_clave, :OrtPre_clave, :Subort_cantidad, :Subort_indicaciones, :Subort_fecha, :Subort_hora,:Sub_cons)";
            /*$query="Insert into NotaOrtesis(Exp_folio, Ort_clave, Ortpre_clave, Notor_cantidad, Notor_indicaciones)
                             Values(:Exp_folio,:Ort_clave,:Ortpre_clave,:Notor_cantidad,:Notor_indicaciones)";*/
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Ort_clave", $ortesis);
            $temporal->bindParam("OrtPre_clave", $presentacion);
            $temporal->bindParam("Subort_cantidad", $cantidad);                 
            $temporal->bindParam("Subort_indicaciones", $indicaciones);                 
            $temporal->bindParam("Subort_fecha", $fecha);
            $temporal->bindParam("Subort_hora", $hora  );
            $temporal->bindParam("Sub_cons", $cont  );
            if ($temporal->execute()){            
                $query="SELECT Subort_clave, Ort_nombre, Ortpre_nombre, Subort_cantidad, Subort_indicaciones FROM SubOrtesis inner Join Ortesis on Ortesis.Ort_clave=SubOrtesis.Ort_clave inner Join  Ortpresentacion on Ortpresentacion.Ortpre_clave=SubOrtesis.Ortpre_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
                $result = $db->query($query);
                $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
                echo json_encode($listMediAgre);
            }else{
                $respuesta = array('respuesta' => 'incorrecto');
                 echo json_encode($respuesta);
            }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
}   
    $db = null;  
}
if($funcion == 'eliminarOrtesis'){
    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];
    $db = conectarMySQL();
    $query="Delete from NotaOrtesis where Notor_clave = :Notor_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Notor_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Notor_clave, Ort_nombre, Ortpre_nombre, Notor_cantidad, Notor_indicaciones FROM NotaOrtesis inner Join Ortesis on Ortesis.Ort_clave=NotaOrtesis.Ort_clave inner Join  Ortpresentacion on Ortpresentacion.Ortpre_clave=NotaOrtesis.Ortpre_clave Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}

if($funcion == 'eliminaOrtesisSub'){
    $fol=$_GET['fol'];
    $proClave=$_GET['proClave'];
    $cont=$_GET['cont'];
    $db = conectarMySQL();
    $query="Delete from SubOrtesis where Subort_clave = :Subort_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Subort_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Subort_clave, Ort_nombre, Ortpre_nombre, Subort_cantidad, Subort_indicaciones FROM SubOrtesis inner Join Ortesis on Ortesis.Ort_clave=SubOrtesis.Ort_clave inner Join  Ortpresentacion on Ortpresentacion.Ortpre_clave=SubOrtesis.Ortpre_clave Where Exp_folio='".$fol."' and Sub_cons=".$cont;
        $result = $db->query($query);
        $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
        echo json_encode($respuesta);
    }    
    $db = null;    
}




if($funcion=='guardaIndicacion'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $indicacion         =$datos->indicacion;
    $obs                =$datos->obs;     
    if($indicacion==''){
        $indicacion=0;
    }
    $db = conectarMySQL(); 
    try{           
            $query="Insert into NotaInd(Exp_folio, Ind_clave, Nind_obs)
                         Values(:Exp_folio,:Ind_clave,:Nind_obs)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Ind_clave", $indicacion);
            $temporal->bindParam("Nind_obs", $obs);                
         if ($temporal->execute()){
            $query="SELECT Nind_clave, Nind_obs FROM NotaInd Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    $db = null;  
}

if($funcion=='guardaIndicacionComplementaria'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $indicacion         =$datos->indicacion;
    $obs                =$datos->obs;     
    if($indicacion==''){
        $indicacion=0;
    }
    $db = conectarMySQL(); 
    try{           
            $query="Insert into NotaIndAlternativa(Exp_folio, Ind_clave, Nind_obs)
                         Values(:Exp_folio,:Ind_clave,:Nind_obs)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Ind_clave", $indicacion);
            $temporal->bindParam("Nind_obs", $obs);                
         if ($temporal->execute()){
            $query="SELECT Nind_clave, Nind_obs FROM NotaIndAlternativa Where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    $db = null;  
}

if($funcion == 'eliminarIndicacionComplementaria'){
    $fol=       $_GET['fol'];
    $proClave=  $_GET['proClave'];
    $db = conectarMySQL();
    $query="Delete from NotaIndAlternativa where Nind_clave = :Nind_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Nind_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Nind_clave, Nind_obs FROM NotaIndAlternativa Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}

if($funcion=='guardaIndicacionesSub'){
    $fol=$_GET['fol'];
    $cont=$_GET['cont'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $indicacion         =$datos->indicacion;
    $obs        =$datos->obs;
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');      
    $db = conectarMySQL(); 
    try{           
            $query="Insert into SubInd(Exp_folio, Ind_clave, Sind_obs,Sub_cons,Sind_fecha,Sind_hora)
                         Values(:Exp_folio,:Ind_clave,:Sind_obs,:Sub_cons,:Sind_fecha,:Sind_hora)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Ind_clave", $indicacion);
            $temporal->bindParam("Sind_obs", $obs);                
            $temporal->bindParam("Sub_cons", $cont);       
            $temporal->bindParam("Sind_fecha", $fecha);                
            $temporal->bindParam("Sind_hora", $hora);                
         if ($temporal->execute()){
            $query="SELECT Sind_clave, Sind_obs FROM SubInd Where Exp_folio='".$fol."' and Sub_cons=".$cont;
            $result = $db->query($query);
            $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($listMediAgre);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
    echo json_encode($respuesta);
}
    $db = null;  
}

if($funcion == 'eliminarIndicacion'){
    $fol=       $_GET['fol'];
    $proClave=  $_GET['proClave'];
    $db = conectarMySQL();
    $query="Delete from NotaInd where Nind_clave = :Nind_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Nind_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Nind_clave, Nind_obs FROM NotaInd Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}

if($funcion == 'eliminarIndicacionesSub'){
    $fol=       $_GET['fol'];
    $proClave=  $_GET['proClave'];
    $cont    =  $_GET['cont'];
    $db = conectarMySQL();
    $query="Delete from SubInd where Sind_clave = :Sind_clave";
    $stmt = $db->prepare($query);
    $stmt->bindParam('Sind_clave', $proClave);
    if ($stmt->execute()){
        $query="SELECT Sind_clave, Sind_obs FROM SubInd Where Exp_folio='".$fol."' and Sub_cons=".$cont;
        $result = $db->query($query);
        $listMediAgre = $result->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($listMediAgre);
    }else{
        $respuesta = array('respuesta' => 'incorrecto');
         echo json_encode($respuesta);
    }   
    $db = null;    
}
if($funcion=='guardaPronostico'){
    $fol=$_GET['fol'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $pronostico         =$datos->pronostico;
    $criterio        =$datos->criterio; 

    $db = conectarMySQL(); 
    try{    
       
            $query=" Update ObsNotaMed Set ObsNot_pron=:ObsNot_pron, ObsNot_waddell=:ObsNot_waddell Where Exp_folio=:Exp_folio";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);           
            $temporal->bindParam("ObsNot_pron", $pronostico);
            $temporal->bindParam("ObsNot_waddell", $criterio);                
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;  
}

if($funcion=='guardaPronosticoSub'){
    $fol=$_GET['fol'];
    $cont=$_GET['subCont'];
    $usr=$_GET['usr'];
    $alta = $_GET['alta'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $pronostico         =$datos->pronostico;
    $criterio        =$datos->criterio; 
    $fecha= date('Y-m-d');
    $hora= date('H:i:s');       
    $db = conectarMySQL(); 
    if($pronostico=='' && $criterio==''){
        $query=" Update Subsecuencia Set Sub_fecha=:Sub_fecha,Sub_hora=:Sub_hora, Usu_registro=:Usu_registro, Sub_altaMedica=:Sub_altaMedica Where Exp_folio=:Exp_folio and Sub_cons=:Sub_cons";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sub_cons", $cont);
            $temporal->bindParam("Sub_fecha", $fecha);
            $temporal->bindParam("Sub_hora", $hora);
            $temporal->bindParam("Usu_registro", $usr);
            $temporal->bindParam("Sub_altaMedica", $alta);                    
        if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }  
    }else{
    try{    
       
            $query=" Update Subsecuencia Set Sub_waddell=:Sub_waddell, Sub_obs=:Sub_obs, Sub_fecha=:Sub_fecha,Sub_hora=:Sub_hora, Usu_registro=:Usu_registro, Sub_altaMedica=:Sub_altaMedica Where Exp_folio=:Exp_folio and Sub_cons=:Sub_cons";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sub_cons", $cont);
            $temporal->bindParam("Sub_waddell", $criterio);
            $temporal->bindParam("Sub_obs", $pronostico);
            $temporal->bindParam("Sub_fecha", $fecha);
            $temporal->bindParam("Sub_hora", $hora);
            $temporal->bindParam("Usu_registro", $usr);
            $temporal->bindParam("Sub_altaMedica", $alta);                    
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
}
    echo json_encode($respuesta);
    $db = null;  
}

////////************************  Subsecuenias    ******************************************/
if($funcion=='guardaSigYSinSub'){
    $fol=$_GET['fol'];
    $cons=$_GET['cons'];
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);
    $sigYSin        = $datos->sigYSin;
    $evo            = $datos->evo;     
    $db = conectarMySQL(); 
    try{    
       		$query="Select Uni_ClaveActual From Expediente Where Exp_folio='".$fol."'";
	        $result = $db->query($query);
	        $rs = $result->fetch();
	        $uniActual = $rs['Uni_ClaveActual'];
            $query=" Insert Into Subsecuencia(Exp_folio, Sub_cons, Sub_SignosSintomas, Sub_evolucion, Uni_clave)
                                values(:Exp_folio,:Sub_cons,:Sub_SignosSintomas,:Sub_evolucion, :Uni_clave)";
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Sub_cons", $cons);
            $temporal->bindParam("Sub_SignosSintomas", $sigYSin);  
            $temporal->bindParam("Sub_evolucion", $evo); 
            $temporal->bindParam("Uni_clave", $uniActual);               
         if ($temporal->execute()){
            $respuesta = array('respuesta' => 'correcto', 'folio'=>$fol);
        }else{
            $respuesta = array('respuesta' => 'incorrecto');
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;  
}

/***************************** fin subsecuencias *******************************************/

/*******************************   digitalizacion ******************************************/
if($funcion=='archivo_temporal'){   
    if ($_FILES['file']["error"] > 0){
        $respuesta = array('error'=>'si'); 
    }else{       
        $respuesta = array('nombre' => $_FILES['file']['name'], 'temporal'=>$_FILES['file']['tmp_name']);

    }
    echo json_encode($respuesta);
}

/*

if($funcion=='guardaDigital'){   
    $fol=$_GET['fol'];  
    $tipo=$_GET['tipo']; 
    $usr=$_GET['usr']; 
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);
    //$nombre         = $datos->archivo;
    //$temporal       = $datos->temporal;
    //$tipo           = $datos->tipo;   
    if ($_FILES['file']["error"] > 0){
        $respuesta = array('error'=>'si'); 
    }else{       
        $db = conectarMySQL();
        $query="Select Exp_fecreg From Expediente Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $rs = $result->fetch();
        $fechareg = $rs['Exp_fecreg'];
        list($fecha,$hora)= explode(" ", $fechareg);
        list($ano,$mes,$dia)= explode("-", $fecha);
        $pre='Default';
        switch ($tipo) {
            case '1':
                $pre='PM';
                break;                    
            case '2':
                $pre='CA';
                break;
            case '3':
                $pre='ID';
                break;
            case '4':
                $pre='HC';
                break;
            case '5':
                $pre='NM';
                break;
            case '6':
                $pre='AP';
                break;
            case '7':
                $pre='IC';
                break;
            case '8':
                $pre='FI';
                break;
            case '9':
                $pre='CM';
                break;
        }
        switch ($mes){
                 case '01':
                 $mesd="January";//Enero
                 break;

                 case '02':
                 $mesd="February";//Febrero
                 break;

                 case '03':
                 $mesd="March";//Marzo
                 break;

                 case '04':
                 $mesd="April";//Abril
                 break;

                 case '05':
                 $mesd="May";//Mayo
                 break;

                 case '06':
                 $mesd="June";//Junio
                 break;

                 case '07':
                 $mesd="July";//Julio
                 break;

                 case '08':
                 $mesd="August";//Agosto
                 break;

                 case '09':
                 $mesd="September";//Septiembre
                 break;

                 case '10':
                 $mesd="October";//Octubre
                 break;

                 case '11':
                 $mesd="November";//Noviembre
                 break;

                 case '12':
                 $mesd="December";//Diciembre
                 break;
             }

            $direc="../../registro/Digitales/".$ano."/".$mesd."/".$fol; 
            $direc1="Digitales/".$ano."/".$mesd."/".$fol;





            if ($_FILES["file"]["size"] < 921600000){
              if ($_FILES["file"]["error"] > 0){
                echo "Error: " . $_FILES["file"]["error"] . "<br />";
              }else{
                if($_FILES["file"]["type"] == "application/pdf" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/bmp" || $_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES["file"]["type"] =="application/msword" || $_FILES["file"]["type"] == "image/pjpeg")
                {    
                  if (file_exists($direc."/".$_FILES["file"]["name"]))
                  {
                    echo $_FILES["file"]["name"] . " <br><br>Error: Ya existe en el directorio. ";
                  }
                  else
                  {        
                    $_dir= is_dir("../../registro/Digitales");        
                    if($_dir==1){                        
                      $_dir1= is_dir("../../registro/Digitales/".$ano);                
                    if($_dir1==1){
                      $_dir2= is_dir("../../registro/Digitales/".$ano."/".$mesd);                                  
                    if($_dir2==1){
                      $_dir3= is_dir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);                                               
                    if($_dir3==1){                                                   
                    }else{
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);            
                    }                                               
                    }else{
                      mkdir("../../registro/Digitales/".$ano."/".$mesd);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol); 
                    }                                                    
                    }else{
                      mkdir("../../registro/Digitales/".$ano);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);  
                    }                
                    }else{
                      mkdir("../../registro/Digitales");
                      mkdir("../../registro/Digitales/".$ano);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);
                    }
                    $query="Select max(doc_clave)+1 As Cons from subeDocumentos Where Exp_folio='".$fol."' and doc_tipo=".$tipo;
                    $result = $db->query($query);
                    $rs = $result->fetch();
                    $cons=$rs['Cons'];
                    if($cons==0 || $cons==null)$cons=1;                                      
                    $partes=explode(".",$_FILES["file"]["name"]);                
                    move_uploaded_file($_FILES["file"]["tmp_name"], $direc."/".$cons."_".$pre."_".$fol.".".$partes[1]);                                
                
                    $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1];
                    $ruta= $direc."/".$cons."_".$pre."_".$fol.".".$partes[1];
                    $fecha = date("Y-m-d H:i:s");
                    try{ 

                    switch ($tipo) {
                        case '1':
                                $query="INSERT INTO subePase(pa_clave, Exp_folio, pa_archivo, Usu_login, pa_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '2':
                                $query="INSERT INTO subeCuestionario(cu_clave, Exp_folio, cu_archivo, Usu_login, cu_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '3':
                                $query="INSERT INTO subeId(id_clave, Exp_folio, id_archivo, Usu_login, id_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '4':
                                $query="INSERT INTO subeHc(hc_clave, Exp_folio, hc_archivo, Usu_login, hc_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '5':
                                $query="INSERT INTO subeIm(im_clave, Exp_folio, im_archivo, Usu_login, im_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login,:fecha)";
                            break;
                    
                        case '6':
                                $query="INSERT INTO subeAviso(av_clave, Exp_folio, av_archivo, Usu_login, av_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '7':
                                $query="INSERT INTO subeInfAse(ia_clave, Exp_folio, ia_archivo, Usu_login, ia_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '8':
                                $query="INSERT INTO subeFiniquito(fq_clave, Exp_folio, fq_archivo, Usu_login, fq_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        case '9':
                                $query="INSERT INTO subeConsMed(cm_clave, Exp_folio, cm_archivo, Usu_login, cm_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                            break;
                    
                        
                        }
                            $query1="INSERT INTO subeDocumentos(doc_clave, Exp_folio, doc_ruta_archivo, Usu_login, doc_fecreg,doc_tipo) 
                                        VALUES(:doc_c, :Exp_folio, :doc_ruta_archivo, :Usu_login,:doc_fecreg ,:doc_tipo)";

                            $temporal = $db->prepare($query1);
                            $temporal->bindParam("doc_c", $cons);
                            $temporal->bindParam("Exp_folio", $fol);
                            $temporal->bindParam("doc_ruta_archivo", $ruta);  
                            $temporal->bindParam("Usu_login", $usr);              
                            $temporal->bindParam("doc_tipo", $tipo);
                            $temporal->bindParam("doc_fecreg", $fecha);           
                            $temporal->execute();

                            $temporal = $db->prepare($query);
                            $temporal->bindParam("clav", $cons);
                            $temporal->bindParam("Exp_folio", $fol);
                            $temporal->bindParam("archivo", $ruta1);  
                            $temporal->bindParam("Usu_login", $usr);                                                 
                            $temporal->bindParam("fecha", $fecha);                                                   

                                                         
                            
                         if ($temporal->execute()){
                            $query="select Doc_clave, doc_ruta_archivo,doc_tipo,doc_fecreg, tipoDoc_nombre from subeDocumentos 
                                    inner join tipoDoc on subeDocumentos.doc_tipo=tipoDoc.tipoDoc_id
                                    where Exp_folio='".$fol."' order by doc_tipo, doc_clave asc";
                            $result = $db->query($query);
                            $rs     = $result->fetchAll(PDO::FETCH_OBJ);                                                
                            echo json_encode($rs);
                            $db = null;
                        }else{
                            $respuesta = array('respuesta' => 'error');
                            echo json_encode($respuesta);
                        }                        
                    }catch(Exception $e){
                        $respuesta=  array('respuesta' => $e->getMessage() );
                        echo json_encode($respuesta);
                    }
                  }
                }else{
                $respuesta = array('respuesta' => 'error');
                echo json_encode($respuesta);
                }// tipo de archivo
              }/// Si no hay error busca el directori
            }/// if Mide tamano de archivo
            else
            {
              $respuesta = array('respuesta' => 'error');
                echo json_encode($respuesta);
            }
   
    }    
}*/

if($funcion=='guardaDigital'){   
    $fol=$_GET['fol'];  
    $tipo=$_GET['tipo']; 
    $usr=$_GET['usr']; 
    $cont = $_GET['cont'];
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);
    //$nombre         = $datos->archivo;
    //$temporal       = $datos->temporal;
    //$tipo           = $datos->tipo;   
    if ($_FILES['file']["error"] > 0){
        $respuesta = array('error'=>'si'); 
    }else{       
        $db = conectarMySQL();
        $query="Select Exp_fecreg From Expediente Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $rs = $result->fetch();
        $fechareg = $rs['Exp_fecreg'];
        list($fecha,$hora)= explode(" ", $fechareg);
        list($ano,$mes,$dia)= explode("-", $fecha);
        $pre='Default';
        switch ($tipo) {
            case '1':
                $pre='pa';
                break;                    
            case '2':
                $pre='in';
                break;
            case '3':
                $pre='dg';
                break;
            case '4':
                $pre='dc';
                break;
            case '5':
                $pre='B-';
                break;
            case '6':
                $pre='B_';
                break;
            case '7':
                $pre='';
                break;
            case '8':
                $pre='Al';
                break;
            case '9':
                $pre='sp';
                break;
            case '10':
                $pre='';
                break;
            case '11':
                $pre='dci';
                break;
            case '12':
                $pre='';
                break;
            case '13':
                $pre='';
                break;
            case '14':
                $pre='';
                break;
            case '15':
                $pre='CA';
                break;
            case '16':
                $pre='ID';
                break;
            case '17':
                $pre='HC';
                break;
            case '18':
                $pre='NM';
                break;
            case '19':
                $pre='AP';
                break;
            case '20':
                $pre='IC';
                break;
            case '21':
                $pre='FI';
                break;
            case '22':
                $pre='CM';
                break;
            case '23':
                $pre='OT';
                break;
            case '31':
                $pre='CE';
                break;
        }
              
            
        switch ($mes){
                 case '01':
                 $mesd="January";//Enero
                 break;

                 case '02':
                 $mesd="February";//Febrero
                 break;

                 case '03':
                 $mesd="March";//Marzo
                 break;

                 case '04':
                 $mesd="April";//Abril
                 break;

                 case '05':
                 $mesd="May";//Mayo
                 break;

                 case '06':
                 $mesd="June";//Junio
                 break;

                 case '07':
                 $mesd="July";//Julio
                 break;

                 case '08':
                 $mesd="August";//Agosto
                 break;

                 case '09':
                 $mesd="September";//Septiembre
                 break;

                 case '10':
                 $mesd="October";//Octubre
                 break;

                 case '11':
                 $mesd="November";//Noviembre
                 break;

                 case '12':
                 $mesd="December";//Diciembre
                 break;
             }

            $direc="../../registro/Digitales/".$ano."/".$mesd."/".$fol; 
            $direc1="Digitales/".$ano."/".$mesd."/".$fol;


            /*if($cont==1){
                $size=1500000;
            }else{
                $size=921600000;
            }*/


            if ($_FILES["file"]["size"] < 921600000){
              if ($_FILES["file"]["error"] > 0){
                echo "Error: " . $_FILES["file"]["error"] . "<br />";
              }else{
                if($_FILES["file"]["type"] == "application/pdf" || $_FILES["file"]["type"] == "image/jpeg"|| $_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/bmp" || $_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES["file"]["type"] =="application/msword" || $_FILES["file"]["type"] == "image/pjpeg")
                {    
                  if (file_exists($direc."/".$_FILES["file"]["name"]))
                  {
                    echo $_FILES["file"]["name"] . " <br><br>Error: Ya existe en el directorio. ";
                  }
                  else
                  {        
                    $_dir= is_dir("../../registro/Digitales");        
                    if($_dir==1){                        
                      $_dir1= is_dir("../../registro/Digitales/".$ano);                
                    if($_dir1==1){
                      $_dir2= is_dir("../../registro/Digitales/".$ano."/".$mesd);                                  
                    if($_dir2==1){
                      $_dir3= is_dir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);                                               
                    if($_dir3==1){                                                   
                    }else{
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);            
                    }                                               
                    }else{
                      mkdir("../../registro/Digitales/".$ano."/".$mesd);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol); 
                    }                                                    
                    }else{
                      mkdir("../../registro/Digitales/".$ano);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);  
                    }                
                    }else{
                      mkdir("../../registro/Digitales");
                      mkdir("../../registro/Digitales/".$ano);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd);
                      mkdir("../../registro/Digitales/".$ano."/".$mesd."/".$fol);
                    }                    
                    
                    try{ 

                    /*switch ($tipo) {
                        case '1':
                                $query="INSERT INTO subePase(pa_clave, Exp_folio, pa_archivo, Usu_login, pa_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(pa_clave)+1 As Cons from subePase Where Exp_folio='".$fol."'";                              
                            break;
                    
                        case '15':
                                $query="INSERT INTO subeCuestionario(cu_clave, Exp_folio, cu_archivo, Usu_login, cu_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(cu_clave)+1 As Cons from subeCuestionario Where Exp_folio='".$fol."'";         
                            break;
                    
                        case '16':
                                $query="INSERT INTO subeId(id_clave, Exp_folio, id_archivo, Usu_login, id_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(id_clave)+1 As Cons from subeId Where Exp_folio='".$fol."'";         
                            break;
                    
                        case '17':
                                $query="INSERT INTO subeHc(hc_clave, Exp_folio, hc_archivo, Usu_login, hc_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(hc_clave)+1 As Cons from subeHc Where Exp_folio='".$fol."'";         
                            break;
                    
                        case '18':
                                $query="INSERT INTO subeIm(im_clave, Exp_folio, im_archivo, Usu_login, im_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login,:fecha)";
                                $query1="Select max(im_clave)+1 As Cons from subeIm Where Exp_folio='".$fol."'";         
                            break;
                    
                        case '19':
                                $query="INSERT INTO subeAviso(av_clave, Exp_folio, av_archivo, Usu_login, av_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(av_clave)+1 As Cons from subeAviso Where Exp_folio='".$fol."'";         
                            break;
                    
                        case '20':
                                $query="INSERT INTO subeInfAse(ia_clave, Exp_folio, ia_archivo, Usu_login, ia_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";                            
                                $query1="Select max(ia_clave)+1 As Cons from subeInfAse Where Exp_folio='".$fol."'";         
                            break;
    
                        case '21':
                                $query="INSERT INTO subeFiniquito(fq_clave, Exp_folio, fq_archivo, Usu_login, fq_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(fq_clave)+1 As Cons from subeFiniquito Where Exp_folio='".$fol."'";         
                            break;
                    
                        case '22':
                                $query="INSERT INTO subeConsMed(cm_clave, Exp_folio, cm_archivo, Usu_login, cm_fecreg) 
                                     VALUES (:clav, :Exp_folio, :archivo, :Usu_login, :fecha)";
                                $query1="Select max(cm_clave)+1 As Cons from subeConsMed Where Exp_folio='".$fol."'";         
                            break;
                    
                        
                        }
                        if($tipo==1||$tipo>=15){
                            $result = $db->query($query1);
                            $rs = $result->fetch();
                            $cons=$rs['Cons'];
                            if($cons==0 || $cons==null)$cons=1;                                      
                        }
                                                        
                    
                        $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1];
                        $ruta= $direc."/".$cons."_".$pre."_".$fol.".".$partes[1];
                        */
                        $fecha = date("Y-m-d H:i:s");


                         /*  $query1="INSERT INTO subeDocumentos(doc_clave, Exp_folio, doc_ruta_archivo, Usu_login, doc_fecreg,doc_tipo) 
                                        VALUES(:doc_c, :Exp_folio, :doc_ruta_archivo, :Usu_login,:doc_fecreg ,:doc_tipo)";

                            $temporal = $db->prepare($query1);
                            $temporal->bindParam("doc_c", $cons);
                            $temporal->bindParam("Exp_folio", $fol);
                            $temporal->bindParam("doc_ruta_archivo", $ruta);  
                            $temporal->bindParam("Usu_login", $usr);              
                            $temporal->bindParam("doc_tipo", $tipo);
                            $temporal->bindParam("doc_fecreg", $fecha);           
                            $temporal->execute();
*/                             
                           /* $temporal = $db->prepare($query);
                            $temporal->bindParam("clav", $cons);
                            $temporal->bindParam("Exp_folio", $fol);
                            $temporal->bindParam("archivo", $ruta1);  
                            $temporal->bindParam("Usu_login", $usr);                                                 
                            $temporal->bindParam("fecha", $fecha);                                                   
*/
                                                         
                            
                         //if ($temporal->execute()){ 

                            $query="SELECT MAX(Arc_cons)+1 As Cons FROM DocumentosDigitales WHERE REG_folio='".$fol."' and Arc_tipo=".$tipo;                            
                            $result = $db->query($query);
                            $rs = $result->fetch();
                            $cons=$rs['Cons'];
                            if($cons==0 || $cons==null)$cons=1;
                            $partes=explode(".",$_FILES["file"]["name"]);                
                            move_uploaded_file($_FILES["file"]["tmp_name"], $direc."/".$cons."_".$pre."_".$fol.".".$partes[1]);
                            $claveArch= $cons."_".$pre."_".$fol.".".$partes[1];
                            $ruta1= $direc1."/".$cons."_".$pre."_".$fol.".".$partes[1];
                            try{
                            $query="INSERT INTO DocumentosDigitales(Arc_cons, Arc_clave, REG_folio, Arc_archivo, Arc_tipo, Arc_desde, USU_login, Arc_fecreg)
                                                VALUES(:Arc_cons, :Arc_clave, :REG_folio, :Arc_archivo, :Arc_tipo,'REGISTRO_MV', :USU_login, NOW())";
                            $temporal= $db->prepare($query);
                            $temporal->bindParam("Arc_cons",$cons);
                            $temporal->bindParam("Arc_clave",$claveArch);
                            $temporal->bindParam("REG_folio",$fol);                         
                            $temporal->bindParam("Arc_archivo",$ruta1);
                            $temporal->bindParam("Arc_tipo",$tipo);                         
                            $temporal->bindParam("USU_login",$usr); 
                            if ($temporal->execute()){
                            /*$query="select Doc_clave, doc_ruta_archivo,doc_tipo,doc_fecreg, tipoDoc_nombre from subeDocumentos 
                                    inner join tipoDoc on subeDocumentos.doc_tipo=tipoDoc.tipoDoc_id
                                    where Exp_folio='".$fol."' order by doc_tipo, doc_clave asc";*/
                            if($usr=='algo'||$usr=='earobles'||$usr=='mmerino'||$usr=='iarvizu'){
                                $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg from DocumentosDigitales
                                    inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 
                                    where REG_folio='".$fol."'";    
                            }else{
                            $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg from DocumentosDigitales
                                    inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 
                                    where REG_folio='".$fol."' and TID_oculto=1";
                            }
                            $result = $db->query($query);
                            $rs     = $result->fetchAll(PDO::FETCH_OBJ);                                                
                            echo json_encode($rs);
                            $db = null;
                            }else{
                                $respuesta = array('respuesta' => 'error');
                                echo json_encode($respuesta);
                            }                               
                            $db = null;                     
                            }catch(Exception $e){
                                $respuesta=  array('respuesta' => $e->getMessage() );
                                echo json_encode($respuesta);
                            }
                            
                        /*}else{
                            $respuesta = array('respuesta' => 'error');
                            echo json_encode($respuesta);
                        } */                       
                    }catch(Exception $e){
                        $respuesta=  array('respuesta' => $e->getMessage() );
                        echo json_encode($respuesta);
                    }
                  }
                }else{
                $respuesta = array('respuesta' => 'error');
                echo json_encode($respuesta);
                }// tipo de archivo
              }/// Si no hay error busca el directori
            }/// if Mide tamano de archivo
            else
            {
              $respuesta = array('respuesta' => 'errorSize');
                echo json_encode($respuesta);
            }
   
    }    
}

if($funcion=='eliminaDigital'){   
    $fol=$_GET['fol'];  
    $cont= $_GET['cont'];
    $tipo=$_GET['tipo']; 
    $conexion = conectarMySQL();
    $sql = "Select Arc_archivo from DocumentosDigitales where REG_folio='".$fol."' and Arc_cons=".$cont." and Arc_tipo=".$tipo;
    $result = $conexion->query($sql);
    $ruta = $result->fetch(); 
    $rutaArc="../../registro/".$ruta['Arc_archivo'];   
    unlink($rutaArc);
    try{
    $sql= "delete from DocumentosDigitales where REG_folio=:REG_folio and Arc_cons=:Arc_cons and Arc_tipo=:Arc_tipo";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam('REG_folio', $fol);
    $stmt->bindParam('Arc_cons', $cont);
    $stmt->bindParam('Arc_tipo', $tipo);
    if ($stmt->execute()){
            $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg from DocumentosDigitales
                    inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 
                    where REG_folio='".$fol."'";
            $result = $conexion->query($query);
            $respuesta     = $result->fetchAll(PDO::FETCH_OBJ);                                                                        
    }else{
            $respuesta = array('respuesta' => 'error');            
    }
}catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
}
    echo json_encode($respuesta);
    $db = null;
    
}

if($funcion=='listaDigPase'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select pa_clave, Exp_folio, pa_archivo, Usu_login, pa_fecreg 
            From subePase 
            Where Exp_folio='".$fol."'
            order by  pa_clave   ";
    $result = $conexion->query($query);
    $resultado = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($resultado);
    $conexion = null;
}
if($funcion=='listaTiposDigitales'){
    $usr=$_GET['usr'];      
    $conexion = conectarMySQL();
    if($usr=='algo'||$usr=='earobles'||$usr=='mmerino'||$usr=='iarvizu'){
        $query="Select TID_claveint,TID_nombre,TID_docMV
            From TipoDocumento where TID_activa=1 order by TID_orden";    
    }else{
        $query="Select TID_claveint,TID_nombre,TID_docMV
            From TipoDocumento where TID_activa=1 and TID_oculto=1 order by TID_orden";    
    }
    $result = $conexion->query($query);
    $resultado = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($resultado);
    $conexion = null;
}
if($funcion=='getPaseDig'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select pa_archivo
            From subePase 
            Where Exp_folio='".$fol."'
            order by  pa_clave   ";
    $result = $conexion->query($query);
    $resultado = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($resultado);
    $conexion = null;
}
if($funcion=='listaDigCuestionario'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select cu_clave, Exp_folio, cu_archivo, Usu_login, cu_fecreg 
            From subeCuestionario 
            Where Exp_folio='".$fol."'
            order by  cu_clave   ";
    $result = $conexion->query($query);
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado); 
    $conexion = null;
}
if($funcion=='getCuestionarioDig'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select cu_archivo
            From subeCuestionario 
            Where Exp_folio='".$fol."'
            order by  cu_clave   ";
    $result = $conexion->query($query);
    $resultado = $result->fetch(PDO::FETCH_OBJ); 
    echo json_encode($resultado); 
    $conexion = null;
}
if($funcion=='listaDigIdentificacion'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select id_clave, Exp_folio, id_archivo, Usu_login, id_fecreg 
            From subeId 
            Where Exp_folio='".$fol."'
            order by  id_clave   ";
    $result = $conexion->query($query);    
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado);  
    $conexion = null; 
}
if($funcion=='getIdentificacionDig'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select id_archivo
            From subeId 
            Where Exp_folio='".$fol."'
            order by  id_clave   ";
    $result = $conexion->query($query);    
    $resultado = $result->fetch(PDO::FETCH_OBJ); 
    echo json_encode($resultado);
    $conexion = null;   
}
if($funcion=='listaDigHisCli'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select hc_clave, Exp_folio, hc_archivo, Usu_login, hc_fecreg 
            From subeHc 
            Where Exp_folio='".$fol."'
            order by  hc_clave   ";
    $result = $conexion->query($query);    
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado);
    $conexion = null; 
}
if($funcion=='listaDigIm'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select im_clave, Exp_folio, im_archivo, Usu_login, im_fecreg 
        From subeIm 
        Where Exp_folio='".$fol."'
        order by  im_clave   ";
        
    $result = $conexion->query($query); 
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado); 
    $conexion = null; 
}
if($funcion=='getInfMedDig'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select im_archivo
        From subeIm 
        Where Exp_folio='".$fol."'
        order by  im_clave   ";
        
    $result = $conexion->query($query); 
    $resultado = $result->fetch(PDO::FETCH_OBJ); 
    echo json_encode($resultado); 
    $conexion = null; 
}
if($funcion=='listaDigIA'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select ia_clave, Exp_folio, ia_archivo, Usu_login, ia_fecreg 
        From subeInfAse 
        Where Exp_folio='".$fol."'
        order by  ia_clave";
    $result = $conexion->query($query);
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado);  
    $conexion = null; 
}
if($funcion=='listaDigFiniquito'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select fq_clave, Exp_folio, fq_archivo, Usu_login, fq_fecreg 
        From subeFiniquito 
        Where Exp_folio='".$fol."'
        order by  fq_clave   ";
    $result = $conexion->query($query); 
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado); 
    $conexion = null; 
}
if($funcion=='listaDigNM'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select cm_clave, Exp_folio, cm_archivo, Usu_login, cm_fecreg 
        From subeConsMed
        Where Exp_folio='".$fol."'
        order by  cm_clave   ";
    $result = $conexion->query($query);  
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado); 
    $conexion = null;
}
if($funcion=='listaDigAP'){
    $fol=$_GET['fol'];  
    $lista=array();
    $conexion = conectarMySQL();
    $query="Select av_clave, Exp_folio, av_archivo, Usu_login, av_fecreg 
        From subeAviso
        Where Exp_folio='".$fol."'
        order by  av_clave   ";
    $result = $conexion->query($query);
    $resultado = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($resultado);
    $conexion = null;   
}

/******************************  fin digitalizacion ****************************************/

/******************************  Estatus de Nota m??dica    *********************************/

if($funcion=='estatusNotaM'){   
    $fol=$_GET['fol'];      
    $conexion = conectarMySQL();
    $sql = "select Not_Estatus from NotaMedica where Exp_folio='".$fol."'";
    $result = $conexion->query($sql);
    $unidad = $result->fetch(); 
    $estatus = $unidad['Not_Estatus'];
    echo $estatus;
    $db = null;
    
}

if($funcion=='guardaEstatusNota'){   
    $fol        =$_GET['fol'];      
    $estatus    =$_GET['estatus'];   
    $conexion   = conectarMySQL();
    $fecha = date("Y-m-d H:i:s"); 
    if($estatus==11){
        $sql = "Update NotaMedica set Not_Estatus=:Not_Estatus,Not_fechareg=:Not_fechareg  where Exp_folio=:Exp_folio";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam('Not_Estatus', $estatus);
        $stmt->bindParam('Exp_folio', $fol);
        $stmt->bindParam("Not_fechareg", $fecha);

    }else{
        $sql = "Update NotaMedica set Not_Estatus=:Not_Estatus where Exp_folio=:Exp_folio";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam('Not_Estatus', $estatus);
        $stmt->bindParam('Exp_folio', $fol);
    }
    if($stmt->execute()){
        $respuesta= Array('respuesta'=>'exito');        
    }else{
        $respuesta= array('respuesta'=>'error');
    }
    echo json_encode($respuesta);
    $conexion = null;
    
}

if($funcion=='verificaSexo'){   
    $fol        =$_GET['fol'];      
    $conexion   = conectarMySQL();
    $sql = "Select Exp_sexo from  Expediente  where Exp_folio='".$fol."'";
    $result = $conexion->query($sql);
    $resultado = $result->fetch(); 
    $estatus = $resultado['Exp_sexo'];
    echo $estatus;
    $conexion = null;
    
}

/******************************  Fin de Estatus de Nota M??dica *****************************/


/****************************** inicio cambio de Unidad ************************************/

if($funcion=='unidadDetalle'){   
    $fol=$_GET['fol'];      
    $conexion = conectarMySQL();
    $sql = "select Unidad.Uni_nombrecorto as actual, Expediente.Uni_ClaveActual as claveActual from Expediente
            inner join Unidad on Expediente.Uni_ClaveActual= Unidad.Uni_clave
            where Exp_folio='".$fol."'";
    $result = $conexion->query($sql);
    $unidad = $result->fetch(); 
    $uniActual = $unidad['actual']; 
    $claveActual = $unidad['claveActual'];
    $sql = "select Unidad.Uni_nombrecorto as origen, Expediente.Uni_clave as claveOrigen from Expediente
            inner join Unidad on Expediente.Uni_Clave= Unidad.Uni_clave
            where Exp_folio='".$fol."'";
    $result = $conexion->query($sql);
    $unidad = $result->fetch(); 
    $uniOrigen = $unidad['origen'];    
    $claveOrigen= $unidad['claveOrigen'];
    $respuesta = array('origen' => $uniOrigen, 'cveOrigen'=> $claveOrigen, 'actual'=>$uniActual, 'cveActual'=>$claveActual);  
    echo json_encode($respuesta);
    $conexion = null;
    
}
if($funcion=='quienAut'){   
    $conexion = conectarMySQL();
    $sql = "select Usu_login, Usu_nombre from Usuario where cambioUnidad=1";
    $result = $conexion->query($sql);
    $QuienAut = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($QuienAut);
    $conexion = null;
    
}
if($funcion=='uniDestino'){   
    $conexion = conectarMySQL();
    $sql = "Select Uni_clave, Uni_nombrecorto From Unidad where Uni_activa = 'S'  order by Uni_nombrecorto";
    $result = $conexion->query($sql);
    $uniDestino = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($uniDestino);
    $conexion = null;
    
}
if($funcion=='motivo'){   
    $conexion = conectarMySQL();
    $sql = "select id_motivo, motivo from motivosCambio where activo='S'";
    $result = $conexion->query($sql);
    $motivo = $result->fetchAll(PDO::FETCH_OBJ); 
    echo json_encode($motivo);
    $conexion = null;
    
}
if($funcion=='guardaDatosCambio'){

    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);
    $uniOrigen      = $datos->uniOrigen;
    $uniActual      = $datos->uniActual;
    $quienSol       = $datos->quienSol;
    $QuienAut       = $datos->quienAuto;
    $unidad         = $datos->unidad;
    $motivo         = $datos->motivo;
    $diagnostico    = $datos->diagnostico;
    $obs            = $datos->observaciones;
    $fecha          = date("Y-m-d H:i:s");

    $conexion = conectarMySQL();

    $sql = "Select Max(HisCamUni_cons)+1 As cons From HistoriaCambioUnidad Where Exp_folio='".$fol."'";
    $result = $conexion->query($sql);
    $rs = $result->fetch();
    $cons = $rs['cons'];
    if($cons==null || $cons=='')$cons=1;
    $query="Insert into HistoriaCambioUnidad(HisCamUni_cons,Exp_folio, HisCamUni_fecha, Uni_origen, Uni_actual,Uni_destino,QuienSol,QuienAut,motivo,diagnostico,observaciones,Usu_login) 
                           Values(:HisCamUni_cons,:Exp_folio,:HisCamUni_fecha,:Uni_origen,:Uni_actual,:Uni_destino,:QuienSol,:QuienAut,:motivo,:diagnostico,:observaciones,:Usu_login)";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam('HisCamUni_cons', $cons);
    $stmt->bindParam('Exp_folio', $fol); 
    $stmt->bindParam('HisCamUni_fecha', $fecha);    
    $stmt->bindParam('Uni_origen', $uniOrigen);
    $stmt->bindParam('Uni_actual', $uniActual);
    $stmt->bindParam('Uni_destino', $unidad);
    $stmt->bindParam('QuienSol', $quienSol);
    $stmt->bindParam('QuienAut', $QuienAut);
    $stmt->bindParam('motivo', $motivo);
    $stmt->bindParam('diagnostico', $diagnostico);
    $stmt->bindParam('observaciones', $obs);
    $stmt->bindParam('Usu_login', $usr);
    if ($stmt->execute()){
        $query="Update Expediente set Uni_claveActual=:Uni_claveActual where Exp_folio=:Exp_folio";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam('Uni_claveActual', $unidad);
        $stmt->bindParam('Exp_folio', $fol);
        if ($stmt->execute()){
            $respuesta= Array('respuesta'=>'exito');
        }    
    }else{
        $respuesta= array('respuesta'=>'error');
    }
    echo json_encode($respuesta);

    $conexion = null;
    
}


/******************************   fin de cambio de undiad **********************************/

// Solicitudes api

$unidad = $_GET['unidad'];


if($funcion == 'ActualizaSolicitud'){
    
    $clave = $_GET['clave'];
    $estatus = $_GET['estatus'];
    $fecha   = date("Y-m-d H:i:s");

    $conexion = conectarMySQL();

    $sql = "UPDATE Solicitudes SET SOL_estatus = $estatus , SOL_fechaActualiza = '$fecha'
            WHERE SOL_claveint = '$clave' ";

    if($conexion->query($sql)){
      $respuesta = array('respuesta' => 'Solicitud actualizada correctamente' );
    }else{
      $respuesta = array('respuesta' => 'Error al momento de actualizar intente nuevamente' );
    }
    
    echo json_encode($respuesta);

    $conexion = null;

}


if($funcion == 'loginfast'){
    
    $user = $_GET['usuario'];
 
    $conexion = conectarMySQL();
        
    //Obtenemos los valores de usuario y contrase??a 
    
    $sql = "SELECT * FROM Usuario
            WHERE Usu_login = '$user'";

    $result = $conexion->query($sql);
    $numero = $result->rowCount();
    
    if ($numero > 0){

        $datos = $result->fetchAll(PDO::FETCH_OBJ);
        
    }else{

        $datos = array('respuesta' => 'El Usuario es inorrecto');
    }
    
    echo json_encode($datos);

    $conexion = null;

}


if($funcion == 'busquedaExpedientes'){

    $db = conectarMySQL();
        
    $sql = "SELECT Exp_folio as expediente, Cia_nombrecorto as cliente, Exp_completo as lesionado, Exp_fecreg as fecha, Exp_sexo as sexo, Expediente.Cia_clave as idcliente FROM Expediente
            INNER JOIN Compania on Compania.Cia_clave = Expediente.Cia_clave  WHERE Exp_cancelado = 0 and Uni_clave = $unidad order by Exp_fecreg DESC LIMIT 0,50";

    $result = $db->query($sql);
    $folios = $result->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($folios);

};

if($funcion == 'digitalizados'){

    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $db = conectarMySQL();
    if($usr=='algo'||$usr=='earobles'||$usr=='mmerino'||$usr=='iarvizu'){
        $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg from DocumentosDigitales
            inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 
            where REG_folio='".$fol."'";    
    }else{
        $query="select DocumentosDigitales.Arc_tipo,Arc_cons,TID_nombre,Arc_Archivo, USU_login, Arc_fecreg from DocumentosDigitales
            inner join TipoDocumento on DocumentosDigitales.Arc_tipo= TipoDocumento.TID_claveint 
            where REG_folio='".$fol."' and TID_oculto=1";   
    }    
    
    $result = $db->query($query);
    $rs     = $result->fetchAll(PDO::FETCH_OBJ);                                                
    echo json_encode($rs);
    $db = null;

};

if($funcion == 'busquedaFolio'){

    $folio = $_GET['folioapi'];

    $db = conectarMySQL();
        
    $sql = "SELECT Exp_folio as expediente, Cia_nombrecorto as cliente, Exp_completo as lesionado, Exp_fecreg as fecha, Exp_sexo as sexo, Expediente.Cia_clave as idcliente FROM Expediente
            INNER JOIN Compania on Compania.Cia_clave = Expediente.Cia_clave 
            WHERE EXP_folio = '$folio' and Uni_clave = $unidad ";

    $result = $db->query($sql);
    $folios = $result->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($folios);

};

if($funcion == 'busquedaLesionado'){

    $lesionado = $_GET['lesionado'];

    $db = conectarMySQL();
        
    $sql = "SELECT Exp_folio as expediente, Cia_nombrecorto as cliente, Exp_completo as lesionado, Exp_fecreg as fecha, Exp_sexo as sexo, Expediente.Cia_clave as idcliente FROM Expediente
            INNER JOIN Compania on Compania.Cia_clave = Expediente.Cia_clave 
            WHERE EXP_completo like '%$lesionado%' AND Exp_cancelado = 0 and Uni_clave = $unidad order by EXP_completo limit 0,50";

    $result = $db->query($sql);
    $folios = $result->fetchAll(PDO::FETCH_OBJ);
    $db = null;

    echo json_encode($folios);

};




if($funcion == 'busquedaSolicitudes'){


    $db = conectarMySQL();
        
    $sql = "SELECT Exp_folio as expediente, Cia_nombrecorto as cliente, Exp_completo as lesionado, Exp_fecreg as fecha, Exp_sexo as sexo, Expediente.Cia_clave as idcliente FROM Expediente
            INNER JOIN Compania on Compania.Cia_clave = Expediente.Cia_clave 
            WHERE EXP_folio = '$folio' and Uni_clave = $unidad ";

    $result = $db->query($sql);
    $folios = $result->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($folios);

};


if($funcion == 'detalleSolicitud'){
    
    $clave = $_GET['clave'];
    $archivo = array();
    $archivos = array();

    $conexion = conectarMySQL();

    $sql = "SELECT Exp_folio as folio, SOL_lesionado as lesionado, Cia_nombrecorto as cliente, Solicitudes.TIM_claveint as tipo ,
            TIM_nombreE as tiponombre, DetalleSolicitud.* FROM Solicitudes 
            INNER JOIN DetalleSolicitud ON DetalleSolicitud.SOL_claveint = Solicitudes.SOL_claveint
            LEFT JOIN Compania on Compania.Cia_clave = Solicitudes.Cia_clave
            LEFT JOIN TipoMovimiento ON TipoMovimiento.TIM_claveint = Solicitudes.TIM_claveint
            WHERE Solicitudes.SOL_claveint = '$clave'";

    $result = $conexion->query($sql);

    $datos = $result->fetch(PDO::FETCH_OBJ);


    $sql2 = "SELECT * FROM ArchivosSolicitud
            WHERE SOL_claveint = '$clave'";

    $result = $conexion->query($sql2);

    foreach ($result as $value) {
        
        $nombre = $value['ARS_nombre'];
        $archivo['nombre'] = $nombre;

        if (strstr($nombre, '.pdf')) {
          $archivo['ruta'] = "imgs/pdf.png";
        }elseif (strstr($nombre, '.doc') || strstr($nombre, '.docx') || strstr($nombre, '.xls') || strstr($nombre, '.xlsx') ){
          $archivo['ruta'] = "imgs/office.png";
        }else{
          $archivo['ruta'] = $value['ARS_ruta'];       
        }

        $archivo['ubicacion'] = $value['ARS_ruta'];

        array_push($archivos, $archivo);

    }

    $respuesta = array('info' => $datos, 'archivos' => $archivos);

    echo json_encode($respuesta);

    $conexion = null;

}

if($funcion == 'guardaSolicitud'){

    $db = conectarMySQL();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    $datos = json_decode($postdata);
    $mimemail = new nomad_mimemail();

    //datos de folio
    $usuario = $datos->usuario;
    $folio = $datos->folio;
    $lesionado = $datos->lesionado;
    $tipo = $datos->tipo;
    $cliente = $datos->cliente;

    $diagnostico = $datos->diagnostico;
    $justificacion = $datos->justificacion;
    
    //interconsulta
    $consultatipo = $datos->interconsulta->tipo;
    $embarazo = $datos->interconsulta->embarazo;
    $controlgineco = $datos->interconsulta->controlgineco;
    $semanas = $datos->interconsulta->semanas;
    $dolorabdominal = $datos->interconsulta->dolorabdominal;
    $frecuencia = $datos->interconsulta->frecuencia;
    $movimientosfetales = $datos->interconsulta->movimientosfetales;
    $consultaObs = $datos->interconsulta->observaciones;

    //estudio especial
    $estudiotipo = $datos->estudio->tipo;
    $estudiodetalle = $datos->estudio->detalle;

    //rehabilitacion

    $dolor = $datos->rehabilitacion->dolor;
    $rehabilitaciones = $datos->rehabilitacion->rehabilitaciones;
    $mejora = $datos->rehabilitacion->mejora;

    //suministro

    $suministrodetalle = $datos->suministro->detalle;

    //informacion
    $notamedica = $datos->informacion->notamedica;
    $rx = $datos->informacion->rx;
    $resultados = $datos->informacion->resultados;
    $infodetalle = $datos->informacion->detalle;

    //salida de paquete

    $hospitalarioDetalle = $datos->salidapaquete->detalle;

    //problema documental

    $pase = $datos->problemadocumental->pase;
    $identificacion = $datos->problemadocumental->identificacion;
    $docdetalle = $datos->problemadocumental->detalle;


    $primera = substr ($lesionado,0, 1); 
    $ultima = substr ($lesionado,-1, 1);

    $fecha = date("Y-m-d H:i:s"); 

    $clave = 'S'. $primera . $ultima . generar_numero();
    //$clave = generar_clave();

    $archivos = $datos->soporte;

     if(!$db) {

        die('Something went wrong while connecting to MSSQL');

    }else{
        
        $sql = "INSERT INTO Solicitudes
              (
                     SOL_claveint,
                     TIM_claveint,
                     Exp_folio,
                     SOL_lesionado,
                     SOL_estatus,
                     SOL_fechaReg,
                     SOL_fechaActualiza,
                     Usu_login,
                     Cia_clave                    
              ) 
              VALUES
              (
                    :clave,
                    :tipo,
                    :folio,
                    :lesionado,
                    1,
                    :SOL_fechaReg,
                    :SOL_fechaActualiza,
                    :usuario,
                    :cliente
              )";
        
        $temporal = $db->prepare($sql);
        $temporal->bindParam("clave", $clave, PDO::PARAM_STR);
        $temporal->bindParam("lesionado", $lesionado, PDO::PARAM_STR);
        $temporal->bindParam("folio", $folio, PDO::PARAM_STR, 10);
        $temporal->bindParam("tipo", $tipo, PDO::PARAM_INT);
        $temporal->bindParam("usuario", $usuario, PDO::PARAM_STR);
        $temporal->bindParam("cliente", $cliente, PDO::PARAM_INT);
        $temporal->bindParam("SOL_fechaReg", $fecha);
        $temporal->bindParam("SOL_fechaActualiza", $fecha);
        
        if ($temporal->execute()){ 

            $sql2 = "INSERT INTO DetalleSolicitud
                  (
                         SOL_claveint,
                         DES_diagnostico,
                         DES_justificacion,
                         DES_intertipo,
                         DES_embarazo,
                         DES_controlgineco,
                         DES_semanas,   
                         DES_dolorabdominal,
                         DES_frecuencia,
                         DES_movimientosfetales,
                         DES_embarazoObs,
                         DES_estudiotipo,
                         DES_estudioDetalle,
                         DES_dolor,
                         DES_rehabilitaciones,
                         DES_mejora,
                         DES_suministroDetalle,
                         DES_notamedica,
                         DES_rx,
                         DES_resultados,
                         DES_infoDetalle,
                         DES_hosDetalle,
                         DES_pase,
                         DES_identificacion,
                         DES_documentalDetalle             
                  ) 
                  VALUES
                  (     
                        :clave,
                        :diagnostico,
                        :justificacion,
                        :consultatipo,
                        :embarazo,
                        :controlgineco,
                        :semanas,
                        :dolorabdominal,
                        :frecuencia,
                        :movimientosfetales,
                        :consultaObs,
                        :estudiotipo,
                        :estudiodetalle,
                        :dolor,
                        :rehabilitaciones,
                        :mejora,
                        :suministrodetalle,
                        :notamedica,
                        :rx,
                        :resultados,
                        :infodetalle,
                        :hospitalarioDetalle,
                        :pase,
                        :identificacion,
                        :docdetalle
                  )";
            
            $temporal2 = $db->prepare($sql2);
            $temporal2->bindParam("clave", $clave);
            $temporal2->bindParam("diagnostico", $diagnostico);
            $temporal2->bindParam("justificacion", $justificacion);
            $temporal2->bindParam("consultatipo", $consultatipo);
            $temporal2->bindParam("embarazo", $embarazo);
            $temporal2->bindParam("controlgineco", $controlgineco);
            $temporal2->bindParam("semanas", $semanas);
            $temporal2->bindParam("dolorabdominal", $dolorabdominal);
            $temporal2->bindParam("frecuencia", $frecuencia);
            $temporal2->bindParam("movimientosfetales", $movimientosfetales);
            $temporal2->bindParam("consultaObs", $consultaObs);
            $temporal2->bindParam("estudiotipo", $estudiotipo);
            $temporal2->bindParam("estudiodetalle", $estudiodetalle);
            $temporal2->bindParam("dolor", $dolor);
            $temporal2->bindParam("rehabilitaciones", $rehabilitaciones);
            $temporal2->bindParam("mejora", $mejora);
            $temporal2->bindParam("suministrodetalle", $suministrodetalle);
            $temporal2->bindParam("notamedica", $notamedica);
            $temporal2->bindParam("rx", $rx);
            $temporal2->bindParam("resultados", $resultados);
            $temporal2->bindParam("infodetalle", $infodetalle);
            $temporal2->bindParam("hospitalarioDetalle", $hospitalarioDetalle);
            $temporal2->bindParam("pase", $pase);
            $temporal2->bindParam("identificacion", $identificacion);
            $temporal2->bindParam("docdetalle", $docdetalle);

            $temporal2->execute();


            mkdir("../solicitudes/".$clave);


            foreach ($archivos as $documento) {

                $archivo= $documento->archivo;
                $observaciones= $documento->observaciones;
                $tipoid = $documento->idtipo;
                $ubicacionactual = "../". $documento ->ubicacionreal; 
                $ubicacionnueva = "../solicitudes/".$clave."/".$archivo;

                // echo $archivo ."<br>";
                // echo $observaciones."<br>";
                // echo $tipoid."<br>";
                // echo $ubicacionactual."<br>";
                // echo $ubicacionnueva;

                
                rename( $ubicacionactual ,  $ubicacionnueva);

                $ubicacionnueva = str_replace( "../", "",$ubicacionnueva);

                $sql3 = "INSERT INTO ArchivosSolicitud
                      (
                             SOL_claveint,
                             TID_claveint,
                             ARS_ruta,
                             ARS_observaciones,
                             ARS_nombre                   
                      ) 
                      VALUES
                      (
                            :clave,
                            :tipo,
                            :ruta,
                            :observaciones,
                            :nombre
                      )";
                
                $temporal3 = $db->prepare($sql3);
                $temporal3->bindParam("clave", $clave);
                $temporal3->bindParam("tipo", $tipoid);
                $temporal3->bindParam("ruta", $ubicacionnueva);
                $temporal3->bindParam("observaciones", $observaciones);
                $temporal3->bindParam("nombre", $archivo);

                $temporal3->execute();

            }
            /********************** se manda corrreo a coordmed@medicavial.com.mx solo si la solicitud es interconsulta***************/
            if($tipo==4){
              

                $sql= " SELECT Exp_completo, Exp_edad, Exp_sexo, ObsNot_diagnosticoRx, ObsNot_edoG, Uni_nombrecorto  FROM Expediente
                    LEFT JOIN ObsNotaMed on Expediente.Exp_folio=ObsNotaMed.Exp_folio                    
                    INNER JOIN Unidad on Expediente.Uni_claveActual = Unidad.Uni_clave
                    WHERE Expediente.Exp_folio='".$folio."'";

                $result = $db->query($sql);
                $rs = $result->fetch();
                $nombre=$rs['Exp_completo'];
                $edad = $rs['Exp_edad'];
                $sexo = $rs['Exp_sexo'];
                if($sexo=='F') $sexo='FEMENINO';
                else $sexo = 'MASCULINO';
                $diagnosticoReg = $rs['ObsNot_diagnosticoRx'];
                $EdoGral = $rs['ObsNot_edoG'];               
                $unidad = $rs['Uni_nombrecorto'];

                $cont='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th align="center" colspan="5" bordercolor="#3f81ba" style="background:#3f81ba;font-size:40px; color:white;"\>
                                                SOLICITUD DE INTERCONSULTA
                                        </th>
                                    </tr>

                                    <tr>
                                        <th align="center" colspan="2" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                NO. DE SOLICITUD
                                        </th>
                                         <th align="center" colspan="3" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                INTERCONSULTA
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            <b>'.$clave.'</b>
                                        </td>  
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="2">
                                            <b> '.$consultatipo.'</b>
                                        </td>   
                                                                                                                                 
                                    </tr>                                     
                                    <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;                                           
                                           padding: 0.3em;">
                                                Datos del paciente
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba" colspan="3">
                                            Nombre:'.utf8_decode($nombre).'</b>
                                        </td>   
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Sexo: <b>'.$sexo.'</b>
                                        </td>        
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom;color:#3f81ba">
                                            Edad: <b>'.$edad.'</b>
                                        </td>                                                                            
                                    </tr>                                                                           
                                   
                                     <tr>
                                        <th align="center" colspan="5" style="  background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                                Diagn&oacute;stico, Estado general y exploraci&oacute;n f&iacute;sica
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                             background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Diagn&oacute;stico: <b>'.utf8_decode($diagnosticoReg).'</b>
                                        </td>                                                                            
                                    </tr>         
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Diagn&oacute;stico al momento: '.utf8_decode($diagnostico).'
                                        </td>                                                                            
                                    </tr>
                                    <tr>
                                        <td style=" width: 100%;                                          
                                           vertical-align: top;
                                           background: #fcfccd;
                                           padding: 0.3em;
                                           caption-side: bottom; font-size:30px; color:#3f81ba" colspan="5">
                                            Justificaci&oacute;n: '.utf8_decode($justificacion).'
                                        </td>                                                                            
                                    </tr>                                
                                </table>
                                </BODY>
                                </HTML> ';


                $mimemail->set_from("solicitud_NoReply@medicavial.com.mx");
                //$mimemail->set_to("facparticulares@medicavial.com.mx");
                $mimemail->set_to("coordmed@medicavial.com.mx");
                $mimemail->add_cc("autpropias@medicavial.com.mx");
                $mimemail->add_cc("jlinares@medicavial.com.mx");
                $mimemail->add_cc("erosas@medicavial.com.mx");
                $mimemail->add_bcc("egutierrez@medicavial.com.mx");
                $mimemail->set_subject("- Solicitud de Interconsulta - ".$unidad." - ".$folio);
                $mimemail->set_html($cont);
                $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
                $mimemail->send();

            }
            /****************************** fin de env??o de correo a coordinaci??n medica cuando es una interconsulta ****************/

            $respuesta = array('respuesta' => "Tu solicitud ha sido registrada correctamente", "clave" => $clave);
            //$correo($html);
        }else{
            $respuesta = array('respuesta' => "Los Datos No se Guardaron Verifique su Informaci??n", "clave" => $clave);
        }
        
        echo json_encode($respuesta);

    }
    $db = null;

}

if($funcion == 'guardaSolicitudInfo'){

    $db = conectarMySQL();
    $postdata = file_get_contents("php://input");
    //aplicacmos json_decode para manejar los datos como arreglos de php
    $datos = json_decode($postdata);

    //datos de folio
    $clave = $datos->clave;
    $usuario = $datos->usuario;
    $descripcion = $datos->descripcion;
    $archivos = $datos->soporte;
    $fecha  = date("Y-m-d H:i:s");

    $rutas = array();

    if(!$db) {

        die('Something went wrong while connecting to MSSQL');

    }else{

        //verificamos que exista la carpeta
        if (!file_exists($nombre_fichero)) {
            mkdir("../solicitudes/".$clave);
        } 

        //recorremos el arreglo de archivos que mandamos 
        foreach ($archivos as $documento) {

            $archivo= $documento->archivo;
            $observaciones= $documento->observaciones;
            $tipoid = $documento->idtipo;
            $ubicacionactual = "../". $documento ->ubicacionreal; 
            $ubicacionnueva = "../solicitudes/".$clave."/".$archivo;
            
            rename( $ubicacionactual ,  $ubicacionnueva);

            $ubicacionnueva = str_replace( "../", "",$ubicacionnueva);

            $sql3 = "INSERT INTO ArchivosSolicitud
                  (
                         SOL_claveint,
                         TID_claveint,
                         ARS_ruta,
                         ARS_observaciones,
                         ARS_nombre                   
                  ) 
                  VALUES
                  (
                        :clave,
                        :tipo,
                        :ruta,
                        :observaciones,
                        :nombre
                  )";
            
            $temporal3 = $db->prepare($sql3);
            $temporal3->bindParam("clave", $clave);
            $temporal3->bindParam("tipo", $tipoid);
            $temporal3->bindParam("ruta", $ubicacionnueva);
            $temporal3->bindParam("observaciones", $observaciones);
            $temporal3->bindParam("nombre", $archivo);

            $temporal3->execute();

            //incluimos en el arreglo las rutas de los archivos que utlizamos
            array_push($rutas, $ubicacionnueva);



        }
        
        //generemamos un string donde ponemos todas las rutas del arreglo separado por comas
        $listarutas = implode(",", $rutas);


        //insertamos el seguimiento 
        $sql = "INSERT INTO SeguimientoSolicitud
              (
                     SOL_claveint,
                     Usu_login,
                     SES_descripcion,
                     SES_fecha,
                     SES_leido,
                     SES_archivos                  
              ) 
              VALUES
              (
                    :clave,
                    :usuario,
                    :descripcion,
                    :SES_fecha,
                    0,
                    :archivos
              )";
        
        $temporal = $db->prepare($sql);
        $temporal->bindParam("clave", $clave, PDO::PARAM_STR);
        $temporal->bindParam("usuario", $usuario, PDO::PARAM_STR);
        $temporal->bindParam("descripcion", $descripcion, PDO::PARAM_STR, 10);
        $temporal->bindParam("archivos", $listarutas, PDO::PARAM_STR);
        $temporal->bindParam("SES_fecha", $fecha);
        

        //ejecutamos la consulta
        if ($temporal->execute()){ 

            $respuesta = array('respuesta' => "Tu solicitud ha sido registrada");

        }else{
            $respuesta = array('respuesta' => "Los Datos No se Guardaron Verifique su Informaci??n");
        }

        
        echo json_encode($respuesta);

    }
    $db = null;

}

if($funcion == 'solicitudesFolio'){
    
    $folio = $_GET['folioapi'];

    $conexion = conectarMySQL();

    $sql = "SELECT Solicitudes.SOL_claveint AS clave, TIM_nombreE as tipo, Exp_folio as folio, SOL_lesionado as lesionado, SOL_fechaReg as fecharegistro, 
            SOL_fechaActualiza as fechaactualiza, Cia_nombrecorto as cliente, DATEDIFF(now() , SOL_fechaReg ) as diferencia FROM Solicitudes 
            INNER JOIN DetalleSolicitud ON DetalleSolicitud.SOL_claveint = Solicitudes.SOL_claveint
            LEFT JOIN Compania on Compania.Cia_clave = Solicitudes.Cia_clave
            LEFT JOIN TipoMovimiento ON TipoMovimiento.TIM_claveint = Solicitudes.TIM_claveint 
            WHERE SOL_estatus = 1 and Exp_folio = '$folio' ";

    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}

if($funcion == 'solicitudes'){
    
    $usuario = '';

    $conexion = conectarMySQL();

    $sql = "SELECT Solicitudes.SOL_claveint AS clave, TIM_nombreE as tipo, Exp_folio as folio, SOL_lesionado as lesionado, SOL_fechaReg as fecharegistro, 
            SOL_fechaActualiza as fechaactualiza, Cia_nombrecorto as cliente, DATEDIFF(now() , SOL_fechaReg ) as diferencia FROM Solicitudes 
            INNER JOIN DetalleSolicitud ON DetalleSolicitud.SOL_claveint = Solicitudes.SOL_claveint
            LEFT JOIN Compania on Compania.Cia_clave = Solicitudes.Cia_clave
            LEFT JOIN TipoMovimiento ON TipoMovimiento.TIM_claveint = Solicitudes.TIM_claveint 
            WHERE SOL_estatus = 1 ";

    if (isset($_GET['userapi'])) {
      $usuario = $_GET['userapi'];
      $sql = $sql . " AND USU_login = '$usuario'";
      $sql = $sql . " ORDER BY Solicitudes.SOL_fechaReg ";
    }else{

      $sql = $sql . " ORDER BY Solicitudes.SOL_fechaReg ";
      $sql = $sql . " LIMIT 0,30";
    }


     


    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}


if($funcion == 'solicitudesInfo'){
    
    $conexion = conectarMySQL();

    $sql = "SELECT Solicitudes.SOL_claveint AS clave, TIM_nombreE as tipo, Exp_folio as folio, SOL_lesionado as lesionado, SOL_fechaReg as fecharegistro, SOL_fechaActualiza as fechaactualiza, Cia_nombrecorto as cliente FROM Solicitudes 
            INNER JOIN DetalleSolicitud ON DetalleSolicitud.SOL_claveint = Solicitudes.SOL_claveint
            LEFT JOIN Compania on Compania.Cia_clave = Solicitudes.Cia_clave
            LEFT JOIN TipoMovimiento ON TipoMovimiento.TIM_claveint = Solicitudes.TIM_claveint 
            WHERE SOL_estatus = 2 ";

    if (isset($_GET['userapi'])) {
      $usuario = $_GET['userapi'];
      $sql = $sql . "AND USU_login = '$usuario'";
    }else{
      $sql = $sql . "LIMIT 0,30";
    }


    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}

if($funcion == 'detalleSolicitudesInfo'){
    
    $clave = $_GET['clave'];
    $datos = array();
    $dato = array();
    $archivos = array();
    $archivosTotales = array();

    $conexion = conectarMySQL();

    $sql = "SELECT SOL_claveint as clave , SES_descripcion as descripcion, SES_fecha as fecha, SES_leido as leido, Usuario.Usu_login as usuario, Usu_nombre as nombre, SES_archivos as archivos  
            FROM SeguimientoSolicitud
            INNER JOIN Usuario ON Usuario.Usu_login = SeguimientoSolicitud.Usu_login 
            WHERE SOL_claveint = '$clave' ORDER BY SES_fecha DESC";

    $result = $conexion->query($sql);

    foreach ($result as $info) {
      
      $clave = $info['clave'];
      $dato['clave'] = $info['clave'];
      $dato['descripcion'] = $info['descripcion'];
      $dato['fecha'] = $info['fecha'];
      $dato['leido'] = $info['leido'];
      $dato['usuario'] = $info['usuario'];
      $dato['nombre'] = $info['nombre'];

      if ($info['archivos'] != '') {

        $temporal = explode(",", $info['archivos']);
        
        foreach ($temporal as $archivo) {

          $archivos['ruta']  = $archivo;
          $nombre = str_replace( "solicitudes/".$clave."/" , "",$archivo);
          $archivos['nombre'] = $nombre;
          array_push($archivosTotales, $archivos);

        }

      }else{
        $archivosTotales = array();
      }

      $dato['archivos'] = $archivosTotales;

      array_push($datos, $dato);

    }
    
    echo json_encode($datos);

    $conexion = null;

}

if($funcion == 'solicitudesRespuestas'){
    
    $conexion = conectarMySQL();

    $sql = "SELECT Solicitudes.SOL_claveint AS clave, TIM_nombreE as tipo, Exp_folio as folio, SOL_lesionado as lesionado, SOL_fechaReg as fecharegistro, SOL_fechaActualiza as fechaactualiza, 
            Cia_nombrecorto as cliente, SOL_estatus as respuesta, DATEDIFF(SOL_fechaActualiza, SOL_fechaReg ) as diferencia FROM Solicitudes 
            INNER JOIN DetalleSolicitud ON DetalleSolicitud.SOL_claveint = Solicitudes.SOL_claveint
            LEFT JOIN Compania on Compania.Cia_clave = Solicitudes.Cia_clave
            LEFT JOIN TipoMovimiento ON TipoMovimiento.TIM_claveint = Solicitudes.TIM_claveint 
            WHERE SOL_estatus in (3,4) ";

    if (isset($_GET['userapi'])) {
      $usuario = $_GET['userapi'];
      $sql = $sql . "AND USU_login = '$usuario'";
    }else{
      $sql = $sql . "LIMIT 0,30";
    }

    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}

if ($funcion == 'temporal') {
    //Obtenemos la imagen que mandamos de angular
      if(isset($_FILES['file'])){
          //The error validation could be done on the javascript client side.       
          $file_name = $_FILES['file']['name'];
          $file_size =$_FILES['file']['size'];
          $file_tmp =$_FILES['file']['tmp_name'];
          $file_type=$_FILES['file']['type'];   
          $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

          move_uploaded_file($file_tmp,"../imgs/tmp/".$file_name);
          $resultado = array('ubicacion' => "imgs/tmp/".$file_name, 'temporal' => $file_tmp , 'tipo' => $file_type, 'nombre' => $file_name);

          echo json_encode($resultado);
          
      }

}

if($funcion == 'tipoDocumento'){

    $conexion = conectarMySQL();

    $sql = "SELECT * FROM TipoDocumento where TID_activa = 1";

    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}

// if($funcion == 'unidades'){

//     $conexion = conectarMySQL();

//     $sql = "SELECT * FROM Compania order BY Cia_nombrecorto";

//     $result = $conexion->query($sql);

//     $resultado = array();
//     $total = array();

//     //$datos = $result->fetchAll(PDO::FETCH_OBJ);
//     foreach ($result as $value) {

//         $clave = $value['Cia_clave'];
//         $nombre = $value['Cia_nombrecorto'];
//         $activa = $value['Cia_activa'];

//         if ($activa == 'S') {

//             $resultado['clave'] = $clave;
//             $resultado['nombre'] = $nombre;

//             array_push($total, $resultado);

//         }elseif ($activa == 'N' && $clave == '52') {

//             $resultado['clave'] = $clave; 
//             $resultado['nombre'] = $nombre;

//             array_push($total, $resultado);

//         }

        

//     } 

//     echo json_encode($total);

//     $conexion = null;

// }


if($funcion == 'usuarios'){

    $conexion = conectarMySQL();

    $sql = "SELECT * FROM Usuario";

    $result = $conexion->query($sql);

    $datos = $result->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode($datos);

    $conexion = null;

}

/////////////////////////// modulo de rehabilitaciones  ///////////////////////////////////////////////
/*************************        solicutud de cancelaci??n de folios       ****************************/
if($funcion=='enviaDatosCancelacion'){
    $mimemail = new nomad_mimemail();
    $usr = $_GET['usr'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $fol=       $datos->folio;
    $motivo =   $datos->motivo;
    $motivoCat =$datos->motivoCat;
    $folioSus = $datos->folioSus;
    $obs    =   $datos->Obs;
    $db = conectarMySQL();
    
    try{
        $query="SELECT Exp_completo, Exp_fechaNac,Expediente.Uni_clave as uni, Uni_nombre, Exp_fecreg, Usu_nombre,Pro_nombre, Cia_nombrecorto  FROM Expediente 
        inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  
        inner join Usuario on Expediente.Usu_registro = Usuario.Usu_login
        inner join Producto on Expediente.PRO_clave = Producto.Pro_clave
        inner join Compania on Expediente.Cia_clave = Compania.Cia_clave
        Where Exp_folio='".$fol."'";
        $result = $db->query($query);
        $datosFol = $result->fetch();    
        $nombre=$datosFol['Exp_completo'];
        $unidad=$datosFol['Uni_nombre'];
        $fecreg=$datosFol['Exp_fecreg'];
        $usuario=$datosFol['Usu_nombre'];
        $producto = $datosFol['Pro_nombre'];
        $compania = $datosFol['Cia_nombrecorto'];
        $uniClave = $datosFol['uni'];


        $documentos='';
        $documento='';

        $query="SELECT COUNT(*) AS contador FROM DocumentosDigitales where REG_folio='".$fol."'";
        $result = $db->query($query);
        $contDig = $result->fetch();
        $contadorDig= $contDig['contador'];
        if($contadorDig==0){
            $query="SELECT COUNT(*) AS contador FROM Consulta where Exp_folio='".$fol."'";
            $result = $db->query($query);
            $contHistCl = $result->fetch();
            $contadorHistoriaClinica= $contHistCl['contador'];
            if($contadorHistoriaClinica==0){
                $query="SELECT COUNT(*) AS contador FROM Vitales where Exp_folio='".$fol."'";
                $result = $db->query($query);
                $contVit = $result->fetch();
                $contadorVitales= $contVit['contador'];
                if($contadorVitales==0){
                    $query="SELECT COUNT(*) AS contador FROM NotaMedica where Exp_folio='".$fol."'";
                    $result = $db->query($query);
                    $contNot = $result->fetch();
                    $contadorNotaMedica= $contNot['contador'];
                    if($contadorNotaMedica==0){
                        $query="SELECT COUNT(*) AS contador FROM Subsecuencia where Exp_folio='".$fol."'";
                        $result = $db->query($query);
                        $contSub = $result->fetch();
                        $contadorSubsecuencia= $contSub['contador'];
                        if($contadorSubsecuencia==0){
                            $query="SELECT COUNT(*) AS contador FROM Rehabilitacion where Exp_folio='".$fol."'";
                            $result = $db->query($query);
                            $contReha = $result->fetch();
                            $contadorRehabilitacion= $contReha['contador'];
                            if($contadorRehabilitacion==0){
                                $documentos='NO';
                                $documento ='ninguno';
                            }else{
                                $documentos='SI';
                                $documento='Rehabilitacion';
                            }
                        }else{
                            $documentos='SI';
                            $documento='Subsecuencia';
                        }
                    }else{
                        $documentos='SI';
                        $documento='Nota Medica';
                    }
                }else{
                    $documentos='SI';
                    $documento='Signos Vitales';
                }
            }else{
                $documentos='SI';
                $documento='Historia Cl??nica';
            }
        }else{
            $documentos='SI';
            $documento='Documentos Digitales';
        }


        if($documentos=='NO'){

            $query="update Expediente SET Exp_cancelado=1, Usu_cancelado=:Usu_cancelado, Exp_fcancelado = now(), Exp_mcancelado=:Exp_mcancelado, Exp_duplicado=:Exp_duplicado, Exp_motCancel=:Exp_motCancel where Exp_folio=:Exp_folio";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);            
            $temporal->bindParam("Exp_mcancelado", $motivo);            
            $temporal->bindParam("Usu_cancelado", $usr);            
            $temporal->bindParam("Exp_duplicado", $folioSus);
            $temporal->bindParam("Exp_motCancel", $motivoCat);            
            $temporal->execute();
            $rs=mysql_query($query,$conn); 
            switch ($uniClave) {
            case '1':
                $emailClinica ='mvroma@medicavial.com.mx';
            break;
            case '2':
                $emailClinica ='mvsatelite@medicavial.com.mx';
            break;
            case '3':
                $emailClinica ='mvperisur@medicavial.com.mx';
            break;
            case '4':
                $emailClinica ='mvpuebla@medicavial.com.mx';
            break;
            case '5':
                $emailClinica ='mvmonterrey@medicavial.com.mx';
            break;
            case '6':
                $emailClinica ='mvmerida@medicavial.com.mx';
            break;
            case '7':
                $emailClinica ='mvsanluis@medicavial.com.mx';
            break;
             case '8':
                $emailClinica ='egutierrez@medicavial.com.mx';
            break;
            case '86':
                $emailClinica ='mvchihuahua@medicavial.com.mx';
            break;
            case '184':
                $emailClinica ='mvinterlomas@medicavial.com.mx';
            break;
            case '186':
                $emailClinica ='mvveracruz@medicavial.com.mx';
            break;
            default:
                # code...
            break;
        }

        $contenido='<HTML>
                        <HEAD>
                        </HEAD>
                        <BODY>
                        <br>                
                        <img src="mv.gif"> 
                        <br>
                        <br>
                        <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                            <tr>
                                <th colspan="4" align="center" style=" width: 25%; background: #ff0000;
                                   text-align: left;
                                   vertical-align: top;
                                   border: 1px solid #000;
                                   border-collapse: collapse;
                                   padding: 0.3em;
                                   caption-side: bottom;">
                                  FOLIO CANCELADO AUTOMATICAMENTE POR SISTEMA
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

                            </tr>                   
                            <tr>
                                <td colspan="4">
                                    Motivo de cancelaci&oacute;n <b>'.utf8_decode($motivo).'</b>
                                </td>                                           
                            </tr>';
                            if($folSus!=''){
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Folio sustituto: <b>'.$folSus.'</b>
                                    </td>                                           
                                </tr>';
                            }
                            if($obs!=''){
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Observaciones: <b>'.utf8_decode($obs).'</b>
                                    </td>                                           
                                </tr>';
                            }
                             if($fecreg!=''){
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Fecha y hora de registro de paciente: <b>'.$fecreg.'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Fecha y hora de solicitud de cancelaci&oacute;n: <b>'.$fecha.'</b>
                                    </td>                                           
                                </tr>';
                            }
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Usuario: <b>'.utf8_decode($usuario).'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Producto: <b>'.utf8_decode($producto).'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Cliente: <b>'.utf8_decode($compania).'</b>
                                    </td>                                           
                                </tr>';

                        $contenido.='</table>
                        </BODY>
                        </HTML>         
        ';
        $mimemail->set_from("cancelacion_noReply@medicavial.com.mx");
        $mimemail->set_to("cmendez@medicavial.com.mx");
        //$mimemail->set_to("egutierrez@medicavial.com.mx");
        //$mimemail->set_to("enriqueerick@gmail.com");
        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
        $mimemail->add_cc($emailClinica);
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("Cancelaci??n del folio ".$fol);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "mv.gif");

        if ($mimemail->send()){
           echo 'exito';
        }
        else {
            echo "error";
        }           

        }else{

        $fecha  = date("Y-m-d H:i:s");

        switch ($uniClave) {
            case '1':
                $emailClinica ='mvroma@medicavial.com.mx';
            break;
            case '2':
                $emailClinica ='mvsatelite@medicavial.com.mx';
            break;
            case '3':
                $emailClinica ='mvperisur@medicavial.com.mx';
            break;
            case '4':
                $emailClinica ='mvpuebla@medicavial.com.mx';
            break;
            case '5':
                $emailClinica ='mvmonterrey@medicavial.com.mx';
            break;
            case '6':
                $emailClinica ='mvmerida@medicavial.com.mx';
            break;
            case '7':
                $emailClinica ='mvsanluis@medicavial.com.mx';
            break;
             case '8':
                $emailClinica ='egutierrez@medicavial.com.mx';
            break;
            case '86':
                $emailClinica ='mvchihuahua@medicavial.com.mx';
            break;
            case '184':
                $emailClinica ='mvinterlomas@medicavial.com.mx';
            break;
            case '186':
                $emailClinica ='mvveracruz@medicavial.com.mx';
            break;
            default:
                # code...
            break;
        }

        $contenido='<HTML>
                        <HEAD>
                        </HEAD>
                        <BODY>
                        <br>                
                        <img src="mv.gif"> 
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
                                    DATOS DE CANCELACION DE FOLIO
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

                            </tr>                   
                            <tr>
                                <td colspan="4">
                                    Motivo de cancelaci&oacute;n <b>'.utf8_decode($motivo).'</b>
                                </td>                                           
                            </tr>';
                            if($folSus!=''){
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Folio sustituto: <b>'.$folSus.'</b>
                                    </td>                                           
                                </tr>';
                            }
                            if($obs!=''){
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Observaciones: <b>'.utf8_decode($obs).'</b>
                                    </td>                                           
                                </tr>';
                            }
                             if($fecreg!=''){
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Fecha y hora de registro de paciente: <b>'.$fecreg.'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Fecha y hora de solicitud de cancelaci&oacute;n: <b>'.$fecha.'</b>
                                    </td>                                           
                                </tr>';
                            }
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Usuario: <b>'.utf8_decode($usuario).'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Producto: <b>'.utf8_decode($producto).'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Cliente: <b>'.utf8_decode($compania).'</b>
                                    </td>                                           
                                </tr>';
                                $contenido.='<tr>
                                    <td colspan="4">
                                        Con documentos: <b>'.utf8_decode($documento).'</b>
                                    </td>                                           
                                </tr>';

                        $contenido.='</table>
                        </BODY>
                        </HTML>         
        ';
        $mimemail->set_from("cancelacion_noReply@medicavial.com.mx");
        $mimemail->set_to("cmendez@medicavial.com.mx");
        //$mimemail->set_to("egutierrez@medicavial.com.mx");
        //$mimemail->set_to("enriqueerick@gmail.com");
        $mimemail->add_cc("mvclinicas@medicavial.com.mx");
        $mimemail->add_cc($emailClinica);
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->add_bcc("enriqueerick@gmail.com");
        $mimemail->set_subject("Cancelaci??n del folio ".$fol);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "mv.gif");

        if ($mimemail->send()){
            $query="update Expediente SET Exp_solCancela='S' where Exp_folio=:Exp_folio";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);            
            $temporal->execute();
            $rs=mysql_query($query,$conn);
            echo 'exito';
        }
        else {
            echo "error";
        }
    }
        }catch(Exception $e){
            echo "errorTry";
        }
        $db = null;

    }
    /*************************        solicutud de cancelaci??n de folios       ****************************/

/*************************        pureba de envio de correo       ****************************/
if($funcion=='pruebaCorreo'){
    $fol=$_GET['fol'];
    $mimemail = new nomad_mimemail();
    

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
                                        <th colspan="3" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            DATOS GENERALES DE REGISTRO
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 20%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Folio: <b>OPMV001000</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Nombre: <b>PRUEBA DE ENVIO DE CORREO</b>
                                        </td>
                                        <td  style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Compa??ia: <b>AXA</b>
                                        </td>

                                    </tr>                                    
                                    <tr>
                                        <td colspan="3">
                                            ObservacionesES UNA PRUEBA DE ENVIO DE CORREO</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';

        $mimemail->set_from("cancelacion_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to("enriqueerick@gmail.com");
        $mimemail->add_bcc("egutierrez@medicavial.com.mx");
        $mimemail->set_subject("-PRUEBA DE ENVIO DE CORREO-");
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
        if ($mimemail->send()){
            echo $contenido;
           
        }else {
           echo 'no se envi??';
        }
        /****************   fin de envio de correo      *********************/
  
    echo json_encode($respuesta);

    }
    /*************************        solicutud de cancelaci??n de folios       ****************************/

if($funcion=='guardaPaseR'){
    $fol=$_GET['folio'];
    $usr=$_GET['usr'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    $noSes         =$datos->noSes;
    $obs        =$datos->obs; 
    $diag        =$datos->diag; 
    $fecharehab=date('Y-m-d H:i:s');
    $db = conectarMySQL(); 

    $query="Select Exp_folio From RehabilitacionPase WHERE Exp_folio='".$fol."';";
    $result = $db->query($query);
    $pasRe = $result->fetch();    
    try{           
            if($pasRe){
                $query="Update RehabilitacionPase set RPase_fecha=:RPase_fecha,RPase_rehabilitacion=:RPase_rehabilitacion,RPase_obs=:RPase_obs,
                        RPase_diagnostico=:RPase_diagnostico,Usu_registro=:Usu_registro where Exp_folio=:Exp_folio";
            }else{
                $query="Insert into RehabilitacionPase (Exp_folio, RPase_fecha, RPase_rehabilitacion, RPase_obs, RPase_diagnostico, Usu_registro)
                        Values(:Exp_folio,:RPase_fecha,:RPase_rehabilitacion,:RPase_obs,:RPase_diagnostico,:Usu_registro)";
            }
            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("RPase_fecha", $fecharehab);
            $temporal->bindParam("RPase_rehabilitacion", $noSes);
            $temporal->bindParam("RPase_obs", $obs);                 
            $temporal->bindParam("RPase_diagnostico", $diag);                 
            $temporal->bindParam("Usu_registro", $usr);                 
        if ($temporal->execute()){            
            $respuesta=array("respuesta"=>'SI');
            echo json_encode($respuesta);
        }else{
            $respuesta = array('respuesta' => 'NO');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
}   
    $db = null;  
}

if($funcion=='guardaRehabilitacion'){
    $mimemail = new nomad_mimemail();
    $fol=$_GET['fol'];
    $usr=$_GET['usr'];
    $uni=$_GET['uni'];
    $postdata = file_get_contents("php://input");
    $datos = json_decode($postdata);
    
    $rehabtipo=$datos->tipo;
    $rehabobs=$datos->observa;
    $rehabwaddell=$datos->criterios;
    $rehabdolor=$datos->escala;
    $rehabmejoria=$datos->mejoria;
    $rehabfecha=date('Y-m-d H:i:s');
    $rehabduracion=$datos->duracion;
    $rehabcitaant=$datos->acudio;
    $db = conectarMySQL(); 

    $query="Select Max(Rehab_cons)+1 As Cons From Rehabilitacion Where Exp_folio='".$fol."';";
    $result = $db->query($query);
    $pasRe = $result->fetch();
    if($pasRe['Cons']==null)$pasRe['Cons']=1;
    $noReha= $pasRe['Cons'];
             
    try{                       
            
            $query="Insert into Rehabilitacion (Exp_folio, Rehab_cons, Rehab_obs, Rehab_dolor, Rehab_mejoria, Rehab_tipo, Rehab_duracion, Rehab_citaant, Rehab_fecha, Usu_registro, Uni_clave,Rehab_waddell)
                     Values(:Exp_folio,:Rehab_cons,:Rehab_obs,:Rehab_dolor,:Rehab_mejoria,:Rehab_tipo,:Rehab_duracion,:Rehab_citaant,:Rehab_fecha,:Usu_registro,:Uni_clave,:Rehab_waddell)";

            $temporal = $db->prepare($query);
            $temporal->bindParam("Exp_folio", $fol);
            $temporal->bindParam("Rehab_cons", $noReha);
            $temporal->bindParam("Rehab_obs", $rehabobs);
            $temporal->bindParam("Rehab_dolor", $rehabdolor);                 
            $temporal->bindParam("Rehab_mejoria", $rehabmejoria);                 
            $temporal->bindParam("Rehab_tipo", $rehabtipo); 
            $temporal->bindParam("Rehab_duracion", $rehabduracion);                 
            $temporal->bindParam("Rehab_citaant", $rehabcitaant); 
            $temporal->bindParam("Rehab_fecha", $rehabfecha);                 
            $temporal->bindParam("Usu_registro", $usr);     
            $temporal->bindParam("Uni_clave", $uni);  
            $temporal->bindParam("Rehab_waddell", $rehabwaddell);         
        if ($temporal->execute()){            
           if($noReha==11){
                $query="SELECT Exp_completo, Exp_fechaNac, Uni_nombre FROM Expediente inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  Where Exp_folio='".$fol."'";
                $result = $db->query($query);
                $datosFol = $result->fetch();    
                $nombre=$datosFol['Exp_completo'];
                $unidad=$datosFol['Uni_nombre'];
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
                                            INICIO DEL TERCER CICLO DE REHABILITACI&Oacute;N
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

                                    </tr> ';
                                    
                                $contenido.='</table>
                                </BODY>
                                </HTML>         
                    ';
                    $mimemail->set_from("rehabilitacion_noReply@medicavial.com.mx");
                    //$mimemail->set_to("egutierrez@medicavial.com.mx");
                    $mimemail->set_to("jlinares@medicavial.com.mx");
                    $mimemail->set_cc("coordreh@medicavial.com.mx");
                    $mimemail->add_bcc("egutierrez@medicavial.com.mx");
                    $mimemail->set_subject("Inicio de tercer ciclo rehabilitaci??n - ".$fol);
                    $mimemail->set_html($contenido);
                    $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

                    if ($mimemail->send()){
                        $respuesta=array("respuesta"=>'SI');
                        echo json_encode($respuesta);
                    }
                    else {
                        echo "error";
                    }
                }
                elseif($noReha==15){
                $query="SELECT Exp_completo, Exp_fechaNac, Uni_nombre FROM Expediente inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  Where Exp_folio='".$fol."'";
                $result = $db->query($query);
                $datosFol = $result->fetch();    
                $nombre=$datosFol['Exp_completo'];
                $unidad=$datosFol['Uni_nombre'];
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
                                            FIN DE TERCER CICLO DE REHABILITACI&Oacute;N
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

                                    </tr> ';
                                    
                                $contenido.='</table>
                                </BODY>
                                </HTML>         
                ';
                $mimemail->set_from("rehabilitacion_noReply@medicavial.com.mx");
                $mimemail->set_to("scisneros@medicavial.com.mx");
                $mimemail->set_cc("jsanchez@medicavial.com.mx");
                $mimemail->set_cc("agutierrez@medicavial.com.mx");
                $mimemail->set_cc("jlinares@medicavial.com.mx");
                $mimemail->set_cc("coordreh@medicavial.com.mx");
                $mimemail->add_bcc("egutierrez@medicavial.com.mx");
                $mimemail->set_subject("ALERTA REHABILITACI??N NO. 15 - ".$fol);
                $mimemail->set_html($contenido);
                $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

                if ($mimemail->send()){
                    $respuesta=array("respuesta"=>'SI');
                    echo json_encode($respuesta);
                }
                else {
                    echo "error";
                }

             }elseif ($noReha==5||$noReha==10) {
                $query="Select Cia_Clave From Expediente Where Exp_folio='".$fol."';";
                $result = $db->query($query);
                $cia = $result->fetch();
                $ciaCve = $cia['Cia_Clave'];
                if($ciaCve==51||$ciaCve==54){
                    $query="SELECT Exp_completo, Exp_fechaNac, Uni_nombre FROM Expediente inner join Unidad on Expediente.Uni_clave = Unidad.Uni_clave  Where Exp_folio='".$fol."'";
                    $result = $db->query($query);
                    $datosFol = $result->fetch();    
                    $nombre=$datosFol['Exp_completo'];
                    $unidad=$datosFol['Uni_nombre'];
                    $query="select Rehab_cons,Usuario.Usu_nombre, Rehab_fecha from Rehabilitacion
                            inner join Usuario on Rehabilitacion.Usu_Registro=Usuario.Usu_login
                            where Exp_folio='".$fol."'";
                    $result = $db->query($query);
                   

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
                                        <th align="center" colspan="3" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            FIN DE CICLO DE REHABILITACION
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
                                        <td style=" width: 50%;
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

                                    </tr><tr>
                                        <td style=" width: 25%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            # REHABILITACION</b>
                                        </td>
                                        <td style=" width: 50%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            REHABILITADOR</b>
                                        </td>
                                        <td style=" width: 25%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                        FECHA</b>
                                        </td>
                                    </tr> ';
                                $indiceTabla=1;
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                                        $contenido.='
                                            <tr style="border: 1px solid black; border-collapse: collapse;">
                                                <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Rehab_cons']).'</td>
                                                <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_encode($row['Usu_nombre']).'</td>
                                                <td style="border: 1px solid black; border-collapse: collapse; padding: 0px 5px 0px 5px;">'.utf8_decode($row['Rehab_fecha']).'</td>                                               
                                            </tr>
                                            ';

                                $indiceTabla++;
                                }                                                             
                                $contenido.='</table>
                                </BODY>
                                </HTML>         
                    ';
                    $mimemail->set_from("rehabilitacion_noReply@medicavial.com.mx");
                    $mimemail->set_to("chernandez@medicavial.com.mx");                    
                    $mimemail->add_bcc("egutierrez@medicavial.com.mx");
                    $mimemail->set_subject("FIN DE CICLO DE REHABLILTACION - ".$fol);
                    $mimemail->set_html($contenido);
                    $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");

                    if ($mimemail->send()){
                        $respuesta=array("respuesta"=>'SI');
                        echo json_encode($respuesta);
                    }
                    else {
                        echo "error";
                    }
                }
             }else{
                 $respuesta=array("respuesta"=>'SI');
                    echo json_encode($respuesta);
             }

        }else{
            $respuesta = array('respuesta' => 'NO');
            echo json_encode($respuesta);
        }    
    }catch(Exception $e){
    $respuesta=  array('respuesta' => $e->getMessage() );
     echo json_encode($respuesta);
    }   
    $db = null;  
}
if($funcion=='calFecha'){
    $fecNac=$_GET['fechaNac'];

    $fecha = explode("-", $fecNac);
    

    $fechaNac2= date('Y-m-d', strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0])); 

    $edad = calculaEdad($fechaNac2);
    $edadArray=explode("/", $edad);
    $anios=$edadArray[0];
    $meses =$edadArray[1];

    $respuesta=array('anios'=>$anios,'meses'=>$meses);
    echo json_encode($respuesta);
}

if($funcion=='registraAcceso'){
        $usr= $_GET['usr'];
        $info=detect();         

        $nav    = $info["browser"];
        $ip     = $_SERVER['REMOTE_ADDR'];
        $fecha = date("Y-m-d H:i:s");
        $db = conectarMySQL(); 
        try{
            $query="Insert into acceso(accUsr,accIp,accNav,accFech,accSistema)
                         Values(:accUsr,:accIp,:accNav,:accFech,'MVPROPIAS')";
            $temporal = $db->prepare($query);
            $temporal->bindParam("accUsr", $usr);
            $temporal->bindParam("accIp", $ip);
            $temporal->bindParam("accNav", $nav);
            $temporal->bindParam("accFech", $fecha); 
             if ($temporal->execute()){            
                $respuesta=array("respuesta"=>'SI');
                echo json_encode($respuesta);
            }else{
                $respuesta = array('respuesta' => 'NO');
                echo json_encode($respuesta);
            }   
        }
        catch(Exception $e){
            echo $e->getMessage();
        }        
        $db = null;
        //echo $_SERVER['HTTP_USER_AGENT'];
    }

//////////////////////////  fin de m??dulo de rehabilitaciones           ///////////////////////////////


function calculaEdad($fechaNac){
    $fecha_de_nacimiento = $fechaNac; 
$fecha_actual = date ("Y-m-d"); 
//$fecha_actual = date ("2006-03-05"); //para pruebas 

// separamos en partes las fechas 
$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
$array_actual = explode ( "-", $fecha_actual ); 

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos a??os 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos d??as 

//ajuste de posible negativo en $d??as 
if ($dias < 0) 
{ 
    --$meses; 

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
    switch ($array_actual[1]) { 
           case 1:     $dias_mes_anterior=31; break; 
           case 2:     $dias_mes_anterior=31; break; 
           case 3:  
                if (bisiesto($array_actual[0])) 
                { 
                    $dias_mes_anterior=29; break; 
                } else { 
                    $dias_mes_anterior=28; break; 
                } 
           case 4:     $dias_mes_anterior=31; break; 
           case 5:     $dias_mes_anterior=30; break; 
           case 6:     $dias_mes_anterior=31; break; 
           case 7:     $dias_mes_anterior=30; break; 
           case 8:     $dias_mes_anterior=31; break; 
           case 9:     $dias_mes_anterior=31; break; 
           case 10:     $dias_mes_anterior=30; break; 
           case 11:     $dias_mes_anterior=31; break; 
           case 12:     $dias_mes_anterior=30; break; 
    } 

    $dias=$dias + $dias_mes_anterior; 
} 

//ajuste de posible negativo en $meses 
if ($meses < 0) 
{ 
    --$anos; 
    $meses=$meses + 12; 
} 
$edadCal=$anos.'/'.$meses;
return $edadCal;
}
function bisiesto($anio_actual){ 
    $bisiesto=false; 
    //probamos si el mes de febrero del a??o actual tiene 29 d??as 
      if (checkdate(2,29,$anio_actual)) 
      { 
        $bisiesto=true; 
    } 
    return $bisiesto; 
}

/************************ funcion para detectar navegaro y su versi??n para registrar en el inicion de sesi??n  *************************/

/*Funcion que devuelve el Navegador Actual*/
function detect()
{
    $browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
    $os=array("WIN","MAC","LINUX");
 
    # definimos unos valores por defecto para el navegador y el sistema operativo
    $info['browser'] = "OTHER";
    $info['os'] = "OTHER";
 
    # buscamos el navegador con su sistema operativo
    foreach($browser as $parent)
    {
        $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
        $f = $s + strlen($parent);
        $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
        $version = preg_replace('/[^0-9,.]/','',$version);
        if ($s)
        {
            $info['browser'] = $parent;
            $info['version'] = $version;
        }
    }
 
    # obtenemos el sistema operativo
    foreach($os as $val)
    {
        if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
            $info['os'] = $val;
    }
 
    # devolvemos el array de valores
    return $info;
}

/************************ fin funcion para detectar navegaro y su versi??n para registrar en el inicion de sesi??n  *************************/

############# REVISA ESTATUS DEL REGISTRO DE PACIENTES #############
if($funcion == 'revisaUltimoRegistro'){ 
    $unidad=$_GET['unidad'];  
    $usr = $_GET['usr']; 
    #print_r($usrLogin);
    $db = conectarMySQL();
    $query="SELECT Exp_folio, Exp_estatusReg 
            from Expediente 
            where Uni_clave='".$unidad."' and Usu_registro='".$usr."'
            order by Exp_cons desc limit 1
            ";
    #print_r($query);
    $result = $db->query($query);
    $verificaMedico = $result->fetch();
    echo json_encode($verificaMedico);
    $db = null;    
}
############# TERMINA REVISION DE ESTATUS DE REGISTRO #############

############# RECUPERA LOS DATOS DEL ULTIMO REGISTRO PENDIENTE #############
if($funcion == 'getRegistroIncompleto'){ 
    $unidad=$_GET['unidad']; 
    $usr = $_GET['usr'];   
    #print_r($usrLogin);
    $db = conectarMySQL();
    $query="SELECT * 
            from Expediente 
            where Uni_clave='".$unidad."' and Usu_registro='".$usr."'
            and Exp_estatusReg=1
            order by Exp_cons desc limit 1
            ";
    #print_r($query);
    $result = $db->query($query);
    $verificaMedico = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($verificaMedico);
    $db = null;    
}
############# TERMINA DATOS DEL ULTIMO REGISTRO PENDIENTE #############

############# RECUPERA EL LISTADO DE MEDICOS #############
if($funcion == 'listadoMedicos'){ 
    $db = conectarMySQL();
    $query="SELECT Med_clave, upper(concat(Med_nombre,' ',Med_paterno,' ',Med_materno)) as nombre, 
                   Med_cedula, Med_esp, Uni_nombrecorto
            from Medico 
            inner join Unidad on Medico.Uni_clave=Unidad.Uni_clave
            where Med_activo='S' and Medico.Uni_clave <> 8
            order by nombre
            ";
    #print_r($query);
    $result = $db->query($query);
    $verificaMedico = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($verificaMedico);
    $db = null;    
}
############# TERMINA LISTADO DE MEDICOS #############

############# RECUPERA EL LISTADO DE OTRAS REFERENCIAS #############
if($funcion == 'listadoOtrasReferencias'){ 
    $db = conectarMySQL();
    $query="SELECT * from Referencia
            where Ref_clave<>1 and Ref_clave<>2
            order by Ref_nombre
            ";
    #print_r($query);
    $result = $db->query($query);
    $verificaMedico = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($verificaMedico);
    $db = null;    
}
############# TERMINA LISTADO DE OTRAS REFERENCIAS #############

############# INSERTA NUEVA REFERENCIA #############
if($funcion=='guardaReferencia'){
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);

    $nombre     = $datos->nombre;
    $telefono   = $datos->telefono;
    $email      = $datos->email;
    $obs        = $datos->obs;
    
    $db = conectarMySQL(); 
    
                try{
                    $sql="INSERT into Referencia(Ref_clave, 
                                                 Ref_nombre, 
                                                 Ref_tel,
                                                 Ref_email,
                                                 Ref_Obs)
                                        VALUES(DEFAULT,
                                               '".$nombre."',
                                               '".$telefono."',
                                               '".$email."',
                                               '".$obs."');
                                                            ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}

############# INSERTA NUEVA REFERENCIA #############


############# CONSULTA PRODUCTO POR PACIENTE #############
if($funcion == 'getProducto'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();

        $query="SELECT Pro_clave 
                    FROM Expediente
                    WHERE Exp_folio='".$folio."'
                    ";

    $result = $db->query($query);
    $respuesta = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($respuesta);
    $db = null;    
}
############# TERMINA CONSULTA PRODUCTO POR PACIENTE #############


############# CONSULTA SI EXISTE DOCUMENTO Rh #############
if($funcion == 'getDoctoHr'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();

        $query="SELECT MAX(Arc_cons) As Cons 
                    FROM DocumentosDigitales 
                    WHERE REG_folio='".$folio."' and Arc_tipo=24";

                    $result = $db->query($query);
                    $rs = $result->fetch();
                    $cons=$rs['Cons'];

        if($cons==0 || $cons==null || $cons==''){
            $existe='N';
        } elseif($cons>0){
            $existe='S';
        };

    //$result = $db->query($query);
    //$respuesta = $result->fetch(PDO::FETCH_ASSOC);
    $respuesta = $existe;
    echo json_encode($respuesta);
    $db = null;    
}
############# TERMINA CONSULTA SI EXISTE DOCUMENTO Rh #############


############# INSERTA DATOS DE SOLO REHABILITACION #############
if($funcion=='guardaSoloRehabilitacion'){
    $folio      = $_GET['folio'];
    $ciaClave   = $_GET['ciaClave'];
    $uniClave   = $_GET['uniClave'];
    $sesiones   = $_GET['sesiones'];
    
    $db = conectarMySQL(); 
    
                try{
                    $sql="INSERT into Solo_rehabilitacion(Id_soloRh, 
                                                          Exp_folio, 
                                                          Cia_clave,
                                                          Uni_clave,
                                                          Total_sesiones)
                                            VALUES(DEFAULT,
                                                   '".$folio."',
                                                   '".$ciaClave."',
                                                   '".$uniClave."',
                                                   '".$sesiones."');
                                                ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}
############# TERMINA DATOS DE SOLO REHABILITACION #############


############# CONSULTA TOTAL DE SESIONES A TOMAR EN SOLO RH #############
if($funcion == 'getTotalSes'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();

        $query="SELECT * FROM Solo_rehabilitacion
                    WHERE Exp_folio='".$folio."'
                    ";

    $result = $db->query($query);
    $respuesta = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode($respuesta);
    $db = null;    
}
############# TERMINA CONSULTA TOTAL DE SESIONES A TOMAR EN SOLO RH #############


############# RECUPERA LA HISTORIA CLINICA #############
if($funcion == 'traeHistoriaClinica'){ 
    $folio=$_GET['folio'];
    $db = conectarMySQL();
    $query="SELECT Hist_clave, Exp_folio, Pad_clave, Pad_obs, Enfermedad.Enf_nombre 
            from HistoriaPadecimiento
            inner join Enfermedad on Enf_clave=Pad_clave
            where Exp_folio='".$folio."'
            ";
    #print_r($query);
    $result = $db->query($query);
    $verificaMedico = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($verificaMedico);
    $db = null;    
}
############# TERMINA LISTADO DE HISTORIA CLINICA #############


############# INSERTA EN HISTORIA CLINICA REHABILITACION #############
if($funcion=='guardaHCR'){
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);

    $folio          = $datos->folio;
    $padecimiento   = $datos->padecimiento;
    $observaciones  = $datos->observaciones;
    $claveEnf       = $datos->claveEnf;
    
    $db = conectarMySQL(); 
    
                try{
                    $sql="INSERT into HistoriaClinicaReh(id_HCR, 
                                                         Exp_folio, 
                                                         Pad_clave, 
                                                         Pad_obs)
                                        VALUES(DEFAULT,
                                               '".$folio."',
                                               '".$claveEnf."',
                                               '".$observaciones."');
                                            ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}
############# TERMINA DE INSERTAR HCR #############


############# BUSCA HISTORIA CLINICA REHABILITACION #############
if($funcion == 'getHCR'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();
    $query="SELECT Enf_nombre, Pad_obs FROM HistoriaClinicaReh
            inner join Enfermedad on HistoriaClinicaReh.Pad_clave=Enfermedad.Enf_clave
            where Exp_folio='".$folio."'
            ";
    $result = $db->query($query);
    $respuesta = $result->fetchAll(PDO::FETCH_OBJ);
    echo json_encode($respuesta);
    $db = null;    
}
############# TERMINA DE BUSCAR HISTORIA CLINICA REHABILITACION #############


############# INDICA SI SE SUBI?? DOCUMENTO PARA PASAR A Rh #############
if($funcion=='doctoRh'){
    $folio          = $_GET['folio'];
    $indicador      = $_GET['indicador']; 
    $nombreDocto    = $_GET['nombreDocto']; //trae el nombre del documento
    $tipoDocto      = $_GET['tipoDocto']; //indica si es Responsiva o Pase a Rehabilitacion
    $tipo           = 24; //indica que se trata de solicitud de terapia fisica
    $usuLogin       = $_GET['usuLogin'];
    
    $db = conectarMySQL(); 
    
                try{
                    $query="SELECT MAX(Arc_cons)+1 As Cons 
                            FROM DocumentosDigitales 
                            WHERE REG_folio='".$folio."' and Arc_tipo='".$tipo."'";

                    $result = $db->query($query);
                    $rs = $result->fetch();
                    $cons=$rs['Cons'];

                    if($cons==0 || $cons==null || $cons==''){
                        $cons=1;
                    } elseif($cons>0){
                        //$cons=$cons+1;
                    };

                    $sql="INSERT INTO DocumentosDigitales(Arc_cons, 
                                                          Arc_clave, 
                                                          REG_folio, 
                                                          Arc_archivo, 
                                                          Arc_tipo, 
                                                          Arc_desde, 
                                                          USU_login, 
                                                          Arc_fecreg)
                                                VALUES('".$cons."', 
                                                       '".$cons."_".$nombreDocto."', 
                                                       '".$folio."',
                                                       'api/classes/doctosRh/".$nombreDocto."', 
                                                       '".$tipo."',
                                                       'REGISTRO_MV',
                                                       '".$usuLogin."', 
                                                       NOW())
                                                    ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}
############# TERMINA DOCUMENTO PARA PASAR A Rh #############


############# ACTUALIZA EL NUMERO TOTAL DE REHABILITACIONES #############
if($funcion=='actualizaRehab'){
    $folio      = $_GET['folio'];
    $sesiones   = $_GET['sesiones'];
    
    $db = conectarMySQL(); 
    
                try{
                    $sql="UPDATE Solo_rehabilitacion
                            set Total_sesiones='".$sesiones."'
                            where Exp_folio='".$folio."'
                            ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}
############# TERMINA DATOS DE SOLO REHABILITACION #############


############# RECUPERA DIAGNOSTICO INICIAL #############
if($funcion == 'getDiagnosticoInicial'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();
    $query="SELECT ObsNot_diagnosticoRx FROM ObsNotaMed
            where Exp_folio='".$folio."'
            ";
    $result = $db->query($query);
    $respuesta = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode($respuesta);
    $db = null;    
}
############################################################


############# GUARDA HISTORIA CLINICA DE REHABILITACION #############
if($funcion=='guardaHistClinicaRh'){
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);

    print_r($datos);

    $folio                  = $datos->folio;
    $diabetes               = $datos->diabetes;
    $hipertension           = $datos->hipertension;
    $marcapasos             = $datos->marcapasos;
    $radioterapia           = $datos->radioterapia;
    $epilepsia              = $datos->epilepsia;
    $pielSensible           = $datos->pielSensible;
    $lupus                  = $datos->lupus;
    $intervenciones         = $datos->intervenciones;
    $aniosDiabetes          = $datos->aniosDiabetes;
    $aniosHipertension      = $datos->aniosHipertension;
    $aniosMarcapasos        = $datos->aniosMarcapasos;
    $aniosRadioterapia      = $datos->aniosRadioterapia;
    $aniosEpilepsia         = $datos->aniosEpilepsia;
    $aniosPielSensible      = $datos->aniosPielSensible;
    $aniosLupus             = $datos->aniosLupus;
    $aniosIntervenciones    = $datos->aniosIntervenciones;
    $mesesDiabetes          = $datos->mesesDiabetes;
    $mesesHipertension      = $datos->mesesHipertension;
    $mesesMarcapasos        = $datos->mesesMarcapasos;
    $mesesRadioterapia      = $datos->mesesRadioterapia;
    $mesesEpilepsia         = $datos->mesesEpilepsia;
    $mesesPielSensible      = $datos->mesesPielSensible;
    $mesesLupus             = $datos->mesesLupus;
    $mesesIntervenciones    = $datos->mesesIntervenciones;
    $diagInicial            = $datos->diagInicial;
    $valInicial             = $datos->valInicial;
    $tratamientosPrevios    = $datos->tratamientosPrevios;
    $escalaDolor            = $datos->escalaDolor;
    $tipoDolor              = $datos->tipoDolor;
    // $flexion                = $datos->flexion;
    // $extension              = $datos->extension;
    // $lateralizacion         = $datos->lateralizacion;
    // $rotInterna             = $datos->rotInterna;
    // $rotExterna             = $datos->rotExterna;
    $examenMuscular         = $datos->examenMuscular;
    $marchaPostura          = $datos->marchaPostura;
    $obsMarchaPostura       = $datos->obsMarchaPostura;
    $banho                  = $datos->banho;
    $comida                 = $datos->comida;
    $trabajo                = $datos->trabajo;
    $vestirse               = $datos->vestirse;
    $obsAVD                 = $datos->obsAVD;
    $estadoActual           = $datos->estadoActual;
    
    $db = conectarMySQL(); 
    
                try{
                    $sql="INSERT into hist_rehabilitacion(id_HR, Exp_folio,
                                                          diabetes, hipertension, marcapasos, radioterapia, 
                                                          epilepsia, piel_sensible, lupus, intervenciones,
                                                          anios_diabetes, anios_hipertension, anios_marcapasos, anios_radioterapia, 
                                                          anios_epilepsia, anios_pielSensible, anios_lupus, anios_intervenciones, 
                                                          meses_diabetes, meses_hipertension, meses_marcapasos, meses_radioterapia, 
                                                          meses_epilepsia, meses_pielSensible, meses_lupus, meses_intervenciones,
                                                          diag_inicial, val_inicial, tratamientos_previos, 
                                                          escala_dolor, tipo_dolor, examen_muscular, marcha_postura, obs_marchaPostura, 
                                                          dif_banho, dif_comida, dif_trabajo, dif_vestirse, 
                                                          obs_AVD, estado_actual, fecha_AVD)
                                VALUES(DEFAULT, '".$folio."',
                                       '".$diabetes."', '".$hipertension."', '".$marcapasos."', '".$radioterapia."',
                                       '".$epilepsia."', '".$pielSensible."', '".$lupus."', '".$intervenciones."',
                                       '".$aniosDiabetes."', '".$aniosHipertension."', '".$aniosMarcapasos."', '".$aniosRadioterapia."',
                                       '".$aniosEpilepsia."', '".$aniosPielSensible."', '".$aniosLupus."', '".$aniosIntervenciones."',
                                       '".$mesesDiabetes."', '".$mesesHipertension."', '".$mesesMarcapasos."', '".$mesesRadioterapia."',
                                       '".$mesesEpilepsia."', '".$mesesPielSensible."', '".$mesesLupus."', '".$mesesIntervenciones."',
                                       '".$diagInicial."', '".$valInicial."', '".$tratamientosPrevios."',
                                       '".$escalaDolor."', '".$tipoDolor."', '".$examenMuscular."', '".$marchaPostura."', '".$obsMarchaPostura."',
                                       '".$banho."', '".$comida."', '".$trabajo."', '".$vestirse."',
                                       '".$obsAVD."', '".$estadoActual."', now());
                                    ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}
############################################################


############# RECUPERA VALORACION INICIAL #############
if($funcion == 'getValoracionInicial'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();
    $query="SELECT diag_inicial, val_inicial, tratamientos_previos 
            FROM hist_rehabilitacion
            where Exp_folio='".$folio."'
            ";
    $result = $db->query($query);
    $respuesta = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode($respuesta);
    $db = null;    
}
############################################################


############# GUARDA RANGOS DE MOVILIDAD HIST REHAB #############
if($funcion=='guardaRangos'){
    $postdata       = file_get_contents("php://input");
    $datos          = json_decode($postdata);

    print_r($datos);

    $folio              = $datos->folio;
    $region             = $datos->region;
    $flexion            = $datos->flexion;
    $extension          = $datos->extension;
    $lateralizacion     = $datos->lateralizacion;
    $rotInterna         = $datos->rotInterna;
    $rotExterna         = $datos->rotExterna;
    
    $db = conectarMySQL(); 
    
                try{
                    $sql="INSERT into rangos_movilidad(RM_id, Exp_folio, RM_region, RM_flexion, 
                                                       RM_extension, RM_lateralizacion, RM_rotInterna, RM_rotExterna)
                                        VALUES(DEFAULT, '".$folio."', '".$region."','".$flexion."',
                                               '".$extension."', '".$lateralizacion."', '".$rotInterna."', '".$rotExterna."');
                                            ";

                    $result = $db->query($sql);
                    $respuesta ='exito';
                    }catch(Exception $e){
                        return $e->getMessage();
                        $respuesta = array('respuesta' =>'error');       
                      }
                    return $respuesta;
    echo json_encode($respuesta);
    $db = null;  
}
############################################################


############# RECUPERA RANGOS DE MOVILIDAD #############
if($funcion == 'getRangos'){
    $folio=$_GET['folio'];

    $db = conectarMySQL();
    $query="SELECT * FROM rangos_movilidad
            where Exp_folio='".$folio."'
            ";
    $result = $db->query($query);
    $respuesta = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($respuesta);
    $db = null;    
}
############################################################


############# QUITA RANGOS DE MOVILIDAD #############
if($funcion == 'quitarRango'){
    $idRango = $_GET['idRango'];
    print_r($idRango);

    $db = conectarMySQL();
    $query="DELETE FROM rangos_movilidad
            where RM_id='".$idRango."'
            ";
            
    $result = $db->query($query);
    $respuesta ='exito';
    echo json_encode($respuesta);
    $db = null;    
}
############################################################


############# RECUPERA DIAGNOSTICO INICIAL DE HISTORIA CLINICA #############
if($funcion == 'getdiagInicial'){
    $folio = $_GET['folio'];

    $db = conectarMySQL();
    $query="SELECT Con_motivo FROM Consulta
            where Exp_folio='".$folio."'
            ";

    $result = $db->query($query);
    $respuesta = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($respuesta);
    $db = null;    
}
############################################################


############# VERIFICA SI EXSITE HCR #############
if($funcion == 'verificaHCR'){
    $folio = $_GET['folio'];

    $db = conectarMySQL();
    $query="SELECT Exp_folio FROM hist_rehabilitacion
            where Exp_folio='".$folio."'
            limit 1
            ";

    $result = $db->query($query);
    $respuesta = $result->fetch(PDO::FETCH_OBJ);
    echo json_encode($respuesta);
    $db = null;    
}
############################################################
?>
