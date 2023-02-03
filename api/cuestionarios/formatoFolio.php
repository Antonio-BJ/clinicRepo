<?php

require "pac_vars.inc";//con
require 'tcpdf.php';
require 'config/lang/eng.php';

////////// Datos del Expediente/////
$fol=$_GET['fol'];
$query= "Select Expediente.Exp_folio, Exp_nombre, Exp_paterno, Exp_materno, Exp_siniestro, Exp_poliza, Exp_reporte, Exp_fecreg, Expediente.Cia_clave, Expediente.Usu_registro, Exp_fecreg, UNI_nombreMV, Uni_propia, Cia_nombrecorto, RIE_clave, Exp_RegCompania, Exp_obs, Pro_clave, DProd_Asegurado, DProd_NoReporte, DProd_Obs, DProd_Deducible, Esc_nombre, cia_recibo, Exp_orden,Exp_bitacora, Exp_modificador, Uni_claveActual, Exp_deducible, Exp_deducible
			From Expediente inner join Unidad on Expediente.UNI_clave=Unidad.UNI_clave inner join Compania on Expediente.Cia_clave=Compania.Cia_clave
                        left join DatosProducto on Expediente.Exp_folio=DatosProducto.Exp_folio
                        left join Escolaridad on Expediente.Esc_clave=Escolaridad.Esc_clave
			where Exp_cancelado=0 and Expediente.Exp_folio='".$fol."';";


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
		$unidad		= $row["UNI_nombreMV"];
		$fechahora	= $row["Exp_fecreg"];
		$usuario	= $row["Usu_registro"];
                $riesgoC        =$row["RIE_clave"];
                $CiaClave       =$row["Cia_clave"];
                $RegCia         =$row["Exp_RegCompania"];
                $propia         =$row["Uni_propia"];
                $producto       =$row["Pro_clave"];
                $prod_asegurado =$row["DProd_Asegurado"];
                $prod_reporte   =$row["DProd_NoReporte"];
                $prod_obs       =$row["DProd_Obs"];
                $prod_deducible =$row["DProd_Deducible"];
                $deducible      =$row['Exp_deducible'];
                $prod_escolaridad =$row["Esc_nombre"];
                $recibo         =$row["cia_recibo"];
                $orden          =$row["Exp_orden"];
                $bitacora       =$row['Exp_bitacora'];
                $modificador    =$row['Exp_modificador'];
                $uniActual      =$row["Uni_claveActual"];
                $deducible      =$row['Exp_deducible'];
                if(!$prod_deducible){
                  $prod_deducible=0;
                }
                if(!$recibo){
                  $recibo='-';
                }

                $cadena=$compania.$nombre.$paterno.$materno.$siniestro.$poliza.$reporte.$obs.$folio.$unidad.$fechahora.$usuario.'MV';
		$cadena=md5($cadena);
if($CiaClave == 19){
      $bitacora ="Bitácora: ".$bitacora;
    }else{
      $bitacora='';
    }

$query="Select RIE_nombre From RiesgoAfectado Where RIE_clave='".$riesgoC."'";
$rs= mysql_query($query,$conn);
$row=mysql_fetch_array($rs);
$riesgo= $row["RIE_nombre"];

$query="Select UNI_nombreMV From Unidad Where Uni_clave=".$uniActual;
$rs= mysql_query($query,$conn);
$row=mysql_fetch_array($rs);
$unidadActual= $row["UNI_nombreMV"];

 if($CiaClave==19){
     $query="Select Fol_ZIMA from RegMVZM where Fol_MedicaVial='".$fol."'";
     $rs=mysql_query($query,$conn);
     $row=mysql_fetch_array($rs);
     $folZima= $row["Fol_ZIMA"];

     $CampoCompania="<b>Folio electrónico: </b>".$RegCia;
     $FolioZima="<b>Folio ZIMA: </b>".$folZima;
     }else if($CiaClave==10){
         $CampoCompania="<b>Folio segme.: </b>".$RegCia;
         $FolioZima="";
     }else{
         $CampoCompania="";
         $FolioZima="";
     }

if($CiaClave==1){ $imagen="../../imgs/logos/aba.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==2){ $imagen="../../imgs/logos/afirme.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==3){ $imagen="../../imgs/logos/aguila.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==4){ $imagen="../../imgs/logos/chartis.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==5){ $imagen="../../imgs/logos/ana.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==6){ $imagen="../../imgs/logos/atlas.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==7){ $imagen="../../imgs/logos/axa.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==8){ $imagen="../../imgs/logos/banorte.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==9){ $imagen="../../imgs/logos/general.jpg"; $tipo="JPG";$sise=40;}
if($CiaClave==10){ $imagen="../../imgs/logos/gnp.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==11){ $imagen="../../imgs/logos/goa.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==12){ $imagen="../../imgs/logos/hdi.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==13){ $imagen="../../imgs/logos/interacciones.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==14){ $imagen="../../imgs/logos/latino.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==15){ $imagen="../../imgs/logos/mapfre.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==16){ $imagen="../../imgs/logos/metro.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==17){ $imagen="../../imgs/logos/multiva.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==18){ $imagen="../../imgs/logos/potosi.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==19){ $imagen="../../imgs/logos/qualitas.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==20){ $imagen="../../imgs/logos/rsa.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==21){ $imagen="../../imgs/logos/zurich.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==22){ $imagen="../../imgs/logos/primero.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==31){ $imagen="../../imgs/logos/HIR.gif"; $tipo="GIF"; $sise=20;}
if($CiaClave==32){ $imagen="../../imgs/logos/SPT.jpg"; $tipo="JPG"; $sise=20;}
if($CiaClave==33){ $imagen="../../imgs/logos/ace.bmp"; $tipo="BMP"; $sise=30;}
if($CiaClave==34){ $imagen="../../imgs/logos/TTRAVOL.jpg"; $tipo="JPG"; $sise=25;}
if($CiaClave==35){ $imagen="../../imgs/logos/multiasistencia.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==36){ $imagen="../../imgs/logos/blanco.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==37){ $imagen="../../imgs/logos/multiafirme.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==38){ $imagen="../../imgs/logos/blanco.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==39){ $imagen="../../imgs/logos/blanco.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==40){ $imagen="../../imgs/logos/blanco.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==41){ $imagen="../../imgs/logos/blanco.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==43){ $imagen="../../imgs/logos/ci.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==44){ $imagen="../../imgs/logos/cortesia.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==45){ $imagen="../../imgs/logos/inbursa.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==46){ $imagen="../../imgs/logos/individual.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==47){ $imagen="../../imgs/logos/thona.jpg"; $tipo="JPG"; $sise=22;}
if($CiaClave==51){ $imagen="../../imgs/logos/individual.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==53){ $imagen="../../imgs/logos/empleado.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==54){ $imagen="../../imgs/logos/convenio.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==55){ $imagen="../../imgs/logos/MetLife.jpg"; $tipo="JPG"; $sise=40;}
if($CiaClave==56){ $imagen="../../imgs/logos/PlanSeguro.jpg"; $tipo="JPG"; $sise=40;}

/****************************validación de identificador de prodcuto ofrecido   EEGR  **///////
		if($producto == 1) $imgProd = '../../imgs/producto/av.jpg';
		else if($producto == 2) $imgProd = '../../imgs/producto/ap.jpg';
		else if($producto == 3) $imgProd = '../../imgs/producto/es.jpg';
		else if($producto == 4) $imgProd = '../../imgs/producto/rh.jpg';
		else if($producto == 5) $imgProd = '../../imgs/producto/rh.jpg';
		else if($producto == 6) $imgProd = '../../imgs/producto/sq.jpg';
		else if($producto == 7) $imgProd = '../../imgs/producto/sn.jpg';
    else if($producto == 8) $imgProd = '../../imgs/producto/sn.jpg';
    else if($producto == 9) $imgProd = '../../imgs/producto/av+.jpg';
    else if($producto == 10) $imgProd = '../../imgs/producto/pa.jpg';
    else if($producto == 12) $imgProd = '../../imgs/producto/av++.jpg';
     else if($producto == 13) $imgProd = '../../imgs/producto/rh.jpg';
		else $imgProd = '../../imgs/producto/av.jpg';
		/******************************* fin de la validacion del producto*//////////////////////
                          ////////////////////////////////////////////////////////////////////////////////////////////
          ////////////          PDF Con tcpdf                                               //////////
          ////////////                                                                     ///////////
          ////////////////////////////////////////////////////////////////////////////////////////////

          // Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		/* Logo
            global $imagen;
            global $fol;
		$image_file = $imagen;
		$this->Image($image_file, 10, 10, 40, '', $tipo, '', 'T', false, 300, '', false, false, 0, false, false, false);
                $image_file = K_PATH_IMAGES.$fol.'.png';
		$this->Image($image_file, 90, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
                 $this->SetFont('helvetica', 'B', 8);
                 $this->Cell(0, 15,"Fecha:".date('d'.'/'.'m'.'/'.'Y')." "."Hora:".date('g'.':'.'i'.' '.'A'), 0, false, 'R', 0, '', 0, false, 'M', 'M');
                 *
                 */
           	}
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-20);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number

	      }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
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
//////////////
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
$pdf->write1DBarcode($fol, 'C39', 95, 18, '',15, 0.26, $style, 'C');
		$image_file = $imagen;
		$pdf->Image($image_file, 15, 18, $sise, '', $tipo, '', 'T', false, 300, '', false, false, 0, false, false, false);
		$image_file = $imgProd;
		$pdf->Image($image_file, 63, 16, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
    $pdf->SetFont('helvetica', 'B', 10); 

    $pdf->Cell(30, 14,$modificador, 0, 40, 'L',false,'', 0,false, 'T', 'B');   
                /*$image_file = K_PATH_IMAGES.$fol.'.png';
		$pdf->Image($image_file, 85, 18, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    */
		// Set font
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(0, 50,"Fecha:".date('d'.'/'.'m'.'/'.'Y')." "."Hora:".date('g'.':'.'i'.' '.'A'), 0, false, 'R', 0, '', 0, false, 'M', 'M');

///////////
$pdf->Ln(25);

if($propia=='S' && $CiaClave==8 && $producto==2){
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell($w, $h, $txt="Hoja de Atención", $border, $ln=1, $align='C', $fill, $link, $stretch, $ignore_min_height);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Ln(10);
}

$html = "
<table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
   <tr>
        <td colspan=\"2\">
            <b>Folio: </b>".utf8_encode($folio)."
        </td>
        <td colspan=\"2\">
            <b>Compañia: </b>".utf8_encode($compania)."
        </td>
        <td colspan=\"2\">
           <b>Unidad Médica: </b>".utf8_encode($unidad)."
        </td>
   </tr>
   <tr>
       <td colspan=\"2\">
          <b>Póliza: </b>".utf8_encode($poliza)."
       </td>
       <td colspan=\"2\">
         <b>Siniestro: </b>".utf8_encode($siniestro)."
       </td>
       <td colspan=\"2\">
       <b>Unidad Actual: </b>".utf8_encode($unidadActual)."
       </td>
   </tr>
   <tr>
   <td colspan=\"2\">
          <b>Riesgo: </b>".utf8_encode($riesgo)."
       </td>
       <td colspan=\"2\">
        <b>Reporte: </b>".  utf8_encode($reporte)."
       </td>
       <td colspan=\"2\">
         ".$FolioZima."
       </td>

   </tr>
   <tr>
   <td colspan=\"2\">
          <b>No. de orden: </b>".$orden."
       </td>
       <td colspan=\"2\">
        <b>".$bitacora."</b>
       </td>
       <td colspan=\"2\">
           ".$CampoCompania."
       </td>

   </tr>
   <tr>
       <td colspan=\"6\">
           <b>Observaciones: </b>".utf8_encode($obs)."
       </td>
   </tr>
   <tr>
       <td colspan=\"6\">
           <b>Lesionado: </b>".utf8_encode($nombre)." ".utf8_encode($paterno)." ".utf8_encode($materno)."
       </td>
   </tr>";
  if($producto == 2){
      if($deducible!=''){
          $html.="<tr>
                       <td colspan=\"6\">
                           <b>Asimilable al deducible: </b>$ ".$deducible."
                       </td>
                  </tr>";
      }
  }
  $html.= "<tr>
       <td colspan=\"2\">
          <b>Usuario: </b>".utf8_encode($usuario)."
       </td>
       <td colspan=\"2\">
           <b>Registro: </b>".$fechahora."
       </td>
       <td colspan=\"2\">
       </td>
   </tr>
   <tr>
       <td colspan=\"4\">
          <b>Verificador: </b>".$cadena."
       </td>
       <td colspan=\"2\">
       </td>
   </tr>
</table>
";
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";
$pdf->Ln(10);

//ORTHOFAM
if($CiaClave!=46){
////AP
if($propia=='S' && $CiaClave==8 && $producto==2){


    $html="
    <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
   <tr>
        <td colspan=\"6\">
            <b>Asegurado: </b>".utf8_encode($prod_asegurado)."
        </td>
   </tr>
   <tr>
       <td colspan=\"4\">
          <b>No. Reporte en Cabina: </b>".utf8_encode($prod_reporte)."
       </td>
       <td colspan=\"2\">
         <b>Escolaridad: </b>".utf8_encode($prod_escolaridad)."
       </td>
   </tr>
   <tr>
   <td colspan=\"6\">
          <b>Deducible: </b> $ ".number_format(utf8_encode($prod_deducible),2)."
       </td>
   </tr>
   <tr>
       <td colspan=\"6\">
           <b>Observaciones: </b>".utf8_encode($prod_obs)."
       </td>
   </tr>
</table>

";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";

$pdf->Ln(35);

$html="
          <table border=\"0\"  width=\"400\">
                 <tr>
                      <td width=\"45%\">
                      <hr />
                      </td>
                      <td width=\"10%\">
                      </td>
                      <td width=\"45%\">
                      <hr />
                     </td>
                 </tr>
                 <tr>
                      <td width=\"45%\" align=\"center\">
                       Nombre y firma de Recepcionista en turno
                      </td>
                      <td width=\"10%\" align=\"center\">
                      </td>
                      <td width=\"45%\" align=\"center\">
                       Nombre y firma de Paciente o Responsable
                      </td>
                 </tr>
          </table>
         ";

$pdf->writeHTMLCell($w=0, $h=0, $x='42', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";

}
else{

$html="
     <h3 align=\"center\">Favor de anexar al expediente la siguiente documentación:</h3>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";

$pdf->Ln(5);

$html="
    <table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
		<tr>
			<td width=\"5%\">
				<input type=\"checkbox\" name=\"a\" />
			</td>
			<th align=\"left\" width=\"27%\">
				Pase Medico Original
			</th>
			<td width=\"68%\">
					<ul>
						<li>Que esta dentro de vigencia.</li>
						<li>Que coincida con la identidad del lesionado.</li>
						<li>Que no presente alteraciones.</li>
						<li>En algunos casos se requiere llenar el informe médico impreso al reverso.</li>
					</ul>
			</td>

		</tr>
		<tr>
			<td width=\"5%\">
				<input type=\"checkbox\" name=\"a\"/>
			</td>
			<th align=\"left\" width=\"27%\">
				Nota Médica (formato Médicavial)
			</th>
			<td width=\"68%\">
					<ul>
						<li>Llena en letra clara.</li>
						<li>Llena en su totalidad.</li>
						<li>Que detalle los suministros otorgados.</li>
						<li>Que detalle los estudios radiológicos.</li>
						<li>Que describa claramente el diagnóstico.</li>
						<li>Que este firmada por el lesionado donde sea pertinente.</li>
						<li>Que este firmada por el medico tratante (incluir cédula de especialidad).</li>
						<li>Deseable:incluir numero de CPTs.</li>
					</ul>
			</td>

		</tr>
		<tr>
			<td width=\"5%\">
				<input type=\"checkbox\" name=\"a\" />
			</td>
			<th align=\"left\" width=\"27%\">
				Cuestionario de atención
			</th>
			<td width=\"68%\">
					<ul>
						<li>Que esto bien identificado (nombre, médico, etc).</li>
						<li>Firmado por el lesionado.</li>
					</ul>
			</td>

		</tr>
		<tr>
			<td width=\"5%\">
				<input type=\"checkbox\" name=\"a\"/>
			</td>
			<th align=\"left\" width=\"27%\">
				Copia de identificación
			</th>
			<td width=\"68%\">
					<ul>
						<li>Que pertenezca al lesionado.</li>
						<li>Que sea oficial.</li>
						<li>Fotocopiada por ambos lados.</li>
						<li>En caso necesario se puede utilizar el formato de identificación. (verificar firmas y huella)</li>
                                                <li>Verificar parentesco</li>
					</ul>
			</td>

		</tr>
		<tr>
			<td colspan=\"4\">
				**Favor de verificar la consistencia de las firmas en todo el expediente<br>
				**Todos los formatos deberan estar bien identificados<br>
				**Favor de imprimir este formato y anexarlo al expediente<br>
			</td>

		</tr>
	</table>

";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";


$pdf->Ln(35);

$html="
          <table border=\"0\"  width=\"400\">
                 <tr>
                      <td width=\"20%\">

                      </td>
                      <td width=\"60%\">
                      <hr />
                     </td>
                     <td width=\"20%\">

                     </td>
                 </tr>
                 <tr>
                      <td width=\"20%\" align=\"center\">

                      </td>
                      <td width=\"60%\" align=\"center\">
                       Nombre y firma de Recepcionista en turno
                      </td>
                      <td width=\"20%\" align=\"center\">

                      </td>
                 </tr>
          </table>
         ";

$pdf->writeHTMLCell($w=0, $h=0, $x='42', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";
}
}//ORTHOFAM
//////AVISO
if($propia =='S' && $CiaClave!=46){


$pdf->AddPage();
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
$pdf->write1DBarcode($fol, 'C39', 147, 5, '',15, 0.26, $style, 'R');
$pdf->SetX(1);
$pdf->SetFont('dejavusans', 'B', 9);
$pdf->Cell($w, $h, $txt="Aviso de Privacidad", $border, $ln=1, $align='C', $fill, $link, $stretch, $ignore_min_height);
$html="";

$pdf->Ln(4);
$pdf->SetFont('dejavusans', '', 7);
$html="
<p align=\"justify\">
El presente aviso de privacidad regula la forma en que manejaremos los datos que nos
proporcione y aquellos que, con fines estadísticos, recabemos cuando acuda a nuestras
clínicas a ser atendido.
<br />
<br />
1. De sus datos
<br />
<br />
MEDICAVIAL se compromete a no vender, intercambiar, transferir, compartir, publicar o
difundir a terceros ajenos a MEDICAVIAL, sin su autorización, los datos que nos
proporcione mediante los formularios de atención o de contacto necesarios para su
tratamiento o atención. La única forma en que nos veríamos obligados a revelar sus datos es mediante la orden de una autoridad competente.
<br />
<br />
2. De su autorización
<br />
<br />
Al atenderse con nosotros, autoriza expresamente a MEDICAVIAL a recabar y tratar sus datos personales (detallados en el punto 5 del presente aviso), incluso aquellos considerados como sensibles, para los fines mencionados
en el punto 4 de este aviso de privacidad. Así mismo, la atención médica que reciba será manifestación expresa de su conformidad y aceptación de nuestro aviso de privacidad y las
modificaciones que eventualmente pueda llegar a tener.
<br />
<br />
En caso de que el paciente sea menor de edad, quien firma el formato de atención o contacto, manifiesta bajo protesta de decir verdad que es el padre, tutor o quien ejerce la custodia legal sobre dicho menor de edad y cuenta con facultades plenas de representación de conformidad con la legislación aplicable.
<br />
<br />
3. Del acceso, rectificación, cancelación y oposición
<br />
<br />
En todo momento, mediante solicitud hecha a MEDICAVIAL, podrá acceder, rectificar o cancelar el registro de los datos que nos ha proporcionado, siempre que la ley lo permita. De la misma forma, podrá oponerse a que sus datos continúen en nuestra base y darse de baja de la misma. Para hacernos llegar sus solicitudes y si tiene alguna duda, por favor envíe un correo electrónico a
privacidad@medicavial.com.mx o envíe una carta a MédicaVial, S.A. de C.V., Alvaro
Obregón 151, Piso 9, Colonia Roma Norte, C.P. 06700, Delegación Cuauhtémoc, en la
Ciudad de México, D.F. dirigido al Departamento de Atención a Usuarios.
<br />
<br />
4. Del uso de su información por parte de MEDICAVIAL S.A DE C.V
<br />
<br />
La información personal que nos proporcione será utilizada únicamente para efectos de poder evaluar su condición física y de salud a fin de poder proveerle de la atención médica y
tratamientos terapéuticos y de rehabilitación adecuados, así como, en su caso, cumplir con las
obligaciones contractuales que tenemos con su aseguradora y contactarle sobre cuestiones
relacionadas con la atención recibida por parte de los médicos y personal de MEDICAVIAL, así como darle a conocer nuestros servicios y ofertas comerciales.
<br />
<br />
5. De la información que recabamos
<br />
<br />
Los datos que necesitamos recabar y tratar para poder proporcionarle un servicio adecuado y
cumplir con nuestras obligaciones contractuales con su aseguradora son los siguientes:
<br />
Nombre, apellido paterno, apellido materno, edad, sexo, ocupación, estado civil, fecha de nacimiento, teléfono particular, teléfono de oficina, teléfono móvil, correo electrónico,
antecedentes clínicos sobre padecimientos como diabetes, presión arterial, gastritis,
problemas cardiacos, osteoporosis o cualquier otro padecimiento preexistente, tratamientos
médicos o terapéuticos a los que esté sometido actualmente, alergias, intervenciones
quirúrgicas previas, enfermedades crónicas o recurrentes que le hayan sido diagnosticadas
por algún médico, hábitos sobre uso de tabaco, ingesta de alcohol, hábitos sobre la práctica de deportes, antecedentes clínicos familiares sobre padecimientos como diabetes, presión arterial, cáncer o problemas cardiacos, situación de vida de sus padres, hermanos e hijos,
antecedentes sobre accidentes, datos sobre las partes del cuerpo que presentan lesiones o dolor por las que fue referido con MEDICAVIAL por parte de su aseguradora, o por lo que acudió con nosotros, así como padecimientos diversos como dolor de cabeza, dolor de cuello, dolor de espalda, dolor de tobillo, dolor de rodilla, adormecimiento de extremidades, problemas de hombro, problemas
de codo, problemas de muñecas, mareos, falta de energía, artritis, insomnio, colitis y cualquier otra información que usted mismo considere relevante para poder atender de manera adecuada el padecimiento por el que ha sido referido con MEDICAVIAL por parte de su aseguradora, o por lo que acudió con nosotros.
<br />
<br />
6. Cambios en la política
<br />
<br />
Es posible que, eventualmente, se realicen cambios a la presente política de privacidad. Le sugerimos visitar frecuentemente la página ubicada en http://www.medicavial.com.mx/
privacidad para estar al tanto de los posibles cambios o modificaciones que pudiera haber.
No se preocupe, con todo y cambios, su información estará segura con MEDICAVIAL.
<br />
<br />
7. Contacto y Encargado
<br />
<br />
Si tiene alguna duda o preocupación en relación a la presente política de privacidad o la forma en que manejamos los datos que nos proporciona o recabamos, por favor envíenos un mensaje al Departamento de Atención a Usuarios que es el encargado de gestionar los datos
recabados en nuestras bases. Para contactarlo, sólo tiene que enviar un correo electrónico a privacidad@medicavial.com.mx. Nuestro compromiso es contestarle en un plazo no mayor a 48 horas, salvo en caso de una excesiva carga de trabajo, caso fortuito o de fuerza mayor.
<br />
<br />
8. Identidad y Domicilio convencional
<br />
<br />
Sus datos son tratados y resguardados por MédicaVial, S.A. de C.V., Alvaro Obregón 151,
Piso 9, Colonia Roma Norte, C.P. 06700, Delegación Cuauhtémoc, en la Ciudad de México, D.F.
<br />
<br />
9. Transferencia de sus datos
<br />
<br />
Al aceptar el presente aviso de privacidad, autoriza a MEDICAVIAL a transferir los datos
que nos proporcione o aquellos que recabemos, a su aseguradora, socios comerciales y otros profesionales de la salud.
</p>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";
$pdf->Ln(13);

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

/*
$year=date('Y');
$mont=date('F');
$_dir = is_dir("Expedientes/".$year);
if($_dir==1){
              $_dirMont= is_dir("Expedientes/".$year."/".$mont);
                if($_dirMont==1)
                    {
                     mkdir("Expedientes/".$year."/".$mont."/".$fol);
                     $pdf->output("Expedientes/".$year."/".$mont."/".$fol."/"."FL_".$fol.".pdf",'F');
                     $pdf->output("FL_".$fol.".pdf",'D');
                    }else{
                         mkdir("Expedientes/".$year."/".$mont);
                         mkdir("Expedientes/".$year."/".$mont."/".$fol);
                         $pdf->output("Expedientes/".$year."/".$mont."/".$fol."/"."FL_".$fol.".pdf",'F');
                         $pdf->output("FL_".$fol.".pdf",'D');
                    }
}else{
       mkdir("Expedientes/".$year);
       mkdir("Expedientes/".$year."/".$mont);
       mkdir("Expedientes/".$year."/".$mont."/".$fol);
       $pdf->output("Expedientes/".$year."/".$mont."/".$fol."/"."FL_".$fol.".pdf",'F');
       $pdf->output("FL_".$fol.".pdf",'D');
     }

 *
 */

////RECIBO
if($propia=='S' && $CiaClave==8 && $producto==2){

$query= "Select Usu_nombre from Usuario where Usu_login = '".$usuario."' ";
$rs = mysql_query($query,$conn);
$row= mysql_fetch_array($rs);
$usuarioNombre = $row['Usu_nombre'];

$monto = number_format(utf8_encode($deducible),2);

$html="";
$pdf->SetFont('dejavusans', '', 10);
$pdf->Ln(10);

$html = '
<h1 style="text-align:center;">RECIBO DE PAGO</h1>
<h2>Recibo: <span style="color:red;" ></span></h2>
<br>
<div style="border-top:2px solid black;padding:50px;">';
$html = $html."
</br>
</br>
<p align=\"justify\">
Recibí la cantidad de: <strong>$ ".$monto."</strong> por concepto de deducible sobre la atención medica de <strong> $nombre $paterno ". utf8_encode($materno)." </strong>
registrada con folio: <strong>".$folio."</strong>.
</p>
<p>
Usuario que emite: <strong>".$usuarioNombre."</strong>
</p>
</div>
";



/*$html = $html . "<table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
   <tr>
        <td colspan=\"1\">
            <b>Recibi de: </b>
        </td>
        <td colspan=\"2\">
           $recibi
        </td>
   </tr>
   <tr>
        <td colspan=\"1\">
           <b>La Cantidad de: </b>
        </td>
        <td colspan=\"2\">
           $ $monto
        </td>
   </tr>
   <tr>
        <td colspan=\"1\">
             <b>Folio: </b>
        </td>
        <td colspan=\"2\">
           $folio
        </td>
    </tr>
    <tr>
        <td colspan=\"1\">
            <b>Usuario: </b>
        </td>
        <td colspan=\"2\">
           $usuarioNombre
        </td>
    </tr>
    <tr>
        <td colspan=\"1\">
           <b>Por Concepto de: </b>
        </td>
        <td colspan=\"2\">
           $concepto
        </td>
    </tr>
</table>";*/

$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

$html="";
$pdf->Ln(13);

$html="
          <table border=\"0\"  width=\"400\">
                 <tr>
                      <td width=\"45%\">
                      <hr />
                      </td>
                      <td width=\"10%\">

                     </td>
                      <td width=\"45%\">
                      <hr />
                     </td>

                 </tr>
                 <tr>
                      <td width=\"45%\" align=\"center\">
                          Nombre y firma de Recepcionista en turno
                      </td>
                       <td width=\"10%\">

                     </td>
                      <td width=\"45%\" align=\"center\">
                          Firma del Paciente
                      </td>
                 </tr>
          </table>
         ";

$pdf->writeHTMLCell($w=0, $h=0, $x='42', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


}
    if($CiaClave==6&&$producto==2){
      include '../cuestionarios/formCuest'.$CiaClave.'personal.php';
    }else{

      include '../cuestionarios/formCuest'.$CiaClave.'.php';
    }
    $pdf->output("FL_".$fol.".pdf",'D');
}
//////AVISO ORTHOFAM
else if($propia =='S' && $CiaClave==46){

$pdf->AddPage();
$pdf->SetX(1);
$pdf->SetFont('dejavusans', 'B', 9);
$pdf->Cell($w, $h, $txt="Aviso de Privacidad", $border, $ln=1, $align='C', $fill, $link, $stretch, $ignore_min_height);
$html="";

$pdf->Ln(4);
$pdf->SetFont('dejavusans', '', 7);
$html="
<p align=\"justify\">
El presente aviso de privacidad regula la forma en que manejaremos los datos que nos
proporcione y aquellos que, con fines estadísticos, recabemos cuando acuda a nuestras
clínicas a ser atendido.
<br />
<br />
1. De sus datos
<br />
<br />
ORTHO FAM se compromete a no vender, intercambiar, transferir, compartir, publicar o
difundir a terceros ajenos a ORTHO FAM y sus socios comerciales, sin su autorización, los datos que nos
proporcione mediante los formularios de atención o de contacto necesarios para su
tratamiento o atención. La única forma en que nos veríamos obligados a revelar sus datos es mediante la orden de una autoridad competente.
<br />
<br />
2. De su autorización
<br />
<br />
Al firmar el formulario de atención o contacto, según sea el caso, autoriza expresamente a ORTHO FAM a recabar y tratar sus datos personales (detallados en el punto 5 del presente aviso), incluso aquellos considerados como sensibles, para los fines mencionados
en el punto 4 de este aviso de privacidad. Así mismo, la atención médica que reciba será manifestación expresa de su conformidad y aceptación de nuestro aviso de privacidad y las
modificaciones que eventualmente pueda llegar a tener.
<br />
<br />
En caso de que el paciente sea menor de edad, quien firma el formato de atención o contacto, manifiesta bajo protesta de decir verdad que es el padre, tutor o quien ejerce la custodia legal sobre dicho menor de edad y cuenta con facultades plenas de representación de conformidad con la legislación aplicable.
<br />
<br />
3. Del acceso, rectificación, cancelación y oposición
<br />
<br />
En todo momento, mediante solicitud hecha a ORTHO FAM, podrá acceder, rectificar o cancelar el registro de los datos que nos ha proporcionado, siempre que la ley lo permita. De la misma forma, podrá oponerse a que sus datos continúen en nuestra base y darse de baja de la misma. Si tiene alguna duda, por favor envíe un correo electrónico a
privacidad@orthofam.com.mx o envíe una carta a ORTHO FAM S.A. de C.V., Alvaro
Obregón 121, Piso 10, Colonia Roma Norte, C.P. 06700, Delegación Cuauhtémoc, en la
Ciudad de México, D.F. dirigido al Departamento de Atención a Usuarios ó Sistemas.
<br />
<br />
4. Del uso de su información por parte de ORTHO FAM S.A DE C.V
<br />
<br />
La información personal que nos proporcione será utilizada únicamente para efectos de poder evaluar su condición física y de salud a fin de poder proveerle de la atención médica y
tratamientos terapéuticos y de rehabilitación adecuados, así como para cumplir con las
obligaciones contractuales que tenemos con su aseguradora y contactarle sobre cuestiones
relacionadas con la atención recibida por parte de los médicos y personal de ORTHO FAM.
<br />
<br />
5. De la información que recabamos
<br />
<br />
Los datos que necesitamos recabar y tratar para poder proporcionarle un servicio adecuado y
cumplir con nuestras obligaciones son los siguientes:
<br />
Nombre, apellido paterno, apellido materno, edad, sexo, ocupación, estado civil, fecha de nacimiento, teléfono particular, teléfono de oficina, teléfono móvil, correo electrónico,
antecedentes clínicos sobre padecimientos como diabetes, presión arterial, gastritis,
problemas cardiacos, osteoporosis o cualquier otro padecimiento preexistente, tratamientos
médicos o terapéuticos a los que esté sometido actualmente, alergias, intervenciones
quirúrgicas previas, enfermedades crónicas o recurrentes que le hayan sido diagnosticadas
por algún médico, hábitos sobre uso de tabaco, ingesta de alcohol, hábitos sobre la práctica de deportes, antecedentes clínicos familiares sobre padecimientos como diabetes, presión arterial, cáncer o problemas cardiacos, situación de vida de sus padres, hermanos e hijos,
antecedentes sobre accidentes, datos sobre las partes del cuerpo que presentan lesiones o dolor por las que fue referido con ORTHO FAM, así como padecimientos diversos como dolor de cabeza, dolor de cuello, dolor de espalda, dolor de tobillo, dolor de rodilla, adormecimiento de extremidades, problemas de hombro, problemas
de codo, problemas de muñecas, mareos, falta de energía, artritis, insomnio, colitis y cualquier otra información que usted mismo o el médico tratante considere relevante para poder atender de manera adecuada el padecimiento por el que ha sido referido con ORTHO FAM.
<br />
<br />
6. Cambios en la política
<br />
<br />
Es posible que, eventualmente, se realicen cambios a la presente política de privacidad. Le sugerimos visitar frecuentemente la página ubicada en http://www.orthofam.com.mx/
privacidad para estar al tanto de los posibles cambios o modificaciones que pudiera haber.
No se preocupe, considerando estos posibles cambios, su información estará segura con ORTHO FAM.
<br />
<br />
7. Contacto y Encargado
<br />
<br />
Si tiene alguna duda o preocupación en relación a la presente política de privacidad o la forma en que manejamos los datos que nos proporciona o recabamos, por favor envíenos un mensaje al Departamento de Atención a Usuarios que es el encargado de gestionar los datos
recabados en nuestras bases. Para contactarlo, sólo tiene que enviar un correo electrónico a privacidad@orthofam.com.mx. Nuestro compromiso es contestarle en un plazo no mayor a 48 horas, salvo en caso de una excesiva carga de trabajo, caso fortuito o de fuerza mayor.
<br />
<br />
8. Identidad y Domicilio convencional
<br />
<br />
Sus datos son tratados y resguardados por ORTHO FAM, S.A. de C.V., Alvaro Obregón 121,
Piso 10, Colonia Roma Norte, C.P. 06700, Delegación Cuauhtémoc, en la Ciudad de México, D.F.
<br />
<br />
9. Transferencia de sus datos
<br />
<br />
Al aceptar el presente aviso de privacidad, autoriza a ORTHO FAM a transferir los datos
que nos proporcione o aquellos que recabemos ó nuestros socios comerciales. En todo momento,
ORTHO FAM se asegurará de que sus socios comerciales conozcan el presente aviso de
privacidad y se obliguen a acatarlo y respetarlo.
</p>
";
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
$html="";
$pdf->Ln(13);

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

/*
$year=date('Y');
$mont=date('F');
$_dir = is_dir("Expedientes/".$year);
if($_dir==1){
              $_dirMont= is_dir("Expedientes/".$year."/".$mont);
                if($_dirMont==1)
                    {
                     mkdir("Expedientes/".$year."/".$mont."/".$fol);
                     $pdf->output("Expedientes/".$year."/".$mont."/".$fol."/"."FL_".$fol.".pdf",'F');
                     $pdf->output("FL_".$fol.".pdf",'D');
                    }else{
                         mkdir("Expedientes/".$year."/".$mont);
                         mkdir("Expedientes/".$year."/".$mont."/".$fol);
                         $pdf->output("Expedientes/".$year."/".$mont."/".$fol."/"."FL_".$fol.".pdf",'F');
                         $pdf->output("FL_".$fol.".pdf",'D');
                    }
}else{
       mkdir("Expedientes/".$year);
       mkdir("Expedientes/".$year."/".$mont);
       mkdir("Expedientes/".$year."/".$mont."/".$fol);
       $pdf->output("Expedientes/".$year."/".$mont."/".$fol."/"."FL_".$fol.".pdf",'F');
       $pdf->output("FL_".$fol.".pdf",'D');
     }

 *
 */

     if($CiaClave==6&&$producto==2){
      include '../cuestionarios/formCuest'.$CiaClave.'personal.php';
    }else{

      include '../cuestionarios/formCuest'.$CiaClave.'.php';
    }
    $pdf->output("FL_".$fol.".pdf",'D');
}
else{
    include '../cuestionarios/formCuest'.$CiaClave.'.php';
    $pdf->output("FL_".$fol.".pdf",'D');
}

?>
