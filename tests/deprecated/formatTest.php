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

require_once __DIR__ . '/../../vendor/autoload.php';

use Biblys\Isbn\Isbn as Isbn;
use PHPUnit\Framework\TestCase;

class testFormatIsbn extends TestCase
{
    protected function setUp(): void
    {
        PHPUnit\Framework\Error\Deprecated::$enabled = false;
    }

    public function testDeprecatedNotice()
    {
        $this->expectException('PHPUnit\Framework\Error\Deprecated');
        $this->expectExceptionMessage(
            "Isbn->format is deprecated and will be removed in the future. Use the Isbn::convertToIsbn13 method instead. Learn more: https://git.io/JtAEx"
        );

        $isbn = new Isbn('9782207258040');

        PHPUnit\Framework\Error\Deprecated::$enabled = true;
        $isbn->format('ISBN-13');
    }

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
}
