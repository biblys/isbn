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

use Biblys\Isbn\Exception\EmptyInputException;
use Biblys\Isbn\Exception\InvalidCharactersException;
use Biblys\Isbn\Exception\IsbnParsingException;

class Parser
{
    // FIXME: Create custom exceptions for each case
    const
        ERROR_INVALID_LENGTH = 'Code is too short or too long',
        ERROR_INVALID_PRODUCT_CODE = 'Product code should be 978 or 979',
        ERROR_INVALID_COUNTRY_CODE = 'Country code is unknown',
        ERROR_CANNOT_MATCH_RANGE = "Cannot find any ISBN range matching prefix %s";

  /**
   * @throws EmptyInputException
   * @throws InvalidCharactersException
   */
  public static function parse(string $input): ParsedIsbn
    {
        if (empty($input)) {
            throw new EmptyInputException();
        }

        $inputWithoutHyphens = self::_stripHyphens($input);
        $inputWithoutChecksum = self::_stripChecksum($inputWithoutHyphens);

        $invalidCharacters = self::_extractInvalidCharacters($inputWithoutChecksum);
        if (!empty($invalidCharacters)) {
            throw new InvalidCharactersException($invalidCharacters);
        }

        $result = self::_extractProductCode($inputWithoutChecksum);
        $inputWithoutProductCode = $result[0];
        $productCode = $result[1];

        $result = self::_extractCountryCode($inputWithoutProductCode, $productCode);
        $inputWithoutCountryCode = $result[0];
        $countryCode = $result[1];

        $result = self::_extractPublisherCode($inputWithoutCountryCode, $productCode, $countryCode);
        $agencyCode = $result[0];
        $publisherCode = $result[1];
        $publicationCode = $result[2];

        return new ParsedIsbn([
            "gs1Element" => $productCode,
            "registrationGroupElement" => $countryCode,
            "registrationAgencyName" => $agencyCode,
            "registrantElement" => $publisherCode,
            "publicationElement" => $publicationCode,
        ]);
    }

    private static function _stripHyphens($input)
    {
        $replacements = array('-', '_', ' ');
        $input = str_replace($replacements, '', $input);

        return $input;
    }

    private static function _stripChecksum($input)
    {
        $length = strlen($input);

        if ($length == 12 || $length == 9) {
            return $input;
        }

        if ($length == 13 || $length == 10) {
            $input = substr_replace($input, "", -1);
            return $input;
        }

        throw new IsbnParsingException(static::ERROR_INVALID_LENGTH);
    }

    private static function _extractProductCode($input)
    {
        if (strlen($input) == 9) {
            return [$input, 978];
        }

        $first3 = substr($input, 0, 3);
        if ($first3 == 978 || $first3 == 979) {
            $input = substr($input, 3);
            return [$input, $first3];
        }

        throw new IsbnParsingException(static::ERROR_INVALID_PRODUCT_CODE);
    }

    private static function _extractCountryCode($input, $productCode)
    {

        // Get the seven first digits
        $first7 = substr($input, 0, 7);

        // Select the right set of rules according to the product code
        $prefixes = Ranges::getPrefixes();
        if (!isset($prefixes[$productCode])) {
            throw new IsbnParsingException(static::ERROR_INVALID_COUNTRY_CODE);
        }

        // Select the right rule
        foreach ($prefixes[$productCode][1] as $r) {
            if ($first7 >= $r[0] && $first7 <= $r[1]) {
                $length = $r[2];
                break;
            }
        }

        // Country code is invalid
        if (empty($length)) {
            throw new IsbnParsingException(static::ERROR_INVALID_COUNTRY_CODE);
        };

        $countryCode = substr($input, 0, $length);
        $input = substr($input, $length);

        return [$input, $countryCode];
    }

    /**
     * Remove and save Publisher Code and Publication Code
     */
    private static function _extractPublisherCode($input, $productCode, $countryCode)
    {
        // Get the seven first digits or less
        $first7 = substr($input, 0, 7);
        $inputLength = strlen($first7);

        // Select the right set of rules according to the agency (product + country code)
        $groups = Ranges::getGroups();
        $prefix = $productCode . '-' . $countryCode;

        $g = $groups[$prefix] ?? ["", []];
        $agency = $g[0];
        $rules = $g[1];

        // Select the right rule
        foreach ($rules as $rule) {

            // Get min and max value in range
            // and trim values to match code length
            $min = substr($rule[0], 0, $inputLength);
            $max = substr($rule[1], 0, $inputLength);

            // If first 7 digits is smaller than min
            // or greater than max, continue to next rule
            if ($first7 < $min || $first7 > $max) {
                continue;
            }

            $length = $rule[2];

            if ($length == 0) {
                throw new IsbnParsingException(
                    sprintf(static::ERROR_CANNOT_MATCH_RANGE, $prefix)
                );
            }

            $publisherCode = substr($input, 0, $length);
            $publicationCode = substr($input, $length);

            return [$agency, $publisherCode, $publicationCode];
        }

        throw new IsbnParsingException(
            sprintf(static::ERROR_CANNOT_MATCH_RANGE, $prefix)
        );
    }

    /**
     * @param string $inputWithoutChecksum
     * @return string
     */
    public static function _extractInvalidCharacters(string $inputWithoutChecksum): string
    {
        return preg_replace('/[0-9]+/', '', $inputWithoutChecksum);
    }
}
