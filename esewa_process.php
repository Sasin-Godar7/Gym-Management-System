<?php
session_start();

$test_id = "9800000000";
$test_mpin = "1234";

$user_id = $_POST['esewa_id'];
$user_mpin = $_POST['mpin'];

if($user_id == $test_id && $user_mpin == $test_mpin){
    echo "<h2 style='color:green;'>Payment Successful ✅</h2>";
    echo "Amount Paid: NPR " . $_SESSION['amount'];
}else{
    echo "<h2 style='color:red;'>Invalid ID or MPIN ❌</h2>";
    echo "<a href='esewa_login.php'>Try Again</a>";
}
?>
