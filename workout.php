<?php
// Ensure session is started to avoid errors with $_SESSION['username']
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Fallback for username if session isn't set for testing
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Athlete';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="images/fav.png">
    
    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            width: 100%;
            font-family: 'Rajdhani', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)),
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #fff;
            overflow-x: hidden;
        }

        /* --- Navbar --- */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* background: rgba(0, 0, 0, 0.95); */
            /* backdrop-filter: blur(10px); */
            padding: 0 50px;
            position: fixed; /* Changed to fixed for better UX */
            width: 100%;
            top: 0;
            z-index: 1000;
            /* box-shadow: 0 2px 15px rgba(0,0,0,0.7); */
            height: 80px;
        }

        .top-navbar .logo img {
            width: 160px;
            height: auto;
            display: block;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-right .welcome-text {
            font-weight: 600;
            color: #32cc11;
            font-size: 18px;
          
        }

        .nav-right a {
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .home-icon:hover {
            color: #32cc11;
            transform: scale(1.1);
        }

               .logout-btn {
            background: #32cc11;
            padding: 10px 22px;
            border-radius: 4px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #28a70e;
            box-shadow: 0 0 15px rgba(50, 204, 17, 0.4);
        }
        /* MAIN CONTENT SPACING */
        .main-content {
            padding: 120px 20px 60px; /* Top padding accounts for fixed navbar */
        }

        /* HEADER SECTION */
        .workout-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .workout-header span {
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            letter-spacing: 8px;
            color: #32cc11;
            display: block;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .workout-title {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(2rem, 6vw, 3.5rem);
            font-weight: 700;
            letter-spacing: 2px;
            color: #fff;
            text-shadow: 0 0 20px rgba(50, 204, 17, 0.3);
            margin-bottom: 15px;
        }

        .workout-sub {
            font-size: 1.1rem;
            color: #ccc;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 25px;
            display: inline-block;
            border-left: 4px solid #32cc11;
            border-radius: 2px;
        }

        /* GRID SYSTEM */
        .workout-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* WORKOUT CARDS */
        .workout-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.47);
            border-radius: 12px;
            padding: 35px;
            transition: 0.4s;
            position: relative;
        }

        .workout-box:hover {
            transform: translateY(-8px);
            border-color: #32cc11;
            background: rgba(30, 30, 30, 0.8);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .workout-box h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.85rem;
            letter-spacing: 3px;
            color: #32cc11;
            margin-bottom: 8px;
        }

        .workout-box h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(50, 204, 17, 0.3);
            padding-bottom: 10px;
        }

        .workout-box ul {
            list-style: none;
        }

        .workout-box ul li {
            padding: 12px 0;
            font-size: 1.1rem;
            color: #eee;
            display: flex;
            align-items: center;
        }

        .workout-box ul li::before {
            content: "\f44b"; /* FontAwesome Dumbbell icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 15px;
            color: #32cc11;
            font-size: 0.9rem;
        }

        /* REST DAY SPECIAL CARD */
        .rest-day {
            background: linear-gradient(145deg, rgba(50, 204, 17, 0.1), rgba(0, 0, 0, 0.5));
            border: 2px dashed rgba(50, 204, 17, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
          
        }

        .rest-day h2 {
            border-bottom: none;
            color: #32cc11;
            font-size: 2.8rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .top-navbar { padding: 0 20px; }
            .nav-right .welcome-text { display: none; } /* Hide greeting on mobile */
            .workout-grid { grid-template-columns: 1fr; }
        }
    </style>
    <title>Elite Weekly Workout</title>
</head>
<body>

<header class="top-navbar">
    <div class="logo">
        <img src="Images/fulllogo.png" alt="logo" onerror="this.src='https://via.placeholder.com/180x50/111/32cc11?text=GYM+LOGO'">
    </div>
    <div class="nav-right">
        <span class="welcome-text">Hi, <?php echo htmlspecialchars($username); ?>!</span>
        <a href="user_dashboard.php"><i class="fas fa-house fa-xl home-icon"></i></a>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt" style="margin-right:8px;"></i> Logout
        </a>
        
    </div>
</header>

<main class="main-content">
    <header class="workout-header">
        <span>Weekly Training Cycle</span>
        <h1 class="workout-title">WORKOUT PLAN</h1>
        <p class="workout-sub">
            <i class="far fa-clock"></i> 1 Minute Per Exercise • 3 Sets • 60s Rest
        </p>
    </header>

    <div class="workout-grid">
        <div class="workout-box">
            <h3>MONDAY</h3>
            <h2>CHEST</h2>
            <ul>
                <li>Resistance Band Flyes</li>
                <li>Dumbbell Chest Press</li>
                <li>Incline Push Ups</li>
                <li>Alternating Plank Row</li>
            </ul>
        </div>

        <div class="workout-box">
            <h3>TUESDAY</h3>
            <h2>LEGS</h2>
            <ul>
                <li>Squat Jumps</li>
                <li>Single Leg Hip Thrust</li>
                <li>Clamshells</li>
                <li>Romanian Deadlift</li>
            </ul>
        </div>

        <div class="workout-box">
            <h3>WEDNESDAY</h3>
            <h2>CARDIO</h2>
            <ul>
                <li>Box Jumps</li>
                <li>Pulsing Squats</li>
                <li>Ski Jumps</li>
                <li>Toe Taps</li>
            </ul>
        </div>

        <div class="workout-box">
            <h3>THURSDAY</h3>
            <h2>BACK</h2>
            <ul>
                <li>Deadlift</li>
                <li>Dumbbell Shrugs</li>
                <li>Opposite Arm / Leg Raises</li>
                <li>Resistance Band Face Pulls</li>
            </ul>
        </div>

        <div class="workout-box">
            <h3>FRIDAY</h3>
            <h2>ABS</h2>
            <ul>
                <li>Standing Oblique Twists</li>
                <li>Suitcase Crunches</li>
                <li>Plank</li>
                <li>Bicycle Crunches</li>
            </ul>
        </div>

        <div class="workout-box">
            <h3>SATURDAY</h3>
            <h2>ARMS</h2>
            <ul>
                <li>Tricep Kickbacks</li>
                <li>Hammer Curls</li>
                <li>Overhead Circles</li>
                <li>Lateral Raises</li>
            </ul>
        </div>

        <div class="workout-box rest-day">
            <h3>SUNDAY</h3>
            <h2>REST DAY</h2>
            <p style="color: #aaa; margin-top: 10px; font-style: italic;">
                "Recovery is where the growth happens."
            </p>
        </div>
    </div>
</main>

</body>
</html>