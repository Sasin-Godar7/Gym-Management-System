<?php
session_start();
require "config.php";

$message = "";

if(isset($_POST['email']) && isset($_POST['password'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(!empty($email) && !empty($password)){

        // Fetch user by email
        $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){

            $row = $result->fetch_assoc();

            // Verify hashed password
            if(password_verify($password, $row['password'])){

                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role']     = $row['role'];

                // Role based redirect
                if($row['role'] === "trainer"){
                    header("Location: trainer_dashboard.php");
                    exit();
                } else {
                    header("Location: user_dashboard.php");
                    exit();
                }

            } else {
                $message = "Incorrect Password!";
            }

        } else {
            $message = "Email not found!";
        }

    } else {
        $message = "All fields are required!";
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
<p style="color:red; text-align:center;"><?php echo $message; ?></p>
<?php endif; ?>

<div class="input-box">
<input type="email" name="email" placeholder="Enter Email" required>
</div>

<div class="input-box">
<input type="password" name="password" placeholder="Enter Password" required>
</div>

<button type="submit" class="btn">Login</button>

<p class="register-link">
Don't have an account? <a href="register.php">Register</a>
</p>

</form>
</div>

</body>
</html>