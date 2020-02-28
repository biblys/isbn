# biblys/isbn

[![Build Status](https://travis-ci.org/biblys/isbn.svg?branch=master)](https://travis-ci.org/biblys/isbn)

This package can be used to:

- validate an ISBN code
- convert codes between ISBN-10, ISBN-13 and EAN (without hyphens) formats
- calculate the checksum digit
- show the registration agency (country or language)

## Installation

Install with composer:

```console
composer require biblys/isbn:^2.1.4
```

## Usage

Use case: converting an EAN (9782843449499) to an ISBN-13 (978-2-84344-949-9).

```php
<?php

use Biblys\Isbn\Isbn as Isbn;
// require_once __DIR__.'/vendor/autoload.php';

$ean = '9782843449499';
$isbn = new Isbn($ean);

try {
    $isbn->validate();
    $isbn13 = $isbn->format("ISBN-13");
    echo "ISBN-13: $isbn13";
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
composer docker
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

## Changelog

[See Github releases](https://github.com/biblys/isbn/releases)
