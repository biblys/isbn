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

class testIsbn extends TestCase
{
    protected $isbn;

    public function setUp(): void
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

    public function testInvalidIsbn()
    {
        $isbn = new Isbn("6897896354577");
        $this->assertFalse($isbn->isValid());
        $this->assertEquals($isbn->getErrors(), '[6897896354577] Product code should be 978 or 979');
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
        $this->assertIsBool($isbn->isValid());
    }

    public function testIsbn10WithChecksumX()
    {
        $isbn = new ISBN('80-7203-717-X');
        $this->assertTrue($isbn->isValid());
    }

    public function testIsbnWithInvalidProductCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cannot format invalid ISBN: [6897896354577] Product code should be 978 or 979");
        $isbn = new Isbn('6897896354577');
        $this->assertFalse($isbn->isValid());
        $isbn13 = $isbn->format('EAN');
    }

    public function testIsbnWithInvalidCountryCode()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Cannot format invalid ISBN: [9792887382562] Country code is unknown");
        $isbn = new Isbn('9792887382562');
        $this->assertFalse($isbn->isValid());
        $this->assertEquals($isbn->format('EAN'), '9792887382562');
    }

    /**
     * Validate method should return true for a valid ISBN
     */
    public function testValidateValidIsbn()
    {
        $isbn = new Isbn('9782843449499');
        $this->assertTrue($isbn->validate());
    }

    /**
     * Validate method should throw of an invalid ISBN
     */
    public function testValidateInvalidIsbn()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Product code should be 978 or 979");
        $isbn = new Isbn('6752843449499');
        $isbn->validate();
    }

    /**
     * Validate method should not throw for a mexican ISBN
     */
    public function testValidateMexicanIsbn()
    {
      $isbn = new Isbn("9700764923");
      $this->assertTrue($isbn->isValid());
      $this->assertEquals($isbn->format("ISBN-13"), "978-970-07-6492-4");
    }

    /**
     * Invalid ISBN should not be validated
     * Github issue #22: https://github.com/biblys/isbn/issues/22
     */
    public function testOtherInvalidIsbn()
    {
        $this->expectException("Exception");
        $this->expectExceptionMessage("Invalid characters in the code");
        
        $isbn = new Isbn("34995031X");
        $isbn->validate();
    }
}
