app.controller('homeCtrl', function($scope, $rootScope,$cookies,busquedas,$http) {   
   $scope.init = function() {
   		$rootScope.usrLogin= $cookies.usrLogin;
	    
	    
	    $rootScope.uniClave = $cookies.uniClave;
	    $scope.cargador=true;
	    $rootScope.ruta='api/Avisos/';
	    $rootScope.ruta1='#toolbar=0';
	    $scope.orden='uniNombre';  	   
	    $scope.stringRecibos = [];

	    busquedas.listaAvisos().success(function(data){  
	         console.log(data);           
	         $rootScope.avisos  = data;
	         $rootScope.noAviso = $rootScope.avisos.length; 
	         $scope.cargador=false;	         
	    });	    
	    /*$http.get('api/catalogos.php?funcion=verMedico&usr='+$rootScope.usrLogin).success(function (data){ 
			if(data==1){
				$("#estatusMedico").modal('show');
			}
		}); */
		$http.get('api/catalogos.php?funcion=Unidad&uni='+$rootScope.uniClave).success(function (data){ 
			$scope.uniNombre=data.Uni_nombrecorto;
		}); 

		$http.get('api/catalogos.php?funcion=getMedico&usr='+$rootScope.usrLogin).success(function (data){ 
			$scope.esMedico=data;
			console.log(data);
		}); 

		if($rootScope.usrLogin!='algo'){
			$http.get('api/catalogos.php?funcion=membresiasEmitidas&uni='+$rootScope.uniClave).success(function (data){ 
				$scope.totalMem 	= data.total;
				$scope.mesMem 		= data.mes;
				$scope.diaMem 		= data.dia;
			});  
			$http.get('api/catalogos.php?funcion=recibosEmitidos&uni='+$rootScope.uniClave).success(function (data){
				console.log(data); 
				$scope.totalRecibo 	= data.total;
				$scope.mesRecibo 		= data.mes;
				$scope.diaRecibo 		= data.dia;
			});  
			$http.get('api/catalogos.php?funcion=recibosMembresias&uni='+$rootScope.uniClave).success(function (data){
				console.log(data); 
				$scope.totalReciboSA 	= data.total;
				$scope.mesReciboSA 		= data.mes;
				$scope.diaReciboSA 		= data.dia;
			});
		}else{
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


		        $scope.chart = c3.generate({
		        	bindto: '#chart2',
		        	donut: {
						  title: 'MEMBRESIAS'
							},
				    data: {
				        x: 'x',
				        columns: [
				            unidades,
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
				          	label: {
				            //text: 'Conteo',
				            //position: 'outer-middle'
				          }
				        }
				    },
				    color: {
				        pattern: ['#FF9A00']
				    }
				});


		        $scope.chart = c3.generate({
		        	bindto: '#chart3',
		        	donut: {
						  title: 'INGRESOS'
							},
				    data: {
				        x: 'x',
				        columns: [
				            unidades,
				            ingresos,
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
				        		values: [''],
				        		format: d3.format("$,")
				        	}
				          // show: false
				          // label: {
				          //   text: 'Conteo',
				          //   position: 'outer-middle'
				          // }
				        }
				    },
				    color: {
				        pattern: ['#2ca02c', '#98df8a']
				    }
				});   	
			});
		$scope.loadGraficas=false;
		}  
		$rootScope.permisos=JSON.parse($cookies.permisos); 
	}	    
});
