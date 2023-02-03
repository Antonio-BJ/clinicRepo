app.controller('capacitacionCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http,$upload) {
	//$rootScope.rutaAse=	$cookies.rutaImgCom; 
	$rootScope.folio= 	$cookies.folio;
  $rootScope.usrLogin= $cookies.usrLogin;



  $scope.documentos = [
        {ruta:'14',nombre:'ATENCIÓN Y BENEFICIOS DE PACIENTES PARTICULARES - INFORMACIÓN PARA MÉDICOS Y DOCTORES - VIDEO 1',ubicacion:'videos/medicos1.mp4'},
        {ruta:'15',nombre:'ATENCIÓN Y BENEFICIOS DE PACIENTES PARTICULARES - INFORMACIÓN PARA MÉDICOS Y DOCTORES - VIDEO 2',ubicacion:'videos/medicos2.mp4'},
        {ruta:'16',nombre:'ATENCIÓN Y BENEFICIOS DE PACIENTES PARTICULARES - INFORMACIÓN PARA MÉDICOS Y DOCTORES - VIDEO 3',ubicacion:'videos/medicos3.mp4'},
        {ruta:'17',nombre:'ATENCIÓN Y BENEFICIOS DE PACIENTES PARTICULARES - INFORMACIÓN PARA MÉDICOS Y DOCTORES - VIDEO 4',ubicacion:'videos/medicos4.mp4'},
        {ruta:'18',nombre:'ATENCIÓN Y BENEFICIOS DE PACIENTES PARTICULARES - INFORMACIÓN PARA MÉDICOS Y DOCTORES - VIDEO 5',ubicacion:'videos/medicos5.mp4'},
        
      {ruta:'0',nombre:'EQUIPO DE PROTECCIÓN PERSONAL Y RADIOGRAFÍA DE TORAX',ubicacion:'videos\particulares/rxTorax.mp4'},
      {ruta:'1',nombre:'CAPITULO I. OBJETIVO',ubicacion:'videos\particulares/capitulo1.mp4'},
      {ruta:'2',nombre:'CAPITULO II.  QUÉ ES UN PACIENTE PARTICULAR Y CÚAL ES LA DIFERENCIA',ubicacion:'videos/particulares/capitulo2.mp4'},
      {ruta:'3',nombre:'CAPITULO III.  BIENVENIDA A MÉDICAVIAL',ubicacion:'videos/particulares/capitulo3.mp4'},
      {ruta:'4',nombre:'CAPITULO IV. TIPOS DE PACIENTES PARTICULARES',ubicacion:'videos/particulares/capitulo4.mp4'},
      {ruta:'5',nombre:'CAPITULO V. REGISTRO DE PACIENTE CON MEMBRESÍA Y SIN MEMBRESÍA',ubicacion:'videos/particulares/capitulo5.mp4'},
      {ruta:'6',nombre:'CAPITULO VI. CÓMO ELABORAR UNA MEMEBRESÍA',ubicacion:'videos/particulares/capitulo6.mp4'},
      {ruta:'7',nombre:'CAPITULO VII. ELABORACIÓN DE UN RECIBO',ubicacion:'videos/particulares/capitulo7.mp4'},
      {ruta:'8',nombre:'CAPITULO VIII. FIN DE CONSULTA Y CUESTIONARIO DE ATENCIÓN',ubicacion:'videos/particulares/capitulo8.mp4'},
      {ruta:'9',nombre:'CAPITULO IX. VENTA SIN REGISTRO CON MEMBRESÍA Y SIN MEMBRESÍA',ubicacion:'videos/particulares/capitulo9.mp4'},     
      {ruta:'10',nombre:'CAPITULO X. REGISTRO DE PACIENTE CON PROMOCIÓN Y ELABORACIÓN DE RECIBO',ubicacion:'videos/particulares/capitulo10.mp4'},     
      {ruta:'11',nombre:'CAPITULO XI. INCENTIVOS ECONÓMICOS \n (Fe de erratas columna promotor)',ubicacion:'videos/particulares/capitulo11.mp4'},     
      {ruta:'12',nombre:'CAPITULO XII. PROTOCOLO DE ATENCIÓN DE PACIENTES EN AMBULANCIA',ubicacion:'videos/particulares/capitulo12.mp4'},
      {ruta:'13',nombre:'CAPITULO XIII. ESPALDAMED',ubicacion:'videos/particulares/capitulo13.mp4'},     
  ];



  $scope.verVideo = function(nombre,ruta){
    $scope.nombre='';
    $rootScope.ruta='';    
         $('#Video').modal('show');     
         $scope.nombre=nombre;
         $scope.video=ruta;
         console.log(ruta);
        switch(ruta){
            case '0':
            $rootScope.ruta='views/videosParticulares/0.html';
           
            break;
            case '1':
            $rootScope.ruta='views/videosParticulares/1.html';
           
            break;
            case '2':
            $rootScope.ruta='views/videosParticulares/2.html';
       
            break;
            case '3':
            $rootScope.ruta='views/videosParticulares/3.html';
 
            break;
            case '4':
            $rootScope.ruta='views/videosParticulares/4.html';
  
            break;
            case '5':
            $rootScope.ruta='views/videosParticulares/5.html';
    
            break;
            case '6':
            $rootScope.ruta='views/videosParticulares/6.html';

            break;
            case '7':
            $rootScope.ruta='views/videosParticulares/7.html';

            break;
            case '8':
            $rootScope.ruta='views/videosParticulares/8.html';
 
            break;
            case '9':
            $rootScope.ruta='views/videosParticulares/9.html';

            break;
            case '10':
            $rootScope.ruta='views/videosParticulares/10.html';

            break;
            case '11':
            $rootScope.ruta='views/videosParticulares/11.html';
            break;

            case '12':
            $rootScope.ruta='views/videosParticulares/12.html';
            break;

            case '13':
                $rootScope.ruta='views/videosParticulares/13.html';
            break;

            case '14':
                $rootScope.ruta='views/videos/28.html';
            break;
            case '15':
                $rootScope.ruta='views/videos/29.html';
            break;
            case '16':
                $rootScope.ruta='views/videos/30.html';
            break;
            case '17':
                $rootScope.ruta='views/videos/31.html';
            break;
            case '18':
                $rootScope.ruta='views/videos/32.html';
            break;

        
            
        }
    }
	
});