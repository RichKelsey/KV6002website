<?php
define("AVATAR_DIRECTORY",  "../img/avatars/");
// Make sure to not echo log messages
define("NO_ECHO", null);

include_once __DIR__ . "/db_connection.php";

//
// Main logic
//
$dbh = db_connect();
if (!$dbh) {
	$str = "createParticipant.php: Can't connect to database";
	log_error($str);
	die($str);
}

// Get user data from the form
$groupID    = defaultNull('groupID');
$username   = defaultNull('username');
$profilePic = defaultNull('avatar');
$bio        = defaultNull('bio');

$details = [$groupID, $username, $profilePic, $bio];
foreach($details as $detail) {
	if ($detail === null) {
		$str = "createParticipant.php: Not enough details provided to create participant";
		log_error($str);
		die($str);
	}
}
// convert pic to relative path
$profilePic = AVATAR_DIRECTORY . $profilePic[0];

createNewParticipant($dbh, $groupID, $username, $profilePic, $bio);
// return new participant ID
echo getMaxParticipantID($dbh);

$dbh = null;
//
// Helper functions
//
function defaultNull($key)
{
	return isset($_POST[$key])? $_POST[$key] : null;
}

function createNewParticipant($dbh, $groupID, $username, $profilePic, $bio)
{
	$sql = "INSERT INTO 
		`Participant`(
			GroupID,
			Name,
			ProfilePic,
			Bio
		) 
		VALUES (
			:GroupID,
			:Name,
			:ProfilePic,
			:Bio
		)";
	
	$statement = $dbh->prepare($sql);
	if ($statement->execute([
		':GroupID'    => $groupID,
		':Name'       => $username,
		':ProfilePic' => $profilePic,
		':Bio'        => $bio,
	])) log_print("Inserted new participant");
	else log_error("Error inserting participant");
}

// Get the maximum ID from the Participant table
function getMaxParticipantID($dbh)
{
	$sql = "SELECT MAX(ParticipantID) as max_id FROM `Participant`";
	$result = db_query($sql, $dbh);

	$json_decoded = json_decode($result, true);
	return $json_decoded[0]["max_id"];
}
?>
