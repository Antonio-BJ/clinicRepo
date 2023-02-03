app.controller('reciboRentaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
    
    $scope.sweet = {}
        
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var f=new Date(); 

    // $scope.unidad = webStorage.session.get('unidad'); 
    $scope.unidad=  $cookies.uniClave;  
    console.log($scope.unidad);    
    $scope.listado = [];
    $scope.folios = [];
    $scope.mensaje = '';
    var anioActual=f.getFullYear();
    $scope.anios = [
        {id:anioActual, nombre:anioActual},
        {id:anioActual+1, nombre:anioActual+1},
        {id:anioActual+2, nombre:anioActual+2},
        {id:anioActual+3, nombre:anioActual+3},
        {id:anioActual+4, nombre:anioActual+4},
        {id:anioActual+5, nombre:anioActual+5},
        {id:anioActual+6, nombre:anioActual+6},
        {id:anioActual+7, nombre:anioActual+7},
        {id:anioActual+8, nombre:anioActual+8},
        {id:anioActual+9, nombre:anioActual+9},
        {id:anioActual+10, nombre:anioActual+10}
    ]
    $scope.listadoRenta = ''; 
    $scope.meses = [
        {id:1,nombre:"Enero"},
        {id:2,nombre:"Febrero"},
        {id:3,nombre:"Marzo"},
        {id:4,nombre:"Abril"},
        {id:5,nombre:"Mayo"},
        {id:6,nombre:"Junio"},
        {id:7,nombre:"Julio"},
        {id:8,nombre:"Agosto"},
        {id:9,nombre:"Septiembre"},
        {id:10,nombre:"Octubre"},
        {id:11,nombre:"Noviembre"},
        {id:12,nombre:"Diciembre"}
    ];

    $scope.Buscar = function(){
        $scope.listadoRenta = '';
        $scope.cargador = true;
    
            $http({
                url:'api/api.php?funcion=buscarRenta',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.datos
            }).success( function (data){
                 $scope.cargador = false;
                 console.log(data);
                 console.log(data.length);
                if(data.length==0){
                    $scope.mensaje='No existen acuses digitalizados';
                }else{
                    $scope.listadoAcuses = data;
                    $scope.mensaje='';
                }                               
                
            }).error( function (xhr,status,data){
                $scope.cargador = false;
                $('#boton').button('reset');
                alert('Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina');
                $scope.listadoAcuses = '';
                $scope.mensaje='';

            });        
    }

    $rootScope.usrLogin= $cookies.usrLogin;
    $scope.cargador=false;  
    //$rootScope.rutaPro=   $cookies.rutaImgPro;    
    $scope.digital={
        archivo:'',
        temporal:'',
        mes:'',
        anio:''
    };

    $scope.archivo='';
    $scope.msjerror=false;
    $scope.contador=1;
    $scope.msj='';

    busquedas.rentas($rootScope.usrLogin).success(function(data){           
        console.log(data);
        if(data==''){
            $scope.listaRenta='';    
        }else{
            $scope.listaRenta=data;  
        }

        $scope.cargador=false;  
    });

    $scope.onFileSelect_xml = function($files) {
       for (var i = 0; i < $files.length; i++) {
            var file = $files[i];
            $scope.archivo=file;
            $scope.variable = 2;
            var amt = 0;
            //$files: an array of files selected, each file has name, size, and type.
            $scope.upload = $upload.upload({
                url: 'api/api.php?funcion=archivo_temporal', //upload.php script, node.js route, or servlet url
                method: 'POST',
                headers: {'header-key': 'header-value'},
                withCredentials: true,
                data: $scope.archivo,
                file: file, // or list of files ($files) for html5 only

                progress:function(evt) {
                    var amt =  parseInt(100.0 * evt.loaded / evt.total);
                    $scope.countTo = amt;
                    $scope.countFrom = 0;
                }
            })
            .success(function (data, status, headers, config){ 
                console.log(data); 
                if(data.error=='si'){
                  scope.msjerror=true;
                    $scope.msj="Hay un error con el archivo, favor de verificarlo";
                }else{
                  $scope.digital.archivo=data.nombre;
                  $scope.digital.temporal=data.temporal;  
                }                              
                //console.log($scope.digital.archivo+'--'+$scope.digital.temporal);           
            }).error( function (xhr,status,data){
                alertService.add('danger', 'Ocurrio un ERROR con tu Archivo!!!');
            });
        }
    }

    $scope.guardaDigital = function(){
        $scope.cargador=true;          
        $scope.upload = $upload.upload({
            url:'api/api.php?funcion=guardaReciboDigital&anio='+$scope.digital.anio+'&mes='+$scope.digital.mes+'&usr='+$rootScope.usrLogin,
            method:'POST',             
            data:$scope.digital,
            file: $scope.archivo
        }).success( function (data, status, headers, config){             
            $scope.contador=1;
            $scope.cargador=false;          
            $scope.msjerror=false; 
                console.log(data);
                $scope.archivo='';  
            if(!data.respuesta){       
                $scope.listaRenta=data;
                $scope.digital={
                    archivo:'',
                    temporal:'',
                    mes:'',
                    anio:''
                };
            }else if(data.respuesta=='errorType'){
                $scope.msjerror=true;
                $scope.msj="El tipo de archivo no es valido, utiliza una imagen, pdf, word o excel";
            }else if(data.respuesta=='errorSize'){
                $scope.msjerror=true;
                $scope.msj="El tamaño del archivo no es valido, el máximo permitido es de 1MB";
            }else{
                $scope.msjerror=true;
                $scope.msj="Hay un error con el archivo, favor de verificarlo";
            }
        }).error( function (xhr,status,data){            
            $scope.cargador=false;          
            $scope.mensaje ='no entra';            
            alert('Error');
        });                               
    }

    $scope.subirArchivo = function(){         
        $("#myModal").modal('hide');
        $scope.contador=2;
        $scope.guardaDigital();
    }
});

app.controller('listadoReciboRentaCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
    
    $scope.sweet = {}
        
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var f=new Date(); 

    // $scope.unidad = webStorage.session.get('unidad'); 
    $scope.unidad=  $cookies.uniClave;  
        
    $scope.listado = [];
    $scope.folios = [];
    $scope.mensaje = '';
    var anioActual=f.getFullYear();
    $scope.anios2 = [
        {id:anioActual, nombre:anioActual},
        {id:anioActual+1, nombre:anioActual+1},
        {id:anioActual+2, nombre:anioActual+2},
        {id:anioActual+3, nombre:anioActual+3},
        {id:anioActual+4, nombre:anioActual+4},
        {id:anioActual+5, nombre:anioActual+5},
        {id:anioActual+6, nombre:anioActual+6},
        {id:anioActual+7, nombre:anioActual+7},
        {id:anioActual+8, nombre:anioActual+8},
        {id:anioActual+9, nombre:anioActual+9},
        {id:anioActual+10, nombre:anioActual+10}
    ]
    $scope.listadoRenta2 = ''; 
    $scope.meses2 = [
        {id:1,nombre:"Enero"},
        {id:2,nombre:"Febrero"},
        {id:3,nombre:"Marzo"},
        {id:4,nombre:"Abril"},
        {id:5,nombre:"Mayo"},
        {id:6,nombre:"Junio"},
        {id:7,nombre:"Julio"},
        {id:8,nombre:"Agosto"},
        {id:9,nombre:"Septiembre"},
        {id:10,nombre:"Octubre"},
        {id:11,nombre:"Noviembre"},
        {id:12,nombre:"Diciembre"}
    ];

    $scope.busqueda={
        medico:'',
        mes:'',
        anio:''
    };

    $scope.Buscar = function(){
        $scope.cargador = true;
    
            $http({
                url:'api/api.php?funcion=rentas',
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.busqueda
            }).success( function (data){
                console.log(data);
                 $scope.cargador = false;
                if(data.length==0){
                    $scope.mensaje='No se encontraron resultados';
                }else{
                    $scope.listaRenta2 = data;
                    $scope.mensaje='';
                }

                $scope.busqueda={
                    medico:'',
                    mes:'',
                    anio:''
                };
                
            }).error( function (xhr,status,data){
                $scope.cargador = false;
                $('#boton').button('reset');
                alert('Existe Un Problema de Conexion Intente Cargar Nuevamente la Pagina');
                $scope.listaRenta2 = '';
                $scope.mensaje='';

            });
    }

    busquedas.rentas(0).success(function(data){
        console.log(data);
        if(data==''){
            $scope.listaRenta2='';    
        }else{
            $scope.listaRenta2=data;  
        }

        $scope.cargador=false;  
    });
});