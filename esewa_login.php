<?php
// esewa_login.php
session_start();
// include 'config.php'; // DB connection if needed

// Session check
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'user'){
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// GET data (from payment.php)
$plan_amount = isset($_GET['amount']) ? $_GET['amount'] : 0;
$plan_name = isset($_GET['plan']) ? $_GET['plan'] : 'Membership';

$message = "";
$success = "";

// Form processing logic (if needed on this page, otherwise it goes to esewa_success.php)
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
        :root {
            --bg-dark: #1a1d21;
            --card-bg: #2c3036;
            --esewa-green: #60bb46;
            --amount-green: #a4c639;
            --input-bg: #1a1d21;
            --text-gray: #aaa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, sans-serif; }

        body { 
            background-color: var(--bg-dark); 
            color: #fff; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
        }

        /* Navbar */
        .navbar { 
            display: flex; 
            justify-content: space-between; 
            padding: 20px 8%; 
            align-items: center; 
        }
        .logo { font-size: 24px; font-weight: bold; letter-spacing: 1px; }

        /* Main Container */
        .container { 
            display: flex; 
            flex: 1; 
            max-width: 1100px; 
            margin: 0 auto; 
            width: 90%; 
            align-items: center; 
            gap: 60px; 
            padding: 40px 0;
        }

        /* Left Side: Payment Info */
        .payment-info { flex: 1; }
        .merchant-header { display: flex; align-items: center; gap: 12px; margin-bottom: 35px; }
        .merchant-icon { 
            background-color: var(--esewa-green); 
            width: 45px; height: 45px; 
            border-radius: 50%; 
            display: flex; justify-content: center; align-items: center; 
            font-weight: bold; font-size: 1.2rem;
        }
        .merchant-name { font-size: 1.1rem; font-weight: 500; }

        .amount-display p { color: var(--text-gray); font-size: 15px; }
        .amount-display h1 { 
            font-size: 38px; 
            color: var(--amount-green); 
            margin: 8px 0 35px 0; 
            font-weight: 600;
        }

        .details-table { 
            background: rgba(255, 255, 255, 0.04); 
            padding: 25px; 
            border-radius: 8px; 
            border: 1px solid rgba(255,255,255,0.05);
        }
        .row { display: flex; justify-content: space-between; margin: 12px 0; color: #ddd; font-size: 15px;}
        hr { border: 0; border-top: 1px solid #444; margin: 18px 0; }
        .total { font-weight: bold; color: #fff; font-size: 17px; }

        /* Right Side: Login Card */
        .login-section { flex: 1; display: flex; flex-direction: column; align-items: center; }
        .login-card { 
            background-color: var(--card-bg); 
            padding: 45px; 
            border-radius: 12px; 
            width: 100%; 
            max-width: 420px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .login-card h3 { margin-bottom: 30px; font-weight: 400; color: #eee; text-align: center; }

        .input-group { position: relative; margin-bottom: 22px; }
        .input-group i:first-child { 
            position: absolute; left: 18px; top: 50%; 
            transform: translateY(-50%); color: var(--esewa-green); 
        }
        .input-group .eye-icon { 
            position: absolute; right: 18px; top: 50%; 
            transform: translateY(-50%); color: #666; cursor: pointer; 
        }
        .input-group input { 
            width: 100%; 
            padding: 16px 15px 16px 50px; 
            background-color: var(--input-bg); 
            border: 1px solid #444; 
            border-radius: 6px; 
            color: white; 
            outline: none; 
            transition: 0.3s;
        }
        .input-group input:focus { border-color: var(--esewa-green); }

        .login-btn { 
            width: 100%; 
            padding: 16px; 
            background-color: #12900c; 
            border: none; 
            border-radius: 6px; 
            color: #fafcff; 
            font-weight: 700; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 10px; 
            transition: 0.3s;
        }
        .login-btn:hover { background-color: #066b03; transform: translateY(-1px); }

        .forgot-link { display: block; text-align: center; margin-top: 25px; color: var(--amount-green); text-decoration: none; font-size: 14px; }
        .register-text { margin-top: 35px; font-size: 14px; color: var(--text-gray); }
        .register-text a { color: var(--amount-green); text-decoration: none; font-weight: 500; }
        .cancel-link { margin-top: 45px; color: var(--text-gray); text-decoration: none; font-size: 13px; letter-spacing: 1px; font-weight: 500; }
        .cancel-link:hover { color: #fff; }

        /* Messages */
        .error-text { background: rgba(255, 77, 77, 0.1); color: #ff4d4d; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; font-size: 14px; }
        .success-text { background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; font-size: 14px; }

        /* Responsive */
        @media (max-width: 850px) {
            .container { flex-direction: column; gap: 40px; text-align: center; }
            .merchant-header { justify-content: center; }
            .details-table { max-width: 420px; margin: 0 auto; }
            .navbar { padding: 20px 5%; }
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="logo">eSewa</div>
</header>

<main class="container">
    <section class="payment-info">
        <div class="merchant-header">
            <div class="merchant-icon">e-</div>
            <span class="merchant-name">EPAYTEST (Sasin Elite Gym)</span>
        </div>

        <div class="amount-display">
            <p>Total Amount</p>
            <h1>NPR <?= htmlspecialchars(number_format($plan_amount, 2)) ?></h1>
        </div>

        <div class="details-table">
            <div class="row">
                <span>Product Name</span>
                <span><?= htmlspecialchars($plan_name) ?></span>
            </div>
            <div class="row">
                <span>Product Amount</span>
                <span><?= htmlspecialchars(number_format($plan_amount, 2)) ?></span>
            </div>
            <hr>
            <div class="row total">
                <span>Total Amount</span>
                <span><?= htmlspecialchars(number_format($plan_amount, 2)) ?></span>
            </div>
        </div>
    </section>

    <section class="login-section">
        <div class="login-card">
            <h3>Sign in to your account</h3>

            <?php if($message != ""): ?>
                <div class="error-text"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <?php if($success != ""): ?>
                <div class="success-text"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="esewa_success.php">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="esewa_id" placeholder="eSewa ID" required autocomplete="off">
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="mpin" placeholder="Password / MPIN" required>
                    <i class="fas fa-eye-slash eye-icon"></i>
                </div>

                <input type="hidden" name="amount" value="<?= htmlspecialchars($plan_amount) ?>">
                <input type="hidden" name="plan" value="<?= htmlspecialchars($plan_name) ?>">

                <button type="submit" class="login-btn">LOGIN & PAY</button>
            </form>

            <a href="#" class="forgot-link">Forgot Password?</a>
        </div>

        <p class="register-text">Don't have an account? <a href="#">Register</a></p>
        <a href="payment.php" class="cancel-link">CANCEL PAYMENT</a>
    </section>
</main>

<script>
    // Eye icon toggle for password
    const eyeIcon = document.querySelector('.eye-icon');
    const passwordInput = document.querySelector('input[name="mpin"]');

    eyeIcon.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>