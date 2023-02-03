app.controller('listadoMovilCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.cveUni=$cookies.uniClave;
	$rootScope.usrLogin= $cookies.usrLogin;

	// $('#modalListadoMovil').modal();

	$scope.init = function() {
		window.setInterval(
			function(){
			// Sección de código para modificar el DIV
			// $("#miDiv").text(variable);
			$scope.ruta = $location.absUrl();
			$scope.cargadorListado='table-responsive csspinner shadow oval right';
			// Ejemplo: Cada dos segundos se imprime la hora
			//console.log($scope.ruta);
			if($scope.ruta=='http://www.medicavial.net/mvnuevo/#/listadoMovil'){
				$http.get('api/movil.php?funcion=getlistadoMovil&usr='+$rootScope.usrLogin).success(function (data){                                 		
					$scope.listadoMovil=data; 
					console.log(data);    
					$scope.cargadorListado='table-responsive';          
				});
			}
			
		}
		// Intervalo de tiempo
		,60000);
	
	}

	swal({title:"", 
          text:"Estimado doctor recuerda que para atender una videollamada es indispensable portar tu bata, poner el fondo de la compañía de seguros correspondiente y verificar que tu espacio de trabajo esté limpio, ordenado y que de una buena imagen.",  
          icon: "info",
          type: "info",
          confirmButtonColor: "#629AFF", 
          ConfirmButtonText: "Cerrar",
          dangerMode: false});

	$scope.cargadorListado='table-responsive';

	// Función de javascript para ejecutar repetidamente
	
	
	$http.get('api/movil.php?funcion=getlistadoMovil&usr='+$rootScope.usrLogin).success(function (data){                                 		
		$scope.listadoMovil=data; 
		console.log(data);              
	});
	$scope.mandaPortada = function(folio){ 		
		$cookies.folio = folio;
        $location.path("/portada");
	}
	$scope.mandaDocumentos = function(folio){ 

		$http.get('api/movil.php?funcion=modificaAtencion&fol='+folio).success(function (data){                                 		
			// $scope.listadoMovil=data; 
			console.log(data);
			if(data=='exito'){
				webStorage.local.clear();
				webStorage.session.add('folio', folio);   		
				$cookies.folio = folio;
				$location.path("/documentosMovil");
			}else{
				alert('Error en insersión');
			}
			             
		});
		
	}
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
	$scope.abreModalCancelacion = function(folio){
		$scope.folioModal=folio;
		$('#myModal').modal(); 			        
	}

	$scope.enviaDatosCancelacion = function(){ 
		$rootScope.cargador2=true;
		$scope.bloqueoboton=true;
		$scope.datos.folio=$scope.folioModal;		
		$http({
            url:'api/api.php?funcion=enviaDatosCancelacion&usr='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.datos
            }).success( function (data){         
            	if(data=='exito'){
            		$scope.bloqueoboton=false;
            		$rootScope.cargador2=false;
            		$scope.verMensaje=true;
            		$scope.enviado=true;
            		$scope.datos.folio=''; 
            		$scope.datos.motivo=''; 
            		$scope.datos.motivoCat='';
            		$scope.datos.folioSus=''; 
            		$scope.datos.Obs='';             		                            
                        setTimeout(function(){
                       	busquedas.listadoFolios($rootScope.cveUni).success(function(data){
                       		$rootScope.cargador=false;		
							$scope.list=data;	
							$scope.verMensaje=false;
						});
						$('#myModal').modal('hide');
                      },3000);  
            	} else if(data=='error'){
            		alert('hubo un error en el envio, intentalo nuevamente!!');
            		$scope.bloqueoboton=false;
            	}	else{
            		alert('hubo un error en el envio, intentalo nuevamente!!');
            		$scope.bloqueoboton=false;
            	}                                                         	
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });  
	}

});

