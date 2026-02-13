<?php
// esewa_success.php
session_start();

// Get amount & plan from POST or GET (hidden inputs)
$plan_amount = isset($_POST['amount']) ? $_POST['amount'] : (isset($_GET['amount']) ? $_GET['amount'] : 0);
$plan_name = isset($_POST['plan']) ? $_POST['plan'] : (isset($_GET['plan']) ? $_GET['plan'] : 'Membership');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="images/fav.png">
<title>Payment Success - Sasin Elite Gym</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #111;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    flex-direction: column;
}

.success-card {
    background-color: #1e1e1e;
    padding: 40px 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    max-width: 400px;
    width: 90%;
}

.success-card img {
    width: 150px;
    margin-bottom: 30px;
}

.success-card h2 {
    color: #a4c639;
    margin-bottom: 15px;
    font-size: 28px;
}

.success-card p {
    color: #ccc;
    margin-bottom: 10px;
    font-size: 16px;
}

.amount-box {
    background: #333;
    padding: 15px 20px;
    border-radius: 6px;
    font-size: 20px;
    color: #a4c639;
    margin: 15px 0;
    font-weight: bold;
}

.info-text {
    color: orange;
    margin-top: 15px;
    font-size: 16px;
}

.thank-you {
    margin-top: 20px;
    color: #fff;
    font-weight: 600;
    font-size: 18px;
}

.dashboard-btn {
    margin-top: 25px;
    display: inline-block;
    padding: 12px 25px;
    background: #a4c639;
    color: #111;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.dashboard-btn:hover {
    background: #8bb32f;
}
</style>
</head>
<body>

<div class="success-card">
    <img src="Images/tick.png" alt="Payment Success">
    <h2>Payment Success!</h2>
    <p>Your payment process has been completed successfully.</p>

    <div class="amount-box">NPR <?= htmlspecialchars(number_format($plan_amount,2)) ?></div>

    <p class="info-text">Your payment was successful.</p>
    <p class="thank-you">Thank you!!!</p>

    <a href="user_dashboard.php" class="dashboard-btn">Go to Dashboard</a>
</div>

<!-- Auto redirect removed -->
<!-- User can now click the button to navigate -->
</body>
</html>
