app.controller('pcovidCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
 
  $http({url:'api/pcovid.php?funcion=buscaunidades', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (data){
    $scope.listadoUnidades=data;
  });

  $http({url:'api/pcovid.php?funcion=totalcovid', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (rspc){
    console.log(rspc);
    var titulo1=document.getElementById('anio');
    titulo1.innerHTML +=` ${rspc.TOTAL}`
   
  });

  $http({url:'api/pcovid.php?funcion=totalcovid1', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (rspc1){
    console.log(rspc1);
    var titulo2=document.getElementById('mes');
    titulo2.innerHTML +=` ${rspc1.TOTAL}`
  });

  $http({url:'api/pcovid.php?funcion=buscapacientes', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (res){
    $scope.listadoPC=res;
    var carga1=document.getElementById('cargadorpc');
    carga1.style.display = 'none';
    console.log(res)
  
  });

  $scope.consulta={
    unidad: '',
    fecha: '',
    fechaf: '',
  }
  
  $scope.buscapacientes= function(){
      var fecha= document.getElementById("fecha").value;
      var fechaf= document.getElementById("fechaf").value;
      var titulo0=document.getElementById('nr');
      console.log($scope.consulta);
      
      $http({
          url:'api/pcovid.php?funcion=buscapacientes1&fecha='+fecha+'&fechaf='+fechaf+'',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.consulta
          }).success( function (data){
            console.log(data);
            if(data.respuesta==''){
              $scope.listadoPC1=true;
              var nregistros=0;
              titulo0.innerHTML="";
              titulo0.innerHTML +=`<b>${nregistros}</b>`
              //console.log(nregistros);
            alert('Sin registros');
            }else{
              $scope.listadoPC1=data;
              nregistros=data.length;
              //console.log(nregistros);
              titulo0.innerHTML="";
              titulo0.innerHTML +=`<b>${nregistros}</b>`
            }
      })
                            
  }

  $scope.descargar= function(){
    var fecha= document.getElementById("fecha").value;
    var fechaf= document.getElementById("fechaf").value;
    console.log($scope.consulta.unidad);
    console.log(fecha);
    console.log(fechaf);

      var fileName = 'PACIENTES COVID';
      var uri = 'api/classes/descargaxls.php?clv='+81+'&fecha='+fecha+'&fechaf='+fechaf+'&unidad='+$scope.consulta.unidad+'';
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

   
  



});