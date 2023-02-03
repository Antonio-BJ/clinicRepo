app.controller('monitorCuestionarioCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.cveUni=$cookies.uniClave;
	$rootScope.usrLogin= $cookies.usrLogin;

	var hoy = new Date();
	var dd = hoy.getDate();
	var mm = hoy.getMonth()+1; //hoy es 0!
	var yyyy = hoy.getFullYear();

	if(dd<10) {
	    dd='0'+dd
	} 

	if(mm<10) {
	    mm='0'+mm
	} 

	$scope.fecha = dd + " - " + mm + " - " + yyyy;
	$scope.busca={
		nombre:'',
		folio:''
	}
	$scope.datos={
		folio:'',
		motivo:'',
		motivoCat:'',
		folioSus:'',
		Obs:''
	}	
	$scope.verContenedor = false;
	$rootScope.cargador=false;
	$rootScope.cargador1=false;
	$rootScope.cargador2=false;
	$scope.bloqueoboton=false;
	$scope.error=false;
	$scope.folioModal='';	
	$scope.sinCuestionario='';
	$rootScope.permisos=JSON.parse($cookies.permisos);	
	busquedas.listadoFolios($rootScope.cveUni).success(function(data){		
		$scope.list=data;	
		$rootScope.cargador=false;
	});
	$http.get('api/catalogos.php?funcion=getCatCancelacion').success(function (data){                                 		
		$scope.catalogoCancelados=data; 
		//console.log(data);              
	});
	

	$http.get('api/catalogos.php?funcion=getMonitorCuestionarios&uni='+$rootScope.cveUni).success(function (data){                                 		
		$scope.conteos=data; 		        
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
	$scope.verSinCaptura = function(){
		$scope.verContenedor = true;
		$rootScope.cargador=true;
		console.log($scope.sinCuestionario);
		if(!$scope.sinCuestionario){
			$http.get('api/catalogos.php?funcion=getSinCuestionario&uni='+$rootScope.cveUni).success(function (data){                                 		
				console.log(data);
				$scope.sinCuestionario=data; 
				$rootScope.cargador=false;			     
			});
		}else{
			$rootScope.cargador=false;	
		}

	}
	$scope.verCapturados = function(){
		$scope.verContenedor = true;
		$rootScope.cargador=true;
		if(!$scope.conCuestionario){
			$http.get('api/catalogos.php?funcion=getConCuestionario&uni='+$rootScope.cveUni).success(function (data){                                 		
				$scope.conCuestionario=data; 
				$rootScope.cargador=false;	
				console.log(data);		     
			});
		}else{
			$rootScope.cargador=false;	
		}
	}
	$scope.VerTodos = function(){
		$scope.verContenedor = true;
		$rootScope.cargador=true;		
		if(!$scope.Todo){
			$http.get('api/catalogos.php?funcion=getTodoCuestionario&uni='+$rootScope.cveUni).success(function (data){                                 		
				$scope.Todo=data; 
				console.log($scope.Todo);
				$rootScope.cargador=false;						     
			});
		}else{
			$rootScope.cargador=false;	
		}
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