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
              'BQVISTOK': 'bddhSekirKB47QXV3h9P4kh5P9gv61Ypxoi0WpGz'
            },
            data:{users:'aalonso@medicavial.com.mx',initialDate:'2022-03-25', finalDate:'2022-03-25',limit:'10',skip:0},
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
                          'BQMODTOK': 'tO9hRhnAdgkw4F9dGpsb8UwC7ogbYqDnP3cVQLV1'
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
                          'BQMODTOK': 'WJjljPuogODoRAYauFrZont8zs6VIN3zNEcYAGk4'
                        },
                        dataType:"json",
                        success: function(data1) {

                          // console.log(data1.answers);
                            // console.log(data1.answers.pregunta1_5);
                
                          var datos2 = [];
                          var datoGeneral = [];

                          var res1  = data1.answers.pregunta1_7.name;
                          var res2  = data1.answers.pregunta2_7.name;
                          var res3  = data1.answers.pregunta3_7.name;
                          var res4  = data1.answers.pregunta4_7.name;
                          var res5  = data1.answers.pregunta5_7.name;
                          var res6  = data1.answers.pregunta6_7.name;
                          var res7  = data1.answers.pregunta7_7.name;
                          var res8  = data1.answers.pregunta8_7.name;
                          var res9  = data1.answers.pregunta9_7.name;
                          var res10 = data1.answers.pregunta10_7.name;

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
                            res10:res10


                          });

                          datoGeneral.push({inicio1:datos, inicio2:datos1,preguntas:datos2});
                          console.log(datoGeneral);

                          $.ajax({
                            url: 'https://medicavial.net/mvnuevo/api/myClasses/Beepquest/guardaCuestionario.php',
                            method: 'POST',
                            data: {tipoCues:7,datoGeneral:datoGeneral,resp: datos2},
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
