var hoy = new Date(); 
var dd = hoy.getDate(); 
var mm = hoy.getMonth()+1;//enero es 0! 
var hora = hoy.getHours();
var minuto = hoy.getMinutes();
var segundo = hoy.getSeconds();

if (mm < 10) { mm = '0' + mm; }
if (dd < 10) { dd = '0' + dd; }
if (minuto < 10) { minuto = '0' + minuto; }

var yyyy = hoy.getFullYear();
//armamos fecha para los datepicker
var FechaAct = yyyy + '-' + mm + '-' + dd;
var horas = hora + ':' + minuto;

console.log(horas);

var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");

var fechaM = dd + '-' + mm + '-' + yyyy;


app.controller('calendarioCtrl', function($scope,$cookies,$http, busquedas, $rootScope, WizardHandler, $routeParams, uiCalendarConfig, $filter,DTOptionsBuilder, DTColumnBuilder,
               $modal, $log) {

     $scope.current = 0; //inicia en el primer indice
     $scope.unidad=$cookies.uniClave;
     $scope.datos={
        tipocita:''
        }

    $scope.uniClave=$cookies.uniClave;
    $scope.inicio = function(){ 



        $rootScope.cerrar = false;
        $rootScope.cargar = false;
        $rootScope.bloqueo= '';
        $scope.boton_guardar = true;

        

        $scope.activa = true;
        $scope.cargando = false;
        $scope.consultalocalidad();
        // $scope.calendario();   
        $scope.altacliente(); 
        $scope.tipocita();
        $scope.calendario();  
        $scope.unidades();        
        $scope.slides = [];

        $scope.citareg = {

            horacita: horas,
            duracion: "30"
        }


        $scope.lesionado = {

            nombre: '',
            edad: '',

        }

        $scope.registro = {

            nombre:'',
            edad:'',
            tipotelefono:'',
            telefono:'',
            siniestro:'',
            poliza:'',
            reporte:'',
            cliente: '',
            uni:  $scope.uniClave,
            folio: ''

        }

        $scope.informacion = {

            telefono: ''

        }

        $scope.datos = {

            asunto: '',
            estado: '',
            localidad: '',
            unidad: $scope.uniClave,
            fecha: FechaAct,
            horacita: horas,
            duracion: "30",
            usuario: $rootScope.username,
            fechainicio: FechaAct,
            cliente: '',
            fnacimiento: '',
            telefono: '',
            fechamostrar: fechaM,
            porcentaje: '',
            colores: ''

        } 
        

        $scope.map = {
            center: {
                latitude: 19.417870, 
                longitude: -99.162287
            }, 
            zoom: 20,
            options : {
                scrollwheel: false
            },
            control: {}
        };

        $scope.blog = {

            bloqueo: ''
        }

        $scope.bcita = {

            lesionado: '',
            tipocita: ''
        }

        $scope.formato = {

            fechaini: FechaAct,
            fechafin: FechaAct
        }


    }

    $scope.muestra_tab = function() { 

    $scope.reglesionado = true; 
    $scope.btnguarda = true;
    $scope.btnactualiza = false;

    }; 

      $scope.open = function (size) {

        var modalInstance = $modal.open({
          templateUrl: 'myModalContent.html',
          controller: ModalInstanceCtrl,
          size: size,
          resolve: {
            lat: function () {
              return $scope.lat;
            },
            lng: function () {
              return $scope.lng;
            }
          }
        });

        modalInstance.result.then(function (selectedItem) {
        }, function () {
          $log.info('Modal dismissed at: ' + new Date());
        });
      };

      var ModalInstanceCtrl = function ($scope, $modalInstance, lat, lng) {

      $scope.lat = lat;
      $scope.lng = lng;

      $scope.ok = function () {
        $modalInstance.close();
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
    };

    $scope.calendario = function(){

        var b;
        var cc;
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();        
        var currentLangCode = 'es';

        $scope.eventSource = {
              
                url: 'api/apiCita.php?funcion=evento&unidad='+$scope.unidad+'&tipocita='+$scope.datos.tipocita,
                rendering: 'background'
                // color: '#9CF0B3'
        }

        $scope.uiConfig = {

            calendar:{
     
            lang: currentLangCode,
            theme: true,
            height: 450,
            width: 450,
            editable: true,
            allDaySlot: true,
            slotDuration: '00:15:00',
            slotEventOverlap: false,
            axisFormat: '(HH:mm)a', 
            timeFormat: {agenda: 'H:mm{ - h:mm}'}, 
            eventLimit: true, // If you set a number it will hide the itens
            eventLimitText: "Mas",
            eventLimitClick: 'day',
            selectHelper: true,
            selectable: true,
            defaultView: 'month',

            // select: function(start, end, date, view) {
            //         // var title = prompt('Event Title:');
            //         // var eventData;
            //         // if (title) {
            //         //     eventData = {
            //         //         title: title,
            //         //         start: start,
            //         //         end: end
            //         //     };
            //         //     $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
            //         // }
            //         // $('#calendar').fullCalendar('unselect');

            //     var ajandamodu=view.name;
            //     if(ajandamodu=='month')
            //     {                             
            //         $('#citacalendario').fullCalendar( 'changeView', 'agendaDay');
            //         $('#citacalendario').fullCalendar('gotoDate', date);
            //     }

            //     },

            // events: "api/apiCita.php?funcion=evento",

             eventSources: $scope.eventSource,

             //[

            // {
            //    url: 'api/apiCita.php?funcion=evento&unidad='+$scope.datos.unidad,
            //    rendering: 'background',
            //    color: '#9CF0B3'
            // }
            // ],

            header:{
            left: 'prev,next',
            center: 'title',
            right: 'month'
            },

           // eventClick: function (date, allDay, jsEvent, view) {  

           //      $("#dialog").dialog('open');     
           //      $("#name").val("(event name)");
           //      $("#date-start").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
           //      $("#date-end").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
           //      $("#time-start").val($.fullCalendar.formatDate(date, 'hh:mmtt'));
           //      $("#time-end").val($.fullCalendar.formatDate(date, 'hh:mmtt')); 

           //      },

            dayClick: function(date, jsEvent, view) {
                // var ajandamodu=view.name;

                // if(ajandamodu=='month')
                // {     
                // $('#preregistro').modal('show');

                    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");                        


                    var fecha = ('gotoDate', date);
                    // var f = fecha.format('DD-MM-YYYY');

                    
                    $scope.datos.fechamostrar = fecha.format('DD-MM-YYYY');
                    $scope.datos.fechainicio = fecha.format();
                    $scope.Buscar();

                    $scope.checarbloqueo();
                        
                // }
                
            },   

            }
        }


        
        $scope.eventSources = [$scope.eventSource];
        $scope.eventSources2 = [$scope.calEventsExt, $scope.eventsF, $scope.events];

    }

    $scope.consultalocalidad = function(){

        $scope.activa = true;

        try{

            $http({

                url:'api/apiCita.php?funcion=localidadclinicas',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
                

            }).success(function (data){

                $scope.activa = false;

                $scope.localidad = data;

            }).error( function (xhr,status,data){

                $scope.mensaje ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }
    }

    $scope.consultaunidad = function(){

        $scope.activa = true;

        try{

            $http({

                url:'api/apiCita.php?funcion=unidadclinica',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
                

            }).success(function (data){

                console.log($scope.datos);

                $scope.activa = false;
                $scope.activacal = true;
                $scope.unidades = data; 


            }).error( function (xhr,status,data){

                $scope.mensaje ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }
    }

    $scope.consultacalendario = function(){

        $scope.activa = true;

        try{

            $http({

                url:'api/apiCita.php?funcion=consultacalendario',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
                

            }).success(function (data){

                $scope.activa = false;
                $scope.calle = data.calle;
                $scope.colonia = data.colonia;

            }).error( function (xhr,status,data){

                $scope.mensaje ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }
    }

    $scope.guardaCita = function(){
    
        $scope.activa = true;

        try{

            $http({

                url:'api/apiCita.php?funcion=guardaCita&usu='+$rootScope.usrLogin,
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data: $scope.citareg
                

            }).success(function (data){

                $scope.activa = false;
                $scope.calle = data.calle;
                $scope.colonia = data.colonia;
                $scope.citareg.clavecita = data.clavecita;
                $scope.mensajecita = data.respuesta;
                $scope.tipoalerta = 'alert-warning';
                $scope.boton_guardar = false;

                // $('#creacita').modal('hide');



            }).error( function (xhr,status,data){

                $scope.mensaje2 ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }
    }

    $scope.muestraDetallePaciente = function(info) {

        $scope.editact = true;
        $scope.mensajecancela = '';

        $scope.detalle = {

            cita: info.CT_clave,
            fechacita: info.CT_fecha,
            horacita: info.CT_hora,
            obscita: info.CT_observaciones,
            duracioncita: info.CT_tiempo,
            folio: info.Exp_folio,
            nombrepaciente: info.PRE_nombre,
            tipocita: info.TC_nombre,
            tipocitaclave: info.TC_clave,
            unidad: info.Uni_nombrecorto,
            cliente: '',
            asunto: '',
            cancelacion: ''

        }

        $('#detallepaciente').modal('show');

    };

    $scope.actualizacita = function(info) {

        $scope.activa = true;
        $scope.mensajeactualiza = false;

        try{

            $http({

                url:'api/apiCita.php?funcion=actualizarcita',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data: $scope.detalle
                

            }).success(function (data){


                $scope.mensajeactualiza = data.mensaje;
                $scope.tipoalerta = 'alert-success';
                $scope.activa = false;
                alert('Se actualizo con Exito!!');
                $scope.Buscar();


            }).error( function (xhr,status,data){

                $scope.mensaje3 ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }

     $scope.$on('event:dataTableLoaded', function(event, data) { 

        $scope.tableId = data.id;
        $scope.Buscar = function() {

            $scope.varcalendario = true;

            $scope.searchData = angular.copy($scope.datos);
            $scope.cargando = true;
            unidad = 185;
            $scope.calendario();
            $scope.infounidad();
            


            $('#'+$scope.tableId).DataTable().ajax.reload(function (data) {
                $scope.$apply(function() {
                    $scope.cargando = false;
                }); 
            });

        };


    }); 

    $scope.dtOptions = DTOptionsBuilder.newOptions() 

        .withOption('ajax', {
            "url": 'api/apiCita.php?funcion=consultacitas&unidad='+$scope.unidad+'&tipoCita=3',
            "type": 'POST',
            "data": function ( d ) {            
                    d.search = $scope.datos || {}; //search criteria
                    return JSON.stringify(d);
            }
                
        })
        .withOption('lengthMenu', [ [10, 15, 20, -1], [5, 10, 15, 20, "Todo"] ])
        .withOption('responsive', true)
        // .withOption('serverSide', true)
        .withPaginationType('full_numbers')
        .withOption('language', {
            paginate: {
                first: "«",
                last: "»",
                next: "→",
                previous: "←"
            },
            search: "Buscar:",
            loadingRecords: "Cargando Información....",
            lengthMenu: "Mostrar _MENU_ entradas",
            processing: "Procesando Información",
            infoEmpty: "No se encontro información",
            emptyTable: "Sin Información disponible",
            info: "Mostrando pagina _PAGE_ de _PAGES_ , Registros encontrados _TOTAL_ ",
            infoFiltered: " - encontrados _MAX_ coincidencias"
        })

        .withOption('rowCallback', function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td', nRow).bind('click', function() {
                $scope.$apply(function() {
                    $scope.muestraDetallePaciente(aData);
                });
            });
            return nRow;
        })
        // Add Bootstrap compatibility
        .withBootstrap()
        // Add ColVis compatibility
        .withColVis()
        // Add a state change function
        .withColVisStateChange(function(iColumn, bVisible) {
            console.log('The column' + iColumn + ' has changed its status to ' + bVisible)
        })

        .withOption("colVis",{
            buttonText: "Mostrar / Ocultar Columnas"
        })
        // Exclude the last column from the list
        // .withColVisOption('aiExclude', [2])

        // Add ColReorder compatibility
        .withColReorder()
        // Set order
        // .withColReorderOrder([1, 0, 2])
        // Fix last right column
        .withColReorderOption('iFixedColumnsRight', 1)
        .withColReorderCallback(function() {
            console.log('Columns order has been changed with: ' + this.fnOrder());
        })
        //Add Table tools compatibility

        .withTableTools('js/swf/copy_csv_xls_pdf.swf')
        .withTableToolsButtons([

            {
                "sExtends":     "copy",
                 "sButtonText": "Copiar"
            },
            {
                'sExtends': 'collection',
                'sButtonText': 'Exportar',
                'aButtons': ['xls', 'pdf']
            }
        ]);
        
    $scope.dtColumns = [

        DTColumnBuilder.newColumn('CT_hora').withTitle('Hora'),
        DTColumnBuilder.newColumn('TC_nombre').withTitle('Tipo de Cita'),
        DTColumnBuilder.newColumn('CT_tiempo').withTitle('Duracion'),
        DTColumnBuilder.newColumn('Exp_folio').withTitle('Folio'),
        DTColumnBuilder.newColumn('PRE_nombre').withTitle('Paciente'),
        DTColumnBuilder.newColumn('CT_observaciones').withTitle('Descripión'),
        DTColumnBuilder.newColumn('cancelado').withTitle('Status'),
        DTColumnBuilder.newColumn('CT_observacancelada').withTitle('Motivo de Cancelación')

    ];

    $scope.limpia = function(){

        $scope.lesionados = '';

        $scope.registro = {

            nombre:'',
            edad:'',
            tipotelefono:'',
            telefono:'',
            siniestro:'',
            poliza:'',
            reporte:'',
            cliente: '',
            unidad: '',
            folio: ''
        }

        $scope.activaregistro = false;
        $scope.consult= false;
        $scope.edita = false;
        $scope.reglesionado = false; 
        $scope.inactiva = false;
        $scope.btcrea = false;
        $scope.mensajeregistro = false;
        $scope.mensajecita = false;

    }


    $scope.B_clinica = function(){

          try{

            $scope.activa = true;

            $http({

                url:'api/apiCita.php?funcion=buscaLesionadoxclinica',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
               
            }).success(function (data){

              $scope.consult= true;
              $scope.activa = false;
              $scope.avanzado = true;
              $scope.activaregistro = true;
              $scope.mensaje1 = data.respuesta;
              $scope.tipoalerta = 'alert-success';
              $scope.lesionados = data;
              $scope.btnactualiza = true;
              $scope.btnguarda = false;


            }).error( function (xhr,status,data){

                $scope.mensaje ='Error de Conexión, Consulta con el Area de Sistemas';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }

    $scope.B_todasclinica = function(){


          try{

            $scope.activa = true;

            $http({

                url:'api/apiCita.php?funcion=b_todasclinica',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
               
            }).success(function (data){

              $scope.consult= true;
              $scope.activa = false;
              $scope.avanzado = true;
              $scope.activaregistro = true;
              $scope.mensaje1 = data.respuesta;
              $scope.tipoalerta = 'alert-success';
              $scope.lesionados = data;

            }).error( function (xhr,status,data){

                $scope.mensaje ='Error de Conexión, Consulta con el Area de Sistemas';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }

    $scope.Guardar = function(){

         $scope.activa = true;


          try{

            $http({

                url:'api/apiCita.php?funcion=guardaLesionado',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.registro
               

            }).success(function (data){

                $scope.activa = false;
                $scope.mensajeregistro = data.respuesta;
                $scope.tipoalerta = 'alert-success';
                $scope.datos.clave = data.pre_clave;
                $scope.inactiva = true;
                $scope.botoncita = true;
                $scope.edita = true;
                $scope.btcrea = true;


            }).error( function (xhr,status,data){

                $scope.mensaje ='Error';
                $scope.alerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }


    $scope.Nuevo = function(){

         $scope.activa = true;


          try{

            $http({

                url:'api/apiCita.php?funcion=nuevoLesionado',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.registro
               

            }).success(function (data){

                $scope.activa = false;
                $scope.mensajeregistro = data.respuesta;
                $scope.tipoalerta = 'alert-success';
                $scope.datos.clave = data.pre_clave;
                $scope.inactiva = true;
                $scope.botoncita = true;
                $scope.edita = true;
                $scope.btcrea = true;


            }).error( function (xhr,status,data){

                $scope.mensaje ='Error';
                $scope.alerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }


    
    $scope.agregaCita = function(){
    
        $scope.boton_guardar = false;
        $scope.mensajecita = false;

        $scope.citareg = {

            clavepaciente: $scope.datos.clave,
            asunto: '',
            estado: '',
            localidad: '',
            fecha: $scope.datos.fechainicio,
            horacita: '',
            duracion: "30",
            usuario: $rootScope.username,
            cliente: '',
            unidad: $scope.datos.unidad,
            tipocita: $scope.datos.tipocita,
            clavecita: '',
            observacion: '',
            duracion: ''
        } 


    }

    $scope.infounidad = function(){

        $scope.mensajeactualiza = false;
        console.log($scope.datos);
          try{

            $http({

                url:'api/apiCita.php?funcion=infounidad&UniClave='+$scope.unidad,
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
               

            }).success(function (data){

                $scope.informacion.telefono = data.telefono;
                $scope.informacion.unidad = data.unidad;
                $scope.informacion.calle = data.calle;
                $scope.informacion.colonia = data.colonia;
                $scope.lat = data.latitud;
                $scope.lng = data.longitud;
                $scope.slides = data.image;
                $scope.map = data.center;

 
            }).error( function (xhr,status,data){

                $scope.mensaje ='Error';
                $scope.alerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }

    $scope.altacliente = function(){

        busquedas.clientes().success(function (data){
            $scope.clientes = data;
        });

    }

    $scope.unidades = function(){

        busquedas.unidades($scope.unidad).success(function (data){
            $scope.unidades = data;
        });

    }

    $scope.siguiente = function(folio){

            $http({

                url:'api/apiCita.php?funcion=buscafolio&folio='+folio,
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
               

            }).success(function (data){

               $scope.reglesionado= true;

              $scope.registro.nombre = data.nombre;
              $scope.registro.telefono = data.telefono;
              $scope.registro.uni = data.unidad;
              $scope.registro.cliente = data.cliente;
              $scope.registro.folio = data.folio;
              $scope.registro.edad = data.edad;
              $scope.registro.fecharegistro = data.fecharegistro;
              $scope.registro.siniestro = data.siniestro;
              $scope.registro.poliza = data.poliza;
              $scope.registro.reporte = data.reporte;
              $scope.registro.correo = data.correo;
              $scope.registro.duracion = "30";
              $scope.btnactualiza = true;
              $scope.mensajeFolio = "Este Folio tiene "+ data.dias + " dias de registro de "+ data.tipo;
              $scope.tipoalerta1 = 'alert-warning';




            }).error( function (xhr,status,data){

                $scope.mensaje ='Error de Conexión, Consulta con el Area de Sistemas';
                $scope.tipoalerta = 'alert-danger';

            });
    }

    $scope.creacita = function(){

        $scope.boton_guardar = true;

        $scope.citareg = {

            clavepaciente: $scope.datos.clave,
            asunto: '',
            estado: '',
            localidad: '',
            fecha: $scope.datos.fechainicio,
            horacita: horas,
            duracion: "30",
            usuario: $rootScope.username,
            cliente: '',
            unidad: $scope.datos.unidad,
            tipocita: $scope.datos.tipocita
        } 

        console.log($scope.datos);


        $scope.btcrea = true;


        $('#preregistro').modal('hide');
        $('#creacita').modal('show');

        $scope.editacita = true;

    }
    
    $scope.tipocita = function(){

        busquedas.tipocita().success(function (data){
            $scope.tcita = data;
        });
    }
    $scope.enviainformacion = function(){

        $scope.activa = false;

        try{

            $http({

                url:'api/apiCita.php?funcion=enviainformacion',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:{unidad: $scope.datos.unidad, correo: $scope.datos.correo}
                

            }).success(function (data){

                $('#info').modal('hide');
                
            }).error( function (xhr,status,data){

                $scope.mensaje ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }
    }

    $scope.evento= function(){

            $http({

                url:'api/apiCita.php?funcion=color1',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
                

            }).success(function (data){

                $scope.datos.porcentaje = data.porcentaje;

                if ($scope.datos.porcentaje <= 25.00) {

                    $scope.datos.colores = "#DCDDE2";
                    var cc = "#DCDDE2";

                }

                if ($scope.datos.porcentaje > 25.00 && $scope.datos.porcentaje <= 75.00) {

                    $scope.datos.colores = "#9CF0B3";
                    var cc = "#9CF0B3";

                }

                if ( $scope.datos.porcentaje > 75.00) {

                    $scope.datos.colores = "#E3C153";
                    var cc = "#E3C153";

                }


            }).error( function (xhr,status,data){

                $scope.mensaje ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

    }


    $scope.checarbloqueo = function(){

        try{

            $http({

                url:'api/apiCita.php?funcion=checabloqueo',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.datos
                

            }).success(function (data){


                $scope.mensajebloqueo = data.respuesta;
                $scope.mensaje = data.bloqueo;
                $scope.tipoalerta = 'alert-danger';                

            }).error( function (xhr,status,data){

                $scope.mensaje ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }
    }

    $scope.pasarConsulta = function(info) {

        $scope.activa = true;

        try{

            $http({

                url:'api/apiCita.php?funcion=pasarConsulta',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data: $scope.detalle
                

            }).success(function (data){

                $scope.activa = false;
                alert('Se actualizo con Exito!!');
                $scope.Buscar();

                $('#detallepaciente').modal('hide');


            }).error( function (xhr,status,data){

                $scope.mensaje3 ='Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina';
                $scope.tipoalerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }

    $scope.cancelaCita = function(){

          try{

            $http({

                url:'api/apiCita.php?funcion=cancelaCita&uni='+$rootScope.uniClave,
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.detalle
               

            }).success(function (data){

                $scope.mensajecancela = data.respuesta;
                $scope.Buscar();


            }).error( function (xhr,status,data){

                $scope.mensaje ='Error';
                $scope.alerta = 'alert-danger';

            });

        }catch(err){

            alert(err);
        }

    }

    $scope.busquedacitas = function(){
            console.log($scope.bcita);
            $http({

                url:'api/apiCita.php?funcion=busquedacitas',
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.bcita
               
            }).success(function (data){

                $scope.mensajebusqueda = data.respuesta;
                $scope.tipoalerta = 'alert-danger';
                $scope.generalcitas = data;

            }).error( function (xhr,status,data){

                $scope.mensaje ='Error';
                $scope.alerta = 'alert-danger';

            });

    }

    $scope.citasdia = function(){
        $scope.bpaciente = 0;

            $http({

                url:'api/apiCita.php?funcion=citasdia&unidad='+$scope.uniClave,
                method:'POST', 
                contentType: "application/json; charset=utf-8", 
                dataType: "json", 
                data:$scope.bcita
               
            }).success(function (data){

                $scope.mensajebusqueda = data.respuesta;
                $scope.tipoalerta = 'alert-danger';
                $scope.generalcitas = data;

            }).error( function (xhr,status,data){

                $scope.mensaje ='Error';
                $scope.alerta = 'alert-danger';

            });

    }
    $scope.descargapdf = function(){
        // $scope.bpaciente = 0;
        // $scope.list = true;

        var fileName = 'Agenda - '+FechaAct;
        var uri = 'api/agendapdf.php?unidad='+$scope.uniClave+'&fechaini='+$scope.formato.fechaini+'&fechafin='+$scope.formato.fechafin;
        var link = document.createElement("a");    
        link.href = uri;
        
        link.style = "visibility:hidden";
        link.download = fileName + ".pdf";
        
        document.body.appendChild(link);
        link.click();           
        document.body.removeChild(link);
    }

});

