<?php
session_start();
require "config.php";

if(!isset($_SESSION['admin_username'])){
    header("Location: adminlogin.php");
    exit();
}

/* =====================
   DELETE MEMBER
===================== */
if(isset($_GET['delete_member'])){
    $id = (int)$_GET['delete_member'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='user'");
    header("Location: admin_dashboard.php"); 
    exit();
}

/* =====================
   DELETE TRAINER
===================== */
if(isset($_GET['delete_trainer'])){
    $id = (int)$_GET['delete_trainer'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='trainer'");
    header("Location: admin_dashboard.php"); 
    exit();
}

/* =====================
   STATS
===================== */
$totalMembers  = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'")->fetch_assoc()['total'];
$totalTrainers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='trainer'")->fetch_assoc()['total'];
$totalRevenue  = 280000; // demo
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Sasin Elite Gym</title>
<link rel="stylesheet" href="admin_dashboard.css">
<link rel="icon" type="image/png" href="images/fav.png">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>GYM ADMIN</h2>
    <a class="active" onclick="show('dashboard')">Dashboard</a>
    <a onclick="show('members')">Members</a>
    <a onclick="show('trainers')">Trainers</a>
    <a href="logout.php">Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <span>Welcome, <?= $_SESSION['admin_username'] ?></span>
    <button onclick="window.location.href='logout.php'">Logout</button>
</div>

<!-- CONTENT -->
<div class="content">

<!-- ================= DASHBOARD ================= -->
<div id="dashboard">
    <h2>Dashboard</h2>
    <div class="card" onclick="show('members')">Total Members<br><?= $totalMembers ?></div>
    <div class="card" onclick="show('trainers')">Total Trainers<br><?= $totalTrainers ?></div>
    <div class="card">Revenue<br>Rs <?= $totalRevenue ?></div>
</div>

<!-- ================= MEMBERS ================= -->
<div id="members" style="display:none;">
    <h2>Members List</h2>
    <a href="add_user.php?role=user" class="btn">Add Member</a>

    <table>
        <tr>
            <th>S.N</th>
            <th>Username</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>

        <?php
        $sn = 1;
        $members = $conn->query("SELECT * FROM users WHERE role='user'");
        while($m = $members->fetch_assoc()):
        ?>
        <tr>
            <td><?= $sn++ ?></td>
            <td><?= $m['username'] ?></td>
            <td><?= $m['email'] ?></td>
            <td><?= $m['contact'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $m['id'] ?>" class="btn">Edit</a>
                <a href="admin_dashboard.php?delete_member=<?= $m['id'] ?>" 
                   class="btn delete"
                   onclick="return confirm('Delete this member?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- ================= TRAINERS ================= -->
<div id="trainers" style="display:none;">
    <h2>Trainers List</h2>
    <a href="add_user.php?role=trainer" class="btn">Add Trainer</a>

    <table>
        <tr>
            <th>S.N</th>
            <th>Username</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>

        <?php
        $sn = 1;
        $trainers = $conn->query("SELECT * FROM users WHERE role='trainer'");
        while($t = $trainers->fetch_assoc()):
        ?>
        <tr>
            <td><?= $sn++ ?></td>
            <td><?= $t['username'] ?></td>
            <td><?= $t['email'] ?></td>
            <td><?= $t['contact'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $t['id'] ?>" class="btn">Edit</a>
                <a href="admin_dashboard.php?delete_trainer=<?= $t['id'] ?>" 
                   class="btn delete"
                   onclick="return confirm('Delete this trainer?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</div>

<!-- JS -->
<script>
function show(id){
    document.getElementById('dashboard').style.display='none';
    document.getElementById('members').style.display='none';
    document.getElementById('trainers').style.display='none';
    document.getElementById(id).style.display='block';
}
</script>

</body>
</html>
