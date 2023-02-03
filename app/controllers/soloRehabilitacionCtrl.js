app.controller('soloRehabilitacionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;


  
  $http.get('api/Clasificaciones.php?funcion=getUnidades').success(function (data){                             
      $scope.listadoUnidades=data;      
  });

  $http.get('api/Clasificaciones.php?funcion=getCompanias').success(function (data){
  $scope.listadoCompania=data;                             
  });

  $scope.interacted = function(field) {          
    return $scope.avisosRH.$submitted && field.$invalid;          
  };

  $scope.cargador=true;   
    

  //verifica unidades propias
     

$scope.propia='';
$scope.mensaje= false;
$scope.reporte={
  fechaIni: '', 
  fechaFin: ''
}
$scope.juegoF={
  pa: 1, 
  im: 1,
  id: 1,
  cu: 1,
  rh: 1,
  ir: 1
}
$scope.verMsj=false;

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
          var FechaIni = yyyy + '-' + mm + '-' +'01'; 
          var FechaAct = yyyy + '-' + mm + '-' +dd;          
          var HoraAct = HH + ':' + MM + ':' + SS;
          $scope.reporte.horaIni=HoraAct; 
          $scope.reporte.horaFin=HoraAct; 
          $scope.reporte.fechaIni=FechaIni; 
          $scope.reporte.fechaFin=FechaAct;
  
          $http({
            url:'api/soloRh.php?funcion=getSoloRh',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.reporte
            }).success( function (data){         
            console.log(data);
            $scope.listadoReporte = data;        
            $scope.cargador=false;   
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });  

$scope.enviaParametros = function(){
    $scope.cargador=true;   
    

//verifica unidades propias
    $http({
        url:'api/soloRh.php?funcion=getSoloRh',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporte
        }).success( function (data){         
        console.log(data);
        $scope.listadoReporte = data;        
        $scope.cargador=false;   
        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });  
                         
}

  $scope.abrirModalDetalle = function(folio, nombrePaciente){
      $scope.folioMV=folio;
      $scope.nombrePaciente=nombrePaciente;
      $('#modalReceta').modal('show');
      $http.get('api/soloRh.php?funcion=getRecibo&folio='+folio).success(function (data){
        console.log(data);
        $scope.listadoCarrito='';
        if(data.length>0){
          $scope.listadoCarrito=data;
          $scope.verMsj=false;
        }
        else{
          $scope.verMsj=true;
        }
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


  $scope.imprimirJuegoFacturacion = function(folio){

    console.log($scope.juegoF);

    $http({
      url:'api/soloRh.php?funcion=imprimirJuego&fol='+folio,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.juegoF
      }).success( function (data){                       
          $scope.url= 'api/JuegosFacturacion/juego_facturacion_'+folio+'.pdf';          
          
        var fileName = "juego_facturacion_"+folio;

        var uri = $scope.url;
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


        
      // console.log(data);   
      // if(data=='exito'){

      // } else{
      //   alert('Error');
      // } 
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      });  
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

function JSClock() {
  var tiempo = new Date();
  var hora = tiempo.getHours();

  var minutos = tiempo.getMinutes();
  var segundos = tiempo.getSeconds();
  var temp = "" + ((hora > 12) ? hora - 12 : hora);
  if (hora == 0)
    temp = "12";
    if(hora<10) temp = '0'+temp;
  temp += ((minutos < 10) ? ":0" : ":") + minutos;
  temp += ((segundos < 10) ? ":0" : ":") + segundos;
  temp += (hora >= 12) ? " P.M." : " A.M.";
  return temp;
}