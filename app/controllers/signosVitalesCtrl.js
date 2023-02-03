app.controller('signosVitalesCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio= webStorage.session.get('folio'); 
  console.log($rootScope.folio);
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
      obs:'',
      oximentria:''
  };
  $rootScope.cargador=false;

  $scope.interacted = function(field) {
    //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
    return $scope.formularios.vital.$submitted && field.$invalid;          
  };

  busquedas.listaVitales($rootScope.folio).success(function(data){
            $scope.listVitales=data;                
            $rootScope.cargador=false; 
            console.log(data);           
        });
       
  $scope.guardaVitales = function(){
    if($scope.formularios.vital.$valid){    
    $scope.cargador=true;
    $http({
      url:'api/api.php?funcion=guardaVitalesP&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: $scope.vitales
      }).success( function (data){
        console.log(data);                          
        if(!data.respuesta){                                      
          $scope.listVitales=data;           
          $scope.vitales={      
            tem:"",
            talla:"",
            peso:"",
            frecResp:"",
            sistole:"",
            astole:"",
            frecCard:"",
            obs:'',
            oximentria:''
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

  $scope.imprimirSignosVitales = function(){            
            var fileName = "Reporte";
            var uri = 'api/classes/formatoVitales.php?fol='+$rootScope.folio;
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