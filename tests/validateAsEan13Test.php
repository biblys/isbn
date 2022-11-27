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

class testValidateAsEan13 extends TestCase
{
    public function testValidIsbn()
    {
        Isbn::validateAsEan13("9782207258040");

        $this->expectNotToPerformAssertions("It should not throw");
    }

    public function testUnparsableIsbn()
    {
        $this->expectException(IsbnParsingException::class);
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsEan13("9782SPI258040");
    }

    public function testIsbnWithHyphens()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("978-2-207-25804-0 is not a valid EAN-13. Expected 9782207258040.");

        Isbn::validateAsEan13("978-2-207-25804-0");
    }

    public function testIsbnWithIncorrectCheckum()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("9782207258049 is not a valid EAN-13. Expected 9782207258040.");

        Isbn::validateAsEan13("9782207258049");
    }
}
