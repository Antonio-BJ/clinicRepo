app.controller('buscarReciboCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$upload) {
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

  $scope.soportesRecibo = {

    fol:'',
    soporte:'',
    archivo: '',
    temporal: ''
  }

  $scope.datosCancelacion1={
    motivo:'',
    Obs:'',
    sustituto:'',
    solicitado: $rootScope.username
  }


  $rootScope.cargador2=false;
  $scope.bloqueoboton=false;
  $scope.folioModal='';

if($rootScope.usrLogin=="algo" || $rootScope.usrLogin=="lmorales"){
  $scope.cancelaRecibo=true;
}else{
  $scope.cancelaRecibo=false;
}

$scope.buscaRecibo = function(){
  if($scope.consulta.folio=='' && $scope.consulta.recibo==''){
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

$scope.abreModalsoporte = function(folio){
    $http.get('api/api.php?funcion=getRecibossoportes&fol='+folio).success(function (data){ 
        
        $scope.soprecibos=data;
        console.log(data);

    });  
    $scope.folioModal=folio;
    $('#myModalsoporte').modal();   


}

$scope.guardasoporteRecibo = function(folio){

  $scope.soportesRecibo.fol = folio;

  $scope.cargador=true;
  $scope.upload = $upload.upload({
    url:'api/api.php?funcion=guardasoporteRecibo&fol='+$rootScope.folio+'&recibo='+$scope.soportesRecibo.fol+'&usr='+$rootScope.usrLogin,
    method:'POST',             
    data:$scope.soportesRecibo,
    file: $scope.archivo
  }).success( function (data, status, headers, config){   
    $scope.cargador=false; 
    $http.get('api/api.php?funcion=getRecibossoportes&fol='+folio).success(function (data){ 
        
        $scope.soprecibos=data;
        console.log(data);

    });  


  }).error( function (xhr,status,data){            
    $scope.cargador=false;          
    $scope.mensaje ='no entra';            
    alert('Error');
  });                               
}

     $scope.onFileSelect_xml = function($files) {


        for (var i = 0; i < $files.length; i++) {

             var file = $files[i];

             $scope.archivo=file;

        $scope.variable = 2;

        var amt = 0;

          //$files: an array of files selected, each file has name, size, and type.

            $scope.upload = $upload.upload({

               url: 'api/api.php?funcion=archivo_temporal', //upload.php script, node.js route, or servlet url

               method: 'POST',

               //headers: {'header-key': 'header-value'},

               //withCredentials: true,

               data: $scope.factura,

               file: file, // or list of files ($files) for html5 only

               

             progress:function(evt) {

                       

                 var amt =  parseInt(100.0 * evt.loaded / evt.total);

           $scope.countTo = amt;

           $scope.countFrom = 0;

           

           /*$timeout(function(){  

             $scope.progressValue = amt;

           }, 200);*/

             }

         })

            .success(function (data, status, headers, config){ 
                  console.log(data); 
                  if(data.error=='si'){
                    $("#myModal").modal();
                  }else{
                    $scope.soportesRecibo.archivo=data.nombre;
                    $scope.soportesRecibo.temporal=data.temporal;  
                  }                              
                  //console.log($scope.digital.archivo+'--'+$scope.digital.temporal);           

                  }).error( function (xhr,status,data){
                      alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
                  });



          }



         

      }
$scope.abreModalCancelacion1 = function(folio){
    $scope.folioModal=folio;
    $('#myModal1').modal();   


}

  $scope.enviaCancelacionRecibo = function(folioRecibo){ 

    $scope.cargador = true; 
    $scope.datosCancelacion1.folio= folioRecibo;
    console.log($scope.datosCancelacion1); 
    $http({
      url:'api/particulares.php?funcion=enviaCancelacionRecibo&usr='+$rootScope.usrLogin,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.datosCancelacion1
    }).success( function (data){
      console.log(data);
          $scope.cargador = false;
          $('#myModal1').modal('hide');  

          $http.get('api/api.php?funcion=cargaRecibo&fol='+folioRecibo).success(function (data){ 
              
              $scope.listadoReporte=data;
              // console.log(data);

          });  
          
                                                      
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

});