<?php
session_start();
session_destroy(); // sab session data remove garxa
header("Location: login.php"); // login page ma pathaune
exit();
?>
