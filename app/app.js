//inicializamos la aplicacion
var app = angular.module('app', ['ui.bootstrap', 'ngCookies','ngRoute','ngAnimate' ,'mgo-angular-wizard','angularFileUpload','akoenig.deckgrid','ngDialog','ngIdle','datatables','ja.qr','ngMessages','barcodeGenerator','webStorageModule','cgNotify','ngSanitize','ngVideo','ui.calendar','datatables','angularSpinner','angularjs-dropdown-multiselect','angular.filter','ng-sweet-alert','gridshore.c3js.chart', 'ngGrid']);

//configuramos nuestra aplicacion
app.config(function($routeProvider,$idleProvider, $keepaliveProvider){

    //Configuramos la ruta que queremos el html que le toca y que controlador usara
    
    // menu y login general

    $routeProvider.when('/autorizacionZima',{
            templateUrl: 'views/autorizacionZima.html',
            controller : 'autorizacionZimaCtrl'
    });

     $routeProvider.when('/capacitacion',{
            templateUrl: 'views/capacitacion.html',
            controller : 'capacitacionCtrl'
    });
    
    
     $routeProvider.when('/bloqueo',{
            templateUrl: 'views/bloqueo.html',
            controller : 'bloqueoCtrl'
    });

    $routeProvider.when('/home',{
            templateUrl: 'views/home.html',
            controller : 'homeCtrl'
    });

    $routeProvider.when('/soloRehabilitacion',{
        templateUrl: 'views/soloRehabilitacion.html',
        controller : 'soloRehabilitacionCtrl'
    });

    $routeProvider.when('/buscarRecibo',{
        templateUrl: 'views/buscarRecibo.html',
        controller : 'buscarReciboCtrl'
});

    $routeProvider.when('/recibos',{
        templateUrl: 'views/recibos.html',
        controller : 'recibosCtrl'
    });

    $routeProvider.when('/pcovid',{
        templateUrl: 'views/pcovid.html',
        controller : 'pcovidCtrl'
    });

    $routeProvider.when('/pconvenio',{
        templateUrl: 'views/pconvenio.html',
        controller : 'pconvenioCtrl'
    });

    $routeProvider.when('/items',{
        templateUrl: 'views/items.html',
        controller : 'itemsCtrl'
    });

    $routeProvider.when('/items1',{
        templateUrl: 'views/items1.html',
        controller : 'items1Ctrl'
    });

    $routeProvider.when('/cancelacion',{
        templateUrl: 'views/cancelacion.html',
        controller : 'cancelacionCtrl'
    });

    $routeProvider.when('/login',{
            templateUrl: 'views/login.html',
            controller : 'loginCtrl'
    });

    $routeProvider.when('/material',{
            templateUrl: 'views/material.html',
            controller : 'materialCtrl'
    });

    $routeProvider.when('/reportes',{
            templateUrl: 'views/reportes.html',
            controller : 'reportesCtrl'           
           
    });

    $routeProvider.when('/reporteMedicoSustituto',{
        templateUrl: 'views/reporteMedicoSustituto.html',
        controller : 'reporteMedicoSustitutoCtrl'           
       
});

    $routeProvider.when('/reporteQualitas',{
            templateUrl: 'views/reporteQualitas.html',
            controller : 'reporteQualitasCtrl'           
           
    });

    $routeProvider.when('/listadoSinDoc',{
            templateUrl: 'views/listadoSinDoc.html',
            controller : 'listadoSinDocCtrl'           
           
    });

    $routeProvider.when('/reporteMembresias',{
            templateUrl: 'views/reporteMembresias.html',
            controller : 'reporteMembresiasCtrl'   
    });

    $routeProvider.when('/informeRehabilitacion',{
            templateUrl: 'views/informeRehabilitacion.html',
            controller : 'informeRehabilitacionCtrl'   
    });

    $routeProvider.when('/listaInfRehab',{
            templateUrl: 'views/listaInfRehab.html',
            controller : 'listaInfRehabCtrl'   
    });

    $routeProvider.when('/logCambios',{
            templateUrl: 'views/logCambios.html',
            controller : 'logCambiosCtrl'   
    });
   
    $routeProvider.when('/solicitudPlantillas',{
            templateUrl: 'views/solicitudPlantillas.html',
            controller : 'solicitudPlantillasCtrl'   
    });

    $routeProvider.when('/solicitudesPlantillas',{
            templateUrl: 'views/solicitudesPlantillas.html',
            controller : 'solicitudesPlantillasCtrl'   
    });

    $routeProvider.when('/entregaTurno',{
            templateUrl: 'views/entregaTurno.html',
            controller : 'entregaTurnoCtrl'   
    });
    




    // apertura de un expediente y seguimiento
    $routeProvider.when('/registro',{
            templateUrl: 'views/registro.html',
            controller : 'registroCtrl'
    });
    $routeProvider.when('/aperturaExp/:opcion',{
            templateUrl: 'views/aperturaExp.html',
            controller : 'aperturaExpCtrl'
    });
    $routeProvider.when('/producto',{
            templateUrl: 'views/producto.html',
            controller : 'productoCtrl'
    });
    $routeProvider.when('/registra',{
            templateUrl: 'views/registra.html',
            controller : 'registraCtrl'           
    });
    $routeProvider.when('/portada',{
            templateUrl: 'views/portada.html',
            controller : 'portadaCtrl'           
    });
    $routeProvider.when('/busqueda',{
            templateUrl: 'views/busqueda.html',
            controller : 'busquedaCtrl'           
    });
    $routeProvider.when('/busqueda/unidad',{
            templateUrl: 'views/busquedaUni.html',
            controller : 'busquedaUniCtrl'           
    });
    $routeProvider.when('/documentos',{
            templateUrl: 'views/documentos.html',
            controller : 'documentosCtrl'           
    });
    $routeProvider.when('/documentosMovil',{
        templateUrl: 'views/documentosMovil.html',
        controller : 'documentosMovilCtrl'           
    });
    $routeProvider.when('/documentosCovid',{
        templateUrl: 'views/documentosCovid.html',
        controller : 'documentosCovidCtrl'           
    });
    $routeProvider.when('/adminMovil',{
        templateUrl: 'views/adminMovil.html',
        controller : 'adminMovilCtrl'           
    });
    $routeProvider.when('/historiaClinica',{
            templateUrl: 'views/historiaClinica.html',
            controller : 'historiaClinicaCtrl'           
    });

    $routeProvider.when('/historiaClinicaMovil',{
            templateUrl: 'views/historiaClinicaMovil.html',
            controller : 'historiaClinicaMovilCtrl'           
    });

    $routeProvider.when('/historiaClinicaCovid',{
        templateUrl: 'views/historiaClinicaCovid.html',
        controller : 'historiaClinicaCovidCtrl'           
});

    $routeProvider.when('/listadoMovil',{
        templateUrl: 'views/listadoMovil.html',
        controller : 'listadoMovilCtrl'           
});
    
    $routeProvider.when('/signosVitales',{
            templateUrl: 'views/signosVitales.html',
            controller : 'signosVitalesCtrl'           
    });

    $routeProvider.when('/signosVitalesSub',{
            templateUrl: 'views/signosVitalesSub.html',
            controller : 'signosVitalesSubCtrl'           
    });

    $routeProvider.when('/notaMedica',{
            templateUrl: 'views/notaMedica.html',
            controller : 'notaMedicaCtrl'           
    });
    $routeProvider.when('/notaMedicaMovil',{
        templateUrl: 'views/notaMedicaMovil.html',
        controller : 'notaMedicaMovilCtrl'           
});
    $routeProvider.when('/notaMedicaCovid',{
        templateUrl: 'views/notaMedicaCovid.html',
        controller : 'notaMedicaCovidCtrl'           
    });
    $routeProvider.when('/subsecuencia',{
            templateUrl: 'views/subsecuencia.html',
            controller : 'subsecuenciaCtrl'           
    });
    $routeProvider.when('/subsecuenciaListado',{
            templateUrl: 'views/subsecuenciaListado.html',
            controller : 'subsecuenciaListadoCtrl'           
    });

    $routeProvider.when('/rehabilitacion',{
            templateUrl: 'views/rehabilitacion.html',
            controller : 'rehabilitacionCtrl'           
    });
    $routeProvider.when('/rehabilitacionForm',{
            templateUrl: 'views/rehabilitacionForm.html',
            controller : 'rehabilitacionFormCtrl'           
    });

    $routeProvider.when('/reciboParticular',{
            templateUrl: 'views/reciboParticular.html',
            controller : 'reciboParticularCtrl'           
    });

    $routeProvider.when('/solicitudFactura',{
            templateUrl: 'views/solicitudFactura.html',
            controller : 'solicitudFacturaCtrl'           
    });

    $routeProvider.when('/cambioUnidad',{
            templateUrl: 'views/cambioUnidad.html',
            controller : 'cambioUnidadCtrl'           
    });

    $routeProvider.when('/cambioUnidadGeneral',{
            templateUrl: 'views/cambioUnidadGeneral.html',
            controller : 'cambioUnidadGeneralCtrl'
    });

     $routeProvider.when('/cobranzaParticulares',{
            templateUrl: 'views/cobranzaParticulares.html',
            controller : 'cobranzaParticularesCtrl'   
    });

    $routeProvider.when('/seguimientoCobranza',{
            templateUrl: 'views/seguimientoCobranza.html',
            controller : 'seguimientoCobranzaCtrl'   
    });

    $routeProvider.when('/constancia',{
            templateUrl: 'views/constancia.html',
            controller : 'constanciaCtrl'           
    });

    $routeProvider.when('/directorio',{
            templateUrl: 'views/directorio.html'            
    });

    $routeProvider.when('/enDirecto',{
            templateUrl: 'views/enDirecto.html',
            controller : 'enDirectoCtrl' 
    });

    $routeProvider.when('/avisos',{
            templateUrl: 'views/avisos.html',
            controller : 'avisosCtrl' 
    });

    $routeProvider.when('/calendario',{
            templateUrl: 'views/calendario.html',
            controller : 'calendarioCtrl'
    });

    $routeProvider.when('/facturaExpress',{
            templateUrl: 'views/facturaExpress.html',
            controller : 'facturaExpressCtrl'
    });

    $routeProvider.when('/convenio',{
            templateUrl: 'views/convenio.html',
            controller : 'convenioCtrl'
    });

    $routeProvider.when('/infoConvenio/:convenio',{
            templateUrl: 'views/infoConvenio.html',
            controller : 'infoConvenioCtrl'           
           
    });

    $routeProvider.when('/infoProducto',{
            templateUrl: 'views/infoProducto.html',
            controller : 'infoProductoCtrl'           
           
    });

    $routeProvider.when('/controlParticulares',{
            templateUrl: 'views/controlParticulares.html',
            controller : 'controlParticularesCtrl'           
           
    });

    $routeProvider.when('/reportePartExtendido',{
            templateUrl: 'views/reportePartExtendido.html',
            controller : 'reportePartExtendidoCtrl'   
    });

    $routeProvider.when('/reporteItemsParticulares',{
            templateUrl: 'views/reporteItemsParticulares.html',
            controller : 'reporteItemsParticularesCtrl'   
    });

    $routeProvider.when('/reporteVentasSinReg',{
            templateUrl: 'views/reporteVentasSinReg.html',
            controller : 'reporteVentasSinRegCtrl'   
    });

    $routeProvider.when('/reporteCompania',{
            templateUrl: 'views/reporteCompania.html',
            controller : 'reporteCompaniaCtrl'   
    });

    $routeProvider.when('/cuestionario',{
            templateUrl: 'views/cuestionario.html',
            controller : 'cuestionarioCtrl'   
    });

     $routeProvider.when('/monitorCuestionario',{
            templateUrl: 'views/monitorCuestionario.html',
            controller : 'monitorCuestionarioCtrl'   
    });

    // apartado de solicitudes 
    $routeProvider.when('/detalle/solicitud/:clave',{
            templateUrl: 'views/solicitudes/detalleSolicitud.html',
            controller : 'detalleSolicitudCtrl'
    });

    $routeProvider.when('/digitalizacion',{
            templateUrl: 'views/digitalizacion.html',
            controller : 'digitalizacionCtrl'
    });

    $routeProvider.when('/solicitudes',{
            templateUrl: 'views/solicitudes/solicitudes.html',
            controller : 'solicitudesCtrl'
    });

    $routeProvider.when('/solicitud',{
            templateUrl: 'views/solicitudes/solicitud.html',
            controller : 'solicitudCtrl'
    });

    $routeProvider.when('/solicitud/:folio',{
            templateUrl: 'views/solicitudes/solicitudexpediente.html',
            controller : 'solicitudExpedienteCtrl'
    });

    $routeProvider.when('/solicitudmasinfo/:clave',{
            templateUrl: 'views/solicitudes/solicitudmasinfo.html',
            controller : 'solicitudMasInfoCtrl'
    });

    $routeProvider.when('/estatusMedico',{
            templateUrl: 'views/estatusMedico.html',
            controller : 'estatusMedicoCtrl'
    });

    $routeProvider.when('/recetaComplementaria',{
            templateUrl: 'views/recetaComplementaria.html',
            controller : 'recetaComplementariaCtrl'
    });
    
    $routeProvider.when('/notaSoap',{
            templateUrl: 'views/notaSoap.html',
            controller : 'notaSoapCtrl'
    });
    
    $routeProvider.when('/notaSoapCE',{ //CORTA ESTANCIA
            templateUrl: 'views/notaSoapCE.html',
            controller : 'notaSoapCECtrl'
    });

    $routeProvider.when('/notaSoapRH',{ //soap RH
            templateUrl: 'views/notaSoapRH.html',
            controller : 'notaSoapRHCtrl'
    });


    $routeProvider.when('/ventasSinRegistro',{
            templateUrl: 'views/ventasSinRegistro.html',
            controller : 'ventasSinRegistroCtrl'   
    });

    $routeProvider.when('/listadoItemsParticulares',{
            templateUrl: 'views/listadoItemsParticulares.html',
            controller : 'listadoItemsParticularesCtrl'
    });

    $routeProvider.when('/rayosX',{
            templateUrl: 'views/rayosX.html',
            controller : 'rayosXCtrl'
    });

    $routeProvider.when('/reporteParticulares',{
            templateUrl: 'views/reporteParticulares.html',
            controller : 'reporteParticularesCtrl'
    });

    $routeProvider.when('/reportesRegistro',{
            templateUrl: 'views/reportesRegistro.html',
            controller : 'reportesRegistroCtrl'   
    });

    $routeProvider.when('/semaforoAxa',{
            templateUrl: 'views/semaforoAxa.html',
            controller : 'semaforoAxaCtrl'   
    });

    $routeProvider.when('/rx',{
            templateUrl: 'views/rx.html',
            controller : 'rxCtrl'
    });

    $routeProvider.when('/recetaSinSurtir',{
            templateUrl: 'views/recetaSinSurtir.html',
            controller : 'recetaSinSurtirCtrl'
    });

    $routeProvider.when('/recetaCortaEstancia',{
            templateUrl: 'views/recetaCortaEstancia.html',
            controller : 'recetaCortaEstanciaCtrl'
    });

    $routeProvider.when('/recetaParticulares',{
            templateUrl: 'views/recetaParticulares.html',
            controller : 'recetaParticularesCtrl'
    });

    $routeProvider.when('/recetaExterna',{
        templateUrl: 'views/recetaExterna.html',
        controller : 'recetaExternaCtrl'
    });
    
    $routeProvider.when('/detalleLesionado',{
            templateUrl: 'views/detalleLesionado.html',
            controller : 'detalleLesionadoCtrl'
    });

     $routeProvider.when('/rxSinAtencion',{
            templateUrl: 'views/rxSinAtencion.html',
            controller : 'rxSinAtencionCtrl'
    });

     $routeProvider.when('/estadoCuenta',{
            templateUrl: 'views/estadoCuenta.html',
            controller : 'estadoCuentaCtrl'
    });

    $routeProvider.when('/registroN',{
            templateUrl: 'views/registroN.html',
            controller : 'registroNCtrl'
    });

    $routeProvider.when('/opcionesRegistro/:opcion',{
            templateUrl: 'views/opcionesRegistro.html',
            controller : 'opcionesRegistroCtrl'
    });

    $routeProvider.when('/promocion',{
            templateUrl: 'views/promocion.html',
            controller : 'promocionCtrl'
    });

    $routeProvider.when('/recetaMovil',{
        templateUrl: 'views/recetaMovil.html',
        controller : 'recetaMovilCtrl'
    });

    $routeProvider.when('/reciboRenta',{
        templateUrl: 'views/reciboRenta.html',
        controller : 'reciboRentaCtrl'
    });

    $routeProvider.when('/listadoReciboRenta',{
        templateUrl: 'views/listadoReciboRenta.html',
        controller : 'listadoReciboRentaCtrl'
    });

    $routeProvider.when('/regPruebaCovid',{
            templateUrl: 'views/regPruebaCovid.html',
            controller : 'regPruebaCovidCtrl'   
    });

    $routeProvider.when('/pruebaCovidPendiente',{
            templateUrl: 'views/pruebaCovidPendiente.html',
            controller : 'pruebaCovidPendienteCtrl'   
    });

    $routeProvider.when('/enviaPruebaCovid',{
            templateUrl: 'views/enviaPruebaCovid.html',
            controller : 'enviaPruebaCovidCtrl'   
    });

    $routeProvider.when('/adminRec',{
            templateUrl: 'views/adminRecibo.html',
            controller : 'adminReciboCtrl'   
    });

    $routeProvider.when('/detalleCobro/:recibo',{
            templateUrl: 'views/detalleCobro.html',
            controller : 'detalleCobroCtrl'   
    });


    $routeProvider.otherwise({redirectTo:'/login'});

    //$locationProvider.html5Mode(true);

    $idleProvider.idleDuration(7200); // tiempo en activarse el modo en reposo 
    $idleProvider.warningDuration(10); // tiempo que dura la alerta de sesion cerrada
    $keepaliveProvider.interval(10); // 

});


//sirve para ejecutar cualquier cosa cuando inicia la aplicacion
app.run(function ($rootScope ,$cookies, $cookieStore, sesion, $location, $idle, $http,notify,$upload, $templateCache){

    
    $rootScope.admin = true;
    $rootScope.bus = true;
    $rootScope.docs = true;
    $rootScope.cerrar = false;
    $rootScope.msjIncidencia=false;
    $rootScope.cargador=false; 
    $rootScope.adminis=true; 
    $rootScope.imagenes=true; 
    $rootScope.particulares=true; 
    $rootScope.cuestionario=true; 
    $rootScope.menuRx=true;
    $rootScope.algo=false;   


    //verifica el tamaño de la pantalle y oculta o muestra el menu
    var mobileView = 992;

    $rootScope.getWidth = function() { return window.innerWidth; };

    $rootScope.$watch($rootScope.getWidth, function(newValue, oldValue)
    {
        if(newValue >= mobileView)
        {
            if(angular.isDefined($cookieStore.get('toggle')))
            {
                if($cookieStore.get('toggle') == false)
                    $rootScope.toggle = false;

                else
                    $rootScope.toggle = true;
            }
            else 
            {
                $rootScope.toggle = true;
            }
        }
        else
        {
            $rootScope.toggle = false;
        }

    });

    $rootScope.toggleSidebar = function() 
    {
        $rootScope.toggle = ! $rootScope.toggle;

        $cookieStore.put('toggle', $rootScope.toggle);
    };

    window.onresize = function() { $rootScope.$apply(); };


    //evento que verifica cuando alguien cambia de ruta
    $rootScope.$on('$routeChangeStart', function(){

        $rootScope.cerrar = false;
        $rootScope.username =  $cookies.username;
        $rootScope.cordinacion =  $cookies.cordinacion;
        $rootScope.uniClave = $cookies.uniClave;
        if ($cookies.permisos  && $rootScope.permisos == undefined) {
            $rootScope.permisos = JSON.parse($cookies.permisos);
        };

        sesion.checkStatus();

    });


    //funcion en angular
    $rootScope.logout = function(){

        sesion.logout();
    } 
    //validacion de formularios para generar membresía
    //
    $rootScope.interacted = function(field) {
      return $rootScope.membresiaForm.$submitted && field.$invalid;
    };
    $rootScope.interacted1 = function(field) {
      return $rootScope.referenciasForm.$submitted && field.$invalid;
    };

    //generamos al rootscope las variables que tenemos en las cookies para no perder la sesion 
    $rootScope.username =  $cookies.username;
    $rootScope.cordinacion =  $cookies.cordinacion;
    $rootScope.uniClave = $cookies.uniClave;
    if ($cookies.permisos  && $rootScope.permisos == undefined) {
        $rootScope.permisos = JSON.parse($cookies.permisos);
    };
    $rootScope.usrLogin = $cookies.usrLogin;

    if($cookies.usrLogin=="algo" || $cookies.usrLogin=="cnolasco"){
        $rootScope.algo=true;
    }

    $rootScope.incidencias={
        tipo:'',
        severidad:'',
        observaciones:'',
        acciones:'',
        archivo:'',
        temporal:'',
        uniIncidencia:''
    }
    $rootScope.btnEnvio = false;

    $rootScope.verForm= true;
    $rootScope.nombreMembresia='';
    $rootScope.cargadorMem = false;
    $rootScope.cargador1 = false;
    $rootScope.cargadorArchivo =false;
    $rootScope.cargadorInc =false;

    $rootScope.status = 'Not started';
    $rootScope.loading = false;
    
    $rootScope.membresia={
        nombre:'',
        apanterno:'',
        amaterno:'',
        email:'',
        telefono:'',
        codPos:'',
        obs:''
    }

    $rootScope.referencia={
        nombre:'',
        email:'',
        telefono:'',
        parentezco:''
    }
    $rootScope.veTabla=false; 
    $rootScope.sumaReferencia =[] ;  

    var cont=1;
    $rootScope.veTabla=false;
    $rootScope.agregaParentezco = function(){ 
        if($rootScope.referenciasForm.$valid){ 
        if($rootScope.referencia.nombre!=''){             
            $rootScope.msjTel=false;
            $rootScope.cont ={};           
            if($rootScope.sumaReferencia==undefined){
               $rootScope.veTabla=false; 
            }else{
                $rootScope.veTabla=true; 
            }          
            $rootScope.cont.nombre=$rootScope.referencia.nombre;
            $rootScope.cont.email=$rootScope.referencia.email;
            $rootScope.cont.telefono=$rootScope.referencia.telefono;
            $rootScope.cont.parentezco=$rootScope.referencia.parentezco; 
            $rootScope.cont.cont=cont;            
            $rootScope.sumaReferencia.push($rootScope.cont); 
            $rootScope.referencia={
                nombre:'',
                email:'',
                parentezco:''
            } 
            $rootScope.referenciasForm.$submitted=false;                        
            cont++; 
            
        }else{
            $rootScope.msjTel=true;
        }
    }
    }

    $rootScope.eliminaParentezco = function(contaTel){  
        console.log('entró');
        contaTel=contaTel-1;
        delete $rootScope.sumaReferencia[contaTel]; 
        $rootScope.sumaReferencia.splice(contaTel,1);               
        console.log($rootScope.sumaReferencia);
    }

    $rootScope.codigoE=false;  
    $rootScope.verificaCodigo = function(){                         
            if($rootScope.membresia.codPos!='00000'){
            $http.get('api/api.php?funcion=codExiste&cod='+$rootScope.membresia.codPos).success(function (data){                
                if(data=='noExiste'){
                    $rootScope.codigoE=true;    
                }else{
                    $rootScope.codigoE=false;    
                }                
            });  
            }else{
                $rootScope.codigoE=false;
            }
    }
    $rootScope.mensajeMembresia=false;
    $rootScope.crearMembresia = function(){  
        $rootScope.validar=false;
        if($rootScope.membresiaForm.$valid){
        $rootScope.cargadorMem=true; 
        $rootScope.mensaje = '';       
        if($rootScope.sumaReferencia!=''){
           $rootScope.todo= [$rootScope.membresia,$rootScope.sumaReferencia];
        }else{
            $rootScope.todo=[$rootScope.membresia];
        }          
        /*$http.get('api/convenio.php?funcion=setMembresiaSinFol&uni='+$rootScope.uniClave+'&nombre='+$rootScope.nombreMembresia).success(function (data){                        
            console.log(data);
            $rootScope.cargadorMem=false;    
            $rootScope.verForm= false;          
            $rootScope.membresiaCreada = data;
        }); */ 

       

        $http({
            url:'api/convenio.php?funcion=guardarMembresia&usr='+$cookies.usrLogin+'&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $rootScope.todo
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
                $rootScope.cargadorMem=false;              
                $rootScope.mensajeMembresia=true;
                $rootScope.nombreMem    = data.mem_completo;
                $rootScope.folioMem     = data.mem_folio;
                $rootScope.serieMem     = data.mem_serie;
                $rootScope.anioMem      = parseInt(data.mem_anio) + 1;
                $rootScope.mesMem       = n;

                $rootScope.membresiaForm.$submitted=false; 
                $rootScope.sumaReferencia='';   

            }).error( function (xhr,status,data){
                $rootScope.mensaje ='no entra';            
                alert('Error');
            });  
        }          
    } 

    $rootScope.reactivarFormulario = function(){  
        $rootScope.mensajeMembresia=false;
         $rootScope.referencia={
               
            }         
        $rootScope.membresia={
            nombre:'',
            apanterno:'',
            amaterno:'',
            email:'',
            telefono:'',
            codPos:'',
            obs:''
        }    
    } 

    $rootScope.onFileSelect_xml = function($files) {
    $rootScope.cargadorArchivo =true;
    $rootScope.btnEnvio = true;
    for (var i = 0; i < $files.length; i++) {
        var file = $files[i];
        $rootScope.archivo=file;
        $rootScope.variable = 2;
        var amt = 0;
        //$files: an array of files selected, each file has name, size, and type.
        $rootScope.upload = $upload.upload({
            url: 'api/api.php?funcion=archivo_temporal', //upload.php script, node.js route, or servlet url
            method: 'POST',
            //headers: {'header-key': 'header-value'},
            //withCredentials: true,
            data: $rootScope.factura,
            file: file, // or list of files ($files) for html5 only
            progress:function(evt) {
                console.log("inside progress");
                            console.log(progress);
            }
        })
        .success(function (data, status, headers, config){                        
            $rootScope.cargadorArchivo =false;
            $rootScope.incidencias.archivo=data.nombre;
            $rootScope.incidencias.temporal=data.temporal;
            $rootScope.btnEnvio = false;

            console.log($rootScope.incidencias.temporal); 
            //console.log($scope.digital.archivo+'--'+$scope.digital.temporal);           
        }).error( function (xhr,status,data){
            alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
        });
    }
}

$rootScope.guardaInsidenciaAdjunto = function(){                 
        $rootScope.cargadorInc =true;
        if($rootScope.incidencias.temporal==''){
            $rootScope.btnEnvio = true;
             
            $http({
            url:'api/api1.php?funcion=guardaIncidencia&usr='+$cookies.usrLogin+'&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $rootScope.incidencias
            }).success( function (data){                 
                 $rootScope.cargadorInc =false;
                if(data!='error'){
                    $rootScope.msjIncidencia=true;
                    $rootScope.incidencias={
                        tipo:'',
                        severidad:'',
                        observaciones:'',
                        acciones:'',
                        uniIncidencia:''
                    }
                }else{
                    alert('error en la inserción!!')
                }
            }).error( function (xhr,status,data){
                $rootScope.mensaje ='no entra';            
                alert('Error');
            });   

        } else{
            $rootScope.btnEnvio = true;            
            $rootScope.msjIncidencia=false;
            $rootScope.upload = $upload.upload({
                url:'api/api1.php?funcion=guardaIncidenciaAdjunto&usr='+$cookies.usrLogin+'&uni='+$rootScope.uniClave
                +'&tipo='+$rootScope.incidencias.tipo+'&severidad='+$rootScope.incidencias.severidad+'&observaciones='+$rootScope.incidencias.observaciones+'&acciones='+$rootScope.incidencias.acciones+'&archivo='
                +$rootScope.incidencias.archivo+'&temporal='+$rootScope.incidencias.temporal+'&uniIncidencia='+$rootScope.incidencias.uniIncidencia,
                method:'POST',             
                data:$rootScope.incidencias,
                file:$rootScope.archivo
                  }).success( function (data, status, headers, config){              
                               
                   $rootScope.cargadorInc =false;
                    /*if(data=="exito"){
                        console.log(data);
                    }else{
                        console.log("No");
                    }*/

                    if(data==1){
                        $rootScope.msjIncidencia=false;
                        $rootScope.btnEnvio = false;
                        $rootScope.msjIncidencia=false;
                        $rootScope.incidencias='';        
                        $('#InsidenciasAdjunto').modal('hide');
                    }else{
                        alert('error en la inserción!!')
                    }
                  }).error( function (xhr,status,data){            
                    
                    alert('Error');
                  });                 

            }    
        
        /*$http({
            url:'api/api1.php?funcion=guardaIncidenciaAdjunto&usr='+$cookies.usrLogin+'&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $rootScope.incidencias,
            file: $rootScope.archivo
            }).success( function (data){                 
                console.log(data);
                /*$rootScope.cargador=false;
                if(data=='exito'){
                    $rootScope.msjIncidencia=true;
                    $rootScope.incidencias={
                        tipo:'',
                        severidad:'',
                        observaciones:''
                    }
                }else{
                    alert('error en la inserción!!')
                }*/
            /*}).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); */  
    }

     $rootScope.verUnidades=false;
     $rootScope.abreUnidades = function(claveInsidencia){
        if(claveInsidencia==19||claveInsidencia==20){
            $rootScope.verUnidades=true;
        }else{
            $rootScope.verUnidades=false;
        }
     }     
     $rootScope.limpiarDatos = function(){
        $rootScope.btnEnvio = false;
        $rootScope.incidencias={
            tipo:'',
            severidad:'',
            observaciones:'',
            acciones:'',
            archivo:'',
            temporal:'',
            uniIncidencia:''
        }
     }
     $rootScope.guardaInsidencia = function(){                 
        $rootScope.btnEnvio = true;
        $rootScope.cargadorInc =true;
        $http({
            url:'api/api1.php?funcion=guardaIncidencia&usr='+$cookies.usrLogin+'&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $rootScope.incidencias
            }).success( function (data){                 
                $rootScope.cargadorInc=true;
                if(data=='exito'){
                    $rootScope.msjIncidencia=true;
                    $rootScope.incidencias={
                        tipo:'',
                        severidad:'',
                        observaciones:'',
                        uniIncidencia:''
                    }
                }else{
                    alert('error en la inserción!!')
                }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });   
    }
    $rootScope.generarMembresia = function(){  
         $rootScope.cargadorMem=true;                              
        $http.get('api/convenio.php?funcion=setMembresiaSinFol&uni='+$rootScope.uniClave+'&nombre='+$rootScope.nombreMembresia).success(function (data){                        
            console.log(data);
            $rootScope.cargadorMem=false;    
            $rootScope.verForm= false;          
            $rootScope.membresiaCreada = data;
        });           
    }

    $rootScope.mem={
        folio:'',
        nombre: ''
    }
    $rootScope.cargadorListMem=false;

     $rootScope.buscarMembresia = function(){ 

        $rootScope.cargadorListMem=true;                            
        $http({
            url:'api/convenio.php?funcion=getMembresia',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $rootScope.mem
            }).success( function (data){                   
                console.log(data);
                $rootScope.cargadorListMem=false;  
                $rootScope.listadoMemebresias = data;
            }).error( function (xhr,status,data){
                $rootScope.mensaje ='no entra';                            
            });                            
    }

    $rootScope.recargarNavegador = function(){ 
        $templateCache.removeAll(); 
        $http.get('api/convenio.php?funcion=actualizaNavegador&usr='+$cookies.usrLogin).success(function (data){ 
            $templateCache.removeAll(); 
            location.reload();            
        });           
    }

     //videos de ayuda para los médicos 

    $rootScope.abreVideo = function(video){
     $('#pruebaModal').modal('show');
        $rootScope.ruta='';
        $rootScope.titulo='';
        switch(video){
            case 1:
            $rootScope.ruta='views/videosMedico/1.html';
            $rootScope.titulo='1.- Dolor de Cuello';
            break;
            case 2:
            $rootScope.ruta='views/videosMedico/2.html';
            $rootScope.titulo='2.- Dolor de Espalda';
            break;
            case 3:
            $rootScope.ruta='views/videosMedico/3.html';
            $rootScope.titulo='3.- Dolor de Hombro';
            break;
            case 4:
            $rootScope.ruta='views/videosMedico/4.html';
            $rootScope.titulo='4.- Dolor Lumbar';
            break;
            case 5:
            $rootScope.ruta='views/videosMedico/5.html';
            $rootScope.titulo='5.- Esguince de Muñeca';
            break;
            case 6:
            $rootScope.ruta='views/videosMedico/6.html';
            $rootScope.titulo='6.- Esguince de Rodilla';
            break;
            case 7:
            $rootScope.ruta='views/videosMedico/7.html';
            $rootScope.titulo='7.- Esguince de Tobillo';
            break;
            case 8:
            $rootScope.ruta='views/videosMedico/8.html';
            $rootScope.titulo='8.- Fractura de Codo';
            break;
            case 9:
            $rootScope.ruta='views/videosMedico/9.html';
            $rootScope.titulo='9.- Fractura de Mano y Muñeca';
            break;
            case 10:
            $rootScope.ruta='views/videosMedico/10.html';
            $rootScope.titulo='10.- Fractura de Pie y Tobillo';
            break;
            case 11:
            $rootScope.ruta='views/videosMedico/11.html';
            $rootScope.titulo='11.- Fractura de Rodilla';
            break;
            case 12:
            $rootScope.ruta='views/videosMedico/12.html';
            $rootScope.titulo='12.- Traumatismo Craneoencefálico Grado I';
            break;           
        }
        console.log($rootScope.ruta);
    }
    $rootScope.pausarVideo = function(){
        var v = document.getElementsByTagName("video")[0];
     v.pause();
    }


    /////fin de videos de ayuda para medicos


    //verificamos el estatus del usuario en la aplicacion
    $idle.watch();

    $rootScope.$on('$idleStart', function() {
        // the user appears to have gone idle  
        
        if($location.path() != "/login"){
           
        }                
    });

    $rootScope.$on('$idleWarn', function(e, countdown) {
        // follows after the $idleStart event, but includes a countdown until the user is considered timed out
        // the countdown arg is the number of seconds remaining until then.
        // you can change the title or display a warning dialog from here.
        // you can let them resume their session by calling $idle.watch()
        if($location.path() != "/login"){
            //console.log('Cuidado se va a bloquear');
        }
         
    });

    $rootScope.$on('$idleTimeout', function() {
       //Entra en el estado de reposo cerramos session guardamos la ultima ruta en la que se encontraba
       //ademas de verificar si no estaban en la pagina del login ni en la de bloqueo 
        if($location.path() != "/login" && $location.path() != "/solicitud" && $location.path() != '/notaMedica' && $location.path() != '/registra'&& $location.path() != '/subsecuencia'&& $location.path() != '/rehabilitacion'){

            if ($location.path() != "/bloqueo") {

                $rootScope.ruta = $location.path(); //Guardamos 
                $location.path('/bloqueo');
            };
        
        }
        
    })

    $rootScope.$on('$idleEnd', function() {
        // the user has come back from AFK and is doing stuff. if you are warning them, you can use this to hide the dialog 
         
        if($location.path() != "/login"){
            //console.log('llegaste bienvenido');
        }
    });

    $rootScope.$on('$keepalive', function() {
        // do something to keep the user's session alive
        if($location.path() != "/login"){
            //console.log('Activo en el sitio'); 
        }
        
    });

});


//servicio que verifica sesiones de usuario
app.factory("sesion", function($cookies,$cookieStore,$location, $rootScope, $http,notify,$upload)
{
    return{
        login : function(username, password)
        {   
            $http({
                url:'api/api.php?funcion=login',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data:{user:username,psw:password}
            }).success( function (data){

                if (data.respuesta) {
                    $rootScope.mensaje = data.respuesta;
                    $('#boton').button('reset');
                }else{     
                    
                    $('html').removeClass('lockscreen');
                    $('#boton').button('reset');             
                    $rootScope.username = data[0].Usu_nombre;
                    $cookies.username = data[0].Usu_nombre;
                    $cookies.uniClave = data[0].Uni_clave;
                    $cookies.usrLogin = data[0].Usu_login;
                    $cookies.cordinacion = data[0].Cordinacion;
                    $rootScope.actualiza = data[0].Actualizacion;

                    $http({
                        url:'api/api.php?funcion=permisos',
                        method:'POST', 
                        contentType: 'application/json', 
                        dataType: "json", 
                        data:{user:username}
                    }).success( function (data){                        
                        $cookies.permisos=JSON.stringify(data); 
                        $rootScope.permisos=JSON.parse($cookies.permisos);                           
                    });

                    $http({
                        url:'api/api.php?funcion=registraAcceso&usr='+$cookies.usrLogin,
                        method:'POST', 
                        contentType: 'application/json', 
                        dataType: "json", 
                        data:{user:username}
                    }).success( function (data){                                               
                    });

                    $http({
                        url:'api/api.php?funcion=zona',
                        method:'POST', 
                        contentType: 'application/json', 
                        dataType: "json", 
                        data:{unidad:$cookies.uniClave}
                    }).success( function (data){
                       $cookies.zona=data.Zon_clave;                                       
                    });

                    $http({
                        url:'api/api.php?funcion=listaAvisos',
                        method:'POST', 
                        contentType: 'application/json', 
                        dataType: "json", 
                        data:{unidad:$cookies.uniClave}
                    }).success( function (data){                        
                        $rootScope.avisos  = data;
                        console.log($rootScope.avisos);
                        $rootScope.noAviso = $rootScope.avisos.length;
                        $rootScope.msg = 'Tienes '+$rootScope.noAviso+' avisos nuevos';
                    $rootScope.template = '';

                    $rootScope.positions = ['right', 'left', 'center'];
                    $rootScope.position = $rootScope.positions[0];

                    $rootScope.duration = 10000;
                    $rootScope.msjTit='';

                    $rootScope.mensajes='<div class="list-group"><span class="list-group">Mensajes</span>';
                    if($rootScope.avisos){
                    $.each($rootScope.avisos, function(k,v){
                        if($rootScope.mensajes==''){
                            $rootScope.mensajes='- '+v.Aviso_titulo+'<br>';
                        }else{
                            $rootScope.mensajes=$rootScope.mensajes+' <a href="#/home" class="list-group-item" >'+v.Aviso_titulo+'</a> ';
                        }                        
                    });
                    $rootScope.mensajes=$rootScope.mensajes+'</div>';
                    }                    

                    var messageTemplate = $rootScope.mensajes;
                    if($rootScope.avisos.length!=0){
                        notify({
                            messageTemplate: messageTemplate,
                            classes: $rootScope.classes,
                            scope:$rootScope,
                            templateUrl: $rootScope.template,
                            position: $rootScope.position,
                        });            
                    }
                    });

                    if($rootScope.actualiza==1){
                        $("#actualiza").modal();
                    }

                   swal({ 
                      title: "",                        
                      imageUrl: "imgs/avisos/aviso2022.png",
                        imageSize: '350x500',                                   
                      showCancelButton: false,                                 
                      confirmButtonColor: "#DD6B55", 
                      ConfirmButtonText: "Cerrar",                                   
                      closeOnConfirm: true,
                      closeOnCancel: true}, 
                      function(){                                           
                            console.log('entro a la redireccion');                                                                                   
                   });  


                //    swal({ 
                //     title: "NO TE DEJES ENGAÑAR \n\n Continuan las llamadas de extorsión:",   
                //     text: "1.    Habla una Contadora, da nombre de algunos compañeros de la empresa, y te comenta que van a llevar un paquete y necesitas pagarlo.\n2.    Piden tu celular para marcarte por ahí y utilizar la linea de la clínica.\n3.    Piden Información de caja chica.\n\nCUELGA Y NO DES NOMBRES DE COMPAÑEROS O JEFES, INFORMACIÓN DE DINERO Y TAMPOCO DATOS PERSONALES COMO TU CELULAR.\n\nEn caso de recibir este tipo de llamadas háznoslo saber a msanchez@medicavial.com.mx, scisneros@medicavial.com.mx, mvclinicas@medicavial.com.mx, coordenf@medicavial.com.mx",   
                //     type: "warning",
                //     showCancelButton: false,                                 
                //     confirmButtonColor: "#DD6B55", 
                //     ConfirmButtonText: "Cerrar",                                   
                //     closeOnConfirm: true,
                //     closeOnCancel: true}, 
                //     function(){                                           
                //           console.log('entro a la redireccion');                                                                                   
                //  });  
                    

                    $location.path("/home");
                    //console.log(data);
                }
            });



        },
        logout : function()
        {
            //al hacer logout eliminamos la cookie con $cookieStore.remove y los rootscope
            $cookieStore.remove("username"),
            $rootScope.username =  '';
            $cookieStore.remove("permisos"),
            $rootScope.permisos =  '';

            //mandamos al login
            $location.path("/login");

        },
        checkStatus : function()
        {
            //verifica el estatus de la sesion al cambiar de ruta 
            //si manda alguna ruta direfente de login y no tiene sesion activa en cookies manda a login
            if($location.path() != "/login" && typeof($cookies.username) == "undefined")
            {   
                $location.path("/login");
            }
            //en el caso de que intente acceder al login y ya haya iniciado sesión lo mandamos a la home
            if($location.path() == "/login" && typeof($cookies.username) != "undefined")
            {
                $location.path("/home");
            }
        }
    }

});


app.factory("busquedas", function($http, $rootScope, $cookies){
    return{
        buscarFolio: function(folio){
            return $http.get('api/api.php?funcion=getFolio&folio='+folio);
        },
        listadoFolios: function(usuUni){
            $rootScope.cargador=1;            
            return $http.get('api/api.php?funcion=listadoFolios&uni='+usuUni);
        },
        motivosCancelacion: function(folio){
            return $http.get('api/api.php?funcion=motivosCancelacion&fol='+folio);
        },
        datosPaciente: function(folio){            
            return $http.get('api/api.php?funcion=getDatosPaciente&folio='+folio);
        },
        datosPacienteMovil: function(folio){            
            return $http.get('api/api.php?funcion=getDatosPacienteMovil&folio='+folio);
        },
        datosPacienteRe: function(folio){            
            return $http.get('api/api.php?funcion=getDatosPacienteRe&folio='+folio);
        },
        medico: function(){
            return $http.get('api/api.php?funcion=getMedico&usu='+$cookies.usrLogin);
        },
        ocupacion: function(){
            return $http.get('api/api.php?funcion=getOcupacion');
        },
        edoCivil: function(){
            return $http.get('api/api.php?funcion=getEdoCivil');
        },
        enfermedad: function(){
            return $http.get('api/api.php?funcion=getEnfermedad');
        },
        familiar: function(){
            return $http.get('api/api.php?funcion=getFamiliar');
        },
        estatusFam: function(){
            return $http.get('api/api.php?funcion=getEstatus');
        },
        listaEnfHeredo: function(folio){
            return $http.get('api/api.php?funcion=getListEnfHeredo&fol='+folio);
        },
        padecimientos: function(){
            return $http.get('api/api.php?funcion=getPadecimientos');
        },
        otrasEnf: function(){
            return $http.get('api/api.php?funcion=getOtrasEnf');
        },
        alergias: function(){
            return $http.get('api/api.php?funcion=getAlergias');
        },
        cirugias: function(){
            return $http.get('api/api.php?funcion=getCirugias');
        },
        listaPadecimientos: function(folio){
            return $http.get('api/api.php?funcion=getListPadecimientos&fol='+folio);
        },
        listaOtrasEnf: function(folio){
            return $http.get('api/api.php?funcion=getListOtrasEnf&fol='+folio);
        },
        listaAlergias: function(folio){
            return $http.get('api/api.php?funcion=getListAlergias&fol='+folio);
        },
        listaCirugias: function(folio){
            return $http.get('api/api.php?funcion=getListCirugias&fol='+folio);
        },
        listaPadEsp: function(folio){
            return $http.get('api/api.php?funcion=getListPadEsp&fol='+folio);
        },
        listaTratQuiro: function(folio){
            return $http.get('api/api.php?funcion=getListTratQuiro&fol='+folio);
        },
        listaPlantillas: function(folio){
            return $http.get('api/api.php?funcion=getListPlantillas&fol='+folio);
        },
        listaTratamientos: function(folio){
            return $http.get('api/api.php?funcion=getListTratamientos&fol='+folio);
        },
        listaIntervenciones: function(folio){
            return $http.get('api/api.php?funcion=getListIntervenciones&fol='+folio);
        },
        listaDeportes: function(folio){
            return $http.get('api/api.php?funcion=getListDeportes&fol='+folio);
        },
        listaAdicciones: function(folio){
            return $http.get('api/api.php?funcion=getListAdicciones&fol='+folio);
        },
        catLugar: function(folio){
            return $http.get('api/api.php?funcion=getCatLugar&fol='+folio);
        },
        listaAccAnteriores: function(folio){
            return $http.get('api/api.php?funcion=getListAccAnt&fol='+folio);
        },
        listaPacLlega: function(folio){
            return $http.get('api/api.php?funcion=getListPacLlega&fol='+folio);
        },
        listaTipVehi: function(folio){
            return $http.get('api/api.php?funcion=getListTipVehi&fol='+folio);
        },
        listaVitales: function(folio){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=getListVitales&fol='+folio);
        },
        listaVitalesSub: function(folio,sub){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=getListVitalesSub&fol='+folio+'&sub='+sub);
        },
        listaMedSymio: function(uni){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=listMedicSymio&uni='+uni);
        },
        listaOrtSymio: function(uni){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=listOrtesisSymio&uni='+uni);
        },
        listaDatosPacRec: function(folio){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=listDatPacRec&fol='+folio);
        },
        medicos: function(uni){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=listMedicos&uni='+uni);
        },
        estatusNota: function(folio){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=estatusNotaM&fol='+folio);
        },
        validaHistoriaClinica: function(folio){
            $rootScope.cargador=true;
            return $http.get('api/api.php?funcion=validaHistoriaClinica&fol='+folio);
        },
        // Apartado de solicitudes
        detalleSolicitud:function(clave){
            return $http.get('api/api.php?funcion=detalleSolicitud&clave='+ clave);
        },
        detalleSolicitudMasInfo:function(clave){
            return $http.get('api/api.php?funcion=detalleSolicitudesInfo&clave='+ clave);
        },
        expedientes:function(){
            return $http.get('api/api.php?funcion=busquedaExpedientes&unidad=' + $rootScope.uniClave);
        },
        folio:function(folio){
            return $http.get('api/api.php?funcion=busquedaFolio&folioapi='+ folio + '&unidad=' + $rootScope.uniClave);
        },
        loginfast:function(usuario){
            return $http.get('api/api.php?funcion=loginfast&usuario='+ usuario);
        },
        lesionado:function(lesionado){
            return $http.get('api/api.php?funcion=busquedaLesionado&lesionado='+ lesionado);
        },
        missolicitudes:function(){
            return $http.get('api/api.php?funcion=solicitudes&userapi='+ $cookies.usrLogin);
        },
        solicitudes:function(){
            return $http.get('api/api.php?funcion=solicitudes');
        },
        solicitudesInfo:function(){
            return $http.get('api/api.php?funcion=solicitudesInfo');
        },
        solicitudesRespuestas:function(){
            return $http.get('api/api.php?funcion=solicitudesRespuestas');
        },
        tipoDocumento:function(){
            return $http.get('api/api.php?funcion=tipoDocumento');
        },
        listaEmbarazo:function(folio){
            return $http.get('api/api.php?funcion=getListEmbarazo&fol='+folio);
        },
        listaLesion:function(){
            return $http.get('api/api.php?funcion=getListLesion');
        },
        listaEjerciciosPx:function(zona){
            return $http.get('api/api.php?funcion=getListEjercicios&zona='+zona);
        },
        cuentaClabe:function(folio){
            return $http.get('api/api.php?funcion=getClabe&folio='+folio);
        },
        NotasHC:function(folio){
            return $http.get('api/api.php?funcion=getHC&folio='+folio);
        },
        RefEjerciciosPx:function(folio){
            return $http.get('api/api.php?funcion=getListReferecias&fol='+folio);
        },
        listaZona:function(){
            return $http.get('api/api.php?funcion=getListZona');
        },
        listaInsumos:function(){
            return $http.get('api/api.php?funcion=getListInsumos');
        },
        listaLesiones:function(folio){
            return $http.get('api/api.php?funcion=getListLesiones&fol='+folio);
        },
        listaRX:function(){
            return $http.get('api/api.php?funcion=getListRX');
        },
        listaEstSol:function(folio){
            return $http.get('api/api.php?funcion=getListEstSol&fol='+folio);
        },        
        listaEstSolSub:function(folio){
            return $http.get('api/api.php?funcion=getlistEstSolSub&fol='+folio);
        },
        listaProced:function(){
            return $http.get('api/api.php?funcion=getListProcedimientos');
        },
        listaProcedimientos:function(folio){
            return $http.get('api/api.php?funcion=getListProRea&fol='+folio);
        },
        listaOtrosEstudios:function(folio){
            return $http.get('api/api.php?funcion=getListadoOtrosEst&fol='+folio);
        },
        listadoMedAgregSymio:function(folio){
            return $http.get('api/api.php?funcion=getListadoMedAgreSymio&fol='+folio);
        },
        listadoOrtAgregSymio:function(folio){
            return $http.get('api/api.php?funcion=getListadoOrtAgreSymio&fol='+folio);
        },
        listaDiagnosticos:function(){
            return $http.get('api/api.php?funcion=getListDiagnostic');
        },
        despDiagnosticos:function(diagnos){
            return $http.get('api/api.php?funcion=getListDiag&diag='+diagnos);
        },
        listaOtrosEst:function(){
            return $http.get('api/api.php?funcion=getListOtrosEst');
        },
        listaMedicamentos:function(){
            return $http.get('api/api.php?funcion=getListMedicamentos');
        },
        listaOrtesis:function(){
            return $http.get('api/api.php?funcion=getListOrtesis');
        },
        listaIndicaciones:function(){
            return $http.get('api/api.php?funcion=getListIndicaciones');
        },
        verPosologia:function(cveMed){
            return $http.get('api/api.php?funcion=vePosologia&cveMed='+cveMed);
        },
        verindicacion:function(cveMed){
            return $http.get('api/api.php?funcion=veIndicacion&cveMed='+cveMed);
        },
        listaMedicamentosAgreg:function(folio){
            return $http.get('api/api.php?funcion=getListMedicamentosAgreg&fol='+folio);
        },
        listaMedicamentosAgregSub:function(folio,cont){
            return $http.get('api/api.php?funcion=getListMedicamentosAgregSub&fol='+folio+'&cont='+cont);
        },
        listaOrtesisAgreg:function(folio){
            return $http.get('api/api.php?funcion=getListOrtesisAgreg&fol='+folio);
        },
        listaOrtesisAgregSub:function(folio,cont){
            return $http.get('api/api.php?funcion=getListOrtesisAgregSub&fol='+folio+'&cont='+cont);
        },
        listaIndicAgreg:function(folio){
            return $http.get('api/api.php?funcion=getListIndicAgreg&fol='+folio);
        },
        listaIndicAgregComplemetarias:function(folio){
            return $http.get('api/api.php?funcion=getListIndicAgregComplementaria&fol='+folio);
        },
        listaIndicAgregSub:function(folio,cont){
            return $http.get('api/api.php?funcion=getListIndicAgregSub&fol='+folio+'&cont='+cont);
        },
        listaUnidades:function(folio){
            return $http.get('api/api.php?funcion=unidades');
        },
        validaSigVitales:function(folio){
            return $http.get('api/api.php?funcion=validaSigVitales&fol='+folio);
        },
        validaSubsec:function(folio){
            return $http.get('api/api.php?funcion=validaSubsecuencia&fol='+folio);
        },
        validaNota:function(folio){
            return $http.get('api/api.php?funcion=validaNotaM&fol='+folio);
        },
        validaPaseM:function(folio){
            return $http.get('api/api.php?funcion=validaPaseMed&fol='+folio);
        },
        validaPaseMed:function(folio){
            return $http.get('api/api.php?funcion=validaPaseMedic&fol='+folio);
        },
        rehabNum:function(folio){
            return $http.get('api/api.php?funcion=rehabNo&fol='+folio);
        },
        listSubsecuencias:function(folio){
            return $http.get('api/api.php?funcion=datosExp&folio='+folio);
        },
        validaParticular:function(folio){
            return $http.get('api/api.php?funcion=particular&folio='+folio);
        },
        datosRecibo:function(folio){            
            return $http.get('api/api.php?funcion=datosReciboInf&folio='+folio);
        },
        familiaItems:function(folio){            
            return $http.get('api/api.php?funcion=familiaItem&folio='+folio);
        },
        contRecibos:function(folio){            
            return $http.get('api/api.php?funcion=contadorRecibos&folio='+folio);
        },
        nomUsuario:function(usr){            
            return $http.get('api/api.php?funcion=nombreUsu&usr='+usr);
        },
        digitalizados:function(folio,usr){            
            return $http.get('api/api.php?funcion=digitalizados&fol='+folio+'&usr='+usr);
        },
        rentas:function(usr){            
            return $http.get('api/api.php?funcion=rentas&usr='+usr);
        },
        unidadDetalle:function(folio){            
            return $http.get('api/api.php?funcion=unidadDetalle&fol='+folio);
        },
        quienAutoriza:function(){            
            return $http.get('api/api.php?funcion=quienAut');
        },
        unidadDestino:function(){            
            return $http.get('api/api.php?funcion=uniDestino');
        },
        motivo:function(){            
            return $http.get('api/api.php?funcion=motivo');
        },
        listaAvisos:function(){            
            return $http.get('api/api.php?funcion=listaAvisos');
        }, 
        listaRxZonaTipo:function(){
            return $http.get('api/catalogos.php?funcion=listaRxZonaTipo');
        },

        folioZima:function(folio,usr){
            return $http.get('api/catalogos.php?funcion=folioZima&fol='+folio+'&usr='+usr);
        },
        listadoRecibosCobranza:function(datos){            
            return $http.post('api/particulares.php?funcion=listadoRecibosCobranza', datos);
        }, 
        listadoRecibosCobranzaAvanzada:function(datos){              
            return $http.post('api/particulares.php?funcion=listadoRecibosCobranzaAvanzada', datos);
        }, 
        /******************************* modulo de citas *********************************/
       clientes:function(){
            return $http.get('api/apiCita.php?funcion=clientes');
        },

        estado:function(){
            return $http.get('api/apiCita.php?funcion=estados');
        },

        unidades:function(unidad){
            return $http.get('api/apiCita.php?funcion=unidades&uni='+unidad);
        },

        lesionado:function(clave){
            return $http.get('api/apiCita.php?funcion=lesionadoregistrado&clave='+clave);
        },

        tipocita:function(){
            return $http.get('api/apiCita.php?funcion=tipocita');       
        },
        infounidad:function(){
            return $http.get('api/apiCita.php?funcion=infounidad');
        },
        bloqueo:function(){
            return $http.get('api/apiCita.php?funcion=evento');
        },
        infoLesionado:function(folio){
            return $http.get('api/detallePx.php?funcion=infoFolio&fol='+folio);
        },
        compania:function(){
            return $http.get('api/catalogos.php?funcion=compania');
        },
        listadoLotes:function(){
            return $http.get('api/movil.php?funcion=listadoLotes');
        },
        listarKits:function(noLote){
            return $http.get('api/movil.php?funcion=listarKits&noLote='+noLote);
        },
        listadoPrueba:function(unidad){
            return $http.get('api/api.php?funcion=listadoPrueba&unidad='+unidad);
        },
        listadoPendientesEnvio:function(unidad){
            return $http.get('api/api.php?funcion=listadoPendientesEnvio&unidad='+unidad);
        }
        /****************************** fin de modulo de cito ****************************/ 


    }
});

app.factory("movimientos", function($http, $rootScope){
    return{
        actualizaSolicitud:function(clave,estatus){
            return $http.get('api/api.php?funcion=ActualizaSolicitud&clave='+clave+'&estatus='+estatus+'&unidad=' + $rootScope.uniClave);
        },
        guardaSolicitudInfo:function(datos){
            return $http.post('api/api.php?funcion=guardaSolicitudInfo&unidad=' + $rootScope.uniClave,datos);
        },
        ingresaSolicitud:function(datos){
            return $http.post('api/api.php?funcion=guardaSolicitud&unidad=' + $rootScope.uniClave,datos);
        },
        estatusCobranza:function(color,serie,cont){
            return $http.post('api/particulares.php?funcion=guardaEstatusCob&color=' + color+'&serie='+serie+'&recibo='+cont);
        }
    }
});


// se genero un controlador para los documentos no es necesario generar archivo adicional
app.controller('materialCtrl', function($scope, $cookies, $http){

    $scope.inicio = function(){
        $scope.usrLogin =  $cookies.usrLogin;
        $scope.esMedico = 'no';
        $http.get('api/catalogos.php?funcion=getMedico&Med='+$scope.usrLogin).success(function (data){                                 		
            if(data.contador>=1){
                $scope.esMedico='si';
            }            else{
                $scope.esMedico='no';
            }
        });

        $scope.documentos = [
            {ruta:'imgs/pdf.png',nombre:'Nota Médica',ubicacion:'archivos/Nota_Medica.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Historia Clínica',ubicacion:'archivos/Historia_Clinica.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Subsecuencia',ubicacion:'archivos/Suministros.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Rehabilitación',ubicacion:'archivos/Rehabilitacion.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Finiquito (GNP)',ubicacion:'archivos/Finiquito.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Carta Finiquito(GNP)',ubicacion:'archivos/Carta_Finiquito.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Aviso de privacidad',ubicacion:'archivos/Aviso_De_Privacidad.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Id de paciente',ubicacion:'archivos/identificacion.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Informe Médico THONA',ubicacion:'archivos/Informe_Medico_Thona.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Informe Médico Quálitas',ubicacion:'archivos/Informe_Medico_Paciente_ABRIL_2014.pdf'}
        ];

        $scope.procesos = [
            {ruta:'imgs/office.png',nombre:'Proceso constancia médica ABA',ubicacion:'archivos/Manual_de_entrega_de_constancia_medica_ABA.doc'},
            {ruta:'imgs/pdf.png',nombre:'Proceso atención médica THONA',ubicacion:'archivos/Procedimiento_Thona.pdf'}
        ];

        $scope.escaners = [
            {ruta:'imgs/pdf.png',nombre:'Guía Visual de Configuración de Escáner HP Scanjet 5590',ubicacion:'archivos/Scaner.pdf'}
        ];

        $scope.consentimientos = [
            {ruta:'imgs/pdf.png',nombre:'Carta de Consentimiento',ubicacion:'archivos/carta-consentimiento.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Carta direccion Banorte Accidente Personal Escolar',ubicacion:'archivos/CARTA_DIRECCION ESCOLAR.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Diagrama de Flujo Banorte Accidente Personal Escolar',ubicacion:'archivos/DIAGRAMA_de_FLUJO1.xlsx'},
            {ruta:'imgs/pdf.png',nombre:'Informe Médico Banorte Accidente Personal Escolar',ubicacion:'archivos/InformeMedico.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Informe Reclamante Banorte Accidente Personal Escolar',ubicacion:'archivos/InformeReclamante.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Addendum CI COVID-19',ubicacion:'archivos/Addendum_CI_COVID-19.pdf'}
        ];

        $scope.manuales = [
            {ruta:'imgs/pdf.png',nombre:'Instructivo de Descarga de Medicamentos',ubicacion:'archivos/DescargaMed.pdf'}
        ];

        $scope.ejercicios = [
            {ruta:'imgs/pdf.png',nombre:'Cadera y Rodilla',ubicacion:'ejercicios/Cadera_Rodilla.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Higiene de Columna',ubicacion:'ejercicios/Higiene_Columna.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Columna Cervical',ubicacion:'ejercicios/Columna_Cervical.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Hombro',ubicacion:'ejercicios/Hombro.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Codo, Mano , y Muñeca',ubicacion:'ejercicios/Codo_Mano_Muneca.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Tobillo y Pie',ubicacion:'ejercicios/Tobillo_Pie.pdf'},
            {ruta:'imgs/pdf.png',nombre:'Columna Dorsolumbar',ubicacion:'ejercicios/Columna_Dorsolumbar.pdf'}
        ];
    }

$scope.abreVideo = function(video){ 
     $('#pruebaModal').modal('show');       
        $scope.ruta=''; 
        $scope.titulo='';       
        switch(video){
            case 1:
            $scope.ruta='views/videos/1.html';
            $scope.titulo='1.-Objetivo y mecánica de bono. por LIC. MARIANA SANCHEZ';  
            break;
            case 2:
            $scope.ruta='views/videos/2.html';
            $scope.titulo='2.- Cero quejas MARICURZ FLOREZ';
            break;
            case 3:
            $scope.ruta='views/videos/3.html';
            $scope.titulo='3.- Número de faltas y retardos LIC. MARIANA SANCHEZ';
            break;
            case 4:
            $scope.ruta='views/videos/4.html';
            $scope.titulo='4.- Inventario LIC. ANGELICALOZANO';
            break;
            case 5:
            $scope.ruta='views/videos/5.html';
            $scope.titulo='5.- Expedientes completos ING. ALFREDO GUTIERREZ';
            break;
            case 6:
            $scope.ruta='views/videos/6.html';
            $scope.titulo='6.- Ventas particulares LIC. HUGO ZARICH';
            break;
            case 7:
            $scope.ruta='views/videos/7.html';
            $scope.titulo='7.- Tiempo de espera ING. ALFREDO GUTIERREZ';
            break;
            case 8:
            $scope.ruta='views/videos/8.html';
            $scope.titulo='8.- Sondeo de calidad LIC. MARIANA SANCHEZ';
            break;
            case 9:
            $scope.ruta='views/videos/9.html';
            $scope.titulo='9.- Reglamento de clínicas ANGELICA MERCADO';
            break;
            case 10:
            $scope.ruta='views/videos/10.html';
            $scope.titulo='10.- Requisitos por persona_LIC. MARIANA SANCHEZ';
            break;
            case 11:
            $scope.ruta='views/videos/11.html';
            $scope.titulo='1.- Introducción';
            break;
            case 12:
            $scope.ruta='views/videos/12.html';
            $scope.titulo='2.- El camino de MV';
            break;
            case 13:
            $scope.ruta='views/videos/13.html';
            $scope.titulo='3.- Beneficios';
            break;
            case 14:
            $scope.ruta='views/videos/14.html';
            $scope.titulo='4.- El Colaborador de MV';
            break;
            case 15:
            $scope.ruta='views/videos/15.html';
            $scope.titulo='5.- Políticas de Vestimenta y de la Empresa';
            break;
            case 16:
            $scope.ruta='views/videos/16.html';
            $scope.titulo='6.- Clientes y Pacientes';
            break;
            case 17:
            $scope.ruta='views/videos/17.html';
            $scope.titulo='7.- Factores de Éxito';
            break;
            case 18:
            $scope.ruta='views/videos/18.html';
            $scope.titulo='8.- Requisitos por persona_LIC. MARIANA SANCHEZ';
            break;
             case 19:
            $scope.ruta='views/videos/19.html';
            $scope.titulo='1.- Introducción';
            break;
             case 20:
            $scope.ruta='views/videos/20.html';
            $scope.titulo='2.- Experiencia Wow 1';
            break;
             case 21:
            $scope.ruta='views/videos/21.html';
            $scope.titulo='3.- Experiencia Wow 2';
            break;
             case 22:
            $scope.ruta='views/videos/22.html';
            $scope.titulo='4.- WESTJET';
            break;
             case 23:
            $scope.ruta='views/videos/23.html';
            $scope.titulo='5.- Servicio al cliente experiencia wow 3';
            break;
             case 24:
            $scope.ruta='views/videos/24.html';
            $scope.titulo='6.- 5 vitales del servicio';
            break;
            case 25:
            $scope.ruta='views/videos/25.html';
            $scope.titulo='Curso: La micro empresa';
            break;
            case 26:
            $scope.ruta='views/videos/26.html';
            $scope.titulo='Inducción clínicas';
            break;
            case 27:
            $scope.ruta='views/videos/27.html';
            $scope.titulo='Sistema de Vacaciones';
            break;
            case 28:
            $scope.ruta='views/videos/28.html';
            $scope.titulo='Video 1';
            break;
            case 29:
            $scope.ruta='views/videos/29.html';
            $scope.titulo='Video 2';
            break;
            case 30:
            $scope.ruta='views/videos/30.html';
            $scope.titulo='Video 3';
            break;
            case 31:
            $scope.ruta='views/videos/31.html';
            $scope.titulo='Video 4';
            break;
            case 32:
            $scope.ruta='views/videos/32.html';
            $scope.titulo='Video 5';
            break;
        }
        console.log($scope.ruta);
    }
    $scope.pausarVideo = function(){ 
        var v = document.getElementsByTagName("video")[0];
     v.pause();   
    }
     




});


//bloqueo de sesion
app.controller('bloqueoCtrl',function($scope, $cookies, $cookieStore, $rootScope, sesion){

    $scope.inicio = function(){

        $scope.usuario = $cookies.usrLogin;
        $scope.nombre = $cookies.username;

        $cookieStore.remove("username"),
        $rootScope.username = '';
        $rootScope.mensaje = '';

        $('html').addClass('lockscreen');

    }

    $scope.login = function(){

        
        $rootScope.mensaje = '';
        //console.log($scope.usuario);
        sesion.login($scope.usuario, $scope.password);

    }

});


//funcion que ayuda a ponerl la hora actual 

var tiempo = new Date();
                
var dd = tiempo.getDate(); 
var mm = tiempo.getMonth()+1;//enero es 0! 
if (mm < 10) { mm = '0' + mm; }
if (dd < 10) { dd = '0' + dd; }

var yyyy = tiempo.getFullYear();
//armamos fecha para los datepicker
var FechaAct = yyyy + '-' + mm + '-' + dd;

var hora = tiempo.getHours();
var minuto = tiempo.getMinutes();

var FechaActHora = hora + ':' + minuto;


///notas 



//definicion de factoria en angular
// app.factory('nombredefactoria',function($http,$rootScope){
//     return{
//         query:function(parametros){
//             //cualquier cosa
//         },
//         alta:function(){
//             //cualquier cosa
//         }
//     }
// })


///funciones en algular
// $rootScope.nombrefuncion = function(parametros){}
// $scope.nombrefuncion = function(){}

// //llamada desde html
// <button ng-click="nombrefuncion()">
// //llamada desde js
// $scope.nombrefuncion();

