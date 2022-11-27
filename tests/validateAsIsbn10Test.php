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

use Biblys\Isbn\Exception\IsbnParsingException;
use Biblys\Isbn\Exception\IsbnValidationException;
use Biblys\Isbn\Isbn;
use PHPUnit\Framework\TestCase;

class testValidateAsIsbn10 extends TestCase
{
    public function testValidIsbn()
    {
        Isbn::validateAsIsbn10("3-464-60352-0");

        $this->expectNotToPerformAssertions("It should not throw");
    }

    public function testUnparsableIsbn()
    {
        $this->expectException(IsbnParsingException::class);
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn10("3-ABC-60352-0");
    }

    public function testIsbnWithMisplacedHyphen()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("3-46460-352-0 is not a valid ISBN-10. Expected 3-464-60352-0.");

        Isbn::validateAsIsbn10("3-46460-352-0");
    }

    public function testIsbnWithoutHyphens()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("3464603520 is not a valid ISBN-10. Expected 3-464-60352-0.");

        Isbn::validateAsIsbn10("3464603520");
    }

    public function testIsbnWithIncorrectCheckum()
    {
        $this->expectException(IsbnValidationException::class);
        $this->expectExceptionMessage("3-46460-352-X is not a valid ISBN-10. Expected 3-464-60352-0.");

        Isbn::validateAsIsbn10("3-46460-352-X");
    }
}
