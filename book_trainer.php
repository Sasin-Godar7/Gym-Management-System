<?php session_start();
require "config.php";

if( !isset($_SESSION['username']) || $_SESSION['role'] !='user') {
  header("Location: login.php");
  exit();
}

// Fetch all trainers
$trainers =$conn->query("SELECT id, username, email, contact FROM users WHERE role='trainer' ORDER BY username ASC");

// Handle booking request
if(isset($_POST['trainer_id'], $_POST['booking_date'], $_POST['booking_time'])) {
  $trainer_id =intval($_POST['trainer_id']);
  $user_id =$_SESSION['user_id'];
  $booking_date =$_POST['booking_date'];
  $booking_time =$_POST['booking_time'];

  // Check duplicate booking for same trainer at same date/time
  $check =$conn->query("SELECT * FROM trainer_bookings 
 WHERE user_id=$user_id AND trainer_id=$trainer_id AND booking_date='$booking_date' AND booking_time='$booking_time' AND status='Pending' ");


    if($check->num_rows ==0) {
      $stmt =$conn->prepare("INSERT INTO trainer_bookings (user_id, trainer_id, booking_date, booking_time) VALUES (?,?,?,?)");
      $stmt->bind_param("iiss", $user_id, $trainer_id, $booking_date, $booking_time);
      $stmt->execute();
      $message ="Trainer booked successfully!";
    }

    else {
      $message ="You have already booked this trainer at this date/time!";
    }
  }

  ?>
<!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Trainer | Sasin Elite Gym</title>
    <link rel="icon" type="image/png" href="Images/fav.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
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
        padding: 8px 22px;
        border-radius: 25px;
        font-weight: 600;
        transition: 0.3s;
      }

      .logout-btn:hover {
        background: #33ed40;
      }

      /* Home Icon bigger */
      .home-icon {
        padding: 10px 14px;
        border-radius: 10px;
        transition: 0.2s;
        font-weight: 700;
        text-decoration: none;
        color: #000;
      }


      /* Container */
      .container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
      }

      h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #32cc11;
      }

      /* Trainer Cards (Box style) */
      .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      .trainer-card {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(50, 204, 17, 0.2);
        text-align: center;
        transition: 0.3s;
      }

      .trainer-card:hover {
        transform: scale(1.05);
      }

      .trainer-card h3 {
        color: #32cc11;
        margin-bottom: 10px;
      }

      .trainer-card p {
        color: #ccc;
        margin: 5px 0;
      }

      .trainer-card input[type="date"],
      .trainer-card input[type="time"] {
        margin-top: 10px;
        padding: 8px;
        border-radius: 8px;
        border: none;
        width: 90%;
      }

      .trainer-card button {
        margin-top: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        background: #32cc11;
        color: #000;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
      }

      .trainer-card button:hover {
        background: #33ed40;
      }

      /* Message */
      .message {
        margin: 15px 0;
        color: #32cc11;
        font-weight: 600;
        text-align: center;
      }

      /* Responsive */
      @media(max-width:768px) {
        .top-navbar {
          flex-direction: column;
          gap: 10px;
          padding: 15px 20px;
        }
      }
    </style>
  </head>

  <body>
    <header class="top-navbar">
      <div class="logo"><img src="Images/fulllogo.png" alt="logo"></div>
      <div class="nav-right"> <span class="welcome-text">Hi,
          <?php echo $_SESSION['username']; ?> !
        </span> <a href="user_dashboard.php" class="home-icon"><i class="fas fa-home fa-2x"></i></a> <a
          href="logout.php" class="logout-btn">Logout</a> </div>
    </header>
    <div class="container">
      <h2>Book a Trainer</h2>
      <?php if(isset($message)) echo "<div class='message'>$message</div>"; ?>
      <div class="card-grid">
        <?php while($trainer =$trainers->fetch_assoc()): ?>
        <div class="trainer-card">
          <h3>
            <?=$trainer['username'] ?>
          </h3>
          <p>Email:
            <?=$trainer['email'] ?>
          </p>
          <p>Contact:
            <?=$trainer['contact'] ?>
          </p>
          <p class="experience">Experience: 2+ Years</p>
          <form method="post"> <input type="hidden" name="trainer_id" value="<?= $trainer['id'] ?>"> <input type="date"
              name="booking_date" required> <input type="time" name="booking_time" required> <button
              type="submit">Book</button> </form>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </body>

  </html>