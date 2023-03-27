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
		if (!isset($data["data"])) goto nodata;

		$stats         = $data["data"]["postsStats"];
		$participantID = $data["data"]["participantID"];

		db_uploadAnalytics($stats, $participantID);
		return;
	case "getParticipantGroup": 
		if (!isset($data["data"])) goto nodata;

		db_getParticipantGroup($data["data"]["participantID"]);
		return;
	default:
		log_error("db_analytics.php: invalid action");
		return;
	}

	nodata:
	log_error("No data uploaded.");
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
	if ($statement->execute([
		':AnalyticsID'   => $analyticsID,
		':HasLiked'      => (int)$postStats["hasLiked"],
		':RetentionTime' => $postStats["retentionTime"],
		':MaxTimeViewed' => $postStats["maxTimeViewed"],
		':TimesViewed'   => $postStats["timesViewed"],
		':Comment'       => $postStats["comment"]
	])) log_print("Updated AnalyticsID: " . $analyticsID);
	else log_error("Error updating AnalyticsID: " . $analyticsID);

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
	if ($statement->execute([
		':PostID'        => $postID,
		':ParticipantID' => $participantID,
		':HasLiked'      => (int)$postStats["hasLiked"],
		':RetentionTime' => $postStats["retentionTime"],
		':MaxTimeViewed' => $postStats["maxTimeViewed"],
		':TimesViewed'   => $postStats["timesViewed"],
		':Comment'       => $postStats["comment"]
	])) log_print("Inserted new analytics");
	else log_error("Error inserting analytics");

	$statement = null;
}

function db_getParticipantGroup($participantID)
{
	$dbh = db_connect();
	if (!$dbh) {
		log_error("db_getParticipantGroup(): Can't connect to database");
		return;
	}

	$sql = "SELECT `GroupID` FROM `Participant` WHERE ParticipantID = :ParticipantID";

	$statement = $dbh->prepare($sql);
	$statement->execute([
		':ParticipantID' => $participantID
	]);

	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if (!$result) goto fail;
	
	$groupID = $result[0]["GroupID"];

	$sql = "SELECT * FROM `Group` WHERE GroupID = :GroupID";

	$statement = $dbh->prepare($sql);
	$statement->execute([
		'GroupID' => $groupID
	]);

	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if (!$result) goto fail;

	echo json_encode($result[0]);
	$statement = null;
	return;

	fail:
	log_error("db_getParticipantGroup(): Error Getting participantID or group");
	echo json_encode(null);
}
?>
