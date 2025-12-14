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
<title>Diet Plan | Sasin Elite Gym</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/fav.png">
<style>
/* --- Global Styles --- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #0f0f0f;
    color: #fff;
    line-height: 1.6;
}

/* --- Navbar --- */
.top-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #111;
    padding: 15px 50px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 15px rgba(0,0,0,0.7);
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
    font-weight: 600;
    color: #32cc11;
    font-size: 18px;
}

.nav-right a {
    color: #fff;
    text-decoration: none;
}

.logout-btn {
    background: #32cc11;
    padding: 8px 22px;
    border-radius: 25px;
    font-weight: 600;
    transition: 0.3s;
}

.logout-btn:hover {
    background: #33ed40;
}

/* --- Hero Section --- */
.hero {
    background: linear-gradient(135deg, rgb(26 57 19 / 30%), rgb(12 76 10 / 70%)), url(Images/fitness-bg.jpg) no-repeat center center / cover;
    text-align: center;
    padding: 80px 20px;
    border-radius: 15px;
    margin: 30px auto;
    max-width: 1200px;
}

.hero h1 {
    font-size: 42px;
    color: #ffffffff;
    margin-bottom: 15px;
}

.hero p {
    font-size: 20px;
    color: #ccc;
}

/* --- Weekly Plan Cards --- */
.weekly-plan {
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
}

.weekly-plan h2 {
    text-align: center;
    color: #32cc11;
    font-size: 32px;
    margin-bottom: 30px;
}

.days-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.day-card {
    background: #1e1e1e;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 10px rgba(87, 251, 50, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

.day-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(50,204,17,0.3);
}

.day-card h3 {
    color: #32cc11;
    margin-bottom: 15px;
    font-size: 24px;
}

.day-card ul {
    list-style: none;
    text-align: left;
}

.day-card ul li {
    margin-bottom: 10px;
    font-size: 16px;
}

/* --- Supplements Section --- */
.supplements {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
}

.supplements h2 {
    text-align: center;
    color: #32cc11;
    font-size: 32px;
    margin-bottom: 30px;
}

.supplement-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
}

.supplement-card {
    background: #1e1e1e;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 15px rgba(50,204,17,0.1);
}

.supplement-card i {
    font-size: 40px;
    margin-bottom: 15px;
    color: #32cc11;
}

.supplement-card h3 {
    font-size: 22px;
    margin-bottom: 10px;
}

.supplement-card p {
    color: #ccc;
    font-size: 16px;
}

.supplement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(50, 204, 17, 0.3);
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .top-navbar {
        flex-direction: column;
        gap: 10px;
        padding: 15px 20px;
    }

    .days-grid, .supplement-cards {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 32px;
    }

    .hero p {
        font-size: 16px;
    }
}
</style>
</head>
<body>

<!-- Navbar -->
<header class="top-navbar">
    <div class="logo"><img src="Images/fulllogo.png" alt="logo"></div>
    <div class="nav-right">
        <span class="welcome-text">Hi, <?php echo $_SESSION['username']; ?>!</span>
        <a href="user_dashboard.php"><i class="fas fa-home fa-2x"></i></a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <h1>Your Personalized Diet Plan</h1>
    <p>Stay strong, stay fit! Follow this plan to reach your fitness goals efficiently.</p>
</section>

<!-- Weekly Plan -->
<section class="weekly-plan">
    <h2>Weekly Diet Overview</h2>
    <div class="days-grid">
        <div class="day-card">
            <h3>Sunday</h3>
            <ul>
                <li>🥤 Breakfast: Fruit Smoothie</li>
                <li>🍽 Lunch: Grilled Veggie + Quinoa</li>
                <li>🥗 Dinner: Light Soup + Salad</li>
            </ul>
        </div>
        <div class="day-card">
            <h3>Monday</h3>
            <ul>
                <li>🍳 Breakfast: Eggs + Whole Wheat Toast</li>
                <li>🐟 Lunch: Fish + Brown Rice + Veggies</li>
                <li>🥤 Dinner: Protein Shake + Salad</li>
            </ul>
        </div>
        <div class="day-card">
            <h3>Tuesday</h3>
            <ul>
                <li>🥛 Breakfast: Greek Yogurt + Berries</li>
                <li>🌯 Lunch: Chicken Wrap + Veggies</li>
                <li>🐟 Dinner: Grilled Fish + Salad</li>
            </ul>
        </div>
        <div class="day-card">
            <h3>Wednesday</h3>
            <ul>
                <li>🥤 Breakfast: Smoothie Bowl</li>
                <li>🥦 Lunch: Veggie Stir-fry + Tofu</li>
                <li>🍗 Dinner: Chicken + Quinoa</li>
            </ul>
        </div>
        <div class="day-card">
            <h3>Thursday</h3>
            <ul>
                <li>🍳 Breakfast: Omelette + Veggies</li>
                <li>🍗 Lunch: Grilled Chicken + Brown Rice</li>
                <li>🥗 Dinner: Soup + Salad</li>
            </ul>
        </div>
        <div class="day-card">
            <h3>Friday</h3>
            <ul>
                <li>🥞 Breakfast: Protein Pancakes + Fruits</li>
                <li>🐟 Lunch: Fish + Veggies</li>
                <li>🥗 Dinner: Chicken Salad</li>
            </ul>
        </div>
        <div class="day-card">
            <h3>Saturday</h3>
            <ul>
                <li>🥣 Breakfast: Oatmeal + Fruits</li>
                <li>🍗 Lunch: Grilled Chicken + Quinoa</li>
                <li>🥗 Dinner: Salad + Soup</li>
            </ul>
        </div>
    </div>
</section>

<!-- Supplements -->
<section class="supplements">
    <h2>Snacks & Supplements</h2>
    <div class="supplement-cards">
        <div class="supplement-card">
            <i class="fas fa-apple-alt"></i>
            <h3>Healthy Snacks</h3>
            <p>Nuts, Fruits, Yogurt for energy boost.</p>
        </div>
        <div class="supplement-card">
            <i class="fas fa-dumbbell"></i>
            <h3>Protein Shakes</h3>
            <p>Whey or plant-based protein for muscle recovery.</p>
        </div>
        <div class="supplement-card">
            <i class="fas fa-carrot"></i>
            <h3>Vitamins & Minerals</h3>
            <p>Ensure daily intake for overall health.</p>
        </div>
        <div class="supplement-card">
            <i class="fas fa-lemon"></i>
            <h3>Hydration</h3>
            <p>Drink at least 2-3 liters of water daily.</p>
        </div>
    </div>
</section>

</body>
</html>
