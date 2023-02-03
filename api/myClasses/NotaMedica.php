<?php 

require_once "Modelo.php";
/**
*  classe hacer el detalle general de Px
*/
class NotaMedica extends Modelo
{
	
	function __construct()
	{
		 parent::__construct();
	}

	public function setInterpretacioRx($cveEstudio, $interpretacion, $fol)
    {        
    	try{            
            $query="UPDATE RxSolicitados SET Rxs_desc=:Rxs_desc where Rxs_clave=:Rxs_clave";
                $temporal = $this->_db->prepare($query);
                $temporal->bindParam("Rxs_desc", $interpretacion);
                $temporal->bindParam("Rxs_clave", $cveEstudio);   
                if ($temporal->execute()){
                    $query="SELECT Rxs_clave, Rx_nombre, Rxs_Obs, Rxs_desc 
                            FROM RxSolicitados inner Join Rx on Rx.Rx_clave=RxSolicitados.Rx_clave 
                            Where Exp_folio='".$fol."'";
                    $result =  $this->_db->query($query);
                    $respuesta = $result->fetchAll(PDO::FETCH_OBJ);                   
                }else{
                    $respuesta = array('respuesta' => 'error');
                }

        }catch(Exception $e){        	
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }

    public function reporteQualitas()
    {        
        try{            
            $query="SELECT  Expediente.Exp_folio as FOLIO_MV, Exp_RegCompania as F_ELECTRONICO,Exp_completo AS NOMBRE_DEL_PACIENTE,
                      IF (Exp_bitacora REGEXP ('^[0-9]')  = 1,Exp_bitacora ,'' ) as BITACORA,
                      IF (Exp_siniestro REGEXP ('^[0-9]')  = 1,concat('0415',Exp_siniestro) ,'' ) as SINIESTRO,
                      IF (Exp_reporte REGEXP ('^[0-9]')  = 1,concat('0415',Exp_reporte) ,'' ) as REPORTE,
                      LPAD(Exp_poliza,10,'0') AS POLIZA , 
                    IF (Exp_inciso REGEXP ('^[0-9]')  = 1,Exp_inciso ,'' ) as INCISO,
                      CASE RIE_clave
                       WHEN 1 THEN 'GM'
                       WHEN 2 THEN 'RC'
                       WHEN 3 THEN 'RC-Pasajero'
                       WHEN 4 THEN 'RC'
                       WHEN 5 THEN 'RC'
                       WHEN 6 THEN 'GM'
                       WHEN 7 THEN 'RC'
                       END
                       AS 'Riesgo Afectado', 
                      '' AS CODIGO_CAUSA,
                      'SAN ANGEL' AS OFICINA_AJUSTE,
                      Unidad.Uni_claveQ AS CLAVE, Uni_razonsocial AS PROVEEDOR, Not_fechaAcc AS F_SINIESTRO,
                      Exp_sexo AS SEXO, Exp_edad AS EDAD, Exp_fecreg AS F_INGRESO, 'AMBULATORIO CON PAQUETE' AS TRIAGE,
                      '$2,810.00' AS GASTO_EROGADO,
                     ObsNot_diagnosticoRx AS DIAGNOSTICO, 
                      concat( Med_nombre,' ',Med_paterno,' ', Med_materno) AS MEDICO_TRATANTE    
                    FROM Expediente 
                    INNER JOIN Unidad on Expediente.Uni_clave = Unidad.Uni_clave 
                    LEFT JOIN ObsNotaMed on Expediente.Exp_folio = ObsNotaMed.Exp_folio
                    LEFT JOIN NotaMedica on Expediente.Exp_folio = NotaMedica.Exp_folio
                    LEFT JOIN Medico on NotaMedica.Usu_nombre = Medico.Usu_login
                    WHERE Exp_fecreg between '2015-09-01 00:00:00' and '2015-10-16 23:59:59'
                    AND Expediente.Cia_clave = 19
                    AND Unidad.Zon_clave=6
                    AND Expediente.PRO_clave=9
                    AND Unidad.Uni_clave<>8
                    AND Unidad.Uni_clave<>185
                    and Exp_cancelado<>1";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll(PDO::FETCH_OBJ);        
               

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>'error');       
        }    
         return $respuesta;    
    }

    public function guardarMedicamentos($fol,$usr,$uni,$datos,$tipoReceta)
    {        
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $almacen      = $datos->almacen;
        $cantidad     = $datos->cantidad;
        $medic        = $datos->descripcion;
        $idMedic      = $datos->idMedicamento;
        $posologia    = $datos->posologia;
        $presentacion = $datos->presentacion;
        $reserva      = $datos->reserva;
        $existencia   = $datos->existencia;

        try{
            $query="SELECT count(*) as contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and RM_terminada<>1";            
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();        
            $contador = $respuesta['contador'];            
            if($contador<=0){ 
                $query = "SELECT MAX(id_receta)+1 as cont from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];
                if($idReceta==''||$idReceta==NULL){ 
                  $idReceta=1;
                }      
                $query="SELECT cont_receta  FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." order by RM_fecreg desc limit 1"; 
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $contadorRec = $respuesta['cont_receta'];
                if($contadorRec=='') $contadorRec=0;
                $contadorRec=$contadorRec+1;
                $query  = "INSERT INTO RecetaMedica(id_receta, Exp_folio, RM_fecreg, Usu_login, Uni_clave, tipo_receta, cont_receta)
                                            VALUES(".$idReceta.", '".$fol."', now(), '".$usr."', ".$uni.", ".$tipoReceta.", ".$contadorRec.")";
                $result = $this->_db->query($query);
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){ 
                  $idSum=1;
                }

                 $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',1,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)"; 

                $result = $this->_db->query($query);
            }else{ 

                $query  = "SELECT cont_receta AS contTipoRec FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];  
                $query  = "SELECT id_receta AS contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and cont_receta=".$conTipRec." and RM_terminada<>1";               
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];                     
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){
                  $idSum=1;
                }
                $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',1,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)";                            
                $result = $this->_db->query($query);
            }
            $query = "INSERT INTO HistoriaReceta(id_receta,Usu_login,HRE_fecMov,HRE_mov, HRE_descripcion) VALUES(".$idReceta.",'".$usr."',now(),1,'".utf8_encode($medic)."')";
            $result = $this->_db->query($query);
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=1";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();     
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    
    }
    public function guardarMedicamentosSubsecuencia($fol,$usr,$uni,$datos,$tipoReceta,$noSub)
    {        
        $almacen      = $datos->almacen;
        $cantidad     = $datos->cantidad;
        $medic        = $datos->descripcion;
        $idMedic      = $datos->idMedicamento;
        $posologia    = $datos->posologia;
        $presentacion = $datos->presentacion;
        $reserva      = $datos->reserva;
        $existencia   = $datos->existencia;

        try{
            $query="SELECT count(*) as contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and cont_receta=".$noSub." and RM_terminada<>1";            
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();        
            $contador = $respuesta['contador'];            
            if($contador<=0){ 
                $query = "SELECT MAX(id_receta)+1 as cont from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];
                if($idReceta==''||$idReceta==NULL){ 
                  $idReceta=1;
                }                     
                
                $query  = "INSERT INTO RecetaMedica(id_receta, Exp_folio, RM_fecreg, Usu_login, Uni_clave, tipo_receta, cont_receta)
                                            VALUES(".$idReceta.", '".$fol."', now(), '".$usr."', ".$uni.", ".$tipoReceta.", ".$noSub.")";
                $result = $this->_db->query($query);
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){ 
                  $idSum=1;
                }

                 $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',1,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)"; 

                $result = $this->_db->query($query);
            }else{ 

                $query  = "SELECT cont_receta AS contTipoRec FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];  
                $query  = "SELECT id_receta AS contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and cont_receta=".$noSub." and RM_terminada<>1";               
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];                     
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){
                  $idSum=1;
                }
                $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_decode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',1,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)";                            
                $result = $this->_db->query($query);
            }
            $query = "INSERT INTO HistoriaReceta(id_receta,Usu_login,HRE_fecMov,HRE_mov, HRE_descripcion) VALUES(".$idReceta.",'".$usr."',now(),1,'".utf8_decode($medic)."')";
            $result = $this->_db->query($query);
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=1";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();     
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    
    }
    public function eliminarMedicamentosNota($cveItem,$usr)
    {               
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        try{
            $query="SELECT id_receta,NS_descripcion FROM NotaSuministros WHERE NS_id=".$cveItem;            
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();
            $idReceta = $respuesta['id_receta'];
            $medic = $respuesta['NS_descripcion'];
            $query="DELETE FROM NotaSuministros WHERE NS_id=".$cveItem;            
            $result = $this->_db->query($query);
            $query = "INSERT INTO HistoriaReceta(id_receta,Usu_login,HRE_fecMov,HRE_mov, HRE_descripcion) VALUES(".$idReceta.",'".$usr."',now(),2,'".utf8_decode($medic)."')";
            $result = $this->_db->query($query);
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=1";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();                   
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    
    }

    public function guardarOrtesis($fol,$usr,$uni,$datos,$tipoReceta)
    {        
        $almacen      = $datos->almacen;
        $cantidad     = $datos->cantidad;
        $medic        = $datos->descripcion;
        $idMedic      = $datos->idMedicamento;
        $posologia    = $datos->indicaciones;
        //$presentacion = $datos->presentacion;
        $reserva      = $datos->reserva;
        $existencia   = $datos->existencia;       
       /* try{*/
            $query="SELECT count(*) as contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and RM_terminada<>1";            
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();        
            $contador = $respuesta['contador'];             
            if($contador<=0){ 
                $query = "SELECT MAX(id_receta)+1 as cont from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];
                if($idReceta==''||$idReceta==NULL){ 
                  $idReceta=1;
                }                
                $query="SELECT cont_receta FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." order by RM_fecreg desc limit 1"; 
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $contadorRec = $respuesta['cont_receta'];
                if($contadorRec=='') $contadorRec=0;
                $contadorRec=$contadorRec+1;

                $query  = "INSERT INTO RecetaMedica(id_receta, Exp_folio, RM_fecreg, Usu_login, Uni_clave, tipo_receta, cont_receta)
                                            VALUES(".$idReceta.", '".$fol."', now(), '".$usr."', ".$uni.", ".$tipoReceta.", ".$contadorRec.")";
                $result = $this->_db->query($query);
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){ 
                  $idSum=1;
                }
                 $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',2,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)"; 
                           echo $query;
                $result = $this->_db->query($query);
            }else{ 
                 $query  = "SELECT cont_receta AS contTipoRec FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];
                $query  = "SELECT id_receta AS contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and cont_receta=".$conTipRec." and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];                
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){ 
                  $idSum=1;
                }
                $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',2,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)"; 
                $result = $this->_db->query($query);
            }
            $query = "INSERT INTO HistoriaReceta(id_receta,Usu_login,HRE_fecMov,HRE_mov, HRE_descripcion) VALUES(".$idReceta.",'".$usr."',now(),1,'".$medic."')";
            $result = $this->_db->query($query);
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=2";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();     
       /* }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }*/    
         return $respuesta;    
    }

    public function guardarOrtesisSubsecuencia($fol,$usr,$uni,$datos,$tipoReceta,$noSub)
    {        
        $almacen      = $datos->almacen;
        $cantidad     = $datos->cantidad;
        $medic        = $datos->descripcion;
        $idMedic      = $datos->idMedicamento;
        $posologia    = $datos->indicaciones;
        //$presentacion = $datos->presentacion;
        $reserva      = $datos->reserva;
        $existencia   = $datos->existencia;       
       /* try{*/
            $query="SELECT count(*) as contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and cont_receta=".$noSub." and RM_terminada<>1";            
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();        
            $contador = $respuesta['contador'];             
            if($contador<=0){ 
                $query = "SELECT MAX(id_receta)+1 as cont from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];
                if($idReceta==''||$idReceta==NULL){ 
                  $idReceta=1;
                }                               

                $query  = "INSERT INTO RecetaMedica(id_receta, Exp_folio, RM_fecreg, Usu_login, Uni_clave, tipo_receta, cont_receta)
                                            VALUES(".$idReceta.", '".$fol."', now(), '".$usr."', ".$uni.", ".$tipoReceta.", ".$noSub.")";
                $result = $this->_db->query($query);
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){ 
                  $idSum=1;
                }
                 $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',2,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)"; 
                           // echo $query;
                $result = $this->_db->query($query);
            }else{ 
                 $query  = "SELECT cont_receta AS contTipoRec FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];
                $query  = "SELECT id_receta AS contador FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=".$tipoReceta." and cont_receta=".$noSub." and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];                
                $query  = "SELECT MAX(NS_id)+1 AS contadorSum FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];
                if($idSum==''||$idSum==NULL){ 
                  $idSum=1;
                }
                $query  = "INSERT INTO NotaSuministros(NS_id,id_receta,NS_descripcion,NS_cantidad,NS_fecha,NS_tipoDoc,NS_posologia,NS_presentacion,NS_tipoItem,id_reserva,id_almacen,id_existencia,id_item,cont_recetaTipo)
                           VALUES(".$idSum.",".$idReceta.",'".utf8_encode($medic)."',".$cantidad.",now(),1,'".$posologia."','".$presentacion."',2,".$reserva.",".$almacen.",".$existencia.",".$idMedic.",1)"; 
                $result = $this->_db->query($query);
            }
            $query = "INSERT INTO HistoriaReceta(id_receta,Usu_login,HRE_fecMov,HRE_mov, HRE_descripcion) VALUES(".$idReceta.",'".$usr."',now(),1,'".utf8_encode($medic)."')";
            $result = $this->_db->query($query);
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=2";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();     
       /* }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }*/    
         return $respuesta;    
    }

    public function eliminarOrtesisNota($cveItem,$usr)
    {               
        try{
            $query="SELECT id_receta,NS_descripcion FROM NotaSuministros WHERE NS_id=".$cveItem;            
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();
            $idReceta = $respuesta['id_receta'];
            $medic = $respuesta['NS_descripcion'];
            $query="DELETE FROM NotaSuministros WHERE NS_id=".$cveItem;            
            $result = $this->_db->query($query);
            $query = "INSERT INTO HistoriaReceta(id_receta,Usu_login,HRE_fecMov,HRE_mov, HRE_descripcion) VALUES(".$idReceta.",'".$usr."',now(),2,'".utf8_encode($medic)."')";
            $result = $this->_db->query($query);
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=2";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();                   
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta;    
    }

    public function listadoItems($fol,$tipo,$tipoReceta)
    {    
        try{           
            $query="SELECT id_receta FROM RecetaMedica WHERE Exp_folio='".$fol."' AND tipo_receta=".$tipoReceta." and RM_terminada=0";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $idReceta= $rs['id_receta'];
            if($idReceta){
            $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad,id_item FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=".$tipo;
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();     
        }else{
            $respuesta='vacio';
        }
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }   

    public function listadoRecetasSinSurtir($uni)
    {    
        try{                       
            $query= "SELECT NotaSuministros.id_receta AS RECETA,
                            RecetaMedica.Exp_folio AS FOLIO,
                            RM_fecreg as FECHA_RECETA, 
                            count(NotaSuministros.id_receta) AS ITEMS_FALTANTES, 
                            TipoReceta.TIR_nombre AS TIPO_RECETA
                    from NotaSuministros 
                    inner join RecetaMedica on NotaSuministros.id_receta = RecetaMedica.id_receta
                    inner join TipoReceta on RecetaMedica.tipo_receta = TipoReceta.TIR_id
                    inner join Expediente on RecetaMedica.Exp_folio = Expediente.Exp_folio
                    WHERE RecetaMedica.Uni_clave=".$uni." and NS_cancelado<>1 and NS_surtida=0
                    AND RM_fecreg >= '2019-01-01 00:00:00'
                    AND RecetaMedica.Uni_clave = Expediente.Uni_ClaveActual
                    GROUP BY NotaSuministros.id_receta";

            $result = $this->_db->query($query);
            $recetas = $result->fetchAll();   

            for ($i=0; $i < sizeof($recetas); $i++) { 
                // return $recetas[$i];
                $queryItems = "SELECT NS_descripcion, NS_cantidad, NS_fecha, NS_surtida, id_reserva, id_item from NotaSuministros where id_receta = ".$recetas[$i]['RECETA'];
                $result = $this->_db->query($queryItems);
                $items = $result->fetchAll();

                $recetas[$i]['ITEMS'] = $items;
            }

            return $recetas;

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }   

    public function checaAddendum($fol)
    {    
        try{           
            $query="SELECT count(*) as contador FROM Addendum WHERE Exp_folio='".$fol."' AND Add_tipoDoc=3 and Add_impreso=0";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $respuesta= $rs['contador'];
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function getItemsCortaEstancia($fol){    
        try{           
            $query="SELECT id_receta from RecetaMedica 
                    where Exp_folio='".$fol."' 
                    order by cont_receta desc limit 1;";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $idReceta= $rs['id_receta'];

            $query="SELECT * from NotaSuministros 
                    where id_receta=".$idReceta."
                    and NS_cancelado=0
                    order by NS_cortaEstancia desc";
            $result = $this->_db->query($query);
            $rs = $result->fetchAll(PDO::FETCH_ASSOC);
            $respuesta=$rs;
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function saveInsumosCortaEstancia($fol,$usr,$uni,$tipoReceta,$datos) {
        $almacen        = $datos->almacen;
        $cantidad       = $datos->cantidad;
        $medic          = $datos->descripcion;
        $idMedic        = $datos->idMedicamento;
        $posologia      = $datos->posologia;
        $presentacion   = $datos->presentacion;
        $reserva        = $datos->reserva;
        $existencia     = $datos->existencia;
        $indicaciones   = $datos->indicaciones;
        $tipoItem       = $datos->tipoItem;

        $posologia = $posologia." | ".$indicaciones;

        try{           
            $query="SELECT count(*) as contador 
                    FROM RecetaMedica 
                    WHERE Exp_folio='".$fol."' 
                    and tipo_receta=".$tipoReceta." 
                    and RM_terminada<>1";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador= $rs['contador'];

            if ($contador<=0) {
                $query = "SELECT MAX(id_receta)+1 as cont 
                            from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];

                if ($idReceta=='' || $idReceta==NULL){ 
                    $idReceta=1;
                };

                $query="SELECT cont_receta 
                        FROM RecetaMedica 
                        WHERE Exp_folio='".$fol."' 
                        and tipo_receta=".$tipoReceta." 
                        order by RM_fecreg desc limit 1"; 
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $contadorRec = $respuesta['cont_receta'];

                if ($contadorRec=='') {
                    $contadorRec=0;
                };

                $contadorRec=$contadorRec+1;

                $query = "INSERT INTO RecetaMedica (id_receta, 
                                                    Exp_folio, 
                                                    RM_fecreg, 
                                                    Usu_login, 
                                                    Uni_clave, 
                                                    tipo_receta, 
                                                    cont_receta)
                            VALUES (".$idReceta.",
                                    '".$fol."', 
                                    now(), 
                                    '".$usr."', 
                                    ".$uni.", 
                                    ".$tipoReceta.", 
                                    ".$contadorRec.")";
                $result = $this->_db->query($query);

                $query  = "SELECT MAX(NS_id)+1 AS contadorSum 
                            FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();
                $idSum  = $rs['contadorSum'];
                
                if ($idSum==''||$idSum==NULL){ 
                    $idSum=1;
                };

                $query = "INSERT INTO NotaSuministros (NS_id, id_receta, NS_descripcion, NS_cantidad, 
                                                        NS_fecha, NS_tipoDoc, NS_posologia, NS_presentacion,
                                                        NS_tipoItem, id_reserva, id_almacen, id_existencia, 
                                                        id_item, cont_recetaTipo, NS_cortaEstancia)
                            VALUES (".$idSum.", ".$idReceta.", '".utf8_encode($medic)."', ".$cantidad.",
                                    now(), 1, '".$posologia."', '".$presentacion."',
                                    '".$tipoItem."', ".$reserva.", ".$almacen.", ".$existencia.", 
                                    ".$idMedic.", 1, 1)"; 

                $result = $this->_db->query($query);
            } else {
                $query = "SELECT cont_receta AS contTipoRec 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=".$tipoReceta." 
                            and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];

                $query = "SELECT id_receta AS contador 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=".$tipoReceta." 
                            and cont_receta=".$conTipRec." 
                            and RM_terminada<>1";               
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];

                $query = "SELECT MAX(NS_id)+1 AS contadorSum 
                            FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];

                if($idSum==''||$idSum==NULL){
                  $idSum=1;
                }

                $query = "INSERT INTO NotaSuministros (NS_id, id_receta, NS_descripcion, NS_cantidad,
                                                        NS_fecha, NS_tipoDoc, NS_posologia, NS_presentacion,
                                                        NS_tipoItem, id_reserva, id_almacen, id_existencia,
                                                        id_item, cont_recetaTipo, NS_cortaEstancia)
                           VALUES (".$idSum.", ".$idReceta.", '".utf8_encode($medic)."', ".$cantidad.",
                                    now(), 1, '".$posologia."', '".$presentacion."',
                                    1, ".$reserva.", ".$almacen.", ".$existencia.",
                                    ".$idMedic.", 1, 1)";                            
                $result = $this->_db->query($query);
            };

            $query = "INSERT INTO HistoriaReceta (id_receta,
                                                    Usu_login,
                                                    HRE_fecMov,
                                                    HRE_mov, 
                                                    HRE_descripcion) 
                        VALUES (".$idReceta.",
                                '".$usr."',
                                now(),
                                1,
                                '".utf8_encode($medic)."')";
            $result = $this->_db->query($query);
            // $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=1";
            // $result = $this->_db->query($query);
            // $respuesta = $result->fetchAll();     
            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function eliminaInsumosCortaEstancia($cveItem, $usr){               
        try{
            $query="SELECT id_receta,NS_descripcion 
                    FROM NotaSuministros 
                    WHERE NS_id=".$cveItem;
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();
            $idReceta = $respuesta['id_receta'];
            $medic = $respuesta['NS_descripcion'];

            $query="DELETE FROM NotaSuministros 
                    WHERE NS_id=".$cveItem;            
            $result = $this->_db->query($query);

            $query = "INSERT INTO HistoriaReceta (id_receta, 
                                                    Usu_login, 
                                                    HRE_fecMov,
                                                    HRE_mov, 
                                                    HRE_descripcion) 
                        VALUES ('".$idReceta."', 
                                '".$usr."',
                                now(),
                                2,
                                '".utf8_encode($medic)."')";
            $result = $this->_db->query($query);

            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
        return $respuesta;
    }


    public function saveIndicacionesCE($fol, $usr, $uni, $datos){
        $indicacion = $datos->indicacion;
        $obs        = $datos->obs;

        $indicacion=0;

        try{           
            $query="SELECT count(*) as contador 
                    FROM RecetaMedica 
                    WHERE Exp_folio='".$fol."' 
                    and tipo_receta=4
                    and RM_terminada<>1";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador= $rs['contador'];

            if ($contador<=0) {
                $query = "SELECT MAX(id_receta)+1 as cont 
                            from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];

                if ($idReceta=='' || $idReceta==NULL){ 
                    $idReceta=1;
                };

                $query="SELECT cont_receta 
                        FROM RecetaMedica 
                        WHERE Exp_folio='".$fol."' 
                        and tipo_receta=4
                        order by RM_fecreg desc limit 1"; 
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $contadorRec = $respuesta['cont_receta'];

                if ($contadorRec=='') {
                    $contadorRec=0;
                };

                $contadorRec=$contadorRec+1;

                $query = "INSERT INTO RecetaMedica (id_receta, 
                                                    Exp_folio, 
                                                    RM_fecreg, 
                                                    Usu_login, 
                                                    Uni_clave, 
                                                    tipo_receta, 
                                                    cont_receta)
                            VALUES (".$idReceta.",
                                    '".$fol."', 
                                    now(), 
                                    '".$usr."', 
                                    ".$uni.", 
                                    4,
                                    ".$contadorRec.")";
                $result = $this->_db->query($query);

                // $query  = "SELECT MAX(NS_id)+1 AS contadorSum 
                //             FROM NotaSuministros";
                // $result = $this->_db->query($query);
                // $rs     = $result->fetch();
                // $idSum  = $rs['contadorSum'];
                
                // if ($idSum==''||$idSum==NULL){ 
                //     $idSum=1;
                // };

                $query = "INSERT INTO NotaIndAlternativa (Exp_folio, 
                                                            Ind_clave, 
                                                            Nind_obs, 
                                                            id_receta)
                            VALUES ('".$fol."',
                                    ".$indicacion.",
                                    '".$obs."',
                                    '".$idReceta."')";
                $result = $this->_db->query($query);
            } else {
                $query = "SELECT cont_receta AS contTipoRec 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=4
                            and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];

                $query = "SELECT id_receta AS contador 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=4
                            and cont_receta=".$conTipRec." 
                            and RM_terminada<>1";               
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];

                // $query = "SELECT MAX(NS_id)+1 AS contadorSum 
                //             FROM NotaSuministros";
                // $result = $this->_db->query($query);
                // $rs     = $result->fetch();   
                // $idSum  = $rs['contadorSum'];

                // if($idSum==''||$idSum==NULL){
                //   $idSum=1;
                // }

                $query = "INSERT INTO NotaIndAlternativa (Exp_folio, 
                                                            Ind_clave, 
                                                            Nind_obs, 
                                                            id_receta)
                            VALUES ('".$fol."',
                                    ".$indicacion.",
                                    '".$obs."',
                                    '".$idReceta."')";
                $result = $this->_db->query($query);
            };

            // $query = "INSERT INTO HistoriaReceta (id_receta,
            //                                         Usu_login,
            //                                         HRE_fecMov,
            //                                         HRE_mov, 
            //                                         HRE_descripcion) 
            //             VALUES (".$idReceta.",
            //                     '".$usr."',
            //                     now(),
            //                     1,
            //                     '".utf8_encode($medic)."')";
            // $result = $this->_db->query($query);
            // $query ="SELECT NS_id, NS_descripcion, NS_posologia,NS_presentacion,id_reserva,NS_cantidad FROM NotaSuministros WHERE  id_receta=".$idReceta." and NS_tipoItem=1";
            // $result = $this->_db->query($query);
            // $respuesta = $result->fetchAll();     
            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function getIndicacionesCE($fol){    
        try{           
            $query="SELECT id_receta from RecetaMedica 
                    where Exp_folio='".$fol."' 
                    and RM_terminada<>1
                    order by cont_receta desc limit 1;";
            $result = $this->_db->query($query);
            $rs = $result->fetch();

            // if ($rs['id_receta']) {
                $idReceta= $rs['id_receta'];

                $query = "SELECT * from NotaIndAlternativa 
                            where Exp_folio='".$fol."' 
                            AND id_receta='".$idReceta."'";

                $result = $this->_db->query($query);
                $rs = $result->fetchAll(PDO::FETCH_ASSOC);
                $respuesta=$rs;
            // } else{
            //     $respuesta='nada';
            // }
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function deleteIndicacionCE($fol, $idIndicacion){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        
        try{
            $query = "DELETE FROM NotaIndAlternativa 
                        WHERE Exp_folio='".$fol."'
                        and Nind_clave='".$idIndicacion."'";
            $result = $this->_db->query($query);

            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
        return $respuesta;
    }

    public function checaRecetaComplementaria($fol)
    {    
        try{           
           
            $query="SELECT id_receta FROM RecetaMedica WHERE Exp_folio='".$fol."' AND tipo_receta=3";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();

            return $respuesta;            
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }



    public function guardaItemsParticulares($fol,$usr,$uni,$tipoReceta,$datos) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $almacen        = $datos->almacen;
        $cantidad       = $datos->cantidad;
        $descripcion    = $datos->descripcion;
        $idMedic        = $datos->idMedicamento;
        $posologia      = $datos->posologia;
        $presentacion   = $datos->presentacion;
        $reserva        = $datos->reserva;
        $existencia     = $datos->existencia;
        $indicaciones   = $datos->indicaciones;
        $tipoItem       = $datos->tipoItem;

        if ($posologia != '') {
            $posologia = $posologia." | ".$indicaciones;
        } elseif($posologia==''){
            $posologia = $indicaciones;
        }
        

        try{           
            $query="SELECT count(*) as contador 
                    FROM RecetaMedica 
                    WHERE Exp_folio='".$fol."' 
                    and tipo_receta=".$tipoReceta." 
                    and RM_terminada<>1";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador= $rs['contador'];

            if ($contador<=0) {
                $query = "SELECT MAX(id_receta)+1 as cont 
                            from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];

                if ($idReceta=='' || $idReceta==NULL){ 
                    $idReceta=1;
                };

                $query="SELECT cont_receta 
                        FROM RecetaMedica 
                        WHERE Exp_folio='".$fol."' 
                        and tipo_receta=".$tipoReceta." 
                        order by RM_fecreg desc limit 1"; 
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $contadorRec = $respuesta['cont_receta'];

                if ($contadorRec=='') {
                    $contadorRec=0;
                };

                $contadorRec=$contadorRec+1;

                $query = "INSERT INTO RecetaMedica (id_receta, 
                                                    Exp_folio, 
                                                    RM_fecreg, 
                                                    Usu_login, 
                                                    Uni_clave, 
                                                    tipo_receta, 
                                                    cont_receta)
                            VALUES (".$idReceta.",
                                    '".$fol."', 
                                    now(), 
                                    '".$usr."', 
                                    ".$uni.", 
                                    ".$tipoReceta.", 
                                    ".$contadorRec.")";
                $result = $this->_db->query($query);

                $query  = "SELECT MAX(NS_id)+1 AS contadorSum 
                            FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();
                $idSum  = $rs['contadorSum'];
                
                if ($idSum==''||$idSum==NULL){ 
                    $idSum=1;
                };

                $query = "INSERT INTO NotaSuministros (NS_id, id_receta, NS_descripcion, NS_cantidad, 
                                                        NS_fecha, NS_tipoDoc, NS_posologia, NS_presentacion,
                                                        NS_tipoItem, id_reserva, id_almacen, id_existencia, 
                                                        id_item, cont_recetaTipo, NS_cortaEstancia)
                            VALUES (".$idSum.", ".$idReceta.", '".utf8_encode($descripcion)."', ".$cantidad.",
                                    now(), 1, '".$posologia."', '".$presentacion."',
                                    '".$tipoItem."', ".$reserva.", ".$almacen.", ".$existencia.", 
                                    ".$idMedic.", 1, 0)"; 

                $result = $this->_db->query($query);
            } else {
                $query = "SELECT cont_receta AS contTipoRec 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=".$tipoReceta." 
                            and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];

                $query = "SELECT id_receta AS contador 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=".$tipoReceta." 
                            and cont_receta=".$conTipRec." 
                            and RM_terminada<>1";               
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];

                $query = "SELECT MAX(NS_id)+1 AS contadorSum 
                            FROM NotaSuministros";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idSum  = $rs['contadorSum'];

                if($idSum==''||$idSum==NULL){
                  $idSum=1;
                }

                $query = "INSERT INTO NotaSuministros (NS_id, id_receta, NS_descripcion, NS_cantidad,
                                                        NS_fecha, NS_tipoDoc, NS_posologia, NS_presentacion,
                                                        NS_tipoItem, id_reserva, id_almacen, id_existencia,
                                                        id_item, cont_recetaTipo, NS_cortaEstancia)
                           VALUES (".$idSum.", ".$idReceta.", '".utf8_encode($descripcion)."', ".$cantidad.",
                                    now(), 1, '".$posologia."', '".$presentacion."',
                                    1, ".$reserva.", ".$almacen.", ".$existencia.",
                                    ".$idMedic.", 1, 0)";                            
                $result = $this->_db->query($query);
            };

            $query = "INSERT INTO HistoriaReceta (id_receta,
                                                    Usu_login,
                                                    HRE_fecMov,
                                                    HRE_mov, 
                                                    HRE_descripcion) 
                        VALUES (".$idReceta.",
                                '".$usr."',
                                now(),
                                1,
                                '".utf8_encode($descripcion)."')";
            $result = $this->_db->query($query);
            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function getItemsRecetaInterna($fol){    
        try{           
            $query="SELECT id_receta from RecetaMedica 
                    where Exp_folio='".$fol."' 
                    and tipo_receta=5
                    order by cont_receta desc limit 1;";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $idReceta= $rs['id_receta'];

            $query="SELECT * from NotaSuministros 
                    where id_receta='".$idReceta."'
                    and NS_cancelado=0";
            $result = $this->_db->query($query);
            $rs = $result->fetchAll(PDO::FETCH_ASSOC);
            $respuesta=$rs;
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function eliminaItemParticulares($cveItem, $usr){
        try{
            $query="SELECT id_receta,NS_descripcion 
                    FROM NotaSuministros 
                    WHERE NS_id=".$cveItem;
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();
            $idReceta = $respuesta['id_receta'];
            $medic = $respuesta['NS_descripcion'];

            $query="DELETE FROM NotaSuministros 
                    WHERE NS_id=".$cveItem;            
            $result = $this->_db->query($query);

            $query = "INSERT INTO HistoriaReceta (id_receta, 
                                                    Usu_login, 
                                                    HRE_fecMov,
                                                    HRE_mov, 
                                                    HRE_descripcion) 
                        VALUES ('".$idReceta."', 
                                '".$usr."',
                                now(),
                                2,
                                '".utf8_encode($medic)."')";
            $result = $this->_db->query($query);

            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
        return $respuesta;
    }


    public function saveIndicacionesParticulares($fol, $usr, $uni, $datos){
        $indicacion = $datos->indicacion;
        $obs        = $datos->obs;

        $indicacion=0;

        try{           
            $query="SELECT count(*) as contador 
                    FROM RecetaMedica 
                    WHERE Exp_folio='".$fol."' 
                    and tipo_receta=5
                    and RM_terminada<>1";
            $result = $this->_db->query($query);
            $rs = $result->fetch();
            $contador= $rs['contador'];

            if ($contador<=0) {
                $query = "SELECT MAX(id_receta)+1 as cont 
                            from RecetaMedica";
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $idReceta = $respuesta['cont'];

                if ($idReceta=='' || $idReceta==NULL){ 
                    $idReceta=1;
                };

                $query="SELECT cont_receta 
                        FROM RecetaMedica 
                        WHERE Exp_folio='".$fol."' 
                        and tipo_receta=5
                        order by RM_fecreg desc limit 1"; 
                $result = $this->_db->query($query);
                $respuesta = $result->fetch();        
                $contadorRec = $respuesta['cont_receta'];

                if ($contadorRec=='') {
                    $contadorRec=0;
                };

                $contadorRec=$contadorRec+1;

                $query = "INSERT INTO RecetaMedica (id_receta, 
                                                    Exp_folio, 
                                                    RM_fecreg, 
                                                    Usu_login, 
                                                    Uni_clave, 
                                                    tipo_receta, 
                                                    cont_receta)
                            VALUES (".$idReceta.",
                                    '".$fol."', 
                                    now(), 
                                    '".$usr."', 
                                    ".$uni.", 
                                    5,
                                    ".$contadorRec.")";
                $result = $this->_db->query($query);


                $query = "INSERT INTO NotaIndAlternativa (Exp_folio, 
                                                            Ind_clave, 
                                                            Nind_obs, 
                                                            id_receta)
                            VALUES ('".$fol."',
                                    ".$indicacion.",
                                    '".$obs."',
                                    '".$idReceta."')";
                $result = $this->_db->query($query);
            } else {
                $query = "SELECT cont_receta AS contTipoRec 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=5
                            and RM_terminada<>1";
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $conTipRec  = $rs['contTipoRec'];

                $query = "SELECT id_receta AS contador 
                            FROM RecetaMedica 
                            WHERE Exp_folio='".$fol."' 
                            and tipo_receta=5
                            and cont_receta=".$conTipRec." 
                            and RM_terminada<>1";               
                $result = $this->_db->query($query);
                $rs     = $result->fetch();   
                $idReceta  = $rs['contador'];

                $query = "INSERT INTO NotaIndAlternativa (Exp_folio, 
                                                            Ind_clave, 
                                                            Nind_obs, 
                                                            id_receta)
                            VALUES ('".$fol."',
                                    ".$indicacion.",
                                    '".$obs."',
                                    '".$idReceta."')";
                $result = $this->_db->query($query);
            };
  
            $respuesta='exito';

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function getIndicacionesParticulares($fol){    
        try{           
            $query="SELECT id_receta from RecetaMedica 
                    where Exp_folio='".$fol."' 
                    and RM_terminada<>1
                    and tipo_receta=5
                    order by cont_receta desc limit 1;";
            $result = $this->_db->query($query);
            $rs = $result->fetch();

            // if ($rs['id_receta']) {
                $idReceta= $rs['id_receta'];

                $query = "SELECT * from NotaIndAlternativa 
                            where Exp_folio='".$fol."' 
                            AND id_receta='".$idReceta."'";

                $result = $this->_db->query($query);
                $rs = $result->fetchAll(PDO::FETCH_ASSOC);
                $respuesta=$rs;
            // } else{
            //     $respuesta='nada';
            // }
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }



    public function saveItemRE($fol,$usr,$uni,$datos) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $item           = $datos->item;
        $indicacion     = $datos->indicacion;
        $idReceta       = $datos->idReceta;
        $presentacion   = $datos->presentacion;

        try{
            //BUSCAMOS SI HAY RECETA ABIERTA PARA EL PACIENTE
            $query = "SELECT max(RE_idReceta) as RE_idReceta from recetaExterna 
                        where Exp_folio='".$fol."' and (RE_terminada<>1 || RE_terminada is null)";
                $result     = $this->_db->query($query);
                $rs         = $result->fetch();   
                $idReceta   = $rs['RE_idReceta'];

            //SI NO HAY RECETA ABIERTA CREAMOS UNA NUEVA
            if ($idReceta=='' || $idReceta==null) {
                    $query = "INSERT INTO recetaExterna (Exp_folio, 
                                                         RE_fecreg, 
                                                         Usr_login, 
                                                         Uni_clave) 
                                VALUES ('".$fol."',
                                        now(),
                                        '".$usr."',
                                        '".$uni."')";
                    $result = $this->_db->query($query);

                    //BUSCAMOS EL ID DE LA RECETA RECIEN CREADA
                    $query = "SELECT max(RE_idReceta) as RE_idReceta from recetaExterna 
                                where Exp_folio='".$fol."' and (RE_terminada<>1 || RE_terminada is null)";
                        $result     = $this->_db->query($query);
                        $rs         = $result->fetch();   
                        $idReceta   = $rs['RE_idReceta'];
            }

            //INSERTAMOS LOS ITEMS CORRESPONDIENTES A LA RECETA
            if(empty($presentacion)){
                $query = "INSERT into recetaExternaItems (idReceta, 
                                                      REI_nombreItem, 
                                                      REI_indicaciones, 
                                                      REI_fecreg) 
                        values ('".$idReceta."',
                                '".$item."',
                                '".$indicacion."',
                                now())";
            }else{
                $query = "INSERT into recetaExternaItems (idReceta, 
                                                      REI_nombreItem,
                                                      REI_presentacion,
                                                      REI_indicaciones, 
                                                      REI_fecreg) 
                        values ('".$idReceta."',
                                '".$item."', '$presentacion',
                                '".$indicacion."',
                                now())";

            }
            $result = $this->_db->query($query);
            $respuesta="exito";


        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function getItemsRecetaExterna($fol){    
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: *");

        try{           
            $query="SELECT max(RE_idReceta) as RE_idReceta from recetaExterna 
                    where Exp_folio='".$fol."' 
                    and (RE_terminada<>1 || RE_terminada is null);";
            $result     = $this->_db->query($query);
            $rs         = $result->fetch();   
            $idReceta   = $rs['RE_idReceta'];

            if ($idReceta=='' || $idReceta==null) {
                $respuesta="no existe";
            } else{
                $query = "SELECT * from recetaExternaItems
                            where idReceta='".$idReceta."'";
                $result = $this->_db->query($query);
                $rs = $result->fetchAll(PDO::FETCH_ASSOC);
                $respuesta=$rs;
            }

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function eliminaItemRE($fol, $idItemExt){    
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        try{           
            $query="DELETE from recetaExternaItems 
                    where REI_id='".$idItemExt."';";
                    
            $result = $this->_db->query($query);
            $respuesta="exito";

        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }


    public function getRecPart($fol)
    {    
        try{           
           
            $query="SELECT id_receta, concat('interna') as tipo 
                        FROM RecetaMedica WHERE Exp_folio='".$fol."' AND tipo_receta=5
                    union
                    SELECT RE_idReceta, concat('externa') as tipo 
                        from recetaExterna where Exp_folio='".$fol."'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetchAll();

            return $respuesta;            
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function getMedico($usr)
    {    
        try{           
           
            $query="SELECT Med_tipo
                        from Medico where Usu_login='".$usr."'";
            $result = $this->_db->query($query);
            $respuesta = $result->fetch();

            return $respuesta;            
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function getDatosReceta($fol)
    {    
        try{        

            $datos = array();

            $query="SELECT id_receta, Uni_clave FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=1";
            $result = $this->_db->query($query);
            $res = $result->fetch();
            $idReceta = $res['id_receta'];
            $unidad = $res['Uni_clave'];


            $query1="SELECT id_receta, Uni_clave FROM RecetaMedica WHERE Exp_folio='".$fol."' and tipo_receta=1 and RM_terminada=0";
            $result1 = $this->_db->query($query1);
            $listaRecetas = $result1->fetch();
            

            if($idReceta){
            
            $query="SELECT id_item as idItem, NS_cantidad as cantidad, NS_descripcion as sustancia, NS_posologia as posologia, NS_presentacion as presentacion FROM NotaSuministros WHERE id_receta=".$idReceta."
              and NS_tipoItem=1";
            $result = $this->_db->query($query);
            $listaMed = $result->fetchAll();            

            $query="SELECT id_item as idItem, NS_cantidad as cantidad, NS_descripcion as sustancia, NS_posologia as posologia, NS_presentacion as presentacion FROM NotaSuministros WHERE id_receta=".$idReceta."
              and NS_tipoItem=2";
            $result = $this->_db->query($query);
            $listaOrt = $result->fetchAll();
            }else{
               $listaOrt=''; 
               $listaMed='';
            }

            $query="Select Nind_obs from NotaInd where Exp_folio='".$fol."'";
            $result = $this->_db->query($query);            
            $listaInd = $result->fetchAll();

           
            $datos['Med'] = $listaMed;
            $datos['Ort'] = $listaOrt;
            $datos['Ind'] = $listaInd;   
            $datos['Rec'] = $listaRecetas;    
            
            return $datos;            
            
        }catch(Exception $e){           
            $respuesta = array('respuesta' =>$e->getMessage());       
        }    
         return $respuesta; 
    }

    public function guardaEjerciciosMovil($fol, $datos){
        $zona= $datos[0];
        $refiere= $datos[1];
        $escala= $datos[2];
        if(empty($zona)){
            $zona=24;
        }
        try{
            $query="SELECT if(MAX(Ref_clave)+1 is null ||MAX(Ref_clave)+1='', 1, MAX(Ref_clave)+1) Maximo FROM EjerciciosReferenciaPx";
            $result = $this->_db->query($query);
            $row = $result->fetch();
            $clave=$row['Maximo'];

            $query1="INSERT INTO EjerciciosReferenciaPx VALUES ($clave, '$fol', $zona, '$refiere', $escala)";
            $result1 = $this->_db->query($query1);
            $respuesta = array('respuesta' =>'exito');
        }catch(Exception $e){
            $respuesta = array('respuesta' =>$e->getMessage());
        }
        
        return $respuesta; 
    }

}
?>