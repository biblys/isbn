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

class testValidateIsbn extends TestCase
{
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
