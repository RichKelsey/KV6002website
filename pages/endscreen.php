<?php
session_start(); // start the session


// if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}
if(isset($_SESSION["participantID"])) {
    $ParticipantID = $_SESSION["participantID"];
}
else
{
    header('Location: signin.php');
    exit();
}
// check if the delete button was clicked
if (isset($_POST['delete_data'])) {
    // check if the confirmation checkbox is ticked
    if (isset($_POST['confirm_delete'])) {
        // the user confirmed that they want to delete their data
        // delete the user's data from the database here



        //connect to the database
        require_once("../php/db_connection.php");
        $db = db_connect();

        //check if connection successful
        if (!$db) die("Can't connect to database");

        $sql = "DELETE FROM Participant WHERE ParticipantID = $ParticipantID";
        db_query($sql, $db);
        // redirect the user to a confirmation page
        //header('Location: delete_confirmation.php');
        //exit();
    } 
}


if (isset($_POST['confirm_delete'])) {
    $confirmDelete = $_POST['confirm_delete'];
}



// if the user is logged in, show the endscreen with the delete button and confirmation form
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/admin.css"> 
    <link rel="stylesheet" href="../css/newsfeed.css"> 
    <meta charset="utf-8">
    <title>Endscreen</title>
</head>
<body>
    <h1>Thank you for contributing to our research!</h1>
    <p>Your participation is very much appreciated.</p>
    <?php
            //connect to the database
            require_once("../php/db_connection.php");
            $db = db_connect();

            //check if connection successful
            if (!$db) die("Can't connect to database");

            $sql = "SELECT URL FROM URL";
            $results = json_decode(db_query($sql, $db), true);
            $url = $results[0]['URL'];

            echo "<p>Please take a few more minutes of your time to complete this <a href='$url' class='link'> survey</a></p> "
    ?>
    <form action="endscreen.php" method="post">
        <br><br><br><br><br><br>
        <input type="submit" name="delete_data" value="Delete my data" class="dropbtn">
    </form>
    <?php if (isset($_POST['delete_data'])) { ?>
        <form action="endscreen.php" method="post">
            <p>Are you sure you want to delete your data?</p>
            <input type="checkbox" name="confirm_delete" value="1"> Yes, I'm sure.<br>
            <input type="submit" value="Confirm">
            <button onclick="window.history.back();return false;">Cancel</button>
        </form>
    <?php } ?>
</body>
</html>

