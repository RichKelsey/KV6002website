<?php
include_once __DIR__ . "/logging.php";

define("CREDENTIALS_FILE", __DIR__ . "/credentials.php");

$isPostRequest  = ($_SERVER['REQUEST_METHOD'] === 'POST');
$isCalledDirectly = (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]));

if ($isPostRequest && $isCalledDirectly) {
	$data = json_decode(file_get_contents('php://input'), true);
	if (isset($data['query'])) {
		$query = $data['query'];
		$dbh = db_connect();
		$result = db_query($query, $dbh);
		echo $result;
	}
	else {
		log_error("Query is null.");
	}
}

function db_connect()
{
	if (file_exists(CREDENTIALS_FILE)) {
		include CREDENTIALS_FILE;
	}
	else {
		log_error("Credentials file not found.");;
		return null;
	}

	$credentialsMissing = (!isset($host) || !isset($dbname) || !isset($username) || !isset($password));
	if ($credentialsMissing) {
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

function db_query($query, $dbh){
	if (!isset($dbh)) {
		log_error("Database handle is null.");
		return null;
	}

	if (!isset($query)) {
		log_error("Query is null.");
		return null;
	}

	try {
		$statement = $dbh->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$jsonResult = json_encode($result); //encode as json

		return $jsonResult;
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
