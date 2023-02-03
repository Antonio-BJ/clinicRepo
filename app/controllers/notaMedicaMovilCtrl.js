app.controller('notaMedicaMovilCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http,$q,webStorage,$upload) {	  
  // $rootScope.folio=$cookies.folio;	
  $rootScope.folio= webStorage.session.get('folio'); 
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
  $scope.msjcorreoEnviado=''; 
  $scope.msjmsjEnviado='';
  $scope.msjEnvioDocs=''; 
  $scope.mensajeFinalizado =false;
  $scope.listRefEjerciciosPx='';
  $scope.blanco=false;
  $scope.terminar=true;
  $scope.total ={
                check : false
              }

  $scope.telEnviar='';

  $scope.modoEnvio ={
    telefono : '',
    mail :''
  }
  $scope.correoEnviarOtro='';

  $scope.msjRecetaCreada =false;
  $scope.msjPaseCreado = false;
  $scope.rx={
    archivo:'',
    temporal:'',
    noPlac:1,
    inter:''
  };
  $scope.envio ={
    correo:'',
    msm:'',
    whatsapp:'',
    correoElegido:'',
    telefonoElegido:'',
  }
  $scope.msjHc2=false;
  $scope.msjHc=false;

  $('body, html').animate({
    scrollTop: '0px'
  }, 300);

  $(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
      $('.ir-arriba').slideDown(300);
    } else {
      $('.ir-arriba').slideUp(300);
    }
  });

 
  $scope.verDocsReque=false;
  // $('#exampleModal').modal();
  $scope.ejercicios = [
    {ruta:'imgs/pdf.png',nombre:'Cadera y Rodilla',nom:'DRM_Eje_caderaRodilla',ubicacion:'ejercicios/Cadera_Rodilla.pdf', variable:false},
    {ruta:'imgs/pdf.png',nombre:'Higiene de Columna',nom:'DRM_Eje_Hcolumna',ubicacion:'ejercicios/Higiene_Columna.pdf', variable:false},
    {ruta:'imgs/pdf.png',nombre:'Columna Cervical',nom:'DRM_Eje_CVertebral',ubicacion:'ejercicios/Columna_Cervical.pdf', variable:false},
    {ruta:'imgs/pdf.png',nombre:'Hombro',nom:'DRM_Eje_hombro',ubicacion:'ejercicios/Hombro.pdf', variable:false},
    {ruta:'imgs/pdf.png',nombre:'Codo, Mano , y Muñeca',nom:'DRM_Eje_cmm',ubicacion:'ejercicios/Codo_Mano_Muneca.pdf', variable:false},
    {ruta:'imgs/pdf.png',nombre:'Tobillo y Pie',nom:'DRM_Eje_TP',ubicacion:'ejercicios/Tobillo_Pie.pdf', variable:false},
    {ruta:'imgs/pdf.png',nombre:'Columna Dorsolumbar',nom:'DRM_Eje_CDorsolumbar',ubicacion:'ejercicios/Columna_Dorsolumbar.pdf', variable:false}
];
  $scope.verListaSumAlter=true;
  $scope.verListaOrtAlter=true;
  $scope.opcionPronostico='';
  $scope.msjPalabraProhi=false;
  $scope.validaPalabra='';
  $scope.medico={
    nombre:'',
    cedula:''
}

$scope.verResultado=false;

$scope.validaDiagostico=false;
$scope.validaPronostico=false;
//$scope.validaKit=true;
$scope.validaReceta=false;
$scope.validaHojaIndicaciones=false;
$scope.validaPase=false;

/*************************************************************************para receta ************************************************ */
$scope.motivo='';
  $scope.cargador=true;
  $scope.claseSpinner="panel-body";
  $scope.spinner = '';
  $scope.spinnerModal = '';
  $scope.spinnerDiv = 'row';
  // $scope.trabajando=true;
  $scope.unidad ='';
  $scope.itemParticulares={
          clave:'',
          cantidad:0,
          indicaciones:'',
          stock:0,
          tipoItem:'',
          posologia: ''
        };
  $scope.itemRecetaExterna = {
    item: null,
    indicacion: null,
    idReceta:null
  }
  $scope.cuentaPx = {
    banco: null,
    clabe: null,
    tarjeta: null,
    beneficiario:null
  }

  $scope.hcRapida = {
    notas: null,
  }

  $scope.hcRapida2 = {
    notas2: null,
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
  $scope.ortesisSym={
    ortSymio:'',
    cantidad:1,
    indicaciones:'',
    stock:''
  }
  $scope.indicacion={
    indicacion:'',
    obs:''
  } 
$scope.traerMedicamentos = function (uni){
    $scope.med={
      sustAct:'',
      medicame:'',
      presentacion:'',
      cantidad:1,
      posologia:'',
      stock:''          
    }
    console.log(uni);
    $scope.unidad = uni;
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
    $scope.timeout = 10; //tiempo de espera de la consulta                                               
    $scope.status = 'Requesting';
    $scope.response = '';              
    var httpRequest = httpRequestHandler();   
    $scope.timeoutOrt = 10; //tiempo de espera de la consulta                                               
    $scope.status = 'Requesting';
    $scope.response = '';              
    var httpRequestOrt = httpRequestHandlerOrt();    
};

$scope.itemRecetaExterna = {
  item: null,
  indicacion: null,
  idReceta:null
}
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
var elmnt = document.getElementById("hastaArriba");
elmnt.scrollTop = 0;
$scope.cargardoModal = "panel-body";
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
  salPaq:false
} 
  $scope.msjPalabraProhiInd=false;
  $scope.validaPalabraInd='';
  $scope.verConsent = true;
  $scope.msjDiagnostico=false; 
  $scope.conteoDiag = 225;
  /********************  Nuevo sistema de inventarios **********************/
  $scope.cveUniInventario=1;
if($rootScope.uniClave==1||$rootScope.uniClave==3||$rootScope.uniClave==2||$rootScope.uniClave==184||$rootScope.uniClave==4||$rootScope.uniClave==86||$rootScope.uniClave==7||$rootScope.uniClave==5||$rootScope.uniClave==186||$rootScope.uniClave==6||$rootScope.uniClave==8){
    $scope.cveUniInventario=$rootScope.uniClave;  
  }
  if($scope.cveUniInventario==$rootScope.uniClave){
    $scope.ValidaInventario=1;
  }else{
    $scope.ValidaInventario=2;
  }
  /*************************************************************************/
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
        llega:"4",
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
          zona:'',
          tipo:[],
          interp:''
        }
        $scope.procedimientos={
          procedimiento:'',
          obs:''
        }
        $scope.diagnostico={
          //kit:'',
          receta:'',
          indicaciones:'',
          ejercicios:[],
          pase:'',
          //obsKit:'',
          recetaInterna:0,
          recetaExterna:0
        }

        $scope.diag={
          diagnostico:'',
          glasgow:15,
          obs:'',
          cron:'',
          pronostico:'',
          ambulancia:'',
          hospital:''
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
          clave:''
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
          example1model:[],
          lesionCodificada:''       
        }
        $scope.tipoRxSelect={};
        $scope.validaTipoRx=false;

        $scope.validacionDianostico='';
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
        $rootScope.cveProducto='';
        $scope.vertipoLesiones=true;
        $scope.noPersonales=true;
        $scope.solicitudCE={
          motivo:'',
          tiempo:''
        } 
        $scope.pase = {
          folio :  $rootScope.folio,
          motivo :'',
          diagnostico : '',
          unidad :'',
          correo :0,
          msj:0,
          usr : $rootScope.usrLogin
        }
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





        $http.get('api/detallePx.php?funcion=getDatosPersonalesMovil&fol='+$rootScope.folio).success(function (data){  

          console.log(data);                                                                                               

          $scope.detalle.nombre=data.datos.Exp_completo;

          if(data.datos.Exp_sexo=='F'){

            $scope.detalle.sexo ='FEMENINO';

          }else if(data.datos.Exp_sexo=='M'){

            $scope.detalle.sexo ='MASCULINO';

          }else{

            $scope.detalle.sexo= '--';  

          }

          if(data.datos.Pro_clave==2){

            $scope.reciboDeducible=true;

            console.log($scope.reciboDeducible);

          }    

          $scope.detalle.edad= data.datos.Exp_edad;

          $scope.detalle.producto= data.datos.Pro_nombre;

          $scope.detalle.fechaReg= data.datos.Exp_fecreg;

          $scope.detalle.aseguradora= data.datos.Cia_nombrecorto;
          if(data.datos.HA_telAdicional==''){
            $scope.detalle.telefono= data.datos.CA_telefono;
          }else{
            $scope.detalle.telefono= data.datos.CA_telefono;
          }

          $scope.telEnviar = $scope.detalle.telefono;

          $scope.detalle.mail= data.datos.Exp_mail;                      

          $scope.detalle.religion= data.datos.Rel_clave;

          $scope.detalle.fechaNacimiento= data.datos.Exp_fechaNac;

          $scope.detalle.triage  = data.datos.triage;

          $scope.detalle.hospitalario = data.datos.hospitalario;

          $scope.detalle.Cia_clave = data.datos.Cia_clave;

          $scope.detalle.Exp_triageActual = data.datos.Exp_triageActual; 

          $scope.detalle.diagnostico = data.datos.ObsNot_diagnosticoRx;              

          if(data.datos.Pro_clave==4 && $scope.detalle.Cia_clave==55 || $scope.detalle.Cia_clave==65 ){

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







        $http.get('api/catalogos.php?funcion=verProducto&fol='+$rootScope.folio).success(function (data){                               



              $rootScope.cveProducto=data; 

             

            if($rootScope.cveProducto==10){



              $scope.vertipoLesiones=false;

              $scope.siguienteMed=false;

              $scope.siguienteOrt=false;



              



            }if($rootScope.cveProducto==2){



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



        console.log($rootScope.cveProducto);



         busquedas.estatusNota($rootScope.folio).success(function(data){                                         



          $scope.estatusNot=String(data);      



          console.log(data);     

          

          if($scope.estatusNot=="0"|| $scope.estatusNot==''){         

            $scope.estatusNot=2; 

          }



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



                      busquedas.listaRxZonaTipo().success(function(data){

                       $scope.tipoRx= data.tipo;

                       $scope.zonaRX= data.zona;

                       console.log(data);

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



                         console.log(data);



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



                           $scope.timeout = 10; //tiempo de espera de la consulta                                               



                            $scope.status = 'Requesting';



                            $scope.response = '';                            



                            var httpRequest = httpRequestHandler();                                                                                                                                                  



                      /********************* fin prueba de tiempo de ejecusión   **************************/



                      



                      /******************** prueba de tiempo de ejecusión  *********************************/



                            $scope.timeoutOrt = 10; //tiempo de espera de la consulta                                               



                            $scope.status = 'Requesting';



                            $scope.response = '';                            



                            var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   



                      /********************* fin prueba de tiempo de ejecusión   **************************/



                      $http.get('api/api.php?funcion=listaAlergiasMovil&fol='+$rootScope.folio).success(function (data){                                                            



                          $scope.alergias = data;



                      });    







                      if($rootScope.uniClave==$scope.cveUniInventario){



                        $http.get('api/notaMedica.php?funcion=listadoItems&fol='+$rootScope.folio+'&tipo=1&tipoReceta=1').success(function (data){  console.log(data);                                                                                                                   



                            if(data=='"vacio"'){



                                $scope.listaMedicamentosSymio='';



                                $scope.siguienteMed=true;



                              }else{                                                                                    



                                if(data.length<1){



                                  $scope.listaMedicamentosSymio='';



                                  $scope.siguienteMed=true;



                                }else{



                                  $scope.listaMedicamentosSymio=data;                     



                                  $scope.siguienteMed=false;



                                }         



                            } 







                            console.log($rootScope.uniClave+'=='+$scope.cveUniInventario);



                        });      



                        $http.get('api/notaMedica.php?funcion=listadoItems&fol='+$rootScope.folio+'&tipo=2&tipoReceta=1').success(function (data){                                                                                                   



                          if(data=='"vacio"'){



                              $scope.listaOrtesisSymio='';



                              $scope.siguienteMed=true;



                            }else{      



                              if(data.length<1){



                                $scope.listaOrtesisSymio='';



                                $scope.siguienteOrt=true;  



                              }else{



                                $scope.listaOrtesisSymio=data;                     



                                $scope.siguienteOrt=false;          



                              }



                          }           



                          console.log(data);                 



                        });                             



                      }else{



                        busquedas.listadoMedAgregSymio($rootScope.folio).success(function(data){ 



                            console.log(data);                            



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



                              console.log($scope.listaOrtesisSymio);



                            }                        



                          });                                                



                      }                                                    







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



                  case "5":



                    $http.get('api/catalogos.php?funcion=lesionCodificadaLesion&opcion=L_LEVE').success(function (data){                                                                  



                      $scope.listadoLesionesModuloCodificado = data;

        

                      console.log(data);

        

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



                WizardHandler.wizard().goTo(String($scope.estatusNot));                     



          }







          



        });



       

        if($scope.rootScope==10){           

          $scope.siguienteMed=false;

          $scope.siguienteOrt=false;



        }

       







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



            if($scope.paso=='4'){



              if($scope.sexo=='M'){



                WizardHandler.wizard().goTo('2');



              }



            }else{



              WizardHandler.wizard().previous();



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



            

            console.log($scope.accidente);

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

                  busquedas.listaDiagnosticos().success(function(data){                      



                    $scope.cargador2=false;



                    $scope.listaDiagnostico=data;              



                  }); 



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



          console.log($scope.embarazo.semanas);



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



                  $scope.embarazo={



                      controlGine:'No',



                      semanas:0,



                      dolor:'No',



                      desc:'',



                      fcFet:'',



                      movFet:'No',



                      justif:''



                  }



                  $scope.formularios.embarazoForm.$submitted=false;



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

        busquedas.RefEjerciciosPx($rootScope.folio).success(function(data){
          $scope.listRefEjerciciosPx=data;
          console.log(data);
        });

        $scope.validaEjercicio =function(zonClave){
          //console.log(zonClave);
          busquedas.listaEjerciciosPx(zonClave).success(function(data){
            $scope.listaEjerciciosPx=data;
            console.log(data);
          }); 
        }

        $scope.agregarEjercicio=function(valor){
          $scope.accidente.pxRefiere='';
           $http.get('api/api.php?funcion=ImprimeEjercicios&clave='+valor).success(function (data){
            //console.log(data);
            console.log(data[0].Ejer_descripcion);
            $scope.accidente.pxRefiere=data[0].Ejer_descripcion+", EL PACIENTE REFIERE ";
            });
        }

        $scope.agregaInsumo=function(valor){
          $scope.itemRecetaExterna.item='';
          $scope.itemRecetaExterna.indicacion='';
          $scope.itemRecetaExterna.presentacion='';
          $http.get('api/api.php?funcion=ImprimeInsumo&clave='+valor).success(function (data){
            //console.log(data);
            console.log(data[0]);
            $scope.itemRecetaExterna.item=data[0].Insumo_nombre;
            $scope.itemRecetaExterna.indicacion=data[0].Insumo_posologia;
            $scope.itemRecetaExterna.presentacion=data[0].Insumo_presentacion;
            });
        }

        $scope.guardaEjercicios=function(){
          $scope.datos=[$scope.accidente.zona, $scope.accidente.pxRefiere, $scope.accidente.escala];
          console.log($scope.datos);
            $scope.cargador=true; 
          $http({
            url: 'api/notaMedica.php?funcion=guardaEjerciciosMovil&fol=' + $rootScope.folio,
            method: 'POST',
            contentType: 'application/json',
            dataType: "json",
            data: $scope.datos
          }).success(function (data) {
            //console.log(data);
            busquedas.RefEjerciciosPx($rootScope.folio).success(function(data){
              $scope.listRefEjerciciosPx=data;
              $scope.cargador=false; 
              console.log(data);
            });
          }).error(function (xhr, status, data) {
            $scope.mensaje = 'no entra';
            alert('Error');
            console.log(data);
            $scope.cargador1 = false;
          });
          $scope.accidente.zona='';
          $scope.listaEjerciciosPx='';
          $scope.accidente.pxRefiere='';
          $scope.accidente.escala='';
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

        $scope.eliminarRef = function(claveRef){ 
            $scope.cargador=true;        
            $http({
              url:'api/api.php?funcion=eliminaReferencia&fol='+$rootScope.folio+'&ref='+claveRef,
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json"
            }).success( function (data){
              if(!data.respuesta){                                   
                if(data==''){
                  $scope.listRefEjerciciosPx='';  
                }else{
                  $scope.listRefEjerciciosPx=data;   
                }                 

                $scope.cargador=false;   
              }     
              else{  
                alert('Error al eliminar');
                $scope.cargador=false;
              }              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';  
                alert('Error');
                $scope.cargador=false; 
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







        // $scope.guardaEstudios= function(){

        //   var cont=0;

        //   for (var i = $scope.tipoRx.length - 1; i >= 0; i--) {

        //     if($scope.tipoRx[i].TRX_elegido==1){

        //       cont++;

        //     }

        //   }

        //   if($scope.formularios.estudio.$valid){

        //     if (cont>0) {

        //       $scope.estudios.tipo=$scope.tipoRx;

        //       console.log($scope.estudios);



        //     $scope.cargador=true;

        //     $scope.validatipoRx=false;

        //     console.log($rootScope.folio);

        //     $http({

        //       url:'api/api.php?funcion=guardaEstudios&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave,

        //       method:'POST',

        //       contentType: 'application/json',

        //       dataType: "json",

        //       data: $scope.estudios

        //       }).success( function (data){

        //         console.log(data);

        //         if(!data.respuesta){

        //           $scope.estudios={

        //             rx:'',

        //             obs:'',

        //             interp:''

        //           }

        //           for (var i = $scope.tipoRx.length - 1; i >= 0; i--) {

        //             $scope.tipoRx[i].TRX_elegido=0;                    

        //           }

        //             $scope.listEstSoli=data;

        //             $scope.cargador=false;

        //             $scope.formularios.estudio.$submitted=false;

        //             $scope.estudioAgreg=false;

        //             $scope.sinEstudios=false;

        //         }

        //         else{

        //           alert('error en la inserción');

        //         }

        //       }).error( function (xhr,status,data){

        //           $scope.mensaje ='no entra';

        //           alert('Error');

        //       });



        //     }else{

        //       cont=0;

        //       for (var i = $scope.tipoRx.length - 1; i >= 0; i--) {

        //         if($scope.tipoRx[i].TRX_elegido==1){

        //           cont++;

        //         }

        //       }

        //       console.log($scope.tipoRx);

        //       if(cont>0){

        //         $scope.validatipoRx=false;

        //       }else{

        //         $scope.validatipoRx=true;

        //       }

        //     }

        //   }

        // }







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




        $scope.guargaItemExterno = function () {
          console.log($scope.itemRecetaExterna);
          $scope.trabajando=true;
          console.log('api/notaMedica.php?funcion=saveItemRE&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave);
            $http({
              url:'api/notaMedica.php?funcion=saveItemRE&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave,
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: $scope.itemRecetaExterna
            }).success( function (data){                        
                console.log(data);
                if (data == '"exito"') {
      
                  //RESETEAMOS EL FORMULARIO
                  $scope.formularios.recetaExterna.$submitted=false;
                  $scope.formularios.recetaExterna.$pristine=true;
                  $scope.formularios.recetaExterna.$dirty=false;
      
                  $scope.itemRecetaExterna = {
                    item: null,
                    indicacion: null,
                    idReceta:null
                  };
                };
      
                $scope.getRecetaExterna();
                $scope.trabajando=false;
      
            }).error( function (xhr,status,data){
                $scope.mensaje ='Error al escribir receta';            
                alert('Error al escribir receta');
                $scope.trabajando=false;
            });   
        }

        $scope.getIndicacionesParticulares = function (){
          $http.get('api/notaMedica.php?funcion=getIndicacionesParticulares&fol='+$rootScope.folio).success(function (data){                                                            
            $scope.listaIndicAgreg=data;
            console.log($scope.listaIndicAgreg);
          });
        };
        $scope.getIndicacionesParticulares();
      

        /******************************** RECETA EXTERNA ************************************/
          $scope.getRecetaExterna = function(){
            $http.get('api/notaMedica.php?funcion=getItemsRecetaExterna&fol='+$rootScope.folio).success( function (data){
              if (data!='"no existe"') {
                $scope.listadoRecetaExterna=data;
              } else{
                $scope.listadoRecetaExterna=null;
              };
              console.log($scope.listadoRecetaExterna);
            });
          };
          $scope.getRecetaExterna();
      
        $scope.eliminaItemExterno = function(idItemExt){ 
          $scope.trabajando=true;
            $http({
            url:'api/notaMedica.php?funcion=eliminaItemRE&fol='+$rootScope.folio+'&idItemExt='+idItemExt,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: {cve:'valor'}
            }).success( function (data){                        
                console.log(data);
      
                $scope.getIndicacionesParticulares();
                $scope.getRecetaExterna();
                $scope.trabajando=false;
      
            }).error( function (xhr,status,data){
                $scope.mensaje ='Error al escribir receta';            
                alert('Error al escribir receta');
                $scope.trabajando=false;
            });                               
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



                 $scope.timeout = 10; //tiempo de espera de la consulta                                               



                  $scope.status = 'Requesting';



                  $scope.response = '';                            



                  var httpRequest = httpRequestHandler();                                                                                                                                                  



            /********************* fin prueba de tiempo de ejecusión   **************************/



            



            /******************** prueba de tiempo de ejecusión  *********************************/



                  $scope.timeoutOrt = 10; //tiempo de espera de la consulta                                               



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

        $scope.focusfn = function () {
          $scope.focus = true;
          $scope.ftxt = 'Focused'
        }
        $scope.blurfn = function () {
          $scope.focus = false;
          $scope.ftxt = ''
        }

        $scope.validarCampo = function(variable, num){
          if(num==1 && variable!=''){
            $scope.validaDiagostico=false;
          }
          if(num==2 && variable!=''){
            $scope.validaPronostico=false;
          }
          if(num==3 && variable!=''){
            //$scope.validaKit=true;
          }
          if(num==4 && variable!=''){
            $scope.validaReceta=false;
          }
          if(num==5 && variable!=''){
            $scope.validaHojaIndicaciones=false;
          }
          if(num==6 && variable!=''){
            $scope.validaPase=false;
          }
        }

  $scope.guardarDatosDiagnostico = function (diag) {
    $scope.validaPalabra = validaPalabrasProhibidas($scope.diag.diagnostico);
    if ($scope.validaPalabra == 0) {
      $scope.spinner = 'csspinner traditional';       
      $http({
        url:'api/movil.php?funcion=guardaDiagnostico&fol='+$rootScope.folio,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.diag
      }).success( function (data){                       
        console.log(data);
        $scope.spinner = '';       
        if(data.respuesta=='exito'){
          //console.log('api/classes/formatoNotaMovil.php?fol='+$rootScope.folio+'&vit='+$rootScope.vitSelect+'&medSup='+$scope.medico.nombre+'&cedSup='+$scope.medico.cedula);
          $scope.verDocsReque=true;
          $scope.medico.nombre = localStorage.getItem("medicoSuplente");
          $scope.medico.cedula = localStorage.getItem("cedulaMedicoSuplente");
          $http.get('api/classes/formatoNotaMovil.php?fol='+$rootScope.folio+'&vit='+$rootScope.vitSelect+'&medSup='+$scope.medico.nombre+'&cedSup='+$scope.medico.cedula).success(function (data){                                                              
          }); 
        }      
      }).error( function (xhr,status,data){
          $scope.mensaje ='no entra';            
          alert('Error');
      });   
    } else {
      $scope.cargador1 = false;
      $scope.msjPalabraProhi = true;
    }
  }

  $scope.guardaDiagnostico = function (diag) {

    if ($scope.diagnostico.receta == '' || $scope.diagnostico.indicaciones == '' || $scope.diagnostico.pase == '') {
      //if ($scope.diagnostico.kit == '') $scope.validaKit = true;
      if ($scope.diagnostico.receta == '') $scope.validaReceta = true;
      if ($scope.diagnostico.indicaciones == '') $scope.validaHojaIndicaciones = true;
      if ($scope.diagnostico.pase == '') $scope.validaPase = true;
      $('body, html').animate({
        scrollTop: '0px'
      }, 300);

      $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
          $('.ir-arriba').slideDown(300);
        } else {
          $('.ir-arriba').slideUp(300);
        }
      });
    } else {
      $http.get('api/movil.php?funcion=veIndicaciones&fol='+$rootScope.folio).success(function (data){    
        if(data==0){
          $scope.validaHojaIndicaciones = true;
          $scope.indiCont=data;
          console.log(data);
        }
        $scope.indiCont=data;
      });
      $http.get('api/movil.php?funcion=veReceta&fol='+$rootScope.folio).success(function (data){    
        if(data['interna']=="0" && data['externa']=="0"){
          $scope.validaReceta = true;
        }
        console.log(data);
        $scope.recetaCont=data['interna']+data['extrena'];
      });
      $http.get('api/movil.php?funcion=vePase&fol='+$rootScope.folio).success(function (data1){    
        if(data1==0){
          $scope.validaPase = true;
          $scope.paseCont=data1;
        }
        console.log(data1);
      });

      if($scope.paseCont==0||$scope.recetaCont==0||$scope.indiCont==0){
        $('body, html').animate({
          scrollTop: '0px'
        }, 300);

        $(window).scroll(function () {
          if ($(this).scrollTop() > 0) {
            $('.ir-arriba').slideDown(300);
          } else {
            $('.ir-arriba').slideUp(300);
          }
        });
      }else{
        $scope.juntos = [$scope.diagnostico, $scope.ejercicios];
        //$scope.validaKit = true;
        $scope.validaReceta = false;
        $scope.validaHojaIndicaciones = false;
        $scope.validaPase = false;
       
          $scope.msjPalabraProhi = false;
          $http({
            url: 'api/movil.php?funcion=guardaDocsRequeridosMovil&fol=' + $rootScope.folio,
            method: 'POST',
            contentType: 'application/json',
            dataType: "json",
            data: $scope.juntos
          }).success(function (data) {
            console.log(data);
            if(data.estatus=='update' || data.estatus=='insert'){
              $scope.verResultado=true;
              $scope.listadoDocsReq = data.listado;
              $scope.listaEjercicios = data.Ejercicios;
              if($scope.diagnostico.indicaciones!=''){
                $http.get('api/classes/formatoIndicacionesMovil.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave).success(function (data){                                                              
                });
              }
            }
          }).error(function (xhr, status, data) {
            $scope.mensaje = 'no entra';
            alert('Error');
            $scope.cargador1 = false;
          });
       
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





        $scope.enviaDatos = function(){  
          $scope.cargardoModal = 'panel-body csspinner sphere';
          $scope.pase.diagnostico = $scope.diag.diagnostico;
          console.log($scope.pase);
          $http({
          url:'api/pases.php?funcion=guardaPase',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.pase
          }).success( function (data){                        
            console.log(data);
            $http.get('api/classes/pdf/generaPDF.php?fol='+$rootScope.folio).success(function (data){    
            });
            $("#exampleModal").modal('hide');
            $scope.msjPaseCreado=true;
            $scope.cargardoModal = 'panel-body';
            $scope.pase = {
              folio :  $rootScope.folio,
              motivo :'',
              diagnostico : '',
              unidad :'',
              correo :0,
              msj:0,
              usr : $rootScope.usrLogin
            }
          }).error( function (xhr,status,data){
              $scope.cargardoModal = 'panel-body';
              $scope.mensaje ='no entra';            
              alert('Error');
          });            
      } 

      $scope.enviarMensajeDocumentos = function(telefono){   
        $scope.msjmsjEnviado ='';         
        $http.get('api/movil.php?funcion=envioMensaje&fol='+$rootScope.folio+'&tel='+$scope.telEnviar).success(function (data){
          console.log(data);
          if(data=='exito'){
            $scope.msjmsjEnviado='El correo se envió correctamente';
          }else{
            $scope.msjmsjEnviado='Algo falló en el envío, vuelve a intentarlo';
          }

        });
      } 
      $scope.enviarCorreoDocumentos = function(correo, correoOtro){   
        $scope.msjcorreoEnviado='';      
        $http.get('api/movil.php?funcion=envioCorreo&fol='+$rootScope.folio+'&correo='+correo+'&correoAlt='+correoOtro).success(function (data){ 
          console.log(data);
          if(data=='exito'){
            $scope.msjcorreoEnviado='El correo se envió correctamente';
          }else{
            $scope.msjcorreoEnviado='Algo falló en el envío, vuelve a intentarlo';
          }         
        });
      }  

      $scope.enviarDocumentos = function(){   
        if($scope.envio.telefonoElegido=='') $scope.envio.telefonoElegido=$scope.detalle.telefono;
        $scope.msjcorreoEnviado=''; 
        $scope.spinnerDiv = 'row csspinner sphere';     
        if($scope.envio.correo==1){
          $http.get('api/movil.php?funcion=envioCorreo&fol='+$rootScope.folio+'&correo='+$scope.detalle.mail+'&correoAlt='+$scope.envio.correoElegido).success(function (data){ 
            console.log(data);
            if(data=='exito'){
              $scope.msjEnvioDocs='El correo se envió correctamente';
            }else{
              $scope.msjEnvioDocs='Algo falló en el envío, vuelve a intentarlo';
            }
            $scope.terminar=false;     
            $scope.spinnerDiv = 'row';    
          });
         
        }
        if($scope.envio.msm==1){
          $http.get('api/movil.php?funcion=envioMensaje&fol='+$rootScope.folio+'&tel='+$scope.envio.telefonoElegido).success(function (data){
            console.log(data);
            if(data=='exito'){
              $scope.msjEnvioDocs='El SMS se envió correctamente';
            }else{
              $scope.msjEnvioDocs='Algo falló en el envío, vuelve a intentarlo';
            }
            $scope.terminar=false;
            $scope.spinnerDiv = 'row';
          });
          
        }
        if($scope.envio.whatapp==1){
          $http.get('api/movil.php?funcion=enviowhatsapp&fol='+$rootScope.folio+'&tel='+$scope.envio.telefonoElegido).success(function (data){
            console.log(data);
            if(data=='exito'){
              $scope.msjEnvioDocs='El whatsapp se envió correctamente';
            }else{
              $scope.msjEnvioDocs='Algo falló en el envío, vuelve a intentarlo';
            }
            $scope.terminar=false;
            $scope.spinnerDiv = 'row';
          });
          
        } 
       
        
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



            if($scope.diag.pronostico==''){



                $scope.diag.pronostico=$scope.opcionPron;



            }else{



                $scope.diag.pronostico=$scope.diag.pronostico+', '+$scope.opcionPron;



            }            



        }  



/*************************           suministro symio            *******************************************/



      /*************************           suministro symio            *******************************************/



        $scope.seleccionaMedicamentos = function(medicamento){            



           if($rootScope.uniClave==$scope.cveUniInventario){



            for(lista in $scope.lisMedSymio){



              



              if(medicamento==$scope.lisMedSymio[lista].Clave_producto){                



                $scope.med.presentacion     = $scope.lisMedSymio[lista].presentacion;



                $scope.med.posologia        = $scope.lisMedSymio[lista].posologia;



                if($scope.lisMedSymio[lista].Caja==0) $scope.lisMedSymio[lista].Caja=1;







                if($scope.lisMedSymio[lista].segmentable==1){



                  $scope.med.stock=$scope.lisMedSymio[lista].Stock;



                }else{



                  $scope.med.stock            = Math.trunc($scope.lisMedSymio[lista].Stock/$scope.lisMedSymio[lista].Caja);                                     



                }     



                console.log($scope.lisMedSymio[lista].segmentable);           



                $scope.med.descripcion      = $scope.lisMedSymio[lista].Descripcion;



                $scope.med.almacen          = $scope.lisMedSymio[lista].almacen;



                $scope.med.idMedicamento    = $scope.lisMedSymio[lista].Clave_producto;



                $scope.med.existencia       = $scope.lisMedSymio[lista].id;                



              }            



          }



          }else{



            for(lista in $scope.lisMedSymio){



              if(medicamento==$scope.lisMedSymio[lista].Clave_producto){



                $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;



                $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;



                $scope.med.stock = $scope.lisMedSymio[lista].Stock;



              }            



            }



          }



          



        }  







        $scope.seleccionaOrtesis = function(ortesis){  







         if($rootScope.uniClave==$scope.cveUniInventario){  



            for(lista in $scope.lisrtOrtSymio){



              console.log(ortesis); 



              if(ortesis==$scope.lisrtOrtSymio[lista].Clave_producto){                 



                $scope.ortesisSym.presentacion     = $scope.lisrtOrtSymio[lista].presentacion;



                $scope.ortesisSym.indicaciones     = $scope.lisrtOrtSymio[lista].posologia;



                $scope.ortesisSym.stock            = $scope.lisrtOrtSymio[lista].Stock;



                $scope.ortesisSym.descripcion      = $scope.lisrtOrtSymio[lista].Descripcion;



                $scope.ortesisSym.almacen          = $scope.lisrtOrtSymio[lista].almacen;



                $scope.ortesisSym.idMedicamento    = $scope.lisrtOrtSymio[lista].Clave_producto;



                $scope.ortesisSym.existencia       = $scope.lisrtOrtSymio[lista].id;                



              }            



            }



          }else{







            for(lista in $scope.lisrtOrtSymio){



              if(ortesis==$scope.lisrtOrtSymio[lista].Clave_producto){                              



                $scope.ortesisSym.presentacion     = $scope.lisrtOrtSymio[lista].presentacion;



                $scope.ortesisSym.indicaciones     = $scope.lisrtOrtSymio[lista].posologia;



                $scope.ortesisSym.stock            = $scope.lisrtOrtSymio[lista].Stock;



                $scope.ortesisSym.descripcion      = $scope.lisrtOrtSymio[lista].Descripcion;



                $scope.ortesisSym.almacen          = $scope.lisrtOrtSymio[lista].almacen;



                $scope.ortesisSym.idMedicamento    = $scope.lisrtOrtSymio[lista].Clave_producto;



                $scope.ortesisSym.existencia       = $scope.lisrtOrtSymio[lista].id;                



              }            



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



               if($rootScope.uniClave==$scope.cveUniInventario){    



               $http({



                url:' http://api.medicavial.mx/api/operacion/reserva/item',



                method:'POST', 



                contentType: 'application/json', 



                dataType: "json", 



                data: {id_item:$scope.med.idMedicamento,id_almacen:$scope.med.almacen,NS_cantidad:$scope.med.cantidad}



                }).success( function (data){                     



                   $scope.med.reserva= data;  



                   $http({



                    url:'api/notaMedica.php?funcion=guardarMedicamentosNota&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin+'&tipoReceta=1',



                    method:'POST', 



                    contentType: 'application/json', 



                    dataType: "json", 



                    data: $scope.med



                    }).success( function (data){ 



                      console.log(data);



                        $scope.med.stock=$scope.med.stock-$scope.med.cantidad;



                        for(lista in $scope.lisMedSymio){



                          if($scope.med.sustAct==$scope.lisMedSymio[lista].Clave_producto){



                            $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;



                            $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;



                            $scope.lisMedSymio[lista].Stock=$scope.med.stock;



                          }            



                        }



                        $scope.listaMedicamentosSymio=data;



                        $scope.formularios.medicSymio.$submitted=false; 



                        $scope.cargador=false; 



                        $scope.siguienteMed=false;  



                         $scope.med={



                        sustAct:'',



                        medicame:'',



                        presentacion:'',



                        cantidad:1,



                        posologia:''          



                      }                                                          



                    }).error( function (xhr,status,data){



                      $scope.cargador=true;



                        $scope.mensaje ='no entra';            



                        alert('Error');



                    });                    







                }).error( function (xhr,status,data){



                  $scope.cargador=true;



                    $scope.mensaje ='no entra';            



                    alert('Error');



                }); 



                }else{       



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



                }



            }else{



              $scope.validaStock=true;



            }



          }



        } 











        $scope.eliminarMedicamentoSymio = function(cveReserva,cveItemReceta){ 



            $scope.cargador=true; 



            $http({



                method: 'DELETE',



                url: 'http://api.medicavial.mx/api/operacion/reserva/'+cveReserva



            }).success(function(data, status, headers, config) {



                console.log(cveItemReceta);



                $http({



                    url:'api/notaMedica.php?funcion=eliminarMedicamentosNota&cveItemReceta='+cveItemReceta+'&usr='+$rootScope.usrLogin,



                    method:'POST', 



                    contentType: 'application/json', 



                    dataType: "json", 



                    data: {cve:'valor'}



                    }).success( function (data){                                                



                      if(!data.respuesta){                  



                            



                            if(data==''){



                              $scope.listaMedicamentosSymio=''; 



                              $scope.siguienteMed=true;                   



                            }else{                                                           



                              $scope.listaMedicamentosSymio=data;



                              $scope.med.stock=parseInt($scope.med.stock)+parseInt($scope.med.cantidad); 



                              for(lista in $scope.lisMedSymio){



                                if($scope.med.sustAct==$scope.lisMedSymio[lista].Clave_producto){



                                  $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;



                                  $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;



                                  $scope.lisMedSymio[lista].Stock=$scope.med.stock;



                                }            



                              }                 



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



            }).error(function(data, status, headers, config) {



                if (status === 400) {



                    defered.reject(data);



                } else {



                    throw new Error("Fallo obtener los datos:" + status + "\n" + data);



                }



            });











                                      



        }







         $scope.eliminarMedicamentoSymio1 = function(clavePro){             



            $scope.cargador=true; 



            $http({



            url:'api/api.php?funcion=eliminarMedicamentoSymio&fol='+$rootScope.folio+'&proClave='+clavePro+'&uni='+$rootScope.uniClave,



            method:'POST', 



            contentType: 'application/json', 



            dataType: "json", 



            data: {cve:'valor'}



            }).success( function (data){                             



              



              if(!data.respuesta){                  



                    console.log(data);



                    for(lista in $scope.lisMedSymio){



                      if(clavePro==$scope.lisMedSymio[lista].Clave_producto){                                                



                        $scope.lisMedSymio[lista].Stock= parseInt($scope.lisMedSymio[lista].Stock)+parseInt(cantidadMed);



                      }            



                    }   



                    if(data==''){



                      $scope.listaMedicamentosSymio=''; 



                      $scope.siguienteMed=true;                   



                    }else{                                                           



                      $scope.listaMedicamentosSymio=data;                                        



                  }   



                  console.log($scope.listaMedicamentosSymio);                                 



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



              console.log($scope.ortesisSym);



              $scope.validaStockOrtesisSym=false;



              $scope.cargador1=true; 



               if($rootScope.uniClave==$scope.cveUniInventario){             



                $http({



                url:' http://api.medicavial.mx/api/operacion/reserva/item',



                method:'POST', 



                contentType: 'application/json', 



                dataType: "json", 



                data: {id_item:$scope.ortesisSym.idMedicamento,id_almacen:$scope.ortesisSym.almacen,NS_cantidad:$scope.ortesisSym.cantidad}



                }).success( function (data){                           



                   $scope.ortesisSym.reserva= data;  



                   $http({



                    url:'api/notaMedica.php?funcion=guardarOrtesisNota&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin+'&tipoReceta=1',



                    method:'POST', 



                    contentType: 'application/json', 



                    dataType: "json", 



                    data: $scope.ortesisSym



                    }).success( function (data){                         



                        $scope.listaOrtesisSymio=data;



                        $scope.formularios.orteSymio.$submitted=false; 



                        $scope.cargador1=false; 



                        $scope.siguienteOrt=false;                           



                        $scope.ortesisSym.stock=$scope.ortesisSym.stock-$scope.ortesisSym.cantidad; 



                        for(lista in $scope.lisrtOrtSymio){



                          if($scope.ortesisSym.ortSymio==$scope.lisrtOrtSymio[lista].Clave_producto){



                            $scope.ortesisSym.presentacion=$scope.lisrtOrtSymio[lista].Sym_forma_far;



                            $scope.ortesisSym.posologia = $scope.lisrtOrtSymio[lista].Sym_indicacion;



                            $scope.lisrtOrtSymio[lista].Stock=$scope.ortesisSym.stock;



                            console.log($scope.lisrtOrtSymio[lista].Stock);



                          }            



                        }  



                        console.log($scope.lisrtOrtSymio);



                         $scope.ortesisSym={



                            ortSymio:'',



                            cantidad:1,



                            indicaciones:'',



                            stock:''



                          }                                                    



                    }).error( function (xhr,status,data){



                      $scope.cargador1=true;



                        $scope.mensaje ='no entra';            



                        alert('Error');



                    });                    







                }).error( function (xhr,status,data){



                  $scope.cargador1=true;



                    $scope.mensaje ='no entra';            



                    alert('Error en la base de serch');



                });



                }else{                            



                  $http({



                    url:'api/api.php?funcion=guardaOrtSymio&fol='+$rootScope.folio+'&uni='+$scope.uniClave,



                    method:'POST', 



                    contentType: 'application/json', 



                    dataType: "json", 



                    data: $scope.ortesisSym



                    }).success( function (data){                                 



                      if(!data.respuesta){ 



                        console.log(data);



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



              }



            }else{



              $scope.validaStockOrtesisSym=true;



            }



        }



      } 







        $scope.eliminarOrtesisSymio = function(cveReserva,cveItemReceta,id_item){ 



            $scope.cargador1=true;              



            $http({



                method: 'DELETE',



                url: 'http://api.medicavial.mx/api/operacion/reserva/'+cveReserva



            }).success(function(data, status, headers, config) {



                console.log(cveItemReceta);



                $http({



                    url:'api/notaMedica.php?funcion=eliminarOrtesisNota&cveItemReceta='+cveItemReceta+'&usr='+$rootScope.usrLogin,



                    method:'POST', 



                    contentType: 'application/json', 



                    dataType: "json", 



                    data: {cve:'valor'}



                    }).success( function (data){                                                



                      if(!data.respuesta){                                              



                          if(data==''){



                            $scope.listaOrtesisSymio=''; 



                            $scope.siguienteMed=true;                   



                          }else{                                                           



                            $scope.listaOrtesisSymio=data;                              



                          }



                          $scope.ortesisSym.stock=parseInt($scope.ortesisSym.stock)+parseInt($scope.ortesisSym.cantidad); 



                          for(lista in $scope.lisrtOrtSymio){



                            if(id_item==$scope.lisrtOrtSymio[lista].Clave_producto){



                              $scope.ortesisSym.presentacion=$scope.lisrtOrtSymio[lista].Sym_forma_far;



                              $scope.ortesisSym.posologia = $scope.lisrtOrtSymio[lista].Sym_indicacion;



                              $scope.lisrtOrtSymio[lista].Stock=$scope.ortesisSym.stock;                              



                            }            



                          }                                               



                          $scope.cargador1=false;



                          $scope.ortesisSym={



                            ortSymio:'',



                            cantidad:1,



                            indicaciones:'',



                            stock:''



                          }                                                                                          



                      }              



                      else{                



                        alert('error en la inserción');



                      }           



                    }).error( function (xhr,status,data){



                        $scope.mensaje ='no entra';            



                        alert('Error');



                    });     



            }).error(function(data, status, headers, config) {



                if (status === 400) {



                    defered.reject(data);



                } else {



                    throw new Error("Fallo obtener los datos:" + status + "\n" + data);



                }



            });



            /*$http({



            url:'api/notaMedica.php?funcion=eliminarOrtesis&fol='+$rootScope.folio+'&proClave='+clavePro+'&uni='+$rootScope.uniClave,



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



            }); */                              



        }











$scope.eliminarOrtesisSymio1 = function(clavePro){ 



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





$scope.irRecetaMovil = function(folio){  
    // $cookies.folio = folio;
    $('#modalReceta').modal();
}

$scope.irRecetaMovilExterna = function(folio){  
    $cookies.folio = folio;
    $scope.pase.indicaciones=1;
    $('#modalRecetaExterna').modal();
}

$scope.irCuentaPx = function(folio){  
    $cookies.folio = folio;
    $('#modalCuentaPx').modal();
}

$scope.historiaRapida = function(folio){  
    $cookies.folio = folio;
    $('#modalhistoria').modal();
}

$scope.historiaRapida2 = function(folio){  
    $cookies.folio = folio;
    $('#modalhistoria2').modal();
}

$scope.contactoAMI = function(){
    $('#modalcontactoAMI').modal();
}

$scope.validaSinReceta = function(folio){  
  // $cookies.folio = folio;
  if($scope.diagnostico.receta==0){
    $scope.diagnostico.indicaciones=1;
  }
  // $('#modalRecetaExterna').modal();
}

$scope.lanzarAlerta = function(){
  if($scope.accidente.mecanismo.mec1 || $scope.accidente.vomido=='S' || $scope.accidente.nauseas=='S' ||$scope.accidente.conocimiento=='S' ||$scope.accidente.mareo=='S' ||$scope.accidente.cefalea=='S'){
    swal({title:"Alerta!", 
          text:"Verificar que el paciente no requera hospitalización",  
          icon: "warning",
          type: "warning",
          confirmButtonColor: "#DD6B55", 
          ConfirmButtonText: "Cerrar",
          dangerMode: true});
  }
}

$scope.guardaCuenta= function(){
  //console.log($rootScope.folio+" "+$rootScope.usrLogin);
  if($scope.formularios.cuentaPx.$valid){
    $scope.trabajando=true;
    console.log($scope.cuentaPx.clabe);
    console.log($scope.cuentaPx.tarjeta);
    if(($scope.cuentaPx.clabe=='' || $scope.cuentaPx.clabe==null)  && ($scope.cuentaPx.tarjeta=='' || $scope.cuentaPx.tarjeta==null)){
      $scope.msjClabe=true;
      $scope.trabajando=false;
    }else{      
      $scope.msjClabe=false;
      $http({
        url:'api/api.php?funcion=guardaCuenta&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json",
        data: $scope.cuentaPx
      }).success( function (data){
        $scope.msjCuenta=true;
        $scope.trabajando=false;
      }).error( function (xhr,status,data){
        console.log(data);
      });
    }
  }
}

$scope.guardaHistoriaRapida= function(){
  //console.log($rootScope.folio+" "+$rootScope.usrLogin);
  if($scope.formularios.historiaRapida.$valid){
    $scope.trabajando=true;
    $http({
      url:'api/api.php?funcion=guardaHistoriaRapida&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json",
      data: $scope.hcRapida
    }).success( function (data){
      console.log(data);
      $scope.msjHc=true;
      $scope.hcRapida.notas='';
      $scope.trabajando=false;      
      busquedas.NotasHC($rootScope.folio).success(function(data){
        $scope.historia=data;
      });
      setTimeout(function(){
        $scope.msjHc=false;
      }, 3000)
    }).error( function (xhr,status,data){
      console.log(data);
    });
  }
}

$scope.guardaHistoriaRapida2= function(){
  //console.log($rootScope.folio+" "+$rootScope.usrLogin);
  if($scope.formularios.historiaRapida2.$valid){
    $scope.trabajando=true;
    $http({
      url:'api/api.php?funcion=guardaHistoriaRapida&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json",
      data: $scope.hcRapida2
    }).success( function (data){
      console.log(data);
      $scope.msjHc2=true;
      $scope.hcRapida2.notas2='';
      $scope.trabajando=false;
      busquedas.NotasHC($rootScope.folio).success(function(data){
        $scope.historia=data;
      });
      setTimeout(function(){
        $scope.msjHc2=false;
      }, 3000)
    }).error( function (xhr,status,data){
      console.log(data);
      $scope.trabajando=false;
    });
  }
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

              

              $http.get('api/notaMedica.php?funcion=confirmaReceta&fol='+$rootScope.folio).success(function (data){                                                              

                  console.log(data);

                  $scope.listaMed=data.Med;

                  $scope.listaOrt=data.Ort;

                  $scope.listaInd=data.Ind;

                  $("#modalConfirmacion").modal();

                  $scope.cargador5=false;

              });                       



            // $http({



            //   url:'api/api.php?funcion=guardaPronostico&fol='+$rootScope.folio,



            //   method:'POST', 



            //   contentType: 'application/json', 



            //   dataType: "json", 



            //   data: $scope.pronostico



            //   }).success( function (data){ 



            //       console.log(data);



            //     if(data.respuesta=='correcto'){ 



            //      $scope.cargador5=false; 



            //              $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=5').success(function (data){                                

            //       });                  

            //            WizardHandler.wizard().next();                                                   

            //       }              



            //     else{                



            //       alert('error en la inserción');



            //     }              



            //   }).error( function (xhr,status,data){



            //       $scope.mensaje ='no entra';            



            //       alert('Error');



            //   });                      



          }



        } 



      $scope.guardaPronosticoValidado= function(){ 

        if($scope.formularios.prono.$valid){



        $http({

            url:'api/api.php?funcion=guardaPronostico&fol='+$rootScope.folio,

            method:'POST', 

            contentType: 'application/json', 

            dataType: "json", 

            data: $scope.pronostico

            }).success( function (data){ 

                console.log(data);

              if(data.respuesta=='correcto'){ 

               $scope.cargador5=false; 

                       $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=5').success(function (data){                                

                });                  

                $http.get('api/catalogos.php?funcion=lesionCodificadaLesion&opcion=L_LEVE').success(function (data){                                                                  



                  $scope.listadoLesionesModuloCodificado = data;

    

                  console.log(data);

    

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

          $scope.medico.nombre = localStorage.getItem("medicoSuplente");

          $scope.medico.cedula = localStorage.getItem("cedulaMedicoSuplente");




          $http.get('api/classes/formatoNotaMovil.php?fol='+$rootScope.folio+'&vit='+$rootScope.vitSelect+'&medSup='+$scope.medico.nombre+'&cedSup='+$scope.medico.cedula).success(function (data){                                                              
            $scope.msjRecetaCreada=true;
          }); 
                        

              $scope.url='api/classes/formatoNotaMovil.php?fol='+$rootScope.folio+'&vit='+$rootScope.vitSelect+'&medSup='+$scope.medico.nombre+'&cedSup='+$scope.medico.cedula;



  



            var fileName = "Reporte";



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



        $scope.irDocumentos = function(){         



              $location.path("/documentosMovil");          



        }



  $scope.imprimirReceta = function () {
    
    $http.get('api/classes/formatoRecetaMovil.php?fol=' + $rootScope.folio + '&vit=' + $rootScope.vitSelect + '&uni=' + $scope.unidad + '&usr=' + $rootScope.usrLogin).success(function (data){                                                              
      $scope.msjRecetaCreada=true;
    });         
    $scope.diagnostico.recetaInterna=1;  
    $('#modalReceta').modal('hide');
  }


  $scope.imprimirRecetaExterna = function(){
    $scope.trabajando=true;
    $http.get('api/classes/formatoRecetaExternaMovil.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave).success(function (data){                                                              
      $scope.msjRecetaCreada=true;
    });          
      //$scope.getRecetaInterna();
      $scope.getRecetaExterna();
      $scope.getIndicacionesParticulares();
      // $location.path("/documentosMovil");
      $scope.trabajando=false;
      $scope.msjRecetaCreada=true;
      $scope.diagnostico.recetaExterna=1;  
      $('#modalRecetaExterna').modal('hide');
  }

  $scope.getRecetaInterna = function(){
    $http.get('api/notaMedica.php?funcion=getItemsRecetaInterna&fol='+$rootScope.folio).success( function (data){
      $scope.listadoRecetaInterna=data;
      console.log($scope.listadoRecetaInterna);
    });
  };
  $scope.getRecetaInterna();







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





         $scope.imprimirSolicitudRx = function(){

            var fileName = 'SolicitudRx - '+$rootScope.folio;

            var uri = 'api/classes/solicitudRx.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

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

        

        $scope.irRecetaParticulares = function(folio){  



          $cookies.folio = folio;

    

              $location.path("/recetaParticulares");

        }





        $scope.eliminaTelefono = function(contaTel){  



            console.log('entró');



            contaTel=contaTel-1;



            delete $scope.sumaTel[contaTel]; 



            $scope.sumaTel.splice(contaTel,1);               



            console.log($scope.sumaTel);



        }







        $scope.checaLesion = function(){                                  



           $scope.opcionCod = 0;



        switch($scope.lesionAdmin.tipoLesion){



        case '1':



            



            $scope.verUnica=  true;



            $scope.verMultiple= false;



            $scope.verLeve=   false;



            $scope.verSimple=   false;



            $scope.listadoOtros='';



            $scope.opcionCod = 3;



            $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=3').success(function (data){                                     



               



                $scope.listadoUnica=data;



                $scope.listadoMultiple='';



                $scope.verMultipleSecundaria=false;



                $scope.verMensajePolicontundidos=false;



                console.log(data);



            }); 



            $scope.listadoLesionesModuloCodificado ='';



        break;



        case '2':



            



            $scope.verUnica=  false;



            $scope.verMultiple= true;



            $scope.verLeve=   false;



            $scope.verSimple=   false;



            $scope.listadoOtros='';



            $scope.opcionCod = 4;



            $http.get('api/api1.php?funcion=lesionDiagnostico&opcion=4').success(function (data){



                



                $scope.verMultipleSecundaria=false;                



                $scope.lesionAdmin.lesionUnica ='';             



                $scope.listadoUnica='';



                $scope.listadoMultiple=data;



                $scope.listadoMultipleSecundaria=data;



            }); 



            $scope.listadoLesionesModuloCodificado ='';



        break;



        case '3':  



          $scope.opcionCod = 2;       



          $scope.verUnica=  false;



            $scope.verMultiple= false;



            $scope.verLeve=   true;



            $scope.verSimple=   false;



            $scope.listadoOtros='';



            $scope.lesionAdmin.lesionCodificada='';



             $http.get('api/catalogos.php?funcion=lesionCodificadaLesion&opcion=L_LEVE').success(function (data){                                                                  



              $scope.listadoLesionesModuloCodificado = data;



              console.log(data);



          });



        break;



        case '4': 



        $scope.opcionCod = 1;        



          $scope.verUnica=  false;



            $scope.verMultiple= false;



            $scope.verLeve=   false;



            $scope.verSimple=   true;



            $scope.listadoOtros='';



            $scope.lesionAdmin.lesionCodificada='';



            $http.get('api/catalogos.php?funcion=lesionCodificadaLesion&opcion=S_SMPL').success(function (data){                                                                  



              $scope.listadoLesionesModuloCodificado = data;



              console.log(data);



            });



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



            $scope.lesionAdmin.lesionCodificada='';         



            $http.get('api/api1.php?funcion=nombreLesion&claveLesion='+$scope.diagnostico.lesionUnica).success(function (data){                                              



                $scope.diagnostico.diagnostico=data.nombre+' // ';



            });  



            $http.get('api/catalogos.php?funcion=lesionCodificadaLesion&opcion='+$scope.lesionAdmin.lesionUnica).success(function (data){                                                                  



                $scope.listadoLesionesModuloCodificado = data;



                console.log(data);



            });           



        }



        $scope.seleccionLesionOtra = function(){             



            $http.get('api/api1.php?funcion=nombreLesion&claveLesion='+$scope.diagnostico.lesionOtro).success(function (data){                                              



                $scope.diagnostico.diagnostico=data.nombre+' // ';



            });             



        }



        $scope.onFileSelect_xml = function($files) {

          for (var i = 0; i < $files.length; i++) {

            var file = $files[i];

            $scope.archivo=file;

            $scope.variable = 2;

            var amt = 0;

            $scope.upload = $upload.upload({

              url: 'api/api.php?funcion=archivo_temporal', 

              method: 'POST',

              data: $scope.factura,

              file: file,

              progress:function(evt) {

               

                console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));

                $scope.progressBar = parseInt(100.0 * evt.loaded / evt.total);

              }

            }).success(function (data, status, headers, config){                        

              $scope.rx.archivo=data.nombre;

              $scope.rx.temporal=data.temporal; 

              console.log($scope.rx.archivo+'--'+$scope.rx.temporal);           

            }).error( function (xhr,status,data){

              alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');

            });

          }  

        }



        $scope.guardaDigital = function(){

          $scope.cargador=true;

          $scope.msjFoto='';          

          $scope.upload = $upload.upload({

            url:'api/digitales.php?funcion=guardaFoto&fol='+$rootScope.folio+'&noPlac='+$scope.rx.noPlac+'&usr='+$rootScope.usrLogin+'&inter='+$scope.rx.inter,

            method:'POST',             

            data:$scope.rx,

            file: $scope.archivo

          }).success( function (data, status, headers, config){

            console.log(data);             

            $scope.cargador=false;          

            $scope.msjerror=false;

            if(!data.respuesta){                

            $scope.msjFoto='La foto se subió correctamente';

            $scope.rx={

              archivo:'',

              temporal:'',

              noPlac:1

            };           

          }else if(data.respuesta=='error'){

            $scope.msjerror=true;

          }

          }).error( function (xhr,status,data){            

            $scope.cargador=false;          

            $scope.mensaje ='no entra';            

            alert('Error');

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



            $scope.lesionAdmin.lesionCodificada='';             



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



          $http.get('api/catalogos.php?funcion=lesionCodificadaLesion&opcion='+$scope.lesionAdmin.lesionMultiple).success(function (data){                                                                  



              $scope.listadoLesionesModuloCodificado = data;



              console.log(data);



          });                       



        }







        $scope.filtraMedicamentosVacios = function () {







            return function (item) {







                if (item.Stock > 0)



                {



                  if(item.segmentable==0){



                    if(item.Caja>0){                      



                      if((item.Stock/item.Caja)>1){



                        return true;



                      }else{



                        return false;



                      }



                    }else{



                      return true;



                    }



                  }else{



                    return true;



                  }                                    



                }



                return false;



            };



        };







        $scope.filtraOrtesisVacios = function () {



            return function (item) {



                if (item.Stock > 0)



                {                  



                    return true;                                             



                }



                return false;



            };



        };







        /*****************************************************************************************************************/







        /************************************************   validación para imprmiir formatos de corta estancia *************************/







        $scope.verificaSolicitudCE = function(){ 







            $http.get('api/cortaEstancia.php?funcion=getSolicitud&fol='+$rootScope.folio).success(function (data){ 



            console.log(data);                                                                               



                   if(data.contador==0){



                      $('#SolicitarCortaEstancia').modal('show');   



                   }else if(data.contador>=1){







                      alert('Ya se mandó la solicitud');



                      /*$http.get('api/cortaEstancia.php?funcion=getcheckValida&fol='+$rootScope.folio).success(function (data){ 



                        $('#verDocsCE').modal('show');



                        if(data.contadorUsado>=1){



                          $scope.verDocumentosCE=true;



                        }else{



                          $scope.verDocumentosCE=false;



                        }                                                  



                      });



                      */ 



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



                   $scope.existeSolCE=true;          



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







         $scope.validarLongitud = function(){    

           

          var longitud = parseInt($scope.diag.diagnostico.length) + 1;        

          $scope.conteoDiag=225;   

          if(longitud<=$scope.conteoDiag){

            $scope.conteoDiag = $scope.conteoDiag - longitud;          

          }else{

            $scope.conteoDiag=0;

          }                    

          

          

        }



        $scope.validarLongitudMecanismo = function(){    

           

          var longitud = parseInt($scope.accidente.mecLesion.length) + 1;        

          $scope.conteoMec=225;   

          if(longitud<=$scope.conteoMec){

            $scope.conteoMec = $scope.conteoMec - longitud;          

          }else{

            $scope.conteoMec=0;

          }                    

          

          

        }

        $scope.validarLongitudEstadoGeneral = function(){    

           

          var longitud = parseInt($scope.accidente.estado.length) + 1;        

          $scope.conteoEstado=225;   

          if(longitud<=$scope.conteoEstado){

            $scope.conteoEstado = $scope.conteoEstado - longitud;          

          }else{

            $scope.conteoEstado=0;

          }                    

          

          

        }



        $scope.verPase = function(){    

           

          if($scope.diagnostico.pase==1){

            $('#exampleModal').modal();

          }               

          

          

        }



        /**************************************************fin formatos corta estancia **************************************************/

















        /****************************************todo lo de receta *************************************************************************************** */





        $http.get('api/api.php?funcion=listaAlergiasMovil&fol='+$rootScope.folio).success(function (data){                                                            

          $scope.alergias = data;

      });

      

      $http.get('api/notaMedica.php?funcion=confirmaReceta&fol='+$rootScope.folio).success(function (data){                                                            

          console.log(data);

          if(data.Ind){

            $scope.listaIndicAgreg=data.Ind;

          }

          if(data.Rec.Uni_clave){

            $scope.unidad = data.Rec.Uni_clave;

            $scope.med={

              sustAct:'',

              medicame:'',

              presentacion:'',

              cantidad:1,

              posologia:'',

              stock:''          

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

            $scope.timeout = 10; //tiempo de espera de la consulta                                               

            $scope.status = 'Requesting';

            $scope.response = '';               

            var httpRequest = httpRequestHandler();   

        

            $scope.timeoutOrt = 10; //tiempo de espera de la consulta                                               

            $scope.status = 'Requesting';

            $scope.response = '';              

            var httpRequestOrt = httpRequestHandlerOrt();    

          }

          

    

      });

    

      busquedas.cuentaClabe($rootScope.folio).success(function(data){
        $scope.listClabe=data;
      });

      busquedas.NotasHC($rootScope.folio).success(function(data){
        $scope.historia=data;
      });

      busquedas.listaIndicaciones().success(function(data){                      
        $scope.listaIndicacion=data;                     
      });

      busquedas.listaZona().success(function(data){
        $scope.listZona=data;
        console.log(data);
      }); 

      busquedas.listaInsumos().success(function(data){
        $scope.listInsumos=data;
        console.log(data);
      }); 

      $http.get('api/notaMedica.php?funcion=listadoItems&fol='+$rootScope.folio+'&tipo=1&tipoReceta=1').success(function (data){  console.log(data);                                                                                                                   

        if(data=='"vacio"'){

            $scope.listaMedicamentosSymio='';

            $scope.siguienteMed=true;

          }else{                                                                                    

            if(data.length<1){

              $scope.listaMedicamentosSymio='';

              $scope.siguienteMed=true;

            }else{

              $scope.listaMedicamentosSymio=data;                     

              $scope.siguienteMed=false;

            }         

        } 

        console.log($scope.listaMedicamentosSymio);

        

    });      

    $http.get('api/notaMedica.php?funcion=listadoItems&fol='+$rootScope.folio+'&tipo=2&tipoReceta=1').success(function (data){                                                                                                   

      if(data=='"vacio"'){

          $scope.listaOrtesisSymio='';

          $scope.siguienteMed=true;

        }else{      

          if(data.length<1){

            $scope.listaOrtesisSymio='';

            $scope.siguienteOrt=true;  

          }else{

            $scope.listaOrtesisSymio=data;                     

            $scope.siguienteOrt=false;          

          }

      }           

      console.log(data);                 

    });   

    

    /****************************************************************************************************/

    

    

    

    $scope.seleccionaMedicamentos = function(medicamento){    

      

      console.log('entro a la funcion');

      

       for(lista in $scope.lisMedSymio){

         if(medicamento==$scope.lisMedSymio[lista].Clave_producto){                

           $scope.med.presentacion     = $scope.lisMedSymio[lista].presentacion;

           $scope.med.posologia        = $scope.lisMedSymio[lista].posologia;

           if($scope.lisMedSymio[lista].Caja==0) $scope.lisMedSymio[lista].Caja=1;

           if($scope.lisMedSymio[lista].segmentable==1){

             $scope.med.stock=$scope.lisMedSymio[lista].Stock;

           }else{

             $scope.med.stock            = Math.trunc($scope.lisMedSymio[lista].Stock/$scope.lisMedSymio[lista].Caja);                                     

           }     

           console.log($scope.lisMedSymio[lista].segmentable);           

           $scope.med.descripcion      = $scope.lisMedSymio[lista].Descripcion;

           $scope.med.almacen          = $scope.lisMedSymio[lista].almacen;

           $scope.med.idMedicamento    = $scope.lisMedSymio[lista].Clave_producto;

           $scope.med.existencia       = $scope.lisMedSymio[lista].id;                

         }            

     }

    }

    

    $scope.seleccionaOrtesis = function(ortesis){  

    

         for(lista in $scope.lisrtOrtSymio){

           console.log(ortesis); 

           if(ortesis==$scope.lisrtOrtSymio[lista].Clave_producto){                 

             $scope.ortesisSym.presentacion     = $scope.lisrtOrtSymio[lista].presentacion;

             $scope.ortesisSym.indicaciones     = $scope.lisrtOrtSymio[lista].posologia;

             $scope.ortesisSym.stock            = $scope.lisrtOrtSymio[lista].Stock;

             $scope.ortesisSym.descripcion      = $scope.lisrtOrtSymio[lista].Descripcion;

             $scope.ortesisSym.almacen          = $scope.lisrtOrtSymio[lista].almacen;

             $scope.ortesisSym.idMedicamento    = $scope.lisrtOrtSymio[lista].Clave_producto;

             $scope.ortesisSym.existencia       = $scope.lisrtOrtSymio[lista].id;                

           }            

         }

     } 

     

     $scope.guardaMedicamentoSymio= function(){                     

        if($scope.med.cantidad <= $scope.med.stock){             

          $scope.validaStock=false;

          $scope.cargador=true;    

          $scope.spinner = 'csspinner traditional';           

           $http({

            url:' http://api.medicavial.mx/api/operacion/reserva/item',

            method:'POST', 

            contentType: 'application/json', 

            dataType: "json", 

            data: {id_item:$scope.med.idMedicamento,id_almacen:$scope.med.almacen,NS_cantidad:$scope.med.cantidad}

            }).success( function (data){                     

               $scope.med.reserva= data;  

               $http({

                url:'api/notaMedica.php?funcion=guardarMedicamentosNota&fol='+$rootScope.folio+'&uni='+$scope.unidad+'&usr='+$rootScope.usrLogin+'&tipoReceta=1',

                method:'POST', 

                contentType: 'application/json', 

                dataType: "json", 

                data: $scope.med

                }).success( function (data){ 

                  console.log(data);

                  $scope.spinner = ''; 

                    $scope.med.stock=$scope.med.stock-$scope.med.cantidad;

                    for(lista in $scope.lisMedSymio){

                      if($scope.med.sustAct==$scope.lisMedSymio[lista].Clave_producto){

                        $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;

                        $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;

                        $scope.lisMedSymio[lista].Stock=$scope.med.stock;

                      }            

                    }

                    $scope.listaMedicamentosSymio=data;

                    $scope.formularios.medicSymio.$submitted=false; 

                    $scope.cargador=false; 

                    $scope.siguienteMed=false;  

                     $scope.med={

                    sustAct:'',

                    medicame:'',

                    presentacion:'',

                    cantidad:1,

                    posologia:''          

                  }                                                          

                }).error( function (xhr,status,data){

                  $scope.spinner = ''; 

                  $scope.cargador=true;

                    $scope.mensaje ='no entra';            

                    alert('Error');

                });                    

            }).error( function (xhr,status,data){

              $scope.cargador=true;

              $scope.spinner = ''; 

                $scope.mensaje ='no entra';            

                alert('Error');

            }); 

            

        }else{

          $scope.validaStock=true;

        }

    } 

    

    $scope.eliminarMedicamentoSymio = function(cveReserva,cveItemReceta){ 

      $scope.cargador=true; 

      $scope.spinner = 'csspinner traditional'; 

      $http({

          method: 'DELETE',

          url: 'http://api.medicavial.mx/api/operacion/reserva/'+cveReserva

      }).success(function(data, status, headers, config) {

          console.log(cveItemReceta);

         

          $http({

              url:'api/notaMedica.php?funcion=eliminarMedicamentosNota&cveItemReceta='+cveItemReceta+'&usr='+$rootScope.usrLogin,

              method:'POST', 

              contentType: 'application/json', 

              dataType: "json", 

              data: {cve:'valor'}

              }).success( function (data){                                                

                if(!data.respuesta){                                

                      if(data==''){

                        $scope.listaMedicamentosSymio=''; 

                        $scope.siguienteMed=true;                   

                      }else{                                                           

                        $scope.listaMedicamentosSymio=data;

                        $scope.med.stock=parseInt($scope.med.stock)+parseInt($scope.med.cantidad); 

                        for(lista in $scope.lisMedSymio){

                          if($scope.med.sustAct==$scope.lisMedSymio[lista].Clave_producto){

                            $scope.med.presentacion=$scope.lisMedSymio[lista].Sym_forma_far;

                            $scope.med.posologia = $scope.lisMedSymio[lista].Sym_indicacion;

                            $scope.lisMedSymio[lista].Stock=$scope.med.stock;

                          }            

                        }                 

                    }

                    $scope.med.medicame='';

                    $scope.med.sustAct='';

                    $scope.med.presentacion='';

                    $scope.med.cantidad=1;

                    $scope.med.posologia=''; 

                    $scope.med.stock='';             

                    $scope.cargador=false;  

                    $scope.spinner = '';                                                                                        

                }              

                else{                

                  alert('error en la inserción');

                }           

              }).error( function (xhr,status,data){

                  $scope.mensaje ='no entra';            

                  alert('Error');

              });     

      }).error(function(data, status, headers, config) {

          if (status === 400) {

              defered.reject(data);

          } else {

              throw new Error("Fallo obtener los datos:" + status + "\n" + data);

          }

      });                            

    }

    

    

     $scope.guardaOrtesisSymio= function(){        

        if($scope.ortesisSym.stock>=$scope.ortesisSym.cantidad){

          console.log($scope.ortesisSym);

          $scope.validaStockOrtesisSym=false;

          $scope.cargador1=true; 

          $scope.spinner = 'csspinner traditional'; 

            $http({

            url:' http://api.medicavial.mx/api/operacion/reserva/item',

            method:'POST', 

            contentType: 'application/json', 

            dataType: "json", 

            data: {id_item:$scope.ortesisSym.idMedicamento,id_almacen:$scope.ortesisSym.almacen,NS_cantidad:$scope.ortesisSym.cantidad}

            }).success( function (data){                           

               $scope.ortesisSym.reserva= data;  

               $http({

                url:'api/notaMedica.php?funcion=guardarOrtesisNota&fol='+$rootScope.folio+'&uni='+$scope.unidad+'&usr='+$rootScope.usrLogin+'&tipoReceta=1',

                method:'POST', 

                contentType: 'application/json', 

                dataType: "json", 

                data: $scope.ortesisSym

                }).success( function (data){                         

                    $scope.listaOrtesisSymio=data;

                    $scope.cargador1=false; 

                    $scope.siguienteOrt=false;  

                    $scope.spinner = '';                          

                    $scope.ortesisSym.stock=$scope.ortesisSym.stock-$scope.ortesisSym.cantidad; 

                    for(lista in $scope.lisrtOrtSymio){

                      if($scope.ortesisSym.ortSymio==$scope.lisrtOrtSymio[lista].Clave_producto){

                        $scope.ortesisSym.presentacion=$scope.lisrtOrtSymio[lista].Sym_forma_far;

                        $scope.ortesisSym.posologia = $scope.lisrtOrtSymio[lista].Sym_indicacion;

                        $scope.lisrtOrtSymio[lista].Stock=$scope.ortesisSym.stock;

                        console.log($scope.lisrtOrtSymio[lista].Stock);

                      }            

                    }  

                    console.log($scope.lisrtOrtSymio);

                     $scope.ortesisSym={

                        ortSymio:'',

                        cantidad:1,

                        indicaciones:'',

                        stock:''

                      }                                                    

                }).error( function (xhr,status,data){

                  $scope.cargador1=true;

                    $scope.mensaje ='no entra';            

                    alert('Error');

                });                    

            }).error( function (xhr,status,data){

              $scope.cargador1=true;

                $scope.mensaje ='no entra';            

                alert('Error en la base de serch');

            });

        }else{

          $scope.validaStockOrtesisSym=true;

        }

    } 

    $scope.eliminarOrtesisSymio = function(cveReserva,cveItemReceta,id_item){ 

      $scope.cargador1=true;    

      $scope.spinner = 'csspinner traditional';                

      $http({

          method: 'DELETE',

          url: 'http://api.medicavial.mx/api/operacion/reserva/'+cveReserva

      }).success(function(data, status, headers, config) {

          console.log(cveItemReceta);

          $http({

              url:'api/notaMedica.php?funcion=eliminarOrtesisNota&cveItemReceta='+cveItemReceta+'&usr='+$rootScope.usrLogin,

              method:'POST', 

              contentType: 'application/json', 

              dataType: "json", 

              data: {cve:'valor'}

              }).success( function (data){                                                

                if(!data.respuesta){                                              

                    if(data==''){

                      $scope.listaOrtesisSymio=''; 

                      $scope.siguienteMed=true;                   

                    }else{                                                           

                      $scope.listaOrtesisSymio=data;                              

                    }

                    $scope.ortesisSym.stock=parseInt($scope.ortesisSym.stock)+parseInt($scope.ortesisSym.cantidad); 

                    for(lista in $scope.lisrtOrtSymio){

                      if(id_item==$scope.lisrtOrtSymio[lista].Clave_producto){

                        $scope.ortesisSym.presentacion=$scope.lisrtOrtSymio[lista].Sym_forma_far;

                        $scope.ortesisSym.posologia = $scope.lisrtOrtSymio[lista].Sym_indicacion;

                        $scope.lisrtOrtSymio[lista].Stock=$scope.ortesisSym.stock;                              

                      }            

                    }                                               

                    $scope.cargador1=false;

                    $scope.spinner = '';      

                    $scope.ortesisSym={

                      ortSymio:'',

                      cantidad:1,

                      indicaciones:'',

                      stock:''

                    }                                                                                          

                }              

                else{                

                  alert('error en la inserción');

                }           

              }).error( function (xhr,status,data){

                  $scope.mensaje ='no entra';            

                  alert('Error');

              });     

      }).error(function(data, status, headers, config) {

          if (status === 400) {

              defered.reject(data);

          } else {

              throw new Error("Fallo obtener los datos:" + status + "\n" + data);

          }

      });

    }

    

    

      $scope.filtraOrtesisVacios = function () {

          return function (item) {

              if (item.Stock > 0)

              {                  

                  return true;                                             

              }

              return false;

          };

      };

    

      $scope.itemSeleccionado = function(claveItem){  

        console.log(claveItem);

    

        $scope.itemParticulares.cantidad=0;

    

        for(lista in $scope.listadoItems){

          if(claveItem==$scope.listadoItems[lista].Clave_producto){              

            $scope.itemParticulares.presentacion     = $scope.listadoItems[lista].presentacion;

            $scope.itemParticulares.indicaciones     = $scope.listadoItems[lista].posologia;

            $scope.itemParticulares.stock            = parseInt($scope.listadoItems[lista].Stock);

            $scope.itemParticulares.descripcion      = $scope.listadoItems[lista].Descripcion;

            $scope.itemParticulares.almacen          = $scope.listadoItems[lista].almacen;

            $scope.itemParticulares.idMedicamento    = $scope.listadoItems[lista].Clave_producto;

            $scope.itemParticulares.existencia       = $scope.listadoItems[lista].id;

            $scope.itemParticulares.tipoItem         = $scope.listadoItems[lista].tipoItem;

            console.log($scope.itemParticulares);

          }            

        }

      }       

    

      $scope.verIndicacionCam = function(){            

          if($scope.indicacion.obs=='' || $scope.indicacion.obs==null){

            $scope.indicacion.obs=$scope.indicacion.indicacion;

          }else{

            $scope.indicacion.obs=$scope.indicacion.obs+', '+$scope.indicacion.indicacion;

          }

      }  


      $scope.blancos = function(){            


        console.log($scope.total.check);

        if($scope.total.check==true){
          $scope.ejercicios = [
            {ruta:'imgs/pdf.png',nombre:'Cadera y Rodilla',nom:'DRM_Eje_caderaRodilla',ubicacion:'ejercicios/Cadera_Rodilla.pdf', variable:false},
            {ruta:'imgs/pdf.png',nombre:'Higiene de Columna',nom:'DRM_Eje_Hcolumna',ubicacion:'ejercicios/Higiene_Columna.pdf', variable:false},
            {ruta:'imgs/pdf.png',nombre:'Columna Cervical',nom:'DRM_Eje_CVertebral',ubicacion:'ejercicios/Columna_Cervical.pdf', variable:false},
            {ruta:'imgs/pdf.png',nombre:'Hombro',nom:'DRM_Eje_hombro',ubicacion:'ejercicios/Hombro.pdf', variable:false},
            {ruta:'imgs/pdf.png',nombre:'Codo, Mano , y Muñeca',nom:'DRM_Eje_cmm',ubicacion:'ejercicios/Codo_Mano_Muneca.pdf', variable:false},
            {ruta:'imgs/pdf.png',nombre:'Tobillo y Pie',nom:'DRM_Eje_TP',ubicacion:'ejercicios/Tobillo_Pie.pdf', variable:false},
            {ruta:'imgs/pdf.png',nombre:'Columna Dorsolumbar',nom:'DRM_Eje_CDorsolumbar',ubicacion:'ejercicios/Columna_Dorsolumbar.pdf', variable:false}
        ];
        }
        

    }  

    $scope.quitarNinguno = function(val){  
      console.log(val);          
      if(val==true){
        console.log('paaaaar favor');
        $scope.blanco=false;
      }
    }

    $scope.cambiar = function () {
      console.log($scope.total);
      angular.forEach($scope.ejercicios, function (v, i) {
          v.seleccionado = $scope.total;
      });
    }

    $scope.terminarAtencion = function () {
      $scope.spinner = 'csspinner traditional';     
      $http.get('api/movil.php?funcion=terminarAtencion&fol='+$rootScope.folio).success(function (data){ 
        console.log(data);
        if(data=='exito'){
          $scope.spinner = '';
          $scope.spinnerDiv = "row";
            $scope.mensajeFinalizado =true;
            swal({title:"Alerta!", 
                    text:"La atención a finalizado correctamente!!!!",  
                    icon: "success",
                    type: "success",
                    confirmButtonColor: "#DD6B55", 
                    ConfirmButtonText: "Cerrar",
                    dangerMode: true},
                    function(isConfirm) {
                      if (isConfirm) {
                        window.location.href = "#/inicio";
                      } 
                    });
            
        }else{
          $scope.spinner = '';
          $scope.spinnerDiv = "row";
          alert("error al insertar los datos");
        }                                                                                                 
      });  
      
    }

    

     

        

    

    

            // $scope.imprimirReceta = function(){

            //   $scope.trabajando=true;

            //     console.log('entro');           

            //     var fileName = 'RecetaParticulares-'+$rootScope.folio;

            //     var uri = 'api/classes/formatoRecetaParticulares.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$scope.unidad;

    

            //     var link = document.createElement("a");    

            //     link.href = uri;

                

            //     //set the visibility hidden so it will not effect on your web-layout

            //     link.style = "visibility:hidden";

            //     link.download = fileName + ".pdf";

                

            //     //this part will append the anchor tag and remove it after automatic click

            //     document.body.appendChild(link);

            //     link.click();           

            //     document.body.removeChild(link);

            //     // window.open('../registro/DigitalesSistema/'+$rootScope.folio+'/RecetaCE_'+$rootScope.folio+'.pdf');

    

            //     $scope.getRecetaInterna();

            //     $scope.getRecetaExterna();

            //     $scope.getIndicacionesParticulares();

            //     $location.path("/documentos");

            //     $scope.trabajando=false;

            // }

        
    

      function httpRequestHandler () {

              $scope.cargadorMed=true;

              $scope.recargarMed=true;

              $scope.claseSpinner="panel-body csspinner standard";

              var timeout = $q.defer(),

                  result = $q.defer(),

                  timedOut = false,

                  httpRequest;         

              setTimeout(function () {

                  timedOut = true;

                  timeout.resolve();

              }, (10000 * $scope.timeout));

              

                $scope.url='http://api.medicavial.mx/api/busquedas/existencias/unidad/'+$scope.unidad+'/1';

                    

              httpRequest = $http({

                  method : 'get',

                  url: $scope.url,                               

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

                  $scope.cargadorMed=false;

                  var events = [];

                  for(lista in data){

                    if(data[lista].Stock>0){ 

                      if(data[lista].segmentable==0){

                        if(data[lista].Caja>0){ 

                          if((data[lista].Stock/data[lista].Caja)>1){ 

                            events.push(data[lista]);

                          }                                                                                                            

                        }else{

                          events.push(data[lista]);

                        }

                      }else{

                        events.push(data[lista]);

                      }          

                    }            

                  }

                  console.log(events);

                  $scope.lisMedSymio=events;  

                  $scope.claseSpinner="panel-body";  

                  }

              });

        httpRequest.error(function(data, status, headers, config) { 

                  if($scope.segundaRecarga==2){

                    if(data==''||data==null){

                      $scope.recargarMed=false;                                      

                      $('#myModal').modal();      

                    }else{

                    var events = [];

                    for(lista in data){

                      if(data[lista].Stock>0){ 

                        if(data[lista].segmentable==0){

                          if(data[lista].Caja>0){ 

                            if((data[lista].Stock/data[lista].Caja)>1){ 

                              events.push(data[lista]);

                            }                                                                                                            

                          }else{

                            events.push(data[lista]);

                          }

                        }else{

                          events.push(data[lista]);

                        }          

                      }            

                    }

                    console.log(events);

                    $scope.lisMedSymio=events;      

                    $scope.cargadorMed=false;   

                    }

                  }else{                             

                    if(data==''||data==null){

                      $scope.recargarMed=false;

                    }else{

                    var events = [];

                    for(lista in data){

                      if(data[lista].Stock>0){ 

                        if(data[lista].segmentable==0){

                          if(data[lista].Caja>0){ 

                            if((data[lista].Stock/data[lista].Caja)>1){ 

                              events.push(data[lista]);

                            }                                                                                                            

                          }else{

                            events.push(data[lista]);

                          }

                        }else{

                          events.push(data[lista]);

                        }          

                      }            

                    }

                    console.log(events);

                    $scope.lisMedSymio=events;     

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

              }, (10000 * $scope.timeoutOrt));

              

                $scope.url='http://api.medicavial.mx/api/busquedas/existencias/unidad/'+$scope.unidad+'/2';                

               httpRequest = $http({

                  method : 'get',

                  url: $scope.url,                               

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

                  console.log($scope.lisrtOrtSymio); 

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

    

    

    

    $scope.guardaIndicaciones= function(){

      if($scope.formularios.indica.$valid){ 

        $scope.validaPalabraInd= validaPalabrasProhibidasInd($scope.indicacion.indicacion);          

        if($scope.validaPalabraInd==0){

        $scope.msjPalabraProhiInd=false;     

        $scope.cargador2=true;

        $scope.spinnerModal = 'csspinner traditional';

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

              $scope.spinnerModal = '';                                                       

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

        $scope.spinnerModal = 'csspinner traditional';                

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

              $scope.spinnerModal = '';

            }              

            else{                

              alert('error en la inserción');

            }              

          }).error( function (xhr,status,data){

              $scope.mensaje ='no entra';            

              alert('Error');

          });                               

      }      



        /************************************************fin de todo lo de receta ************************************************************************ */







               



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