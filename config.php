<?php
$servername = "localhost:3307";
$dbusername = "root";
$dbpassword = "";
$dbname = "gymdb";
$dbport = 3307;

$conn = new mysqli("localhost", "root", "", "gymdb", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
