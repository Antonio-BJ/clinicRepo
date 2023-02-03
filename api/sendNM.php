<?
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


$db = conectarMySQL();
$fol="OPMV001555";
$file=[];
            $file[0]="../../registro/DigitalesSistema/".$fol."/NM_".$fol.".pdf";
            $file[1]="../../registro/DigitalesSistema/".$fol."/Receta_".$fol.".pdf";

            $query0="SELECT Exp_mail FROM Expediente WHERE Exp_folio='$fol'";
            $result0 = $db->query($query0);
            $row0= $result0->fetch();
            $correo=$row0['Exp_mail'];
            $to = $correo;

            //remitente del correo
            $from = 'noReply@medicavial.com.mx';
            $fromName = 'MedicaVial';

            //Asunto del email
            $subject = '- Documentos de su atencion'; 
            //Contenido del Email
                $htmlContent = '<img src="http://www.medicavial.net/mvnuevo/imgs/logomv.jpg"> 
                                <br><br>    
                                <img src="http://www.medicavial.net/mvnuevo/api/codigos/'.$fol.'.png">
                                <br>
                                <p><h3> <b> </b> ('.$fol.')<br><br>
                                En MédicaVial, agradecemos su preferencia. Adjunto encontrará su receta y la nota médica de la atención recibida. </h3><br><br>
                                <h3>Presente este correo y podemos obsequiarle una membresía familiar con vigencia de un año, para que pueda tener acceso a grandes descuentos y promociones. En MédicaVial, somos expertos en huesos y articulaciones y estamos las 24 horas los 365 días del año.</h3></p>
                                <br>';

                //Encabezado para información del remitente
                $headers = "De: $fromName"." <".$from.">". "\r\n" . "CC: annyjonasstylik12@gmail.com";

                //Limite Email
                $semi_rand = md5(time()); 
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

                //Encabezados para archivo adjunto 
                $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

                //límite multiparte
                $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
                "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

                //preparación de archivo
                for($i=0; $i<=1; $i++){
                    if(is_file($file[$i])){
                        $message .= "--{$mime_boundary}\n";
                        $fp =    @fopen($file[$i],"rb");
                        $data =  @fread($fp,filesize($file[$i]));

                        @fclose($fp);
                        $data = chunk_split(base64_encode($data));
                        $message .= "Content-Type: application/octet-stream; name=\"".basename($file[$i])."\"\n" . 
                        "Content-Description: ".basename($files[$i])."\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"".basename($file[$i])."\"; size=".filesize($file[$i]).";\n" . 
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                    }
                }
                $message .= "--{$mime_boundary}--";
                $returnpath = "-f" . $from;

                //Enviar EMail
                $mail = @mail($to, $subject, $message, $headers, $returnpath); 
                $mail?$envio=1:$envio=0;

                echo $envio;

                if ($envio==1){
                    $query1="UPDATE Expediente SET Exp_correoVerificado=1, Exp_correoVeriFecha=now() WHERE Exp_folio='$fol'";
                    $result1 = $db->query($query1);
                }else{          
                    $cabeceras = 'From: Clinicas <clinicas@ami.com>' . "\r\n";
                    $mensaje="Hubo un error al enviar los documentos el folio $fol. Favor de revisarlo";
                    mail("aalonso@medicavial.com.mx", "Documentos no enviados", $mensaje, $cabeceras);
                } 
                ?>