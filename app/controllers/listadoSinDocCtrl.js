app.controller('listadoSinDocCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio=   $cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  //$rootScope.rutaPro= $cookies.rutaImgPro;
  
  $scope.reporte={
    compania:'',
    unidad:''
  }
  
  $scope.mensaje=false;
  $http.get('api/Clasificaciones.php?funcion=getUnidades').success(function (data){                             
      $scope.listadoUnidades=data;
      console.log(data);
  });
  $scope.cargador = false;

  $http.get('api/Clasificaciones.php?funcion=getCompanias').success(function (data){
      console.log(data);
  $scope.listadoCompania=data;                             
  });
  
    $scope.generaReporteSinDoc = function(){ 
            $scope.cargador=true;                          
                $scope.cargador=true;               
                $http({
                      url:'api/Clasificaciones.php?funcion=listadoSinDocumentacion&usr='+$rootScope.usrLogin,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.reporte
                      }).success( function (data){ 
                        $scope.cargador = false;                
                        if(data.respuesta){                        
                            $scope.comentario.comentario='';
                        }else{
                            if(data==''){
                              $scope.mensaje=true; 
                            }else{
                              $scope.mensaje=false;
                              $scope.listadoSinCla=data;  
                            }
                            
                        }                         
                                            
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
                                   
        
    }

     $scope.exportarReporte = function(){               
            var fileName = 'Reporte Qualitas ';
            var uri = 'api/classes/generaExcelSinDoc.php?uni='+$scope.reporte.unidad+'&cia='+$scope.reporte.compania;
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

    $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }
 
});