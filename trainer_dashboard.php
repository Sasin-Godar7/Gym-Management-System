<?php
session_start();
require "config.php"; 

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// --- INTEGRATED ACTION LOGIC (From trainer_action.php) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action'];
    
    // Logic from your action file:
    $status = ($action == 'approve') ? 'approved' : 'rejected';
    
    // Added is_seen=0 so the user knows their booking status changed
    $stmt = $conn->prepare("UPDATE trainer_bookings SET status=?, is_seen=0 WHERE id=? AND trainer_id=?");
    $stmt->bind_param("sii", $status, $booking_id, $trainer_id);
    
    if($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=updated");
        exit();
    }
}

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

// Stats Calculation
$total_res = $conn->query("SELECT COUNT(*) as count FROM trainer_bookings WHERE trainer_id=$trainer_id")->fetch_assoc();
$pending_res = $conn->query("SELECT COUNT(*) as count FROM trainer_bookings WHERE trainer_id=$trainer_id AND status='pending'")->fetch_assoc();

// Profile Image Fetch
$user_data = $conn->query("SELECT profile_pic FROM users WHERE id=$trainer_id")->fetch_assoc();
$user_img = $user_data['profile_pic'];

$bookings = $conn->query("SELECT tb.*, u.username, u.contact FROM trainer_bookings tb JOIN users u ON tb.user_id = u.id WHERE tb.trainer_id = $trainer_id ORDER BY tb.booking_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sasin Elite | Trainer Dashboard</title>
    <link rel="icon" type="image/png" href="Images/fav.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #32cc11;
            --primary-dark: #28a70d;
            --bg: #050505;
            --panel: #0d0d0d;
            --border: #1a1a1a;
            --text-dim: #888;
            --danger: #ff3d00;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); color: #fff; padding-top: 100px; padding-bottom: 50px; }

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

        .profile-trigger {
            width: 48px; height: 48px; border-radius: 50%; border: 2px solid var(--primary);
            overflow: hidden; cursor: pointer; transition: 0.3s;
            background: #111; display: flex; align-items: center; justify-content: center;
        }
        .profile-trigger:hover { transform: scale(1.1); border-color: #fff; }
        .profile-trigger img { width: 100%; height: 100%; object-fit: cover; }
        .profile-trigger i { font-size: 24px; color: var(--text-dim); }

        .logout-btn {
            background: var(--danger); color: #fff;
            padding: 10px 18px; border-radius: 8px; font-weight: 800;
            text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 8px;
            transition: 0.3s;
        }
        .logout-btn:hover { opacity: 0.8; }

        .main { max-width: 1200px; margin: 0 auto; padding: 20px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--panel); padding: 25px; border-radius: 16px; border: 1px solid var(--border); }
        .stat-card h3 { color: var(--text-dim); font-size: 12px; text-transform: uppercase; margin-bottom: 10px; }
        .stat-card p { font-size: 28px; font-weight: 800; color: var(--primary); }

        /* Table Design */
        .data-card { background: var(--panel); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111; padding: 20px; text-align: left; font-size: 11px; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1.5px; }
        td { padding: 22px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:hover { background: rgba(255,255,255,0.02); }

        /* Status Styling */
        .st-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 15px; border-radius: 50px; font-weight: 800; font-size: 11px;
        }
        .st-badge.pending { color: #FFB300; background: rgba(255, 179, 0, 0.1); }
        .st-badge.approved { color: var(--primary); background: rgba(50, 204, 17, 0.1); }
        .st-badge.rejected { color: var(--danger); background: rgba(255, 61, 0, 0.1); }

        /* Action Buttons */
        .action-btns { display: flex; gap: 10px; }
        .btn-act { 
            padding: 8px 12px; border-radius: 6px; border: none; cursor: pointer; 
            font-weight: 700; font-size: 11px; transition: 0.2s; color: #fff;
        }
        .btn-approve { background: var(--primary); }
        .btn-reject { background: #333; }
        .btn-approve:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .btn-reject:hover { background: var(--danger); transform: translateY(-2px); }

        .client-info b { display: block; font-size: 16px; margin-bottom: 2px; color: var(--primary); }
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

        <div class="profile-trigger" onclick="document.getElementById('pInput').click();" title="Update Profile Photo">
            <?php if(!empty($user_img) && file_exists("uploads/profile_pics/".$user_img)): ?>
                <img src="uploads/profile_pics/<?= $user_img ?>">
            <?php else: ?>
                <i class="fas fa-user-circle"></i>
            <?php endif; ?>
        </div>
        
        <a href="logout.php" class="logout-btn">
            LOGOUT <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>

<div class="main">
    <h2 style="margin-bottom: 25px; font-size: 40px; font-family: 'Orbitron', sans-serif;">
        Trainer <span style="color:var(--primary);">Dashboard</span>
    </h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Bookings</h3>
            <p><?= $total_res['count'] ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Requests</h3>
            <p style="color: #FFB300;"><?= $pending_res['count'] ?></p>
        </div>
        <div class="stat-card">
            <h3>Active Status</h3>
            <p style="font-size: 14px;">Online & Accepting</p>
        </div>
    </div>
    
    <div class="data-card">
        <table>
            <thead>
                <tr>
                    <th>Client Information</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($bookings->num_rows > 0): ?>
                    <?php while ($b = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td class="client-info">
                            <b><?= $b['username'] ?></b>
                            <span><i class="fas fa-phone-alt"></i> <?= $b['contact'] ?></span>
                        </td>
                        <td><div style="font-weight:600;"><?= date('M d, Y', strtotime($b['booking_date'])) ?></div></td>
                        <td><div style="font-weight:600; color: #ccc;"><?= $b['booking_time'] ?></div></td>
                        <td>
                            <div class="st-badge <?= strtolower($b['status']) ?>">
                                <i class="fas fa-circle" style="font-size: 7px;"></i>
                                <?= strtoupper($b['status']) ?>
                            </div>
                        </td>
                        <td>
                            <?php if(strtolower($b['status']) == 'pending'): ?>
                                <div class="action-btns">
                                    <form method="POST">
                                        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn-act btn-approve">ACCEPT</button>
                                        <button type="submit" name="action" value="reject" class="btn-act btn-reject">REJECT</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span style="font-size: 11px; color: var(--text-dim);"><i class="fas fa-check-circle"></i> Decision Made</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; color:var(--text-dim); padding: 50px;">No bookings found in your schedule.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

