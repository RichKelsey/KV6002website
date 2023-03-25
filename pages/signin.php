<!doctype html>
<html lang="en">
	<head>
		<!-- temp css -->
		<link rel="stylesheet" href="../css/admin.css"> 
		<link rel="stylesheet" href="../css/newsfeed.css"> 
		<meta charset="utf-8">
		<title> Administrator Dashboard </title>
	</head>

<?php
// Start the session
session_start();


echo '<div class="login">';
    echo '<form method="post">';
        echo '<p>Username: <input type="text" name="username" required><br></p>';
        echo '<input type="submit" value="Next" class="dropbtn">';
    echo '</form>';
echo '</div>';
if(isset($_POST['GroupID']))
{
    $GroupID = $_POST['GroupID'];
    $_SESSION['GroupID'] = $GroupID;
}

// Check if the form is submitted
if(isset($_POST['username'])) {
    // Get the username from the form
    $username = $_POST['username'];
    $_SESSION['username'] = $username;
    header('Location: userprofile.php');
} 

?>
