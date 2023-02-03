app.controller('cobranzaParticularesCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;

  $scope.trabajando=false;
  $scope.cargador=false;  

  var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    var today = yyyy+'-'+mm+'-'+dd;

    console.log(today);

  $scope.parametrosBusqueda={
    folioRecibo:'',
    folioMV:'',
    nombre:'',
  }
  $scope.datosRecibo = {
            folRecibo: '',
            serie:'P',
            nombreUsuario:'',
            nombrerecepcionista:'',
            total:0,
            aplicado:0,
            totalcobro:''       
  }
  $scope.aplicacion={
      monto:'',
      referencia:'',
      observaciones:'',
      fecCobro:today
  } 

  $scope.bcobro = {

    fechaIni: '',
    fechaFin: ''
  } 

  $scope.selection = [];
/* BUSCA DATOS DEL PACIENTE */
  $scope.buscarDatos = function() {
      $scope.trabajando=true;
    $http({
            url:'api/cobranzaParticulares.php?funcion=buscaRecibo',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.parametrosBusqueda
            }).success( function (data){                              
            //console.log(data);
            $scope.recibos=data;            
              $scope.trabajando=false;
                $scope.datosRecibo.total=parseFloat(data.Recibo_total);    
                $scope.datosRecibo.folRecibo=data.Recibo_cont;
                $scope.datosRecibo.serie=data.Recibo_serie; 
                $scope.datosRecibo.aplicado=data.Recibo_aplicado;   
                console.log(data);
                $scope.parametrosBusqueda.folioRecibo='';
                $scope.parametrosBusqueda.folioMV='';
                $scope.parametrosBusqueda.nombre='';
                $http.get('api/particulares.php?funcion=getPagos&Recibo_cont='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie).success(function (data){                                 
                  console.log(data);
                    $scope.listadoAplicaciones=data;               
                }); 
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
  }


/* BUSCA DATOS DE COBRO */

  $scope.buscaCobro = function(){       
    $scope.cargador=true; 
    $http({
      url:'api/cobranzaParticulares.php?funcion=listadoCobros',
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.bcobro
      }).success( function (data){                              
      console.log(data);
        $scope.listadoCobros=data;            
        $scope.trabajando=false;
        $scope.cobro = true;
 
        
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
    });   
  }


  $scope.interacted = function(field) {          
      return $scope.formAplicacionParticulares.$submitted && field.$invalid;          
  };

  $scope.guardarAplicacion = function(){       
    if($scope.formAplicacionParticulares.$valid){      
      $scope.cargador=true;        
      $http({
      url:'api/particulares.php?funcion=SetAplicacion&FolRecibo='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie+'&usr='+$rootScope.usrLogin,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.aplicacion
      }).success( function (data){  
        console.log(data);         
        $scope.listadoAplicaciones=data; 
        $http.get('api/particulares.php?funcion=getMonto&Recibo_cont='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie).success(function (data){ 
            console.log(data);
            $scope.cargador=false;      
            $scope.datosRecibo.aplicado=data.Recibo_aplicado;
            $scope.aplicacion={
                monto:'',
                referencia:'',
                observaciones:''
            }
            $scope.formAplicacionParticulares.$submitted=false;  
        });  
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      }); 
    }   
  }

  $scope.borrarAplicacion = function(idPago){       
    $scope.cargador=true; 
    $http.get('api/particulares.php?funcion=DeleteAplicacion&cve='+idPago).success(function (data){ 
      $scope.datosRecibo.aplicado=data.descuento;                    
      $http.get('api/particulares.php?funcion=getPagos&Recibo_cont='+data.cve_recibo+'&serie='+data.serie).success(function (data){                                 
          $scope.cargador=false; 
          $scope.listadoAplicaciones=data;               
      });
    });  
  }

  $scope.toggleSelection = function (todo) {
      var idx = $scope.selection.indexOf(todo);
      // is currently selected
      if (idx > -1) {

          $scope.selection.splice(idx, 1);

      } else {
          $scope.selection.push(todo);


      }


  };

  $scope.enviaAplicacion = function () {

      if ($scope.selection == '') {

        alert('Selecciona un cobro para poder aplicar');

        return $scope.selection;

      }else{
          var sumaCobro = 0;
          for (var i = 0; i < $scope.selection.length; i++){

                sumaCobro += parseFloat($scope.selection[i].COB_saldoinicial);
                $scope.datosRecibo.totalcobro = sumaCobro.toFixed(2);

          }
          if($scope.datosRecibo.totalcobro < $scope.datosRecibo.total){
            
            alert('El cobro no puede ser mayor que el recibo');


          }else{

            $('#ventanaApp').modal({backdrop: 'static', keyboard: false}); // abrir
            $scope.aplicacion.monto = parseFloat($scope.datosRecibo.totalcobro);

          }


          console.log($scope.datosRecibo.totalcobro );


      }





  }


});