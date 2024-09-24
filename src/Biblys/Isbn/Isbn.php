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

class Isbn
{
    /**
     * Converts input into an ISBN-10
     *
     * ISBN-10 are 10 characters long and includes hyphens.
     *
     * // Returns 3-464-60352-0
     * $isbn10 = Isbn::convertToIsbn10("9783464603529");
     *
     * @param string $input A string to convert
     *
     * @return string
     * @throws IsbnParsingException
     */
    static public function convertToIsbn10(string $input): string
    {
        return Formatter::formatAsIsbn10($input);
    }

    /**
     * Converts input into an ISBN-13
     *
     * ISBN-13 are 13 characters long and includes hyphens.
     *
     * // Returns 978-2-207-25804-0
     * $isbn10 = Isbn::convertToIsbn13("9782207258040");
     *
     * @param string $input A string to convert
     *
     * @return string
     * @throws IsbnParsingException
     */
    static public function convertToIsbn13(string $input): string
    {
        return Formatter::formatAsIsbn13($input);
    }

    /**
     * Converts input into an ISBN-A
     *
     * See https://www.doi.org/factsheets/ISBN-A.html for ISBN-A syntax
     *
     * // Returns 10.978.2207/258040
     * $isbnA = Isbn::convertToIsbnA("9782207258040");
     *
     * @param string $input A string to convert
     *
     * @return string
     * @throws IsbnParsingException
     */
    static public function convertToIsbnA(string $input): string
    {
        return Formatter::formatAsIsbnA($input);
    }

    /**
     * Converts input into an EAN-13
     *
     * EAN-13 are 13 characters long and does not include hyphens.
     *
     * // Returns 9782207258040
     * $isbn10 = Isbn::convertToEan13("978-2-207-25804-0");
     *
     * @param string $input A string to convert
     *
     * @return string
     * @throws IsbnParsingException
     */
    static public function convertToEan13(string $input): string
    {
        return Formatter::formatAsEan13($input);
    }

    /**
     * Converts input into a GTIN-14
     *
     * GTIN-14 are 14 characters long and does not include hyphens.
     *
     * // Returns 19783464603526
     * $isbn10 = Isbn::convertToGtin14("9783464603529", 1);
     *
     * @param string $input A string to convert
     * @param int $prefix A int to preprend (defaults to 1)
     *
     * @return string
     * @throws IsbnParsingException
     */
    static public function convertToGtin14(string $input, int $prefix = 1): string
    {
        return Formatter::formatAsGtin14($input, $prefix);
    }

    /**
     * Validates input as a correctly formed ISBN-10
     *
     * // Throws because second hyphen is misplaced
     * Isbn::validateAsIsbn10("3-46460-352-0");
     *
     * @param string $input A string to validate
     *
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    static public function validateAsIsbn10(string $input): void
    {
        $expected = Formatter::formatAsIsbn10($input);

        if ($input !== $expected) {
            throw new IsbnValidationException(
                "$input is not a valid ISBN-10. Expected $expected."
            );
        }
    }

    /**
     * Validates input as a correctly formed ISBN-13
     *
     * // Throws because second hyphen is misplaced
     * Isbn::validateAsIsbn13("978-220-7-25804-0");
     *
     * @param string $input A string to validate
     *
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    static public function validateAsIsbn13(string $input): void
    {
        $expected = Formatter::formatAsIsbn13($input);

        if ($input !== $expected) {
            throw new IsbnValidationException(
                "$input is not a valid ISBN-13. Expected $expected."
            );
        }
    }

    /**
     * Validates input as a correctly formed EAN-13
     *
     * // Throws because checksum character is invalid
     * Isbn::validateAsEan13("9782207258045");
     *
     * @param string $input A string to validate
     *
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    static public function validateAsEan13(string $input): void
    {
        $expected = Formatter::formatAsEan13($input);

        if ($input !== $expected) {
            throw new IsbnValidationException(
                "$input is not a valid EAN-13. Expected $expected."
            );
        }
    }

    /**
     * Checks that an input can be parsed (and thus, formatted) by the library
     *
     * // Returns false because string contains invalid characters
     * Isbn::validateAsEan13("9782SPI258040");
     *
     * @param string $input A string to check for parsability
     *
     * @return boolean true if the input can be parsed
     */
    static public function isParsable(string $input): bool
    {
        try {
            Parser::parse($input);
            return true;
        } catch (IsbnParsingException $exception) {
            return false;
        }
    }

    /**
     * Returns a parsed isbn
     *
     * @param string $input A string to be parsed as an ISBN
     *
     * @return ParsedIsbn the parsed isbn object
     * @throws IsbnParsingException
     */
    static public function parse(string $input): ParsedIsbn
    {
         return Parser::parse($input);
    }
}
