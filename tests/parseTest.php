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
use Biblys\Isbn\IsbnParsingException;
use PHPUnit\Framework\TestCase;

class parseTest extends TestCase
{

    /**
     * @throws IsbnParsingException
     */
    public function testGetGs1Element()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("978", $isbn->getGs1Element());
  }

    /**
     * @throws IsbnParsingException
     */
    public function testGetRegistrationGroupElement()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("2", $isbn->getRegistrationGroupElement());
  }

    /**
     * @throws IsbnParsingException
     */
    public function testGetRegistrantElement()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("207", $isbn->getRegistrantElement());
  }

    /**
     * @throws IsbnParsingException
     */
    public function testGetPublicationElement()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("25804", $isbn->getPublicationElement());
  }

    /**
     * @throws IsbnParsingException
     */
    public function testGetRegistrationAgencyName()
  {
    $isbn = Isbn::parse("9782207258040");

    $this->assertEquals("French language", $isbn->getRegistrationAgencyName());
  }
}
