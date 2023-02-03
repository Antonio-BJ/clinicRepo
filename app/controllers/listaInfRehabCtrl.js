app.controller('listaInfRehabCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
  $rootScope.uniClave= $cookies.uniClave;
  $rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.folio= $cookies.folio;

  $scope.trabajando=true;

/* BUSCA DATOS DEL PACIENTE */
    $http({
            url:'api/infRehabilitacion.php?funcion=buscaPaciente&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            }).success( function (data){                              
            //console.log(data);
            $scope.nombre=data.Exp_completo;
            $scope.trabajando=false;
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 

/* RECUPERA EL LISTADO DE LAS NOTAS DE REHABILITACIONES DEL PACIENTE */
    $http({
            url:'api/infRehabilitacion.php?funcion=getListaNotasRehab&fol='+$rootScope.folio,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            }).success( function (data){                              
            $scope.listado=data;
            //console.log($scope.listado);
            $scope.trabajando=false;
              
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 

//$('#confirmacion').modal('show');
  $scope.imprimeNota = function(idInforme) {
        $scope.trabajando=true;
            var fileName = "Reporte";
            var uri = 'api/classes/imprimeNotaDeRehabilitacion.php?fol='+$rootScope.folio+'&nota='+idInforme;
            //console.log(uri);
            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".pdf";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();                        
            document.body.removeChild(link);
        $scope.trabajando=false;
  }

});