app.controller('reporteVentasSinRegCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
  
  $http.get('api/reporteParticulares.php?funcion=getUnidades').success(function (data){                             
      $scope.listadoUnidades=data;
      //console.log($scope.listadoUnidades);
  });

$scope.cargador=false; 
$scope.cargaRep=false;
$scope.mensaje= false;
$scope.reporte={
  fechaIni: '',
  fechaFin: '',
  unidad:'',
}

$scope.ver={
  nombrePaciente:1,
  fechaRegistro:0,
  facturado:0,
  banco:0,
  tarjeta:0,
  unidad:0,
  cia:0,
  contadorVenta:0,
  fPago:0,
}

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
  //console.log($scope.reporte);
    $scope.cargador=true;
    $http({
        url:'api/reporteVentasSinReg.php?funcion=buscaParametrosReporte&unidad='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporte
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
        //console.log($scope.reporte.unidad);
            var fileName = 'Reporte';
            var uri = 'api/classes/xlsRepVentasSinReg.php?funcion=excelReportes&uni='+$scope.reporte.unidad+
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
            $scope.cargaRep=false;
        }


/*-------------------------------- PDF --------------------------------*/
    $scope.pdfReportes = function(){  
        $scope.cargaRep=true;
        //console.log($scope.reporte.unidad);         
            var fileName = 'Reporte';
            var uri = 'api/classes/pdfRepVentasSinReg.php?funcion=pdfReportes&uni='+$scope.reporte.unidad+
                      '&cia='+$scope.reporte.compania+
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
            $scope.cargaRep=false;
        }

        $scope.reimprimirRecibo = function(folio, folioRecibo,serie){          
          var fileName = "Reporte";
          var uri = 'api/classes/reimprimirReciboSinReg.php?fol='+folio+'&cveRec='+folioRecibo+'&serie='+serie;
          var link = document.createElement("a");    
          link.href = uri;
          
          //set the visibility hidden so it will not effect on your web-layout
          link.style = "visibility:hidden";
          link.download = fileName + ".pdf";
          
          //this part will append the anchor tag and remove it after automatic click
          document.body.appendChild(link);
          link.click();
          $scope.cargador=false;
          document.body.removeChild(link);            
}         

});