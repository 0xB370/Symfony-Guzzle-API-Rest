<?php

require __DIR__.'/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000',
    'timeout'  => 2.0
]);

//Primer parámetro a ingresar por consola
$desde =  $argv[1];

//Segundo parámetro a ingresar por consola
$hasta = $argv[2];

//Tercer parámetro a ingresar por consola
$pag = $argv[3];

//Cuarto parámetro a ingresar por consola
$nroProd = $argv[4];

$response = $client->request('GET', '/producto/findByPrecio/'. $desde .'/'. $hasta .'/'. $pag .'/'. $nroProd);

$code = $response->getStatusCode();
$reason = $response->getReasonPhrase();
echo 'Code: '. $code .' '. $reason;
echo "\n";
foreach ($response->getHeaders() as $name => $values) {
    echo $name . ': ' . implode(', ', $values) . "\r\n";
}
echo $response->getBody();
echo "\n\n";