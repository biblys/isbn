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
use PHPUnit\Framework\TestCase;

class convertToIsbn13Test extends TestCase
{
    /**
     * @throws IsbnParsingException
     */
    public function testFormatIsbn13()
    {
        $Isbn13 = Isbn::convertToIsbn13("9782207258040");
        $this->assertEquals(
            "978-2-207-25804-0",
            $Isbn13,
            "It should convert to ISBN-13"
        );
    }

    public function testFormatIsbn13InvalidIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::convertToIsbn13("ABC-80-7203-7");
    }

    public function testIsbnWithUnknownGroup()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Cannot find any ISBN range matching prefix 978-630");

        Isbn::convertToIsbn13("978630002557");
    }
}
