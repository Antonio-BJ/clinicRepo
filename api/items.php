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
          $usr=$_GET['usr'];
        
          if($funcion == 'buscaitem'){
            $sql4="SELECT Ite_cons,Ite_item FROM ItemOrtho_nuevo where FAM_id =1 AND Ite_activo='S' ORDER BY Ite_item ASC";
            $result4 = $conectar->query($sql4);
            while($row4 = $result4->fetch_assoc()) {
              $array[] = $row4;
            }
          echo json_encode($array);
          $conectar = null;
          }
          if($funcion == 'buscadatositem'){
            $id = $_GET['id'];
            $sql5="SELECT Ite_descripcion,Ite_Precio1,Ite_Precio2,Ite_Precio3 FROM ItemOrtho_nuevo where Ite_cons='$id'";
            $result5 = $conectar->query($sql5);
            $row5 = $result5->fetch_array();
          echo json_encode($row5);
          $conectar = null;
          }

          
          if($funcion == 'registraitem'){
          $postdata = file_get_contents("php://input");
          $data = json_decode($postdata);
          $nombre = $data->nombre;
          $descripcion=$data->descripcion;
          $precio=$data->precio;
          $precio1=$data->precio1;
          $clinica1=$data->clinica1;
          if($clinica1=='1'){
            $clinica1=$precio1;  
          }
          $clinica2=$data->clinica2;
          if($clinica2=='1'){
            $clinica2=$precio1;  
          }else{
            $clinica2=0;
          }
          $clinica3=$data->clinica3;
          if($clinica3=='1'){
            $clinica3=$precio1;  
          }else{
            $clinica3=0;
          }
          $clinica4=$data->clinica4;
          if($clinica4=='1'){
            $clinica4=$precio1;  
          }else{
            $clinica4=0;
          }
          $clinica5=$data->clinica5;
          if($clinica5=='1'){
            $clinica5=$precio1;  
          }else{
            $clinica5=0;
          }
          $clinica6=$data->clinica6;
          if($clinica6=='1'){
            $clinica6=$precio1;  
          }else{
            $clinica6=0;
          }
          $clinica7=$data->clinica7;
          if($clinica7=='1'){
            $clinica7=$precio1;  
          }else{
            $clinica7=0;
          }

          $sql="SELECT Ite_cons as ID FROM ItemOrtho_nuevo WHERE FAM_id=1 ORDER BY Ite_cons DESC LIMIT 1";
          $result = $conectar->query($sql); 
          $row = $result->fetch_assoc();
          //echo $row['ID']."<br>";
          $id_reg=$row['ID']+1;
          //echo $id_reg."<br>";
          $sql1="INSERT INTO ItemOrtho_nuevo (Ite_cons,Ite_item,Ite_descripcion,Ite_precio,Ite_activo,Ite_fecreg,Tip_clave,
          ite_orden,GPA_id,ite_medicamento,Ite_Precio1,Ite_Precio2,Ite_Precio3,Ite_Precio4,
                                      Ite_Precio5,Ite_Precio6,Ite_Precio7,Ite_Precio86,Ite_Precio184,Ite_Precio186,Ite_Precio8,Ite_img,FAM_id,Ite_promocion,Ite_horaInicio,
                                      Ite_horaFin,usr_reg)
                                      VALUES
                                      ('$id_reg','$nombre','$descripcion','$precio','S',NOW(),'1','100','1',0,
                                      '$clinica1','$clinica2','$clinica3','$clinica4','$clinica5','$clinica6','$clinica7',0,0,0,0,
                                      'imgs/items/sutura.jpg','1','0','00:00:00','23:59:59','$usr')";
          
          $result1 = $conectar->query($sql1);

          $sql2="SELECT PIT_cve as ID1 FROM PermisosItems  ORDER BY PIT_cve DESC LIMIT 1";
          $result2 = $conectar->query($sql2);
          $row2 = $result2->fetch_assoc();
          $id_reg1=$row2['ID1']+1;

          $sql3="INSERT INTO PermisosItems (PIT_cve,Ite_cons,individual,Empleado,Cortesia,T_Inbursa,G_BIMBO,M_MV,Desclub,MetLife,keken,
          hapag,assis,T_multiva,cosem,friza,aFrancesa,rSiniestro,ausa,scotia,volante,buenFin,regala,premaraton,5sesiones,sanValentin,cellers,
          hive,eticket,asmas,bienestar,sisnova,revajim,rehabilitador,sSanta,sSantaM,diaNinio,diaMadre,diaMadreM,diaPadre,carnet,kontroll,
          rxVolante,verano,paqueteSes,paqueteSesM,bf_5reah,bf_rx,bf_to,cupon15,blackFriday,cyberMonday,deducible,navidad,adicional,kalaan,
          redesSociales,twitter,pagaAntes,promoFisioterapia,mms,medici,rhCovid,goBenefits,rxBanamex,memScotia,10sesiones,sinai,10Mayo,paquetemejores,mapfre)
          VALUES
          ('$id_reg1','$id_reg',0,0,0,0,0,0,0,0,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
          $result3 = $conectar->query($sql3);
          
          }

          if($funcion == 'actualizaitem'){
            $postdata = file_get_contents("php://input");
            $data = json_decode($postdata);
            $id_item = $data->iditem;
            $precio1=$data->precio1;
            $precio2=$data->precio2;
            $precio3=$data->precio3;
            $sql8="UPDATE ItemOrtho_nuevo SET Ite_Precio1='$precio1',Ite_Precio2='$precio2',Ite_Precio3='$precio3'
                  WHERE Ite_cons='$id_item'";
            $result8= $conectar->query($sql8);
            $sql9="INSERT INTO BitacoraItems (Ite_cons,Ite_Precio1,Ite_Precio2,Ite_Precio3,fec_act,usr_act)
						VALUES ('$id_item','$precio1',' $precio2','$precio3',NOW(),'$usr')";
             $result9= $conectar->query($sql9);        
            $conectar = null;
          }

?>