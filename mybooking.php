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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bookings | Sasin Elite Gym</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

    :root {
      --accent: #32cc11;
      --glass: rgba(255, 255, 255, 0.03);
      --glass-border: rgba(255, 255, 255, 0.08);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

    body {
      background: #050505;
      color: #fff;
      min-height: 100vh;
      background-image: radial-gradient(circle at 10% 20%, rgba(50, 204, 17, 0.05) 0%, transparent 40%);
    }

    /* Navbar */
    .top-navbar {
      display: flex; justify-content: space-between; align-items: center;
      padding: 0 60px; height: 90px;
      background: rgba(0,0,0,0.8); backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--glass-border);
      position: sticky; top: 0; z-index: 1000;
    }
    .logo img { height: 50px; }

    .nav-right { display: flex; align-items: center; gap: 25px; }
    .user-info { color: var(--accent); font-weight: 700; font-size: 14px; letter-spacing: 0.5px; }

    .logout-btn {
      color: #ff4444; text-decoration: none; font-size: 13px; font-weight: 700;
      padding: 8px 18px; border-radius: 10px; border: 1px solid rgba(255,68,68,0.2);
      transition: 0.3s;
    }
    .logout-btn:hover { background: rgba(255,68,68,0.1); }

    /* Main Content */
    .container { max-width: 1100px; margin: 60px auto; padding: 0 20px; }

    .hero-section { text-align: center; margin-bottom: 50px; }
    .hero-section h1 { font-size: 42px; font-weight: 800; margin-bottom: 10px; }
    .hero-section h1 span { color: var(--accent); }
    .hero-section p { color: #888; font-size: 16px; }

    /* Glass Table Card */
    .table-card {
      background: var(--glass);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 30px;
      backdrop-filter: blur(15px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    
    th {
      text-align: left; padding: 20px;
      color: var(--accent); font-size: 12px;
      text-transform: uppercase; letter-spacing: 1.5px;
      border-bottom: 1px solid var(--glass-border);
    }

    td { padding: 20px; font-size: 15px; color: #ddd; border-bottom: 1px solid rgba(255,255,255,0.03); }

    tr:last-child td { border: none; }
    tr:hover td { color: #fff; background: rgba(255,255,255,0.02); }

    /* Status Badges */
    .status {
      padding: 6px 16px; border-radius: 50px; font-size: 11px;
      font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .status.pending { 
        background: rgba(255, 193, 7, 0.1); color: #ffc107; 
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    .status.approved { 
        background: rgba(50, 204, 17, 0.1); color: #32cc11; 
        border: 1px solid rgba(50, 204, 17, 0.3);
        box-shadow: 0 0 15px rgba(50, 204, 17, 0.2);
    }
    .status.rejected { 
        background: rgba(255, 68, 68, 0.1); color: #ff4444; 
        border: 1px solid rgba(255, 68, 68, 0.3);
    }

    /* Actions */
    .btn-container { margin-top: 30px; text-align: center; }
    .back-dashboard {
      display: inline-flex; align-items: center; gap: 10px;
      color: #fff; text-decoration: none; font-weight: 700;
      background: var(--accent); color: #000;
      padding: 14px 30px; border-radius: 12px;
      transition: 0.3s;
    }
    .back-dashboard:hover { transform: scale(1.05); background: #fff; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .top-navbar { padding: 0 20px; }
      th, td { padding: 12px; font-size: 13px; }
      .hero-section h1 { font-size: 30px; }
      .table-card { padding: 15px; border-radius: 15px; }
      table, thead, tbody, th, td, tr { display: block; }
      thead tr { position: absolute; top: -9999px; left: -9999px; }
      tr { margin-bottom: 15px; border: 1px solid var(--glass-border); border-radius: 15px; padding: 10px; }
      td { border: none; position: relative; padding-left: 50%; text-align: right; }
      td:before {
        content: attr(data-label); position: absolute; left: 15px; width: 45%;
        text-align: left; font-weight: 700; color: var(--accent);
      }
    }
  </style>
</head>
<body>

<header class="top-navbar">
  <div class="logo"><img src="Images/fulllogo.png" alt="Sasin Elite"></div>
  <div class="nav-right">
    <span class="user-info"><i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?></span>
    <a href="user_dashboard.php" style="color:#fff;"><i class="fas fa-house-user fa-lg"></i></a>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</header>

<div class="container">
  <div class="hero-section">
    <h1>Booking <span>History</span></h1>
    <p>Monitor your training schedule and request status</p>
  </div>

  <div class="table-card">
    <table>
      <thead>
        <tr>
          <th>Trainer</th>
          <th>Contact</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if($bookings->num_rows > 0): ?>
          <?php while($row = $bookings->fetch_assoc()): ?>
            <tr>
              <td data-label="Trainer">
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-user-tie" style="color:var(--accent)"></i>
                    <strong><?= $row['trainer_name'] ?></strong>
                </div>
              </td>
              <td data-label="Contact"><?= $row['contact'] ?></td>
              <td data-label="Date"><i class="far fa-calendar-alt" style="margin-right:8px; opacity:0.6"></i><?= $row['booking_date'] ?></td>
              <td data-label="Time"><i class="far fa-clock" style="margin-right:8px; opacity:0.6"></i><?= $row['booking_time'] ?></td>
              <td data-label="Status">
                <span class="status <?= strtolower($row['status']) ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align:center; padding: 40px; color:#666;">
                <i class="fas fa-calendar-times fa-3x" style="margin-bottom:15px; display:block; opacity:0.3"></i>
                No bookings found. Start your journey today!
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="btn-container">
    <a href="user_dashboard.php" class="back-dashboard">
      <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
  </div>
</div>

</body>
</html>