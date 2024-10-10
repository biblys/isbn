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
use PHPUnit\Framework\TestCase;

class isParsableTest extends TestCase
{
    public function testParsableIsbn()
    {
        $isParsable = Isbn::isParsable("9782207258040");

        $this->assertTrue($isParsable);
    }

    public function testUnparsableIsbn()
    {
        $isParsable = Isbn::isParsable("9782SPI258040");

        $this->assertFalse($isParsable);
    }
}
