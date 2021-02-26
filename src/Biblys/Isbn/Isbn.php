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
    // GS1 Product Code (978 or 979 for books)
    private $_product;
    // Registrant group (country) code
    private $_country;
    // Registrant (publisher) Code
    private $_publisher;
    // Publication code
    private $_publication;
    // Checksum character
    private $_checksum;
    // Prefix for GTIN-14 formatting
    private $_gtin14Prefix;
    // Input code
    private $_input;
    // Is the code a valid ISBN
    private $_isValid = true;
    // Why is the code invalid
    private $_errors = array();
    // ISBN Agency
    private $_agency;

    public function __construct($code = null)
    {
        $this->_input = $code;

        try {
            $parsedCode = Parser::parse($code);

            $this->setProduct($parsedCode["productCode"]);
            $this->setCountry($parsedCode["countryCode"]);
            $this->setAgency($parsedCode["agencyCode"]);
            $this->setPublisher($parsedCode["publisherCode"]);
            $this->setPublication($parsedCode["publicationCode"]);
        } catch (IsbnParsingException $exception) {
            $this->setValid(false);
            $this->addError($exception->getMessage());
        }
    }

    /* Check methods */

    /**
     * Check if ISBN is valid
     */
    public function isValid()
    {
        return (bool) $this->_isValid;
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
            // FIXME: remove message customization on next major version
            // (kept for retrocompatibility)
            throw new IsbnParsingException(
                "Cannot format invalid ISBN: [$this->_input] " . $exception->getMessage()
            );
        }
    }

    // Private methods

    /**
     * Set ISBN Validity
     */
    private function setValid($isValid)
    {
        $this->_isValid = (bool) $isValid;
    }

    /**
     * Add to error log
     */
    private function addError($error)
    {
        $this->_errors[] = (string) $error;
    }

    /* SETTERS */

    private function setProduct($product)
    {
        $this->_product = $product;
    }

    private function setCountry($country)
    {
        $this->_country = $country;
    }

    private function setPublisher($publisher)
    {
        $this->_publisher = $publisher;
    }

    private function setPublication($publication)
    {
        $this->_publication = $publication;
    }

    private function setAgency($agency)
    {
        $this->_agency = $agency;
    }

    private function setGtin14Prefix($prefix)
    {
        $this->_gtin14Prefix = $prefix;
    }

    /* GETTERS */

    public function getProduct()
    {
        return $this->_product;
    }

    public function getCountry()
    {
        return $this->_country;
    }

    public function getPublisher()
    {
        return $this->_publisher;
    }

    public function getPublication()
    {
        return $this->_publication;
    }

    public function getChecksum()
    {
        return $this->_checksum;
    }

    public function getAgency()
    {
        return $this->_agency;
    }

    public function getGtin14Prefix()
    {
        return $this->_gtin14Prefix;
    }

    public function getErrors()
    {
        $errors = '[' . $this->_input . ']';
        foreach ($this->_errors as $e) {
            $errors .= ' ' . $e;
        }
        return $errors;
    }

    public function validate()
    {
        $errors = $this->_errors;
        if ($errors) {
            throw new \Exception($errors[0]);
        }

        return true;
    }
}
