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

class convertToIsbnATest extends TestCase
{
    /**
     * @throws IsbnParsingException
     */
    public function testFormatIsbnA()
    {
        $IsbnA = Isbn::convertToIsbnA("9782207258040");
        $this->assertEquals(
            "10.978.2207/258040",
            $IsbnA,
            "It should convert to ISBN-A"
        );
    }

    public function testFormatIsbnAInvalidIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::convertToIsbnA("ABC-80-7203-7");
    }
}
