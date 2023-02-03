app.controller('historiaClinicaMovilCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http,webStorage) {

  $rootScope.folio= webStorage.session.get('folio'); 

  console.log($rootScope.folio);

  // $rootScope.folio=$cookies.folio;

  $rootScope.usrLogin= $cookies.usrLogin;
  $scope.medico='';

  $scope.formularios={};  

  $scope.msj = false;

  $scope.botonNota = false;

  $scope.cargador=false;

  $scope.cargador1=false;

  $scope.cargador2=false;

  $scope.cargador3=false;

  $scope.cargador4=false;

  $scope.cargador5=false;

  $scope.cargador6=false;

  $scope.cargador7=false;

  $scope.cargador8=false;

  $scope.cargador9=false;

    $scope.padEsp1='No';

    $scope.quiro='No';

    $scope.plant='No';

    $scope.trat='No';

    $scope.inter='No';

    $scope.dep='No';

    $scope.adic='No';    

    $scope.padObs='';

    $scope.siEmb='No';

    $scope.msjTel=false;

    $scope.motivoCon='';

    

  $scope.telefonos={

    tipo:'',

    telefono:''

  }

    

    $scope.datos={

        nombre:'',

        pat:'',

        mat:'',

        fecnac:'',

        anios:'',

        meses:'',

        tel:'',

        numeroTel:'',

        mail:'',

        obs:'',

        toma:'no',

        fuma:'no',

        heredoFam:'',

        antPers:''

    };

    $scope.datos1={

        enfermedad:'',

        familiar:'',

        estatus:'',

        observaciones:''        

    };

    $scope.edicionEnfHer={
      enfermedad:'',
      familiar:'',
      estatus:'',
      observaciones:'',
      idDato:''       
    };

    $scope.edicionAlergias={
      alergia:'',
      observaciones:'',
      idDato:''       
    };

    $scope.edicionPersonales={
      personal:'',
      observaciones:'',
      idDato:''       
    };

    $scope.cargadorHeredo='table table-striped table-hover';
    $scope.cargadorAlergia='table table-striped table-hover';
    $scope.cargadorPadecimiento='table table-striped table-hover';

    $scope.padecimiento={

        nombre:'',

        obs:''

    };

    $scope.otras={

        enf:'',

        obs:''

    };

    $scope.alergia={

        alergia:'',

        obs:''

    };

    $scope.padEsp={      

        obs:''

    };

    $scope.quiropractico={      

        obs:''

    };

    $scope.plantillas={      

        obs:''

    };

    $scope.tratamiento={      

        obs:''

    };

    $scope.intervenciones={      

        obs:''

    };

     $scope.deporte={      

        obs:''

    };

    $scope.adiccion={      

        obs:''

    };

    $scope.acc={      

        opc:'Si',

        lugar:'',

        obs:'',

        fecha:''

    };

     $scope.vitales={      

        tem:"",

        talla:"",

        peso:"",

        frecResp:"",

        sistole:"",

        astole:"",

        frecCard:"",

        obs:''

    };

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

        mecLesion:''

    }; 

    $scope.arregloOpc={  

               Nod:'1',

               Nodtx:'1',

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

               Nodi:'1',

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

        $scope.embarazo={

            controlGine:'No'

        }

        $scope.defaultRelig={

            obsReligDefault:'No'

        }



        $scope.seEntero='';

        $scope.mensajeLlego='';



        $scope.interacted = function(field) {

          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input

          return $scope.formularios.pac.$submitted && field.$invalid;          

        };



        $scope.interacted1 = function(field) {

          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input          

          return $scope.formularios.heredo.$submitted && field.$invalid;          

        };



        $scope.interacted2 = function(field) {

          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input          

          return $scope.formularios.cronicos.$submitted && field.$invalid;          

        };

         $scope.interacted3 = function(field) {

          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input          

          return $scope.formularios.enfermedades.$submitted && field.$invalid;          

        };

         $scope.interacted4 = function(field) {

          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input          

          return $scope.formularios.alergi.$submitted && field.$invalid;          

        };



        $scope.interacted5 = function(field) {

          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input          

          return $scope.formularios.ante.$submitted && field.$invalid;          

        };



        busquedas.ocupacion().success(function(data){

            $scope.ocupacion=data;               

        });

        busquedas.medico().success(function(data){
            console.log(data[0].Medico);
            $scope.medico=data[0].Medico;               

        });

        busquedas.edoCivil().success(function(data){

            $scope.edoCivil=data;

            //console.log(data);

        })

        busquedas.alergias().success(function(data){                      

          $scope.alergias=data;                          

        });

        busquedas.listaAlergias($rootScope.folio).success(function(data){                      

          if(data.length>0){   

            $scope.listaAlergias=data; 

          }
        });


        busquedas.enfermedad().success(function(data){

          $scope.enfermedad=data;                                                  

        });

        busquedas.familiar().success(function(data){

            $scope.familiar=data;                            

        });

        busquedas.estatusFam().success(function(data){

            $scope.estatus=data;                            

        });

        busquedas.listaEnfHeredo($rootScope.folio).success(function(data){

            if(data.length>0){

            $scope.listEnfeHe=data;

            }



            console.log(data);

            $scope.cargador=false;                                                

        });

        busquedas.padecimientos().success(function(data){                      

          $scope.padecimientos=data;                                 

        }); 



        busquedas.listaPadecimientos($rootScope.folio).success(function(data){                                                 

          if(data.length>0){   

             $scope.listaPad=data;

          }                        

        });



        busquedas.datosPacienteMovil($rootScope.folio).success(function(data){            
          console.log(data);
            $rootScope.nombre= data.Exp_completo;                                              
            $scope.datos.anios= data.Exp_edad;     
            $scope.datos.mail=data.Exp_mail;
            $scope.datos.obs=data.Rel_clave;
            $scope.datos.tel=data.Exp_telefono;
            $scope.datos.ocu = data.Ocu_clave;
            $scope.datos.edoC= data.Edo_clave;
            $scope.datos.sexo= data.Exp_sexo;
            $scope.datos.folio= $rootScope.folio;      
            
            //$('#modalHistoriaClinicaMovil').modal();
              // swal({title:"", 
              // text:"Doctor: Buen día, soy el o la Dr."+$rootScope.usrLogin+" de Atención Médica Inmediata y lo estaré atendiendo por medio de esta videollamada. \n¿Es usted el o la sra. "+$rootScope.nombre+"?\n\n ¿Cómo se encuentra el día de hoy? Paciente- _____      Doctor. Le haré unas breves preguntas para completar la historía clínica que nos ayudó a llenar hace un momento.Es importante verlo a los ojos, mantener una actitud cálida y escuchar activamente para crear una buena relación interpersonal con él.",  
              // icon: "info",
              // type: "info",
              // confirmButtonColor: "#629AFF", 
              // ConfirmButtonText: "Cerrar",
              // dangerMode: false});
          });  

         $scope.finished = function() {

            alert("Wizard finished :)");

        }



       



        $scope.goBack = function() {

            WizardHandler.wizard().goTo(0);

        }



        $scope.obsRelig = function(){        

            if($scope.defaultRelig.obsReligDefault){

              $scope.datos.obs='Ninguna';

            }

            else{

              $scope.datos.obs=''; 

            }

        }



        $scope.calculaFecha = function(fechaNacimiento){

          var anioFecha   = fechaNacimiento.substring(6,10);

          var mesFecha    = fechaNacimiento.substring(3,5);

          var diaFecha    = fechaNacimiento.substring(0,2);

          console.log(anioFecha);

          console.log(mesFecha);

          console.log(diaFecha);

          

          var d = new Date();

          var anioActual=d.getFullYear();

          var mesActual=(d.getMonth())+1;

          var diaActual=d.getDate();



          var aniosPaciente=anioActual-anioFecha;

          var mesesPaciente=mesActual-mesFecha;

          var diasPaciente=diaActual-diaFecha;

          if (mesesPaciente<0) {

              aniosPaciente=aniosPaciente-1;

              mesesPaciente=mesesPaciente*(-1);

              mesesPaciente=mesesPaciente-12;

              mesesPaciente=mesesPaciente*(-1);

          };

          if (diasPaciente<0) {

              mesesPaciente=mesesPaciente-1;

          };



          $scope.datos.anios  = aniosPaciente;

          $scope.datos.meses = mesesPaciente;

            // $http({

            //         url:'api/api.php?funcion=calFecha&fechaNac='+$scope.datos.fecnac,

            //         method:'POST', 

            //         contentType: 'application/json', 

            //         dataType: "json", 

            //         data: {'calve':'valor'}

            //         }).success( function (data){   

            //           //console.log(data);

            //             $scope.datos.anios= data.anios;

            //             $scope.datos.meses= data.meses;       

            //         }).error( function (xhr,status,data){

            //             $scope.mensaje ='no entra';            

            //             alert('Error');

            //         });

        }





        $scope.enviaDatos = function(){            

            if($scope.formularios.pac.$valid){

              $scope.cargador=true;

              $scope.msj= false;

              $scope.botonNota = false;

            $http({

                    url:'api/api.php?funcion=guardaDatosHistMovil',

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.datos

                    }).success( function (data){   

                      console.log(data);

                      $scope.cargador=false;

                      $scope.msj= true;

                      $scope.botonNota = true;



                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

                  }

        }

        $scope.guardaAntedente = function(){          

          //console.log($scope.datos1);

            if($scope.formularios.heredo.$valid){

            $scope.cargador=true;

            $http({

                    url:'api/api.php?funcion=guardaEnfH&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.datos1

                    }).success( function (data){                        

                      if(!data.respuesta){                                                                                      

                        $scope.listEnfeHe=data;

                        $scope.cargador=false;                           

                        $scope.datos1={

                          enfermedad:'',

                          familiar:'',

                          estatus:'',

                          observaciones:''        

                        }

                        $scope.formularios.heredo.$submitted=false;                                                                                               

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

        $scope.borrarEnfHeredo = function(contEnf){   
            $scope.cargador=true;                
            $http({
                    url:'api/api.php?funcion=borraEnfH&fol='+$rootScope.folio+'&cont='+contEnf,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: {'calve':'valor'}
                    }).success( function (data){   
                      //console.log(data);
                      if(!data.respuesta){
                        if(data.length>0){                                                                                    
                            $scope.listEnfeHe=data;                                                      
                         }else{
                            $scope.listEnfeHe=undefined;
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

      $scope.editaEnfHeredo = function(contEnf){   
          
          $http.get('api/movil.php?funcion=getDatosHeredo&fol='+$rootScope.folio+'&cont='+contEnf).success(function (data){                                 		
            $('#editarHeredo').modal();
            console.log($scope.enfermedad);
            $scope.edicionEnfHer.enfermedad = data.Enf_clave;
            $scope.edicionEnfHer.familiar = data.Fam_clave;
            $scope.edicionEnfHer.estatus = data.Est_clave;
            $scope.edicionEnfHer.observaciones = data.FamE_obs; 
            $scope.edicionEnfHer.idDato =  contEnf;         
          });
          
      }

      $scope.editapadecimiento = function(contPerso){   
          
        $http.get('api/movil.php?funcion=getDatosPadecimiento&fol='+$rootScope.folio+'&cont='+contPerso).success(function (data){                                 		
          $('#editarPersonales').modal();
          console.log($scope.enfermedad);
          $scope.edicionPersonales.personal = data.Pad_clave;
          $scope.edicionPersonales.observaciones = data.Pad_obs; 
          $scope.edicionPersonales.idDato =  contPerso;         
        });
        
    }

    

      $scope.editarAlergia = function(contAlergia){             
        $http.get('api/movil.php?funcion=getDatosAlergias&fol='+$rootScope.folio+'&cont='+contAlergia).success(function (data){                                 		
          $('#editarAlergia').modal();
          console.log(data);
          $scope.edicionAlergias.alergia = data.Ale_clave;          
          $scope.edicionAlergias.observaciones = data.Ale_obs; 
          $scope.edicionAlergias.idDato =  contAlergia;         
        });
        
    }

      $scope.editaHeredoFamiliares = function(){   
        $scope.cargadorHeredo='table table-striped table-hover csspinner shadow oval right';  
        $http({
          url:'api/movil.php?funcion=editaDatosHeredo',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.edicionEnfHer
          }).success( function (data){   
            console.log(data);
            busquedas.listaEnfHeredo($rootScope.folio).success(function(data){
              $scope.cargadorHeredo='table table-striped table-hover';
                if(data.length>0){
                  $scope.listEnfeHe=data;
                } 
                $('#editarHeredo').modal('hide');                             
            });
          }).error( function (xhr,status,data){
              $scope.mensaje ='no entra';            
              alert('Error');
          });
        
    }

    $scope.editaAlergias = function(){ 
      
      console.log($scope.edicionAlergias);
      $scope.cargadorAlergia='table table-striped table-hover csspinner shadow oval right';  
      $http({
        url:'api/movil.php?funcion=editaDatosAlergia',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.edicionAlergias
        }).success( function (data){   
          console.log(data);
          busquedas.listaAlergias($rootScope.folio).success(function(data){  
            $scope.cargadorAlergia='table table-striped table-hover';                    
            if(data.length>0){   
              $scope.listaAlergias=data;
            }
            $('#editarAlergia').modal('hide');        
          });          
        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });
      
  }


  $scope.editarPadecimiento = function(){ 
          
    $scope.cargadorPadecimiento='table table-striped table-hover csspinner shadow oval right';  
    $http({
      url:'api/movil.php?funcion=editaDatosPadecimiento',
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.edicionPersonales
      }).success( function (data){   
        console.log(data); 
        busquedas.listaPadecimientos($rootScope.folio).success(function(data){  
          $scope.cargadorPadecimiento='table table-striped table-hover';                                               
          if(data.length>0){   
             $scope.listaPad=data;
          }                        
        }); 
        $('#editarPersonales').modal('hide');          
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      });
    
}

        $scope.antPersonales = function(){         

            busquedas.padecimientos().success(function(data){                      

              $scope.padecimientos=data;                                 

            }); 

            busquedas.otrasEnf().success(function(data){                                 

                $scope.otrasE=data;                                      

            });

            busquedas.alergias().success(function(data){                      

              $scope.alergias=data;                          

            });    

            busquedas.listaPadecimientos($rootScope.folio).success(function(data){                                                 

              if(data.length>0){   

                 $scope.listaPad=data;

              }                        

            });  

            busquedas.listaOtrasEnf($rootScope.folio).success(function(data){                      

              if(data.length>0){   

                $scope.listaOtras=data; 

              }

              //console.log($scope.listaOtras);                         

            });  

            busquedas.listaAlergias($rootScope.folio).success(function(data){                      

              if(data.length>0){   

                $scope.listaAlergias=data; 

              }

              //console.log($scope.listaAlergias);                         

            }); 

            busquedas.listaPadEsp($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaPadEsp=data; 

              }

              //console.log($scope.listaPadEsp);                         

            }); 

            busquedas.listaTratQuiro($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaTratQui=data; 

              }

              //console.log($scope.listaPadEsp);                         

            }); 

            busquedas.listaPlantillas($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaPlantillas=data; 

              }

              //console.log($scope.listaPlantillas);                        

            }); 

            busquedas.listaTratamientos($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaTratamientos=data; 

              }

              //console.log($scope.listaTratamientos);                        

            });

            busquedas.listaIntervenciones($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaIntervenciones=data; 

              }

              //console.log($scope.listaIntervenciones);                        

            });

            busquedas.listaDeportes($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaDeportes=data; 

              }

              //console.log($scope.listaDeportes);                      

            }); 

             busquedas.listaAdicciones($rootScope.folio).success(function(data){                      

              if(data.length>0){   

              $scope.listaAdicciones=data; 

              }

              //console.log($scope.listaAdicciones);                      

            }); 

             $cookies.estatus=2;

            WizardHandler.wizard().next();



        }

        $scope.agregaCronDeg= function(){

            //console.log($scope.padecimiento);

            if($scope.formularios.cronicos.$valid){

            $scope.cargador=true;           

            $http({

                    url:'api/api.php?funcion=guardaPad&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.padecimiento

                    }).success( function (data){   

                    //console.log(data);

                      if(!data.respuesta){

                        $scope.cargador=false;                                     

                        $scope.listaPad=data;                                                                      

                        $scope.padecimiento={

                            nombre:'',

                            obs:''                            

                        };

                        $scope.formularios.cronicos.$submitted=false;

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

        $scope.borrarPadecimiento = function(idPad){ 
         $scope.cargador=true;                            
            $http({
                    url:'api/api.php?funcion=borraPadec&fol='+$rootScope.folio+'&cont='+idPad,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: {'calve':'valor'}
                    }).success( function (data){   
                      //console.log(data);
                      if(!data.respuesta){
                        if(data.length>0){                                        
                          $scope.listaPad=data;  
                          }else{
                            $scope.listaPad=undefined;
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

        $scope.guardaOtras= function(){

           if($scope.formularios.enfermedades.$valid){

            //console.log($scope.otras);

            $scope.cargador1=true;

            $http({

                    url:'api/api.php?funcion=guardaOtras&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.otras

                    }).success( function (data){   

                     // console.log(data);

                      if(!data.respuesta){                                       

                          $scope.listaOtras=data; 

                          $scope.cargador1=false;

                         // console.log($scope.listaOtras);                                               

                        $scope.otras={

                            enf:'',

                            obs:''                            

                        };

                        $scope.formularios.enfermedades.$submitted=false;

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

        $scope.borrarOtras = function(idPad){

          $scope.cargador1=true;                             

            $http({

                    url:'api/api.php?funcion=borraOtrasEnf&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'calve':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){  

                        if(data.length>0){                                                              

                          $scope.listaOtras=data;                                                                                                 

                        }else{

                          $scope.listaOtras=undefined;

                        }

                         $scope.cargador1=false;

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaAlergia= function(){

          if($scope.formularios.alergi.$valid){

            $scope.cargador2=true;            

            $http({

                    url:'api/api.php?funcion=guardaAlergia&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.alergia

                    }).success( function (data){   

                     // console.log(data);

                      if(!data.respuesta){                                

                          $scope.listaAlergias=data; 

                          $scope.cargador2=false; 

                          //console.log($scope.listaAlergias);                                               

                        $scope.alergia={

                            alergia:'',

                            obs:''

                        };

                        $scope.formularios.alergi.$submitted=false;

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

        $scope.borrarAlergia = function(idPad){ 

            $scope.cargador2=true;                             

            $http({

                    url:'api/api.php?funcion=borraAlergia&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'calve':'valor'}

                    }).success( function (data){   

                     // console.log(data);

                      if(!data.respuesta){

                       if(data.length>0){                                                

                          $scope.listaAlergias=data; 

                        }else{

                          $scope.listaAlergias=undefined;

                        }

                          $scope.cargador2=false; 

                         // console.log($scope.listaAlergias);                                                                                 

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaPadEspalda= function(){           

          //console.log($scope.padEsp);

            $scope.cargador3=true; 

            $http({

                    url:'api/api.php?funcion=guardaPadEspalda&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.padEsp

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                                              

                            $scope.listaPadEsp=data; 

                            $scope.cargador3=false; 

                            //console.log($scope.listaPadEsp);                                                 

                        $scope.padEsp={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.eliminaPadEspalda = function(idPad){

            $scope.cargador3=true;                              

            $http({

                    url:'api/api.php?funcion=borraPadEspalda&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'calve':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){ 

                        if(data.length>0){                                                                           

                          $scope.listaPadEsp=data;                           

                        }else{

                          $scope.listaPadEsp=undefined;

                        }                             

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaTratQui= function(){           

          //console.log($scope.quiropractico);

          $scope.cargador4=true; 

            $http({

                    url:'api/api.php?funcion=guardaTratQuiro&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.quiropractico

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                        

                          $scope.listaTratQui=data; 

                          $scope.cargador4=false;                          

                        $scope.quiropractico={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.eliminaTratQui = function(idPad){ 

            $scope.cargador4=true;                             

            $http({

                    url:'api/api.php?funcion=borraTratQui&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'calve':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){  

                        if(data.length>0){                                          

                          $scope.listaTratQui=data; 

                        }else{

                          $scope.listaTratQui=undefined;

                        }

                          $scope.cargador4=false;                                                                               

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaPlantillas= function(){           

          //console.log($scope.plantillas);

          $scope.cargador5=true; 

            $http({

                    url:'api/api.php?funcion=guardaPlantillas&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.plantillas

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                                          

                          $scope.listaPlantillas=data; 

                          $scope.cargador5=false;                          

                        $scope.plantillas={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

         $scope.eliminaPlantillas = function(idPad){                             

            $scope.cargador5=true; 

            $http({

                    url:'api/api.php?funcion=borraPlatillas&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'clave':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){

                        if(data.length>0){                                           

                          $scope.listaPlantillas=data; 

                        }else{

                          $scope.listaPlantillas=undefined;

                        }

                          $scope.cargador5=false;                                

                        }                      

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaTratamiento= function(){           

          //console.log($scope.tratamiento);

          $scope.cargador6=true; 

            $http({

                    url:'api/api.php?funcion=guardaTratamiento&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.tratamiento

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                                          

                          $scope.listaTratamientos=data; 

                          $scope.cargador6=false; 

                        $scope.tratamiento={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.eliminaTratamiento = function(idPad){                             

            $scope.cargador6=true; 

            $http({

                    url:'api/api.php?funcion=borraTratamiento&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'clave':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){

                        if(data.length>0){                                          

                          $scope.listaTratamientos=data; 

                        }else{

                          $scope.listaTratamientos=undefined;

                        }

                          $scope.cargador6=false;                                                                            

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaIntervenciones= function(){           

          //console.log($scope.tratamiento);

          $scope.cargador7=true; 

            $http({

                    url:'api/api.php?funcion=guardaIntervenciones&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.intervenciones

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                        

                          $scope.listaIntervenciones=data; 

                          $scope.cargador7=false;                                                   

                        $scope.intervenciones={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.eliminaIntervencion = function(idPad){ 

            $scope.cargador7=true;                             

            $http({

                    url:'api/api.php?funcion=borraIntervencion&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'clave':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){  

                        if(data.length>0){                      

                          $scope.listaIntervenciones=data; 

                        }else{

                          $scope.listaIntervenciones=undefined;

                          }                        

                          $scope.cargador7=false;                                                       

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaDeporte= function(){           

          //console.log($scope.tratamiento);

          $scope.cargador8=true; 

            $http({

                    url:'api/api.php?funcion=guardaDeporte&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.deporte

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                        

                          $scope.listaDeportes=data; 

                          $scope.cargador8=false;  

                        $scope.deporte={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.eliminaDeporte = function(idPad){  

            $scope.cargador8=true;                            

            $http({

                    url:'api/api.php?funcion=borraDeporte&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'clave':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){   

                        if(data.length>0){                     

                          $scope.listaDeportes=data; 

                        }else{

                          $scope.listaDeportes=undefined;

                        }

                          $scope.cargador8=false;                                            

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.guardaAdiccion= function(){           

          //console.log($scope.adiccion);

            $scope.cargador9=true; 

            $http({

                    url:'api/api.php?funcion=guardaAdiccion&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.adiccion

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){                        

                          $scope.listaAdicciones=data; 

                          $scope.cargador9=false;  

                        $scope.adiccion={

                          obs:''

                        }

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.eliminaAdiccion = function(idPad){ 

            $scope.cargador9=true;                             

            $http({

                    url:'api/api.php?funcion=borraAdiccion&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'clave':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){    

                        if(data.length>0){                     

                          $scope.listaAdicciones=data; 

                        }else{

                          $scope.listaAdicciones=undefined;

                        }

                          $scope.cargador9=false;                              

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.accidentesAnteriores = function(){          

          busquedas.catLugar().success(function(data){                      

            $scope.lugar=data;                          

          }); 

          busquedas.listaAccAnteriores($rootScope.folio).success(function(data){

            if(data.length>0){                      

              $scope.listAccAnt=data;

            }

            //console.log($scope.listAccAnt);                          

          }); 

          $cookies.estatus=3;

          WizardHandler.wizard().next();  

        }

        $scope.guardaAccAnt= function(){           

          //console.log($scope.acc);

          if($scope.formularios.ante.$valid){

          $scope.cargador=true;

            $http({

                    url:'api/api.php?funcion=guardaAccAnt&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.acc

                    }).success( function (data){                      

                      if(!data.respuesta){                        

                          $scope.listAccAnt=data;                            

                        $scope.acc={

                          opc:'Si',

                          lugar:'',

                          obs:'',

                          fecha:''

                        }

                        $scope.formularios.ante.$submitted=false;

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

        $scope.eliminaAccAnt = function(idPad){                             

            $scope.cargador=true;

            $http({

                    url:'api/api.php?funcion=borraAccAnt&fol='+$rootScope.folio+'&cont='+idPad,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: {'clave':'valor'}

                    }).success( function (data){   

                      //console.log(data);

                      if(!data.respuesta){        

                        if(data.length>0){

                          $scope.listAccAnt=data;

                        }else{

                          $scope.listAccAnt=undefined;

                        }                                     

                        $scope.cargador=false                                                                            

                      }

                      else{

                        alert('error en la eliminación');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

        }

        $scope.imprimirHistoriaClinica = function(motivo){          

            var fileName = "Reporte";

            var uri = 'api/classes/FormatoH.php?fol='+$rootScope.folio+'&motivo='+motivo+'&usr='+$rootScope.usrLogin;

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

         $scope.irDocumentos = function(motivo){         

              $http.get('api/api.php?funcion=guardamotivoCons&fol='+$rootScope.folio+'&motivo='+motivo+'&usr='+$rootScope.usrLogin).success(function (data){                                                

                if(data.respuesta=='correcto'){

                  $location.path("/documentos");  

                }else{

                  $location.path("/documentos");  

                }

              });             

        }



        $scope.irNotaMedica = function(){  

          $location.path("/notaMedicaMovil");  

        }



        $scope.agregaTel = function(){         

              

              if($scope.telefonos.tipo!='' && $scope.telefonos.telefono!=''){                

                $scope.msjTel=false;               

              $http({

                    url:'api/api.php?funcion=guardaTels&fol='+$rootScope.folio,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.telefonos

                    }).success( function (data){   

                      console.log(data);

                      $scope.telefonos={

                        tipo:'',

                        telefono:''

                      }

                      if(!data.respuesta){                               

                          $scope.listTelefonos=data;                                                                              

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

              }else{

                $scope.msjTel=true;

              }



        }

        $scope.borrarTels = function(cveTel){                                     

              $http({

                    url:'api/api.php?funcion=borraTels&fol='+$rootScope.folio+'&cveTel='+cveTel,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.telefonos

                    }).success( function (data){                                               

                      if(!data.respuesta){                               

                          if(data.length>0){

                            $scope.listTelefonos=data;                                                                                

                          }else{

                            $scope.listTelefonos='';

                          }

                          

                      }

                      else{

                        alert('error en la inserción');

                      }

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });              

        }



        $scope.guardarComoLLego = function(){  

          $scope.mensajeLlego=''; 

            $http.get('api/api.php?funcion=comoLlego&fol='+$rootScope.folio+'&llego='+$scope.seEntero).success(function (data){  

            console.log(data);                                              

             if(data=='exito'){

              $('#comoLlegoModal').modal('hide');

             }else{

                $scope.mensajeLlego='ocurrio un problema';

             }

            });                      

        }

});







function verOpciones(opcion){

  switch(opcion){

    case '0':

            validos={Nod:'1',

               Nodtx:'1',

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

               Nodi:'1',

               Noditx:'1',

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

               Cintu:'1',

               Cintutx:'1',

               Bolsa:'1',

               Bolsatx:'1',

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

               Volc:'1',

               Volctx:'1',

               Alca:'1',

               Alcatx:'1',

               Late:'1',

               Latetx:'1',

               Fron:'1',

               Frontx:'1',

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

               ManSub:'1',

               ManSubtx:'1',

               Otr:'1',

               Otrtx:'1'

              }

    break; 

     case '2':

            validos={Nod:'',

               Nodtx:'',

               Cintu:'',

               Cintutx:'',

               Bolsa:'',

               Bolsatx:'',

               Ropa:'1',

               Ropatx:'1',

               Casco:'1',

               Cascotx:'1',

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

               Alca:'1',

               Alcatx:'1',

               Late:'1',

               Latetx:'1',

               Fron:'1',

               Frontx:'1',

               Derr:'1',

               Derrtx:'1',

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

            validos={Nod:'1',

               Nodtx:'1',

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

               Caid:'1',

               Caidtx:'1',

               Lesdep:'1',

               Lesdeptx:'1',

               Lestra:'1',

               Lestratx:'1',

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

               Casco:'1',

               Cascotx:'1',

               Rodi:'1',

               Roditx:'1',

               Code:'1',

               Codetx:'1',

               Costi:'1',

               Costitx:'1',

               Nodi:'',

               Noditx:'',

               Volc:'',

               Volctx:'',

               Alca:'1',

               Alcatx:'1',

               Late:'1',

               Latetx:'1',

               Fron:'1',

               Frontx:'1',

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

            validos={Nod:'1',

               Nodtx:'1',

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

               Simp:'1',

               Simptx:'1',

               Imap:'1',

               Imaptx:'1',

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

            validos={Nod:'1',

               Nodtx:'1',

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

               Volc:'1',

               Volctx:'1',

               Alca:'1',

               Alcatx:'1',

               Late:'1',

               Latetx:'1',

               Fron:'1',

               Frontx:'1',

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

               ManSub:'1',

               ManSubtx:'1',

               Otr:'1',

               Otrtx:'1'

              }

    break; 

     case '7':

            validos={Nod:'1',

               Nodtx:'1',

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

               Nodi:'1',

               Noditx:'1',

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

            validos={Nod:'1',

               Nodtx:'1',

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

               Nodi:'1',

               Noditx:'1',

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