app.controller('entregaTurnoCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $rootScope.userName=$cookies.username;
  
  $scope.CurrentDate = new Date();
  $scope.cargadorInicio=true;
  $scope.cargadorEnvio=false;
  $scope.entrega={
    unidad: $rootScope.uniClave,
    usuEntrega: $rootScope.usrLogin,
    usuRecibe: null,
    turnoEntrega: null,
    turnoRecibe: null,
    siguiente: null,
    compuConectado: true,
    compuFuncionando: true,
    compuLimpio: true,
    impresoraConectado: true,
    impresoraFuncionando: true,
    impresoraLimpio: true,
    scannerConectado: true,
    scannerFuncionando: true,
    scannerLimpio: true,
    copiadoraConectado: true,
    copiadoraFuncionando: true,
    copiadoraLimpio: true,
    impBnConectado: true,
    impBnFuncionando: true,
    impBnLimpio: true,
    telConectado: true,
    telFuncionando: true,
    telLimpio:true,
    tvConectado:true,
    tvFuncionando:true,
    tvLimpio:true,
    aireConectado: true,
    aireFuncionando: true,
    aireLimpio: true,
    botanaConectado: true,
    botanaFuncionando: true,
    botanaLimpio: true,
    obsArea: null,
    controlTV: 1,
    controlAire: 1,
    controlLlaves: 1,
    obsControles: null,
    fondoCaja: null,
    efectivoCaja: null,
    valesRecibos: null,
    obsCaja: null,
    efectivoPart: null,
    tarjetaPart: null,
    totalPart: null,
    foliosPart: null,
    cierreTerminal: null,
    ingresadosABA: null,
    enviadosABA: null,
    ingresadosAtlas: null,
    enviadosAtlas: null,
    totalExpedientes: null,
    expDigitalizados: null,
    expIncompletos: null,
    expJustificacion: null,
    expProceso: null,
    incidencias: null,
    pendientes: null
  };


  $scope.inicio = function(){
      $scope.getUsuarios();
      $scope.getTurnos();
  }



  $scope.getUsuarios = function() {
      $scope.cargadorInicio=true;
      $http.get('api/entregaTurno.php?funcion=getUsuarios&uni='+$rootScope.uniClave).success(function (data){
          //console.log(data);
          $scope.listadoUsuarios=data;
          $scope.cargadorInicio=false;
      });
  }

  $scope.getTurnos = function() {
      $scope.cargadorInicio=true;
      $http.get('api/entregaTurno.php?funcion=getTurnos').success(function (data){
          console.log(data);
          $scope.listaTurnos=data;
          $scope.cargadorInicio=false;
      });
  }


  $scope.enviaDatos = function() {
      $scope.cargadorEnvio=true;
      console.log($scope.entrega);

      $http({
          url:'api/entregaTurno.php?funcion=cambioTurno',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.entrega
          }).success( function (data){
            //console.log(data);
            if (data=='"exito"') {
              //console.log('continua');
              $scope.abreModal();
              $scope.pdfCambioTurno();

            } else{
              console.log('algo no funciona');
            };
            $scope.cargadorEnvio=false;

          }).error( function (xhr,status,data){
              $scope.mensaje ='no entra';            
              alert('Error');
              $scope.cargadorEnvio=false;
      });  
  }


  $scope.abreModal = function() {
    $('#modalConfirmacion').modal({backdrop: 'static', keyboard: false});
        //$('#modalConfirmacion').modal('show');

  }

  $scope.irHome = function() {
      $('#modalConfirmacion').modal('hide');
      $location.path("/home");
  }

/*-------------------------------- PDF --------------------------------*/
    $scope.pdfCambioTurno = function(){  
            var fileName = 'FileError';
            var uri = 'api/classes/formatoCambioTurno.php?funcion=ImprimeEnvia&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin;
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