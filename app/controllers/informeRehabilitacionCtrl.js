app.controller('informeRehabilitacionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload, busquedas) {
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;   //console.log($rootScope.usrLogin);
  $rootScope.folio= $cookies.folio;         //console.log($rootScope.folio);

  $scope.trabajando=false;
  $scope.bloqueoBoton=true;
  $scope.finalizarInforme=false;

  $scope.infRehab={
      //diagnosticoInicial:"",
      //valInicial:"",      
      valFinal:"",
      tratamientoRehab:"",
      observaciones:"",
      folio: $rootScope.folio,
      usu_login: $cookies.usrLogin,
      uni_clave: $cookies.uniClave,
      etapaInforme:0,
      medico:'',
      emailMedico:'',
      //tratamientoPrevio:'',
      areasTrabajadas:'',
      sesionesRequiere:0,
      sesionesTomadas:0,
      sesionesAdicionales:0,
  };

  busquedas.rehabNum($rootScope.folio).success(function(data){    
        if(!data.respuesta){
            $scope.formu=false;
           $scope.infRehab.sesionesTomadas=parseInt(data.rehab)-1;
           if ($scope.infRehab.sesionesTomadas) {
                /* BUSCA INFORME DE REHABILITACION INCONCLUSO */
                    $http({
                            url:'api/infRehabilitacion.php?funcion=buscaInfRehab&fol='+$rootScope.folio+'&med='+$rootScope.usrLogin,
                            method:'POST', 
                            contentType: 'application/json', 
                            dataType: "json", 
                            data: $scope.infRehab
                            }).success( function (data){
                                $scope.inconcluso=data;
                                console.log($scope.inconcluso);
                                    $scope.infRehab.sesionesRequiere = parseInt($scope.inconcluso.sesionesRequeridas);
                                    $scope.infRehab.sesionesAdicionales=$scope.infRehab.sesionesRequiere-$scope.infRehab.sesionesTomadas;

                            }).error( function (xhr,status,data){
                                $scope.mensaje ='no entra';            
                                alert('Error');
                            }); 
           };
           //console.log($scope.infRehab.sesionesTomadas);
        }                                           
    });


//RECUPERA DATOS DE LA HIST CLINICA DE REHABILITACION
  $http.get('api/api.php?funcion=getdiagInicial&folio='+$rootScope.folio).success(function (data){
        //console.log(data.Con_motivo);
      if ($scope.infRehab.diagnosticoInicial=='') {
            $scope.infRehab.diagnosticoInicial=data.Con_motivo;
      };
  });

//RECUPERA DATOS DE LA HIST CLINICA DE REHABILITACION
  $http.get('api/api.php?funcion=getValoracionInicial&folio='+$rootScope.folio).success(function (data){
      //console.log(data);
/*      if ($scope.infRehab.diagnosticoInicial=='') {
        $scope.infRehab.diagnosticoInicial=data.diag_inicial;  
      };*/
      if ($scope.infRehab.valInicial=='') {
        $scope.infRehab.valInicial=data.val_inicial;
      };
      if ($scope.infRehab.tratamientoPrevio=='') {
        $scope.infRehab.tratamientoPrevio=data.tratamientos_previos;
      };
      //$scope.infRehab.diagnosticoInicial=data.diag_inicial;
      //$scope.infRehab.valInicial=data.val_inicial;
      //$scope.infRehab.tratamientoPrevio=data.tratamientos_previos;
  });

/* BUSCA DATOS DEL PACIENTE */
    $http({
            url:'api/infRehabilitacion.php?funcion=buscaPaciente&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.infRehab
            }).success( function (data){                              
            //console.log(data);
            $scope.nombre=data.Exp_completo;
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 

/* BUSCA INFORME DE REHABILITACION INCONCLUSO */
    $http({
            url:'api/infRehabilitacion.php?funcion=buscaInfRehab&fol='+$rootScope.folio+'&med='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.infRehab
            }).success( function (data){
                $http.get('api/infRehabilitacion.php?funcion=medicoOrden&fol='+$rootScope.folio).success(function (data){
                    //console.log(data);
                    if(data!=""){
                        $scope.infRehab.medico=data[0].Medico;
                        $scope.infRehab.emailMedico=data[0].USU_email;
                        $scope.infRehab.sesionesRequiere=parseInt(data[0].PAS_catidadRehab);
                    }
                    
                });
                $scope.inconcluso=data;
                //console.log($scope.inconcluso);
                if($scope.infRehab.emailMedico==""){
                    $scope.infRehab.emailMedico = $scope.inconcluso.mailMedico;
                }

                if($scope.infRehab.sesionesRequiere==""){
                    $scope.infRehab.sesionesRequiere = parseInt($scope.inconcluso.sesionesRequeridas);
                }
                    
                    $scope.infRehab.diagnosticoInicial=$scope.inconcluso.infRehab_DiagnosticoInicial;
                    $scope.infRehab.valInicial=$scope.inconcluso.infRehab_valoracionInicial;
                    $scope.infRehab.tratamientoPrevio=$scope.inconcluso.tratamientoPrevio;

            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 


/* GUARDA EL INFORME DE REHABILITACION */
  $scope.guardaInformeRehab = function() {
    $scope.trabajando=true;
    if ($scope.inconcluso=='false') {
        if ($scope.infRehab.valFinal=='') {
            $scope.bloqueoBoton=true;
            $scope.infRehab.etapaInforme=0;
        } else{
            $scope.infRehab.etapaInforme=1;
            $scope.bloqueoBoton=false;
        };
        //console.log($scope.infRehab);
        $http({
                url:'api/infRehabilitacion.php?funcion=guardaInfRehab',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.infRehab
                }).success( function (data){                              
                console.log(data);
                    if (data=='"exito"') {
                        $('#confirmacion').modal('show');
                        $scope.trabajando=false;
                    };  

                $http({
                        url:'api/infRehabilitacion.php?funcion=buscaInfRehab&fol='+$rootScope.folio+'&med='+$rootScope.usrLogin,
                        method:'POST', 
                        contentType: 'application/json', 
                        dataType: "json", 
                        data: $scope.infRehab
                        }).success( function (data){
                            $scope.inconcluso=data;
                            //console.log($scope.inconcluso);
                            $scope.trabajando=false;

                        }).error( function (xhr,status,data){
                            $scope.mensaje ='no entra';            
                            alert('Error');
                            $scope.trabajando=false;
                        }); 

                //console.log($scope.infRehab);
                }).error( function (xhr,status,data){
                    $scope.mensaje ='no entra';            
                    alert('Error');
                    $scope.trabajando=false;
                });
    };


/* BUSCA INFORME DE REHABILITACION INCONCLUSO */
    $http({
            url:'api/infRehabilitacion.php?funcion=buscaInfRehab&fol='+$rootScope.folio+'&med='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.infRehab
            }).success( function (data){
                $scope.inconcluso=data;
                console.log($scope.inconcluso);
                $scope.trabajando=false;

            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
                $scope.trabajando=false;
            }); 

    //para cuando se tiene que terminar el registro
    if ($scope.inconcluso!='false') {
            $scope.infRehab.idInforme=$scope.inconcluso.infRehab_id;
            if ($scope.infRehab.diagnosticoInicial=='') {
                //RECUPERA DATOS DE LA HIST CLINICA DE REHABILITACION
                  $http.get('api/api.php?funcion=getdiagInicial&folio='+$rootScope.folio).success(function (data){
                        //console.log(data.Con_motivo);
                            $scope.infRehab.diagnosticoInicial=data.Con_motivo;
                  });
                //$scope.infRehab.diagnosticoInicial = $scope.inconcluso.infRehab_DiagnosticoInicial;
            };
            if ($scope.infRehab.valInicial=='') {
                $scope.infRehab.valInicial = $scope.inconcluso.infRehab_valoracionInicial;
            };
            if ($scope.infRehab.valFinal=='') {
                $scope.infRehab.valFinal = $scope.inconcluso.infRehab_valoracionFinal;
            };
            if ($scope.infRehab.tratamientoRehab=='') {
                $scope.infRehab.tratamientoRehab = $scope.inconcluso.infRehab_tratamientoRehab;
            };
            if ($scope.infRehab.observaciones=='') {
                $scope.infRehab.observaciones = $scope.inconcluso.infRehab_obs;
            };
            if ($scope.infRehab.medico=='') {
                $scope.infRehab.medico = $scope.inconcluso.medico;
            };
            if ($scope.infRehab.emailMedico=='') {
                $scope.infRehab.emailMedico = $scope.inconcluso.mailMedico;
            };
            if ($scope.infRehab.tratamientoPrevio=='') {
                $scope.infRehab.tratamientoPrevio = $scope.inconcluso.tratamientoPrevio;
            };
            if ($scope.infRehab.areasTrabajadas=='') {
                $scope.infRehab.areasTrabajadas = $scope.inconcluso.areasTrabajadas;
            };
/*            if ($scope.infRehab.sesionesRequiere=='') {
                $scope.infRehab.sesionesRequiere = parseInt($scope.inconcluso.sesionesRequeridas);
            };*/
/*            if ($scope.infRehab.sesionesTomadas=='') {
                $scope.infRehab.sesionesTomadas = $scope.inconcluso.sesionesTomadas;
            };*/
/*            if ($scope.infRehab.sesionesAdicionales=='') {
                $scope.infRehab.sesionesAdicionales = $scope.inconcluso.sesionesAdicionales;
            };*/
        $scope.infRehab.sesionesAdicionales=$scope.infRehab.sesionesRequiere-$scope.infRehab.sesionesTomadas;

        if ($scope.infRehab.valFinal=='') {
            $scope.infRehab.etapaInforme=0;
            $scope.bloqueoBoton=true;
        } else{
            $scope.infRehab.etapaInforme=1;
            $scope.bloqueoBoton=false;
        };
        console.log($scope.infRehab);
        $http({
                url:'api/infRehabilitacion.php?funcion=terminaInforme',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.infRehab
                }).success( function (data){                              
                console.log(data);
                    if (data=='"exito"') {
                        $('#confirmacion').modal('show');
                        $scope.trabajando=false;
                    };  

                $http({
                        url:'api/infRehabilitacion.php?funcion=buscaInfRehab&fol='+$rootScope.folio+'&med='+$rootScope.usrLogin,
                        method:'POST', 
                        contentType: 'application/json', 
                        dataType: "json", 
                        data: $scope.infRehab
                        }).success( function (data){
                            $scope.inconcluso=data;
                            //console.log($scope.inconcluso);
                            $scope.trabajando=false;                          

                        }).error( function (xhr,status,data){
                            $scope.mensaje ='no entra';            
                            alert('Error');
                            $scope.trabajando=false;
                        }); 

                //console.log($scope.infRehab);
                }).error( function (xhr,status,data){
                    $scope.mensaje ='no entra';            
                    alert('Error');
                    $scope.trabajando=false;
                });
    };
  }
//$('#confirmacion').modal('show');
  $scope.imprimeNota = function() {
        $scope.trabajando=true;
            var fileName = "Reporte";
            var uri = 'api/classes/formatoNotaDeRehabilitacion.php?fol='+$rootScope.folio+'&usr='+$scope.infRehab.medico+'&mail='+$scope.infRehab.emailMedico;
            //console.log(uri);
            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".pdf";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();                        
            document.body.removeChild(link);
        $scope.trabajando=false;
  }

  $scope.irDocumentos = function() {
    $('#confirmacion').modal('hide');
    $location.path("/documentos");
  }
});