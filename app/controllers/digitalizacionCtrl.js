app.controller('digitalizacionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {

	//$rootScope.rutaAse=	$cookies.rutaImgCom; 

	$rootScope.folio= 	$cookies.folio;

  $rootScope.usrLogin= $cookies.usrLogin;

  $rootScope.clave= $cookies.clave;

  $scope.cargador=false;  

	//$rootScope.rutaPro=	$cookies.rutaImgPro;	

	$scope.digital={

        archivo:'',

        temporal:'',

        tipo:''

    };

    $scope.archivo='';

    $scope.msjerror=false;

    $scope.contador=1;

    $scope.enviarCorreo=false;



     $http.get('api/api.php?funcion=listaTiposDigitales&usr='+$rootScope.usrLogin).success(function (data){                            

          $scope.listadoTipoDocumento = data;

          console.log(data);

    });   

    busquedas.validaParticular($rootScope.folio).success(function(data){   
        $scope.cia =data.Cia_clave;            
      console.log($scope.cia);
        if(data.Cia_clave==51||data.Cia_clave==44||data.Cia_clave==54||data.Cia_clave==53||data.Cia_clave==64||data.Cia_clave==71){

            $scope.enviarCorreo=true;

        }
    });



    busquedas.digitalizados($rootScope.folio,$rootScope.usrLogin).success(function(data){           

        console.log(data);

        if(data==''){

                $scope.listaDigitales='';    

              }else{

                $scope.listaDigitales=data;  

              } 

              $scope.cargador=false;  

    });

    if($rootScope.clave==51){
      $scope.enviarCorreo=true;
    }



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

         //headers: {'header-key': 'header-value'},

         //withCredentials: true,

         data: $scope.factura,

         file: file, // or list of files ($files) for html5 only

         

       progress:function(evt) {

                 

           var amt =  parseInt(100.0 * evt.loaded / evt.total);

   

     $scope.countTo = amt;

     $scope.countFrom = 0;

     

     /*$timeout(function(){  

       $scope.progressValue = amt;

     }, 200);*/

       }

   })



      .success(function (data, status, headers, config){ 
            console.log(data); 
            if(data.error=='si'){
              $("#myModal").modal();
            }else{
              $scope.digital.archivo=data.nombre;
              $scope.digital.temporal=data.temporal;  
            }                              
            //console.log($scope.digital.archivo+'--'+$scope.digital.temporal);           

            }).error( function (xhr,status,data){
                alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
            });



    }



   

}



$scope.guardaDigital = function(){

  $scope.cargador=true;
  $scope.upload = $upload.upload({
    url:'api/api.php?funcion=guardaDigital&fol='+$rootScope.folio+'&tipo='+$scope.digital.tipo+'&usr='+$rootScope.usrLogin+'&cont='+$scope.contador,
    method:'POST',             
    data:$scope.digital,
    file: $scope.archivo
  }).success( function (data, status, headers, config){             
    $scope.contador=1;
    $scope.cargador=false;          
    $scope.msjerror=false;
    if(!data.respuesta){  
      console.log(data);         
      $scope.listaDigitales=data;
      $scope.digital={
        archivo:'',
        temporal:'',
        tipo:''
      };
    }else if(data.respuesta=='errorSize'){
      $scope.msjerror=true;
      $scope.error="El tamaño de archivo es muy grande (1.5 MB máximo)";
      $("#myModal").modal();
    }else if(data.respuesta=='errorType'){
      $scope.msjerror=true;
      $scope.error="Solo puedes subir imágenes en jpg para este tipo de archivo.";
      $("#myModal").modal();
    }else if(data.respuesta=='error'){
      $scope.msjerror=true;
      $scope.error="Ocurrio un error";
      $("#myModal").modal();
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

          $http({

            url:'api/api.php?funcion=eliminaDigital&fol='+$rootScope.folio+'&cont='+cont+'&tipo='+tipo,

            method:'POST',             

            data:$scope.digital,            

          }).success( function (data, status, headers, config){ 

            $scope.cargador=false;       

            if(!data.respuesta){

              if(data==''){

                $scope.listaDigitales='';    

              }else{

                $scope.listaDigitales=data;  

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

$scope.subirArchivo = function(){         

        $("#myModal").modal('hide');

        $scope.contador=2;

        $scope.guardaDigital();

}

$scope.irDocumentos = function(){         

        $location.path("/documentos");          

  }

});