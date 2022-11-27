<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Latzarus
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

namespace Biblys\Isbn;

use Biblys\Isbn\Exception\IsbnParsingException;

class Formatter
{
    /**
     * @throws IsbnParsingException
     */
    public static function formatAsIsbn10(string $input): string
    {
        $parsedInput = Parser::parse($input);
        $countryCode = $parsedInput->getRegistrationGroupElement();
        $publisherCode = $parsedInput->getRegistrantElement();
        $publicationCode = $parsedInput->getPublicationElement();
        $checksum = self::_calculateChecksumForIsbn10Format($countryCode, $publisherCode, $publicationCode);

        return "$countryCode-$publisherCode-$publicationCode-$checksum";
    }

    /**
     * @throws IsbnParsingException
     */
    public static function formatAsIsbn13(string $input): string
    {
        $parsedInput = Parser::parse($input);
        $productCode = $parsedInput->getGs1Element();
        $countryCode = $parsedInput->getRegistrationGroupElement();
        $publisherCode = $parsedInput->getRegistrantElement();
        $publicationCode = $parsedInput->getPublicationElement();
        $checksum = self::_calculateChecksumForIsbn13Format($productCode, $countryCode, $publisherCode, $publicationCode);

        return "$productCode-$countryCode-$publisherCode-$publicationCode-$checksum";
    }

    /**
     * @throws IsbnParsingException
     */
    public static function formatAsIsbnA(string $input): string
    {
        $doiPrefix = "10";
        $parsedInput = Parser::parse($input);
        $productCode = $parsedInput->getGs1Element();
        $countryCode = $parsedInput->getRegistrationGroupElement();
        $publisherCode = $parsedInput->getRegistrantElement();
        $publicationCode = $parsedInput->getPublicationElement();
        $checksum = self::_calculateChecksumForIsbn13Format($productCode, $countryCode, $publisherCode, $publicationCode);

        return "$doiPrefix.$productCode.$countryCode$publisherCode/$publicationCode$checksum";
    }

    /**
     * @throws IsbnParsingException
     */
    public static function formatAsEan13(string $input): string
    {
        $parsedInput = Parser::parse($input);
        $productCode = $parsedInput->getGs1Element();
        $countryCode = $parsedInput->getRegistrationGroupElement();
        $publisherCode = $parsedInput->getRegistrantElement();
        $publicationCode = $parsedInput->getPublicationElement();
        $checksum = self::_calculateChecksumForIsbn13Format($productCode, $countryCode, $publisherCode, $publicationCode);

        return $productCode . $countryCode . $publisherCode . $publicationCode . $checksum;
    }

    /**
     * @throws IsbnParsingException
     */
    public static function formatAsGtin14(string $input, int $prefix): string
    {
        $parsedInput = Parser::parse($input);
        $productCode = $parsedInput->getGs1Element();
        $countryCode = $parsedInput->getRegistrationGroupElement();
        $publisherCode = $parsedInput->getRegistrantElement();
        $publicationCode = $parsedInput->getPublicationElement();

        $productCodeWithPrefix = $prefix . $productCode;
        $checksum = self::_calculateChecksumForIsbn13Format($productCodeWithPrefix, $countryCode, $publisherCode, $publicationCode);

        return $prefix . $productCode . $countryCode . $publisherCode . $publicationCode . $checksum;
    }

    private static function _calculateChecksumForIsbn10Format(
        string $countryCode,
        string $publisherCode,
        string $publicationCode
    ): string {
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

    private static function _calculateChecksumForIsbn13Format(
        string $productCode,
        string $countryCode,
        string $publisherCode,
        string $publicationCode
    ): string {
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
