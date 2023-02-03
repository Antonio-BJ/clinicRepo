app.controller('autorizacionZimaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom;
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;


$scope.cargador=false;
$scope.cargador1=false;
$scope.mensaje= '';
$scope.intervalo={
  fechaInicio:'',
  fechaFin:'',
  localidad:''
}

$scope.rehabilitacionForm={
		tipo:'',
		escala:'',
		mejoria:'',
		criterios:'',
		observa:'',
		duracion:'',
		acudio:''
}

$scope.rx={
    archivo:'',
    temporal:'',
    noPlac:1,
    inter:''
  };
$scope.selectos = [];

$scope.filterOptions = {
		filterText: '',
		useExternalFilter: false
};
$scope.noAutorizacion='';
$scope.folioZima='';

$scope.myData = [{name: "Moroni", age: 50},
                     {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
										 {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
										 {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
										 {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
										 {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
										 {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
										 {name: "Teancum", age: 43},
                     {name: "Jacob", age: 27},
                     {name: "Nephi", age: 29},
                     {name: "Enos", age: 34}];


	$scope.myOptions = {
			 data: 'myData',
			 enableColumnResize:true,
			 enablePinning: true,
			 enableRowSelection:false,
			 multiSelect:false,
			 showSelectionCheckbox: false,
			 selectWithCheckboxOnly: false,
			 enableCellSelection: false,
			 selectedItems: $scope.selectos,
			 filterOptions: $scope.filterOptions,
			 columnDefs: [
							 { field:'name', displayName:'Nombre' , width: 500, pinned:true, enableCellEdit: true , cellTemplate: '<div ng-class="{ \'text-danger\': row.entity.penalizado ==  \'1\'}" class="padding-cell"><i ng-if="row.entity.penalizado ==  \'1\'" class="glyphicon glyphicon-warning-sign"></i> {{row.getProperty(col.field)}}</div>'},
							 { field:'age', displayName:'Edad', width: 500 }
			 ],
			 showFooter: true,
			 showFilter:true
	 };




  $http.get('api/catalogos.php?funcion=getLocalidades').success(function (data){
    $scope.catLoc=data;
  });


  $scope.irDocumentos = function(){
    $location.path("/documentos");
  }

  $scope.mandaPortada = function(folio){
    webStorage.local.clear();
    webStorage.session.add('folio', folio);
    $cookies.folio = folio;
        $location.path("/portada");
  }
  $scope.mandaDocumentos = function(folio){
    webStorage.local.clear();
    webStorage.session.add('folio', folio);
    $cookies.folio = folio;
        $location.path("/documentos");
  }

   /*************************************    imprimir formato de RX ******************************************/
        $scope.imprimirReporte = function(){
            console.log($scope.intervalo.fechaInicio);
            var fileName = 'Reporte Qualitas ';
            var uri = 'api/classes/generaExcel.php?fecha1='+$scope.intervalo.fechaInicio+'&fecha2='+$scope.intervalo.fechaFin+'&localidad='+$scope.intervalo.localidad;
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

        /**********************************************************************************************************/
				$scope.buscarAutorizacion = function(){
					$scope.cargador=true;
					if($scope.noAutorizacion==''&&$scope.folioZima==''){
						$scope.mensaje='Número de autorización o Folio Zima necesario';
						$scope.cargador=false;
					}else{
						$scope.mensaje='';
						console.log($scope.noAutorizacion);
						$http.get('api/autorizacionZima.php?funcion=getAutorizacion&aut='+$scope.noAutorizacion+'&folZima='+$scope.folioZima).success(function (data){
							$scope.cargador=false;
							console.log(data);
								if(data.autorizacion==false){
									$scope.autDetalle = '';
									$scope.mensaje='No se encontró autorización';
								}else{
										$scope.autDetalle = data;
										$scope.folioZima = data.autorizacion.REG_folio;
										$scope.noAutori =parseInt(data.noRehab.contador)+1;
								}
						});
					}
        }

				$scope.onFileSelect_xml = function($files) {
					 for (var i = 0; i < $files.length; i++) {
						 var file = $files[i];
						 $scope.archivo=file;
						 $scope.variable = 2;
						 var amt = 0;
						 $scope.upload = $upload.upload({
							 url: 'api/api.php?funcion=archivo_temporal',
							 method: 'POST',
							 data: $scope.factura,
							 file: file,
							 progress:function(evt) {

								 console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
								 $scope.progressBar = parseInt(100.0 * evt.loaded / evt.total);
							 }
						 }).success(function (data, status, headers, config){
							 $scope.rx.archivo=data.nombre;
							 $scope.rx.temporal=data.temporal;
							 console.log($scope.rx.archivo+'--'+$scope.rx.temporal);
						 }).error( function (xhr,status,data){
							 alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
						 });
					 }
				 }

				$scope.guardaRehabilitacion = function(){
			              $scope.cargador1=true;

			              $http({
			                    url:'api/autorizacionZima.php?funcion=guardaRehabilitacion&folZima='+$scope.folioZima+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave,
			                    method:'POST',
			                    contentType: 'application/json',
			                    dataType: "json",
			                    data: $scope.rehabilitacionForm
			                    }).success( function (data){
			                      console.log(data);
			                      $scope.formu=true;
			                      if(data.respuesta=='SI'){
			                        var fileName = "Reporte";
			                        var uri = 'api/classes/formatoRehabilitacionZima.php?folioZima='+$scope.folioZima+'&usr='+$rootScope.usrLogin;
			                        var link = document.createElement("a");
			                        link.href = uri;

			                        //set the visibility hidden so it will not effect on your web-layout
			                        link.style = "visibility:hidden";
			                        link.download = fileName + ".pdf";

			                        //this part will append the anchor tag and remove it after automatic click
			                        document.body.appendChild(link);
			                        link.click();
			                        document.body.removeChild(link);
			                        $scope.cargador1=false;

															$scope.rehabilitacionForm={
																	tipo:'',
																	escala:'',
																	mejoria:'',
																	criterios:'',
																	observa:'',
																	duracion:'',
																	acudio:''
															}
															$http.get('api/autorizacionZima.php?funcion=getAutorizacion&aut='+$scope.noAutorizacion+'&folZima='+$scope.folioZima).success(function (data){
																$scope.cargador=false;
																console.log(data);
																	if(data.autorizacion==false){
																		$scope.autDetalle = '';
																		$scope.mensaje='No se encontró autorización';
																	}else{
																			$scope.autDetalle = data;
																			$scope.folioZima = data.autorizacion.REG_folio;
																			$scope.noAutori =parseInt(data.noRehab.contador)+1;
																	}
															});

															$("#myModal").modal('hide');

			                        //$location.path("/documentos");
			                      }else{
			                        alert('Error en la insersión');
			                      };

			                    }).error( function (xhr,status,data){
			                        $scope.mensaje ='no entra';
			                        alert('Error');
			                    });
			        }

							$scope.guardaDigital = function(){
						          $scope.cargador1=true;
						          $scope.upload = $upload.upload({
						            url:'api/digitales.php?funcion=guardaRhZima&fol='+$scope.folioZima+'&usr='+$rootScope.usrLogin+'&inter='+$scope.rx.inter,
						            method:'POST',
						            data:$scope.rx,
						            file: $scope.archivo
						          }).success( function (data, status, headers, config){
						            console.log(data);
						            $scope.cargador1=false;
						            $scope.msjerror=false;
						            if(!data.respuesta){
						            $scope.listaDigitales=data;
						            $scope.rx={
						              archivo:'',
						              temporal:'',
						              noPlac:1
						            };
												//$("#mySoportes").modal('hide');
						          }else if(data.respuesta=='error'){
						            $scope.msjerror=true;
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
						           console.log(cont+'--'+tipo)
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

						$scope.verRehabilitaciones = function(){
					    	$('#listadoModal').modal();
					    	console.log('entro');
					    }
});
