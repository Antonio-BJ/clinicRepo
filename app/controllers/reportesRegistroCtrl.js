app.controller('reportesRegistroCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  
  $http.get('api/Clasificaciones.php?funcion=getUnidades').success(function (data){                             
      $scope.listadoUnidades=data;      
  });

  $http.get('api/Clasificaciones.php?funcion=getCompanias').success(function (data){
  $scope.listadoCompania=data;                             
  });

  $scope.interacted = function(field) {          
    return $scope.avisosRH.$submitted && field.$invalid;          
  };

$scope.propia='';
$scope.cargador=false; 
$scope.mensaje= false;
$scope.reporte={
  fechaIni: '',
  horaIni: '',
  fechaFin: '',
  horaFin: '',
  folio:1,
  lesionado:1,
  poliza:0,
  siniestro:0,
  reporte:0,
  aseguradora:0, 
  fecAten:1,  
  compania:'',  
  unidad:''
}

var hoy = new Date(); 
          var HH   = hoy.getHours();
          var MM   = hoy.getMinutes();
          var SS   = hoy.getSeconds();
          var dd   = hoy.getDate(); 
          var mm   = hoy.getMonth()+1;//enero es 0! 
          var yyyy = hoy.getFullYear();

          if (HH < 10) { HH = '0' + HH; }
          if (MM < 10) { MM = '0' + MM; }
          if (SS < 10) { SS = '0' + SS; }
          if (mm < 10) { mm = '0' + mm; }
          if (dd < 10) { dd = '0' + dd; }

          //armamos fecha para los datepicker
          var FechaAct = yyyy + '-' + mm + '-' +dd;          
          var HoraAct = HH + ':' + MM + ':' + SS;
          $scope.reporte.horaIni=HoraAct; 
          $scope.reporte.horaFin=HoraAct; 
          $scope.reporte.fechaIni=FechaAct; 
          $scope.reporte.fechaFin=FechaAct;

$scope.enviaParametros = function(){
    $scope.cargador=true;   
    
    if ($scope.reporte.unidad=='') {
        $scope.reporte.unidad=$rootScope.uniClave;
    };

//verifica unidades propias
    $http({
        url:'api/Clasificaciones.php?funcion=verificaUnidad&unidad='+$scope.reporte.unidad,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporte.unidad
        }).success( function (data){
          if (data.Uni_propia=='N') {
            $scope.propia='N';
          } else {
            $scope.propia='S';
          };
        //console.log(data);

    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });  


    $http({
        url:'api/Clasificaciones.php?funcion=buscaParametrosReporteRegistro&uni='+$rootScope.uniClave,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporte
        }).success( function (data){                                                      
        $scope.cargador=false; 
        console.log(data);
        if(data==''){
          $scope.mensaje=true;
        }else{
          //console.log(data);
          $scope.listadoReporte=data;
          $scope.mensaje=false;
        }
    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });                          
}

  $scope.abrirModal = function(folio, nombrePaciente){
      $scope.folioMV=folio;
      $scope.nombrePaciente=nombrePaciente;
      $http.get('api/Clasificaciones.php?funcion=getReceta&folio='+folio).success(function (data){
        console.log(data);
        $scope.receta=data;
        $('#modalReceta').modal('show');
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
            console.log('entro');             
            var fileName = 'Reporte';
            var uri = 'api/classes/generaExcelRegistros.php?funcion=excelReportes&uni='+$scope.reporte.unidad+'&cia='+$scope.reporte.compania+
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
    $scope.pdfReportes = function(){  
            console.log($scope.reporte.compania);             
            var fileName = 'Reporte';
            var uri = 'api/classes/generaReporteRegistros.php?funcion=pdfReportes&uni='+$scope.reporte.unidad+'&cia='+$scope.reporte.compania+
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