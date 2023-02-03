app.controller('registroCtrl', function($scope,$rootScope,$location,$cookies,$http,$cookieStore) {
  $cookies.cveUnidadAlternativa='';
	$scope.cveUnidad= $cookies.uniClave;
  $scope.usr      = $cookies.usrLogin;
	$rootScope.zona = $cookies.zona;
  $rootScope.permisos=JSON.parse($cookies.permisos); 
  if($cookies.folioMembresia){
      $cookieStore.remove("folioMembresia");
  } 
	$scope.cia=$cookies.clave;
	$scope.unidadAlternativa='';
	$scope.sinParticulares= true;	
  console.log($cookies.cveUnidadAlternativa);
	$http.get('api/catalogos.php?funcion=catUnidades').success(function (data){                                                              
           $scope.listadoUnidades=data; 
  	}); 


// VERIFICA EL ESTADO DEL ULTIMO REGISTRO
    $http({
        url:'api/api.php?funcion=revisaUltimoRegistro&unidad='+$scope.cveUnidad+'&usr='+$scope.usr,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.cveUnidad
        }).success( function (data){ 
        $scope.estadoUltimo = data;
        console.log($scope.estadoUltimo.Exp_estatusReg);
          if ($scope.estadoUltimo.Exp_estatusReg==1) {
            $location.path("/registra");
          }

        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });
// TERMINA LA VERIFICACION


  	$scope.asignarUnidad = function(){
      
  		if($scope.unidadAlternativa==''){
  			$cookies.cveUnidadAlternativa='';	
  		}else{
  			$cookies.cveUnidadAlternativa=$scope.unidadAlternativa;	
  		}
      console.log($scope.unidadAlternativa);                       		
        $http.get('api/catalogos.php?funcion=unidadPropia&unidad='+$scope.unidadAlternativa).success(function (data){ 
          console.log(data);
       	if(data=='N'){
       		$scope.sinParticulares= false;
       	}else{
       		$scope.sinParticulares= true;
       	}
  	});

    } 

     $scope.confirmaUnidad = function(tipo){
      if ($scope.unidadAlternativa==''&&$scope.cveUnidad==8&&$scope.permisos.Per_cabina =='S'){
      swal({ 
                                  title: "Unidad Incorrecta",   
                                  text: "Tienes que seleccionar unidad de registro",   
                                  type: "warning",
                                  showCancelButton: false,                                 
                                  confirmButtonColor: "#DD6B55", 
                                  ConfirmButtonText: "Cerrar",                                   
                                  closeOnConfirm: true,
                                  closeOnCancel: true}, 
                                  function(){                                           
                                        console.log('entro a la redireccion');                                                       
                                        
                                   });   
                                             
      }else{
        $location.path("/aperturaExp/"+tipo); 
      }
    } 
});