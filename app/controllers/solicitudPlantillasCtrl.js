app.controller('solicitudPlantillasCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  
  $scope.cargador=false;
  $scope.msjBoton='Realizar solicitud';

  $scope.datosSolicitud={
    folio:$rootScope.folio,
    medico:$rootScope.usrLogin,
    unidad:$rootScope.uniClave,
    material:'',
    medida:null,
    especificaciones:'',
    fechaPedido:'',
    fechaEntrega:'',
  };

    //CALCULAMOS 12 DÍAS PARA ENTREGA ESTIMADA
    fecha=new Date();
    
    day     = fecha.getDate();
    month   = fecha.getMonth()+1;
    year    = fecha.getFullYear();
    
    var datePedido=day+"/"+month+"/"+year;
    $scope.datosSolicitud.fechaPedido=year+'-'+month+'-'+day;
    //console.log("Fecha actual: "+datePedido);
 
    //Obtenemos los milisegundos de la fecha actual
    tiempo=fecha.getTime();
    //Calculamos los milisegundos equivalentes a los días que vamos a sumar
    milisegundos=parseInt(12*24*60*60*1000);
    //sumamos la fecha actual en milisegundos con los dias en milisegundos
    total   = fecha.setTime(tiempo+milisegundos);
    day     = fecha.getDate();
    if (day<10) {
      day='0'+day;
    };

    month   = fecha.getMonth()+1;
    year    = fecha.getFullYear();
    
    var dateEntrega=day+'/'+month+'/'+year;
    $scope.datosSolicitud.fechaEntrega=year+'-'+month+'-'+day;
 
    //console.log("Fecha estimada: "+dateEntrega);
    //console.log($scope.fechaEntrega);

/*-------------------------------- PDF --------------------------------*/
    $scope.pdfReportesRed = function(){  
            var fileName = 'FileError';
            var uri = 'api/classes/formatoSolicitudPlantillas.php?funcion=ImprimeEnvia&fol='+$rootScope.folio+'&sol=0';
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

$scope.enviarSolicitud = function() {
  $scope.cargador=true;
  $scope.msjBoton='Procesando solicitud ...';
  console.log($scope.datosSolicitud);

      $http({
          url:'api/solicitudPlantillas.php?funcion=enviarSolicitud',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.datosSolicitud
          }).success( function (data){
            console.log(data);
            if (data=='"exito"') {
                $scope.cargador=false;
                $scope.datosSolicitud.material='';
                $scope.datosSolicitud.medida=null;
                $scope.datosSolicitud.especificaciones='';
                $scope.msjBoton='Solicitud Enviada';
                $scope.pdfReportesRed();
                $location.path("/solicitudesPlantillas");

            } else{
                $scope.cargador=false;
                alert('Hubo un error; presiona F5 e intentalo nuevamente');
            };


      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Hubo un error; presiona F5 e intentalo nuevamente');
          $scope.cargador=false;
      });  


  $scope.cargador=false;
}




$scope.abreModal = function(){
  $scope.carga=false;
  $('#modalModificacion').modal('show');
}

});