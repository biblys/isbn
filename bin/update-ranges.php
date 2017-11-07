<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Bourgoin
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
$res = $client->request('POST', 'https://www.isbn-international.org/?q=bl_proxy/GetRangeInformations', [
    'form_params' => [
        'format' => 1,
        'language' => 'en',
        'translatedTexts' => 'Printed;Last Change'
    ]
]);
$result = json_decode($res->getBody()->getContents());
$value = $result->result->value;
$filename = $result->result->filename;
$url = sprintf('https://www.isbn-international.org/?q=download_range/%s/%s', $value, $filename);

echo "Getting XML from $url...\n";
$res = $client->request('GET', $url);
$xml = $res->getBody()->getContents();

echo "Converting to PHP array...\n";
$ranges = (array) simplexml_load_string($xml);
$ranges = json_encode($ranges);
$ranges = json_decode($ranges, true);

$prefixes = (array) $ranges['EAN.UCCPrefixes']['EAN.UCC'];
$groups = (array) $ranges['RegistrationGroups']['Group'];

foreach ($groups as &$group) {
    // Fix entries with a single "range", converting it to an array
    if (isset($group['Rules']['Rule']['Range'])) {
        $group['Rules']['Rule'] = array($group['Rules']['Rule']);
    }
}

$file = dirname(__FILE__) . '/../src/Biblys/Isbn/ranges-array.php';
echo "Saving to $file...\n";

file_put_contents($file,
    '<?php ' . "\n"
    . '/*' . "\n"
    . ' * This file is generated automatically by update-ranges.php, do not edit' . "\n"
    . ' * manually! See README.md for more info. ' . "\n"
    . ' *' . "\n"
    . ' * This file is part of the biblys/isbn package.' . "\n"
    . ' *' . "\n"
    . ' * (c) Clément Bourgoin' . "\n"
    . ' *' . "\n"
    . ' * This package is Open Source Software. For the full copyright and license' . "\n"
    . ' * information, please view the LICENSE file which was distributed with this' . "\n"
    . ' * source code.' . "\n"
    . ' */' . "\n\n"
    . '$groups = ' . var_export($groups, TRUE) . ";\n"
    . '$prefixes = ' . var_export($prefixes, TRUE) . ";\n"
);
