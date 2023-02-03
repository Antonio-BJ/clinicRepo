app.controller('notaMedicaCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http,$q,webStorage) {	  
  $rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $rootScope.permisos=JSON.parse($cookies.permisos);   
  $scope.formularios={};  
  $rootScope.cargador=false;
  $rootScope.cargador1=false;
  $rootScope.cargador2=false;
  $scope.cargador3=false;
  $scope.cargadorModalMed=false;
  $scope.cargadorModalOrt=false;
  $scope.mensaje=false;
  $scope.mensajeLesion=false;
  $scope.irVitales=false;
  $scope.verVitales=false;
  $scope.validaHistoria=true;
  $scope.siEmb='No';  
  $scope.regresar=false;
  $scope.paso='';
  $scope.sexo='';
  $scope.categoria='';
  $scope.string = '';
  $scope.validaStock=false;
  $scope.validaStockOrtesisSym=false;
  $rootScope.vitSelect='';
  $scope.cargadorMed=true;
  $scope.cargadorOrt=true;
  $scope.siguienteMed=true;
  $scope.siguienteOrt=true;
  $scope.cargaMedicamento=true;
  $scope.checkMed=false;
  $scope.checkOrt=false;
  $scope.recargarMed=true;
  $scope.segundaRecarga=0;
  $scope.segundaRecargaOrt=0;
  $scope.mensajevacio=false;
  $scope.opcionPronostico='';
  $scope.sinEstudios=false;
  $scope.sinEstud=false; 
  $scope.estudioAgreg=true; 

  $scope.verListaSumAlter=true;
  $scope.verListaOrtAlter=true;
  $scope.opcionPronostico='';
  $scope.msjPalabraProhi=false;
  $scope.validaPalabra='';

  $scope.msjPalabraProhiInd=false;
  $scope.validaPalabraInd='';
  $scope.verConsent = true;

  var tiempo = new Date();
                
    var dd = tiempo.getDate(); 
    var mm = tiempo.getMonth()+1;//enero es 0! 
    if (mm < 10) { mm = '0' + mm; }
    if (dd < 10) { dd = '0' + dd; }

    var yyyy = tiempo.getFullYear();
    //armamos fecha para los datepicker
    var FechaAct = yyyy + '-' + mm + '-' + dd;
    var FechaAct2 = dd + '/' + mm + '/' + yyyy;

    var hora = tiempo.getHours();
    var minuto = tiempo.getMinutes();

    var HoraAct = hora + ':' + minuto;

    $scope.accidente={
        llega:'',
        fecha:'',
        vehiculo:'',
        mecanismo:[],
        seguridad:[],
        vomido:'',
        mareo:'',
        nauseas:'',
        conocimiento:'',
        cefalea:'',
        mecLesion:'',
        stringMec:'',
        stringSeg:'',
        estado:'',
        glasgow:15
    }
    $rootScope.arregloOpc={  
               Nod:'SI',
               Nodtx:'SI',
               Cintu:'NO',
               Cintutx:'NO',
               Bolsa:'NO',
               Bolsatx:'NO',
               Ropa:'NO',
               Ropatx:'NO',
               Casco:'NO',
               Cascotx:'NO',
               Rodi:'NO',
               Roditx:'NO',
               Code:'NO',
               Codetx:'NO',
               Costi:'NO',
               Costitx:'NO',
               Nodi:'NO',
               Noditx:'NO',
               Volc:'NO',
               Volctx:'NO',
               Alca:'NO',
               Alcatx:'NO',
               Late:'NO',
               Latetx:'NO',
               Fron:'NO',
               Frontx:'NO',
               Derr:'NO',
               Derrtx:'NO',
               Simp:'NO',
               Simptx:'NO',
               Imap:'NO',
               Imaptx:'NO',
               Caid:'NO',
               Caidtx:'NO',
               Lesdep:'NO',
               Lesdeptx:'NO',
               Lestra:'NO',
               Lestratx:'NO',
               ManSub:'NO',
               ManSubtx:'NO',
               Otr:'NO',
               Otrtx:'NO'
              }   
        $scope.embarazo={
            controlGine:'No',
            semanas:'',
            dolor:'No',
            desc:'',
            fcFet:'',
            movFet:'No',
            justif:''

        }
        $scope.lesion={
          lesion:''
        }
        $scope.edoGral={
          estado:'',
          glasgow:15
        }
        $scope.estudios={
          rx:'',
          obs:'',
          interp:''
        }
        $scope.procedimientos={
          procedimiento:'',
          obs:''
        }
        $scope.diagnostico={
          diagnostico:'',
          glasgow:15,
          obs:''
        }
        $scope.med={
          sustAct:'',
          medicame:'',
          presentacion:'',
          cantidad:1,
          posologia:'',
          stock:''          
        }
        $scope.medica={
          medica:'',
          posologia:'',
          cantidad:1
        }
        $scope.ortesis={
          ortesis:'',
          presentacion:'',
          cantidad:1,
          indicaciones:''
        }
        $scope.indicacion={
          indicacion:'',
          obs:''
        }
        $scope.pronostico={
          pronostico:'',
          criterio:''
        }
        $scope.vital={
          clave:'',
          
        }
        $scope.otrosEst={
          estudio:'',
          justObs:''
        }
        $scope.datos={
        	folio: $rootScope.folio
        }
        $scope.ortesisSym={
          ortSymio:'',
          cantidad:1,
          indicaciones:'',
          stock:''
        }

        /********** para listado de medicamentos symio  ********/
        $scope.lisMedSymio={
          Clave_producto:'',
          Stock:0,
          indicaciones:'',
          stock:''
        }

        /*******************************************************/

        /********** para agregar medicamentos alternativos cuando no carga el listado  ********/
        $scope.medAlter={
          medicamento:'',
          cantidad:1,
          indicaciones:''          
        }
        $scope.ortAlter={
          ortesis:'',
          cantidad:1,
          indicaciones:''          
        }
        $scope.sumaMed =[]; 
        $scope.sumaOrt =[]; 

        /*******************************************************/

         /********************** Lesion Administrativa **********/

        $scope.lesionAdmin={
          
          tipoLesion:'',
          lesionMultiple:'',
          lesionUnica:'',
          lesionOtro:'',
          lesionMultiple:false,          
          example1model:[]         
        }

        /*******************************************************/

        /**************************  para diagnostico tipo lesiones lesiones y descripcion *******************/

        $scope.verUnica=  false;
        $scope.verMultiple= false;
        $scope.verLeve=   false;
        $scope.verSimple=   false;

        $scope.example1model = []; 
        $scope.example1data = [ {id: 1, label: "Enrique Erick Gutierrez Rojas"}, 
                    {id: 2, label: "Jhon"}, 
                    {id: 3, label: "Danny"},
                    {id: 4, label: "prueba"}]; 
        $scope.verMultipleSecundaria=false;
        $scope.verMensajePolicontundidos=false;
        $scope.acumulado='';
        $scope.cveProducto='';
        $scope.vertipoLesiones=true;
        $scope.noPersonales=true;
   /***************************************  fin diagnostico ********************************************/
        $scope.estatusNot='';
        $scope.interacted = function(field) {          
          return $scope.formularios.dAccidente.$submitted && field.$invalid;          
        };
        $scope.interacted1 = function(field) {          
          return $scope.formularios.embarazoForm.$submitted && field.$invalid;          
        };
        $scope.interacted2 = function(field) {          
          return $scope.formularios.estadoGral.$submitted && field.$invalid;          
        };
        $scope.interacted3 = function(field) {          
          return $scope.formularios.estudio.$submitted && field.$invalid;          
        };
        $scope.interacted4 = function(field) {          
          return $scope.formularios.proce.$submitted && field.$invalid;          
        };
         $scope.interacted5 = function(field) {          
          return $scope.formularios.diagnos.$submitted && field.$invalid;          
        };
        $scope.interacted6 = function(field) {          
          return $scope.formularios.medic.$submitted && field.$invalid;          
        };
        $scope.interacted7 = function(field) {          
          return $scope.formularios.orte.$submitted && field.$invalid;          
        };
        $scope.interacted8 = function(field) {          
          return $scope.formularios.indica.$submitted && field.$invalid;          
        };
        $scope.interacted9 = function(field) {          
          return $scope.formularios.prono.$submitted && field.$invalid;          
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
        $scope.interacted13 = function(field) {          
          return $scope.formularios.lesAdmin.$submitted && field.$invalid;          
        };


  /***************************     Estatus de la ntoa Médica        **********************/ 
        $http.get('api/api.php?funcion=verificaSexo&fol='+$rootScope.folio).success(function (data){                       
            $scope.sexo=data; 
            if($scope.sexo=='F'){
              busquedas.listaEmbarazo($rootScope.folio).success(function(data){                      
                $scope.listEmbarazo=data;                                           
              }); 
            }                  
        });

        $http.get('api/catalogos.php?funcion=verProducto&fol='+$rootScope.folio).success(function (data){                               
            $scope.cveProducto=data; 
            if($scope.cveProducto==10){
              $scope.vertipoLesiones=false;
            }if($scope.cveProducto==2){
              $http.get('api/catalogos.php?funcion=verCiaLocalidad&fol='+$rootScope.folio).success(function (data){ 
                  if(data.Cia_clave==12){
                    $scope.noPersonales=false;
                  }
                  if(data.Cia_clave==18&&data.LOC_claveint!=16){
                    $scope.noPersonales=false;
                  }
              });  
              
            } 
        });  

         busquedas.estatusNota($rootScope.folio).success(function(data){                                         
          $scope.estatusNot=data;                    
          if($scope.estatusNot=="0"|| $scope.estatusNot==''){            
              busquedas.validaSigVitales($rootScope.folio).success(function(data){                                          
                if(data.noRowVit==1){
                        busquedas.validaHistoriaClinica($rootScope.folio).success(function(data){                   
                       if(data!='false'){
                          $scope.validaHistoria=true;
                          $scope.regresar=true;
                          $rootScope.vitSelect='';
                          WizardHandler.wizard().goTo(1); 
                       }else{
                          $scope.validaHistoria=false;
                       }
                  });
                 
                }
                if(data.noRowVit>1){               
                  $scope.verVitales=true;
                   busquedas.listaVitales($rootScope.folio).success(function(data){
                    $scope.vital.clave=data[0].Vit_clave;
                  $scope.listVitales=data;                          
                  $rootScope.cargador=false;
                });  
                }
                if(data.noRowVit==0||data.noRowVit==null){
                  $scope.irVitales=true;
                }                        
            });             
          }else{               
                switch($scope.estatusNot){
                  case "2":
                      $scope.cargador=false;
                      if($scope.sexo==''){
                      $http.get('api/api.php?funcion=verificaSexo&fol='+$rootScope.folio).success(function (data){                       
                          $scope.sexo=data;                    
                          });
                      }
                       busquedas.listaLesion().success(function(data){                      
                        $scope.listLesion=data;                                 
                      }); 
                       busquedas.listaLesiones($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listLesiones='';  
                        }else{
                          $scope.listLesiones=data;                                   
                        }
                        $scope.cargador=false;
                      });
                        $scope.regresar=true;
                        
                  break;
                  case "3":
                      busquedas.listaRX().success(function(data){                      
                        $scope.listRX=data;                                         
                      });
                      busquedas.listaEstSol($rootScope.folio).success(function(data){
                        if(data!=''){                      
                          $scope.listEstSoli=data; 
                          $scope.sinEstudios=false; 
                          $scope.estudioAgreg=false;                
                        }else{
                          $scope.sinEstudios=true;
                          $scope.estudioAgreg=true; 
                        }
                         $scope.cargador=false;
                      }); 
                       $scope.regresar=true;
                       busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      }); 
                       $scope.cargador2=true;
                      busquedas.listaDiagnosticos().success(function(data){                      
                        $scope.cargador2=false;
                        $scope.listaDiagnostico=data;              
                      }); 
                      $scope.cargador=false;                    
                       $scope.regresar=true;             
                  break;
                  case "4":
                        /******************** prueba de tiempo de ejecusión  *********************************/
                           $scope.timeout = 3; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequest = httpRequestHandler();                                                                                                                                                  
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      
                      /******************** prueba de tiempo de ejecusión  *********************************/
                            $scope.timeoutOrt = 3; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
                          $scope.alergias = data;
                      });                                       

                      busquedas.listaIndicaciones().success(function(data){                      
                        $scope.listaIndicacion=data;                     
                      });                       
                      busquedas.listaIndicAgreg($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listaIndicAgreg='';  
                        }else{
                          $scope.listaIndicAgreg=data;                     
                        }                        
                      });
                      busquedas.listadoMedAgregSymio($rootScope.folio).success(function(data){                                                               
                        if(data==''){
                          $scope.listaMedicamentosSymio='';
                          $scope.siguienteMed=true;
                        }else{
                          $scope.listaMedicamentosSymio=data;                     
                          $scope.siguienteMed=false;
                        }                        
                      });
                      busquedas.listadoOrtAgregSymio($rootScope.folio).success(function(data){                                             
                        if(data==''){
                          $scope.listaOrtesisSymio='';
                          $scope.siguienteOrt=true;  
                        }else{
                          $scope.listaOrtesisSymio=data;                     
                          $scope.siguienteOrt=false;
                        }                        
                      });
                       busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      }); 

                      $http.get('api/catalogos.php?funcion=pronostico&fol='+$rootScope.folio).success(function (data){                                                 
                          $scope.pronostico.pronostico=data.ObsNot_pron;                          
                      });                    
                      $scope.cargador=false; 
                       $scope.regresar=true;                                    
                  break;
                  case "5":

                    busquedas.listaLesion().success(function(data){                      
                      $scope.listLesion=data;                                 
                    }); 
                     busquedas.listaLesiones($rootScope.folio).success(function(data){                      
                      if(data==''){
                        $scope.listLesiones='';  
                      }else{
                        $scope.listLesiones=data;                                   
                      }
                      $scope.cargador=false;
                    });

                     $http.get('api/catalogos.php?funcion=lesionAdmin&fol='+$rootScope.folio).success(function (data){                                                                                    
                        $scope.lesionAdmin.tipoLesion=data.ObsNot_tipoLesion;                        
                         switch($scope.lesionAdmin.tipoLesion){
                          case '1':
                              
                              $scope.verUnica=  true;
                              $scope.verMultiple= false;
                              $scope.verLeve=   false;
                              $scope.verSimple=   false;
                              $scope.listadoOtros='';
                              $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=3').success(function (data){                                     
                                 
                                  $scope.listadoUnica=data;
                                  $scope.listadoMultiple='';
                                  $scope.verMultipleSecundaria=false;
                                  $scope.verMensajePolicontundidos=false;
                              }); 
                              $http.get('api/catalogos.php?funcion=LesionSelect&fol='+$rootScope.folio).success(function (data){
                                  console.log(data);  
                                  $scope.lesionAdmin.lesionUnica=data.Clave_lesionMV;
                              });
                          break;
                          case '2':                              
                              $scope.verUnica=  false;
                              $scope.verMultiple= true;
                              $scope.verLeve=   false;
                              $scope.verSimple=   false;
                              $scope.listadoOtros='';
                              $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=4').success(function (data){                                  
                                  $scope.verMultipleSecundaria=false;                
                                  $scope.lesionAdmin.lesionUnica ='';             
                                  $scope.listadoUnica='';
                                  $scope.listadoMultiple=data;
                                  $scope.listadoMultipleSecundaria=data;
                                  $http.get('api/catalogos.php?funcion=LesionSelect&fol='+$rootScope.folio).success(function (data){
                                    console.log(data);  
                                    $scope.lesionAdmin.lesionMultiple=data.Clave_lesionMV;
                                  }); 
                              }); 
                          break;
                          case '3':         
                            $scope.verUnica=  false;
                              $scope.verMultiple= false;
                              $scope.verLeve=   true;
                              $scope.verSimple=   false;
                              $scope.listadoOtros='';
                          break;
                          case '4':         
                            $scope.verUnica=  false;
                              $scope.verMultiple= false;
                              $scope.verLeve=   false;
                              $scope.verSimple=   true;
                              $scope.listadoOtros='';
                          break;
                          case '5':            
                              $scope.verUnica=  false;
                              $scope.verMultiple= false; 
                              $scope.verLeve=   false;
                              $scope.verSimple=   false;
                              $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=7').success(function (data){                                
                                  $scope.lesionAdmin.lesionUnica ='';             
                                  $scope.listadoUnica='';
                                  $scope.listadoMultiple='';
                                  $scope.listadoMultipleSecundaria='';
                                  $scope.listadoOtros=data;
                              }); 
                          break;

                              }
                    });
                                                          
                  break;
                  case "6":
                      busquedas.listaRX().success(function(data){                      
                        $scope.listRX=data;                                         
                      });
                      busquedas.listaEstSol($rootScope.folio).success(function(data){
                        if(data!=''){                      
                          $scope.listEstSoli=data; 
                          $scope.sinEstudios=false; 
                          $scope.estudioAgreg=false;                
                        }else{
                          $scope.sinEstudios=true;
                          $scope.estudioAgreg=true; 
                        }
                         $scope.cargador=false;
                      }); 
                       $scope.regresar=true;
                  break; 
                  case "7":
                      busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      }); 
                       $scope.regresar=true;
                  break;
                  case "8":
                      $scope.cargador2=true;
                      busquedas.listaDiagnosticos().success(function(data){                      
                        $scope.cargador2=false;
                        $scope.listaDiagnostico=data;              
                      }); 
                      $scope.cargador=false;
                       $scope.regresar=true;                 
                  break;
                  case "9": 
                      busquedas.listaOtrosEst().success(function(data){                      
                        $scope.lisOtrosEst=data;                     
                      });                     
                      busquedas.listaOtrosEstudios($rootScope.folio).success(function(data){                      
                        $scope.cargador2=false;
                        console.log(data);
                        if(data==''){
                          $scope.listOtrosEstSoli='';   
                        }else{
                         $scope.listOtrosEstSoli=data;              
                        }
                      }); 
                      $scope.cargador=false;
                       $scope.regresar=true;
                  break;
                  case "10":


                      /******************** prueba de tiempo de ejecusión  *********************************/
                           $scope.timeout = 3; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequest = httpRequestHandler();                                                                                                                                                  
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      
                      /******************** prueba de tiempo de ejecusión  *********************************/
                            $scope.timeoutOrt = 3; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
                          $scope.alergias = data;
                      });                                       

                      busquedas.listaIndicaciones().success(function(data){                      
                        $scope.listaIndicacion=data;                     
                      });                       
                      busquedas.listaIndicAgreg($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listaIndicAgreg='';  
                        }else{
                          $scope.listaIndicAgreg=data;                     
                        }                        
                      });
                      busquedas.listadoMedAgregSymio($rootScope.folio).success(function(data){                                                               
                        if(data==''){
                          $scope.listaMedicamentosSymio='';
                          $scope.siguienteMed=true;
                        }else{
                          $scope.listaMedicamentosSymio=data;                     
                          $scope.siguienteMed=false;
                        }                        
                      });
                      busquedas.listadoOrtAgregSymio($rootScope.folio).success(function(data){                                             
                        if(data==''){
                          $scope.listaOrtesisSymio='';
                          $scope.siguienteOrt=true;  
                        }else{
                          $scope.listaOrtesisSymio=data;                     
                          $scope.siguienteOrt=false;
                        }                        
                      });
                      $scope.cargador=false; 
                       $scope.regresar=true;             
                  break;
                  case "11":

                      busquedas.listaDatosPacRec($rootScope.folio).success(function(data){                      
                    
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
                    $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=12').success(function (data){                                
                    });
                      $scope.cargador=false;
                       $scope.regresar=true;
                  break;
                  case "12":
                      busquedas.listaDatosPacRec($rootScope.folio).success(function(data){                      
                    
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

                  $http.get('api/api.php?funcion=datosUni&uni='+$rootScope.uniClave).success(function (data){
                      $scope.datos.direccionUni  = data.Uni_calleNum+', '+data.Uni_colMun;
                      $scope.datos.telUni = data.Uni_tel; 
                      
                  });

                  $http.get('api/api.php?funcion=generaFolio&fol='+$rootScope.folio).success(function (data){                                            
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
                    $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=12').success(function (data){                                
                    });
                      $scope.cargador=false;
                       $scope.regresar=true;
                  break;

                }  


                WizardHandler.wizard().goTo($scope.estatusNot);                     
          }

          
        });

/***************************    fin Estatus de la ntoa Médica        **********************/
        
        var hoy = new Date(); 
          var dd = hoy.getDate(); 
          var mm = hoy.getMonth()+1;//enero es 0! 
          if (mm < 10) { mm = '0' + mm; }
          if (dd < 10) { dd = '0' + dd; }

          var yyyy = hoy.getFullYear();
          //armamos fecha para los datepicker
          var FechaAct = dd + '-' + mm + '-' +yyyy;          
          $scope.accidente.fecha=FechaAct;           

        busquedas.listaPacLlega($rootScope.folio).success(function(data){                      
          $scope.listPacLLega=data;                   
        });
        busquedas.listaTipVehi($rootScope.folio).success(function(data){                      
          $scope.listTipVehi=data;                             
        });
         busquedas.listaLesion().success(function(data){                      
                        $scope.listLesion=data;                                 
                      }); 
                       busquedas.listaLesiones($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listLesiones='';  
                        }else{
                          $scope.listLesiones=data;                                   
                        }
                        $scope.cargador=false;
                      });
                        $scope.regresar=true;
                        
        $scope.cargador=false;
        $scope.selectVital = function() {            
            $rootScope.vitSelect=$scope.vital.clave;          
            $scope.regresar=true;
            WizardHandler.wizard().next();  
            
        }              
        $scope.regresaWizard = function() {           
            if($scope.sexo==''){
            $http.get('api/api.php?funcion=verificaSexo&fol='+$rootScope.folio).success(function (data){                       
                $scope.sexo=data;                    
                });
            }
            
              WizardHandler.wizard().previous();
              $scope.pas= $scope.paso;
              $scope.pas--;           

             switch($scope.pas){
                  case 1:
                      $location.path("/documentos");
                  break;
                  case 2:                     
                      //$scope.cargador=false;
                      if($scope.sexo==''){
                      $http.get('api/api.php?funcion=verificaSexo&fol='+$rootScope.folio).success(function (data){                       
                          $scope.sexo=data;                    
                          });                      
                      }
                       busquedas.listaLesion().success(function(data){                      
                        $scope.listLesion=data;                                 
                      }); 
                       busquedas.listaLesiones($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listLesiones='';  
                        }else{
                          $scope.listLesiones=data;                                   
                        }
                        //$scope.cargador=false;
                      });
                        $scope.regresar=true;
                        $http.get('api/catalogos.php?funcion=datosAccidente&fol='+$rootScope.folio).success(function (data){                       
                          console.log(data);  
                          $scope.accidente.estado=data.ObsNot_edoG; 
                          $scope.accidente.mecLesion=data.Not_obs;
                          $scope.accidente.glasgow= data.ObsNot_glasgow; 
                          $scope.accidente.llega = data.Llega_clave;
                          fecha = data.Not_fechaAcc.split('-');                          
                          fechaRe = fecha[2]+'-'+fecha[1]+'-'+fecha[0];
                          $scope.accidente.fecha = fechaRe;
                          $scope.accidente.vehiculo = data.TipoVehiculo_clave; 
                          $scope.accidente.posicion = data.Posicion_clave;            
                        });
                  break;
                  case 3:
                      busquedas.listaRX().success(function(data){                      
                        console.log(data);
                        $scope.listRX=data;                                         
                      });
                      busquedas.listaEstSol($rootScope.folio).success(function(data){
                        if(data!=''){                      
                          $scope.listEstSoli=data; 
                          $scope.sinEstudios=false; 
                          $scope.estudioAgreg=false;                
                        }else{
                          $scope.sinEstudios=true;
                          $scope.estudioAgreg=true; 
                        }
                         $scope.cargador=false;
                      }); 
                       $scope.regresar=true;
                       busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      }); 
                       $scope.cargador2=true;
                      busquedas.listaDiagnosticos().success(function(data){                      
                        $scope.cargador2=false;
                        $scope.listaDiagnostico=data;              
                      }); 
                      $scope.cargador=false;                    
                       $scope.regresar=true; 
                       $http.get('api/catalogos.php?funcion=diagnostico&fol='+$rootScope.folio).success(function (data){                       
                          console.log(data);  
                          $scope.diagnostico.diagnostico=data.ObsNot_diagnosticoRx; 
                          $scope.diagnostico.obs=data.ObsNot_obs;                                 
                        });            
                  break;
                  case 4:
                        /******************** prueba de tiempo de ejecusión  *********************************/
                           $scope.timeout = 3; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequest = httpRequestHandler();                                                                                                                                                  
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      
                      /******************** prueba de tiempo de ejecusión  *********************************/
                            $scope.timeoutOrt = 3; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
                          $scope.alergias = data;
                      });                                       

                      busquedas.listaIndicaciones().success(function(data){                      
                        $scope.listaIndicacion=data;                     
                      });                       
                      busquedas.listaIndicAgreg($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listaIndicAgreg='';  
                        }else{
                          $scope.listaIndicAgreg=data;                     
                        }                        
                      });
                      busquedas.listadoMedAgregSymio($rootScope.folio).success(function(data){                                                               
                        if(data==''){
                          $scope.listaMedicamentosSymio='';
                          $scope.siguienteMed=true;
                        }else{
                          $scope.listaMedicamentosSymio=data;                     
                          $scope.siguienteMed=false;
                        }                        
                      });
                      busquedas.listadoOrtAgregSymio($rootScope.folio).success(function(data){                                             
                        if(data==''){
                          $scope.listaOrtesisSymio='';
                          $scope.siguienteOrt=true;  
                        }else{
                          $scope.listaOrtesisSymio=data;                     
                          $scope.siguienteOrt=false;
                        }                        
                      });

                       $http.get('api/catalogos.php?funcion=pronostico&fol='+$rootScope.folio).success(function (data){                                                 
                          $scope.pronostico.pronostico=data.ObsNot_pron;                          
                        });  
                        busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      });                   
                      $scope.cargador=false; 
                       $scope.regresar=true;                                    
                  break;

                   case 5:

                    busquedas.listaLesion().success(function(data){                      
                      $scope.listLesion=data;                                 
                    }); 
                     busquedas.listaLesiones($rootScope.folio).success(function(data){                      
                      if(data==''){
                        $scope.listLesiones='';  
                      }else{
                        $scope.listLesiones=data;                                   
                      }
                      $scope.cargador=false;
                    });
                    $http.get('api/catalogos.php?funcion=lesionAdmin&fol='+$rootScope.folio).success(function (data){                                                                                    
                        $scope.lesionAdmin.tipoLesion=data.ObsNot_tipoLesion;                        
                         switch($scope.lesionAdmin.tipoLesion){
                          case '1':
                              
                              $scope.verUnica=  true;
                              $scope.verMultiple= false;
                              $scope.verLeve=   false;
                              $scope.verSimple=   false;
                              $scope.listadoOtros='';
                              $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=3').success(function (data){                                     
                                 
                                  $scope.listadoUnica=data;
                                  $scope.listadoMultiple='';
                                  $scope.verMultipleSecundaria=false;
                                  $scope.verMensajePolicontundidos=false;
                              }); 
                              $http.get('api/catalogos.php?funcion=LesionSelect&fol='+$rootScope.folio).success(function (data){
                                  console.log(data);  
                                  $scope.lesionAdmin.lesionUnica=data.Clave_lesionMV;
                              });
                          break;
                          case '2':                              
                              $scope.verUnica=  false;
                              $scope.verMultiple= true;
                              $scope.verLeve=   false;
                              $scope.verSimple=   false;
                              $scope.listadoOtros='';
                              $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=4').success(function (data){                                  
                                  $scope.verMultipleSecundaria=false;                
                                  $scope.lesionAdmin.lesionUnica ='';             
                                  $scope.listadoUnica='';
                                  $scope.listadoMultiple=data;
                                  $scope.listadoMultipleSecundaria=data;
                                  $http.get('api/catalogos.php?funcion=LesionSelect&fol='+$rootScope.folio).success(function (data){
                                    console.log(data);  
                                    $scope.lesionAdmin.lesionMultiple=data.Clave_lesionMV;
                                  }); 
                              }); 
                          break;
                          case '3':         
                            $scope.verUnica=  false;
                              $scope.verMultiple= false;
                              $scope.verLeve=   true;
                              $scope.verSimple=   false;
                              $scope.listadoOtros='';
                          break;
                          case '4':         
                            $scope.verUnica=  false;
                              $scope.verMultiple= false;
                              $scope.verLeve=   false;
                              $scope.verSimple=   true;
                              $scope.listadoOtros='';
                          break;
                          case '5':            
                              $scope.verUnica=  false;
                              $scope.verMultiple= false; 
                              $scope.verLeve=   false;
                              $scope.verSimple=   false;
                              $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=7').success(function (data){                                
                                  $scope.lesionAdmin.lesionUnica ='';             
                                  $scope.listadoUnica='';
                                  $scope.listadoMultiple='';
                                  $scope.listadoMultipleSecundaria='';
                                  $scope.listadoOtros=data;
                              }); 
                          break;

                              }
                    });
                                                          
                  break;
                  case "6":
                      busquedas.listaRX().success(function(data){                      
                        $scope.listRX=data;                                         
                      });
                      busquedas.listaEstSol($rootScope.folio).success(function(data){
                        if(data!=''){                      
                          $scope.listEstSoli=data; 
                          $scope.sinEstudios=false; 
                          $scope.estudioAgreg=false;                
                        }else{
                          $scope.sinEstudios=true;
                          $scope.estudioAgreg=true; 
                        }
                         $scope.cargador=false;
                      }); 
                       $scope.regresar=true;
                  break; 
                }
        }

         $scope.finished = function() {
            alert("Wizard finished :)");
        }

        $scope.logStep = function() {
            //console.log("Step continued");
        }

        $scope.goBack = function() {
            WizardHandler.wizard().goTo(0);
        }
        
        $scope.selectPosicion = function(){          
            $scope.cargador1=true;
            $scope.cargador2=true;
            $http({
            url:'api/api.php?funcion=selectPosicion&opcion='+$scope.accidente.vehiculo,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {1:'valor'}
            }).success( function (data){                              
              $scope.posicion=data;
              $rootScope.arregloOpc=verOpciones($scope.accidente.vehiculo);                    
              $scope.cargador1=false;
              $scope.cargador2=false;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });    
        }
        $scope.guardaDatAcc = function(){ 
          if($scope.formularios.dAccidente.$valid){  
            $scope.cargador3=true;
            for (key in $scope.accidente.mecanismo) {           
                if (!/^([1-9])*$/.test($scope.accidente.mecanismo[key])){}
                  else{       
                  if ($scope.accidente.stringMec=='') {
                    $scope.accidente.stringMec=$scope.accidente.mecanismo[key];
                  }else{
                    $scope.accidente.stringMec=$scope.accidente.stringMec+','+$scope.accidente.mecanismo[key];
                  }    
                }            
            }
            for (key in $scope.accidente.seguridad) { 
              if (!/^([1-9])*$/.test($scope.accidente.seguridad[key])){}
              else{               
                if ($scope.accidente.stringSeg=='') {
                  $scope.accidente.stringSeg=$scope.accidente.seguridad[key];
                }else{
                  $scope.accidente.stringSeg=$scope.accidente.stringSeg+','+$scope.accidente.seguridad[key];
                }
              }
            }                     
            
            $http({
              url:'api/api.php?funcion=guardaDatAcc&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: $scope.accidente
              }).success( function (data){                              
                if(data.respuesta=='correcto'){                  
                  $scope.sexo=data.sexo;
                  $scope.cargador3=false;
                  $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=3').success(function (data){          
                      WizardHandler.wizard().next(); 
                       busquedas.listaRX().success(function(data){                      
                        $scope.listRX=data;                                         
                      });
                      busquedas.listaEstSol($rootScope.folio).success(function(data){
                        if(data!=''){                      
                          $scope.listEstSoli=data; 
                          $scope.sinEstudios=false; 
                          $scope.estudioAgreg=false;                
                        }else{
                          $scope.sinEstudios=true;
                          $scope.estudioAgreg=true; 
                        }
                         $scope.cargador=false;
                      }); 
                       $scope.regresar=true;
                       busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      }); 
                       $scope.cargador2=true;
                      busquedas.listaDiagnosticos().success(function(data){                      
                        $scope.cargador2=false;
                        $scope.listaDiagnostico=data;              
                      }); 
                      $scope.cargador=false;                    
                       $scope.regresar=true;  
                       window.scrollTo(0,0);          
                      });               
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
        $scope.guardaEmbarazo = function(){          
        if($scope.embarazo.semanas=='' || $scope.embarazo.semanas<0 || $scope.embarazo.semanas>45|| $scope.embarazo.semanas==undefined){ 
          $scope.verValidacion=true;
        }else{       
          $scope.cargador=true;     
          $scope.verValidacion=false;    
          $http({
            url:'api/api.php?funcion=guardaEmbarazo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.embarazo
            }).success( function (data){                              
              if(!data.respuesta){                
                  $scope.listEmbarazo=data;
                  $scope.cargador=false;                 
                
              }
              else if(data.respuesta=='lleno'){
                $scope.mensaje=true;
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
        }
      
        $scope.lesionSig = function(){
           $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=4').success(function (data){                                
            });
          busquedas.listaLesion().success(function(data){                      
            $scope.listLesion=data;                                 
          }); 
           busquedas.listaLesiones($rootScope.folio).success(function(data){                      
            $scope.listLesiones=data;  
            $scope.cargador=false;                                 
          }); 

          WizardHandler.wizard().next(); 
        }

        $scope.validaLista = function(){
          if($scope.lesion.lesion==''){
            $scope.mensajeLesion=true;
          }else{
            $scope.mensajeLesion=false;
          }
          
        }
         $scope.guardaLesion = function(cuerpo){          
          $scope.lesion.cuerpo=cuerpo;
          $scope.cargador=true;
          if($scope.lesion.lesion==''|| $scope.lesion.lesion==null){
            $scope.mensajeLesion=true;
             $scope.cargador=false; 
          }else{
            $http({
            url:'api/api.php?funcion=guardaLesion&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.lesion
            }).success( function (data){                        
              if(!data.respuesta){
                $scope.lesion.lesion='';                                  
                if(data==''){
                  $scope.listLesiones='';  
                }else{
                  $scope.listLesiones=data;                                   
                }              
                $scope.cargador=false;                                         
              }              
              else{               
                alert('error en la inserción');
                $scope.cargador.false;   
              }            
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador.false;   
            });           
          }         
        }

         $scope.eliminarLes = function(claveLesion){ 
            $scope.cargador=true;                   
            $http({
            url:'api/api.php?funcion=eliminaLesion&fol='+$rootScope.folio+'&cveLes='+claveLesion,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){
                $scope.lesion.lesion='';                                    
                if(data==''){
                  $scope.listLesiones='';  
                }else{
                  $scope.listLesiones=data;                                   
                }                 
                $scope.cargador=false;                                        
              }              
              else{                
                alert('error en la inserción');
                $scope.cargador.false;   
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador.false;   
            });                               
        }
        $scope.siguienteEdoGral= function(){
          WizardHandler.wizard().next(); 
          $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=5').success(function (data){                                
          });            
        }
        $scope.guardaEdoGral= function(){           
          if($scope.formularios.estadoGral.$valid){         
            $scope.cargador=true;
            $http({
              url:'api/api.php?funcion=guardaEdoGral&fol='+$rootScope.folio,
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: $scope.edoGral
              }).success( function (data){                        
                $scope.cargador=false;
                if(data.respuesta=='correcto'){ 
                  busquedas.listaRX().success(function(data){                      
                    $scope.listRX=data;                                         
                  });
                  busquedas.listaEstSol($rootScope.folio).success(function(data){
                    if(data!=''){                      
                      $scope.listEstSoli=data; 
                      $scope.sinEstudios=false;                
                    }else{
                      $scope.sinEstudios=true;
                    }
                  }); 
                   $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=4').success(function (data){                              
                    });                                 
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
        $scope.guardaEstudios= function(){ 
          if($scope.formularios.estudio.$valid){           
          $scope.cargador=true;        
          $http({
            url:'api/api.php?funcion=guardaEstudios&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.estudios
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.estudios={
                  rx:'',
                  obs:'',
                  interp:''
                }                
                  $scope.listEstSoli=data;                                                                                                             
                  $scope.cargador=false;
                  $scope.formularios.estudio.$submitted=false;
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

        $scope.guardaOtrosEstudios= function(){ 
          if($scope.formularios.otrosEstudios.$valid){           
          $scope.cargador=true;           
          $http({
            url:'api/api.php?funcion=guardaOtrosEstudios&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.otrosEst
            }).success( function (data){ 
              
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

         $scope.eliminarEstRealizado = function(claveEst){ 
            $scope.cargador=true;                   
            $http({
            url:'api/api.php?funcion=eliminaEstRealizado&fol='+$rootScope.folio+'&cveEst='+claveEst,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){
                  if(data==''){                                
                    $scope.listEstSoli='';
                    $scope.estudioAgreg=true; 
                    $scope.sinEstudios=true;      
                  }else{
                    $scope.sinEstudios=false;
                    $scope.listEstSoli=data;
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
         $scope.eliminarOtrosEstudios = function(claveEst){ 
            $scope.cargador=true;             
            $http({
            url:'api/api.php?funcion=eliminaOtrosEstRealizado&fol='+$rootScope.folio+'&cveEst='+claveEst,
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
        $scope.proMedSig = function(){
            $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=7').success(function (data){                                
            });
            busquedas.listaProced().success(function(data){                      
              $scope.listaProced=data;             
            });  
            busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
              if(data!=''){
                $scope.listaProcedimientos=data;             
              }
            });       
            WizardHandler.wizard().next();  
        }
        $scope.SumSymioSig = function(){
            $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=10').success(function (data){                                
            });

            /******************** prueba de tiempo de ejecusión  *********************************/
                 $scope.timeout = 3; //tiempo de espera de la consulta                                               
                  $scope.status = 'Requesting';
                  $scope.response = '';                            
                  var httpRequest = httpRequestHandler();                                                                                                                                                  
            /********************* fin prueba de tiempo de ejecusión   **************************/
            
            /******************** prueba de tiempo de ejecusión  *********************************/
                  $scope.timeoutOrt = 3; //tiempo de espera de la consulta                                               
                  $scope.status = 'Requesting';
                  $scope.response = '';                            
                  var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   
            /********************* fin prueba de tiempo de ejecusión   **************************/  

            /*
            busquedas.listaMedSymio($rootScope.uniClave).success(function(data){                      
                    $scope.lisMedSymio=data;
                    $scope.cargadorMed=false;                                         
                  });
            busquedas.listaOrtSymio($rootScope.uniClave).success(function(data){                      
              $scope.lisrtOrtSymio=data;     
                    $scope.cargadorOrt=false;                                
            });
            */ 
            busquedas.listaIndicaciones().success(function(data){                      
              $scope.listaIndicacion=data;                     
            });
            busquedas.listaIndicAgreg($rootScope.folio).success(function(data){                      
              if(data==''){
                $scope.listaIndicAgreg='';  
              }else{
                $scope.listaIndicAgreg=data;                     
              }
            });  
             busquedas.listadoMedAgregSymio($rootScope.folio).success(function(data){                                           
                        if(data==''){
                          $scope.listaMedicamentosSymio='';  
                        }else{
                          $scope.listaMedicamentosSymio=data;                     
                        }                        
                      });
                      busquedas.listadoOrtAgregSymio($rootScope.folio).success(function(data){                                             
                        if(data==''){
                          $scope.listaOrtesisSymio='';  
                        }else{
                          $scope.listaOrtesisSymio=data;                     
                        }                        
                      }); 

            WizardHandler.wizard().next();  
        }
        $scope.guardaProcMedicos= function(){ 
        if($scope.formularios.proce.$valid){         
          $scope.cargador1=true;
          $http({
            url:'api/api.php?funcion=guardaProcedimientos&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.procedimientos
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.procedimientos={
                  procedimiento:'',
                  obs:''
                }                
                $scope.listaProcedimientos=data;                                   
                $scope.formularios.proce.$submitted=false; 
                $scope.cargador1=false;                                                       
              }              
              else{                
                alert('error en la inserción');
                $scope.cargador1=false; 
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador1=false; 
            }); 
          }                     
        }
        $scope.eliminarProcedimiento = function(clavePro){                    
            $scope.cargador1=true;
            $http({
            url:'api/api.php?funcion=eliminaProcedimiento&proClave='+clavePro+'&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){ 
                  if(data==''){
                    $scope.listaProcedimientos='';  
                  }else{                              
                    $scope.listaProcedimientos=data;                                  
                  }
                  $scope.cargador1=false; 
              }              
              else{                
                alert('error en la inserción');
                $scope.cargador1=false; 
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador1=false; 
            });                               
        }
        $scope.diagnosticoSig = function(){
        	$scope.cargador2=true;
            busquedas.listaDiagnosticos().success(function(data){                      
            	$scope.cargador2=false;
              $scope.listaDiagnostico=data;              
            });  
            $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=8').success(function (data){                                
            });
            WizardHandler.wizard().next();  
        }
        $scope.despliegaDiagnosticos = function(diagnostic){
            /*busquedas.despDiagnosticos(diagnostic).success(function(data){                      
              $scope.listaDiagnostics=data;             
            });*/
            $scope.cargador2=true;
            $http({
            url:'api/api.php?funcion=getListDiag&diag='+diagnostic,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                                      
              $scope.listaDiagnostics=data;      
              $scope.cargador2=false;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.cargador2=false; 
            });              

        }
        $scope.agregaDiagnostico = function(diag){            
            if($scope.diagnostico.diagnostico==''||$scope.diagnostico.diagnostico==undefined){
              $scope.diagnostico.diagnostico=diag;
            }else{
              $scope.diagnostico.diagnostico=$scope.diagnostico.diagnostico+' // '+diag;
            }
            $scope.diagnostico.diagnostico = $scope.diagnostico.diagnostico 
        }
         $scope.guardaDiagnostico = function(diag){
         
         if($scope.formularios.diagnos.$valid){ 
          $scope.validaPalabra= validaPalabrasProhibidas($scope.diagnostico.diagnostico);          
          if($scope.validaPalabra==0){
          $scope.msjPalabraProhi=false;
          $scope.cargador2=true;           
            $http({
            url:'api/api.php?funcion=guardaDiagnostico&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.diagnostico
            }).success( function (data){                        
              if(data.respuesta=='correcto'){                
                  $scope.cargador2=false; 
                  $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=4').success(function (data){                                
            });

            /******************** prueba de tiempo de ejecusión  *********************************/
                 $scope.timeout = 3; //tiempo de espera de la consulta                                               
                  $scope.status = 'Requesting';
                  $scope.response = '';                            
                  var httpRequest = httpRequestHandler();                                                                                                                                                  
            /********************* fin prueba de tiempo de ejecusión   **************************/
            
            /******************** prueba de tiempo de ejecusión  *********************************/
                  $scope.timeoutOrt = 3; //tiempo de espera de la consulta                                               
                  $scope.status = 'Requesting';
                  $scope.response = '';                            
                  var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   
            /********************* fin prueba de tiempo de ejecusión   **************************/  

            /*
            busquedas.listaMedSymio($rootScope.uniClave).success(function(data){                      
                    $scope.lisMedSymio=data;
                    $scope.cargadorMed=false;                                         
                  });
            busquedas.listaOrtSymio($rootScope.uniClave).success(function(data){                      
              $scope.lisrtOrtSymio=data;     
                    $scope.cargadorOrt=false;                                
            });
            */ 
            busquedas.listaIndicaciones().success(function(data){                      
              $scope.listaIndicacion=data;                     
            });
            busquedas.listaIndicAgreg($rootScope.folio).success(function(data){                      
              if(data==''){
                $scope.listaIndicAgreg='';                 
              }else{
                $scope.listaIndicAgreg=data;                     
              }
            });  
             busquedas.listadoMedAgregSymio($rootScope.folio).success(function(data){                                           
                        if(data==''){
                          $scope.listaMedicamentosSymio='';  
                          $scope.siguienteMed=true;
                        }else{
                          $scope.listaMedicamentosSymio=data;                     
                          $scope.siguienteMed=false;
                        }                        
                      });
                      busquedas.listadoOrtAgregSymio($rootScope.folio).success(function(data){                                             
                        if(data==''){
                          $scope.listaOrtesisSymio=''; 
                          $scope.siguienteOrt=true; 
                        }else{
                          $scope.listaOrtesisSymio=data;                     
                          $scope.siguienteOrt=false; 
                        }                        
                      }); 
                       busquedas.listaProced().success(function(data){                      
                        $scope.listaProced=data;             
                      });  
                      busquedas.listaProcedimientos($rootScope.folio).success(function(data){                      
                        if(data!=''){
                          $scope.listaProcedimientos=data;                                    
                        }
                        $scope.cargador=false;
                      });                   
           
                  /*busquedas.listaOtrosEst().success(function(data){                      
                        $scope.lisOtrosEst=data;                     
                      });                     
                      busquedas.listaOtrosEstudios($rootScope.folio).success(function(data){                      
                        $scope.cargador2=false;                        
                        if(data==''){
                          $scope.listOtrosEstSoli='';   
                        }else{
                         $scope.listOtrosEstSoli=data;              
                        }
                      }); */
                  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
                          $scope.alergias = data;
                      });                  
                       
                  WizardHandler.wizard().next();                                                               
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
            busquedas.verPosologia($scope.medica.medica).success(function(data){                      
                    $scope.medica.posologia=data.Sum_indicacion;                                         
                  }); 

            $http({
            url:'api/api.php?funcion=vePosologia&cveMed='+$scope.medica.medica,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.medica
            }).success( function (data){                        
              $scope.medica.posologia=data.Sum_indicacion;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });            
        }  
        $scope.verIndicacionCam = function(){            
            if($scope.indicacion.obs=='' || $scope.indicacion.obs==null){
              $scope.indicacion.obs=$scope.indicacion.indicacion;
            }else{
              $scope.indicacion.obs=$scope.indicacion.obs+', '+$scope.indicacion.indicacion;
            }
        }  
        $scope.agregarPronostico = function(valor){ 
              
          $scope.opcionPron='';
            switch(valor){
                case '1':
                  $scope.opcionPron='Bueno para la vida y bueno para la función';
                break;
                 case '2':
                  $scope.opcionPron='Bueno para la vida y malo para la función';
                break;
                 case '3':
                  $scope.opcionPron='Malo para la vida y bueno para la función';
                break;
                 case '4':
                  $scope.opcionPron='Malo para la vida y malo para la función';
                break;
                 case '5':
                  $scope.opcionPron='Reservado a evolución';
                break;
            }
            if($scope.pronostico.pronostico==''){
                $scope.pronostico.pronostico=$scope.opcionPron;
            }else{
                $scope.pronostico.pronostico=$scope.pronostico.pronostico+', '+$scope.opcionPron;
            }            
        }  
/*************************           suministro symio            *******************************************/
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
                url:'api/api.php?funcion=guardaMedicamentoSymio&fol='+$rootScope.folio+'&uni='+$scope.uniClave,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.med
                }).success( function (data){                   
                  if(!data.respuesta){ 
                    $scope.med.stock=$scope.med.stock-$scope.med.cantidad;
                    for(lista in $scope.lisMedSymio){
                      if($scope.med.sustAct==$scope.lisMedSymio[lista].Clave_producto){
                        $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;
                        $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;
                        $scope.lisMedSymio[lista].Stock=$scope.med.stock;
                      }            
                    }
                     $scope.med={
                        sustAct:'',
                        medicame:'',
                        presentacion:'',
                        cantidad:1,
                        posologia:''          
                      }                                                              
                      $scope.listaMedicamentosSymio=data;
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


        $scope.eliminarMedicamentoSymio = function(clavePro,claveMed,cantidadMed){ 
            $scope.cargador=true;              
            $http({
            url:'api/api.php?funcion=eliminarMedicamentoSymio&fol='+$rootScope.folio+'&proClave='+clavePro+'&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                             
              if(!data.respuesta){                  
                    for(lista in $scope.lisMedSymio){
                      if(claveMed==$scope.lisMedSymio[lista].Clave_producto){                                                
                        $scope.lisMedSymio[lista].Stock= parseInt($scope.lisMedSymio[lista].Stock)+parseInt(cantidadMed);
                      }            
                    }   
              	  	if(data==''){
              	  		$scope.listaMedicamentosSymio=''; 
                      $scope.siguienteMed=true;                 	
              	  	}else{                                                           
                  		$scope.listaMedicamentosSymio=data;                  
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
              $http({
                url:'api/api.php?funcion=guardaOrtSymio&fol='+$rootScope.folio+'&uni='+$scope.uniClave,
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
                      $scope.listaOrtesisSymio=data;
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
            url:'api/api.php?funcion=eliminarOrtesisSymio&fol='+$rootScope.folio+'&proClave='+clavePro+'&uni='+$rootScope.uniClave,
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
                      $scope.listaOrtesisSymio='';                      
                      $scope.siguienteOrt=true;                   
                    }else{                                                           
                      $scope.listaOrtesisSymio=data;                  
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


/*************************          fin  suministro symio         *******************************************/

        $scope.guardaMedicamento= function(){
          if($scope.formularios.medic.$valid){
          $scope.cargador=true;          
          $http({
            url:'api/api.php?funcion=guardaMedicamento&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.medica
            }).success( function (data){                        
              if(!data.respuesta){ 
                 $scope.medica={
                    medica:'',
                    posologia:'',
                    cantidad:1
                  }                                                 
                  $scope.listaMedicamentosAgreg=data;
                  $scope.formularios.medic.$submitted=false; 
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
        $scope.eliminarMedicamento = function(clavePro){ 
            $scope.cargador=true;                   
            $http({
            url:'api/api.php?funcion=eliminaMedicamento&fol='+$rootScope.folio+'&proClave='+clavePro,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){
                if(data==''){
                    $scope.listaMedicamentosAgreg='';  
                }else{                                                  
                  $scope.listaMedicamentosAgreg=data;
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
        $scope.guardaOrtesis= function(){ 
          if($scope.formularios.orte.$valid){
          $scope.cargador1=true;         
          $http({
            url:'api/api.php?funcion=guardaOrtesis&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.ortesis
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.ortesis={
                  ortesis:'',
                  presentacion:'',
                  cantidad:1,
                  indicaciones:''
                }                             
                $scope.listaOrtesisAgreg=data;                                                
                $scope.formularios.orte.$submitted=false; 
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
        $scope.eliminarOrtesis = function(clavePro){                    
            $scope.cargador1=true;
            $http({
            url:'api/api.php?funcion=eliminarOrtesis&fol='+$rootScope.folio+'&proClave='+clavePro,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){                              
                if(data==''){
              		$scope.listaOrtesisAgreg='';	
              	}else{
                	$scope.listaOrtesisAgreg=data;                     
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
        $scope.guardaIndicaciones= function(){
        if($scope.formularios.indica.$valid){ 
          $scope.validaPalabraInd= validaPalabrasProhibidasInd($scope.indicacion.indicacion);          
          if($scope.validaPalabraInd==0){
          $scope.msjPalabraProhiInd=false;     
        	$scope.cargador2=true;
          $http({
            url:'api/api.php?funcion=guardaIndicacion&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.indicacion
            }).success( function (data){                        
              if(!data.respuesta){ 
                $scope.indicacion={
                  indicacion:'',
                  obs:''
                }                           
                $scope.listaIndicAgreg=data;
                $scope.formularios.indica.$submitted=false;                                    
                $scope.cargador2=false;                                                        
              }              
              else{                
                alert('error en la inserción');
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
            } else{
              $scope.msjPalabraProhiInd=true;
            }                     
        }
        } 
        $scope.eliminarIndicacion = function(clavePro){ 
        	$scope.cargador2=true;                   
            $http({
            url:'api/api.php?funcion=eliminarIndicacion&fol='+$rootScope.folio+'&proClave='+clavePro,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
              if(!data.respuesta){
          		if(data==''){                               
                  	$scope.listaIndicAgreg='';                                   
              	}else{
              		$scope.listaIndicAgreg=data; 
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
        $scope.pronosSiguiente = function(){ 
            $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=5').success(function (data){                                
            });                           
            WizardHandler.wizard().next();  
        }  
        $scope.pronosSiguiente = function(){ 
            $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=11').success(function (data){                                
            });                           
            WizardHandler.wizard().next();  
        }        
        $scope.guardaPronostico= function(){        
        if($scope.formularios.prono.$valid){
        $scope.cargador5=true;
          $http({
            url:'api/api.php?funcion=guardaPronostico&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.pronostico
            }).success( function (data){                        
              if(data.respuesta=='correcto'){ 
            		$scope.cargador5=false; 
                       $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=5').success(function (data){                                
            });                  
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
        $scope.imprimirNota = function(){           
        	$scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoNota.php?fol='+$rootScope.folio+'&vit='+$rootScope.vitSelect;
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
        $scope.irDocumentos = function(){         
              $location.path("/documentos");          
        }
        $scope.imprimirReceta = function(){        	     
            var fileName = 'Receta - '+$rootScope.folio;
            var uri = 'api/classes/formatoRecetaNuevo.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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
        $scope.imprimirConcentimiento = function(){              
            var fileName = 'Receta - '+$rootScope.folio;
            var uri = 'api/classes/consentimientoN.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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
        $scope.botonHabilita1 = function(){                    
          if (document.getElementById('checkMed').checked)
          {
            $scope.siguienteMed=false;
          }else{
            $scope.siguienteOrt=true;
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

        $scope.pruebaFuncion = function(){
             busquedas.listaMedSymio($rootScope.uniClave).success(function(data){                      
                $scope.lisMedSymio=data; 
                $scope.cargadorMed=false;                                        
              });

             console.log($scope.lisMedSymio);
         
        }
        /***************       funcion para el tiempo de espera de medicamentos  ***********************/
        function httpRequestHandler () {
                            $scope.cargadorMed=true;
                            $scope.recargarMed=true;
                            var timeout = $q.defer(),
                                result = $q.defer(),
                                timedOut = false,
                                httpRequest;
                            
                            setTimeout(function () {
                                timedOut = true;
                                timeout.resolve();
                            }, (1000 * $scope.timeout));
                            
                            httpRequest = $http({
                                method : 'post',
                                url: 'api/api.php?funcion=listMedicSymio&uni='+$rootScope.uniClave,                               
                                cache: false,
                                timeout: timeout.promise
                            });
                            
                            httpRequest.success(function(data, status, headers, config) {
                                result.resolve(data); 
                                console.log(data);                                                   
                                if(data==''||data==null){ 
                                  if($scope.segundaRecarga==2){
                                    $('#myModal').modal();                                 
                                  }
                                  $scope.recargarMed=false;
                                  $scope.cargadorMed=true;                                       
                                                                           
                                }else{
                                $scope.lisMedSymio=data; 
                                $scope.cargadorMed=false;   
                                }
                            });

                      httpRequest.error(function(data, status, headers, config) { 
                                if($scope.segundaRecarga==2){

                                  if(data==''||data==null){
                                    $scope.recargarMed=false;                                      
                                    $('#myModal').modal();      
                                  }else{
                                  $scope.lisMedSymio=data; 
                                  $scope.cargadorMed=false;   
                                  }

                                }else{                             
                                  if(data==''||data==null){
                                    $scope.recargarMed=false;
                                  }else{
                                  $scope.lisMedSymio=data; 
                                  $scope.cargadorMed=false;   
                                  }
                                }                              
                            });
                            
                            return result.promise;
        }
        /******************************* fin de tiempo de espera para medicamentos  ******************************/

        /***************       funcion para el tiempo de espera para ortesis  ***********************/
        function httpRequestHandlerOrt () {
                            $scope.cargadorOrt=true;
                            $scope.recargarOrt=true;
                            var timeout = $q.defer(),
                                result = $q.defer(),
                                timedOut = false,
                                httpRequest;
                            
                            setTimeout(function () {
                                timedOut = true;
                                timeout.resolve();
                            }, (1000 * $scope.timeoutOrt));
                            
                            httpRequest = $http({
                                method : 'post',
                                url: 'api/api.php?funcion=listOrtesisSymio&uni='+$rootScope.uniClave,                               
                                cache: false,
                                timeout: timeout.promise
                            });
                            
                            httpRequest.success(function(data, status, headers, config) {

                              result.resolve(data);                                                    
                                if(data==''||data==null){ 
                                  if($scope.segundaRecargaOrt==2){
                                    $('#myModalOrt').modal();                                 
                                  }
                                  $scope.recargarOrt=false;
                                  $scope.cargadorOrt=true;                                       
                                                                           
                                }else{
                                $scope.lisrtOrtSymio=data; 
                                $scope.cargadorOrt=false;   
                                }
                            });

                      httpRequest.error(function(data, status, headers, config) { 
                                if($scope.segundaRecargaOrt==2){

                                  if(data==null){
                                    $scope.recargarOrt=false;                                      
                                    $('#myModalOrt').modal();      
                                  }else{
                                  $scope.lisrtOrtSymio=data; 
                                  $scope.cargadorOrt=false;   
                                  }

                                }else{                             
                                  if(data==null){
                                    $scope.recargarOrt=false;
                                  }else{
                                  $scope.lisMedSymio=data; 
                                  $scope.cargadorOrt=false;   
                                  }
                                }                              
                            });
                            
                            return result.promise;
        }
        /******************************* fin de tiempo de espera para ortesis  ******************************/

        

        $scope.botonRecargaMed = function(){
          $scope.recargarMed=true;
          $scope.cargadorMed=true;     
          console.log($scope.segundaRecarga);
          $http({
            url:'api/api1.php?funcion=recargaMedSymio&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.indicacion
            }).success( function (data){
               $http.get('api/api1.php?funcion=incidenciaInventario&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin).success(function (data){                                
                console.log(data);
                });
              if(data==''){
                if($scope.segundaRecarga==2){
                  $scope.timeout = .5; //tiempo de espera de la consulta                       
                              
                  $scope.status = 'Requesting';
                  $scope.response = '';
                  
                  var httpRequest = httpRequestHandler();
                }
                  $scope.segundaRecarga=2;
                  $scope.recargarMed=false;
                  $scope.cargadorMed=true;                  
              }else{
                console.log(data);
                  $scope.lisMedSymio=data; 
                  $scope.cargadorMed=false;   
              }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                                           
        }

        $scope.botonRecargaOrt = function(){
          $scope.recargarOrt=true;
          $scope.cargadorOrt=true;               
          $http({
            url:'api/api.php?funcion=listOrtesisSymio&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.indicacion
            }).success( function (data){              
              if(data==''){
                if($scope.segundaRecargaOrt==2){
                  $scope.timeout = .5; //tiempo de espera de la consulta                       
                              
                  $scope.status = 'Requesting';
                  $scope.response = '';
                  
                  var httpRequest = httpRequestHandlerOrt();
                }
                  $scope.segundaRecargaOrt=2;
                  $scope.recargarOrt=false;
                  $scope.cargadorOrt=true;                  
              }else{
                  $scope.lisrtOrtSymio=data; 
                  $scope.cargadorOrt=false;   
              }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                                           
        }

        /**********************   funcion para agregar medicamentos alternativos a un array  *****************************/
        var cont=1;
        $scope.agregaMedAlternativo = function(){                     

            $scope.cont ={};            
            $scope.cont.medicamento=$scope.medAlter.medicamento;
            $scope.cont.cantidad=$scope.medAlter.cantidad;
            $scope.cont.indicaciones=$scope.medAlter.indicaciones; 
            $scope.cont.cont=cont;            
            $scope.sumaMed.push($scope.cont); 
            $scope.medAlter={
              medicamento:'',
              cantidad:1,
              indicaciones:''          
            }                  
            cont++; 
            console.log($scope.sumaMed);              
        }
        var contOrt=1;
        $scope.agregaOrtAlternativo = function(){                     

            $scope.cont ={};            
            $scope.cont.ortesis=$scope.ortAlter.ortesis;
            $scope.cont.cantidad=$scope.ortAlter.cantidad;
            $scope.cont.indicaciones=$scope.ortAlter.indicaciones; 
            $scope.cont.cont=contOrt;            
            $scope.sumaOrt.push($scope.cont); 
            $scope.ortAlter={
              ortesis:'',
              cantidad:1,
              indicaciones:''          
            }                  
            contOrt++;             
        }

        $scope.guardarMedicamentosAlternativos = function(){ 
          if($scope.sumaMed==''){
              $scope.mensajevacio=true;
          }else{ 
            $scope.cargadorModalMed=true;
           $http({
            url:'api/api1.php?funcion=guardaMedicamentosAlternativos&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.sumaMed
            }).success( function (data){
            $scope.cargadorModalMed=false;              
              if(data!='error'){                
                $scope.listaMedicamentosSymioAlternativo=data;
                $('#myModal').modal('hide');
                $scope.verListaSumAlter=false;
                $scope.recargarMed=true;
                $scope.siguienteMed=false;
              }else{
                console.log('No se pudieron guardar los medicamentos');
              }

            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
          }              
        }

        $scope.eliminaTelefono = function(contaTel){  
            console.log('entró');
            contaTel=contaTel-1;
            delete $scope.sumaTel[contaTel]; 
            $scope.sumaTel.splice(contaTel,1);               
            console.log($scope.sumaTel);
        }

        $scope.checaLesion = function(){                                  
        switch($scope.lesionAdmin.tipoLesion){
        case '1':
            
            $scope.verUnica=  true;
            $scope.verMultiple= false;
            $scope.verLeve=   false;
            $scope.verSimple=   false;
            $scope.listadoOtros='';
            $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=3').success(function (data){                                     
               
                $scope.listadoUnica=data;
                $scope.listadoMultiple='';
                $scope.verMultipleSecundaria=false;
                $scope.verMensajePolicontundidos=false;
            }); 
        break;
        case '2':
            
            $scope.verUnica=  false;
            $scope.verMultiple= true;
            $scope.verLeve=   false;
            $scope.verSimple=   false;
            $scope.listadoOtros='';
            $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=4').success(function (data){
                
                $scope.verMultipleSecundaria=false;                
                $scope.lesionAdmin.lesionUnica ='';             
                $scope.listadoUnica='';
                $scope.listadoMultiple=data;
                $scope.listadoMultipleSecundaria=data;
            }); 
        break;
        case '3':         
          $scope.verUnica=  false;
            $scope.verMultiple= false;
            $scope.verLeve=   true;
            $scope.verSimple=   false;
            $scope.listadoOtros='';
        break;
        case '4':         
          $scope.verUnica=  false;
            $scope.verMultiple= false;
            $scope.verLeve=   false;
            $scope.verSimple=   true;
            $scope.listadoOtros='';
        break;
        case '5':            
            $scope.verUnica=  false;
            $scope.verMultiple= false;
            $scope.verLeve=   false;
            $scope.verSimple=   false;
            $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=7').success(function (data){                                
                $scope.lesionAdmin.lesionUnica ='';             
                $scope.listadoUnica='';
                $scope.listadoMultiple='';
                $scope.listadoMultipleSecundaria='';
                $scope.listadoOtros=data;
            }); 
        break;

            }
        }

        $scope.guardarOrtesisAlternativos = function(){ 
          if($scope.sumaOrt==''){
              $scope.mensajevacio=true;
          }else{ 
            $scope.cargadorModalOrt=true;
           $http({
            url:'api/api1.php?funcion=guardaOrtesisAlternativos&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.sumaOrt
            }).success( function (data){
            $scope.cargadorModalOrt=false;
              console.log(data);            
              if(data!='error'){                
                $scope.listaOrtesisSymioAlternativo=data;
                $('#myModalOrt').modal('hide');
                $scope.siguienteOrt=false;
                $scope.verListaOrtAlter=false;
                $scope.recargarOrt=true;
              }else{
                console.log('No se pudieron guardar los medicamentos');
              }

            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
          }              
        }

        $scope.guardarLesionAdmin = function(){

         if($scope.formularios.lesAdmin.$valid){ 
         $scope.cargador1=true;            
           $http({
            url:'api/api.php?funcion=guardaLesionAdministrativa&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.lesionAdmin
            }).success( function (data){
                 $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=6').success(function (data){                                
                  });                           
            WizardHandler.wizard().next();  
              $scope.cargador1=false;

            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
          }                        
        }

        $scope.eliminaTelefono = function(contaTel){  
            console.log('entró');
            contaTel=contaTel-1;
            delete $scope.sumaTel[contaTel]; 
            $scope.sumaTel.splice(contaTel,1);               
            console.log($scope.sumaTel);
        }

         $scope.seleccionLesion = function(){             
            $http.get('api/api1.php?funcion=nombreLesion&claveLesion='+$scope.diagnostico.lesionUnica).success(function (data){                                              
                $scope.diagnostico.diagnostico=data.nombre+' // ';
            });             
        }
        $scope.seleccionLesionOtra = function(){             
            $http.get('api/api1.php?funcion=nombreLesion&claveLesion='+$scope.diagnostico.lesionOtro).success(function (data){                                              
                $scope.diagnostico.diagnostico=data.nombre+' // ';
            });             
        }
       $scope.agregarMultipleSecundaria = function(){                  
            var data=$scope.diagnostico.example1model;
            $scope.secundarias='';
            $scope.acumulado='';
            var respuesta='';                                 
            $http({
            url:'api/api1.php?funcion=nombresLesion',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.diagnostico.example1model
            }).success( function (data){            
              $scope.secundarias= data;              
              $scope.diagnostico.diagnostico=$scope.diagnostico.diagnostico1+$scope.secundarias;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                                 
        }

         $scope.recorreDatos = function(data){                  
           
            var datos='';
            if(data==''||data==undefined){datos= 'nada';}
            else{            

            for(i=0;i<data.length;i++){                
                $http.get('api/api1.php?funcion=nombresLesion&claveLesion='+data[i]['id']).success(function (data){                 
                    datos=datos + data.nombre;
                    alert(datos);                   
                });                 
            } 
            alert(datos);
            }                 
              //return datos;
        }


        $scope.seleccionLesionSecundaria = function(){          
            $scope.diagnostico.example1model=[];              
            if($scope.diagnostico.lesionMultiple=='M_POLI'||$scope.diagnostico.lesionMultiple=='M_POLL'||$scope.diagnostico.lesionMultiple=='M_POLM'||$scope.diagnostico.lesionMultiple=='M_POLS'){
              $scope.verMultipleSecundaria=false;
              $scope.verMensajePolicontundidos=true;
              $scope.diagnostico.diagnostico='';
          }
            else{
              $scope.verMultipleSecundaria=true;
              $scope.verMensajePolicontundidos=false;
               $http.get('api/api1.php?funcion=nombreLesion&claveLesion='+$scope.diagnostico.lesionMultiple).success(function (data){                                              
                $scope.diagnostico.diagnostico1=data.nombre+' // ';
              }); 
            }            
        }

        /*****************************************************************************************************************/


               
});

function validaPalabrasProhibidas(texto){
  var palabras = [/POLICONTUNDIDO/,/POLICONTUNDIDA/, /POLYCONTUNDIDO/, /POLI-CONTUNDIDO/, /POLI-CONTUNDIDA/, /POLY-CONTUNDIDO/, /POLY-CONTUNDIDA/, /POLI-TRAUMATIZADO/, /POLI-TRAUMATIZADA/, /POLI-TRAUMATISADO/, /POSIBLE/, /POCIBLE/, /POSIVLE/, /POCIVLE/,/POLI_CONTUNDIDO/,/POSIBLE/,POSIVLE,/PBE/];
  for (i = 0, len = palabras.length, text = ""; i < len; i++) {   
    if(palabras[i].test(texto)){      
      return palabras[i];
      break;
    }    
  }  
  return 0;  
}

function validaPalabrasProhibidasInd(texto){
  var palabras = [/incapacidad/,/incapasidad/, /INCAPACIDAD/, /INCAPASIDAD/, /Incapacidad/];
  for (i = 0, len = palabras.length, text = ""; i < len; i++) {   
    if(palabras[i].test(texto)){      
      return palabras[i];
      break;
    }    
  }  
  return 0;  
}

function verOpciones(opcion){
  switch(opcion){
    case '0':
            validos={Nod:'SI',
               Nodtx:'SI',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'SI',
               Noditx:'SI',
               Volc:'',
               Volctx:'',
               Alca:'',
               Alcatx:'',
               Late:'',
               Latetx:'',
               Fron:'',
               Frontx:'',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
     case '1':
            validos={Nod:'',
               Nodtx:'',
               Cintu:'SI',
               Cintutx:'SI',
               Bolsa:'SI',
               Bolsatx:'SI',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'',
               Noditx:'',
               Volc:'SI',
               Volctx:'SI',
               Alca:'SI',
               Alcatx:'SI',
               Late:'SI',
               Latetx:'SI',
               Fron:'SI',
               Frontx:'SI',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'SI',
               ManSubtx:'SI',
               Otr:'SI',
               Otrtx:'SI'
              }
    break; 
     case '2':
            validos={Nod:'',
               Nodtx:'',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'SI',
               Ropatx:'SI',
               Casco:'SI',
               Cascotx:'SI',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'',
               Noditx:'',
               Volc:'',
               Volctx:'',
               Alca:'SI',
               Alcatx:'SI',
               Late:'SI',
               Latetx:'SI',
               Fron:'SI',
               Frontx:'SI',
               Derr:'SI',
               Derrtx:'SI',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
     case '3':
            validos={Nod:'SI',
               Nodtx:'SI',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'',
               Noditx:'',
               Volc:'',
               Volctx:'',
               Alca:'',
               Alcatx:'',
               Late:'',
               Latetx:'',
               Fron:'',
               Frontx:'',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'SI',
               Caidtx:'SI',
               Lesdep:'SI',
               Lesdeptx:'SI',
               Lestra:'SI',
               Lestratx:'SI',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
     case '4':
            validos={Nod:'',
               Nodtx:'',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'SI',
               Cascotx:'SI',
               Rodi:'SI',
               Roditx:'SI',
               Code:'SI',
               Codetx:'SI',
               Costi:'SI',
               Costitx:'SI',
               Nodi:'',
               Noditx:'',
               Volc:'',
               Volctx:'',
               Alca:'SI',
               Alcatx:'SI',
               Late:'SI',
               Latetx:'SI',
               Fron:'SI',
               Frontx:'SI',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
     case '5':
            validos={Nod:'SI',
               Nodtx:'SI',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'',
               Noditx:'',
               Volc:'',
               Volctx:'',
               Alca:'',
               Alcatx:'',
               Late:'',
               Latetx:'',
               Fron:'',
               Frontx:'',
               Derr:'',
               Derrtx:'',
               Simp:'SI',
               Simptx:'SI',
               Imap:'SI',
               Imaptx:'SI',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
     case '6':
            validos={Nod:'SI',
               Nodtx:'SI',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'',
               Noditx:'',
               Volc:'SI',
               Volctx:'SI',
               Alca:'SI',
               Alcatx:'SI',
               Late:'SI',
               Latetx:'SI',
               Fron:'SI',
               Frontx:'SI',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'SI',
               ManSubtx:'SI',
               Otr:'SI',
               Otrtx:'SI'
              }
    break; 
     case '7':
            validos={Nod:'SI',
               Nodtx:'SI',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'SI',
               Noditx:'SI',
               Volc:'',
               Volctx:'',
               Alca:'',
               Alcatx:'',
               Late:'',
               Latetx:'',
               Fron:'',
               Frontx:'',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
     default:
            validos={Nod:'SI',
               Nodtx:'SI',
               Cintu:'',
               Cintutx:'',
               Bolsa:'',
               Bolsatx:'',
               Ropa:'',
               Ropatx:'',
               Casco:'',
               Cascotx:'',
               Rodi:'',
               Roditx:'',
               Code:'',
               Codetx:'',
               Costi:'',
               Costitx:'',
               Nodi:'SI',
               Noditx:'SI',
               Volc:'',
               Volctx:'',
               Alca:'',
               Alcatx:'',
               Late:'',
               Latetx:'',
               Fron:'',
               Frontx:'',
               Derr:'',
               Derrtx:'',
               Simp:'',
               Simptx:'',
               Imap:'',
               Imaptx:'',
               Caid:'',
               Caidtx:'',
               Lesdep:'',
               Lesdeptx:'',
               Lestra:'',
               Lestratx:'',
               ManSub:'',
               ManSubtx:'',
               Otr:'',
               Otrtx:''
              }
    break; 
  }  
    return validos;  
}