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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use Biblys\Isbn\ISBN;
use PHPUnit\Framework\TestCase;

class testValidateAsIsbn13 extends TestCase
{
    public function testValidIsbn()
    {
        ISBN::validateAsIsbn13("978-2-207-25804-0");

        $this->expectNotToPerformAssertions("It should not throw");
    }

    public function testUnparsableIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        ISBN::validateAsIsbn13("978-2-SPI-25804-0");
    }

    public function testIsbnWithMisplacedHyphen()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("978-220-7-25804-0 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        ISBN::validateAsIsbn13("978-220-7-25804-0");
    }

    public function testIsbnWithoutHyphens()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("9782207258040 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        ISBN::validateAsIsbn13("9782207258040");
    }

    public function testIsbnWithIncorrectCheckum()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("978-2-207-25804-2 is not a valid ISBN-13. Expected 978-2-207-25804-0.");

        ISBN::validateAsIsbn13("978-2-207-25804-2");
    }
}
