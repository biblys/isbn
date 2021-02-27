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
     */
    static public function convertToIsbn13(string $input): string
    {
        return Formatter::formatAsIsbn13($input);
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

    /* Legacy non static properties and methods (backward compatibility) */
    // FIXME: deprecate and remove on next major version

    private $_input;
    private $_gs1productCode;
    private $_countryCode;
    private $_publisherCode;
    private $_publicationCode;
    private $_isbnAgencyCode;
    private $_checksumCharacter;
    private $_gtin14Prefix;

    public function __construct($code = null)
    {
        $this->_input = $code;

        try {
            $parsedCode = Parser::parse($code);
            $this->_gs1productCode = $parsedCode["productCode"];
            $this->_countryCode = $parsedCode["countryCode"];
            $this->_isbnAgencyCode = $parsedCode["agencyCode"];
            $this->_publisherCode = $parsedCode["publisherCode"];
            $this->_publicationCode = $parsedCode["publicationCode"];
        } catch (IsbnParsingException $exception) {
            // FIXME in next major version (breaking change)
            // For backward compatibility reason, instanciating should not throw
        }
    }

    /**
     * Checks if ISBN is valid
     *
     * @deprecated
     *
     * @return boolean true if the ISBN is valid
     */
    public function isValid()
    {
        trigger_error(
            "Isbn->isValid is deprecated and will be removed in the future. Use Isbn::validateAs… methods instead. Learn more: https://git.io/JtAEx",
            E_USER_DEPRECATED
        );

        try {
            Parser::parse($this->_input);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Returns a list of errors if ISBN is invalid
     *
     * @deprecated
     *
     * @return string the error list
     */
    public function getErrors()
    {
        trigger_error(
            "Isbn->getErrors is deprecated and will be removed in the future. Use Isbn::validateAs… methods instead. Learn more: https://git.io/JtAEx",
            E_USER_DEPRECATED
        );

        try {
            Parser::parse($this->_input);
        } catch (\Exception $exception) {
            return '[' . $this->_input . '] ' . $exception->getMessage();
        }
    }

    /**
     * Throws an exception if ISBN is invalid
     *
     * @deprecated
     */
    public function validate()
    {
        trigger_error(
            "Isbn->validate is deprecated and will be removed in the future. Use Isbn::validateAs… methods instead. Learn more: https://git.io/JtAEx",
            E_USER_DEPRECATED
        );

        Parser::parse($this->_input);
        return true;
    }

    /**
     * Formats an ISBN according to specified format
     *
     * @deprecated
     *
     * @param string $format (ISBN-10, ISBN-13, EAN-13, GTIN-14), default EAN-13
     * @param string $prefix The prefix to use when formatting, default 1
     *
     * @return string the formatted ISBN
     */
    public function format($format = 'EAN-13', $prefix = 1)
    {
        try {
            switch ($format) {
                case 'ISBN-10':
                    trigger_error(
                        "Isbn->format is deprecated and will be removed in the future. Use the Isbn::convertToIsbn10 method instead. Learn more: https://git.io/JtAEx",
                        E_USER_DEPRECATED
                    );
                    return Formatter::formatAsIsbn10($this->_input);

                case 'ISBN-13':
                case 'ISBN':
                    trigger_error(
                        "Isbn->format is deprecated and will be removed in the future. Use the Isbn::convertToIsbn13 method instead. Learn more: https://git.io/JtAEx",
                        E_USER_DEPRECATED
                    );
                    return Formatter::formatAsIsbn13($this->_input);

                case 'GTIN-14':
                    trigger_error(
                        "Isbn->format is deprecated and will be removed in the future. Use the Isbn::convertToGtin14 method instead. Learn more: https://git.io/JtAEx",
                        E_USER_DEPRECATED
                    );
                    return Formatter::formatAsGtin14($this->_input, $prefix);

                case 'EAN-13':
                case 'EAN':
                default:
                    trigger_error(
                        "Isbn->format is deprecated and will be removed in the future. Use the Isbn::convertToEan13 method instead. Learn more: https://git.io/JtAEx",
                        E_USER_DEPRECATED
                    );
                    return Formatter::formatAsEan13($this->_input);
            }
        } catch (IsbnParsingException $exception) {
            // FIXME: remove message customization
            // (kept for backward compatibility)
            throw new IsbnParsingException(
                "Cannot format invalid ISBN: [$this->_input] " . $exception->getMessage()
            );
        }
    }

    public function getProduct()
    {
        return $this->_gs1productCode;
    }

    public function getCountry()
    {
        return $this->_countryCode;
    }

    public function getPublisher()
    {
        return $this->_publisherCode;
    }

    public function getPublication()
    {
        return $this->_publicationCode;
    }

    public function getChecksum()
    {
        return $this->_checksumCharacter;
    }

    public function getAgency()
    {
        return $this->_isbnAgencyCode;
    }

    public function getGtin14Prefix()
    {
        return $this->_gtin14Prefix;
    }
}
