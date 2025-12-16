<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's bookings
$sql = "
SELECT tb.*, u.username AS trainer_name, u.contact
FROM trainer_bookings tb
JOIN users u ON tb.trainer_id = u.id
WHERE tb.user_id = $user_id
ORDER BY tb.booking_date DESC
";
$bookings = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>My Trainer Bookings</title>
<link rel="icon" href="images/fav.png">
<style>
body{background:#0f0f0f;color:#fff;font-family:Poppins}
.container{max-width:1100px;margin:40px auto}
h1{text-align:center;color:#32cc11}
table{width:100%;border-collapse:collapse;margin-top:30px}
th,td{padding:12px;text-align:center}
th{background:#32cc11;color:#000}
tr:nth-child(even){background:#1e1e1e}
.status{font-weight:bold}
.pending{color:#ffcc00}
.approved{color:#4CAF50}
.rejected{color:#f44336}
.back{display:inline-block;margin-top:20px;background:#32cc11;padding:10px 22px;border-radius:20px;color:#000;text-decoration:none;font-weight:600}
</style>
</head>
<body>

<div class="container">
<h1>My Trainer Bookings</h1>

<table>
<tr>
    <th>Trainer</th>
    <th>Contact</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
</tr>

<?php if($bookings->num_rows > 0): ?>
<?php while($row = $bookings->fetch_assoc()): ?>
<tr>
    <td><?= $row['trainer_name'] ?></td>
    <td><?= $row['contact'] ?></td>
    <td><?= $row['booking_date'] ?></td>
    <td><?= $row['booking_time'] ?></td>
    <td class="status <?= strtolower($row['status']) ?>">
        <?= $row['status'] ?>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="5">No bookings yet</td></tr>
<?php endif; ?>
</table>

<a href="user_dashboard.php" class="back">⬅ Back to Dashboard</a>
</div>

</body>
</html>
