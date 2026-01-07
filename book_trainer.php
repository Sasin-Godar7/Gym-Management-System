<?php session_start();
require "config.php";

if( !isset($_SESSION['username']) || $_SESSION['role'] !='user') {
  header("Location: login.php");
  exit();
}

$trainers = $conn->query("SELECT id, username, email, contact FROM users WHERE role='trainer' ORDER BY username ASC");

// Experience and Fields variation logic
$expertises = ['Strength & Conditioning', 'Cardio Specialist', 'Zumba Instructor', 'CrossFit Pro', 'Yoga Guru'];

if(isset($_POST['trainer_id'], $_POST['booking_date'], $_POST['booking_time'])) {
  $trainer_id = intval($_POST['trainer_id']);
  $user_id = $_SESSION['user_id'];
  $booking_date = $_POST['booking_date'];
  $booking_time = $_POST['booking_time'];

  $check = $conn->query("SELECT * FROM trainer_bookings 
  WHERE user_id=$user_id AND trainer_id=$trainer_id AND booking_date='$booking_date' AND booking_time='$booking_time' AND status='Pending' ");

  if($check->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO trainer_bookings (user_id, trainer_id, booking_date, booking_time) VALUES (?,?,?,?)");
    $stmt->bind_param("iiss", $user_id, $trainer_id, $booking_date, $booking_time);
    $stmt->execute();
    $message = "Success! Your session is being scheduled.";
  } else {
    $message = "Duplicate request detected for this slot.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Trainer | Sasin Elite Gym</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

    :root {
      --accent: #32cc11;
      --accent-glow: rgba(50, 204, 17, 0.4);
      --glass: rgba(255, 255, 255, 0.03);
      --glass-border: rgba(255, 255, 255, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

    body {
      background: #050505;
      color: #fff;
      min-height: 100vh;
      background-image: 
        radial-gradient(circle at 80% 10%, rgba(50, 204, 17, 0.08) 0%, transparent 40%),
        radial-gradient(circle at 10% 90%, rgba(50, 204, 17, 0.05) 0%, transparent 40%);
    }

    /* --- Navbar (Fixed for Elite Look) --- */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* background: rgba(17, 17, 17, 0.95); */
            backdrop-filter: blur(2px);
            padding: 0 50px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            /* box-shadow: 0 2px 20px rgba(0,0,0,0.8); */
            height: 70px;
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

    

    /* Container */
    .container { max-width: 1200px; margin: 60px auto; padding: 0 25px; }
    .hero-text { text-align: center; margin-bottom: 60px; }
    .hero-text h1 { font-size: 48px; font-weight: 800; }
    .hero-text h1 span { color: var(--accent); text-shadow: 0 0 30px var(--accent-glow); }

    /* Trainer Grid */
    .trainer-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; }

    .glass-card {
      background: var(--glass);
      border: 1px solid var(--glass-border);
      border-radius: 24px; padding: 35px;
      backdrop-filter: blur(10px);
      transition: all 0.4s ease;
      position: relative;
    }
    .glass-card:hover { transform: translateY(-10px); border-color: var(--accent); background: rgba(255,255,255,0.06); }

    .badge {
      position: absolute; top: 20px; right: 20px;
      background: #fff; color: #000;
      padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase;
    }

    .trainer-header h3 { font-size: 24px; margin-bottom: 5px; }
    .trainer-header p { color: var(--accent); font-size: 13px; font-weight: 600; margin-bottom: 20px; }

    /* Stats Row - Different Values */
    .stats-row {
      display: flex; justify-content: space-between;
      margin-bottom: 25px; padding: 15px 0;
      border-top: 1px solid var(--glass-border);
      border-bottom: 1px solid var(--glass-border);
    }
    .stat { text-align: center; }
    .stat-val { display: block; font-size: 17px; font-weight: 700; color: #fff; }
    .stat-label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 1px; }

    /* White Form Elements */
    .booking-fields { display: flex; flex-direction: column; gap: 15px; }
    .input-wrapper { display: flex; flex-direction: column; gap: 6px; }
    .input-wrapper label { font-size: 12px; color: #fff; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .input-wrapper label i { color: var(--accent); font-size: 14px; }

    .modern-input {
      background: #332a2aff; /* White Background */
      border: none;
      border-radius: 12px;
      padding: 14px 15px;
      color: #ffffffff; /* Black text for white background */
      font-size: 14px; font-weight: 600; outline: none;
      transition: 0.3s;
    }
    .modern-input:focus { box-shadow: 0 0 15px rgba(255,255,255,0.2); }

    /* Custom calendar icon color for chrome/safari */
    .modern-input::-webkit-calendar-picker-indicator {
      filter: invert(8); /* Keep it dark on white background */
      cursor: pointer;
    }

    .submit-btn {
      margin-top: 15px;
      background: var(--accent); color: #000;
      border: none; padding: 16px; border-radius: 12px;
      font-weight: 800; cursor: pointer; text-transform: uppercase;
      transition: 0.3s;
    }
    .submit-btn:hover { background: #fff; transform: scale(1.02); }

    .toast {
      background: var(--accent); color: #000;
      padding: 15px 30px; border-radius: 12px;
      position: fixed; bottom: 30px; right: 30px;
      font-weight: 700; z-index: 2000;
    }
  </style>
</head>
<body>

<header class="top-navbar">
    <div class="logo">
        <img src="Images/fulllogo.png" alt="logo" onerror="this.src='https://via.placeholder.com/180x50/111/32cc11?text=SASIN+ELITE'">
    </div>
    <div class="nav-right">
        <!-- <span class="welcome-text">Hi, <?php echo htmlspecialchars($username); ?>!</span> -->
        <a href="user_dashboard.php"><i class="fas fa-house fa-xl home-icon"></i></a>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt" style="margin-right:8px;"></i> Logout
        </a>
    </div>
</header>


<div class="container">
  <div class="hero-text">
    <h1>Elite <span>Coaching</span></h1>
    <p>Personalized training sessions with industry experts.</p>
  </div>

  <?php if(isset($message)): ?>
    <div class="toast"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
  <?php endif; ?>

  <div class="trainer-grid">
    <?php 
    $i = 0;
    while($trainer = $trainers->fetch_assoc()): 
        // Unique variations based on loop index
        $exp = (2 + ($i % 5)) . "+ Yrs";
        $clients = (50 + ($i * 35)) . "+";
        $rating = number_format(4.7 + (($i % 4) * 0.1), 1);
        $field = $expertises[$i % count($expertises)];
        $i++;
    ?>
      <div class="glass-card">
        <span class="badge">Professional</span>
        <div class="trainer-header">
          <h3><?=$trainer['username'] ?></h3>
          <p><?=$field?></p>
        </div>

        <div class="stats-row">
          <div class="stat">
            <span class="stat-val"><?=$rating?></span>
            <span class="stat-label">Rating</span>
          </div>
          <div class="stat">
            <span class="stat-val"><?=$exp?></span>
            <span class="stat-label">Experience</span>
          </div>
          <div class="stat">
            <span class="stat-val"><?=$clients?></span>
            <span class="stat-label">Clients</span>
          </div>
        </div>

        <form method="post" class="booking-fields">
          <input type="hidden" name="trainer_id" value="<?= $trainer['id'] ?>">
          
          <div class="input-wrapper">
            <label><i class="far fa-calendar-check"></i> Select Training Date</label>
            <input type="date" name="booking_date" class="modern-input" required min="<?= date('Y-m-d') ?>">
          </div>
          
          <div class="input-wrapper">
            <label><i class="far fa-clock"></i> Select Preferred Time</label>
            <input type="time" name="booking_time" class="modern-input" required>
          </div>
          
          <button type="submit" class="submit-btn">Reserve Slot</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script>
  setTimeout(() => {
    const toast = document.querySelector('.toast');
    if(toast) toast.style.opacity = '0';
  }, 4000);
</script>

</body>
</html>