<?php
session_start(); // start the session
// check if the delete button was clicked
if (isset($_POST['delete_data'])) {
    // check if the confirmation checkbox is ticked
    if (isset($_POST['confirm_delete'])) {
        // the user confirmed that they want to delete their data
        // delete the user's data from the database here

        // redirect the user to a confirmation page
        header('Location: delete_confirmation.php');
        exit();
    } else {
        // the user didn't confirm that they want to delete their data
        echo "You must confirm that you want to delete your data.";
    }
}

// if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
if (isset($_POST['confirm_delete'])) {
    $confirmDelete = $_POST['confirm_delete'];
}

if (isset($_POST['delete_reason'])) {
    $deleteReason = $_POST['delete_reason'];
}

// if the user is logged in, show the endscreen with the delete button and confirmation form
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/endscreen.css">
    <title>Endscreen</title>
</head>
<body>
    <h1>Thank you for contributing to our research!</h1>
    <p>Your participation is very much appreciated.</p>
    <form action="endscreen.php" method="post">
        <input type="submit" name="delete_data" value="Delete my data">
    </form>
    <?php if (isset($_POST['delete_data'])) { ?>
        <form action="endscreen.php" method="post">
            <p>Are you sure you want to delete your data?</p>
            <input type="checkbox" name="confirm_delete" value="1"> Yes, I'm sure.<br>
            <label for="delete_reason">Reason for deleting:</label><br>
            <textarea name="delete_reason" id="delete_reason" cols="30" rows="5"></textarea><br>
            <input type="submit" value="Confirm">
            <button onclick="window.history.back();return false;">Cancel</button>
        </form>
    <?php } ?>
</body>
</html>

