
<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'trainer'){
    header("Location: login.php");
    exit();
}

// Fetch all trainer bookings assigned to this trainer
$trainer_id = $_SESSION['user_id'];
$bookings = $conn->query("SELECT tb.*, u.username, u.email, u.contact 
                          FROM trainer_bookings tb 
                          JOIN users u ON tb.user_id = u.id
                          WHERE tb.trainer_id = $trainer_id
                          ORDER BY tb.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trainer Dashboard | Sasin Elite Gym</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="images/fav.png">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#0f0f0f;color:#fff;line-height:1.6;}

/* Navbar */
.top-navbar{
  display:flex;justify-content:space-between;align-items:center;
  background:#111;padding:15px 50px;position:sticky;top:0;z-index:100;
  box-shadow:0 2px 15px rgba(0,0,0,0.7);height:80px;
}
.top-navbar .logo img{width:180px;}
.nav-right{display:flex;align-items:center;gap:20px;}
.nav-right .welcome-text{font-weight:600;color:#32cc11;font-size:18px;}
.nav-right a{color:#fff;text-decoration:none;}
.logout-btn{background:#32cc11;padding:8px 22px;border-radius:25px;font-weight:600;transition:0.3s;}
.logout-btn:hover{background:#33ed40;}

/* Hero Section */
.hero{text-align:center;padding:60px 20px;margin:30px auto;max-width:1200px;}
.hero h1{font-size:42px;color:#fff;margin-bottom:15px;}
.hero p{font-size:20px;color:#ccc;}

/* Booking Table */
.table-container{max-width:1200px;margin:50px auto;padding:0 20px;overflow-x:auto;}
table{width:100%;border-collapse:collapse;background:#1e1e1e;border-radius:10px;overflow:hidden;box-shadow:0 5px 15px rgba(50,204,17,0.2);}
th,td{padding:12px 15px;text-align:left;}
th{background:#32cc11;color:#000;}
td{color:#fff;border-bottom:1px solid #333;}
tr:hover{background:#2a2a2a;}

/* Status Buttons */
.status-btn{padding:6px 12px;border:none;border-radius:8px;font-weight:600;cursor:pointer;transition:0.3s;}
.approve{background:#4CAF50;color:#fff;}
.reject{background:#f44336;color:#fff;}
.status-btn:hover{opacity:0.8;}

/* Responsive */
@media(max-width:768px){.top-navbar{flex-direction:column;gap:10px;padding:15px 20px;}}
</style>
</head>
<body>

<header class="top-navbar">
  <div class="logo"><img src="Images/fulllogo.png" alt="logo"></div>
  <div class="nav-right">
      <span class="welcome-text">Hi, <?php echo $_SESSION['username']; ?>!</span>
      <a href="trainer_dashboard.php"><i class="fas fa-home fa-2x"></i></a>
      <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</header>

<section class="hero">
  <h1>Trainer Dashboard</h1>
  <p>Manage your user bookings efficiently</p>
</section>

<div class="table-container">
<table>
<tr>
    <th>User</th>
    <th>Email</th>
    <th>Contact</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php while($row = $bookings->fetch_assoc()): ?>
<tr>
    <td><?= $row['username'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['contact'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
      <?php if($row['status'] == 'Pending'): ?>
        <a href="trainer_action.php?id=<?= $row['id'] ?>&action=accept" class="status-btn approve">Approve</a>
        <a href="trainer_action.php?id=<?= $row['id'] ?>&action=reject" class="status-btn reject">Reject</a>
      <?php else: ?>
        <span><?= $row['status'] ?></span>
      <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

</body>
</html>
