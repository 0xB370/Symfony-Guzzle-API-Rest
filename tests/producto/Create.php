<?php

require __DIR__.'/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost:8000',
    'timeout'  => 2.0
]);

$data = array(
    'nombre' => 'Producto Testing',
    'descripcion' => 'Probando los tests con Guzzle',
    'precio' => '30',
    'imagen' => 'https://qalaboratory.com/wp-content/uploads/2019/06/Diferencia-entre-el-Testing-y-Quality-Assurance-420x280_c.png',
    //Asegurarse que la taxonomia a ingresar exista en la base de datos, de lo contrario devuelve 404 y un mensaje alusivo
    'taxonomia' => '1'
);

$response = $client->request('POST', '/producto/add', [
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