app.controller('solicitudesPlantillasCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$upload, uploadPlantillas) {
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  
  $scope.cargador=false;
  $scope.msjBoton='Realizar solicitud';



$scope.inicio = function(){
    $scope.getSolicitudes();
}



$scope.getSolicitudes = function() {
    $scope.cargadorInicio=true;
    $http.get('api/solicitudPlantillas.php?funcion=getSolicitudes&uni='+$rootScope.uniClave).success(function (data){
        //console.log(data);
        $scope.listadoSolicitudes=data;
        $scope.cargadorInicio=false;
    });
}

$scope.marcaEntrega = function(numSolicitud, folioMV) {
  $scope.cargador=true;
  $scope.cargadorUpload=true;
  $scope.msjBoton='Trabajando ...';

        var name = $scope.name;
        var file = $scope.file;
            //llama a servicio upload para subir los archivos
            uploadPlantillas.uploadFile(file,name,folioMV,numSolicitud).then(function(res) {
                var datosArchivo = res.data;
                //console.log(datosArchivo);
                if (datosArchivo.respuesta=='success') {
                    $scope.cargadorUpload=false;

                } else{
                  alert('Hubo un error al subir el documento');
                  $scope.cargadorUpload=false;
                };
            })

      $http({
          url:'api/solicitudPlantillas.php?funcion=marcaEntrega&idSolicitud='+numSolicitud+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          //contentType: 'application/json', 
          //dataType: "json", 
          //data: $scope.datosSolicitud
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.msjBoton='Listo';
                $scope.getSolicitudes();
                $('#modalEntrega').modal('hide');

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
                $('#modalEntrega').modal('hide');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
          $('#modalEntrega').modal('hide');
      });  

}


$scope.registraPago = function(numSolicitud, folioMV) {
  $scope.cargador=true;
      $http({
          url:'api/solicitudPlantillas.php?funcion=registraPago&idSolicitud='+numSolicitud+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          //contentType: 'application/json', 
          //dataType: "json", 
          //data: $scope.datosSolicitud
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.getSolicitudes();

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
          $('#modalEntrega').modal('hide');
      });  
}

$scope.solicitudProveedor = function(numSolicitud, folioMV) {
  $scope.cargador=true;
      $http({
          url:'api/solicitudPlantillas.php?funcion=solicitudProveedor&idSolicitud='+numSolicitud+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          //contentType: 'application/json', 
          //dataType: "json", 
          //data: $scope.datosSolicitud
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.getSolicitudes();

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
          $('#modalEntrega').modal('hide');
      });  
}

$scope.enMVoficinas = function(numSolicitud, folioMV) {
  $scope.cargador=true;
      $http({
          url:'api/solicitudPlantillas.php?funcion=enMVoficinas&idSolicitud='+numSolicitud+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          //contentType: 'application/json', 
          //dataType: "json", 
          //data: $scope.datosSolicitud
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.getSolicitudes();

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
          $('#modalEntrega').modal('hide');
      });  
}

$scope.caminoClinica = function(numSolicitud, folioMV) {
  $scope.cargador=true;
      $http({
          url:'api/solicitudPlantillas.php?funcion=caminoClinica&idSolicitud='+numSolicitud+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          //contentType: 'application/json', 
          //dataType: "json", 
          //data: $scope.datosSolicitud
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.getSolicitudes();

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
          $('#modalEntrega').modal('hide');
      });  
}

$scope.enClinica = function(numSolicitud, folioMV) {
  $scope.cargador=true;
      $http({
          url:'api/solicitudPlantillas.php?funcion=enClinica&idSolicitud='+numSolicitud+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          //contentType: 'application/json', 
          //dataType: "json", 
          //data: $scope.datosSolicitud
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.getSolicitudes();

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
          $('#modalEntrega').modal('hide');
      });  
}




$scope.abreModalEntrega = function(idSolicitud, folioMV){
  //$scope.carga=false;
  $('#modalEntrega').modal('show');
  $scope.idSolModal=idSolicitud;
  $scope.folio=folioMV;
}



/*-------------------------------- PDF --------------------------------*/
    $scope.pdfReportesRed = function(folioMV, idSolicitud){  
            var fileName = 'FileError';
            var uri = 'api/classes/formatoSolicitudPlantillas.php?funcion=soloImprime&fol='+folioMV+'&sol='+idSolicitud;
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

});




app.directive('uploaderModel', ["$parse", function ($parse) {
    return {
      restrict: 'A',
      link: function (scope, iElement, iAttrs) 
      {
        iElement.on("change", function(e)
        {
          $parse(iAttrs.uploaderModel).assign(scope, iElement[0].files[0]);
        });
      }
    };
}])

app.service('uploadPlantillas', ["$http", "$q", function ($http, $q) 
{
  this.uploadFile = function(file, name, folioMV, numSolicitud)
  {
    var deferred = $q.defer();
    var formData = new FormData();
    formData.append("name", name);
    formData.append("file", file);
    return $http.post("api/uploadPlantillas.php?folio="+folioMV+'&idSol='+numSolicitud, formData, {
      headers: {
        "Content-type": undefined
      },
      transformRequest: angular.identity
    })
    .success(function(res)
    {
      deferred.resolve(res);
    })
    .error(function(msg, code)
    {
      deferred.reject(msg);
    })
    return deferred.promise;
  } 
}])