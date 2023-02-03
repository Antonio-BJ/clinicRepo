<?php 

require_once "Modelo.php";
// clase para el envío de correos

/**
*  clase para el control de incidencias 
*/
class NotaMedicaParticulares extends Modelo
{	   
	function __construct()
	{
		 parent::__construct();         
	}
/************************************************************************************************************************************/
/************************       método que guarda motivos de consulta en la nota medica para particulares             **************/
/************************************************************************************************************************************/
    public function setNotaMotivos($folio, $datos,$usr)
    {        
        $tipo = $datos->motivostring;
        $descripcion= $datos->descripcion;
        $arrayTipo=explode(",", $tipo);
        $prevencion=0;
        $enfermedad=0;
        $accidente=0;
        foreach ($arrayTipo as $key) {
            if($key==1){
                $prevencion=1;
            }elseif ($key==2) {
                $enfermedad=1;
            }elseif ($key==3) {
                $accidente=1;
            }
        }
        $fecha = date("Y-m-d H:i:s");
        $query="SELECT * FROM NotaParticularesComplemento WHERE Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $rs = $result->fetch();
        try{
            if($rs){                                
                $query="UPDATE NotaParticularesComplemento SET Not_motivo=0,Not_motDescripcion='".$descripcion."',Not_Enfermedad=".$enfermedad.",Not_Accidente=".$accidente.",Not_Prevencion=".$prevencion.",Not_fechaReg='".$fecha."',Usu_login='".$usr."' where Exp_folio='".$folio."'";    
                $result = $this->_db->query($query);
                $query="UPDATE NotaMedica SET Usu_nombre='".$usr."', Not_fechaReg='".$fecha."', Not_Estatus=3 WHERE Exp_folio='".$folio."'";
                $result = $this->_db->query($query);
            }else{
                $query="Insert into NotaParticularesComplemento(Exp_folio, Not_motivo, Not_motDescripcion, Not_Enfermedad, Not_Accidente, Not_Prevencion, Not_fechaReg,Usu_login)
                                   Values('".$folio."',0,'".$descripcion."',".$enfermedad.",".$accidente.",".$prevencion.",'".$fecha."','".$usr."')";
                $result = $this->_db->query($query);
                $query="INSERT INTO NotaMedica(Exp_folio,Usu_nombre, Not_fechaReg, Not_Estatus)
                                    VALUES('".$folio."','".$usr."','".$fecha."',3)";            
                $result = $this->_db->query($query);
            }
            echo 'exito';
        }catch(Exception $e){
            echo 'error';
        }        
    }

/************************************************************************************************************************************/
/************************       método que guarda padecimiento en la nota medica para particulares                     **************/
/************************************************************************************************************************************/
    public function setNotaPadecimiento($folio, $datos,$usr)
    {        
        $tipo = $datos->tipoPadString;
        $padecimiento= $datos->padecimiento;
        $evolucion= $datos->evolucion;
        $codigo= $datos->codigo;
        $arrayTipo=explode(",", $tipo);
        $congenito=0;
        $adquirido=0;
        $agudo=0;
        $cronico=0;
        $intEvolucion=0;
        foreach ($arrayTipo as $key) {
            if($key==1){
                $congenito=1;
            }elseif ($key==2) {
                $adquirido=1;
            }elseif ($key==3) {
                $agudo=1;            
            }elseif ($key==4) {
                $cronico=1;
            }
        }
        switch ($evolucion) {
            case 'opcion1':
                $intEvolucion=1;
                break;
            case 'opcion2':
                $intEvolucion=2;
                break;
            case 'opcion3':
                $intEvolucion=3;
            break;
            default:
                break;
        }
        $fecha = date("Y-m-d H:i:s");
        /*$query="SELECT * FROM NotaParticularesComplemento WHERE Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $rs = $result->fetch();*/
        try{
           // if($rs){                                
                $query="UPDATE NotaParticularesComplemento SET Not_padecimiento='".$padecimiento."',Not_padCongenito=".$congenito.",Not_padAdquirido=".$adquirido.",Not_padAgudo=".$agudo.",Not_padCronico=".$cronico.",Not_evolucion=".$intEvolucion.",Not_codigo='".$codigo."' where Exp_folio='".$folio."'";    
                $result = $this->_db->query($query);
                $query="UPDATE NotaMedica SET Not_Estatus=4 WHERE Exp_folio='".$folio."'";
                $result = $this->_db->query($query);
            /*}else{
                $query="Insert into NotaParticularesComplemento(Exp_folio, Not_motivo, Not_motDescripcion, Not_Enfermedad, Not_Accidente, Not_Prevencion, Not_fechaReg,Usu_login)
                                   Values('".$folio."',0,'".$descripcion."',".$enfermedad.",".$accidente.",".$prevencion.",'".$fecha."','".$usr."')";
                $result = $this->_db->query($query);
                $query="INSERT INTO NotaMedica(Exp_folio,Usu_nombre, Not_fechaReg, Not_Estatus)
                                    VALUES('".$folio."','".$usr."','".$fecha."',3)";            
                $result = $this->_db->query($query);
            }*/
            echo 'exito';
        }catch(Exception $e){
            echo $e->getMessage();
        }        
    }

/************************************************************************************************************************************/
/************************       método que guarda motivos de consulta en la nota medica para particulares             **************/
/************************************************************************************************************************************/
    public function getRecibos($folio)
    {        
        
        $query="SELECT *,IF(FAP_folRec is null,'Recibo no facturado','Recibo Facturado') as estatus, 
                        if(FAP_global = 1,'Publico en General','Cliente') as tipoFact FROM reciboParticulares 
                LEFT JOIN FacturaProveedor on reciboParticulares.Recibo_cont = FacturaProveedor.FAP_folRec
                WHERE Exp_folio='".$folio."'";
        $result = $this->_db->query($query);
        $rs = $result->fetchAll(PDO::FETCH_OBJ);
        return $rs;
                
    }


/************************************************************************************************************************************/
/************************       método de envío de correo de incidencia dependiendo la severidad y el tipo             **************/
/************************************************************************************************************************************/

   /* public function sendIncidencia($usuario, $unidad, $datos)
    {
        
        $tipo= $datos->tipo;
        $severidad= $datos->severidad;
        $observaciones= $datos->observaciones;
        $fecha = date('d-m-Y');
        $hora  = date('h:i a');


        switch ($tipo) {
            case '1':
                $nombreTipo='Equipo Rx';
                $correos='egutierrez@medicavial.com.mx, sistemasrep2@medicavial.com.mx';
                break;
            case '2':
                $nombreTipo='Personal';
                break;
            case '3':
                $nombreTipo='Equipo de Cómputo';
                break;
            case '4':
                $nombreTipo='Sistema';
                break;
            case '5':
                $nombreTipo='Medicamentos';
                break;
            case '6':
                $nombreTipo='Ortesis';
                break;              
            case '7':
                $nombreTipo='Médico';
                break;              
            case '8':
                $nombreTipo='Cabina';
                break;              
            case '9':
                $nombreTipo='Otro';
                break;                    
        }    

        switch ($severidad) {
            case '1':
                $severidadNom='baja';
                break;
            case '2':
                $severidadNom='regular';
                break;
            case '3':
                $severidadNom='alta';
                break;        
        }
    
        $query="select Usu_nombre, Uni_nombrecorto from Usuario
                inner join Unidad on Unidad.Uni_clave=Usuario.Uni_clave 
                where Usuario.Usu_login='".$usuario."'";
        $result = $this->_db->query($query);
        $rs = $result->fetch();
        $contenido='<HTML>
                                <HEAD>
                                </HEAD>
                                <BODY>
                                <br>                
                                <img src="logomv.gif"> 
                                <br>
                                <br>
                                <table class="table1" border="1" style="width: 100%; border: 1px solid #000;">
                                    <tr>
                                        <th colspan="6" align="center" style=" width: 25%; background: #eee;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            INCIDENCIA
                                        </th>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style=" width: 40%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Unidad: <b>'.utf8_encode($rs['Uni_nombrecorto']).'</b>
                                        </td>
                                        <td style=" width: 60%;
                                           text-align: left;
                                           vertical-align: top;
                                           border: 1px solid #000;
                                           border-collapse: collapse;
                                           padding: 0.3em;
                                           caption-side: bottom;">
                                            Usuario: <b>'.utf8_encode($rs['Usu_nombre']).'</b>
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            Tipo de incidencia: <b>'.$nombreTipo.'</b>
                                        </td>
                                        <td >
                                            Severidad de incidencia: <b>'.$severidadNom.'</b>
                                        </td>                                       
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            Observaciones: <b>'.utf8_encode($observaciones).'</b>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                                </BODY>
                                </HTML>         
                ';
        $mimemail->set_from("seguimiento_NoReply@medicavial.com.mx");
        //$mimemail->set_to("facparticulares@medicavial.com.mx");
        $mimemail->set_to($correos);     
        //$mimemail->add_cc('enriqueerick@gmail.com');        
        $mimemail->set_subject("- Incidencia - ".$datosFolio['Uni_nombrecorto']." - ".$rs['Usu_nombre']);
        $mimemail->set_html($contenido);
        $mimemail->add_attachment("../imgs/logomv.jpg", "logomv.gif");
       
        if ($mimemail->send()){
            return 'exito';
           
        }else {
            return 'error';
        }
            
    }*/
}
 ?>