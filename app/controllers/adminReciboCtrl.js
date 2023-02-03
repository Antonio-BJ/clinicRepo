app.controller('adminReciboCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
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
$scope.consulta={
  recibo: '',
  unidad: '',
  folio: '',
  lesionado: ''
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

$scope.datos={
    folio:'',
    motivo:'',
    folioSus:'',
    Obs:'',
    solicitado:''
  }


  $rootScope.cargador2=false;
  $scope.bloqueoboton=false;
  $scope.folioModal='';

if($rootScope.usrLogin=="algo" || $rootScope.usrLogin=="lmorales"){
  $scope.cancelaRecibo=true;
}else{
  $scope.cancelaRecibo=false;
}

$http({
  url:'api/particulares.php?funcion=cargaRecibos&usr='+$rootScope.usrLogin+'&unidad='+$rootScope.uniClave,
  method:'POST', 
  contentType: 'application/json', 
  dataType: "json", 
  data: $scope.datos
}).success( function (data){
  $scope.listadoReporte = data;
                                                    
}).error( function (xhr,status,data){
  $scope.mensaje ='no entra';            
  alert('Error');
}); 

$scope.buscaRecibo = function(){
  if($scope.consulta.folio=='' && $scope.consulta.recibo=='' && $scope.consulta.lesionado==''){
    alert("Es necesario el folio MV o el numero de recibo");
  }else{
    $scope.cargador=true;
    $http({
        url:'api/particulares.php?funcion=consultaRecibos&unidad='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.consulta
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
}

$scope.modalCancelacion = function(recibo){
  $scope.folioModal=recibo;
  $('#modalCancelacion').modal();              
}

$scope.verCancelado = function(recibo,serie){
  $scope.folioModal=serie+recibo;
  $('#verDetalle').modal();  
  $http.get('api/particulares.php?funcion=detalleCancelacion&recibo='+serie+recibo).success(function (data){                             
    $scope.datosCancelado=data;
    //console.log(data);
  });            
}

$scope.verdetalleFol = function(folio){
  $('#verdetalleRec').modal();  
  $http.get('api/particulares.php?funcion=detallefol&folio='+folio).success(function (data){                             
    $scope.detafols=data;
    //console.log(data);
  });            
}

  $scope.enviaCancelacionRecibo = function(){ 
    $rootScope.cargador2=true;
    $scope.bloqueoboton=true;
    $scope.datos.folio=$scope.folioModal; 
    console.log($scope.datos); 
    $http({
      url:'api/particulares.php?funcion=enviaCancelacionRecibo&usr='+$rootScope.usrLogin,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.datos
    }).success( function (data){
      console.log(data);
      if(data==0){
        $scope.bloqueoboton=false;
        $rootScope.cargador2=false;
        $scope.verMensaje=true;
        $scope.enviado=true;
        $scope.datos.folio=''; 
        $scope.datos.motivo='';
        $scope.datos.folioSus='';
        $scope.datos.Obs='';
        $scope.datos.solicitado='';
        setTimeout(function(){
          $scope.listadoReporte="";
          $scope.verMensaje=false;
          $('#modalCancelacion').modal('hide');
        },2000);
      } else{
        alert('hubo un error en el envio, intentalo nuevamente!!');
        $scope.bloqueoboton=false;
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
          console.log(folio+" "+ folioRecibo+" "+ serie)
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

        $scope.reimprimirReciboC = function(folio, folioRecibo,serie){          
          var fileName = "Reporte";
          console.log(folio+" "+ folioRecibo+" "+ serie)
          var uri = 'api/classes/reimprimirReciboCancelado.php?fol='+folio+'&cveRec='+folioRecibo+'&serie='+serie;
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

        $scope.verDatosTar = function(recibo,serie){
          $scope.folioModal=serie+recibo;
          $('#verDatoTarj').modal();  
          $http.get('api/particulares.php?funcion=tarjetaRecibo&recibo='+serie+recibo).success(function (data){                             
            $scope.datosTarjeta=data;
            //console.log(data);
          });            
        }

      $scope.enviaAplicacion = function () {

      if ($scope.selection == '') {

        alert('Selecciona un cobro para poder aplicar');

        return $scope.selection;

      }else{
          var sumaCobro = 0;
          for (var i = 0; i < $scope.selection.length; i++){

                sumaCobro += parseFloat($scope.selection[i].COB_saldoinicial);
                $scope.datosRecibo.totalcobro = sumaCobro.toFixed(2);

          }
          if($scope.datosRecibo.totalcobro < $scope.datosRecibo.total){
            
            alert('El cobro no puede ser mayor que el recibo');


          }else{

            $('#ventanaApp').modal({backdrop: 'static', keyboard: false}); // abrir
            $scope.aplicacion.monto = parseFloat($scope.datosRecibo.totalcobro);

          }


          console.log($scope.datosRecibo.totalcobro );


      }

  }

  $scope.modalSop = function(recibo){

    $scope.recibo1 = recibo;

    $('#myModalsoporte').modal({ show:true });
    $http.get('api/api.php?funcion=getRecibossoportes&fol='+recibo).success(function (data){ 
        
        $scope.soprecibos=data;
        console.log(data);

    });           
  }

  $scope.recargaList = function(){

    $http({
      url:'api/particulares.php?funcion=cargaRecibos&usr='+$rootScope.usrLogin+'&unidad='+$rootScope.uniClave,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.datos
    }).success( function (data){
      $scope.listadoReporte = data;
                                                        
    }).error( function (xhr,status,data){
      $scope.mensaje ='no entra';            
      alert('Error');
    }); 


  }

  $scope.verdetalleApp = function(recibo,serie){
    $scope.recibo=serie+recibo;
    $('#myModalApli').modal();  
    $http.get('api/particulares.php?funcion=verDetApp&folioRecibo='+recibo+'&serie='+serie).success(function (data){                             
      $scope.detalleApp=data;
            //console.log(data);
    });            
  }



});