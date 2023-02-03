app.controller('adminMovilCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.cveUni=$cookies.uniClave;
	$rootScope.usrLogin= $cookies.usrLogin;

	$scope.cargadorListado='table-responsive';

	$scope.formu ={
		compania:'',
		noKits:''
	}

	$scope.descontar ={
		claveAsesoria:'',
		ajustador:'',
		nuevoAjustador:'',
		noKit:''
	}
	$scope.vernuevoAjustador=false;


	busquedas.listadoLotes().success(function(data){                        
		$scope.listLotes=data;
		console.log(data);
    });

	busquedas.compania().success(function(data){                        
		$scope.listCompania=data;
		console.log(data);
    });




	$scope.crearLote = function(){
		$('#myModal').modal(); 			        
	}

	$scope.generarLote = function(){
		console.log($scope.formu);	
		$scope.cargador=true;
		$http({
			url:'api/movil.php?funcion=guardaLote&usr='+$rootScope.usrLogin,
			method:'POST', 
			contentType: 'application/json', 
			dataType: "json", 
			data: $scope.formu
			}).success( function (data){  
				$scope.listLotes = data; 
				console.log(data);  
				       
			}).error( function (xhr,status,data){
				$scope.mensaje ='no entra';            
				alert('Error');
			});  
	}

	$scope.listarKits = function(noLote){
		busquedas.listarKits(noLote).success(function(data){                        
			$scope.listKitsLote=data;
			console.log(data);
		});		        
	}

	$scope.imprimirListadoLote = function(noLote){
		var fileName = 'Receta - '+noLote;
		var uri = 'http://medicavial.net/mvnuevo/api/classes/pdf/listadoLote.php?lot='+noLote;
		var link = document.createElement("a");    
		link.href = uri;
		//set the visibility hidden so it will not effect on your web-layout
		link.style = "visibility:hidden";
		link.download = fileName + ".pdf";	
		//this part will append the anchor tag and remove it after automatic click
		document.body.appendChild(link);
		link.click();           
		document.body.removeChild(link);
	}




	$scope.guardarKitOcupado = function(noLote){
		$http({
			url:'api/movil.php?funcion=guardaKitUsado&usr='+$rootScope.usrLogin,
			method:'POST', 
			contentType: 'application/json', 
			dataType: "json", 
			data: $scope.descontar
			}).success( function (data){  
				console.log(data);  
				$scope.listKitsLote=data;
				       
			}).error( function (xhr,status,data){
				$scope.mensaje ='no entra';            
				alert('Error');
			});  
	}


});

