app.controller('notaSoapCECtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.folio= $cookies.folio;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;

  $scope.soap={
      folio: $rootScope.folio,
      appSoap:"",
      apxSoap:"",      
      subjetivo:"",
      objetivo:"",
      analisis:"",
      pronostico:"",
      usu_login: $rootScope.usrLogin,
      uni_clave: $rootScope.uniClave,
  };
  window.scrollTo(0,0);    
  $scope.cargador = false;


  $scope.guardarSoap = function() {
    //console.log($scope.soap);
    $scope.cargadorGuarda = true;
    $http({
            url:'api/notaSoapCortaEst.php?funcion=guardarSoap&usr='+$rootScope.usrLogin+'&unidad='+$rootScope.uniClave+'&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.soap
            }).success( function (data){ 
              //console.log(data);
            $scope.cargadorGuarda = false;
            if(data.respuesta=='exito'){
                  var fileName = 'Nota Soap - '+$rootScope.folio;
                  var uri = 'api/classes/formatoNotaSoapCE.php?fol='+$rootScope.folio;
                  var link = document.createElement("a");    
                  link.href = uri;
                  
                  //set the visibility hidden so it will not effect on your web-layout
                  link.style = "visibility:hidden";
                  link.download = fileName + ".pdf";
                  
                  //this part will append the anchor tag and remove it after automatic click
                  document.body.appendChild(link);
                  link.click();           
                  document.body.removeChild(link);

                  window.scrollTo(0,0);
            }
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargadorGuarda=false;
            }); 
      }
  


});