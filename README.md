# biblys/isbn

[![tests](https://github.com/biblys/isbn/actions/workflows/tests.yml/badge.svg)](https://github.com/biblys/isbn/actions/workflows/tests.yml)

This package can be used to:

- validate an ISBN code
- convert codes between ISBN-10, ISBN-13 and EAN (without hyphens) formats
- format an ISBN as a GTIN-14 string (for use in packing and shipping)
- calculate the checksum digit
- show the registration agency (country or language)

[CHANGELOG](https://github.com/biblys/isbn/releases)

## Installation

Install with composer:

```console
composer require biblys/isbn:^2.2.0
```

## Usage

Use case: converting an EAN (9782843449499) to an ISBN-13 (978-2-84344-949-9).

```php
<?php

$ean = "9782843449499";
$isbn = new Biblys\Isbn\Isbn($ean);

try {
    $isbn->validate();
    $isbn13 = $isbn->format("ISBN-13");
    echo "ISBN-13: $isbn13";
} catch(Exception $e) {
    echo "An error occured while parsing $ean: ".$e->getMessage();
}
```

Use case: outputting an EAN (9782843449499) as a GTIN-14-formatted string with the prefix 1.

```php
<?php

$ean = "9782843449499";
$isbn = new Biblys\Isbn\Isbn($ean);

try {
    $isbn->validate();
    $gtin14 = $isbn->format("GTIN-14", 1);
    echo "GTIN-14: $gtin14";
} catch(Exception $e) {
    echo "An error occured while parsing $ean: ".$e->getMessage();
}
```

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
