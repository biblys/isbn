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


namespace Biblys\Isbn;

class ParsedIsbn
{
  // ISBN Users' Manual, International Edition, p. 11-12
  // https://www.kb.se/download/18.71dda82e160c04f1cc412bc/1531827912246/ISBN%20International%20Users%20Manual%20-%207th%20edition.pdf
  private $_gs1Element;
  private $_registrationGroupElement;
  private $_registrantElement;
  private $_publicationElement;
  private $_registrationAgencyName;

  public function __construct(array $elements)
  {
    $this->_gs1Element = $elements["gs1Element"];
    $this->_registrationGroupElement = $elements["registrationGroupElement"];
    $this->_registrantElement = $elements["registrantElement"];
    $this->_publicationElement = $elements["publicationElement"];
    $this->_registrationAgencyName = $elements["registrationAgencyName"];
  }

  public function getGs1Element(): string
  {
    return $this->_gs1Element;
  }

  public function getRegistrationGroupElement(): string
  {
    return $this->_registrationGroupElement;
  }

  public function getRegistrantElement(): string
  {
    return $this->_registrantElement;
  }

  public function getPublicationElement(): string
  {
    return $this->_publicationElement;
  }

  public function getRegistrationAgencyName(): string
  {
    return $this->_registrationAgencyName;
  }
}
