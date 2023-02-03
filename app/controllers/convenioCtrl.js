app.controller('convenioCtrl', function($scope,$rootScope,$location,$cookies,$routeParams) {
    $rootScope.permisos=JSON.parse($cookies.permisos);
    $rootScope.accPersonal=false;    
    $scope.opcion=$routeParams.opcion;    

    $scope.confirmaConvenio = function(claveConvenio){
        $location.path("/infoConvenio/"+claveConvenio);        
    }
   
});