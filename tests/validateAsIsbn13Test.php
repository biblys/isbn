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

use Biblys\Isbn\Exception\IsbnParsingException;
use Biblys\Isbn\Exception\IsbnValidationException;
use Biblys\Isbn\Isbn;
use PHPUnit\Framework\TestCase;

class testValidateAsIsbn13 extends TestCase
{
    public function testValidIsbn()
    {
        Isbn::validateAsIsbn13("978-2-207-25804-0");

        $this->expectNotToPerformAssertions("It should not throw");
    }

    public function testUnparsableIsbn()
    {
        $this->expectException(IsbnParsingException::class);
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn13("978-2-SPI-25804-0");
    }

    public function testIsbnWithMisplacedHyphen()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("978-220-7-25804-0 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        Isbn::validateAsIsbn13("978-220-7-25804-0");
    }

    public function testIsbnWithoutHyphens()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("9782207258040 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        Isbn::validateAsIsbn13("9782207258040");
    }

    public function testIsbnWithIncorrectCheckum()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("978-2-207-25804-2 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        Isbn::validateAsIsbn13("978-2-207-25804-2");
    }

    public function testValidateInvalidProductCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Product code should be 978 or 979");


        Isbn::validateAsIsbn13('6752843449499');
    }

    public function testValidateInvalidCharacters()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn13('5780AAC728440');
    }

    public function testIsbnWithInvalidProductCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Product code should be 978 or 979");

        Isbn::validateAsIsbn13('6897896354577');
    }

    public function testIsbnWithInvalidCountryCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Country code is unknown");

        Isbn::validateAsIsbn13('9792887382562');
    }

    /**
     * Non regression-test for Github issue #22
     * https://github.com/biblys/isbn/issues/22
     */
    public function testOtherInvalidIsbn()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn13("34995031X");
    }
}
