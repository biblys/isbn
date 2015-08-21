php-isbn-class
==============

This PHP class can be used to :  
* validate an ISBN code
* convert codes between ISBN-10, ISBN-13 and EAN (without hyphens) formats
* calculate the checksum digit
* show the registration agency (country or language)

Demo
----

A demo can be found here : http://labs.nokto.net/php-isbn-class/

Installation
------------

1. Include ISBN.class.php in your PHP script
2. Download range.xml from ISBN International Agency (https://www.isbn-international.org/range_file_generation)
3. Define constant ISBN_RANGES_FILE with the path to the range.xml file
4. Use other constant to customize error messages

Unit testing
------------

Run tests with PHPUnit :

	phpunit tests/

Usage
-----

	<?php
		require_once('ISBN.class.php');
		$isbn = new ISBN('9791091146098'); // creates an ISBN object from an EAN code
		
		// check if input is a valid ISBN code
		if ($isbn->isValid()) {
			echo $isbn->format('ISBN-13'); // print the code in ISBN-13 format
			echo $isbn->format('ISBN-10'); // print the code in ISBN-10 format
			echo $isbn->getChecksum(); // print the checksum digit
			echo $isbn->getAgency(); // print the registration agency
		} else {
			echo $isbn->getErrors(); // show validation errors
		}



Changelog
---------

1.1 (21/08/2015)
* Fixed ISBN-10 checksum character calculation (thanks to @thinkmobilede)
* Added phpunit tests
* Updated ISBN XML ranges file

1.0.1 (21/04/2014)
* EAN-13 checksum character calculation bug fix

1.0.0 (19/04/2014)
* First release
