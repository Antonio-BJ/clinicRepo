app.controller('recetaParticularesCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$q) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;
  $scope.motivo='';
  $scope.cargador=true;
  // $scope.trabajando=true;

  $scope.itemParticulares={
          clave:'',
          cantidad:0,
          indicaciones:'',
          stock:0,
          tipoItem:'',
          posologia: ''
        };

  $scope.itemRecetaExterna = {
    item: null,
    indicacion: null,
    idReceta:null
  }

  // $scope.trabajando=false;

  $scope.existenciasParticulares = function (){
    $http.get('http://api.medicavial.mx/api/busquedas/exiParticulares/unidad/'+$rootScope.uniClave).success(function (data){                                                                                                   
      console.log(data);
      $scope.listadoItems = data;
    });
      $scope.trabajando=false;
  };
  $scope.existenciasParticulares();


/*******************************************  RECETA INTERNA************************************************/
  $scope.getRecetaInterna = function(){
    $http.get('api/notaMedica.php?funcion=getItemsRecetaInterna&fol='+$rootScope.folio).success( function (data){
      $scope.listadoRecetaInterna=data;
      console.log($scope.listadoRecetaInterna);
    });
  };
  $scope.getRecetaInterna();


  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){                                                            
      $scope.alergias = data;
  });                                       


  busquedas.listaIndicaciones().success(function(data){                      
    $scope.listaIndicacion=data;                     
  });


  $scope.getIndicacionesParticulares = function (){
    $http.get('api/notaMedica.php?funcion=getIndicacionesParticulares&fol='+$rootScope.folio).success(function (data){                                                            
      $scope.listaIndicAgreg=data;
      console.log($scope.listaIndicAgreg);
    });
  };
  $scope.getIndicacionesParticulares();
/****************************************************************************************************/


/******************************** RECETA EXTERNA ************************************/
  $scope.getRecetaExterna = function(){
    $http.get('api/notaMedica.php?funcion=getItemsRecetaExterna&fol='+$rootScope.folio).success( function (data){
      if (data!='"no existe"') {
        $scope.listadoRecetaExterna=data;
      } else{
        $scope.listadoRecetaExterna=null;
      };
      console.log($scope.listadoRecetaExterna);
    });
  };
  $scope.getRecetaExterna();
/**********************************************************************/


  $scope.filtraOrtesisVacios = function () {
      return function (item) {
          if (item.Stock > 0)
          {                  
              return true;                                             
          }
          return false;
      };
  };

  $scope.itemSeleccionado = function(claveItem){  
    console.log(claveItem);

    $scope.itemParticulares.cantidad=0;

    for(lista in $scope.listadoItems){
      if(claveItem==$scope.listadoItems[lista].Clave_producto){              
        $scope.itemParticulares.presentacion     = $scope.listadoItems[lista].presentacion;
        $scope.itemParticulares.indicaciones     = $scope.listadoItems[lista].posologia;
        $scope.itemParticulares.stock            = parseInt($scope.listadoItems[lista].Stock);
        $scope.itemParticulares.descripcion      = $scope.listadoItems[lista].Descripcion;
        $scope.itemParticulares.almacen          = $scope.listadoItems[lista].almacen;
        $scope.itemParticulares.idMedicamento    = $scope.listadoItems[lista].Clave_producto;
        $scope.itemParticulares.existencia       = $scope.listadoItems[lista].id;
        $scope.itemParticulares.tipoItem         = $scope.listadoItems[lista].tipoItem;
        console.log($scope.itemParticulares);
      }            
    }
  }


  $scope.guardaRecetaInterna=function(){
    // $scope.trabajando=true;
    console.log($scope.formularios.recetaInterna);

    if ($scope.formularios.recetaInterna.$valid) {
      $scope.trabajando=true;

      if ($scope.itemParticulares.stock >= $scope.itemParticulares.cantidad) {
        console.log($scope.itemParticulares);

        //GENERAMOS LA RESERVA DE ITEMS
        $http({
          url: 'http://api.medicavial.mx/api/operacion/reserva/item',
          method: 'POST',
          contentType: 'application/json',
          dataType: "json",
          data: {id_item:     $scope.itemParticulares.idMedicamento, 
                 id_almacen:  $scope.itemParticulares.almacen, 
                 NS_cantidad: $scope.itemParticulares.cantidad,
                 tipo_item:   $scope.itemParticulares.tipoItem
               }
        }).success( function (data){
            $scope.itemParticulares.reserva = data;
            console.log($scope.itemParticulares);

            //GUARDAMOS LOS DATOS DE LOS ITEMS PARA GENERAR LA RECETA
            $http({
              url: 'api/notaMedica.php?funcion=guardaItemsParticulares&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave+'&usr='+$rootScope.usrLogin+'&tipoReceta=5',
              method:'POST', 
              contentType: 'application/json', 
              dataType: "json", 
              data: $scope.itemParticulares
            }).success( function (data){

              //TRAEMOS LA LISTA DE ITEMS GUARDADOS EN LA RECETA
              $scope.getRecetaInterna();

              //VOLVEMOS A TRAER LOS ITEMS EXISTENTES
              $scope.existenciasParticulares();

              //ACTUALIZAMOS LA CANTIDAD DE EXISTENTES DE ACUERDO A LOS ITEMS QUE FUERON RESERVADOS
              $scope.itemParticulares.stock = $scope.itemParticulares.stock - $scope.itemParticulares.cantidad;

              for( lista in $scope.listadoItems ){
                if( $scope.itemParticulares.clave == $scope.listadoItems[lista].Clave_producto ){
                      $scope.itemParticulares.presentacion  = $scope.listadoItems[lista].presentacion;
                      $scope.itemParticulares.posologia     = $scope.listadoItems[lista].posologia;
                      $scope.listadoItems[lista].Stock      = $scope.itemParticulares.stock;
                      
                      console.log( $scope.listadoItems[lista].Stock );
                };
              };

              //RESETEAMOS LAS VARIABLES
              $scope.itemParticulares={
                      clave:'',
                      cantidad:0,
                      indicaciones:'',
                      stock:0,
                      tipoItem:'',
                      posologia: ''
                    };

              //RESETEAMOS EL FORMULARIO
              $scope.formularios.recetaInterna.$submitted=false;
              $scope.formularios.recetaInterna.$pristine=true;
              $scope.formularios.recetaInterna.$dirty=false;
              // document.getElementById("formularios.recetaInterna").reset();

              $scope.trabajando=false;

            }).error( function (xhr,status,data){
                $scope.trabajando=false;
                $scope.mensaje = 'Error al ecribir la receta interna';
                alert('Error al ecribir la receta interna');
            });

        }).error( function (xhr,status,data){
              $scope.trabajando=false;
              $scope.mensaje = 'Error en inventario';            
              alert('Error en inventario');
        });
      } else{
          alert("Revisa la cantidad");
      };
    } else{
        alert("Revisa que los datos sean correctos");
    };
  };


  $scope.eliminaItemParticulares = function(cveReserva, cveItemReceta, id_item, cantidad){ 
      $scope.trabajando=true;

      $http({
          method: 'DELETE',
          url: 'http://api.medicavial.mx/api/operacion/reserva/'+cveReserva
      }).success(function(data, status, headers, config) {
          console.log(cveItemReceta);

          $http({
            url:'api/notaMedica.php?funcion=eliminaItemParticulares&cveItemReceta='+cveItemReceta+'&usr='+$rootScope.usrLogin,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json"
          }).success( function (data){
            console.log(data);

            //TRAEMOS LA LISTA DE ITEMS GUARDADOS EN LA RECETA
            $scope.getRecetaInterna();

            //VOLVEMOS A TRAER LOS ITEMS EXISTENTES
            $scope.existenciasParticulares();

            $scope.itemParticulares.stock=parseInt($scope.itemParticulares.stock)+parseInt(cantidad); 

            for( lista in $scope.listadoItems ){
              if( id_item == $scope.listadoItems[lista].Clave_producto ){
                    $scope.itemParticulares.presentacion        = $scope.listadoItems[lista].presentacion;
                    $scope.itemParticulares.posologia           = $scope.listadoItems[lista].posologia;
                    $scope.listadoItems[lista].Stock            = $scope.itemParticulares.stock;

                    console.log( $scope.listadoItems[lista].Stock );
              }
            }

            $scope.trabajando=false;

          }).error( function (xhr,status,data){
              $scope.mensaje ='Error al actualizar la receta';            
              alert('Error al actualizar la receta');
              $scope.trabajando=false;
          });     
      }).error(function (data, status, headers, config) {
          if (status === 400) {
              defered.reject(data);
              $scope.trabajando=false;
          } else {
              throw new Error("Fallo obtener los datos:" + status + "\n" + data);
              $scope.trabajando=false;
          }
      });                         
  };


  $scope.guardaIndicaciones= function(){
    if($scope.formularios.indica.$valid){ 
      $scope.trabajando=true;
      $scope.validaPalabraInd= validaPalabrasProhibidasInd($scope.indicacion.indicacion);          
      if($scope.validaPalabraInd==0){
      $scope.msjPalabraProhiInd=false;     
      $scope.cargador2=true;
      $scope.trabajando=true;
      $http({
        url:'api/notaMedica.php?funcion=saveIndicacionesParticulares&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.indicacion
        }).success( function (data){
          console.log(data);
          $scope.getIndicacionesParticulares();
            $scope.indicacion={
              indicacion:'',
              obs:''
            }

            // $scope.listaIndicAgreg=data;
            $scope.formularios.indica.$submitted=false;                                    
            $scope.cargador2=false;
            $scope.trabajando=false;

        }).error( function (xhr,status,data){
            $scope.mensaje ='Error al escribir la receta';            
            alert('Error al escribir la receta');
            $scope.trabajando=false;
        });
        } else{
          $scope.trabajando=false;
          $scope.msjPalabraProhiInd=false;
        }                     
    }
  }

  $scope.eliminarIndicacion = function(idIndicacion){ 
    $scope.trabajando=true;
      $http({
      url:'api/notaMedica.php?funcion=deleteIndicacionCE&fol='+$rootScope.folio+'&idIndicacion='+idIndicacion,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: {cve:'valor'}
      }).success( function (data){                        
          console.log(data);

          $scope.getIndicacionesParticulares();
          $scope.trabajando=false;

      }).error( function (xhr,status,data){
          $scope.mensaje ='Error al escribir receta';            
          alert('Error al escribir receta');
          $scope.trabajando=false;
      });                               
  }           

  $scope.verIndicacionCam = function(){            
      if($scope.indicacion.obs=='' || $scope.indicacion.obs==null){
        $scope.indicacion.obs=$scope.indicacion.indicacion;
      }else{
        $scope.indicacion.obs=$scope.indicacion.obs+', '+$scope.indicacion.indicacion;
      }
  }  

  $scope.guargaItemExterno = function () {
    console.log($scope.itemRecetaExterna);
    $scope.trabajando=true;
      $http({
        url:'api/notaMedica.php?funcion=saveItemRE&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave,
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.itemRecetaExterna
      }).success( function (data){                        
          console.log(data);
          if (data == '"exito"') {

            //RESETEAMOS EL FORMULARIO
            $scope.formularios.recetaExterna.$submitted=false;
            $scope.formularios.recetaExterna.$pristine=true;
            $scope.formularios.recetaExterna.$dirty=false;

            $scope.itemRecetaExterna = {
              item: null,
              indicacion: null,
              idReceta:null
            };
          };

          $scope.getRecetaExterna();
          $scope.trabajando=false;

      }).error( function (xhr,status,data){
          $scope.mensaje ='Error al escribir receta';            
          alert('Error al escribir receta');
          $scope.trabajando=false;
      });   
  }


  $scope.eliminaItemExterno = function(idItemExt){ 
    $scope.trabajando=true;
      $http({
      url:'api/notaMedica.php?funcion=eliminaItemRE&fol='+$rootScope.folio+'&idItemExt='+idItemExt,
      method:'POST', 
      contentType: 'application/json', 
      dataType: "json", 
      data: {cve:'valor'}
      }).success( function (data){                        
          console.log(data);

          $scope.getIndicacionesParticulares();
          $scope.trabajando=false;

      }).error( function (xhr,status,data){
          $scope.mensaje ='Error al escribir receta';            
          alert('Error al escribir receta');
          $scope.trabajando=false;
      });                               
  }    

    

        $scope.getRecetaCompleta = function(){
            $scope.getRecetaInterna();
            $scope.getIndicacionesParticulares();
            $scope.getRecetaExterna();
        };


        $scope.imprimirReceta = function(){
          $scope.trabajando=true;
            console.log('entro');           
            var fileName = 'RecetaParticulares-'+$rootScope.folio;
            var uri = 'api/classes/formatoRecetaParticulares.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;

            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".pdf";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();           
            document.body.removeChild(link);
            // window.open('../registro/DigitalesSistema/'+$rootScope.folio+'/RecetaCE_'+$rootScope.folio+'.pdf');

            $scope.getRecetaInterna();
            $scope.getRecetaExterna();
            $scope.getIndicacionesParticulares();
            $location.path("/documentos");
            $scope.trabajando=false;
        }
 
});