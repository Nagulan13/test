<?php

$host_name = "localhost";
$db_name = "premier_league";
$username = "root";
$password = "";

$conn = mysqli_connect($host_name, $username, $password, $db_name);

if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
    //header('location: index.php');
    echo "<a href='index.php'>Login Again...</a>"; 
}


?>
