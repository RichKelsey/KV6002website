# logging.php

``logging.php`` contains functions to handle logging in php code.

Logging writes to ``php.log`` in the project root if the DEBUG constant is not
defined.

## Functions

### log_print

```php
log_print(string $string): void
```

Logs a normal message.

#### Parameters

**string**

The string for the log message.

#### Return Values

This function doesn't return anything.

### log_error

```php
log_error(string $string): void
```

Logs an error message by prepending ``$string``, indicating that it is an error
message.

This function calls the log_print message.

#### Parameters

**string**

The string for the error message.

#### Return Values

This function doesn't return anything.

## Known issues

``log_print()`` may fail to create and write to the log file, depending on file
system and web server user permissions.

A work around is to manually create the ``php.log`` file manually, and changing
the permissions.

Example commands on a \*nix system would be:

```bash
$ cd <project_root>/
$ touch php.log
$ chmod 666 php.log
```

This would allow read and write permissions for the user, group, and everyone
else.
