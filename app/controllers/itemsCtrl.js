app.controller('itemsCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
  

  $scope.registra={
    nombre: '',
    descripcion: '',
    precio: '',
    precio1: '',
    clinica1:'',
    clinica2:'',
    clinica3:'',
    clinica4:'',
    clinica5:'',
    clinica6:'',
    clinica7:''

  }
  
  $scope.registraitem= function(){
      
      console.log($scope.registra);
      
      $http({
          url:'api/items.php?funcion=registraitem&usr='+$rootScope.usrLogin+'',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.registra
          }).success( function (data1){
             if (data1==''){
              swal("OK","Item Registrado correctamente","success")
              document.getElementById('form1').reset();
              }
             });
                            
  }

  



});