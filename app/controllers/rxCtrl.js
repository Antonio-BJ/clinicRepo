app.controller('rxCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage, $timeout,$upload) {
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
	$rootScope.cargador=true;
	$rootScope.cargador1=false;
	$rootScope.cargador2=false;
	$scope.bloqueoboton=false;
	$scope.error=false;
	$scope.folioModal='';	
	$rootScope.permisos=JSON.parse($cookies.permisos);





	$http.get('api/rx.php?funcion=getSolRx&uni='+$rootScope.cveUni).success(function (data){                                 		
		$scope.sinCuestionario=data; 
		console.log(data);      
    $rootScope.cargador=false;        
	});

$scope.rx={};
    $scope.archivo='';
    $scope.msjerror=false;

  $scope.listaDigitales='';
  $scope.noPlacas = [
      {clave:1,valor:1},
      {clave:2,valor:2},
      {clave:3,valor:3},
      {clave:4,valor:4},
      {clave:5,valor:5},
      {clave:6,valor:6},
      {clave:7,valor:7},
      {clave:8,valor:8},
      {clave:9,valor:9},
      {clave:10,valor:10},      
  ];
  

    $http.get('api/digitales.php?funcion=listadoRX&fol='+$rootScope.folio).success(function (data){ 
          console.log(data);                           
          $scope.listaDigitales = data;
    });   

     $scope.onFileSelect_xml = function($files,$index) {
      for (var i = 0; i < $files.length; i++) {
        var file = $files[i];
        $scope.archivo=file;
        $scope.variable = 2;
        var amt = 0;
        $scope.upload = $upload.upload({
          url: 'api/api.php?funcion=archivo_temporal', 
          method: 'POST',
          data: $scope.factura,
          file: file,
          progress:function(evt) {

           
            
            $scope.listadoRx[$index].barra= parseInt(100.0 * evt.loaded / evt.total);
          }
        }).success(function (data, status, headers, config){                        
          $scope.rx.archivo=data.nombre;
          $scope.rx.temporal=data.temporal;           
        }).error( function (xhr,status,data){
          alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
        });
      }  
    }
  

$scope.guardaDigital = function(index){
          
          console.log($scope.rx[index]);
          $scope.cargador1=true;   
          $scope.upload = $upload.upload({
            url:'api/digitales.php?funcion=guardaRxSol&fol='+$scope.rx[index].fol+'&noPlac='+$scope.rx[index].noPlac+'&usr='+$rootScope.usrLogin+'&inter='+$scope.rx[index].inter+'&rxCve='+$scope.rx[index].Rxs_clave,
            method:'POST',             
            data:$scope.rx.$index,
            file: $scope.archivo
          }).success( function (data, status, headers, config){  
            console.log(data);                
            $scope.cargador1=false;          
            $scope.msjerror=false;
            $scope.consultaRx($scope.rx[index].fol);
            if(!data.respuesta){                                                      
            $scope.listaDigitales = data;       
            }else if(data.respuesta=='error'){
              $scope.msjerror=true;
            }
          }).error( function (xhr,status,data){            
            $scope.cargador1=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
          });                          
        }

$scope.actializarListado = function(){
  $scope.cargador=true;
  $http.get('api/rx.php?funcion=getSolRx&uni='+$rootScope.cveUni).success(function (data){                                    
    $scope.sinCuestionario=data; 
    $scope.cargador=false;
    console.log(data);              
  });
}

$scope.eliminaDigital = function(cont, tipo){
          //$scope.cargador=true;   
           $scope.cargador=true;    
           console.log(cont+'--'+tipo)   
          $http({
            url:'api/api.php?funcion=eliminaDigital&fol='+$rootScope.folio+'&cont='+cont+'&tipo='+tipo,
            method:'POST',             
            data:$scope.digital,            
          }).success( function (data, status, headers, config){ 
            $scope.cargador=false;       
            if(!data.respuesta){
              if(data==''){
                $scope.listaDigitales='';    
              }else{
                $scope.listaDigitales=data;  
              }
              
            }else{
              console.log('error en la eliminación del archivo');
            }
            /*$scope.cargador=false;          
            $scope.msjerror=false;              
            $scope.listaDigitales=data;
            $scope.digital={
                archivo:'',
                temporal:'',
                tipo:''
            };*/                     
          }).error( function (xhr,status,data){            
            $scope.cargador=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
          });                               
        
}

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

	$scope.consultaRx = function(fol){	
		$scope.cargador = true;		
		$('#SubirRx').modal();
		$http.get('api/rx.php?funcion=listadoRxSol&fol='+fol).success(function (data){                                 					
			console.log(data); 
			$scope.listadoRx=data;
  			$scope.cargador = false;					             
		});	 				
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