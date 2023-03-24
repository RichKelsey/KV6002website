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


$directory = "../img/avatars/";
$files = array();

// Get the maximum ID from the users table
$sql = "SELECT MAX(id) as max_id FROM users";
$result = mysqli_query($db, $sql);

// Check if query was successful
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $PartcipantID = $row['max_id'] + 1; // Create a new ID by adding 1 to the maximum ID
} else {
    $PartcipantID = 1; // If the table is empty, start the IDs from 1
}

// Get user data from the form
$username = $_POST['username'];
$bio = $_POST['bio'];


//select Avatar
  echo"<br><label for='avatar[]'>Select an avatar:<br><br>";

 	//display all available avatars
 		foreach (scandir($directory) as $file) 
 			{
 			 if ($file !== '.' && $file !== '..') 
 				{
 					$files[] = $file;

 					echo"<img src='../img/avatars/$file'" . "width='40' height='40' />";
                        
            echo"<div class='avatarcontainer'>";
                                 
              echo"<label for='avatar'> <img src='../img/avatars/$file'/> </label>";
          }
            else
            {

              echo"<input type='checkbox' id='avatar' name='avatar[]' value='{$file}' onclick='AvatarSelection(\"$file\")'>";
                echo"</div>";

                }
                                 
              $tmp +=1;
                                 
                                
               }

// Validate input
  if (empty($username) || empty($bio)) {
  // Error: All fields are required
     echo "All fields are required";
    } elseif (strlen($bio) > 400) {
        // Error: Bio must be at least 400 characters
        echo "Bio must be at least 400 characters";
    } 

// Check connection
  if ($db->failed) {
    die("Connection failed: " . $db->failed);
    }

// Insert user into database
$conn = "INSERT INTO Participants (PartcipantID, username, bio) VALUES ('$PartcipantID', '$username', '$bio')";
  if ($db->query($sql) === TRUE) {
    echo "User created successfully";
     } else {
        echo "Error: " . $sql . "<br>" . $db->error;
        }

    

// randomly allocate user to a group
$group = $db->query('SELECT GroupID FROM Group');


if (mysqli_query($db, $sql)) {
    echo "User allocated to group $group";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}

// insert user's group into database
$sql = "INSERT INTO Participant (GroupID)
        VALUES ('$PartcipantID', '$group')";

//write entered values into variables to later display
require_once "../php/db_connection.php";

?>

<script>
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
			}
			
      </script>

<form>

  <fieldset disabled>
    <legend>Create Profile</legend>
    <div class="col-auto">
    <label for="staticUsername" class="visually-hidden">Username</label>
    <input type="text" readonly class="form-control-plaintext" id="staticusername" value="username">
  </div>
    <div class="mb-3">
      <label for="disabledTextInput" class="form-label">Bio</label>
      <input type="text" id="disabledTextInput" class="form-control" placeholder="Bio">
    </div>
    <div class="mb-3">
      <label for="disabledSelect" class="form-label">Select Avatar</label>
      <select id="disabledSelect" class="form-select">
        <option>Avatars</option>
      </select>
    </div>
    
    <button onclick="location.href='newsfeed.php'">Complete your profile </button>
    <button onclick="location.href='login.php'">Return to Login Page</button>
    <button type="submit" class="btn btn-primary">Submit</button>
  </fieldset>

</form>

</body>
</html> 
