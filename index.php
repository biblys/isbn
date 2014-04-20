<?php

	require_once 'ISBN.class.php';
	
	if (isset($_GET['code']))
	{
		$isbn = new ISBN($_GET['code']);
		if ($isbn->isValid())
		{
			$result = '
				<p>Validation : OK</p>
				<p>Result : '.$isbn->format($_GET['format']).'</p>
				<p>
					PHP Code :<br />
					<pre>
	$isbn = new ISBN(\''.$_GET['code'].'\');
	if ($isbn->isValid()) echo $isbn->format(\''.$_GET['format'].'\');
					</pre>
				</p>
			';
		}
		else
		{
			$result = '
				<p>Validation : Error</p>
				<p>Errors : '.$isbn->getErrors().'</p>
			';
		}
	}
	else
	{
		$_GET['code'] = '979-10-91146-09-8';
	}
	
?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>PHP : ISBN validation and conversion class</title>
	</head>
	<body>
		<h1>PHP : ISBN validation and conversion class</h1>
		<? if (isset($result)) echo $result; ?>
		<form>
			<fieldset>
				<legend>Conversion ISBN/EAN</legend>
				Code : <input type="text" name="code" value="<? echo $_GET["code"]; ?>"><br>
				Validate and convert into :
				<select name="format">
					<option>EAN</option>
					<option>ISBN-13</option>
					<option>ISBN-10</option>
				</select><br>
				<button type="submit">Submit</button>
			</fieldset>
		</form>
		
	<br />
	<a href="readme.md">Readme</a> | <a href="http://nokto.net/php-isbn-class/">More info (french)</a> | <a href="https://github.com/iwazaru/php-isbn-class">Source on Github</a><br />
	</body>
</html>