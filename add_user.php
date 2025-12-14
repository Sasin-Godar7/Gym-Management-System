<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// Get role from query string (user or trainer)
$role = isset($_GET['role']) && in_array($_GET['role'], ['user','trainer']) ? $_GET['role'] : 'user';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Check if username/email exists
    $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $check->bind_param("ss",$username,$email);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $message = "Username or Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username,password,email,contact,role) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss",$username,$password,$email,$contact,$role);
        if($stmt->execute()){
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Error adding user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add <?= ucfirst($role) ?></title>
<link rel="stylesheet" href="add_user.css">
<link rel="icon" type="image/png" href="images/fav.png">

</head>
<body>

<div class="container">
<h2>Add <?= ucfirst($role) ?></h2>

<?php if($message != '') echo "<p class='error'>$message</p>"; ?>

<form method="post">
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
<button type="submit">Add <?= ucfirst($role) ?>
</button>
</form>

<a href="admin_dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
