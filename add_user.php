
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

<form method="post" onsubmit="return validateForm()">

<div class="input-box">
  <input type="text" id="username" name="username" placeholder="Username" required>
  <div id="usernameError" class="error"></div>
</div>

<div class="input-box">
  <input type="password" id="password" name="password" placeholder="Password" required>
  <div id="passwordError" class="error"></div>
</div>

<div class="input-box">
  <input type="email" id="email" name="email" placeholder="Email" required>
  <div id="emailError" class="error"></div>
</div>

<div class="input-box">
  <input type="tel" id="contact" name="contact" placeholder="Contact" required>
  <div id="contactError" class="error"></div>
</div>

<button type="submit">Add <?= ucfirst($role) ?></button>
</form>


<script>
function validateForm() {

  var username = document.getElementById("username").value.trim();
  var password = document.getElementById("password").value.trim();
  var email    = document.getElementById("email").value.trim();
  var contact  = document.getElementById("contact").value.trim();

  // clear old errors
  document.getElementById("usernameError").innerHTML = "";
  document.getElementById("passwordError").innerHTML = "";
  document.getElementById("emailError").innerHTML = "";
  document.getElementById("contactError").innerHTML = "";

  // patterns
  var usernamePattern = /^[A-Za-z][A-Za-z0-9]{2,}$/;
  var passwordPattern = /^(?=.*[0-9])(?=.*[@#\$%\^&\*])[A-Za-z0-9@#\$%\^&\*]{6,}$/;
var emailPattern = /^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z]+\.[A-Za-z]{2,}$/;
  var phonePattern    = /^(97|98)[0-9]{8}$/;

  if (!usernamePattern.test(username)) {
    document.getElementById("usernameError").innerHTML =
      "Username must start with a letter and be at least 3 characters";
    return false;
  }

  if (!passwordPattern.test(password)) {
    document.getElementById("passwordError").innerHTML =
      "Password must be 6+ chars with number & symbol";
    return false;
  }

  if (!emailPattern.test(email)) {
    document.getElementById("emailError").innerHTML =
      "Invalid email format";
    return false;
  }

  if (!phonePattern.test(contact)) {
    document.getElementById("contactError").innerHTML =
      "Phone must start with 97 or 98 and be 10 digits";
    return false;
  }

  return true; // allow submit
}
</script>


</body>
</html>
