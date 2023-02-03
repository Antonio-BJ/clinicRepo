app.controller('facturaExpressCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {

  $scope.unidad=0;
  $scope.cargaro=false;
	$http.get('api/facturaExpress.php?funcion=getUnidadesPropias').success(function (data){                       
      console.log(data);
      $scope.listadoUnidades=data;
  });
    $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }

  $scope.listadoFoliosUnidad = function(){
    $scope.cargador=true;      
    $http.get('api/facturaExpress.php?funcion=getFoliosUnidad&unidad='+$scope.unidad).success(function (data){                               
      $scope.cargador=false;
      if(!data){
        $scope.listadoFolioUnidad='';  
      }else{
        $scope.listadoFolioUnidad=data;  
      }          
    });
  }

  $scope.mandaDocumentos = function(folio){   
    $cookies.folio = folio;
        $location.path("/documentos");
  }
 
});