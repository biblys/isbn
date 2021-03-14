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

class testValidateIsbn extends TestCase
{
    protected function setUp(): void
    {
        PHPUnit\Framework\Error\Deprecated::$enabled = false;
    }

    public function testDeprecatedNotice(): void
    {
        $this->expectException('PHPUnit\Framework\Error\Deprecated');
        $this->expectExceptionMessage(
            "Isbn->validate is deprecated and will be removed in the future. Use Isbn::validateAs… methods instead. Learn more: https://git.io/JtAEx"
        );

        $isbn = new Isbn('9782843449499');

        PHPUnit\Framework\Error\Deprecated::$enabled = true;
        $isbn->validate();
    }

    public function testValidateValidIsbn()
    {
        $isbn = new Isbn('9782843449499');
        $this->assertTrue($isbn->validate());
    }

    /**
     * Non-regression test for Github issue #6
     * https://github.com/biblys/isbn/issues/6
     */
    public function testValidateIsbn10WithChecksumX()
    {
        $isbn = new Isbn('80-7203-717-X');
        $this->assertTrue($isbn->validate());
    }

    /**
     * Non-regression test for Github issue #21
     * https://github.com/biblys/isbn/issues/21
     */
    public function testValidateMexicanIsbn()
    {
        $isbn = new Isbn("9700764923");
        $this->assertTrue($isbn->validate());
    }
}
