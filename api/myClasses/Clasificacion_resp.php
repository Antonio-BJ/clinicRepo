<?php 

require_once "Modelo.php";
/**
*  classe para agregar addendums a documentos
*/
class Clasificacion extends Modelo
{
    
    function __construct()
    {
         parent::__construct();
    }

    public function getUnidades()
    {        
        try{
            $query="Select Uni_nombrecorto, Uni_clave From Unidad where UNI_clave <> 8 and Uni_activa='S'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->db=null;      
    }

    public function getCompanias()
    {        
        try{
            $query="Select Cia_clave, Cia_nombrecorto From Compania where Cia_activa='S'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->db=null;      
    }

    public function buscaParametros($datos,$uni)
    { 
        $fechaIni=      $datos->fechaIni;
        $fechaFin=      $datos->fechaFin;
        $horaIni=       $datos->horaIni;
        $horaFin=       $datos->horaFin;
        $folio=         $datos->folio;
        $lesionado=     $datos->lesionado;
        $poliza=        $datos->poliza;
        $siniestro=     $datos->siniestro;
        $reporte=       $datos->reporte;
        $aseguradora=   $datos->aseguradora;
        $clasificacion= $datos->clasificacion;
        $fecAten=       $datos->fecAten;
        $importe=       $datos->importe;
        $compania=      $datos->compania;
        $FolioBus=      $datos->FolioBus;
        $unidad=        $datos->unidad;
        try{
            $query = "Select ";
            if($folio==1){
                $query.=" Exp_folio ";
            }if($lesionado==1){
                $query.=", Exp_nombre ";
            }if($poliza==1){
                $query.=", Exp_poliza ";
            }if($siniestro==1){
                $query.=", Exp_siniestro ";
            }if($reporte==1){
                $query.=", Exp_reporte ";
            }if($aseguradora==1){
                $query.=", Cia_nombrecorto ";
            }if($clasificacion==1){
                $query.=", LesE_nombre, ClasL_tipo ";
            }if($importe==1){
                $query.=", ClasL_costo ";
            }if($fecAten==1){
                $query.=", Exp_fechaAtencion ";
            }

            $query.= "From ClasificacionLes Inner Join Compania On ClasificacionLes.Cia_clave=Compania.Cia_clave Inner Join LesionEmpresa On LesionEmpresa.LesE_clave=ClasificacionLes.LesE_clave ";
            $query.="Where ";

            if($fechaIni!='' && $fechaFin!=''){
                //$query.= "ClasL_fechareg Between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59' and ";
                $query.= "ClasL_fechareg Between '".$fechaIni." ' '".$horaIni."' and '".$fechaFin." ' '".$horaFin."' and ";
            }            
            if($compania){$query.="  ClasificacionLes.Cia_clave='".$compania."' and";}
            if($FolioBus!="" || $FolioBus !=null){$query.="  Exp_folio='".$FolioBus."' And";}
            if($unidad>=1){$query.="  Uni_clave='".$unidad."' ";}
            elseif($uni==8&&$unidad>=1){}
            else{$query.=" Uni_clave='".$uni."' ";}
            $query.=" order by Exp_folio";            
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;   
         $this->db=null;    
    }

    public function buscaParametrosReporte($datos,$uni)
    {         
        $fechaIni=      $datos->fechaIni;
        $fechaFin=      $datos->fechaFin;
        $horaIni=       $datos->horaIni;
        $horaFin=       $datos->horaFin;
        $folio=         $datos->folio;
        $lesionado=     $datos->lesionado;
        $poliza=        $datos->poliza;
        $siniestro=     $datos->siniestro;
        $reporte=       $datos->reporte;
        $aseguradora=   $datos->aseguradora;
        $clasificacion= $datos->clasificacion;
        $fecAten=       $datos->fecAten;
        $importe=       $datos->importe;
        $compania=      $datos->compania;        
        $unidad=        $datos->unidad;
        try{
            $query = "Select ";
          
                $query.=" Exp_folio ";
           
                $query.=", Exp_completo ";
            
                $query.=", Exp_poliza ";
           
                $query.=", Exp_siniestro ";
            
                $query.=", Exp_reporte ";
           
                $query.=", Cia_nombrecorto ";
           
                $query.=", Exp_fecreg ";           

            $query.= "From Expediente Inner Join Compania On Expediente.Cia_clave=Compania.Cia_clave ";
            $query.="Where ";
            if($fechaIni!='' && $fechaFin!=''){
                //$query.= "Exp_fecreg Between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59' and ";
                $query.= "Exp_fecreg Between '".$fechaIni." ' '".$horaIni."' and '".$fechaFin." ' '".$horaFin."' and ";
            }            
            if($compania){$query.="  Expediente.Cia_clave='".$compania."' and";}            
            if($unidad>=1){$query.="  Uni_clave='".$unidad."' and Exp_cancelado<>1 ";}
            else{$query.=" Uni_clave='".$uni."' and Exp_cancelado<>1 ";}
            $query.=" order by Exp_folio";             


            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;   
         $this->db=null;    
    }

     public function buscaFolSinDoc($datos,$usr)
    {         
        
        $compania=      $datos->compania;        
        $unidad=        $datos->unidad;
        try{
            $query="select Uni_nombrecorto,Exp_folio,Exp_completo,Exp_fecreg, Exp_poliza, Exp_siniestro, Exp_reporte, Cia_nombrecorto, DATEDIFF(curdate(),Exp_fecreg) as diasAtraso  from Expediente
                    inner join Unidad on Expediente.Uni_clave=Unidad.Uni_clave
                    left join  Documento on Expediente.Exp_folio = Documento.DOC_folio
                    inner join Compania on Expediente.Cia_clave = Compania.Cia_clave
                    where  Expediente.Cia_clave=".$compania." and
                    Exp_cancelado<>1 and
                    Expediente.Uni_clave=".$unidad." and                    
                    Exp_fecreg < curdate() - interval 5 day and
                    Exp_fecreg > '2015-10-01 00:00:00' and
                    Documento.DOC_etapa is null
                     order by Uni_nombrecorto, Exp_fecreg desc ";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta; 
         $this->_db=null;        
    }

    
    public function getLocalidad($unidad)
    {        
        try{
            $query="Select LOC_claveint From Unidad where Uni_clave=".$unidad;
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();    
            $localidad= $respuesta['LOC_claveint'];
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $localidad = 'error';       
        }    
         return $localidad; 
         $this->_db=null;        
    }


    
}
?>