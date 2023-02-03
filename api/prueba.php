<?php
require('./classes/generaCDB_resp.php');

$folio='OPMV000432'; 

$codigo = new generaCDB_resp();

$respuesta = $codigo->generaCodigo($folio);



/*
// Including all required classes
require('BCGFont.php');
require('BCGColor.php');
require('BCGDrawing.php');

$folio='OPMV000432'; 

// Including the barcode technology
include('BCGcode39.barcode.php'); 

// Loading Font
$font = new BCGFont('font/Arial.ttf', 10);

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255); 

$code = new BCGcode39();
$code->setScale(1); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($color_black); // Color of bars
$code->setBackgroundColor($color_white); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse($folio); // Text

// Header that says it is an image (remove it if you save the barcode to a file)
//header('Content-Type: image/png');


/* Here is the list of the arguments
1 - Filename (empty : display on screen)
2 - Background color */
/*$archivo='../codigos/'.$folio.'.png';
$drawing = new BCGDrawing($archivo, $color_white);
$drawing->setBarcode($code);
$drawing->draw();

// Draw (or save) the image into PNG format.
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);*/
?>
