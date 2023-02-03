// coleccion de directivas para inputs


//funcion para convertir mayusculas
app.directive('mayusculas', function() {
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
        var capitalize = function(inputValue) {
           var capitalized = inputValue.toUpperCase();
           if(capitalized !== inputValue) {
              modelCtrl.$setViewValue(capitalized);
              modelCtrl.$render();
            }         
            return capitalized;
         }
         modelCtrl.$parsers.push(capitalize);
         capitalize(scope[attrs.ngModel]);  // capitalize initial value
     }
   };
});

//directiva para teclear un folio medicavial 
app.directive('folio', function() {
    return {
      restrict: 'A',
      require: 'ngModel',
      link: function(scope, element, attrs, modelCtrl) {

          var functionToCall = scope.$eval(attrs.folio);

          //console.log(functionToCall);
          //console.log(modelCtrl.$modelValue);
          //console.log(attrs);
          console.log(scope);

          var rellenaFolio = function(folio){

            if (folio != '') {

              var totalletras = folio.length;

              var letras = folio.substr(0,4);
              var numeros = folio.substr(4,totalletras);

              if(letras.length < 4 ){

                var faltantes = 4 - letras.length;

                for (var i = 0; i < faltantes; i++) {

                  var letra = letras.charAt(i);
                  letras = letras + "0";
                }
              }

              if(numeros.length < 6 ){

                var faltantes = 6 - numeros.length;

                for (var i = 0; i < faltantes; i++) {
                  
                  numeros = "0" + numeros;
                }
              }

              folio = letras + numeros;

              return folio;

            }else{

              return folio

            }
          }
          
          element.on('blur',function(){

            if (modelCtrl.$modelValue.length > 3) {
              var nuevo = rellenaFolio(modelCtrl.$modelValue);
              //console.log(nuevo);
              modelCtrl.$setViewValue(nuevo);
              modelCtrl.$render();
              scope.$apply();
            };

          });

          element.on('keydown', function(e){
                

                var cantidad = modelCtrl.$modelValue.length;

                //console.log(e.keyCode);

                //los primero cuatro caracteres NO deben ser numeros
                if(cantidad <= 3){

                  if (e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 96 && e.keyCode <= 105) {
                        e.preventDefault();
                  }else{
                        modelCtrl.$parsers.push(function (inputValue) {

                           if (inputValue == undefined) return '' 
                           var transformedInput = inputValue.toUpperCase();
                           if (transformedInput!=inputValue) {
                              modelCtrl.$setViewValue(transformedInput);
                              modelCtrl.$render();
                           }         

                           return transformedInput; 

                        });
                  }

                }

                //los ultimos 6 NO deben ser letras
                if(cantidad >= 4 && cantidad <= 9){

                  if (e.keyCode >= 65 && e.keyCode <= 90 || e.keyCode >= 106) {
                        e.preventDefault();
                  }

                }

                //Si son mas de 10 digitos no escribas mas
                if(cantidad >= 10){
                    
                    if (e.keyCode >= 65 && e.keyCode <= 90 || e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 96 && e.keyCode <= 105 || e.keyCode >= 106) {
                      e.preventDefault();
                    }else{
                      //console.log('Presionaste ' + e.keyCode);
                    } 

                }

                if (e.keyCode == 13 || e.keyCode == 9) {

                      if (cantidad > 4) {

                        
                        var nuevo = rellenaFolio(modelCtrl.$modelValue);
                        //console.log(nuevo);
                        modelCtrl.$setViewValue(nuevo);
                        modelCtrl.$render();
                        scope.$apply();
                        functionToCall(modelCtrl.$modelValue);
                              
                      };
                      
                          
                }


          });



      }

    };
    
});

//directiva que activa una funcion al apretar enter
app.directive('enter', function() {
    return {
      restrict: 'A',
      require: 'ngModel',
      link: function(scope, element, attrs, modelCtrl) {

          var functionToCall = scope.$eval(attrs.enter);

          element.on('keydown', function(e){

              if (e.keyCode == 13) {

                      functionToCall(modelCtrl.$modelValue);
 
              }

          });



      }

    };
    
});


//directiva para escribir solo numeros
app.directive('numeros', function(){
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {

       modelCtrl.$parsers.push(function (inputValue) {
           if (inputValue == undefined) return '' 
           var transformedInput = inputValue.replace(/[^0-9]/g, ''); 
           if (transformedInput!=inputValue) {
              modelCtrl.$setViewValue(transformedInput);
              modelCtrl.$render();
           }         

           return transformedInput;         
       });
     }
   };
});


//directiva para escribir cantidades decimal
app.directive('dinero', function () {

  var NUMBER_REGEXP = /^\s*(\-|\+)?(\d+|(\d*(\.\d*)))\s*$/;

  function link(scope, el, attrs, ngModelCtrl) {
    var min = parseFloat(attrs.min || 0);
    var precision = parseFloat(attrs.precision || 2);
    var lastValidValue;

    function round(num) {
      var d = Math.pow(10, precision);
      return Math.round(num * d) / d;
    }

    function formatPrecision(value) {
      return parseFloat(value).toFixed(precision);
    }

    function formatViewValue(value) {
      return ngModelCtrl.$isEmpty(value) ? '' : '' + value;
    }


    ngModelCtrl.$parsers.push(function (value) {
      // Handle leading decimal point, like ".5"
      if (value.indexOf('.') === 0) {
        value = '0' + value;
      }

      // Allow "-" inputs only when min < 0
      if (value.indexOf('-') === 0) {
        if (min >= 0) {
          value = null;
          ngModelCtrl.$setViewValue('');
          ngModelCtrl.$render();
        } else if (value === '-') {
          value = '';
        }
      }

      var empty = ngModelCtrl.$isEmpty(value);
      if (empty || NUMBER_REGEXP.test(value)) {
        lastValidValue = (value === '')
          ? null
          : (empty ? value : parseFloat(value));
      } else {
        // Render the last valid input in the field
        ngModelCtrl.$setViewValue(formatViewValue(lastValidValue));
        ngModelCtrl.$render();
      }

      ngModelCtrl.$setValidity('number', true);
      return lastValidValue;
    });
    ngModelCtrl.$formatters.push(formatViewValue);

    var minValidator = function(value) {
      if (!ngModelCtrl.$isEmpty(value) && value < min) {
        ngModelCtrl.$setValidity('min', false);
        return undefined;
      } else {
        ngModelCtrl.$setValidity('min', true);
        return value;
      }
    };
    ngModelCtrl.$parsers.push(minValidator);
    ngModelCtrl.$formatters.push(minValidator);

    if (attrs.max) {
      var max = parseFloat(attrs.max);
      var maxValidator = function(value) {
        if (!ngModelCtrl.$isEmpty(value) && value > max) {
          ngModelCtrl.$setValidity('max', false);
          return undefined;
        } else {
          ngModelCtrl.$setValidity('max', true);
          return value;
        }
      };

      ngModelCtrl.$parsers.push(maxValidator);
      ngModelCtrl.$formatters.push(maxValidator);
    }

    // Round off
    if (precision > -1) {
      ngModelCtrl.$parsers.push(function (value) {
        return value ? round(value) : value;
      });
      ngModelCtrl.$formatters.push(function (value) {
        return value ? formatPrecision(value) : value;
      });
    }

    el.bind('blur', function () {
      var value = ngModelCtrl.$modelValue;
      if (value) {
        ngModelCtrl.$viewValue = formatPrecision(value);
        ngModelCtrl.$render();
      }
    });
  }

  return {
    restrict: 'A',
    require: 'ngModel',
    link: link
  };

});


//directiva que agrega el atributo download a un boton para que descargue archivo con ruta especiica
// app.directive('archivo', function() {
//     return {
//       restrict: 'A',
//       require: 'ngModel',
//       link: function(scope, element, attrs, modelCtrl) {
//           debugger;
//           var file = scope.$eval(attrs.archivo);
//           console.log(file);

//           element.on('click', function(){

//               var link = document.createElement("a");    
//               link.href = file;
              
//               //set the visibility hidden so it will not effect on your web-layout
//               link.style = "visibility:hidden";
//               link.download = file;
              
//               //this part will append the anchor tag and remove it after automatic click
//               document.body.appendChild(link);
//               link.click();
//               document.body.removeChild(link);

//           });



//       }

//     };
    
// });

//directiva que agrega boton para descargar lo que pongas como atributo
app.directive('descarga', function(){
    return {
        restrict: 'E',
        scope: true,
        scope: { info: '=' },
        template: '<button ng-click="click(info)" class="btn btn-primary btn-sm glyphicon glyphicon-download-alt"> Descargar</button>',
        controller: function($scope, $element){

            $scope.click = function(info){
              
              console.log(info);
              
              //this trick will generate a temp <a /> tag
              var link = document.createElement("a");    
              link.href = info;
              
              //set the visibility hidden so it will not effect on your web-layout
              link.style = "visibility:hidden";
              link.download = info;
              
              //this part will append the anchor tag and remove it after automatic click
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);

            }
        }
    }
});


app.directive('archivo', function() {
  return {
      scope: {
          archivo: '=',
      },
      link: function($scope, element, attrs) {
        
        element.on('click',function(){
          
            var file = attrs.archivo;

            var link = document.createElement("a");    
            link.href = file;
            
            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = file;
            
            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);



        })
      }
    }
});



//directiva que agrega boton para descargar lo que pongas como atributo
app.directive('pdf', function(){
    return {
        restrict: 'E',
        scope: true,
        scope: { data: '=' },
        template: '<button ng-click="click(data)" class="btn btn-default">Imprimir Comprobante</button>',
        controller: function($scope, $element, busquedas){

            var getImageFromUrl = function(url, callback, clave) {
        
                var img = new Image(), data, ret = {
                    data: null,
                    pending: true
                };
                
                img.onError = function() {
                    throw new Error('Cannot load image: "'+url+'"');
                };
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    document.body.appendChild(canvas);
                    canvas.width = img.width;
                    canvas.height = img.height;

                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);
                    // Grab the image as a jpeg encoded in base64, but only the data
                    data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                    // Convert the data to binary form
                    data = atob(data);
                    document.body.removeChild(canvas);

                    ret['data'] = data;
                    ret['pending'] = false;
                    if (typeof callback === 'function') {
                        callback(data,clave);
                    }
                };
                img.src = url;

                return ret;
            };

            var createPDF = function(imgData,clave) {

                var doc = new jsPDF();

                doc.addImage(imgData, 'JPEG', 85, 10, 35, 20);
                //rectangulo
                //doc.setDrawColor(0);
                //doc.setFillColor(255, 255, 255);
                //doc.roundedRect(10, 40, 190, 200, 3, 3, 'FD');

                busquedas.detalleSolicitud(clave).success(function (data){
                  console.log(data);

                  var datos = data.info;
                  //encabezado
                  doc.setFontSize(9);
                  doc.text(160, 10, FechaAct + ' ' + FechaActHora); 

                  doc.setFontSize(18);
                  doc.setFont("helvetica");
                  doc.setFontType("bold");
                  doc.text(20, 52, 'Solicitud: '+ datos.tiponombre); 

                  doc.setTextColor(0, 136, 204);
                  doc.text(160, 52, clave); 

                  doc.setTextColor(100);
                  doc.setLineWidth(0.5);
                  doc.line(20, 60, 190, 60);

                  doc.setFontSize(14);
                  //contenido
                  doc.setFontType("normal");

                  doc.text(20, 70, 'Folio:'); 
                  doc.text(50, 70, datos.folio); 
                  doc.text(90, 70, 'Cliente:');
                  doc.text(110, 70, datos.cliente); 

                  doc.text(20, 80, 'Lesionado:'); 
                  doc.text(50, 80, datos.lesionado);

                  doc.text(20, 90, 'Unidad:'); 
                  doc.text(50, 90, 'MV Operaciones');

                  //encabezado tipo
                  doc.setFontSize(15);
                  doc.setTextColor(0);
                  doc.setFontType("bold");
                  
                  if (datos.tipo == 4) {

                    ///para interconsulta
                    doc.text(20, 105, 'Tipo de Solicitud :');
                    doc.text(70, 105, 'INTERCONSULTA ' + datos.DES_intertipo.toUpperCase()); 

                    //linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    if(datos.DES_intertipo == 'Ginecologia'){

                      doc.text(20, 120, 'Embarazo:'); 

                      if (datos.DES_embarazo) {

                        doc.text(60, 120, 'SI'); 
                        doc.text(90, 120, 'Sometida a control ginecológico:'); 
                        doc.text(170, 120, datos.DES_controlgineco);

                        doc.text(20, 130, 'Semanas de gestación:'); 
                        doc.text(80, 130, datos.DES_semanas); 
                        doc.text(90, 130, 'Dolor abdominal:'); 
                        doc.text(140, 130, datos.DES_dolorabdominal);

                        doc.text(20, 140, 'Frecuencia cardiaca fetal:'); 
                        doc.text(80, 140, datos.DES_frecuencia); 
                        doc.text(110, 140, 'Movimientos fetales:'); 
                        doc.text(160, 140, datos.DES_movimientosfetales);

                        doc.text(20, 160, 'Observaciones:'); 
                        doc.text(20, 170, doc.splitTextToSize(datos.DES_embarazoObs,170));

                        doc.setFontSize(14);
                        doc.text(20, 190, 'Diagnostico Actual:'); 
                        doc.setFontSize(12);
                        doc.text(20, 200, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                        doc.setFontSize(14);
                        doc.text(20, 220, 'Justificación y Observaciones:'); 
                        doc.setFontSize(12);
                        doc.text(20, 230, doc.splitTextToSize(datos.DES_justificacion, 170)); 

                      }else{

                        doc.text(60, 120, 'NO'); 
                        doc.setFontSize(14);
                        doc.text(20, 130, 'Diagnostico Actual:'); 
                        doc.setFontSize(12);
                        doc.text(20, 140, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                        doc.setFontSize(14);
                        doc.text(20, 160, 'Justificación y Observaciones:'); 
                        doc.setFontSize(12);
                        doc.text(20, 170, doc.splitTextToSize(datos.DES_justificacion, 170)); 

                      }
 
                         


                    }else{

                      doc.setFontSize(14);
                      doc.text(20, 120, 'Diagnostico Actual:'); 
                      doc.setFontSize(12);
                      doc.text(20, 130, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                      doc.setFontSize(14);
                      doc.text(20, 150, 'Justificación y Observaciones:'); 
                      doc.setFontSize(12);
                      doc.text(20, 160, doc.splitTextToSize(datos.DES_justificacion, 170));    

                    }

                    
                  };

                  if (datos.tipo == 3) {

                    // estudio especial
                    doc.text(20, 105, 'Tipo de Solicitud :');
                    doc.text(70, 105, datos.DES_estudiotipo); 
                    // linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    doc.text(20, 120, 'Detalle:'); 
                    doc.setFontSize(12);
                    doc.text(20, 130, doc.splitTextToSize(datos.DES_estudioDetalle, 170));

                    doc.setFontSize(14);
                    doc.text(20, 160, 'Diagnostico Actual:'); 
                    doc.setFontSize(12);
                    doc.text(20, 170, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                    doc.setFontSize(14);
                    doc.text(20, 190, 'Justificación y Observaciones:'); 
                    doc.setFontSize(12);
                    doc.text(20, 200, doc.splitTextToSize(datos.DES_justificacion, 170));    

                  }

                  if (datos.tipo == 1) {

                    //rehabilitacion

                    doc.text(20, 105, 'Detalle de Solicitud :');

                    //linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    doc.text(20, 120, 'Escala de dolor del paciente:'); 
                    doc.text(90, 120, datos.DES_dolor);

                    doc.text(20, 130, 'Numero de sesiones de rehabilitación del paciente:'); 
                    doc.text(140, 130, datos.DES_rehabilitaciones); 

                    doc.text(20, 140, 'Escala de mejoria:'); 
                    doc.text(70, 140, datos.DES_mejora);

                    doc.setFontSize(14);
                    doc.text(20, 150, 'Diagnostico Actual:'); 
                    doc.setFontSize(12);
                    doc.text(20, 160, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                    doc.setFontSize(14);
                    doc.text(20, 180, 'Justificación y Observaciones:'); 
                    doc.setFontSize(12);
                    doc.text(20, 190, doc.splitTextToSize(datos.DES_justificacion, 170));

                  }

                  if (datos.tipo == 9) {
                    
                    // Suministro

                    doc.text(20, 105, 'Detalle de Solicitud :');

                    //linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    doc.text(20, 120, 'Detalle del suministro requerido:'); 
                    doc.setFontSize(12);
                    doc.text(20, 130, doc.splitTextToSize(datos.DES_suministroDetalle, 170));

                    doc.setFontSize(14);
                    doc.text(20, 160, 'Diagnostico Actual:'); 
                    doc.setFontSize(12);
                    doc.text(20, 170, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                    doc.setFontSize(14);
                    doc.text(20, 190, 'Justificación y Observaciones:'); 
                    doc.setFontSize(12);
                    doc.text(20, 200, doc.splitTextToSize(datos.DES_justificacion, 170));  
                  }

                  if (datos.tipo == 11) {
                    
                    // Prestamo info

                    doc.text(20, 105, 'Detalle de Solicitud :');
                    
                    //linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    doc.text(20, 120, 'Nota medica:'); 
                    if (datos.DES_notamedica) {
                      doc.text(55, 120, 'SI'); 
                    }else{
                      doc.text(55, 120, 'NO'); 
                    }
                    
                    
                    doc.text(90, 120, 'Rx:');
                    if (datos.DES_rx) {
                      doc.text(105, 120, 'SI'); 
                    }else{
                      doc.text(105, 120, 'NO'); 
                    }
 
                    doc.text(130, 120, 'Resultado de estudios:');
                    if (datos.DES_resultados) {
                      doc.text(185, 120, 'SI'); 
                    }else{
                      doc.text(185, 120, 'NO'); 
                    }
                    
                    doc.text(20, 130, 'Motivo por el cual el paciente requiere esta información:'); 
                    doc.setFontSize(12);
                    doc.text(20, 140, doc.splitTextToSize(datos.DES_infoDetalle, 170));

                    doc.setFontSize(14);
                    doc.text(20, 160, 'Diagnostico Actual:'); 
                    doc.setFontSize(12);
                    doc.text(20, 170, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                    doc.setFontSize(14);
                    doc.text(20, 190, 'Justificación y Observaciones:'); 
                    doc.setFontSize(12);
                    doc.text(20, 200, doc.splitTextToSize(datos.DES_justificacion, 170));  

                  }

                  if (datos.tipo == 2) {

                    // Problema Documental

                    doc.text(20, 105, 'Detalle de Solicitud :');

                    //linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    doc.text(20, 120, 'Problema pase médico:'); 
                    if (datos.DES_pase) {
                      doc.text(80, 120, 'SI'); 
                    }else{
                      doc.text(80, 120, 'NO'); 
                    }

                    doc.text(100, 120, 'Problema identificación:');

                    if (datos.DES_identificacion) {
                      doc.text(160, 120, 'SI'); 
                    }else{
                      doc.text(160, 120, 'NO'); 
                    }

                    doc.text(20, 130, 'Motivo del problema:'); 

                    //cambiamos de fuente por que se espera un texto largo
                    doc.setFontSize(12);
                    //la funcion splitTextToSize nos permite delimitar el texto para que no se pase y corte texto
                    doc.text(20, 140, doc.splitTextToSize(datos.DES_documentalDetalle, 170));

                    doc.setFontSize(14);
                    doc.text(20, 160, 'Diagnostico Actual:'); 
                    doc.setFontSize(12);
                    doc.text(20, 170, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                    doc.setFontSize(14);
                    doc.text(20, 190, 'Justificación y Observaciones:'); 
                    doc.setFontSize(12);
                    doc.text(20, 200, doc.splitTextToSize(datos.DES_justificacion, 170));  
                    
                  }

                  if (datos.tipo == 21) {
                    // salida de paquete

                    doc.text(20, 105, 'Detalle de Solicitud :');

                    //linea
                    doc.setLineWidth(0.5);
                    doc.line(20, 110, 190, 110);

                    doc.setTextColor(100);

                    doc.setFontSize(14);
                    doc.setFontType("normal");

                    doc.text(20, 120, 'Detalle:'); 
                    doc.text(20, 130, doc.splitTextToSize(datos.DES_hosDetalle, 170));

                    doc.setFontSize(14);
                    doc.text(20, 160, 'Diagnostico Actual:'); 
                    doc.setFontSize(12);
                    doc.text(20, 170, doc.splitTextToSize(datos.DES_diagnostico, 170)); 

                    doc.setFontSize(14);
                    doc.text(20, 190, 'Justificación y Observaciones:'); 
                    doc.setFontSize(12);
                    doc.text(20, 200, doc.splitTextToSize(datos.DES_justificacion, 170)); 
                     

                  }


                  doc.save(clave + '-comprobante.pdf');

                });
                  
                


            }

            $scope.click = function(clave){

              getImageFromUrl('imgs/logomv.jpg', createPDF, clave);


            }
        }
    }
});

 //directiva que agrega boton para descargar la receta lo que pongas como atributo
   app.directive('receta', function(){
        return {
            restrict: 'E',
            scope: true,
            scope: { data: '=' },
            template: '<button ng-click="click(data)" class="btn btn-primary"  >Imprimir Receta</button>',
            controller: function($scope, $element){

                var info;

                 var tiempo = new Date();
                
                  var dd = tiempo.getDate(); 
                  var mm = tiempo.getMonth()+1;//enero es 0! 
                  if (mm < 10) { mm = '0' + mm; }
                  if (dd < 10) { dd = '0' + dd; }

                  var yyyy = tiempo.getFullYear();
                  //armamos fecha para los datepicker
                  var FechaAct = yyyy + '-' + mm + '-' + dd;
                  var FechaAct2 = dd + '/' + mm + '/' + yyyy;

                  var hora = tiempo.getHours();
                  var minuto = tiempo.getMinutes();

                  var HoraAct = hora + ':' + minuto;

                var getImage1 = function(image1, image2, image3, callback) {
            
                    var img = new Image(), data, ret = {
                        data: null,
                        pending: true
                    };

                    img.onError = function() {
                        throw new Error('Cannot load image: "'+image+'"');
                    };

                    img.onload = function() {

                        //primera imagen
                        var canvas = document.createElement('canvas');
                        document.body.appendChild(canvas);
                        canvas.width = img.width;
                        canvas.height = img.height;

                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        // Grab the image as a jpeg encoded in base64, but only the data
                        data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                        
                        // Convert the data to binary form
                        data = atob(data);
                        document.body.removeChild(canvas);

                        ret['data'] = data;
                        ret['pending'] = false;

                        if (typeof callback === 'function') {
                            callback(data,image2,image3,getImage3);
                        }

                    };
                    img.src = image1;

                    return ret;

                };

                var getImage2 = function(image1, image2, image3, callback) {
            
                    var img = new Image(), data, ret = {
                        data: null,
                        pending: true
                    };
                    
                    img.onError = function() {
                        throw new Error('Cannot load image: "'+image+'"');
                    };

                    img.onload = function() {

                        //primera imagen
                        var canvas = document.createElement('canvas');
                        document.body.appendChild(canvas);
                        canvas.width = img.width;
                        canvas.height = img.height;

                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        // Grab the image as a jpeg encoded in base64, but only the data
                        data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                        // Convert the data to binary form
                        data = atob(data);
                        document.body.removeChild(canvas);

                        ret['data'] = data;
                        ret['pending'] = false;


                        if (typeof callback === 'function') {
                            callback(image1,data,image3,createPDF);
                        }

                    };
                    img.src = image2;

                    return ret;

                };

                var getImage3 = function(image1, image2, image3, callback) {
            
                    var img = new Image(), data, ret = {
                        data: null,
                        pending: true
                    };
                    
                    img.onError = function() {
                        throw new Error('Cannot load image: "'+image+'"');
                    };

                    img.onload = function() {

                        //primera imagen
                        var canvas = document.createElement('canvas');
                        document.body.appendChild(canvas);
                        canvas.width = img.width;
                        canvas.height = img.height;

                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        // Grab the image as a jpeg encoded in base64, but only the data
                        data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                        // Convert the data to binary form
                        data = atob(data);
                        document.body.removeChild(canvas);

                        ret['data'] = data;
                        ret['pending'] = false;

                        var canvas2 = $("canvas");
                        
                        var qr = canvas2[0].toDataURL("image/jpeg");
                        console.log(qr);
                        if (typeof callback === 'function') {
                            callback(image1,image2,data,qr);
                        }

                    };
                    img.src = image3;

                    return ret;

                };

                var createPDF = function(imgData,imgData2,imgData3,qr) {


                    var md5 = "d4287a37682bc5f1a1cb93e7a990e6f5";

                    var codigo = 'data:image/jpeg;base64,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';
                    var doc = new jsPDF();

                    doc.addImage(imgData, 'JPEG', 10, 8, 35, 20);

                    //agregamos codigo qr generado en el html
                    doc.addImage(qr, 'JPEG', 172, 4, 28, 29);

                    doc.setFontType("bold");

                    doc.setFontSize(20);
                    doc.setFont("helvetica");
                    doc.text(82,15, 'Receta Médica'); 

                    doc.setFontSize(9);
                    doc.text(82, 19, 'Fecha: ' + FechaAct2); 
                    doc.text(112, 19, 'Hora: '+ HoraAct); 
                    doc.text(88, 23, 'RECETA ID:'+ info.receta);


                    //primaera seccion datos del paciente
                    doc.setFontType("normal");
                    //tamaño
                    doc.setFontSize(10);
                    //color de la fuente
                    doc.setTextColor(50);
                    //ingresamos texto
                    doc.text(10,41, 'Paciente: ' + info.lesionado); 
                    doc.text(110, 41, 'Genero: ' + info.sexo); 
                    doc.text(175, 41, 'Edad: '+ info.edad +' años'); 

                    doc.text(10, 46, 'Talla: '+ info.talla +' mts'); 
                    doc.text(60, 46, 'Peso: '+ info.peso +' kg'); 
                    doc.text(110, 46, 'Temperatura: '+ info.temperatura +' ºC'); 
                    doc.text(166, 46, 'Presión arterial: '+ info.presion); 

                    doc.text(10, 55, 'Folio MV:');
                    // doc.setLineWidth(0.5);
                    // doc.line(10, 43, 200, 43);
                    doc.addImage(imgData2, 'JPEG', 28, 48, 35, 11);

                    doc.text(70, 55, doc.splitTextToSize('Alergias: ' + info.alergias, 127));

                    doc.setFontType("bold");

                    doc.setDrawColor(0);
                    doc.setFillColor(232,232,232);
                    doc.rect(10, 61, 190, 3, 'F'); 
                    doc.text(15, 64, 'Datos de la Receta'); 

                    doc.setFontSize(12);
                    doc.setTextColor(50);

                    doc.setFontType("normal");

                    doc.setFontType("bold");

                    doc.setFontSize(12);
                    doc.setTextColor(50);

                    doc.setFontType("bold");
                    doc.setFontSize(8);

                    doc.setFillColor(220,228,249);
                    doc.rect(10, 65, 29, 4, 'F');
                    doc.text(22, 68, 'Clave'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(40, 65,25, 4, 'F');
                    doc.text(44, 68, 'Autorización'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(66, 65, 14, 4, 'F');
                    doc.text(67, 68, 'Cantidad'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(81, 65, 93, 4, 'F');
                    doc.text(105, 68, 'Medicamento (sustancia activa y presentación)'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(173, 65, 35, 4, 'F');
                    doc.text(178, 68, 'Forma farmacéutica');

                    doc.setFontType("normal");

                    // posiciones iniciales en y 
                    var i = 75; //para la parte principal de la medicina
                    var x = 80; //para la linea que divide cada receta
                    var medicamentos = 0;
                    var cantidades = 0;

                    angular.forEach(info.medicamentos, function(item, index){
                        
                        //agregmos el contenido de los medicamentos
                        doc.text(22, i, doc.splitTextToSize(item.clave, 21));
                        doc.text(43, i, doc.splitTextToSize('No requiere', 21));
                        doc.text(71, i, doc.splitTextToSize(String(item.cantidad), 14));
                        doc.text(85, i, doc.splitTextToSize(item.sustancia + ' ' + item.medicamento, 80));
                        doc.text(169, i, doc.splitTextToSize(item.presentacion, 25));
                        
                        doc.text(43, i+9, doc.splitTextToSize(item.posologia, 166));
                        
                        //agregamos una linea divisoria
                        doc.line(10, x+8, 200, x+7);

                        i += 20;
                        x += 20;
                        medicamentos += 1;
                        cantidades += Number(item.cantidad);

                    });
                    angular.forEach(info.ortesis, function(item, index){
                        
                        //agregmos el contenido de los medicamentos
                        doc.text(22, i, doc.splitTextToSize(item.clave, 21));
                        doc.text(43, i, doc.splitTextToSize('No requiere', 21));
                        doc.text(71, i, doc.splitTextToSize(String(item.cantidad), 14));
                        doc.text(85, i, doc.splitTextToSize(item.sustancia + ' ' + item.medicamento, 80));
                        doc.text(169, i, doc.splitTextToSize(item.presentacion, 25));
                        
                        doc.text(43, i+9, doc.splitTextToSize(item.posologia, 166));
                        
                        //agregamos una linea divisoria
                        doc.line(10, x+8, 200, x+7);

                        i += 20;
                        x += 20;
                        medicamentos += 1;
                        cantidades += Number(item.cantidad);

                    });


                   

                    //verificamos el espacio disponible para poner aviso de espacio nulo
                    if(199-i > 30){
                        doc.addImage(imgData3, 'JPEG', 10, i-3 , 190, 209-i);
                    };

                    //cuadro de indicaciones generales 
                    doc.setFontType("bold");
                    doc.setFontSize(8);

                    doc.setDrawColor(0);
                    doc.setFillColor(212,212,212);
                    doc.rect(10, 210, 190, 4, 'F'); 

                    doc.text(15, 213, 'Indicaciones'); 
                    doc.rect(10, 210, 190, 25);

                    doc.setFontType("normal");
                    doc.text(15, 217, doc.splitTextToSize(info.indicaciones, 186)); 

                    doc.setFontType("bold");
                    doc.text(10, 240, 'Médico : '+ info.doctor);
                    doc.text(95, 240, 'Cédula: ' + info.cedula);  
                    doc.text(10, 245, 'Reg. Especialidad: '+ info.especialidad); 
                    //doc.text(88, 275, 'Telefono(s): '+ info.telefonos);

                    doc.text(165, 250, 'Firma');
                    doc.setLineWidth(0.5);                    
                    doc.line(140, 245, 200, 245);

                    doc.text(10, 255,'Dirección: ' +info.direccionUni);
                    doc.text(10, 260,'Teléfono: ' +info.telUni);
                    doc.text(10, 265,'Atención las 24 hrs.');
                    doc.text(10, 270,'Quejas y Sugerencias 01 800 3 MEDICA');

                    doc.text(10, 280, info.cadena);

                    doc.save('RC'+info.folio + '.pdf');

                    $('#genera').button('reset');
                      
                }

                $scope.click = function(datos){

                  $('#genera').button('loading');

                  info = datos;
                  var folio = info.folio;
                  console.log(datos);

                  getImage1('imgs/logomv.jpg', 'api/codigos/' + folio + '.png','imgs/nulo.png', getImage2);

                }
            }
        }
    
    });

//directiva que agrega boton para descargar la receta lo que pongas como atributo
    app.directive('receta1', function(){
        return {
            restrict: 'E',
            scope: true,
            scope: { data: '=' },
            template: '<button ng-click="click(data)" class="btn btn-primary" id="genera" style="width:100%;border-radius: 4px 4px 4px 4px;-moz-border-radius: 4px 4px 4px 4px;-webkit-border-radius: 4px 4px 4px 4px;border: 2px groove #2b61b3; background:#5b6e94; height:5em;font-size:16px" >Imprimir Receta</button>',
            controller: function($scope, $element){

                var info;

                 var tiempo = new Date();
                
                  var dd = tiempo.getDate(); 
                  var mm = tiempo.getMonth()+1;//enero es 0! 
                  if (mm < 10) { mm = '0' + mm; }
                  if (dd < 10) { dd = '0' + dd; }

                  var yyyy = tiempo.getFullYear();
                  //armamos fecha para los datepicker
                  var FechaAct = yyyy + '-' + mm + '-' + dd;
                  var FechaAct2 = dd + '/' + mm + '/' + yyyy;

                  var hora = tiempo.getHours();
                  var minuto = tiempo.getMinutes();

                  var HoraAct = hora + ':' + minuto;

                var getImage1 = function(image1, image2, image3, callback) {
            
                    var img = new Image(), data, ret = {
                        data: null,
                        pending: true
                    };

                    img.onError = function() {
                        throw new Error('Cannot load image: "'+image+'"');
                    };

                    img.onload = function() {

                        //primera imagen
                        var canvas = document.createElement('canvas');
                        document.body.appendChild(canvas);
                        canvas.width = img.width;
                        canvas.height = img.height;

                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        // Grab the image as a jpeg encoded in base64, but only the data
                        data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                        
                        // Convert the data to binary form
                        data = atob(data);
                        document.body.removeChild(canvas);

                        ret['data'] = data;
                        ret['pending'] = false;

                        if (typeof callback === 'function') {
                            callback(data,image2,image3,getImage3);
                        }

                    };
                    img.src = image1;

                    return ret;

                };

                var getImage2 = function(image1, image2, image3, callback) {
            
                    var img = new Image(), data, ret = {
                        data: null,
                        pending: true
                    };
                    
                    img.onError = function() {
                        throw new Error('Cannot load image: "'+image+'"');
                    };

                    img.onload = function() {

                        //primera imagen
                        var canvas = document.createElement('canvas');
                        document.body.appendChild(canvas);
                        canvas.width = img.width;
                        canvas.height = img.height;

                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        // Grab the image as a jpeg encoded in base64, but only the data
                        data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                        // Convert the data to binary form
                        data = atob(data);
                        document.body.removeChild(canvas);

                        ret['data'] = data;
                        ret['pending'] = false;


                        if (typeof callback === 'function') {
                            callback(image1,data,image3,createPDF);
                        }

                    };
                    img.src = image2;

                    return ret;

                };

                var getImage3 = function(image1, image2, image3, callback) {
            
                    var img = new Image(), data, ret = {
                        data: null,
                        pending: true
                    };
                    
                    img.onError = function() {
                        throw new Error('Cannot load image: "'+image+'"');
                    };

                    img.onload = function() {

                        //primera imagen
                        var canvas = document.createElement('canvas');
                        document.body.appendChild(canvas);
                        canvas.width = img.width;
                        canvas.height = img.height;

                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        // Grab the image as a jpeg encoded in base64, but only the data
                        data = canvas.toDataURL('image/jpeg').slice('data:image/jpeg;base64,'.length);
                        // Convert the data to binary form
                        data = atob(data);
                        document.body.removeChild(canvas);

                        ret['data'] = data;
                        ret['pending'] = false;

                        var canvas2 = $("canvas");
                        
                        var qr = canvas2[0].toDataURL("image/jpeg");
                        console.log(qr);
                        if (typeof callback === 'function') {
                            callback(image1,image2,data,qr);
                        }

                    };
                    img.src = image3;

                    return ret;

                };

                var createPDF = function(imgData,imgData2,imgData3,qr) {


                    var md5 = "d4287a37682bc5f1a1cb93e7a990e6f5";

                    var codigo = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2NjIpLCBxdWFsaXR5ID0gOTAK/9sAQwADAgIDAgIDAwMDBAMDBAUIBQUEBAUKBwcGCAwKDAwLCgsLDQ4SEA0OEQ4LCxAWEBETFBUVFQwPFxgWFBgSFBUU/9sAQwEDBAQFBAUJBQUJFA0LDRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQU/8AAEQgAtQK8AwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A/Pr9lv8A5OQ+GP8A2MVj/wCjlr9mP+Cq/wDyZZ4u/wCvyx/9KUr8Z/2W/wDk5D4Zf9jFY/8Ao5a/Zj/gqv8A8mWeLv8Ar7sf/SlKAPwXooooA+8/+CM//J0us/8AYs3X/o+CvT/+C4P/ACHfhL/17aj/AOhwV5h/wRn/AOTpdZ/7Fm6/9HwV6f8A8Fwf+Q78Jf8Ar21H/wBDgoA/L2v1j/4Ihf8AIt/FP/r7sf8A0CWvycr9Y/8AgiF/yLfxU/6+7H/0CWgD9PzR0NIOKMmgD+eb/goX/wAnnfFX/sKD/wBEx1+tP/BLf/kyvwT/ANdbz/0oevyW/wCChX/J53xU/wCwoP8A0THX60/8Et/+TK/BP/XW8/8ASh6APq6f/USf7p/lX8vHjP8A5G/XP+v6f/0Y1f1DXH+ok/3T/Kv5efGf/I365/1/T/8AoxqAP6TvgH/yQr4c/wDYt6b/AOksdd4fyrg/gH/yQr4cf9i3pv8A6Sx13YNAHzj/AMFF/wDkyn4p/wDXhD/6Uw1+J/7G3/J1/wAI/wDsZ7D/ANHrX7X/APBRf/kyr4pf9eEP/pTDX4ofsbf8nX/CP/sZ7D/0etAH9H+cV8A/8Fn/APk2rw3/ANjHF/6Imr79PJr4C/4LP/8AJtXhv/sY4v8A0RNQB8ff8Eef+TtW/wCwDefzjr3T/gt//wAePwn/AOumofyhrwv/AII8/wDJ2rf9gG7/AJx17p/wW/8A+PH4T/8AXTUP5Q0Acf8A8ER/+So/Ej/sD2//AKONfr371+Qn/BEf/kqPxI/7A9v/AOjjX69nJoAOlfgr/wAFUv8Ak9Dxb/17Wf8A6IWv3p6V+C3/AAVS/wCT0PF3/XtZ/wDohaAPkev32/4Jef8AJlHgP/evf/SqWvwJr99v+CXn/JlHgP8A3r3/ANKpaAPqTUv+Qddf9cm/ka/l88Yf8jbrf/X9P/6Mav6gtSP/ABL7r/rk38jX8vvjD/kbdb/6/p//AEY1AH9Clj/yZDb/APYhr/6Q1/Oq33j9a/oqsf8AkyGD/sQl/wDSGv51W+8frQAleofsuf8AJx/wy/7GGx/9HLXl9eofsuf8nH/DL/sYbH/0ctAH9KR618K/8Fj/APk0+y/7GO0/9FzV91HrXwr/AMFjjn9k+y/7GO0/9FzUAfCf/BJH/k8zRf8AsE6h/wCia/dUdq/Cr/gkj/yebov/AGCdQ/8ARNfurQB+TP8AwW9/5Gr4V/8AXlff+jI6/MSv07/4Le/8jV8K/wDryvv/AEZHX5iUAfrN/wAEPv8AkVvi1/1+ad/6BcV8zf8ABXD/AJPM1n/sE2H/AKKr6Z/4Iff8it8Wv+vzTv8A0XcV8zf8FcP+TzNZ/wCwTYf+iqAPuL/gjP8A8mqaz/2NF1/6T21fl3+2v/ydr8Wf+xhuv/QzX6if8EZ/+TVNZ/7Gi6/9J7avy7/bX/5O1+LP/Yw3X/oZoA/aH/gm5/yZX8Mv+vWf/wBKZa/Bn4of8lL8W/8AYXu//Rz1+8v/AATc/wCTLPhl/wBes/8A6Uy1+DXxQ/5KX4t/7C93/wCjnoA/ou/Zh/5Nx+GH/Ytaf/6TpUv7Sn/JvfxJ/wCxevv/AEQ9Q/sw8/s4/DD/ALFrT/8A0nSpv2lP+Te/iT/2L19/6IegD+cX4ff8j74b/wCwnbf+jVr+oMnBb61/L58Pv+R98N/9hO2/9GrX9QZzk/WgBK+E/wDgsh/yanp//Yx2v/ouavuzua+E/wDgsh/yalp//Yx2v/ouagD8SqKKKAP0O/4Ip/8AJf8Axp/2Lh/9KYq/ZTNfjX/wRT/5L/40/wCxcP8A6UxV+yfegD8Wf+Cz3/Jzfh7/ALFqD/0fPX0R/wAESv8AkjvxE/7D0X/pOtfO/wDwWe/5Ob8Pf9izB/6Pnr6I/wCCJP8AyR34if8AYei/9J1oA+HP+CmH/J6nxE/67W//AKTx1+l3/BIr/kznTf8AsMX3/oYr80f+CmH/ACep8RP+u1v/AOk8dfpd/wAEiv8AkznTf+wxff8AoYoA+1K/mH+LH/JU/GX/AGGbz/0e9f08V/MP8WP+Sp+Mv+wzef8Ao96AP6Fv2Of+TU/hN/2LVj/6KWvYjXjn7HP/ACap8Jv+xasf/RS17HQB49+2N/yaf8Yf+xU1L/0nev5vq/pA/bG/5NP+MH/Yqal/6TvX839AHtP7Fn/J2Pwp/wCw/bf+hV+rn/BX7/k0SX/sN2f/ALPX5R/sWf8AJ2Pwp/7D9t/6FX6uf8Ffv+TRJf8AsN2f/s9AH4dV9v8A/BHr/k70/wDYu33/AKFFXxBX2/8A8Eev+TvT/wBi7ff+hRUAfuED+NfkN/wW3/5Kd8Nf+wPcf+jhX6854r8hv+C3H/JTvht/2B7j/wBHCgDr/wDgiB/x6fFT/fsf5S1+pXWvy0/4Ig/8enxU/wB+x/lLX6ljgUAHFfzz/wDBQz/k874p/wDYTH/omOv6GCK/nn/4KGf8nnfFP/sJj/0THQB+rv8AwSq/5Mx8Kf8AX3e/+jmr63uv+PWX/dP8q+Sf+CVY/wCMMfCn/X3ef+jmr62uv+Pab/dP8qAP5ePF/wDyNmt/9f0//oxq/oH8F/8AKP7RP+yZQ/8AprFfz8eL/wDkbNb/AOv6f/0Y1f0DeC/+Uf2if9kyh/8ATWKAP546KKKAPWP2S/8Ak5/4U/8AYzaf/wClCV/SSDxX8237Jf8Ayc/8Kf8AsZtP/wDShK/pJGaAPg//AILI/wDJrGmf9jDbf+i5a+GP+CSX/J5+g/8AYL1D/wBEGvuf/gsj/wAmsaZ/2MNt/wCi5a+GP+CSf/J5+g/9gvUP/RJoA+jP+C3/APx6fCn/AH7/APlDX5U1+q3/AAW//wCPX4U/79//AChr8qaAPUP2W/8Ak5H4Y/8AYxWP/o9a/Zj/AIKr/wDJlni7/r7sf/SlK/Gf9lv/AJOQ+GX/AGMVj/6OWv2Y/wCCrH/Jlni7/r7sf/SlKAPwXooooA+8/wDgjP8A8nS6z/2LN1/6Pgr0/wD4Lg/8h34Sf9e2o/8AocFeYf8ABGj/AJOl1n/sWbr/ANHQV6f/AMFwf+Q78JP+vbUf/Q4KAPy9r9Y/+CIX/It/FT/r7sf/AECWvycr9Y/+CIX/ACLfxU/6+7H/ANAloA/T4dOtLRRigD+eX/goV/yed8VP+woP/RMdfrT/AMEt/wDkyvwT/wBdbz/0oevyW/4KF/8AJ5/xV/7Cg/8ARMdfrT/wS2/5Mr8Ff9dbz/0oegD6tnP7iT/dP8q/l58Z/wDI365/1/T/APoxq/qHnH7iT/dP8q/l48Z/8jfrn/X9P/6MagD+k74B/wDJCvhzz/zLem/+ksdd329a4T4BjPwL+HP/AGLem/8ApLHXefSgD5v/AOCi5/4wq+KX/XhD/wClMNfih+xr/wAnX/CP/sZ7D/0etftf/wAFGB/xhV8U/wDrwh/9KYa/FD9jb/k6/wCEf/Yz2H/o9aAP6P8AvXwD/wAFn/8Ak2rw5/2MkX/oiavv7FfAP/BZ/wD5Nq8N/wDYxxf+iJqAPj7/AII8/wDJ2r/9gG7/AJx17p/wW/8A+PH4T/8AXTUP5Q14X/wR5/5O1b/sA3n84690/wCC3/8Ax4/Cf/rpqH8oaAOP/wCCI/8AyVH4kf8AYHt//Rxr9evzr8hf+CI//JUfiR/2B7f/ANHGv17xjigBM5r8Fv8Agql/yeh4u/69rP8A9ELX71H/ADzX4K/8FUv+T0PF3/XtZ/8AohaAPkev31/4Jef8mUeA/wDevf8A0qlr8Cq/fX/gl5/yZR4D/wB68/8ASqWgD6k1L/kH3P8A1yb+Rr+X3xh/yNut/wDX9P8A+jGr+oPUhnTrr18p/wCRr+Xzxj/yN2t/9f0//oxqAP6FbH/kyG3/AOxDX/0hr+dRvvH61/RVY/8AJkUH/Yhr/wCkNfzqt94/WgBK9Q/Zc/5OP+GX/Yw2P/o5a8vr1D9lz/k4/wCGX/Yw2P8A6OWgD+lI9TXwr/wWO/5NOsv+xjtP/Rc1fdR618K/8Fj/APk06y/7GO0/9FzUAfCf/BJH/k83Rf8AsE6h/wCia/dUfWvwq/4JI/8AJ5ui/wDYJ1D/ANE1+6wFAH5Mf8Fvf+Rq+Ff/AF5X3/oyOvzEr9O/+C3v/I1fCv8A68r3/wBGR1+YlAH6zf8ABD7/AJFX4tf9fmnf+i7ivmb/AIK4f8nmaz/2CbD/ANFV9M/8EPv+RW+LX/X5p3/ou4r5m/4K4f8AJ5ms/wDYJsP/AEVQB9xf8EZ/+TVNZ/7Gi6/9J7avy7/bX/5O1+LP/Yw3X/oZr9RP+CM//Jqms/8AY0XX/pPbV+Xf7a//ACdr8Wf+xhuv/QzQB+0P/BNz/kyv4Z/9es//AKUy1+DPxQ/5KX4t/wCwvd/+jnr95v8Agm5/yZX8Mv8Ar1n/APSmWvwZ+KH/ACUvxb/2F7v/ANHPQB/Rd+zD/wAm4/DD/sWtP/8ASdKl/aU/5N7+JPp/wj19/wCiHqL9mD/k3H4Yf9i1p/8A6TpUv7Sv/JvfxJ/7F2+/9EPQB/OL8Pv+R98N/wDYTtv/AEatf1BnqfrX8vnw+/5H3w3/ANhO2/8ARq1/UGep+tACdDXwn/wWQ5/ZS0//ALGO1/8ARc1fdvevhL/gsgMfspaf/wBjHa/+ipqAPxKooooA/Q7/AIIp/wDJf/Gn/YuH/wBKYq/ZM/Wvxs/4Ip/8l/8AGn/YuH/0pir9lMfWgD8WP+Cz3/Jzfh7/ALFqD/0fPX0R/wAESf8AkjvxE/7D0X/pOtfO/wDwWe/5Ob8Pf9i1B/6Pnr6I/wCCJP8AyR34if8AYei/9J1oA+HP+CmH/J6nxE/67W//AKTx1+l3/BIr/kznTf8AsMX3/oYr80f+CmH/ACep8RP+u1v/AOk8dfpf/wAEif8AkzrTf+wxff8AoYoA+0q/mI+LH/JU/GX/AGGbz/0e9f08dK/mH+LH/JU/GX/YZvP/AEe9AH9Cv7HP/Jqfwm/7Fqx/9FLXsR/GvHv2OR/xin8Jv+xasf8A0Utew0AePfti/wDJp/xg/wCxU1L/ANJ3r+b+v6Qf2xuP2T/jB/2Kmpf+k71/N9QB7T+xZ/ydj8Kf+w/bf+hV+rn/AAV+/wCTRJf+w3Z/zevyj/Ys/wCTsfhT/wBh+2/9Cr9XP+Cv3/Jokv8A2G7P/wBnoA/Dqvt//gj1/wAnen/sXb7/ANCir4gr7f8A+CPX/J3p/wCxevv/AEKKgD9w6/IX/gtx/wAlO+G3/YHuP/Rwr9eQOK/Ib/gtx/yU74a/9ge4/wDRwoA67/giD/x6fFT/AH7H+UtfqWK/LT/giCP9E+Kn+/Y/ylr9TMcY5oAQ8V/PP/wUM/5PO+Kf/YTH/omOv6GMV/PP/wAFDP8Ak874p/8AYTH/AKJjoA/V7/glV/yZj4U/6+73/wBHtX1tdf8AHrL/ALh/lXyR/wAEqv8AkzHwp/193v8A6Oavra55tZT/ALJ/lQB/Lz4v/wCRs1v/AK/p/wD0Y1f0DeC/+Uf2if8AZMof/TWK/n58X/8AI2a3/wBf0/8A6Mav6BvBf/KP7RP+yZQ/+msUAfzx0UUUAesfsl/8nP8Awp/7GbT/AP0oSv6Sa/m2/ZL/AOTn/hT/ANjNp/8A6UJX9JQ6UAfB3/BZE5/ZY03/ALGG2/8ARctfDH/BJL/k8/Qf+wXqH/og19z/APBZH/k1jTf+xhtv/RctfDH/AASS/wCTz9B/7Beof+iDQB9Gf8Fv/wDj0+FP+/f/AMoa/Kmv1W/4Lf8A/Hr8Kf8Afv8A+UNflTQB6h+y3/ych8Mf+xisf/Ry1+zH/BVj/kyzxd/192P/AKUJX4z/ALLf/JyHwx/7GKx/9HrX7Mf8FWP+TLPF3/X3Y/8ApSlAH4L0UUUAfef/AARn/wCTpdZ/7Fm6/wDR8Fen/wDBcH/kO/CX/r21H/0OCvMP+CM//J02s/8AYs3X/o+CvT/+C4P/ACHfhJ/17aj/AOhwUAfl7X6x/wDBEL/kW/ip/wBfdj/6BLX5OV+sf/BEL/kW/ip/192P/oEtAH6fdvel4pOnOaXpQB/PL/wUL/5PO+Kn/YUH/omOv1p/4Jb/APJlfgn/AK7Xn/pQ9fkt/wAFC/8Ak8/4q/8AYUH/AKJjr9af+CW//Jlfgr/rref+lD0AfVtx/qJP90/yr+Xnxn/yN+uf9f0//oxq/qGnP7iUf7J/lX8vPjP/AJG/XP8Ar+n/APRjUAf0nfAM4+BXw5/7FvTf/SWOu7+tcJ8A/wDkhXw5/wCxb03/ANJY67vNAHzh/wAFF/8Akyr4p/8AXhD/AOlMNfih+xt/ydf8I/8AsZ7D/wBHrX7X/wDBRf8A5Mq+Kf8A14Q/+lMNfih+xt/ydf8ACP8A7Gew/wDR60Af0fk18A/8Fn/+TavDn/Yxxf8Aoiavv4/jXwD/AMFn/wDk2rw5/wBjHF/6ImoA+Pv+CPP/ACdq3/YBu/5x17p/wW//AOPH4T/9dNQ/lDXhf/BHn/k7Zv8AsA3f84690/4Lf/8AHj8J/wDrpqH8oaAOP/4Ij/8AJUfiR/2B7f8A9HGv16r8hf8AgiP/AMlR+JH/AGB7f/0ca/XvOKAEya/Bb/gql/yeh4u/69rP/wBELX71Yr8Ff+CqX/J6Hi7/AK9rP/0QtAHyPX76/wDBLz/kyjwH/vXv/pVLX4FV++v/AAS8/wCTKPAn+9e/+lUtAH1JqP8AyD7r/rk/8jX8vvjD/kbdb/6/p/8A0Y1f1Baif+Jdc/8AXJv5Gv5ffGH/ACNut/8AX9P/AOjGoA/oUsf+TIrf/sQ1/wDSGv51W+8frX9FVj/yZFB/2Ia/+kNfzqt94/WgBK9Q/Zc/5OP+GX/Yw2P/AKOWvL69Q/Zc/wCTj/hl/wBjDY/+jloA/pSPWvhX/gsdz+ydZf8AYx2n/ouavuo9TXwr/wAFjv8Ak0+y/wCxjtP/AEXNQB8J/wDBJH/k83Rf+wTqH/omv3VB6V+FX/BJH/k8zRf+wTqH/omv3VHbNAH5M/8ABb3/AJGr4V/9eV9/6Mjr8xK/Tv8A4Le/8jV8K/8Aryvv/RkdfmJQB+sv/BD7/kV/i1/1+ad/6BcV8z/8FcP+TzNZ/wCwTYf+iq+mf+CH3/IrfFr/AK/NO/8ARdxXzN/wVw/5PM1n/sE2H/oqgD7i/wCCM/8AyaprP/Y0XX/pPbV+Xf7a/wDydr8Wf+xhuv8A0M1+on/BGf8A5NU1n/saLr/0ntq/Lv8AbX/5O1+LP/Yw3X/oZoA/aH/gm5/yZZ8Mv+vWf/0plr8Gfih/yUvxb/2F7v8A9HPX7y/8E3P+TLPhl/16z/8ApTLX4NfFD/kpfi3/ALC93/6OegD+i79mH/k3H4Yf9i1p/wD6TpUv7Sn/ACb38Sf+xdv/AP0Q1Rfsw/8AJuPww/7FrT//AEnSpf2lD/xj58Sf+xdv/wD0Q1AH84vw+/5H3w3/ANhO2/8ARq1/UHnk/Wv5fPh9/wAj74b/AOwnbf8Ao1a/qDJ5P1oASvhP/gsh/wAmpaf/ANjHa/8AouavuyvhT/gsj/yalp//AGMdr/6KmoA/EmiiigD9Dv8Agin/AMl/8af9i4f/AEpir9k6/Gz/AIIp/wDJf/Gn/YuH/wBKYq/ZPrQB+LP/AAWe/wCTm/D3/YtQf+j56+iP+CJX/JHfiJ/2Hov/AEnWvnf/AILPf8nN+Hv+xZg/9Hz19Ef8ESv+SO/ET/sPRf8ApOtAHw5/wUw/5PU+In/Xa3/9J46/S7/gkV/yZzp3/YYvv/QxX5o/8FMP+T1PiJ/12t//AEnjr9Lv+CRX/JnOnf8AYYvv/QxQB9p9a/mI+LH/ACVPxl/2Gr3/ANHvX9O9fzEfFj/kqfjL/sM3n/o96AP6Fv2Of+TU/hN/2LVj/wCilr2HPPWvHf2Of+TU/hN/2LVj/wCilr2PPNAHjv7Yp/4xP+MH/Yqal/6TvX839f0gftjc/sn/ABg/7FTUv/Sd6/m/oA9p/Ys/5Ox+FP8A2H7b/wBCr9XP+Cv3/Jokv/Ybs/5vX5R/sWf8nY/Cn/sP23/oVfq5/wAFfv8Ak0WX/sN2f83oA/Dqvt//AII9f8nen/sXr7/0KKviCvt//gj1/wAnen/sXr7/ANCioA/cLNfkN/wW4/5Kd8Nf+wPcf+jhX6854r8hv+C3H/JTvht/2B7j/wBHCgDrv+CIP/Hr8VP9+x/lLX6l1+Wn/BEH/j1+Kn+/Y/ylr9TKAEzX88//AAUM/wCTzvin/wBhMf8AomOv6GPWv55/+Chn/J53xT/7CY/9Ex0Afq9/wSq/5Mx8Kf8AX3e/+j2r62uv+PWb/cP8q+Sf+CVY/wCMMfCn/X3e/wDo5q+tro/6LL/un+VAH8vHi/8A5GzW/wDr+n/9GNX9A/gr/lH9on/ZMof/AE1iv5+PF/8AyNmt/wDX9P8A+jGr+gfwX/yj+0T/ALJlD/6axQB/PFRRRQB6x+yX/wAnP/Cn/sZtP/8AShK/pJ7V/Nt+yX/yc/8ACn/sZtP/APShK/pJ96APg/8A4LI/8msab/2MNt/6Llr4Y/4JJ/8AJ5+g/wDYL1D/ANEGvuf/AILI8/ssaZ/2MNt/6Llr4Y/4JJ/8nn6D/wBgvUP/AEQaAPo3/guB/wAenwp/37/+UNflRX6r/wDBb/8A49PhT/v3/wDKGvyooA9R/Za/5OR+GP8A2MVj/wCj1r9l/wDgqv8A8mWeLv8Ar7sf/SlK/Gf9lv8A5OR+GP8A2MVj/wCjlr9l/wDgqv8A8mWeLv8Ar7sf/SlKAPwYooooA+8/+CM//J0us/8AYs3X/o+CvT/+C4P/ACHfhL/17aj/AOhwV5h/wRn/AOTpdZ/7Fm6/9HwV6f8A8Fwf+Q78Jf8Ar21H/wBDgoA/L2v1i/4Ihf8AItfFT/r7sf8A0CWvydr9Y/8AgiF/yLfxU/6+7H/0CWgD9Pu9HOKKM8ZoA/nm/wCChf8Ayed8VP8AsKD/ANEx1+tP/BLj/kyvwV/11vP/AEoevyW/4KF/8nnfFT/sKD/0THX60/8ABLf/AJMr8Ff9drz/ANKHoA+rZ+IJP90/yr+Xnxn/AMjfrn/X9P8A+jGr+oaf/USf7p/lX8vPjP8A5G/XP+v6f/0Y1AH9J3wD/wCSFfDnn/mW9N/9JY67sVwnwE/5IV8Of+xb03/0ljruxzigD5w/4KLj/jCr4p/9eEP/AKUw1+KH7G3/ACdf8I/+xnsP/R61+1//AAUXP/GFXxT/AOvCH/0phr8UP2Nv+Tr/AIR/9jPYf+j1oA/o+PHOa+Av+Cz/APybV4c/7GOL/wBETV9/Z5r4B/4LP/8AJtXhv/sY4v8A0RNQB8ff8Eef+Ttm/wCwDd/zjr3T/gt//wAePwn/AOumofyhrwv/AII8/wDJ2rf9gG8/nHXun/Bb/wD48fhP/wBdNQ/lDQBx/wDwRH/5Kj8SP+wPb/8Ao41+vWM1+Qv/AARH4+KPxJ/7A9v/AOjjX69ZzigA/GvwW/4Kpf8AJ6Hi7/r2s/8A0QtfvTnAr8Fv+CqX/J6Hi7/r2s//AEQtAHyPX76/8EvP+TKPAf8Av3v/AKVS1+BVfvr/AMEvP+TKPAn+9e/+lUtAH1JqP/IPuv8Ark38jX8vvjD/AJG3W/8Ar+n/APRjV/UFqX/IPuf+uTfyNfy++MP+Rt1v/r+n/wDRjUAf0KWP/JkVv/2IS/8ApDX86rfeP1r+iqx/5Mig/wCxDX/0hr+dVvvH60AJXqH7Ln/Jx/wy/wCxhsf/AEcteX16h+y5/wAnH/DL/sYbH/0ctAH9KTdTXwp/wWO/5NOsv+xjtP8A0XNX3W3X1r4V/wCCx3/Jp1l/2Mdp/wCi5qAPhP8A4JI/8nm6L/2CdQ/9E1+6or8Kv+CSP/J5ui/9gnUP/RNfurnpQB+TP/Bb3/kavhX/ANeV9/6Mjr8xK/Tv/gt7/wAjV8K/+vK+/wDRkdfmJQB+s3/BD7/kVvi1/wBfunf+i7ivmb/grh/yeZrP/YJsP/RVfTP/AAQ+/wCRW+LX/X5p3/ou4r5m/wCCuH/J5ms/9gmw/wDRVAH3F/wRn/5NU1n/ALGi6/8ASe2r8u/21/8Ak7X4s/8AYw3X/oZr9RP+CM//ACaprP8A2NF1/wCk9tX5d/tr/wDJ2vxZ/wCxhuv/AEM0AftB/wAE3P8Akyv4Zf8AXrP/AOlMtfg18UP+Sl+Lf+wvd/8Ao56/eX/gm5/yZX8Mv+vWf/0plr8Gvih/yUvxb/2F7v8A9HPQB/Rd+zD/AMm4fDD/ALFrT/8A0nSpf2lP+TfPiT/2Lt//AOiGqL9mH/k3H4Yf9i1p/wD6TpUv7Sn/ACb58Sf+xev/AP0Q1AH84vw+/wCR98N/9hO2/wDRq1/UEepr+X34ff8AI++G/wDsJ23/AKNWv6gz1P1oATvXwn/wWQ/5NS0//sY7X/0VNX3Yfyr4T/4LIc/spaf/ANjHa/8AoqagD8SqKKKAP0O/4Ip/8l/8af8AYuH/ANKYq/ZPFfjZ/wAEU/8Akv8A40/7Fw/+lMVfsmO3NAH4s/8ABZ7/AJOb8Pf9izB/6Pnr6I/4Ilf8kd+In/Yei/8ASda+d/8Ags9/yc34e/7FmD/0fPX0R/wRK/5I78RP+w9F/wCk60AfDn/BTD/k9T4if9drf/0njr9Lv+CRX/JnOm/9hi+/9DFfmj/wUw/5PU+In/Xa3/8ASeOv0u/4JFf8mc6d/wBhi+/9DFAH2mBiv5iPix/yVPxl/wBhm8/9HvX9O+c1/MR8WP8AkqfjL/sM3n/o96AP6Ff2OT/xip8Jf+xasf8A0UtewnqcV4/+xz/yan8Jv+xasf8A0Utew559KAPHv2xRj9k/4wf9ipqX/pO9fzf1/SB+2Nz+yf8AGD/sVNS/9J3r+b+gD2n9iz/k7H4U/wDYftv/AEKv1c/4K/f8miS/9huz/m9flH+xZ/ydj8Kf+w/bf+hV+rn/AAV+/wCTRZf+w3Z/+z0Afh1X2/8A8Eev+Tvf+5evv/Qoq+IK+3/+CPX/ACd6f+xevv8A0KKgD9wa/If/AILcf8lO+Gv/AGB7j/0cK/XnPFfkN/wW4/5Kd8Nv+wPcf+jhQB13/BEH/j0+Kn+/Y/ylr9SxX5af8EQf+PT4qf79j/KWv1Lz3oAPxr+ef/goZ/yed8U/+wmP/RMdf0MZr+ef/goZ/wAnnfFP/sJj/wBEx0Afq9/wSr5/Yw8Kf9fd5/6Oavra6H+jTf7h/lXyT/wSr4/Yx8Kf9fd7/wCjmr62uv8Aj1m/3T/KgD+Xjxf/AMjZrf8A1/T/APoxq/oG8F/8o/tE/wCyZQ/+msV/Pz4v/wCRs1v/AK/p/wD0Y1f0DeC/+Uf2if8AZMof/TWKAP546KKKAPWP2S/+Tn/hT/2M2n/+lCV/STxX8237Jf8Ayc/8Kf8AsZtP/wDShK/pJNAHwf8A8FkP+TWNN/7GG2/9Fy18Mf8ABJP/AJPP0H/sF6h/6INfc/8AwWQ/5NY03/sYbb/0XLXwx/wSS/5PP0H/ALBeof8Aog0AfRn/AAW//wCPX4U/79//AChr8qa/Vb/gt/8A8evwp/37/wDlDX5U0Aeo/st/8nI/DH/sYrH/ANHLX7Lf8FV/+TLPF3/X3Y/+lKV+NH7Lf/JyHwy/7GKx/wDRy1+zH/BVf/kyzxd/1+WP/pQlAH4L0UUUAfef/BGf/k6XWf8AsWbr/wBHwV6f/wAFwf8AkO/CX/r21H/0OCvMP+CM/wDydLrP/Ys3X/o+CvT/APguD/yHfhL/ANe2o/8AoUFAH5e1+sf/AARC/wCRb+Kn/X3Y/wDoEtfk5X6x/wDBEL/kWvip/wBfdj/6BLQB+n2KMfhQKM8UAfzzf8FC/wDk874qf9hQf+iY6/Wn/glv/wAmV+Cv+ut5/wClD1+S3/BQv/k874q/9hQf+iY6/Wn/AIJb/wDJlfgr/rref+lD0AfVs+PIk/3T/Kv5efGf/I365/1/T/8Aoxq/qGn/ANRJ/un+Vfy8+M/+Rv1z/r+n/wDRjUAf0nfAP/khXw5/7FvTf/SWOu7GK4T4Cf8AJCvhzx/zLem/+ksdd3igD5w/4KMf8mVfFP8A68If/SmGvxQ/Y2/5Ov8AhH/2M9h/6PWv2v8A+Ci4/wCMK/in/wBeEP8A6Uw1+KH7G3/J1/wj/wCxnsP/AEetAH9H5FfAP/BZ/wD5Nq8Of9jHF/6Imr79J5NfAX/BZ7/k2rw3/wBjHF/6ImoA+Pv+CPP/ACds3/YBu/5x17p/wW//AOPH4T/9dNQ/lDXhf/BHn/k7Zv8AsA3f84690/4Lf/8AHj8J/wDrpqH8oaAOP/4Ij/8AJUfiR/2B7f8A9HGv16xX5C/8ESP+So/En/sD2/8A6ONfr1+FABj1r8Fv+CqX/J6Hi7/r2s//AEQtfvTyBX4Lf8FUv+T0PF3/AF7Wf/ohaAPkev32/wCCXn/JlHgT/evf/SqWvwJr99f+CXn/ACZR4D/3r3/0qloA+pNRx/Z91/1yb+Rr+X3xh/yNut/9f0//AKMav6gtS/5B9zx/yyb+Rr+X3xh/yNut/wDX9P8A+jGoA/oUsf8AkyGD/sQ1/wDSGv51W+8frX9FVj/yZFB/2IS/+kNfzqt94/WgBK9Q/Zc/5OP+GX/Yw2P/AKOWvL69Q/Zc/wCTj/hl/wBjDY/+jloA/pSYc18K/wDBY4Y/ZOsv+xjtP/Rc1fdTda+Ff+Cx3/Jp9l/2Mdp/6LmoA+E/+CSP/J5mi/8AYJ1D/wBE1+6oAxX4Vf8ABJH/AJPN0X/sE6h/6Jr91e3SgD8mP+C3v/I1fCv/AK8r7/0ZHX5i1+nf/Bb3/kavhX/15X3/AKMjr8xKAP1m/wCCH3/IrfFr/r807/0XcV8zf8FcP+TzNZ/7BNh/6Kr6Z/4Iff8AIrfFr/r807/0XcV8zf8ABXD/AJPM1n/sE2H/AKKoA+4v+CM//Jqms/8AY0XX/pPbV+Xf7a//ACdr8Wf+xhuv/QzX6if8EZ/+TVNZ/wCxouv/AEntq/Lv9tf/AJO1+LP/AGMN1/6GaAP2h/4Juf8AJlfwz/69Z/8A0plr8Gfih/yUvxb/ANhe7/8ARz1+8v8AwTc/5Mr+GX/XrP8A+lMtfg18UP8Akpfi3/sL3f8A6OegD+i79mH/AJNx+GH/AGLWn/8ApOlS/tKf8m9/En/sXr7/ANEPUX7MP/JuPww/7FrT/wD0nSpf2lB/xj58Sf8AsXb/AP8ARDUAfzi/D7/kffDf/YTtv/Rq1/UEep+tfy+/D7/kffDf/YTtv/Rq1/UE33jQAfhXwn/wWR/5NS0//sY7X/0VNX3Z3r4T/wCCyH/JqWn/APYx2v8A6KmoA/EqiiigD9Dv+CKf/Jf/ABp/2Lh/9KYq/ZMDpX42f8EU/wDkv/jT/sXD/wClMVfsn2oA/Fn/AILPf8nN+Hv+xag/9Hz19Ef8ESf+SO/ET/sPRf8ApOtfO/8AwWe/5Ob8Pf8AYtQf+j56+iP+CJX/ACR34if9h6L/ANJ1oA+HP+CmH/J6nxE/67W//pPHX6Xf8Eiv+TOdN/7DF9/6GK/NH/gph/yep8RP+u1v/wCk8dfpd/wSK/5M503/ALDF9/6GKAPtTpX8w/xY/wCSp+Mv+w1e/wDo96/p3AxX8xHxY/5Kn4y/7DN5/wCj3oA/oV/Y5/5NT+Ev/YtWP/opa9iIFeO/sc/8mp/CX/sWrH/0Utexdz3oA8e/bF4/ZP8AjB/2Kmpf+k71/N/X9IH7YvH7J/xh/wCxU1L/ANJ3r+b+gD2n9iz/AJOx+FP/AGH7b/0Kv1c/4K/f8miy/wDYbs/5vX5R/sWf8nY/Cn/sP23/AKFX6uf8Ffv+TRJf+w3Z/wA3oA/Dqvt//gj1/wAnen/sXr7/ANCir4gr7f8A+CPX/J3p/wCxevv/AEKKgD9wu3avyG/4Lb/8lO+Gv/YHuP8A0cK/XntX5Df8Ft/+SnfDX/sD3H/o4UAdd/wRB/49fip/v2P8pa/Uuvy0/wCCIX/Hp8VP9+x/lLX6l84NABiv55/+Chn/ACed8U/+wmP/AETHX9C9fz0f8FDP+Tzvin/2Ex/6JjoA/V7/AIJV/wDJmPhT/r7vf/RzV9bXX/HtL/uH+VfJP/BKs/8AGGPhT/r7vP8A0c1fW11/x7Tf7p/lQB/Lx4v/AORs1v8A6/p//RjV/QN4K/5R/wCif9kyh/8ATWK/n58X/wDI2a3/ANf0/wD6Mav6B/Bf/KP7RP8AsmUP/prFAH88VFFFAHrH7Jf/ACc/8Kf+xm0//wBKEr+kntX8237Jf/Jz/wAKf+xm0/8A9HpX9JOM0AfB/wDwWR/5NZ0z/sYbb/0XLXwx/wAEkv8Ak8/Qf+wXqH/og19z/wDBZD/k1jTf+xhtv/RctfDH/BJL/k8/Qf8AsF6h/wCiDQB9Gf8ABb//AI9fhT/v3/8AKGvypr9Vv+C3/wDx6/Cn/fv/AOUNflTQB6h+y3/ycj8Mf+xisf8A0ctfsx/wVX/5Ms8Xf9fdj/6UJX4z/st/8nI/DH/sYrH/ANHLX7Mf8FV/+TLPF3/X3Y/+lKUAfgvRRRQB95/8EZ/+TpdZ/wCxZuv/AEfBXp//AAXB/wCQ78Jf+vbUf/Q4K8w/4Iz/APJ0us/9izdf+j4K9P8A+C4POu/CX/r21H/0OCgD8va/WP8A4Ihf8i38VP8Ar7sf/QJa/Jyv1i/4Ihf8i18VP+vux/8AQJaAP0/GM4xRx6UYoIoA/nm/4KF/8nnfFX/sKD/0THX60/8ABLf/AJMr8Ff9dbz/ANKHr8lv+Chf/J53xV/7Cg/9Ex1+tP8AwS3/AOTK/BP/AF2vP/Sh6APq2c/uJP8AdP8AKv5efGf/ACN+uf8AX9P/AOjGr+oaf/USf7p/lX8vPjP/AJG/XP8Ar+n/APRjUAf0nfAP/khfw4/7FvTf/SWOu76GuE+AfPwL+HP/AGLem/8ApLHXd4oA+cP+CjH/ACZV8U/+vCH/ANKYa/FD9jb/AJOv+Ef/AGM9h/6PWv2v/wCCi/8AyZV8U/8Arwh/9KYa/FD9jb/k6/4R/wDYz2H/AKPWgD+j8ivgH/gs/wA/s1eG/wDsY4v/AERNX38eOa+Af+Cz/wDybV4b/wCxji/9ETUAfH3/AAR5/wCTtW/7AN5/OOvdP+C3/wDx4/Cf/rpqH8oa8L/4I8/8nbN/2Abv+cde6f8ABb//AI8fhP8A9dNQ/lDQBx//AARH/wCSo/Ej/sD2/wD6ONfr3n8a/IT/AIIj/wDJUfiR/wBge3/9HGv16xj1oAK/Bb/gql/yeh4u/wCvaz/9ELX704r8Fv8Agql/yeh4u/69rP8A9ELQB8j1++3/AAS8/wCTKPAf+/e/+lUtfgTX76/8EvP+TKPAn+9ef+lUtAH1LqP/ACDrr/rk38jX8vnjD/kbdb/6/p//AEY1f1BakP8AiX3X/XJv5Gv5ffGH/I263/1/T/8AoxqAP6FLH/kyGD/sQ1/9Ia/nVb7x+tf0VWP/ACZFB/2IS/8ApDX86rfeP1oASvUP2XP+Tj/hl/2MNj/6OWvL69Q/Zc/5OP8Ahl/2MNj/AOjloA/pSPWvhX/gscAP2TrLH/Qx2n/ouavuk/er4W/4LHf8mn2X/Yx2n/ouagD4T/4JI/8AJ5ui/wDYJ1D/ANE1+6vpmvwq/wCCSP8Ayebov/YJ1D/0TX7qgUAfkz/wW9/5Gr4V/wDXlff+jI6/MSv07/4Le/8AI1fCv/ryvv8A0ZHX5iUAfrL/AMEPv+RX+LX/AF+ad/6LuK+Z/wDgrh/yeZrP/YJsP/RVfTP/AAQ+/wCRV+LX/X5p3/ou4r5m/wCCuH/J5ms/9gmw/wDRVAH3F/wRn/5NU1n/ALGi6/8ASe2r8u/21/8Ak7X4s/8AYw3X/oZr9RP+CM//ACaprP8A2NF1/wCk9tX5d/tr/wDJ2vxZ/wCxhuv/AEM0AftD/wAE3P8Akyv4Zf8AXrP/AOlMtfgz8UP+Sl+Lf+wvd/8Ao56/eX/gm5/yZX8Mv+vWf/0plr8Gvih/yUvxb/2F7v8A9HPQB/Rd+zBj/hnH4YZ/6FrT/wD0nSpf2lP+TfPiT/2L19/6Iaov2YRn9nH4Yf8AYtaf/wCk6VL+0p/yb58Sf+xev/8A0Q9AH84vw+/5H3w3/wBhO2/9GrX9QR6n61/L78Pv+R98N/8AYTtv/Rq1/UERyaAFGDXwl/wWR/5NS0//ALGO1/8ARc1fdlfCf/BZAY/ZS0//ALGO1/8ARU1AH4lUUUUAfod/wRT/AOS/+NP+xcP/AKUxV+yfAr8bP+CKf/Jf/Gn/AGLh/wDSmKv2TFAH4s/8Fnv+Tm/D3/YtQf8Ao+evoj/giV/yR34if9h6L/0nWvnf/gs9/wAnN+Hv+xag/wDR89fRH/BEr/kjvxE/7D0X/pOtAHw5/wAFMP8Ak9T4if8AXa3/APSeOv0v/wCCRP8AyZzpv/YYvv8A0MV+aH/BTD/k9T4if9drf/0njr9Lv+CRX/JnOm/9hi+/9DFAH2pnrX8w/wAWP+Sp+Mv+wzef+j3r+ncDjNfzEfFj/kqfjL/sM3n/AKPegD+hb9jn/k1P4Tf9i1Y/+ilr2L8K8c/Y5/5NU+E3/YtWP/opa9iI96APH/2xv+TT/jB/2Kmpf+k71/N9X9IH7Yox+yf8YP8AsVNS/wDSd6/m/oA9p/Ys/wCTsfhT/wBh+2/9Cr9XP+Cv/wDyaLL/ANhuz/8AZ6/KP9iz/k7H4U/9h+2/9Cr9XP8Agr9/yaJL/wBhuz/m9AH4dV9v/wDBHr/k73/uXr7/ANCir4gr7f8A+CPX/J3p/wCxdvv/AEKKgD9whgivyG/4Lcf8lO+G3/YHuP8A0cK/Xnt1r8hv+C2//JTvhr/2B7j/ANHCgDrv+CIP/Hp8VP8Afsf5S1+pgNfln/wRC/49Pip/v2P8pa/UsDigBT3r+eb/AIKGf8nnfFP/ALCY/wDRMdf0MY681/PP/wAFDP8Ak874p/8AYTH/AKJjoA/V7/glXx+xj4U/6+73/wBHNX1tdn/Rpv8AcP8AKvkn/glXz+xh4U/6+73/ANHNX1tdf8e03+4f5UAfy8eL/wDkbNb/AOv6f/0Y1f0D+C/+Uf2if9kyh/8ATWK/n48X/wDI2a3/ANf0/wD6Mav6B/Bf/KP7RP8AsmUP/prFAH88VFFFAHrH7Jf/ACc/8Kf+xm0//wBKEr+kn8K/m2/ZL/5Of+FP/Yzaf/6UJX9JHGaAPhD/AILI/wDJrGmf9jDbf+i5a+GP+CSX/J5+g/8AYL1D/wBEGvuf/gsj/wAmsab/ANjDbf8AouWvhj/gkl/yefoP/YL1D/0QaAPo3/guB/x6fCn/AH7/APlDX5UV+q3/AAW//wCPT4U/79//AChr8qaAPUP2W/8Ak5D4Y/8AYxWP/o5a/Zf/AIKr/wDJlni7/r8sf/ShK/Gj9lv/AJOR+GP/AGMVj/6OWv2Y/wCCrH/Jlni7/r7sf/SlKAPwXooooA+8/wDgjP8A8nS6z/2LN1/6Pgr0/wD4Lg/8h34Sf9e2o/8AocFeYf8ABGf/AJOm1n/sWbr/ANHwV6f/AMFwf+Q78JP+vbUf/Q4KAPy9r9Y/+CIX/It/FT/r7sf/AECWvycr9Y/+CIX/ACLfxU/6+7H/ANAloA/T4GjPvR19s0f5xQB/PN/wUL/5PO+Kv/YUH/omOv1o/wCCXH/Jlfgn/rtef+lD1+S//BQv/k8/4qf9hQf+iY6/Wn/glv8A8mV+Cf8Artef+lD0AfVs/wDqJP8AdP8AKv5efGf/ACN+uf8AX9P/AOjGr+oaf/USem0/yr+Xnxn/AMjfrn/X9P8A+jGoA/pO+AZx8C/hx/2Lem/+ksdd31NcJ8A/+SF/Dn/sW9N/9JY67zigD5v/AOCi/wDyZV8Uv+vCH/0phr8UP2Nv+Tr/AIR/9jPYf+j1r9r/APgovz+xV8U/+vCH/wBKYa/FD9jb/k6/4R/9jPYf+j1oA/o+Jr4C/wCCz/8AybV4b/7GOL/0RNX38a+Af+Cz/wDybV4c/wCxji/9ETUAfHv/AAR6/wCTtX/7AN5/OOvdf+C3/wDx4/Cf/rpqH8oa8L/4I8/8nbN/2Abv+cde6f8ABb//AI8fhP8A9dNQ/lDQBx//AARH/wCSo/Ej/sD2/wD6ONfr1mvyF/4Ij/8AJUfiR/2B7f8A9HGv166UAGeK/Bb/AIKpf8noeLf+vaz/APRC1+9Wa/BX/gql/wAnoeLv+vaz/wDRC0AfI9fvr/wS8/5Mo8B/717/AOlUtfgVX77f8EvP+TKPAf8AvXv/AKVS0AfUepH/AIl91/1yb+Rr+X3xh/yNut/9f0//AKMav6gtR/5B91/1yb+Rr+X3xh/yNut/9f0//oxqAP6FLE/8YRQf9iGv/pDX86rfeP1r+iuy/wCTIbf/ALEJf/SGv51G+8frQAleofsuf8nH/DL/ALGGx/8ARy15fXqH7Ln/ACcf8Mv+xhsf/Ry0Af0on71fC3/BY7/k0+y/7GO0/wDRc1fdR618K/8ABY7/AJNOsv8AsY7T/wBFzUAfCX/BJL/k8zRf+wTqH/omv3Wz0r8Kv+CSP/J5ui/9gnUP/RNfuqD0oA/Jn/gt7/yNXwr/AOvK+/8ARkdfmJX6d/8ABb3/AJGr4V/9eV9/6Mjr8xKAP1m/4Iff8it8Wv8Ar807/wBF3FfM3/BXD/k8zWf+wTYf+iq+mf8Agh9/yK3xa/6/NO/9F3FfM3/BXD/k8zWf+wTYf+iqAPuL/gjP/wAmqaz/ANjRdf8ApPbV+Xf7a/8Aydr8Wf8AsYbr/wBDNfqJ/wAEZ/8Ak1TWf+xouv8A0ntq/Lv9tf8A5O1+LP8A2MN1/wChmgD9oP8Agm5/yZX8Mv8Ar1n/APSmWvwa+KH/ACUvxb/2F7v/ANHPX7zf8E3P+TK/hl/16z/+lMtfgz8UP+Sl+Lf+wvd/+jnoA/ou/ZgOP2cfhh/2LWn/APpOlS/tKH/jHz4k/wDYu3//AKIeov2YP+Tcvhh/2LWn/wDpOlS/tKf8m9/Ej/sXb7/0Q9AH84vw+/5H3w3/ANhO2/8ARq1/UETyfrX8vvw+/wCR98N/9hO2/wDRq1/UFxk/WgAzzXwn/wAFkOf2UtP/AOxjtf8A0VNX3bXwl/wWQ/5NT0//ALGO1/8ARU1AH4lUUUUAfod/wRT/AOS/+NP+xcP/AKUxV+yec1+Nn/BFP/kv/jT/ALFw/wDpTFX7KUAfix/wWe/5Ob8Pf9i1B/6Pnr6I/wCCJX/JHfiJ/wBh6L/0nWvnf/gs9/yc34e/7FqD/wBHz19Ef8ESv+SO/ET/ALD0X/pOtAHw5/wUw/5PU+In/Xa3/wDSeOv0u/4JFf8AJnOm/wDYYvv/AEMV+aP/AAUw/wCT1PiJ/wBdrf8A9J46/S7/AIJFf8mc6b/2GL7/ANDFAH2nxzX8xHxY/wCSp+Mv+wzef+j3r+njPFfzD/Fj/kqfjL/sM3n/AKPegD+hb9jn/k1P4Tf9i1Y/+ilr2En3xXj37HP/ACan8JvT/hGrH/0UtexUAeO/tinP7J/xg/7FTUv/AEnev5v6/pA/bGGP2T/jD/2Kmpf+k71/N/QB7T+xZ/ydj8Kf+w/bf+hV+rn/AAV+/wCTRJf+w3Z/zevyj/Ys/wCTsfhT/wBh+2/9Cr9XP+Cv/wDyaLL/ANhuz/m9AH4dV9v/APBHr/k73/uXr7/0KKviCvt//gj1/wAnen/sXr7/ANCioA/cLPHNfkN/wW4/5Kd8Nv8AsD3H/o4V+vPavyG/4Lcf8lO+G3/YHuP/AEcKAOu/4Ig8WnxU/wB+x/lLX6lg4Fflp/wRB/49fip/v2P8pa/UwUAISK/nn/4KGf8AJ53xT/7CY/8ARMdf0MGv55/+Chn/ACed8U/+wmP/AETHQB+r3/BKv/kzDwp/193n/o5q+trrH2Wb/cP8q+Sf+CVfH7GPhT/r7vf/AEc1fW11/wAe03+4f5UAfy8eL/8AkbNb/wCv6f8A9GNX9A3gv/lH9on/AGTKH/01iv5+fF//ACNmt/8AX9P/AOjGr+gfwX/yj+0T/smUP/prFAH88VFFFAHrH7Jf/Jz/AMKf+xm0/wD9KEr+kj15r+bf9kv/AJOf+FP/AGM2n/8ApQlf0k5xQB8H/wDBZE5/ZY03/sYbb/0XLXwx/wAEk/8Ak8/Qf+wXqH/ok190f8Fkf+TWNM/7GG2/9Fy18L/8Ekv+Tz9B/wCwXqH/AKINAH0Z/wAFv/8Aj0+FP+/f/wAoa/Kmv1W/4Lf/APHp8Kf9+/8A5Q1+VNAHqH7Lf/JyHwy/7GKx/wDRy1+zH/BVf/kyzxd/192P/pQlfjP+y3/ych8Mv+xisf8A0ctfsv8A8FWGx+xb4uBP/L3Y/wDpSlAH4MUUUUAfef8AwRn/AOTpdZ/7Fm6/9HwV6f8A8Fwf+Q78Jf8Ar21H/wBDgrzD/gjQQP2ptY56+Gbr/wBHQV6f/wAFwf8AkO/CX/r21H/0OCgD8va/WP8A4Ihf8i18VP8Ar7sf/QJa/Jyv1i/4Ihf8i38VPX7XY/8AoEtAH6fjrQaO+KD9KAP55v8AgoX/AMnnfFX/ALCg/wDRMdfrR/wS4/5Mr8E/9dbz/wBKHr8lv+ChRB/bO+KmDn/iaD/0THX60/8ABLcj/hizwT3PnXn/AKUPQB9XT/6iT/dP8q/l58Z/8jfrn/X9P/6Mav6hp/8AUSf7p/lX8vPjLnxfrn/X9P8A+jGoA/pO+Ag/4sV8Of8AsW9N/wDSWOu7FcJ8A8H4FfDn/sW9N/8ASWOu770AfOH/AAUX/wCTK/in/wBeEP8A6Uw1+KH7G3/J1/wj/wCxnsP/AEetftf/AMFF8D9ir4pdv9Ah/wDSmGvxQ/Y2IH7V/wAI88f8VPp//o9aAP6PjmvgL/gs/wD8m1eG/wDsY4v/AERNX36cGvgH/gs//wAm1eGx/wBTHF/6ImoA+P8A/gjz/wAnat/2Abz+cde6f8Fv/wDjx+E//XTUP5Q14V/wR6OP2tW99BvP5pXuv/Bb8/6F8KPXzNQ/lDQBx/8AwRH/AOSo/Ej/ALA9v/6ONfr1yc1+Qv8AwRHOPil8SOef7Ht//Rxr9ejQAYr8Fv8Agql/yeh4u/69rP8A9ELX7054r8Ff+CqJB/bQ8XY5/wBGs/8A0QtAHyRX76/8EvP+TJ/An+9e/wDpVLX4FV++v/BLw/8AGFHgT/evf/SqWgD6k1I/8S+6/wCuTfyNfy++MP8Akbdb/wCv6f8A9GNX9QWpHGnXWf8Ank38jX8vvjD/AJG3W/8Ar+n/APRjUAf0KWP/ACZFb/8AYhL/AOkNfzqt94/Wv6KbJgP2IYDkf8iGv/pDX86zfeP1oASvUP2XP+Tj/hl/2MNj/wCjlry+vUP2XOP2j/hn/wBjDY/+jloA/pSPDV8K/wDBY7/k0+y/7GO0/wDRc1fdR+9Xwp/wWOP/ABifZDv/AMJHaf8AouagD4U/4JI/8nm6L/2CdQ/9E1+6ozX4U/8ABJI4/bN0TPH/ABKtQ/8ARJr91qAPyZ/4Le/8jV8K/wDryvv/AEZHX5iV+nf/AAW9/wCRr+FY7/Yr7/0ZHX5iUAfrN/wQ+/5Fb4tf9fmnf+i7ivmb/grh/wAnmaz/ANgmw/8ARVfTP/BD4j/hFvi1/wBfmnf+gT18zf8ABXD/AJPM1n/sE2H/AKKoA+4v+CM//Jqms/8AY0XX/pPbV+Xf7a//ACdr8Wf+xhuv/QzX6if8EZ/+TVNZ/wCxouv/AEntq/Lv9tf/AJO1+LP/AGMN1/6GaAP2h/4Juf8AJlfwy/69Z/8A0plr8Gfih/yUvxb/ANhe7/8ARz1+83/BNz/kyv4Z/wDXrP8A+lMtfgz8T+fiX4t/7C93/wCjnoA/ou/ZhBP7OPww/wCxa0//ANJ0qX9pT/k3v4k/9i9ff+iGqH9mAg/s4/DA/wDUtaf/AOk6VL+0qcfs9/En0/4R6+/9ENQB/ON8Pv8AkffDf/YTtv8A0atf1BHOT9a/l9+H3Hj3w3n/AKCdt/6NWv6gz1P1oATvXwn/AMFkP+TUtP8A+xjtf/Rc1fdgr4T/AOCyJH/DKen+v/CR2v8A6KmoA/EqiiigD9Dv+CKf/Jf/ABp/2Lh/9KYq/ZP9a/Gz/giocftAeNAeD/wjZ/8ASmGv2T/CgD8Wf+Cz3/Jzfh7/ALFqD/0fPX0R/wAESv8AkjvxE/7D0X/pOtfO/wDwWe5/ab8Pf9i1B/6Pnr6I/wCCJRA+DvxE5/5j0X/pOtAHw5/wUw/5PU+In/Xa3/8ASeOv0u/4JFf8mc6b/wBhi+/9DFfmj/wUv5/bU+ImP+e1v/6Tx1+l3/BIo/8AGHWm/wDYYvv/AEMUAfadfzEfFj/kqfjL/sM3n/o96/p36DtX8xHxY5+KfjLHT+2bz/0e9AH9Cv7HP/Jqnwm/7Fqx/wDRS17HzzXjn7HGD+yn8Jj1/wCKbsf/AEUtex0AeO/ti/8AJp/xg/7FTUv/AEnev5v6/pA/bGP/ABif8YO2fCmpf+k71/N/QB7T+xZ/ydj8Kf8AsP23/oVfq5/wV+/5NFl/7Ddn/N6/KP8AYs/5Ow+FP/Yftv8A0Kv1b/4K+sP+GRpRn/mN2eP/AB+gD8O6+3/+CPX/ACd7/wBy9ff+hRV8QV9v/wDBHr/k73/uXr7/ANCioA/cIZxX5Df8FuP+SnfDX/sD3H/o4V+vOOK/If8A4LcY/wCFnfDb1/si4/8ARwoA63/giD/x6fFT/fsf5S1+pYz+Nfln/wAEQiBa/FQd99jx+EtfqYKAA55r+ef/AIKGf8nnfFP/ALCY/wDRMdf0MdBX88//AAULIP7Z3xTIOf8AiZj/ANFR0Afq9/wSr/5Mw8Kf9fd5/wCjmr62uv8Aj2m/3D/Kvkj/AIJVH/jDHwr/ANfd5/6Oavra6OLWb/cP8qAP5efF/wDyNmt/9f0//oxq/oG8F/8AKP7RP+yZQ/8AprFfz8+LufFmtEcg3s/P/bRq/oG8FsD/AME/tEORj/hWMPOf+oWKAP546KKKAPWP2S/+Tn/hT/2M2n/+j0r+kn1r+bX9kwgftPfCkn/oZtP/APShK/pJz1oA+EP+CyH/ACaxpv8A2MNt/wCi5a+GP+CSf/J5+g/9gvUP/RBr7n/4LI/8msab/wBjDbf+i5a+GP8Agkn/AMnn6D/2C9Q/9EmgD6M/4Lf/APHr8Kf9+/8A5Q1+VNfqt/wW/H+ifCn/AH7/APlDX5U0Aeofst/8nIfDH/sYrH/0etf0h65oGm+JbB7HV9PtdUsnYM1teQrLGxByCVYEcGv5vP2W/wDk5D4Zf9jFY/8Ao9a/pSPU0AcaPgz8P8f8iP4c/wDBVB/8RR/wpj4f/wDQj+HP/BVB/wDEVlfHz47eHf2cvhzc+NfFSXj6NbTxQSCxiEku6Rtq4UkcZ96+X/8Ah8V8Cf8Anh4o/wDBan/xygD7F0L4eeFfC98bzRvDWkaTeFDGZ7Gxihcqeq7lUHHA49qn8ReCPDvi54H1zQdN1l4AViOoWkc5jBxkKWBxnA6elfGX/D4n4E/8+/ij/wAFq/8AxytrwX/wVd+DHj3xdovhvS4PEn9o6veRWNv52nqqeZI4Vdx8zgZNAH05/wAKZ8Af9CP4c/8ABVB/8RWz4e8GaB4SEw0PRNO0ZZ8GUWFqkHmY6btoGcZP51sHggUZ+tAAPajHT0oHX2rG8Y+MtE8AeGtQ8QeItTt9H0WwjM1ze3T7Y41Hr6+wHJPSgChqfwr8Ga1fz32oeEdDvr2dt8txc6dDJJIfVmK5J+tbWjaHpvhywSw0qwtdMsoySltZwrFGpJycKoAGTX54ePv+C1fgbQtZktfCvgLVvFNlG237ddXq6esg9UQxyNj/AHsGu8/Z+/4KyfDL4y+JrLw7r2mXvgHVr6RYbZ7+Zbi0kkYgKnnKF2kk4BZQPcUAfcJAYY6iuOb4NeAHZi3gjw6zMSSTpUBJJ6/w12AbcOuR7UvegCK1tIbK2itraKOC3hRY44olCoigYCgDgAAYwKlxRmjNAFTVdHsNe0+aw1Kzt9QsZgBLbXUSyRyAEEZVgQeQPyrn7H4TeB9MvILuz8H6Da3cDiSK4h02FHjYHIZSFyCD3FeY/tN/to/Dj9laxgHim/lvdcuk8y10PTVEl1KucbzkgIvuxGecZxXx/B/wW/0JtSCS/CXUUsN2DOmuRtLt9fL8gDPtu/GgD9OeAKzNf8LaN4qtY7bW9JsdYto38xIb63SdFbGNwDAgHBPPvXlv7Nf7WPgD9qbw7cal4O1CQXlntF9pN6nl3VqSOCy5IZT0DKSOMda9lzzQBzuifDfwn4avhe6R4Z0fS7wKUFxZWEUMm09RuVQcGrPiHwX4f8W+R/buh6drPkZ8r+0LWOfy84zt3A4zgdPStntQaAMPw/4D8NeEp5Z9D8P6Xo80q7JJLCzjgZ164JUDIrcwOeKXtSFsAk8Y9aADt7VzWr/DLwh4h1CS+1XwtoupX0mPMubvT4pZGwMDLMpJ44r56tP+Ck3wg1P4023wy0641XU9cudWTRYru1tA1o87OI8iTfyoY43Y7HGa+qh06UAcd/wpjwB38D+HP/BVB/8AEV0ei6FpvhzT47HSbC20yxjJKW1nCsUa5OThVAAycmr360ZoAGRXBBAIIwQa45/g14AkdmbwR4dZicljpUBJP/fFdjmvnv8Aae/bi+Gv7LCRWviO+m1LxFOnmQ6DpiiS5ZOzPkhY1PYscnsDQB71/ZNkNMGnC0gGn+V5H2URr5Xl4xs24xtxxjpXML8GPh+B/wAiP4c/8FUH/wATX57Qf8FwNDbUFjl+EuoR2BbBnTXI2lC+uzyAM+278a+1/wBm/wDaw+Hv7Unh+fUPBmqO17a4+26TeJ5d3a56FkycqegZSR2znigDsh8Gfh//ANCP4c/8FUH/AMRU1p8I/A1hdQ3Nt4N0C2uInDxzRaZCrowOQQQuQR611fWj60AHArN1/wAMaR4qs1tNa0uz1e1VxIIL63SZAwBAbawIzyefetLNcx8SviZ4Z+EPg6+8U+L9Wi0TQbEAz3cqswUk4UBVBZiTwAATQBJovw18I+G9QS/0jwxo+l3yKVW5s7CKKQAjBAZVBGRXR8Cvz28e/wDBZ/4X6DdPD4Z8K6/4qCkgTyFLGJvcbtzYPutcv4d/4Lc+F7zUY49c+F2qaXZkgNcWerR3br77Gijz+dAH6LeIfA/hzxbLDJrmg6ZrMkClYmv7SOcxg8kKWBxnFZP/AApn4f8A/Qj+HP8AwVQf/EVU+Cvxw8H/ALQHge38V+CtVXVNKlcxPlTHLBKAC0ciHlWGRx05BBI5rvD+NAGR4d8G6D4RSddC0TTtGW4IMo0+1SASEZxu2gZxk9fWvxC/4K4f8nmaz/2CbD/0VX7qV+Ff/BXD/k8zWf8AsE2H/oqgD7i/4Iz/APJqms/9jRdf+k9tX5d/tr/8na/Fn/sYbr/0M1+on/BGf/k1TWf+xouv/Se2r8u/21/+Ttfiz/2MN1/6GaAP2g/4Ju/8mV/DP/r1n/8ASmWvZ5fg54CmleSTwV4ekkdizM2lwEsTySTtrxj/AIJvf8mV/DP/AK9J/wD0plr6W7UAQ2NjbabZwWlpBHa2sCCOKCFAiRqBgKoHAAHYUXtlb6jaTWt3BHc20yGOSGZAyOp4KsDwQfSps4ryP9ov9qTwB+zB4Zi1bxpqhhnutwsdLtU8y6vGGM7E7AZGWYgDPWgDrI/g34BikV08E+HldSCrLpUAII6EHbXYYGBivzGuf+C4GhJqTR2/wm1GWwD4E8muIkpX18sQkZ9t3419cfsvftufDj9quCa38NXdxp3iG2j8250LUlCXCJ03qQSsi5xyp44yBmgD6BrN17wzo/imyW01rSrLVrVXEggvrdJkDDIDbWBGeTzWkDR+dAHHf8KY+H4/5kfw5/4KoP8A4ij/AIUz4A7+B/Dn/gqg/wDiK7EVHPcR2sMkssixxIpZncgBQBkkk9BigDE0D4f+F/Ct291ovhzSdIunTy3msbKOF2XIO0lVBIyBx7Vv4r4I+NX/AAWC+Gfw48Q3Wj+E9Dv/AIgy2rmOW+trhbSzLA4ISRlYuM9wuD2JFZPwr/4LNfDzxfr0Gn+L/Cep+B4JnCDUVuhf28eTjMm2NHA9wrYoA+7te+HnhbxVerea14b0nV7tUEYnvrKKZwoJIUMyk4yTx71Z8PeEtD8JQzRaHo9ho0UzB5UsLZIFdsYBIUDJxVjQ9d07xNo1lq2k3sGpabeRLPbXdrIHjljYZDKw4IIq960Aczqvwu8G69fzX+peFNE1C9mOZLm60+GSRzjAyzKSeAK1tC8OaV4XsfsWj6baaVZ7i/2eygWGPcep2qAMmtDNFABgH2rkJfg54CnleSTwV4ekkdizO2lwEsSckk7etHxU+LPhT4K+DL3xV4y1mDRNFtcB55clnY8KiIMl2J6AAmvgLxV/wW38KafqskPh/wCGeq61YISFu73VEs2f3EYikwPqaAP0l0/TrXSbGCysbaKzs4EEcVvAgSONR0VVHAHsKn718g/sx/8ABTL4aftHeI7bwxJb3ngzxRdcWthqbrJFdP8A3IplwC3oGCk44z0r6+yD3zQBX1DTbXVrGeyvraK8s7hDHNbzoHjkQ8FWU8EH0Nct/wAKY+H/AP0I/hz/AMFUH/xFdl1xTfzoA5ax+E/gjTLyG7svB+g2l3A4king02FHjYdCrBcg+4rZ1zw3pPiiyFnrGl2erWm4P5F9As0e4dDtYEZFReLPFek+BvDepa/rt9Fpmj6dA1xd3k2dkUajJY4yfyr4a+IX/BZP4SeGbmS38M6Jr3jAodouI0Wzhb3Bk+fH1SgD7K/4Ux8Px/zI/hz/AMFUH/xFX9D+HPhTwzfi+0fwzo+lXoQoLiysYoZNp6jcqg4OK/OnSP8Agt7oE9/HHqfwn1KysmPzz2utJPIo9QjQoDx/tCvuP9nr9pjwJ+034SfXvBOqG6W3cR3ljcJ5VzaSEZCyIfXswypwcHg0AeqmsLxB4D8NeLbiKfXPD+l6xNEpSOS/s452Rc5wCynAzW7nrRQBjeH/AAV4f8ImY6HoWm6N52PN/s+0jg8zHTdtAzjJ61sjke1GfY184/tH/t4/Df8AZb8ZWPhrxjFrL6heWS38R0+0WVPLLsgyS4wcoeMUAfR2Aa5fUvhX4L1m/nvtQ8I6HfXs7b5bi406GSSRvVmK5J+tfIP/AA+K+BP/ADw8Uf8AgtT/AOOUn/D4r4E/88PFH/gtT/45QB9s6JoGmeG7FLLSdPtdLs0JZbezhWKME9SFUAc1dZQwIIyD1FfPn7Nf7b/w+/ap17V9J8GRaulzpdstzcHUbVYV2s20YIc5Oa+hD+NAHHH4NeAHYs3gjw6WJySdKgyT6/crpU0awi0tdMSyt00xYfsws1iUQiLbt2bMY244xjGKuUe/NAHGj4MfD8dPA/hz/wAFUH/xFL/wpn4f/wDQj+HP/BVB/wDEV2PvyK8j/aN/ah8Dfsu+F7PXPGl5Okd7cfZrW0soxLcTNjLFUJHyqMZOccj1oA7C0+Efgawuobq18G6BbXMLiSKaHTIVdHByGUhcgg9xXWcDivMf2eP2hfDH7TPgB/GHhFL5NJW8lscahCIpN8YUt8oJ4+cd69OoA+D/APgsj/yaxpn/AGMNt/6Llr4Y/wCCSf8AyefoP/YL1D/0Qa+5/wDgsj/yaxpv/Yw23/ouWvhj/gkn/wAnn6D/ANgvUP8A0SaAPoz/AILf/wDHp8Kf9+//AJQ1+VNfqt/wW/8A+PT4U/79/wDyhr8qaAPUP2W/+TkPhl/2MVj/AOjlr+lI9a/mt/Zb/wCTkPhl/wBjFY/+j1r+lI9TQBxHxh+DfhX48eCJ/CXjSwfU9Cnljnkt453hLMjblO5CDwfevAv+HWX7N/8A0JNz/wCDe7/+OV9Z9uvFGcdKAPkHVv8AgmR+zJoWmXeo6h4Rks7C0iaee4m1m6VIo1GWZiZOAACa/Pf9lz4M6H8e/wBu5br4ZaNLonwy8LaouprK8kkuLeBx5ZZ3JO+Z1BC54BPpX0D/AMFJP2qNY+Knja0/Zx+FjS6hfX13HaazNZtnz5mOFtAw/hXrIenGOgNfaX7Hn7MGkfssfCCy8N2gjuddutt3rOoqozc3RUbgD18tPuqPTJ6k0Ae5g560tJijFAB9a/MX/gtj471fTvDvw78JW0ksWj6jNc310FOEmeLYsat643scfSv06718wf8ABQD9k5/2qPg8LPSWSLxhokjXmkPKQqSkjEkLHHAcDg9mC54oA0P2NP2cPh18NfgD4QOj6HpepXOqaZBfXurz2ySy3ckiB2JdgTtBYgL0AFfA3/BXr4C+CPhX4m8HeLPCdja+H9T11p0vdPsVEUbmPaROqLgKctg46nFct+yZ/wAFCPGP7HM198NfiRoWoax4e0ySSKOwkOy/0qUEkxpvODGWP3T0zkHHB3Ph78PPiP8A8FTPj/H488WW50X4ZaRcLbssbMIo4EYMbWDOd0rgje/QZJ4+VaAP1D/ZX13U/E/7Nfwv1bWXkl1S88N2E1xLJ96RzAnzn3br+NepVV0vTLXRdNtdPsYUtbO1iWCCCMYWNFAVVA7AAAVaoAUD/IpCaPxoPSgD8U/g14Y0z9rD/gpZ4kh+JU41Gxj1HUJk024c7bhbcssNuP8AYUAEr3Cn1NfrX4r+AXw68Z+DrjwvqvgvRJdEmh8j7NHYxx+WuMAxlVBQjsVwRX5f/wDBRH9ljxf+z78ZZPj18NHu7fSbm8/tG7ubHh9IvCwDMcf8spGJOemWZTwRVnxH/wAFh/FPir4JJ4e0nwuLH4pX+bGTVbUk2qKw2iaCPJbzjnhTwp5BPSgDif2DbBvhT/wUjv8Awh4Z1KS90KG71XSXlUkie2j3ld2ODho059RX7XLjAxXwT/wTS/Yh1X4HW998SfH0ePHWtwGOCzkO97G3ch3Mh/56uQM46DjqTX3v9KADOBSE0YNGKAFr4Z/4Ke/tgf8ACkfh1/wgXhe7/wCK48TQtG0kDfPYWhIDOQOQ7glV/wCBHsK+oP2gfjfoX7PHwp1vxv4gkH2WwjxDbhsPdTtxHCnqWbH0GT2r8zP2Evgpr37aH7Q+ufHz4mxNe6JY33nW8EwJhurof6uJA3WKFQvHqFHrQB8w/AL4da58LP21/hL4f8R2/wBk1dNf0e6mtyctEJmilVW9GCuuR2PFf0L55r8aPjwMf8FiNCH/AFNGg/8Aoq2r9liDQAdKMUdaKAON+MvxEt/hL8KfFnjK6AeLRNNnvRH/AH2VCVX8WwPxr8lv+CfvwAH7a/xv8Y/FH4pl/EGlafcCaa2nY+XeXkpLJG3/AEzjUZ2D/YHTiv0p/bh8PXvij9kz4oWFhEZro6LNMsYBJYR4dgPfCmvjf/gib470oeEPiH4NedItaF/FqscLsA0sLRCNio77WQZ/3xQB98+KP2ffhv4w8ISeF9V8EaFPobReULVLCOMRjtsKgFCOxXBr8XfEcGp/8E5v26TFod9NNo2n3cMhRjzdaXPtZon9SFJGf7yA1+8TMqAksAAOSeBX4A/8FJPi7pfxl/au8Salolyt7pOlww6PBcIQUk8kHeykdR5jSYPcUAfvppOqQa1pdnqFo4ltbuFJ4nHRkYBlP5EVcJ5rxD9iXxLL4t/ZN+FeozyGWc6FbwOxJJzEPK5P/AK9v+hoATvXn3x1+Ceh/tBfDy68F+JZLqPRbuaGa4FlII5HEbh9oYg4BIwT1r0HFH40AeTfDX9lH4R/CWzSDw14A0SzdVCm5mtFuLhsessgZv1rhf2x/wBl/wCG3xU+Cviu51jQdM0zVNM024vLPW4LdIZrV40Lgl1Ayp24KnIwTX0kTj3r8uP+Cm37a8nie4n+BHwzlk1S9vJVtdcu7DMjSsSNtnDtzuJPD/8AfPrQByX/AARM1rVF+JvxH0dGkfRH0iG7kHOxbhZgiH0BKvJ9dvtX66k8V8o/8E7/ANkd/wBl34Ru+tKjeNfELJd6oVwfsyhf3dsD32ZYk/3mPYCvq48d6AFHSvwq/wCCuH/J5ms/9gmw/wDRVfup06V+Ff8AwVw/5PM1n/sE2H/oqgD7i/4Iz/8AJqms/wDY0XX/AKT21fl3+2v/AMna/Fn/ALGG6/8AQzX6if8ABGf/AJNU1n/saLr/ANJ7avy7/bX/AOTtfiz/ANjDdf8AoZoA/aD/AIJu/wDJlfwz/wCvWf8A9KZa+lq+af8Agm7/AMmV/DP/AK9Z/wD0plr6Wx+FAC1+LPxm0+H9pP8A4KqyeDvHF66+HItaXSY7ZpCqi3gh3CJP7vmspyR1Mh9q/aUjNflj/wAFRP2PPEdj41Px5+Hkd1JOojm1yKxJE1pLCqrHeR45xtUbiOhUN3JAB+in/CjPh4fCf/CNHwToP9heR9n+wHTovL2Yxj7vX36985r8dvCHhO1/Z6/4Klaf4W8CXUo0qz8Sx2UaKxcpbzIDLAx7hQ7Lz/d55r0jwx/wWS8S6d8D7nRtU8Nx6h8SYoha2etKwFpIu0jz5o858wHB2j5WPPy9K9T/AOCbv7F/ia08ZTfHr4qCVvEepeZd6XZ3p3Tl5xue7m9GYMdo6jJJxxQB+lA9jS/SkxRigBRX59/8Fe/2iLz4cfCfSvh9ot21rqfi1na8kibDpZREblyOQHYhfcKw71+gfavxQ/4K1ajP4v8A2w9N8PJLkWumWdnEo+bY0rsx49fnHFAH1r/wTO/Yt8MeCfhDpHxD8V6Faat4x8Qx/bIG1CFZRY2rZ8pUVhgMy4ctjPzAducD/grr8O/hNofwZsdbudKstJ8fy3kcGkS6bAkUtyoyZVlCgbo1U5yeQ20DrivvkSaN8MvAqNPNFpmgaFpyhpZDhIbeGPGT9FWvyM8OWOr/APBUb9tW41LUEuIfhh4bIYxEkKtiknyRDsJZ25bHIG7+6KAPS/8AgjZ+0PfajH4g+EWr3T3ENpEdU0YSNnyk3ATxD2yyuAOhL+tfqRX4cfsi2p+Dn/BTeLw3YD7PaW3iHVNDMecARfvkC8em1fyr9x6AFxSfSjHvRQB+QX/BY/xnqWt/HLwL4Iur02Ph61sFuwW4jEs0pR5W9dqoB7c+tfpT8JP2cPhv8LPh9pvhvQPC2kyafHbIslxPaRyyXhKjMkrlSXLdeeOeOK+dv+Cmn7GmoftG+CLDxV4RtxceNvDkcgSzXAbULZiC0QP99SCyg9csO4r5B/Zd/wCCqPiX4D+Br3wR8QNEu/FQ0mB4NIuDJ5d1BIg2rb3BbrGCMZ+8o454wAc1/wAFNvhB4Z/Z4/aY8N3/AMPI00KfUrSLWGsLI7FtLlZ2CvGB9wMUBAHQg4r9qvCV5daj4W0a7vU8u9uLKGWdMY2yNGpYY+pNfk5+yv8As8eO/wBuz48/8L4+KsQh8HRXguLe2bIS8MTAxW0KNn/R0PDE9cEckkj9eAu0YHTtQA4nBpOlGDRz60Acr8Vfh5YfFn4deIPB2qTT2+m61aNZ3EtqQJVRuu0kEA/hXn3wr/Yz+DXwctYY/D3gHSftEQA+36hAt3csfUySAkH6Yr2zt1pp470AeQ/HP9mX4a/GbwHqekeJPDGlhfs0nkahDbJFPZtsOJEkUArt4OOhxyDX5Z/8Ejr7UPD/AO2Fq+hafctc6TPpN9FdlM+XIkToY5D/AMCwAf8AbPrX1B/wU1/blh+HWg33wk8CXZufGerw+Rql3anP9n28gIMSlTnznHGP4VOepFdF/wAEvv2O7z4C+Bbnxz4qtjb+MvE0CBLSQYayszh1Rh1DucMw7YUdQaAPur2oFJ+NFABXiHxv/Yx+E/7RXii08Q+O/D82r6pa2q2UUqX80AWIMzAbUYDq7c17fj3oxxQB8mH/AIJY/s4f9CTc/wDg3uv/AI5XiP7YX7IP7Lv7MfwS1rxRP4Qk/tuWJrTRrR9YuS094ynZ8pk5Vfvt7L71+hHjHxdpHgHwvqniLXr6PTdH023e6urqU4WONRkn+mO5IFfkNp48T/8ABVf9rUXFwt1pnwo8NsGMG4hYLQPwvcfaJ8c+g9kFAHtf/BGv4Ear4V8IeJ/iVqsL2sHiER2WmROCDLBGxZ5voWwB/umv0nHFUPD+gaf4W0Sw0fSbSKw0yxgS2trWFdqRRqAFUAdgBV/r34oAP1pe/wBKTHWj8aAMrxT4o0zwX4e1HXdavItP0rT4Hubq5mOFjjUZYk1+CH7VPxZ8X/tm+OPGfxJt7Sa38EeFo4oLaKY4S1geUJGPQyyMS5HoD2FfV/8AwU2/aZ1f4vePtP8A2c/hsZNRllvI4dY+yNn7VdFgY7UEfwocM/8AtAf3Dnsv2mf2atI/Zc/4Jm634VsRHPqslzY3Oragq/NdXLTpuOeu1fuqPQe5oA9B/wCCO3H7Isv/AGMd7/6Lgr7iJ5r4d/4I7f8AJosv/Yx3v/ouCvuL680AfCH/AAWR/wCTWdN/7GG2/wDRctfC/wDwST/5PP0H/sF6h/6JNfc//BZH/k1jTf8AsYbb/wBFy18Mf8Ekv+Tz9B/7Beof+iDQB9Gf8Fv/APj0+FP+/f8A8oa/Kmv1W/4Lf/8AHr8Kf9+//lDX5U0AfbnwE/4J5ftBeC/jb4E17V/h7NaaVputWl1dXH9pWb+XEkqszYWYk4APQV+43XmjqKKADHFfKv8AwUO/ayX9mH4NMNJlA8a+IvMs9HXr5OAPNuCP9hXGPVmX3r6q6V+JP/BQXVbz43/8FDLfwLNcP/Ztjd6boNvG3AQSrFJKQPdpm/IUAfTX/BKX9lF9C0KX42+MImufEmvh/wCyRc/M8Nuxy9wSf45DnB67f96v0bFU9E0e18P6PZaZYwJbWVlAlvBDGMKiIoVQB6AAVdoAADmjPPFBoAoATp2r4P8A+Clv7dF5+z5pFv4C8E3Aj8cavbGaa/U5OmW5OAyj/no+Gx6AZ9K+8SPx9q/C79ry4s1/4KY6m/jklfDsev6d55uh+7FkEh/8cxn9aAPYf2ZP+CVl38ZPhnqPjX4ra3qel+IPEVuZtLgHzz25Y5W5uCxy5YAEJwcNycnA8V8NeNPjB/wS9+PEui6iJLvw/PIHnsC5+xava7sebEeQkmB1HKng5FfulY3NveWkE9rJHNbSoHikiIZGQ8gqRwRjpivgn/gsnb+F3/Z00aXUjbjxMmsxf2TyPOKlH88DuU27Se2QtAH2Z8IfinoXxr+HGheNPDdwbjSdWt1nj3cPEejRuOzKwKn3Fdjg55r4e/4I+2uqQfsltJfl/sk2u3cliH7RbYw2PbzBJX3FQAVW1LULfSNOur69mS2s7WJ55ppDhY0UEsxPYAAmrPU14b+3G2or+yR8VTpe/wC2f2HPjy+vl8eZ/wCOb/wzQB+YHx4+P/xJ/wCCj3x2g+Gnw7M9r4LFw62dlvMcUsScteXR9MDIU9MgAFjXpP7R/wDwSKl8EfCux174Yarfa/4n0e236nYTgBr8jLNLb4+6w7R85AGDnrL/AMETLzw1Fr3xJt7iSFfFksNqbVXIDtagyGXZ9G2E49q/WE8CgD8tf+CbP/BQPWtU8Tad8HfideSXdxN/oui6zdk+eJRgC1mJ65wQrHnIwc5FfqWDuFfhL+1bY6Uf+CkMsPgJY2mk8Sac22w5AvzJEZduOM+ZnPvmv3b70AHJBFIc4zS4xRjNAH4j/wDBQn9on/hof9p2H4falrJ8JfD/AMLam2mz3NyjyKsquVuLpo0BZiACqqAeB23Gvun4V/t0/sm/Bz4f6L4O8N/ECG00fSoBBCv9kX25j1Z2PkcsxyxPqa7jxb/wTh/Z78c+KNW8Ra34Ee81jVbqS8u7j+2r9PMmkYs7bVnCjJJ4AArIP/BLb9mcf807k/8AB7qP/wAkUAfmv8Xfj14E8S/8FK9J+KOm68tz4Fh17SL2TVhbTKFihSASt5ZQP8pRuNueOK/Vb4fft7fAv4q+MtM8K+FvHKatr2pSGK1tF027j8xgpY/M8QUcA9TX5S/Fr9n/AMBeF/8AgpJpXwr0zQzb+BJ9d0myk0s3c7lopkhMq+azmTku3IbIzxiv1R+HH/BPz4EfCXxppni3wr4KfTNf0yQy2t0dXvpRGxBBOx5mU8E9QaAPogUUAYFLQBDd20V7bS286LLBKhjkjcZDKRgg+xFflr8b/wDgmT8Sfhd8UZfiB+zjrZtmMr3EWli7FpdWRbrHFI52SRnJG1iOODmv1QpD7GgD8oNR+G3/AAUC+NtgPCnia9fw5ocy+Td3kl3ZWwdO+5rcmR/oo5ry39vT9lrwt+yJ8Bvhv4W0+VNY8V6xqlxf6trcibZJfKiVVRF52xgynAzyRk5PT9r55Y4ImkkdY0QFmdjgADqSa/En9srx/dftz/tqaH4K8Eyf2lo1lLHodhPDzG/z7rq5z/cHPPTbGD3oA/Tz9gvRZdC/Y/8AhXbzKySPo0dwQ2M4kLSD9GH5175WV4R8O23hDwto+hWQ22el2cNlCB2SNAi/oorWFACY5oPFL+NfPX7eHx6vP2df2b/EPibSmCa5cMmmac5/5Zzy5Ak9yqhm+oFAHzt/wUL/AG/LvwVfXHwe+FDy33ju+ItL/ULIF3sS+B5EIAO6ZgcHH3c+vTo/+Cff/BPe2+Atlb+PPH9vHf8AxIu08yOCTEiaSGzkA5IaUg/M3bkDuT5Z/wAEjf2bLXXLPVPjp4pR9U1y5vZrbSJLr5zGf+W9zk9XZiVB7Yb14/T4DAwBigBAMcUdaUg0UAHavyZ/4KLfsV/Gb45ftOap4p8E+C5da0KXT7SBLtb62iDOkeGG2SRW4PtX6zfjRigD5E/4JkfBPxr8A/2ftU8OePNEfQNZl1+4vEtnnim3QtDAqtujZhyUYYznivgn9qP/AIJ/fHz4h/tE/ETxLoHgCW/0XVNauLq0uRqNonmxM2Vba0oIyOxANftiKQigDwz9iD4eeIfhT+y74G8KeKtObSdf023mjurNpEkMZM8jD5kJU8MDwe9e54pccUUAIx2jPavxu/bM/a48cftffGRfgv8ACV7hvDLXh09Es32Nq8w+/JI3aFSrEDOMAsewH6ufG9tUX4OeOW0TcNYGiXptCn3vM8h9uPfPT3r8jv8AgjXd+HLb9o3Xk1Z4U1ybRXj0jziAWfzFMoTP8WwH8A1AHpfxH/4I5PpHwKtLvwrrk2qfE2xiNzeW0mFtb8kZMEWcbCuMKxOG74zxy/8AwT+/b58SfCXxrY/CH4pTXEugPc/2daXeo7hc6TOG2LC+efL3Dbg8qfbiv2CIGK/ED/grJZeHk/a+iHhcQtq8+mWp1VLPlvtu9wucfxmMRZ79KAP3AHPajr71ieB476LwXoCanu/tBbCBbnf18zy13Zx3zmtwCgAr8RP+Cn5/4RX9vCDWZmxEINMvfmHAWPAPTr9w1+3ZHHWvyn/4LU/CO6N/4H+JNnCz2gik0a/kC/6tg3mQkn3zIPwoA3/+CqH7UN54gvdK+AHgGR77WNYeA6x9kJLN5m0wWgx1L5V29to7mvr39i/9mew/Ze+CemeHFSOTxBdgXus3ajmW5ZRlQe6oPlH0J718Lf8ABJH9n6P4i+LNa+N3iu9TWL7Tbg2WmxzzebKLnYN9xJkkghGCpn1J7Cv0a/aO+L+m/Aj4LeK/GWpXCQ/YLKT7IjNgzXTKRDGvuz4H0ye1AH5DfBC5HjL/AIKvy6hZfPDceNtUvAU+YbA87E/THev3DHrX41/8EfvhhqHjn9oHxD8SL+Np7TQ7SVTdSDh7y5yPz2eYT9RX7KjpQAAelITgHNKM0hoA/M//AIKZ/t3a14R1yb4M/DW6ltdclVE1nVbRj58RfBS2hI5DMCNxHPzADvXK/C3/AII8SeJPgjd6j4z1+50n4lapGLq0hUB4LBsEiOfnMjNkbiD8p6Zwc+DfDjUNIsf+CpJu/HcyQWEfjK9aWW/OEWbMoty5PQeZ5WO3TtX7pKQVyKAPw1+An7SHxP8A+CdXxlvPAXji0u7jwulzt1HRJH3KqE4F1aN05AyMcMODg9P228KeJ9M8beGdL1/RbtL/AEnU7aO7tLmP7skTqGVh+Br8zP8AgtlB4W/sz4dTgxDxr506AIR5hscZ+bvt8z7vuWr6y/4Ju22o2v7Fnw0TUzIJWtZ5IhJ94QtcymL8NhUj2IoA+lz2pMH1paMYoACQBmviP/goL+3zB+zvp7+B/BbJf/EnUIgNyjeulxvjbIw/ikYH5V/E9gfo39pr4vj4DfArxj462JNcaTZF7aOT7rzuwjiB9t7rX5uf8EsvgU/x9+Kfij44+P5X1680y+Bszd/OJr9wWeZs8fu127R0BYf3RQB6t/wT/wD+CfFzoV9B8YPjFbvqPjC9k+3afpN/+8a1djvFzPk8zEkkKfu5yfm6fo2BilC4FGKAE60UtHSgAOaQnApa574h+KF8D+AfEniJxuXSdOuL7GM58uNnx+lAH5f/APBTb9ovW/jT8VtM/Z2+HrS3eL2KDU1gOPtl65UxwZ/uR5y3bd1+7X37+yh+zjo37MPwd0rwjpipNflRc6rfhcNd3bKN7n/ZGAqjsqj3r8yP+CR3hb/haP7Uvijxzrrfb9S0iwlv1ml5JubiTYX+uDJ+dfs0BQAc0uDmjtS0ANxivmv9v39pe4/Zj+Al7rWlxM/iHVpf7K0yTHyQTOjEyt/uqpIHc4r6VxXnPxu/Z78B/tFeHrLQ/iBora7pdnci8ggW8nttku0ru3QuhPDEYJxzQB+UP/BOP4k/An4PaxrHxL+K/juFPH11LJFYWs9hd3D2iNnzZ2dImUySbiODkLn+9x7r/wAFAP24Pgn8aP2X/EfhTwb42TWdfuri1eGzGn3cRYJKrMd0kSqMAE8mvoL/AIdbfsz/APRO5P8Awe6j/wDJFfO37ff7CPwR+B/7MniLxb4M8GtpGv2s9rHDdtqt5PtDzKrfJJMynIJHIoAx/wDgmt+2b8HPgN+zjJ4Z8deMk0PWzrd1di0NjczHynSIK26ONl5KtxnPFfpD8Lvip4Y+M/gyy8V+DtTGsaBeM6wXYhki3lGKt8siqwwQRyK/Nf8A4JufsTfBv9oH9nR/FHjvwk+ta2utXVmLldTu7f8AdIkRVdsUqrxubnGea/Sb4U/Cbwt8E/BNl4R8G6adI8P2RdoLQ3Es5QuxZvnkZmOSSeTQB86/8FM/gn40+PPwBsPDngTQ317WI9ZguntknihxEqSAtukZR1Yd8818n/8ABOv9ir4z/A79p7SPFXjXwVLo2gw2F5DJeG+tpQrvEQo2xyM3J46V+tOKTFAHwF/wVU/Zp+JH7RMPw9T4e+GpPELaY14bvZdQQ+Vv8vb/AK11znaemelfn3/w7L/aU/6JrN/4NLH/AOPV/QEBR07igBAacBkUUUAJX4efH+TH/BWOdsdPGGlD/wAh21FFAH7h0hPNFFACjmkB5NFFACkcZr89f+CsH7LPhvxj8O7n4txXEmmeJtCgWCYRxB47+Hd8qvyCGXJw3PHBB4wUUAfnR8Kv28vjf8GfDcWgeG/GtwNHhG2C0v4kulgX+6hkBKjnoDirPw6k8W/t2ftH+HdF+IXjTULmfUZDGbx1EnkRj5ikUeVVM89OBnODRRQB++Pw1+Huh/CjwPovhHw5aLZaLpNsttbxDrgdWY92Y5YnuSTXUYxRRQAg9Kq6rptrrOmXdhewJdWd1E8E8Ei5SSNgVZSO4IJFFFAH4I/ti/CCT9iX9pBU+HniXUbDcn9o2E0LGGeyVif3XmBvnAHGSBkcEGs/xH/wUg/aD8VeHJdEu/Hs8FtLH5ck9lbRW9wy4wf3qKGGfUEGiigD6o/4JI/swaB4vvbn4z69eTarrGnXUkFjYzRgxxTHBNwzkku+CcdMEk8nGP1fXjiiigBaAeaKKAA80h7UUUAfjN8fJNv/AAWG0U4zjxRoI/8AIdtX7MjmiigBe9HeiigBCetfhh+0H+158Xfgh+1j8WLTwf431LT9Nj8RXe3TpnFxar8/aOTKr+AFFFAHmHxR/b3+OXxg8PS6H4g8cXS6VMpWe206NLQTKequYwCw9icV+qP/AATp/Y68I/A74f6f44jmfXfF/iGyWSTUrmEJ9lhbB8mJcnb23NnLYHQcUUUAfZq0E4oooAWvhL/gsg+P2UrBcdfEdp/6KmoooA/G/Rvid4w8OadHYaT4r1vS7GMkpbWeozQxqScnCqwAyavf8Lq+IX/Q9+Jf/Bvcf/F0UUAfX3/BKv4keLfE/wC15pNlrPijWdWszpV8xt77UJZo8iPg7WYjNftjRRQAAUuM0UUAJ3xQR8tFFABnNFFFACOAwII4Nfhv/wAFGv2eNN/ZT+N2l+JfAOrXmkx67JLqNtaW+Ym02VSC3lSqc7SzEgYG3pk0UUAcI/8AwUp/aHk8NHRT4/nEJi8n7UtrCLrGMZ83bu3e+c17N/wS9/Zv0n9oL4nan8SPGuqXWsXPh68W5WyuR5n2u6PzLLLIxJbB5245IGT2oooA/Z4dKQGiigBW4rjfi98KvDvxs+HuseDfFVn9s0XU4vLlVSA8bA5WRGwdrqQCD7UUUAfg3q3jrx7+wh8evGHhv4eeNLyFLC5+zyyNEoiu1ADKZISWUkBsZ+uMZxWd4q+PvxO/bF8e+FfDXjzxnc3Nnd38NtDFHCqW9uzsF8zyU2qzAMeTz2yKKKAP3U/Zw/Z/8M/s2fC7TvB3hhHeCL99dXswHnXk7AbpXxxk4AA7AAdq9TxxiiigA60HpRRQB+VX/BXb9lvw5otuvxm0m4k0/Wb2eO01KxSMGK6f5VWYHIKOBgHg7sDoc5+RfBH/AAUT+P3w/wDDcGhab48uJ7GFBHCdQgjupY1AwAJHUtgdsmiigDW/ZX+HWoft1/tMxQfEjxXqd8xQ397cSHzZbiNGGYFJIESnOBgEAdBX71aBolh4a0Wx0nS7WOy06xgS2traFdqRRoAqqB6AAUUUAXzQDmiigD5S/wCCo52/sQfEE+r6f/6X29fhVoHxE8VeFLNrTRPEur6Pas/mNBYX0sCFsYyQrAZ4HNFFAGn/AMLq+IX/AEPfiX/wb3H/AMXX0Z/wTz+KPjLxB+2D8O7HVfFuualZS3UoktrvUppY3/cSEZVmINFFAH7xKeopaKKAAHivN/2km2/s+fEk/wDUuX//AKTvRRQB+Zv/AARGbHxO+Ja+ukW3P/bZq/XkmiigBaM89KKKACjNFFAATivkT/gqq+P2L/Fgx1u7If8AkdaKKAOV/wCCOzZ/ZGmHp4kvR/5DgNfcvaiigAoxxRRQADmloooA/9k=';
                    var doc = new jsPDF();

                    doc.addImage(imgData, 'JPEG', 10, 8, 35, 20);

                    //agregamos codigo qr generado en el html
                    doc.addImage(qr, 'JPEG', 172, 4, 28, 29);

                    doc.setFontType("bold");

                    doc.setFontSize(20);
                    doc.setFont("helvetica");
                    doc.text(82,15, 'Receta Médica'); 

                    doc.setFontSize(9);
                    doc.text(82, 19, 'Fecha: ' + FechaAct2); 
                    doc.text(112, 19, 'Hora: '+ HoraAct); 
                    doc.text(88, 23, 'RECETA ID:'+ info.receta);


                    //primaera seccion datos del paciente
                    doc.setFontType("normal");
                    //tamaño
                    doc.setFontSize(10);
                    //color de la fuente
                    doc.setTextColor(50);
                    //ingresamos texto
                    doc.text(10,41, 'Paciente: ' + info.lesionado); 
                    doc.text(110, 41, 'Genero: ' + info.sexo); 
                    doc.text(175, 41, 'Edad: '+ info.edad +' años'); 

                    doc.text(10, 46, 'Talla: '+ info.talla +' mts'); 
                    doc.text(60, 46, 'Peso: '+ info.peso +' kg'); 
                    doc.text(110, 46, 'Temperatura: '+ info.temperatura +' ºC'); 
                    doc.text(166, 46, 'Presión arterial: '+ info.presion); 

                    doc.text(10, 55, 'Folio MV:');
                    // doc.setLineWidth(0.5);
                    // doc.line(10, 43, 200, 43);
                    doc.addImage(imgData2, 'JPEG', 28, 48, 35, 11);

                    doc.text(70, 55, doc.splitTextToSize('Alergias: ' + info.alergias, 127));

                    doc.setFontType("bold");

                    doc.setDrawColor(0);
                    doc.setFillColor(232,232,232);
                    doc.rect(10, 61, 190, 3, 'F'); 
                    doc.text(15, 64, 'Datos de la Receta'); 

                    doc.setFontSize(12);
                    doc.setTextColor(50);

                    doc.setFontType("normal");

                    doc.setFontType("bold");

                    doc.setFontSize(12);
                    doc.setTextColor(50);

                    doc.setFontType("bold");
                    doc.setFontSize(8);

                    doc.setFillColor(220,228,249);
                    doc.rect(10, 65, 29, 4, 'F');
                    doc.text(22, 68, 'Clave'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(40, 65,25, 4, 'F');
                    doc.text(44, 68, 'Autorización'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(66, 65, 14, 4, 'F');
                    doc.text(67, 68, 'Cantidad'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(81, 65, 93, 4, 'F');
                    doc.text(105, 68, 'Medicamento (sustancia activa y presentación)'); 

                    doc.setFillColor(220,228,249);
                    doc.rect(173, 65, 35, 4, 'F');
                    doc.text(178, 68, 'Forma farmacéutica');

                    doc.setFontType("normal");

                    // posiciones iniciales en y 
                    var i = 75; //para la parte principal de la medicina
                    var x = 80; //para la linea que divide cada receta
                    var medicamentos = 0;
                    var cantidades = 0;

                    angular.forEach(info.medicamentos, function(item, index){
                        
                        //agregmos el contenido de los medicamentos
                        doc.text(22, i, doc.splitTextToSize(item.clave, 21));
                        doc.text(43, i, doc.splitTextToSize('No requiere', 21));
                        doc.text(71, i, doc.splitTextToSize(String(item.cantidad), 14));
                        doc.text(85, i, doc.splitTextToSize(item.sustancia + ' ' + item.medicamento, 80));
                        doc.text(169, i, doc.splitTextToSize(item.presentacion, 25));
                        
                        doc.text(43, i+9, doc.splitTextToSize(item.posologia, 166));
                        
                        //agregamos una linea divisoria
                        doc.line(10, x+8, 200, x+7);

                        i += 20;
                        x += 20;
                        medicamentos += 1;
                        cantidades += Number(item.cantidad);

                    });
                    angular.forEach(info.ortesis, function(item, index){
                        
                        //agregmos el contenido de los medicamentos
                        doc.text(22, i, doc.splitTextToSize(item.clave, 21));
                        doc.text(43, i, doc.splitTextToSize('No requiere', 21));
                        doc.text(71, i, doc.splitTextToSize(String(item.cantidad), 14));
                        doc.text(85, i, doc.splitTextToSize(item.sustancia + ' ' + item.medicamento, 80));
                        doc.text(169, i, doc.splitTextToSize(item.presentacion, 25));
                        
                        doc.text(43, i+9, doc.splitTextToSize(item.posologia, 166));
                        
                        //agregamos una linea divisoria
                        doc.line(10, x+8, 200, x+7);

                        i += 20;
                        x += 20;
                        medicamentos += 1;
                        cantidades += Number(item.cantidad);

                    });


                   

                    //verificamos el espacio disponible para poner aviso de espacio nulo
                    if(199-i > 30){
                        doc.addImage(imgData3, 'JPEG', 10, i-3 , 190, 209-i);
                    };

                    //cuadro de indicaciones generales 
                    doc.setFontType("bold");
                    doc.setFontSize(8);

                    doc.setDrawColor(0);
                    doc.setFillColor(212,212,212);
                    doc.rect(10, 210, 190, 4, 'F'); 

                    doc.text(15, 213, 'Indicaciones'); 
                    doc.rect(10, 210, 190, 25);

                    doc.setFontType("normal");
                    doc.text(15, 217, doc.splitTextToSize(info.indicaciones, 186)); 

                    doc.setFontType("bold");
                    doc.text(10, 240, 'Médico : '+ info.doctor);
                    doc.text(95, 240, 'Cédula: ' + info.cedula);  
                    doc.text(10, 245, 'Reg. Especialidad: '+ info.especialidad); 
                    //doc.text(88, 275, 'Telefono(s): '+ info.telefonos);

                    doc.text(165, 250, 'Firma');
                    doc.setLineWidth(0.5);                    
                    doc.line(140, 245, 200, 245);

                    doc.text(10, 255,'Dirección: ' +info.direccionUni);
                    doc.text(10, 260,'Teléfono: ' +info.telUni);
                    doc.text(10, 265,'Atención las 24 hrs.');
                    doc.text(10, 270,'Quejas y Sugerencias 01 800 3 MEDICA');

                    doc.text(10, 280, info.cadena);

                    doc.save('RC'+info.folio + '.pdf');

                    $('#genera').button('reset');
                      
                }

                $scope.click = function(datos){

                  $('#genera').button('loading');

                  info = datos;
                  var folio = info.folio;
                  console.log(datos);

                  getImage1('imgs/logomv.jpg', 'api/codigos/' + folio + '.png','imgs/nulo.png', getImage2);

                }
            }
        }
    
    });