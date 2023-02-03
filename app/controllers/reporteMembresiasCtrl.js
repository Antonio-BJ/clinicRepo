app.controller('reporteMembresiasCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  //$rootScope.rutaAse= $cookies.rutaImgCom; 
  $rootScope.folio=   $cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  //$rootScope.rutaPro= $cookies.rutaImgPro;
  
  $http.get('api/Clasificaciones.php?funcion=getUnidades').success(function (data){                             
      $scope.listadoUnidades=data;
  });

  $http.get('api/Clasificaciones.php?funcion=getCompanias').success(function (data){
  $scope.listadoCompania=data;                             
  });

  $scope.interacted = function(field) {          
    return $scope.avisosRH.$submitted && field.$invalid;          
  };

$scope.cargador=false; 
$scope.mensaje= false;
$scope.reporte={
  fechaIni: '',
  fechaFin: '',
  compania:'',  
  unidad:'',
  //unidad: $rootScope.uniClave,
}

//habilita y deshabilita la seleccion de unidades
if ($rootScope.uniClave==8) {
  $scope.opUni='si';
  $scope.mensajeUni='Seleccione unidad';
} else{
  $scope.opUni='no';
  $scope.mensajeUni='No se permite seleccionar otra unidad';
  $scope.reporte.unidad=$rootScope.uniClave;
};

var hoy = new Date(); 
          var dd   = hoy.getDate(); 
          var mm   = hoy.getMonth()+1;//enero es 0! 
          var yyyy = hoy.getFullYear();

          if (mm < 10) { mm = '0' + mm; }
          if (dd < 10) { dd = '0' + dd; }

          //armamos fecha para los datepicker
          var FechaAct = yyyy + '-' + mm + '-' +dd;          

          $scope.reporte.fechaIni=FechaAct; 
          $scope.reporte.fechaFin=FechaAct;

$scope.enviaParametros = function(){
  //console.log($scope.reporte.unidad);
  //console.log($scope.reporte);
    $scope.cargador=true;
    $http({
        url:'api/reporteMembresias.php?funcion=buscaParametrosReporte',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporte
        }).success( function (data){
          console.log(data);                                                      
          $scope.cargador=false; 
          //console.log(data);
          if(data==''){
            $scope.mensaje=true;
          }else{
            $scope.listadoReporte=data;
            $scope.mensaje=false;
          }
    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });                          
}

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

/*-------------------------------- EXCEL --------------------------------*/
   $scope.excelReportes = function(){  
    //console.log($scope.reporte.unidad);
            var fileName = 'Reporte';
            var uri = 'api/classes/generaExcelRepMembresias.php?funcion=excelReportes&uni='+$scope.reporte.unidad+
                      '&fechaIni='+$scope.reporte.fechaIni+'&fechaFin='+$scope.reporte.fechaFin;
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
    $scope.pdfReportes = function(){  
      //console.log($scope.reporte.unidad);         
            var fileName = 'Reporte';
            var uri = 'api/classes/generaPFDRepMembresias.php?funcion=pdfReportes&uni='+$scope.reporte.unidad+
                      '&fechaIni='+$scope.reporte.fechaIni+'&fechaFin='+$scope.reporte.fechaFin;
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