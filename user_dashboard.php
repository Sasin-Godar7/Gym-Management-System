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
    <title>Dashboard | Sasin Elite Gym</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="Images/fulllogo.png" alt="logo">
        </div>
        <div class="menu">
            <a href="#welcome">Home</a>
            <a href="#classes">Classes</a>
            <a href="#trainers">Trainers</a>
            <a href="#subscription">Subscription</a>
            <a href="#gallery">Gallery</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="welcome">
        <div class="hero-text">
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
            <p>Transform your body and mind at <b>SASIN ELITE GYM</b></p>
            <button onclick="window.location.href='#subscription'">Join a Plan</button>
        </div>
    </section>

    <!-- Classes Section -->
    <section class="classes-section" id="classes">
        <h2 class="section-title">Our Classes</h2>
        <div class="classes-container">
            <div class="class-card">
                <img src="Images/cardio.jpg" alt="Cardio Class">
                <h3>Cardio Training</h3>
            </div>
            <div class="class-card">
                <img src="Images/strength training.jpg" alt="Strength Class">
                <h3>Strength Training</h3>
            </div>
            <div class="class-card">
                <img src="Images/yoga.jpg" alt="Yoga Class">
                <h3>Yoga</h3>
            </div>
            <div class="class-card">
                <img src="Images/zumba.jpg" alt="Zumba Class">
                <h3>Zumba</h3>
            </div>
        </div>
    </section>

    <!-- Trainers Section -->
    <section class="trainers-section" id="trainers">
        <h2 class="section-title">Our Trainers</h2>
        <div class="trainers-container">
            <div class="trainer-card">
                <img src="Images/strength trainer.jpg" alt="Trainer 1">
                <h3>Satish Ghimire</h3>
            </div>
            <div class="trainer-card">
                <img src="Images/yoga.jpg" alt="Trainer 2">
                <h3>Maya Devi</h3>
            </div>
            <div class="trainer-card">
                <img src="Images/gym profile 2.avif" alt="Trainer 3">
                <h3>Rooney Carlsen</h3>
            </div>
            <div class="trainer-card">
                <img src="Images/cardio-trainer.jpg" alt="Trainer 4">
                <h3>Bikram Parajuli</h3>
            </div>
        </div>
    </section>

    <!-- Subscription Section -->
    <section class="subscription-section" id="subscription">
        <h2 class="section-title">Subscription Packages</h2>
        <div class="pricing-container">
            <div class="pricing-card">
                <h3>Basic</h3>
                <p>Rs 1,200 / month</p>
            </div>
            <div class="pricing-card popular">
                <h3>Standard</h3>
                <p>Rs 2,500 / month</p>
            </div>
            <div class="pricing-card">
                <h3>Premium</h3>
                <p>Rs 4,000 / month</p>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery" id="gallery">
        <h2>Our Gallery</h2>
        <div class="gallery-container">
            <div class="item"><img src="Images/gymhall1.jpg"></div>
            <div class="item"><img src="Images/gymhall2.jpg"></div>
            <div class="item"><img src="Images/gymhall3.jpg"></div>
            <div class="item"><img src="Images/gymhall1.jpg"></div>
        </div>
    </section>

</body>

</html>
