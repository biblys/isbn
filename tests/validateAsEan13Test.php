<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * © Clément Latzarus
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

class validateAsEan13Test extends TestCase
{
    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testValidIsbn()
    {
        Isbn::validateAsEan13("9782207258040");

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws IsbnValidationException
     */
    public function testUnparsableIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsEan13("9782SPI258040");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithHyphens()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("978-2-207-25804-0 is not a valid EAN-13. Expected 9782207258040.");

        Isbn::validateAsEan13("978-2-207-25804-0");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithIncorrectChecksum()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("9782207258049 is not a valid EAN-13. Expected 9782207258040.");

        Isbn::validateAsEan13("9782207258049");
    }
}
