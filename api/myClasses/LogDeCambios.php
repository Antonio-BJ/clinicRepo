<?php 
/************************************************************************************
*************************************************************************************
*******             LOG DE MODIFICACIONES AL SISTEMA
*******             by:  SAMUEL
*******
*************************************************************************************
*************************************************************************************/
require_once "Modelo.php";

class LogDeCambios extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();         
	}

//////////////////// RECUPERA LOS ULTIMOS CAMBIOS //////////////////////////
	public function getCambios()
    {
        try{
              $query ="SELECT * from LogCambios order by logID desc limit 20";

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }

//////////////////// GUARDA CAMBIO //////////////////////////
	public function nuevoCambio($datos)
    {
        $nombre       = $datos->nombre;
        $descripcion  = $datos->descripcion;
        $usuario      = $datos->usuario;

        try{
              $query ="INSERT into LogCambios(logID, 
                                              logNombre, 
                                              logDescripcion, 
                                              logFecha, 
                                              logUsuario)
                              		values(DEFAULT,
                              					  '".$nombre."',
                              						'".$descripcion."',
                                          now(),
                                          '".$usuario."')";

            print_r($query);
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