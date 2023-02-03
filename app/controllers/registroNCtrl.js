app.controller('registroNCtrl', function($scope,$rootScope,$location,$cookies,$cookieStore,$http,$cookieStore) {
  $cookies.cveUnidadAlternativa='';
	$scope.cveUnidad= $cookies.uniClave;
  $scope.usr      = $cookies.usrLogin;
	$rootScope.zona = $cookies.zona;
  $scope.cargador1 = true;  
  $rootScope.permisos=JSON.parse($cookies.permisos); 
  if($cookies.folioMembresia){
      $cookieStore.remove("folioMembresia");
  } 
  if($scope.cveUnidad==392){
      $cookies.clavePro = 10;
      rutaImgProducto= "pa.jpg"; 
      $cookies.rutaImgPro = rutaImgProducto;  
      $cookies.clave = 51;   
      $cookies.rutaImgCom = "individual.jpg";
      $location.path("/registra");
  }
  $cookieStore.remove("promocion");
	$scope.cia=$cookies.clave;
	$scope.unidadAlternativa='';
	$scope.sinParticulares= true;	
  $scope.cargador =true;
  $scope.cargador1 =true;
	$http.get('api/catalogos.php?funcion=catUnidades').success(function (data){                                                              
         $scope.listadoUnidades=data; 

	}); 

  $http.get('api/catalogos.php?funcion=catEmpresas').success(function (data){ 
          console.log(data);                                                             
         $scope.listadoCompanias=data; 
         $scope.cargador1 =false;         
  }); 

  $http.get('api/catalogos.php?funcion=masUsados&uni='+$scope.cveUnidad).success(function (data){                                                              
         // $scope.listadoCompanias=data; 
         console.log(data);
         $scope.usados = data;
         $scope.cargador =false;
  }); 


// VERIFICA EL ESTADO DEL ULTIMO REGISTRO
    $http({
        url:'api/api.php?funcion=revisaUltimoRegistro&unidad='+$scope.cveUnidad+'&usr='+$scope.usr,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.cveUnidad
        }).success( function (data){ 
        $scope.estadoUltimo = data;
        console.log($scope.estadoUltimo.Exp_estatusReg);
          if ($scope.estadoUltimo.Exp_estatusReg==1) {
            $location.path("/registra");
          }
        }).error( function (xhr,status,data){
            $scope.mensaje ='no entra';            
            alert('Error');
        });
// TERMINA LA VERIFICACION


 $scope.asignaProducto = function(claveCompania,tipo,id){
      claveDefault=1;
      if(tipo==1 || tipo==4){
        $rootScope.clave = claveCompania;
          if($rootScope.clave==9||$rootScope.clave==7||$rootScope.clave==8||$rootScope.clave==19){
              $rootScope.accPersonal=true;
              
          }
          $cookies.clave = claveCompania;
        $rootScope.clavePro = claveDefault;
          $cookies.clavePro = claveDefault;
        rutaImgCompania= $scope.imgCompania(claveCompania);     
        $cookies.rutaImgCom = rutaImgCompania;
        rutaImgProducto= "av.jpg";      
        $cookies.rutaImgPro = rutaImgProducto;
         if(claveCompania==44||claveCompania==51||claveCompania==53){
              $cookies.clavePro = 10;
              rutaImgProducto= "pa.jpg"; 
              $cookies.rutaImgPro = rutaImgProducto;     
              $location.path("/registra");
          }
          else{

            $location.path("/producto");
          }
        }else if (tipo==2) {
          var res = claveCompania.substring(2, 4);
           $cookies.clave = 54;
          $location.path("/infoConvenio/"+res);
        }else if (tipo==3) {          
          $cookies.clavePro = 10;
          rutaImgProducto= "pa.jpg"; 
          $cookies.rutaImgPro = rutaImgProducto;  
          $cookies.rutaImgCom = 'promocion.jpg'; 
          $cookies.promocion = id;  
          $cookies.clave = 51;
          $location.path("/registra");
        }
    }


  	$scope.asignarUnidad = function(){
      
  		if($scope.unidadAlternativa==''){
  			$cookies.cveUnidadAlternativa='';	
  		}else{
  			$cookies.cveUnidadAlternativa=$scope.unidadAlternativa;	
  		}                  		
        $http.get('api/catalogos.php?funcion=unidadPropia&unidad='+$scope.unidadAlternativa).success(function (data){ 
       	if(data=='N'){
       		$scope.sinParticulares= false;
       	}else{
       		$scope.sinParticulares= true;
       	}
  	});

    } 

     $scope.confirmaUnidad = function(tipo){
        $location.path("/opcionesRegistro/"+tipo); 
    } 

    $scope.imgCompania = function(claveCompania){
      var img=0;
      switch( parseInt(claveCompania)){        
        case 1:
            img="aba.jpg";
            break;        
        case 33:
            img="ace.jpg";
            break;
         case 2:
            img="afirme.jpg";
            break;
         case '3':
            img="aguila.jpg";
            break;
         case 4:
            img="aig.jpg";
            break;
         case 5:
            img="ana.jpg";
            break;
         case 6:
            img="atlas.jpg";
            break;
         case 7:
            img="axa.jpg";
            break;
         case 8:
            img="banorte.jpg";
            break;
         case 43:
            img="ci.jpg";
            break;
         case 44:
            img="cortesia.jpg";
            break;
         case 39:
            img="futv.JPG";
            break;
         case 40:
            img="futv2.JPG";
            break;
         case 9:
            img="general.jpg";
            break;
         case 10:
            img="gnp.jpg";
            break;
         case 11:
            img="goa.jpg";
            break;
         case 12:
            img="hdi.jpg";
            break;
         case 31:
            img="hir.jpg";
            break;
         case 45:
            img="inbursa.jpg";
            break;
         case 14:
            img="latino.jpg";
            break;
         case 41:
            img="lidnorte.JPG";
            break;
         case 15:
            img="mapfre.jpg";
            break;
         case 16:
            img="metro.jpg";
            break;
         case 37:
            img="multiafirme.jpg";
            break;
         case 35:
            img="multibancomer.jpg";
            break;
         case 36:
            img="multizurich.jpg";
            break;
         case 17:
            img="bx+.jpg";
            break;
         case 51:
            img="individual.jpg";
            break;
         case 18:
            img="potosi.jpg";
            break;
         case 22:
            img="primero.jpg";
            break;
         case 19:
            img="qualitas.jpg";
            break;
         case 20:
            img="rsa.jpg";
            break;
         case 32:
            img="spt.JPG";
            break;
         case 47:
            img="thona.jpg";
            break;
         case 34:
            img="travol.jpg";
            break;
         case 53:
            img="empleado.jpg";
            break;
         case 54:
            img="convenio.png";
            break;
         case 55:
            img="MetLife.jpg";
            break;
         case 56:
            img="PlanSeguro.jpg";
            break;
         case 57:
            img="caleb.jpg";
            break;
         case 21:
            img="zurich.jpg";
            break;
         case 58:
            img="siam_ace.jpg";
            break;
         case 59:
            img="siam_chubb.jpg";
            break;
         case 65:
            img="anahuac.jpg";
            break;
         case 68:
               img="cosem.jpg";
               break;

        }        
      return img;
    }
});