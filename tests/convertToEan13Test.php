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
use Biblys\Isbn\Isbn;
use PHPUnit\Framework\TestCase;

class testConvertToEan13 extends TestCase
{
    public function testFormatEan13()
    {
        $ean13 = Isbn::convertToEan13("978-2-207-25804-0");
        $this->assertEquals(
            "9782207258040",
            $ean13,
            "It should convert to EAN-13"
        );
    }

    public function testFormatEan13InvalidIsbn()
    {
        $this->expectException(IsbnParsingException::class);
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::convertToEan13("ABC-80-7203-7");
    }
}
