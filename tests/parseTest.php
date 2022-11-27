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

use Biblys\Isbn\Exception\EmptyInputException;
use Biblys\Isbn\Isbn;
use PHPUnit\Framework\TestCase;

class testParse extends TestCase
{
  public function testParseIsbn()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertInstanceOf("Biblys\Isbn\ParsedIsbn", $isbn);
  }

  public function testGetGs1Element()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("978", $isbn->getGs1Element());
  }

  public function testGetRegistrationGroupElement()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("2", $isbn->getRegistrationGroupElement());
  }

  public function testGetRegistrantElement()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("207", $isbn->getRegistrantElement());
  }

  public function testGetPublicationElement()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("25804", $isbn->getPublicationElement());
  }

  public function testGetRegistrationAgencyName()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("French language", $isbn->getRegistrationAgencyName());
  }
}
