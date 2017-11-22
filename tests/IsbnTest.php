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

class testIsbn extends PHPUnit_Framework_TestCase
{
    protected $isbn;

    public function setUp()
    {
        $this->isbn = new Isbn('9782207258040');
    }

    public function testIsValid()
    {
        $this->assertTrue($this->isbn->isValid());
    }

    public function testIsNotValid()
    {
        $invalid = new Isbn('5780AAC728440');
        $this->assertFalse($invalid->isValid());
    }

    public function testFormatIsbn13()
    {
        $this->assertEquals($this->isbn->format('ISBN-13'), "978-2-207-25804-0");
    }

    public function testFormatIsbn10()
    {
        $isbn10 = new Isbn('9783464603529');
        $this->assertEquals($isbn10->format('ISBN-10'), "3-464-60352-0");
    }

    public function testMauritiusRange()
    {
        $isbn = new Isbn('9786130971311');
        $this->assertEquals($isbn->format('ISBN-13'), "978-613-0-97131-1");
    }

    public function testUnknownPrefix()
    {
        // Should not raise an error
        $isbn = new Isbn('9790706801940');
        $this->assertInternalType('bool', $isbn->isValid());
    }

    public function testIsbn10WithChecksumX()
    {
        $isbn = new ISBN('80-7203-717-X');
        $this->assertTrue($isbn->isValid());
    }
}
