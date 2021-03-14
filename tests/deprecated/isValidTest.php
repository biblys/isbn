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

class testIsbnIsValid extends TestCase
{
    protected function setUp(): void
    {
        PHPUnit\Framework\Error\Deprecated::$enabled = false;
    }

    public function testDeprecationNotice(): void
    {
        $this->expectException('PHPUnit\Framework\Error\Deprecated');
        $this->expectExceptionMessage(
            "Isbn->isValid is deprecated and will be removed in the future. Use Isbn::validateAs… methods instead. Learn more: https://git.io/JtAEx"
        );

        $isbn = new Isbn('9782207258040');

        PHPUnit\Framework\Error\Deprecated::$enabled = true;
        $isbn->isValid();
    }

    public function testIsValid()
    {
        $isbn = new Isbn('9782207258040');
        $this->assertTrue($isbn->isValid());
    }

    public function testIsNotValid()
    {
        $invalid = new Isbn('5780AAC728440');
        $this->assertFalse($invalid->isValid());
    }

    public function testIsbn10WithChecksumX()
    {
        $isbn = new ISBN('80-7203-717-X');
        $this->assertTrue($isbn->isValid());
    }

    public function testValidateMexicanIsbn()
    {
        $isbn = new Isbn("9700764923");
        $this->assertTrue($isbn->isValid());
    }
}
