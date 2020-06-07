<?php

require __DIR__.'/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000',
    'timeout'  => 2.0
]);

//Primer parámetro a ingresar por consola
$pag = $argv[1];

//Segundo parámetro a ingresar por consola
$nroTax = $argv[2];

$response = $client->request('GET', '/taxonomia/getPagina/'. $pag .'/'. $nroTax);

$code = $response->getStatusCode();
$reason = $response->getReasonPhrase();
echo 'Code: '. $code .' '. $reason;
echo "\n";
foreach ($response->getHeaders() as $name => $values) {
    echo $name . ': ' . implode(', ', $values) . "\r\n";
}
echo $response->getBody();
echo "\n\n";