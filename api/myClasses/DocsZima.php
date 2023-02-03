<?php
error_reporting(0);
include  "classes/DocsWebClass.php";

$ftpWeb = new Ftp();
$ftpSoporte = new Ftp();
$ftpZima = new Ftp();
$ftpWeb->conectar('medicavial.net','medica','M@d1$4_01i0');
$ftpSoporte->conectar('172.17.10.10','admin','m3D$k4_100i');
$ftpZima ->conectar('pmzima.net','zima','M@d1$4_01i0');
$db     = $ftpWeb->conectarMySQL();
$dbZima = $ftpWeb->conectarMySQLZima();

$anio   =date("Y"); 
$mes    =1;
$dia    = 30;

$date = date( "Y-m-d" );
$ayer = date( "Y-m-d", strtotime( "-1 day", strtotime( $date ) ) );  


$queryDocs = "SELECT Exp_folio from Expediente where Exp_fecreg between '".$ayer."' AND '".$ayer." 23:59:59' and Cia_clave= 15 and Exp_cancelado<>1 and Uni_clave in (4,5,186)";
$result = $db->query($queryDocs);
$docs = $result->fetchAll();
$folios = '';
$cont = 0;
foreach ($docs as $key => $value) {   
    if($cont == 0){
        $folios ="'".$value['Exp_folio']."'";    
    }else{
        $folios .= ", '".$value['Exp_folio']."'";    
    }  
    $cont++;  
}

    
$queryDocs = "select REG_folio, REG_folioMV, ASE_clave, REG_fechahora from PURegistro where REG_folioMV in (".$folios.")";
$result = $dbZima->query($queryDocs);
$docs = $result->fetchAll();


$meses_ES = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"); 
$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$nombreMes = str_replace($meses_EN, $meses_ES, $mes);




        foreach ($docs as $key => $value) {
            // $folio='';           // echo $value['Exp_folio'].'<br>';
            // $folio = $value['folio'];                     
            $folioZima = $value['REG_folio'];
            $folioMV   = $value['REG_folioMV'];
            $ase       = $value['ASE_clave'];
            $fecreg    = $value['REG_fechahora'];            

            list($fecha,$hora)= explode(" ", $fecreg);
            list($ano,$mes,$dia)= explode("-", $fecha);

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

            $dir="public_html/PU/Archivos2.0/Mapfre/".$ano;
            $ftpZima ->crearCarpeta($dir);
            $dir1="public_html/PU/Archivos2.0/Mapfre/".$ano."/".$mesd;
            $ftpZima ->crearCarpeta($dir1);
            $dir2="public_html/PU/Archivos2.0/Mapfre/".$ano."/".$mesd."/".$folioZima;
            $ftpZima ->crearCarpeta($dir2);
            $rut = $ano."/".$mesd."/".$folioZima;
        

            /************************************************************************************************************************/
            /************************************************************************************************************************/
            /*************************************      busqueda en web                                                             */
            /************************************************************************************************************************/
            /************************************************************************************************************************/   
           
            

            $queryDocs = "SELECT Arc_archivo,Arc_tipo,Arc_clave FROM DocumentosDigitales
                          WHERE REG_folio='".$folioMV."'";
            $result = $db->query($queryDocs);
            $docs = $result->fetchAll();

           

            // print_r($docs);
            
                if(count($docs)>0){
                    $pa=0;
                    $nm=0;
                    $cu=0;
                    $id=0;
                    foreach ($docs as $key1 => $value1) {
                        // echo $value1['Arc_archivo'].'<br>'; 
                        switch ($value1['Arc_tipo']) {
                            case '1':
                                $pa++;
                                break;
                            case '15':
                                $cu++;
                                break;
                            case '16':
                                $id++;
                                break;
                            case '18':
                                $nm++;
                                break;
                            default:                            
                                break;
                        }                                     
                    }

                    if($pa>0){

                            
                            /******************************se pasan todas la validaciones para descargar  archivos ************************/
                            $contPa=0;
                            $contNm=0;
                            $contId=0;
                            $contCu=0;
                            $contOt=0;
                            $contPre='';

                            foreach ($docs as $key1 => $value1) { 
                                $prefijo = '';
                                $tipo    = '';
                                $otro    = '';
                                $infMed  = '';

                                switch ($value1['Arc_tipo']) {
                                    case '1':
                                        $contPa++;
                                        $prefijo='_pa_';
                                        $tipo = 1;
                                        if($contPa==1){
                                            $contPre=''; 
                                        }else{
                                            $contPre=$contPa; 
                                        }
                                        break;
                                    case '15':
                                        $contCu++;
                                        $prefijo='_sp_';
                                        $tipo = 9;
                                        if($contCu==1){
                                            $contPre=''; 
                                        }else{
                                            $contPre=$contCu; 
                                        }
                                        break;
                                    case '16':
                                        $contId++;
                                        $prefijo='_sp_';
                                        $tipo = 9;
                                        if($contId==1){
                                            $contPre=''; 
                                        }else{
                                            $contPre=$contId; 
                                        }
                                        break;
                                    case '18':
                                        $contNm++;
                                        $prefijo='_sp_';
                                        $tipo = 9;
                                        if($contNm==1){
                                            $contPre=''; 
                                        }else{
                                            $contPre=$contNm; 
                                        }
                                        break;
                                    default: 
                                        $contOt++;    
                                        $prefijo='i'; 
                                        if($contOt==1){
                                            $contPre='otro'; 
                                        }else{
                                            $contPre=$contOt; 
                                        }                      
                                        break;
                                }  

                                if($value1['Arc_tipo']==1 || $value1['Arc_tipo']==15 || $value1['Arc_tipo']==16 || $value1['Arc_tipo']==18){
                                    mkdir('soportesWeb');
                                    switch ($value1['Arc_tipo']) {
                                        case '15':
                                            $otro ='S';
                                            break;
                                        case '16':
                                            $otro ='S';
                                            break;
                                        case '18':
                                            $infMed ='S';
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                                                    
                                    
                                    $carpeta = 'pruebasZima/';                                    
                                    if (!file_exists($carpeta)) {
                                        mkdir($carpeta, 0777,true);
                                    }

                                    $archivoNom = explode('.', $value1['Arc_clave']);
                                    $ext = $archivoNom[1];


                                    if($value1['Arc_tipo']==1){
                                        $cont=1;
                                    }else{
                                        $querycont = "SELECT max(Arc_cons)+1 as cons from PUArchivo where REG_folio = '".$folioZima."' and Arc_tipo = '9' ";     
                                       $result = $dbZima->query($querycont);
                                        $rs = $result->fetch();
                                        $cont = $rs['cons'];
                                        if($cont==''|| $cont==NULL){
                                          $cont=1;
                                        }
                                    }
                            
                                    $ftpWeb->descargar('public_html/registro/'.$value1['Arc_archivo'],$carpeta.'/'.$value1['Arc_clave']); 
                                    $nombre = $cont.$prefijo.$folioZima.'.'.$ext;
                                    $destino = $dir2.'/'.$cont.$prefijo.$folioZima.'.'.$ext;

                                    $ftpZima->subirDirectorios($destino,'pruebasZima/'.$value1['Arc_clave']);

                                    $ruta = '../../../../PU/Archivos2.0/Mapfre/'.$rut.'/'.$cont.$prefijo.$folioZima.'.'.$ext;

                                     $mysql = "INSERT INTO PUArchivo (Arc_cons,Arc_clave,REG_folio,Arc_obs,Arc_archivo,Arc_tipo,Arc_desde,USU_login,Arc_fecreg,SOL_clave,SOL_infMedico, SOL_desglose, SOL_recMedica, SOL_intRX,SOL_imgRX,SOL_fotoLes,SOL_formatARH,SOL_otro) 
                                        values ('".$cont."','".$nombre."','".$folioZima."','','".$ruta."',".$tipo.",'MVWEB','algo',now(),'', '".$infMed."', '', '','','','','','".$otro."')"; 
                                    $result = $dbZima->query($mysql);

                                     $directorio = "pruebasZima/"; 
                                    $ftpSoporte->eliminar_directorio($directorio);                                                                                                                                        
                                }

                            /************************************************* descargar archivos  ***************************************/
                            }
                            echo 'el folio '.$folioMV.' se descargo correctamente <br>';
                            try {
                                
                            
                            $mysql = "INSERT INTO RegistroDigitalesFTP ( Exp_folio, RDI_comentario, RDI_fecreg) 
                                        values ('".$folioMV."','el folio se descargo correctamente',now())"; 
                            $result = $db->query($mysql);
                            } catch (Exception $e) {
                             echo $e->getmessage();   
                            }
                            
                    }else{
                            $mensaje='';
                            if($pa==0){
                                $mensaje = $folioMV.' falta pase médico ';
                            }elseif ($cu==0) {
                                $mensaje .= $folioMV.', falta cuestionario';
                            }elseif ($id == 0) {
                                $mensaje .= $folioMV.', falta identificacion';
                            }elseif ($nm == 0) {
                                $mensaje .= $folioMV.', falta nota médica';
                            }


                            echo $mensaje . '<br>';
                            $mysql = "INSERT INTO RegistroDigitalesFTP ( Exp_folio, RDI_comentario, RDI_fecreg) 
                                        values ('".$folioMV."','".$mensaje."',now())"; 
                            $result = $db->query($mysql);


                        } 
                }else{    

                    echo $folioMV.' No Existen Archivos En este folio <br>';
                    $mysql = "INSERT INTO RegistroDigitalesFTP ( Exp_folio, RDI_comentario, RDI_fecreg) 
                                        values ('".$folioMV."','No Existen Archivos En este folio',now())"; 
                    $result = $db->query($mysql);
                }

            /************************************************************************************************************************/
            /************************************************************************************************************************/
            /*************************************      FIN busqueda en web                                                         */
            /************************************************************************************************************************/
            /************************************************************************************************************************/  
            
        }
