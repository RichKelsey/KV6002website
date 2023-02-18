# queryDB.js

``queryDB.js`` uses [db_connection.php][db_connection.php] to query the database. 
It does so by sending a POST request to the file. For more information on how the 
request is handled see 
[db_connection POST request][db_connection.php/POST].

**!Getting the return value requires using .then(). Please see [Example Usage](#example-usage) for an explanation!**

[db_connection.php]: db_connection.php.md
[db_connection.php/POST]: db_connection.php.md#on-post-request
[displayPosts.js]: displayPosts.js.md

### Depends on:
- [db_connection.php][db_connection.php]
---

## queryDB

```JavaScript
queryDB(str query): JSON
```

### Parameters

**query**

- A string containing the intended MYSQL statement to be sent to the database.

### Return Values

If the operation was successful a promise will be returned containing the 
result of the MYSQL query upon fulfillment.

### Example Usage

```JavaScript
var query = "SELECT * FROM Post";
queryDB(query).then((posts) => {
            displayPosts(posts);
        });
```

This example passes a query to ``queryDB()`` and then uses ``.then()`` to pass the 
resolved promise's data a function ([displayPosts.js][displayPosts.js]) that 
formats and displays the returned data in the DOM.

The functionality of ``.then()`` is required because without it the code doesn't know to wait for the promise to resolve and will therefore probably execute before it has resolved. This means it may attempt to use data that doesn't exist yet, resulting in failure. Therefore please ensure anytime you use data acquired by this function, please do so within a structure like this:

```JavaScript
queryDB(query).then((returnedData) => {
    //code that uses returnedData here
})
```