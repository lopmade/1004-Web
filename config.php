<?php

/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

if (!defined('DB_SERVER') && !defined('DB_USERNAME') && !defined('DB_PASSWORD') && !defined('DB_NAME')){
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'mydb');
}

/* Attempt to connect to MySQL database */

$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link -> connect_errno){
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
?>