app.controller('recetaComplementariaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$q) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.motivo='';
  $scope.cargador=true;
  $scope.med={
          sustAct:'',
          medicame:'',
          presentacion:'',
          cantidad:1,
          posologia:'',
          stock:''          
        }
  $scope.ortesisSym={
          ortSymio:'',
          cantidad:1,
          indicaciones:'',
          stock:''
        }
  $scope.siguienteMed=true;
  $scope.siguienteOrt=true;  
   $http.get('api/notaMedica.php?funcion=listadoItems&fol='+$rootScope.folio+'&tipo=1&tipoReceta=3').success(function (data){ 
        console.log(data); 
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
    	$scope.cargador= false;
    });   

    $http.get('api/notaMedica.php?funcion=listadoItems&fol='+$rootScope.folio+'&tipo=2&tipoReceta=3').success(function (data){                                                                                                   
    	if(data=='"vacio"'){
        	$scope.listaMedicamentosSymio='';
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
	    $scope.cargador= false;            
    });           

  $('#exampleModal').modal('show');

/*******************************************  RECETA ************************************************/

                            $scope.timeout = 4; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequest = httpRequestHandler();                                                                                                                                                  
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      
                      /******************** prueba de tiempo de ejecusión  *********************************/
                            $scope.timeoutOrt = 4; //tiempo de espera de la consulta                                               
                            $scope.status = 'Requesting';
                            $scope.response = '';                            
                            var httpRequestOrt = httpRequestHandlerOrt();                                                                                                                                                   
                      /********************* fin prueba de tiempo de ejecusión   **************************/
                      $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
                          $scope.alergias = data;
                      });                                       

                      busquedas.listaIndicaciones().success(function(data){                      
                        $scope.listaIndicacion=data;                     
                      });                       
                      busquedas.listaIndicAgregComplemetarias($rootScope.folio).success(function(data){                      
                        if(data==''){
                          $scope.listaIndicAgreg='';  
                        }else{
                          $scope.listaIndicAgreg=data;                     
                        }                        
                      });
/****************************************************************************************************/

/***************       funcion para el tiempo de espera de medicamentos  ***********************/
        function httpRequestHandler () {
                            $scope.cargadorMed=true;
                            $scope.recargarMed=true;
                            var timeout = $q.defer(),
                                result = $q.defer(),
                                timedOut = false,
                                httpRequest;
                            
                           setTimeout(function () {
                                timedOut = true;
                                timeout.resolve();
                            }, (1000 * $scope.timeout));
                            
                            httpRequest = $http({
                                method : 'get',
                                url: 'http://api.medicavial.mx/api/busquedas/existencias/unidad/'+$rootScope.uniClave+'/1',                               
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
                            }, (1000 * $scope.timeoutOrt));
                            
                            httpRequest = $http({
                                method : 'get',
                                url: 'http://api.medicavial.mx/api/busquedas/existencias/unidad/'+$rootScope.uniClave+'/2',                               
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
                                console.log(data);
                                $scope.lisrtOrtSymio=data; 
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
        /******************************* fin de tiempo de espera para ortesis  ******************************/

        

        $scope.botonRecargaMed = function(){
          $scope.recargarMed=true;
          $scope.cargadorMed=true;     
          console.log($scope.segundaRecarga);
          $http({
            url:'api/api1.php?funcion=recargaMedSymio&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.indicacion
            }).success( function (data){
              console.log(data);
              if(data==''){
                if($scope.segundaRecarga==2){
                  $scope.timeout = .5; //tiempo de espera de la consulta                       
                              
                  $scope.status = 'Requesting';
                  $scope.response = '';
                  
                  var httpRequest = httpRequestHandler();
                }
                  $scope.segundaRecarga=2;
                  $scope.recargarMed=false;
                  $scope.cargadorMed=true;                  
              }else{
                console.log(data);
                  $scope.lisMedSymio=data; 
                  $scope.cargadorMed=false;   
              }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                                           
        }

        $scope.botonRecargaOrt = function(){
          $scope.recargarOrt=true;
          $scope.cargadorOrt=true;               
          $http({
            url: 'http://api.medicavial.mx/api/busquedas/existencias/unidad/'+$rootScope.uniClave+'/2',
            method:'GET', 
            contentType: 'application/json', 
            dataType: "json", 
            
            // url:'api/api.php?funcion=listOrtesisSymio&uni='+$rootScope.uniClave,
            // method:'POST', 
            // contentType: 'application/json', 
            // dataType: "json", 
            // data: $scope.indicacion
            }).success( function (data){              
              // if(data==''){
              //   if($scope.segundaRecargaOrt==2){
              //     $scope.timeout = .5; //tiempo de espera de la consulta                       
                              
              //     $scope.status = 'Requesting';
              //     $scope.response = '';
                  
              //     var httpRequest = httpRequestHandlerOrt();
              //   }
              //     $scope.segundaRecargaOrt=2;
              //     $scope.recargarOrt=false;
              //     $scope.cargadorOrt=true;                  
              // }else{
                  $scope.lisrtOrtSymio=data; 
                  $scope.cargadorOrt=false;   
              // }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });                                           
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

      $scope.seleccionaMedicamentos = function(medicamento){ 
        
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
            //Math.trunc($scope.lisMedSymio[lista].Stock/$scope.lisMedSymio[lista].Caja);              
            $scope.med.descripcion      = $scope.lisMedSymio[lista].Descripcion;
            $scope.med.almacen          = $scope.lisMedSymio[lista].almacen;
            $scope.med.idMedicamento    = $scope.lisMedSymio[lista].Clave_producto;
            $scope.med.existencia       = $scope.lisMedSymio[lista].id; 
            console.log($scope.med.sustAct);           
          }            
        }
      } 

       $scope.seleccionaOrtesis = function(ortesis){  

          for(lista in $scope.lisrtOrtSymio){
            if(ortesis==$scope.lisrtOrtSymio[lista].Clave_producto){              
              $scope.ortesisSym.presentacion     = $scope.lisrtOrtSymio[lista].presentacion;
              $scope.ortesisSym.indicaciones     = $scope.lisrtOrtSymio[lista].posologia;
              $scope.ortesisSym.stock            = $scope.lisrtOrtSymio[lista].Stock;
              $scope.ortesisSym.descripcion      = $scope.lisrtOrtSymio[lista].Descripcion;
              $scope.ortesisSym.almacen          = $scope.lisrtOrtSymio[lista].almacen;
              $scope.ortesisSym.idMedicamento    = $scope.lisrtOrtSymio[lista].Clave_producto;
              $scope.ortesisSym.existencia       = $scope.lisrtOrtSymio[lista].id;
              console.log($scope.ortesisSym);
            }            
          }
        }   

$scope.guardarMotivo = function(){
  console.log($scope.motivo);
  $('#exampleModal').modal('hide');

}

$scope.guardaMedicamentoSymio = function(){
   if($scope.formularios.medicSymio.$valid){            
            if($scope.med.cantidad <= $scope.med.stock){             
              $scope.validaStock=false;
              $scope.cargador=true;               
               $http({
                url:' http://api.medicavial.mx/api/operacion/reserva/item',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: {id_item:$scope.med.idMedicamento,id_almacen:$scope.med.almacen,NS_cantidad:$scope.med.cantidad}
                }).success( function (data){                     
                   $scope.med.reserva= data;  
                   $http({
                    url:'api/notaMedica.php?funcion=guardarMedicamentosNota&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin+'&tipoReceta=3',
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.med
                    }).success( function (data){ 
                        console.log(data);
                        $scope.siguienteMed=false; 
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
              /*$http({
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
                });  */                    
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
                    url:'api/notaMedica.php?funcion=eliminarMedicamentosNota&cveItemReceta='+cveItemReceta,
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


        $scope.guardaOrtesisSymio= function(){        
          if($scope.formularios.orteSymio.$valid){
            if($scope.ortesisSym.stock>=$scope.ortesisSym.cantidad){
              console.log($scope.ortesisSym);
              $scope.validaStockOrtesisSym=false;
              $scope.cargador1=true;              
                $http({
                url:' http://api.medicavial.mx/api/operacion/reserva/item',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: {id_item:$scope.ortesisSym.idMedicamento,id_almacen:$scope.ortesisSym.almacen,NS_cantidad:$scope.ortesisSym.cantidad}
                }).success( function (data){                           
                   $scope.ortesisSym.reserva= data;  
                   $http({
                    url:'api/notaMedica.php?funcion=guardarOrtesisNota&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin+'&tipoReceta=3',
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
                        console.log(data);
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


              /*          
              $http({
                url:'api/api.php?funcion=guardaOrtSymio&fol='+$rootScope.folio+'&uni='+$scope.uniClave,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.ortesisSym
                }).success( function (data){                                 
                  if(!data.respuesta){ 
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
                });*/                      
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
                    url:'api/notaMedica.php?funcion=eliminarOrtesisNota&cveItemReceta='+cveItemReceta,
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

        $scope.guardaIndicaciones= function(){
        if($scope.formularios.indica.$valid){ 
          $scope.validaPalabraInd= validaPalabrasProhibidasInd($scope.indicacion.indicacion);          
          if($scope.validaPalabraInd==0){
          $scope.msjPalabraProhiInd=false;     
          $scope.cargador2=true;
          $http({
            url:'api/api.php?funcion=guardaIndicacionComplementaria&fol='+$rootScope.folio+'&tipo=3',
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.indicacion
            }).success( function (data){
              console.log(data);                        
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
            url:'api/api.php?funcion=eliminarIndicacionComplementaria&fol='+$rootScope.folio+'&proClave='+clavePro,
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

        $scope.verIndicacionCam = function(){            
            if($scope.indicacion.obs=='' || $scope.indicacion.obs==null){
              $scope.indicacion.obs=$scope.indicacion.indicacion;
            }else{
              $scope.indicacion.obs=$scope.indicacion.obs+', '+$scope.indicacion.indicacion;
            }
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
        $scope.imprimirReceta = function(){   
        console.log('entro');           
            var fileName = 'Receta - '+$rootScope.folio;
            var uri = 'api/classes/formatoRecetaComplementaria.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave+'&motivo='+$scope.motivo;
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
              $("#exampleModal").modal('hide');
              $location.path("/documentos");          

        }
 
});