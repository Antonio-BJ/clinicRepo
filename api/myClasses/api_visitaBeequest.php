
<?php 


// ************** ARCHIVO QUE CONSUME LA API DE BEEPQUEST **************`***********************************************
// *********************************************************************************************************************
// ************************************************************************************************************************

$correo="aalonso@medicavial.com.mx";
$fechaInicial="2022-02-28";
$fechaFinal="2022-03-28";
$limite=10;
$skit=0;

$fields = "{users: '$correo', initialDate: '$fechaInicial', finalDate: '$fechaFinal', limit: $limite, skip: $skit}";
$fields_string = json_decode($fields);

print_r($fields);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.beepquest.com/v1/visit-answers");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('BQAPPTOK: 1ES2KuN4FKmwWoeBbUSjb3ZCxWYEL34WUp6ZWvm0',
										  'BQMODTOK: ZcgqlRNlMOqtyfX56RSQYnvzdJm8ZpcGL0xBZwOl'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$html = curl_exec($ch);
$data = curl_exec($ch);
curl_close($ch);
var_dump($data);
var_dump(json_decode($data));


?>