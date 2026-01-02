<?php session_start();
require "config.php";

if( !isset($_SESSION['username']) || $_SESSION['role'] !='user') {
    header("Location: login.php");
    exit();
}

$user_id =intval($_SESSION['user_id']);
$date =date("Y-m-d");

/* =========================
   MARK TODAY PRESENT
========================= */
if(isset($_POST['mark'])) {
    $check =$conn->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date'");

    if($check->num_rows ==0) {
        $conn->query("INSERT INTO attendance(user_id, date, status) VALUES($user_id,'$date','Present')");
    }

    header("Location: attendance.php");
    exit();
}

/* =========================
   FETCH ATTENDANCE DATA
========================= */
$attendanceData =[];
$result =$conn->query("SELECT date, status FROM attendance WHERE user_id=$user_id");

while($row =$result->fetch_assoc()) {
    $attendanceData[$row['date']]=$row['status'];
}

/* =========================
   LAST 30 DAYS ARRAY
========================= */
$dates =[];

for($i=0; $i<30; $i++) {
    $dates[]=date("Y-m-d", strtotime("-$i days"));
}

?>

<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Attendance | Sasin Elite Gym</title>
        <link rel="icon" type="image/png" href="Images/fav.png">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Poppins, sans-serif;
            }

            body {
                background: #0b0b0b;
                color: #fff
            }

            /* Navbar */
            .navbar {
                height: 80px;
                background: #111;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 50px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, .8)
            }

            .navbar img {
                width: 170px
            }

            .nav-right {
                display: flex;
                align-items: center;
                gap: 20px
            }

            .nav-right span {
                color: #32cc11;
                font-weight: 600
            }

            .logout {
                background: #32cc11;
                color: #ffffffff;
                padding: 8px 22px;
                border-radius: 25px;
                text-decoration: none;
                font-weight: 600
            }

            .home-icon {
                color: white
            }

            /* Header */
            .header {
                text-align: center;
                padding: 50px 20px
            }

            .header h1 {
                font-size: 40px
            }

            .header p {
                color: #aaa
            }

            /* Button */
            .mark-btn {
                display: block;
                margin: 20px auto 40px;
                padding: 14px 35px;
                border: none;
                border-radius: 30px;
                background: linear-gradient(135deg, #32cc11, #6aff3d);
                font-size: 16px;
                font-weight: 700;
                cursor: pointer
            }

            /* Attendance Grid */
            .attendance-wrapper {
                max-width: 1200px;
                margin: auto;
                padding: 0 20px
            }

            .attendance-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 20px
            }

            /* Card */
            .att-card {
                background: #161616;
                border-radius: 18px;
                padding: 20px;
                text-align: center;
                box-shadow: 0 6px 15px rgba(0, 0, 0, .5);
                transition: .3s
            }

            .att-card:hover {
                transform: translateY(-6px)
            }

            .day {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 8px
            }

            .date {
                color: #aaa;
                font-size: 14px;
                margin-bottom: 15px
            }

            /* Status */
            .badge {
                padding: 8px 18px;
                border-radius: 20px;
                font-weight: 700;
                font-size: 14px;
                display: inline-block
            }

            .present {
                background: #32cc11;
                color: #000
            }

            .absent {
                background: #ff3b3b
            }

            /* Info */
            .info {
                text-align: center;
                color: #32cc11;
                font-weight: 600;
                margin-bottom: 30px
            }
        </style>
    </head>

    <body>
        <!-- Navbar -->
            <div class="navbar"><img src="Images/fulllogo.png">
                <div class="nav-right"><span>Hi,
                        <?=$_SESSION['username'] ?>
                    </span><a href="user_dashboard.php"><i class="fas fa-home fa-xl home-icon"></i></a><a
                        href="logout.php" class="logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:6px;">
        <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.104 0 2-.896 2-2V5c0-1.104-.896-2-2-2z"/>
    </svg>Logout</a></div>
            </div>
            <!-- Header -->
                <div class="header">
                    <h1>Attendance Record</h1>
                    <p>Your last 30 days gym attendance</p>
                </div>
                <?php $today_check =$conn->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date'");
                 if($today_check->num_rows ==0): ?>
                <form method="post"><button class="mark-btn" name="mark">Mark Today Present</button></form>
                <?php else: ?>
                <p class="info">✅ Today's attendance already marked</p>
                <?php endif;
?>
                <!-- Attendance Cards -->
                    <div class="attendance-wrapper">
                        <div class="attendance-grid">
                            <?php foreach($dates as $d): $status =isset($attendanceData[$d]) ? $attendanceData[$d] : "Absent";
?>
                            <div class="att-card">
                                <div class="day">
                                    <?=date("l", strtotime($d)) ?>
                                </div>
                                <div class="date">
                                    <?=$d ?>
                                </div><span class="badge <?= strtolower($status) ?>">
                                    <?=$status ?>
                                </span>
                            </div>
                            <?php endforeach;
?>
                        </div>
                    </div>
    </body>

    </html>