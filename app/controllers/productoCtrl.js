app.controller('productoCtrl', function($scope,$rootScope,$location,$cookies,$http) {
	$rootScope.cveUnidadAlternativa!='';
	$scope.cveUnidad= $cookies.uniClave;
	$rootScope.zona = $cookies.zona;
	$scope.clave=$cookies.clave;
	$rootScope.cveUnidadAlternativa = $cookies.cveUnidadAlternativa;
	$scope.localidad=0;

	if($scope.clave==65){
		$('#anahuac').modal(); 
	}

	if($scope.clave==651){
		$('#prevem').modal(); 

	}	

	if($scope.clave==55){
		$('#metlife').modal(); 
	}
	
	if($scope.clave==31){
		$('#hir').modal(); 
	}
	console.log($cookies.clave);
	if($rootScope.cveUnidadAlternativa!=''){
		$http.get('api/catalogos.php?funcion=verZona&CveUnidadAlter='+$rootScope.cveUnidadAlternativa).success(function (data){                                                              
           $rootScope.zona=data;
           console.log($rootScope.zona);
  		});
  		$http.get('api/Clasificaciones.php?funcion=verLocalidad&uni='+$scope.cveUnidadAlternativa).success(function (data){                       
	       	$scope.localidad=data;  
	       	console.log($scope.localidad);     	    
	    }); 
	    $scope.cveUnidad=$rootScope.cveUnidadAlternativa;
	}else{
		$rootScope.zona = $cookies.zona;
		$http.get('api/Clasificaciones.php?funcion=verLocalidad&uni='+$scope.cveUnidad).success(function (data){                       
	       	$scope.localidad=data;  
	       	console.log($scope.localidad);     	    
	    });
	}

	console.log($rootScope.zona);	
	$scope.registraLesionado = function(claveProducto){
		if($scope.clave==651){
			$cookies.clave = 65;      	
		}
		rutaImgProd = $scope.validarutaProducto(claveProducto);
    	$cookies.rutaImgPro = rutaImgProd;    
    	$cookies.clavePro = claveProducto;      
        	$location.path("/registra");
    }
    $scope.detalleProducto = function(claveProducto){
		rutaImgProd = $scope.validarutaProducto(claveProducto);
    	$cookies.rutaImgPro = rutaImgProd;    
    	$cookies.clavePro = claveProducto;      
        	$location.path("/infoProducto");
    }    
    $scope.validarutaProducto = function(claveProducto){
		switch (claveProducto) {
	    case 1:
	        imgPro="av.jpg";
	        break;
	    
	    case 2:
	        imgPro="ap.jpg";
	        break;
	    case 3:
	        imgPro="es.jpg";
	        break;
	    case 4:
	        imgPro="rh.jpg";
	        break;
	    case 5:
	        imgPro="rh.jpg";
	        break;
	    case 6:
	        imgPro="sq.jpg";
	        break;
	    case 7:
	        imgPro="sn.jpg";
	        break;
	    case 8:
	        imgPro="sn.jpg";
	        break;
	    case 9:
	    	imgPro="av+.jpg";
	    	break;
	   	case 12:
	    	imgPro="av++.jpg";
	    	break;	 
	    case 13:
	    	imgPro="rh.jpg";
	    	break;
	    case 15:
	    	imgPro="ae.jpg";
	    	break;
	    case 16:
	    	imgPro="dn.jpg";
	    	break;
		}
		return imgPro;
    }

});