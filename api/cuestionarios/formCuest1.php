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


        
$pdf->AddPage();

/////////////////////////////////////////////////////////////////
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
$pdf->write1DBarcode($fol, 'C39', '82', '35', '',15, 0.26, $style, 'C');
//////////      fin de creacion de codigo de barras       ////////
 $image_file = '../../imgs/logos/chubb.jpg';
		$pdf->Image($image_file, 140, 10, 60, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $image_file = "../../imgs/logomv.jpg";
		$pdf->Image($image_file, 10, 10, 40, '', 'JPG', '', 'T', false, 600, '', false, false, 0, false, false, false);
		// Set font
                $pdf->Ln(22);
		$pdf->SetFont('helvetica', 'B', 12);
		// Title
                $pdf->Cell(0, 25, 'Encuesta de calidad', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(0, 15, 'Folio Asignado:'.$fol, 0, 1, 'R', 0, '', 0, false, 'M', 'M');
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(0, 10,"Fecha:".date('d'.'/'.'m'.'/'.'Y')." "."Hora:".date('g'.':'.'i'.' '.'A'), 0, 1, 'R', 0, '', 0, false, 'M', 'M');
/////////////////////////////////////////////////////////////////





/*$image_file = "../codigos/".$fol.".png";
$pdf->Image($image_file, 90, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(25);
*/
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($w, $h, "Nombre del Lesionado: ".$nombre." ".$paterno." ".$materno." ", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->Ln(2);
$pdf->Cell($w=45, $h, "Tel. Casa:__________________________ Tel. Ofic.:__________________________ Cel:__________________________ ", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(6);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell($w, $h, "No. de siniestro: ".$siniestro, $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->SetFont('dejavusans', '', 8, '', true);
// $pdf->Cell($w=45, $h, "Lugar de Residencia:__________________________________________________ ¿Recibí atención medica previa?   Si________  No_______", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
// $pdf->Ln(6);
// $pdf->Cell($w=45, $h, "Si en la pregunta anterior su respuesta fue Si, mencione la Insitución donde recibió atención medica: ", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
// $pdf->Ln(6);
// $pdf->Cell($w=45, $h, "_________IMSS               _________ISSSTE               _________Cruz Roja              Otros:_____________________________________________", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
// $pdf->Ln(6);
// $pdf->Cell($w=45, $h, "_________Unidad Médica de la Compañía, especifique cual  ____________________________________________________________________.", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
// $pdf->Ln(6);
// $pdf->Cell($w=45, $h, "Ciudad donde recibió atencion medica previa: ________________________________________________________________________________.", $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
/*$pdf->Ln(5);
$pdf->Cell($w=45, $h, "Siniestro: ".$siniestro, $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Cell($w=45, $h, "Póliza: ".$poliza, $border, $ln=0, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Cell($w=45, $h, "Reporte: ".$reporte, $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);*/
/*$pdf->Ln(5);
$pdf->Cell($w, $h, "Estimado Paciente:", $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height);*/
$pdf->Ln(7);
$pdf->Cell($w, $h, "Instrucciones: Con la finalidad de conocer su opinión sobre el Servicio Médico que se le ha otorgado,  por favor seleccione y", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(2);
$pdf->Cell($w, $h, "marque su respuesta.", $border, $ln=1, $align, $fill, $link, $stretch, $ignore_min_height);
$pdf->Ln(5);


$html="
    <table cellspacing=\"2\" cellpadding=\"3\">
           
                      <tr>
           <td colspan=6 bgcolor=\"#E2EFED\"><b>1. La asesoría que proporcionó el ajustador para recibir atención médica fue:</b></td>
          
           </tr>

           <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>

           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>2. En cuestión de comodidad, iluminación, limpieza las instalaciones son:</b></td>
          
           </tr>
           <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>3. El trato del Personal de Recepción fue:</b></td>
           
           </tr>
            <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>4. La presentación del médico que le atendió fue:</b></td>
           
           </tr>
            <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>5. El trato del médico que le atendió. Usted lo calificaría como:</b></td>
           
           </tr>
            <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>6. La información proporcionada de su padecimiento y tratamiento por el médico tratante fue:</b></td>
           
           </tr>
            <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>7. Los servicios de Rayos X fueron:</b></td>
           
           </tr>
           <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>8. La rapidez con que le atendieron a su llegada fue:</b></td>
           
           </tr>
            <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>9. Los servicios de Rehabilitación fueron</b>:</td>
          
           </tr>
           <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>
           <tr>
           <td colspan=\"6\" bgcolor=\"#E2EFED\"><b>10. En general, el servicio ofrecido por el Proveedor fue:</b></td>
           
           </tr>
            <tr>
               <th width=\"10%\"></th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\"> a) EXCELENTE
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                b) BUENO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"15%\">
                c) REGULAR
                </th>
                <th valing=\"middle\"  align=\"center\"  width=\"15%\">
                d) MALO
                </th>
                <th valing=\"middle\" align=\"center\"  width=\"28%\">
                e) NO REQUERIDO
                </th>
           </tr>

      </table>
     ";

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";
$pdf->Ln(2);


$html="
<br><br>
      <table >
            <tr>
                    <td>
                    Comentarios: _____________________________________________________________________________________________________________
                    </td>
            </tr>
            <tr>
                    <td>
                    
                    </td>
            </tr>
            <tr>
                    <td>
                    ___________________________________________________________________________________________________________________________
                    </td>
            </tr>
          
      </table>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";

$html="
<br><br><br><br><br><br>
      <table >
        <tr>
          <td width=\"25%\">

          </td>
          <td width=\"50%\" align=\"center\">
            <hr />
          </td>
          <td width=\"25%\">

          </td>
        </tr>
        <tr>
            <td>

            </td>
            <td align=\"center\">
             Nombre y Firma del Lesionado o Tutor<br>
             (Mujer poner nombre del Soltera)
            </td>
            <td  align=\"right\">
               
            </td>
       </tr>
        <tr>
            <td>

            </td>
            <td align=\"center\">
            
            </td>
            <td  align=\"right\">
               
            </td>
       </tr>

        <tr>
           
            <td  align=\"right\" colspan=\"3\">
                Lugar:____________________________________<br>
                Fecha:____________________________________
            </td>
       </tr>
      
        
      </table>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";



?>
