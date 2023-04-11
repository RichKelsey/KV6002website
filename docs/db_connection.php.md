# db_connection.php

``db_connection.php`` handles the authentication and connection to a database
with PDO.

Authenticating requires that a ``credentials.php`` file exists in the same
directory as the ``db_connection.php`` file, as defined by a constant:

```php
define("CREDENTIALS_FILE",  __DIR__ . "/credentials.php");
```

It requires that you also define the following variables:

- ``host``: the host name
- ``dbname``: the database name
- ``username``: the username
- ``pasword``: the password for the user 

## Examples

**Basic usage:**

The below example creates a database connection, makes a query and closes the
connection.

```php
$dbh = db_connect();
// Check if connection successful
if (!$dbh) die("Can't connect to database");

// Do things with database connection
$tables = db_getTableNames($dbh);
foreach ($tables as $table) {
	echo $table . " ";
}
echo "<br>"

// close conection
$dbh = null;
```

## db_connect

```php
db_connect(): PDO|null
```

``db_connect()`` contains is wrapper to create the php PDO class, handling
credentials and errors. 

### Parameters

This function has no parameters.

### Return Values

If connection is successful it returns an instance of the PDO class, or null on
failure.

## db_query

```php
function db_query($query, $dbh): JSON
```

Allows for a MYSQL query to be sent to the database and for its response to be received and encoded to JSON data.

### Parameters

**query**

- A string containing a valid MYSQL statement that will be sent to the database.

**dbh**

- This function takes in a PDO instance as a handle to to the database.

### Return Values

If the operation was successful a JSON object will be returned containing the result of the MYSQL query. On failure null is returned.

## db_getTableNames

```php
db_getTableNames(PDO $dbh): array
```

This function may mostly be temporary, only to test the connection to database
during development.

### Parameters

**dbh**

- This function takes in a PDO instance as a handle to the database.

### Return Values

This function returns an array of table names.

## On POST request

When the file receives a POST request it checks the body of the POST for a 
JSON key called ``query`` and then passes the query (along with a PDO instance 
from [db_connect()](#db_connect)) to [db_query()](#db_query) in order to 
execute said query as a MYSQL statement on the database. The result of the 
statement is then output using ``echo`` acting as a POST response.

### Parameters

**JSON**

- Expects a JSON object that includes a key called ``query``.

### Return Values

Returns the response from the database formatted as a JSON object using 
``echo``.

### Example POST request

```JavaScript
const URL = "../php/db_connection.php"; //set file path to php file
var data = { query: 'SELECT * FROM Post' }; //query to be sent to db

//POST to file
fetch(URL, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify(data), //encode the data as JSON
})
  .then((response) => {
    return response.json(); //return the POST response as a JSON object
  })
  .then((data) => {
    console.log('Success:', data);//log the response in the console
  })
  .catch((error) => {
    console.error('Error:', error); //on error log the error to console
  });
```

An example post request written in JavaScript that POSTs the query and then 
logs the response to the browser's console.
