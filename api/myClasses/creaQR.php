<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('phpqrcode/qrlib.php');
$content = "hola";
QRcode::png($content,"img/qr.png",QR_ECLEVEL_L,10,2);
echo "<img src='hola.png'/>";

// include  'phpqrcode/qrlib.php';
// $dato   =   time();
// QRcode::png($dato,"img/qr_".$dato.".png",'L',32,5);
?>