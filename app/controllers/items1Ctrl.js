app.controller('items1Ctrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
  
  $scope.actualiza={
    iditem:'',
    descripcion: '',
    precio1: '',
    precio2: '',
    precio3: '',

  }
  
  $http({url:'api/items.php?funcion=buscaitem&usr='+$rootScope.usrLogin+'', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (data){
    console.log(data);
    $scope.listadoitems=data;
  });
  
  $scope.verdetalles=function(i){
  
   $http({url:'api/items.php?funcion=buscadatositem&id='+i+'&usr='+$rootScope.usrLogin+'', 
   contentType: 'application/json', 
   dataType: "json", 
   }).success(function (data){
    console.log(data);
    $scope.actualiza.descripcion=data.Ite_descripcion;
    $scope.actualiza.precio1=data.Ite_Precio1;
    $scope.actualiza.precio2=data.Ite_Precio2;
    $scope.actualiza.precio3=data.Ite_Precio3;
    
  });  
  }

  $scope.actualizaitem= function(){
      
      console.log($scope.actualiza);

      $http({
        url:'api/items.php?funcion=actualizaitem&usr='+$rootScope.usrLogin+'',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.actualiza
        }).success( function (data1){
           if (data1==''){
            swal("OK","Item Actualizado correctamente","success")
            document.getElementById('form1').reset();
            }
           });
      
      
                            
  }

  



});