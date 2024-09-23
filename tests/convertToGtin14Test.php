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

class convertToGtin14Test extends TestCase
{
    /**
     * @throws IsbnParsingException
     */
    public function testFormatGtin14()
    {
        $gtin14 = Isbn::convertToGtin14("9783464603529", 2);
        $this->assertEquals(
            "29783464603523",
            $gtin14,
            "It should convert to GTIN-14"
        );
    }

    /**
     * @throws IsbnParsingException
     */
    public function testFormatGtin14WithDefaultPrefix()
    {
        $gtin14 = Isbn::convertToGtin14("9783464603529");
        $this->assertEquals(
            "19783464603526",
            $gtin14,
            "It should convert to GTIN-14 using 1 as default prefix"
        );
    }

    public function testFormatGtin14InvalidIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::convertToGtin14("ABC-80-7203-7");
    }
}
