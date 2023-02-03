app.controller('ventasSinRegistroCtrl', function($scope,$rootScope,$location,$cookies,busquedas,WizardHandler,$http,$upload) {
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.folio= $cookies.folio;

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
    terminacion:''
  }

  $scope.sinRecibos=false;
  $scope.verDatosTarjeta=false;
  $scope.Descuento=false;

  $scope.verListadoItems=false;
  $scope.sumaItems =[]; 
  $scope.mensaje=false;
  $scope.ningunItem=false;
  $scope.sinFamilia=false; 
  $scope.sinItem=false;   

  $scope.nuevo={
      nombre:"",
      apellidoPaterno:"",
      apellidoMaterno:"",      
      email:"",
      telefono:"",
      codigoPostal:"",
      enterado:"",
      usuarioRegistro: $cookies.usrLogin,
      folio: $cookies.folio,
      sexo:'',
      edad:'',
      bitCupon: 0,
      cuponDescuento: ''
  };

  $scope.codigoE=false;
  $scope.membresia = false;
  $scope.tipoVenta = '';
  $scope.esMembresia = false;
  $scope.esEmpleado = false;
  $scope.valorMembresia ='';
  $scope.noEmpleado ='';
  $scope.mem=0;

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

          var FechaAct = yyyy + '-' + mm + '-' +dd;          
          var HoraAct = HH + ':' + MM + ':' + SS;
          $scope.nuevo.horaRegistro=HoraAct; 
          $scope.nuevo.fechaRegistro=FechaAct; 

  $scope.nuevoRegistro = function() {
    console.log($scope.sumaItems); 
    if($scope.nuevoRegistro1.$valid || valido==true){ 
      console.log($scope.sumaItems); 
      if($scope.sumaItems.length>0){
        $scope.ningunItem=false;
        $scope.nuevo.fam          =   $scope.items.fam;
        $scope.nuevo.total        =   $scope.total=total.toFixed(2);
        $scope.nuevo.fPago        =   $scope.datosRec.fPago;
        $scope.nuevo.fec          =   $scope.datosRec.fec;
        $scope.nuevo.noRec        =   $scope.datosRec.noRec;
        $scope.nuevo.medico       =   $scope.datosRec.medico;
        $scope.nuevo.banco        =   $scope.datosRec.banco;
        $scope.nuevo.terminacion  =   $scope.datosRec.terminacion;
        $scope.nuevo.unidad       =   $rootScope.uniClave;
        $scope.nuevo.cveMembresia =   $scope.valorMembresia;

        $scope.sumaItems.userReg=$cookies.usrLogin;
        $scope.juntos=[$scope.nuevo,$scope.sumaItems];    
        console.log($scope.juntos);
        $http({
            url:'api/ventasSinRegistro.php?funcion=guardarNuevo',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.juntos
          }).success( function (data){

             // var folio = data.folio;

              
              var fileName = "Recibo"+data.folioRecibo;
              //var uri = 'api/classes/tcpdf/examples/ventaSinRegistroRecibo.php?folioRecibo='+data.folioRecibo+'&folio='+data.folio+'&medico='+data.medico;
              var uri = 'api/classes/tcpdf/ventaSinRegistroRecibo1.php?folioRecibo='+data.folioRecibo+'&folio='+data.folio+'&medico='+data.medico+'&cupon='+data.cuponDescuento;
              // var uri = 'api/classes/imprimirRecibo.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&cveRec='+data.folRecibo;
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
              //location.reload();
              console.log(data);
              $http.get('api/crearLayout.php?funcion=creaArchivo&fol='+data.folio+'&folRecibo='+data.folioRecibo+'&serie=P').success(function (data){                                      
              });
          }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador=false;
            });
      }else{
        $scope.ningunItem=true;
      }
    }else{
      if($scope.sumaItems!=''){
        $scope.ningunItem=false;
      }else{
        $scope.ningunItem=true;
      }
        console.log($scope.ningunItem);
        console.log($scope.nuevoRegistro1.$invalid);
        console.log($scope.items.fam);
        console.log($scope.total=total.toFixed(2));
        console.log($scope.datosRec.fPago);
        console.log($scope.datosRec.banco);
        console.log($scope.datosRec.terminacion);
          
    }
  } //cierra nuevoRegistro



/*------------------- AGREGADO -------------------*/

var valido=false; 
  $scope.interacted = function(field) { 
      
    if(field.$invalid){
      return $scope.nuevoRegistro1.$submitted && field.$invalid;
      valido=false;
    }else{
      valido=true;
    }
       console.log(valido);
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

      $http.get('api/api1.php?funcion=getCia&fol='+$rootScope.folio).success(function (data){                            
          if(data==51){
            $scope.Descuento=false;
          }else{
            $scope.Descuento=true;
          }
      });

    });

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

   $scope.verificaCodigo = function(){                         
            if($scope.nuevo.codigoPostal!='00000'){
            $http.get('api/api.php?funcion=codExiste&cod='+$scope.nuevo.codigoPostal).success(function (data){ 
            console.log(data);               
                if(data=='noExiste'){
                    $scope.codigoE=true;    
                }else{
                    $scope.codigoE=false;    
                }                
            });  
            }else{
                $scope.codigoE=false;
            }
    }

  $scope.selectItem = function(){
        $scope.cargador1=true;          
          var fam=$scope.items.fam;
          if($scope.items.fam!=''){
            $scope.sinFamilia=false;
          } else{
            $scope.sinFamilia=true;
          }

              $http({
                      url:'api/api.php?funcion=SelectItems1&cveFam='+fam+'&fol='+$rootScope.folio+'&mem='+$scope.mem,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.datos1
                      }).success( function (data){ 
                        console.log(data); 
                         if(fam==6){
                          $scope.verEnfermeras = true;
                            $http.get('api/particulares.php?funcion=getEnfermeras&uni='+$rootScope.uniClave).success(function (data){
                              $scope.listaEnfermeras =data;
                              console.log(data);
                            }); 
                        }
                        $scope.items.precio='';
                        $scope.items.descuento='';                                                   
                        $scope.listItems=data;
                        $scope.cargador1=false;
                        $scope.items.fam=$scope.items.fam;
                }).error( function (xhr,status,data){
                    $scope.mensaje ='no entra';            
                    alert('Error');
                });
          }
        $scope.ponerPrecio = function(){                
                if($scope.items.item){
                  $scope.sinItem=false;
                  precio=$scope.items.item.split('/');
                  $scope.items.precio=precio[1];
                  $scope.items.descuento = precio[2];
                }else{
                  $scope.items.precio='';
                  $scope.sinItem=true;
                }
              }

$scope.reimprimirRecibo = function(noRecibo){          
          var fileName = "Reporte";
          var uri = 'api/classes/reimprimirRecibo.php?fol='+$rootScope.folio+'&cveRec='+noRecibo;
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
           if($scope.items.fam!=''&&$scope.items.item!=''){           
              $scope.sinFamilia=false; 
              $scope.sinItem=false;   
              if($scope.items.descuento<=100){ 
              $scope.cargador2=true; 
              $scope.porMayor=false;               
              precio=$scope.items.item.split('/');
              $scope.cveItem=precio[0]; 
              console.log($scope.cveItem);             
              $scope.cont ={} ;
              // $scope.verEnfermeras = false; 
              //$http.get('api/model/recibos.php?funcion=getValuesItem&cveItem='+$scope.cveItem).success(function (data){   
              $http.get('api/ventasSinRegistro.php?funcion=getValuesItem&cveItem='+$scope.cveItem).success(function (data){   
                console.log(data);                                            
                  $scope.verListadoItems=true;
                  $scope.cont.cont=cont;         
                  $scope.cont.cveMV=data.Ite_clave;
                  $scope.cont.cveItem=$scope.cveItem;
                  $scope.cont.item = data.Ite_item;
                  $scope.cont.desc = data.Ite_descripcion;
                  $scope.cont.pres = data.Ite_presentacion;
                  $scope.cont.descuento = $scope.items.descuento;

                  $scope.cont.precio = $scope.items.precio;  
                          
                  $scope.sumaItems.push($scope.cont);

                  if($scope.sumaItems!=''){
                    $scope.ningunItem=false;
                    console.log($scope.ningunItem);
                  }
                  $scope.cargador2=false;

                  $scope.items.fam='';
                  $scope.items.item='';
                  $scope.items.descuento=$scope.cont.descuento;
                  $scope.items.precio='';  

                  $scope.items.cveItem= $scope.cont.cont;
                  $scope.items.folio=$cookies.folio;

                  subtotal=0;
                  total=0;
                  porcentaje=0;
                  descuento=0;
                  descuentoCup = 0;
                  console.log($scope.nuevo.cuponDescuento);
                  if($scope.nuevo.cuponDescuento == '' || $scope.nuevo.cuponDescuento == 'undefined'){

                    var descuentoCup = 0;

                  }else{

                    var descuentoCup = 15;

                  }
                  for (var i = 0; i < $scope.sumaItems.length; i++) {  
                    if($scope.sumaItems[i].tipo==6)  $scope.verEnfermeras = true;                               
                     subtotal=parseFloat(subtotal.toFixed(2))+parseFloat($scope.sumaItems[i].precio);                    
                     porcentaje=($scope.sumaItems[i].precio*$scope.sumaItems[i].descuento)/100;
                     descuento=descuento+parseFloat(porcentaje.toFixed(2));

                     if($scope.nuevo.cuponDescuento == ''){

                        total = subtotal-descuento;

                     }else{

                         total0=subtotal-descuento;
                         total1= total0*descuentoCup/100;
                         total=  total0-total1;

                     }

                  }

                      $scope.subtotal=subtotal.toFixed(2);
                      $scope.descuento=descuento.toFixed(2);
                      $scope.total=total.toFixed(2);

                  cont++; 
                  console.log($scope.sumaItems);  
              });

                 
             
                           
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
              }else{
                if($scope.items.fam=='')
                $scope.sinFamilia=true;                
              }if($scope.items.item==''){
                $scope.sinItem=true;
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
                      $scope.items.descuento=$scope.descuento;
                      
                     
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
             $scope.verEnfermeras = false; 
              delete $scope.sumaItems[cveItem];                                                               
              $scope.sumaItems.splice(cveItem,1);              
              if($scope.sumaItems.length<=0){
                $scope.verListadoItems=false;
                $scope.ningunItem=true;
              }else{
                  $scope.verListadoItems=true;
                  subtotal=0;
                  total=0;
                  porcentaje=0;
                  descuento=0;
                  for (var i = 0; i < $scope.sumaItems.length; i++) {  
                    if($scope.sumaItems[i].tipo==6)  $scope.verEnfermeras = true;                               
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
                    console.log($scope.ningunItem);
              console.log($scope.sumaItems);
              if($scope.sumaItems!=''){                                
                $scope.mensaje=false;
                $scope.datosRec.fec=$scope.datos.fecExp;
                $scope.datosRec.noRec=$scope.datos.noRec
                $scope.todo= [$scope.datosRec,$scope.sumaItems];

                console.log($scope.todo);

                $http({
                    //url:'api/model/recibos.php?funcion=guardaRecibo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
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
        console.log($scope.datosRec.fPago);
        if($scope.datosRec.fPago==2 || $scope.datosRec.fPago==3)
        {
          $scope.verDatosTarjeta=true; 
        }else
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

  $scope.verVentaSinReg = function(){ 
          console.log($scope.tipoVenta);
          if($scope.tipoVenta=='ind'){
            $scope.membresia=true;
          }else if($scope.tipoVenta=='mem'){
            $scope.esMembresia=true;
            $scope.esEmpleado=false;
          }else if($scope.tipoVenta=='emp'){
            $scope.esMembresia=false;
            $scope.esEmpleado=true;
          }
  }
  $scope.VerificarMembresia = function(){ 

          $http({
              url:'api/ventasSinRegistro.php?funcion=verificaMem',
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: {'mem':$scope.valorMembresia}
          }).success( function (data){ 
              console.log(data);
              if(data.contador>0){
                $scope.membresia=true;
                $scope.mem=1;
                $scope.msjError=false;
              }else{
                $scope.msjError=true;
              }
          }).error( function (xhr,status,data){
              $scope.mensaje ='no entra';            
              alert('Error');
          });  
  }

  $scope.guardaEmpleado = function(){ 
                $scope.membresia=true;
                $scope.mem=2;
                $scope.msjError=false;
   
  }
  $scope.iraIndividual = function(){ 
          $scope.membresia=true;
  }

  $scope.validarCupon = function(){

  if($scope.nuevo.cuponDescuento>= '0001' && $scope.nuevo.cuponDescuento<= '0200'){

    $http.get('api/api.php?funcion=verificaCupon&fol='+$rootScope.folio+'&cupon='+$scope.nuevo.cuponDescuento).success(function (data){  
      console.log(data);
      $con = data[0].contador;
      if($con == 0){

      $http.get('api/api.php?funcion=guardarCuponVSR&cupon='+$scope.nuevo.cuponDescuento).success(function (data){           
        $scope.errorCupon='El cupón se agregó correctamente!!.';
        $scope.nuevo.bitCupon = 1;
      });

      }else{
        $scope.errorCupon='El cupón ya fue ocupado';

      }

    });
   }else{
     $scope.errorCupon='El cupón no es válido';
   }        
  }


//////////////////////////////////////////////
//console.log($scope.nuevo);

});