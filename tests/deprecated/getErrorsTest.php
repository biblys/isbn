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

class testGetErrors extends TestCase
{
    public function testDeprecatedNotice()
    {
        $this->expectException('PHPUnit\Framework\Error\Deprecated');
        $this->expectExceptionMessage(
            "Isbn->getErrors is deprecated and will be removed in the future. Use Isbn::validateAs… methods instead. Learn more: https://git.io/JtAEx"
        );

        $isbn = new Isbn("6897896354577");

        PHPUnit\Framework\Error\Deprecated::$enabled = true;
        $isbn->getErrors();
    }

    public function testInvalidIsbn()
    {
        PHPUnit\Framework\Error\Deprecated::$enabled = false;
        $isbn = new Isbn("6897896354577");
        $this->assertEquals($isbn->getErrors(), '[6897896354577] Product code should be 978 or 979');
    }
}
