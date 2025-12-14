<?php
session_start();
require "config.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!='trainer'){
    header("Location: login.php");
    exit();
}

$members = $conn->query("SELECT * FROM users WHERE role='user'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trainer Dashboard</title>
<link rel="icon" type="image/png" href="images/fav.png">
<style>
    body {
        margin: 0;
        font-family: Poppins, sans-serif;
        background: #f5f7fa;
        display: flex;
    }

    /* ------------------- SIDEBAR ------------------- */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #1f1f1f;
        padding: 20px;
        color: white;
        position: fixed;
        left: 0;
        top: 0;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 22px;
        color: #32cc11;
    }

    .sidebar a {
        display: block;
        padding: 12px 15px;
        margin: 8px 0;
        text-decoration: none;
        background: #292929;
        color: #ddd;
        border-radius: 6px;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: #32cc11;
        color: #fff;
    }

    /* ------------------- MAIN CONTENT ------------------- */
    .main {
        margin-left: 260px;
        padding: 25px;
        width: calc(100% - 260px);
    }

    .topbar {
        background: white;
        padding: 15px 25px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .topbar h1 {
        margin: 0;
        font-size: 26px;
        color: #333;
    }

    .logout-btn {
        background: #e63946;
        color: white;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
    }

    .logout-btn:hover {
        background: #c82333;
    }

    /* ------------------- TABLE ------------------- */
    .card {
        background: white;
        padding: 20px;
        margin-top: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table th {
        background: #32cc11;
        color: #fff;
        padding: 12px;
        font-size: 16px;
    }

    table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    table tr:nth-child(even) {
        background: #f9f9f9;
    }

    .btn {
        padding: 7px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        font-weight: bold;
    }

    .btn.info { background: #2196f3; }
    .btn.diet { background: #28a745; }

    .btn:hover { opacity: 0.85; }

</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Trainer Panel</h2>
    <a href="#">Dashboard</a>
    <a href="#">Members</a>
    <a href="#">Diet Plans</a>
    <a href="#">Workout Plans</a>
    <a href="#">Settings</a>
</div>

<!-- MAIN CONTENT -->
<div class="main">
    <div class="topbar">
        <h1>Trainer Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="card">
        <h2>Members List</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>

            <?php while($m = $members->fetch_assoc()): ?>
            <tr>
                <td><?= $m['id'] ?></td>
                <td><?= $m['username'] ?></td>
                <td><?= $m['email'] ?></td>
                <td><?= $m['contact'] ?></td>
                <td>
                    <a href="view_member.php?id=<?= $m['id'] ?>" class="btn info">Personal Info</a>
                    <a href="diet_plan.php?id=<?= $m['id'] ?>" class="btn diet">Diet Plan</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
