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

class testGetErrors extends TestCase
{
    public function testInvalidIsbn()
    {
        $isbn = new Isbn("6897896354577");
        $this->assertFalse($isbn->isValid());
        $this->assertEquals($isbn->getErrors(), '[6897896354577] Product code should be 978 or 979');
    }
}
