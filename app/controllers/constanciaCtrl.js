app.controller('constanciaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.formCambio=false;
  $scope.cargador=false;
  $scope.cambioUnidad=false;
  $scope.formularios={}; 
  $scope.contanciaEnv=false; 	
	$scope.constancia={
    motivo:'',
    observaciones:''
  };
  $scope.formCambio=false;
    

    $scope.enviaDatos = function(){                                  
              $scope.cargador=true;               
              $http({
                    url:'api/api.php?funcion=enviaConstancia&fol='+$rootScope.folio+"&usr="+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.constancia
                    }).success( function (data){                        
                      if(data.respuesta=='correcto'){
                          $scope.contanciaEnv=true;  
                      }else{
                          alert('Error en el envío');
                      }
                       $scope.cargador=false;
                       $scope.formCambio=true;                             
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });                                    
        
    }

    $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }
 
});