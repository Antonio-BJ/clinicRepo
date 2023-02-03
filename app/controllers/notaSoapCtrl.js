app.controller('notaSoapCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.folio= $cookies.folio;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;

  $scope.soap={
      appSoap:"",
      apxSoap:"",      
      subjetivo:"",
      objetivo:"",
      analisis:"",
      pronostico:"",
      usu_login: $cookies.usrLogin,
      uni_clave: $cookies.uniClave,
      fechaRegistro: Date(),
  };
  window.scrollTo(0,0);    
  $scope.cargador = false;


  $scope.guardarSoap = function() {
    $scope.cargador = true;
    $http({
            url:'api/notaSoap.php?funcion=guardarSoap&usr='+$rootScope.usrLogin+'&unidad='+$rootScope.uniClave+'&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.soap
            }).success( function (data){ 
            $scope.cargador = false;
            window.scrollTo(0,0);                           
            if(data.respuesta=='exito'){
                  var fileName = 'Nota Soap - '+$rootScope.folio;
                  var uri = 'api/classes/formatoNotaSoap.php?fol='+$rootScope.folio+'&cont='+data.contador;
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
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador=false;
            }); 
      }
  


});