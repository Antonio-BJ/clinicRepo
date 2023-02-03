app.controller('promocionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;
	//$rootScope.rutaPro=	$cookies.rutaImgPro;

  var date = new Date();
  var mes ='';
  var dia ='';
  if(date.getMonth()>=10){
    mes=parseInt(date.getMonth())+1;
    
  }else{
    mes=parseInt(date.getMonth())+1;
    mes='0'+mes;
  }

  if(date.getDate()>=10){
    dia=parseInt(date.getDate());
    
  }else{
    dia=parseInt(date.getDate());
    dia='0'+dia;
  }
  var primerDia = date.getFullYear()+'-'+mes+'-'+'01';
  var hoy = date.getFullYear()+'-'+mes+'-'+dia;
  $scope.datos ={
    fechaIni : primerDia,
    fechaFin : hoy
  }
  
  $scope.item={
    item:'',
    descripcion:'',
    precio:''
  }

  $scope.promocion = {
    nombre: '',
    descripcion: '',
    fechaIni : primerDia,
    fechaFin : hoy,
    descuento: 0

  }
  $scope.$msjItem = '';
  $scope.desc =1;

  $scope.sumaItems =[]; 

  $scope.interacted = function(field) {          
    return $scope.avisosRH.$submitted && field.$invalid;          
  };
$scope.cargador1=false;       

  $scope.guardarPromocion = function(){ 

    $scope.todo= [$scope.promocion,$scope.sumaItems];

    if($scope.sumaItems!=''){
        $http({
            url:'api/particulares.php?funcion=crearPromocion&&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.todo
          }).success( function (data){ 
              console.log(data);
          }).error( function (xhr,status,data){
              $scope.mensaje ='no entra';            
              alert('Error');
          });
        $scope.msjItem = '';
    }else{
        $scope.msjItem = 'Debes agregar por lo menos un Item';
    }
    
  }



    $scope.irDocumentos = function(){         
        $location.path("/documentos");          
  }

  var cont=1;
  $scope.sumaItem = function(){ 
    $scope.cont ={} ; 
    $scope.cont.cont= cont;       
    $scope.cont.item = $scope.item.item;
    $scope.cont.descripcion = $scope.item.descripcion;
    $scope.cont.precio = $scope.item.precio;
    $scope.sumaItems.push($scope.cont);

    console.log($scope.sumaItems);
    cont++; 
  }
 
});