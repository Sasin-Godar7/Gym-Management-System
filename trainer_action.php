<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'trainer'){
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$action = $_GET['action'];

$status = ($action == 'accept') ? 'Approved' : 'Rejected';

$conn->query("
  UPDATE trainer_bookings 
  SET status='$status', is_seen=0 
  WHERE id=$id
");

header("Location: trainer_dashboard.php");
exit();
