<?php
session_start();
require "config.php";

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle booking
if(isset($_POST['book'])){
    $trainer_id   = intval($_POST['trainer_id']);
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    // Simple validation
    if($booking_date == "" || $booking_time == ""){
        $message = "Please select date and time";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO trainer_bookings (user_id, trainer_id, booking_date, booking_time)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("iiss", $user_id, $trainer_id, $booking_date, $booking_time);

        if($stmt->execute()){
            $message = "Trainer booked successfully!";
        } else {
            $message = "Booking failed, try again.";
        }
    }
}

// Fetch trainers
$trainers = $conn->query("SELECT * FROM users WHERE role='trainer'");
?>
<!DOCTYPE html>
<html>
<head>
<title>Book Trainer</title>
<link rel="icon" href="images/fav.png">
<style>
body{background:#0f0f0f;color:#fff;font-family:Poppins}
.container{max-width:1000px;margin:40px auto}
h1{text-align:center;color:#32cc11}
.trainer-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px}
.card{background:#1e1e1e;padding:20px;border-radius:12px}
.card h3{color:#32cc11}
input,button{width:100%;padding:10px;margin-top:8px;border-radius:6px;border:none}
button{background:#32cc11;color:#fff;font-weight:600;cursor:pointer}
button:hover{background:#33ed40}
.msg{text-align:center;margin:15px;color:#32cc11}
</style>
</head>
<body>

<div class="container">
<h1>Book Your Trainer</h1>

<?php if($message!=""): ?>
<p class="msg"><?= $message ?></p>
<?php endif; ?>

<div class="trainer-grid">
<?php while($t = $trainers->fetch_assoc()): ?>
<div class="card">
    <h3><?= $t['username'] ?></h3>
    <p>Email: <?= $t['email'] ?></p>
    <p>Contact: <?= $t['contact'] ?></p>

    <form method="post">
        <input type="hidden" name="trainer_id" value="<?= $t['id'] ?>">
        <input type="date" name="booking_date" required>
        <input type="time" name="booking_time" required>
        <button name="book">Book Trainer</button>
    </form>
</div>
<?php endwhile; ?>
</div>
</div>

</body>
</html>
