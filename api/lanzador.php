<?php
require "validaUsuario.php";//con
$titulo = "imgs/tit_opciones.gif";
require "enc.php";//encabezado de html

$btnRegistro          = $_SESSION["perRegistro"];
$btnReportes          = $_SESSION["perReportes"];
$btnBuscar	      = $_SESSION["perBuscar"];
$btnBuscarUnidades    = $_SESSION["perBuscarUnidades"];
$btnAdmin             = $_SESSION["perAdmin"];
$btnIncidente         = $_SESSION["perIncidente"];
$btnReporteIncidentes = $_SESSION["perCheckincidencia"];
$btnControlDocumentos = $_SESSION["perControlDocumentos"];
$btnRemesa            = $_SESSION["perRemesa"];
$btnAtenciones        = $_SESSION["perControlAtenciones"];
$btnPapeleria         = $_SESSION["perSolPapeleria"];
$btnSegPapeleria      = $_SESSION["perSegPapeleria"];
$btnClas              = $_SESSION["perClas"];
$btnSeguimiento       = $_SESSION["perSeguimiento"];
$btnBuscarRehab       = $_SESSION["perBuscarRehab"];
$btnCancelar          = $_SESSION["perCancelar"];


	$query = "Select UNI_nombreCorto, UNI_propia From Unidad Where UNI_clave = '".$_SESSION["usuUni"]."';";
	$rs = mysql_query($query,$conn);
	$row=mysql_fetch_row($rs);
	$uninombre=$row[0];
        $unipropia=$row[1];



      //  echo $query. "  ".$uninombre;
	
?>
<table align="left" border="0" width="950p">
<tr>
<td>
<h2>Operaciones disponibles:</h2>
</td>
</tr>
<?php
//Poner Condiciones Para Los Registros de Red y Propias para Aviso de Privacidad
if ($btnRegistro =='S' and $_SESSION["usuClave"]=='lendex'){
 ?>
<tr>
<td>
<?
echo "<a href=\"formatoAviso.php\">Aviso de Privacidad</a>";
?>
</td>
</tr>
<tr>
<td>
<?
echo "<a href=\"registroAviso.php\">Apertura de expediente.(AVISO PRIVACIDAD)</a>";
?>
</td>
</tr>
<?php
}
if ($_SESSION["usuClave"]=='lendex' || $_SESSION["usuClave"]=='lmorales'){
?>
<tr>
<td>
<?
echo "<a href=\"maximos.php\">M&aacute;ximos</a>";
?>
</td>
</tr>
<?}
if ($btnRegistro =='S' && ($_SESSION["usuUni"]!=1&&$_SESSION["usuUni"]!=2&&$_SESSION["usuUni"]!=3&&$_SESSION["usuUni"]!=86&&$_SESSION["usuUni"]!=186&&$_SESSION["usuUni"]!=6&&$_SESSION["usuUni"]!=4&&$_SESSION["usuUni"]!=7)){
 ?>
<tr>
<td>
<?
echo "<a href=\"registro.php\">Apertura de expediente.</a>";
?>
</td>
</tr>
<?}
if ($btnRegistro =='S' && ($_SESSION["usuUni"]==1||$_SESSION["usuUni"]==2&&$_SESSION["usuUni"]==3&&$_SESSION["usuUni"]==86&&$_SESSION["usuUni"]==186&&$_SESSION["usuUni"]==6&&$_SESSION["usuUni"]==4&&$_SESSION["usuUni"]==7)){
 ?>
<tr>
<td>
<?
echo "Apertura de expediente. <a href=\"../mvnuevo\">Redirigir a nuevo registro.</a>";
?>
</td>
</tr>
<?}
if ($btnReportes =='S' ){
?>
<tr>
<td>
<?php
echo "<a href=\"reportes.php\">Reportes.</a>";
?>
</td>
</tr>
<?}
if ($btnBuscar =='S' && ($_SESSION["usuUni"]!=1&&$_SESSION["usuUni"]!=2&&$_SESSION["usuUni"]!=3&&$_SESSION["usuUni"]!=86&&$_SESSION["usuUni"]!=186&&$_SESSION["usuUni"]!=6&&$_SESSION["usuUni"]!=4&&$_SESSION["usuUni"]!=7)){
?>
<tr>
<td>
<?php
echo "<a href=\"buscar.php\">Buscar</a>";
?>
</td>
</tr>
<?}
if ($btnBuscar =='S' && ($_SESSION["usuUni"]==1||$_SESSION["usuUni"]==2||$_SESSION["usuUni"]==3&&$_SESSION["usuUni"]==86&&$_SESSION["usuUni"]==186&&$_SESSION["usuUni"]==6&&$_SESSION["usuUni"]==4&&$_SESSION["usuUni"]==7)){
?>
<tr>
<td>
<?php
echo "Buscar <a href=\"../mvnuevo\">Redirigir a nuevo registro.</a>";
?>
</td>
</tr>
<?}
if ($btnBuscarUnidades =='S' ){
?>
<tr>
<td>
<?php
echo "<a href=\"BuscarUnidades.php\">Buscar Unidades</a>";
?>
</td>
</tr>
<tr>
    <td>
        <a href="../solicitudes/#/acceso/<?echo$_SESSION["usuClave"]?>" target="_blank">Solicitud de Autorizaci&oacute;n</a>
    </td>
</tr>
<?}
if($unipropia=='N'){
?>
<tr>
    <td>
        <?
           //echo"<a href=\"receta.php\">Receta</a>";
        ?>
    </td>
</tr>
<tr>
    <td>
        <?
           //echo"<a href=\"buscaReceta.php\">Buscar Receta</a>";
        ?>
    </td>
</tr>
<?}
if ($btnAdmin =='S' ){
?>
<tr>
<td>
<?php
echo "<a href=\"lanzadorAdmin.php\">Administraci&oacute;n</a>";
?>
</td>
</tr>
<tr>
<td>
<?php
echo "<a href=\"reporte1.php\">Reporte 1</a>";
?>
</td>
</tr>
<?}
if($btnIncidente =='S'){
?>
<tr>
    <td>
        <?php
      echo"<a href=\"RegIncidente.php\">Incidencias</a>";
        ?>
    </td>
</tr>
<?}
if($btnReporteIncidentes =='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"ReporteIncidentes.php\">Reporte Incidencias</a>";
        ?>
    </td>
</tr>
<?}
if($btnRemesa=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"Remesa.php\">Genera Remesa</a>";
        ?>
    </td>
</tr>
<tr>
    <td>
        <?
           echo"<a href=\"muestRemesa.php\">Env&iacute;a Remesa</a>";
        ?>
    </td>
</tr>
<?}
if($btnControlDocumentos=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"ControlRemesa.php\">Control de Remesas</a>";
        ?>
    </td>
</tr>
<?
}
if($btnControlDocumentos=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"ControlExpedientes.php\">Control de Expedientes</a>";
        ?>
    </td>
</tr>
<?}
if($unipropia=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"formatos.php\">Material de Apoyo</a>";
        ?>
    </td>
</tr>
<?}
if($unipropia=='N'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"formatosRed.php\">Material de Apoyo</a>";
        ?>
    </td>
</tr>
<?}
if($btnAtenciones=="S"){
?>
<tr>
    <td>
        <?
           echo"<a href=\"ControlAtenciones.php\">Control de Atenci&oacute;nes</a>";
        ?>
    </td>
</tr>
<?}
if($btnPapeleria=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"pedidos.php\">Solicitud de papeler&iacute;a y formatos</a>";
        ?>
    </td>
</tr>
<?}?>
<?
if($btnSegPapeleria=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"segPedidos.php\">Segumiento papeler&iacute;a</a>";
        ?>
    </td>
</tr>
<?}?>
<?
if($btnClas=='S'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"Clasificacion.php\">Clasificaciones</a>";
        ?>
    </td>
</tr>
<?}?>

<?
if($_SESSION["usuClave"]=='ereyes' || $_SESSION["usuClave"]=='lendex' || $_SESSION["usuClave"]=='algo' || $_SESSION["usuClave"]=='dpaz' || $_SESSION["usuClave"]=='amejia' || $_SESSION["usuClave"]=='jolvera'){
?>
<tr>
    <td>
        <?
           echo"<a href=\"SubeClasificacion.php\">Sube Clasificaci&oacute;n</a>";
        ?>
    </td>
</tr>
<?}?>

<?php
if ($btnSeguimiento=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"tickets.php\">Seguimiento de Folios</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($btnBuscarRehab=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"BuscarRehabilitaciones.php\">Buscar Rehabilitaciones</a>";
        ?>
    </td>
</tr>
<?php
        }
if ($_SESSION["uniPropia"]=='N'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"formatosRehabilitacionRed.php\">Formatos de Rehabilitaci&oacute;n</a>";
        ?>
    </td>
</tr>
<?php
 }
?>

<?php
if ($btnCancelar=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"Cancelar.php\">Editar Folio</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["perRedMedica"]=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"lanzadorRedMedica.php\">Red M&eacute;dica</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["perReporteAxa"]=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"reporteAxa.php\">Reporte Axa</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["perFactExp"]=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"listadofactexp.php\">Listado Facturaci&oacute;n Express</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["perRx"]=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"list_Rx.php\">Solicitudes de Rx</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["perCorreos"]=='S'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"correoadjuntoarchivo.php\">Env&iacute;o de Correos</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["usuClave"]=='algo'||$_SESSION["usuClave"]=='dvazquez'){
?>
<tr>
    <td>
        <?php
           echo"<a href=\"modifNotMedRed.php\">Modifica Nota Medica Red</a>";
        ?>
    </td>
</tr>
<?php
        }
?>

<?php
if ($_SESSION["usuUni"]==1){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1jbgp8-9Qf9o_iyuVeU_ytBxFQsqKCFcB5qBml92BGUQ/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==2){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1qNdGFM8x63QkRSXn8f6WknebeywVO2azBNVbETIcYUM/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==3){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1IfKWglvwvJK8idysar2wmwh65RS3tuQqa5dZZXumjOo/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==4){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/16SqLa1_2MgMXtnP-BQj6N8yVvgQsAnk2-CeDPf8qG7E/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==5){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1BbEeunNcjhHwJEnCtrXfawk5Ivs07fJNpoLOHp3PHCM/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==6){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1NJAQbc_kXf_FTD3czXLRjNF28BAAhOz8WkD3kV4X3wo/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==7){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1VbCL2J46WqnuMdgG4Lki3FUOH-GFIHHnyKJoWtl-iJo/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
if ($_SESSION["usuUni"]==86){
    echo "<tr><td><a href=\"https://docs.google.com/forms/d/1yWI5QZjK6Wi_5PZOlAuMRQZfiDgXBpG7OqP9gHWUq3A/viewform\" target=\"_new\">Encuesta</a></tr></td>";
}
?>

</table>

</body>
</html>
