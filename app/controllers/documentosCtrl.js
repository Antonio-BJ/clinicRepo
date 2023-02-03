app.controller('documentosCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {

  $rootScope.folio=$cookies.folio;

  $rootScope.usrLogin= $cookies.usrLogin;

  $rootScope.uniClave=$cookies.uniClave;

  $rootScope.permisos=JSON.parse($cookies.permisos);    

  $scope.subsecuenciaVal=true;

    $scope.Ndigital=false;

    $scope.paseReha=false;

    $scope.esParticular=false;

    $scope.esAba=false;

    $scope.esAxa=false;

    $scope.ProSegNuevo=true

    $scope.cargador=true; 

    $scope.noSubsec=''; 

    $scope.soloReha=true; 

    $scope.veEditaNota=true;

    $scope.valida1=false;

    $scope.valida2=false;

    $scope.valida3=false;

    $scope.valida4=false;

    $scope.valida5=false;

    $scope.valida6=false;

    $scope.valida7=false;

    $scope.valida8=false;

    $scope.validaHistoria=false;

    $scope.string = ''; 

    $scope.tipoDoc=''; 

    $scope.fecha='';

    $scope.divSubsecuencia=false;

    $scope.enviado=false;

    $scope.cia=0;

    $scope.datos={

        folio: $rootScope.folio

    }

    $scope.cargardoModal = '';

    $scope.validaProducto=true;

    $scope.verReciboDeducibleMetlife=false;

    $scope.addendum={

        cuerpo:'',

        doc:'',

        noSub:'',

        usr:''

    }

    $scope.alta={

      tipo:'',

      observaciones:''

    }

    $scope.tipDocAddem=0; 

    $scope.mensajeExito=false;



    $scope.detalle={

      nombre:'',

      sexo:'',

      edad:'',

      producto:'',

      fechaReg:'',

      aseguradora:'',

      telefono:'',

      mail:'',

      ocupacion:'',

      religion:'',

      salPaq:false,
      referencia:'',
      partMed:''

    } 

    $scope.solicitudCE={

      motivo:'',

      tiempo:''

    } 

    $scope.ejercicios = [
      {ruta:'imgs/pdf.png',nombre:'Cadera y Rodilla',nom:'DRM_Eje_caderaRodilla',ubicacion:'ejercicios/Cadera_Rodilla.pdf', variable:false},
      {ruta:'imgs/pdf.png',nombre:'Higiene de Columna',nom:'DRM_Eje_Hcolumna',ubicacion:'ejercicios/Higiene_Columna.pdf', variable:false},
      {ruta:'imgs/pdf.png',nombre:'Columna Cervical',nom:'DRM_Eje_CVertebral',ubicacion:'ejercicios/Columna_Cervical.pdf', variable:false},
      {ruta:'imgs/pdf.png',nombre:'Hombro',nom:'DRM_Eje_hombro',ubicacion:'ejercicios/Hombro.pdf', variable:false},
      {ruta:'imgs/pdf.png',nombre:'Codo, Mano , y Muñeca',nom:'DRM_Eje_cmm',ubicacion:'ejercicios/Codo_Mano_Muneca.pdf', variable:false},
      {ruta:'imgs/pdf.png',nombre:'Tobillo y Pie',nom:'DRM_Eje_TP',ubicacion:'ejercicios/Tobillo_Pie.pdf', variable:false},
      {ruta:'imgs/pdf.png',nombre:'Columna Dorsolumbar',nom:'DRM_Eje_CDorsolumbar',ubicacion:'ejercicios/Columna_Dorsolumbar.pdf', variable:false}
  ];



    /******  alta membresia con folio ***************/

    $scope.nombreMembresia='';

    $scope.cargadorMem = false;



    $scope.membresia={

        nombre:'',

        apanterno:'',

        amaterno:'',

        email:'',

        telefono:'',

        codPos:'',

        obs:''

    }



    $scope.referencia={

        nombre:'',

        email:'',

        telefono:'',

        parentezco:''

    }

    $scope.medico={

      nombre:localStorage.getItem("medicoSuplente"),

      cedula:localStorage.getItem("cedulaMedicoSuplente")

  }

    $scope.veTabla=false; 

    $scope.sumaReferencia =[] ;  


    $scope.camUnidad= true;


    /******  alta membresia con folio ***************/

 

    $scope.solicutudValida=''; 

    $scope.verDocumentosCE=''; 

    $scope.reciboDeducible=false;

    $scope.validaFacturaVal='';



    $scope.existeSolCE=false;

    $scope.existeAutCE=true;



    $scope.verCrearMemebresia=false;

    $scope.btonGenerarMem=true;



    $scope.valAccNotSoap='';

    $scope.notaIntComentario='';

    $scope.msjError=false;

    $scope.atender=false;

    $scope.cveUniInventario=1;
    $scope.verComentario = false;
    $scope.contador = 0;

    $scope.comentario='';
    $scope.docsCorreo = '';

    $scope.datosEnvio = {
      notaMedica      : 1,
      historiaClinica : 1,
      receta          : 1,
      correo          : ''
    }

    if($rootScope.uniClave==1||$rootScope.uniClave==3||$rootScope.uniClave==2||$rootScope.uniClave==184||$rootScope.uniClave==4||$rootScope.uniClave==86||$rootScope.uniClave==7||$rootScope.uniClave==5||$rootScope.uniClave==186||$rootScope.uniClave==6||$rootScope.uniClave==8){

      $scope.cveUniInventario=$rootScope.uniClave;  

    }

    

    if($scope.cveUniInventario==$rootScope.uniClave){

      $scope.ValidaInventario=1;

    }else{

      $scope.ValidaInventario=2;

    }

    $http.get('api/catalogos.php?funcion=getCambioUnidad&fol='+$rootScope.folio).success(function (data){                      
            console.log(data);
              $scope.camUnidad=false; 
            /*if(data==0){             
            }*/
            /*if(data>0){
              $scope.camUnidad=false;              
            }
            */
    });

    $http.get('api/movil.php?funcion=getUnidad&fol='+$rootScope.folio).success(function (data){                       
      console.log(data.unidad);
      if(data.unidad==392){
        $rootScope.uniClave    = data.unidad;     
      }
});

$http.get('api/movil.php?funcion=getUnidad&fol='+$rootScope.folio).success(function (data){                       
  console.log(data.unidad);
  if(data.unidad==392){
    $rootScope.uniClave    = data.unidad;     
  }
});

    $http.get('api/detallePx.php?funcion=getComentarios&fol='+$rootScope.folio).success(function (data){                       
        console.log(data);
        if(data.length>0){
          $scope.contador = data.length;
          $scope.listadoComentarios = data;
          $scope.verComentario = true;
        }else{
          $scope.verComentario = false;
        }
    });

    $http.get('api/detallePx.php?funcion=getCarrito&fol='+$rootScope.folio).success(function (data){                       
      console.log(data);
      $scope.contadorItemsCarrito = data;
  });

      $http.get('api/detallePx.php?funcion=getDocumentos&fol='+$rootScope.folio).success(function (data){                       
        console.log(data);
        if(data.length>0){
          $scope.docsCorreo = data;
          $scope.datosEnvio.correo = data[0].correo;
        }
        
    });

    $http.get('api/detallePx.php?funcion=verVigenciaFolio&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin).success(function (data){                          
          //if((data.dias>30||data.tipo=='reactivar')&&data.autorizado!=1){          
        $scope.dias=data.dias;
        $scope.tipoMod = data.tipo;
        console.log(data);
      if(data.dias>30&&data.autorizado!=1){          
        

        /***********************************funcion para saber el perfil del usuario*************************************/
        $http.get('api/detallePx.php?funcion=getPerfil&usr='+$rootScope.usrLogin).success(function (data){   
            console.log(data);
            if(data==4){
              $('#extemporaneo').modal({ backdrop: 'static', keyboard: false });     
            }else{
              $('#extemporaneoMedico').modal({ backdrop: 'static', keyboard: false });     
            }                  
        });         
      }
      if($scope.dias>30&&data.autorizado==1){
        $http.get('api/detallePx.php?funcion=getPerfil&usr='+$rootScope.usrLogin).success(function (data){                 
            if(data!='4'){
              $('#extemporaneoMedicoAviso').modal({ backdrop: 'static', keyboard: false });      
            }                 
        });               
      }
    });

    $scope.interacted = function(field) {

      //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input

      return $scope.agreAddendum.$submitted && field.$invalid;          

    };

    $scope.interacted2 = function(field) {

      return $scope.membresiaFormDoc.$submitted && field.$invalid;

    };

    $scope.interacted3 = function(field) {

      return $scope.referenciasForm.$submitted && field.$invalid;

    };

     $http.get('api/notaMedica.php?funcion=getRecComp&fol='+$rootScope.folio).success(function (data){     
          $scope.listadoRecComplementaria = data;
          console.log(data);
    });

    $http.get('api/notaMedica.php?funcion=getMedico&usr='+$rootScope.usrLogin).success(function (data){   
      if(data.Med_tipo==2){
        $scope.medico.nombre = localStorage.getItem("medicoSuplente");
        $scope.medico.cedula = localStorage.getItem("cedulaMedicoSuplente");
        $('#medico').modal({ backdrop: 'static', keyboard: false });      
      }  
      
    });

     $http.get('api/notaMedica.php?funcion=getRecPart&fol='+$rootScope.folio).success(function (data){     
          $scope.listadoRecParticulares = data;
          console.log(data);
    });

    


    $http.get('api/detallePx.php?funcion=getDatosPersonales&fol='+$rootScope.folio).success(function (data){  

                      console.log(data);                                                                                               

                      $scope.detalle.nombre=data.Exp_completo;

                      if(data.Exp_sexo=='F'){

                        $scope.detalle.sexo ='FEMENINO';

                      }else if(data.Exp_sexo=='M'){

                        $scope.detalle.sexo ='MASCULINO';

                      }else{

                        $scope.detalle.sexo= '--';  

                      }



                      if(data.Pro_clave==2){

                        $scope.reciboDeducible=true;

                        console.log($scope.reciboDeducible);

                      }    

                      $scope.detalle.referencia=data.Ref_nombre;7
                      $scope.detalle.partMed=data.partMed;
                      $scope.detalle.edad= data.Exp_edad;

                      $scope.detalle.producto= data.Pro_nombre;

                      $scope.detalle.fechaReg= data.Exp_fecreg;

                      $scope.detalle.aseguradora= data.Cia_nombrecorto;

                      $scope.detalle.telefono= data.Exp_telefono;

                      $scope.detalle.mail= data.Exp_mail;                      

                      $scope.detalle.religion= data.Rel_clave;

                      $scope.detalle.fechaNacimiento= data.Exp_fechaNac;

                      $scope.detalle.triage  = data.triage;

                      $scope.detalle.hospitalario = data.hospitalario;

                      $scope.detalle.Cia_clave = data.Cia_clave;

                      $scope.detalle.Exp_triageActual = data.Exp_triageActual; 
                      
                      
                      if(data.Pro_clave==4 && $scope.detalle.Cia_clave==55 || $scope.detalle.Cia_clave==65 ){
                        $scope.verReciboDeducibleMetlife=true;
                      }

                      if($scope.detalle.edad==0||$scope.detalle.edad==null){

                        $scope.detalle.edad='--';                        

                      }

                      if($scope.detalle.mail==''||$scope.detalle.mail==null){

                        $scope.detalle.mail='--';                        

                      }

                      if($scope.detalle.religion==''||$scope.detalle.religion==null){

                        $scope.detalle.religion='--';                        

                      }

                      if($scope.detalle.Cia_clave==19&&$scope.detalle.Exp_triageActual>2){

                        $scope.detalle.salPaq = true;

                      }else if(($scope.detalle.Cia_clave!=7&&$scope.detalle.Cia_clave!=19&&$scope.detalle.Cia_clave!=4&&$scope.detalle.Cia_clave!=3)&&$scope.detalle.Exp_triageActual>1){

                        $scope.detalle.salPaq = true;

                      }

                      console.log($scope.detalle.salPaq);

                  }); 



    $http.get('api/cortaEstancia.php?funcion=getfolioCE&fol='+$rootScope.folio).success(function (data){   

        if(data.Exp_folio){

          $scope.existeSolCE=true;

          if(data.CE_codUsado!=''){

            $scope.existeAutCE=false;

          }

        }

    });

  $scope.recetaComp=false;

  $http.get('api/api.php?funcion=getProducto&folio='+$rootScope.folio).success(function (data){
      console.log(data);
      if (data.Pro_clave==4) {
        $scope.recetaComp=true;
      } else {
        $scope.recetaComp=false;
      }
  });


    $http.get('api/api.php?funcion=verificaHCR&folio='+$rootScope.folio).success(function (data){   

        console.log(data);

        if (data.Exp_folio) {

          $scope.existeHCR=true;

        };

        if (data=='false') {

          $scope.existeHCR=false;

        };

    });





    $http.get('api/catalogos.php?funcion=avisosCoordinacion&fol='+$rootScope.folio).success(function (data){     

  

    if(!data.respuesta){

      $scope.listadoComentarios=data;

        $('#comentarioExpediente').modal('show');   

    }

  busquedas.validaNota($rootScope.folio).success(function(data){    

        $scope.cargador=false;

    if(data.respuesta=='existe'){

      $scope.subsecuenciaVal=false;

      busquedas.validaParticular($rootScope.folio).success(function(data){
        console.log(data);
        if(data.Pro_clave==5 || data.Pro_clave==4 || data.Pro_clave==13){

            $scope.soloReha=false;            

        }else{

            $scope.soloReha=true;

        } 

      }); 

    } 

    else if(data.respuesta=='noExiste'&&data.digital=='existe'){

      $scope.subsecuenciaVal=false;

      $scope.Ndigital=true;

      busquedas.validaParticular($rootScope.folio).success(function(data){

        if(data.Pro_clave==5 || data.Pro_clave==4 || data.Pro_clave==13){

            $scope.soloReha=false;            

        }else{

            $scope.soloReha=true;

        } 

      });                                       

    }                    

    else{

             busquedas.validaParticular($rootScope.folio).success(function(data){                                                      

                $scope.proClave=data.Pro_clave;

                if(data.Pro_clave==8){

                    $scope.validaProducto=false;

                    $scope.soloReha=false;

                }else{

                    $scope.validaProducto=true;

                }

                if(data.Pro_clave==5 || data.Pro_clave==4 || data.Pro_clave==13){

                    $scope.soloReha=false;                    

                }else{

                    $scope.soloReha=true;

                }

                if(data.Pro_clave==2){

                  $scope.reciboDeducible=true;

                }else{

                  $scope.reciboDeducible=false;

                }          

            });

      

    }               

    });

    busquedas.validaPaseM($rootScope.folio).success(function(data){             
      console.log("paseReha:"+data);
        if(data.respuesta=='existe'){

            $scope.paseReha=true;

        }                     

        else{

            $scope.paseReha=false;

        }                 

    });




    



    $http.get('api/catalogos.php?funcion=getMembresia&fol='+$rootScope.folio).success(function (data){  

          console.log(data);   

          if(data.respuesta==0){

            $scope.verCrearMemebresia=true;

          }else{

            $scope.verCrearMemebresia=false;

            $scope.folMem    = data.mem_folio;

            $scope.serieMem  = data.mem_serie; 

            $scope.nombreMem = data.mem_nombre;

          }

    });



    $http.get('api/Clasificaciones.php?funcion=getConvenioExp&fol='+$rootScope.folio).success(function (data){  

          /*console.log(data); */

          if (data.CON_cve==4) {

            $scope.esDesclub=true;

            /*console.log('Es Desclub');*/

          } else {

            $scope.esDesclub=false;

          };

    });

    

    busquedas.validaParticular($rootScope.folio).success(function(data){   
      $scope.cia =data.Cia_clave;            

        if(data.Cia_clave==51||data.Cia_clave==44||data.Cia_clave==54||data.Cia_clave==53||data.Cia_clave==64||data.Cia_clave==71){

            $scope.esParticular=true;

        }else if(data.Cia_clave==1){

            $scope.esAba=true;

            $http.get('api/catalogos.php?funcion=getCambioUnidad&fol='+$rootScope.folio).success(function (data){     

                  console.log(data);

                  if(data!=0){

                    $scope.paseReha=true;

                    $scope.validaProducto=false;

                  }

            });

        }else if(data.Cia_clave==7||data.Cia_clave==3||data.Cia_clave==19||data.Cia_clave==4){

            $scope.esAxa=true;

            $http.get('api/catalogos.php?funcion=getCambioUnidad&fol='+$rootScope.folio).success(function (data){     

                  console.log(data);

                  if(data!=0){

                    $scope.paseReha=true;

                    $scope.validaProducto=false;

                  }

            });

        }else if(data.Cia_clave==54){



        }              

    });



     busquedas.validaHistoriaClinica($rootScope.folio).success(function(data){  

        console.log(data);                

         if(data!='false'){

            $scope.validaHistoria=true;

         }else{

            $scope.validaHistoria=false;

         }

    });






 

    

  });

    busquedas.estatusNota($rootScope.folio).success(function(data){         

      $scope.pasoHC=data;

      console.log($scope.pasoHC);

        if ($scope.pasoHC=="6" || $scope.recetaComp==true) {
          $scope.recetaComp=true;
        };
        
        if(data=="6"){

            $scope.veEditaNota=false;

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

                    $scope.valida1=true;



                  });

                  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){          

                      $scope.datos.alergias = data;

                      $scope.valida2=true;

                  });

                  $http.get('api/api.php?funcion=datosDoc&usr='+$rootScope.usrLogin).success(function (data){

                                           

                      $scope.datos.doctor = data.Med_nombre+' '+data.Med_paterno+' '+data.Med_materno;

                      $scope.datos.cedula = data.Med_cedula;

                      $scope.datos.especialidad = data.Med_esp;

                      $scope.datos.telefonos = data.Med_telefono;

                      $scope.valida3=true;   

                  });



                  $http.get('api/api.php?funcion=datosUni&uni='+$rootScope.uniClave).success(function (data){

                      $scope.datos.direccionUni  = data.Uni_calleNum+', '+data.Uni_colMun;

                      $scope.datos.telUni = data.Uni_tel; 

                      $scope.valida7=true;                                         

                  });



                  $http.get('api/api.php?funcion=generaFolio&fol='+$rootScope.folio).success(function (data){                      

                      $scope.valida8=true; 

                  });

                  $http.get('api/api.php?funcion=datosMedicamentoRec&fol='+$rootScope.folio).success(function (data){                                        

                      

                      $scope.datos.medicamentos=data;                    

                      $scope.valida4=true;



                  });

                  $http.get('api/api.php?funcion=datosOrtRec&fol='+$rootScope.folio).success(function (data){                                        

                      

                      $scope.datos.ortesis=data;                    

                      $scope.valida5=true;



                  });

                  $http.get('api/api.php?funcion=datosIndicacionesRec&fol='+$rootScope.folio).success(function (data){                                                              

                      $scope.datos.indicaciones=data;  

                      $scope.cargador1=false;              

                      $scope.valida6=true;



                  });                                      

        }

    });

  $scope.irHistoriaClinica = function(folio){  

    $cookies.folio = folio;

        $location.path("/historiaClinica");

  }
  
  $scope.irHistoriaClinicaMovil = function(folio){  

    $cookies.folio = folio;

        $location.path("/historiaClinicaMovil");

  }

  $scope.irSignosVitales = function(folio){  

    $cookies.folio = folio;

        $location.path("/signosVitales");

  }

    $scope.irSignosVitalesSub = function(folio){  

        $cookies.folio = folio;

        $location.path("/signosVitalesSub");

    }

  $scope.irNotaMedica = function(folio){  

    $('#desclub').modal('hide');

    $cookies.folio = folio;

        $location.path("/notaMedica");

  }
  
  $scope.irNotaMedicaMovil = function(folio){  

    $('#desclub').modal('hide');

    $cookies.folio = folio;

        $location.path("/notaMedicaMovil");

  }
 

  $scope.irSubsecuencia = function(folio){      

    $cookies.folio = folio;

        $location.path("/subsecuencia");

  }

    $scope.irSubsecuenciaListado = function(folio){        

        $cookies.folio = folio;

        $location.path("/subsecuenciaListado");

    }

    $scope.irSolRehab = function(folio){          

        $cookies.folio = folio;

        $location.path("/rehabilitacion");

    }

     $scope.irSolRehabForm = function(folio){          

        $cookies.folio = folio;

        $location.path("/rehabilitacionForm");

    }

  $scope.irSolicitud = function(folio){  

    $cookies.folio = folio;

        $location.path("/solicitud/"+$rootScope.folio);

  }

    $scope.irReciboPar = function(folio){  

        $cookies.folio = folio;

        $location.path("/reciboParticular");

    }

    $scope.irSolFact = function(folio){  

        $cookies.folio = folio;

        $location.path("/solicitudFactura");

    }

    $scope.irDigitalizacion = function(folio){  

        $cookies.folio = folio;

        $location.path("/digitalizacion");

    }

     $scope.irCambioUnidad = function(folio){  

        $cookies.folio = folio;

        $location.path("/cambioUnidad");

    }

     $scope.irConsatancia = function(folio){  

        $cookies.folio = folio;

        $location.path("/constancia");

    }

    $scope.irInformeRehab = function(folio){          

        $cookies.folio = folio;

        $location.path("/informeRehabilitacion");

    }

    $scope.irListaInfRehab = function(folio){          

        $cookies.folio = folio;

        $location.path("/listaInfRehab");

    }

    $scope.irRayosX = function(folio){  

        $cookies.folio = folio;

        $location.path("/rayosX");

    }

    $scope.irSolPlantillas = function(folio){  

        $cookies.folio = folio;

        $location.path("/solicitudPlantillas");

    }



    $scope.irRecetaComplemtaria = function(folio){  

      $cookies.folio = folio;

          $location.path("/recetaComplementaria");

    }

    $scope.irRecetaMovil = function(folio){  

      $cookies.folio = folio;

          $location.path("/recetaMovil");

    }


    $scope.irNotaSoapRH = function(folio){  

      $cookies.folio = folio;

          $location.path("/notaSoapRH");         
    }

    $scope.irRecetaCortaEstancia = function(folio){  

      $cookies.folio = folio;

          $location.path("/recetaCortaEstancia");
          $('#verDocsCE').modal('hide');
    }


    $scope.irNotaSoapCortaEst = function(folio){  

        $cookies.folio = folio;

        $location.path("/notaSoapCE");

        $('#verDocsCE').modal('hide');

    }

    $scope.irRecetaParticulares = function(folio){  

      $cookies.folio = folio;

          $location.path("/recetaParticulares");
    }

    $scope.irNotaSoap = function(){  

        

         var hoy = new Date();

        var dd = hoy.getDate();

        var mm = hoy.getMonth()+1; //hoy es 0!

        var yyyy = hoy.getFullYear();



        if(dd<10) {

            dd='0'+dd

        } 



        if(mm<10) {

            mm='0'+mm

        } 

        console.log(dd);

        hoy = dd.toString()+mm.toString()+yyyy.toString();



        

        if(hoy==$scope.valAccNotSoap){

          $scope.msjError=false;

          $('#confirmarSoap').modal('hide');

          $location.path("/notaSoap"); 

        }else{

          $scope.msjError=true;

        }                

    }



     $scope.VerNotaSOAP = function(){  
      console.log($scope.proClave);
       busquedas.validaParticular($rootScope.folio).success(function(data){                
        if(data.Pro_clave==10){
             $location.path("/notaSoap"); 
        }else{
          $('#confirmarSoap').modal('show'); 
          $http.get('api/notaSoap.php?funcion=notasSOAP&fol='+$scope.folio).success(function (data){                                                              
              if(data.length==0){
                $scope.listadoNotSOAP='';  
              }else{
                $scope.listadoNotSOAP=data;
              }
              console.log($scope.listadoNotSOAP);
          }); 
        }
      });
    }


    $scope.guardarComentario = function(){  
      $http.get('api/detallePx.php?funcion=setComentario&fol='+$rootScope.folio+'&comentarios='+$scope.comentario+'&usr='+$rootScope.usrLogin).success(function (data){     

        console.log(data.length);
        if(data.length>0){
          $scope.contador = data.length;
          $scope.listadoComentarios = data;
          $scope.verComentario = true;
          $scope.comentario='';
        }else{
          $scope.verComentario = false;
          $scope.comentario='';
        }       
      });
    }


    $scope.confirmarEnvio = function(){  
       $("#docsEnviar").modal();
    }


    $scope.enviarCorreo = function(){
      $scope.cargardoModal = 'csspinner sphere';
      $scope.juntos = [$scope.datosEnvio, $scope.ejercicios];
          $http({
          url:'api/movil.php?funcion=envioCorreoDocs&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.juntos
          }).success( function (data){                        
            $scope.cargardoModal = '';
            $scope.enviado=true;
            console.log(data);

          }).error( function (xhr,status,data){
              $scope.cargardoModal = '';
              alert('Error');
          });            
    }

   



  $scope.imprimirNota = function(folio){ 

          $scope.cargador=true;   

          $scope.addImpreso=1;

          $scope.validaDoc1=0;



            if($scope.ValidaInventario==1){

              $scope.validaDoc1=$scope.verificaArchivo();              

              console.log($scope.validaDoc1);

              if($scope.validaDoc1==1){

                 $http.get('api/notaMedica.php?funcion=checaAddendum&fol='+$scope.folio).success(function (data){

                     $scope.addImpreso=data;                     

                     if($scope.addImpreso==0){

                        $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/NM_'+$rootScope.folio+'.pdf'; 

                        var fileName = "NM_"+$rootScope.folio+'.pdf';

                        var uri = $scope.url;                        

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

                     }else{

                        $scope.url= 'api/classes/formatoNota1.php?fol='+folio+'&vit=1'; 

                        var fileName = "NM_"+$rootScope.folio+'.pdf';

                        var uri = $scope.url;                        

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

              }else

              {

                $scope.url= 'api/classes/formatoNota1.php?fol='+folio+'&vit=1';

                 var fileName = "NM_"+$rootScope.folio+'.pdf';

                        var uri = $scope.url;                        

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

            }else{

              $scope.validaDoc1=verificaArchivo();

              if($scope.validaDoc1==1){

                 $http.get('api/notaMedica.php?funcion=checaAddendum&fol='+$scope.folio).success(function (data){

                     $scope.addImpreso=data;

                     if($scope.addImpreso==0){

                        $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/NM_'+$rootScope.folio+'.pdf';

                        var fileName = "NM_"+$rootScope.folio+'.pdf';

                        var uri = $scope.url;                        

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

                     }else{

                        $scope.url= 'api/classes/formatoNota1.php?fol='+folio+'&vit=1'; 

                        var fileName = "NM_"+$rootScope.folio+'.pdf';

                        var uri = $scope.url;                        

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

              }else{

                $scope.medico.nombre = localStorage.getItem("medicoSuplente");
                $scope.medico.cedula = localStorage.getItem("cedulaMedicoSuplente");

                $scope.url='api/classes/formatoNota.php?fol='+folio+'&vit=1'+'&medSup='+$scope.medico.nombre+'&cedSup='+$scope.medico.cedula;

                var fileName = "NM_"+$rootScope.folio+'.pdf';

                var uri = $scope.url;                

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



            }        



        }



//validar si existe el archivo

        $scope.verificaArchivo = function(){

              var http = new XMLHttpRequest();

              http.open('HEAD', '../registro/DigitalesSistema/'+$rootScope.folio+'/NM_'+$rootScope.folio+'.pdf', false);

              http.send();

              if(http.status!=404){

                return 1;

              }else{

                return 0;

              }

            }







      $scope.imprimirRecetaNuevo = function(folio){                

            var fileName = 'Receta';

            if($scope.ValidaInventario==1){

              $scope.validaDoc=$scope.verificaArchivoRec();

              if($scope.validaDoc==1){

                $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/Receta_'+$rootScope.folio+'.pdf';

              }else{

                $scope.url= 'api/classes/formatoRecetaNuevo1.php?fol='+$rootScope.folio+'&vit=1&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin;

              }

            }else{

              $scope.validaDoc=$scope.verificaArchivoRec();

              if($scope.validaDoc==1){

                $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/Receta_'+$rootScope.folio+'.pdf';

              }else{

                $scope.url='api/classes/formatoRecetaNuevo.php?fol='+$rootScope.folio+'&vit=1&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin;

              }

            } 

            var uri = $scope.url;            

            var fileName = "Receta_"+$rootScope.folio+'.pdf';

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



      $scope.verificaArchivoRec = function(){

              var http = new XMLHttpRequest();

              http.open('HEAD', '../registro/DigitalesSistema/'+$rootScope.folio+'/Receta_'+$rootScope.folio+'.pdf', false);

              http.send();

              if(http.status!=404){

                return 1;

              }else{

                return 0;

              }

            }

    $scope.imprimirReceta = function(folio){

          /*$rootScope.cargador=true;          

            var fileName = "Reporte";

            var uri = 'api/classes/formatoReceta.php?fol='+folio+'&usr='+$rootScope.usrLogin;

            var link = document.createElement("a");    

            link.href = uri;

            

            //set the visibility hidden so it will not effect on your web-layout

            link.style = "visibility:hidden";

            link.download = fileName + ".pdf";

            

            //this part will append the anchor tag and remove it after automatic click

            document.body.appendChild(link);

            link.click();

            $rootScope.cargador=false;

            document.body.removeChild(link);*/

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



        }



        $scope.imprimirRehabilitacion = function(){

        var fileName = "Reporte";

        var uri = 'api/classes/formatoRehabilitacion.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;

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



      $scope.imprimirRecetasComplementarias = function(){

        $('#imprimeRecetasComplementarias').modal('show'); 
        
      }


      $scope.imprimirRehabilitacion = function(){

        $('#imprimeRehab').modal('show'); 

        $http.get('api/catalogos.php?funcion=getRehabilitaciones&fol='+$scope.folio).success(function (data){  

          $scope.listadoRehabilitacion=data;                           

        });          

      }

      $scope.imprimirRecetaComplementria = function(noReceta){
            $scope.cargador=true;                
            $scope.url='api/classes/formatoRecetaComplementariaListado.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&noReceta='+noReceta;                
            var fileName = 'RSub_'+cont+'_'+$rootScope.folio+'.pdf';
            var uri = $scope.url;
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


      $scope.imprimirRecetasParticulares = function(){

        $('#imprimeRecetasParticulares').modal('show'); 
        
      }

      $scope.imprimirRecetaComplementriaParticulares = function(noReceta, tipoReceta){
        console.log(noReceta+' '+tipoReceta);
            $scope.cargador=true;                
            $scope.url='api/classes/formatoRecetaParticularesListado.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&idReceta='+noReceta+'&tipo='+tipoReceta+'&uni='+$rootScope.uniClave;
            var fileName = 'Receta'+tipoReceta+'-'+noReceta+'_'+$rootScope.folio+'.pdf';
            var uri = $scope.url;
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



      $scope.reImprimirRehabilitacion = function(cont,folio){

        var fileName = "Rehabiltiacion";

        if($rootScope.usrLogin=="algo"){
          //var uri = 'api/classes/formatoEspecificoRehabilitacionReimpresion2.php?fol='+folio+'&cont='+cont;
          var uri = 'api/classes/formatoRehabilitacionReimpresion.php?fol='+folio+'&cont='+cont;
        }else{
          var uri = 'api/classes/formatoRehabilitacionReimpresion.php?fol='+folio+'&cont='+cont;
        }
        

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

      

      $scope.imprimirPaseReha = function(){

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



  }

    $scope.imprimirHistoriaClinica = function(){            

            var fileName = "Reporte";

            var uri = 'api/classes/FormatoH.php?fol='+$rootScope.folio;

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





     $scope.imprimirSignosVitales = function(){            

            var fileName = "Reporte";

            var uri = 'api/classes/formatoVitales.php?fol='+$rootScope.folio;

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



        $scope.imprimirSignosVitalesSub = function(){ 

             busquedas.validaSubsec($rootScope.folio).success(function(data){                                      

                if(data.Cons==null){

                  data.Cons=1;

                }

                $scope.noSubsec=data.Cons;    

              });           

            var fileName = "Reporte";

            var uri = 'api/classes/formatoVitalesSub.php?fol='+$rootScope.folio+'&sub='+$scope.noSubsec;

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



         $scope.imprimirConstancia = function(){            

            var fileName = "Reporte";

            var uri = 'api/classes/formatoCia.php?fol='+$rootScope.folio;

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





         $scope.irDocumentos = function(){         

              $location.path("/documentos");          

        }

        $scope.regresaBusqueda = function(){         
          
          $('#medico').modal('hide');      
          $location.path("/busqueda");  
          


    }



        $scope.guardaAddendum = function(){          

           if($scope.agreAddendum.$valid){

            $scope.cargador1=true; 

            console.log($scope.addendum);                                

            $http({

                    url:'api/api1.php?funcion=agregarAddendum&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.addendum

                    }).success( function (data){  

                    console.log(data);                   

                     if(data==1){

                        $scope.cargador1=false; 

                        $scope.mensajeExito=true;

                        $scope.addendum={

                            cuerpo:''

                        }

                        $scope.agreAddendum.$submitted=false;           

                     }

                      

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    });

          }

        }



        /****************************************** nota interna ***************************************/

        /***********************************************************************************************/



          $scope.guardarNotaInterna = function(){                              

            $scope.cargador1=true;                                 

            $http({

                    url:'api/estatusMedico.php?funcion=notaInterna&fol='+$scope.folio+'&comentario='+$scope.notaIntComentario+'&usr='+$scope.usrLogin,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.addendum

                    }).success( function (data){

                      console.log(data);                                

                     if(data.respuesta=='exito'){

                        $scope.cargador1=false; 

                        $scope.msjNotInternaExito=true; 

                        $scope.notaIntComentario='';                                                       

                     }                    

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    }); 

          }

          /****************************************** guardar medico suplente en variables locales ***************************************/

        /***********************************************************************************************/       

          $scope.guardarMedicoSuplente = function(){       
            
            $('#medico').modal('hide'); 

            localStorage.setItem("medicoSuplente", $scope.medico.nombre);
            localStorage.setItem("cedulaMedicoSuplente", $scope.medico.cedula);
            

          }



        /****************************************** nota interna ***************************************/

        /***********************************************************************************************/       



         $scope.actualizaFactura = function(){                              

            $scope.cargador1=true;                                 

            $http({

                    url:'api/api1.php?funcion=actualizaFactura&fol='+$rootScope.folio+'&folFact='+$scope.validaFacturaVal,

                    method:'POST', 

                    contentType: 'application/json', 

                    dataType: "json", 

                    data: $scope.addendum

                    }).success( function (data){                                

                     if(data=='exito'){

                        $scope.cargador1=false; 

                        $scope.mensajeExito=true; 

                        $scope.validaFacturaVal='';                                                       

                     }                    

                    }).error( function (xhr,status,data){

                        $scope.mensaje ='no entra';            

                        alert('Error');

                    }); 

        }



        $scope.listaDetalle = function(){             

            

////////////////////////////////////////////////////////////////////

                  $http.get('api/detallePx.php?funcion=getMedico&fol='+$rootScope.folio).success(function (data){                     

                        console.log(data);

                        if(data=='false'){

                          $scope.detalle.medicoAtn='--';

                        }else{

                          $scope.detalle.medicoAtn=data.Medico;

                        }

                  }); 

                  $http.get('api/detallePx.php?funcion=getDiagActual&fol='+$rootScope.folio).success(function (data){                     

                        console.log(data);

                        if(data=='false'){

                          $scope.detalle.diagnosticoActual='--';

                        }else{

                          $scope.detalle.diagnosticoActual=data.ObsNot_diagnosticoMomento;

                        }

                  }); 

////////////////////////////////////////////////////////////////////

                  $http.get('api/detallePx.php?funcion=getAutorizacion&fol='+$rootScope.folio).success(function (data){                     

                        console.log(data);

                        if(data=='false'){

                          $scope.autorizacion='--';

                        }else{

                          $scope.autorizacion=data.AUM_clave;

                        }

                  }); 

                  $http.get('api/detallePx.php?funcion=getSignosVitales&fol='+$rootScope.folio).success(function (data){                     

                        //console.log(data);

                        if(data==''){

                          $scope.conSigVit='';

                        }else{

                          $scope.conSigVit=data;

                        }

                  }); 

                  $http.get('api/detallePx.php?funcion=getHistoriaClinica&fol='+$rootScope.folio).success(function (data){                     

                        //console.log(data);

                        if(data=='false'){

                          $scope.estatusHistoria='No se ha agregado';

                          $scope.fechaHist = '--';

                        }else{

                          $scope.estatusHistoria='Completa';

                          $scope.fechaHist = data.fecha;

                        }

                  }); 

                  $http.get('api/detallePx.php?funcion=getNotaMedica&fol='+$rootScope.folio).success(function (data){                     

                       // console.log(data);

                        if(data=='false'){

                          $scope.estatusNota='No se ha agregado';

                          $scope.fechaNota = '--';

                        }                        

                        else{

                          if(data.estatus>0 && data.estatus<6){

                            $scope.estatusNota='Incompleta - estatus ('+data.estatus+')';

                            $scope.fechaNota = '--';

                          }else{

                            $scope.estatusNota='Completa';

                            $scope.fechaNota = data.fecha; 

                          }

                          

                        }

                  }); 

                  $http.get('api/detallePx.php?funcion=getSubsecuencias&fol='+$rootScope.folio).success(function (data){ 

                  //console.log(data);                    

                       if(data==false){

                          $scope.msjSubsecuencia='No se han agregado subsecuencias.';

                       }else{

                          $scope.listSubsecuencias=data;

                       }

                        

                  }); 

                  $http.get('api/detallePx.php?funcion=getRehabilitaciones&fol='+$rootScope.folio).success(function (data){ 

                  //console.log(data);                    

                       if(data==''){

                          $scope.msjRehab='No se han agregado rehabilitaciones.';

                       }else{

                          $scope.listRehabilitaciones=data;

                       }                      

                  });

                  /******************** documentos digitalizados *********************************************/

                  $http.get('api/detallePx.php?funcion=getAviso&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeAviso=false;

                        }else{

                          $scope.existeAviso=true;

                        }                                                          

                  }); 



                   $http.get('api/detallePx.php?funcion=getConsMed&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeConsMed=false;

                        }else{

                          $scope.existeConsMed=true;

                        }                                                          

                  }); 

                    $http.get('api/detallePx.php?funcion=getCuestionario&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeCuestionario=false;

                        }else{

                          $scope.existeCuestionario=true;

                        }                                                          

                  }); 

                     $http.get('api/detallePx.php?funcion=getFiniquito&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeFiniquito=false;

                        }else{

                          $scope.existeFiniquito=true;

                        }                                                          

                  }); 

                      $http.get('api/detallePx.php?funcion=getHistoria&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeHistoria=false;

                        }else{

                          $scope.existeHistoria=true;

                        }                                                          

                  }); 

                       $http.get('api/detallePx.php?funcion=getIdentificacion&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeId=false;

                        }else{

                          $scope.existeId=true;

                        }                                                          

                  }); 

                        $http.get('api/detallePx.php?funcion=getInfMedico&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeInfMedico=false;

                        }else{

                          $scope.existeInfMedico=true;

                        }                                                          

                  }); 

                         $http.get('api/detallePx.php?funcion=getInfAseg&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existeInfAse=false;

                        }else{

                          $scope.existeInfAse=true;

                        }                                                          

                  }); 



                   $http.get('api/detallePx.php?funcion=getPaseMedico&fol='+$rootScope.folio).success(function (data){                   

                        if(data.contador==0){

                          $scope.existePase=false;

                        }else{

                          $scope.existePase=true;

                        }                                                          

                  }); 

                   $http.get('api/detallePx.php?funcion=getDiagnostico&fol='+$rootScope.folio).success(function (data){ 

                        console.log(data);                  

                        if(data=='error'){

                          $scope.diagnostico='--';

                        }else{

                          $scope.diagnostico=data.ObsNot_diagnosticoRx;

                        }                                                          

                  }); 



                  /******************  fin de documentos digitalizado ****************************************/



    }





        $scope.validaDoc = function(){         

            if($scope.addendum.doc==4){       

              $http.get('api/api1.php?funcion=buscaSubs&fol='+$rootScope.folio).success(function (data){

                if(data>0){

                  $scope.divSubsecuencia=true;

                  var range = [];

                  var contador = parseInt(data);

                  for(var i=1;i<contador+1;i++) {

                    range.push(i);

                  }

                  $scope.range = range;

                  console.log(data);                             

                }else{

                  $scope.divSubsecuencia=false;

                }

              });

          }else{

            $scope.divSubsecuencia=false;

          }

        }





        /************************************************   validación para imprmiir formatos de corta estancia *************************/



        $scope.verificaSolicitudCE = function(){ 
            $http.get('api/cortaEstancia.php?funcion=getSolicitud&fol='+$rootScope.folio).success(function (data){ 
            console.log(data);                                                                               
                   if(data.contador==0){
                      $('#SolicitarCortaEstancia').modal('show');   
                   }else if(data.contador>=1){
                      $http.get('api/cortaEstancia.php?funcion=getcheckValida&fol='+$rootScope.folio).success(function (data){ 
                        $('#verDocsCE').modal('show');
                        if(data.contadorUsado>=1){
                          $scope.verDocumentosCE=true;
                        }else{
                          $scope.verDocumentosCE=false;
                        }                                                  
                      });                       
                   }           
            });                
        }

        $scope.verFormularioSolicitud = function(){ 

            $scope.verSolicitudCE=true;

        }

        $scope.solicitarFormatos = function(){          
          $scope.cargador1=true;
           $http({
                url:'api/cortaEstancia.php?funcion=setSolicitud&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.solicitudCE
                }).success( function (data){
                console.log(data);                             
                $scope.cargador1=false;
                if(data.respuesta=='exito'){
                  $scope.mensajeExito=false;
                  $('#SolicitarCortaEstancia').modal('hide'); 
                  $('#verDocsCE').modal('show'); 
                   $scope.verDocumentosCE=true;         
                }else{
                  $scope.mensajeExito=false;
                }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
        }



        $scope.validaSolicitudCE = function(){          

          $scope.cargador1=true;

          $http.get('api/cortaEstancia.php?funcion=validaAut&fol='+$rootScope.folio+'&aut='+$scope.solicutudValida).success(function (data){

            console.log(data);

            $scope.cargador1=false;

             if(data.respuesta=='error'){

                $scope.mensajeExito= true;

                $scope.solicutudValida=''; 

             }else if(data.respuesta=='exito'){

                $scope.mensajeExito= false;

                $scope.verDocumentosCE=true; 

                $scope.existeAutCE=false;

             }

          });

           

        }





        $scope.generarMembresia = function(){                    

          

          var fecha = new Date();

          var anio = fecha.getFullYear();

          $http.get('api/convenio.php?funcion=setMembresia&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave).success(function (data){  
             var d = new Date();
                var month = new Array();
                month[0] = "Enero";
                month[1] = "Febrero";
                month[2] = "Marzo";
                month[3] = "Abril";
                month[4] = "Mayo";
                month[5] = "Junio";
                month[6] = "Julio";
                month[7] = "Agosto";
                month[8] = "Septiembre";
                month[9] = "Octubre";
                month[10] = "Noviembre";
                month[11] = "Diciembre";
                var n = month[d.getMonth()];                      

            $scope.btonGenerarMem=false;

            $scope.membresiaCreada = data;

            $scope.membresiaCreada.mem_vigencia=parseInt(anio)+1;

          });

           

        }





        /**************************************************fin formatos corta estancia **************************************************/



/************************************************************************************************************/

/************************************************************************************************************/

/**************************************  crear Membresía   **************************************************/

/*************************************                      *************************************************/

/************************************************************************************************************/



   

    



    var cont=1;

    $scope.veTabla=false;

    $scope.agregaParentezco = function(){



        if($scope.referenciasForm.$valid){

        console.log('entro');  

        if($scope.referencia.nombre!=''){             

            $scope.msjTel=false;

            $scope.cont ={};           

            if($scope.sumaReferencia==undefined){

               $scope.veTabla=false; 

            }else{

                $scope.veTabla=true; 

            }          

            $scope.cont.nombre=$scope.referencia.nombre;

            $scope.cont.email=$scope.referencia.email;

            $scope.cont.telefono=$scope.referencia.telefono;

            $scope.cont.parentezco=$scope.referencia.parentezco; 

            $scope.cont.cont=cont;            

            $scope.sumaReferencia.push($scope.cont); 

            $scope.referencia={

                nombre:'',

                email:'',

                parentezco:''

            }    

            $scope.referenciasForm.$submitted=false;              

            cont++; 

            

        }else{

            $scope.msjTel=true;

        }

    }

    }



    $scope.eliminaParentezco = function(contaTel){  

        console.log('entró');

        contaTel=contaTel-1;

        delete $scope.sumaReferencia[contaTel]; 

        $scope.sumaReferencia.splice(contaTel,1);               

        console.log($scope.sumaReferencia);

    } 



    $scope.codigoE=false;  

    $scope.verificaCodigo = function(){                         

            if($scope.membresia.codPos!='00000'){

            $http.get('api/api.php?funcion=codExiste&cod='+$scope.membresia.codPos).success(function (data){                

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







    $scope.mensajeMembresia=false;

    $scope.crearMembresia = function(){ 

        console.log($scope.membresiaFormDoc.$valid);

    $scope.validar=false;

    if($scope.membresiaFormDoc.$valid){

        console.log('hola1');

        $scope.cargadorMem=true; 

        $scope.mensaje = '';       

        if($scope.sumaReferencia!=''){

           $scope.todo= [$scope.membresia,$scope.sumaReferencia];

        }else{

            $scope.todo=[$scope.membresia];

        }          

        /*$http.get('api/convenio.php?funcion=setMembresiaSinFol&uni='+$scope.uniClave+'&nombre='+$scope.nombreMembresia).success(function (data){                        

            console.log(data);

            $scope.cargadorMem=false;    

            $scope.verForm= false;          

            $scope.membresiaCreada = data;

        }); */ 



        $http({

            url:'api/convenio.php?funcion=guardarMembresiaFolio&usr='+$cookies.usrLogin+'&uni='+$scope.uniClave+'&fol='+$scope.folio,

            method:'POST', 

            contentType: 'application/json', 

            dataType: "json", 

            data: $scope.todo

            }).success( function (data){ 

                console.log(data);   

                var d = new Date();
                var month = new Array();
                month[0] = "Enero";
                month[1] = "Febrero";
                month[2] = "Marzo";
                month[3] = "Abril";
                month[4] = "Mayo";
                month[5] = "Junio";
                month[6] = "Julio";
                month[7] = "Agosto";
                month[8] = "Septiembre";
                month[9] = "Octubre";
                month[10] = "Noviembre";
                month[11] = "Diciembre";
                var n = month[d.getMonth()];               

                $scope.cargadorMem=false;              

                $scope.mensajeMembresia=true;

                $scope.nombreMem    = data.mem_completo;

                $scope.folioMem     = data.mem_folio;

                $scope.serieMem     = data.mem_serie;

                $scope.anioMem      = parseInt(data.mem_anio)+1;
                $scope.mesMem       = n;




            }).error( function (xhr,status,data){

                $scope.mensaje ='no entra';            

                alert('Error');

            }); 

        }           

    } 



    $scope.reactivarFormulario = function(){  

        $scope.mensajeMembresia=false;

         $scope.incidencias={

            tipo:'',

            severidad:'',

            observaciones:'',

            archivo:'',

            temporal:''

        }

        $scope.membresia={

            nombre:'',

            apanterno:'',

            amaterno:'',

            email:'',

            telefono:'',

            codPos:'',

            obs:''

        }    

    } 



$scope.verCrearMembresia = function(){

    $http.get('api/convenio.php?funcion=verDatosMembresia&fol='+$scope.folio).success(function (data){                



        console.log(data);

        $('#crearMembresia').modal('show');

        $scope.membresia.nombre=data.Exp_nombre;

        $scope.membresia.apanterno=data.Exp_paterno;

        $scope.membresia.amaterno=data.Exp_materno;

        $scope.membresia.email=data.Exp_mail;

        $scope.tel = data.Exp_telefono.split("-");        

        $scope.membresia.telefono=$scope.tel[1];

        $scope.membresia.codPos=data.Exp_codPostal;



    });  

}


$scope.mandaraBusqueda = function(){
  $('#extemporaneo').modal('hide');   
   $scope.atender=true;
}

$scope.aceptarExtemporaneo = function(){  
  $http.get('api/detallePx.php?funcion=mandarCorreoextemporaneo&fol='+$rootScope.folio+'&dias='+$scope.dias+'&tipo='+$scope.tipoMod+'&usr='+$rootScope.usrLogin).success(function (data){        
    console.log(data);
    $('#extemporaneo').modal('hide');      
  });  
  
}


$scope.cerraModalExtermporaneo = function(){

  $('#extemporaneoMedico').modal('hide'); 
  $scope.atender=true;
  if($rootScope.usrLogin=="algo" ||$rootScope.usrLogin=="calvarez" ||$rootScope.usrLogin=="calvarez_r"){
    $scope.atender=false;
  }
}

$scope.catItemsCarrito = function(){

  $http.get('api/detallePx.php?funcion=catItmesCarrito&fol='+$rootScope.folio).success(function (data){
    console.log(data);        
    $scope.catItemsCarrito = data.catalogo;
    $scope.listadoCarrito = data.itemsCarrito;    
  });  
 
}

$scope.agregarItem = function(id){

  $http.get('api/detallePx.php?funcion=agregaItem&fol='+$rootScope.folio+'&cveItem='+id+'&usr='+$rootScope.usrLogin).success(function (data){        
    console.log(data);
    $scope.listadoCarrito = data;    
    $http.get('api/detallePx.php?funcion=getCarrito&fol='+$rootScope.folio).success(function (data){                       
      console.log(data);
      $scope.contadorItemsCarrito = data;
  });
  });  
 
}

$scope.cerrarRecibo = function(){
  $http.get('api/detallePx.php?funcion=cerrarRecibo&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin).success(function (data){        
    console.log(data);
    $scope.listadoCarrito = data;   
    if(data=='exito'){
        console.log('el recibo se cerró exitosamente');
    } else{

    }   
  });   
}

$scope.eliminarItemCartito = function(idItem){
  $http.get('api/detallePx.php?funcion=eliminarItemCarrito&fol='+$rootScope.folio+'&idItem='+idItem).success(function (data){        
    console.log(data);
    if(data=='"error"'){
      $scope.listadoCarrito = '';       
      $scope.contadorItemsCarrito = '';
    }else{
      $scope.listadoCarrito = data;
      $http.get('api/detallePx.php?funcion=getCarrito&fol='+$rootScope.folio).success(function (data){                       
        console.log(data);
        $scope.contadorItemsCarrito = data;
    });
            
    }
    
  });   
}




// //validar si existe el archivo

// $scope.verificaArchivo = function(){

//   var http = new XMLHttpRequest();

//   http.open('HEAD', '../registro/DigitalesSistema/'+$rootScope.folio+'/NM_'+$rootScope.folio+'.pdf', false);

//   http.send();

//   alert(http.status);

// }





/************************************************************************************************************/

/***************************************                   ***************************************************/

/************************************** fin  crear Membresía**************************************************/

/*************************************                      *************************************************/

/************************************************************************************************************/







        $scope.imprimirDetalle = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoDetalle.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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



        $scope.imprimeHojaEvolucion = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoEvolucion.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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



         $scope.imprimeHojaIndicaciones = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoIndicaciones.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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



        $scope.imprimeTraslado = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoTraslado.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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



        $scope.imprimeAltaCE = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoAltaCE.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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



        $scope.imprimeConsentimiento = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoConsentimiento.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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



         $scope.imprimirReciboDeducible = function(folio){ 

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/reciboDeducible.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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

    $scope.descargaRegClinico = function(folio){  

          $scope.cargador=true;        

            var fileName = "HE_"+folio;

            var uri = 'api/classes/formatoRegClinicoCE.pdf';

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

    $scope.descargaSolEstudios = function(folio){  

          $scope.cargador=true;        

            var fileName = "Solicitud de Estudios";

            var uri = 'api/classes/formatoSolEstudiosCE.pdf';

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

    $scope.descargaConsImagenes = function(folio){  

          $scope.cargador=true;        

            var fileName = "Reporte";

            var uri = 'api/classes/formatoConsentimientoFotografico.php?fol='+folio+'&usr='+$rootScope.usrLogin;

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





    $scope.printHCR = function(folio){

            var fileName = "error";

            var uri = 'api/classes/formatoHistoriaRehab.php?fol='+folio+'&usr='+$rootScope.usrLogin;

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

});