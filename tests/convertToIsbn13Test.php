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

use Biblys\Isbn\Isbn;
use PHPUnit\Framework\TestCase;

class testConvertToIsbn13 extends TestCase
{
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
}
