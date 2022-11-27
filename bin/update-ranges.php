<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Latzarus
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 *
 * This script is used to update the ISBN ranges. See README.md for more info.
 *
 */

use GuzzleHttp\Client;

include('vendor/autoload.php');

echo "Requesting range information...\n";
$client = new Client();
$url = 'https://www.isbn-international.org/bl_proxy/GetRangeInformations';
$res = $client->request('POST', $url, [
    'form_params' => [
        'format' => 1,
        'language' => 'en',
        'translatedTexts' => 'Printed;Last Change'
    ]
]);

$result = json_decode($res->getBody()->getContents());
if ($result === null || !property_exists($result, 'result')) {
    exit("The ISBN range API at $url is currently unavailable, exiting...\n");
}

$value = $result->result->value;
$filename = $result->result->filename;
$url = sprintf('https://www.isbn-international.org/download_range/%s/%s', $value, $filename);

echo "Getting XML from $url...\n";
$res = $client->request('GET', $url);
$xml = $res->getBody()->getContents();

echo "Converting to PHP array...\n";
$ranges = (array) simplexml_load_string($xml);
$ranges = json_encode($ranges);
$ranges = json_decode($ranges, true);

processRanges(
    (array)$ranges['EAN.UCCPrefixes']['EAN.UCC'],
    dirname(__FILE__).'/../src/Biblys/Isbn/prefixes-array.php'
);
processRanges(
    (array) $ranges['RegistrationGroups']['Group'],
    dirname(__FILE__).'/../src/Biblys/Isbn/groups-array.php'
);

function processRanges($array, $filePath)
{
    $file = fopen($filePath, "w");
    if (! is_resource($file)) {
        exit("Could not open $filePath for writing...\n");
    }
    fwrite($file, '<?php '."\n"
                  .'/*'."\n"
                  .' * This file is generated automatically by update-ranges.php, do not edit'."\n"
                  .' * manually! See README.md for more info. '."\n"
                  .' *'."\n"
                  .' * This file is part of the biblys/isbn package.'."\n"
                  .' *'."\n"
                  .' * (c) Clément Latzarus'."\n"
                  .' *'."\n"
                  .' * This package is Open Source Software. For the full copyright and license'."\n"
                  .' * information, please view the LICENSE file which was distributed with this'."\n"
                  .' * source code.'."\n"
                  .' */'."\n\n"
                  . 'return ['."\n"
    ) or exit("Error writing to $filePath");

    foreach ($array as $item) {
        fwrite($file,
            sprintf(
                "    %s => [%s, [\n",
                var_export($item['Prefix'], true),
                var_export($item['Agency'], true)
            )) or exit("Error writing to $filePath");
        $rules = isset($item['Rules']['Rule']['Range']) ? [$item['Rules']['Rule']] : $item['Rules']['Rule'];
        foreach ($rules as $rule) {
            $range = explode('-', $rule['Range']);
            if (count($range) != 2) {
                exit("malformed Range in XML: {$rule['Range']}");
            }
            fwrite($file,
                sprintf(
                    "        [%s, %s, %d],\n",
                    var_export($range[0], true),
                    var_export($range[1], true),
                    intval($rule["Length"])
                )) or exit("Error writing to $filePath");
        }
        fwrite($file,"    ]],\n") or exit("Error writing to $filePath");
    }
    fwrite($file, '];'."\n") or exit("Error writing to $filePath");
    fclose($file) or exit("Error closing $filePath");
}
