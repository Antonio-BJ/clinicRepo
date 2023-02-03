app.controller('documentosCtrl', function($scope,$rootScope,$location,$cookies,busquedas,$http) {
	$rootScope.folio=$cookies.folio;
	$rootScope.usrLogin= $cookies.usrLogin;
  $rootScope.uniClave=$cookies.uniClave;
	$rootScope.permisos=JSON.parse($cookies.permisos);    
	$scope.subsecuenciaVal=true;
    $scope.paseReha=false;
    $scope.esParticular=false;
    $scope.esAba=false;
    $scope.esAxa=false;
    $scope.ProSegNuevo=true
    $scope.cargador=true; 
    $scope.noSubsec=''; 
    $scope.soloReha=true; 
    $scope.veEditaNota=true;
    $scope.valida1=false;
    $scope.valida2=false;
    $scope.valida3=false;
    $scope.valida4=false;
    $scope.valida5=false;
    $scope.valida6=false;
    $scope.valida7=false;
    $scope.valida8=false;
    $scope.validaHistoria=false;
    $scope.string = ''; 
    $scope.tipoDoc=''; 
    $scope.fecha='';
    $scope.divSubsecuencia=false;
    $scope.cia=0;
    $scope.datos={
        folio: $rootScope.folio
    }
    $scope.validaProducto=true;
    $scope.addendum={
        cuerpo:'',
        doc:'',
        noSub:'',
        usr:''
    }
    $scope.alta={
      tipo:'',
      observaciones:''
    }
    $scope.tipDocAddem=0; 
    $scope.mensajeExito=false;

    $scope.detalle={
      nombre:'',
      sexo:'',
      edad:'',
      producto:'',
      fechaReg:'',
      aseguradora:'',
      telefono:'',
      mail:'',
      ocupacion:'',
      religion:''
    } 
    $scope.solicitudCE={
      motivo:'',
      tiempo:''
    }  
    $scope.solicutudValida=''; 
    $scope.verDocumentosCE=''; 
    $scope.reciboDeducible=false;
    $scope.validaFacturaVal='';

    $scope.existeSolCE=false;
    $scope.existeAutCE=true;

    $scope.verCrearMemebresia=false;
    $scope.btonGenerarMem=true;
    $scope.interacted = function(field) {
      //$dirty es una propiedad de formulario que detecta cuando se esta escribieno algo en el input
      return $scope.agreAddendum.$submitted && field.$invalid;          
    };

    $http.get('api/cortaEstancia.php?funcion=getfolioCE&fol='+$rootScope.folio).success(function (data){   
        if(data.Exp_folio){
          $scope.existeSolCE=true;
          if(data.CE_codUsado!=''){
            $scope.existeAutCE=false;
          }
        }
    });

    $http.get('api/catalogos.php?funcion=avisosCoordinacion&fol='+$rootScope.folio).success(function (data){     
  
    if(!data.respuesta){
      $scope.listadoComentarios=data;
        $('#comentarioExpediente').modal('show');   
    }
	busquedas.validaNota($rootScope.folio).success(function(data){ 		
        $scope.cargador=false;
		if(data.respuesta=='existe'){
			$scope.subsecuenciaVal=false;
		}                     
		else{
             busquedas.validaParticular($rootScope.folio).success(function(data){                                        
              console.log(data.Pro_clave);
                $scope.proClave=data.Pro_clave;
                if(data.Pro_clave==8){
                    $scope.validaProducto=false;
                    $scope.soloReha=false;
                }else{
                    $scope.validaProducto=true;
                }
                if(data.Pro_clave==5 || data.Pro_clave==4 || data.Pro_clave==13){
                    $scope.soloReha=false;
                }else{
                    $scope.soloReha=true;
                }
                if(data.Pro_clave==2){
                  $scope.reciboDeducible=true;
                }else{
                  $scope.reciboDeducible=false;
                }          
            });
			
		} 		          
    });
    busquedas.validaPaseM($rootScope.folio).success(function(data){             
        if(data.respuesta=='existe'){
            $scope.paseReha=true;
        }                     
        else{
            $scope.paseReha=false;
        }                 
    });

    $http.get('api/catalogos.php?funcion=getMembresia&fol='+$rootScope.folio).success(function (data){     
          if(data==0){
            $scope.verCrearMemebresia=true;
          }else{
            $scope.verCrearMemebresia=false;
          }
    });

    
    busquedas.validaParticular($rootScope.folio).success(function(data){               
        if(data.Cia_clave==51||data.Cia_clave==44||data.Cia_clave==54){
            $scope.esParticular=true;
        }else if(data.Cia_clave==1){
            $scope.esAba=true;
        }else if(data.Cia_clave==7||data.Cia_clave==4||data.Cia_clave==19){
            $scope.esAxa=true;
        }else if(data.Cia_clave==54){

        }              
    });

     busquedas.validaHistoriaClinica($rootScope.folio).success(function(data){  
        console.log(data);                
         if(data!='false'){
            $scope.validaHistoria=true;
         }else{
            $scope.validaHistoria=false;
         }
    });

 
    
  });
    busquedas.estatusNota($rootScope.folio).success(function(data){         
        if(data=="12"){
            $scope.veEditaNota=false;
            busquedas.listaDatosPacRec($rootScope.folio).success(function(data){                      
                    
                    $scope.datos.lesionado=data.Exp_completo;
                    $scope.datos.sexo=data.Exp_sexo;
                    $scope.datos.edad=data.Exp_edad;
                    $scope.datos.talla=data.Vit_talla;
                    $scope.datos.peso=data.Vit_peso;
                    $scope.datos.temperatura=data.Vit_temperatura;
                    $scope.datos.presion=data.Vit_ta;
                    $scope.string = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';
                    $scope.datos.qr = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';                    
                    $scope.datos.cadena = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';                                        
                    $scope.datos.receta= $scope.datos.folio;                    
                    $scope.valida1=true;

                  });
                  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){          
                      $scope.datos.alergias = data;
                      $scope.valida2=true;
                  });
                  $http.get('api/api.php?funcion=datosDoc&usr='+$rootScope.usrLogin).success(function (data){
                                           
                      $scope.datos.doctor = data.Med_nombre+' '+data.Med_paterno+' '+data.Med_materno;
                      $scope.datos.cedula = data.Med_cedula;
                      $scope.datos.especialidad = data.Med_esp;
                      $scope.datos.telefonos = data.Med_telefono;
                      $scope.valida3=true;   
                  });

                  $http.get('api/api.php?funcion=datosUni&uni='+$rootScope.uniClave).success(function (data){
                      $scope.datos.direccionUni  = data.Uni_calleNum+', '+data.Uni_colMun;
                      $scope.datos.telUni = data.Uni_tel; 
                      $scope.valida7=true;                                         
                  });

                  $http.get('api/api.php?funcion=generaFolio&fol='+$rootScope.folio).success(function (data){                      
                      $scope.valida8=true; 
                  });
                  $http.get('api/api.php?funcion=datosMedicamentoRec&fol='+$rootScope.folio).success(function (data){                                        
                      
                      $scope.datos.medicamentos=data;                    
                      $scope.valida4=true;

                  });
                  $http.get('api/api.php?funcion=datosOrtRec&fol='+$rootScope.folio).success(function (data){                                        
                      
                      $scope.datos.ortesis=data;                    
                      $scope.valida5=true;

                  });
                  $http.get('api/api.php?funcion=datosIndicacionesRec&fol='+$rootScope.folio).success(function (data){                                                              
                      $scope.datos.indicaciones=data;  
                      $scope.cargador1=false;              
                      $scope.valida6=true;

                  });                                      
        }
    });
	$scope.irHistoriaClinica = function(folio){  
		$cookies.folio = folio;
        $location.path("/historiaClinica");
	}
	$scope.irSignosVitales = function(folio){  
		$cookies.folio = folio;
        $location.path("/signosVitales");
	}
    $scope.irSignosVitalesSub = function(folio){  
        $cookies.folio = folio;
        $location.path("/signosVitalesSub");
    }
	$scope.irNotaMedica = function(folio){  
		$cookies.folio = folio;
        $location.path("/notaMedica");
	}
	$scope.irSubsecuencia = function(folio){  		
		$cookies.folio = folio;
        $location.path("/subsecuencia");
	}
    $scope.irSubsecuenciaListado = function(folio){        
        $cookies.folio = folio;
        $location.path("/subsecuenciaListado");
    }
    $scope.irSolRehab = function(folio){          
        $cookies.folio = folio;
        $location.path("/rehabilitacion");
    }
     $scope.irSolRehabForm = function(folio){          
        $cookies.folio = folio;
        $location.path("/rehabilitacionForm");
    }
	$scope.irSolicitud = function(folio){  
		$cookies.folio = folio;
        $location.path("/solicitud/"+$rootScope.folio);
	}
    $scope.irReciboPar = function(folio){  
        $cookies.folio = folio;
        $location.path("/reciboParticular");
    }
    $scope.irSolFact = function(folio){  
        $cookies.folio = folio;
        $location.path("/solicitudFactura");
    }
    $scope.irDigitalizacion = function(folio){  
        $cookies.folio = folio;
        $location.path("/digitalizacion");
    }
     $scope.irCambioUnidad = function(folio){  
        $cookies.folio = folio;
        $location.path("/cambioUnidad");
    }
     $scope.irConsatancia = function(folio){  
        $cookies.folio = folio;
        $location.path("/constancia");
    }

	$scope.imprimirNota = function(folio){ 
        	$scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoNota.php?fol='+folio;
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
      $scope.imprimirRecetaNuevo = function(folio){                
            var fileName = 'Receta';
            var uri = 'api/classes/formatoRecetaNuevo.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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
    $scope.imprimirReceta = function(folio){
        	/*$rootScope.cargador=true;          
            var fileName = "Reporte";
            var uri = 'api/classes/formatoReceta.php?fol='+folio+'&usr='+$rootScope.usrLogin;
            var link = document.createElement("a");    
            link.href = uri;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".pdf";
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();
            $rootScope.cargador=false;
            document.body.removeChild(link);*/
            busquedas.listaDatosPacRec($rootScope.folio).success(function(data){                      
                    
                    $scope.datos.lesionado=data.Exp_completo;
                    $scope.datos.sexo=data.Exp_sexo;
                    $scope.datos.edad=data.Exp_edad;
                    $scope.datos.talla=data.Vit_talla;
                    $scope.datos.peso=data.Vit_peso;
                    $scope.datos.temperatura=data.Vit_temperatura;
                    $scope.datos.presion=data.Vit_ta;
                    $scope.string = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';
                    $scope.datos.qr = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';                    
                    $scope.datos.cadena = String(dd) + String(mm) + String(yyyy) +'||'+'900001'+'||algo||' + $scope.datos.folio + '||654+5456';                                        
                    $scope.datos.receta= $scope.datos.folio;                    

                  });
                  $http.get('api/api.php?funcion=listaAlergiasRec&fol='+$rootScope.folio).success(function (data){          
                      $scope.datos.alergias = data;
                  });
                  $http.get('api/api.php?funcion=datosDoc&usr='+$rootScope.usrLogin).success(function (data){
                                           
                      $scope.datos.doctor = data.Med_nombre+' '+data.Med_paterno+' '+data.Med_materno;
                      $scope.datos.cedula = data.Med_cedula;
                      $scope.datos.especialidad = data.Med_esp;
                      $scope.datos.telefonos = data.Med_telefono;

                  });
                  $http.get('api/api.php?funcion=datosMedicamentoRec&fol='+$rootScope.folio).success(function (data){                                        
                      
                      $scope.datos.medicamentos=data;                    

                  });
                  $http.get('api/api.php?funcion=datosOrtRec&fol='+$rootScope.folio).success(function (data){                                        
                      
                      $scope.datos.ortesis=data;                    

                  });
                  $http.get('api/api.php?funcion=datosIndicacionesRec&fol='+$rootScope.folio).success(function (data){                                                              
                      $scope.datos.indicaciones=data;  
                      $scope.cargador1=false;              

                  });
                    $http.get('api/api.php?funcion=guardaEstatusNota&fol='+$rootScope.folio+'&estatus=12').success(function (data){                                
                    });                    

        }

        $scope.imprimirRehabilitacion = function(){
        var fileName = "Reporte";
        var uri = 'api/classes/formatoRehabilitacion.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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
      $scope.imprimirRehabilitacion = function(){
        var fileName = "Reporte";
        var uri = 'api/classes/formatoRehabilitacion.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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
      $scope.imprimirPaseReha = function(){
      var fileName = "Reporte";
                        var uri = 'api/classes/formatoPaseRehabilitacion.php?fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin;
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
    $scope.imprimirHistoriaClinica = function(){            
            var fileName = "Reporte";
            var uri = 'api/classes/FormatoH.php?fol='+$rootScope.folio;
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


     $scope.imprimirSignosVitales = function(){            
            var fileName = "Reporte";
            var uri = 'api/classes/formatoVitales.php?fol='+$rootScope.folio;
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

        $scope.imprimirSignosVitalesSub = function(){ 
             busquedas.validaSubsec($rootScope.folio).success(function(data){                                      
                if(data.Cons==null){
                  data.Cons=1;
                }
                $scope.noSubsec=data.Cons;    
              });           
            var fileName = "Reporte";
            var uri = 'api/classes/formatoVitalesSub.php?fol='+$rootScope.folio+'&sub='+$scope.noSubsec;
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

         $scope.imprimirConstancia = function(){            
            var fileName = "Reporte";
            var uri = 'api/classes/formatoCia.php?fol='+$rootScope.folio;
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

        $scope.guardaAddendum = function(){          
           if($scope.agreAddendum.$valid){
            $scope.cargador1=true; 
            console.log($scope.addendum);                                
            $http({
                    url:'api/api1.php?funcion=agregarAddendum&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.addendum
                    }).success( function (data){  
                    console.log(data);                   
                     if(data=='exito'){
                        $scope.cargador1=false; 
                        $scope.mensajeExito=true;
                        $scope.addendum={
                            cuerpo:''
                        }
                        $scope.agreAddendum.$submitted=false;           
                     }
                      
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    });
          }
        }

         $scope.actualizaFactura = function(){                              
            $scope.cargador1=true;                                 
            $http({
                    url:'api/api1.php?funcion=actualizaFactura&fol='+$rootScope.folio+'&folFact='+$scope.validaFacturaVal,
                    method:'POST', 
                    contentType: 'application/json', 
                    dataType: "json", 
                    data: $scope.addendum
                    }).success( function (data){                                
                     if(data=='exito'){
                        $scope.cargador1=false; 
                        $scope.mensajeExito=true; 
                        $scope.validaFacturaVal='';                                                       
                     }                    
                    }).error( function (xhr,status,data){
                        $scope.mensaje ='no entra';            
                        alert('Error');
                    }); 
        }

        $scope.listaDetalle = function(){             
            $http.get('api/detallePx.php?funcion=getDatosPersonales&fol='+$rootScope.folio).success(function (data){  
                      //console.log(data);                                                                                               
                      $scope.detalle.nombre=data.Exp_completo;
                      $scope.detalle.sexo= data.Exp_sexo;
                      $scope.detalle.edad= data.Exp_edad;
                      $scope.detalle.producto= data.Pro_nombre;
                      $scope.detalle.fechaReg= data.Exp_fecreg;
                      $scope.detalle.aseguradora= data.Cia_nombrecorto;
                      $scope.detalle.telefono= data.Exp_telefono;
                      $scope.detalle.mail= data.Exp_mail;                      
                      $scope.detalle.religion= data.Rel_clave;

                      if($scope.detalle.sexo==''||$scope.detalle.sexo==null){
                        $scope.detalle.sexo='--';                        
                      }
                      if($scope.detalle.edad==0||$scope.detalle.edad==null){
                        $scope.detalle.edad='--';                        
                      }
                      if($scope.detalle.mail==''||$scope.detalle.mail==null){
                        $scope.detalle.mail='--';                        
                      }
                      if($scope.detalle.religion==''||$scope.detalle.religion==null){
                        $scope.detalle.religion='--';                        
                      }
                  }); 
                  $http.get('api/detallePx.php?funcion=getAutorizacion&fol='+$rootScope.folio).success(function (data){                     
                        console.log(data);
                        if(data=='false'){
                          $scope.autorizacion='--';
                        }else{
                          $scope.autorizacion=data.AUM_clave;
                        }
                  }); 
                  $http.get('api/detallePx.php?funcion=getSignosVitales&fol='+$rootScope.folio).success(function (data){                     
                        //console.log(data);
                        if(data==''){
                          $scope.conSigVit='';
                        }else{
                          $scope.conSigVit=data;
                        }
                  }); 
                  $http.get('api/detallePx.php?funcion=getHistoriaClinica&fol='+$rootScope.folio).success(function (data){                     
                        //console.log(data);
                        if(data=='false'){
                          $scope.estatusHistoria='No se ha agregado';
                          $scope.fechaHist = '--';
                        }else{
                          $scope.estatusHistoria='Completa';
                          $scope.fechaHist = data.fecha;
                        }
                  }); 
                  $http.get('api/detallePx.php?funcion=getNotaMedica&fol='+$rootScope.folio).success(function (data){                     
                       // console.log(data);
                        if(data=='false'){
                          $scope.estatusNota='No se ha agregado';
                          $scope.fechaNota = '--';
                        }                        
                        else{
                          if(data.estatus>0 && data.estatus<12){
                            $scope.estatusNota='Incompleta - estatus ('+data.estatus+')';
                            $scope.fechaNota = '--';
                          }else{
                            $scope.estatusNota='Completa';
                            $scope.fechaNota = data.fecha; 
                          }
                          
                        }
                  }); 
                  $http.get('api/detallePx.php?funcion=getSubsecuencias&fol='+$rootScope.folio).success(function (data){ 
                  //console.log(data);                    
                       if(data==false){
                          $scope.msjSubsecuencia='No se han agregado subsecuencias.';
                       }else{
                          $scope.listSubsecuencias=data;
                       }
                        
                  }); 
                  $http.get('api/detallePx.php?funcion=getRehabilitaciones&fol='+$rootScope.folio).success(function (data){ 
                  //console.log(data);                    
                       if(data==''){
                          $scope.msjRehab='No se han agregado rehabilitaciones.';
                       }else{
                          $scope.listRehabilitaciones=data;
                       }                      
                  });
                  /******************** documentos digitalizados *********************************************/
                  $http.get('api/detallePx.php?funcion=getAviso&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeAviso=false;
                        }else{
                          $scope.existeAviso=true;
                        }                                                          
                  }); 

                   $http.get('api/detallePx.php?funcion=getConsMed&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeConsMed=false;
                        }else{
                          $scope.existeConsMed=true;
                        }                                                          
                  }); 
                    $http.get('api/detallePx.php?funcion=getCuestionario&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeCuestionario=false;
                        }else{
                          $scope.existeCuestionario=true;
                        }                                                          
                  }); 
                     $http.get('api/detallePx.php?funcion=getFiniquito&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeFiniquito=false;
                        }else{
                          $scope.existeFiniquito=true;
                        }                                                          
                  }); 
                      $http.get('api/detallePx.php?funcion=getHistoria&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeHistoria=false;
                        }else{
                          $scope.existeHistoria=true;
                        }                                                          
                  }); 
                       $http.get('api/detallePx.php?funcion=getIdentificacion&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeId=false;
                        }else{
                          $scope.existeId=true;
                        }                                                          
                  }); 
                        $http.get('api/detallePx.php?funcion=getInfMedico&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeInfMedico=false;
                        }else{
                          $scope.existeInfMedico=true;
                        }                                                          
                  }); 
                         $http.get('api/detallePx.php?funcion=getInfAseg&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existeInfAse=false;
                        }else{
                          $scope.existeInfAse=true;
                        }                                                          
                  }); 

                   $http.get('api/detallePx.php?funcion=getPaseMedico&fol='+$rootScope.folio).success(function (data){                   
                        if(data.contador==0){
                          $scope.existePase=false;
                        }else{
                          $scope.existePase=true;
                        }                                                          
                  }); 
                   $http.get('api/detallePx.php?funcion=getDiagnostico&fol='+$rootScope.folio).success(function (data){ 
                        console.log(data);                  
                        if(data=='error'){
                          $scope.diagnostico='--';
                        }else{
                          $scope.diagnostico=data.ObsNot_diagnosticoRx;
                        }                                                          
                  }); 

                  /******************  fin de documentos digitalizado ****************************************/

    }


        $scope.validaDoc = function(){         
            if($scope.addendum.doc==4){       
              $http.get('api/api1.php?funcion=buscaSubs&fol='+$rootScope.folio).success(function (data){
                if(data>0){
                  $scope.divSubsecuencia=true;
                  var range = [];
                  var contador = parseInt(data);
                  for(var i=1;i<contador+1;i++) {
                    range.push(i);
                  }
                  $scope.range = range;
                  console.log(data);                             
                }else{
                  $scope.divSubsecuencia=false;
                }
              });
          }else{
            $scope.divSubsecuencia=false;
          }
        }


        /************************************************   validación para imprmiir formatos de corta estancia *************************/

        $scope.verificaSolicitudCE = function(){ 

            $http.get('api/cortaEstancia.php?funcion=getSolicitud&fol='+$rootScope.folio).success(function (data){ 
            console.log(data);                                                                               
                   if(data.contador==0){
                      $('#SolicitarCortaEstancia').modal('show');   
                   }else if(data.contador>=1){
                      $http.get('api/cortaEstancia.php?funcion=getcheckValida&fol='+$rootScope.folio).success(function (data){ 
                        $('#verDocsCE').modal('show');
                        if(data.contadorUsado>=1){
                          $scope.verDocumentosCE=true;
                        }else{
                          $scope.verDocumentosCE=false;
                        }                                                  
                      });
                       
                   }           
            });      
            
        }
        $scope.verFormularioSolicitud = function(){ 
            $scope.verSolicitudCE=true;
        }
        $scope.solicitarFormatos = function(){          
          $scope.cargador1=true;
           $http({
                url:'api/cortaEstancia.php?funcion=setSolicitud&fol='+$rootScope.folio+'&usr='+$rootScope.usrLogin,
                method:'POST', 
                contentType: 'application/json', 
                dataType: "json", 
                data: $scope.solicitudCE
                }).success( function (data){
                console.log(data);                             
                $scope.cargador1=false;
                if(data.respuesta=='exito'){
                  $scope.mensajeExito=false;
                  $('#SolicitarCortaEstancia').modal('hide'); 
                  $('#verDocsCE').modal('show'); 
                   $scope.existeSolCE=true;          
                }else{
                  $scope.mensajeExito=false;
                }
            }).error( function (xhr,status,data){
                $scope.mensaje ='no entra';            
                alert('Error');
            }); 
        }

        $scope.validaSolicitudCE = function(){          
          $scope.cargador1=true;
          $http.get('api/cortaEstancia.php?funcion=validaAut&fol='+$rootScope.folio+'&aut='+$scope.solicutudValida).success(function (data){
            console.log(data);
            $scope.cargador1=false;
             if(data.respuesta=='error'){
                $scope.mensajeExito= true;
                $scope.solicutudValida=''; 
             }else if(data.respuesta=='exito'){
                $scope.mensajeExito= false;
                $scope.verDocumentosCE=true; 
                $scope.existeAutCE=false;
             }
          });
           
        }


        $scope.generarMembresia = function(){                    
          
          $http.get('api/convenio.php?funcion=setMembresia&fol='+$rootScope.folio+'&uni='+$rootScope.uniClave).success(function (data){            
            console.log(data);
            $scope.btonGenerarMem=false;
            $scope.membresiaCreada = data;
          });
           
        }


        /**************************************************fin formatos corta estancia **************************************************/




        $scope.imprimirDetalle = function(folio){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoDetalle.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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

        $scope.imprimeHojaEvolucion = function(folio){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoEvolucion.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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

         $scope.imprimeHojaIndicaciones = function(folio){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoIndicaciones.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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

        $scope.imprimeTraslado = function(folio){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoTraslado.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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

        $scope.imprimeConsentimiento = function(folio){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/formatoConsentimiento.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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

         $scope.imprimirReciboDeducible = function(folio){ 
          $scope.cargador=true;        
            var fileName = "Reporte";
            var uri = 'api/classes/reciboDeducible.php?fol='+folio+'&usr='+$rootScope.usrLogin+'&uni='+$rootScope.uniClave;
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