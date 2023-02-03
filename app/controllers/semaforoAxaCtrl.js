app.controller('semaforoAxaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.carga=true;

  
  $http.get('api/semaforoAxa.php?funcion=getRegistros').success(function (data){                             
      $scope.listadoRegistros=data;
      //console.log($scope.listadoRegistros);
      $scope.carga=false;
  });

$scope.cargador=false; 
$scope.mensaje= false;
$scope.reporte={
  folio:'',
}

var hoy = new Date(); 
          var HH   = hoy.getHours();
          var MM   = hoy.getMinutes();
          var SS   = hoy.getSeconds();
          var dd   = hoy.getDate(); 
          var mm   = hoy.getMonth()+1;//enero es 0! 
          var yyyy = hoy.getFullYear();

          // if (HH < 10) { HH = '0' + HH; }
          // if (MM < 10) { MM = '0' + MM; }
          // if (SS < 10) { SS = '0' + SS; }
          // if (mm < 10) { mm = '0' + mm; }
          // if (dd < 10) { dd = '0' + dd; }

          //armamos fecha para los datepicker
          var FechaAct = yyyy + '-' + mm + '-' +dd;          
          var HoraAct = HH + ':' + MM + ':' + SS;
          $scope.horaAct=HoraAct; 
          //$scope.fechaAct=FechaAct;

          $scope.fechaAct={
            hoy:dd,
            mes: mm,
            anio: yyyy,
          };

$scope.ultimaSes={
  anio:'',
  mes:'',
  dia:'',
};


$scope.verDetalles = function(folio, totalSes, fechaReg){
    $scope.folioSeleccionado=folio;
    $scope.totalSes=totalSes;
    $scope.fechaReg=fechaReg;
    $scope.cargador=true;   
    $http({
        url:'api/semaforoAxa.php?funcion=verDetalles&folio='+folio,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        //data: $scope.
        }).success( function (data){                                                      
        $scope.cargador=false; 
        $scope.sesionesTomadas=data;
        if ($scope.sesionesTomadas[0].SES_TOMADAS==null) {
          $scope.sesionesTomadas[0].SES_TOMADAS=0;
        };

        $scope.sesRestan=$scope.totalSes-$scope.sesionesTomadas[0].SES_TOMADAS;

        //console.log($scope.sesionesTomadas);
        $('#modalDetalles').modal('show');

          //se calculan los días desde la última sesion
          if ($scope.sesionesTomadas[0].ULTIMA_RH!=null) {
              $scope.ultimaSes.anio=parseInt($scope.sesionesTomadas[0].ULTIMA_RH.substr(0,4));
              $scope.ultimaSes.mes=parseInt($scope.sesionesTomadas[0].ULTIMA_RH.substr(5,2));
              $scope.ultimaSes.dia=parseInt($scope.sesionesTomadas[0].ULTIMA_RH.substr(8,2));

              var ultima=new Date($scope.ultimaSes.anio+','+$scope.ultimaSes.mes+','+$scope.ultimaSes.dia);
              //console.log(ultima);
              var resta =ultima.getTime() - hoy.getTime();

              $scope.diasUltima= Math.floor((resta/(1000*24*60*60))*(-1));
              //console.log($scope.diasUltima);

                if ($scope.diasUltima>=0 && $scope.diasUltima<=5) {
                  $scope.colorModal='info';
                };
                if ($scope.diasUltima>5 && $scope.diasUltima<=15) {
                  $scope.colorModal='warning';
                };
                if ($scope.diasUltima>15 && $scope.diasUltima<=30) {
                  $scope.colorModal='danger';
                };
          } else {
              $scope.mensajeRh='No se han registrado Rehabilitaciones';
              //console.log($scope.diasUltima);
              $scope.colorModal='danger';
          };

          //se calculan los días desde el registro
          var regAnio = parseInt($scope.fechaReg.substr(0,4));
          var regMes = parseInt($scope.fechaReg.substr(5,2));
          var regDia = parseInt($scope.fechaReg.substr(8,2));

          var fechaReg = new Date(regAnio+','+regMes+','+regDia);
          var resta = fechaReg.getTime() - hoy.getTime();

          $scope.diasTotal=Math.floor((resta/(1000*24*60*60))*(-1));
          //console.log('Desde el registro'+$scope.diasTotal);


    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });                          
}

$scope.enviaParametros = function(){
  //console.log($scope.reporte);
    $scope.cargador=true;   
    $http({
        url:'api/semaforoAxa.php?funcion=buscaFolio',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.reporte
        }).success( function (data){                                                      
        $scope.cargador=false; 
        $scope.resFolio=data;
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

 
});