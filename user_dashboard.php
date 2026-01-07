<?php
session_start();
include 'config.php'; // Database connection file thapnus

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// YO LOGIC THAPNU PARCHA ERROR HATAUNA
$query = "SELECT profile_pic FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

// User ko image set garne, yadi chhaina bhane default rakhne
$user_img = (!empty($user_data['profile_pic'])) ? $user_data['profile_pic'] : 'default_profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Hub | Sasin Gym</title>

    <link rel="icon" type="image/png" href="Images/fav.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="user_dashboard.css"> 
    
    <style>
        /* Navbar Profile Image styling */
        .profile-upload-wrapper { cursor: pointer; display: flex; align-items: center; }
        .profile-img-nav {
            width: 45px; height: 45px; border-radius: 50%;
            object-fit: cover; border: 2px solid #32cc11;
        }
        .default-avatar i { font-size: 40px; color: #fff; }
    </style>
</head>

<body>
<header class="top-navbar">
    <div class="logo">
        <img src="Images/fulllogo.png" alt="logo">
    </div>
    
    <div class="nav-right">
        <div class="welcome-text">Welcome, <span><?= strtoupper($username) ?></span></div>
        
        <div class="profile-upload-wrapper" onclick="document.getElementById('profileInput').click();" title="Click to Change Photo">
            <?php if($user_img != 'default_profile.png' && file_exists("uploads/profile_pics/".$user_img)): ?>
                <img src="uploads/profile_pics/<?= $user_img ?>" class="profile-img-nav" id="navProfilePreview">
            <?php else: ?>
                <div class="default-avatar" id="navProfileIcon"><i class="fas fa-user-circle"></i></div>
            <?php endif; ?>
            
            <input type="file" id="profileInput" style="display:none;" accept="image/*" onchange="uploadProfile(this)">
        </div>
        
        <a href="index.php" class="logout-box">
            Logout <i class="fas fa-power-off"></i>
        </a>
    </div>
</header>

<div class="dashboard-container">
    <aside class="sidebar">
        <ul>
            <li><a href="user_dashboard.php" class="active"><i class="fas fa-th-large"></i> Overview</a></li>
            <li><a href="attendence.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            <li><a href="diet.php"><i class="fas fa-apple-alt"></i> Nutrition</a></li>
            <li><a href="book_trainer.php"><i class="fas fa-user-tie"></i> Trainers</a></li>
            <li><a href="mybooking.php"><i class="fas fa-history"></i> History</a></li>
            <li><a href="workout.php"><i class="fas fa-dumbbell"></i> Workout Plan</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <section class="hero-card">
            <h1>DOMINATE<br><span>THE DAY, <?= strtoupper($username) ?></span></h1>
            <p>"This is not a routine. This is the life you chose — disciplined, focused, unstoppable."</p>
        </section>

        <section class="section-header">
    <h2>EXECUTE. TRACK. EVOLVE.</h2>
    <p>No excuses. Every action you take here builds your dominance.</p>
    </section>


        <section class="grid">
            <a href="attendence.php" class="action-card">
                <i class="fas fa-calendar-check"></i>
                <h3>Attendance</h3>
            </a>
            <a href="diet.php" class="action-card">
                <i class="fas fa-utensils"></i>
                <h3>Diet Plan</h3>
            </a>
            <a href="book_trainer.php" class="action-card">
                <i class="fas fa-user-tie"></i>
                <h3>Book Coach</h3>
            </a>
            <a href="workout.php" class="action-card">
                <i class="fas fa-dumbbell"></i>
                <h3>Workouts</h3>
            </a>
        </section>

        <div class="pricing-header" style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 2.5rem; font-weight: 800;">Elite Memberships</h2>
        </div>

        <div class="pricing-grid">
            <div class="price-card">
                <div class="plan-header">
                    <h3 style="color: #888; font-size: 12px; letter-spacing: 2px;">BASIC</h3>
                    <div class="price">Rs 1,200<span style="font-size: 16px; color: #888;">/mo</span></div>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Full Gym Access</li>
                    <li><i class="fas fa-check"></i> Locker Facility</li>
                    <li class="disabled"><i class="fas fa-check"></i> Personal Trainer</li>
                    <li class="disabled"><i class="fas fa-check"></i> Custom Diet</li>
                </ul>
                <button class="plan-btn">GET STARTED</button>
            </div>

            <div class="price-card" style="border-color: var(--primary); background: rgba(50, 204, 17, 0.04);">
                <div class="plan-header">
                    <h3 style="color: var(--primary); font-size: 12px; letter-spacing: 2px;">STANDARD</h3>
                    <div class="price">Rs 2,500<span style="font-size: 16px; color: #888;">/mo</span></div>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Everything in Basic</li>
                    <li><i class="fas fa-check"></i> 2 PT Sessions / Mo</li>
                    <li><i class="fas fa-check"></i> Nutrition Guide</li>
                    <li class="disabled"><i class="fas fa-check"></i> VIP Lounge</li>
                </ul>
                <button class="plan-btn" style="background: var(--primary); color: #000;">GET STARTED</button>
            </div>

            <div class="price-card">
                <div class="plan-header">
                    <h3 style="color: #888; font-size: 12px; letter-spacing: 2px;">PREMIUM</h3>
                    <div class="price">Rs 4,500<span style="font-size: 16px; color: #888;">/mo</span></div>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Everything in Standard</li>
                    <li><i class="fas fa-check"></i> Daily Personal Trainer</li>
                    <li><i class="fas fa-check"></i> Custom Meal Prep</li>
                    <li><i class="fas fa-check"></i> Steam & Sauna</li>
                </ul>
                <button class="plan-btn">GET STARTED</button>
            </div>
        </div>
    </main>
</div>

<script>

function uploadProfile(input) {
    if (input.files && input.files[0]) {
        let formData = new FormData();
        formData.append('profile_image', input.files[0]);

        // Loading state
        document.body.style.cursor = 'wait';

        fetch('upload_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.body.style.cursor = 'default';
            if (data.success) {
                // Image update garne without reload
                const navImg = document.getElementById('navProfilePreview');
                if(navImg) {
                    navImg.src = 'uploads/profile_pics/' + data.filename + '?t=' + new Date().getTime();
                } else {
                    location.reload(); // First time photo halda reload gardine
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Upload failed!');
        });
    }
}

</script>

</body>
</html>