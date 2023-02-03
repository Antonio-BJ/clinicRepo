<script src="lib/jquery.min.js"></script>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(0);
ini_set ('memory_limit', '-1');

?>
<script>
          $.ajax({
            url: 'https://api.beepquest.com/v1/visit-answers',
            method: 'GET',
            headers: {
              'BQAPPTOK': '1ES2KuN4FKmwWoeBbUSjb3ZCxWYEL34WUp6ZWvm0',
              'BQVISTOK': 'fRmTW8RHN8J6OYgrkUAVmWMn2pCeJHaS7ZsZ98sW'
            },
            data:{users:'aalonso@medicavial.com.mx',initialDate:'2022-03-25', finalDate:'2022-03-25',limit:'10',skip:0},
            dataType:"json",
            success: function(data) {

              modulos = data.list;

              for (var i = 0; i < modulos.length; i++) {

                    var idAdicionales = modulos[i].activities[1].moduleAnswerId;
                    var idPreguntas   = modulos[i].activities[0].moduleAnswerId;
                    // console.log(modulos[i].activities);
                    console.log(modulos[i]);

                      var datos = [];

                      datos.push({

                        folioMV: modulos[i].keysAnswers.Exp_folio,
                        fechaCaptura: '',
                        fechaRespondio: modulos[i].created,
                        telContacto: '',
                        correo: ''

                      }); 

                        $.ajax({
                        url: 'https://api.beepquest.com/v1/question-module-answers/'+idAdicionales,
                        method: 'GET',
                        headers: {
                          'BQAPPTOK': '1ES2KuN4FKmwWoeBbUSjb3ZCxWYEL34WUp6ZWvm0',
                          'BQMODTOK': '36D99sHRqLQdyWXLv7AUX5NywrI2zE8CfSep2Qgg'
                        },
                        dataType:"json",
                        success: function(data) {

                          var datos1      = [];
                          var nombre      = data.user.firstName;
                          var telefono    = data.answers.movil;
                          var correo      = data.answers.correo;

                          datos1.push({usuCaptura:nombre,telContacto:telefono, correo:correo});
                      

                    $.ajax({
                        url: 'https://api.beepquest.com/v1/question-module-answers/'+idPreguntas,
                        method: 'GET',
                        headers: {
                          'BQAPPTOK': '1ES2KuN4FKmwWoeBbUSjb3ZCxWYEL34WUp6ZWvm0',
                          'BQMODTOK': 'REpYLrKmnzwE3lVs0SxppV77WKyoGP8Pj0YqSCIY'
                        },
                        dataType:"json",
                        success: function(data1) {

                          // console.log(data1.answers);
                            // console.log(data1.answers.pregunta1_5);
                
                          var datos2 = [];
                          var datoGeneral = [];

                          var res1  = data1.answers.pregunta1_9.name;
                          var res2  = data1.answers.pregunta2_9.name;
                          var res3  = data1.answers.pregunta3_9.name;
                          var res4  = data1.answers.pregunta4_9.name;
                          var res5  = data1.answers.pregunta5_9.name;
                          var res6  = data1.answers.pregunta6_9.name;
                          var res7  = data1.answers.pregunta7_9.name;


                          datos2.push({

                            res1:res1,
                            res2:res2,
                            res3:res3,
                            res4:res4,
                            res5:res5,
                            res6:res6,
                            res7:res7


                          });

                          datoGeneral.push({inicio1:datos, inicio2:datos1,preguntas:datos2});
                          console.log(datoGeneral);

                          $.ajax({
                            url: 'https://medicavial.net/mvnuevo/api/myClasses/Beepquest/guardaCuestionario.php',
                            method: 'POST',
                            data: {tipoCues:9,datoGeneral:datoGeneral,resp: datos2},
                            success: function(data2) {

                              // console.log(data2);


                              



                            }
                          });

                          



                        }
                      });
                  }
              });

                }

            }
          });
</script> 
