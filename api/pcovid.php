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
        
          if($funcion == 'buscaunidades'){
            $query ="SELECT * FROM Unidad WHERE Uni_propia='S' and Uni_activa='S' AND Uni_clave!='8' ORDER BY Uni_nombrecorto";
            $resultado = $conectar->query($query);
            while($row = $resultado->fetch_assoc()) {
              $array[] = $row;
            }
          echo json_encode($array);
          $conectar = null;
        }

        if($funcion == 'buscapconvenio'){
          date_default_timezone_set('America/Mexico_City');
          $query1="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='54' ORDER BY Exp_fecreg DESC LIMIT 50";
           $resultado1 = $conectar->query($query1);
           while($row1 = $resultado1->fetch_assoc()) {
             $array1[] = $row1;
           }
           echo json_encode($array1);
           $conectar = null;
         }

            if($funcion == 'buscapacientes'){
            date_default_timezone_set('America/Mexico_City');
            $sql="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
            Compania.cia_clave ='81' ORDER BY Exp_fecreg DESC LIMIT 50";
            $resultado = $conectar->query($sql);
            while($row3 = $resultado->fetch_assoc()) {
              $array3[] = $row3;
            }
            echo json_encode($array3);
            $conectar = null;
          }

         if($funcion == 'buscapacientes1'){

          $postdata = file_get_contents("php://input");
          $data = json_decode($postdata);
          $unidad = $data->unidad;
          $fecha = $_GET['fecha'];
          $fechaf = $_GET['fechaf'];
          if($fecha!='' && $fechaf!=''&& $unidad==''){

          $hora_min = $fecha . ' 00:00:00';
          $hora_max = $fechaf . ' 23:59:00' ;
          
          $sql1="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='81' AND Exp_fecreg BETWEEN '$hora_min' AND '$hora_max' ORDER BY Exp_fecreg DESC";
           $resultado1 = $conectar->query($sql1);
           $row1= $resultado1->fetch_assoc();
           if(empty($row1)||$row1==NULL){
            $array1= array('respuesta' =>'');
          }else{ while($row1 = $resultado1->fetch_assoc()) {
            $array1[] = $row1;
          }}
           echo json_encode($array1);
           $conectar = null;
          }elseif($unidad!=''&& $fecha=='' && $fechaf==''){
            $sq="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
            Compania.cia_clave ='81' AND Unidad.Uni_clave='$unidad' AND Exp_fecreg=YEAR(NOW()) ORDER BY Exp_fecreg DESC LIMIT 50";
             $result = $conectar->query($sq);
             $r= $result->fetch_assoc();
             if(empty($r)||$r==NULL){
              $ar= array('respuesta' =>'');
            }else{ while($r= $result->fetch_assoc()) {
              $ar[] = $r;
            }}
             echo json_encode($ar);
             $conectar = null; 
          }elseif($unidad!=''&& $fecha!='' && $fechaf!=''){
            $hora_min = $fecha . ' 00:00:00';
            $hora_max = $fechaf . ' 23:59:00' ;

            $sq="SELECT Exp_folio, Compania.Cia_clave,Cia_nombrecorto, Exp_fecreg as Fecha, Exp_paterno, Exp_materno, Exp_nombre, Exp_obs, Exp_solCancela, Exp_cancelado,Uni_nombrecorto as Unidad From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
            inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
            inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
            Compania.cia_clave ='81' AND Unidad.Uni_clave='$unidad' AND Exp_fecreg BETWEEN '$hora_min' AND '$hora_max' ORDER BY Exp_fecreg DESC ";
             $result = $conectar->query($sq);
             if(mysqli_num_rows($result)==0){
              $ar= array('respuesta' =>'');
            }else{ while($r= $result->fetch_assoc()) {
              $ar[] = $r;
            }}
             echo json_encode($ar);
           
             $conectar = null; 
          }

        }

       

        if($funcion == 'totalcovid'){
          date_default_timezone_set('America/Mexico_City');
          $sq="SELECT COUNT(*) as TOTAL From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='81' AND Exp_fecreg=YEAR(NOW())";
          $resultado = $conectar->query($sq);
          $row= $resultado->fetch_assoc();
          echo json_encode($row);
          
        }

        if($funcion == 'totalcovid1'){
          date_default_timezone_set('America/Mexico_City');
          $sq="SELECT COUNT(*) as TOTAL From Expediente inner join Unidad on Expediente.Uni_claveActual=Unidad.Uni_clave 
          inner join Producto on Expediente.Pro_clave = Producto.Pro_clave 
          inner join Compania on Expediente.Cia_clave=Compania.Cia_clave WHERE
          Compania.cia_clave ='81'AND MONTH(Expediente.Exp_fecreg)=MONTH(NOW()) AND YEAR(Expediente.Exp_fecreg)=YEAR(NOW())";
          $resultado = $conectar->query($sq);
          $row= $resultado->fetch_assoc();
          echo json_encode($row);
          
        }

      
?>