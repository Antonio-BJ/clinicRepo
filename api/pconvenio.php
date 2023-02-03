<?php  
          function conexion(){
          $dbhost="www.medicavial.net";
          $dbuser="medica_webusr";
          $dbpass="tosnav50";
          $dbname="medica_registromv";
          
          $mysqli= new mysqli("$dbhost","$dbuser","$dbpass","$dbname");
          date_default_timezone_set('America/Mexico_City');
          return $mysqli;
          }
          $conectar =conexion();
          date_default_timezone_set('America/Mexico_City');
          $funcion = $_GET['funcion'];

        if($funcion == 'buscapconvenio1'){
         
          $postdata = file_get_contents("php://input");
          $data = json_decode($postdata);
          $unidad = $data->unidad;
          $fecha = $_GET['fecha'];
          $fechaf = $_GET['fechaf'];
          if($fecha!='' && $fechaf!=''&& $unidad==''){
          
          $hora_min = $fecha . ' 00:00:00';
          $hora_max = $fechaf . ' 23:59:00';
          date_default_timezone_set('America/Mexico_City');
          $sql1c="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='54' AND Exp_fecreg BETWEEN '$hora_min' AND '$hora_max'";

           $resultado1c = $conectar->query($sql1c);
           $row1c= $resultado1c->fetch_assoc();
           if(empty($row1c)){
            $array1c= array('respuesta' =>'');
          }else{ while($row1c = $resultado1c->fetch_assoc()) {
            $array1c[] = $row1c;
          }}
           echo json_encode($array1c);
           $conectar = null;
          }elseif($fecha=='' && $fechaf==''&& $unidad!=''){
            $sql2c="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
            Compania.cia_clave ='54' AND Unidad.Uni_clave='$unidad'  ORDER BY Exp_fecreg DESC LIMIT 20";
             $resultado2c= $conectar->query($sql2c);
             $row2c = $resultado2c->fetch_assoc();
             if(empty($row2c)){
              $array2c= array('respuesta' =>'');
            }else{ while($row2c = $resultado2c->fetch_assoc()) {
              $array2c[] = $row2c;
            }}
             echo json_encode($array2c);
             $conectar = null; 
          }elseif($unidad!='' &&  $fecha!='' && $fechaf!=''){
            $hora_min = $fecha . ' 00:00:00';
            $hora_max = $fechaf . ' 23:59:00';
          
            
            $s="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
            Compania.cia_clave ='54' AND Unidad.Uni_clave='$unidad' AND Expediente.Exp_fecreg BETWEEN '$hora_min' AND '$hora_max' ORDER BY Exp_fecreg DESC";
            $res= $conectar->query($s);
            if(mysqli_num_rows($res)==0){
              $ar= array('respuesta' =>'');
            }else{ while($r= $res->fetch_assoc()) {
              $ar[] = $r;
            }}
             echo json_encode($ar);
            }

          

        }

        if($funcion == 'totalconvenio'){
          $sq="SELECT COUNT(*) as TOTAL From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='54' AND Exp_fecreg=YEAR(NOW())";
          $resultado = $conectar->query($sq);
          $row= $resultado->fetch_assoc();
          echo json_encode($row);
          
        }

        if($funcion == 'totalconvenio1'){
          $sq="SELECT COUNT(*) as TOTAL From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='54' AND MONTH(Expediente.Exp_fecreg)=MONTH(NOW()) AND YEAR(Expediente.Exp_fecreg)=YEAR(NOW())";
          $resultado = $conectar->query($sq);
          $row= $resultado->fetch_assoc();
          echo json_encode($row);
          
        }


      
?>