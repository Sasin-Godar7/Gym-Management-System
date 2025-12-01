<?php
session_start();
require_once "config.php"; // database connection file

$message = "";

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['name']);
    $password = trim($_POST['password']);

    // Query to find user
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.php"); // redirect to home/dashboard
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
    <link rel="stylesheet" href="Login.css">
</head>
<body>

<div class="container">
    <form action="" method="post">
        <h2>Login</h2>

        <?php if ($message != "") { echo '<p style="color:red;">'.$message.'</p>'; } ?>

        <div class="input-box">
            <input type="text" name="name" id="uname" placeholder="Username" required />
        </div>

        <div class="input-box">
            <input type="password" name="password" id="upassword" placeholder="Password" required />
        </div>

        <div class="remember-forget">
            <label> <input type="checkbox"> Remember Me </label>
            <a href="#">Forget password?</a>
        </div>

        <button type="submit" class="btn">Login</button>

        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </form>
</div>

</body>
</html>
