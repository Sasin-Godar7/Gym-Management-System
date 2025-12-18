
<?php
session_start();
require "config.php";
if(!isset($_SESSION['admin_username'])){
    header("Location: adminlogin.php");
    exit();
}

$role = $_GET['role'] ?? 'user';
$message = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0){
        $message = "Username already exists!";
    } else {
        $stmt2 = $conn->prepare("INSERT INTO users (username,password,email,contact,role) VALUES (?,?,?,?,?)");
        $stmt2->bind_param("sssss", $username,$password,$email,$contact,$role);
        $stmt2->execute();
        header("Location: admin_dashboard.php");
        exit();
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
