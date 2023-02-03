app.controller('recetaMovilCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$q) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  //$rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.motivo='';
  $scope.cargador=true;
  $scope.claseSpinner="panel-body";
  $scope.spinner = '';
  $scope.spinnerModal = '';
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





  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
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


  busquedas.listaIndicaciones().success(function(data){                      
    $scope.listaIndicacion=data;                     
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
      

        $scope.imprimirReceta = function(){  

          // $scope.medico.nombre = localStorage.getItem("medicoSuplente");
          // $scope.medico.cedula = localStorage.getItem("cedulaMedicoSuplente");

           
              $scope.url= 'api/classes/formatoRecetaNuevo1.php?fol='+$rootScope.folio+'&vit='+$rootScope.vitSelect+'&uni='+$scope.unidad+'&usr='+$rootScope.usrLogin;        	     
            var fileName = 'Receta - '+$rootScope.folio;
            var uri = $scope.url;
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
 
});