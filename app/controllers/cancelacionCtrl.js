app.controller('cancelacionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {

  $scope.inicio = function(){
	$scope.cveUni = '';
	$scope.busca = {
		nombre:'',
		folio:''
	}	
	$scope.error=false;
  }
  $scope.buscar = false;
  $rootScope.cveUni=$cookies.uniClave;
	$rootScope.usrLogin= $cookies.usrLogin;
	$scope.busca={
		nombre:'',
		folio:'',
		poliza:'',
		siniestro:'',
		reporte:''
	}
	$scope.datos={
		folio:'',
		motivo:'',
		motivoCat:'',
		folioSus:'',
		Obs:''
	}	
	$rootScope.cargador=false;
	$rootScope.cargador1=false;
	$rootScope.cargador2=false;
	$scope.bloqueoboton=false;
	$scope.error=false;
	$scope.folioModal='';	
	$rootScope.permisos=JSON.parse($cookies.permisos);	
	$http.get('api/catalogos.php?funcion=getCatCancelacion').success(function (data){                                 		
		$scope.catalogoCancelados=data; 
		console.log(data);              
	});
	$scope.mandaPortada = function(folio){ 		
		$cookies.folio = folio;
        $location.path("/portada");
	}
	$scope.mandaDocumentos = function(folio){ 
		webStorage.local.clear();
		webStorage.session.add('folio', folio);   		
		$cookies.folio = folio;
        $location.path("/documentos");
	}
	$scope.buscaParametros = function(){ 
  $rootScope.cargador1=true;	
  $scope.buscar = true; 
  $scope.listaFolPar='';   
	
  $http({
                url:'api/cancelacion.php?funcion=buscaParametros',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.busca
            }).success( function (data){                            
              $scope.buscar = false; 
              console.log(data);
				if(data.respuesta!='error'){
					$scope.error=false;					
					$scope.listaFolPar=data;   
					             
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

  $scope.abreModalActivacion = function(folio){
		$scope.folioModal=folio;
		$('#myModalActivacion').modal(); 			        
  }
	$scope.enviaDatosCancelacion = function(){ 
		$rootScope.cargador2=true;
		$scope.bloqueoboton=true;
		$scope.datos.folio=$scope.folioModal;		
		$http({
            url:'api/cancelacion.php?funcion=enviaDatosCancelacion&usr='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.datos
            }).success( function (data){ 
				console.log(data);        
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
                  $scope.buscaParametros();
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

	$scope.enviaDatosActivacion = function(){ 
		$rootScope.cargador2=true;
		$scope.bloqueoboton=true;
		$scope.datos.folio=$scope.folioModal;		
		$http({
            url:'api/cancelacion.php?funcion=enviaDatosActivacion&usr='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.datos
            }).success( function (data){ 
				console.log(data);        
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
                  $scope.buscaParametros();
                  $('#myModalActivacion').modal('hide');
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

	$scope.verDetalle = function(folio,cancelado){
		$scope.mensajeCancelado='';
		console.log(cancelado);
		if(cancelado==1){
			$("#modalDetalle").modal(); 
			$http.get('api/catalogos.php?funcion=getDetalleCanelacion&fol='+folio).success(function (data){                                 		
				$scope.detalleCancelacion = data;
				console.log(data);
					            
			});
		}else{
			$scope.mensajeCancelado ='El folio está activo';
		}
		
	}

});

