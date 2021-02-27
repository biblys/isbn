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
     * $isbn10 = ISBN::convertToIsbn10("9783464603529");
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
     * $isbn10 = ISBN::convertToIsbn13("9782207258040");
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
     * $isbn10 = ISBN::convertToEan13("978-2-207-25804-0");
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
     * $isbn10 = ISBN::convertToGtin14("9783464603529", 1);
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
    private $_isValid = true;
    private $_errors = array();

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
            $this->_isValid = false;
            $this->_errors[] = $exception->getMessage();
        }
    }

    /**
     * Checks if ISBN is valid
     * @return boolean true if the ISBN is valid
     */
    public function isValid()
    {
        return (bool) $this->_isValid;
    }

    /**
     * Returns a list of errors if ISBN is invalid
     * @return string the error list
     */
    public function getErrors()
    {
        $errors = '[' . $this->_input . ']';
        foreach ($this->_errors as $e) {
            $errors .= ' ' . $e;
        }
        return $errors;
    }

    /**
     * Throws an exception if ISBN is invalid
     */
    public function validate()
    {
        $errors = $this->_errors;
        if ($errors) {
            throw new \Exception($errors[0]);
        }

        return true;
    }

    /**
     * Formats an ISBN according to specified format
     *
     * @param string $format (ISBN-10, ISBN-13, EAN-13, GTIN-14), default EAN-13
     * @param string $prefix The prefix to use when formatting, default 1
     */
    public function format($format = 'EAN-13', $prefix = 1)
    {
        try {
            switch ($format) {
                case 'ISBN-10':
                    return Formatter::formatAsIsbn10($this->_input);

                case 'ISBN-13':
                case 'ISBN':
                    return Formatter::formatAsIsbn13($this->_input);

                case 'GTIN-14':
                    return Formatter::formatAsGtin14($this->_input, $prefix);

                case 'EAN-13':
                case 'EAN':
                default:
                    return Formatter::formatAsEan13($this->_input);
            }
        } catch (IsbnParsingException $exception) {
            // FIXME: remove message customization
            // (kept for retrocompatibility)
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
