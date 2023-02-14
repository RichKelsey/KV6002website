<?php
include_once __DIR__ . "/logging.php";

define("CREDENTIALS_FILE",  __DIR__ . "/credentials.php");


function db_connect()
{
	if (file_exists(CREDENTIALS_FILE)) { 
		include CREDENTIALS_FILE;
	}
	else {
		log_error("Credentials file not found.");;
		return null;
	}

	if (
		!isset($host) ||
		!isset($dbname) ||
		!isset($username) ||
		!isset($password)
	) {
		log_error("Credentials are incomplete.<br>");
		return null;
	}

	try {
		$dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $dbh;
	} catch (PDOException $e) {
		log_error("Error!: " . $e->getMessage() . "<br>");
		return null;
	}
}

function db_getTableNames($dbh)
{
	if (!isset($dbh)) return;

	// Query to get all the tables
	$stmt = $dbh->query("SHOW TABLES");

	// Fetch the results
	$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

	return $tables;
}

?>
