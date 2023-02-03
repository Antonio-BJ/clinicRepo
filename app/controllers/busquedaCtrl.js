app.controller('busquedaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.cveUni=$cookies.uniClave;
	$rootScope.usrLogin= $cookies.usrLogin;
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
	$rootScope.cargador=false;
	$rootScope.cargador1=false;
	$rootScope.cargador2=false;
	$scope.bloqueoboton=false;
	$scope.error=false;
	$scope.folioModal='';	
	$rootScope.permisos=JSON.parse($cookies.permisos);	
	busquedas.listadoFolios($rootScope.cveUni).success(function(data){		
		$scope.list=data;	
		$rootScope.cargador=false;
	});
	$http.get('api/catalogos.php?funcion=getCatCancelacion').success(function (data){                                 		
		$scope.catalogoCancelados=data; 
		console.log(data);              
	});
	$scope.mandaPortada = function(folio){ 		
		$cookies.folio = folio;
        $location.path("/portada");
	}
	$scope.mandaDocumentos = function(folio, cia){ 
		webStorage.local.clear();
		webStorage.session.add('folio', folio);   		
		$cookies.folio = folio;
		if($rootScope.usrLogin=="algo" && cia==81){
			$location.path("/documentosCovid");
		}else{
			$location.path("/documentos");
		}
        
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

//busqueda solo para administrador para buscar en todas las unidades
app.controller('busquedaUniCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http) {
	

	$scope.inicio = function(){

		$scope.buscar = false;
		$scope.cveUni = '';
		$scope.busca = {
			nombre:'',
			folio:''
		}	
		$scope.error=false;

		busquedas.listaUnidades().success(function(data){
			$scope.unidades = data;
		});

		$scope.usuCan='';
		$scope.fecCan='';
		$scope.motivoCatalogo='';
		$scope.motivo='';

	}

	$scope.muestraFolios = function(){

		$scope.buscar = true;
		busquedas.listadoFolios($scope.cveUni).success(function(data){			
			$scope.list=data;
			$scope.buscar = false;	
		});
		
	}

	$scope.mandaPortada = function(folio){
		
		$cookies.folio = folio;
        $location.path("/portada");
	}
	$scope.mandaDocumentos = function(folio){  
		
		$cookies.folio = folio;
        $location.path("/documentos");
	}
	$scope.buscaParametros = function(){ 	 	
	 	$scope.buscar = true;
	 	// if ($scope.cveUni == '') {

	 	// 	alert('Necesitas seleccionar unidad');
	 	// }else{
	 		var ini= document.getElementById("fecini").value;
	 		var fin= document.getElementById("fecfin").value;
            console.log($scope.busca+" "+ini+" "+fin);

			$http({
	            url:'api/api.php?funcion=buscaParametros&cveUnidad='+$scope.cveUni+'&ini='+ini+'&fin='+fin,
	            method:'POST', 
	            contentType: 'application/json', 
	            dataType: "json", 
	            data: $scope.busca
	        }).success( function (data){    
            console.log(data);        

				if(data.respuesta!='error'){
					$scope.error=false;					
					$scope.listaFolPar=data;   
					$scope.busca={
						nombre:'',
						folio:''
					} 
					$scope.cveUni = '';
					               
				}else{
					$scope.error=true;
				}

				$scope.buscar = false;

	        }).error( function (xhr,status,data){
	            $scope.mensaje ='no entra';            
	            alert('Error');
	        });                        

	 	// }
	}

	$scope.modalCancelado = function(folio){
		$scope.folioModal=folio;
		busquedas.motivosCancelacion(folio).success(function(data){
			$scope.usuCan=data[0].Usu_nombre;
			$scope.fecCan=data[0].Exp_fcancelado;
			$scope.motivoCatalogo=data[0].CAC_nombre;
			$scope.motivo=data[0].Exp_mcancelado;
			console.log($scope.fecCan);
		});
		$('#Cancelados').modal(); 			        
	}

});