<?php
$servername = "localhost";
$username = "root";
$password = "197969";
$dbname = "travel_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
