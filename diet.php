<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
// For testing/fallback
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Athlete';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Diet Plan | Sasin Elite Gym</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/fav.png">
    
    <style>
        /* --- Global Styles --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* --- Navbar (Fixed for Elite Look) --- */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* background: rgba(17, 17, 17, 0.95); */
            backdrop-filter: blur(10px);
            padding: 0 50px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            /* box-shadow: 0 2px 20px rgba(0,0,0,0.8); */
            height: 80px;
        }

        .top-navbar .logo img {
            width: 170px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-right .welcome-text {
            font-weight: 700;
            color: #32cc11;
            font-size: 18px;
            font-family: 'Orbitron', sans-serif;
        }

        .nav-right a {
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .home-icon:hover { color: #32cc11; transform: scale(1.1); }

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

        /* --- Hero Section --- */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 140px 20px 80px;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
        }

        .hero h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(2rem, 6vw, 4rem);
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .hero p {
            font-size: 22px;
            color: #32cc11;
            font-weight: 500;
            letter-spacing: 1px;
        }

        /* --- Weekly Plan --- */
        .section-container {
            max-width: 1400px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 32px;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: #32cc11;
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .day-card {
            background: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 30px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .day-card:hover {
            transform: translateY(-10px);
            border-color: #32cc11;
            background: rgba(40, 40, 40, 0.8);
            box-shadow: 0 15px 30px rgba(0,0,0,0.5);
        }

        .day-card h3 {
            font-family: 'Orbitron', sans-serif;
            color: #32cc11;
            margin-bottom: 25px;
            font-size: 22px;
            border-bottom: 1px solid rgba(50, 204, 17, 0.2);
            padding-bottom: 10px;
        }

        .meal-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .meal-item i {
            width: 30px;
            color: #32cc11;
            font-size: 1.2rem;
        }

        .meal-label {
            font-weight: 700;
            color: #bbb;
            margin-right: 10px;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        /* --- Calculator Styling --- */
.calculator-box {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(50, 204, 17, 0.3);
    border-radius: 20px;
    padding: 40px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    backdrop-filter: blur(10px);
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    font-family: 'Orbitron', sans-serif;
    font-size: 12px;
    color: #32cc11;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.input-group input, .input-group select {
    width: 100%;
    padding: 12px;
    background: rgba(0,0,0,0.5);
    border: 1px solid #444;
    color: #fff;
    border-radius: 5px;
    outline: none;
}

.calc-btn {
    width: 100%;
    background: #32cc11;
    color: #000;
    border: none;
    padding: 15px;
    font-family: 'Orbitron', sans-serif;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 5px;
}

.calc-btn:hover {
    background: #fff;
    box-shadow: 0 0 20px rgba(50, 204, 17, 0.6);
}

.result-card {
    text-align: center;
    background: rgba(50, 204, 17, 0.1);
    padding: 30px;
    border-radius: 15px;
    border: 1px solid #32cc11;
}

.result-card h4 {
    font-size: 48px;
    color: #32cc11;
    font-family: 'Orbitron', sans-serif;
}

.macro-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
    margin-top: 20px;
}

.macro-item {
    background: rgba(255,255,255,0.05);
    padding: 15px 10px;
    border-radius: 10px;
    text-align: center;
    font-size: 14px;
}

@media (max-width: 768px) {
    .calculator-box { grid-template-columns: 1fr; }
}

        /* --- Supplements --- */
        .supplement-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .supplement-card {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
        }

        .supplement-card:hover {
            border-color: #32cc11;
            box-shadow: 0 10px 20px rgba(50, 204, 17, 0.2);
        }

        .supplement-card img {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .supplement-card h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.3rem;
            color: #32cc11;
            margin-bottom: 10px;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .top-navbar { padding: 0 20px; }
            .nav-right .welcome-text { display: none; }
            .hero { padding-top: 120px; }
        }
    </style>
</head>
<body>

<header class="top-navbar">
    <div class="logo">
        <img src="Images/fulllogo.png" alt="logo" onerror="this.src='https://via.placeholder.com/180x50/111/32cc11?text=SASIN+ELITE'">
    </div>
    <div class="nav-right">
        <span class="welcome-text">Hi, <?php echo htmlspecialchars($username); ?>!</span>
        <a href="user_dashboard.php"><i class="fas fa-house fa-xl home-icon"></i></a>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt" style="margin-right:8px;"></i> Logout
        </a>
    </div>
</header>

<section class="hero">
    <h1>ELITE FUELING SYSTEM</h1>
    <p>Precision Nutrition for Maximum Performance</p>
</section>

<section class="section-container">
    <h2 class="section-title">Weekly Diet Protocol</h2>
    <div class="days-grid">
        
        <div class="day-card">
            <h3>Sunday</h3>
            <div class="meal-item"><i class="fas fa-sun"></i> <div><span class="meal-label">Breakfast</span> Fruit Smoothie</div></div>
            <div class="meal-item"><i class="fas fa-cloud-sun"></i> <div><span class="meal-label">Lunch</span> Grilled Veggie + Quinoa</div></div>
            <div class="meal-item"><i class="fas fa-moon"></i> <div><span class="meal-label">Dinner</span> Light Soup + Salad</div></div>
        </div>

        <div class="day-card">
            <h3>Monday</h3>
            <div class="meal-item"><i class="fas fa-sun"></i> <div><span class="meal-label">Breakfast</span> Eggs + Whole Wheat Toast</div></div>
            <div class="meal-item"><i class="fas fa-cloud-sun"></i> <div><span class="meal-label">Lunch</span> Fish + Brown Rice + Veggies</div></div>
            <div class="meal-item"><i class="fas fa-moon"></i> <div><span class="meal-label">Dinner</span> Protein Shake + Salad</div></div>
        </div>

        <div class="day-card">
            <h3>Tuesday</h3>
            <div class="meal-item"><i class="fas fa-sun"></i> <div><span class="meal-label">Breakfast</span> Greek Yogurt + Berries</div></div>
            <div class="meal-item"><i class="fas fa-cloud-sun"></i> <div><span class="meal-label">Lunch</span> Chicken Wrap + Veggies</div></div>
            <div class="meal-item"><i class="fas fa-moon"></i> <div><span class="meal-label">Dinner</span> Grilled Fish + Salad</div></div>
        </div>

        <div class="day-card">
            <h3>Wednesday</h3>
            <div class="meal-item"><i class="fas fa-sun"></i> <div><span class="meal-label">Breakfast</span> Smoothie Bowl</div></div>
            <div class="meal-item"><i class="fas fa-cloud-sun"></i> <div><span class="meal-label">Lunch</span> Veggie Stir-fry + Tofu</div></div>
            <div class="meal-item"><i class="fas fa-moon"></i> <div><span class="meal-label">Dinner</span> Chicken + Quinoa</div></div>
        </div>

        <div class="day-card">
            <h3>Thursday</h3>
            <div class="meal-item"><i class="fas fa-sun"></i> <div><span class="meal-label">Breakfast</span> Omelette + Veggies</div></div>
            <div class="meal-item"><i class="fas fa-cloud-sun"></i> <div><span class="meal-label">Lunch</span> Grilled Chicken + Brown Rice</div></div>
            <div class="meal-item"><i class="fas fa-moon"></i> <div><span class="meal-label">Dinner</span> Soup + Salad</div></div>
        </div>

        <div class="day-card">
            <h3>Friday</h3>
            <div class="meal-item"><i class="fas fa-sun"></i> <div><span class="meal-label">Breakfast</span> Protein Pancakes</div></div>
            <div class="meal-item"><i class="fas fa-cloud-sun"></i> <div><span class="meal-label">Lunch</span> Fish + Veggies</div></div>
            <div class="meal-item"><i class="fas fa-moon"></i> <div><span class="meal-label">Dinner</span> Chicken Salad</div></div>
        </div>

    </div>
</section>

<section class="section-container">
    <h2 class="section-title">Macro & Calorie Calculator</h2>
    <div class="calculator-box">
        <div class="calc-inputs">
            <div class="input-group">
                <label>Weight (kg)</label>
                <input type="number" id="weight" placeholder="e.g. 75">
            </div>
            <div class="input-group">
                <label>Height (cm)</label>
                <input type="number" id="height" placeholder="e.g. 180">
            </div>
            <div class="input-group">
                <label>Age</label>
                <input type="number" id="age" placeholder="e.g. 25">
            </div>
            <div class="input-group">
                <label>Gender</label>
                <select id="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <button onclick="calculateCalories()" class="calc-btn">Calculate Results</button>
        </div>

        <div class="calc-results" id="results" style="display: none;">
            <div class="result-card">
                <h4 id="calories">0</h4>
                <span>Daily Calories</span>
            </div>
            <div class="macro-grid">
                <div class="macro-item"><strong>Protein:</strong> <span id="protein">0</span>g</div>
                <div class="macro-item"><strong>Carbs:</strong> <span id="carbs">0</span>g</div>
                <div class="macro-item"><strong>Fats:</strong> <span id="fats">0</span>g</div>
            </div>
        </div>
    </div>
</section>



<section class="section-container" style="margin-top: 20px;">
    <h2 class="section-title">Support & Hydration</h2>
    <div class="supplement-cards">
        <div class="supplement-card">
            <img src="https://images.unsplash.com/photo-1543339308-43e59d6b73a6?q=80&w=2070&auto=format&fit=crop" alt="Healthy Snacks">
            <h3>Energy Snacks</h3>
            <p>Raw nuts, seasonal fruits, and Greek yogurt for sustained energy levels.</p>
        </div>
        <div class="supplement-card">
            <img src="Images/diet2.jpg" alt="Protein Shakes">
            <h3>Muscle Recovery</h3>
            <p>High-quality Whey or plant protein within 45 mins of training.</p>
        </div>
        <div class="supplement-card">
            <img src="Images/diet4.jpg" alt="Hydration">
            <h3>Hydration</h3>
            <p>Maintain 3-4 liters of water daily to ensure peak cellular function.</p>
        </div>
    </div>
</section>


<script>
function calculateCalories() {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const age = parseInt(document.getElementById('age').value);
    const gender = document.getElementById('gender').value;

    if (!weight || !height || !age) {
        alert("Please fill in all fields!");
        return;
    }

    // BMR (Mifflin-St Jeor)
    let bmr;
    if (gender === 'male') {
        bmr = (10 * weight) + (6.25 * height) - (5 * age) + 5;
    } else {
        bmr = (10 * weight) + (6.25 * height) - (5 * age) - 161;
    }

    // TDEE (Moderate Activity)
    const tdee = Math.round(bmr * 1.55);

    // Macros (Scientific)
    const protein = Math.round(weight * 2);        // 2g/kg
    const fats = Math.round(weight * 0.9);         // 0.9g/kg

    const proteinCalories = protein * 4;
    const fatCalories = fats * 9;

    const remainingCalories = tdee - (proteinCalories + fatCalories);
    const carbs = Math.round(remainingCalories / 4);

    // Show Results
    document.getElementById('results').style.display = 'block';
    document.getElementById('calories').innerText = tdee;
    document.getElementById('protein').innerText = protein;
    document.getElementById('carbs').innerText = carbs;
    document.getElementById('fats').innerText = fats;
}
</script>


</body>
</html>