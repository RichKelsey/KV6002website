<?php 
    $target_dir = "../img/posts/";
    $target_file = $target_dir . basename($_FILES["Image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $myfile = fopen("ProcessingOutput.txt", "w") or die("Unable to open file!");

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["Image"]["tmp_name"]);
        if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
        } else {
        echo "File is not an image.";
        $txt = "<p> File is not an image</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo '<p name="exists"> Sorry, file already exists. </p><br>';
        $txt = "<p>Sorry, file already exists.</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
    }

    // Check file size should be <=5MB
    if ($_FILES["Image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $txt = "<p>Sorry, your file is too large.</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $txt = "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p><br>";
        fwrite($myfile, $txt);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        $txt = "<p>Sorry, your file was not uploaded.</p><br>";
        fwrite($myfile, $txt);
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["Image"]["name"])). " has been uploaded.";
        $txt = "<p>The file has been uploaded.</p><br>";
        fwrite($myfile, $txt);
        } else {
        echo "Sorry, there was an error uploading your file.";
        $txt = "<p>Sorry, there was an error uploading your file.</p><br>";
        fwrite($myfile, $txt);
        }
    }
    fclose($myfile);

?>