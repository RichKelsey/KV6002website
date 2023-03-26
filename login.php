<?php
session_start(); // start the session

// MySQL server configuration
$server = "localhost";
$username = "nick";
$password = "604t0CggxwWsOEhp@";
$dbname = "KV6002";

// Create connection
$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

if (isset($_POST['username']) && isset($_POST['password'])) { // check if the username and password are submitted
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // prepare the SQL statement with placeholders for the user input
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    if ($stmt->fetch()) {
        // check if the username and password are correct
        $_SESSION['username'] = $username; // set the username in the session
        $_SESSION['logged_in'] = true; // set the session variable to true
        header('Location: admin.php'); // redirect to the dashboard
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
