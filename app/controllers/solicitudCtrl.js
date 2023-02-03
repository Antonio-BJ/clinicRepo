app.controller('solicitudCtrl', function ($scope, $cookies, $route, busquedas, movimientos, WizardHandler, $upload) {

	$scope.inicio = function(){ 

		$scope.files = [];
		$scope.documentos = [];
		$scope.nombredoc = '';
		$scope.mensaje = '';
		$scope.clavesolicitud = '';
		$scope.folio = '';

		$scope.datos = {
			usuario:$cookies.usrLogin,
			folio:'',
			lesionado:'',
			tipo:'',
			cliente:'',
			clientenombre:'',
			interconsulta:{
				tipo:'',
				embarazo:'',
				controlgineco:'NO',
				semanas:4,
				dolorabdominal:'NO',
				frecuencia:'',
				movimientosfetales:'',
				observaciones:''	
			},
			estudio:{
				tipo:'',
				detalle:''
			},
			rehabilitacion:{
				dolor:0,
				rehabilitaciones:0,
				mejora:0
			},
			suministro:{
				detalle:''
			},
			informacion:{
				notamedica:false,
				rx:false,
				resultados:false,
				detalle:''
			},
			salidapaquete:{
				detalle:''
			},
			problemadocumental:{
				pase:false,
				identificacion:false,
				detalle:''
			},
			diagnostico:'',
			justificacion:'',
			soporte:$scope.documentos
		}

		$scope.ultimo = 'Lesionado';

		$scope.paso = 0;
		$scope.acutal = '';

		$scope.buscar = false;

		$scope.muestraExpedientes();
		$scope.buscaTipoDoc();


	}

	$scope.buscaTipoDoc = function(){

		busquedas.tipoDocumento().success(function (data){
			$scope.tiposDocumentos = data;
		});

	}

	$scope.muestraExpedientes = function(){
		$scope.buscar = true;
		busquedas.expedientes().success(function (data){
			$scope.expedientes = data;
			$scope.buscar = false;
		});
	}

	$scope.buscaFolio = function(folio){
		console.log(folio);
		$scope.buscar = true;
		busquedas.folio(folio).success(function (data){
			$scope.expedientes = data;
			$scope.buscar = false;
		});
	}

	$scope.buscaLesionado = function(lesionado){

		// if (lesionado == '') {
		// 	lesionado = $scope.lesionado;
		// };
		$scope.buscar = true;
		busquedas.lesionado(lesionado).success(function (data){
			$scope.expedientes = data;
			$scope.buscar = false;
		});
	}

	$scope.datosPaciente = function(valor){

		var datos = $scope.expedientes[valor];
		
		console.log(datos);

		$scope.datos.folio = datos.expediente;
		$scope.datos.lesionado = datos.lesionado;
		$scope.datos.cliente = datos.idcliente;
		$scope.datos.clientenombre = datos.cliente;

		WizardHandler.wizard().next();

		//console.log($scope.datos);

	}

	$scope.mensaje = function(){

		$scope.tipo = false;


		var valor = $scope.datos.tipo;

		console.log(valor);
		// debugger;

		switch(valor){
			case "4":
		        $scope.mensajetipo = 'Te recordamos que dentro del paquete pactado está incluida la atención inicial y subsecuente por médico traumatólogo o urgenciólogo. Así como también la consulta de valoración por médico de cualquier otra especialidad, en caso de requerir tratamiento de una especialidad distinta a Traumatología y Ortopedia por favor solicita una salida de paquete.';
		        $scope.tipo = true;
		        break;
		    case "3":
		        $scope.mensajetipo = 'Te recordamos que los Rx están conveniados dentro del paquete.';
		        $scope.tipo = true;
		        break;
		    case "1":
		    	$scope.mensajetipo = 'Según su lesión y siguiendo el criterio y las indicaciones del médico tratante es recomendable proporcionarle al lesionado ejercicio para realizar por su cuenta. Aunado a lo anterior les recordamos que las primeras cinco sesiones de rehabilitación están previamente convenidas dentro del paquete.';
		    	$scope.tipo = true;
		    	break;
		    case "9":
		    	$scope.mensajetipo = 'Pendiente';
		    	$scope.tipo = true;
		    	break;
		    case "11":
		    	$scope.mensajetipo = 'La coordinación médica es quien dictaminará, en su caso, la entrega o rechazo de informes médicos y estudios a los pacientes, de acuerdo a las indicaciones de cada compañía de seguros.';
		    	$scope.tipo = true;
		    	break;
		   	case "21":
		    	$scope.mensajetipo = 'Se debe solicitar salida de paquete cuando el tratamiento amerite periodo de tiempo en corta estancia, observación, hospitalización o procedimientos quirúrgicos, terapia intermedia o intensiva.';
		    	$scope.tipo = true;
		    	break;
		    case "2":
		    	$scope.mensajetipo = 'Pendiente';
		    	$scope.tipo = true;
		    	break;
		    default:
		    	$scope.mensajetipo = '';
		    	$scope.tipo = false;

		}

		

		//console.log($scope.mensajetipo);
	}

	$scope.siguiente = function(){
		
		 WizardHandler.wizard().next();

	}

	$scope.final = function() {

		$('#final').button('loading');

        console.log($scope.datos.usuario);

        movimientos.ingresaSolicitud($scope.datos).success(function (data){
        	console.log(data);
        	$('#final').button('reset');
        	$scope.clavesolicitud = data.clave;
        	$scope.mensaje = data.respuesta;
        })

    }


    $scope.generaComprobante = function(){
	
    }

    

	$scope.onFileSelect = function($files) {

	    //var archivo = $files.length;
	    $scope.max = 100;
	    $scope.archivos = $files.length;
	    $scope.archivo = 1;
	    $scope.cargando = true;
	    

	    for (var i = 0; i < $files.length; i++) {

	      var file = $files[i];
	      $scope.valor = 0;

	      try{
	      	console.log(file.type);

	        if (file.type.indexOf('image') == -1 && file.type.indexOf('application/pdf') == -1 && file.type.indexOf('officedocument') == -1 && file.type.indexOf('application/msword') == -1 && file.type.indexOf('application/vnd.ms-excel') == -1 ) {

	             throw 'La extension no es de tipo permitido'; 
	        }

	        $scope.upload = $upload.upload({
	            url: 'api/api.php?funcion=temporal', //upload.php script, node.js route, or servlet url
	            method: 'POST',
	            data: {dato: 'datos Enviados'},
	            file: file
	        }).progress(function(evt) {

	            //console.log('porcentaje: ' + parseInt(100.0 * evt.loaded / evt.total));
	            $scope.valor = parseInt(100.0 * evt.loaded / evt.total);

	        }).success(function(data, status, headers, config) {

	            // imagen se guardo con exito en temporales
	            $scope.files.push({
	              nombre:data.nombre,
	              ubicacion:data.ubicacion,
	              tipo:data.tipo
	            });

	            //Verificamos si es el ultimo archivo a subir para que mande la ventana de informacion de imagen
	            if ($scope.archivos == i) {

	                $scope.cargando = false;
	                $('#altadoc').modal({keyboard:false,backdrop:'static'});
	                $scope.inicioarchivo = 0;
	                $scope.numeroimagenes = Number($scope.archivos)-1;
	                $scope.nombreArchivo = $scope.files[0].nombre;
	                $scope.nombredoc = '';
	      			$scope.observacionesimagen = '';

	            }else{

	              $scope.archivo = Number($scope.archivo)+1;
	                
	            };

	        });

	      }catch(err){

	          alert(err);
	          $scope.cargando = false;

	      }

	      //para medir el peso del archivo
	      // }else{
	      //     if (file.size > 2097152){
	      //        $scope.error ='El archivo excede los 2MB dispnibles';
	      //   }

	    }

	}

	$scope.cancelaimagen = function(){

		$scope.documentos = [];
		$scope.files = [];
		angular.forEach(
		  angular.element("input[type='file']"),
		  function(inputElem) {
		    angular.element(inputElem).val(null);
		});

	}

	$scope.eliminaImagen = function(index){
		$scope.documentos.splice(index,1);
	}

	$scope.imagenInfo = function(){

		var file = $scope.files[$scope.inicioarchivo];

		console.log(file);

		if(file.tipo.indexOf('application/pdf') != -1){
			var muestra = "imgs/pdf.png";
		}else if(file.tipo.indexOf('officedocument') != -1 || file.tipo.indexOf('application/msword') != -1 || file.tipo.indexOf('application/vnd.ms-excel') != -1 ){
			var muestra = "imgs/office.png";
		}else{
			var muestra = file.ubicacion;
		}



		$scope.documentos.push({
		    archivo:file.nombre,
		    idtipo:$scope.nombredoc.TID_claveint,
		    tipo:$scope.nombredoc.TID_nombre,
		    observaciones:$scope.observacionesimagen,
		    ubicacion:muestra,
		    ubicacionreal:file.ubicacion
		});


		$scope.inicioarchivo++;


		if(Number($scope.inicioarchivo) > Number($scope.numeroimagenes)){

		  $('#altadoc').modal('hide');
		  $scope.files = [];

		}else{
		  
		  $scope.nombreArchivo = $scope.files[$scope.inicioarchivo].nombre;
		  $scope.nombredoc = '';
		  $scope.observacionesimagen = '';

		}

	}

    $scope.upperLimit = 10;
    $scope.lowerLimit = 0;
    $scope.unit = "";
    $scope.precision = 1;
    $scope.ranges = [
        {
            min: 0,
            max: 3,
            color: '#8DCA2F'
        },
        {
            min: 3,
            max: 7,
            color: '#FDC702'
        },
        {
            min: 7,
            max: 10,
            color: '#C50200'
        }
    ];

    $scope.upperLimit2 = 10;
    $scope.lowerLimit2 = 0;
    $scope.unit2 = "";
    $scope.precision2 = 1;
    $scope.ranges2 = [
        {
            min: 0,
            max: 3,
            color: '#8DCA2F'
        },
        {
            min: 3,
            max: 7,
            color: '#FDC702'
        },
        {
            min: 7,
            max: 10,
            color: '#C50200'
        }
    ];

    
    
});

app.controller('solicitudesCtrl', function ($scope, $cookies,$location, busquedas) {

	$scope.inicio = function(){

		$scope.cargaSolicitudes();
		$scope.cargaMasinfo();
		$scope.cargaRepondidas();
	}

	$scope.cargaSolicitudes = function(){
		$scope.buscar = true;
		if($cookies.cordinacion){
			busquedas.solicitudes().success(function (data){
				$scope.solicitudes = data;
				$scope.buscar = false;
			});
		}else{
			busquedas.missolicitudes().success(function (data){
				$scope.solicitudes = data;
				$scope.buscar = false;
			});
		}

	}

	$scope.cargaMasinfo = function(){

		$scope.buscar = true;

		busquedas.solicitudesInfo().success(function (data){
			$scope.solicitudesInformacion = data;
			$scope.buscar = false;
		});

		
	}

	$scope.cargaRepondidas = function(){

		$scope.buscar = true;

		busquedas.solicitudesRespuestas().success(function (data){
			$scope.solicitudesRespondidas = data;
			$scope.buscar = false;
		});

	}


	$scope.detalleSolicitud = function(clave){
		$location.path('/detalle/solicitud/' + clave);
	}


	$scope.seguimientoSolicitud = function(clave){
		$location.path('/solicitudmasinfo/' + clave);
	}

	$scope.respuestaSolicitud = function(clave){
		$location.path('/solicitudRespuesta/' + clave);
	}

});

app.controller('solicitudExpedienteCtrl', function ($scope, $cookies, $route, $routeParams, busquedas, movimientos, WizardHandler, $upload) {

	$scope.inicio = function(){ 

		$scope.files = [];
		$scope.documentos = [];
		$scope.nombredoc = '';
		$scope.mensaje = '';
		$scope.clavesolicitud = '';

		$scope.datos = {
			usuario:$cookies.usrLogin,
			folio:$routeParams.folio,
			lesionado:'',
			tipo:'',
			cliente:'',
			clientenombre:'',
			interconsulta:{
				tipo:'',
				embarazo:'',
				controlgineco:'NO',
				semanas:4,
				dolorabdominal:'NO',
				frecuencia:'',
				movimientosfetales:'',
				observaciones:''	
			},
			estudio:{
				tipo:'',
				detalle:''
			},
			rehabilitacion:{
				dolor:0,
				rehabilitaciones:0,
				mejora:0
			},
			suministro:{
				detalle:''
			},
			informacion:{
				notamedica:false,
				rx:false,
				resultados:false,
				detalle:''
			},
			salidapaquete:{
				detalle:''
			},
			problemadocumental:{
				pase:false,
				identificacion:false,
				detalle:''
			},
			diagnostico:'',
			justificacion:'',
			soporte:$scope.documentos
		}

		$scope.ultimo = 'Lesionado';

		$scope.paso = 0;
		$scope.acutal = '';

		$scope.buscar = false;

		$scope.muestraExpedientes();
		$scope.buscaTipoDoc();


	}

	$scope.buscaTipoDoc = function(){

		busquedas.tipoDocumento().success(function (data){
			$scope.tiposDocumentos = data;
		});

	}

	$scope.muestraExpedientes = function(){

		busquedas.folio($routeParams.folio).success(function (data){
			console.log(data);
			$scope.datos.lesionado = data[0].lesionado;
			$scope.datos.cliente = data[0].idcliente;
			$scope.datos.clientenombre = data[0].cliente;
		});

	}


	$scope.buscaLesionado = function(lesionado){

		$scope.buscar = true;
		busquedas.lesionado(lesionado).success(function (data){
			$scope.expedientes = data;
			$scope.buscar = false;
		});
	}

	$scope.datosPaciente = function(folio){

		var datos = $scope.expedientes[valor];
		
		

		WizardHandler.wizard().next();

		//console.log($scope.datos);

	}

	$scope.mensaje = function(){

		$scope.tipo = false;


		var valor = $scope.datos.tipo;

		console.log(valor);
		// debugger;

		switch(valor){
			case "4":
		        $scope.mensajetipo = 'Te recordamos que dentro del paquete pactado está incluida la atención inicial y subsecuente por médico traumatólogo o urgenciólogo. Así como también la consulta de valoración por médico de cualquier otra especialidad, en caso de requerir tratamiento de una especialidad distinta a Traumatología y Ortopedia por favor solicita una salida de paquete.';
		        $scope.tipo = true;
		        break;
		    case "3":
		        $scope.mensajetipo = 'Te recordamos que los Rx están conveniados dentro del paquete.';
		        $scope.tipo = true;
		        break;
		    case "1":
		    	$scope.mensajetipo = 'Según su lesión y siguiendo el criterio y las indicaciones del médico tratante es recomendable proporcionarle al lesionado ejercicio para realizar por su cuenta. Aunado a lo anterior les recordamos que las primeras cinco sesiones de rehabilitación están previamente convenidas dentro del paquete.';
		    	$scope.tipo = true;
		    	break;
		    case "9":
		    	$scope.mensajetipo = 'Pendiente';
		    	$scope.tipo = true;
		    	break;
		    case "11":
		    	$scope.mensajetipo = 'La coordinación médica es quien dictaminará, en su caso, la entrega o rechazo de informes médicos y estudios a los pacientes, de acuerdo a las indicaciones de cada compañía de seguros.';
		    	$scope.tipo = true;
		    	break;
		   	case "21":
		    	$scope.mensajetipo = 'Se debe solicitar salida de paquete cuando el tratamiento amerite periodo de tiempo en corta estancia, observación, hospitalización o procedimientos quirúrgicos, terapia intermedia o intensiva.';
		    	$scope.tipo = true;
		    	break;
		    case "2":
		    	$scope.mensajetipo = 'Pendiente';
		    	$scope.tipo = true;
		    	break;
		    default:
		    	$scope.mensajetipo = '';
		    	$scope.tipo = false;

		}

		

		//console.log($scope.mensajetipo);
	}

	$scope.siguiente = function(){
		
		 WizardHandler.wizard().next();

	}

	$scope.final = function() {

		$('#final').button('loading');

        console.log($scope.datos.usuario);

        movimientos.ingresaSolicitud($scope.datos).success(function (data){
        	console.log(data);
        	$('#final').button('reset');
        	$scope.clavesolicitud = data.clave;
        	$scope.mensaje = data.respuesta;
        })

    }


    $scope.generaComprobante = function(){
	
    }

    

	$scope.onFileSelect = function($files) {

	    //var archivo = $files.length;
	    $scope.max = 100;
	    $scope.archivos = $files.length;
	    $scope.archivo = 1;
	    $scope.cargando = true;
	    

	    for (var i = 0; i < $files.length; i++) {

	      var file = $files[i];
	      $scope.valor = 0;

	      try{
	      	console.log(file.type);

	        if (file.type.indexOf('image') == -1 && file.type.indexOf('application/pdf') == -1 && file.type.indexOf('officedocument') == -1 && file.type.indexOf('application/msword') == -1 && file.type.indexOf('application/vnd.ms-excel') == -1 ) {

	             throw 'La extension no es de tipo permitido'; 
	        }

	        $scope.upload = $upload.upload({
	            url: 'api/api.php?funcion=temporal', //upload.php script, node.js route, or servlet url
	            method: 'POST',
	            data: {dato: 'datos Enviados'},
	            file: file
	        }).progress(function(evt) {

	            //console.log('porcentaje: ' + parseInt(100.0 * evt.loaded / evt.total));
	            $scope.valor = parseInt(100.0 * evt.loaded / evt.total);

	        }).success(function(data, status, headers, config) {

	            // imagen se guardo con exito en temporales
	            $scope.files.push({
	              nombre:data.nombre,
	              ubicacion:data.ubicacion,
	              tipo:data.tipo
	            });

	            //Verificamos si es el ultimo archivo a subir para que mande la ventana de informacion de imagen
	            if ($scope.archivos == i) {

	                $scope.cargando = false;
	                $('#altadoc').modal({keyboard:false,backdrop:'static'});
	                $scope.inicioarchivo = 0;
	                $scope.numeroimagenes = Number($scope.archivos)-1;
	                $scope.nombreArchivo = $scope.files[0].nombre;
	                $scope.nombredoc = '';
	      			$scope.observacionesimagen = '';

	            }else{

	              $scope.archivo = Number($scope.archivo)+1;
	                
	            };

	        });

	      }catch(err){

	          alert(err);
	          $scope.cargando = false;

	      }

	    }

	}

	$scope.cancelaimagen = function(){

		$scope.documentos = [];
		$scope.files = [];
		angular.forEach(
		  angular.element("input[type='file']"),
		  function(inputElem) {
		    angular.element(inputElem).val(null);
		});

	}

	$scope.eliminaImagen = function(index){
		$scope.documentos.splice(index,1);
	}

	$scope.imagenInfo = function(){

	var file = $scope.files[$scope.inicioarchivo];

	console.log(file.tipo);

	if(file.tipo.indexOf('application/pdf') != -1){
		var muestra = "imgs/pdf.png";
	}else if(file.tipo.indexOf('officedocument') != -1 || file.tipo.indexOf('application/msword') != -1 || file.tipo.indexOf('application/vnd.ms-excel') != -1 ){
		var muestra = "imgs/office.png";
	}else{
		var muestra = file.ubicacion;
	}



	$scope.documentos.push({
	    archivo:file.nombre,
	    idtipo:$scope.nombredoc.TID_claveint,
	    tipo:$scope.nombredoc.TID_nombre,
	    observaciones:$scope.observacionesimagen,
	    ubicacion:muestra,
	    ubicacionreal:file.ubicacion
	});


	$scope.inicioarchivo++;


	if(Number($scope.inicioarchivo) > Number($scope.numeroimagenes)){

	  $('#altadoc').modal('hide');
	  $scope.files = [];

	}else{
	  
	  $scope.nombreArchivo = $scope.files[$scope.inicioarchivo].nombre;
	  $scope.nombredoc = '';
	  $scope.observacionesimagen = '';

	}

	}

    $scope.upperLimit = 10;
    $scope.lowerLimit = 0;
    $scope.unit = "";
    $scope.precision = 1;
    $scope.ranges = [
        {
            min: 0,
            max: 3,
            color: '#8DCA2F'
        },
        {
            min: 3,
            max: 7,
            color: '#FDC702'
        },
        {
            min: 7,
            max: 10,
            color: '#C50200'
        }
    ];

    $scope.upperLimit2 = 10;
    $scope.lowerLimit2 = 0;
    $scope.unit2 = "";
    $scope.precision2 = 1;
    $scope.ranges2 = [
        {
            min: 0,
            max: 3,
            color: '#8DCA2F'
        },
        {
            min: 3,
            max: 7,
            color: '#FDC702'
        },
        {
            min: 7,
            max: 10,
            color: '#C50200'
        }
    ];
    
});

app.controller('detalleSolicitudCtrl', function ($scope, $routeParams, $cookies, busquedas, movimientos) {

	$scope.inicio = function(){
		$scope.clave = $routeParams.clave;
		$scope.cargaDetalle($scope.clave);
		$scope.terminado = false;
		$scope.info = false;
	}

	$scope.cargaDetalle = function(clave){

		busquedas.detalleSolicitud(clave).success(function (data){
			//console.log(data);
			$scope.datos = data.info;
			$scope.archivos = data.archivos;
			$scope.buscar = false;
		});

	}

	$scope.estatus = function(estatus){

		if (estatus != 2) {

			movimientos.actualizaSolicitud($scope.clave, estatus).success(function (data){
				$scope.mensaje = data.respuesta;

				$scope.terminado = true;
			});
			
		}else{

			$scope.info = true;

			$scope.datosinfo = {

				clave:$scope.clave,
				usuario: $cookies.usrLogin,
				descripcion:''
			}

		}
	}

	$scope.mandamensaje = function(){

		$('#boton').button('loading');

		//console.log($scope.datosinfo);
		movimientos.actualizaSolicitud($scope.clave, 2).success(function (data){
				
				movimientos.guardaSolicitudInfo($scope.datosinfo).success(function (data){

					$scope.mensaje = data.respuesta;
					$('#boton').button('reset');
					
					$scope.info = false;
					$scope.terminado = true;
					
				});
		});

	}

});

app.controller('solicitudMasInfoCtrl',function ($scope, $routeParams, $cookies, $upload, busquedas, movimientos){

	$scope.inicio = function(){

		$scope.files = [];
		$scope.documentos = [];
		$scope.solicitoinfo = false;

		$scope.clave = $routeParams.clave;
		$scope.usuario = $cookies.usrLogin;
		$scope.muestraSeguimiento();
		$scope.buscaTipoDoc();
		$scope.mensaje = '';

		$scope.datosinfo = {
			clave:$scope.clave,
			usuario: $cookies.usrLogin,
			descripcion:'',
			soporte:$scope.documentos
		}

	}


	$scope.buscaTipoDoc = function(){

		busquedas.tipoDocumento().success(function (data){
			$scope.tiposDocumentos = data;
		});

	}

	$scope.muestraSeguimiento = function(){

		busquedas.detalleSolicitudMasInfo($scope.clave).success(function (data){
			$scope.seguimientos = data;
		});
	}

	$scope.estatus = function(estatus){

		movimientos.actualizaSolicitud($scope.clave, estatus).success(function (data){
			$scope.mensaje = data.respuesta;
		});
			
	}

	$scope.mandamensaje = function(){

		$('#boton').button('loading');

		//console.log($scope.datosinfo);
		movimientos.actualizaSolicitud($scope.clave, 2).success(function (data){
				
				movimientos.guardaSolicitudInfo($scope.datosinfo).success(function (data){

					$scope.mensaje = data.respuesta;
					$('#boton').button('reset');
					$scope.inicio();
					$scope.muestraSeguimiento();
					$scope.solicitoinfo = true;

				});
		});

	}


	$scope.onFileSelect = function($files) {

	    //var archivo = $files.length;
	    $scope.max = 100;
	    $scope.archivos = $files.length;
	    $scope.archivo = 1;
	    $scope.cargando = true;
	    

	    for (var i = 0; i < $files.length; i++) {

	      var file = $files[i];
	      $scope.valor = 0;

	      try{
	      	console.log(file.type);

	        if (file.type.indexOf('image') == -1 && file.type.indexOf('application/pdf') == -1 && file.type.indexOf('officedocument') == -1 && file.type.indexOf('application/msword') == -1 && file.type.indexOf('application/vnd.ms-excel') == -1 ) {

	             throw 'La extension no es de tipo permitido'; 
	        }

	        $scope.upload = $upload.upload({
	            url: 'api/api.php?funcion=temporal', //upload.php script, node.js route, or servlet url
	            method: 'POST',
	            data: {dato: 'datos Enviados'},
	            file: file
	        }).progress(function(evt) {

	            //console.log('porcentaje: ' + parseInt(100.0 * evt.loaded / evt.total));
	            $scope.valor = parseInt(100.0 * evt.loaded / evt.total);

	        }).success(function(data, status, headers, config) {

	            // imagen se guardo con exito en temporales
	            $scope.files.push({
	              nombre:data.nombre,
	              ubicacion:data.ubicacion,
	              tipo:data.tipo
	            });

	            //Verificamos si es el ultimo archivo a subir para que mande la ventana de informacion de imagen
	            if ($scope.archivos == i) {

	                $scope.cargando = false;
	                $('#altadoc2').modal({keyboard:false,backdrop:'static'});
	                $scope.inicioarchivo = 0;
	                $scope.numeroimagenes = Number($scope.archivos)-1;
	                $scope.nombreArchivo = $scope.files[0].nombre;
	                $scope.nombredoc = '';
	      			$scope.observacionesimagen = '';

	            }else{

	              $scope.archivo = Number($scope.archivo)+1;
	                
	            };

	        });

	      }catch(err){

	          alert(err);
	          $scope.cargando = false;

	      }

	      //para medir el peso del archivo
	      // }else{
	      //     if (file.size > 2097152){
	      //        $scope.error ='El archivo excede los 2MB dispnibles';
	      //   }

	    }

	}

	$scope.cancelaimagen = function(){

		$scope.documentos = [];
		$scope.files = [];
		angular.forEach(
		  angular.element("input[type='file']"),
		  function(inputElem) {
		    angular.element(inputElem).val(null);
		});

	}

	$scope.eliminaImagen = function(index){
		$scope.documentos.splice(index,1);
	}

	$scope.imagenInfo = function(){

		var file = $scope.files[$scope.inicioarchivo];

		console.log(file.tipo);

		if(file.tipo.indexOf('application/pdf') != -1){
			var muestra = "imgs/pdf.png";
		}else if(file.tipo.indexOf('officedocument') != -1 || file.tipo.indexOf('application/msword') != -1 || file.tipo.indexOf('application/vnd.ms-excel') != -1 ){
			var muestra = "imgs/office.png";
		}else{
			var muestra = file.ubicacion;
		}



		$scope.documentos.push({
		    archivo:file.nombre,
		    idtipo:$scope.nombredoc.TID_claveint,
		    tipo:$scope.nombredoc.TID_nombre,
		    observaciones:$scope.observacionesimagen,
		    ubicacion:muestra,
		    ubicacionreal:file.ubicacion
		});


		$scope.inicioarchivo++;


		if(Number($scope.inicioarchivo) > Number($scope.numeroimagenes)){

		  $('#altadoc2').modal('hide');
		  $scope.files = [];

		}else{
		  
		  $scope.nombreArchivo = $scope.files[$scope.inicioarchivo].nombre;
		  $scope.nombredoc = '';
		  $scope.observacionesimagen = '';

		}

	}

});

