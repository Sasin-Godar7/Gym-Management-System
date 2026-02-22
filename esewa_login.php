<?php
session_start();

/* -------------------------------
   SESSION CHECK
--------------------------------*/
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

/* -------------------------------
   GET DATA FROM PAYMENT PAGE
--------------------------------*/
$plan_amount = isset($_GET['amount']) ? $_GET['amount'] : 0;
$plan_name   = isset($_GET['plan'])   ? $_GET['plan']   : 'Membership';

$message = "";

/* -------------------------------
   HANDLE FORM SUBMISSION
--------------------------------*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $esewa_id   = trim($_POST['esewa_id']);
    $mpin       = trim($_POST['mpin']);
    $plan_amount = $_POST['amount'];
    $plan_name   = $_POST['plan'];

    // Demo Credentials Check
    if ($esewa_id === "9800000000" && $mpin === "1234") {

        header("Location: esewa_success.php?amount=" . urlencode($plan_amount) . 
               "&plan=" . urlencode($plan_name));
        exit();

    } else {
        $message = "Invalid eSewa ID or MPIN. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>eSewa Payment - Sasin Elite Gym</title>
<link rel="icon" type="image/png" href="images/fav.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ===============================
   ROOT VARIABLES
================================*/
:root {
    --bg-dark: #1a1d21;
    --card-bg: #2c3036;
    --esewa-green: #60bb46;
    --amount-green: #a4c639;
    --input-bg: #1a1d21;
    --text-gray: #aaa;
}

/* ===============================
   GLOBAL
================================*/
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: var(--bg-dark);
    color: #fff;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ===============================
   NAVBAR
================================*/
.navbar {
    padding: 20px 8%;
}

.logo {
    font-size: 24px;
    font-weight: bold;
}

/* ===============================
   MAIN CONTAINER
================================*/
.container {
    display: flex;
    flex: 1;
    max-width: 1100px;
    margin: auto;
    width: 90%;
    gap: 60px;
    align-items: center;
}

/* ===============================
   PAYMENT INFO
================================*/
.payment-info {
    flex: 1;
}

.merchant-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 30px;
}

.merchant-icon {
    background: var(--esewa-green);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.amount-display h1 {
    font-size: 38px;
    color: var(--amount-green);
    margin: 10px 0 30px;
}

.details-table {
    background: rgba(255,255,255,0.05);
    padding: 25px;
    border-radius: 8px;
}

.row {
    display: flex;
    justify-content: space-between;
    margin: 12px 0;
}

.total {
    font-weight: bold;
}

/* ===============================
   LOGIN CARD
================================*/
.login-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.login-card {
    background: var(--card-bg);
    padding: 40px;
    border-radius: 12px;
    width: 100%;
    max-width: 420px;
}

.login-card h3 {
    text-align: center;
    margin-bottom: 30px;
}

.input-group {
    position: relative;
    margin-bottom: 20px;
}

.input-group i:first-child {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--esewa-green);
}

.input-group input {
    width: 100%;
    padding: 14px 14px 14px 45px;
    background: var(--input-bg);
    border: 1px solid #444;
    border-radius: 6px;
    color: #fff;
}

.login-btn {
    width: 100%;
    padding: 14px;
    background: #12900c;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    color:#fff;
}

.login-btn:hover {
    background: #066b03;
}

.error-text {
    background: rgba(255,77,77,0.1);
    color: #ff4d4d;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
    text-align: center;
}
</style>
</head>

<body>

<header class="navbar">
    <div class="logo">eSewa</div>
</header>

<main class="container">

<!-- ===============================
     LEFT SIDE (PAYMENT INFO)
================================-->
<section class="payment-info">

    <div class="merchant-header">
        <div class="merchant-icon">e-</div>
        <span>EPAYTEST (Sasin Elite Gym)</span>
    </div>

    <div class="amount-display">
        <p>Total Amount</p>
        <h1>NPR <?= number_format($plan_amount, 2) ?></h1>
    </div>

    <div class="details-table">
        <div class="row">
            <span>Product Name</span>
            <span><?= htmlspecialchars($plan_name) ?></span>
        </div>

        <div class="row total">
            <span>Total Amount</span>
            <span><?= number_format($plan_amount, 2) ?></span>
        </div>
    </div>

</section>

<!-- ===============================
     RIGHT SIDE (LOGIN)
================================-->
<section class="login-section">

    <div class="login-card">

        <h3>Sign in to your account</h3>

        <?php if ($message != ""): ?>
            <div class="error-text">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="esewa_id" placeholder="eSewa ID" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="mpin" placeholder="Password / MPIN" required>
            </div>

            <input type="hidden" name="amount" value="<?= $plan_amount ?>">
            <input type="hidden" name="plan" value="<?= $plan_name ?>">

            <button type="submit" class="login-btn">
                LOGIN & PAY
            </button>

        </form>

    </div>

    <a href="payment.php" style="margin-top:20px;color:#aaa;">
        CANCEL PAYMENT
    </a>

</section>

</main>

</body>
</html>
