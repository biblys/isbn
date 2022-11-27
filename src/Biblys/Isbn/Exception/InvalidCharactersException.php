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

namespace Biblys\Isbn\Exception;

class InvalidCharactersException extends IsbnParsingException
{
  public function __construct($invalidCharacters)
  {
    parent::__construct("Cannot parse string with invalid characters: $invalidCharacters");
  }
}