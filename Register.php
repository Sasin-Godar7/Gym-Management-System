<?php
session_start();
require_once "config.php";

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email    = trim($_POST['email']);
    $contact  = trim($_POST['contact']);

    // Check if username or email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Username or Email already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Default role 'user'
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, contact, role) VALUES (?, ?, ?, ?, 'user')");
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
<link rel="stylesheet" href="register.css">
 <link rel="icon" type="image/png" href="images/fav.png">
</head>
<body>

<div class="container">
  <form method="post">
    <h2>Register</h2>

    <?php if($message != ''): ?>
      <p style="color:red; text-align:center;"><?= $message ?></p>
    <?php endif; ?>

    <div class="input-box">
      <input type="text" name="username" placeholder="Username" required>
    </div>

    <div class="input-box">
      <input type="password" name="password" placeholder="Password" required>
    </div>

    <div class="input-box">
      <input type="email" name="email" placeholder="Email" required>
    </div>

    <div class="input-box">
      <input type="tel" name="contact" placeholder="Contact" required>
    </div>

    <button type="submit" class="btn">Register</button>

    <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>

</body>
</html>
