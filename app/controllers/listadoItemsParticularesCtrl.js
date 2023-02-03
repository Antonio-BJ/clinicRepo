app.controller('listadoItemsParticularesCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload, DTOptionsBuilder) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio=   $cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
  //$rootScope.rutaPro= $cookies.rutaImgPro;
  
  $scope.reporte={
    compania:'',
    unidad:''
  }
   $scope.personColour={'background-color':'#ffffff'};
  
  $scope.mensaje=false;
  $scope.cargador = true;
  $http.get('api/Clasificaciones.php?funcion=getItemsParticulares').success(function (data){                             
      console.log(data);
       $scope.cargador = false;
      $scope.listadoItemPar=data;      
  });
 $scope.changeColor= function(){
        console.log('si pasa');
        $scope.personColour='cc0000';// not sure what to do here???
}

  $http.get('api/Clasificaciones.php?funcion=getCompanias').success(function (data){
      console.log(data);
  $scope.listadoCompania=data;                             
  });
  
    $scope.generaReporteSinDoc = function(){ 
            $scope.cargador=true;                          
                $scope.cargador=true;               
                $http({
                      url:'api/Clasificaciones.php?funcion=listadoSinDocumentacion&usr='+$rootScope.usrLogin,
                      method:'POST', 
                      contentType: 'application/json', 
                      dataType: "json", 
                      data: $scope.reporte
                      }).success( function (data){ 
                        $scope.cargador = false;                
                        if(data.respuesta){                        
                            $scope.comentario.comentario='';
                        }else{
                            if(data==''){
                              $scope.mensaje=true; 
                            }else{
                              $scope.mensaje=false;
                              $scope.listadoSinCla=data;  
                            }
                            
                        }                         
                                            
                      }).error( function (xhr,status,data){
                          $scope.mensaje ='no entra';            
                          alert('Error');
                      });
                                   
        
    }

     $scope.exportarReporte = function(){               
            var fileName = 'Reporte Qualitas ';
            var uri = 'api/classes/generaExcelSinDoc.php?uni='+$scope.reporte.unidad+'&cia='+$scope.reporte.compania;
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
  $scope.descargar= function(){
    var fileName = 'Listado de Items';
    var uri = 'api/classes/descargalista_itemsxls.php';
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".xls";
    
    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();           
    document.body.removeChild(link);
  }

  $scope.dtOptions = DTOptionsBuilder.newOptions()
  .withOption('lengthMenu', [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"] ])
    // .withOption('responsive', true)
    .withPaginationType('full_numbers')
    .withOption('language', {
        paginate: {
            first: "«",
            last: "»",
            next: "→",
            previous: "←"
        },
        search: "Buscar:",
        loadingRecords: "Cargando Información....",
        lengthMenu: "    Mostrar _MENU_ entradas",
        processing: "Procesando Información",
        infoEmpty: "No se encontro información",
        emptyTable: "Sin Información disponible",
        info: "Mostrando pagina _PAGE_ de _PAGES_ , Registros encontrados _TOTAL_ ",
        infoFiltered: " - encontrados _MAX_ coincidencias"
    })
    .withOption('rowCallback', function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        $('td', nRow).bind('click', function() {



            $scope.$apply(function() {



            });
        });
        return nRow;
    })


 
});