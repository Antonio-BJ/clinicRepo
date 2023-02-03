app.controller('seguimientoCobranzaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload,movimientos) {
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;

  $scope.trabajando=false;
  $scope.cargador=false;  
  $scope.verBusquedaAvanzada=false;


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



  $scope.init = function() {
      $scope.trabajando=true;

      $scope.parametrosBusqueda={
        folioRecibo:'',
        folioMV:'',
        nombre:'',
        fechaIni:today,
        fechaFin:today
      }
      $scope.datosRecibo = {
          folRecibo: '',
          serie:'P',
          nombreUsuario:'',
          nombrerecepcionista:'',
          total:0,
          aplicado:0         
      }
      $scope.aplicacion={
          monto:'',
          referencia:'',
          observaciones:'',
          fecCobro:today
      } 

      busquedas.listadoRecibosCobranza($scope.parametrosBusqueda).success(function(data){  
           console.log(data);  
           $scope.listadoRecibos = data;   
           $scope.trabajando=false;    
      });    
    
  }
    
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
            console.log(data);          
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
                /*$http.get('api/particulares.php?funcion=getRecibo&Recibo_cont=2068').success(function (data){                                 
                  $scope.datosRecibo.total=data.Recibo_total;    
                  $scope.datosRecibo.folRecibo=data.Recibo_cont;
                  $scope.datosRecibo.serie=data.Recibo_serie; 
                  $scope.datosRecibo.aplicado=data.Recibo_aplicado;    
                  $http.get('api/particulares.php?funcion=getPagos&Recibo_cont='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie).success(function (data){                                 
                      $scope.listadoAplicaciones=data;               
                  });    
                });*/
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
  }

  $scope.interacted = function(field) {          
      return $scope.formAplicacionParticulares.$submitted && field.$invalid;          
  };

  $scope.verColorCobranza = function(color) {          
      alert('ya entro a nuestra funcion');
  };

   $scope.cambiarEstatusCobranza = function(color,serie,cont) {    
      
     swal({ 
      title: "Confirmar cambio estatus en cobranza",   
      text: "",   
      type: "warning",
      showCancelButton: true,                                 
      confirmButtonColor: "#DD6B55", 
      ConfirmButtonText: "Aceptar",                                   
      closeOnConfirm: true,
      closeOnCancel: true}, 
      function(){  
          $scope.listadoRecibos = '';       
          $scope.trabajando=true;
           movimientos.estatusCobranza(color,serie,cont).success(function(data){
              console.log(data);
              if(data.respuesta=='exito'){
                if($scope.parametrosBusqueda.folioRecibo||$scope.parametrosBusqueda.folioMV||$scope.parametrosBusqueda.nombre){
                  busquedas.listadoRecibosCobranzaAvanzada($scope.parametrosBusqueda).success(function(data){                               
                           if(data.length>0){
                              $scope.listadoRecibos = data;                                  
                               $scope.mensaje=false;
                           }else{
                              $scope.mensaje=true;
                              $scope.listadoRecibos = '';                                  
                           }
                           
                           $scope.trabajando=false;      
                    });                  
                }else{
                  busquedas.listadoRecibosCobranza($scope.parametrosBusqueda).success(function(data){                     
                     $scope.listadoRecibos = data;   
                     $scope.trabajando=false;                       
                  });
                }
              }else{
                $scope.trabajando=false;                       
                alert('error en modificación');
              }
            });                                                                              
       });                     
  };

  
  $scope.buscarPorFecha = function(){ 
    $scope.trabajando=true;    
     $scope.parametrosBusqueda.folioRecibo='';
     $scope.parametrosBusqueda.folioMV='';
     $scope.parametrosBusqueda.nombre='';  
      console.log($scope.parametrosBusqueda);
    busquedas.listadoRecibosCobranza($scope.parametrosBusqueda).success(function(data){  
           console.log(data);  
            if(data.length>0){
              $scope.listadoRecibos = data;                                 
              $scope.mensaje=false;
            }else{
              $scope.listadoRecibos = '';                                 
              $scope.mensaje=true;
            }
           $scope.trabajando=false;      
    });  
  }

  $scope.abrirBusquedaAvanzada = function(){ 
    $scope.verBusquedaAvanzada=true;
  }



  $scope.buscarDatos = function() {
      $scope.trabajando=true;
      $scope.recibos='';
      $scope.datosRecibo = {
          folRecibo: '',
          serie:'P',
          nombreUsuario:'',
          nombrerecepcionista:'',
          total:0,
          aplicado:0         
      }
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
                /*$http.get('api/particulares.php?funcion=getRecibo&Recibo_cont=2068').success(function (data){                                 
                  $scope.datosRecibo.total=data.Recibo_total;    
                  $scope.datosRecibo.folRecibo=data.Recibo_cont;
                  $scope.datosRecibo.serie=data.Recibo_serie; 
                  $scope.datosRecibo.aplicado=data.Recibo_aplicado;    
                  $http.get('api/particulares.php?funcion=getPagos&Recibo_cont='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie).success(function (data){                                 
                      $scope.listadoAplicaciones=data;               
                  });    
                });*/
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
  }

  $scope.busquedaAvanzada = function() {
    $scope.trabajando=true;
    console.log($scope.parametrosBusqueda);
    busquedas.listadoRecibosCobranzaAvanzada($scope.parametrosBusqueda).success(function(data){  
           console.log(data);  
           if(data.length>0){
              $scope.listadoRecibos = data;                                  
               $scope.mensaje=false;
           }else{
              $scope.mensaje=true;
              $scope.listadoRecibos = '';                                  
           }
           
           $scope.trabajando=false;      

    });  
  }

  $scope.abreModalAplicacion = function(serie,recibo){   
    $("#myModal").modal();    
    console.log(serie+'---'+recibo);
     $http.get('api/particulares.php?funcion=getDatosRecibo&recibo='+recibo+'&serie='+serie).success(function (data){                                 
          $scope.datosRecibo.total=parseFloat(data.Recibo_total);    
          $scope.datosRecibo.folRecibo=data.Recibo_cont;
          $scope.datosRecibo.serie=data.Recibo_serie; 
          $scope.datosRecibo.aplicado=data.Recibo_aplicado;             
          $scope.parametrosBusqueda.folioRecibo='';
          $scope.parametrosBusqueda.folioMV='';
          $scope.parametrosBusqueda.nombre='';
          $scope.recibos=data;     

          $http.get('api/particulares.php?funcion=getPagos&Recibo_cont='+recibo+'&serie='+serie).success(function (data){                                               
              $scope.listadoAplicaciones=data;               
          });          
      });

    // $http({
    //         url:'api/cobranzaParticulares.php?funcion=buscaRecibo',
    //         method:'POST', 
    //         contentType: 'application/json', 
    //         dataType: "json", 
    //         data: $scope.parametrosBusqueda
    //         }).success( function (data){                              
    //         //console.log(data);
    //         $scope.recibos=data;            
    //           $scope.trabajando=false;
    //             $scope.datosRecibo.total=parseFloat(data.Recibo_total);    
    //             $scope.datosRecibo.folRecibo=data.Recibo_cont;
    //             $scope.datosRecibo.serie=data.Recibo_serie; 
    //             $scope.datosRecibo.aplicado=data.Recibo_aplicado;   
    //             console.log(data);
    //             $scope.parametrosBusqueda.folioRecibo='';
    //             $scope.parametrosBusqueda.folioMV='';
    //             $scope.parametrosBusqueda.nombre='';
    //             $http.get('api/particulares.php?funcion=getPagos&Recibo_cont='+recibo+'&serie='+serie).success(function (data){                                 
    //               console.log(data);
    //                 $scope.listadoAplicaciones=data;               
    //             });                  
              
    //         }).error( function (xhr,status,data){
    //             $scope.mensaje ='no entra';            
    //             alert('Error');
    //         });             
  }

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


});