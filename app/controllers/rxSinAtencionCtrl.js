app.controller('rxSinAtencionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage, $timeout,$upload) {
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

  $scope.folioRecibo='';


	$http.get('api/api.php?funcion=getRxSinAtencion&uni='+$rootScope.cveUni).success(function (data){                                 		
		$scope.listadoRecibos=data; 
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
  

    // $http.get('api/digitales.php?funcion=listadoRX&fol='+$rootScope.folio).success(function (data){ 
    //       console.log(data);                           
    //       $scope.listaDigitales = data;
    // });   

 $scope.onFileSelect_xml = function($files) {
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
           
            console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
            $scope.progressBar = parseInt(100.0 * evt.loaded / evt.total);
          }
        }).success(function (data, status, headers, config){      
        console.log(data);                  
          $scope.rx.archivo=data.nombre;
          $scope.rx.temporal=data.temporal; 
          console.log($scope.rx.archivo+'--'+$scope.rx.temporal);           
        }).error( function (xhr,status,data){
          alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
        });
      }  
    }
  

$scope.guardaDigital = function(){
          $scope.cargador=true;          
          $scope.upload = $upload.upload({
            url:'api/digitales.php?funcion=guardaRx&fol='+ $scope.folioRecibo+'&noPlac='+$scope.rx.noPlac+'&usr='+$rootScope.usrLogin+'&inter='+$scope.rx.inter,
            method:'POST',             
            data:$scope.rx,
            file: $scope.archivo
          }).success( function (data, status, headers, config){
            $scope.progressBar=0;            
            $scope.cargador=false;          
            $scope.msjerror=false;
            if(!data.respuesta){                
            $scope.listaDigitales=data;
            $scope.rx={
              archivo:'',
              temporal:'',
              noPlac:1
            };           
          }else if(data.respuesta=='error'){
            $scope.msjerror=true;
          }
          }).error( function (xhr,status,data){            
            $scope.cargador=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
          });                            
        }



$scope.eliminaDigital = function(cont, tipo){
          //$scope.cargador=true;   
           $scope.cargador=true;    
           console.log(cont+'--'+tipo)   
          $http({
            url:'api/api.php?funcion=eliminaDigital&fol='+$scope.folioRecibo+'&cont='+cont+'&tipo='+tipo,
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
    $scope.listaDigitales='';
    console.log(fol);
		$('#SubirRx').modal();
    $scope.folioRecibo='';
		$http.get('api/digitales.php?funcion=listadoRX&fol='+fol).success(function (data){                                 					
			console.log(data); 
      $scope.folioRecibo=fol;
			$scope.listaDigitales=data;
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