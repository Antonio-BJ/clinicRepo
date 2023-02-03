app.controller('recibosCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
  
  $http({url:'api/recibos-i.php?funcion=buscarecibos', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (resp){
    $scope.listadoR=resp;
    var carga=document.getElementById('cargador');
    carga.style.display = 'none';                            
    console.log(resp);
  
});

  $http({url:'api/recibos-i.php?funcion=buscamedicos', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (res){
    $scope.listaMedicos=res;
    console.log(res)
  });

$scope.consulta={
  recibo: '',
  fecha: '',
  folio: '',
  medico: ''
}

$scope.buscaRecibo= function(){
    var fecha= document.getElementById("fecha").value;
    console.log($scope.consulta);
    
    $http({
        url:'api/recibos-i.php?funcion=buscarecibos1&fecha='+fecha+'',
        method:'POST', 
        contentType: 'application/json', 
        dataType: "json", 
        data: $scope.consulta
        }).success( function (data){                                                      
          console.log(data);
          if(data==''||data.respuesta==''){
            $scope.listadoReporte=' ';
            alert('Sin registros');
          }else{
            $scope.listadoReporte=data;
          }
    }).error( function (xhr,status,data){
        $scope.mensaje ='no entra';            
        alert('Error');
    });
                          
}
/*-------------------------------- EXCEL --------------------------------*/
$scope.excelReportes = function(){  
  $scope.cargaRep=true;
  //console.log($scope.reporte.unidad);
      var fileName = 'Reporte';
      var uri = 'api/classes/xlsRepVentasSinReg.php?funcion=excelReportes&uni='+$scope.reporte.unidad+
                '&fechaIni='+$scope.reporte.fechaIni+'&fechaFin='+$scope.reporte.fechaFin;
      var link = document.createElement("a");    
      link.href = uri;
      
      //set the visibility hidden so it will not effect on your web-layout
      link.style = "visibility:hidden";
      link.download = fileName + ".xlsx";
      
      //this part will append the anchor tag and remove it after automatic click
      document.body.appendChild(link);
      link.click();           
      document.body.removeChild(link);
      $scope.cargaRep=false;
  }


/*-------------------------------- PDF --------------------------------*/
$scope.pdfReportes = function(){  
  $scope.cargaRep=true;
  //console.log($scope.reporte.unidad);         
      var fileName = 'Reporte';
      var uri = 'api/classes/pdfRepVentasSinReg.php?funcion=pdfReportes&uni='+$scope.reporte.unidad+
                '&cia='+$scope.reporte.compania+
                '&fechaIni='+$scope.reporte.fechaIni+'&fechaFin='+$scope.reporte.fechaFin;
      var link = document.createElement("a");    
      link.href = uri;
      
      //set the visibility hidden so it will not effect on your web-layout
      link.style = "visibility:hidden";
      link.download = fileName + ".pdf";
      
      //this part will append the anchor tag and remove it after automatic click
      document.body.appendChild(link);
      link.click();           
      document.body.removeChild(link);
      $scope.cargaRep=false;
  }

  $scope.reimprimirRecibo = function(folio, folioRecibo,serie){          
    var fileName = "Reporte";
    console.log(folio+" "+ folioRecibo+" "+ serie)
    var uri = 'api/classes/reimprimirReciboSinReg.php?fol='+folio+'&cveRec='+folioRecibo+'&serie='+serie;
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
});