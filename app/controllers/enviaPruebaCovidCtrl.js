app.controller('enviaPruebaCovidCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$upload ) {
	
	$rootScope.cveUni=$cookies.uniClave;
	$rootScope.usrLogin= $cookies.usrLogin;
	$scope.busca={
		nombre:'',
		folio:''
	}

	$rootScope.cargador=false;
	$rootScope.cargador1=false;
	$rootScope.cargador2=false;
	$scope.bloqueoboton=false;
	$scope.error=false;
	$scope.folioModal='';
	$scope.aut='';
	$rootScope.permisos=JSON.parse($cookies.permisos);

	busquedas.listadoPendientesEnvio($rootScope.cveUni).success(function(data){		
		$scope.listadosCovid=data;
		$rootScope.cargador=false;
	});

	$scope.buscaParametros = function(){ 
		$rootScope.cargador1=true;	          
		$http({
            url:'api/api.php?funcion=buscaParametros&cveUnidad='+$rootScope.cveUni,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.busca
        }).success( function (data){                                           
        	$rootScope.cargador1=false;
			if(data.respuesta!='error'){
				$scope.error=false;					
				$scope.listaFolPar=data;   
				$scope.busca={
					nombre:'',
					folio:''
				}                
			}else{
				$scope.error=true;
			}
        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });                        
	}

	$scope.abreConfirmacion = function(id,aut){ 
		$http.get('api/api.php?funcion=datosRegistroCovid&fol='+id+'&aut='+aut).success(function (data){
			console.log(data);
			$scope.id=id;
			$scope.aut=aut;
          	$scope.nombrePx= data[0].nombre+" "+ data[0].paterno;
          	$scope.correoPx= data[0].correo;
      	});

		$('#modalConfirma').modal({ 
            keyboard: false,
            backdrop: 'static'
        });

        $scope.aut=aut;
	}

	$scope.regresaPagina = function(){
        $('#modalConfirma').modal('hide');
    }

    $scope.generaEnvia= function(){
    	$rootScope.cargador1=true;
    	$http.get('http://busqueda.medicavial.net/api/busquedas/pdfCovid-'+$scope.id+'-'+$scope.aut).success(function(data){
    		$scope.ruta=data.ruta;
    		$http({

	                url:'api/api.php?funcion=enviaPruebaCovid&aut'+$scope.aut+'&nom='+$scope.nombrePx+'&ruta='+$scope.ruta+'&correo='+$scope.correoPx,
	                method:'POST',
	                contentType: 'application/json',
	                dataType: "json",
	                data: $scope.aut
	            }).success( function (data){
	                console.log(data);
	                $scope.mensaje = data.respuesta;
	                alert("Mensaje enviado");
	                $rootScope.cargador1=false;                
	                $scope.btnGuardar=true;
	                $('#modalConfirma').modal('hide');

	                $rootScope.cargador=true;
	                busquedas.listadoPendientesEnvio($rootScope.cveUni).success(function(data){		
						$scope.listadosCovid=data;	
						$rootScope.cargador=false;
					});
	            }).error( function (xhr,status,data){
	                $scope.mensaje ='no entra ';
	                alert('Error al registrar');
	            });

            $scope.cargador=false;                
            $scope.btnGuardar=true;

        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra ';
            alert('Error al registrar');
        });
    }

});