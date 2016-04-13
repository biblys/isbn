<?php

include('vendor/autoload.php');

echo "Requesting XML ranges file...\n";

$client = new GuzzleHttp\Client();
$res = $client->request('POST', 'https://www.isbn-international.org/?q=bl_proxy/GetRangeInformations', [
    'form_params' => [
        'format' => 1,
        'language' => 'en',
        'translatedTexts' => 'Printed;Last Change'
    ]
]);

echo $res->getBody();

echo $res->getStatusCode();
