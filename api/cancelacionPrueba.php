<?
$fol="OPMV000535";
$nombre="ANNI ALONSO SÁNCHEZ";
$unidad="MV Oficinas";
$motivo="ERROR EN DATOS";
$fecreg="2021-08-20 16:52:33";
$fecha="2021-08-20 16:52:33";
$usuario="Usuario de sistemas";
$producto="Ambulatorio";
$compania="ATLAS";


$contenido='<HTML>
                        <HEAD>
                        </HEAD>
                        <BODY>
                        <br>                
                        <img src="http://www.medicavial.net/mvnuevo/imgs/logomv.jpg"> 
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
            //Encabezado para información del remitente
            //$to="enlaceoperativo@medicavial.com.mx";
            $subject = "Cancelación del folio ".$fol;

            $headers = "From: cancelacion_noReply@medicavial.com.mx <Cancelacion>";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            //$headers .= 'Cc: mvclinicas@medicavial.com.mx';
            //$headers .= 'Cc: '.$emailClinica;
            $headers .= 'Bcc: aalonso@medicavial.com.mx';

            //Enviar EMail
            $mail = mail($to, $subject, $contenido, $headers);

            if($mail==1){
              echo "exito";
            }else{
              echo "error";
            }
            ?>