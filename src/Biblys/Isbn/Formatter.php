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

class Formatter
{
    public static function calculateChecksum($format, $productCode, $countryCode, $publisherCode, $publicationCode, $gtin14prefix)
    {
        $sum = null;

        if ($format == 'ISBN-10') {
            $code = $countryCode . $publisherCode . $publicationCode;
            $c = str_split($code);
            $sum = (11 - (($c[0] * 10) + ($c[1] * 9) + ($c[2] * 8) + ($c[3] * 7) + ($c[4] * 6) + ($c[5] * 5) + ($c[6] * 4) + ($c[7] * 3) + ($c[8] * 2)) % 11) % 11;
            if ($sum == 10) {
                $sum = 'X';
            }
        } else {
            $code = $gtin14prefix . $productCode . $countryCode . $publisherCode . $publicationCode;
            $c = array_reverse(str_split($code));

            foreach ($c as $k => $v) {
                if ($k & 1) { // If current array key is odd
                    $sum += $v;
                } else { // If current array key is even
                    $sum += $v * 3;
                }
            }

            $sum = (10 - ($sum % 10)) % 10;
        }

        return $sum;
    }
}
