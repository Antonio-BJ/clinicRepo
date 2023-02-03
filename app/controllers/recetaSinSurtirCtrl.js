app.controller('recetaSinSurtirCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.cargador=true;
  
  $http.get('api/notaMedica.php?funcion=listadoRecetasSinSurtir&uni='+$rootScope.uniClave).success(function (data){                                   
      $scope.listadorecetas=data;
      $scope.cargador=false;
  });
});