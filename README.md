# firebird-db
Facilitates the use of Firebird with object orientation without using the PDO

## Installation
Add repositories and require section in composer.json project

```json
{
		"repositories": [
		    {
		        "url": "https://github.com/ricogmacedo/firebird-db.git",
		        "type": "git"
		    }
		],
    "require": {
        "ricogmacedo/firebird-db": "1.0.0"
    }
}
```

## Example usage
Create a namespace alias for ease of use
```php
use \FirebirdDB\FirebirdDB as FirebirdDB;
```
Set the connection parameters
```php
$ipAddr = "IP Address";
$pathFDB = "Path to .FDB file";
$nameFDB = "Name of .FDB file";
$userDB = "Firebird Server Username";
$passwDB = "Firebird Server Password";
$charConn = "Connection Charset";
```
Create a connection to the Firebird's Server
```php
$dbh = new FirebirdDB($ipAddr, $pathFDB, $nameFDB, $userDB, $passwDB, $charConn);
```
Set a SQL Query
```php
$sqlQuery = "SELECT FOO FROM BAR WHERE NAME = ?";
```
Build a statement with the SQL Query
```php
$stmt = $dbh->build($sql);
```
Set the Query Param
```php
$stmt->setParam('S', 'FOOBAR');
```
Run the Query
```php
$stmt->run($conn);
```
Return all results
```php
echo $stmt->getResult();
```
Returns only one result
```php
echo $stmt->getOneResult();
```
Returns the query
```php
echo $stmt->getQuery();
```