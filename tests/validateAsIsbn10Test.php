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
use Biblys\Isbn\IsbnValidationException;
use PHPUnit\Framework\TestCase;

class validateAsIsbn10Test extends TestCase
{
    /**
     * @throws IsbnValidationException
     * @throws IsbnParsingException
     */
    public function testValidIsbn()
    {
        Isbn::validateAsIsbn10("3-464-60352-0");

        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws IsbnValidationException
     */
    public function testUnparsableIsbn()
    {
        $this->expectException("Biblys\Isbn\IsbnParsingException");
        $this->expectExceptionMessage("Invalid characters in the code");

        Isbn::validateAsIsbn10("3-ABC-60352-0");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithMisplacedHyphen()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("3-46460-352-0 is not a valid ISBN-10. Expected 3-464-60352-0.");

        Isbn::validateAsIsbn10("3-46460-352-0");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithoutHyphens()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("3464603520 is not a valid ISBN-10. Expected 3-464-60352-0.");

        Isbn::validateAsIsbn10("3464603520");
    }

    /**
     * @throws IsbnParsingException
     */
    public function testIsbnWithIncorrectChecksum()
    {
        $this->expectException("Biblys\Isbn\IsbnValidationException");
        $this->expectExceptionMessage("3-46460-352-X is not a valid ISBN-10. Expected 3-464-60352-0.");

        Isbn::validateAsIsbn10("3-46460-352-X");
    }
}
