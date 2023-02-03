app.controller('controlParticularesCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio=   $cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador = true;
  $scope.cargador1 = false;
  $scope.msj =false;
  $scope.msjRecibos=false;
  $http.get('api/particulares.php?funcion=getControlParticulares').success(function (data){                                 
      $scope.cargador = false;
      $scope.listadoMedicos=data; 
      console.log(data);              
  });
  $scope.verDetalle = function(doc, dia){     
      $scope.cargador1=true;
      console.log(doc);
      if(dia<=0){
        $scope.cargador1=false;
        $scope.msj=true;
        $scope.doc=''; 
      }else{
        $http.get('api/particulares.php?funcion=getPacientesMedico&med='+doc).success(function (data){                                 
            $scope.cargador1 = false;
            $scope.doc=data; 
            $scope.msj=false;   
            console.log(data); 

        });
      }     
  }  

  $scope.verRecibos = function(folio){     
              $http.get('api/particulares.php?funcion=getRecibosFolio&fol='+folio).success(function (data){                                 
            if(data.length>0){              
              $scope.listaRecibos=data;
              $scope.msjRecibos=false;
            }else{
              $scope.listaRecibos='';
              $scope.msjRecibos=true;
            }             
                     
        });      
  }  
});