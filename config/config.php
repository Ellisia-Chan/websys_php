<?php
$host = "localhost";
$dbname = "shoestore";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
