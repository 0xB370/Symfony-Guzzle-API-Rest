<?php

require __DIR__.'/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000',
    'timeout'  => 2.0
]);

//Primer parámetro a ingresar por consola
$id = $argv[1];

//Segundo parámetro (atributo a actualizar) y tercer parámetro (valor del atributo) a ingresar por consola
$data = array(
    $argv[2] => $argv[3],
);

$response = $client->request('PUT', '/taxonomia/update/'. $id , [
    'body' => json_encode($data)
]);

$code = $response->getStatusCode();
$reason = $response->getReasonPhrase();
echo 'Code: '. $code .' '. $reason;
echo "\n";
foreach ($response->getHeaders() as $name => $values) {
    echo $name . ': ' . implode(', ', $values) . "\r\n";
}
echo $response->getBody();
echo "\n\n";