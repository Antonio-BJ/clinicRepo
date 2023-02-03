app.controller('loginCtrl', function($scope, $rootScope, sesion) {

    $scope.inicio = function(){
        
        $scope.user = '';
        $scope.psw = '';
        $rootScope.mensaje = '';
        $rootScope.cerrar = true;
        
    }
    
    $scope.login = function(){
        
        $('#boton').button('loading')
        $rootScope.mensaje = '';
        sesion.login($scope.user,$scope.psw);
        
    }
    
});