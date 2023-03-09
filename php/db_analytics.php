<?php
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
		if (!isset($data["data"])) {
			log_error("No data uploaded.");
			break;
		}
		db_uploadAnalytics($data["data"], 1);
		break;
	default:
		log_error("db_analytics.php: invalid action");
	}
}

function db_uploadAnalytics($postsStats, $participantID)
{
	$dbh = db_connect();
	if (!$dbh) {
		log_error("db_uploadAnalytics(): Can't connect to database");
		return;
	}

	foreach ($postsStats as $id => $postStats) {
		log_print ("Post ID: " . $id . ":");
		db_uploadPostStats($dbh, $id, $participantID, $postStats);
	}

	$dbh = null;
}

function db_uploadPostStats($dbh, $postID, $participantID, $postStats)
{
	$sql = "SELECT AnalyticsID FROM `Analytics` WHERE 
		`PostID` = :PostID AND
		`ParticipantID` = :ParticipantID";
	$statement = $dbh->prepare($sql); 

	$statement->execute([
		':PostID' => $postID, 
		':ParticipantID' => $participantID, 
	]);

	if (!$statement) {
		log_error("db_uploadPostStats: Error querying for analytics ID.");
		return;
	}

	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$statement = null;

	if (!$result) {
		db_insertPostStat($dbh, $postID, $participantID, $postStats);
		return;
	}

	$analyticsID = $result[0]["AnalyticsID"];
	db_updatePostStat($dbh, $analyticsID, $postStats);
}

function db_updatePostStat($dbh, $analyticsID, $postStats)
{
	$sql = "UPDATE `Analytics`
		SET
			`HasLiked`      = :HasLiked,
			`RetentionTime` = :RetentionTime,
			`MaxTimeViewed` = :MaxTimeViewed,
			`TimesViewed`   = :TimesViewed,
			`Comment`       = :Comment
		WHERE 
			`AnalyticsID` = :AnalyticsID";

	$statement = $dbh->prepare($sql);
	$statement->execute([
		':AnalyticsID'   => $analyticsID,
		':HasLiked'      => (int)$postStats["hasLiked"],
		':RetentionTime' => $postStats["retentionTime"],
		':MaxTimeViewed' => $postStats["maxTimeViewed"],
		':TimesViewed'   => $postStats["timesViewed"],
		':Comment'       => $postStats["comment"]
	]);

	log_print("Updated AnalyticsID: " . $analyticsID);

	$statement = null;
}

function db_insertPostStat($dbh, $postID, $participantID, $postStats)
{
	$sql = "INSERT INTO 
		`Analytics`(
			`PostID`,
			`ParticipantID`,
			`HasLiked`,
			`RetentionTime`,
			`MaxTimeViewed`,
			`TimesViewed`,
			`Comment`
		)
		VALUES(
			:PostID,
			:ParticipantID,
			:HasLiked,
			:RetentionTime,
			:MaxTimeViewed,
			:TimesViewed,
			:Comment
		)";

	$statement = $dbh->prepare($sql);
	$statement->execute([
		':PostID'        => $postID,
		':ParticipantID' => $participantID,
		':HasLiked'      => (int)$postStats["hasLiked"],
		':RetentionTime' => $postStats["retentionTime"],
		':MaxTimeViewed' => $postStats["maxTimeViewed"],
		':TimesViewed'   => $postStats["timesViewed"],
		':Comment'       => $postStats["comment"]
	]);

	log_print("Inserted new analytics");

	$statement = null;
}
?>
