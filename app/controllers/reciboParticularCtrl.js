app.controller('reciboParticularCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http,$upload) {
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
  $scope.tarjetas=false;
  $scope.scotia=false;
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
    observaciones:'',
    enfermera:''
  }

   $scope.datosCancelacion={
    motivoC:'',
    observaciones:'',
    sustituto:'',
    solicitado: $rootScope.username
  }

  $scope.datosCancelacion1={
    motivo:'',
    Obs:'',
    sustituto:'',
    solicitado: $rootScope.username
  }

  $scope.soportesRecibo = {

    fol:'',
    soporte:'',
    archivo: '',
    temporal: ''
  }

  $scope.erromem='';
  $scope.cupon

  $scope.cuponDescuento='';
  $scope.sinRecibos=false;
  $scope.verDatosTarjeta=false;
  $scope.verDatosMultiple=false;
  $scope.Descuento=true;

  $scope.verListadoItems=false;
  $scope.sumaItems =[]; 
  $scope.mensaje=false;
  $scope.tipoCobro=0;
  $scope.mensajeError='';
   $scope.folioRelacionado='';
  $scope.errorCupon='';
  $scope.cuponDescuento='';
  $scope.verReciboDeducibleMetlife=false;
  $scope.rehabilitador=false;

  $scope.interacted = function(field) {          
    return $scope.formularios.formItem.$submitted && field.$invalid;          
  };
  $scope.interacted1 = function(field) {          
    return $scope.formularios.formRecibo.$submitted && field.$invalid;          
  };

  $http.get('api/detallePx.php?funcion=getDatosPersonales&fol='+$rootScope.folio).success(function (data){
    console.log(data);
    $scope.cia=data.Cia_clave;
    if(data.Pro_clave==4 && data.Cia_clave!=71){
      $scope.verReciboDeducibleMetlife=true;
      $scope.verListadoItems=true;
      $scope.cont ={} ;
      $scope.cont1 ={} ;
    
        console.log(data);
        if(data.Exp_deducible === null){
          $scope.deducible = 0;
        }else{
         
          $scope.deducible = data.Exp_deducible
        }
         if(data.Exp_coaseguro === null){
          $scope.coaseguro = 0;
         }else{
          $scope.coaseguro = data.Exp_coaseguro
          
         }

        
      
      $scope.verListadoItems=true;
          $scope.cont.cont=1;         
          $scope.cont.cveMV='';
          $scope.cont.cveItem=1001113;
          $scope.cont.item = 'GESTION DE DEDUCUBLE ';
          $scope.cont.desc = 'GESTION DE DEDUCIBLE ';
          $scope.cont.pres = '';
          $scope.cont.descuento = 0;
          $scope.cont.precio = parseInt($scope.deducible) ;                            
          $scope.cont.tipo = 1;
          
          
          $scope.sumaItems.push($scope.cont);


          $scope.cont1.cont=2;         
          $scope.cont1.cveMV='';
          $scope.cont1.cveItem=1001117;
          $scope.cont1.item = 'GESTION DE COASEGURO ';
          $scope.cont1.desc = 'GESTION DE COASEGURO';
          $scope.cont1.pres = '';
          $scope.cont1.descuento = 0;
          $scope.cont1.precio = parseInt($scope.coaseguro);                            
          $scope.cont1.tipo = 1;

          $scope.sumaItems.push($scope.cont1);


          console.log($scope.sumaItems);
          $scope.cargador2=false;

          $scope.items.fam='';
          $scope.items.item='';
          $scope.items.precio='';       

          subtotal=0;
          total=0;
          porcentaje=0;
          descuento=0;
          for (var i = 0; i < $scope.sumaItems.length; i++) {  
              if($scope.sumaItems[i].tipo==6)  $scope.verEnfermeras = true;                         
              subtotal=parseFloat(subtotal.toFixed(2))+parseFloat($scope.sumaItems[i].precio);                    
              porcentaje=($scope.sumaItems[i].precio*$scope.sumaItems[i].descuento)/100;
              descuento=descuento+parseFloat(porcentaje.toFixed(2));
              total=subtotal-descuento;
          }
          $scope.subtotal=subtotal.toFixed(2);
          $scope.descuento=descuento.toFixed(2);
          $scope.total=total.toFixed(2);
          cont++; 
    }

    if($scope.cia==81 && $rootScope.usrLogin=="algo"){
      console.log("hola");
      $scope.rehabilitador=true;
      $http.get('api/particulares.php?funcion=getRhDomicilio&uni='+$rootScope.uniClave).success(function (data){
        $scope.listaRehab =data;
        console.log(data);
      }); 
    }
  });

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
      
      $http.get('api/particulares.php?funcion=cechaDoctos&fol='+$rootScope.folio).success(function (data){   
        console.log(data);
        if(data==''){
            busquedas.medicos($rootScope.uniClave).success(function(data){         
                $scope.medicos=data;       
            });
        }     
        else if(data=='false'){
          $http.get('api/particulares.php?funcion=notaDigital&fol='+$rootScope.folio).success(function (data){
            console.log(data);
            if(data.contador > 0){
              busquedas.medicos($rootScope.uniClave).success(function(data){         
                $scope.medicos=data;       
              });
            }
          });  

        }else if(data.Med_activo=='N'){
              busquedas.medicos($rootScope.uniClave).success(function(data){         
                $scope.medicos=data;       
              });
        }
        else{
          $scope.datosRec.medico=data.claveUsuario;
          $scope.datosRec.medicoNombre=data.nombreUsuario;
        }      
      });

      $http.get('api/particulares.php?funcion=checaConvenio&fol='+$rootScope.folio).success(function (data){
          console.log(data);
          $scope.cvemembresia=data.convenio;
        if (data.membresia>0) {                            
          if(data.membresia!=data.convenio){
            console.log(data);
            $scope.cveMem = data.membresia;
            $scope.nomMem = 'Membresia';
            $scope.cveConvenio = data.convenio;
            $scope.nomConvenio = data.nomConvenio;
            $("#tipoPago").modal('show');
          }else{
            $scope.tipoCobro=data.convenio;
          }
        }else if(data.membresia==0){
            $scope.tipoCobro=data.membresia;
        }else if(data.membresia=='empleado'){
            $scope.tipoCobro=data.membresia;
        }else if(data.respuesta=='sinObs'){
            $scope.tipoCobro=data.convenio;
        }else if(data.respuesta=='igual'){
            $scope.tipoCobro=data.convenio;
        }
      });

      $http.get('api/api.php?funcion=checaParticular&fol='+$rootScope.folio).success(function (data){ 
            console.log(data.cvePromocion); 
            $scope.cuponDescuento= data.cupon;
            $scope.claveProm = data.cvePromocion;
            if($scope.claveProm==122){
            $scope.tarjetas=true;
          }else if($scope.claveProm==123){
            $scope.tarjetas=true;
            $scope.scotia=true;
          } 
          if(data.cvePromocion==77){
               $('#sanValentin').modal();   
          }else if(data.cvePromocion==87 && $scope.cuponDescuento!='fbrehab02'){

              $('#semanaSanta').modal();   

          }
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

    $http.get('api/api.php?funcion=getconteoRecibos&fol='+$rootScope.folio).success(function (data){   
        console.log(data[0].conteo);
        $scope.conteoRecibo = data[0].conteo;
     
    });

$scope.abreModalCancelacion = function(folio){
    $scope.folioModal=folio;
    $('#myModal').modal();   


  }

$scope.abreModalCancelacion1 = function(folio){
    $scope.folioModal=folio;
    $('#myModal1').modal();   


  }

$scope.abreModalsoporte = function(folio){
    $http.get('api/api.php?funcion=getRecibossoportes&fol='+folio).success(function (data){ 
        
        $scope.soprecibos=data;
        console.log(data);

    });  
    $scope.folioModal=folio;
    $('#myModalsoporte').modal();   


}


$scope.validarCupon = function(){
   if($scope.cuponDescuento=='fbrehab02'){
     $scope.errorCupon='';
      $http.get('api/api.php?funcion=guardarCupon&fol='+$rootScope.folio).success(function (data){           
         $('#semanaSanta').modal('hide');
      });
   }
   if($scope.cuponDescuento=='EM15'){
      $http.get('api/api.php?funcion=guardarCupon&fol='+$rootScope.folio).success(function (data){           
        $scope.errorCupon='El cupón se agregó correctamente!!.';
      });
   }

  if($scope.cuponDescuento>= '0001' && $scope.cuponDescuento<= '0200'){
    $http.get('api/api.php?funcion=verificaCupon&fol='+$rootScope.folio+'&cupon='+$scope.cuponDescuento).success(function (data){  
      console.log(data);
      $con = data[0].contador;
      if($con == 0){

      $http.get('api/api.php?funcion=guardarCupon1&fol='+$rootScope.folio+'&cupon='+$scope.cuponDescuento).success(function (data){           
        $scope.errorCupon='El cupón se agregó correctamente!!.';
      });

      }else{
        $scope.errorCupon='El cupón ya fue ocupado';

      }

    });
   }else{
     $scope.errorCupon='El cupón no es válido';
   }        
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

  $scope.relacionarFolio = function(){     
     
      $http({
        url:'api/api.php?funcion=relacionarRecibo&fol='+$rootScope.folio,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.folioRelacionado
      }).success( function (data){ 
        console.log(data);
          if(data>0){
            $scope.mensajeError='';
            $('#sanValentin').modal('hide');
          }else{
            
            $scope.mensajeError='No Existe el folio del recibo';
          }
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      });
  }

  $scope.regresarDocumentos = function(){
     $('#sanValentin').modal('hide');
     $('#semanaSanta').modal('hide');
     $location.path("/documentos");    
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


  $scope.guardarMembresia = function(){

    console.log($scope.memPaciente);
    $http.get('api/api.php?funcion=guardarMembresia&fol='+$rootScope.folio+'&mem='+$scope.memPaciente).success(function (data){           
      $scope.erromem='';
      console.log(data);
      if(data=='si'){
        $scope.erromem='La membresía se agregó correctamente';
       
      }else{
        $scope.erromem='La membresía no se encuentra'; 
      }
   });


}


  $scope.selectItem = function(){
        $scope.cargador1=true;          
          var fam=$scope.items.fam; 
          console.log($scope.tipoCobro);
          $scope.verEnfermeras = false;                                              
              $http({
                      url:'api/api.php?funcion=SelectItems&cveFam='+fam+'&fol='+$rootScope.folio+'&tipoCobro='+$scope.tipoCobro,
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
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
              }
        $scope.ponerPrecio = function(){                
         
         if($scope.items.item){                                 
            $scope.msjDoc = '';
            precio=$scope.items.item.split('/');
            $scope.items.claveItem = precio[0];     
            if($scope.items.claveItem=='1001066' || $scope.items.claveItem=='1001067'){
              console.log($scope.datosRec.medico);
              $http.get('api/api.php?funcion=getEspecialidad&medico='+$scope.datosRec.medico+'&cveItem='+$scope.items.claveItem).success(function (data){                      
                console.log(data);
                //console.log(data.length);
                if(data=='NOGeneral'){
                  $("#mensajeDoc").modal('show');
                  $scope.msjDoc = 'El médico que atendio tiene Especialidad por lo cual no se puede cobrar consulta general'; 
                   $scope.items.fam='';
                  $scope.items.item='';
                  $scope.items.precio='';
                  $scope.listItems='';                            
                }else if(data=='NOEspecialista'){
                  $("#mensajeDoc").modal('show');
                  $scope.msjDoc = 'El médico que atendio no tiene Especialidad por lo cual no se puede cobrar consulta de ortopedia';                                         
                   $scope.items.fam='';
                  $scope.items.item='';
                  $scope.items.precio='';  
                  $scope.items.descuento='';  
                  $scope.listItems='';   
                }
              });
            }
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

$scope.reimprimirRecibo1 = function(noRecibo,serie){          
          var fileName = "Reporte";
          var uri = 'api/classes/reimprimirRecibo1.php?fol='+$rootScope.folio+'&cveRec='+noRecibo+'&serie='+serie;
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
           $scope.conCupon = 0;                                                
           if($scope.formularios.formItem.$valid){               
              if($scope.items.descuento<=100){ 

              $http.get('api/api.php?funcion=verificaExpCupon&fol='+$rootScope.folio).success(function (data){           
                 console.log(data[0].contador);
                 $scope.conCupon = data[0].contador;
                 console.log($scope.conCupon);
                 if($scope.conCupon == 1){

                      $scope.cargador2=true; 
                      $scope.porMayor=false;               
                      precio=$scope.items.item.split('/');
                      $scope.cveItem=precio[0];              
                      $scope.cont ={} ;
                      console.log($scope.cveItem);
                      $http.get('api/recibos.php?funcion=getValuesItem&cveItem='+$scope.cveItem).success(function (data){   
                          console.log(data);
                          $scope.verListadoItems=true;
                          $scope.cont.cont=cont;         
                          $scope.cont.cveMV=data.Ite_clave;
                          $scope.cont.cveItem=data.Ite_cons;
                          $scope.cont.item = data.Ite_item;
                          $scope.cont.desc = data.Ite_descripcion;
                          $scope.cont.pres = data.Ite_presentacion;
                          $scope.cont.descuento = $scope.items.descuento;
                          $scope.cont.precio = $scope.items.precio;                            
                          $scope.cont.tipo = data.Tip_clave;
                          console.log($scope.sumaItems.length);
                          $scope.sumaItems.push($scope.cont);
                          $scope.cargador2=false;

                          $scope.items.fam='';
                          $scope.items.item='';
                          $scope.items.precio='';  
                          $scope.listItems='';        

                          subtotal=0;
                          total=0;
                          porcentaje=0;
                          descuento=0;
                          descuentoCup = 15;
                          for (var i = 0; i < $scope.sumaItems.length; i++) {  
                             if($scope.sumaItems[i].tipo==6)  $scope.verEnfermeras = true;                         
                             subtotal=parseFloat(subtotal.toFixed(2))+parseFloat($scope.sumaItems[i].precio);                    
                             porcentaje=($scope.sumaItems[i].precio*$scope.sumaItems[i].descuento)/100;
                             descuento=descuento+parseFloat(porcentaje.toFixed(2));
                             if(data.Ite_cons == '1001092'){

                               total0=subtotal-descuento;
                               total00 = total0 - 50;
                               total1= total00*descuentoCup/100;
                               total=  total0-total1;
                             }else{

                               total0=subtotal-descuento;
                               total00 = total0;
                               total1= total00*descuentoCup/100;
                               total=  total0-total1;
                             }


                             console.log(total1);

                          }
                          $scope.subtotal=subtotal.toFixed(2);
                          $scope.descuento=descuento.toFixed(2);
                          $scope.descuentoCup=descuentoCup.toFixed(2);
                          $scope.total=total.toFixed(2);
                          cont++; 
                      });
                      $scope.formularios.formItem.$submitted=false;


                 }else{

                        $scope.conCupon = 0;
                        $scope.cargador2=true; 
                        $scope.porMayor=false;               
                        precio=$scope.items.item.split('/');
                        $scope.cveItem=precio[0];              
                        $scope.cont ={} ;
                        console.log($scope.cveItem);
                        $http.get('api/recibos.php?funcion=getValuesItem&cveItem='+$scope.cveItem).success(function (data){   
                            console.log(data);
                            $scope.verListadoItems=true;
                            $scope.cont.cont=cont;         
                            $scope.cont.cveMV=data.Ite_clave;
                            $scope.cont.cveItem=data.Ite_cons;
                            $scope.cont.item = data.Ite_item;
                            $scope.cont.desc = data.Ite_descripcion;
                            $scope.cont.pres = data.Ite_presentacion;
                            $scope.cont.descuento = $scope.items.descuento;
                            $scope.cont.precio = $scope.items.precio;                            
                            $scope.cont.tipo = data.Tip_clave;
                            console.log($scope.sumaItems.length);
                            $scope.sumaItems.push($scope.cont);
                            $scope.cargador2=false;

                            $scope.items.fam='';
                            $scope.items.item='';
                            $scope.items.precio='';  
                            $scope.listItems='';        

                            subtotal=0;
                            total=0;
                            porcentaje=0;
                            descuento=0;
                            descuentoCup = 0;
                            for (var i = 0; i < $scope.sumaItems.length; i++) {  
                               if($scope.sumaItems[i].tipo==6)  $scope.verEnfermeras = true;                         
                               subtotal=parseFloat(subtotal.toFixed(2))+parseFloat($scope.sumaItems[i].precio);                    
                               porcentaje=($scope.sumaItems[i].precio*$scope.sumaItems[i].descuento)/100;
                               descuento=descuento+parseFloat(porcentaje.toFixed(2));
                               total=subtotal-descuento;
                            }
                            $scope.subtotal=subtotal.toFixed(2);
                            $scope.descuento=Math.round(descuento);
                            $scope.descuento= $scope.descuento.toFixed(2);
                            $scope.total=Math.round(total);
                            $scope.total = $scope.total.toFixed(2);
                            cont++; 
                        });
                        $scope.formularios.formItem.$submitted=false;
                }

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
                      // $scope.total=total.toFixed(2);
                      console.log(total.toFixed(2));
                      $scope.total = Math.round(total.toFixed(2));
                      
                     
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

          if ($scope.datosRec.medico == '') {

             if(confirm("¿Quieres guardar el recibo sin asignar Médico?")){
               

                $scope.cargadorEnvia=true;

              if($scope.sumaItems!=''){                                
                $scope.mensaje=false;
                $scope.datosRec.fec=$scope.datos.fecExp;
                $scope.datosRec.noRec=$scope.datos.noRec
                $scope.todo= [$scope.datosRec,$scope.sumaItems];

                if($scope.tipoCobro.$submitted==false){
                  $scope.tipoCobro=0;
                }

                $http({
                    url:'api/recibos.php?funcion=guardaRecibo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&tipoCobro='+$scope.tipoCobro,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.todo
                    }).success( function (data){
                      console.log(data); 
                      if(data.respuesta=='exito'){
                        var fileName = "Reporte";
                        var uri = 'api/classes/imprimirRecibo1.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cveRec='+data.folRecibo;
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

                    
                }else{
                  $scope.mensaje=true;
                  $scope.cargadorEnvia=false;
                } 

            }else{
            
               alert('Genera Nota Médica, Nota Soap o genera una subsecuencia');

            }


          }else{

              $scope.cargadorEnvia=true;

              if($scope.sumaItems!=''){                                
                $scope.mensaje=false;
                $scope.datosRec.fec=$scope.datos.fecExp;
                $scope.datosRec.noRec=$scope.datos.noRec
                $scope.todo= [$scope.datosRec,$scope.sumaItems];

                if($scope.tipoCobro.$submitted==false){
                  $scope.tipoCobro=0;
                }

                $http({
                    url:'api/recibos.php?funcion=guardaRecibo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&tipoCobro='+$scope.tipoCobro,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.todo
                    }).success( function (data){
                      console.log(data); 
                      if(data.respuesta=='exito'){
                        var fileName = "Reporte";
                        var uri = 'api/classes/imprimirRecibo1.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cveRec='+data.folRecibo;
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
          if($scope.claveProm==122){
            $scope.datosRec.banco='CITIBANAMEX';
          }else if($scope.claveProm==123){
            $scope.datosRec.banco='SCOTIABANK';
          }
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
  $scope.elegirTipocobro = function(){         
         $("#tipoPago").modal('hide');
         console.log($scope.tipoCobro);
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
          $('#Reimprimir').modal('hide');  
          
          $http.get('api/api1.php?funcion=getRecibos&fol='+$rootScope.folio).success(function (data){ 
              
              $scope.listarecibos=data;
              if($scope.listarecibos==''){
                $scope.sinRecibos=false;
              }
              else{
                $scope.sinRecibos=true;            
              }
          });                                                       
    }).error( function (xhr,status,data){
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
          $http.get('api/api1.php?funcion=getRecibos&fol='+$rootScope.folio).success(function (data){ 
              
              $scope.listarecibos=data;
              if($scope.listarecibos==''){
                $scope.sinRecibos=false;
              }
              else{
                $scope.sinRecibos=true;            
              }
          });  


  }).error( function (xhr,status,data){            
    $scope.cargador=false;          
    $scope.mensaje ='no entra';            
    alert('Error');
  });                               
}


});