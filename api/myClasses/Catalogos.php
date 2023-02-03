<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class Catalogos extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function getlistadoAjustador($compania, $unidad)
    {                
        $sql="select LOC_claveint from Unidad where Uni_clave=".$unidad;
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        $localidad=$respuesta['LOC_claveint'];
    	try{
            $sql = "SELECT DISTINCT CONCAT( AJU_nombre, ' / ', AJU_clavease ) as nombreAjustador, AJU_claveint from Cat_Ajustador where LOC_claveint='$localidad' and EMP_claveint='$compania' order by AJU_nombre ";
            $result = $this->_db->query($sql);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        }catch(Exception $e){
        	//$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e);       
        }    
         return $respuesta;  
         $this->db=null;     
    }

    public function getProducto($folio)
    {        
        $sql="select Pro_clave from Expediente where Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        $producto=$respuesta['Pro_clave'];        
        return $producto; 
        $this->db=null;      
    }

    public function getCia($folio)
    {        
        $sql="select Cia_clave from Expediente where Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();
        $cia=$respuesta['Cia_clave'];        
        return $respuesta; 
        $this->db=null;      
    }

     public function getCiaLocalidad($folio)
    {        
        $sql="select Cia_clave,LOC_claveint from Expediente
            inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
            where Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch(PDO::FETCH_OBJ);        
        return $respuesta; 
        $this->db=null;      
    }

      /*public function getListadoUnidades()
    {        
        $sql="select Uni_clave, Uni_nombre from Unidad where Uni_activa='S' order by Uni_nombre";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        return $respuesta;    
    }*/
    public function getListadoUnidades()
    {        

        $sql="select Uni_clave, UNI_nombreMV as Uni_nombre from Unidad 
                inner join Localidad on Unidad.LOC_claveint=Localidad.LOC_claveint
                where Uni_activa='S' order by UNI_nombreMV";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
        return $respuesta;
        $this->db=null;     

    }
    public function getUnidad($uni)
    {        
        $sql="select Uni_propia from Unidad where Uni_clave=".$uni;
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();        
        return $respuesta['Uni_propia'];  
        $this->db=null;     
    }

     public function getZona($uniAlternativa)
    {        
        $sql="select Zon_clave from Unidad where Uni_clave=".$uniAlternativa;
        $result = $this->_db->query($sql);
        $respuesta = $result->fetch();        
        return $respuesta['Zon_clave']; 
        $this->db=null;      
    }

     public function getavisosCoordinacion($folio)
    {        
        $sql="SELECT COE_comentario, COE_fecha, Usu_nombre FROM ComentarioExpediente 
        inner join Usuario on ComentarioExpediente.COE_usuario=Usuario.Usu_login
        WHERE COE_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $respuesta = $result->fetchAll(PDO::FETCH_OBJ); 
        if($respuesta){
            return $respuesta;        
        }else{
            $respuesta = array('respuesta' =>'error'); 
            return $respuesta;
        } 
        $this->db=null;                 
  }

   public function getMembresia($fol)
    {     
        $folio= $_GET['fol'];   
        $sql="SELECT Exp_completo FROM Expediente WHERE Exp_folio='".$fol."'";
        $result = $this->_db->query($sql);
        $datosFol = $result->fetch(); 
        $nombre= $datosFol['Exp_completo'];      
        $sql="SELECT count(*) as contador FROM MembresiaMv WHERE Exp_folio='".$fol."' and mem_cancelada!=1"; 
        $result = $this->_db->query($sql);
        $memExiste = $result->fetch(); 
        $contador= $memExiste['contador'];
        if($contador>0){
            $sql="SELECT mem_folio, mem_serie, mem_nombre FROM MembresiaMv WHERE Exp_folio='".$fol."'"; 
            $result = $this->_db->query($sql);
            $datosMem = $result->fetch(); 
            return $datosMem;

        }else{
            return array('respuesta'=>0);           
        }
        $this->_db=null;        
        
    }
    public function getCambioUnidad($fol)
    {     
       
        $sql="SELECT count(*) as contador FROM HistoriaCambioUnidad WHERE Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $memExiste = $result->fetch(); 
        $contador= $memExiste['contador'];
        return $contador; 
        $this->_db=null;        
        
    }
    public function getRehabsFol($fol)
    {     
       
        $sql="SELECT Rehab_cons,Exp_folio FROM Rehabilitacion WHERE Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $listado = $result->fetchAll();         
        return $listado;       
        
    }

     public function getCatCancelados()
    {            
        $sql="SELECT CAC_cve, CAC_nombre FROM CatCancelacion"; 
        $result = $this->_db->query($sql);
        $catCancela = $result->fetchAll();         
        return $catCancela;       
        
    }
    public function catalogoCompania()
    {            
        $sql="SELECT Cia_clave, Cia_nombrecorto FROM Compania WHERE Cia_activa='S'"; 
        $result = $this->_db->query($sql);
        $catCia = $result->fetchAll();         
        return $catCia;       
        
    }

     public function getCatLocalidades()
    {            
        $sql="SELECT LOC_claveint, LOC_nombre FROM Localidad WHERE LOC_claveint in (18,29)"; 
        $result = $this->_db->query($sql);
        $catCancela = $result->fetchAll();         
        return $catCancela;       
        
    }

    public function getMedico($med)
    {     
       
        $sql="SELECT count(*) as contador FROM Medico where Usu_login='".$med."'"; 
        $result = $this->_db->query($sql);
        $catCancela = $result->fetch();  
        $medicoExist= $catCancela['contador'];       
        return $medicoExist;       
        
    }

    public function datAccidente($fol)
    {            
        $sql="SELECT ObsNot_edoG, ObsNot_glasgow, Not_fechaAcc, TipoVehiculo_clave, Posicion_clave, Not_vomito, Not_mareo, Not_nauseas, Not_perdioConocimiento, Not_cefalea, Not_obs, Llega_clave FROM ObsNotaMed
              INNER JOIN NotaMedica on ObsNotaMed.Exp_folio = NotaMedica.Exp_folio
              where ObsNotaMed.Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();        
        return $obsNot;       
        
    }
    public function diagnostico($fol)
    {            
        $sql="SELECT ObsNot_diagnosticoRx, ObsNot_obs FROM ObsNotaMed WHERE Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();        
        return $obsNot;       
    }
    public function pronostico($fol)
    {            
        $sql="SELECT ObsNot_pron FROM ObsNotaMed WHERE Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();        
        return $obsNot;       
    }
    public function lesionAdmin($fol)
    {            
        $sql="SELECT ObsNot_tipoLesion, Clave_lesionMV FROM ObsNotaMed WHERE Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();        
        return $obsNot;       
    }
    public function lesionSelecc($fol)
    {            
        $sql="SELECT ObsNot_tipoLesion, Clave_lesionMV FROM ObsNotaMed WHERE Exp_folio='".$fol."'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();        
        return $obsNot;       
    }
    public function UnidadNombre($uni)
    {            
        $sql="SELECT Uni_nombrecorto FROM Unidad WHERE Uni_clave=".$uni; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();        
        return $obsNot;       
    }

    // conteo de membresías emitidas por undiad
    public function membresiasEmitidas($uni)
    {   
        $dia  = date('d');
        $mes  = date('m');
        $anio = date('Y');                
               
        $sql="SELECT count(DISTINCT mem_nombre) as CONTEO from MembresiaMv where Uni_clave=".$uni; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteTotal = $obsNot['CONTEO'];        
        $conteos['total']= $conteTotal;
        $sql="SELECT count(DISTINCT mem_nombre) as CONTEO from MembresiaMv where Uni_clave=".$uni." 
              and mem_fecreg between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteMes = $obsNot['CONTEO'];        
        $conteos['mes']= $conteMes;
        $sql="SELECT count(DISTINCT mem_nombre) as CONTEO from MembresiaMv where Uni_clave=".$uni." 
              and mem_fecreg between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59'"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteDia = $obsNot['CONTEO'];        
        $conteos['dia']= $conteDia;  
        return $conteos;      
    }

    public function recibosEmitidos($uni)
    {   
        $dia  = date('d');
        $mes  = date('m');
        $anio = date('Y');                
               
        $sql="SELECT count(*) as CONTEO from reciboParticulares where Uni_clave=".$uni." AND Recibo_cancelado<>1"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteTotal = $obsNot['CONTEO'];        
        $conteosRecibos['total']= $conteTotal;
        $sql="SELECT count(*) as CONTEO from reciboParticulares where Uni_clave=".$uni." 
              and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteMes = $obsNot['CONTEO'];        
        $conteosRecibos['mes']= $conteMes;
        $sql="SELECT count(*) as CONTEO from reciboParticulares where Uni_clave=".$uni." 
              and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteDia = $obsNot['CONTEO'];        
        $conteosRecibos['dia']= $conteDia;  
        return $conteosRecibos;      
    }
    public function recibosMembresias($uni)
    {   
        $dia  = date('d');
        $mes  = date('m');
        $anio = date('Y');                
               
        $sql="SELECT count(*) as CONTEO from reciboParticulares 
             INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
              where reciboParticulares.Uni_clave=".$uni." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
             AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteTotal = $obsNot['CONTEO']; 

        $conteosRecibosSAp['total']= $conteTotal;
        $sql="SELECT count(*) as CONTEO from reciboParticulares 
                INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
                where reciboParticulares.Uni_clave=".$uni." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
              and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' 
              AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteMes = $obsNot['CONTEO'];        
        $conteosRecibosSAp['mes']= $conteMes;
        $sql="SELECT count(*) as CONTEO from reciboParticulares 
                INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
                where reciboParticulares.Uni_clave=".$uni." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
              and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' 
              AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteDia = $obsNot['CONTEO'];        
        $conteosRecibosSAp['dia']= $conteDia;  
        return $conteosRecibosSAp;      
    }
    // conteo de membresías emitidas por undiad
    public function membresiasEmitidasAdmin()
    {   
        $dia        = date('d');
        $mes        = date('m');
        $anio       = date('Y');                
       $array = array(1, 2, 3, 4, 5, 6, 7, 86, 184, 186);

/*---- AQUI VAMOS A SACAR EL TOTAL DE INGRESOS $$ DE LOS RECIBOS ----*/
       foreach ($array as $key => $value) {
            $sql="SELECT count(*) as CONTEO from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
             where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteTotal = $obsNot['CONTEO']; 
            $conteTotal= (string)$conteTotal;       
            $conteos[$conteTotal]['totalRecibo']= $conteTotal;


            $sql="SELECT ROUND( SUM(Recibo_total), 2 ) as INGRESOS from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
             where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $ingresoTotal = $obsNot['INGRESOS'];                         
            $conteos[$conteTotal]['ingresosTotal']= $ingresoTotal;

            $sql="SELECT ROUND( SUM(Recibo_total), 2 ) as INGRESOS from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
                  where reciboParticulares.Uni_clave=".$value." 
                  and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();            
            $ingresosMes = $obsNot['INGRESOS'];             
            $conteos[$conteTotal]['ingresosMes']= $ingresosMes;


            $sql="SELECT ROUND( SUM(Recibo_total), 2 ) as INGRESOS from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
                  where reciboParticulares.Uni_clave=".$value." 
                  and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $ingresosDia = $obsNot['INGRESOS'];        
            $conteos[$conteTotal]['ingresosDia']= $ingresosDia;  
            //print_r($conteos);
        }
/*----  ----*/

       foreach ($array as $key => $value) {
            $sql="SELECT count(*) as CONTEO from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
             where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteTotal = $obsNot['CONTEO'];                         
            $conteos[$conteTotal]['totalRecibo']= $conteTotal;


            $sql="SELECT count(*) as CONTEO from reciboParticulares 
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
                  where reciboParticulares.Uni_clave=".$value." 
                  and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);

            $obsNot = $result->fetch();            
            $conteMes = $obsNot['CONTEO'];             
            $conteos[$conteTotal]['mesRecibo']= $conteMes;
            $sql="SELECT count(*) as CONTEO from reciboParticulares 
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
                  where reciboParticulares.Uni_clave=".$value." 
                  and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteDia = $obsNot['CONTEO'];        
            $conteos[$conteTotal]['diaRecibo']= $conteDia;  
        }

        foreach ($array as $key => $value) {
            $sql="SELECT count(*) as CONTEO from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
             where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteTotal = $obsNot['CONTEO']; 
            $conteTotal= (string)$conteTotal;       
            $conteos[$conteTotal]['totalRecibo']= $conteTotal;

            $sql="SELECT count(DISTINCT mem_nombre) as CONTEO from MembresiaMv where Uni_clave=".$value; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteTotalMem = $obsNot['CONTEO'];        
            $conteos[$conteTotal]['totalMembresias']= $conteTotalMem;
            $sql="SELECT count(DISTINCT mem_nombre) as CONTEO from MembresiaMv where Uni_clave=".$value." 
                  and mem_fecreg between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteMes = $obsNot['CONTEO'];        
            $conteos[$conteTotal]['mesMembresias']= $conteMes;
            $sql="SELECT count(DISTINCT mem_nombre) as CONTEO from MembresiaMv where Uni_clave=".$value." 
                  and mem_fecreg between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteDia = $obsNot['CONTEO'];                
            $conteos[$conteTotal]['diaMembresias']= $conteDia;           
            $sql = "SELECT Uni_nombreotro, Uni_nombrecorto FROM Unidad where Uni_clave=".$value;
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteos[$conteTotal]['uniNombre']=$obsNot['Uni_nombrecorto'];
            $conteos[$conteTotal]['uniNombreOtro']=$obsNot['Uni_nombreotro'];
        }    
         
        foreach ($array as $key => $value) {
            $sql="SELECT count(*) as CONTEO from reciboParticulares
                  INNER JOIN Expediente on reciboParticulares.Exp_folio = Expediente.Exp_folio
             where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 and Exp_modificador='M.MV'"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteTotal = $obsNot['CONTEO']; 
            $conteTotal= (string)$conteTotal;       
            $conteos[$conteTotal]['totalRecibo']= $conteTotal;
            $sql="SELECT count(*) as CONTEO from reciboParticulares 
             INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
              where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
             AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteTotalRecib = $obsNot['CONTEO']; 

            $conteos[$conteTotal]['totalRecibosMem']= $conteTotalRecib;
            $sql="SELECT count(*) as CONTEO from reciboParticulares 
                    INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
                    where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
                  and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' 
                  AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteMes = $obsNot['CONTEO'];        
            $conteos[$conteTotal]['mesRecibosMem']= $conteMes;
            $sql="SELECT count(*) as CONTEO from reciboParticulares 
                    INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
                    where reciboParticulares.Uni_clave=".$value." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
                  and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' 
                  AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
            $result = $this->_db->query($sql);
            $obsNot = $result->fetch();
            $conteDia = $obsNot['CONTEO'];        
            $conteos[$conteTotal]['diaRecibosMem']= $conteDia;  
        }       
       
        $nuevoArray=$this->array_sort($conteos, 'totalRecibo', SORT_DESC);
        $cont=0;

        foreach ($nuevoArray as $key => $value) {
            $conteos1[$cont]=$value;
            $cont++;
        }

        return $conteos1;
    }

    public function recibosEmitidosAdmin()
    {   
        $dia  = date('d');
        $mes  = date('m');
        $anio = date('Y');                
               
        $sql="SELECT count(*) as CONTEO from reciboParticulares where Uni_clave=".$uni." AND Recibo_cancelado<>1"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteTotal = $obsNot['CONTEO'];        
        $conteosRecibos['total']= $conteTotal;
        $sql="SELECT count(*) as CONTEO from reciboParticulares where Uni_clave=".$uni." 
              and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteMes = $obsNot['CONTEO'];        
        $conteosRecibos['mes']= $conteMes;
        $sql="SELECT count(*) as CONTEO from reciboParticulares where Uni_clave=".$uni." 
              and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' AND Recibo_cancelado<>1"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteDia = $obsNot['CONTEO'];        
        $conteosRecibos['dia']= $conteDia;  
        return $conteosRecibos;      
    }
    public function recibosMembresiasAdmin()
    {   
        $dia  = date('d');
        $mes  = date('m');
        $anio = date('Y');                
               
        $sql="SELECT count(*) as CONTEO from reciboParticulares 
             INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
              where reciboParticulares.Uni_clave=".$uni." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
             AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteTotal = $obsNot['CONTEO']; 

        $conteosRecibosSAp['total']= $conteTotal;
        $sql="SELECT count(*) as CONTEO from reciboParticulares 
                INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
                where reciboParticulares.Uni_clave=".$uni." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
              and Recibo_fecExp between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-".$dia." 23:59:59' 
              AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteMes = $obsNot['CONTEO'];        
        $conteosRecibosSAp['mes']= $conteMes;
        $sql="SELECT count(*) as CONTEO from reciboParticulares 
                INNER JOIN Expediente on reciboParticulares.Exp_folio= Expediente.Exp_folio
                where reciboParticulares.Uni_clave=".$uni." AND Recibo_cancelado<>1 AND Exp_cancelado<>1 
              and Recibo_fecExp between '".$anio."-".$mes."-".$dia."' and '".$anio."-".$mes."-".$dia." 23:59:59' 
              AND Exp_modificador='M.MV' AND Recibo_aplicado<Recibo_total"; 
        $result = $this->_db->query($sql);
        $obsNot = $result->fetch();
        $conteDia = $obsNot['CONTEO'];        
        $conteosRecibosSAp['dia']= $conteDia;  
        return $conteosRecibosSAp;      
    }




    function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

 public function lesionCodificada($opcion)
    {     
       
        $sql="SELECT LCO_id, LCO_nombre  FROM LesionCodificadaBloques WHERE TipoLesionCve=".$opcion; 
        $result = $this->_db->query($sql);
        $lesionMod = $result->fetchAll(); 
        return $lesionMod;       
        
    }
     public function lesionCodificadaLesion($opcion)
    {                    
        $sql = "SELECT Clave_lesionCia FROM LesionEquivalencia where Clave_lesionMV='".$opcion."'";
        $result = $this->_db->query($sql);
        $lesionCod = $result->fetch();
        $lesionCia = $lesionCod['Clave_lesionCia'];        

        $sql="  SELECT LesionCodificada.CIE_cve, CIE_descripcion  FROM LesionCodificada
                INNER JOIN CieOrtopedico on LesionCodificada.CIE_cve= CieOrtopedico.CIE_cve collate utf8_general_ci
                where LesE_clave='".$opcion."'"; 
        $result = $this->_db->query($sql);
        $lesionCod = $result->fetchAll(); 
        return $lesionCod;       
        
    }

     public function getSinCuestionario($uni){     
        $sql="SELECT Exp_folio, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs,
                Uni_nombrecorto, Cia_nombrecorto
              from Expediente 
              left join PaseCuestionario on Expediente.Exp_folio=PaseCuestionario.PAS_Folio 
              inner join Unidad on Unidad.Uni_clave=Expediente.Uni_clave
              inner join Compania on Compania.Cia_clave=Expediente.Cia_clave             
              where Exp_fecreg>='2016-10-01 00:00:00'
              and Exp_cancelado<>1
              and Exp_cu<>1
              and Unidad.Uni_clave=".$uni."
              and PAS_Folio is null order by Exp_fecreg desc"; 
        $result = $this->_db->query($sql);
        $listado = $result->fetchAll(PDO::FETCH_ASSOC); 
        return $listado;               
    }

    public function getConCuestionario($uni){     
        $sql="SELECT Exp_folio, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs,
                Uni_nombrecorto, Cia_nombrecorto
              from Expediente 
              left join PaseCuestionario on Expediente.Exp_folio=PaseCuestionario.PAS_Folio 
              inner join Unidad on Unidad.Uni_clave=Expediente.Uni_clave
              inner join Compania on Compania.Cia_clave=Expediente.Cia_clave             
              where Exp_fecreg>='2016-10-01 00:00:00'
              and Exp_cancelado<>1
              and Unidad.Uni_clave=".$uni." and PAS_Folio is not null order by Exp_fecreg desc"; 
        $result = $this->_db->query($sql);
        $listado = $result->fetchAll(PDO::FETCH_ASSOC); 
        return $listado;               
    }

    public function getTodoCuestionario($uni){     
        $sql="SELECT Exp_folio, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs,
                Uni_nombrecorto, Cia_nombrecorto, if(PAS_Folio is null,'no','si') as capturado
              from Expediente 
              left join PaseCuestionario on Expediente.Exp_folio=PaseCuestionario.PAS_Folio 
              inner join Unidad on Unidad.Uni_clave=Expediente.Uni_clave
              inner join Compania on Compania.Cia_clave=Expediente.Cia_clave             
              where Exp_fecreg>='2016-10-01 00:00:00'
              and Exp_cancelado<>1
              and Unidad.Uni_clave=".$uni."  order by Exp_fecreg desc"; 
        $result = $this->_db->query($sql);
        $listado = $result->fetchAll(PDO::FETCH_ASSOC); 
        return $listado;               
    }

     public function getMonitorCuestionario($uni){         
        $listado = array();            
        $sql="SELECT count(*) as contSinCaptura
              from Expediente 
              left join PaseCuestionario on Expediente.Exp_folio=PaseCuestionario.PAS_Folio              
              where Exp_fecreg>='2016-10-01 00:00:00'
              and Exp_cancelado<>1              
              and Uni_clave=".$uni." and PAS_Folio is null";
        $result = $this->_db->query($sql);
        $contSinCap= $result->fetch();        
        $listado['contSinCaptura']= $contSinCap['contSinCaptura'];
        $sql="SELECT count(*) as contCaptura
              from Expediente 
              left join PaseCuestionario on Expediente.Exp_folio=PaseCuestionario.PAS_Folio              
              where Exp_fecreg>='2016-10-01 00:00:00'
              and Exp_cancelado<>1
              and Uni_clave=".$uni." and PAS_Folio is not null";              
        $result = $this->_db->query($sql);
        $contCap= $result->fetch();
        $listado['contCaptura']= $contCap['contCaptura'];
         $sql="SELECT count(*) as conTodos
              from Expediente            
              where Exp_fecreg>='2016-10-01 00:00:00'
              and Exp_cancelado<>1
              and Uni_clave=".$uni;              
        $result = $this->_db->query($sql);
        $contTod= $result->fetch();
        $listado['contTodas']= $contTod['conTodos'];
        return $listado;               
    }

    /**********************************************   nuevo catálogo de de Ajustadores **********************************/
    public function busquedaFolioZima($fol,$usr)
    {                      
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        try{
            
            $sql = "Select Fol_MedicaVial from RegMVZM where Fol_ZIMA='".$fol."'";
            $result = $this->_db->query($sql);
            $rxTipo = $result->fetch(); 
            
            if($rxTipo['Fol_MedicaVial']){
                return array('respuesta'=>'existe','folioMv'=>$rxTipo['Fol_MedicaVial']);
            }else{
                $sqlZima = "select UNI_clave, ASE_clave,REG_nombrecompleto,REG_nombre,REG_apaterno,REG_amaterno,REG_poliza,REG_siniestro,REG_reporte,REG_edad,REG_genero,REG_folio,REG_fechahora from PURegistro where REG_folio='".$fol."'";

                $result = $this->_dbZima->query($sqlZima);
                $detalleFolZima = $result->fetch();                
                $respuesta = $detalleFolZima;
                if($respuesta){
                    $completo = $respuesta['REG_nombrecompleto'];
                    $nombre = $respuesta['REG_nombre'];
                    $apPaterno = $respuesta['REG_apaterno'];
                    $apMaterno = $respuesta['REG_amaterno'];
                    $poliza = $respuesta['REG_poliza'];
                    $siniestro = $respuesta['REG_siniestro'];
                    $reporte = $respuesta['REG_reporte'];
                    $riesgo = 7;        
                    $triageActual = 1;
                    $edad = $respuesta['REG_edad'];
                    $sexo = $respuesta['REG_genero'];
                    $folioZ = $respuesta['REG_folio'];
                    $fecha = $respuesta['REG_fechahora'];

                    if($ciaMV==4){
                        $producto=9;
                    }elseif($ciaMV==7){
                        $producto=12;
                    }else{
                        $producto=1;
                    }   
                    $query= "SELECT Uni_clave FROM Unidad WHERE UNI_zima=".$respuesta['UNI_clave']." AND Uni_activa='S' ";
                    $result = $this->_db->query($query);
                    $uniAsociada = $result->fetch();   
                    
                    $query1 = "SELECT Cia_clave FROM Compania WHERE ClaveZIMA=".$respuesta['ASE_clave']." and  Cia_activa='S'";
                    $result1 = $this->_db->query($query1);
                    $CiaAsociada = $result1->fetch();   
                    if($uniAsociada && $CiaAsociada){
                        $sql = "SELECT Pre_prefijo FROM Prefijo where Uni_clave=".$uniAsociada['Uni_clave'];
                        $result = $this->_db->query($sql);
                        $prefijoArray = $result->fetch();         
                        $prefijo = $prefijoArray['Pre_prefijo'];
                        $sql ="Select MAX(EXP_cons)+1 as contador From Expediente Where Exp_prefijo='".$prefijo."'";
                        $result = $this->_db->query($sql);
                        $ContadorArray = $result->fetch(); 
                        $contador = $ContadorArray['contador'];
                        if ($contador==null) {$contador=1;}
                        $c="000000".$contador;
                        $c=substr($c,-6,6);
                        $folio=$prefijo.$c;

                         $sql="INSERT INTO Expediente(Exp_folio, Exp_prefijo, Exp_cons, Uni_clave, Usu_registro, Exp_poliza, Exp_siniestro, Exp_paterno,Exp_materno, Exp_nombre, Exp_completo, Exp_fecreg, Cia_clave, RIE_clave, Pro_clave, Uni_ClaveActual, Exp_triageOrigen, Exp_triageActual, Exp_edad, Exp_sexo, REG_folioZima)
                              VALUES(:Exp_folio, :Exp_prefijo, :Exp_cons, :Uni_clave, :Usu_registro, :Exp_poliza, :Exp_siniestro, :Exp_paterno, :Exp_materno, :Exp_nombre, :Exp_completo, :Exp_fecreg, :Cia_clave, :RIE_clave, :Pro_clave, :Uni_ClaveActual, 1, :Exp_triageActual, :Exp_edad, :Exp_sexo, :REG_folioZima)";
                        $temporal= $this->_db->prepare($sql);
                        $temporal->bindParam("Exp_folio",$folio);          
                        $temporal->bindParam("Exp_prefijo",$prefijo);              
                        $temporal->bindParam("Exp_cons",$contador);                         
                        $temporal->bindParam("Uni_clave",$uniAsociada['Uni_clave']);                
                        $temporal->bindParam("Usu_registro",$usr);         
                        $temporal->bindParam("Exp_poliza",$poliza);       
                        $temporal->bindParam("Exp_siniestro",$siniestro);         
                        $temporal->bindParam("Exp_paterno",$apPaterno);         
                        $temporal->bindParam("Exp_materno",$apMaterno);         
                        $temporal->bindParam("Exp_nombre",$nombre);         
                        $temporal->bindParam("Exp_completo",$completo);         
                        $temporal->bindParam("Exp_fecreg",$fecha);         
                        $temporal->bindParam("Cia_clave",$CiaAsociada['Cia_clave']);         
                        $temporal->bindParam("RIE_clave",$riesgo);         
                        $temporal->bindParam("Pro_clave",$producto);         
                        $temporal->bindParam("Uni_ClaveActual",$uniAsociada['Uni_clave']);         
                        $temporal->bindParam("Exp_triageActual",$triageActual);         
                        $temporal->bindParam("Exp_edad",$edad);         
                        $temporal->bindParam("Exp_sexo",$sexo);  
                        $temporal->bindParam("REG_folioZima",$fol);      
                        if($temporal->execute()){
                            $query = "INSERT INTO RegMVZM(Fol_MedicaVial,Fol_ZIMA,Cia_clave)
                                      VALUES(:Fol_MedicaVial,:Fol_ZIMA,:Cia_clave)";
                            $temporal= $this->_db->prepare($query);
                            $temporal->bindParam("Fol_MedicaVial",$folio);          
                            $temporal->bindParam("Fol_ZIMA",$fol);              
                            $temporal->bindParam("Cia_clave",$CiaAsociada['Cia_clave']); 

                            //actualizamos el registro Zima con el folio MV ($folio -> MV; $fol -> Zima)
                            try {
                                $queryZima ="UPDATE PURegistro set REG_folioMV='".$folio."'
                                                where REG_folio='".$fol."'";

                                $result = $this->_dbZima->query($queryZima);
                            } catch (Exception $e) {
                                // print_r($e)
                            }

                            if($temporal->execute()){
                                $sql = "select Uni1.Uni_nombrecorto as actual, Expediente.Uni_ClaveActual as claveActual, Uni2.Uni_nombrecorto as origen, Expediente.Uni_Clave as claveOrigen  from Expediente
                                        inner join Unidad as Uni1 on Expediente.Uni_ClaveActual= Uni1.Uni_clave
                                        inner join Unidad as Uni2 on Expediente.Uni_clave = Uni2.Uni_Clave
                                        where Exp_folio='".$folio."'";
                                $result = $this->_db->query($sql);
                                $unidad = $result->fetch();
                                $uniActual = $unidad['actual'];
                                $claveActual = $unidad['claveActual'];
                                $uniOrigen = $unidad['origen'];
                                $claveOrigen= $unidad['claveOrigen'];

                                $respuesta = array('origen' => $uniOrigen, 'cveOrigen'=> $claveOrigen, 'actual'=>$uniActual, 'cveActual'=>$claveActual,'folio'=>$folio);
                                return $respuesta;
                            }
                        }
                    }else{                        
                        return array('respuesta'=>'sinUnidadAsociada');
                    }
                }else{
                    return array('respuesta'=>'sinFolioZima');                    
                }
            }            
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' => $e->getMessage());       
        }    
         return $respuesta;  
         $this->db=null;     
    }

     public function listaRxZonaTipo()
    {                      
        try{
            
            $sql = "SELECT TRX_id, TRX_nombre from TipoRx";
            $result = $this->_db->query($sql);
            $rxTipo = $result->fetchAll(); 

            $sql1 = "SELECT RXZ_id, RXZ_zona FROM RxZona";
            $result1 = $this->_db->query($sql1);
            $rxZona = $result1->fetchAll(); 

            $respuesta['tipo'] = $rxTipo;
            $respuesta['zona'] = $rxZona;

            
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' => $e->getMessage());       
        }    
         return $respuesta;  
         $this->db=null;     
    }

    public function validaDescuento($item, $cod){
        $datos = explode('/', $item);
        $itemCve = $datos[0]; 
        $sql="select * from CodigoDescuento where CDE_codigo='".$cod."' and Ite_cons='".$itemCve."'";
        $result = $this->_db->query($sql);
        $listado= $result->fetch();
        return $listado;               
    }

     public function catalogoEmpresas($op){ 
        if ($op) {
            $sql="select * from EmpresaRegistro where EMR_activo=1 and EMR_tipo=".$op;        
        }else{
            $sql="select * from EmpresaRegistro where EMR_activo=1";
        }       
        
        $result = $this->_db->query($sql);
        $listado= $result->fetchAll();
        return $listado;               
    }

    public function masUsados($uni){        
        $sql="select * from MasUsados 
              inner join Compania on MasUsados.Cia_clave = Compania.Cia_clave
                where Uni_clave=".$uni." limit 6";
        $result = $this->_db->query($sql);
        $listado= $result->fetchAll();
        return $listado;               
    }

    public function catTwitter($uni){        
        $sql="select * from CatTwitter";
        $result = $this->_db->query($sql);
        $listado= $result->fetchAll();
        return $listado;               
    }

    public function esMedico($med){        
        $sql="select count(*) contador from Medico where Usu_login='".$med."'";
        $result = $this->_db->query($sql);
        $cont= $result->fetch();
        return $cont;               
    }

    public function compania(){        
        $sql="select * from Compania where  Cia_activa='S'";
        $result = $this->_db->query($sql);
        $cont= $result->fetchAll();
        return $cont;               
    }

    public function getDetalleCanelacion($folio){   
        
        $sql="select Usu_nombre, Exp_folio, Exp_fcancelado, Exp_mcancelado, Exp_motCancel, CAC_nombre, Exp_duplicado from Expediente 
                left join Usuario on Expediente.Usu_cancelado = Usuario.Usu_login
                left join CatCancelacion on Expediente.Exp_motCancel = CatCancelacion.CAC_cve
                where  Exp_folio='".$folio."'";
        $result = $this->_db->query($sql);
        $cont= $result->fetch();
        return $cont;               
    }

    /********************************************************************************************************************/

}
?>