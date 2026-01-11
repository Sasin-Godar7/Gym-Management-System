<?php
session_start();
require "config.php";

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Update is_seen to 1 so they don't show up as new
    $conn->query("UPDATE trainer_bookings SET is_seen = 1 WHERE user_id = '$user_id'");
}

header("Location: user_dashboard.php");
exit();
?>