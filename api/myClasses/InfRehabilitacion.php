<?php 
/************************************************************************************
*************************************************************************************
*******             INFORME DE REHABILITACION
*******             by:  SAMUEL
*******
*************************************************************************************
*************************************************************************************/
require_once "Modelo.php";

class InfRehabilitacion extends Modelo{
	
	function __construct(){
		parent::__construct();         
	}

  //////////////////// BUSCA NOMBRE DE PACIENTE //////////////////////////
	public function buscaPaciente($folio){
    try{
      $query ="SELECT Exp_completo from Expediente where Exp_folio='".$folio."'";
      $result = $this->_db->query($query);
      $respuesta = $result->fetch(PDO::FETCH_OBJ); #para un solo array
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');       
    }    
    
    return $respuesta;    
  }

  //////////////////// GUARDA INFORME //////////////////////////
	public function guardaInfRehab($datos){
    $diagnosticoInicial   = $datos->diagnosticoInicial;
  	$valInicial          = $datos->valInicial;
  	$valFinal            = $datos->valFinal;
  	$tratamientoRehab    = $datos->tratamientoRehab;
  	$observaciones       = $datos->observaciones;
  	$folio 					     = $datos->folio;
  	$usu_login           = $datos->usu_login;
  	$uni_clave           = $datos->uni_clave;
    $etapaInforme        = $datos->etapaInforme;
    $medico              = $datos->medico;
    $emailMedico         = $datos->emailMedico;
    $tratamientoPrevio   = $datos->tratamientoPrevio;
    $areasTrabajadas     = $datos->areasTrabajadas;
    $sesionesRequiere    = $datos->sesionesRequiere;
    $sesionesTomadas     = $datos->sesionesTomadas;
    $sesionesAdicionales = $datos->sesionesAdicionales;

    try{
      $query ="INSERT into InformeRehabilitacion(infRehab_id, 
                                               Exp_folio, 
                                               infRehab_DiagnosticoInicial, 
                                               infRehab_valoracionInicial, 
                                               infRehab_valoracionFinal, 
                                               infRehab_tratamientoRehab, 
                                               infRehab_obs, 
                                               medico,
                                               mailMedico,
                                               Usu_login, 
                                               infRehab_estatusForm, 
                                               infRehab_fecha,
                                               tratamientoPrevio,
                                               areasTrabajadas,
                                               sesionesRequeridas,
                                               sesionesTomadas,
                                               sesionesAdicionales)
                                          values(DEFAULT,
                                              '".$folio."',
                                              '".$diagnosticoInicial."',
                                              '".$valInicial."',
                                              '".$valFinal."',
                                              '".$tratamientoRehab."',
                                              '".$observaciones."',
                                              '".$medico."',
                                              '".$emailMedico."',
                                              '".$usu_login."',
                                              '".$etapaInforme."',
                                              now(),
                                              '".$tratamientoPrevio."',
                                              '".$areasTrabajadas."',
                                              '".$sesionesRequiere."',
                                              '".$sesionesTomadas."',
                                              '".$sesionesAdicionales."'
                                              )";

      #print_r($query);
      $result = $this->_db->query($query);
      
      $respuesta ='exito';
      #print_r($respuesta);
    }catch(Exception $e){
      $respuesta = array('respuesta' =>'error');       
    }    
      return $respuesta;    
  }

  //////////////////// BUSCA INFORME INCONCLUSO //////////////////////////
  public function buscaInfRehab($folio,$username){
    try{
      $query ="SELECT * FROM InformeRehabilitacion WHERE Exp_folio='".$folio."' AND infRehab_estatusForm=0 ORDER BY infRehab_id desc LIMIT 1";
                    //AND Usu_login='".$username."'
      $result = $this->_db->query($query);
      $respuesta = $result->fetch(PDO::FETCH_OBJ); #para un solo array
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');       
    }

    return $respuesta;    
  }

  //////////////////// TERMINA INFORME INCONCLUSO //////////////////////////
  public function terminaInforme($datos){
    $idInforme           = $datos->idInforme;
    $diagnosticoInicial  = $datos->diagnosticoInicial;
    $valInicial          = $datos->valInicial;
    $valFinal            = $datos->valFinal;
    $tratamientoRehab    = $datos->tratamientoRehab;
    $observaciones       = $datos->observaciones;
    $folio               = $datos->folio;
    $usu_login           = $datos->usu_login;
    $uni_clave           = $datos->uni_clave;
    $etapaInforme        = $datos->etapaInforme;
    $tratamientoPrevio   = $datos->tratamientoPrevio;
    $areasTrabajadas     = $datos->areasTrabajadas;
    $sesionesRequiere    = $datos->sesionesRequiere;
    $sesionesTomadas     = $datos->sesionesTomadas;
    $sesionesAdicionales = $datos->sesionesAdicionales;
    $medico              = $datos->medico;
    $emailMedico         = $datos->emailMedico;

    try{
      $query ="UPDATE InformeRehabilitacion
                    SET infRehab_DiagnosticoInicial = '".$diagnosticoInicial."',
                        infRehab_valoracionInicial  = '".$valInicial."',
                        infRehab_valoracionFinal    = '".$valFinal."',
                        infRehab_tratamientoRehab   = '".$tratamientoRehab."',
                        infRehab_obs                = '".$observaciones."',
                        infRehab_estatusForm        = '".$etapaInforme."',
                        tratamientoPrevio           = '".$tratamientoPrevio."',
                        areasTrabajadas             = '".$areasTrabajadas."',
                        sesionesRequeridas          = '".$sesionesRequiere."',
                        sesionesTomadas             = '".$sesionesTomadas."',
                        sesionesAdicionales         = '".$sesionesAdicionales."',
                        medico                      = '".$medico."',
                        mailMedico                  = '".$emailMedico."'

                    WHERE Exp_folio   ='".$folio."'
                      AND infRehab_id ='".$idInforme."'
                      AND infRehab_estatusForm = 0
                        ";
                                //AND Usu_login   ='".$usu_login."'
      $result = $this->_db->query($query);
      #print_r($query);
      $respuesta ='exito';
      //print_r($respuesta);
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');       
    }

    return $respuesta;    
  }

  /////////////////// RECUPERA LISTADO DE NOTAS DE  REHABILITACIONES //////////////////////////
  public function getListaNotasRehab($folio){
    try{
      //$query ="SELECT * from InformeRehabilitacion where Exp_folio='".$folio."'";
      $query="SELECT infRehab_id, Exp_folio, InformeRehabilitacion.Usu_login, infRehab_fecha, medico, Usu_nombre from InformeRehabilitacion inner join Usuario on InformeRehabilitacion.Usu_login=Usuario.Usu_login where Exp_folio='".$folio."'";
      $result = $this->_db->query($query);
      $respuesta = $result->fetchAll(PDO::FETCH_ASSOC); #para un solo array
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');       
    }    
    
    return $respuesta;    
  }

  public function medicoOrden($folio){
    try{
      $query="SELECT CONCAT(USU_nombre,' ',USU_aPaterno,' ', USU_aMaterno) Medico, USU_email, PAS_catidadRehab from pases a inner join usuarios b on a.USU_id=b.USU_id where Exp_folio='".$folio."'";
      $result = $this->_dbExternos->query($query);
      $respuesta = $result->fetchAll(PDO::FETCH_ASSOC); #para un solo array
    }catch(Exception $e){
        $respuesta = array('respuesta' =>'error');       
    }    
    
    return $respuesta;    
  }
  //TERMINA TODO
}
?>