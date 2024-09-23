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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Isbn\IsbnValidationException;
use PHPUnit\Framework\TestCase;

class validateAsIsbn13Test extends TestCase
{
    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testValidIsbn()
    {
        Isbn::validateAsIsbn13("978-2-207-25804-0");

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws IsbnValidationException
     */
    public function testUnparsableIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn13("978-2-SPI-25804-0");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithMisplacedHyphen()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("978-220-7-25804-0 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        Isbn::validateAsIsbn13("978-220-7-25804-0");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithoutHyphens()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("9782207258040 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        Isbn::validateAsIsbn13("9782207258040");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithIncorrectChecksum()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("978-2-207-25804-2 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        Isbn::validateAsIsbn13("978-2-207-25804-2");
    }

    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testValidateInvalidProductCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Product code should be 978 or 979");


        Isbn::validateAsIsbn13('6752843449499');
    }

    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testValidateInvalidCharacters()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn13('5780AAC728440');
    }

    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testIsbnWithInvalidProductCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Product code should be 978 or 979");

        Isbn::validateAsIsbn13('6897896354577');
    }

    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testIsbnWithInvalidCountryCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Country code is unknown");

        Isbn::validateAsIsbn13('9792887382562');
    }

    /**
     * Non-regression-test for GitHub issue #22
     * https://github.com/biblys/isbn/issues/22
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testOtherInvalidIsbn()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn13("34995031X");
    }
}
