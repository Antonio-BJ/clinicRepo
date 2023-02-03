app.controller('estatusMedicoCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http) {
	
    //definimos variables guardadas en cookies
	$scope.User  =      $cookies.usrLogin;
    $scope.Unidad =      $cookies.uniClave;
    $rootScope.permisos=JSON.parse($cookies.permisos);    
    //variables de validacion
    $scope.iniTurno=false;
    $scope.iniPausa=true;
    $scope.finPausa=true;
    $scope.finTurno=true;

    $scope.cargador=false;

    //definicion de json para estatus de turno
    $scope.turno={
        estatus:''
    }
    //definicion de json para reseteo de turno
    $scope.reteteo={
        motivo:''
    }
    $scope.estatus='';
    $scope.imgEstatus='';
    $scope.fecInicioTurno = '';
    $scope.estatusId=0;
    $scope.idPausa='';
    $scope.mensajeFin=false;

    //guardado de datos para el inicio de sesion por parte de un médico
    $http.get('api/estatusMedico.php?funcion=getRegistro&usr='+$scope.User+'&uni='+$scope.Unidad).success(function (data){                                                                       
            console.log(data);
          if(data!=false){            
            $scope.estatus=data.Estatus_obsEstatus;
            $scope.fecInicioTurno=data.Estatus_inicioTurno;
            $scope.estatusId=data.Estatus_id;
            $scope.idPausa= data.pausa;
            if(data.Estatus_estatus==1||data.Estatus_estatus==3){
                $scope.imgEstatus='imgs/ok.jpg';
            }else{
                $scope.imgEstatus='imgs/no.png';
            }
            switch(data.Estatus_estatus){
                case '1':
                    $scope.iniTurno=true;
                    $scope.iniPausa=false;
                    $scope.finPausa=true;
                    $scope.finTurno=false;
                break;
                case '2':
                    $scope.iniTurno=true;
                    $scope.iniPausa=true;
                    $scope.finPausa=false;
                    $scope.finTurno=true;
                break;
                case '3':
                    $scope.iniTurno=true;
                    $scope.iniPausa=true;
                    $scope.finPausa=true;
                    $scope.finTurno=false;
                break;
                case '4':
                    $scope.iniTurno=true;
                    $scope.iniPausa=true;
                    $scope.finPausa=true;
                    $scope.finTurno=true;
                break;
            }            
          } 
    });

     //consulta los medicos disponibles
    $http.get('api/estatusMedico.php?funcion=getListado&uni='+$scope.Unidad).success(function (data){                                                                       
         console.log(data);
         if(data.length>0){
         $scope.listado = data;
         //$scope.imgEstatus ='imgs/ok.jpg';
         
         $scope.imgEstatus='imgs/ok.jpg';
         if (data.Estatus_estatus==2||data.Estatus_estatus==4) {
            imgEstatus='imgs/no.jpg'
         } else {
            imgEstatus='imgs/ok.png'
         }
     }else{ 
        $scope.listado='';
     }
    });

    $scope.guardaInicioTurno = function(){ 
        $scope.cargador=true;       
        $http({
            url:'api/estatusMedico.php?funcion=inicioTurno&usr='+$scope.User+'&uni='+$scope.Unidad,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.turno
            }).success( function (data){
               $scope.cargador=false;  
               $('#inicioTurno').modal('hide');  
               if(data!=false){
                $scope.turno.estatus='';
                $scope.estatus=data.Estatus_obsEstatus;
                $scope.fecInicioTurno=data.Estatus_inicioTurno;
                $scope.estatusId=data.Estatus_id;
                $scope.idPausa= data.pausa;
                if(data.Estatus_estatus==1||data.Estatus_estatus==3){
                    $scope.imgEstatus='imgs/ok.jpg';
                }else{
                    $scope.imgEstatus='imgs/no.png';
                }
                switch(data.Estatus_estatus){
                    case '1':
                        $scope.iniTurno=true;
                        $scope.iniPausa=false;
                        $scope.finPausa=true;
                        $scope.finTurno=false;
                    break;
                    case '2':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=false;
                        $scope.finTurno=true;
                    break;
                    case '3':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=true;
                        $scope.finTurno=false;
                    break;
                    case '4':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=true;
                        $scope.finTurno=true;
                    break;
                } 
                $http.get('api/estatusMedico.php?funcion=getListado&uni='+$scope.Unidad).success(function (data){                                                                                            
                     if(data.length>0){
                     $scope.listado = data;
                     $scope.imgEstatus='imgs/ok.jpg';
                     if (data.Estatus_estatus==2||data.Estatus_estatus==4) {
                        imgEstatus='imgs/no.jpg'
                     } else {
                        imgEstatus='imgs/ok.png'
                     }
                     }else{ 
                        $scope.listado='';
                     }
                });           
              } 
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
    }
    $scope.guardaInicioPausa = function(){ 
        $scope.cargador=true;       
        $http({
            url:'api/estatusMedico.php?funcion=inicioPausa&usr='+$scope.User+'&uni='+$scope.Unidad+'&idEstatus='+$scope.estatusId+'&idPausa='+$scope.idPausa,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.turno
            }).success( function (data){ 
               $scope.turno.estatus='';
               $scope.cargador=false;  
               $('#inicioPausa').modal('hide');  
               if(data!=false){
                console.log(data);
                $scope.estatus=data.Estatus_obsEstatus;
                $scope.fecInicioTurno=data.Estatus_inicioTurno;
                $scope.estatusId=data.Estatus_id;
                $scope.idPausa= data.pausa;
                if(data.Estatus_estatus==1||data.Estatus_estatus==3){
                    $scope.imgEstatus='imgs/ok.jpg';
                }else{
                    $scope.imgEstatus='imgs/no.png';
                }
                switch(data.Estatus_estatus){
                    case '1':
                        $scope.iniTurno=true;
                        $scope.iniPausa=false;
                        $scope.finPausa=true;
                        $scope.finTurno=false;
                    break;
                    case '2':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=false;
                        $scope.finTurno=true;
                    break;
                    case '3':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=true;
                        $scope.finTurno=false;
                    break;
                    case '4':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=true;
                        $scope.finTurno=true;
                    break;
                } 
                $http.get('api/estatusMedico.php?funcion=getListado&uni='+$scope.Unidad).success(function (data){                                                                                            
                     if(data.length>0){
                     $scope.listado = data;
                     $scope.imgEstatus='imgs/ok.jpg';
                     if (data.Estatus_estatus==2||data.Estatus_estatus==4) {
                        imgEstatus='imgs/no.jpg'
                     } else {
                        imgEstatus='imgs/ok.png'
                     }
                     }else{ 
                        $scope.listado='';
                     }
                });           
              }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
    }
    $scope.guardaFinPausa = function(){ 
        $scope.cargador=true;       
        $http({
            url:'api/estatusMedico.php?funcion=finPausa&usr='+$scope.User+'&uni='+$scope.Unidad+'&idEstatus='+$scope.estatusId+'&idPausa='+$scope.idPausa,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.turno
            }).success( function (data){ 
               $scope.turno.estatus='';
               $scope.cargador=false;  
               $('#finPausa').modal('hide');  
               if(data!=false){
                console.log(data);
                $scope.estatus=data.Estatus_obsEstatus;
                $scope.fecInicioTurno=data.Estatus_inicioTurno;
                $scope.estatusId=data.Estatus_id;
                $scope.idPausa= data.pausa;
                if(data.Estatus_estatus==1||data.Estatus_estatus==3){
                    $scope.imgEstatus='imgs/ok.jpg';
                }else{
                    $scope.imgEstatus='imgs/no.png';
                }
                switch(data.Estatus_estatus){
                    case '1':
                        $scope.iniTurno=true;
                        $scope.iniPausa=false;
                        $scope.finPausa=true;
                        $scope.finTurno=false;
                    break;
                    case '2':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=false;
                        $scope.finTurno=true;
                    break;
                    case '3':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=true;
                        $scope.finTurno=false;
                    break;
                    case '4':
                        $scope.iniTurno=true;
                        $scope.iniPausa=true;
                        $scope.finPausa=true;
                        $scope.finTurno=true;
                    break;
                }  
                $http.get('api/estatusMedico.php?funcion=getListado&uni='+$scope.Unidad).success(function (data){                                                                                            
                     if(data.length>0){
                     $scope.listado = data;
                     $scope.imgEstatus='imgs/ok.jpg';
                     if (data.Estatus_estatus==2||data.Estatus_estatus==4) {
                        imgEstatus='imgs/no.jpg'
                     } else {
                        imgEstatus='imgs/ok.png'
                     }
                     }else{ 
                        $scope.listado='';
                     }
                });          
              }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
    }
    $scope.guardaFinTurno = function(){ 
       $scope.cargador=true;       
        $http({
            url:'api/estatusMedico.php?funcion=finTurno&usr='+$scope.User+'&uni='+$scope.Unidad+'&idEstatus='+$scope.estatusId+'&idPausa='+$scope.idPausa,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.turno
            }).success( function (data){ 
               $scope.turno.estatus='';
               $scope.cargador=false;  
               $('#finTurno').modal('hide');  
                $scope.mensajeFin=true;                
                $scope.estatus='';
                $scope.iniTurno=false;
                $scope.iniPausa=true;
                $scope.finPausa=true;
                $scope.finTurno=true;

                $http.get('api/estatusMedico.php?funcion=getListado&uni='+$scope.Unidad).success(function (data){                                                                                            
                     if(data.length>0){
                     $scope.listado = data;
                     $scope.imgEstatus='imgs/ok.jpg';
                     if (data.Estatus_estatus==2||data.Estatus_estatus==4) {
                        imgEstatus='imgs/no.jpg'
                     } else {
                        imgEstatus='imgs/ok.png'
                     }
                     }else{ 
                        $scope.listado='';
                     }
                });
                
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
        }
    $scope.enviaReseteoTurno = function(){ 
       $scope.cargador=true;       
        $http({
            url:'api/estatusMedico.php?funcion=enviaReseteo&usr='+$scope.User+'&uni='+$scope.Unidad+'&idEstatus='+$scope.estatusId+'&idPausa='+$scope.idPausa,
            method:'POST', 
            contentType: 'application/json', 
            dataType: "json", 
            data: $scope.reseteo
            }).success( function (data){ 
               $scope.turno.estatus='';
               $scope.cargador=false;  
               $('#resetearRegistro').modal('hide');  
                $scope.mensajeFin=true;                
                $scope.estatus='';
                $scope.iniTurno=false;
                $scope.iniPausa=true;
                $scope.finPausa=true;
                $scope.finTurno=true;
                
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            });
        }
});