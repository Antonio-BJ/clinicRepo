app.controller('logCambiosCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  
$scope.cargador=true;
$scope.carga=false;
$scope.datosCambio={
  nombre: '',
  descripcion: '',
  usuario: $rootScope.usrLogin,
}

//trae los ultimos registros
    $http({
        url:'api/logCambios.php?funcion=getCambios',
        //method:'POST', 
        //contentType: 'application/json', 
        dataType: "json", 
        //data: $rootScope.uniClave
        }).success( function (data){
          $scope.listadoCambios=data;
          //console.log($scope.listadoCambios[0]);
          $scope.cargador=false;

    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
        $scope.cargador=false;
    });  

$scope.abreModal = function(){
  $scope.carga=false;
  $('#modalModificacion').modal('show');
}

$scope.nuevoCambio = function(){
    $scope.carga=true;
    //console.log($scope.datosCambio);

    $http({
        url:'api/logCambios.php?funcion=nuevoCambio',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.datosCambio
        }).success( function (data){                                                      
            //console.log(data);
            $('#modalModificacion').modal('hide');
            $scope.carga=false; 

                  $http({
                      url:'api/logCambios.php?funcion=getCambios',
                      dataType: "json", 
                      }).success( function (data){
                        $scope.listadoCambios=data;
                        $scope.carga=false;
                          //trae los ultimos registros
                              $http({
                                  url:'api/classes/MailCambios.php',
                                  method:'POST', 
                                  contentType: 'application/json', 
                                  dataType: "json", 
                                  data: $scope.listadoCambios[0]
                                  }).success( function (data){
                                    //$scope.listadoCambios=data;
                                    //console.log($scope.listadoCambios[0]);
                                    //$scope.cargador=false;

                              }).error( function (xhr,status,data){
                                  $scope.mensaje ='no entra';            
                                  alert('Error');
                                  $scope.cargador=false;
                              });  

                  }).error( function (xhr,status,data){
                      $scope.mensaje ='no entra';            
                      alert('Error');
                      $scope.carga=false;
                  });

            $scope.datosCambio={
              nombre: '',
              descripcion: '',
              usuario: $rootScope.usrLogin,
            }

    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';
        alert('Error');
        $scope.carga=false;
    });                          
}




/*-------------------------------- EXCEL --------------------------------*/
   $scope.excelReportesRed = function(){  
            console.log($scope.reporte);
            console.log('entro');             
            var fileName = 'Reporte';
            var uri = 'api/classes/generaExcelRegistros.php?funcion=excelReportesRed&uni='+$scope.reporte.unidad+'&cia='+$scope.reporte.compania+
                      '&fechaIni='+$scope.reporte.fechaIni+'&horaIni='+$scope.reporte.horaIni+'&fechaFin='+$scope.reporte.fechaFin+
                      '&horaFin='+$scope.reporte.horaFin+'&UniClave='+$rootScope.uniClave;
            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".xlsx";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();           
            document.body.removeChild(link);
        }


/*-------------------------------- PDF --------------------------------*/
    $scope.pdfReportesRed = function(){  
            console.log($scope.reporte.compania);             
            var fileName = 'Reporte';
            var uri = 'api/classes/generaReporteRegistros.php?funcion=pdfReportesRed&uni='+$scope.reporte.unidad+'&cia='+$scope.reporte.compania+
                      '&fechaIni='+$scope.reporte.fechaIni+'&horaIni='+$scope.reporte.horaIni+'&fechaFin='+$scope.reporte.fechaFin+
                      '&horaFin='+$scope.reporte.horaFin+'&UniClave='+$rootScope.uniClave;
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