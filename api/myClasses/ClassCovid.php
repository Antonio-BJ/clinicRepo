<?php 

require_once "Modelo.php";
// clase para el envío de correos
require_once 'nomad_mimemail.inc.php';

require_once '../../asesorias/admin/twilio-php-5.42.2/src/Twilio/autoload.php'; 

use Twilio\Rest\Client;
/**
*  classe para agregar addendums a documentos


*/


class ClassCovid extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}


    public function guardaDatosCovid($datos,$folio)
    {        

        $fechaPrimerSintoma     = $datos->fechaPrimerSintoma;
        $sospechaMedio          = $datos->sospechaMedio;
        $diagnosticoConfirmado  = $datos->diagnosticoConfirmado;
        $tipEstudio             = $datos->tipEstudio;
        $fechaEstudio           = $datos->fechaEstudio;
        $fechaPruebaNegativa    = $datos->fechaPruebaNegativa;
        $tipPueba               = $datos->tipPueba;
        $hospitalizado          = $datos->hospitalizado;
        $dias                   = $datos->dias;
        $asistenciaRespitaria   = $datos->asistenciaRespitaria;
        $hospital               = $datos->hospital; 
        $tratamiento            = $datos->tratamiento;
        $secuelas               = $datos->secuelas;

        if(!empty($fechaPrimerSintoma)){
            $fecha = explode("/", $fechaPrimerSintoma);
            $fechaPrimerSintoma= $fecha[2]."-".$fecha[1]."-".$fecha[0]; 
        }
        
        if(!empty($fechaEstudio)){
            $fecha1 = explode("/", $fechaEstudio);
            $fechaEstudio= $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]; 
        }
        
        if(!empty($fechaPruebaNegativa)){
            $fecha2 = explode("/", $fechaPruebaNegativa);
            $fechaPruebaNegativa= $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]; 
        }
        

        if(!$dias){
            $dias=0;
        }
         
        $sql="INSERT INTO HistoriaCovid(Exp_folio, HCO_fecPrimerSintoma,HCO_sospechaMedioContagio,HCO_diagnosticoConfirmado,HCO_tipoEstudio,HCO_fechaEstudio,HCO_fechaPruebaNegativa,HCO_tipoPrueba,HCO_hospitalizado,HCO_dias,HCO_asistenciaRespiratoria,HCO_hospital,HCO_tratamiento,HCO_secuelas) VALUES('$folio','$fechaPrimerSintoma','$sospechaMedio',$diagnosticoConfirmado,'$tipEstudio','$fechaEstudio','$fechaPruebaNegativa','$tipPueba',$hospitalizado,$dias,$asistenciaRespitaria,'$hospital','$tratamiento','$secuelas')";
        if($this->_db->query($sql)){
            $respuesta = array('respuesta'=>'exito');
        }else{
            $respuesta = array('respuesta'=>'error');
        }
         return $respuesta;  

         $this->db=null;     
    }

    public function getHistoria($folio){
        $sql="SELECT count(*) contador FROM HistoriaCovid WHERE EXP_folio='".$folio."'";
        if($this->_db->query($sql)){
            $result = $this->_db->query($sql);
            $respuesta = $result->fetch();
        }else{
            $respuesta = array('respuesta'=>'error');
        }
        return $respuesta;  

        $this->db=null;     
    }

}
?>