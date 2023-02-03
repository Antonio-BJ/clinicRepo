<?php
require "classes/pac_vars_ana.inc";//con
require 'classes/tcpdf.php';
require 'classes/config/lang/eng.php';
$unidad = $_GET['unidad'];
$fechaini = $_GET['fechaini'];
$fechafin = $_GET['fechafin'];

  $fecha=date("Y-m-d");

      /*$sql="SELECT Exp_folio as folio, CT_fecha as fecha,CT_hora as hora,CT_tiempo as duracion,TC_nombre as tipocita, PRE_nombre as paciente
            FROM Cita a
            INNER JOIN PacientePreregistro b  on a.PRE_clave = b.PRE_clave
            INNER JOIN TipoCita c on a.TC_clave = c.TC_clave
            where CT_fecha='$fecha'";
      */
      $sql="SELECT Exp_folio as folio, CT_fecha as fecha,CT_hora as hora,CT_tiempo as duracion,TC_nombre as tipocita, PRE_nombre as paciente
            FROM Cita a
            LEFT JOIN PacientePreregistro b  on a.PRE_clave = b.PRE_clave
            INNER JOIN TipoCita c on a.TC_clave = c.TC_clave
            where CT_fecha BETWEEN '$fechaini' and '$fechafin' and CT_cancelado = 0 and a.Uni_clave=".$unidad." and
            CT_utilizada = 0 order by CT_hora asc";

      $result = $conn->query($sql);

      $sql1="SELECT Uni_nombre FROM Unidad WHERE Uni_clave = $unidad";
      $result1 = $conn->query($sql1);

         ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         ///////////////////////////////////                       PDF                      /////////////////////////////
         ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

         class MYPDF extends TCPDF {
	//Page header
	public function Header() {
            /*
		// Logo
                $image_file = K_PATH_IMAGES.'mv.jpg';
		$this->Image($image_file, 160, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $image_file = "../imgs/logos/aba.jpg";
		$this->Image($image_file, 10, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
                $this->Ln(15);
		$this->SetFont('helvetica', 'B', 12);
		// Title
                $this->Cell(0, 10, 'Encuesta de calidad', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                //$this->SetFont('helvetica', 'B', 10);
                //$this->Cell(0, 15, 'Folio Asignado:'.$_SESSION['FOLIO'], 0, 1, 'R', 0, '', 0, false, 'M', 'M');
                //$this->SetFont('helvetica', 'B', 8);
                $this->SetFont('helvetica', 'B', 8);
                 $this->Cell(0, 10,"Fecha:".date('d'.'/'.'m'.'/'.'Y')." "."Hora:".date('g'.':'.'i'.' '.'A'), 0, 1, 'R', 0, '', 0, false, 'M', 'M');
             * 
             */
           	}
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		//$this->SetY(-20);
		// Set font
		//$this->SetFont('helvetica', 'I', 8);
		// Page number
		//$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	      }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(10, 20, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//set auto page breaks
// $pdf->SetAutoPageBreak(FALSE, 0);

$pdf->SetAutoPageBreak(true,PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set default font subsetting mode
$pdf->setFontSubsetting(true);
// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 8, '', true);
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

///////////////////////////////////////////////////////////////// 
                $image_file = "../imgs/logos/mv.jpg";
	            	$pdf->Image($image_file, 5, 5, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
                 
                $pdf->Ln(3);
		            $pdf->SetFont('helvetica', 'B', 15);
                $pdf->Cell(0, 10, "Agenda Médica Vial", 0, 1, 'C', 0, '', 0, false, 'M', 'M');

      
  while($row1 = $result1->fetch(PDO::FETCH_ASSOC)){
                $pdf->Ln(4);
                $pdf->SetFont('helvetica', 'B', 15);
                $pdf->Cell(0, 10, $row1['Uni_nombre']  , 0, 1, 'C', 0, '', 0, false, 'M', 'M');

      
  }
/////////////////////////////////////////////////////////////////
// $pdf->Ln(5);

$pdf->Ln(14);
$pdf->SetFont('helvetica', '', 8);

$html="";

$html.="<style type=\"text/css\">
<!--
.Estilo5 {font-size: smaller; font-weight: bold; border-collapse:collapse }
.Estilo7 {
  color: #5C92BC;
  font-weight: bold;
}
.Estilo9 {font-size: smaller; font-weight: bold; color: #4A597A;}
.Estilo10 {color: #4A597A}
.Estilo11 {font-size: small; font-weight: bold; color: #4A597A; }
table {font-family: verdana,arial,sans-serif; font-size:11px; border-collapse: collapse;}
-->
</style>";

$html.="<table width=\"800\" border=\"0\" align=\"center\">";
  $html.="<tr><div style=\"border-width: 1px;\"></div>"; 
   $html.="<td colspan=\"6\"><div align=\"left\" class=\"Estilo7\">Citas Agendadas</div></td>
  </tr>
  <tr bgcolor=\"#E4E7EF\">
    <td  align=\"center\"><span class=\"Estilo9\">Fecha del Dia</span></td>
    <td  align=\"center\"><span class=\"Estilo9\">Hora</span></td>
    <td  align=\"center\"><span class=\"Estilo9\">Tipo Cita</span></td>
    <td  align=\"center\"><span class=\"Estilo9\">Folio</span></td>
    <td  align=\"center\"><span class=\"Estilo9\">Paciente</span></td>
  </tr>";

  while($row = $result->fetch(PDO::FETCH_ASSOC)){

  $html.="<tr>";
  $html.="<td>".$row['fecha']."</td>";
  $html.="<td>".$row['hora']."</td>";
  $html.="<td>".$row['tipocita']."</td>";
  $html.="<td>".$row['folio']."</td>";
  $html.="<td>".$row['paciente']."</td>";
  $html.="</tr>";
}

$html.="</table>";

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='',$html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true );
$html="";

$pdf->output("Agenda.pdf",'D');



?>
