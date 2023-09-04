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

  public function testInvalidCharacters()
  {
    // given
    $stringWithInvalidCharacters = "978-1-2345-ABCD-0";

    // then
    $this->expectException(InvalidCharactersException::class);
    $this->expectExceptionMessage("Cannot parse string with invalid characters: ABCD");

    // when
    Parser::parse($stringWithInvalidCharacters);
  }

}
