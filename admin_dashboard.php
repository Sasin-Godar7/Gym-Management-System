<?php
session_start();
require_once "config.php"; // Database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gym Admin Dashboard</title>
<link rel="icon" href="images/fav.png">
<style>
/* GENERAL */
* { margin:0; padding:0; box-sizing:border-box; font-family:Poppins,sans-serif; }
body { display:flex; background:#0b1110; color:white; }

/* SIDEBAR */
.sidebar {
  width:240px;
  height:100vh;
  background:#0f1b1a;
  padding:25px 0;
  position:fixed;
  border-right:1px solid #1e2e2c;
}
.sidebar h2 { text-align:center; color:#00ff84; margin-bottom:35px; }
.sidebar a {
  display:block;
  padding:14px 25px;
  color:#c8d3ce;
  text-decoration:none;
  font-size:16px;
  transition:0.3s;
}
.sidebar a:hover, .sidebar a.active { background:#00ff84; color:#000; font-weight:600; }

/* TOPBAR */
.topbar {
  margin-left:240px;
  width:calc(100% - 240px);
  padding:18px 28px;
  background:#0f1b1a;
  border-bottom:1px solid #1e2e2c;
  display:flex;
  justify-content:space-between;
  align-items:center;
  position:fixed;
  top:0;
  z-index:5;
}
.topbar h1 { color:#00ff84; font-size:20px; }
.profile { display:flex; align-items:center; gap:10px; }
.profile img { width:40px; height:40px; border-radius:50%; border:2px solid #00ff84; }
.profile span { color:#d7e5df; }

/* CONTENT */
.content {
  margin-top:80px;
  margin-left:240px;
  padding:30px;
}
.slide { display:none; animation:fade 0.3s; }
.slide.active { display:block; }
@keyframes fade { from{opacity:0;} to{opacity:1;} }

/* CARDS */
.cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-top:20px; }
.card {
  background:#121f1d;
  padding:25px;
  border-radius:12px;
  border:1px solid #1e2e2c;
  text-align:center;
  transition:0.25s;
  cursor:pointer;
}
.card:hover { transform:translateY(-5px); box-shadow:0 4px 15px rgba(0,255,132,0.25); }
.card h3 { color:#00ff84; margin-bottom:10px; }
.card p { font-size:24px; font-weight:600; }

/* MEMBERS TABLE */
.members-table {
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
  background:#121f1d;
  color:#fff;
}
.members-table th, .members-table td {
  padding:12px 15px;
  border-bottom:1px solid #1e2e2c;
  text-align:left;
}
.members-table th {
  background:#0f1b1a;
  color:#00ff84;
}
.members-table tr:hover { background:#1e2e2c; }
.members-table tr:nth-child(even) { background:#141f1c; }

/* GRAPH CANVAS */
.graphBox {
  margin-top:30px;
  background:#121f1d;
  height:300px;
  border-radius:12px;
  border:1px solid #1e2e2c;
  padding:20px;
}
canvas { width:100%; height:100%; }
</style>
</head>
<body>

<div class="sidebar">
  <h2>GYM ADMIN</h2>
  <a class="active" onclick="showSlide('dashboard')">Dashboard</a>
  <a onclick="showSlide('members')">Members</a>
  <a onclick="showSlide('trainers')">Trainers</a>
  <a onclick="showSlide('attendance')">Attendance</a>
  <a onclick="showSlide('payments')">Payments</a>
  <a onclick="showSlide('settings')">Settings</a>
</div>

<div class="topbar">
  <h1>Hello, Admin 👋</h1>
  <div class="profile">
    <img src="https://i.ibb.co/5xKq2Z3/user.png">
    <span>admin@gmail.com</span>
  </div>
</div>

<div class="content">

  <!-- DASHBOARD -->
  <div class="slide active" id="dashboard">
    <h1>Dashboard Overview</h1>

    <?php
      $members = $conn->query("SELECT COUNT(*) AS total_members FROM users WHERE role='user'");
      $memberCount = $members->fetch_assoc()['total_members'];

      $trainers = $conn->query("SELECT COUNT(*) AS total_trainers FROM users WHERE role='trainer'");
      $trainerCount = $trainers->fetch_assoc()['total_trainers'];

      $todayAttendance = 92;
      $totalRevenue = 280000;
    ?>

    <div class="cards">
      <div class="card" onclick="showSlide('members')">
        <h3>Total Members</h3>
        <p><?= $memberCount ?></p>
      </div>
      <div class="card">
        <h3>Trainers</h3>
        <p><?= $trainerCount ?></p>
      </div>
      <div class="card">
        <h3>Today Attendance</h3>
        <p><?= $todayAttendance ?></p>
      </div>
      <div class="card">
        <h3>Total Revenue</h3>
        <p>Rs. <?= number_format($totalRevenue) ?></p>
      </div>
    </div>

    <div class="graphBox">
      <canvas id="myGraph"></canvas>
    </div>
  </div>

  <!-- MEMBERS -->
  <div class="slide" id="members">
    <h1>Members List</h1>

    <table class="members-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Contact</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $membersList = $conn->query("SELECT * FROM users WHERE role='user'");
          if($membersList->num_rows > 0){
            while($row = $membersList->fetch_assoc()){
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['username']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['contact']}</td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='4'>No members found</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- OTHER SLIDES -->
  <div class="slide" id="trainers"><h1>Trainer Management</h1></div>
  <div class="slide" id="attendance"><h1>Attendance Panel</h1></div>
  <div class="slide" id="payments"><h1>Payment History</h1></div>
  <div class="slide" id="settings"><h1>Admin Settings</h1></div>

</div>

<script>
function showSlide(id){
    document.querySelectorAll(".slide").forEach(s => s.classList.remove("active"));
    document.getElementById(id).classList.add("active");

    document.querySelectorAll(".sidebar a").forEach(a => a.classList.remove("active"));
    if(id === 'members'){
        document.querySelector(".sidebar a:nth-child(2)").classList.add("active");
    }
}

/* Simple Graph */
let c = document.getElementById("myGraph");
let ctx = c.getContext("2d");
let data = [40, 75, 50, 90, 60, 110];
ctx.strokeStyle = "#00ff84";
ctx.lineWidth = 3;
ctx.beginPath();
let gap = c.width / (data.length + 1);
for(let i=0; i<data.length; i++){
  let x = (i+1)*gap;
  let y = c.height - data[i];
  if(i===0) ctx.moveTo(x,y);
  else ctx.lineTo(x,y);
}
ctx.stroke();
</script>

</body>
</html>
