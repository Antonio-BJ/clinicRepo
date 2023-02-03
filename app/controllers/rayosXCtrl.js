app.controller('rayosXCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 

  $scope.inicio = function(){
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $scope.cargador=false;  
	//$rootScope.rutaPro=	$cookies.rutaImgPro;	
	$scope.rx={
    archivo:'',
    temporal:'',
    noPlac:1,
    inter:''
  };
    $scope.archivo='';
    $scope.msjerror=false;
    $scope.botonCorreo =false;

    $scope.correo='';
    $scope.correon='';
    $scope.msjMail='';

  $scope.listaDigitales='';
  $scope.noPlacas = [
      {clave:1,valor:1},
      {clave:2,valor:2},
      {clave:3,valor:3},
      {clave:4,valor:4},
      {clave:5,valor:5},
      {clave:6,valor:6},
      {clave:7,valor:7},
      {clave:8,valor:8},
      {clave:9,valor:9},
      {clave:10,valor:10},      
  ];
  

    $http.get('api/digitales.php?funcion=listadoRX&fol='+$rootScope.folio).success(function (data){ 
          console.log(data);                           
          $scope.listaDigitales = data;

          if($scope.listaDigitales.length>0){

            busquedas.validaParticular($rootScope.folio).success(function(data){   
                  $scope.cia =data.Cia_clave;            
                  console.log($scope.cia);
                  if(data.Cia_clave==51||data.Cia_clave==44||data.Cia_clave==54||data.Cia_clave==53||data.Cia_clave==64||data.Cia_clave==71){
          
                    $scope.botonCorreo = true;
          
                  }
          });
            
          }
    });   

     $scope.onFileSelect_xml = function($files) {
      for (var i = 0; i < $files.length; i++) {
        var file = $files[i];
        $scope.archivo=file;
        $scope.variable = 2;
        var amt = 0;
        $scope.upload = $upload.upload({
          url: 'api/api.php?funcion=archivo_temporal', 
          method: 'POST',
          data: $scope.factura,
          file: file,
          progress:function(evt) {
           
            console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
            $scope.progressBar = parseInt(100.0 * evt.loaded / evt.total);
          }
        }).success(function (data, status, headers, config){                        
          $scope.rx.archivo=data.nombre;
          $scope.rx.temporal=data.temporal; 
          console.log($scope.rx.archivo+'--'+$scope.rx.temporal);           
        }).error( function (xhr,status,data){
          alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
        });
      }  
    }
  }

$scope.guardaDigital = function(){
          $scope.cargador=true;          
          $scope.upload = $upload.upload({
            url:'api/digitales.php?funcion=guardaRx&fol='+$rootScope.folio+'&noPlac='+$scope.rx.noPlac+'&usr='+$rootScope.usrLogin+'&inter='+$scope.rx.inter,
            method:'POST',             
            data:$scope.rx,
            file: $scope.archivo
          }).success( function (data, status, headers, config){
            console.log(data);             
            $scope.cargador=false;          
            $scope.msjerror=false;
            if(!data.respuesta){                
            $scope.listaDigitales=data;
            if($scope.listaDigitales.length>0){

              busquedas.validaParticular($rootScope.folio).success(function(data){   
                    $scope.cia =data.Cia_clave;            
                    console.log($scope.cia);
                    if(data.Cia_clave==51||data.Cia_clave==44||data.Cia_clave==54||data.Cia_clave==53||data.Cia_clave==64||data.Cia_clave==71){
            
                      $scope.botonCorreo = true;
            
                    }
            });
          }
            $scope.rx={
              archivo:'',
              temporal:'',
              noPlac:1
            };           
          }else if(data.respuesta=='error'){
            $scope.msjerror=true;
          }
          }).error( function (xhr,status,data){            
            $scope.cargador=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
          });                            
        }



$scope.eliminaDigital = function(cont, tipo){
          //$scope.cargador=true;   
           $scope.cargador=true;    
           console.log(cont+'--'+tipo)   
          $http({
            url:'api/api.php?funcion=eliminaDigital&fol='+$rootScope.folio+'&cont='+cont+'&tipo='+tipo,
            method:'POST',             
            data:$scope.digital,            
          }).success( function (data, status, headers, config){ 
            $scope.cargador=false;       
            if(!data.respuesta){
              if(data==''){
                $scope.listaDigitales='';  
                $scope.botonCorreo = false;  
              }else{
                $scope.listaDigitales=data;  
                if($scope.listaDigitales.length>0){

                  busquedas.validaParticular($rootScope.folio).success(function(data){   
                        $scope.cia =data.Cia_clave;            
                        console.log($scope.cia);
                        if(data.Cia_clave==51||data.Cia_clave==44||data.Cia_clave==54||data.Cia_clave==53||data.Cia_clave==64||data.Cia_clave==71){
                
                          $scope.botonCorreo = true;
                
                        }
                });
                  
                }
              }
              
            }else{
              console.log('error en la eliminación del archivo');
            }
            /*$scope.cargador=false;          
            $scope.msjerror=false;              
            $scope.listaDigitales=data;
            $scope.digital={
                archivo:'',
                temporal:'',
                tipo:''
            };*/                     
          }).error( function (xhr,status,data){            
            $scope.cargador=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
          });                               
        
}
$scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }

$scope.confirmarEnvio = function(){         
    $http.get('api/digitales.php?funcion=datosPaciente&fol='+$rootScope.folio).success(function (data){ 
      console.log(data);
      $('#myModal').modal();
      $scope.datos = data;
      $scope.correo = $scope.datos.Exp_mail;
    });
}

$scope.enviarCorreo = function(){   
  $http.get('api/digitales.php?funcion=enviarRxCorreo&fol='+$rootScope.folio+'&correo='+$scope.correo+'&correon='+$scope.correon).success(function (data){ 

    
      $scope.msjMail='El correo se envió correctamente,';
    
  });
}
});