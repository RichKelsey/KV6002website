<?php
    $myfile = fopen("UploadGroup.txt", "w") or die("Unable to open file!");

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



    //$GroupID =          filter_has_var(INPUT_POST, 'GroupID')?           $_POST['GroupID'] : null;
    $LikesAllowance =   filter_has_var(INPUT_POST, 'LikesAllowance')?    $_POST['LikesAllowance'] : null;
    $TimeAllowance =    filter_has_var(INPUT_POST, 'TimeAllowance')?     $_POST['TimeAllowance'] : null;
    $LikesReceived =    filter_has_var(INPUT_POST, 'LikesReceived')?     $_POST['LikesReceived'] : null;



    try
    {
        $sqlInsert = "INSERT INTO `Group` (LikesAllowance, TimeAllowance, LikesReceived) 
                    VALUES (:LikesAllowance, :TimeAllowance, :LikesReceived)";

        $stmt = $db->prepare($sqlInsert); 
        $stmt->execute(array(':LikesAllowance' => $LikesAllowance, 
                            ':TimeAllowance' => $TimeAllowance, 
                            ':LikesReceived' => $LikesReceived));

        $txt = "<p> Successfully Created a new Group</p><br>";
        fwrite($myfile, $txt);
        fclose($myfile);
        exit();
    }
    catch(PDOException $e)
    {
        $txt = "Error!: " . $e->getMessage() . "<br>";
        fwrite($myfile, $txt);
        fclose($myfile);
        exit();
    }
?>