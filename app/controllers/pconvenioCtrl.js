app.controller('pconvenioCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,webStorage) {
  $rootScope.folio    = $cookies.folio;
  $rootScope.usrLogin = $cookies.usrLogin;
  $rootScope.uniClave = $cookies.uniClave;
  
  $http({url:'api/pcovid.php?funcion=buscaunidades', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (data){
    $scope.listadoUnidades=data;
  });

  $http({url:'api/pcovid.php?funcion=buscapconvenio', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (rs){
    $scope.listadoPC=rs;
    var carga2=document.getElementById('cargadorpcc');
    carga2.style.display = 'none';
    console.log(rs)
  });

  $http({url:'api/pconvenio.php?funcion=totalconvenio', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (rsp){
    console.log(rsp);
    var titulo1c=document.getElementById('anioc');
    titulo1c.innerHTML +=` ${rsp.TOTAL}`
  });

  $http({url:'api/pconvenio.php?funcion=totalconvenio1', 
  contentType: 'application/json', 
  dataType: "json", 
  }).success(function (rsp1){
    console.log(rsp1);
    var titulo2c=document.getElementById('mesc');
    titulo2c.innerHTML +=` ${rsp1.TOTAL}`
  });

  $scope.consulta={
    unidad: '',
    fecha: '',
    fechaf: ''
  }
  
  $scope.buscapconvenio= function(){
      var fecha= document.getElementById("fecha").value;
      var fechaf= document.getElementById("fechaf").value;
       var titulo0=document.getElementById('nrc');
      console.log($scope.consulta);
      
      $http({
          url:'api/pconvenio.php?funcion=buscapconvenio1&fecha='+fecha+'&fechaf='+fechaf+'',
          method:'POST', 
          contentType: 'application/json', 
          dataType: "json", 
          data: $scope.consulta
          }).success( function (data1){
           
            console.log(data1);

            if(data1.respuesta==''){
              alert('Sin registros');
              $scope.listadoPC1=true;
              var nregistros=0;
              titulo0.innerHTML="";
              titulo0.innerHTML +=`<b>${nregistros}</b>`
            }else{
              $scope.listadoPC1=data1;
              nregistros=data1.length;
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

    var fileName = 'PACIENTES CON CONVENIO';
    var uri = 'api/classes/descargaxls.php?clv='+54+'&fecha='+fecha+'&fechaf='+fechaf+'&unidad='+$scope.consulta.unidad+'';
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