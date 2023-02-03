app.controller('cambioUnidadCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.formCambio=false;
  $scope.cargador=false;
  $scope.cambioUnidad=false;
  $scope.formularios={};  	
	$scope.cambio={
        uniOrigen:'',
        uniActual:'',
        quienSol:'',
        quienAuto:'',
        unidad:'',
        motivo:'',
        diagnostico:'',
        observaciones:''
    };
    $scope.archivo='';
    $scope.msjerror=false;
    busquedas.unidadDetalle($rootScope.folio).success(function(data){ 
        console.log(data);        
        $scope.origen=data.origen;
        $scope.actual=data.actual;
        $scope.cambio.uniOrigen=data.cveOrigen;
        $scope.cambio.uniActual=data.cveActual;
    });
    busquedas.quienAutoriza().success(function(data){                 
        $scope.quienAut=data;
    });
    busquedas.unidadDestino().success(function(data){                        
        $scope.uniDestino=data;
    });
    busquedas.motivo().success(function(data){                                
        $scope.motivo=data;
    });
     
    $scope.interacted = function(field) {
          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
          return $scope.formularios.formCambioU.$submitted && field.$invalid;          
    };


    $scope.enviaDatos = function(){            
            if($scope.formularios.formCambioU.$valid){              
              $scope.cargador=true;
              $http({
                    url:'api/api.php?funcion=guardaDatosCambio&fol='+$rootScope.folio+"&usr="+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.cambio
                    }).success( function (data){   
                       console.log(data);  
                       if(data.respuesta=='exito'){
                         $scope.formCambio=true; 
                         $scope.cargador=false;
                         $scope.cambioUnidad=true;

                       }else{

                       }                   
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