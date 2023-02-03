<?php

$query= "Select Exp_folio, Exp_nombre, Exp_paterno, Exp_materno, Exp_siniestro, Exp_poliza, Exp_reporte, Exp_fecreg, Usu_registro, Exp_fecreg, USU_registro, Uni_nombre, Uni_propia
			From Expediente inner join Unidad on Expediente.UNI_clave=Unidad.UNI_clave
			where Exp_folio='".$fol."';";

	$rs = mysql_query($query,$conn);
	$row=mysql_fetch_array($rs);

		$compania	= $row["Cia_nombrecorto"];
		$nombre		= $row["Exp_nombre"];
		$paterno	= $row["Exp_paterno"];
		$materno	= $row["Exp_materno"];
		$siniestro	= $row["Exp_siniestro"];
		$poliza		= $row["Exp_poliza"];
		$reporte	= $row["Exp_reporte"];
		$obs		= $row["Exp_obs"];
		$folio		= $row["Exp_folio"];
		$unidad		= $row["Uni_nombre"];
		$fechahora	= $row["Exp_fecreg"];
		$usuario	= $row["Usu_registro"];
                $propia         = $row["Uni_propia"];

                $dir='codigos/'.$fol.'.png';

		$cadena=$compania.$nombre.$paterno.$materno.$siniestro.$poliza.$reporte.$obs.$folio.$unidad.$fechahora.$usuario.'MV';
		$cadena=md5($cadena);

	$fechafac = date ("d-m-Y");
         $hora     = date("g:i a");


         ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         ///////////////////////////////////                       PDF                      /////////////////////////////
         ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$pdf->AddPage();
/////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
///// código de barras creado en el pdf
//////////      fin de creacion de codigo de barras       ////////
 $image_file = '../../imgs/atlasPersonal.JPG';
		$pdf->Image($image_file, 100, 10, 90, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
                $image_file = "../../imgs/logomv.jpg";
		$pdf->Image($image_file, 10, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
                $pdf->Ln(20);
		$pdf->SetFont('helvetica', 'B', 12);
		// Title
                $pdf->Cell(0, 20, 'ENCUESTA DE SERVICIO', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(0, 15, 'Folio Asignado:'.$fol, 0, 1, 'R', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(0, 10,"Fecha:".date('d'.'/'.'m'.'/'.'Y')." "."Hora:".date('g'.':'.'i'.' '.'A'), 0, 1, 'R', 0, '', 0, false, 'M', 'M');
/////////////////////////////////////////////////////////////////
/*$image_file = "../codigos/".$fol.".png";
$pdf->Image($image_file, 90, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(25);*/
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($w, $h, "Paciente: ".utf8_encode($nombre)." ".utf8_encode($paterno)." ".utf8_encode($materno)." ", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->SetFont('helvetica', '', 9, '', true);
$pdf->Ln(5);

$pdf->Cell($w, $h, "¿Cómo calificaría la atención del personal que lo recibe en urgencias?", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "a) Muy satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "b) Satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "¿Cómo califica el tiempo de espera hasta ser atendido por un médico?’", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "a) Muy satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "b) Satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "¿Cómo considera la atención por parte del médico?", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "a) Muy satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "b) Satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "¿Cómo calificaría la información que recibió con respecto a su paciente?", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "a) Muy satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "b) Satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "¿Cómo considera la limpieza, iluminación y comodidad de la clínica u hospital?", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "a) Muy satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "b) Satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "¿Cómo es su percepción general de la atención recibida?", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "a) Muy satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(1);
$pdf->Cell($w, $h, "b) Satisfactoria", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(4);

$pdf->Cell($w, $h, "Nombre del Hospital o Clínica____________________________________________________________________________", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "Nombre del Paciente___________________________________________________________________________________", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "Nombre del Padre o Tutor_______________________________________________________________________________", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(20);
$pdf->Cell($w, $h, "         ______________________________________                                  _____________________________________", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(2);
$pdf->Cell($w, $h, "                               Firma del afectado                                                                         Firma del Padre o Tutor", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(10);
$pdf->Cell($w, $h, "Teléfono casa__________________________________ E mail. ______________________________________", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(3);
$pdf->Cell($w, $h, "Teléfono Cel___________________________________ Fecha: ______________________________________", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(20);
//$pdf->writeHTMLCell($w=0, $h=0, $x='42', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
?>