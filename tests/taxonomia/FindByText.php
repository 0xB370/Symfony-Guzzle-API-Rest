<?php

require __DIR__.'/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000',
    'timeout'  => 2.0
]);

//Primer parámetro a ingresar por consola
$data = array(
    "texto" => $argv[1],
);

//Segundo parámetro a ingresar por consola
$pag = $argv[2];

//Tercer parámetro a ingresar por consola
$nroTax = $argv[3];

$response = $client->request('GET', '/taxonomia/findByText/'. $pag .'/'. $nroTax, [
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