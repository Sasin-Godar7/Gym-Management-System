<?php
session_start();
require "config.php";
if(!isset($_SESSION['admin_username'])){
    header("Location: adminlogin.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i",$id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if(!$user) exit("User not found!");

$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);

    if($password){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("UPDATE users SET username=?, password=?, email=?, contact=? WHERE id=?");
        $stmt2->bind_param("ssssi",$username,$password,$email,$contact,$id);
    } else {
        $stmt2 = $conn->prepare("UPDATE users SET username=?, email=?, contact=? WHERE id=?");
        $stmt2->bind_param("sssi",$username,$email,$contact,$id);
    }
    $stmt2->execute();
    header("Location: admin_dashboard.php");
    exit();
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
