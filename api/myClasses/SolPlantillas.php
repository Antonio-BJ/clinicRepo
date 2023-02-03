<?php 
/************************************************************************************
*************************************************************************************
*******             SOLICITUD DE PLANTILLAS
*******             by:  SAMUEL
*******             Octubre 2016
*************************************************************************************
*************************************************************************************/
require_once "Modelo.php";

class SolPlantillas extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();         
	}


//////////////////// GUARDA CAMBIO //////////////////////////
	public function enviarSolicitud($datos)
    {
        $folio              = $datos->folio;
        $medico             = $datos->medico;
        $unidad             = $datos->unidad;
        $material           = $datos->material;
        $medida             = $datos->medida;
        $especificaciones   = $datos->especificaciones;
        $fechaPedido        = $datos->fechaPedido;
        $fechaEntrega       = $datos->fechaEntrega;

        try{
              $query ="INSERT into solicitud_plantillas(id_solicitud, Exp_folio, Usu_login, 
                                                        Uni_clave, material_plantillas, medida_plantillas, 
                                                        obs_plantillas, fecha_pedido, fecha_estimada,
                                                        estatus_solicitud)
                              		values(DEFAULT, '".$folio."', '".$medico."',
                              					 '".$unidad."', '".$material."', '".$medida."',
                              					 '".$especificaciones."', now(), '".$fechaEntrega." 00:00:00',
                                                 1)
                                        ";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }


//////////////////// RRECUPERA LAS ULTIMAS SOLICITUDES //////////////////////////
  public function getSolicitudes($unidad)
    {
        try{
              $query ="SELECT * from solicitud_plantillas
                        ";
                    if ($unidad==8) {
                        $query.=" ";
                    } else {
                        $query.=" where Uni_clave='".$unidad."'";
                    };
                
                    $query.=" order by id_solicitud desc";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }



//////////////////// MARCA PAGO DE PRODUCTO //////////////////////////
  public function registraPago($idSolicitud, $usrEntrega)
    {
        //$folio              = $datos->folio;

        try{
              $query ="UPDATE solicitud_plantillas SET estatus_solicitud=2
                        WHERE id_solicitud='".$idSolicitud."'
                                        ";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }


//////////////////// REGISTRA SOLICITUD A PROVEEDOR //////////////////////////
  public function solicitudProveedor($idSolicitud, $usrEntrega)
    {
        //$folio              = $datos->folio;

        try{
              $query ="UPDATE solicitud_plantillas SET estatus_solicitud=3
                        WHERE id_solicitud='".$idSolicitud."'
                                        ";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }


//////////////////// REGISTRA SOLICITUD A PROVEEDOR //////////////////////////
  public function enMVoficinas($idSolicitud, $usrEntrega)
    {
        //$folio              = $datos->folio;

        try{
              $query ="UPDATE solicitud_plantillas SET estatus_solicitud=4
                        WHERE id_solicitud='".$idSolicitud."'
                                        ";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }


//////////////////// REGISTRA PRODUCTO EN CLÍNICA //////////////////////////
  public function caminoClinica($idSolicitud, $usrEntrega)
    {
        //$folio              = $datos->folio;

        try{
              $query ="UPDATE solicitud_plantillas SET estatus_solicitud=5
                        WHERE id_solicitud='".$idSolicitud."'
                                        ";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }


//////////////////// REGISTRA SOLICITUD A PROVEEDOR //////////////////////////
  public function enClinica($idSolicitud, $usrEntrega)
    {
        //$folio              = $datos->folio;

        try{
              $query ="UPDATE solicitud_plantillas SET estatus_solicitud=6
                        WHERE id_solicitud='".$idSolicitud."'
                                        ";

            //print_r($query);
            $result = $this->_db->query($query);
            
            $respuesta ='exito';
            #print_r($respuesta);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }



//////////////////// MARCA ENTREGA DE PRODUCTO //////////////////////////
  public function marcaEntrega($idSolicitud, $usrEntrega)
    {
        //$folio              = $datos->folio;

        try{
              $query ="UPDATE solicitud_plantillas SET entrega=1, usr_entrega='".$usrEntrega."', fecha_entrega=now(), 
                                                       estatus_solicitud=7
                        WHERE id_solicitud='".$idSolicitud."'
                                        ";

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