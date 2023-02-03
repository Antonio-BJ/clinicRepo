<?php 

require_once "Modelo.php";
include ('classes/nomad_mimemail.inc.php');
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
          /*
          $query = "Select ";
                $query.=" Exp_folio ";
                $query.=", Exp_completo ";
                $query.=", Exp_poliza ";
                $query.=", Exp_siniestro ";
                $query.=", Exp_reporte ";
                $query.=", Cia_nombrecorto ";
                $query.=", Exp_fecreg ";
                $query.=", Exp_solCancela ";

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
            */


          #----------------- CONSULTA ORIGINAL -----------------#
          $query1 = "Select ";
                $query1.=" Exp_folio ";
                $query1.=", Exp_completo ";
                $query1.=", Exp_poliza ";
                $query1.=", Exp_siniestro ";
                $query1.=", Exp_reporte ";
                $query1.=", Cia_nombrecorto ";
                $query1.=", Exp_fecreg ";
                $query1.=", Exp_solCancela ";

            $query1.= "From Expediente Inner Join Compania On Expediente.Cia_clave=Compania.Cia_clave ";
            $query1.="Where ";
            if($fechaIni!='' && $fechaFin!=''){
                //$query.= "Exp_fecreg Between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59' and ";
                $query1.= "Exp_fecreg Between '".$fechaIni." ' '".$horaIni."' and '".$fechaFin." ' '".$horaFin."' and ";
            }            
            if($compania){$query1.="  Expediente.Cia_clave='".$compania."' and";}            
            if($unidad>=1){$query1.="  Uni_claveActual='".$unidad."' and Exp_cancelado<>1 ";}
            else{$query1.=" Uni_claveActual='".$uni."' and Exp_cancelado<>1 ";}
            $query1.=" order by Exp_folio";             

            $result = $this->_db->query($query1);
            $respuesta1 = $result->fetchAll(PDO::FETCH_OBJ);  
            
          #----------------- CONSULTA CON FECHA DE NOTA MEDICA -----------------#
          $query2 = "Select ";
                $query2.=" Expediente.Exp_folio ";
                $query2.=", Exp_completo ";
                $query2.=", Exp_poliza ";
                $query2.=", Exp_siniestro ";
                $query2.=", Exp_reporte ";
                $query2.=", Cia_nombrecorto ";
                $query2.=", Not_fechareg ";
                $query2.=", Exp_solCancela ";

            $query2.= "From Expediente ";
            $query2.= "Inner Join Compania On Expediente.Cia_clave=Compania.Cia_clave ";
            $query2.= "inner join NotaMedica on Expediente.Exp_folio=NotaMedica.Exp_folio ";
            $query2.="Where ";
            if($fechaIni!='' && $fechaFin!=''){
                $query2.= "Not_fechareg Between '".$fechaIni." ' '".$horaIni."' and '".$fechaFin." ' '".$horaFin."' and ";
            }            
            if($compania){$query2.="  Expediente.Cia_clave='".$compania."' and";}            
            if($unidad>=1){$query2.="  Uni_claveActual='".$unidad."' and Exp_cancelado<>1 ";}
            else{$query2.=" Uni_claveActual='".$uni."' and Exp_cancelado<>1 ";}
            $query2.=" order by Exp_folio";             

            $result = $this->_db->query($query2);
            $respuesta2 = $result->fetchAll(PDO::FETCH_OBJ);  


          #----------------- CONSULTA CON FECHA DE SUBSECUENCIA -----------------#
          $query3 = "Select ";
                $query3.=" Expediente.Exp_folio ";
                $query3.=", Exp_completo ";
                $query3.=", Exp_poliza ";
                $query3.=", Exp_siniestro ";
                $query3.=", Exp_reporte ";
                $query3.=", Cia_nombrecorto ";
                $query3.=", Sub_fecha ";
                $query3.=", Sub_hora ";
                $query3.=", Exp_solCancela ";

            $query3.= "From Expediente ";
            $query3.= "Inner Join Compania On Expediente.Cia_clave=Compania.Cia_clave ";
            $query3.= "inner join Subsecuencia on Expediente.Exp_folio=Subsecuencia.Exp_folio ";
            $query3.="Where ";
            if($fechaIni!='' && $fechaFin!=''){
                $query3.= "Sub_fecha Between '".$fechaIni."' and '".$fechaFin."' and ";
                #$query3.= "Sub_fecha>='".$fechaIni."' and Sub_hora>='".$horaIni."' and Sub_fecha<='".$fechaFin."' and Sub_hora<='".$horaFin."' and ";
            }            
            if($compania){$query3.="  Expediente.Cia_clave='".$compania."' and";}            
            if($unidad>=1){$query3.="  Uni_claveActual='".$unidad."' and Exp_cancelado<>1 ";}
            else{$query3.=" Uni_clave='".$uni."' and Exp_cancelado<>1 ";}
            #$query3.=" order by Exp_folio";             

            $result = $this->_db->query($query3);
            $respuesta3 = $result->fetchAll(PDO::FETCH_OBJ);  


          #----------------- CONSULTA CON FECHA DE REHABILITACION -----------------#
          $query4 = "Select ";
                $query4.=" Expediente.Exp_folio ";
                $query4.=", Exp_completo ";
                $query4.=", Exp_poliza ";
                $query4.=", Exp_siniestro ";
                $query4.=", Exp_reporte ";
                $query4.=", Cia_nombrecorto ";
                $query4.=", Rehab_fecha ";
                $query4.=", Exp_solCancela ";

            $query4.= "From Expediente ";
            $query4.= "Inner Join Compania On Expediente.Cia_clave=Compania.Cia_clave ";
            $query4.= "inner join Rehabilitacion on Expediente.Exp_folio=Rehabilitacion.Exp_folio ";
            $query4.="Where ";
            if($fechaIni!='' && $fechaFin!=''){
                $query4.= "Rehab_fecha Between '".$fechaIni." ' '".$horaIni."' and '".$fechaFin." ' '".$horaFin."' and ";
            }            
            if($compania){$query4.="  Expediente.Cia_clave='".$compania."' and";}            
            if($unidad>=1){$query4.="  Expediente.Uni_claveActual='".$unidad."' and Exp_cancelado<>1 ";}
            else{$query4.=" Expediente.Uni_claveActual='".$uni."' and Exp_cancelado<>1 ";}
            $query4.=" order by Exp_folio";             

            $result = $this->_db->query($query4);
            $respuesta4 = $result->fetchAll(PDO::FETCH_OBJ);  


            $respuesta =array('expediente'=>$respuesta1,'notaMedica'=>$respuesta2,'subsecuencia'=>$respuesta3,'rehabilitaciones'=>$respuesta4);



        }catch(Exception $e){
            $respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    

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
    }


//buscamos particulares
    public function buscaFolioParticularMV($datos)
    {
        $fechaIni   =   $datos->fechaIni;
        $fechaFin   =   $datos->fechaFin;
        $folio      =   $datos->folio;
        $folioMV    =   $datos->folioMV;
        $folioRecibo=   $datos->folioRecibo;
        $nombre     =   $datos->nombre;
        $lesionado  =   $datos->lesionado;
        $poliza     =   $datos->poliza;
        $siniestro  =   $datos->siniestro;
        $reporte    =   $datos->reporte;
        $aseguradora=   $datos->aseguradora;
        $fecAten    =   $datos->fecAten;
        $compania   =   $datos->compania;        
        $unidad     =   $datos->unidad;

        try{
            $query = "Select ";
                $query.=" Expediente.Exp_folio ";
                $query.=", Exp_completo ";
                $query.=", Recibo_cont ";
                $query.=", Exp_fechAtencion ";
                $query.=", Exp_fecreg ";
                $query.=", Cia_nombrecorto ";
                $query.=", Recibo_total ";
                $query.=", Recibo_mpago ";
                $query.=", Recibo_facturado ";
                $query.=", Recibo_banco ";
                $query.=", Recibo_terminacion ";
                $query.=", Recibo_cancelado ";

                $query.= "From reciboParticulares 
                          Inner Join Expediente On reciboparticulares.Exp_folio=Expediente.Exp_folio 
                          inner join Compania on Expediente.Cia_clave = Compania.Cia_clave ";

                if ($folioMV || $folioRecibo || $nombre) {
                    if ($folioMV&&$folioRecibo) {
                    $query.="Where Expediente.Exp_folio='".$folioMV."' and reciboParticulares.Recibo_cont='".$folioRecibo."'";
                    } elseif ($folioRecibo&&$nombre) {
                        $query.="Where reciboParticulares.Recibo_cont='".$folioRecibo."' and Expediente.Exp_completo='".$nombre."'";
                    } elseif ($folioMV&&$$nombre) {
                        $query.="Where Expediente.Exp_folio='".$folioMV."' and Expediente.Exp_completo='".$nombre."'";
                    } else $query.="Where Expediente.Exp_folio='".$folioMV."' or reciboParticulares.Recibo_cont='".$folioRecibo."' or 
                                  Expediente.Exp_completo='".$nombre."'";
                } else $query.="Where reciboparticulares.Exp_fechAtencion between '".$fechaIni." 00:00:00' and '".$fechaFin." 23:59:59'";
                

            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            $respuesta = array('respuesta' =>$e->getMessage());    
         }
        return $respuesta;
        print_r($respuesta);
    }


    public function getParticulares()
    {        
        try{
            $query="Select * From Expediente where Exp_folio='".$folioMV."'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);    
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }

     public function cancelarRecibo($datos)
    {        

        $fol      = $datos->folio;
        $motivo   = $datos->motivo;
        $folioSus = $datos->folioSus;
        $obs      = $datos->Obs;
        try{
            $query="UPDATE reciboParticulares SET Recibo_cancelado=1, Recibo_motCancel='".$motivo."', Recibo_obsCancel='".$obs."' where Recibo_cont=".$fol;            
            $result = $this->_db->query($query);
            $respuesta = array('respuesta' =>'exito');       
        }catch(Exception $e){
            //$respuesta=$e->getMessage();
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    
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