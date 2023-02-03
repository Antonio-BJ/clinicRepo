<?php //****************************************************Cuestionario Orthofam

//**Obtener datos
	$query= "Select Exp_folio, Exp_nombre, Exp_paterno, Exp_materno, Exp_siniestro, Exp_poliza, Exp_reporte, Exp_fecreg, Usu_registro, Exp_fecreg, USU_registro, Uni_nombre
			From Expediente inner join Unidad on Expediente.UNI_clave=Unidad.UNI_clave 
			where Exp_cancelado=0 and Exp_folio='".$fol."';";
			
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
		
		$dir='codigos/'.$fol.'.png';

		$cadena=$compania.$nombre.$paterno.$materno.$siniestro.$poliza.$reporte.$obs.$folio.$unidad.$fechahora.$usuario.'MV';
		$cadena=md5($cadena);
$nombrearch='cuest1_'.$fol;

	$fechafac = date ("d-m-Y");

//**************************************************************************************************************
//***********************************************************************************PDF
//**************************************************************************************************************
//**************************************************************************************************************	
	
$pdf->addPage();                      		// Start a page. 
//*********************************************************************************************Fecha
$pdf->setFont('Arial', 'B', 9);      		// Set font to arial 10 pt. 
$PosVerDatFe=107;
$PosHorDatFe=510;
$IntDatFe=9;
$pdf->text($PosHorDatFe, $PosVerDatFe + ($IntDatFe*0), $fechafacformateada ); 	// *Fecha de hoy


//*****************************************************************************************inicio
 $margenizq=30;
 
//$pdf->image("../imgs/logos/banorte.jpg", $margenizq, 10, $width = 150, $height = 65);			//Logo cia
$pdf->image("../../imgs/logos/orthofam.jpg", $margenizq + 425, 10, $width = 150, $height = 65);		//Logo Orthofam

$pdf->setFont('Arial', 'B', 14);      		// Set font to arial 9 pt.
$pdf->text(230, 60, "Encuesta de calidad");   					// *Titulo



$pdf->setFont('Arial', 'B', 11);      		// Set font to arial 9 pt.
$pdf->text($margenizq, 110, "Paciente: ".$folio." - ".utf8_encode($nombre)." ".utf8_encode($paterno)." ".utf8_encode($materno)." ");   	
$pdf->setFont('Arial', 'B', 9);      		// Set font to arial 9 pt.						// *Folio
 								// *Reporte

$pdf->text($margenizq, 160, "Estimado Paciente:");   								// *Leyenda 1
$pdf->text($margenizq, 180, utf8_decode("Con el propósito de conocer su atención acerca del servico médico ofrecido, le agradeceremos contestar el siguiente cuestionario."));   								// *Leyenda 1
$pdf->text($margenizq, 200, utf8_decode("Acude a esta unidad médica:"));   								// *Leyenda 3
$pdf->text(335, 200, "en ambulancia (   )"); $pdf->text(425, 200,"por sus propios medios (   )");
$pdf->text($margenizq, 230, utf8_decode("Coloque una 'X' en la columna que mejor refleje el nivel de satisfacción que obtuvo en cada uno de los siguientes aspectos:"));   								// *Leyenda 3
$pdf->text($margenizq + 310, 250, "Excelente");$pdf->text($margenizq +363, 250, "Bueno");$pdf->text($margenizq +410, 250, "Regular");$pdf->text($margenizq +463, 250, "Malo");$pdf->text($margenizq +510, 250, "No Aplica");

$y=270;  //y
$int=25; //intervalo
/*
$r=0;// renglon
$pdf->text($margenizq, $y+($int*$r), "Independiente de esta atencion medica,");
$pdf->text($margenizq, $y+($int*$r + 10), "la asesoria proporcionada por el Ajustador fue:");
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq +373, $y+($int*$r), "(  )");$pdf->text($margenizq +420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");
*/
$r=1;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("El trato del Personal de Recepción fue:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=2;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("El tiempo transcurrido desde su llegada a la recepción"));
$pdf->text($margenizq, $y+($int*$r+10), utf8_decode("y hasta que le atendió el médico tratante fue:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");
$pdf->text(350, $y+($int*$r+10), "5 min");$pdf->text($margenizq + 366, $y+($int*$r+10), "15 min");$pdf->text($margenizq + 412, $y+($int*$r+10), "20 min");$pdf->text($margenizq + 460, $y+($int*$r+10), "30 min");$pdf->text($margenizq + 503, $y+($int*$r+10), "mas de 30 min");

$r=3;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("En cuestión de comodidad, iluminacion, limpieza las instalaciones son:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=4;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("La presentación del médico que le atendió fue:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=5;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("El trato del médico que le atendió, usted lo calificaría como:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=6;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("La información proporcionada de su padecimiento y tratamiento"));
$pdf->text($margenizq, $y+($int*$r)+10, utf8_decode("por el médico tratante fue:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=7;// renglon
$pdf->text($margenizq, $y+($int*$r), "Los servicios de Rayos X fueron:");
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=8;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("Los servicios de Rehabilitación fueron:"));
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=9;// renglon
$pdf->text($margenizq, $y+($int*$r), "En general, el servicio ofrecido por ORTHO FAM fue:");
$pdf->text(355, $y+($int*$r), "(  )");$pdf->text($margenizq + 373, $y+($int*$r), "(  )");$pdf->text($margenizq + 420, $y+($int*$r), "(  )");$pdf->text($margenizq + 468, $y+($int*$r), "(  )");$pdf->text($margenizq + 525, $y+($int*$r), "(  )");

$r=10;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("¿Recomenaría usted nuestro servicio?"));
$pdf->text(355, $y+($int*$r), "(  )Si");$pdf->text($margenizq + 373, $y+($int*$r), "(  )Tal vez");$pdf->text($margenizq + 420, $y+($int*$r), "(  )No");

$r=11;// renglon
$pdf->text($margenizq, $y+($int*$r), utf8_decode("¿Cómo se enteró de ORTHO FAM?"));
$pdf->text(355, $y+($int*$r), "___________________________________________");



$r=12;// Teléfonos y correo
$pdf->text($margenizq + 3, $y+($int*$r)+10, utf8_decode("Agredecemos sus comentarios para mejorar nuestro servicio en atención a usted:"));
$r=12;
$pdf->rect($margenizq, $y+($int*$r)-5, 550, 75, $style = 'S');


$r=16;// Teléfonos y correo
$pdf->text($margenizq, $y+($int*$r), utf8_decode("Teléfono móvil:____________________   Teléfono particular:____________________   Teléfono oficina:____________________"));
$r=17;// Correo
$pdf->text($margenizq, $y+($int*$r), utf8_decode("Correo Electrónico:_______________________________________"));
$r=18;// Correo
$pdf->text($margenizq, $y+($int*$r), utf8_decode("Dirección:___________________________________________________________________________________________________"));
$r=19;// Correo
$pdf->text(335, $y+($int*$r), "Fecha:  ".$fechafac);
$r=19;// Correo
$pdf->text($margenizq, $y+($int*$r), "Firma del paciente:_______________________________________");


	//5 15 20 30 + de 30

?>


