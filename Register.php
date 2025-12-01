<?php
session_start();
require_once "config.php";
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email    = trim($_POST['email']);
    $contact  = trim($_POST['contact']);

    // Check duplicate username/email
    $check = $conn->prepare("SELECT * FROM users WHERE email=? OR username=?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Username or Email already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, contact) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $contact);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Registration failed, try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="Register.css">
</head>
<body>

<div class="container">
  <form action="register.php" method="post">
    <h2>Register</h2>

    <?php if($message != ''): ?>
      <p style="color:red; text-align:center;"><?= $message ?></p>
    <?php endif; ?>

    <div class="input-box">
      <input type="text" name="username" id="username" placeholder="Username" required>
    </div>

    <div class="input-box">
      <input type="password" name="password" id="password" placeholder="Password" required>
    </div>

    <div class="input-box">
      <input type="email" name="email" id="email" placeholder="Email" required>
    </div>

    <div class="input-box">
      <input type="tel" name="contact" id="contact" placeholder="Contact" required>
    </div>

    <button type="submit" class="btn">Register</button>

    <div class="login-link">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </form>
</div>

</body>
</html>
