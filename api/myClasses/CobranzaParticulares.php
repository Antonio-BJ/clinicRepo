<?php 
/************************************************************************************
*************************************************************************************
*******             COBRANZA PARTICULARES
*******             by:  SAMUEL RAMIREZ
*******
*************************************************************************************
*************************************************************************************/
require_once "Modelo.php";

class CobranzaParticulares extends Modelo
{
	function __construct()
	{
		 parent::__construct();         
	}


//////////////////// RECUPERA LISTADO DE NOTAS DE  REHABILITACIONES //////////////////////////
  public function buscaRecibo($datos)
    {
      $folioRecibo  =  $datos->folioRecibo;
      $folioMV      =  $datos->folioMV;
      $nombre       =  $datos->nombre;      
       try{
        $query = "SELECT Recibo_tipo FROM reciboParticulares WHERE Recibo_cont=".$folioRecibo;
        $result = $this->_db->query($query);        
        $respuesta = $result->fetch();
        $tipoR = $respuesta['Recibo_tipo'];
        if($tipoR==1){       
          if ($folioRecibo) {
              $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,
                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,
                              Expediente.Exp_completo as nombre, metodo, Usu_nombre, Recibo_aplicado
                          from reciboParticulares 
                          inner join Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio
                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                          where Recibo_cont=".$folioRecibo." and Recibo_cancelado<>1
                      ";
          } else if($folioMV) {
              $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp, 
                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,
                              Expediente.Exp_completo as nombre, metodo, Usu_nombre, Recibo_aplicado
                          from reciboParticulares 
                          inner join Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio
                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                          where reciboParticulares.Exp_folio='".$folioMV."' and Recibo_cancelado<>1
                      ";
          } else if($nombre) {
              $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,
                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,
                              Expediente.Exp_completo as nombre, metodo, Usu_nombre, Recibo_aplicado
                          from reciboParticulares 
                          inner join Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio
                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                          where Exp_completo='".$nombre."' and Recibo_cancelado<>1
                      ";
          }
        }else{
          if ($folioRecibo) {
              $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,
                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,
                              concat(sinReg_nombre,' ',sinReg_apPaterno,' ', sinReg_apMaterno) as nombre, metodo, Usu_nombre, Recibo_aplicado
                          from reciboParticulares 
                          inner join ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id
                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                          where Recibo_cont=".$folioRecibo." and Recibo_cancelado<>1
                      ";
          } else if($folioMV) {
              $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp, 
                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,
                              concat(sinReg_nombre,' ',sinReg_apPaterno,' ', sinReg_apMaterno) as nombre, metodo, Usu_nombre, Recibo_aplicado
                          from reciboParticulares 
                          inner join ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id
                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                          where reciboParticulares.Exp_folio='".$folioMV."' and Recibo_cancelado<>1
                      ";
          } else if($nombre) {
              $query="SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,
                              Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,
                              concat(sinReg_nombre,' ',sinReg_apPaterno,' ', sinReg_apMaterno) as nombre, metodo, Usu_nombre, Recibo_aplicado
                          from reciboParticulares 
                          inner join ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id
                          inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                          inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                          where Exp_completo='".$nombre."' and Recibo_cancelado<>1
                      ";
          }
        }          
            $result = $this->_db->query($query);
            //print_r($result);
            $respuesta = $result->fetch(PDO::FETCH_ASSOC);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error'.$e);       
        }    
         return $respuesta;    
    }

    public function listadoCobro($uni){

      $query = "SELECT COB_id,
                            COB_claveint,
                            COB_fecha,
                            COB_saldoinicial,
                            COB_relacionBancaria,
                            COB_saldo,
                            COB_fechaRegistroCobro,
                            Recibo_serie,
                            Recibo_cont,
                            COB_fechaAct,
                            COB_fechaimportacion,
                            Uni_claveint
                    FROM CobroxAplicar WHERE COB_Saldo > 0 and Uni_claveint = $uni

                UNION 

                SELECT COB_id,
                            COB_claveint,
                            COB_fecha,
                            COB_saldoinicial,
                            COB_relacionBancaria,
                            COB_saldo,
                            COB_fechaRegistroCobro,
                            Recibo_serie,
                            Recibo_cont,
                            COB_fechaAct,
                            COB_fechaimportacion,
                            Uni_claveint
                FROM CobroxAplicar WHERE COB_Saldo > 0 and Uni_claveint is null";
        $result = $this->_db->query($query);        
        $respuesta = $result->fetchAll();
        return $respuesta;

    }
    public function detalleRecibo($recibo){

        $query = "SELECT Recibo_Id, Recibo_serie, Recibo_cont, reciboParticulares.Exp_folio, Recibo_fecExp,
                                Recibo_total, Recibo_facturado, Recibo_doc, Recibo_Tipo, Recibo_banco, Recibo_terminacion,Recibo_total-Recibo_aplicado as restanteRec,
                                Expediente.Exp_completo as nombre, metodo, Usu_nombre, Recibo_aplicado,CONCAT(Recibo_serie,'',Recibo_cont) as rec, Uni_nombrecorto as unidad, reciboParticulares.Uni_clave
                            from reciboParticulares 
                            inner join Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio
                            inner join metodoPagoPar on metodoPagoPar.id_metodo=reciboParticulares.Recibo_mpago
                            inner join Usuario on Usuario.Usu_login=reciboParticulares.Usu_login
                            inner join Unidad on reciboParticulares.Uni_clave = Unidad.Uni_clave
                            where CONCAT(Recibo_serie,'',Recibo_cont) ='$recibo'";
          $result = $this->_db->query($query);        
          $respuesta = $result->fetchAll();
          return $respuesta;

      }

    public function buscacobro($datos,$uniclave){

      $fechaIni     = $datos->fechaIni;
      $fechaFin     = $datos->fechaFin;


            $query = "SELECT COB_id,
                            COB_claveint,
                            COB_fecha,
                            COB_saldoinicial,
                            COB_relacionBancaria,
                            COB_saldo,
                            COB_fechaRegistroCobro,
                            Recibo_serie,
                            Recibo_cont,
                            COB_fechaAct,
                            COB_fechaimportacion,
                            Uni_claveint
                    FROM CobroxAplicar WHERE COB_Saldo > 0 and Uni_claveint = $uniclave 
                    and COB_fechaRegistroCobro BETWEEN '$fechaIni' AND '$fechaFin 23:59:59'

                UNION 

                SELECT COB_id,
                            COB_claveint,
                            COB_fecha,
                            COB_saldoinicial,
                            COB_relacionBancaria,
                            COB_saldo,
                            COB_fechaRegistroCobro,
                            Recibo_serie,
                            Recibo_cont,
                            COB_fechaAct,
                            COB_fechaimportacion,
                            Uni_claveint
                FROM CobroxAplicar WHERE COB_Saldo > 0 and Uni_claveint is null and
                COB_fechaRegistroCobro BETWEEN '$fechaIni' AND '$fechaFin 23:59:59'";
        $result = $this->_db->query($query);        
        $respuesta = $result->fetchAll();
        return $respuesta;


    }

//TERMINA TODO
}
?>