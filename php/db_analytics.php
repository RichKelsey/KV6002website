<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once __DIR__ . "/db_connection.php";

$isPostRequest  = ($_SERVER['REQUEST_METHOD'] === 'POST');
$isCalledDirectly = (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]));

if ($isPostRequest && $isCalledDirectly) {
	handleAction();
}

function handleaction()
{
	$data = json_decode(file_get_contents('php://input'), true);
	if (!isset($data["action"])) return;

	switch ($data["action"]) {
	case "upload": 
		db_uploadAnalytics();
		break;
	case "getComments": 
		db_getComments();
		break;
	default:
		log_error("db_analytics.php: invalid action");
	}
}

function db_uploadAnalytics()
{
	$dbh = db_connect();
	if (!$dbh) {
		log_error("db_uploadAnalytics(): Can't connect to database");
		return;
	}

	// Database actions here

	echo json_encode("db_uploadAnalytics stub was called.");
	$dbh = null;
}

function db_getComments()
{
	$dbh = db_connect();
	if (!$dbh) {
		log_error("db_getComments(): Can't connect to database");
		return;
	}

	echo json_encode("db_getComments stub was called.");
	$dbh = null;
}
?>
