<?php
/* Database credentials. */
define('DB_SERVER', 'localhost:3306');
define('DB_USERNAME', 'GHT');
define('DB_PASSWORD', 'azerty1234');
define('DB_NAME', 'GHT');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>