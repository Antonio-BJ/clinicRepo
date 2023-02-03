app.controller('subsecuenciaListadoCtrl', function($scope,$rootScope,$location,$cookies,WizardHandler,busquedas,$http) {
	$rootScope.folio=$cookies.folio;	
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  $scope.cargador=true;
  busquedas.listSubsecuencias($rootScope.folio).success(function(data){             
  		$scope.cargador=false;        
        $scope.subsecuencias=data.Subs;              
        console.log($scope.subsecuencias);
	});

  /********************  Nuevo sistema de inventarios **********************/
  $scope.cveUniInventario=1;
    if($rootScope.uniClave==1||$rootScope.uniClave==3||$rootScope.uniClave==2||$rootScope.uniClave==184||$rootScope.uniClave==4||$rootScope.uniClave==86||$rootScope.uniClave==7||$rootScope.uniClave==5||$rootScope.uniClave==186||$rootScope.uniClave==6||$rootScope.uniClave==8){
      $scope.cveUniInventario=$rootScope.uniClave;  
    }
    
    if($scope.cveUniInventario==$rootScope.uniClave){
      $scope.ValidaInventario=1;
    }else{
      $scope.ValidaInventario=2;
    }
  /*************************************************************************/

  $scope.imprimirRecetaSubse = function(cont){
          $scope.cargador=true;    
              if($scope.ValidaInventario==1){
                $scope.validaDoc=$scope.verificaArchivoSubRec(cont);
                console.log($scope.validaDoc);
                console.log($rootScope.folio);
                console.log($rootScope.usrLogin);
                console.log(cont);
                if($scope.validaDoc==1){
                  $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/RSub_'+cont+'_'+$rootScope.folio+'.pdf';
                }else{
                  $scope.url='api/classes/formatoRecSubList1.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cont='+cont;
                }
              }else{
                $scope.validaDoc=$scope.verificaArchivoSubRec(cont);
                if($scope.validaDoc==1){
                  $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/RSub_'+cont+'_'+$rootScope.folio+'.pdf';
                }else{
                  $scope.url='api/classes/formatoRecSubList.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cont='+cont;
                }
              }

            var fileName = 'RSub_'+cont+'_'+$rootScope.folio+'.pdf';
            var uri = $scope.url;
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

        //validación de archivo si existe regresa 1, si no 0
         $scope.verificaArchivoSubRec = function(cont){
            var http = new XMLHttpRequest();
            http.open('HEAD', '../registro/DigitalesSistema/'+$rootScope.folio+'/RSub_'+cont+'_'+$rootScope.folio+'.pdf', false);
            http.send();
            if(http.status!=404){
              return 1;
            }else{
              return 0;
            }
          }
        $scope.imprimirSubList = function(cont){          
          $scope.cargador=true;
          console.log($scope.ValidaInventario);
             if($scope.ValidaInventario==1){
                $scope.validaDoc=$scope.verificaArchivoSubNot(cont);
                console.log($scope.validaDoc);
                if($scope.validaDoc==1){
                  $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/SB_'+cont+'_'+$rootScope.folio+'.pdf';
                }else{
                  $scope.url='api/classes/formatoSubList1.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cont='+cont;
                }
              }else{
                $scope.validaDoc=$scope.verificaArchivoSubNot(cont);
                if($scope.validaDoc==1){
                  $scope.url= '../registro/DigitalesSistema/'+$rootScope.folio+'/SB_'+cont+'_'+$rootScope.folio+'.pdf';
                }else{
                 $scope.url='api/classes/formatoSubList.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&cont='+cont;
                }
              }          
            var fileName = 'SB_'+cont+'_'+$rootScope.folio+'.pdf';
            var uri = $scope.url;
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

        //validación de archivo si existe regresa 1, si no 0
         $scope.verificaArchivoSubNot = function(cont){
            var http = new XMLHttpRequest();
            http.open('HEAD', '../registro/DigitalesSistema/'+$rootScope.folio+'/SB_'+cont+'_'+$rootScope.folio+'.pdf', false);
            http.send();
            if(http.status!=404){
              return 1;
            }else{
              return 0;
            }
          }


});
