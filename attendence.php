<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$date = date("Y-m-d");

// Mark today's attendance
if(isset($_POST['mark'])){
    $check = $conn->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date'");
    if($check->num_rows == 0){
        $conn->query("INSERT INTO attendance(user_id, date, status) VALUES($user_id, '$date', 'Present')");
        header("Location: attendance.php");
        exit();
    }
}

// Fetch last 30 days attendance
$attendance = $conn->query("SELECT * FROM attendance WHERE user_id=$user_id ORDER BY date DESC LIMIT 30");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance | Sasin Elite Gym</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif;}
body{background:#0b0b0b;color:#fff}

/* Navbar */
.navbar{
    height:80px;background:#111;display:flex;align-items:center;
    justify-content:space-between;padding:0 50px;
    box-shadow:0 4px 20px rgba(0,0,0,.8)
}
.navbar img{width:170px}
.nav-right{display:flex;align-items:center;gap:20px}
.nav-right span{color:#32cc11;font-weight:600}
.logout{background:#32cc11;color:#000;padding:8px 22px;border-radius:25px;text-decoration:none;font-weight:600}

/* Header */
.header{
    text-align:center;padding:50px 20px
}
.header h1{font-size:40px}
.header p{color:#aaa}

/* Button */
.mark-btn{
    display:block;margin:20px auto 40px;
    padding:14px 35px;border:none;border-radius:30px;
    background:linear-gradient(135deg,#32cc11,#6aff3d);
    font-size:16px;font-weight:700;cursor:pointer
}

/* Attendance Grid */
.attendance-wrapper{
    max-width:1200px;margin:auto;padding:0 20px
}
.attendance-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:20px
}

/* Card */
.att-card{
    background:#161616;border-radius:18px;
    padding:20px;text-align:center;
    box-shadow:0 6px 15px rgba(0,0,0,.5);
    transition:.3s
}
.att-card:hover{transform:translateY(-6px)}

.day{
    font-size:18px;font-weight:600;margin-bottom:8px
}
.date{
    color:#aaa;font-size:14px;margin-bottom:15px
}

/* Status badge */
.badge{
    padding:8px 18px;border-radius:20px;
    font-weight:700;font-size:14px;
    display:inline-block
}
.present{background:#32cc11;color:#000}
.absent{background:#ff3b3b}

/* Info */
.info{
    text-align:center;color:#32cc11;
    font-weight:600;margin-bottom:30px
}
</style>
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <img src="Images/fulllogo.png">
    <div class="nav-right">
        <span>Hi, <?= $_SESSION['username'] ?></span>
        <a href="user_dashboard.php"><i class="fas fa-home fa-xl"></i></a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<!-- Header -->
<div class="header">
    <h1>Attendance Record</h1>
    <p>Your last 30 days gym attendance</p>
</div>

<?php 
$today_check = $conn->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date'");
if($today_check->num_rows == 0): ?>
<form method="post">
    <button class="mark-btn" name="mark">Mark Today Present</button>
</form>
<?php else: ?>
<p class="info">✅ Today's attendance already marked</p>
<?php endif; ?>

<!-- Attendance Cards -->
<div class="attendance-wrapper">
    <div class="attendance-grid">
        <?php while($row = $attendance->fetch_assoc()): ?>
        <div class="att-card">
            <div class="day"><?= date("l",strtotime($row['date'])) ?></div>
            <div class="date"><?= $row['date'] ?></div>
            <span class="badge <?= strtolower($row['status']) ?>">
                <?= $row['status'] ?>
            </span>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
