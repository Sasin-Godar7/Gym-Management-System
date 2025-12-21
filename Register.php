<?php
session_start();
require_once "config.php";

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email    = trim($_POST['email']);
    $contact  = trim($_POST['contact']);

    $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Username or Email already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, email, contact, role)
                                VALUES (?, ?, ?, ?, 'user')");
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $contact);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Registration failed!";
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

<style>
/* error text style (UI safe) */
.error-text{
    color:red;
    font-size:13px;
    margin-top:5px;
}
.password-wrapper{
    position:relative;
}
.toggle-password{
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:13px;
}
</style>
</head>
<body>

<div class="container">
<form method="post" onsubmit="return validateForm();">
<h2>Register</h2>

<?php if($message != ''): ?>
<p style="color:red; text-align:center;"><?= $message ?></p>
<?php endif; ?>

<div class="input-box">
<input type="text" id="username" name="username" placeholder="Username" required>
<div id="usernameError" class="error-text"></div>
</div>

<div class="input-box password-wrapper">
<input type="password" id="password" name="password" placeholder="Password" required>
<!-- <span class="toggle-password" onclick="togglePassword()">Show</span> -->
<div id="passwordError" class="error-text"></div>
</div>

<div class="input-box">
<input type="email" id="email" name="email" placeholder="Email" required>
<div id="emailError" class="error-text"></div>
</div>

<div class="input-box">
<input type="tel" id="contact" name="contact" placeholder="Contact" required>
<div id="contactError" class="error-text"></div>
</div>

<button type="submit" class="btn">Register</button>

<p class="login-link">
Already have an account? <a href="login.php">Login</a>
</p>
</form>
</div>

<!-- ✅ JAVASCRIPT -->
<script src="register.js">

</script>


</body>
</html>
