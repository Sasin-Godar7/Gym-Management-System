<?php
session_start();
require "config.php";

$message = "";

if(isset($_POST['name']) && isset($_POST['password'])){
    $username = $_POST['name'];
    $password = $_POST['password'];

    // DB bata user fetch
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();

        // Simple password check (if not hashed)
        if($password == $user['password']){ 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Role check
            if($user['role'] == 'admin'){
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Username not found!";
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
</head>
<body>

<div class="container">
    <form method="post">
        <h2>Login</h2>

        <?php if($message != "") { echo '<p style="color:red;">'.$message.'</p>'; } ?>

        <div class="input-box">
            <input type="text" name="name" placeholder="Username" required>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn">Login</button>

        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>

</body>
</html>
