<?php

require __DIR__.'/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000',
    'timeout'  => 2.0
]);

//Primer parámetro a ingresar por consola (texto a buscar)
$data = array(
    "texto" => $argv[1],
);

//Segundo parámetro a ingresar por consola (pagina a obtener)
$pag = $argv[2];
if ($pag === null){
    $pag = 1;
}

//Tercer parámetro a ingresar por consola (cantidad de taxonomías por página)
$nroTax = $argv[3];
if ($nroTax === null){
    $nroTax = 3;
}

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