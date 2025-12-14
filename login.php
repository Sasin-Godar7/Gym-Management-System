<?php
session_start();
require "config.php";

$message = ""; // Error message

if(isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // DB bata user fetch
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){
            
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] === "admin"){
                header("Location: admin_dashboard.php");
                exit();
            }
             elseif($row['role'] === "trainer"){
                header("Location: trainer_dashboard.php");
                exit();
            }
            
            else {
                header("Location: user_dashboard.php");
                exit();
            }

        } else {
            $message = "Incorrect password!";
        }

    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
   <link rel="icon" type="image/png" href="images/fav.png">
</head>
<body>

<div class="container">
  <form method="post">
    <h2>Login</h2>

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
