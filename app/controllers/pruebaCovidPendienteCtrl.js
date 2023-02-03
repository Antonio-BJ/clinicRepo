app.controller('pruebaCovidPendienteCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$upload ) {
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
	$rootScope.permisos=JSON.parse($cookies.permisos);	
	busquedas.listadoPrueba($rootScope.cveUni).success(function(data){		
		$scope.listadosCovid=data;	
		$rootScope.cargador=false;
	});

	$scope.datos = {

		resultado:'',
		nombreArc:'',
		temporal:'',
		id:''
	}

	var aut='';

	$scope.mandaDocumentos = function(folio, cia){ 
		webStorage.local.clear();
		webStorage.session.add('folio', folio);   		
		$cookies.folio = folio;
		if(cia==81){
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

	$scope.editaRest = function(id){ 

		var id = id;

		$scope.datos.id = id;

		$('#modaleditaRest').modal({ 
            keyboard: false,
            backdrop: 'static'
        });

	}

	$scope.regresaPagina = function(){
        $('#modaleditaRest').modal('hide');
        window.history.back();
    }

    $scope.onFileSelect_rest = function($files) {
      $scope.cargador=true;
       for (var i = 0; i < $files.length; i++) {
       var file = $files[i];
       $scope.archivo=file;
       $scope.variable = 2;
      var amt = 0;
      $scope.upload = $upload.upload({
         url: 'api/api.php?funcion=archivo_temporal', //upload.php script, node.js route, or servlet url
         method: 'POST',
         data: $scope.factura,
         file: file, // or list of files ($files) for html5 only

       progress:function(evt) {

           var amt =  parseInt(100.0 * evt.loaded / evt.total);
	       $scope.countTo = amt;
	       $scope.countFrom = 0;

       }

		})
		   .success(function (data, status, headers, config){ 
                        $scope.cargador=false;
						$scope.datos.nombreArc=data.nombre;
						$scope.datos.temporal=data.temporal;

					    console.log($scope.datos); 

		            }).error( function (xhr,status,data){
		                alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
		            });
		    }
		}

		$scope.guardaRest = function(){

		  $scope.cargador=true;
		  $scope.upload = $upload.upload({
		    url:'api/api.php?funcion=guardaResultados&usr='+$rootScope.usrLogin+'&result='+$scope.datos.resultado+'&id='+$scope.datos.id,
		    method:'POST',             
		    data:$scope.datos,
		    file: $scope.archivo
		  }).success( function (data, status, headers, config){             
		    $scope.cargador=false;          
		    $scope.msjerror=false;

		    alert('Se guardo tu resultado. Enviando por correo...');

			$scope.generaEnvia($scope.datos.id);

	    	busquedas.listadoPrueba($rootScope.cveUni).success(function(data){		
				$scope.listadosCovid=data;	
				$rootScope.cargador=false;
			});

			$('#modaleditaRest').modal('hide');

			
		    console.log(data);

		  }).error( function (xhr,status,data){            
		    $scope.cargador=false;          
		    $scope.mensaje ='no entra';            
		    alert('Error');
		  });                               
		}

	$scope.generaEnvia= function(id){
		console.log(id);
		$http.get('api/api.php?funcion=datosRegistroCovid&fol='+id).success(function (data){
			console.log(data);
          	$scope.nombrePx= data[0].nombre+" "+ data[0].paterno;
          	$scope.correoPx= data[0].correo;
			aut= data[0].aut;
        
			// console.log(aut);
	    	$http.get('https://busqueda.medicavial.net/api/busquedas/pdfCovid-'+id+'-'+aut).success(function(data1){
	    		console.log(data1);
	    		// if(data.pdf!='true'){
	    		// 	alert("Ocurrio un error al generar el pdf");
	    		// }else{
		    		$scope.ruta=data1.ruta;
		    		$http({

			                url:'api/api.php?funcion=enviaPruebaCovid&aut='+aut+'&nom='+$scope.nombrePx+'&ruta='+$scope.ruta+'&correo='+$scope.correoPx,
			                method:'POST',
			                contentType: 'application/json',
			                dataType: "json",
			                data: aut
			            }).success( function (data){
			                console.log(data);
			                alert("Resultados enviados por correo");
			            }).error( function (xhr,status,data){
			                $scope.mensaje ='no entra ';
			                alert('Error al enviar correo');
			            });

		            $scope.cargador=false;                
		            $scope.btnGuardar=true;
		        // }

	        }).error( function (xhr,status,data){
	            $scope.mensaje ='no entra ';
	            alert('Error al generar PDF');
	        });
	    });
    }

    $scope.HttpClient = function() {
	    this.get = function(aUrl, aCallback) {
	        var anHttpRequest = new XMLHttpRequest();
	        anHttpRequest.onreadystatechange = function() { 
	            if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
	                aCallback(anHttpRequest.responseText);
	        }

	        anHttpRequest.open( "GET", aUrl, true );            
	        anHttpRequest.send( null );
	    }
    }

});