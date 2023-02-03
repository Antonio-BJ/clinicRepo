app.controller('avisosCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  
  $scope.avisos={
    titulo:'',
    textCorto:'',
    temporal:'',
     temporal1:'',
    archivo:'',
    archivo1:''
  }
  $scope.archivo='';
  $scope.archivo1='';
  $scope.enviado=false;
  $scope.exito=false;
  $scope.duplicado=false;
  $scope.errors=false;
  $scope.duplicado1=false;
  $scope.errors1=false;
  $scope.errorTipo=false;
  $scope.errorTipo1=false;
  $scope.interacted = function(field) {          
    return $scope.avisosRH.$submitted && field.$invalid;          
  };
$scope.cargador1=false;       

$scope.onFileSelect_xml = function($files) {
  for (var i = 0; i < $files.length; i++) {
    var file = $files[i];
    $scope.archivo=file;
    $scope.variable = 2;
    var amt = 0;
    //$files: an array of files selected, each file has name, size, and type.
    $scope.upload = $upload.upload({
      url: 'api/api.php?funcion=archivo_temporal', //upload.php script, node.js route, or servlet url
      method: 'POST',      
      file: file, // or list of files ($files) for html5 only
      progress:function(evt) {
        var amt =  parseInt(100.0 * evt.loaded / evt.total);
        $scope.countTo = amt;
        $scope.countFrom = 0;
      }
    })
    .success(function (data, status, headers, config){                        
      $scope.avisos.archivo=data.nombre;
      $scope.avisos.temporal=data.temporal;            
      console.log($scope.avisos.archivo);
    }).error( function (xhr,status,data){
      alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
    });
  }
}

$scope.onFileSelect_xml1 = function($files) {
  for (var i = 0; i < $files.length; i++) {
    var file = $files[i];
    $scope.archivo1=file;
    $scope.variable1 = 2;
    var amt = 0;
    //$files: an array of files selected, each file has name, size, and type.
    $scope.upload = $upload.upload({
      url: 'api/api.php?funcion=archivo_temporal', //upload.php script, node.js route, or servlet url
      method: 'POST',      
      file: file, // or list of files ($files) for html5 only
      progress:function(evt) {
        var amt =  parseInt(100.0 * evt.loaded / evt.total);
        $scope.countTo = amt;
        $scope.countFrom = 0;
      }
    })
    .success(function (data, status, headers, config){                        
      $scope.avisos.archivo1=data.nombre;
      $scope.avisos.temporal1=data.temporal;            
      console.log($scope.avisos.archivo1);
    }).error( function (xhr,status,data){
      alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
    });
  }
}




$scope.guardarAvisoRH = function(){
  if($scope.avisosRH.$valid){ 
        $scope.cargador1=true;
        console.log($scope.avisos);                  
        $scope.upload = $upload.upload({
            url:'api/api.php?funcion=guardaAvisoRH&usr='+$rootScope.usrLogin+'&datos='+$scope.avisos,
            method:'POST',             
            data:$scope.avisos,
            file: $scope.archivo
        }).success( function (data, status, headers, config){ 
          console.log(data);                     
            if(data.respuesta=='exito'){
              $scope.exito=true;
              $scope.duplicado=false;
              $scope.errors=false;
              $scope.errorTipo=false;
              $scope.avisos={
                titulo:'',
                textCorto:'',
                temporal:'',
                 temporal1:'',
                archivo:'',
                archivo1:''
              }
              $scope.avisosRH.$submitted=false;
            }else if(data.respuesta=='doble'){
              $scope.exito=false;
              $scope.duplicado=true;
              $scope.errors=false;
              $scope.errorTipo=false;
            }else if(data.respuesta=='errorTipo'){
              $scope.exito=false;
              $scope.duplicado=false;
              $scope.errors=false;
              $scope.errorTipo=true;
            }else if(data.respuesta=='error'){
              $scope.exito=false;
              $scope.duplicado=false;
              $scope.errors=true;
              $scope.errorTipo=false;
            }

            /*$scope.upload = $upload.upload({
            url:'api/api.php?funcion=guardaAvisoAdjunto&usr='+$rootScope.usrLogin+'&datos='+$scope.avisos,
            method:'POST',             
            data:$scope.avisos,
            file: $scope.archivo1
              }).success( function (data, status, headers, config){ 
                  console.log(data);                     
                  if(data.respuesta=='exito'){
                    $scope.exito=true;
                    $scope.duplicado=false;
                    $scope.errors=false;
                    $scope.errorTipo=false;
                    $scope.avisos={
                      titulo:'',
                      textCorto:'',
                      temporal:'',
                       temporal1:'',
                      archivo:'',
                      archivo1:''
                    }

                    $scope.avisosRH.$submitted=false;
                    }else if(data.respuesta=='doble'){
                      $scope.exito=false;
                      $scope.duplicado1=true;
                      $scope.errors1=false;
                      $scope.errorTipo1=false;
                    }else if(data.respuesta=='errorTipo'){
                      $scope.exito=false;
                      $scope.duplicado1=false;
                      $scope.errors1=false;
                      $scope.errorTipo1=true;
                    }else if(data.respuesta=='error'){
                      $scope.exito=false;
                      $scope.duplicado1=false;
                      $scope.errors1=true;
                      $scope.errorTipo1=false;
                    }                

                    }).error( function (xhr,status,data){                        
                        $scope.cargador=false;          
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
*/      $scope.cargador1=false;  
        }).error( function (xhr,status,data){                        
            $scope.cargador=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
        });
  }  
}



      /* console.log($scope.comentario);                                 
                $scope.cargador=true;               
                $http({
                      url:'api/api.php?funcion=enviaComentarioRH&usr='+$rootScope.usrLogin,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.comentario
                      }).success( function (data){                        
                        if(data.respuesta=='correcto'){
                            $scope.enviado=true;
                            $scope.comentario.comentario='';
                        }else{
                            alert('Error en el envío');
                        }
                         $scope.cargador=false;
                                            
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
      }                                    
        
    }*/

    $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }
 
});