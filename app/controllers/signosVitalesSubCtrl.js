app.controller('signosVitalesSubCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http) {
	$rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin =$cookies.usrLogin;
  $scope.formularios={};  
   $scope.vitales={      
      tem:"",
      talla:"",
      peso:"",
      frecResp:"",
      sistole:"",
      astole:"",
      frecCard:"",
      obs:''
  };
  $rootScope.cargador=false;

  $scope.interacted = function(field) {
    //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
    return $scope.formularios.vital.$submitted && field.$invalid;          
  };

  busquedas.validaSubsec($rootScope.folio).success(function(data){                      
    $scope.cargador=false;
    if(data.Cons==null){
      data.Cons=1;
    }
    $scope.noSubsec=data.Cons;    
  });

  busquedas.listaVitalesSub($rootScope.folio,$scope.noSubsec).success(function(data){
            console.log(data);
            $scope.listVitalesSub=data;                
            $rootScope.cargador=false;            
        });
       
  $scope.guardaVitales = function(){
    if($scope.formularios.vital.$valid){    
    $scope.cargador=true;
    console.log($scope.vitales);
    $http({
      url:'api/api.php?funcion=guardaVitalesSub&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&sub='+$scope.noSubsec,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.vitales
      }).success( function (data){
      console.log(data);                        
        if(!data.respuesta){                                            
          $scope.listVitalesSub=data;           
          $scope.vitales={      
            tem:"",
            talla:"",
            peso:"",
            frecResp:"",
            sistole:"",
            astole:"",
            frecCard:"",
            obs:''
          };
          $scope.formularios.vital.$submitted=false;
          $scope.cargador=false;
        }
        else{
          $scope.cargador=false;          
          alert('error en la inserción');         
        }      
      }).error( function (xhr,status,data){
          $scope.cargador=false;
          $scope.mensaje ='no entra';            
          alert('Error');
      }); 
      } 
  }

  $scope.imprimirSignosVitalesSub = function(){            
            var fileName = "Reporte";
            var uri = 'api/classes/formatoVitalesSub.php?fol='+$rootScope.folio+'&sub='+$scope.noSubsec;
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
              $location.path("/documentos");          
        }
        
});