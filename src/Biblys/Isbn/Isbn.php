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
        if (!$this->isValid()) {
            throw new \Exception('Cannot format invalid ISBN: ' . $this->getErrors());
        }

        if ($format == 'GTIN-14') {
            $this->setGtin14Prefix($prefix);
        } else {
            $this->setGtin14Prefix(NULL);
        }

        $this->calculateChecksum($format);

        $A = $this->getGtin14Prefix();
        $B = $this->getProduct();
        $C = $this->getCountry();
        $D = $this->getPublisher();
        $E = $this->getPublication();
        $F = $this->getChecksum();

        switch ($format) {
            case 'ISBN-10':
                return "$C-$D-$E-$F";
                break;

            case 'ISBN-13':
            case 'ISBN':
                return "$B-$C-$D-$E-$F";
                break;

            case 'GTIN-14':
                return $A . $B . $C . $D . $E . $F;
                break;

            default:
                return $B . $C . $D . $E . $F;
                break;
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

    /**
     * Calculate checksum character
     */
    private function calculateChecksum($format = 'EAN')
    {
        $sum = null;

        if ($format == 'ISBN-10') {
            $code = $this->getCountry() . $this->getPublisher() . $this->getPublication();
            $c = str_split($code);
            $sum = (11 - (($c[0] * 10) + ($c[1] * 9) + ($c[2] * 8) + ($c[3] * 7) + ($c[4] * 6) + ($c[5] * 5) + ($c[6] * 4) + ($c[7] * 3) + ($c[8] * 2)) % 11) % 11;
            if ($sum == 10) {
                $sum = 'X';
            }
        } else {
            $code = $this->getGtin14Prefix() . $this->getProduct() . $this->getCountry() . $this->getPublisher() . $this->getPublication();
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

        $this->setChecksum($sum);
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

    private function setChecksum($checksum)
    {
        $this->_checksum = $checksum;
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
