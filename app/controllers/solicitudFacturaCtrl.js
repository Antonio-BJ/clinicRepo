app.controller('solicitudFacturaCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http) {
	$rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador=true;
  $scope.cargador1=false;
  $scope.cargador2=false;
  $scope.formularios={};
  $scope.verlistado=true;
  $scope.mensajeError=false;
  $scope.errorReporte=false;
  $scope.enviado=false;
  $scope.enviando=false;
  $scope.total=0;
  $scope.datos={        
        mPago:'',
        usuario:''
  };
  $scope.fac={
        facRfc:'',
        facSocNom:'',
        facCalle:'',
        facNoEx:'',
        facNoInt:'',
        facCol:'',
        facDel:'',
        facCodP:'',
        facRef:'',
        facEmail:'',
        facMed:'',
        facObs:'',
        facMpago:'',
        facPcobra:'',
        facFolio:'',
        facUsu:'',
        facTotal:0
  };
  $scope.bancoTerminacion=false;
  
  $scope.interacted = function(field) {          
    return $scope.formularios.solicitudFac.$submitted && field.$invalid;          
  };  

  busquedas.contRecibos($rootScope.folio).success(function(data){
      
      if(data==''){
        $scope.mensajeError=true;
      }else{
        $scope.datosRecibo=data;
        $scope.mensajeError=false;
      }
      $scope.cargador=false;
  }); 
  busquedas.nomUsuario($rootScope.usrLogin).success(function(data){    
      $scope.datos.usuario=data.Usu_nombre;
      $scope.fac.facPcobra=$scope.datos.usuario;
  }); 
  

  $scope.verForm = function(noFol,mPago){ 
  $scope.bancoTerminacion=false;                                           
    $scope.datos.mPago=verMPago(mPago);
    if(mPago==2 || mPago==3){
      $http.get('api/api.php?funcion=tarjetaDoc&fol='+$rootScope.folio+'&recibo='+noFol).success(function (data){        
        $scope.bancoTerminacion=true;
        $scope.banco=data.Recibo_banco;
        $scope.terminacion=data.Recibo_terminacion; 
      });
    }
    $scope.fac.facMpago=mPago;
    $scope.fac.facUsu=$rootScope.usrLogin;
    $scope.fac.facFolio=$rootScope.folio;
    $scope.fac.facFolRec=noFol;

            $scope.cargador2=true;  
              $http({
                      url:'api/api.php?funcion=listadoItems&folio='+$rootScope.folio+'&noFol='+noFol,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: {'clave':'valor'}
                      }).success( function (data){
                        $scope.verlistado=false;                        
                        $scope.cargador2=false; 
                         if(!data.respuesta){
                     if(data==''){
                        $scope.listadoItems='';
                     }else{
                        $scope.listadoItems=data;
                     }                        
                        
                        subtotal=0;
                        total=0;
                        porcentaje=0;
                        descuento=0;
                        for (var i = 0; i < $scope.listadoItems.length; i++) {                           
                           subtotal=parseFloat(subtotal)+parseFloat($scope.listadoItems[i].it_precio);
                           porcentaje=($scope.listadoItems[i].it_precio*$scope.listadoItems[i].it_descuento)/100;
                           descuento=descuento+porcentaje;
                           total=subtotal-descuento
                        }
                      $scope.subtotal=subtotal.toFixed(2);
                      $scope.descuento=descuento.toFixed(2);
                      $scope.total=total.toFixed(2);
                      $scope.fac.facTotal=$scope.total;
                     }
                      else{
                        $scope.listadoItems='';
                      }
                      $scope.cargador2=false;                      
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
              
        }

  $scope.enviarSolicitud = function(){

          if($scope.formularios.solicitudFac.$valid){
              
              $scope.enviando=true;                     
              $scope.cargador=true;
              $http({
                    url:'api/api.php?funcion=enviaSolicitudFac',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.fac
                    }).success( function (data){ 
                      $scope.enviando=false;
                      if(data=='error'){
                        alert('error en el envio de solicitud, borre el historial del navegador he intentelo de nuevo.');
                      }else if(data.respuesta){
                        alert('error en el envio de solicitud, borre el historial del navegador he intentelo de nuevo.');
                      }else{
                        $scope.enviado=true;                              
                        setTimeout(function(){
                        url = '#/documentos';
                        $(location).attr('href',url);
                      },3000);  
                      }
                       
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
                        
            }
        }  


  $scope.verRecibos = function(){         
         $scope.verlistado=true; 
         $scope.bancoTerminacion=true;       
  } 

  $scope.verExpdiente = function(){                                            
            //if($scope.acc.fecSin==''){
              if(!$scope.validaEx){
              $scope.cargador=true;
              $http({
                    url:'api/api.php?funcion=datosExp&folio='+$rootScope.folio,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.datos1
                    }).success( function (data){                                     
                      if(data.nota=='SI'){
                        $scope.nota=true;
                      }
                      $scope.subsec=data.Subs;
                      $scope.cargador=false;
                      $scope.validaEx=true;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
                  }
              //}
        }  

  $scope.guardaPase = function(){                                                      
           if($scope.formularios.paseRehab.$valid){    
              $scope.cargador1=true;                          
              $http({
                    url:'api/api.php?funcion=guardaPaseR&folio='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.formReah
                    }).success( function (data){ 
                      $scope.formu=true;
                      if(data.respuesta=='SI'){
                        var fileName = "Reporte";
                        var uri = 'api/classes/formatoPaseRehabilitacion.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
                        var link = document.createElement("a");    
                        link.href = uri;
                        
                        //set the visibility hidden so it will not effect on your web-layout
                        link.style = "visibility:hidden";
                        link.download = fileName + ".pdf";
                        
                        //this part will append the anchor tag and remove it after automatic click
                        document.body.appendChild(link);
                        link.click();                        
                        document.body.removeChild(link);
                        $scope.cargador1=false;
                        $location.path("/documentos"); 
                      }else{
                        alert('Error en la insersión');
                      }
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
              }
        }  
  

  
  
  $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }
  $scope.irRecibo = function(){         
        $location.path("/reciboParticular");          
  }

  $scope.imprimirReceta = function(){
    $scope.cargador=true;          
      var fileName = "Reporte";
      var uri = 'api/classes/formatoRecSub.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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
  $scope.imprimirSub = function(){          
    $scope.cargador=true;          
      var fileName = "Reporte";
      var uri = 'api/classes/formatoSub.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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

  $scope.imprimirSubList = function(cont){          
          $scope.cargador=true;          
            var fileName = "Reporte";
            var uri = 'api/classes/formatoSubList.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cont='+cont;
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

  $scope.imprimirNota = function(){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoNota.php?fol='+$rootScope.folio;
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


function verMPago(mPago){
  var pago='';
    switch (mPago) {
      case '1':
          pago = 'Efectivo';
          break;
      case '2':
          pago = 'Tarjeta de crédito';
          break;
      case '3':
          pago = 'Tarjeta de débito';
          break;
      case '4':
          pago = 'Transferencia';
          break;
      case '5':
          pago = 'Cheque';
          break;
    } 
  return pago;           
}