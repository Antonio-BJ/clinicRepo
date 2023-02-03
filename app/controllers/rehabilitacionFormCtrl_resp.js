app.controller('rehabilitacionFormCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http, uploadSoloRh) {
  $rootScope.folio=$cookies.folio;  
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador=true;
  $scope.cargador1=false;
  $scope.cargador2=false;
  $scope.formularios={};
  $scope.validaEx=false;
  $scope.validaPase=false;
  $scope.formu=true;

  
  $scope.terminaHCR=false;
  $scope.indicador='';
  $scope.sesAdicionales='N';
  $scope.numAdicionales=1;

// if ($scope.noR==1) {
//   $('#modalRh').modal('show');
// };

if ($scope.noR<=1) {
  $scope.btnAceptar=true;
}
else{
  $scope.btnAceptar=false;
};


//$scope.doctoRh='';
//$scope.noR=1;
/*  $http.get('api/api.php?funcion=getPatologicosRh').success(function (data){
      $scope.catalogoPatologicosRh=data;
  });*/

/*  $http.get('api/api.php?funcion=getIntQuirurgicas&folio='+$rootScope.folio).success(function (data){
      $scope.IntQuirurgicas=data;
      console.log($scope.IntQuirurgicas);
  });
*/
  $http.get('api/api.php?funcion=getProducto&folio='+$rootScope.folio).success(function (data){
      console.log(data);

      if (data.Pro_clave=='4' && $rootScope.uniClave=='8') {
          $scope.producto='SoloRehabilitacion';
          $http.get('api/api.php?funcion=getDoctoHr&folio='+$rootScope.folio).success(function (data){
            $scope.respuestaDocto=data;
            console.log('Tiene Documento: '+$scope.respuestaDocto);
            if ($scope.respuestaDocto=='"S"') {
              $('#modalRh').modal('hide');
            }
            else {
              $('#modalRh').modal('show');
            };

          });
      } else {
        $('#modalRh').modal('hide');
        //true para que oculte el formulario HCR e ir directo a la rehabilitacion para pacientes que no son Solo Rehabilitacion
        /* AL COMENTALO SE HABILITARA EL FORMULARIO PARA TODOS LOS PRODUCTOS*/ 
        //$scope.terminaHCR=true;
      };
  });

  $http.get('api/api.php?funcion=getTotalSes&folio='+$rootScope.folio).success(function (data){
      $scope.datosSoloReh=data;
      console.log($scope.datosSoloReh);
      $rootScope.rehabTotales=parseInt(data['Total_sesiones']);
  });

/*  $http.get('api/api.php?funcion=traeHistoriaClinica&folio='+$rootScope.folio).success(function (data){
      $scope.histClinica=data;
      //console.log($scope.histClinica);
  });*/

/*  $http.get('api/api.php?funcion=getHCR&folio='+$rootScope.folio).success(function (data){
      $scope.listaHCR=data;
  });*/

/*  $http.get('api/api.php?funcion=getEnfermedad').success(function (data){                                                              
      $scope.listaEnfermedades=data;
      //console.log($scope.listaEnfermedades);
  });*/

  // $http.get('api/api.php?funcion=getDoctoHr&folio='+$rootScope.folio).success(function (data){
  //   $scope.respuestaDocto=data;
  //   console.log('Tiene Documento: '+$scope.respuestaDocto);
  //   if ($scope.respuestaDocto!='S') {
  //     $('#modalRh').modal('show');
  //   }
  //   else {
  //     $('#modalRh').modal('hide');
  //   };
  // });

/*  $scope.datosHCR={};
  $scope.editaHCR={};
  $scope.nuevoHCR={
    folio: $rootScope.folio,
    padecimiento: '',
    claveEnf:0,
    observaciones:'',
  };*/

  $http.get('api/api.php?funcion=getDiagnosticoInicial&folio='+$rootScope.folio).success(function (data){
      //console.log(data);
      $scope.histClinicaReh.diagInicial=data.Not_obs;
  });


$scope.histClinicaReh={
  folio: $rootScope.folio,
  diabetes: 0,
  hipertension: 0,
  marcapasos: 0,
  radioterapia:0,
  epilepsia:0,
  pielSensible:0,
  lupus:0,
  intervenciones:0,
  aniosDiabetes:null,
  aniosHipertension:null,
  aniosMarcapasos:null,
  aniosRadioterapia:null,
  aniosEpilepsia:null,
  aniosPielSensible:null,
  aniosLupus:null,
  aniosIntervenciones:null,
  diagInicial: '',
  valInicial:'',
  tratamientosPrevios:'',
  escalaDolor:null,
  flexion:null,
  extension:null,
  lateralizacion:null,
  rotInterna:null,
  rotExterna:null,
  examenMuscular:'',
  marchaPostura:0,
  obsMarchaPostura:'',
  banho:0,
  comida:0,
  trabajo:0,
  vestirse:0,
  obsAVD:'',
  estadoActual:'',
};

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
  $scope.datosPase={
      medic:'',
      fech:'',
      noSes:'',
      obs:'',
      diag:''
  }
  $scope.rehabilitacionForm={
      tipo:'',
      escala:'',
      mejoria:'',
      criterios:'',
      observa:'',
      duracion:'',
      acudio:''
  }
  $scope.nota=false;
  $scope.noR='';
  
  $scope.interacted = function(field) {          
    return $scope.formularios.rehabForm.$submitted && field.$invalid;          
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

      
      $scope.datos.folio= $rootScope.folio;      
      
  }); 
  busquedas.rehabNum($rootScope.folio).success(function(data){    
        if(!data.respuesta){
            $scope.formu=false;
           $scope.noR=data.rehab;
           $rootScope.sesionActual=parseInt(data.rehab);
        if ($rootScope.rehabTotales<$rootScope.sesionActual && $rootScope.uniClave==8) {
                          $('#modalRehabFinal').modal({
                            backdrop: 'static', 
                            keyboard: false});
      };
        }                                           
    });

  busquedas.validaPaseMed($rootScope.folio).success(function(data){      
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
   $scope.VerDatosPase = function(){                                            
             if(!$scope.validaPase){
              $scope.cargador=true;
              $http({
                    url:'api/api.php?funcion=verDatosPase&fol='+$rootScope.folio,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.datos1
                    }).success( function (data){ 
                                           
                      if(!data.respuesta){
                        $scope.datosPase={
                            medic:data.Med_nombre+' '+data.Med_materno+' '+data.Med_materno,
                            fech:data.RPase_fecha,
                            noSes:data.RPase_rehabilitacion,
                            obs:data.RPase_obs,
                            diag:data.RPase_diagnostico
                        }                      
                      }else{
                        alert('Error en la consulta');
                      }
                      $scope.subsec=data.Subs;
                      $scope.cargador=false;
                      $scope.validaEx=true;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
                  }
    }
   

  $scope.guardaRehabilitacion = function(){
           if($scope.formularios.rehabForm.$valid){    
              $scope.cargador1=true; 
              
              $http({
                    url:'api/api.php?funcion=guardaRehabilitacion&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.rehabilitacionForm
                    }).success( function (data){
                      console.log(data);
                      $scope.formu=true;                                            
                      if(data.respuesta=='SI'){
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
                        $scope.cargador1=false;

                        // if ($rootScope.sesionActual<$rootScope.rehabTotales) {
                        //   console.log('faltan rehabilitaciones');
                        //   console.log('sesion: '+$rootScope.sesionActual);
                        //   console.log('totales: '+$rootScope.rehabTotales);
                        //   $('#modalRehabFinal').modal('hide');
                        // };

                        if ($scope.producto=='SoloRehabilitacion' && $rootScope.sesionActual==$rootScope.rehabTotales && $rootScope.uniClave==8) {
                          console.log($scope.producto);
                          console.log('es la ultima');
                          //$('#modalRehabFinal').modal('show');
                          $('#modalRehabFinal').modal({
                            backdrop: 'static', 
                            keyboard: false});
                        }
                        else {
                          $location.path("/informeRehabilitacion");
                        };

                        //$location.path("/documentos"); 
                      }else{
                        alert('Error en la insersión');
                      };

                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
              }
        }  

/*$scope.antPatologicos={
    folio: $rootScope.folio,
    padClave: 0,
    padAnios:0,
    padObs:'',
};

   $scope.guardaAntPat = function(){
          $scope.cargador=true;

          console.log($scope.antPatologicos);

              $http({
                    url:'api/api.php?funcion=guardaAntPat',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.antPatologicos
                    }).success( function (data){ 
                      console.log(data);

                        $http.get('api/api.php?funcion=getAntPat&folio='+$rootScope.folio).success(function (data){
                            $scope.listadoAntPat=data;
                            console.log($scope.listadoAntPat);
                        });

                        swal({ 
                                  title: "Listo",   
                                  text: "Se agregó correctamente",   
                                  type: "success",
                                  showCancelButton: false,                                 
                                  confirmButtonColor: "#5cb85c", 
                                  ConfirmButtonText: "Cerrar",                                   
                                  closeOnConfirm: true,
                                  closeOnCancel: true})

                        $scope.cargador=false;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
              $scope.antPatologicos={
                  folio: $rootScope.folio,
                  padClave: 0,
                  padAnios:0,
                  padObs:'',
              };
    }*/

/*   $scope.guardaDatoHCR = function(indice, padecimiento, observaciones, claveEnf){
          $scope.cargador=true;
          //console.log(indice+' '+padecimiento+' '+observaciones+' '+claveEnf);

          $scope.datosHCR.folio=$rootScope.folio;
          $scope.datosHCR.padecimiento=padecimiento;
          $scope.datosHCR.observaciones=observaciones;
          $scope.datosHCR.claveEnf=claveEnf;

          console.log($scope.datosHCR);

          //$scope.histClinica[indice]=undefined;
              $http({
                    url:'api/api.php?funcion=guardaHCR',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.datosHCR
                    }).success( function (data){ 

                        $http.get('api/api.php?funcion=getHCR&folio='+$rootScope.folio).success(function (data){
                            $scope.listaHCR=data;
                            //console.log($scope.listaEnfermedades);
                        });

                        console.log('salió bien');
                        swal({ 
                                  title: "Listo",   
                                  text: "Se agregó correctamente",   
                                  type: "success",
                                  showCancelButton: false,                                 
                                  confirmButtonColor: "#5cb85c", 
                                  ConfirmButtonText: "Cerrar",                                   
                                  closeOnConfirm: true,
                                  closeOnCancel: true})

                        $scope.cargador=false;
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
    }*/
  
/*  $scope.editarDatoHCR = function(indice, padecimiento, observaciones, claveEnf){
        $scope.editaHCR.indice=indice;
        $scope.editaHCR.padecimiento=padecimiento;
        $scope.editaHCR.observaciones=observaciones;
        $scope.editaHCR.claveEnf=claveEnf;
        $('#modalEditaDatos').modal('show');
  }*/
  
/*  $scope.modificar = function(){
      var indice = $scope.editaHCR.indice;
      var idEnfermedad=parseInt($scope.editaHCR.claveEnf);
        $scope.histClinica[indice].Pad_clave=$scope.editaHCR.claveEnf;
        $scope.histClinica[indice].Enf_nombre=$scope.listaEnfermedades[idEnfermedad-1].Enf_nombre;
        $scope.histClinica[indice].Pad_obs=$scope.editaHCR.observaciones;

        console.log($scope.histClinica[indice]);
        $('#modalEditaDatos').modal('hide');
  }*/

/*  $scope.agregaEnf = function(){
              $http({
                    url:'api/api.php?funcion=guardaHCR',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.nuevoHCR
                    }).success( function (data){ 

                        $http.get('api/api.php?funcion=getHCR&folio='+$rootScope.folio).success(function (data){
                            $scope.listaHCR=data;
                            //console.log($scope.listaEnfermedades);
                        });

                          $scope.nuevoHCR.claveEnf=0;
                          $scope.nuevoHCR.observaciones='';

                        console.log('salió bien');
                        swal({ 
                                  title: "Listo",   
                                  text: "Se agregó correctamente",   
                                  type: "success",
                                  showCancelButton: false,                                 
                                  confirmButtonColor: "#5cb85c", 
                                  ConfirmButtonText: "Cerrar",                                   
                                  closeOnConfirm: true,
                                  closeOnCancel: true});
                        
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
  }*/

  $scope.continuarRh = function(){
        //console.log($scope.histClinicaReh);

    if ($scope.histClinicaReh.diagInicial=='' || $scope.histClinicaReh.valInicial=='' || $scope.histClinicaReh.tratamientosPrevios=='' ||
        $scope.histClinicaReh.escalaDolor==null || $scope.histClinicaReh.flexion==null || $scope.histClinicaReh.extension==null || 
        $scope.histClinicaReh.lateralizacion==null || $scope.histClinicaReh.rotInterna==null || $scope.histClinicaReh.rotExterna==null ||
        $scope.histClinicaReh.examenMuscular=='' || $scope.histClinicaReh.estadoActual=='')
    { // abre sentencia if
        $scope.mensaje='Es necesario completar los datos';
        swal({ 
          title: "Faltan datos",   
          text: "Es necesario completar todos los datos",   
          type: "warning",
          showCancelButton: false,                                 
          confirmButtonColor: "orange", 
          ConfirmButtonText: "Cerrar",                                   
          closeOnConfirm: true,
          closeOnCancel: true});
    } else{
        $http({
              url:'api/api.php?funcion=guardaHistClinicaRh',
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: $scope.histClinicaReh
              }).success( function (data){ 
                  console.log(data);
                  $scope.printHCR();
                  $scope.terminaHCR=true;
              }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
              });
    };
  } //continuarRh

/*$('#modalRehabFinal').modal({
      backdrop: 'static', 
      keyboard: false
});*/

  $scope.confirmaReh = function(){
    if ($scope.sesAdicionales=='S') {
      $scope.numAdicionales=$scope.numAdicionales+$rootScope.rehabTotales;
      console.log($scope.numAdicionales);

        $http({
              url:'api/api.php?funcion=actualizaRehab&folio='+$rootScope.folio+'&sesiones='+$scope.numAdicionales,
              method:'POST', 
              //contentType: 'application/json', 
              //dataType: "json", 
              //data: $scope.indicador
              }).success( function (data){ 
                  console.log(data);
              }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
              });
    } else{
      console.log('terminó sesiones');
    };
      $('#modalRehabFinal').modal('hide');
      $scope.irDocumentos();
      //$location.path("/informeRehabilitacion");
  }

  $scope.irDocumentos = function(){         
        $location.path("/documentos");
  }

  $scope.subeArchivo = function(){
      console.log($scope.doctoRh);
        var name = $scope.name;
        var file = $scope.file;
            //llama a servicio upload para subir los archivos
            uploadSoloRh.uploadFile(file,name,$rootScope.folio,$scope.doctoRh).then(function(res) {
                var datosArchivo = res.data;
                console.log(datosArchivo);
                if (datosArchivo.respuesta=='success') {
                    $scope.btnAceptar=true;
                    $scope.indicador='S';
                    $scope.nombreDocto=datosArchivo.nombreDocto;

                      $http({
                            url:'api/api.php?funcion=doctoRh&folio='+$rootScope.folio+'&indicador='+$scope.indicador+
                                '&nombreDocto='+$scope.nombreDocto+'&tipoDocto='+$scope.doctoRh+
                                '&usuLogin='+$rootScope.usrLogin,
                            method:'POST', 
                            //contentType: 'application/json', 
                            //dataType: "json", 
                            //data: $scope.indicador
                            }).success( function (data){ 
                              console.log(data);
                            }).error( function (xhr,status,data){
                                $scope.mensaje ='no entra';            
                                alert('Error');
                            });
                } else{
                  alert('Hubo un error al subir el documento');
                };
            })
  }

  $scope.cerrarModal = function(){         
        $('#modalRh').modal('hide');
  }


/* DESCARGA RESPONSIVA */
  $scope.imprimirResponsiva = function(){
    var fileName = "ResponsivaError";
    var uri = 'api/classes/formatoSolTerapia.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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

  $scope.printHCR = function(){
    if ($scope.histClinicaReh.diagInicial=='' || $scope.histClinicaReh.valInicial=='' || $scope.histClinicaReh.tratamientosPrevios=='' ||
        $scope.histClinicaReh.escalaDolor==null || $scope.histClinicaReh.flexion==null || $scope.histClinicaReh.extension==null || 
        $scope.histClinicaReh.lateralizacion==null || $scope.histClinicaReh.rotInterna==null || $scope.histClinicaReh.rotExterna==null ||
        $scope.histClinicaReh.examenMuscular=='' || $scope.histClinicaReh.estadoActual=='')
    { // abre sentencia if
      $scope.mensaje='Es necesario completar los datos';
      swal({ 
        title: "Faltan datos",   
        text: "Es necesario completar todos los datos",   
        type: "warning",
        showCancelButton: false,                                 
        confirmButtonColor: "orange", 
        ConfirmButtonText: "Cerrar",                                   
        closeOnConfirm: true,
        closeOnCancel: true});

    } else{
        $scope.cargador=true;          
          var fileName = "Reporte";
          var uri = 'api/classes/formatoHistoriaRehab.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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
      };
  }

});




app.directive('uploaderModel', ["$parse", function ($parse) {
    return {
      restrict: 'A',
      link: function (scope, iElement, iAttrs) 
      {
        iElement.on("change", function(e)
        {
          $parse(iAttrs.uploaderModel).assign(scope, iElement[0].files[0]);
        });
      }
    };
}])

app.service('uploadSoloRh', ["$http", "$q", function ($http, $q) 
{
  this.uploadFile = function(file, name, folio, documento)
  {
    var deferred = $q.defer();
    var formData = new FormData();
    formData.append("name", name);
    formData.append("file", file);
    return $http.post("api/classes/subirArchivos.php?folio="+folio+"&documento="+documento, formData, {
      headers: {
        "Content-type": undefined
      },
      transformRequest: angular.identity
    })
    .success(function(res)
    {
      deferred.resolve(res);
    })
    .error(function(msg, code)
    {
      deferred.reject(msg);
    })
    return deferred.promise;
  } 
}])