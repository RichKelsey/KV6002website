<?php
session_start(); // start the session

// connect to the database
require_once("../php/db_connection.db");
$connection = db_connect();

//check if the connection is successful
if (!$connection) die("Cannot connect to the database");

if (isset($_POST['username']) && !empty($_POST['username'])) { // check if the username is submitted
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check if the username and password are correct for the admin account
    if ($username == 'admin' && $password == '12345') {
        $_SESSION['username'] = 'admin'; // set the admin username in the session
        $_SESSION['logged_in'] = true; // set the session variable to true
        header('Location: admin.php'); // redirect to the dashboard
        exit();
    }

    // check if the username and password are correct for a regular user
    $stmt = $connection->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['username'] = $username; // set the username in the session
        header('Location: userprofile.php'); // redirect to the dashboard
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

// if the username and password are not submitted, show the login form
?>
<form action="login.php" method="post">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
