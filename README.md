# biblys/isbn 3.0

[![tests](https://github.com/biblys/isbn/actions/workflows/tests.yml/badge.svg)](https://github.com/biblys/isbn/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/biblys/isbn/v/stable)](https://packagist.org/packages/biblys/isbn)
[![Total Downloads](https://poser.pugx.org/biblys/isbn/downloads)](https://packagist.org/packages/biblys/isbn)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](http://opensource.org/licenses/MIT)
[![Gitpod ready-to-code](https://img.shields.io/badge/Gitpod-ready--to--code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/biblys/isbn)

biblys/isbn can be used to:

- [validate](#validate) a string against the ISBN-10, ISBN-13 and EAN-13 formats
- [convert](#convert) an ISBN to ISBN-10, ISBN-13, EAN-13, GTIN-14 and ISBN-A/DOI formats
- [parse](#parse) an ISBN to extract registration agency, publisher code, publication code, checksum, etc.

[CHANGELOG](https://github.com/biblys/isbn/releases)

## Installation

### 
- Requirements: PHP 7.2 or above

Install with composer:

```console
composer require biblys/isbn:~3.0
```

## Usage

### Convert

Use case: converting an EAN (9782843449499) to an ISBN-13 (978-2-84344-949-9).

```php
<?php

use Biblys\Isbn\Isbn;

try {
    $input = "9782843449499";
    $isbn13 = Isbn::convertToIsbn13($input);
    echo "ISBN-13: $isbn13"; // Prints ISBN-13: 978-2-84344-949-9
} catch(Exception $e) {
    echo "An error occurred while attempting to format ISBN $input: ".$e->getMessage();
}
```

All formatting methods:

- `Isbn::convertToIsbn10`
- `Isbn::convertToIsbn13`
- `Isbn::convertToEan13`
- `Isbn::convertToGtin14`
- `Isbn::convertToIsbnA`

### Validate

Use case: validating an incorrectly formed ISBN-13 (978-2-843-44949-9, should
be 978-2-84344-949-9).

```php
<?php

use Biblys\Isbn\Isbn;

try {
    $input = "978-2-843-44949-9";
    Isbn::validateAsIsbn13($input);
    echo "ISBN $input is valid!";
} catch(Exception $e) { // Will throw because third hyphen is misplaced
    echo "ISBN $input is invalid: ".$e->getMessage();
}
```

All validating methods:

- `Isbn::validateAsIsbn10`
- `Isbn::validateAsIsbn13`
- `Isbn::validateAsEan13`
- `Isbn::isParsable`

[Learn more about validating ISBNs](https://github.com/biblys/isbn/wiki/Validating-ISBNs-using-the-new-public-API)

### Parse

Use case: extracting the publisher code from an ISBN.

```php
<?php
use Biblys\Isbn\Isbn;

$input = "9782956420132";
$isbn = Isbn::parse($input);
echo $isbn->getRegistrantElement(); // Prints "9564201"

```

`Isbn::parse` returns a `ParsedIsbn` object implementing the following methods:
- `ParsedIsbn->getGs1Element`:: EAN product code
- `ParsedIsbn->getRegistrationGroupElement`: Country, geographical region or language aera code
- `ParsedIsbn->getRegistrantElement`: Publisher (or imprint within a group) code
- `ParsedIsbn->getPublicationElement`: Publication code
- `ParsedIsbn->getCheckDigit`: Checksum used for validation


## Development

### Using Gitpod

You can start a dev environment by clicking
[![Gitpod ready-to-code](https://img.shields.io/badge/Gitpod-ready--to--code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/biblys/isbn)
and start hacking in your browser right away!

### Locally

If you'd rather set up a local dev environment, you'll need:

- PHP 7.x
- Composer
- (Optional) Docker to run tests and debug against different version of PHP

Clone this repository and run `composer install` to get started!

## Tests

Run tests with PHPUnit:

```console
composer install
composer test
```

Run tests in a docker container:

```console
composer docker:test
```

Run tests in a docker container using a specific PHP version:

```console
PHP_VERSION=7.1 composer docker:test
```

## ISBN ranges update

New ISBN ranges may be added from time to time by the
[International ISBN Agency](https://www.isbn-international.org/). Whenever it
happens, this library must be updated. If a range update is necessary, please
open an issue on GitHub.
You can also open a pull request after updating the ranges your self with the
following commands:

```console
composer install
composer run update-ranges
```

Or using a docker container:

```console
composer docker:update-ranges
```

## Changelog

[See GitHub releases](https://github.com/biblys/isbn/releases)
