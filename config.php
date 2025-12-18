<?php
$servername = "localhost:3307";
$dbusername = "root";
$dbpassword = "sasin@123";
$dbname = "gymdb";
$dbport = 3306;

$conn = new mysqli("localhost", "root", "sasin@123", "gymdb", 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
