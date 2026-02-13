<?php
session_start();
include 'config.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$plan_amount = isset($_GET['amount']) ? $_GET['amount'] : 0;
$plan_name = isset($_GET['plan']) ? $_GET['plan'] : "Membership";

$message = "";

// Payment processing for eSewa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directly go to eSewa payment page
    header("Location: esewa_login.php?amount=$plan_amount&plan=$plan_name");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment - Sasin Elite Gym</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="images/fav.png">

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

* { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans', sans-serif; }

body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    position: relative;
    overflow-x: hidden;
}

.payment-container {
    width: 440px;
    padding: 45px 35px;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(6px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.15);
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.payment-container h2 {
    font-size: 36px;
    color: #32cc11;
    margin-bottom: 8px;
    font-weight: 700;
}

.payment-container .sub-text {
    font-size: 16px;
    margin-bottom: 25px;
    color: #f1f1f1;
}

.plan-summary {
    background: #32cc11;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    color: #ffffff;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: transform 0.3s;
}

.plan-summary:hover { transform: scale(1.02); }

.plan-summary h3 { margin:0; font-size:20px; }
.plan-summary .plan-type { font-size:16px; font-weight:500; margin-top:5px; }
.plan-summary .amount { font-size:26px; margin-top:5px; }

button.btn {
    width:100%; padding:14px;
    border:none; border-radius:10px;
    background: #32cc11;
    font-size:18px; font-weight:700;
    cursor:pointer; transition:0.3s;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
}

button.btn:hover { background: #28a80a; box-shadow:0 6px 15px rgba(0,0,0,0.3); }

.error-text { color:#ff4d4d; font-size:14px; margin-bottom:10px; }
.info-text { font-size:13px; color:#f1f1f1; margin-top:15px; display:block; }
.info-text i { color:#32cc11; margin-right:5px; }

.payment-logo {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    
}

.payment-logo img {
    width: 100px;
    filter: drop-shadow(0 3px 6px rgba(0,0,0,0.5));
    border-radius:16px;
}
</style>
</head>
<body>

<div class="payment-container">
    <h2>Welcome, <?= strtoupper($username) ?>!</h2>
    <p class="sub-text">Complete your <?= $plan_name ?> payment securely</p>

    <div class="plan-summary">
        <h3><?= $plan_name ?> Plan</h3>
        <p class="plan-type"><?= $plan_name == 'BASIC' ? 'Basic Membership' : ($plan_name == 'STANDARD' ? 'Standard Membership' : 'Premium Membership') ?></p>
        <p class="amount">Rs <?= $plan_amount ?></p>
    </div>

    <?php if($message != ""): ?>
        <p class="error-text"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="payment-logo">
            <img src="images/esewalogo.jpg" alt="eSewa">
        </div>
        <button type="submit" class="btn"><i class="fas fa-credit-card"></i>&nbsp;&nbsp;Pay with eSewa</button>
    </form>

    <span class="info-text"><i class="fas fa-info-circle"></i> Your payment is secure and encrypted via eSewa.</span>
</div>

</body>
</html>
