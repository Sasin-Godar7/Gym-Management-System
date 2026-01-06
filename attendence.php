<?php 
session_start();
require "config.php";

if( !isset($_SESSION['username']) || $_SESSION['role'] !='user') {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$date_today = date("Y-m-d");
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

/* =========================
   MARK TODAY PRESENT
========================= */
if(isset($_POST['mark'])) {
    $check = $conn->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date_today'");
    if($check->num_rows == 0) {
        $conn->query("INSERT INTO attendance(user_id, date, status) VALUES($user_id,'$date_today','Present')");
    }
    header("Location: attendance.php");
    exit();
}

/* =========================
   CALENDAR LOGIC
========================= */
$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$number_of_days = date('t', $first_day_of_month);
$date_info = getdate($first_day_of_month);
$day_of_week = $date_info['wday']; // 0 (Sun) to 6 (Sat)

// Fetch attendance for this specific month
$attendanceData = [];
$result = $conn->query("SELECT date FROM attendance WHERE user_id=$user_id AND MONTH(date) = $month AND YEAR(date) = $year AND status='Present'");
while($row = $result->fetch_assoc()) {
    $attendanceData[] = $row['date'];
}

$presentCount = count($attendanceData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elite Calendar | Sasin Gym</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/fav.png">
    <style>
        :root { --primary: #32cc11; --bg: #0a0a0a; }
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Rajdhani', sans-serif; }
        body { background: var(--bg); color: #fff; padding-bottom: 50px; }

      
      /* Navbar */
      .top-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #111;
        padding: 15px 50px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.7);
        height: 80px;
      }

      .top-navbar .logo img {
        width: 180px;
      }

      .nav-right {
        display: flex;
        align-items: center;
        gap: 20px;
      }

      .nav-right .welcome-text {
        font-weight: 700;
        color: #32cc11;
        font-size: 23px;
      }

      .nav-right a {
        color: #fff;
        text-decoration: none;
      }

       .logout-btn {
            background: #32cc11;
            padding: 10px 22px;
            border-radius: 5px; /* Squared off for a more aggressive gym look */
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            color:white;
            text-decoration:none;
        }

        .logout-btn:hover {
            background: #28a70e;
            
        }
      /* Home Icon bigger */
      .home-icon {
        padding: 10px 14px;
        border-radius: 10px;
        transition: 0.2s;
        font-weight: 700;
        text-decoration: none;
        color: #ffffffff;
      }

        /* Header */
        .calendar-header { text-align: center; padding: 40px 20px; }
        .calendar-header h1 { font-family: 'Orbitron'; font-size: 2.5rem; color: #fff; }
        .month-nav { display:flex; align-items:center; justify-content:center; gap:20px; margin-top:10px; font-family:'Orbitron'; color: var(--primary); }
        .month-nav a { color: #fff; text-decoration:none; font-size: 20px; }

        /* Stats Bar */
        .stats-bar { max-width: 800px; margin: 0 auto 30px; display: flex; justify-content: space-around; background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; border: 1px solid #222; }
        .stat-item { text-align: center; }
        .stat-item h3 { font-family: 'Orbitron'; color: var(--primary); font-size: 24px; }
        .stat-item p { font-size: 12px; text-transform: uppercase; color: #888; }

        /* Calendar Grid */
        .calendar-container { max-width: 900px; margin: auto; padding: 20px; background: #111; border-radius: 20px; border: 1px solid #222; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
        .weekday { text-align: center; font-family: 'Orbitron'; color: var(--primary); font-size: 12px; padding-bottom: 10px; }
        
        .day-cell { height: 100px; background: #181818; border-radius: 10px; padding: 10px; position: relative; border: 1px solid #222; transition: 0.3s; }
        .day-cell.empty { background: transparent; border: none; }
        .day-cell.today { border: 2px solid var(--primary); }
        .day-cell .day-num { font-weight: 700; font-size: 18px; color: #444; }
        .day-cell.active .day-num { color: #fff; }

        /* Status Indicators */
        .status-dot { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 5px auto; font-size: 20px; }
        .present-bg { background: rgba(50, 204, 17, 0.15); color: var(--primary); border: 1px solid var(--primary); box-shadow: 0 0 10px rgba(50,204,17,0.2); }
        
        .mark-btn { display: block; margin: 20px auto; background: var(--primary); border: none; padding: 12px 30px; font-family: 'Orbitron'; font-weight: 700; cursor: pointer; border-radius: 5px; }

        @media (max-width: 600px) {
            .day-cell { height: 70px; }
            .day-cell .day-num { font-size: 14px; }
            .status-dot { width: 30px; height: 30px; font-size: 12px; }
        }
    </style>
</head>
<body>
<header class="top-navbar">
      <div class="logo"><img src="Images/fulllogo.png" alt="logo"></div>
      <div class="nav-right"> <span class="welcome-text">Hi,
          <?php echo $_SESSION['username']; ?> !
         </span><a href="user_dashboard.php"><i class="fas fa-home fa-xl home-icon"></i></a>
         <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt" style="margin-right:8px;"></i> Logout
        </a>
        </div>
    </header>

    <div class="calendar-header">
        <h1>WORKOUT LOG</h1>
        <div class="month-nav">
            <a href="?month=<?= ($month==1?12:$month-1) ?>&year=<?= ($month==1?$year-1:$year) ?>"><i class="fas fa-chevron-left"></i></a>
            <span><?= date('F Y', $first_day_of_month) ?></span>
            <a href="?month=<?= ($month==12?1:$month+1) ?>&year=<?= ($month==12?$year+1:$year) ?>"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <h3><?= $presentCount ?></h3>
            <p>Days This Month</p>
        </div>
        <div class="stat-item">
            <h3><?= round(($presentCount/$number_of_days)*100) ?>%</h3>
            <p>Month Score</p>
        </div>
    </div>

    <?php 
    $today_check = $conn->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date_today'");
    if($today_check->num_rows == 0 && $month == date('m')): ?>
        <form method="post"><button class="mark-btn" name="mark">MARK TODAY PRESENT</button></form>
    <?php endif; ?>

    <div class="calendar-container">
        <div class="calendar-grid">
            <div class="weekday">SUN</div><div class="weekday">MON</div><div class="weekday">TUE</div>
            <div class="weekday">WED</div><div class="weekday">THU</div><div class="weekday">FRI</div>
            <div class="weekday">SAT</div>

            <?php
            // Padding for start of month
            for($x = 0; $x < $day_of_week; $x++) { echo '<div class="day-cell empty"></div>'; }

            // Days of month
            for($day = 1; $day <= $number_of_days; $day++) {
                $current_date = sprintf("%04d-%02d-%02d", $year, $month, $day);
                $is_present = in_array($current_date, $attendanceData);
                $is_today = ($current_date == $date_today);
                
                echo '<div class="day-cell '.($is_today ? 'today' : '').' active">';
                echo '<div class="day-num">'.$day.'</div>';
                if($is_present) {
                    echo '<div class="status-dot present-bg"><i class="fas fa-check"></i></div>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>

</body>
</html>