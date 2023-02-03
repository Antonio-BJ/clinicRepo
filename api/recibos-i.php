<?php  
          function conexion(){
          $dbhost="www.medicavial.net";
          $dbuser="medica_webusr";
          $dbpass="tosnav50";
          $dbname="medica_registromv";
          $mysqli= new mysqli("$dbhost","$dbuser","$dbpass","$dbname");
         
          return $mysqli;
          }
          $conectar =conexion();
          date_default_timezone_set('America/Mexico_City');
          $funcion = $_GET['funcion'];
        
          if($funcion == 'buscarecibos'){
         
         $sql1="SELECT Uni_nombrecorto UNIDAD, Usu_nombre USUARIO, 
          concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE  FROM reciboParticulares INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id WHERE Recibo_fecExp BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() ORDER BY Recibo_fecExp DESC ";
          $result = $conectar->query($sql1);
          while($row = $result->fetch_assoc()) {
            $array[] = $row;
          }
          echo json_encode($array);
          $conectar = null;
        }

        if($funcion == 'buscamedicos'){
         
          $sql="SELECT DISTINCT concat( Med_paterno,' ',Med_materno,' ', Med_nombre ) as NOMBRE,Usu_login as USR FROM `Medico` WHERE Med_activo='S' GROUP BY Med_paterno,' ',Med_materno,' ', Med_nombre ORDER BY Med_paterno ASC";
           $resultado = $conectar->query($sql);
           while($row3 = $resultado->fetch_assoc()) {
             $array3[] = $row3;
           }
           echo json_encode($array3);
           $conectar = null;
         }

        if($funcion == 'buscarecibos1'){

          $postdata = file_get_contents("php://input");
          $data = json_decode($postdata);
          $recibo = $data->recibo;
          $fecha = $_GET['fecha'];
          $folio = $data->folio;
          $medico= $data->medico;
         
          if($recibo!='' && $fecha=='' &&$folio=='' && $medico==''){
           //CONSULTA PARA RECIBO
          $sql2="SELECT Uni_nombrecorto UNIDAD, Usu_nombre USUARIO, 
          concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE  FROM reciboParticulares INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id WHERE Recibo_cont='$recibo'";
          $result2 = $conectar->query($sql2);
          while($row2 = $result2->fetch_assoc()) {
            $array2[] = $row2;
          }
          if(empty($array2)){
          $array2=array('respuesta'=>'');
          }
          echo json_encode($array2);
          $conectar = null;
          }elseif($fecha!='' && $recibo==''&& $folio=='' && $medico==''){
           //CONSULTA PARA FECHA
           $hora_min = $fecha . ' 00:00:00';
           $hora_max = $fecha. ' 23:59:00' ;
           
           $sql3="SELECT Uni_nombrecorto UNIDAD, Usu_nombre USUARIO, 
           concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE  FROM reciboParticulares INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id WHERE reciboParticulares.Recibo_fecExp BETWEEN '$hora_min' AND '$hora_max'";
           $result3= $conectar->query($sql3);
           $row3= $result3->fetch_assoc();
            if(empty($row3)||$row3==NULL){
             $array3= array('respuesta' =>'');
             }else{ while($row3= $result3->fetch_assoc()) {
             $array3[] = $row3;
            }}
           echo json_encode($array3);
           $conectar = null;
          }
          elseif($folio!=''&& $recibo=='' &&$fecha=='' && $medico==''){
            //CONSULTA PARA FOLIO
            $sql4="SELECT Uni_nombrecorto UNIDAD, Usu_nombre USUARIO, 
            concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE  FROM reciboParticulares INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id WHERE reciboParticulares.Exp_folio='$folio'";
            $result4 = $conectar->query($sql4);
            $row4= $result4->fetch_assoc();
            $array4[] = $row4;
            echo json_encode($array4);
           
          }elseif($medico!=''&& $recibo==''&& $fecha==''&&$folio==''){
            //CONSULTA PARA MEDICO
            date_default_timezone_set('America/Mexico_City');
            $mednombre=$medico->USR;
            $sql5="SELECT Uni_nombrecorto UNIDAD, Usuario.Usu_nombre USUARIO,NotaMedica.Usu_nombre as MEDIC1O, 
            CONCAT( Med_paterno, ' ', Med_materno, ' ', Med_nombre ) as NomMedico,
                        concat( recibo_serie, Recibo_cont ) RECIBO, Recibo_total TOTAL, metodo FORMA_PAGO, reciboParticulares.Exp_folio FOLIOMV, Recibo_serie SERIE, Recibo_cont FOLIO_RECIBO, IF(Recibo_Tipo=2,'VENTA SIN ATENCION','ATENCION') TIPO_RECIBO, Recibo_fecExp FECHA_RECIBO, Recibo_cancelado, IF ( Recibo_Tipo = 2, ( SELECT CONCAT( sinReg_nombre, ' ', sinReg_apPaterno, ' ', sinReg_apMaterno ) sinReg_nombre FROM ventas_sin_registro WHERE reciboParticulares.Exp_folio = ventas_sin_registro.sinReg_id ), ( SELECT Exp_completo FROM Expediente WHERE reciboParticulares.Exp_folio = Expediente.Exp_folio ) ) NOMBRE  FROM reciboParticulares INNER JOIN Unidad ON reciboParticulares.Uni_clave = Unidad.Uni_clave INNER JOIN Usuario ON reciboParticulares.Usu_login = Usuario.Usu_login INNER JOIN metodoPagoPar ON reciboParticulares.Recibo_mpago = metodoPagoPar.id_metodo LEFT JOIN Expediente on reciboParticulares.Exp_folio=Expediente.Exp_folio LEFT JOIN ventas_sin_registro on reciboParticulares.Exp_folio=ventas_sin_registro.sinReg_id INNER JOIN NotaMedica on reciboParticulares.Exp_folio=NotaMedica.Exp_folio INNER JOIN Medico on NotaMedica.Usu_nombre=Medico.Usu_login 
            WHERE NotaMedica.Usu_nombre='$mednombre' AND Recibo_fecExp=YEAR(NOW()) ORDER BY  Recibo_fecExp DESC ";
            $result5 = $conectar->query($sql5);
            $row5= $result5->fetch_assoc();
            if(empty($row5)||$row5==NULL){
             $array5= array('respuesta' =>'');
             }else{ while($row5 = $result5->fetch_assoc()) {
             $array5[] = $row5;
            }}
            echo json_encode($array5);
            $conectar = null;
          }
      }
?>