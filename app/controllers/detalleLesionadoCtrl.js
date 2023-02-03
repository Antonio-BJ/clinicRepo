app.controller('detalleLesionadoCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
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


	/**************************************************************************************************/





	$scope.loadGraficas=true;
			//console.log($rootScope.usrLogin);
			$http.get('api/catalogos.php?funcion=membresiasEmitidasAdmin').success(function (data){ 

				$scope.nombre ='recibos';								
				$scope.stringRecibos='';
				$scope.listadoUnidadesDatos=data;
				//console.log($scope.listadoUnidadesDatos);
				var recibos = [];
				var unidades = [];
				var membresias = [];
				var ingresos = [];
				recibos.push('Recibos con Membresia');				
				for (var i = 0; i <= $scope.listadoUnidadesDatos.length-1; i++) {					
						recibos.push($scope.listadoUnidadesDatos[i].totalRecibo);	

				}
				unidades.push('x');				
				for (var i = 0; i <= $scope.listadoUnidadesDatos.length-1; i++) {					
						unidades.push($scope.listadoUnidadesDatos[i].uniNombreOtro);	

				}
				membresias.push('Membresias Emitidas');				
				for (var i = 0; i <= $scope.listadoUnidadesDatos.length-1; i++) {					
						membresias.push($scope.listadoUnidadesDatos[i].totalMembresias);	
				}
				//console.log(membresias);

				ingresos.push('Ingresos Económicos');				
				for (var i = 0; i <= $scope.listadoUnidadesDatos.length-1; i++) {					
						ingresos.push($scope.listadoUnidadesDatos[i].ingresosTotal);	
				}
				//console.log(ingresos);
				

		        $scope.chart = c3.generate({
		        	bindto: '#chart1',
		        	donut: {
						  title: 'RECIBOS'
							},
				    data: {
				        x: 'x',
				        columns: [
				            unidades,            
				            recibos,
				            membresias,
				        ],
				        types: {
				            data1: 'area',
				            data2: 'area-spline'
				        }
				    },
				    axis: {
				        x: {
				        	label: 'Unidades',
				            type: 'category',
				            tick: {
				                format: '%Y-%m-%d'
				            }
				        },
				        y: {
				          	tick: {
				          		values: ['']
				          	},
					        //show: false,
					        //label: {
				            //text: 'Conteo',
				            //position: 'outer-middle'
				          	//}
				        }
				    },
				    color: {
				        pattern: ['#00aaaa']
				    }
				});   			


		       	
			});
		$scope.loadGraficas=false;







	/**************************************************************************************************/
	$scope.verDetalle=false;
	$scope.verDetalleCargado=false;
	$rootScope.cargador=false;	
	$scope.error=false;
	$scope.folioModal='';	
	$rootScope.permisos=JSON.parse($cookies.permisos);	
	
	$scope.buscaParametros = function(){ 
	$rootScope.cargador1=true;	 
		$http({
            url:'api/api.php?funcion=buscaParametros',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.busca
            }).success( function (data){  
            	console.log(data);                                                    
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

	$scope.verDetalleFn = function(folio){
		$scope.verDetalle=true; 
			// setTimeout(function(){
		 //        $scope.verDetalle=false; 
		 //        $scope.verDetalleCargado=true; 
		 //   },1000); 

	 	$scope.procedimientos = '';
		$scope.subsecuencias = '';
		$scope.rehabilitaciones = '';
		 busquedas.infoLesionado(folio).success(function(data){
		 	console.log(data);
		 	$scope.verDetalle=false; 
		 	$scope.verDetalleCargado=true;
		 	$scope.detallePaciente=data.datosPersonales;  
		 	$scope.primeraEtapa = data.etapa1;
		 	if(data.procedimientos){
		 		$scope.procedimientos = data.procedimientos;		 		
		 	}
		 	if(data.subsecuencias){
		 		$scope.subsecuencias = data.subsecuencias;
		 	}
		 	if(data.rehabilitaciones){
		 		$scope.rehabilitaciones = data.rehabilitaciones
		 	}



				//console.log($scope.listadoUnidadesDatos);
				var dolor = [];
				var mejoria = [];
				var membresias = [];
				var ingresos = [];
				var rehabilitaciones =['x'];

				var escala =['y',1,2,3,4,5,6,7,8,9,10];



				dolor.push('Escala de dolor');				
				for (var i = 0; i <= $scope.rehabilitaciones.length-1; i++) {					
						dolor.push($scope.rehabilitaciones[i].Rehab_dolor);
						rehabilitaciones.push(i+1);	
				}
				mejoria.push('Escala de mejoría');				
				for (var i = 0; i <= $scope.rehabilitaciones.length-1; i++) {					
						mejoria.push($scope.rehabilitaciones[i].Rehab_mejoria);	

				}				
				//console.log(membresias);

				
				//console.log(ingresos);
				

		        $scope.chart1 = c3.generate({
		        	bindto: '#grafica1',
		        	donut: {
						  title: 'RECIBOS'
							},
				    data: {
				        x: 'x',
				        columns: [
				            rehabilitaciones,            
				            dolor,
				            mejoria,
				        ],
				        types: {
				            data1: 'area',
				            data2: 'area-spline'
				        }
				    },
				    axis: {
				        x: {
				        	label: 'Rehabilitaciones',
				            type: 'category',
				            tick: {
				                format: '%Y-%m-%d'
				            }
				        },
				        y: {
				          	tick: {
				          		values: escala
				          	},
					        // show: false,
					        label: {
				            text: 'Escala',
				            position: 'outer-middle'
				          	}
				        }
				    },
				    color: {
				        pattern: ['#00aaaa','#3f81ba']
				    }
				});   			




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

