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
              'BQVISTOK': 'ZcgqlRNlMOqtyfX56RSQYnvzdJm8ZpcGL0xBZwOl'
            },
            data:{users:'aalonso@medicavial.com.mx',initialDate:'2022-03-24', finalDate:'2022-03-24',limit:'10',skip:0},
            dataType:"json",
            success: function(data) {

              modulos = data.list;

              for (var i = 0; i < modulos.length; i++) {

                    var idAdicionales = modulos[i].activities[1].moduleAnswerId;
                    var idPreguntas   = modulos[i].activities[0].moduleAnswerId;
                    // console.log(modulos[i].activities);
                    // console.log(modulos[i]);

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
                          'BQMODTOK': 'oZPOBrxu3WQNJt5OMl9LlSMtTxJI9fkaWkaokALJ'
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
                          'BQMODTOK': 'nXjYQprCifhzj2CvxsMpLcWWffW09kyDaSLO2Od7'
                        },
                        dataType:"json",
                        success: function(data1) {

                          // console.log(data1.answers);
                            // console.log(data1.answers.pregunta1_5);
                
                          var datos2 = [];
                          var datoGeneral = [];

                          var res1  = data1.answers.pregunta1_5.name;
                          var res2  = data1.answers.pregunta2_5.name;
                          var res3  = data1.answers.pregunta3_5.name;
                          var res4  = data1.answers.pregunta4_5.name;
                          var res5  = data1.answers.pregunta5_5.name;
                          var res6  = data1.answers.pregunta6_5.name;
                          var res7  = data1.answers.pregunta7_5.name;
                          var res8  = data1.answers.pregunta8_5.name;
                          var res9  = data1.answers.pregunta9_5.name;
                          var res10 = data1.answers.pregunta10_5.name;
                          var res11 = data1.answers.pregunta11_5.name;
                          var res12 = data1.answers.pregunta12_5.name;

                          datos2.push({

                            res1:res1,
                            res2:res2,
                            res3:res3,
                            res4:res4,
                            res5:res5,
                            res6:res6,
                            res7:res7,
                            res8:res8,
                            res9:res9,
                            res10:res10,
                            res11:res11,
                            res12:res12


                          });

                          datoGeneral.push({inicio1:datos, inicio2:datos1,preguntas:datos2});
                          console.log(datoGeneral);

                          $.ajax({
                            url: 'https://medicavial.net/mvnuevo/api/myClasses/Beepquest/guardaCuestionario.php',
                            method: 'POST',
                            data: {tipoCues:10,datoGeneral:datoGeneral,resp: datos2},
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
