<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php"); // admin na bhaye login page
    exit();
}
?>

<h1>Welcome Admin <?php echo $_SESSION['username']; ?></h1>
<a href="logout.php">Logout</a>

<!-- Example: Show all users -->
<?php
require "connect.php";
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Contact</th><th>Role</th></tr>";
while($row = $result->fetch_assoc()){
    echo "<tr>
        <td>".$row['id']."</td>
        <td>".$row['username']."</td>
        <td>".$row['email']."</td>
        <td>".$row['contact']."</td>
        <td>".$row['role']."</td>
    </tr>";
}
echo "</table>";
?>
