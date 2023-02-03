app.controller('registraCtrl', function($scope,$rootScope, $http,$cookies,$location,$routeParams) {	

$scope.inicio = function(){
    
    $rootScope.rutaImgPro=$cookies.rutaImgPro;
    $rootScope.rutaImgCom= $cookies.rutaImgCom;
    $rootScope.ciaClave= $cookies.clave;
    $rootScope.cveProd= $cookies.clavePro;
    $rootScope.uniClave=$cookies.uniClave;

    $rootScope.convenio='';

    /***************  clave de unidad alternativa *************/
    $rootScope.UniClaveAlternativa!='';
    $rootScope.UniClaveAlternativa = $cookies.cveUnidadAlternativa;
    if($rootScope.UniClaveAlternativa!=''){
        $rootScope.uniClave=$rootScope.UniClaveAlternativa;        
    }
    
    console.log($rootScope.uniClave);

    $scope.autoriza=false;
    $scope.mensajeError=false;
    $scope.validaInputAut=false;
    $scope.veCDB=false;
    $scope.respuesta ='';
    $scope.segundaEtapa=false;
    $scope.errorAut=false;
    $scope.veCupon=false;
    $scope.cargador=false;
    $scope.cargador1=false;
    $scope.valida=false;
    $scope.desactivaBoton=false;
    $scope.divParticulares= false;
    $scope.divEmpleado= false;
    $scope.divConvenio= false;
    $scope.codigoE=false;
    if($rootScope.ciaClave==19){
        $scope.valida=true
    }
    $scope.checado=true;

    $scope.datos={
    	nombre:'',
    	pat:'',
    	mat:'',
    	fecnac:'',
    	tel:'',
    	numeroTel:'',
    	correo:'',
        codPostal:'',
        esChedraui:0,
        folioMembresia:''
    }
    $scope.datosSin={
        cobAfec:'',
        inciso:'',
        noCia:'',
        poliza:'',
        siniestro:'',
        reporte:'',
        folPase:'',
        obs:'',
        ajustador:'',
        cveAjustador:'',
        telAjustador:'',
        deducibleMonto:'',
        obsDed:'',
        deducible:'',
        orden:'',
        bitacora:''
    }
    $scope.obsPart={
        observacion:'',
        entero:0,
        cualTar:'',
        quienEnvTar:'',
        folioVale:'',
        cualPag:'',
        quienEnvVal:''
    }
    $scope.cor={
        autoriza:'',
        manda:'',
        obs:''
    }
     $scope.empleado={
        noEmp:'',
        area:'',
        jefeInm:'',
        obs:''
    }
    $scope.convenio={        
        obs:''
    }
    $scope.telefonos={
        cont:'',
        tip:'',
        tel:''
    }  
     $scope.msjTel=false;
     $scope.telSum=[];
     $scope.sumaTel =[] ;     
    
     /************* valiable para validar Chedraui ******/
    $scope.banortePersonal=false;   
    if($rootScope.ciaClave==8&&$rootScope.cveProd==2){
        $scope.banortePersonal=true;
    }
    /***************************************************/


    if($rootScope.ciaClave==54){
        $rootScope.convenio=$cookies.convenio;
        if($rootScope.convenio==3){
            $scope.datos.folioMembresia = $cookies.folioMembresia;
        }
    }
   
}
$scope.interacted = function(field) {
          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
          return $scope.etapa1.$submitted && field.$invalid;
        };
$scope.interacted1 = function(field) {
          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
          return $scope.etapa2.$submitted && field.$invalid;
        };
/*
$scope.validaEmail = function(){
        alert($scope.datos.mail);
        $scope.valida=valMail($scope.datos.mail);
        if($scope.valida){
            $scope.veCupon=true;
        }
}*/

$scope.$watch('datos.correo',function(valorant,valornuev){    
    if(valorant){
        $scope.veCupon=true;
    }
    else{
        $scope.veCupon=false;   
    }
});

$scope.veTabla=false;
if($scope.sumaTel==undefined){
   $scope.veTabla=false; 
}else{
    $scope.veTabla=true; 
}


    var cont=1;
    $scope.agregaTel = function(){  
        if($scope.telefonos.tip!='' && $scope.telefonos.tel!=''){
            $scope.msjTel=false;
            if($scope.sumaTel==undefined){
               $scope.veTabla=false; 
            }else{
                $scope.veTabla=true; 
            }          
            $scope.cont ={} ;
            switch($scope.telefonos.tip){
                case "1":
                    $scope.telefonos.tipoLe="Particular";                    
                break;
                case "2":
                    $scope.telefonos.tipoLe="Oficina";                    
                break;
                case "3":
                    $scope.telefonos.tipoLe="Móvil";                    
                break;
                case "4":
                    $scope.telefonos.tipoLe="Otro";                    
                break;

            }
            $scope.cont.tip=$scope.telefonos.tip;
            $scope.cont.tipoLe=$scope.telefonos.tipoLe;
            $scope.cont.tel=$scope.telefonos.tel; 
            $scope.cont.cont=cont;            
            $scope.sumaTel.push($scope.cont); 
            $scope.telefonos={
                tip:'',
                tel:''
            }                     
            cont++; 
            console.log($scope.sumaTel);   
        }else{
            $scope.msjTel=true;
        }
    }

    $scope.eliminaTelefono = function(contaTel){  
        console.log('entró');
        contaTel=contaTel-1;
        delete $scope.sumaTel[contaTel]; 
        $scope.sumaTel.splice(contaTel,1);               
        console.log($scope.sumaTel);
    }  

$scope.guardaEtapa1 = function(){
    if($scope.etapa1.$valid){
        if($scope.codigoE==false){
        $scope.cargador=true;
        $scope.desactivaBoton=true;		
		$rootScope.usrLogin =  $cookies.usrLogin;
        $rootScope.uniClave = $cookies.uniClave;
        $rootScope.UniClaveAlternativa = $cookies.cveUnidadAlternativa;
        if($rootScope.UniClaveAlternativa!=''){
            $rootScope.uniClave=$rootScope.UniClaveAlternativa;        
        }
		$scope.mensaje = '';       
        if($scope.sumaTel!=''){
           $scope.todo= [$scope.datos,$scope.sumaTel];
        }else{
            $scope.todo=[$scope.datos];
        }         
        //edad=chkdate($scope.datos.fecnac,1);        
        $http({
                    url:'api/api.php?funcion=checaDuplicado&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave+'&ciaClave='+$rootScope.ciaClave+'&prod='+$rootScope.cveProd,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.todo
                    }).success( function (data){                        
                        if(data!='nada'){  
                            $scope.desactivaBoton=false;                       
                            $('#modalDuplicado').modal();  
                            $scope.listadoFolDupli=data; 
                            $scope.cargador=false;                                    
                        }else{
                            $http({
                                url:'api/api.php?funcion=registra&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave+'&ciaClave='+$rootScope.ciaClave+'&prod='+$rootScope.cveProd+'&convenio='+ $rootScope.convenio,
                                method:'POST', 
                                contentType: 'application/json', 
                                dataType: "json", 
                                data: $scope.todo
                            }).success( function (data){  
                                console.log(data);                           
                                $scope.mensaje = data.respuesta;
                                $scope.folio = data.folio;
                                $scope.cargador=false;
                                $scope.veCDB=true;        
                                if($rootScope.ciaClave==44){
                                    $scope.autoriza=true;             
                                    $scope.primeraEtapa=true;
                                    $scope.btnGuardar=true;
                                }
                                else if($rootScope.ciaClave==51){
                                    $scope.divParticulares=true;             
                                    $scope.primeraEtapa=true;
                                    $scope.btnGuardar=true;
                                }
                                else if($rootScope.ciaClave==53){
                                    $scope.divEmpleado=true;             
                                    $scope.primeraEtapa=true;
                                    $scope.btnGuardar=true;
                                }
                                else if($rootScope.ciaClave==54){
                                    $scope.divConvenio=true;             
                                    $scope.primeraEtapa=true;
                                    $scope.btnGuardar=true;
                                }
                                else{
                                    if($rootScope.ciaClave==19){
                                        $scope.noCompania='Folio Electrónico';
                                    }
                                    else if($rootScope.ciaClave=10){
                                        $scope.noCompania='Folio de Derivación';   
                                    }
                                    else {
                                        $scope.noCompania='No. Compañía';
                                    }
                                    $scope.validaPase=true;
                                    $scope.primeraEtapa=true;
                                    $scope.btnGuardar=true;
                                    
                                    $http({
                                        url:'api/api.php?funcion=catCobertura',
                                        method:'POST', 
                                        contentType: 'application/json', 
                                        dataType: "json", 
                                        data: {'clave':1}
                                        }).success( function (data){                                     
                                            $scope.list= data;
                                        }).error( function (xhr,status,data){
                                            $scope.mensaje ='no entra ';            
                                            alert('Error cobertura');

                                        });
                                    }                                    
                                    }).error( function (xhr,status,data){
                                        $scope.mensaje ='no entra ';            
                                        alert('Error insercion');
                                    });
                        }
                        
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra ';            
                        alert('Error cobertura');

                    });
        }     
    }
}

$scope.registroValidado= function(){
        $scope.cargador=true;
        $scope.desactivaBoton=true;          
        $('#modalDuplicado').modal('hide');  
       $http({
            url:'api/api.php?funcion=registra&usr='+$rootScope.usrLogin+'&uniClave='+$rootScope.uniClave+'&ciaClave='+$rootScope.ciaClave+'&prod='+$rootScope.cveProd,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.todo
        }).success( function (data){ 
            console.log(data);      
            $scope.mensaje = data.respuesta;
            $scope.folio = data.folio;
            $scope.cargador=false;
            $scope.veCDB=true;        
            if($rootScope.ciaClave==44){
                $scope.autoriza=true;             
                $scope.primeraEtapa=true;
                $scope.btnGuardar=true;
            }
            else if($rootScope.ciaClave==51){
                $scope.divParticulares=true;             
                $scope.primeraEtapa=true;
                $scope.btnGuardar=true;
            }
            else{
                if($rootScope.ciaClave==19){
                    $scope.noCompania='Folio Electrónico';
                }
                else if($rootScope.ciaClave=10){
                    $scope.noCompania='Folio de Derivación';   
                }
                else {
                    $scope.noCompania='No. Compañía';
                }
                $scope.validaPase=true;
                $scope.primeraEtapa=true;
                $scope.btnGuardar=true;
                
                $http({
                    url:'api/api.php?funcion=catCobertura',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: {'clave':1}
                    }).success( function (data){                                     
                        $scope.list= data;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra ';            
                        alert('Error cobertura');

                    });
                }                                    
                }).error( function (xhr,status,data){
                    $scope.mensaje ='no entra ';            
                    alert('Error insercion');
                });
    }

    $scope.validaAutorizacion= function(){
        $scope.validaInputAut=true;
        $scope.errorAut=false;
        if($scope.autorizacion!=''){
            noAut=$scope.autorizacion;            
            $http({
                    url:'api/api.php?funcion=validaAut',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: {'aut':noAut}
                    }).success( function (data){   
                        if(data.respuesta=='correcto'){                            
                            $scope.segundaEtapa=true;
                            $scope.validaPase=false;
                            $scope.validaInputAut=false;
                            $scope.mensaje='';
                        } 
                        else{
                            $scope.errorAut=true;
                        }                                         
                        $scope.respuesta= data.aut;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
        }

    }

    $scope.guardaEtapa2 = function(){ 
        if($scope.etapa2.$valid){  
            $scope.cargador1=true;     
            if($rootScope.ciaClave==19&&$scope.datosSin.siniestro==''&&$scope.datosSin.reporte==''){
                $scope.mensajeError=true;
                $scope.cargador1=false;  
            }else{
                $scope.mensajeError=false;
            $http({
                url:'api/api.php?funcion=registraSin&fol='+$scope.folio+'&usr='+$rootScope.usrLogin,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.datosSin
                }).success( function (data){  
                    console.log(data);                 
                    if(data.respuesta=='correcto'){ 
                        $scope.cargador1=false;                           
                      $cookies.folio = data.folio;
                      $location.path("/portada");
                    } 
                    else{
                        $scope.errorAut=true;
                    }                                        
                    $scope.respuesta= data.aut;
                }).error( function (xhr,status,data){
                    $scope.mensaje ='no entra';                            
                });
            }    
        }
    }

    $scope.registraParticular = function(){ 
        $scope.cargador1=true;        
        if($scope.obsPart.observacion!=''){                
        $http({
            url:'api/api.php?funcion=registraPart&fol='+$scope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.obsPart
            }).success( function (data){                   
                if(data.respuesta=='correcto'){ 
                    $scope.cargador1=false;                           
                  $cookies.folio = data.folio;
                  $location.path("/portada");
                } 
                else{
                    $scope.errorAut=true;
                }                                        
                $scope.respuesta= data.aut;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';                            
            });
        }else {
            $scope.cargador1=false;                           
            $cookies.folio = $scope.folio;
            $location.path("/portada");
        }                       
    }  

    $scope.registraEmpleado = function(){ 

        console.log($scope.empleado);
        $scope.cargador1=true;                            
        $http({
            url:'api/api.php?funcion=registraEmpleado&fol='+$scope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.empleado
            }).success( function (data){                   
                if(data.respuesta=='correcto'){ 
                    $scope.cargador1=false;                           
                  $cookies.folio = data.folio;
                  $location.path("/portada");
                } 
                else{
                    $scope.errorAut=true;
                }                                        
                $scope.respuesta= data.aut;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';                            
            });                            
    }  

    $scope.registraConvenio = function(){        
        $scope.cargador1=true;                            
        $http({
            url:'api/api.php?funcion=registraConvenio&fol='+$scope.folio+'&convenio='+$cookies.convenio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.convenio
            }).success( function (data){                   
                if(data.respuesta=='correcto'){ 
                    $scope.cargador1=false;                           
                  $cookies.folio = data.folio;
                  $location.path("/portada");
                } 
                else{
                    $scope.errorAut=true;
                }                                        
                $scope.respuesta= data.aut;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';                            
            });                            
    }  

    
    $scope.registraCortesia = function(){ 
        $scope.cargador1=true; 
        console.log($scope.cor);                   
        $http({
            url:'api/api.php?funcion=registraCort&fol='+$scope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.cor
            }).success( function (data){                   
                if(data.respuesta=='correcto'){ 
                    $scope.cargador1=false;                           
                  $cookies.folio = data.folio;
                  $location.path("/portada");
                } 
                else{
                    $scope.errorAut=true;
                }                                        
                $scope.respuesta= data.aut;
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';                            
            });                        
    }  

     $scope.verificaCodigo = function(){                         
            if($scope.datos.codPostal!='00000'){
            $http.get('api/api.php?funcion=codExiste&cod='+$scope.datos.codPostal).success(function (data){                
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

    /********************************* funcion para vericar el tipo de dato de como se enteró ***************************/
    $scope.verificaRadio = function(){
        console.log($scope.obsPart.entero);
        if($scope.obsPart.entero==1){
            $scope.ver1=true;
            $scope.ver4=false;
            $scope.ver9=false;
        }else if($scope.obsPart.entero==4){
            $scope.ver4=true;
            $scope.ver1=false;
            $scope.ver9=false;
        }else if($scope.obsPart.entero==9){
            $scope.ver9=true;
            $scope.ver1=false;
            $scope.ver4=false;
        }else{
            $scope.ver1=false;
            $scope.ver4=false;
            $scope.ver9=false;            
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

function chkdate(objName)
{
//var strDatestyle = "US"; //United States date style
var strDatestyle = "EU";  //European date style
var strDate;
var strDateArray;
var strDay;
var strMonth;
var strYear;
var intday;
var intMonth;
var intYear;
var booFound = false;
var datefield = objName;
var strSeparatorArray = new Array("-"," ","/",".");
var intElementNr;
var err = 0;
var strMonthArray = new Array(12);

var d = new Date();
var dhoy =d.getDate();
var mhoy =d.getMonth()+1;
var ahoy =d.getFullYear();
var edad;

strMonthArray[0] = "Ene";
strMonthArray[1] = "Feb";
strMonthArray[2] = "Mar";
strMonthArray[3] = "Abr";
strMonthArray[4] = "May";
strMonthArray[5] = "Jun";
strMonthArray[6] = "Jul";
strMonthArray[7] = "Ago";
strMonthArray[8] = "Sep";
strMonthArray[9] = "Oct";
strMonthArray[10] = "Nov";
strMonthArray[11] = "Dic";
strDate = datefield;
if (strDate.length < 1) {
return true;
}
for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
if (strDate.indexOf(strSeparatorArray[intElementNr]) != -1) {
strDateArray = strDate.split(strSeparatorArray[intElementNr]);
if (strDateArray.length != 3) {
err = 1;
return false;
}
else {
strDay = strDateArray[0];
strMonth = strDateArray[1];
strYear = strDateArray[2];
}
booFound = true;
   }
}
if (booFound == false) {
if (strDate.length>5) {
strDay = strDate.substr(0, 2);
strMonth = strDate.substr(2, 2);
strYear = strDate.substr(4);
   }
}
if (strYear.length == 2) {
strYear = '20' + strYear;
}
// US style
if (strDatestyle == "US") {
strTemp = strDay;
strDay = strMonth;
strMonth = strTemp;
}
intday = parseInt(strDay, 10);
if (isNaN(intday)) {
err = 2;
return false;
}
intMonth = parseInt(strMonth, 10);
if (isNaN(intMonth)) {
for (i = 0;i<12;i++) {
if (strMonth.toUpperCase() == strMonthArray[i].toUpperCase()) {
intMonth = i+1;
strMonth = strMonthArray[i];
i = 12;
   }
}
if (isNaN(intMonth)) {
err = 3;
return false;
   }
}
intYear = parseInt(strYear, 10);
if (isNaN(intYear)) {
err = 4;
return false;
}
if (intMonth>12 || intMonth<1) {
err = 5;
return false;
}
if ((intMonth == 1 || intMonth == 3 || intMonth == 5 || intMonth == 7 || intMonth == 8 || intMonth == 10 || intMonth == 12) && (intday > 31 || intday < 1)) {
err = 6;
return false;
}
if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && (intday > 30 || intday < 1)) {
err = 7;
return false;
}
if (intMonth == 2) {
if (intday < 1) {
err = 8;
return false;
}
if (LeapYear(intYear) == true) {
if (intday > 29) {
err = 9;
return false;
}
}
else {
if (intday > 28) {
err = 10;
return false;
}
}
}
if (strDatestyle == "US") {
//datefield.value = strMonthArray[intMonth-1] + " " + intday+" " + strYear;
}
else
  {//Regreso de fecha *********************************************************************************
  //datefield.value = intday + " " + strMonthArray[intMonth-1] + " " + strYear;
  //si el mes es el mismo pero el dï¿½a inferior aun no ha cumplido aï¿½os, le quitaremos un aï¿½o al actual
  if ((intMonth==mhoy)&&(intday > dhoy))
  {
  ahoy=ahoy-1;

  }
  //si el mes es superior al actual tampoco habrï¿½ cumplido aï¿½os, por eso le quitamos un aï¿½o al actual
  if (intMonth > mhoy)
  {
  ahoy=ahoy-1;
  }
  if(intMonth==mhoy){
    meses=0
  }
  else if(intMonth>mhoy){
    meses =12-(intMonth - mhoy);
  }
  else if(intMonth<mhoy){
    meses = mhoy-intMonth;
  }
  edad=ahoy-strYear;
}
var edadCom=null;
edadCom=[edad,meses];
return edadCom;
}
