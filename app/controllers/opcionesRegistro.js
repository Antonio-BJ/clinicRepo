app.controller('opcionesRegistroCtrl', function($scope,$rootScope,$location,$cookies,$routeParams,$cookieStore,$http,webStorage) {

    $rootScope.permisos=JSON.parse($cookies.permisos);

    $rootScope.uniClave=$cookies.uniClave;

    if($cookies.folioMembresia){

        $cookieStore.remove("folioMembresia");

    }
    
    
    if(webStorage.session.get('rhCovidTipo')){
        webStorage.session.remove('rhCovidTipo'); 
    }

    $rootScope.accPersonal=false;

    $scope.opcion=$routeParams.opcion;

    $scope.cargador1 =true;     
    
    $scope.miRenglon ='';

    $scope.opcionRhCovid='';

    $scope.opcion1=0;



    $http.get('api/catalogos.php?funcion=catEmpresas&opcion='+$scope.opcion).success(function (data){  

        console.log(data);                                                            

         $scope.listadoCompanias=data; 

         $scope.cargador1 =false;         

    });

    $scope.asignaProducto = function(claveCompania,tipo,id){

	   claveDefault=1;

      if(tipo==1){

        $rootScope.clave = claveCompania;

          if($rootScope.clave==9||$rootScope.clave==7||$rootScope.clave==8||$rootScope.clave==19){

              $rootScope.accPersonal=true;

              

          }

          $cookies.clave = claveCompania;

        $rootScope.clavePro = claveDefault;

          $cookies.clavePro = claveDefault;



        rutaImgCompania= $scope.imgCompania(claveCompania);     

        console.log(claveCompania);

        $cookies.rutaImgCom = rutaImgCompania;

        rutaImgProducto= "av.jpg";      

        $cookies.rutaImgPro = rutaImgProducto;

         if(claveCompania==44||claveCompania==51||claveCompania==53||claveCompania==66||claveCompania==64||claveCompania==81){

              $cookies.clavePro = 10;

              rutaImgProducto= "pa.jpg"; 

              $cookies.rutaImgPro = rutaImgProducto;     

              $location.path("/registra");

          }

          else{



            $location.path("/producto");

          }

        }else if (tipo==2) {

         console.log(claveCompania);

          var res = claveCompania.substring(2, 4);

          $cookies.clave = 54;

          $cookies.clavePro = 10;

          $location.path("/infoConvenio/"+res);
          



        }else if (tipo==3) {          

          $cookies.clavePro = 10;

          rutaImgProducto= "pa.jpg"; 

          $cookies.rutaImgPro = rutaImgProducto;  

          $cookies.rutaImgCom = 'promocion.jpg'; 

          $cookies.promocion = id;  

          $cookies.clave = 51;

          if(id==78){

            $("#cellers").modal();

          }else if(id==79){

            $("#experience").modal();

          }else if(id==80){

            $("#aliado").modal();

          }else if(id==81){

            $("#asmas").modal();

          }else if(id==82){

            $("#bienestar").modal();

          }else if(id==84){

            $("#revajim").modal();

          }else if(id==122){

            $("#banamex").modal();

          }else if(id==123){

            $("#scotia").modal();

          }else if(id==124){

            $("#rehab5").modal();

          }else if(id==125){

            $("#10sesiones").modal();

          }else if(id==126){

            $("#5sesiones").modal();

          }else if(id==710){

            $("#10mayo").modal();


          }else if(id==711){

            $("#paquetemejores").modal();

                    
          }else if(id==714){

            $("#BANCOPPEL").modal();

                    
          }else if(id==715){

            $("#CONVENIOMAPFRE").modal();

                    
          }else{

            $location.path("/registra");  

          }

          

        }else if(tipo==4){

        $rootScope.clave = claveCompania;

          if($rootScope.clave==9||$rootScope.clave==7||$rootScope.clave==8||$rootScope.clave==19){

              $rootScope.accPersonal=true;

              

          }

          $cookies.clave = claveCompania;

        $rootScope.clavePro = claveDefault;

          $cookies.clavePro = claveDefault;



        rutaImgCompania= $scope.imgCompania(claveCompania);     

        console.log(claveCompania);

        $cookies.rutaImgCom = rutaImgCompania;

        rutaImgProducto= "av.jpg";      

        $cookies.rutaImgPro = rutaImgProducto;


         if(claveCompania==44||claveCompania==51||claveCompania==53||claveCompania==64||claveCompania==71||claveCompania==5424||claveCompania==81||claveCompania==83 ||claveCompania==92 ||claveCompania==93){

              $cookies.clavePro = 10;
              rutaImgProducto= "pa.jpg"; 
              $cookies.rutaImgPro = rutaImgProducto;
              if(claveCompania==81){
                  $("#rhCovid").modal();
              }else{
                  $location.path("/registra");
              }     
              
          }else{



            $location.path("/producto");

          }

        }else if(tipo==5){
         $rootScope.clave = claveCompania;
         $cookies.clave = claveCompania;
         $rootScope.clavePro = claveDefault;
         $cookies.clavePro = claveDefault;
         rutaImgCompania= $scope.imgCompania(claveCompania);     
         $cookies.rutaImgCom = rutaImgCompania;
         rutaImgProducto= "ap.jpg";      
         $cookies.rutaImgPro = rutaImgProducto;
         $cookies.clavePro = 2;
         $location.path("/registra");
 
         }

    }

    $scope.formRHCovid = function(dato){
      $("#rhCovid").modal("hide");
      $location.path("/registra");
    }

    $scope.asignaValor = function(dato){
      webStorage.session.add('rhCovidTipo', dato); 

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

   $scope.verRenglon = function(index){

      if(index==6 || index==12 ||index==8 ||index==24 ||index==30 ||index==36 ||index==42 ||index==48 || index==54){
         $scope.miRenglon ='row';
      }else{
         $scope.miRenglon ='';
      }
      return $scope.miRenglon;

   }



     $scope.irRegistra = function(){ 

       $("#cellers").modal('hide');

       $("#experience").modal('hide');

       $("#aliado").modal('hide');

       $("#asmas").modal('hide');

       $("#bienestar").modal('hide');

       $("#revajim").modal('hide');

       $("#banamex").modal('hide');
       $("#scotia").modal('hide');
       $("#rehab5").modal('hide'); 
       $("#10sesiones").modal('hide');
       $("#5sesiones").modal('hide'); 
       $("#10mayo").modal('hide'); 
       $("#paquetemejores").modal('hide'); 
       $("#BANCOPPEL").modal('hide');                   

       $location.path("/registra");

    }



    $scope.registraGastosMedicos = function(claveCompania){

        

        $cookies.clave = claveCompania;            

        rutaImgCompania= $scope.imgCompania(claveCompania);

        $cookies.rutaImgCom = rutaImgCompania;                

        $cookies.clavePro = 10;

        rutaImgProducto= "pa.jpg"; 

        $cookies.rutaImgPro = rutaImgProducto;     

        $location.path("/registroGMM");        

    }



    $scope.imgCompania = function(claveCompania){

    	var img=0;

    	switch(claveCompania){    		

        case '1':

            img="aba.jpg";

            break;        

        case '33':

            img="ace.jpg";

            break;

         case '2':

            img="afirme.jpg";

            break;

         case '3':

            img="aguila.jpg";

            break;

         case '4':

            img="aig.jpg";

            break;

         case '5':

            img="ana.jpg";

            break;

         case '6':

            img="atlas.jpg";

            break;

         case '7':

            img="axa.jpg";

            break;

         case '8':

            img="banorte.jpg";

            break;

         case '43':

            img="ci.jpg";

            break;

         case '44':

            img="cortesia.jpg";

            break;

         case '39':

            img="futv.JPG";

            break;

         case '40':

            img="futv2.JPG";

            break;

         case '9':

            img="general.jpg";

            break;

         case '10':

            img="gnp.jpg";

            break;

         case '11':

            img="goa.jpg";

            break;

         case '12':

            img="hdi.jpg";

            break;

         case '31':

            img="hir.jpg";

            break;

         case '45':

            img="inbursa.jpg";

            break;

         case '14':

            img="latino.jpg";

            break;

         case '41':

            img="lidnorte.JPG";

            break;

         case '15':

            img="mapfre.jpg";

            break;

         case '16':

            img="metro.jpg";

            break;

         case '37':

            img="multiafirme.jpg";

            break;

         case '35':

            img="multibancomer.jpg";

            break;

         case '36':

            img="multizurich.jpg";

            break;

         case '17':

            img="bx+.jpg";

            break;

         case '51':

            img="individual.jpg";

            break;

         case '18':

            img="potosi.jpg";

            break;

         case '22':

            img="primero.jpg";

            break;

         case '19':

            img="qualitas.jpg";

            break;

         case '20':

            img="rsa.jpg";

            break;

         case '32':

            img="spt.JPG";

            break;

         case '47':

            img="thona.jpg";

            break;

         case '34':

            img="travol.jpg";

            break;

         case '53':

            img="empleado.jpg";

            break;

         case '54':

            img="convenio.png";

            break;

         case '55':

            img="MetLife.jpg";

            break;

         case '56':

            img="PlanSeguro.jpg";

            break;

         case '57':

            img="caleb.jpg";

            break;

         case '21':

            img="zurich.jpg";

            break;

         case '58':

            img="siam_ace.jpg";

            break;

         case '59':

            img="siam_chubb.jpg";

            break;

         case '65':

            img="anahuac.jpg";

            break;

          case '651':

            img="anahuac.jpg";

            break;

            case '67':

            img="crabi.jpg";

            break;

            case '68':

               img="cosem.jpg";

               break;

            case '69':

               img="flecha.jpg";

               break;

            case '70':

               img="camar.jpg";

               break;

            case '71':

               img="redesSociales.jpg";

               break;
               case '74':

                  img="monterrey.jpg";
   
                  break;
               case '76':

                  img="keken.jpg";
   
                  break;
               case '77':

                  img="gmx.jpg";
   
                  break;
               case '78':

                  img="crb.jpg";
   
                  break;
                  case '79':

                     img="mms.jpg";
      
                     break;
                  case '80':

                     img="yza.jpg";
      
                  break;
                  case '81':

                     img="covid.jpg";
      
                  break;
                  case '83':

                     img="medici.jpg";
      
                  break;
                  case '84':

                     img="84.jpg";
      
                  break;

            case '5424':

               img="twitter.jpg";

               break;

               case '87':
               img="87.jpg";
               break;

               case '90':
               img="90.jpg";
               break;

               case '92':
               img="92.jpg";
               break;

               case '93':
               img="93.jpg";
               break;

        }        

    	return img;

    }

    

    

});