<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Bourgoin
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

namespace Biblys\Isbn;

class Isbn
{
	// Error messages (for localization)
	const ERROR_EMPTY = 'No code provided',
		  ERROR_INVALID_CHARACTERS = 'Invalid characters in the code',
		  ERROR_INVALID_LENGTH = 'Code is too short or too long',
		  ERROR_INVALID_COUNTRY = 'Country code unknown';

	private $_product, // GS1 Product Code (978 or 979 for books)
			$_country, // Registrant group (country) code
			$_publisher, // Registrant (publisher) Code
			$_publication, // Publication code
			$_checksum, // Checksum character
			$_input, // Input code
			$_isValid = true, // Is the code a valid ISBN
			$_errors = array(), // Why is the code invalid
			$_format = 'EAN', // Output format
			$_prefixes, // XML ranges file prefixes
			$_groups, // XML ranges file groups
			$_agency; // ISBN Agency

	public function __construct($code = NULL)
	{
		if (!empty($code))
		{
			$this->_input = $code;

			// Load ISBN ranges from XML file
			$this->loadRanges();

			// Remove hyphens and check characters
			$code = $this->removeHyphens($code);

			// Remove checksum and check length
			$code = $this->removeChecksum($code);

			if ($this->isValid())
			{
				// Remove (and set) product code
				$code = $this->removeProductCode($code);

				// Remove (and save) country code
				$code = $this->removeCountryCode($code);

				// Remove (and save) publisher code
				$this->removePublisherCode($code);
			}
		}
		else
		{
			$this->addError(self::ERROR_EMPTY);
			$this->setValid(false);
		}

	}

	/* Check methods */

	/**
	 * Check if ISBN is valid
	 */
	public function isValid()
	{
		return (bool) $this->_isValid;
	}

	/* Format methods */

	/**
	 * Format an ISBN according to specified format
	 * @param string $format (ISBN-10, ISBN-13, EAN)
	 */
	public function format($format = 'EAN')
	{
		$this->calculateChecksum($format);

		$A = $this->getProduct();
		$B = $this->getCountry();
		$C = $this->getPublisher();
		$D = $this->getPublication();
		$E = $this->getChecksum();

		if($format == 'ISBN-10')
		{
			return $B.'-'.$C.'-'.$D.'-'.$E;
		}
		elseif($format == 'ISBN-13' || $format == 'ISBN')
		{
			return $A.'-'.$B.'-'.$C.'-'.$D.'-'.$E;
		}
		else
		{
			return $A.$B.$C.$D.$E;
		}
	}

	// Private methods

	/**
	 * Set ISBN Validity
	 */
	private function setValid($isValid)
	{
		$this->_isValid = (bool) $isValid;
	}

	/**
	 * Add to error log
	 */
	private function addError($error)
	{
		$this->_errors[] = (string) $error;
	}

	/**
	 * Delete '-', '_' and ' '
	 */
	private function removeHyphens($code)
	{
		// Remove Hyphens and others characters
		$replacements = array('-','_',' ');
		$code = str_replace($replacements,'',$code);

		// Check for unwanted characters
		if (!is_numeric($code))
		{
			$this->setValid(false);
			$this->addError(self::ERROR_INVALID_CHARACTERS);
		}

		return $code;
	}

	/**
	 * Remove checksum character if present
	 */
	private function removeChecksum($code)
	{
		$length = strlen($code);
		if ($length == 13 || $length == 10)
		{
			$code = substr_replace($code,"",-1);
			return $code;
		}
		elseif ($length == 12 || $length == 9)
		{
			return $code;
		}
		else
		{
			$this->setValid(false);
			$this->addError(self::ERROR_INVALID_LENGTH);
			return $code;
		}
	}

	/**
	 * Remove first three characters if 978 or 979 and save Product Code
	 */
	private function removeProductCode($code)
	{
		$first3 = substr($code,0,3);
		if ($first3 == 978 || $first3 == 979) {
			$this->setProduct($first3);
			$code = substr($code,3);
		} else {
			$this->setProduct(978);
		}
		return $code;
	}

	/**
	 * Remove and save Country Code
	 */
	private function removeCountryCode($code)
	{

		// Get the seven first digits
		$first7 = substr($code,0,7);

		// Select the right set of rules according to the product code
		foreach ($this->_prefixes as $p)
		{
			if ($p['Prefix'] == $this->getProduct())
			{
				$rules = $p['Rules']['Rule'];
				break;
			}
		}

		// Select the right rule
		foreach ($rules as $r)
		{
			$ra = explode('-',$r['Range']);
			if ($first7 >= $ra[0] && $first7 <= $ra[1])
			{
				$length = $r['Length'];
				break;
			}
		}

		$this->setCountry(substr($code,0,$length));
		$code = substr($code,$length);

		return $code;
	}

	/**
	 * Remove and save Publisher Code and Publication Code
	 */
	private function removePublisherCode($code)
	{

		// Get the seven first digits
		$first7 = substr($code,0,7);

		// Select the right set of rules according to the agency (product + country code)
		foreach ($this->_groups as $g)
		{
			if ($g['Prefix'] == $this->getProduct().'-'.$this->getCountry())
			{
				$rules = $g['Rules']['Rule'];
				$this->setAgency($g['Agency']);
				break;
			}
		}

		// Select the right rule
		foreach ($rules as $r)
		{
			$ra = explode('-',$r['Range']);
			if ($first7 >= $ra[0] && $first7 <= $ra[1])
			{
				$length = $r['Length'];
				break;
			}
		}

		$this->setPublisher(substr($code,0,$length));
		$this->setPublication(substr($code,$length));

	}

	/**
	 * Calculate checksum character
	 */
	private function calculateChecksum($format = 'EAN')
	{
		$sum = null;

		if ($format == 'ISBN-10')
		{
			$code = $this->getCountry().$this->getPublisher().$this->getPublication();
			$c = str_split($code);
			$sum = (11 - (($c[0] * 10) + ($c[1] * 9) + ($c[2] * 8) + ($c[3] * 7) + ($c[4] * 6) + ($c[5] * 5) + ($c[6] * 4) + ($c[7] * 3) + ($c[8] * 2)) % 11) % 11;
			if($sum == 10) $sum = 'X';
		}
		else
		{
			$code = $this->getProduct().$this->getCountry().$this->getPublisher().$this->getPublication();
			$c = str_split($code);
			$sum = (($c[1] + $c[3] + $c[5] + $c[7] + $c[9] + $c[11]) * 3) + ($c[0] + $c[2] + $c[4] + $c[6] + $c[8] + $c[10]);
			$sum = (10 - ($sum % 10)) % 10;
		}

		$this->setChecksum($sum);

	}


	/* SETTERS */

	private function loadRanges()
	{
		$this->_prefixes = Ranges::getPrefixes();
		$this->_groups = Ranges::getGroups();
	}

	private function setProduct($product)
	{
		$this->_product = $product;
	}

	private function setCountry($country)
	{
		$this->_country = $country;
	}

	private function setPublisher($publisher)
	{
		$this->_publisher = $publisher;
	}

	private function setPublication($publication)
	{
		$this->_publication = $publication;
	}

	private function setChecksum($checksum)
	{
		$this->_checksum = $checksum;
	}

	private function setAgency($agency)
	{
		$this->_agency = $agency;
	}

	/* GETTERS */

	public function getProduct()
	{
		return $this->_product;
	}

	public function getCountry()
	{
		return $this->_country;
	}

	public function getPublisher()
	{
		return $this->_publisher;
	}

	public function getPublication()
	{
		return $this->_publication;
	}

	public function getChecksum()
	{
		return $this->_checksum;
	}

	public function getAgency()
	{
		return $this->_agency;
	}

	public function getErrors()
	{
		$errors = '['.$this->_input.']';
		foreach ($this->_errors as $e)
		{
			$errors .= ' '.$e;
		}
		return $errors;
	}
}
