<?php 

require_once "Modelo.php";
require "nusoap-0.9.5/lib/nusoap.php";
/**
*  clase para el control de suministros con el web service del sistema symio
*/
class Suministros extends Modelo
{	
	function __construct()
	{
		 parent::__construct();
	}
    /************************************************************************************************************************************/
/************************       función para guardar suministros alternativos cuando no se cargan los sumistros symio  **************/
/************************************************************************************************************************************/
    public function setSumAlter($folio, $datos)
    {
        $error = '';
        foreach ($datos as $key => $value) {                
                try{
                    $query="Insert into NotaSuministroAlternativa(NsumAl_clave, Exp_folio, NsumAl_medicamento,NsumAl_posologia,NsumAl_Cantidad,NsumAl_fecha) Values('".$value->cont."','".$folio."','".$value->medicamento."','".$value->indicaciones."','".$value->cantidad."',now())";
                    $result = $this->_db->query($query);
                    }catch(Exception $e){
                      $error='error';
                    }             
            }
        if($error=='error'){
            return $error;
        }else{
            return 'exito';
        }
    }
 /************************************************************************************************************************************/
/************************       función para guardar ortesis alternativas cuando no se cargan los sumistros symio  **************/
/************************************************************************************************************************************/
    public function setOrtAlter($folio, $datos)
    {
        $error = '';
        foreach ($datos as $key => $value) {                
                try{
                    $query="Insert into NotaOrtesisAlternativa(NortAl_clave, Exp_folio, NortAl_ortesis,NortAl_posologia,NortAl_Cantidad,NortAl_fecha) Values('".$value->cont."','".$folio."','".$value->ortesis."','".$value->indicaciones."','".$value->cantidad."',now())";
                    $result = $this->_db->query($query);
                    }catch(Exception $e){
                      $error='error';
                    }             
            }
        if($error=='error'){
            return $error;
        }else{
            return 'exito';
        }
    }

/********************************************************************************************************************************/
/************************       función para cargar suministros del sistema symio dependiendo la unidad        ******************/
/********************************************************************************************************************************/
	public function setSuministros($uni)
    {               
        $tabla="";
        $inventario=0;
        $cadena="";        
        switch ($uni) {
            case '1':
                $tabla="SymioBotRoma";
                $inventario=419;
                $cadena="";
                break;
            case '2':
                $tabla="SymioBotSatelite";
                $inventario=468;
                $cadena="";
                break;
            case '3':
                $tabla="SymioBotPerisur";
                $inventario=425;
                $cadena="";
                break;
            case '4':
                $tabla="SymioBotPuebla";
                $inventario=473;
                $cadena="";
                break;
            case '5':
                $tabla="SymioBotMonterrey";
                $inventario=544;
                $cadena="";
                break;
            case '6':
                $tabla="SymioBotMerida";
                $inventario=485;
                $cadena="";
                break;
            case '7':
                $tabla="SymioBotSanLuis";
                $inventario=531;
                $cadena="";
                break;
            case '8':
                $tabla="SymioBotPrueba";
                $inventario=419;
                $cadena="";
                break;
            case '86':
                $tabla="SymioBotChihuahua";
                $inventario=477;
                $cadena="";
                break;
            case '184':
                $tabla="SymioBotInterlomas";
                $inventario=767;
                $cadena="";
                break;
            case '186':
                $tabla="SymioBotVeracruz";
                $inventario=840;
                $cadena="";
                break;        
        }        
        $sql = "Truncate ".$tabla;
        $result = $this->_db->query($sql);
        
        $WebService = new nusoap_client('http://sistema.symio.com.mx/WSSymio/WSAlmacenes.asmx?WSDL','wsdl');
        $parametros = array('login' => 'wsmedicavial@symio.mx','pswd' => 'medicavial','clave_almacen' => $inventario);
        $result = $WebService->call("WS_Obtiene_inventarios", $parametros);
        
        foreach ($result as $WS_Obtiene_inventariosResult){
            foreach ($WS_Obtiene_inventariosResult as $Inventarios){
                foreach ($Inventarios as $DetalleInventario){
                    foreach($DetalleInventario as $Datos){
                        $i=0;
                        foreach($Datos as $Descripcion){
                            if($i==5){
                                $cadena.=$Descripcion."***";
                            }
                            else{
                                $cadena.=$Descripcion."///";
                            }
                            $i=$i+1;
                        }
                    }
                }
            }
        } 
        
        $numero=substr_count($cadena,"***");
        $datos = explode("***", $cadena);
        $i=0;
        try{
            while($i<$numero){
                $valor = explode("///", $datos[$i]);
                $query="Insert into ".$tabla."(Clave_producto, Stock, Fecha_consulta,Descripcion) Values('".$valor[2]."','".$valor[5]."',now(),'".$valor[3]."')";
                $result = $this->_db->query($query);
                $i=$i+1;
            }
            $respuesta = "exito"; 
        }catch(Exception $e){
            $respuesta=array('respuesta'=> $e->getMessage());
        }
        return $respuesta;
    } 

    /********************************************************************************************************************************/
/************************       función para regresar los suministros alternativos agregados en el modal           ******************/
/************************************************************************************************************************************/
    public function getSuministrosAlternativos($folio)
    {
        $query="select * from NotaSuministroAlternativa where Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $rs = $result->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    } 

/********************************************************************************************************************************/
/************************       función para regresar los suministros alternativos agregados en el modal           ******************/
/************************************************************************************************************************************/
    public function getOrtesisAlternativos($folio)
    {
        $query="select * from NotaOrtesisAlternativa where Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $rs = $result->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    } 
    /********************************************************************************************************************************/
    /************************       función para cargar suministros de la tabla del sisetma propio cargada anteriormente   **********/
    /********************************************************************************************************************************/
    public function getSuministros($uni)
    {
        set_time_limit(120); // limite de tiempo en tiempo en segundos
        $urlMed = 'http://api.medicavial.mx/api/busquedas/existencias/unidad/'.$uni.'/1';
        $datosURL = file_get_contents($urlMed);
        $listaMed = json_decode($datosURL, true);
        return $listaMed;


        $tabla="";
        $inventario=0;
        $cadena="";        
        switch ($uni) {
            case '1':
                $tabla="SymioBotRoma";
                $inventario=419;
                $cadena="";
                break;
            case '2':
                $tabla="SymioBotSatelite";
                $inventario=468;
                $cadena="";
                break;
            case '3':
                $tabla="SymioBotPerisur";
                $inventario=425;
                $cadena="";
                break;
            case '4':
                $tabla="SymioBotPuebla";
                $inventario=473;
                $cadena="";
                break;
            case '5':
                $tabla="SymioBotMonterrey";
                $inventario=544;
                $cadena="";
                break;
            case '6':
                $tabla="SymioBotMerida";
                $inventario=485;
                $cadena="";
                break;
            case '7':
                $tabla="SymioBotSanLuis";
                $inventario=531;
                $cadena="";
                break;
            case '8':
                $tabla="SymioBotPrueba";
                $inventario=419;
                $cadena="";
                break;
            case '86':
                $tabla="SymioBotChihuahua";
                $inventario=477;
                $cadena="";
                break;
            case '184':
                $tabla="SymioBotInterlomas";
                $inventario=767;
                $cadena="";
                break;
            case '186':
                $tabla="SymioBotVeracruz";
                $inventario=840;
                $cadena="";
                break;        
        }
        $query="select ".$tabla.".Clave_producto, Stock, Descripcion, Sym_indicacion, Sym_forma_far  from ".$tabla."
            inner join SymioCuadroBasico on ".$tabla.".Clave_producto=SymioCuadroBasico.Clave_producto
            where ".$tabla.".Clave_producto like 'MED%' and Stock>0 order by Descripcion asc";
        $result = $this->_db->query($query);
        $rs = $result->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }
}
 ?>