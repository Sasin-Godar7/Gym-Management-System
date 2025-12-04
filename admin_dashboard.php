<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gym Admin Dashboard</title>
  <style>
    *{
      margin: 0; padding: 0;
      box-sizing: border-box;
      font-family: Poppins, sans-serif;
    }

    body{
      display: flex;
      background: #0b1110; 
      color: white;
    }

    /* Sidebar */
    .sidebar{
      width: 240px;
      height: 100vh;
      background: #0f1b1a;
      padding: 25px 0;
      border-right: 1px solid #1e2e2c;
      position: fixed;
    }

    .sidebar h2{
      text-align: center;
      font-size: 22px;
      margin-bottom: 35px;
      color: #00ff84;
    }

    .sidebar a{
      display: block;
      padding: 14px 25px;
      color: #c8d3ce;
      text-decoration: none;
      transition: 0.25s;
      font-size: 15px;
    }

    .sidebar a:hover,
    .sidebar a.active{
      background: #00ff84;
      color: #000;
    }

    /* Topbar */
    .topbar{
      margin-left: 240px;
      width: calc(100% - 240px);
      padding: 18px 28px;
      background: #0f1b1a;
      border-bottom: 1px solid #1e2e2c;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      z-index: 5;
    }

    .topbar h1{
      font-size: 20px;
      color: #00ff84;
    }

    .profile{
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .profile img{
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #00ff84;
    }

    .profile span{
      font-size: 15px;
      color: #d7e5df;
    }

    /* Content */
    .content{
      margin-top: 80px;
      margin-left: 240px;
      padding: 30px;
    }

    .slide{
      display: none;
      animation: fade 0.3s;
    }

    .slide.active{
      display: block;
    }

    @keyframes fade{
      from{opacity: 0;}
      to{opacity: 1;}
    }

    /* Cards */
    .cards{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .card{
      background: #121f1d;
      padding: 25px;
      border-radius: 12px;
      border: 1px solid #1e2e2c;
      transition: 0.25s;
    }

    .card:hover{
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0,255,132,0.25);
    }

    .card h3{
      font-size: 20px;
      margin-bottom: 10px;
      color: #00ff84;
    }

    .graphBox{
      margin-top: 30px;
      background: #121f1d;
      height: 300px;
      border-radius: 12px;
      border: 1px solid #1e2e2c;
      padding: 20px;
    }

    canvas{
      width: 100%;
      height: 100%;
    }
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

  <!-- Dashboard -->
  <div class="slide active" id="dashboard">
    <h1>Dashboard Overview</h1>

    <div class="cards">
      <div class="card"><h3>Total Members</h3><p>156 Active</p></div>
      <div class="card"><h3>Trainers</h3><p>08 Available</p></div>
      <div class="card"><h3>Today Attendance</h3><p>92 Checked-in</p></div>
      <div class="card"><h3>Total Revenue</h3><p>Rs. 280,000</p></div>
    </div>

    <div class="graphBox">
      <canvas id="myGraph"></canvas>
    </div>

  </div>

  <!-- Members -->
  <div class="slide" id="members">
    <h1>Members List</h1>
  </div>

  <!-- Trainers -->
  <div class="slide" id="trainers">
    <h1>Trainer Management</h1>
  </div>

  <!-- Attendance -->
  <div class="slide" id="attendance">
    <h1>Attendance Panel</h1>
  </div>

  <!-- Payments -->
  <div class="slide" id="payments">
    <h1>Payment History</h1>
  </div>

  <!-- Settings -->
  <div class="slide" id="settings">
    <h1>Admin Settings</h1>
  </div>

</div>

<script>
function showSlide(id){
    document.querySelectorAll(".slide").forEach(s => s.classList.remove("active"));
    document.getElementById(id).classList.add("active");

    document.querySelectorAll(".sidebar a").forEach(a => a.classList.remove("active"));
    event.target.classList.add("active");
}

/* Simple Graph */
let c = document.getElementById("myGraph");
let ctx = c.getContext("2d");

let data = [40, 75, 50, 90, 60, 110]; // Fake attendance data

ctx.strokeStyle = "#00ff84";
ctx.lineWidth = 3;
ctx.beginPath();

let gap = c.width / (data.length + 1);

for(let i=0; i<data.length; i++){
  let x = (i+1) * gap;
  let y = c.height - data[i];

  if(i===0) ctx.moveTo(x,y);
  else ctx.lineTo(x,y);
}

ctx.stroke();
</script>

</body>
</html>
