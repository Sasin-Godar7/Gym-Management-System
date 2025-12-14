<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing data
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
if(!$user){ header("Location: admin_dashboard.php"); exit(); }

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = trim($_POST['password']);

    if($password != '') $password = password_hash($password, PASSWORD_DEFAULT);
    else $password = $user['password'];

    // Check duplicate username/email (exclude current)
    $check = $conn->query("SELECT * FROM users WHERE (username='$username' OR email='$email') AND id != $id");
    if($check->num_rows > 0){
        $message = "Username or Email already exists!";
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, password=?, email=?, contact=? WHERE id=?");
        $stmt->bind_param("ssssi",$username,$password,$email,$contact,$id);
        if($stmt->execute()){
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Error updating user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit <?= ucfirst($user['role']) ?></title>
<link rel="icon" type="image/png" href="images/fav.png">
<link rel="stylesheet" href="edit_user.css">
</head>


<body>
<div class="container">
<h2>Edit <?= ucfirst($user['role']) ?></h2>

<?php if($message != '') echo "<p class='error'>$message</p>"; ?>

<form method="post">
<input type="text" name="username" placeholder="Username" value="<?=$user['username']?>" required>
<input type="password" name="password" placeholder="New Password (leave blank to keep)">
<input type="email" name="email" placeholder="Email" value="<?=$user['email']?>" required>
<input type="tel" name="contact" placeholder="Contact" value="<?=$user['contact']?>" required>
<button type="submit">Update <?= ucfirst($user['role']) ?></button>
</form>

<a href="admin_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
