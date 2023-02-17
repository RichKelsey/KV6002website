# fetchPosts.php

``fetchPosts.php`` uses the pdo functionality included in [db_connection.php][db_connection.php] 
to fetch all the posts from the database. 

[db_connection.php]: db_connection.php.md

Intended to be called by a JavaScript FETCH sequence to allow DOM content to be loaded asynchronously to actual page load to combat long page load times or hangs.

### Depends on:
- [db_connection.php][db_connection.php] 


## Fetching the posts

```php
//try to fetch the posts from the database
try {
    $statement = $dbh->prepare("SELECT * FROM Post");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    $json = json_encode($result); //encode as json
}catch (Exception $e) {
    log_print($e->getMessage());
    return null;
}

echo $json; //return the json via an echo
```
Uses a SELECT SQL statement to request all entries in the Post table. The 
requested data is then encoded into JSON format for easy interoperability.
The JSON data is then echo'd.

## Return Values

Returns all the entries in the Post database table as a JSON object. 

