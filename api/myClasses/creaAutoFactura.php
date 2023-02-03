<?php

////////////////// crea autofactura //////////////
////////////////// creado by Ana DC //////////////

$curl = curl_init();
$token = "fd2101b31b922d7ba0d57cd8fc0e3890";

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Accept: application/json',
   'Authorization: Bearer ' . $token
   ));

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.facture.com.mx/api/autofactura',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
   "entity":{
     
      "data":{
        
         "claveAcceso":"51VBSpu00KOA3ePwbApA",
         
         "numTicket":"0000000001",
         
         "auxId":"12313A",
         
         "moneda":"MXN",
         
         "formaDePago":"01",
     
         "sucursal":{
            "id":42123
         },
         
         "partidas":[
            {
           
               "descripcion":"Exento Total",
               "valorUnitario":1000,
               "claveUnidad":"mtr",
               "claveProdServ": "10101501",
               "cantidad":10.00
            
            }
         ]
      }
   }
}',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
?>