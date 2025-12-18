<?php
session_start();
require "config.php";
if(!isset($_SESSION['admin_username'])){
    header("Location: adminlogin.php");
    exit();
}

// Delete member
if(isset($_GET['delete_member'])){
    $id = (int)$_GET['delete_member'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='user'");
    header("Location: admin_dashboard.php"); exit();
}

// Delete trainer
if(isset($_GET['delete_trainer'])){
    $id = (int)$_GET['delete_trainer'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='trainer'");
    header("Location: admin_dashboard.php"); exit();
}

// Stats
$totalMembers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'")->fetch_assoc()['total'];
$totalTrainers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='trainer'")->fetch_assoc()['total'];
$totalRevenue = 280000;
?>