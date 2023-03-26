<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>User's sign up</title>
	<link rel="stylesheet" href="../css/admin.css"> 
	<link rel="stylesheet" href="../css/newsfeed.css"> 
</head>
<body >

<?php
// Set to 1 to show GroupID in the form
define("SHOW_GROUP", 0);

session_start();
$username = isset($_SESSION['username'])? $_SESSION['username'] : null;
$groupID  = isset($_SESSION['GroupID'])? $_SESSION['GroupID'] : null;

if($username === null || $groupID === null)
{
	header('Location: signin.php');
}
?>

<div style="margin-left: 20%;">
	<h1>Create Profile</h1>
	
	<form>
		<div>
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" required readonly
				style="background: transparent; color: lightgrey; border: none" 
				value="<?php echo $username; ?>"
			>
			<label for="groupID" style="<?php echo SHOW_GROUP? "" : "display: none;"; ?>">GroupID:</label>
			<input type="text" id="groupID" name="groupID" required readonly
			style="<?php echo SHOW_GROUP? "" : "display: none;"; ?> background: transparent; color: lightgrey; border: none" 
				value="<?php echo $groupID; ?>"
			>
		</div>
		<div>
			<label for="bio">Bio:</label><br>
			<textarea class="text" id="bio" name="bio" required></textarea>
		</div>

		<?php outputAvatarSelection(); ?>

		<button type="submit">Submit</button>
	</form>

</div>

<script>
const redirectLocation = "newsfeed.php";
const form = document.forms[0];
form.onsubmit = function (e)
{
	e.preventDefault();
	if (!validateForm()) return;

	const data = new FormData(form);

	fetch ("../php/createParticipant.php", {
		method: 'POST',
		body: data
	}).then((response) => {
		return response.text();
	}).then((text) => {
		const id = parseInt(text);

		sessionStorage.setItem("participantID", id);
		window.location.replace(redirectLocation);
	});
}
//Allow only one checkbox to be selected
function selectOnlyOne(checkbox) 
{
	var checkboxes = document.getElementsByName('avatar[]');
	checkboxes.forEach((item) => 
	{
		if (item !== checkbox) item.checked = false;
	});
}

//Check if an avatar has been selected
function validateForm()
{
	const username = document.getElementById("username").value;
	const bio = document.getElementById("bio").value;

	const fieldsEmpty = (username.length == 0 || bio.length == 0);
	if (fieldsEmpty) alert("Please enter all fields.");

	var checkboxes = document.getElementsByName("avatar[]");
	var checked = false;
	for (var i = 0; i < checkboxes.length; i++)
	{
		if (checkboxes[i].checked) 
		{
			checked = true;
			break;
		}
	}
	if (!checked)
	{
		alert("Please select an Avatar to continue.");
		return false;
	}
	return true;
}
</script>
</body>
</html> 

<?php
function outputAvatarSelection()
{
	$avatarDirectory="../img/avatars/";

	echo"<br><label for='avatar[]'>Select an avatar:<br><br>";
	echo"<div class='avatars'>";
	//display all available avatars
	$count = 0;
	foreach (scandir($avatarDirectory) as $file) 
	{
		if ($file === '.' || $file === '..') continue;

		echo"<div class='avatarcontainer'>";
		echo"<label for='avatar'> <img src='../img/avatars/$file'/> </label>";

		//to select the first avatar by default
		$checked = "";
		if($count == 10) $checked = "checked";
		
		echo"<input type='checkbox' id='avatar' name='avatar[]' value='{$file}' onclick='selectOnlyOne(this)' $checked>";
		echo"</div>";

		$count++;
	}
	echo"</label><br><br>";
	echo"</div>";
}
?>
