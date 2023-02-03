
<?php 


// ************** ARCHIVO QUE CONSUME LA API DE BEEPQUEST **************`***********************************************
// ************************************ANA DC***************************************************************************
// *********************************************************************************************************************
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$correo="aalonso@medicavial.com.mx";
$fechaInicial="2022-02-28";
$fechaFinal="2022-03-30";
$limite=10;
$skit=0;

$headers = [
	'Content-type: application/json',
    'BQAPPTOK: 1ES2KuN4FKmwWoeBbUSjb3ZCxWYEL34WUp6ZWvm0',
    'BQMODTOK: ZcgqlRNlMOqtyfX56RSQYnvzdJm8ZpcGL0xBZwOl'
];

$fields = ['users' => 'aalonso@medicavial.com.mx', 'initialDate'=> '2022-02-28', 'finalDate'=> '2022-03-30', 'limit'=> 1000, 'skip'=> 0];
// print_r($fields);

$datosCodificados = json_encode($fields);

// Comenzar a crear el objeto de curl
# A dónde se hace la petición...
$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)'; 
$url = "https://api.beepquest.com/v1/visit-answers";
$ch = curl_init($url);

# Ahora le ponemos todas las opciones
# Nota: podríamos usar la versión corta de arreglos: https://parzibyte.me/blog/2018/10/11/sintaxis-corta-array-php/
curl_setopt_array($ch, array(
    // Indicar que vamos a hacer una petición POST
    CURLOPT_CUSTOMREQUEST => "GET",
    // Justo aquí ponemos los datos dentro del cuerpo
    CURLOPT_POSTFIELDS => $datosCodificados,
    // Encabezados
    //CURLOPT_HEADER => true,
    CURLOPT_HTTPHEADER => array(
	    'BQAPPTOK: 1ES2KuN4FKmwWoeBbUSjb3ZCxWYEL34WUp6ZWvm0',
	    'BQMODTOK: ZcgqlRNlMOqtyfX56RSQYnvzdJm8ZpcGL0xBZwOl'
    ),
    # indicar que regrese los datos, no que los imprima directamente
    CURLOPT_RETURNTRANSFER => true,
));
# Hora de hacer la petición
$resultado = curl_exec($ch);
# Vemos si el código es 200, es decir, HTTP_OK
$codigoRespuesta = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if($codigoRespuesta === 200){
    # Decodificar JSON porque esa es la respuesta
    $respuestaDecodificada = json_decode($resultado);
    # Simplemente los imprimimos
    echo "<strong>El servidor dice que la hora de petición fue: </strong>" . $respuestaDecodificada->fechaYHora;
    echo "<br><strong>El servidor dice que el primer lenguaje es: </strong>" . $respuestaDecodificada->primerLenguaje;
    echo "<br><strong>Los encabezados que el servidor recibió fueron: </strong><pre>" . var_export($respuestaDecodificada->encabezados, true) . "</pre>";
    echo "<br><strong>Los gustos musicales que el servidor recibió fueron: </strong><pre>" . var_export($respuestaDecodificada->gustosMusicales, true) . "</pre>";
    echo "<br><strong>Los libros que el servidor recibió fueron: </strong><pre>" . var_export($respuestaDecodificada->libros, true) . "</pre>";
    echo "<br><strong>Mensaje del servidor: </strong>" . $respuestaDecodificada->mensaje;
}else{
    # Error
    echo "Error consultando. Código de respuesta: $codigoRespuesta";
}
curl_close($ch);

