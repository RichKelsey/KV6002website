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

## db_getTableNames

```php
db_getTableNames(PDO $dbh): array
```

This function may mostly be temporary, only to test the connection to database
during development.

### Parameters

**dbh**

- This function takes in a PDO instance as a handle to to the database.

### Return Values

This function returns an array of table names.
