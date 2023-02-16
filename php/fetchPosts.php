<?php
    include_once __DIR__ . "/logging.php";

    try {
        require_once("db_connection.php");
        $dbh = db_connect();
    }catch (Exception $e) {
        log_print("Error!: " . $e->getMessage() . "<br>");
        return null;
    }

    try {
        $statement = $dbh->prepare("SELECT * FROM Post");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($result);
    }catch (Exception $e) {
        log_print($e->getMessage());
        return null;
    }

    echo $json;
?>