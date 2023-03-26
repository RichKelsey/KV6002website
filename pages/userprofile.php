<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/userprofile.css">
    <meta charset="utf-8">
    <title>user profile creation</title>
    <h1>Create your profile</h1>
</head>

<body>
    <?php

    //connect to the database

    require("../php/db_connection.php");
    $db = db_connect();

    // Check if connection successful
     if (!$db) die("Can't connect to database");



    // Get the maximum ID from the users table
     $sql = "SELECT MAX(id) as max_id FROM users";
     $result = mysqli_query($query, $conn);

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


    // Validate input
    if (empty($username) || empty($bio)) {
    // Error: All fields are required
     echo "All fields are required";
      } elseif (strlen($bio) > 400) {
    // Error: Bio must be at least 400 characters
       echo "Bio must be at least 400 characters";
     }


    // randomly allocate user to a group
     $group = $db->query('SELECT GroupID FROM Group');


    ?>
    <script src="../js/participantdata.js"></script>
    <script>
        //Allow only one checkbox to be selected

        function selectOnlyOne(checkbox) {
            var checkboxes = document.getElementsByName('avatar[]');
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
            });
        }

        //Check if an avatar has been selected

        function validateForm() {
            var checkboxes = document.getElementsByName("avatar[]");
            var checked = false;
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    checked = true;
                    break;
                }
            }
            if (!checked) {
                alert("Please select an Avatar to continue.");
                return false;
            }
        }
    </script>

    <form>

        <fieldset>
            <legend>Create Profile</legend>
            <div class="col-auto">
                <label for="TextInput" class="form-label">Username</label>
                <input id='usernamen' type="text" id="TextInput" class="form-control" placeholder="">
            </div>
            <div class="mb-3">
                <label for="TextInput" class="form-label">Bio</label>
                <input id='bio' type="text" id="TextInput" class="form-control" placeholder="">
            </div>



        </fieldset>

    </form>

    <button class='button' id="btn" onclick="myFunction()"> Select Avatar </button>
    <p id="avatar1">

        <?php

        $tmp = 0;

        $directory = "../img/avatars/";
        $files = array();

        //display all available avatars
        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = $file;
                echo "<img src='../img/avatars/$file'" . "width='80' height='80' />";
            } else {

                echo "<img id='avatar' name='avatar[]' value='{$file}' onclick='AvatarSelection(\"$file\")'>";
                echo "</div>";
            }
        }
        $tmp += 1;
        ?>
    </p>




    <button class='button' onclick="location.href='../login.php'">Return to Login Page</button>
    <button class='button' onclick="location.href='../pages/newsfeed.php'">Complete your profile </button>


</body>

<script>
    function myFunction() {
        document.getElementById("avatar1");

    }
</script>


<script>
    const btn = document.getElementById('btn');

    btn.addEventListener('click', () => {
        const form = document.getElementById('avatar1');

        if (form.style.display === 'none') {
            //  this SHOWS the form
            form.style.display = 'block';
        } else {
            //  this HIDES the form
            form.style.display = 'none';
        }
    });
</script>


</html>
