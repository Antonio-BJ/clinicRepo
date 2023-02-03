app.controller('rehabilitacionCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http) {
	$rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador=true;
  $scope.cargador1=false;
  $scope.cargador2=false;
  $scope.formularios={};
  $scope.validaEx=false;
  $scope.formu=false;
  $scope.datos={
        nombre:'',
        pat:'',
        mat:'',
        fecnac:'',
        tel:'',
        numeroTel:'',
        mail:'',
        obs:''
  };
  $scope.acc={
       fecSin:'',
       fecAtn:'',
       med:'',
       diag:''
  };
  $scope.exp={

  }
  $scope.formReah={
      noSes:'',
      obs:'',
      diag:''
  }
  $scope.nota=false;
  
  $scope.interacted = function(field) {          
    return $scope.formularios.paseRehab.$submitted && field.$invalid;          
  };
  $scope.interacted1 = function(field) {          
    return $scope.formularios.estudiosSub.$submitted && field.$invalid;          
  };

  busquedas.datosPacienteRe($rootScope.folio).success(function(data){
    $scope.cargador=false;      
      $rootScope.nombre= data.Exp_nombre + ' '+data.Exp_paterno+ ' ' + data.Exp_materno;
      $scope.datos.cia=data.Cia_nombrecorto;
      $scope.datos.sin=data.Exp_siniestro;
      $scope.datos.pol=data.Exp_poliza;
      $scope.datos.rep=data.Exp_reporte;
      $scope.datos.fecnac= data.Exp_fechaNac;            
      $scope.datos.mail=data.Exp_mail;
      $scope.datos.obs=data.Rel_clave;
      $scope.datos.tel=data.Exp_telefono;
      $scope.datos.ocu = data.Ocu_clave;
      $scope.datos.edoC= data.Edo_clave;
      $scope.datos.sexo= data.Exp_sexo;

      edad=chkdate($scope.datos.fecnac,1);
      $scope.datos.anios=edad[0];
      $scope.datos.meses=edad[1]; 
      $scope.datos.folio= $rootScope.folio;      
      
  }); 
  busquedas.validaPaseMed($rootScope.folio).success(function(data){
        console.log(data);             
        if(!data.respuesta){
           $scope.formReah={
                noSes:parseInt(data.RPase_rehabilitacion),
                obs:data.RPase_obs,
                diag:data.RPase_diagnostico
          }
        }                                           
    });

  $scope.verDatosAcc = function(){          
          //console.log($scope.datos1);
            
            
            if($scope.acc.fecSin==''){
              $scope.cargador=true;
              $http({
                      url:'api/api.php?funcion=datosAcc&folio='+$rootScope.folio,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.datos1
                      }).success( function (data){ 
                        console.log(data);                       
                        $scope.acc.fecSin=data.Not_fechaAcc;
                        $scope.acc.fecAtn=data.Not_fechareg;
                        $scope.acc.doc=data.Med_nombre;
                        $scope.acc.diag=data.ObsNot_diagnosticoRx;
                        $scope.cargador=false;
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
              }
        }  

  $scope.verExpdiente = function(){                                            
            //if($scope.acc.fecSin==''){
              if(!$scope.validaEx){
              $scope.cargador=true;
              $http({
                    url:'api/api.php?funcion=datosExp&folio='+$rootScope.folio,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.datos1
                    }).success( function (data){ 
                      console.log(data);                       
                      if(data.nota=='SI'){
                        $scope.nota=true;
                      }
                      $scope.subsec=data.Subs;
                      $scope.cargador=false;
                      $scope.validaEx=true;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
                  }
              //}
        }  

  $scope.guardaPase = function(){                                                      
           if($scope.formularios.paseRehab.$valid){    
              $scope.cargador1=true;                          
              $http({
                    url:'api/api.php?funcion=guardaPaseR&folio='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.formReah
                    }).success( function (data){ 
                      $scope.formu=true;
                      console.log(data);
                      if(data.respuesta=='SI'){
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
                        $scope.cargador1=false;
                        $location.path("/documentos"); 
                      }else{
                        alert('Error en la insersión');
                      }
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
              }
        }  
  

  
  
  $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }
  $scope.imprimirReceta = function(){
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

  $scope.imprimirSubList = function(cont){          
          $scope.cargador=true;          
            var fileName = "Reporte";
            var uri = 'api/classes/formatoSubList.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cont='+cont;
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

  $scope.imprimirNota = function(){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoNota.php?fol='+$rootScope.folio;
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




function chkdate(objName, conedad)
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
  if (conedad==1){  
  }
}
var edadCom=null;
edadCom=[edad,meses];
return edadCom;
}
