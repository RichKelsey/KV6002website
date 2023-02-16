<?php 
    $target_dir = "../img/posts/";
    $myfile = fopen("ProcessingOutput.txt", "w") or die("Unable to open file!");
    $FileUploaded = 1;

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

    //Retrieving values submited in admin.php
    $username =     filter_has_var(INPUT_POST, 'Name')?     $_POST['Name'] : null;
    $groupID =      filter_has_var(INPUT_POST, 'Group')?    $_POST['Group'] : null;
    $image =        filter_has_var(INPUT_POST, 'Image')?    $_POST['Image'] : null;
    $directorID =   filter_has_var(INPUT_POST, 'Text')?     $_POST['Text'] : null;

    $sqlInsert = "INSERT INTO p_products (productName, description, categoryID, price) 
              VALUES (:productName, :description, :categoryID, :price)";
    $stmt = $dbConn->prepare($sqlInsert); 
    $stmt->execute(array(':productName' => $productName, 
                        ':description' => $description, 
                        ':categoryID' => $categoryID,
                        ':price' => $price));



    $target_file = $target_dir . basename($_FILES["Image"]["name"]);
    $uploadOk = 1;

    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));



    // Check if image image is a actual image or fake image or no file was uploaded
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

    // Check if image already exists
    // if (file_exists($target_file) && $FileUploaded == 1) 
    // {
    //     echo '<p name="exists"> Sorry, image already exists. </p><br>';
    //     $txt = "<p>Sorry, image already exists.</p><br>";
    //     fwrite($myfile, $txt);
    //     $uploadOk = 0;
    // }

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
        if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
        echo "The image ". htmlspecialchars( basename( $_FILES["Image"]["name"])). " has been uploaded.";
        $txt = "<p>The post has been uploaded.</p><br>";
        fwrite($myfile, $txt);
        } else {
        echo "Sorry, there was an error uploading your file.";
        $txt = "<p>Sorry, there was an error uploading your post.</p><br>";
        fwrite($myfile, $txt);
        }
    }
    fclose($myfile);






  
?>