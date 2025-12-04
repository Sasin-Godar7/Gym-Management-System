<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard | Sasin Elite Gym</title>
<link rel="stylesheet" href="user_dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<header class="top-navbar">
    <div class="logo">
        <img src="Images/fulllogo.png" alt="logo">
    </div>
    <div class="nav-right">
        <span class="welcome-text">Hi, <?php echo $_SESSION['username']; ?>!</span>
        <a href="my_account.php"><i class="fas fa-user-circle fa-2x"></i></a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <ul>
            <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            <li><a href="diet.php"><i class="fas fa-apple-alt"></i> Diet Plan</a></li>
            <li><a href="book_trainer.php"><i class="fas fa-user-friends"></i> Book Trainer</a></li>
            <li><a href="#classes"><i class="fas fa-dumbbell"></i> Classes</a></li>
            <li><a href="#subscription"><i class="fas fa-credit-card"></i> Subscription</a></li>
        </ul>
    </aside>

    <!-- MAIN -->
    <main class="dashboard-main">

        <!-- Welcome Section -->
        <section class="welcome-section">
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
            <p>Keep track of your fitness journey and stay motivated.</p>
        </section>

        <!-- Quick Action Cards -->
        <section class="quick-actions">
            <div class="action-card" onclick="window.location.href='attendance.php'">
                <i class="fas fa-calendar-check"></i>
                <h3>Attendance</h3>
            </div>
            <div class="action-card" onclick="window.location.href='diet.php'">
                <i class="fas fa-apple-alt"></i>
                <h3>Diet Plan</h3>
            </div>
            <div class="action-card" onclick="window.location.href='book_trainer.php'">
                <i class="fas fa-user-friends"></i>
                <h3>Book Trainer</h3>
            </div>
            <div class="action-card" onclick="window.location.href='#classes'">
                <i class="fas fa-dumbbell"></i>
                <h3>Classes</h3>
            </div>
        </section>

        <!-- Classes -->
        <section class="classes-section" id="classes">
            <h2>Our Classes</h2>
            <div class="class-grid">
                <div class="class-card"><img src="Images/cardio.jpg"><h4>Cardio</h4></div>
                <div class="class-card"><img src="Images/strength training.jpg"><h4>Strength</h4></div>
                <div class="class-card"><img src="Images/yoga.jpg"><h4>Yoga</h4></div>
                <div class="class-card"><img src="Images/zumba.jpg"><h4>Zumba</h4></div>
            </div>
        </section>

        <!-- Subscription -->
        <section class="subscription-section" id="subscription">
            <h2>Subscription Plans</h2>
            <div class="sub-grid">
                <div class="sub-card">Basic <span>Rs 1,200 / month</span></div>
                <div class="sub-card popular">Standard <span>Rs 2,500 / month</span></div>
                <div class="sub-card">Premium <span>Rs 4,000 / month</span></div>
            </div>
        </section>

    </main>
</div>

</body>
</html>
