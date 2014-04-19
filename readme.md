php-isbn-class
==============

Created by Clément Bourgoin  
Contact : http://nokto.net/contact/

En français : http://nokto.net/php-isbn-class/

This PHP class can be used to :  
- validate an ISBN code
- convert codes between ISBN-10, ISBN-13 and EAN (without hyphens) formats
- calculate the checksum digit
- show the registration agency (country or language)

Demo
----

A demo can be found here : http://labs.nokto.net/php-isbn-class/

Installation
------------

- Include ISBN.class.php in your PHP script
- Download range.xml from ISBN International Agency (https://www.isbn-international.org/range_file_generation)
- Define constant ISBN_RANGES_FILE with the path to the range.xml file
- Use other constant to customize error messages

Usage
-----

	<?php
		require_once('ISBN.class.php');
		$isbn = new ISBN('9791091146098'); // creates an ISBN object from an EAN code
		if ($isbn->isValid()) // check if input is a valid ISBN code
		{
			echo $isbn->format('ISBN-13'); // print the code in ISBN-13 format
			echo $isbn->format('ISBN-10'); // print the code in ISBN-10 format
			echo $isbn->getChecksum(); // print the checksum digit
			echo $isbn->getAgency(); // print the registration agency
		}
		else
		{
			echo $isbn->getErrors(); // show validation errors
		}
	?>



Changelog
---------

1.0 (19/04/2014)
- first release