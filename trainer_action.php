<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'trainer'){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id']) || !isset($_GET['action'])){
    header("Location: trainer_dashboard.php");
    exit();
}

$id = intval($_GET['id']);
$action = $_GET['action'];

if($action == 'accept'){
    $status = 'Approved';
}
elseif($action == 'reject'){
    $status = 'Rejected';
}
else{
    header("Location: trainer_dashboard.php");
    exit();
}

$conn->query("UPDATE trainer_bookings SET status='$status' WHERE id=$id");

header("Location: trainer_dashboard.php");
exit();
