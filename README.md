# biblys/isbn

[![tests](https://github.com/biblys/isbn/actions/workflows/tests.yml/badge.svg)](https://github.com/biblys/isbn/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/biblys/isbn/v/stable)](https://packagist.org/packages/biblys/isbn)
[![Total Downloads](https://poser.pugx.org/biblys/isbn/downloads)](https://packagist.org/packages/biblys/isbn)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](http://opensource.org/licenses/MIT)

biblys/isbn can be used to:

- validate a string against the ISBN-10, ISBN-13 and EAN-13 formats
- convert an ISBN to ISBN-10, ISBN-13, EAN-13 and GTIN-14 formats
- parse an ISBN and extract registration agency, publisher code, publication code, checksum, etc.

[CHANGELOG](https://github.com/biblys/isbn/releases)

## Installation

Install with composer:

```console
composer require biblys/isbn:^2.3.0
```

## Usage

### Formatting

Use case: converting an EAN (9782843449499) to an ISBN-13 (978-2-84344-949-9).

```php
<?php

use Biblys\Isbn\Isbn;

try {
    $input = "9782843449499";
    $isbn13 = Isbn::convertToIsbn13($input);
    echo "ISBN-13: $isbn13"; // Prints ISBN-13: 978-2-84344-949-9
} catch(Exception $e) {
    echo "An error occured while attempting to format ISBN $input: ".$e->getMessage();
}
```

All formating methods:

- `Isbn::convertToIsbn10`
- `Isbn::convertToIsbn13`
- `Isbn::convertToEan13`
- `Isbn::convertToGtin14`

### Validating

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
- `Isbn::validateAsIbsn13`
- `Isbn::validateAsEan13`

## Test

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
open an issue on Github.
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

[See Github releases](https://github.com/biblys/isbn/releases)
