<?php
session_start();
require "config.php";

$message = "";

if(isset($_POST['username'], $_POST['password'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $admin = $result->fetch_assoc();
        if($password === $admin['password']){ // plain password
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Admin not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
  <form method="post">
    <h2>Admin Login</h2>

    <?php if($message != ''): ?>
      <p style="color:red; text-align:center;"><?= $message ?></p>
    <?php endif; ?>

    <div class="input-box">
      <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="input-box">
      <input type="password" name="password" placeholder="Password" required>
    </div>

    <button type="submit" class="btn">Login</button>

    <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
  </form>
</div>
</body>
</html>
