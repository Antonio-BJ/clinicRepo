<?php

include  "DocsWebClass.php";

require 'tcpdf.php';

require 'fpdi.php';

require 'config/lang/eng.php';

// error_reporting(0);

$hostname = '{mail.medicavial.com.mx/notls}';
$username = 'egutierrez@medicavial.com.mx';
$password = 'PP1Pd$wTO1w4';



$proc = new Ftp();

$inbox = imap_open($hostname,$username,$password) or die('Ha fallado la conexión: ' . imap_last_error());
$emails   = imap_search($inbox, 'FROM "ghernandez@medicavial.com.mx" SUBJECT " EDX Signature: Mensaje Importante" SINCE "30 April 2020"');

if($emails) {
   
    $salida = '';
     
    foreach($emails as $email_number) {  
        
      $overview = imap_fetch_overview($inbox,$email_number);
      $message = imap_qprint(imap_body($inbox,$email_number));
      

      $arrayDatos= explode('base64',$message);

      $string1 = explode('http://www.edxsolutions.mx/images/assets/edxlogosmall.jpg',$message);

      $string2 = explode('------=',$string1[2]);

      $string2[0];

  


      
      $string =  str_replace('http://www.edxsolutions.mx/images/assets/spacerxit.gif','descarga.png', $string2[0]);

    

      $string3 =  str_replace('C:/xampp1/htdocs/modulos/correoLatino/cache/img_325472601571f31e1bf00674c368d335','',$string);

      // $cadena = substr($string3, 2);  

      // echo "<html><body><div><p>".$cadena;


      $html = "<html><body><div><p><o:p></o:p></p></td><td width=325 style='width:243.75pt;padding:3.0pt 3.0pt 3.0pt 3.0pt'><p class=MsoNormal><b><span style='font-size:13.0pt;font-family:\"Helvetica\",sans-serif;color:#666666'>Recepción de CFD/CFDI</span></b><o:p></o:p></p></td></tr><tr><td colspan=2 style='padding:3.0pt 3.0pt 3.0pt 3.0pt'><p class=MsoNormal><img width=550 height=1 style='width:5.7291in;height:.0104in' id=\"_x0000_i1026\" src=\"descarga.png\"><o:p></o:p></p></td></tr><tr><td colspan=2 style='padding:3.0pt 3.0pt 3.0pt 3.0pt'><p><strong><span style='font-size:10.5pt;font-family:\"Helvetica\",sans-serif;color:#666666'>Estimado Proveedor</span></strong> <br><b><span style='font-size:10.5pt;font-family:\"Helvetica\",sans-serif;color:#2E84CB'>MEDICAVIAL SA DE CV</span></b><o:p></o:p></p><p><span style='font-size:9.0pt;font-family:\"Helvetica\",sans-serif;color:#666666'>Le confirmamos la recepción de un nuevo comprobante fiscal con los siguientes datos:</span> <o:p></o:p></p></td></tr><tr><td colspan=2 style='padding:3.0pt 3.0pt 3.0pt 3.0pt'><table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=\"100%\" style='width:100.0%'><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Estado de Validación Fiscal: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><span class=style11><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif'>Recibidoedx</span></b></span><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'><o:p></o:p></span></b></p></td></tr><tr><td width=\"42%\" style='width:42.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Emitido por:<o:p></o:p></span></b></p></td><td width=\"58%\" style='width:58.0%;padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>MED011012TD4<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Enviado a:<o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>LSE7406056F6<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Fecha: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>2020-07-02 <o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Serie:<o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>C<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Folio: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>570082 <o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>UUID:<o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>1a7bc5c7-1073-4686-a5e0-156aacfe381f<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Monto total: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>2900.00<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Id del Documento: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>163905890<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Descripción: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>Recibidoedx<o:p></o:p></span></b></p></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>Detalle: <o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'></td></tr><tr><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:10.5pt;font-family:\"Arial\",sans-serif;color:#666666'>&nbsp;<o:p></o:p></span></b></p></td><td style='padding:2.25pt 2.25pt 2.25pt 2.25pt'><p class=MsoNormal><b><span style='font-size:9.0pt;font-family:\"Arial\",sans-serif;color:#999999'>&nbsp;<o:p></o:p></span></b></p></td></tr></table></td></tr><tr><td colspan=2 style='border:none;border-top:solid #CCCCCC 1.0pt;padding:3.0pt 3.0pt 3.0pt 3.0pt'><p class=MsoNormal align=center style='text-align:center'><b><span style='font-size:7.5pt;font-family:\"Helvetica\",sans-serif;color:#666666'>Insurgentes Sur #105, piso 12, Col. Juárez Del. Cuauhtémoc, México D.F. </span></b><o:p></o:p></p></td></tr><tr><td colspan=2 style='padding:3.0pt 3.0pt 3.0pt 3.0pt'></td></tr></table></div></td></tr></table></div></td></tr></table><p class=MsoNormal><img width=1 height=1 style='width:.0104in;height:.0104in' id=\"_x0000_i1027\" <o:p></o:p></p></div></body></html>";
      
echo $html;

      
      // $cadena_buscada = 'Nombre completo';
      // $cadena_buscada2 = 'Saludos cordiales';
      // $posicion_coincidencia = strpos($string, $cadena_buscada);
      // $posicion_coincidencia2 = strpos($string, $cadena_buscada2);
      // $rest = substr($string, $posicion_coincidencia);
      //   $arrayResp = explode('|',$rest);

      //   $res = $proc->capturaDatosTeleconsulta($arrayResp);
      //   if($res==1){
      //     echo 'Se inserto un registro nuevo';
      //   }elseif($res==2){
      //     echo 'ya existe un registro con ese nombre';
      //   }else{
      //     echo 'Ocurrió un error en la insersión';
      //   }
    
      // echo '<br><br>';
    }
  }    
  imap_close($inbox);

  class MYPDF extends FPDI {
    public function Header() {}
    public function Footer() {
      $this->SetY(-20);
      $this->SetFont('helvetica', 'I', 8);
    }  
  }
    
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->setLanguageArray($l);
  $pdf->setFontSubsetting(true);
  $pdf->SetFont('dejavusans', '', 8, '', true);
  $pdf->AddPage();

  $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

  $html="";

  $pdf->output("prueba.pdf",'D');

