app.controller('detalleCobroCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$routeParams) {

  $rootScope.folio    = $cookies.folio;

  $rootScope.usrLogin = $cookies.usrLogin;

  $rootScope.uniClave = $cookies.uniClave;

  $scope.recibo       = $routeParams.recibo;



  // console.log($routeParams.recibo);



  $scope.cargador=false; 

  $scope.selection =[];

  $scope.pan = false;

  if ($scope.pan == false) {



    $scope.colun = 12;

    $scope.noIden = false;



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



  }else{



    $scope.colun = 6;

    $scope.noIden = true;



  }



  $scope.datosRecibo = {

            folRecibo: '',

            serie:'P',

            nombreUsuario:'',

            nombrerecepcionista:'',

            total:0,

            aplicado:0,

            totalcobro:'',

            restanteRec: '',

            comision:0,
            uniclave: ''  

  }



  $scope.aplicacion={

      monto:'',

      referencia:'',

      observaciones:'',

      fecCobro:today

  } 



  $http.get('api/cobranzaParticulares.php?funcion=detalleRec&recibo='+$scope.recibo).success(function (data){  

    $scope.datos=data[0];

    // arregloDeTerminos = $routeParams.recibo;

    var cadena= $routeParams.recibo;

    var fstChar = cadena.charAt(0);



    var regex = /(\d+)/g;

    var fol=$routeParams.recibo;

    fol = fol.match(regex);



    $scope.datosRecibo = {

            folRecibo: fol[0],

            serie: fstChar,

            total: data[0].Recibo_total,

            restanteRec: data[0].restanteRec,

            uniclave: data[0].Uni_clave 

    }



    $scope.cargador=false;  

    $scope.buscaCobro(data[0].Uni_clave);



  });



  $scope.buscaCobro = function(unidad){       

    $scope.cargador=true; 

    $http({

      url:'api/cobranzaParticulares.php?funcion=listadoCobros&unidad='+unidad,

      method:'POST', 

      contentType: 'application/json', 

      dataType: "json", 

      data: $scope.bcobro

      }).success( function (data){                              

      console.log(data);

        $scope.listadoCobros=data;            

        $scope.trabajando=false;
         $scope.cargador=false; 

        $scope.cobro = true;

        if ($scope.pan == false) {



          $scope.colun = 12;



        }else{



          $scope.colun = 6;



        }

 

        

      }).error( function (xhr,status,data){

          $scope.mensaje ='no entra';            

          alert('Error');

    });   

  }



  $scope.toggleSelection = function (todo) {

    console.log(todo);

      var idx = $scope.selection.indexOf(todo);

      // is currently selected

      if (idx > -1) {



          $scope.selection.splice(idx, 1);

          console.log($scope.selection);



      } else {



           $scope.selection.push(todo);

           console.log($scope.selection);



      }

  }



  $scope.actCol = function(){ 



      if ($scope.pan == false) {



          $scope.colun = 12;

          $scope.noIden = false;



      }else{



          $scope.colun = 6;

          $scope.noIden = true;



      }



  }



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

          $scope.datosRecibo.totalcobro = parseFloat($scope.datosRecibo.totalcobro);

          $scope.datosRecibo.total      = parseFloat($scope.datosRecibo.total);



          if($scope.datosRecibo.totalcobro > $scope.datosRecibo.total){

            

            alert('El cobro no puede ser mayor que el total del recibo');





          }else{



            $('#ventanaApp').modal({backdrop: 'static', keyboard: false}); // abrir

            $scope.aplicacion.monto = parseFloat($scope.datosRecibo.totalcobro);

            var com = parseFloat($scope.aplicacion.monto*3.6/100);

            var iva = parseFloat(com*0.16);

            var res = parseFloat(com+iva);

            $scope.aplicacion.comision = parseFloat(res.toFixed(2));

            var totalapp = parseFloat($scope.aplicacion.monto-res);

            $scope.aplicacion.totalapp = parseFloat(totalapp.toFixed(2))



          }



      }



  }



  $scope.guardarAplicacion = function(){       

    // if($scope.formAplicacionParticulares.$valid){      

      $scope.cargador=true; 

      if($scope.aplicacion.monto <= $scope.datosRecibo.total){



          $http({

          url:'api/particulares.php?funcion=SetAplicacion&FolRecibo='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie+'&usr='+$rootScope.usrLogin,

          method:'POST', 

          contentType: 'application/json', 

          dataType: "json", 

          data: {aplicacion:$scope.aplicacion, cobro: $scope.selection}

          }).success( function (data){  

            console.log(data);         

            $scope.listadoAplicaciones=data; 

            $http.get('api/particulares.php?funcion=getMonto&Recibo_cont='+$scope.datosRecibo.folRecibo+'&serie='+$scope.datosRecibo.serie).success(function (data){ 

                console.log(data);

                $scope.cargador=false;      

                $scope.datosRecibo.aplicado=data.Recibo_aplicado;

                $scope.datosRecibo.restanteRec=data.restanteRec;

                $scope.aplicacion={

                    monto:'',

                    referencia:'',

                    observaciones:'',

                    fecCobro:today,

                    comision:0

                } 

                $scope.formAplicacionParticulares.$submitted=false;  

                // $('#ventanaApp').modal('hide');

                // $location.path('/adminRec');

                // $location.path('/bloqueo');

            });  

          }).error( function (xhr,status,data){

              $scope.mensaje ='no entra';            

              alert('Error');

          }); 

      }else{



              $scope.mensaje ='No puedes Aplicar mas que del total del recibo';            

              alert('Error');





      }

    // }   

  }

  $scope.enviaParametros = function(){
  //console.log($scope.reporteIt);
    $scope.cargador=true;
    $http({
        url:'api/cobranzaParticulares.php?funcion=buscacobro&unidad='+$scope.datosRecibo.uniclave,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporteIt
        }).success( function (data){                                                      
          $scope.cargador=false; 
          console.log(data);

          $scope.listadoCobros = data;

    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });                          
}










});