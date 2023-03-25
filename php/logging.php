<?php
//define("DEBUG", null);
define("LOG_FILE", __DIR__ . "/../php.log");
define("PREFIX_ERROR", "Error: ");

function log_print($string)
{
	if(defined("DEBUG") && !defined("NO_ECHO")) {
		echo $string . "<br>";
		return;
	}

	$handle = fopen(LOG_FILE, "a");
	if (!$handle) { 
		echo "Unable to open file.<br>";
		return;
	}
	
	if (!fwrite($handle, $string . "\n")) {
		echo "Unable to write to file.<br>";
	}
	fclose($handle);
}

function log_error($string)
{
	log_print(PREFIX_ERROR . $string);
}
?>
