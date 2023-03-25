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
    session_start();

    //connect to the database
    require_once("../php/db_connection.php");
    $db = db_connect();

    //check if connection successful
    if (!$db) die("Can't connect to database");
    

    //check if the form is submitted
    if(isset($_POST['username']) && isset($_POST['password'])) 
    {
        //get the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];
        

        //query to check if the username exists in the database
        $sql ="SELECT * FROM `Admin` WHERE `Username` = '$username'";

        //get results
        $results = json_decode(db_query($sql, $db), true);

        //check if the query returned any rows
        if(count($results) > 0) 
        {
            //get password hash for that username
            $hash = $results[0]['PasswordHash'];

            //check if the password is correct
            if (password_verify($password, $hash)) 
            {
                //if password is correct, set session variables and redirect
                $_SESSION['logged_in'] = 1;
                header('Location: admin.php');
                exit;
            } 
            else 
            {
                //password is incorrect, display an error message
                echo 'Incorrect username or password. Please try again.';
                $_SESSION['logged_in'] = 0;
            }

        }
        else
        {
            //username doesn't exist, display an error message
            echo 'Incorrect username or password. Please try again.';
        }


    } 


?>
<div class="login">
    <form action="" method="post">
        <p>Username:<input type="text" name="username"> </p>
        <p>Password: <input type="password" name="password"><br></p> 
        <input type="submit" value="Login" class="dropbtn">
    </form>
</div>