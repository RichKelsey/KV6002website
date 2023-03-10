<!DOCTYPE html>
<html lang="en">>
<head>
<meta charset="UTF-8">
<title>User's sign up</title>
</head>
<body>
<?php
//include 'connection.php';
require_once("../php/db_connection.php");
	$db = db_connect();

// Check if connection successful
if (!$db) die("Can't connect to database");


//write entered values into variables to later display
require_once "credentials.php";

$directory = "../img/avatars/";
$files = array();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect user input
    $Name = $_POST['name'];
    $Bio = $_POST['bio'];

    echo"<br><label for='avatar[]'>Select an avatar:<br><br>";

 				//display all available avatars
 				foreach (scandir($directory) as $file) 
 				{
 					if ($file !== '.' && $file !== '..') 
 					{
 						$files[] = $file;

 						echo"<img src='../img/avatars/$file'" . "width='40' height='40' />";
					}
				}


    // Validate input
    if (empty($Name) || empty($Bio)) {
        // Error: All fields are required
        echo "All fields are required";
    } elseif (strlen($Bio) < 400) {
        // Error: Bio must be at least 400 characters
        echo "Bio must be at least 400 characters";
    } else {
        // Hash Bio
        $hashed_Bio = bio_hash($Bio, BIO_DEFAULT);

        // Connect to database
        $conn = new mysqli('localhost', 'username', 'bio', 'ProfilePic');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert user into database
        $sql = "INSERT INTO users (name , bio) VALUES ('$Name', '$hashed_Bio')";
        if ($conn->query($sql) === TRUE) {
            echo "User created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }

    
    }
}

// get user information
$user_id = $conn->query('SELECT ParticipandID FROM Participant');// replace with the actual user ID
$user_name = $conn->query('SELECT Name FROM Participant'); // replace with the actual user name

// randomly allocate user to a group
$group = $conn->query('SELECT GroupID FROM Group');

// insert user and group information into database
$sql = "INSERT INTO Participant (ParticipantID, GroupID, Name)
        VALUES ('$user_id', '$user_name', '$group')";

if (mysqli_query($conn, $sql)) {
    echo "User allocated to group $group";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

?>
<!-- HTML form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="name">Name:</label>
  <input type="text" name="name" id="name" value="<?php echo $name;?>">
  <span class="error"><?php echo $nameErr;?></span><br><br>

  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?php echo $email;?>">
  <span class="error"><?php echo $emailErr;?></span><br><br>

  <label for="password">Password:</label>
  <input type="password" name="password" id="password">
  <span class="error"><?php echo $passwordErr;?></span><br><br>

  <input type="submit" name="submit" value="Sign Up">
</form>

</body>
</html> 
