<?php

require_once "Modelo.php";

/**
*  classe para agregar addendums a documentos
*/
class AutZima extends Modelo
{

	function __construct()
	{
		 parent::__construct();
	}

	public function getAutorizacion($aut,$folZima)
    {
    	try{
            if($aut!=''&&$folZima==''){
                $query1=" Aut_clave='".$aut."' ";
            }elseif ($aut==''&&$folZima!='') {
                $query1=" PUAutorizacionesMedicas.REG_folio='".$folZima."' ";
            }else{
                $query1=" PUAutorizacionesMedicas.REG_folio='".$folZima."' and Aut_clave='".$aut."' ";
            }
            $sql = "SELECT Aut_clave, PUAutorizacionesMedicas.REG_folio, Aut_solicita, Aut_dx, Aut_obs, Aut_fecreg, Aut_bloques, Aut_sesiones,  Aut_cancelado,Unidad.UNI_clave, Pro_desc, UNI_nomCorto, CONCAT(REG_nombre,' ',REG_apaterno,' ',REG_amaterno) as nombre FROM PUAutorizacionesMedicas
                    INNER JOIN PUUniProveedor ON PUAutorizacionesMedicas.Pro_clave = PUUniProveedor.Pro_clave
                    INNER JOIN Unidad ON PUUniProveedor.Uni_clave = Unidad.UNI_clave
										INNER JOIN PURegistro ON PUAutorizacionesMedicas.REG_folio = PURegistro.REG_folio
                    WHERE TipAut_clave=4 AND UNI_razonSocial='MEDICAVIAL, S.A. DE C.V.' AND ".$query1;
            $result = $this->_dbZima->query($sql);
            $respuesta['autorizacion'] = $result->fetch();
						$folZima=$respuesta['autorizacion']['REG_folio'];
						$sql="SELECT count(*) AS contador FROM RehabilitacionZima where Exp_folio='".$folZima."'";
						$result = $this->_db->query($sql);
						$respuesta['noRehab'] = $result->fetch();
        }catch(Exception $e){
        	//$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());
        }
         return $respuesta;
         $this->db=null;
    }

		public function setAutorizacion($folZima,$usr,$uni,$datos)
  	{
			try {
				$sql="SELECT MAX(Rehab_cons)+1 as cont FROM RehabilitacionZima WHERE Exp_folio='".$folZima."'";
				$result = $this->_db->query($sql);
				$rp = $result->fetch();
				$contador = $rp['cont'];
				if(!$contador) $contador=1;
				$query = "INSERT INTO RehabilitacionZima(Exp_folio,Rehab_cons,Rehab_obs,Rehab_dolor, Rehab_mejoria, Rehab_tipo,Rehab_duracion, Rehab_citaant, Rehab_fecha, Usu_registro, Uni_clave, Rehab_waddell)
									VALUES('".$folZima."',".$contador.",'".$datos->observa."',".$datos->escala.",".$datos->mejoria.",'".$datos->tipo."',".$datos->duracion.",'".$datos->acudio."',now(),'".$usr."',".$uni.",'".$datos->criterios."')";
				$result = $this->_db->query($query);
				$result = array('respuesta'=>'SI');
			} catch (Exception $e) {
				$result = array('respuesta'=>'NO');
			}
			return $result;
		}

    /********************************************************************************************************************/

}
?>
