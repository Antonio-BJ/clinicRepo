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

////////////////////////////////////////////////////////////////
///// código de barras creado en el pdf
$style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => TRUE,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 4
               );          
$pdf->write1DBarcode($fol, 'C39', '87', '', '', 10, 0.2, $style, 'C');
//////////      fin de creacion de codigo de barras       ////////
/////////////////////////////////////////////////////////////////
 $image_file = '../../imgs/logomv.jpg';
		$pdf->Image($image_file, 160, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $image_file = "../../imgs/logos/aguila.jpg";
		$pdf->Image($image_file, 10, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
                $pdf->Ln(15);
		$pdf->SetFont('helvetica', 'B', 12);
		// Title
                $pdf->Cell(0, 10, 'Encuesta de calidad', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
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
$pdf->Cell($w, $h, "Paciente: ".$folio." - ".utf8_encode($nombre)." ".utf8_encode($paterno)." ".utf8_encode($materno)." ", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Ln(5);
$pdf->Cell($w=45, $h, "Siniestro: ".$siniestro, $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Cell($w=45, $h, "Póliza: ".$poliza, $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Cell($w=45, $h, "Reporte: ".$reporte, $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(5);
$pdf->Cell($w, $h, "Estimado Paciente:", $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(5);
$pdf->Cell($w, $h, "Con el propósito de conocer su opinión acerca del servico médico ofrecido, le agradeceremos contestar el siguiente cuestionario.", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(5);
$pdf->Cell($w=119, $h, "Acude a esta unidad médica:", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Cell($w=27, $h, "en ambulacia (   )", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Cell($w=20, $h, "por sus propios medios (   )", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(5);
$pdf->Cell($w, $h, "Coloque una 'X' en la columna que mejor refleje el nivel de satisfacción que obtuvo en cada uno de los siguientes aspectos:", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(5);

$html="
    <table cellspacing=\"2\" cellpadding=\"3\">
           <tr>
                <th   align=\"center\" width=\"50%\">
                </th>
                <th valing=\"middle\" align=\"center\" bgcolor=\"#cccccc\" width=\"10%\"><b>Excelente</b>
                </th>
                <th valing=\"middle\" align=\"center\" bgcolor=\"#cccccc\" width=\"10%\">
                <b>Bueno</b>
                </th>
                <th valing=\"middle\" align=\"center\" bgcolor=\"#cccccc\" width=\"10%\">
                <b>Regular</b>
                </th>
                <th valing=\"middle\"  align=\"center\" bgcolor=\"#cccccc\" width=\"10%\">
                <b>Malo</b>
                </th>
                <th valing=\"middle\" align=\"center\" bgcolor=\"#cccccc\" width=\"10%\">
                <b>No aplica</b>
                </th>
           </tr>
           <tr>
           <td bgcolor=\"#E2EFED\">¿El trato del Personal de Recepción fue?</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           </tr>
           <tr>
           <td >¿El tiempo transcurrido desde su llegada a la recepción y hasta que le atendio el médico tratante fue?</td>
           <td align=\"center\">5 min(   )</td>
           <td align=\"center\">15 min(   )</td>
           <td align=\"center\">20 min(   )</td>
           <td align=\"center\">30 min(   )</td>
           <td align=\"center\">+ de 30 min(   )</td>
           </tr>
           <tr>
           <td bgcolor=\"#E2EFED\">¿En cuestión de comodidad, iluminación, limpieza las instalaciones son?</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           </tr>
           <tr>
           <td>¿La presentación del médico que le atendió fue?</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           </tr>
           <tr>
           <td bgcolor=\"#E2EFED\">¿El trato del médico que le atendió. Usted lo calificaría como?</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           </tr>
           <tr>
           <td>¿La información proporcionada de su padecimiento y tratamiento por el médico tratante fue?</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           </tr>
           <tr>
           <td bgcolor=\"#E2EFED\">¿Los servicios de Rayos X fueron?</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           </tr>
           <tr>
           <td>¿Los servicios de Rehabilitación fueron?</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           <td align=\"center\">(    )</td>
           </tr>
           <tr>
           <td bgcolor=\"#E2EFED\">¿En general, el servicio ofrecido por MÉDICAVIAL fue?</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           <td align=\"center\" bgcolor=\"#E2EFED\">(    )</td>
           </tr>
           <tr>
           <td>¿Recomendaría usted nuestro servicio?</td>
           <td align=\"center\">Si(   )</td>
           <td align=\"center\">Tal vez(   )</td>
           <td align=\"center\">No(   )</td>
           <td align=\"center\"></td>
           <td align=\"center\"></td>
           </tr>
      </table>
     ";

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";
$pdf->Ln(2);


$html="
      <table border=\"1\" cellspacing=\"2\" cellpadding=\"3\">
            <tr>
                    <th valing=\"middle\"  align=\"center\" bgcolor=\"#cccccc\" >
                    Agredecemos sus comentarios para mejorar nuestro servicio en atención a usted:
                    </th>
            </tr>
            <tr>
                  <td height=\"80px\">
                  </td>
            </tr>
      </table>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";

$html="
   <table border=\"1\" cellspacing=\"2\" cellpadding=\"3\">
            <tr>
                    <th valing=\"middle\" colspan=\"4\" align=\"center\" bgcolor=\"#cccccc\" >
                        Contacto
                    </th>
            </tr>
            <tr>
                   <td colspan=\"2\">
                 <b>Teléfono móvil: </b>
                   </td>
                   <td colspan=\"2\">
                   <b>Teléfono particular: </b>
                   </td>
           </tr>
           <tr>
                   <td colspan=\"2\">
                   <b>Teléfono oficina: </b>
                   </td>
                   <td colspan=\"2\">
                   <b>Correo Electrónico: </b>
                   </td>
            </tr>
            <tr>
                   <td colspan=\"4\" height=\"40px\">
                   <b>Dirección: </b>
                   </td>
            </tr>
            </table>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";


$pdf->Ln(20);
   $html="
          <table border=\"0\"  width=\"400\">
                 <tr>
                      <td width=\"30%\">

                      </td>
                      <td width=\"40%\">
                      <hr />
                     </td>
                     <td width=\"30%\">

                     </td>
                 </tr>
                 <tr>
                      <td width=\"30%\" align=\"center\">

                      </td>
                      <td width=\"40%\" align=\"center\">
                       Firma del Paciente
                      </td>
                      <td width=\"30%\" align=\"center\">

                      </td>
                 </tr>
          </table>
         ";

$pdf->writeHTMLCell($w=0, $h=0, $x='42', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
      

?>
