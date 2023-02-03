app.controller('subsecuenciaCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http) {
	$rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador=false;
  $scope.cargador1=false;
  $scope.cargador2=false;
  $scope.regresar=false;
  $scope.formularios={};
  $scope.string = '';

  //varibles para validacion de estudios solicitados
  $scope.sinEstudios=false;
  $scope.sinEstud=false; 
  $scope.estudioAgreg=true; 

   /*****************************   validación de diagnostico palablas prohibidas ***************************/
  $scope.opcionPronostico='';
  $scope.msjPalabraProhi=false;
  $scope.validaPalabra='';
  /********************************fin validacion palabras prohibidas **************************************/ 

  $scope.sub={
    sigYSin:'',
    evo:''
  }
  $scope.estudiosSub={
    rx:'',
    obs:'',
    interp:''
  }
  $scope.diagnosticoSub={
          diagnostico:'',
          obs:''
        }
  $scope.medicaSub={
          medica:'',
          posologia:'',
          cantidad:1
        }
   $scope.med={
          sustAct:'',
          medicame:'',
          presentacion:'',
          cantidad:1,
          posologia:'',
          stock:''          
        }
  $scope.ortesisSub={
          ortesis:'',
          presentacion:'',
          cantidad:1,
          indicaciones:''
        }
  $scope.ortesisSym={
          ortSymio:'',
          cantidad:1,
          indicaciones:'',
          stock:''
        }
  $scope.indicacionSub={
          indicacion:'',
          obs:''
        }
  $scope.pronostico={
          pronostico:'',
          criterio:''
        }
  $scope.datos={
          folio: $rootScope.folio
        }
  $scope.otrosEstSub={
          estudio:'',
          justObs:''
        }
  $scope.progreso=10;
  /***************** actualizacion ******************/
  $scope.siguienteMed=true;
  $scope.siguienteOrt=true;
  $scope.cargaMedicamento=true;
  $scope.checkMed=false;
  $scope.checkOrt=false;

  $scope.altaMedica=0;
  $scope.verAltaMedica=false;
  /***************** actualizacion ******************/
  $scope.interacted = function(field) {          
    return $scope.formularios.sigysinsub.$submitted && field.$invalid;          
  };
  $scope.interacted1 = function(field) {          
    return $scope.formularios.estudiosSub.$submitted && field.$invalid;          
  };
  $scope.interacted2 = function(field) {          
    return $scope.formularios.diagnosticSub.$submitted && field.$invalid;          
  };
  $scope.interacted3 = function(field) {          
    return $scope.formularios.medicSub.$submitted && field.$invalid;          
  };
  $scope.interacted4 = function(field) {          
    return $scope.formularios.formOrtesis.$submitted && field.$invalid;          
  };
  $scope.interacted5 = function(field) {          
    return $scope.formularios.formIndicaciones.$submitted && field.$invalid;          
  };
  $scope.interacted10 = function(field) {          
          return $scope.formularios.otrosEstudios.$submitted && field.$invalid;          
        };
  $scope.interacted11 = function(field) {          
    return $scope.formularios.medicSymio.$submitted && field.$invalid;          
  };
   $scope.interacted12 = function(field) {          
    return $scope.formularios.orteSymio.$submitted && field.$invalid;          
  };

  $scope.cargador=true;
  busquedas.validaSubsec($rootScope.folio).success(function(data){                      
    $scope.cargador=false;
    if(data.Cons==null){
      data.Cons=1;
    }
    $scope.noSubsec=data.Cons;    

    if ($scope.noSubsec>=4) {
        $('#modalSubsecuencias').modal('show');
    };
  });

  $scope.regresaWizard = function() {           
            WizardHandler.wizard().previous();
           
        }

  $scope.guardaSigYSinSub = function(){
    if($scope.formularios.sigysinsub.$valid){ 
      $scope.cargador=true;   
      $http({
        url:'api/api.php?funcion=guardaSigYSinSub&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cons='+$scope.noSubsec,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.sub
      }).success( function (data){                        
        if(data.respuesta=='correcto'){
         $scope.sub={
            sigYSin:'',
            evo:''
          };
          busquedas.listaRX().success(function(data){                      
            $scope.listRX=data;             
          }); 
          busquedas.listaEstSolSub($rootScope.folio).success(function(data){
                    if(data==''){
                      $scope.listEstSoliSub='';
                      $scope.sinEstudios=true;
                      $scope.estudioAgreg=true;   
                    }else{                      
                      $scope.listEstSoliSub=data; 
                      $scope.sinEstudios=false; 
                      $scope.estudioAgreg=false;                       
                    }
                  });
          $scope.regresar=true;          
          $scope.progreso=25;
          $scope.cargador=false; 
          WizardHandler.wizard().next();                  
        }
        else{          
          alert('error en la inserción');
        }        
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      });
    } 
  }

   $scope.guardaEstudiosSub = function(){ 
     if($scope.formularios.estudiosSub.$valid){    
      $scope.cargador=true;
      $http({
        url:'api/api.php?funcion=guardaEstudiosSub&fol='+$rootScope.folio+'&subCons='+$scope.noSubsec,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.estudiosSub
      }).success( function (data){                        
        if(!data.respuesta){
          $scope.cargador=false;
          $scope.estudiosSub={
            rx:'',
            obs:'',
            interp:''
          }
          $scope.formularios.estudiosSub.$submitted=false;                                
          $scope.listEstSoliSub=data; 
          $scope.estudioAgreg=false;
          $scope.sinEstudios=false;                                                               
        }
        else{        
          alert('error en la inserción');
        }      
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      }); 
    }
  }

  $scope.eliminarEstSolSub = function(claveEst){ 
            $scope.cargador=true;             
            $http({
            url:'api/api.php?funcion=eliminaEstRealizadoSub&fol='+$rootScope.folio+'&cveEst='+claveEst,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){            
              if(!data.respuesta){ 
                  if(data==''){
                    $scope.listEstSoliSub='';
                    $scope.estudioAgreg=true; 
                    $scope.sinEstudios=true;   
                  }else{                                
                    $scope.listEstSoliSub=data;
                    $scope.sinEstudios=false;   
                  }
                    $scope.cargador=false;                                                                                         
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                               
        }

        $scope.diagnSig = function(){
            $scope.cargador=true;   
            $scope.progreso=50;           
             busquedas.listaDiagnosticos().success(function(data){                      
              $scope.cargador=false;
              $scope.listaDiagnostico=data;             
            });  
            WizardHandler.wizard().next();  
        }

         $scope.despliegaDiagnosticos = function(diagnostic){
          $scope.cargador1=true;
          $http({
            url:'api/api.php?funcion=getListDiag&diag='+diagnostic,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
          }).success( function (data){
            $scope.listaDiagnostics=data;     
            $scope.cargador1=false;
          }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
          });                               
        }
        $scope.agregaDiagnostico = function(diag){            
            if($scope.diagnosticoSub.diagnostico==''||$scope.diagnosticoSub.diagnostico==undefined){
              $scope.diagnosticoSub.diagnostico=diag;
            }else{
              $scope.diagnosticoSub.diagnostico=$scope.diagnosticoSub.diagnostico+' // '+diag;
            }            
        }

         $scope.guardaDiagnosticoSub = function(diag){ 
            if($scope.formularios.diagnosticSub.$valid){ 
            $scope.validaPalabra= validaPalabrasProhibidas($scope.diagnosticoSub.diagnostico);          
            if($scope.validaPalabra==0){
            $scope.msjPalabraProhi=false;
            $scope.cargador2=true;          
            $http({
            url:'api/api.php?funcion=guardaDiagnosticoSub&fol='+$rootScope.folio+'&subCons='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.diagnosticoSub
            }).success( function (data){                        
              if(data.respuesta=='correcto'){ 

                   busquedas.listaMedSymio($rootScope.uniClave).success(function(data){                      
                    $scope.lisMedSymio=data;                                         
                  });
                  busquedas.listaOrtSymio($rootScope.uniClave).success(function(data){                      
                    $scope.lisrtOrtSymio=data;                                     
                  }); 
                   $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
                          $scope.alergias = data;
                      });

                  /*busquedas.listaMedicamentos().success(function(data){
                    if(data==''){
                      $scope.listaMedicamento='';                       
                    }else{                     
                      $scope.listaMedicamento=data;                     
                    }
                  });
                  busquedas.listaOrtesis().success(function(data){                      
                    if(data==''){
                      $scope.listaOrt='';
                    }
                    else{                                        
                      $scope.listaOrt=data;                                           
                    }
                  });*/
                  busquedas.listaIndicaciones().success(function(data){                      
                    if(data==''){
                      $scope.listaIndicacion='';  
                    }
                    else{
                      $scope.listaIndicacion=data;                     
                    }
                  }); /*
                   busquedas.listaMedicamentosAgregSub($rootScope.folio,$scope.noSubsec).success(function(data){                      
                      if(data==''){
                        $scope.listaMedicamentosASub='';
                      }
                      else{
                        $scope.listaMedicamentosASub=data;                   
                      }
                   });
                   busquedas.listaOrtesisAgreg($rootScope.folio,$scope.noSubsec).success(function(data){ 
                      if(data==''){
                        $scope.listaOrtesisAgreg='';     
                      }else{
                        $scope.listaOrtesisAgreg=data; 
                      }                                                             
                  }); */
                   busquedas.listaIndicAgregSub($rootScope.folio,$scope.noSubsec).success(function(data){                                          
                    if(data==''){
                      $scope.listaIndicAgregSub='';
                    }else{
                      $scope.listaIndicAgregSub=data;  
                    }                      
                  }); 
                  /*busquedas.listaMedicamentosAgreg($rootScope.folio).success(function(data){                      
                    $scope.listaMedicamentosAgreg=data; 
                    console.log($scope.listaMedicamentosAgreg);
                  }); 
                  busquedas.listaOrtesisAgreg($rootScope.folio).success(function(data){                      
                    $scope.listaOrtesisAgreg=data; 
                    console.log($scope.listaOrtesisAgreg);
                  });  
                  busquedas.listaIndicAgreg($rootScope.folio).success(function(data){                      
                    $scope.listaIndicAgreg=data;  
                    console.log($scope.listaIndicAgreg);
                  });*/ 
                  $scope.progreso=75;     
                  WizardHandler.wizard().next();
                  $scope.cargador2=false;                                                               
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
            }else{
              $scope.msjPalabraProhi=true;
            }
            }  
        }
         $scope.verIndicacion = function(){            
            busquedas.verPosologia($scope.medicaSub.medica).success(function(data){                      
                    $scope.medicaSub.posologia=data.Sum_indicacion;                                       
                  }); 
        }  
        $scope.verIndicacionCam = function(){            
            if($scope.indicacionSub.obs=='' || $scope.indicacionSub.obs==null){
              $scope.indicacionSub.obs=$scope.indicacionSub.indicacion;
            }else{
              $scope.indicacionSub.obs=$scope.indicacionSub.obs+', '+$scope.indicacionSub.indicacion;
            }
        }  

        $scope.guardaMedicamentoSub= function(){
        if($scope.formularios.medicSub.$valid){              
          $scope.cargador=true;
          $http({
            url:'api/api.php?funcion=guardaMedicamentoSub&fol='+$rootScope.folio+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.medicaSub
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.medicaSub={
                  medica:'',
                  posologia:'',
                  cantidad:1
                }
                $scope.formularios.medicSub.$submitted=false;                                     
                $scope.listaMedicamentosASub=data;                                   
                $scope.cargador=false;                                                        
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
          }                     
        } 
        $scope.eliminarMedicamentoSub = function(clavePro){             
            $scope.cargador=true;                  
            $http({
            url:'api/api.php?funcion=eliminaMedicamentoSub&fol='+$rootScope.folio+'&proClave='+clavePro+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){               
                if(data==''){
                  $scope.listaMedicamentosASub='';
                }else{                               
                  $scope.listaMedicamentosASub=data;
                }
                  $scope.cargador=false;                               
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                              
        }

        $scope.guardaOrtesisSub= function(){
        if($scope.formularios.formOrtesis.$valid){           
          $scope.cargador1=true;          
          $http({
            url:'api/api.php?funcion=guardaOrtesisSub&fol='+$rootScope.folio+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.ortesisSub
            }).success( function (data){
              console.log(data);
              if(!data.respuesta){ 
                $scope.ortesisSub={
                  ortesis:'',
                  presentacion:'',
                  cantidad:1,
                  indicaciones:''
                }
                $scope.formularios.formOrtesis.$submitted=false;                                     
                $scope.listaOrtesisAgregSub=data;                                   
                $scope.cargador1=false;                                                        
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
          }                     
        }

        $scope.eliminarOrtesisSub = function(clavePro){             
            $scope.cargador1=true;                  
            $http({
            url:'api/api.php?funcion=eliminaOrtesisSub&fol='+$rootScope.folio+'&proClave='+clavePro+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.medicaSub={
                  medica:'',
                  posologia:'',
                  cantidad:1
                }              
                if(data==''){
                  $scope.listaOrtesisAgregSub='';
                }else{                               
                  $scope.listaOrtesisAgregSub=data;
                }
                  $scope.cargador1=false;                               
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                              
        }

        $scope.guardaIndicacionesSub= function(){
        if($scope.formularios.formIndicaciones.$valid){          
          $scope.cargador2=true;          
          $http({
            url:'api/api.php?funcion=guardaIndicacionesSub&fol='+$rootScope.folio+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.indicacionSub
            }).success( function (data){
              console.log(data);
              if(!data.respuesta){ 
                $scope.indicacionSub={
                  indicacion:'',
                  obs:''
                }
                $scope.formularios.formIndicaciones.$submitted=false;
                if(data==''){
                  $scope.listaIndicAgregSub='';  
                }else{
                  $scope.listaIndicAgregSub=data;                                   
                }                                     
                
                $scope.cargador2=false;                                                        
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
          }                     
        }

        $scope.eliminarIndicacionesSub = function(clavePro){             
            $scope.cargador2=true;                  
            $http({
            url:'api/api.php?funcion=eliminarIndicacionesSub&fol='+$rootScope.folio+'&proClave='+clavePro+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.indicacionSub={
                  indicacion:'',
                  obs:''
                }            
                if(data==''){
                  $scope.listaIndicAgregSub='';  
                }else{
                  $scope.listaIndicAgregSub=data;                                   
                }    
                  $scope.cargador2=false;                               
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                              
        }

        /****************************     SUMINISTROS SYMIO    ***************************************/

        $scope.seleccionaMedicamentos = function(medicamento){                                       
          for(lista in $scope.lisMedSymio){
            if(medicamento==$scope.lisMedSymio[lista].Clave_producto){
              $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;
              $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;
              $scope.med.stock = $scope.lisMedSymio[lista].Stock;
            }            
          }
        }  

        $scope.seleccionaCategoria = function(){                            
          console.log($scope.med.medicame);
          $http({
              url:'api/api.php?funcion=detalleMed&clave='+$scope.med.medicame+'&uni='+$rootScope.uniClave,
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: {'clave':'valor'}
            }).success( function (data){                       
              $scope.med.presentacion=data.Sym_presentacion;
              $scope.med.posologia=data.Sym_indicacion;
              $scope.med.stock=data.Stock;             
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });    
                   
        }  

         $scope.guardaMedicamentoSymio= function(){          
          if($scope.formularios.medicSymio.$valid){            
            if($scope.med.cantidad <= $scope.med.stock){
              $scope.validaStock=false;
              $scope.cargador=true;          
              $http({
                url:'api/api.php?funcion=guardaMedicamentoSymioSub&fol='+$rootScope.folio+'&uni='+$scope.uniClave+'&cont='+$scope.noSubsec,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.med
                }).success( function (data){                                 
                  if(!data.respuesta){ 
                    console.log(data);
                     $scope.med={
                        sustAct:'',
                        medicame:'',
                        presentacion:'',
                        cantidad:1,
                        posologia:''          
                      }                                              
                      $scope.listaMedicamentosSymioSub=data;
                      $scope.formularios.medicSymio.$submitted=false; 
                      $scope.cargador=false;  
                      $scope.siguienteMed=false;                                                                                        
                  }              
                  else{                
                    alert('error en la inserción');
                  }            
                }).error( function (xhr,status,data){
                  $scope.cargador=true;
                    $scope.mensaje ='no entra';            
                    alert('Error');
                });                      
            }else{
              $scope.validaStock=true;
            }
          }
        } 


        $scope.eliminarMedicamentoSymio = function(clavePro){ 
            $scope.cargador=true;              
            $http({
            url:'api/api.php?funcion=eliminarMedicamentoSymioSub&fol='+$rootScope.folio+'&proClave='+clavePro+'&uni='+$rootScope.uniClave+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){ 
              console.log(data);                       
              if(!data.respuesta){   
                     if(data==''){
                      $scope.listaMedicamentosSymioSub='';
                      $scope.siguienteMed=true;                       
                    }else{                                                           
                      $scope.listaMedicamentosSymioSub=data;
                      $scope.siguienteMed=false;                      
                    }
                  $scope.med.medicame='';
                  $scope.med.sustAct='';
                  $scope.med.presentacion='';
                  $scope.med.cantidad=1;
                  $scope.med.posologia=''; 
                  $scope.med.stock='';             
                  $scope.cargador=false;                                                                                         
              }              
              else{                
                alert('error en la inserción');
              }           
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                               
        }

        $scope.guardaOrtesisSymio= function(){        
          if($scope.formularios.orteSymio.$valid){
            if($scope.ortesisSym.stock>=$scope.ortesisSym.cantidad){
              $scope.validaStockOrtesisSym=false;
              $scope.cargador1=true; 
              console.log($scope.ortesisSym);         
              $http({
                url:'api/api.php?funcion=guardaOrtSymioSub&fol='+$rootScope.folio+'&uni='+$scope.uniClave+'&cont='+$scope.noSubsec,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.ortesisSym
                }).success( function (data){                                 
                  if(!data.respuesta){ 
                    busquedas.listaOrtSymio($rootScope.uniClave).success(function(data1){                      
                        $scope.lisrtOrtSymio=data1;                                     
                      }); 
                     $scope.ortesisSym={
                        ortSymio:'',
                        cantidad:1,
                        indicaciones:''
                      }                                          
                      $scope.listaOrtesisSymioSub=data;
                      $scope.formularios.orteSymio.$submitted=false; 
                      $scope.cargador1=false;
                      $scope.siguienteOrt=false;                                                                                                               
                  }              
                  else{                
                    alert('error en la inserción');
                  }            
                }).error( function (xhr,status,data){
                  $scope.cargador=true;
                    $scope.mensaje ='no entra';            
                    alert('Error');
                });                      
              }else{
                $scope.validaStockOrtesisSym=true;
              }
        }
      } 

        $scope.eliminarOrtesisSymio = function(clavePro){ 
            $scope.cargador1=true;              
            $http({
            url:'api/api.php?funcion=eliminarOrtesisSymioSub&fol='+$rootScope.folio+'&proClave='+clavePro+'&uni='+$rootScope.uniClave+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){ 
               busquedas.listaOrtSymio($rootScope.uniClave).success(function(data1){                      
                    $scope.lisrtOrtSymio=data1;                                     
                  });                        
              if(!data.respuesta){   
                   if(data==''){
                      $scope.listaOrtesisSymioSub=''; 
                      $scope.siguienteOrt=true;                      
                    }else{                                                           
                      $scope.listaOrtesisSymioSub=data;                  
                      $scope.siguienteOrt=false;    
                  }
                  $scope.cargador1=false;                                                                                         
              }              
              else{                
                alert('error en la inserción');
              }           
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                               
        }


        /****************************   FIN SUMINISTROS SYMIO  ***************************************/




        $scope.pronosSiguiente = function(){                            
            WizardHandler.wizard().next();  
        } 

        $scope.otrosEstudiosSiguiente = function(){ 
            WizardHandler.wizard().next();  
            $scope.verAltaMedica=true; 
        }

        /***********************      inicio otros Estudios Subsecuencia  *****************************/

        $scope.guardaOtrosEstudiosSub= function(){ 
          if($scope.formularios.otrosEstudios.$valid){           
          $scope.cargador=true;                     
          
          $http({
            url:'api/api.php?funcion=guardaOtrosEstudiosSub&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.otrosEstSub
            }).success( function (data){ 
              console.log(data);
              if(!data.respuesta){ 
                 $scope.otrosEst={
                    estudio:'',
                    justObs:''
                  }          
                  $scope.listOtrosEstSoli=data;                                                                                                             
                  $scope.cargador=false;
                  $scope.formularios.otrosEstudios.$submitted=false;
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
          }                       
        }   

        $scope.eliminarOtrosEstudiosSub = function(claveEst){ 
            $scope.cargador=true; 
            console.log(claveEst);            
            $http({
            url:'api/api.php?funcion=eliminaOtrosEstRealizadoSub&fol='+$rootScope.folio+'&cveEst='+claveEst+'&cont='+$scope.noSubsec,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){ 
              console.log(data);                       
              if(!data.respuesta){
                  if(data==''){                                
                    $scope.listOtrosEstSoli='';       
                  }else{
                    $scope.listOtrosEstSoli=data;
                  }
                  $scope.cargador=false;                        
              }              
              else{                
                alert('error en la inserción');
                $scope.cargador=false;
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador=false;
            });                               
        }       

        /***********************         fin otros Estudios Subsecuencia   ****************************/

        $scope.guardaPronoSub = function(){              
        $scope.cargador=true;           
          $http({
            url:'api/api.php?funcion=guardaPronosticoSub&fol='+$rootScope.folio+'&subCont='+$scope.noSubsec+'&usr='+$rootScope.usrLogin+'&alta='+$scope.altaMedica,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.pronostico
            }).success( function (data){ 
              $scope.verAltaMedica=false;                        
              if(data.respuesta=='correcto'){
                  busquedas.listaDatosPacRec($rootScope.folio).success(function(data){                      
                    console.log(data);
                    $scope.datos.lesionado=data.Exp_completo;
                    $scope.datos.sexo=data.Exp_sexo;
                    $scope.datos.edad=data.Exp_edad;
                    $scope.datos.talla=data.Vit_talla;
                    $scope.datos.peso=data.Vit_peso;
                    $scope.datos.temperatura=data.Vit_temperatura;
                    $scope.datos.presion=data.Vit_ta;
                    $scope.string = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';
                    $scope.datos.qr = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';                    
                    $scope.datos.cadena = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';                                        
                    $scope.datos.receta= $scope.datos.folio;                    

                  });
                  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){          
                      $scope.datos.alergias = data;
                  });
                  $http.get('api/api.php?funcion=datosDoc&usr='+$rootScope.usrLogin).success(function (data){
                                           
                      $scope.datos.doctor = data.Med_nombre+' '+data.Med_paterno+' '+data.Med_materno;
                      $scope.datos.cedula = data.Med_cedula;
                      $scope.datos.especialidad = data.Med_esp;
                      $scope.datos.telefonos = data.Med_telefono;

                  });
                  $http.get('api/api.php?funcion=datosMedicamentoRec&fol='+$rootScope.folio).success(function (data){                                        
                      
                      $scope.datos.medicamentos=data;                    

                  });
                  $http.get('api/api.php?funcion=datosOrtRec&fol='+$rootScope.folio).success(function (data){                                        
                      
                      $scope.datos.ortesis=data;                    

                  });
                  $http.get('api/api.php?funcion=datosIndicacionesRec&fol='+$rootScope.folio).success(function (data){                                                              
                      $scope.datos.indicaciones=data;  
                      $scope.cargador1=false;              

                  });
                  
                  //envío de correo de subsecuencia
                  if ($scope.noSubsec>=4) {
                      $scope.datos.numSubsecuencia = $scope.noSubsec;
                      $scope.datos.folioLesionado = $rootScope.folio;
                      console.log($scope.datos);
                      $http({
                            url:'api/classes/mailSubsecuencia.php',
                            method:'POST', 
                            contentType: 'application/json', 
                            dataType: "json", 
                            data: $scope.datos
                            }).success( function (data){
                            //console.log(data);

                            }).error( function (xhr,status,data){
                                $scope.mensaje ='no entra';
                                alert('Error');
                                $scope.cargador=false;
                            });  
                  };
                  //termina el envio de correo

                  $scope.cargador=false;
                     WizardHandler.wizard().next();                                                   
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                         
        }

        $scope.irDocumentos = function(){         
              $location.path("/documentos");          
        }
        $scope.imprimirRecetaSub = function(){
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

         $scope.botonHabilita1 = function(){ 
          console.log('hola');                   
          if (document.getElementById('checkMed').checked)
          {
            $scope.siguienteMed=false;
          }else{
            $scope.siguienteMed=true;
          }
        }
        $scope.botonHabilita2 = function(){
          if (document.getElementById('checkOrt').checked)
          {
              $scope.siguienteOrt=false;              
          }else{
              $scope.siguienteOrt=true;             
          }
          console.log($scope.siguienteOrt);
        }



});

function validaPalabrasProhibidas(texto){
  var palabras = [/POLICONTUNDIDO/,/POLICONTUNDIDA/, /POLYCONTUNDIDO/, /POLI-CONTUNDIDO/, /POLI-CONTUNDIDA/, /POLY-CONTUNDIDO/, /POLY-CONTUNDIDA/, /POLI-TRAUMATIZADO/, /POLI-TRAUMATIZADA/, /POLI-TRAUMATISADO/, /POSIBLE/, /POCIBLE/, /POSIVLE/, /POCIVLE/];
  for (i = 0, len = palabras.length, text = ""; i < len; i++) {   
    if(palabras[i].test(texto)){      
      return palabras[i];
      break;
    }    
  }  
  return 0;  
}
