<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class Cancelacion extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function getFolioNombre($datos)
    {                

   
  
    $nombre     = $datos->nombre;
    $folio      = $datos->folio;
    $poliza     = $datos->poliza;
    $siniestro  = $datos->siniestro;
    $reporte    = $datos->reporte;
    $cveUnidad  = $_GET['cveUnidad'];

    $queryPoliza    ='';
    $querySiniestro ='';
    $queryReporte   ='';

    if($poliza){
        $queryPoliza = " and Exp_poliza like '%".$poliza."%'";
    }
    if($siniestro){
        $querySiniestro = " and Exp_siniestro like '%".$siniestro."%'";
    }
    if($reporte){
        $queryReporte   = " and Exp_reporte like '%".$reporte."%'";
    }


  

    if (empty($_GET['cveUnidad']) || $_GET['cveUnidad'] == 'null') {

        if($nombre && ($folio==''||$folio==null)){
            $query="Select Exp_folio,Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Exp_completo like '%".$nombre."%'".$queryPoliza.$querySiniestro.$queryReporte;
        }
        elseif ($nombre && $folio) {
            $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where  Exp_folio like '%".$folio."%' and Exp_completo like '%".$nombre."%'".$queryPoliza.$querySiniestro.$queryReporte;
        }
        elseif ($folio &&($nombre==''||$nombre==null)) {
           $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Exp_folio like '%".$folio."%'".$queryPoliza.$querySiniestro.$queryReporte;
        }elseif($poliza!='' || $reporte!='' || $siniestro!=''){
            $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where 1=1 ".$queryPoliza.$querySiniestro.$queryReporte;

        }

       
    }else{

        if($nombre && ($folio==''||$folio==null)){
            $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad." and Exp_completo like '%".$nombre."%'".$queryPoliza.$querySiniestro.$queryReporte;
        }
        elseif ($nombre && $folio) {
            $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad." and  Exp_folio like '%".$folio."%' and Exp_completo like '%".$nombre."%'".$queryPoliza.$querySiniestro.$queryReporte;
        }
        elseif ($folio &&($nombre==''||$nombre==null)) {
           $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad." and Exp_folio like '%".$folio."%'".$queryPoliza.$querySiniestro.$queryReporte;
        }elseif($poliza!='' || $reporte!='' || $siniestro!=''){
            $query="Select Exp_folio, Exp_poliza, Exp_siniestro, Exp_reporte,Exp_RegCompania, REG_folioZima, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado, Pro_img, IF(Exp_cancelado=1,Usu_nombre,'') Usu_nombre From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
                inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                inner join Compania on Expediente.Cia_clave=Compania.Cia_clave Where Expediente.Uni_claveActual=".$cveUnidad.$queryPoliza.$querySiniestro.$queryReporte;

        }
        
        
    }

    $result = $this->_db->query($query);
    $datosFolio = $result->fetchAll(PDO::FETCH_OBJ);
    if(empty($datosFolio)){
        $datosFolio= array('respuesta' =>'error');
    }
    $db = null;
    return $datosFolio;   
    }

    public function setCancelacion($datos,$usr)
    {  
        $fol=       $datos->folio;
        $motivo =   $datos->motivo;
        $motivoCat =$datos->motivoCat;
        $folioSus = $datos->folioSus;
        $obs    =   $datos->Obs;
        $query="update Expediente SET Exp_cancelado=1, Usu_cancelado='".$usr."', Exp_fcancelado = now(), Exp_mcancelado='".$motivo."', Exp_duplicado='".$folioSus."', Exp_motCancel=".$motivoCat." where Exp_folio='".$fol."'";
        if($this->_db->query($query)){
            $query1= "INSERT INTO HistorialCancelacion(EXP_folio, USU_login, HCA_estatus, HCA_motivo, HCA_fecha, Exp_folioZima, HCA_folioSusutituto) VALUES('".$fol."','".$usr."','Cancelado','".$motivo."',now(),'',$folioSus)";
            $this->_db->query($query1);
            return 'exito';
        }   
        else {
            return "error";
        }
        $db = null;
    }

    
    public function setActivacion($datos,$usr)
    {  
        $fol=       $datos->folio;
        $motivo =   $datos->motivo;
        $motivoCat =$datos->motivoCat;
        $folioSus = $datos->folioSus;
        $obs    =   $datos->Obs;
        $query="update Expediente SET Exp_cancelado=0, Usu_cancelado='".$usr."', Exp_fcancelado = now(), Exp_mcancelado='".$motivo."' where Exp_folio='".$fol."'";

        $result = $this->_db->query($query);
        if($this->_db->query($query)){
            $query1= "INSERT INTO HistorialCancelacion(EXP_folio, USU_login, HCA_estatus, HCA_motivo, HCA_fecha, Exp_folioZima, HCA_folioSusutituto) VALUES('".$fol."','".$usr."','Activo','".$motivo."',now(),'','')";
            $this->_db->query($query1);
            return 'exito';
        }   
        else {
            return "error";
        }
        $db = null;
    }
}
?>