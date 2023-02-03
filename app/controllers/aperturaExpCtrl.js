app.controller('aperturaExpCtrl', function($scope,$rootScope,$location,$cookies,$routeParams,$cookieStore) {
    $rootScope.permisos=JSON.parse($cookies.permisos);
    if($cookies.folioMembresia){
        $cookieStore.remove("folioMembresia");
    } 
    $rootScope.accPersonal=false;
    $scope.opcion=$routeParams.opcion;
    $scope.asignaProducto = function(claveCompania){
    	claveDefault=1;
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
       if(claveCompania==44||claveCompania==51||claveCompania==53||claveCompania==64){
            $cookies.clavePro = 10;
            rutaImgProducto= "pa.jpg"; 
            $cookies.rutaImgPro = rutaImgProducto;     
            $location.path("/registra");
        }
        else{

        	$location.path("/producto");
        }
    }

    $scope.veConvenios = function(claveCompania){
        
        $cookies.clave = claveCompania;            
        rutaImgCompania= $scope.imgCompania(claveCompania);
        $cookies.rutaImgCom = rutaImgCompania;                
        $cookies.clavePro = 10;
        rutaImgProducto= "pa.jpg"; 
        $cookies.rutaImgPro = rutaImgProducto;     
        $location.path("/convenio");        
    }

    $scope.imgCompania = function(claveCompania){
    	var img=0;
    	switch(claveCompania){    		
        case 1:
            img="aba.jpg";
            break;        
        case 33:
            img="ace.jpg";
            break;
         case 2:
            img="afirme.jpg";
            break;
         case 3:
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
         case 61:
            img="sinergia.jpg";
            break;
         case 62:
            img="anave.jpg";
            break;
         case 63:
            img="chubb.jpg";
            break;
        case 64: img = 'rehabilitacion.png'; 
            break;
        case 65:
            img="anahuac.jpg";
            break;

        }
    	return img;
    }
    
    
});