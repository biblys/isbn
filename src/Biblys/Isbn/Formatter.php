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
        if ($format === 'ISBN-10') {
            return self::_calculateChecksumForIsbn10Format($countryCode, $publisherCode, $publicationCode);
        }

        if ($format === 'ISBN-13') {
            return self::_calculateChecksumForIsbn13Format($productCode, $countryCode, $publisherCode, $publicationCode);
        }

        if ($format === 'GTIN-14') {
            $productCodeWithPrefix = $gtin14prefix . $productCode;
            return self::_calculateChecksumForIsbn13Format($productCodeWithPrefix, $countryCode, $publisherCode, $publicationCode);
        }
    }

    private static function _calculateChecksumForIsbn10Format($countryCode, $publisherCode, $publicationCode)
    {
        $code = $countryCode . $publisherCode . $publicationCode;
        $chars = str_split($code);

        $checksum = (11 - (
            ($chars[0] * 10) +
            ($chars[1] * 9) +
            ($chars[2] * 8) +
            ($chars[3] * 7) +
            ($chars[4] * 6) +
            ($chars[5] * 5) +
            ($chars[6] * 4) +
            ($chars[7] * 3) +
            ($chars[8] * 2)) % 11) % 11;

        if ($checksum == 10) {
            $checksum = 'X';
        }

        return $checksum;
    }

    private static function _calculateChecksumForIsbn13Format($productCode, $countryCode, $publisherCode, $publicationCode)
    {
        $checksum = null;

        $code = $productCode . $countryCode . $publisherCode . $publicationCode;
        $chars = array_reverse(str_split($code));

        foreach ($chars as $index => $char) {
            if ($index & 1) {
                $checksum += $char;
            } else {
                $checksum += $char * 3;
            }
        }

        $checksum = (10 - ($checksum % 10)) % 10;

        return $checksum;
    }
}
