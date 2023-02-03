app.controller('reporteQualitasCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  
  
$scope.cargador=false; 
$scope.mensaje= false;
$scope.intervalo={
  fechaInicio:'',
  fechaFin:'',
  localidad:''
}

  $http.get('api/catalogos.php?funcion=getLocalidades').success(function (data){                       
    $scope.catLoc=data;                    
  });


  $scope.irDocumentos = function(){         
    $location.path("/documentos");          
  }

  $scope.mandaPortada = function(folio){ 
    webStorage.local.clear();
    webStorage.session.add('folio', folio); 
    $cookies.folio = folio;
        $location.path("/portada");
  }
  $scope.mandaDocumentos = function(folio){
    webStorage.local.clear();
    webStorage.session.add('folio', folio);   
    $cookies.folio = folio;
        $location.path("/documentos");
  }

   /*************************************    imprimir formato de RX ******************************************/
        $scope.imprimirReporte = function(){   
            console.log($scope.intervalo.fechaInicio);           
            var fileName = 'Reporte Qualitas ';
            var uri = 'api/classes/generaExcel.php?fecha1='+$scope.intervalo.fechaInicio+'&fecha2='+$scope.intervalo.fechaFin+'&localidad='+$scope.intervalo.localidad;
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

        /**********************************************************************************************************/
 
});