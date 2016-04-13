<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Bourgoin
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

namespace Biblys\Isbn;

use GuzzleHttp\Client;

class Ranges
{
    private $prefixes, $groups;

    public function __construct()
    {
        include('ranges-array.php');
        $this->prefixes = $prefixes;
        $this->groups = $groups;
    }

    public function getPrefixes()
    {
        return $this->prefixes;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Generates the file 'ranges-array.php' which is used by this class
     *
     * ISBN range.xml file, get it here:
     * https://www.isbn-international.org/range_file_generation
     *
     */
    public static function updateArray()
    {
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

        $file = dirname(__FILE__) . '/ranges-array.php';
        echo "Saving to $file...\n";

        file_put_contents($file, '<?php # generated automatically by update-ranges.php, do not edit manually! ' . "\n"
            . '$groups = ' . var_export($groups, TRUE) . ";\n"
            . '$prefixes = ' . var_export($prefixes, TRUE) . ";\n"
        );
    }
}
