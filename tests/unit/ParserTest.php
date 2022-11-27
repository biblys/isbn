<?php

namespace Biblys\Isbn;

use Biblys\Isbn\Exception\EmptyInputException;
use Biblys\Isbn\Exception\InvalidCharactersException;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{

  public function testEmptyInput()
  {
    // given
    $emptyValue = "";

    // then
    $this->expectException(EmptyInputException::class);
    $this->expectExceptionMessage("Cannot parse empty input");

    // when
    Parser::parse($emptyValue);
  }

}
