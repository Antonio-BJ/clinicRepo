<?php 
/************************************************************************************
*************************************************************************************
*******             ENTREGA DE TURNO
*******             by:  SAMUEL
*******
*************************************************************************************
*************************************************************************************/
require_once "Modelo.php";

class EntregaDeTurno extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();         
	}

//////////////////// RECUPERA LOS ULTIMOS CAMBIOS //////////////////////////
	public function getUsuarios($uniClave)
    {
        try{
              $query ="SELECT Usu_nombre, Usu_login, Uni_clave, Usuario.Puesto_clave, Puesto_nombre 
                        from Usuario 
                        inner join puesto_usuarios on Usuario.Puesto_clave=puesto_usuarios.Puesto_clave
                        where (Usuario.Puesto_clave=1 or Usuario.Puesto_clave=2 or Usuario.Puesto_clave=10)
                        and Uni_clave='".$uniClave."'
                        order by Usu_nombre
                        ";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }


//////////////////// RECUPERA LA LISTA DE TURNOS //////////////////////////
  public function getTurnos()
    {
        try{
              $query ="SELECT * from turno_clinica
                       order by id_turno
                       ";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }

//////////////////// GUARDA CAMBIO DE TURNO //////////////////////////
	public function cambioTurno($datos)
    {
        $unidad                 = $datos->unidad;
        $usuEntrega             = $datos->usuEntrega;
        $usuRecibe              = $datos->usuRecibe;
        $turnoEntrega           = $datos->turnoEntrega;
        $turnoRecibe            = $datos->turnoRecibe;
        $siguiente              = $datos->siguiente;
        $compuConectado         = $datos->compuConectado;
        $compuFuncionando       = $datos->compuFuncionando;
        $compuLimpio            = $datos->compuLimpio;
        $impresoraConectado     = $datos->impresoraConectado;
        $impresoraFuncionando   = $datos->impresoraFuncionando;
        $impresoraLimpio        = $datos->impresoraLimpio;
        $scannerConectado       = $datos->scannerConectado;
        $scannerFuncionando     = $datos->scannerFuncionando;
        $scannerLimpio          = $datos->scannerLimpio;
        $copiadoraConectado     = $datos->copiadoraConectado;
        $copiadoraFuncionando   = $datos->copiadoraFuncionando;
        $copiadoraLimpio        = $datos->copiadoraLimpio;
        $impBnConectado         = $datos->impBnConectado;
        $impBnFuncionando       = $datos->impBnFuncionando;
        $impBnLimpio            = $datos->impBnLimpio;
        $telConectado           = $datos->telConectado;
        $telFuncionando         = $datos->telFuncionando;
        $telLimpio              = $datos->telLimpio;
        $tvConectado            = $datos->tvConectado;
        $tvFuncionando          = $datos->tvFuncionando;
        $tvLimpio               = $datos->tvLimpio;
        $aireConectado          = $datos->aireConectado;
        $aireFuncionando        = $datos->aireFuncionando;
        $aireLimpio             = $datos->aireLimpio;
        $botanaConectado        = $datos->botanaConectado;
        $botanaFuncionando      = $datos->botanaFuncionando;
        $botanaLimpio           = $datos->botanaLimpio;
        $obsArea                = $datos->obsArea;
        $controlTV              = $datos->controlTV;
        $controlAire            = $datos->controlAire;
        $controlLlaves          = $datos->controlLlaves;
        $obsControles           = $datos->obsControles;
        $fondoCaja              = $datos->fondoCaja;
        $efectivoCaja           = $datos->efectivoCaja;
        $valesRecibos           = $datos->valesRecibos;
        $obsCaja                = $datos->obsCaja;
        $efectivoPart           = $datos->efectivoPart;
        $tarjetaPart            = $datos->tarjetaPart;
        $totalPart              = $datos->totalPart;
        $foliosPart             = $datos->foliosPart;
        $cierreTerminal         = $datos->cierreTerminal;
        $ingresadosABA          = $datos->ingresadosABA;
        $enviadosABA            = $datos->enviadosABA;
        $ingresadosAtlas        = $datos->ingresadosAtlas;
        $enviadosAtlas          = $datos->enviadosAtlas;
        $totalExpedientes       = $datos->totalExpedientes;
        $expDigitalizados       = $datos->expDigitalizados;
        $expIncompletos         = $datos->expIncompletos;
        $expJustificacion       = $datos->expJustificacion;
        $expProceso             = $datos->expProceso;
        $incidencias            = $datos->incidencias;
        $pendientes             = $datos->pendientes;

        try{
              $query ="INSERT into entrega_turno(id_entrega, 
                                                 Uni_clave, usu_entrega, usu_recibe, 
                                                 fecha_entrega, turno_entrega, turno_recibe, 
                                                 pc_con, pc_fun, pc_lim, 
                                                 imp_con, imp_fun, imp_lim, 
                                                 scan_con, scan_fun, scan_lim, 
                                                 copi_con, copi_fun, copi_lim, 
                                                 impBN_con, impBN_fun, impBN_lim, 
                                                 tel_con, tel_fun, tel_lim, 
                                                 tv_con, tv_fun, tv_lim, 
                                                 aire_con, aire_fun, aire_lim, 
                                                 maq_con, maq_fun, maq_lim, obs_area, 
                                                 ctrl_tv, ctrl_aire, ctrl_llaves, obs_ctrl, 
                                                 fondo_caja, efectivo_caja, vales_caja, obs_caja, 
                                                 part_efectivo, part_tarjeta, part_total, part_folios, part_cierre_terminal, 
                                                 aba_ing, aba_env, atlas_ing, atlas_env, 
                                                 exp_total, exp_digitalizados, exp_incompletos, 
                                                 justificacion, exp_enProceso, incidentes, pendientes)
                              		values(DEFAULT,
                              					  '".$unidad."', '".$usuEntrega."', '".$usuRecibe."',
                                          now(), '".$turnoEntrega."', '".$turnoRecibe."',
                                          '".$compuConectado."', '".$compuFuncionando."', '".$compuLimpio."',
                                          '".$impresoraConectado."', '".$impresoraFuncionando."', '".$impresoraLimpio."',
                                          '".$scannerConectado."', '".$scannerFuncionando."', '".$scannerLimpio."',
                                          '".$copiadoraConectado."', '".$copiadoraFuncionando."', '".$copiadoraLimpio."',
                                          '".$impBnConectado."', '".$impBnFuncionando."', '".$impBnLimpio."',
                                          '".$telConectado."', '".$telFuncionando."', '".$telLimpio."',
                                          '".$tvConectado."', '".$tvFuncionando."', '".$tvLimpio."',
                                          '".$aireConectado."', '".$aireFuncionando."', '".$aireLimpio."',
                                          '".$botanaConectado."', '".$botanaFuncionando."', '".$botanaLimpio."', '".$obsArea."',
                                          '".$controlTV."', '".$controlAire."', '".$controlLlaves."', '".$obsControles."',
                                          '".$fondoCaja."', '".$efectivoCaja."', '".$valesRecibos."', '".$obsCaja."',
                                          '".$efectivoPart."', '".$tarjetaPart."', '".$foliosPart."', '".$foliosPart."', '".$cierreTerminal."',
                                          '".$ingresadosABA."', '".$enviadosABA."', '".$ingresadosAtlas."', '".$enviadosAtlas."',
                                          '".$totalExpedientes."', '".$expDigitalizados."', '".$expIncompletos."',
                                          '".$expJustificacion."', '".$expProceso."', '".$incidencias."', '".$pendientes."'
                                        )";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }

//TERMINA TODO
}
?>