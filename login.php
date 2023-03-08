<?php
session_start(); // start the session

if (isset($_POST['username']) && !empty($_POST['username'])) { // check if the username is submitted
    $username = $_POST['username'];

    if ($username == 'admin') { // check if the username is 'admin'
        echo 'Please enter the admin password:<br>';
        echo '<form action="login.php" method="post">';
        echo '<input type="password" name="password">';
        echo '<input type="submit" value="Login">';
        echo '</form>';

        exit(); // stop the execution of the script to wait for the admin password
    }

    $_SESSION['username'] = $username; // set the username in the session
    header('Location: dashboard.php'); // redirect to the dashboard
    exit();
}

if (isset($_POST['password']) && !empty($_POST['password'])) { // check if the admin password is submitted
    $password = $_POST['password'];

    if ($password == '12345') { // check if the password is correct
        $_SESSION['username'] = 'admin'; // set the admin username in the session
        header('Location: dashboard.php'); // redirect to the dashboard
        exit();#
	}
		else{
			echo'Incorrect password';
		}
}

// if the username and admin password are not submitted, show the login form
?>

<form action="login.php" method="post">
    Username: <input type="text" name="username"><br>
    <input type="submit" value="Login">
</form>