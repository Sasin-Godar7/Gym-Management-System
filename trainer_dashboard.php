<?php
session_start();
require "config.php"; 

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// --- IMAGE UPLOAD LOGIC ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $target_dir = "uploads/profile_pics/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_ext = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
    $filename = "trainer_" . $trainer_id . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $conn->query("UPDATE users SET profile_pic='$filename' WHERE id=$trainer_id");
        header("Location: " . $_SERVER['PHP_SELF']); 
        exit();
    }
}

// Profile Image Fetch
$user_data = $conn->query("SELECT profile_pic FROM users WHERE id=$trainer_id")->fetch_assoc();
$user_img = $user_data['profile_pic'];

$bookings = $conn->query("SELECT tb.*, u.username, u.contact FROM trainer_bookings tb JOIN users u ON tb.user_id = u.id WHERE tb.trainer_id = $trainer_id ORDER BY tb.booking_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sasin Elite | Dashboard</title>
    <link rel="icon" type="image/png" href="Images/fav.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #32cc11;
            --bg: #050505;
            --panel: #0d0d0d;
            --border: #1a1a1a;
            --text-dim: #888;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: #fff; padding-top: 100px; }

        /* Fixed Navbar */
        .nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 5%; position: fixed; width: 100%; top: 0; z-index: 1000;
            height: 85px; background: rgba(0, 0, 0, 0.95); border-bottom: 1px solid var(--border);
            backdrop-filter: blur(15px);
        }
        .logo img { width: 160px; }

        .nav-right { display: flex; align-items: center; gap: 20px; }
        .welcome { font-weight: 700; font-size: 14px; letter-spacing: 0.5px; }
        .welcome span { color: var(--primary); }

        /* Clickable Profile Image */
        .profile-trigger {
            width: 48px; height: 48px; border-radius: 50%; border: 2px solid var(--primary);
            overflow: hidden; cursor: pointer; transition: 0.3s;
            background: #111; display: flex; align-items: center; justify-content: center;
        }

        .profile-trigger img { width: 100%; height: 100%; object-fit: cover; }
        .profile-trigger i { font-size: 20px; color: #fff; }

        .logout-btn {
            background: rgba(77, 239, 28, 0.89); color: #ffffffff;
            padding: 10px 18px; border-radius: 8px; font-weight: 800;
            text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 8px;
            border: 1px solid rgba(255, 61, 0, 0.2); transition: 0.3s;
        }
        .logout-btn:hover { background: #0b8111ff; color: #fff; }

        .main { max-width: 1200px; margin: 0 auto; padding: 20px; }
        

        /* Table Design */
        .data-card { background: var(--panel); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111; padding: 20px; text-align: left; font-size: 11px; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1.5px; }
        td { padding: 22px 20px; border-bottom: 1px solid var(--border); }

        /* Status Styling */
        .st-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 15px; border-radius: 50px; font-weight: 800; font-size: 11px;
        }
        .st-badge.pending { color: #FFB300; background: rgba(255, 179, 0, 0.1); }
        .st-badge.approved { color: var(--primary); background: rgba(50, 204, 17, 0.1); }
        .st-badge.rejected { color: #FF3D00; background: rgba(255, 61, 0, 0.1); }

        .time-col { font-weight: 600;  font-size: 13px; }
        .date-col { font-weight: 600; font-size: 14px; }
        .client-info b { display: block; font-size: 16px; margin-bottom: 2px; color:rgba(23, 242, 48, 1); }
        .client-info span { font-size: 12px; color: var(--text-dim); }
    </style>
</head>
<body>

<nav class="nav">
    <div class="logo"><img src="Images/fulllogo.png" alt="Sasin Elite"></div>
    <div class="nav-right">
        <div class="welcome">WELCOME, <span><?= strtoupper($username) ?></span></div>
        
        <form id="pForm" method="POST" enctype="multipart/form-data" style="display:none;">
            <input type="file" name="profile_pic" id="pInput" onchange="document.getElementById('pForm').submit();">
        </form>

        <div class="profile-trigger" onclick="document.getElementById('pInput').click();" title="Click to Change Photo">
            <?php if(!empty($user_img) && file_exists("uploads/profile_pics/".$user_img)): ?>
                <img src="uploads/profile_pics/<?= $user_img ?>">
            <?php else: ?>
                <i class="fas fa-camera"></i>
            <?php endif; ?>
        </div>
        
        <a href="logout.php" class="logout-btn">
            LOGOUT <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>

<div class="main">
    <h2 style="margin-bottom: 25px; letter-spacing: 1px; font-size:1.5rem;  font-size: 40px;">Trainer<span style="color:rgba(23, 242, 48, 1);"> Dashboard</span></h2>
    
    <div class="data-card">
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($b = $bookings->fetch_assoc()): ?>
                <tr>
                    <td class="client-info">
                        <b><?= $b['username'] ?></b>
                        <span><i class="fas fa-phone-alt" style="font-size:10px;"></i> <?= $b['contact'] ?></span>
                    </td>
                    <td class="date-col"><?= date('M d, Y', strtotime($b['booking_date'])) ?></td>
                    <td class="time-col"><?= $b['booking_time'] ?></td>
                    <td>
                        <div class="st-badge <?= strtolower($b['status']) ?>">
                            <i class="fas fa-circle" style="font-size: 7px;"></i>
                            <?= strtoupper($b['status']) ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>