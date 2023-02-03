app.controller('reciboParticularCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http) {
	$rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador=true;
  $scope.cargador1=false;
  $scope.cargador2=false;
  $scope.cargador3=false;
  $scope.cargadorEnvia=false;
  $scope.listadoItems='';
  $scope.valItems=false;
  $scope.formularios={}; 
  $scope.porMayor=false;
  $scope.formu=false;
  $scope.guardado=false; 
  $scope.opcion=0;
  $scope.enviando=false;
  $scope.datos={
    fecExp:'',
    noRec:'',
    folMv:'',
    pac:'',
    fecAt:''
  }
  $scope.items={
    fam:'',
    item:'',
    descuento:'',
    precio:''
  }
  $scope.datosRec={
    fPago:'',
    fec:'',
    noRec:'',
    medico:'',
    banco:'',
    terminacion:'',
    observaciones:''
  }

   $scope.datosCancelacion={
    motivoC:'',
    observaciones:''
  }

  $scope.sinRecibos=false;
  $scope.verDatosTarjeta=false;
  $scope.verDatosMultiple=false;
  $scope.Descuento=true;

  $scope.verListadoItems=false;
  $scope.sumaItems =[]; 
  $scope.mensaje=false;
 
  $scope.interacted = function(field) {          
    return $scope.formularios.formItem.$submitted && field.$invalid;          
  };
  $scope.interacted1 = function(field) {          
    return $scope.formularios.formRecibo.$submitted && field.$invalid;          
  };

      busquedas.datosRecibo($rootScope.folio).success(function(data){  
      $scope.datos={
        fecExp:data.fecExp,
        noRec:data.noRec,
        folMv:$rootScope.folio,
        pac:data.nombre,
        fecAt:data.fecReg
      }                         
      busquedas.familiaItems($rootScope.folio).success(function(data){        
        $scope.famItems=data;
        $scope.items.precio='';          
        $scope.cargador=false;
      });
      busquedas.medicos($rootScope.uniClave).success(function(data){         
        $scope.medicos=data;        
      });
      $http.get('api/api.php?funcion=checaRecibo&fol='+$rootScope.folio).success(function (data){                            
          
      });

      $http.get('api/api1.php?funcion=getRecibos&fol='+$rootScope.folio).success(function (data){ 
          
          $scope.listarecibos=data;
          if($scope.listarecibos==''){
            $scope.sinRecibos=false;
          }
          else{
            $scope.sinRecibos=true;            
          }
      });

      /*$http.get('api/api1.php?funcion=getCia&fol='+$rootScope.folio).success(function (data){                            
          if(data==51){
            $scope.Descuento=false;
          }else{
            $scope.Descuento=true;
          }
      });*/

    });

$scope.abreModalCancelacion = function(folio){
    $scope.folioModal=folio;
    $('#myModal').modal();              
  }

  // solicitud cancelacion folio.
// recibimos de una lista un no. de folio.
$scope.enviaSolCancelRecibo = function(folioRecibo){
      $scope.folRec= folioRecibo; 
      $scope.cargador = true;    
      $http({
        url:'api/particulares.php?funcion=enviaSolCancelRecibo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave+'&folRec='+$scope.folRec,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.datosCancelacion
      }).success( function (data){ 
          $scope.cargador = false;
          $('#myModal').modal('hide');  
          $scope.listarecibos=data;
          if($scope.listarecibos==''){
            $scope.sinRecibos=false;
          }
          else{
            $scope.sinRecibos=true;            
          }
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      });
  }

  $scope.selectopcion = function(){
      console.log($scope.opcion);
      if($scope.opcion==1){
        $scope.items.descuento=15;
        $scope.datosRec.fPago=2;       
      }else if($scope.opcion==2){
        $scope.items.descuento=10;
        $scope.datosRec.fPago=1;       
      }else if($scope.opcion==3){
        $scope.items.descuento='';
        $scope.datosRec.fPago='';       
      }
      
  }


  $scope.selectItem = function(){
        $scope.cargador1=true;          
          var fam=$scope.items.fam;                                               
              $http({
                      url:'api/api.php?funcion=SelectItems&cveFam='+fam+'&fol='+$rootScope.folio,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.datos1
                      }).success( function (data){ 
                        console.log(data); 
                        $scope.items.precio='';
                        $scope.items.descuento='';                                                   
                        $scope.listItems=data;
                        $scope.cargador1=false;
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
              }
        $scope.ponerPrecio = function(){                
                if($scope.items.item){
                  console.log($scope.items.item);
                  precio=$scope.items.item.split('/');
                  $scope.items.precio=precio[1];
                  $scope.items.descuento = precio[2];
                }else{
                  $scope.items.precio='';
                }
              }

$scope.reimprimirRecibo = function(noRecibo,serie){          
          var fileName = "Reporte";
          var uri = 'api/classes/reimprimirRecibo.php?fol='+$rootScope.folio+'&cveRec='+noRecibo+'&serie='+serie;
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


  $scope.verDatosAcc = function(){          
          //console.log($scope.datos1);
            
            
            if($scope.acc.fecSin==''){
              $scope.cargador=true;
              $http({
                      url:'api/api.php?funcion=datosAcc&folio='+$rootScope.folio,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.datos1
                      }).success( function (data){ 
                                            
                        $scope.acc.fecSin=data.Not_fechaAcc;
                        $scope.acc.fecAtn=data.Not_fechareg;
                        $scope.acc.doc=data.Med_nombre;
                        $scope.acc.diag=data.ObsNot_diagnosticoRx;
                        $scope.cargador=false;
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
              }
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
        var cont=1;
        $scope.guardaItem = function(){                                                     
           if($scope.formularios.formItem.$valid){               
              if($scope.items.descuento<=100){ 
              $scope.cargador2=true; 
              $scope.porMayor=false;               
              precio=$scope.items.item.split('/');
              $scope.cveItem=precio[0];              
              $scope.cont ={} ;
              $http.get('api/model/recibos.php?funcion=getValuesItem&cveItem='+$scope.cveItem).success(function (data){                                               
                  $scope.verListadoItems=true;
                  $scope.cont.cont=cont;         
                  $scope.cont.cveMV=data.Ite_clave;
                  $scope.cont.cveItem=data.Ite_cons;
                  $scope.cont.item = data.Ite_item;
                  $scope.cont.desc = data.Ite_descripcion;
                  $scope.cont.pres = data.Ite_presentacion;
                  $scope.cont.descuento = $scope.items.descuento;
                  $scope.cont.precio = $scope.items.precio;  
                          
                  $scope.sumaItems.push($scope.cont);
                  $scope.cargador2=false;

                  $scope.items.fam='';
                  $scope.items.item='';
                  $scope.items.precio='';       

                  subtotal=0;
                  total=0;
                  porcentaje=0;
                  descuento=0;
                  for (var i = 0; i < $scope.sumaItems.length; i++) {                          
                     subtotal=parseFloat(subtotal.toFixed(2))+parseFloat($scope.sumaItems[i].precio);                    
                     porcentaje=($scope.sumaItems[i].precio*$scope.sumaItems[i].descuento)/100;
                     descuento=descuento+parseFloat(porcentaje.toFixed(2));
                     total=subtotal-descuento;
                  }
                  $scope.subtotal=subtotal.toFixed(2);
                  $scope.descuento=descuento.toFixed(2);
                  $scope.total=total.toFixed(2);
                  cont++; 
              });
              $scope.formularios.formItem.$submitted=false;
                           
              /*$http({
                    url:'api/api.php?funcion=guardaItem&folio='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cveRec='+$scope.datos.noRec,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.items
                    }).success( function (data){ 
                      $scope.cargador2=false;                      
                      console.log(data);
                      if(!data.respuesta){
                        $scope.listadoItems=data;
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
                      }else{
                        $scope.listadoItems='';
                      }
                      if($scope.opcion==1) $scope.items.descuento=15;
                      else if($scope.opcion==2) $scope.items.descuento=10;
                      else if($scope.opcion==3) $scope.items.descuento='';

                      $scope.items.fam='';
                      $scope.items.item='';
                      $scope.items.precio='';                      
                      
                      $scope.subtotal=subtotal.toFixed(2);
                      $scope.descuento=descuento.toFixed(2);
                      $scope.total=total.toFixed(2);
                      
                     
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });*/


                  }else{
                      $scope.porMayor=true;
                  }
              }
        } 

  $scope.guardaItem_1 = function(){                                                     
           if($scope.formularios.formItem.$valid){               
              if($scope.items.descuento<=100){ 
              $scope.cargador2=true; 
              $scope.porMayor=false;             
              $http({
                    url:'api/api.php?funcion=guardaItem&folio='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cveRec='+$scope.datos.noRec,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.items
                    }).success( function (data){ 
                      $scope.cargador2=false;                      
                      console.log(data);
                      if(!data.respuesta){
                        $scope.listadoItems=data;
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
                      }else{
                        $scope.listadoItems='';
                      }
                      if($scope.opcion==1) $scope.items.descuento=15;
                      else if($scope.opcion==2) $scope.items.descuento=10;
                      else if($scope.opcion==3) $scope.items.descuento='';

                      $scope.items.fam='';
                      $scope.items.item='';
                      $scope.items.precio='';                      
                      
                      $scope.subtotal=subtotal.toFixed(2);
                      $scope.descuento=descuento.toFixed(2);
                      $scope.total=total.toFixed(2);
                      
                     
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
                  }else{
                      $scope.porMayor=true;
                  }
              }
        }  
  

  $scope.eliminarItemRec = function(cveItem){
             console.log(cveItem);
              delete $scope.sumaItems[cveItem];                                                               
              $scope.sumaItems.splice(cveItem,1);              
              if($scope.sumaItems.length<=0){
                $scope.verListadoItems=false;
              }else{
                  $scope.verListadoItems=true;
                  subtotal=0;
                  total=0;
                  porcentaje=0;
                  descuento=0;
                  for (var i = 0; i < $scope.sumaItems.length; i++) {                          
                     subtotal=parseFloat(subtotal)+parseFloat($scope.sumaItems[i].precio);                    
                     porcentaje=($scope.sumaItems[i].precio*$scope.sumaItems[i].descuento)/100;
                     descuento=descuento+porcentaje;
                     total=subtotal-descuento
                  }
                  $scope.subtotal=subtotal.toFixed(2);
                  $scope.descuento=descuento.toFixed(2);
                  $scope.total=total.toFixed(2);
              }
        }  

$scope.eliminarItemRec_resp = function(cons,folRec){                                                               
              $scope.cargador2=true; 
                                       
              $http({
                    url:'api/api.php?funcion=eliminarItemRecibo&folio='+$rootScope.folio+'&cons='+cons+'&folRec='+folRec,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: {'clave':'valor'}
                    }).success( function (data){ 
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
  $scope.guardaRecibo = function(){                                                     
           if($scope.formularios.formRecibo.$valid){               
              $scope.cargadorEnvia=true;

              if($scope.sumaItems!=''){                                
                $scope.mensaje=false;
                $scope.datosRec.fec=$scope.datos.fecExp;
                $scope.datosRec.noRec=$scope.datos.noRec
                $scope.todo= [$scope.datosRec,$scope.sumaItems];

                console.log($scope.todo);

                $http({
                    url:'api/model/recibos.php?funcion=guardaRecibo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.todo
                    }).success( function (data){
                      console.log(data); 
                      if(data.respuesta=='exito'){
                        var fileName = "Reporte";
                        var uri = 'api/classes/imprimirRecibo.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cveRec='+data.folRecibo;
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
                        $scope.formu=true; 
                        $scope.guardado=true;

                        $http.get('api/crearLayout.php?funcion=creaArchivo&fol='+$rootScope.folio+'&folRecibo='+data.folRecibo).success(function (data){                            
          
                        });



                      }                                    
                      $scope.mensaje=false;
                      $scope.cargadorEnvia=false;
                      console.log(data);
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });

                /*$http({
                    url:'api/api.php?funcion=validaItems&folio='+$rootScope.folio+'&cveRec='+$scope.datos.noRec,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data:{'clave':'valor'}
                    }).success( function (data){ 
                      if(data==''){
                        $scope.valItems=true;
                        $('#familia').focus();
                      }else{                          
                        var fileName = "Reporte";
                        var uri = 'api/classes/imprimirRecibo.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&fecha='+$scope.datosRec.fec+'&facPago='+$scope.datosRec.fPago+'&cveRec='+$scope.datos.noRec+'&medico='+$scope.datosRec.medico+'&tipoRecibo='+$scope.opcion+'&banco='+$scope.datosRec.banco+'&terminacion='+$scope.datosRec.terminacion;
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
                        $scope.formu=true; 
                        $scope.guardado=true;
                      }
                      $scope.cargador3=false;                     
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    }); */
                    
                }else{
                  $scope.mensaje=true;
                  $scope.cargadorEnvia=false;
                }                     
              }
        }  
  
  $scope.cechaMetodoPago = function(){         
        $scope.datosRec.banco='';  
        if($scope.datosRec.fPago==2 || $scope.datosRec.fPago==3)
        {
          $scope.verDatosTarjeta=true; 
          $scope.verDatosMultiple=false;
        }else if($scope.datosRec.fPago==6)
        {
          $scope.datosRec.banco='AMEX';
          $scope.verDatosTarjeta=true; 
          $scope.verDatosMultiple=false;
          
        }else if($scope.datosRec.fPago==7)
        {
          $scope.verDatosTarjeta=false; 
          $scope.verDatosMultiple=true;
        } else
        {
          $scope.verDatosTarjeta=false; 
        }        
  }
  
  $scope.irDocumentos = function(){         
        $location.path("/documentos");          
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