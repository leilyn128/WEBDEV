<?php

$database = "nursery_admission";
$port = 3306;
$username = "berna";
$password = "Leilyn@123456";
$hostname = "localhost";
$dbhandle = mysqli_connect($hostname, $username, $password, $database, $port) or die ("unable to connect");

echo"";

$selected = mysqli_select_db($dbhandle, $database) or die ("could not select the database");

?>