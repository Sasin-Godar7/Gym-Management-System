<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Trainer Bookings | Sasin Elite Gym</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="images/fav.png">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
/* ===== Global ===== */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#0f0f0f;color:#fff;line-height:1.6;}

/* ===== Navbar ===== */
.navbar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:15px 50px;
  background:#111;
  position:sticky;
  top:0;
  z-index:100;
  box-shadow:0 2px 10px rgba(0,0,0,.7);
  height:80px;
}
.navbar img{width:180px;}
.nav-right{display:flex;align-items:center;gap:20px;}
.nav-right span{color:#32cc11;font-weight:600;font-size:16px;}
.nav-right a{color:#fff;text-decoration:none;font-weight:500;}
.logout{background:#32cc11;color:#fff;padding:8px 22px;border-radius:25px;transition:0.3s;}
.logout:hover{background:#2bb40f;}

/* ===== Hero ===== */
.hero{
  max-width:1100px;
  margin:40px auto 20px;
  text-align:center;
}
.hero h1{font-size:36px;color:#32cc11;margin-bottom:8px;}
.hero p{color:#ccc;font-size:16px;}

/* ===== Container ===== */
.container{
  max-width:1100px;
  margin:30px auto 60px;
  padding:0 20px;
}

/* ===== Table ===== */
.table-wrapper{
  overflow-x:auto;
  background:#1e1e1e;
  border-radius:12px;
  box-shadow:0 5px 20px rgba(50,204,17,0.15);
}
table{
  width:100%;
  border-collapse:collapse;
}
th,td{
  padding:14px;
  text-align:center;
}
th{
  background:#32cc11;
  color:#000;
  font-weight:600;
}
tr{
  border-bottom:1px solid #333;
}
tr:nth-child(even){background:#292929;}
tr:hover{background:#323232;transition:0.3s;}
.status{
  font-weight:600;
  padding:6px 14px;
  border-radius:20px;
  font-size:13px;
  display:inline-block;
}
.pending{background:#ffcc00;color:#000;}
.approved{background:#4CAF50;color:#fff;}
.rejected{background:#f44336;color:#fff;}

/* Back Button */
.back-btn{
  display:inline-block;
  margin-top:20px;
  background:#32cc11;color:#000;
  padding:10px 25px;
  border-radius:25px;
  text-decoration:none;
  font-weight:600;
  transition:0.3s;
}
.back-btn:hover{background:#2bb40f;}

/* ===== Responsive ===== */
@media(max-width:768px){
  .navbar{flex-direction:column;gap:10px;padding:15px;}
  .hero h1{font-size:28px;}
}
</style>
</head>
<body>

<!-- Navbar -->
<header class="navbar">
  <img src="Images/fulllogo.png" alt="Sasin Elite Gym Logo">
  <div class="nav-right">
    <span>Hi, <?= $_SESSION['username'] ?>!</span>
    <a href="user_dashboard.php"><i class="fas fa-home"></i></a>
    <a href="logout.php" class="logout">Logout</a>
  </div>
</header>

<!-- Hero -->
<section class="hero">
  <h1>My Trainer Bookings</h1>
  <p>Check your trainer booking status at a glance</p>
</section>

<!-- Table -->
<div class="container">
  <div class="table-wrapper">
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
            <td>
              <span class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></span>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No bookings yet</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <a href="user_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
