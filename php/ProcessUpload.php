<?php 
    $target_dir = "../img/posts/";
    $avatar_dir = "../img/avatars/";
    $myfile = fopen("ProcessingOutput.txt", "w") or die("Unable to open file!");
    $FileUploaded = 1;
    $FileDuplicate = 0;

    foreach ((array)$_FILES["Image"]["error"] as $key => $error) 
    {
        if ($error == UPLOAD_ERR_NO_FILE) 
        {
            $FileUploaded = 0;
        }
    }

    require_once("db_connection.php");
    $db = db_connect();

    //checking if connection to the database has been established
    if($db == null)
    {
        $txt = "<p> Failed to connect to the database</p><br>";
        fwrite($myfile, $txt);
        fclose($myfile);
        exit();
    }




    $target_file_post = $target_dir . basename($_FILES["Image"]["name"]);

    $uploadOk = 1;

    $imageFileType = strtolower(pathinfo($target_file_post,PATHINFO_EXTENSION));



    // Check if image image is a actual image or fake image
    if(isset($_POST["submit"])) 
    {
        $check = getimagesize($_FILES["Image"]["tmp_name"]);

        if($check !== false && $FileUploaded == 1) 
        {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
        } 
        else
        {
        echo "File is not an image.";
        $txt = "<p> image is not an image</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
        }
    }

    //Check if image already exists
    if (file_exists($target_file_post) && $FileUploaded == 1) 
    {
        $FileDuplicate=1;
    }

    // Check image size should be <=5MB
    if ($_FILES["Image"]["size"] > 5000000 && $FileUploaded == 1) 
    {
        echo "Sorry, your image is too large.";
        $txt = "<p>Sorry, your image is too large.</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
    }

    // Allow certain image formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $FileUploaded == 1) 
    {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $txt = "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0 && $FileUploaded == 1) 
    {
        echo "Sorry, your post was not uploaded.";
        $txt = "<p>Sorry, your post was not uploaded.</p><br>";
        fwrite($myfile, $txt);
    // if everything is ok, try to upload file
    } 
    else 
    {
        if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file_post)) 
        {
            echo "The image ". htmlspecialchars( basename( $_FILES["Image"]["name"])). " has been uploaded.";
            $txt = "<p>The post has been uploaded.</p><br>";
            fwrite($myfile, $txt);
        }
        else
        {
            echo "Sorry, there was an error uploading your file.";
            $txt = "<p>Sorry, there was an error uploading your post.</p><br>";
            fwrite($myfile, $txt);
        }
    }
    fclose($myfile);
    
    //if image was not selected to upload delete error logs
    if($FileUploaded == 0)
    {
        $myfile = fopen("ProcessingOutput.txt", "w") or die("Unable to open file!");

        $txt = "<p>The post has been uploaded.</p><br>";
        fwrite($myfile, $txt);

        fclose($myfile);
    }

    
    //Retrieving values submited in admin.php
    if($uploadOk == 1 && $FileUploaded == 1 || $uploadOk == 1 && $FileDuplicate == 1 && $FileUploaded == 1 )
    {
        $Username =     filter_has_var(INPUT_POST, 'Name')?         $_POST['Name'] : null;
        $GroupID =      filter_has_var(INPUT_POST, 'Group')?        $_POST['Group'] : null;
        $Text =         filter_has_var(INPUT_POST, 'Text')?         $_POST['Text'] : null;
        $ProfilePic =   filter_has_var(INPUT_POST, 'avatar')?       $_POST['avatar'] : null;
        $LikeCount =    filter_has_var(INPUT_POST, 'LikeCount')?    $_POST['LikeCount'] : null;

        //since only one checkbox can be selected the [0]'th element will be the selected avatar
        $ProfilePic_path = $avatar_dir . $ProfilePic[0];


    
        $sqlInsert = "INSERT INTO Post (GroupID, Username, Text, LikeCount, Image, ProfilePic) 
                  VALUES (:GroupID, :Username, :Text, :LikeCount, :Image, :ProfilePic)";
        $stmt = $db->prepare($sqlInsert); 
        $stmt->execute(array(':GroupID' => $GroupID, 
                            ':Username' => $Username, 
                            ':Text' => $Text,
                            ':LikeCount' => $LikeCount,
                            ':Image' => $target_file_post,
                            ':ProfilePic' => $ProfilePic_path));
    }  
    
    if($FileUploaded == 0)
    {
        $Username =     filter_has_var(INPUT_POST, 'Name')?         $_POST['Name'] : null;
        $GroupID =      filter_has_var(INPUT_POST, 'Group')?        $_POST['Group'] : null;
        $Text =         filter_has_var(INPUT_POST, 'Text')?         $_POST['Text'] : null;
        $ProfilePic =   filter_has_var(INPUT_POST, 'avatar')?       $_POST['avatar'] : null;
        $LikeCount =    filter_has_var(INPUT_POST, 'LikeCount')?    $_POST['LikeCount'] : null;
        

        //since only one checkbox can be selected the [0]'th element will be the selected avatar
        $ProfilePic_path = $avatar_dir . $ProfilePic[0];

    

        $sqlInsert = "INSERT INTO Post (GroupID, Username, Text, LikeCount, ProfilePic) 
                  VALUES (:GroupID, :Username, :Text, :LikeCount, :ProfilePic)";
        $stmt = $db->prepare($sqlInsert); 
        $stmt->execute(array(':GroupID' => $GroupID, 
                            ':Username' => $Username, 
                            ':Text' => $Text,
                            ':LikeCount' => $LikeCount,
                            ':ProfilePic' => $ProfilePic_path));
    }

    

  
?>
<script src="../js/queryDB.js"></script>
<script>
function Query()


</script>

