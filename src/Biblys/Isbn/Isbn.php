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
    // FIXME in next major version (breaking change)
    // (kept for backward compatibility)
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

    /* Validation methods */

    /**
     * Check if ISBN is valid
     * @return boolean true if the ISBN is valid
     */
    public function isValid()
    {
        try {
            $this->validate();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Returns a list of errors if ISBN is invalid
     * @return string the error list
     */
    public function getErrors()
    {
        try {
            $this->validate();
        } catch (\Exception $exception) {
            return '[' . $this->_input . '] ' . $exception->getMessage();
        }
    }

    /**
     * Throws an exception if ISBN is invalid
     */
    public function validate()
    {
        Parser::parse($this->_input);
        return true;
    }

    /* Format methods */

    /**
     * Format an ISBN according to specified format
     * @param string $format (ISBN-10, ISBN-13, EAN, GTIN-14), default EAN
     * @param string $prefix The prefix to use when formatting, default 1
     */
    public function format($format = 'EAN', $prefix = 1)
    {
        try {
            return Formatter::format($this->_input, $format, $prefix);
        } catch (IsbnParsingException $exception) {
            // FIXME: remove message customization
            // (kept for backward compatibility)
            throw new IsbnParsingException(
                "Cannot format invalid ISBN: [$this->_input] " . $exception->getMessage()
            );
        }
    }

    /* Public getters */
    // FIXME: remove in next major version (breaking change)
    // Kept for backward compatibility

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
