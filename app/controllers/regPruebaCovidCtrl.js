app.controller('regPruebaCovidCtrl', function($scope,$rootScope, $http,$cookies,$cookieStore,$location,$routeParams,webStorage) {
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

    $scope.inicio = function(){
        $rootScope.rutaImgPro=$cookies.rutaImgPro;
        $rootScope.rutaImgCom= $cookies.rutaImgCom;
        $rootScope.ciaClave= $cookies.clave;
        $rootScope.cveProd= $cookies.clavePro;
        $rootScope.uniClave=$cookies.uniClave;
        $rootScope.usrLogin= $cookies.usrLogin;
        $rootScope.promocion= $cookies.promocion;
        $rootScope.cargador=false;
        $scope.busca={
            nombre:'',
            folio:''
        }

        $scope.datos={
            nombre:'',
            pat:'',
            mat:'',
            fecnac:'',
            tel:'',
            numeroTel:'',
            correo:'',
            curp:'',
            sexo:''
        }

        $rootScope.convenio='';

        $scope.msjCorreo ='Si el usuario no proporciona el correo perderá los cupones de descuento, ¿Desea continuar?';
        if($rootScope.cveProd=='10'){
            $scope.msjCorreo ='El paciente no desea recibir sus estudios por correo, ¿Desea continuar?';
        }

        $rootScope.hoy=dd+'-'+mm+'-'+yyyy;

        console.info( $rootScope.ciaClave );
        console.info( $rootScope.clavePro );
        
        //modal para Orden de Registro

        $('#modalBuscaPaciente').modal({ 
            keyboard: false,
            backdrop: 'static'
        });
    }

    $scope.interacted = function(field) {
      //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
      return $scope.etapa1.$submitted && field.$invalid;
    };

    $scope.regresaPagina = function(){
        $('#modalBuscaPaciente').modal('hide');
        window.history.back();
    }

    $scope.regNuevo = function(){
        $('#modalBuscaPaciente').modal('hide');
        $location.path("/regPruebaCovid");
    }

    $scope.buscaXFolioMv = function(){ 
    $rootScope.cargador=true;            
        $http({
            url:'api/api.php?funcion=buscaXFolioMv',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.busca
        }).success( function (data){
            $('#modalBuscaPaciente').modal('hide');                                           
            $rootScope.cargador=false;
            $scope.estadoUltimo=data;
            //console.log($scope.estadoUltimo[0]);
            if($scope.estadoUltimo!=''){
                $scope.datos.nombre=$scope.estadoUltimo[0].Exp_nombre;
                $scope.datos.pat=$scope.estadoUltimo[0].Exp_paterno;
                $scope.datos.mat=$scope.estadoUltimo[0].Exp_materno;
                $scope.datos.fecnac=$scope.estadoUltimo[0].Exp_fechaNac;
                $scope.datos.sexo=$scope.estadoUltimo[0].Exp_sexo;
                $scope.datos.curp=$scope.estadoUltimo[0].RFC_rfc;
                $scope.datos.correo=$scope.estadoUltimo[0].Exp_mail;
                $scope.datos.numeroTel=$scope.estadoUltimo[0].Exp_telefono;
            }
            $scope.busca={
                nombre:'',
                folio:''
            }
        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });                        
    }

    $scope.buscaXnombre = function(){ 
    $rootScope.cargador=true;            
        $http({
            url:'api/api.php?funcion=buscaXnombre',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.busca
        }).success( function (data){
            //console.log(data);
            $('#modalBuscaPaciente').modal('hide');
            $rootScope.cargador=false;
            $scope.estadoUltimo=data;
            console.log($scope.estadoUltimo[0]);
            if($scope.estadoUltimo!=''){
                $scope.datos.nombre=$scope.estadoUltimo[0].Exp_nombre;
                $scope.datos.pat=$scope.estadoUltimo[0].Exp_paterno;
                $scope.datos.mat=$scope.estadoUltimo[0].Exp_materno;
                $scope.datos.fecnac=$scope.estadoUltimo[0].Exp_fechaNac;
                $scope.datos.sexo=$scope.estadoUltimo[0].Exp_sexo;
                $scope.datos.curp=$scope.estadoUltimo[0].RFC_rfc;
                $scope.datos.correo=$scope.estadoUltimo[0].Exp_mail;
                $scope.datos.numeroTel=$scope.estadoUltimo[0].Exp_telefono;
            }
            $scope.busca={
                nombre:'',
                folio:''
            }
        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });                        
    }
    

    $scope.guardaEtapa1 = function(){
        console.log("click");
        if($scope.etapa1.$valid){
            console.log("click");
            $scope.cargador=true;
            $rootScope.usrLogin =  $cookies.usrLogin;
            $rootScope.uniClave = $cookies.uniClave;
            
            $scope.mensaje = '';
            $http({
                url:'api/api.php?funcion=registraCovid&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave,
                method:'POST',
                contentType: 'application/json',
                dataType: "json",
                data: $scope.datos
            }).success( function (data){
                console.log(data);
                $scope.mensaje = data.respuesta;
                alert($scope.mensaje);
                $scope.datos='';
                location.reload();
                
                $scope.cargador=false;                
                $scope.btnGuardar=true;

            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra ';
                alert('Error al registrar');
            });     
        }
    }

    /***************************************   fin funcion como se enterṕ **************************************************/

});


function valMail(valor) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3,4})+$/.test(valor)){
       return true;
    } else {
       return false;
    }
}

