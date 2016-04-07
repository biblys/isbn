# biblys/isbn

[![Build Status](https://travis-ci.org/biblys/isbn.svg?branch=master)](https://travis-ci.org/biblys/isbn)

This package can be used to :  
* validate an ISBN code
* convert codes between ISBN-10, ISBN-13 and EAN (without hyphens) formats
* calculate the checksum digit
* show the registration agency (country or language)


## Installation

Install with composer:

`composer require biblys/isbn:~2.0`


## Test

Run tests with PHPUnit:

* `composer install`
* `phpunit tests/`


## Usage

```php
<?php

use Biblys\Isbn\Isbn as Isbn;

// Create an ISBN object from an EAN code
$isbn = new Isbn('9791091146098'); 

// Check if input is a valid ISBN code
if ($isbn->isValid()) {
  
  // Print the code in ISBN-13 format
	echo $isbn->format('ISBN-13');
  
  // Print the code in ISBN-10 format
	echo $isbn->format('ISBN-10');
  
  // Print the checksum digit
	echo $isbn->getChecksum();
  
  // Print the registration agency
	echo $isbn->getAgency();
  
} else {
  
  // Show validation errors
	echo $isbn->getErrors();
}
```


## Changelog

2.0.0 (2016-03-01)
* Revamped library as a Composer package

1.1.0 (2015-08-21)
* Fixed ISBN-10 checksum character calculation (thanks to @thinkmobilede)
* Added phpunit tests
* Updated ISBN XML ranges file

1.0.1 (2014-04-21)
* EAN-13 checksum character calculation bug fix

1.0.0 (2014-04-19)
* First release
