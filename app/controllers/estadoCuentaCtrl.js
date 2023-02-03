app.controller('estadoCuentaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$upload) {
	$rootScope.usrLogin= $cookies.usrLogin;
	$scope.mensajeSin='';
	$scope.cargador = false;
 	$http.get('api/api.php?funcion=getDatosEnfermera&usr='+$rootScope.usrLogin).success(function (data){                                                                            
 		$scope.cargador = true;
     	$scope.datosEnfermera= data;
     	console.log(data);
     	 $http.get('api/api.php?funcion=getDetalle&cve='+$scope.datosEnfermera.ENF_id).success(function (data){
     	 	
     	 	if(data==false){
     	 		$scope.mensajeSin = 'No tienes ningún recibo asociado';	
     	 	}else{
     	 		$scope.detalle= data.recibos;
	    		$scope.enfDet = data.datosEnf;	
	    		$scope.mensajeSin='';    		
     	 	}                          	    	
	    	$scope.cargador = false;
	    });    
	});    

	$scope.verEnfermeras = function(Uni){ 
	    $http.get('api/api.php?funcion=getListadoEnfermeras&uni='+Uni).success(function (data){                                
	    	console.log(data);
	    	$scope.listadoEnfermeras= data;
	    });                           
    }    

    $scope.verDetalle = function(id){ 
	    $http.get('api/api.php?funcion=getDetalle&cve='+id).success(function (data){                                	    	
	    	$scope.detalle= data.recibos;
	    	$scope.enfDet = data.datosEnf;
	    	$("#myModal").modal();
	    });                           
    }      
});
