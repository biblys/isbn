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

use Biblys\Isbn\Isbn as Isbn;
use PHPUnit\Framework\TestCase;

class testFormatIsbn extends TestCase
{
    public function testFormatIsbn13()
    {
        $isbn = new Isbn('9782207258040');
        $this->assertEquals($isbn->format('ISBN-13'), "978-2-207-25804-0");
    }

    public function testFormatIsbn10()
    {
        $isbn10 = new Isbn('9783464603529');
        $this->assertEquals($isbn10->format('ISBN-10'), "3-464-60352-0");
    }

    public function testFormatEan13()
    {
        $isbn10 = new Isbn("978-2-207-25804-0");
        $this->assertEquals($isbn10->format('EAN'), "9782207258040");
    }

    public function testFormatEan_13()
    {
        $isbn10 = new Isbn("978-2-207-25804-0");
        $this->assertEquals($isbn10->format('EAN-13'), "9782207258040");
    }

    public function testFormatGtin14()
    {
        $isbn = new Isbn('9783464603529');
        $this->assertEquals($isbn->format('GTIN-14'), '19783464603526');
    }

    public function testMauritiusRange()
    {
        $isbn = new Isbn('9786130971311');
        $this->assertEquals($isbn->format('ISBN-13'), "978-613-0-97131-1");
    }

    public function testIsbnWithInvalidProductCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cannot format invalid ISBN: [6897896354577] Product code should be 978 or 979");
        $isbn = new Isbn('6897896354577');
        $this->assertFalse($isbn->isValid());
        $isbn->format('EAN');
    }

    public function testIsbnWithInvalidCountryCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cannot format invalid ISBN: [9792887382562] Country code is unknown");
        $isbn = new Isbn('9792887382562');
        $this->assertFalse($isbn->isValid());
        $this->assertEquals($isbn->format('EAN'), '9792887382562');
    }
}
