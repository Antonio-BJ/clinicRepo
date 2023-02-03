app.controller('enDirectoCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  
  $scope.comentario={
    comentario:''
  }
  $scope.cargador=false;  
  $scope.enviado=false;
  $scope.interacted = function(field) {          
    return $scope.comentarioRH.$submitted && field.$invalid;          
  };

    $scope.enviaDatosRH = function(){ 
       if($scope.comentarioRH.$valid){  
                            
                $scope.cargador=true;               
                $http({
                      url:'api/api.php?funcion=enviaComentarioRH&usr='+$rootScope.usrLogin,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.comentario
                      }).success( function (data){                        
                        if(data.respuesta=='correcto'){               
                             $scope.enviado=true;
                            $scope.comentario.comentario=''; 
                             $scope.comentarioRH.$submitted=false;
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
        
    }

    $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }
 
});