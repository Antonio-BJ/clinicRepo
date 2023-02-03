app.controller('cambioUnidadGeneralCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 	
  $rootScope.usrLogin= $cookies.usrLogin;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.formCambio=false;
  $scope.cargador=false;
  $scope.cargador1=false;
  $scope.cambioUnidad=false;
  $scope.formularios={};  	
	$scope.cambio={
        uniOrigen:'',
        uniActual:'',
        quienSol:'',
        quienAuto:'',
        unidad:'',
        motivo:'',
        diagnostico:'',
        observaciones:''
    };
    $scope.archivo='';
    $scope.msjerror=false;

    $scope.folio='';
    $scope.verDetalle=false;
    $scope.mensaje='';
    $scope.tipoFol=0;
    $scope.folioAct='';
    
    busquedas.quienAutoriza().success(function(data){                 
        $scope.quienAut=data;
    });
    busquedas.unidadDestino().success(function(data){                        
        $scope.uniDestino=data;
    });
    busquedas.motivo().success(function(data){                                
        $scope.motivo=data;
    });
     
    $scope.interacted = function(field) {
          //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
          return $scope.formularios.formCambioU.$submitted && field.$invalid;          
    };


    $scope.consultaFolio = function(){ 
       $scope.verDetalle=false; 
       $scope.cargador1=true;
      if( $scope.tipoFol==0){       
        busquedas.unidadDetalle($scope.folio).success(function(data){ 
            console.log(data);  
            if(data.origen==null){
                $scope.mensaje='El folio no existe en el portal MV';
                $scope.verDetalle=false;
            }else{
                $scope.folioAct=$scope.folio;
                $scope.mensaje=false;
                $scope.verDetalle=true;    
                $scope.origen=data.origen;
                $scope.actual=data.actual;
                $scope.cambio.uniOrigen=data.cveOrigen;
                $scope.cambio.uniActual=data.cveActual;
            } 
            $scope.cargador1=false;              
        }); 
       
      }else{
        busquedas.folioZima($scope.folio,$rootScope.usrLogin).success(function(data){
            console.log(data);
            if(data.respuesta=='existe'){
              $scope.mensaje='El folio ya existe en Medicavial, folio:'+data.folioMv;
            }
            if(data.respuesta=='sinFolioZima'){
              $scope.mensaje='No existe el folio '+$scope.folio+' en el portal ZIMA';
            }
            if(data.respuesta=='sinUnidadAsociada'){
              $scope.mensaje='No están asociadas las unidades para registrar el folio en el portal MV, consulte al área de sistemas';
            }
            if(data.folio||data.folio!=null){
                $scope.folioAct = data.folio;
                $scope.mensaje=false;
                $scope.verDetalle=true;    
                $scope.origen=data.origen;
                $scope.actual=data.actual;
                $scope.cambio.uniOrigen=data.cveOrigen;
                $scope.cambio.uniActual=data.cveActual;
            }
            $scope.cargador1=false;
        });
        
      }
    }
 
    $scope.enviaDatos = function(){            
            if($scope.formularios.formCambioU.$valid){              
              $scope.cargador=true;
              $http({
                    url:'api/api.php?funcion=guardaDatosCambio&fol='+$scope.folioAct+"&usr="+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.cambio
                    }).success( function (data){   
                       console.log(data);  
                       if(data.respuesta=='exito'){
                         $scope.formCambio=true; 
                         $scope.cargador=false;
                         $scope.cambioUnidad=true;

                       }else{

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
 
});