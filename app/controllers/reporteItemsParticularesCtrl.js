app.controller('reporteItemsParticularesCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
/*  
  $http.get('api/reporteParticulares.php?funcion=getUnidades').success(function (data){                             
      $scope.listadoUnidades=data;
      //console.log($scope.listadoUnidades);
  });*/

$scope.cargador=false; 
$scope.cargaRep=false;
$scope.mensaje= false;
$scope.reporteIt={
  fechaIni: '',
  fechaFin: '',
}

$scope.ver={
  folioMV:1,
  producto:1,
  descripcion:0,
  fecha:1,
  recibo:1,
  precio:1,
  descuento:1,
}

var hoy = new Date(); 
          var dd   = hoy.getDate(); 
          var mm   = hoy.getMonth()+1;//enero es 0! 
          var yyyy = hoy.getFullYear();

          if (mm < 10) { mm = '0' + mm; }
          if (dd < 10) { dd = '0' + dd; }

          //armamos fecha para los datepicker
          var FechaAct = yyyy + '-' + mm + '-' +dd;          

          $scope.reporteIt.fechaIni=FechaAct; 
          $scope.reporteIt.fechaFin=FechaAct;

$scope.enviaParametros = function(){
  //console.log($scope.reporteIt);
    $scope.cargador=true;
    $http({
        url:'api/reporteItemsParticulares.php?funcion=buscaParametrosReporte',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporteIt
        }).success( function (data){                                                      
          $scope.cargador=false; 
          console.log(data);
          if(data==''){
            $scope.mensaje=true;
            $scope.listadoReporte='';
          }else{
            $scope.listadoReporte=data;
            $scope.mensaje=false;
          }
    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });                          
}


/*-------------------------------- EXCEL --------------------------------*/
   $scope.excelReportes = function(){  
        $scope.cargaRep=true;
        //console.log($scope.reporteIt.unidad);
            var fileName = 'Reporte';
            var uri = 'api/classes/xlsReporteItemsParticulares.php?funcion=excelReportes&fechaIni='+$scope.reporteIt.fechaIni+
                      '&fechaFin='+$scope.reporteIt.fechaFin;
            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".xlsx";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();           
            document.body.removeChild(link);
            $scope.cargaRep=false;
        }


/*-------------------------------- PDF --------------------------------*/
    $scope.pdfReportes = function(){  
        $scope.cargaRep=true;
        //console.log($scope.reporteIt.unidad);         
            var fileName = 'Reporte';
            var uri = 'api/classes/pdfReporteItemsParticulares.php?funcion=pdfReportes&fechaIni='+$scope.reporteIt.fechaIni+
                      '&fechaFin='+$scope.reporteIt.fechaFin;
            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".pdf";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();           
            document.body.removeChild(link);
            $scope.cargaRep=false;
        }

});