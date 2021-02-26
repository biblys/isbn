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

class testConstructor extends TestCase
{
    /**
     * Non-regression test for Github issue #5
     * https://github.com/biblys/isbn/pull/5
     */
    public function testUnknownPrefix()
    {
        $isbn = new Isbn("9790706801940");

        $this->expectNotToPerformAssertions("Should not throw");
    }
}
