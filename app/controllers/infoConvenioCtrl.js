app.controller('infoConvenioCtrl', function($scope,$rootScope,$location,$cookies,$routeParams,$cookieStore,$http) { 
	$cookieStore.remove("convenio");  
    $scope.convenio=$routeParams.convenio; 
    $cookies.convenio = $scope.convenio;
    $cookies.rutaImgCom = 'convenio.png';
    $scope.mem={
    	folio:'',
    	nombre: ''
    }

     $scope.buscarMembresia = function(){ 

        $scope.cargador1=true;                            
        $http({
            url:'api/convenio.php?funcion=getMembresia',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.mem
            }).success( function (data){                   
                console.log(data);
                $scope.listadoMemebresias = data;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';                            
            });                            
    }

    $scope.registrarLesionado = function(folioMembresia){ 
            $cookies.folioMembresia=folioMembresia;                    
    }

});

app.controller('infoProductoCtrl', function($scope,$rootScope,$location,$cookies,$routeParams,$cookieStore,$http) { 
        
    $scope.producto = $cookies.clavePro;
    console.log($scope.producto);
     $scope.buscarMembresia = function(){ 

        $scope.cargador1=true;                            
        $http({
            url:'api/convenio.php?funcion=getMembresia',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.mem
            }).success( function (data){                   
                console.log(data);
                $scope.listadoMemebresias = data;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';                            
            });                            
    }

    $scope.registrarLesionado = function(folioMembresia){ 
            $cookies.folioMembresia=folioMembresia;                    
    }

});