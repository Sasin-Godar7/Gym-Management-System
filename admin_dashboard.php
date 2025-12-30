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

$newMembers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user' AND MONTH(created_at)=MONTH(CURDATE())")->fetch_assoc()['total'];

/* =====================
   GRAPH DATA (MONTH WISE)
===================== */

$labels = [];
$membersData = [];
$trainersData = [];

$memberCount = 0;
$trainerCount = 0;

$sql = "
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as ym,
    SUM(role='user') as members,
    SUM(role='trainer') as trainers
FROM users
GROUP BY ym
ORDER BY ym
";

$res = $conn->query($sql);

while($row = $res->fetch_assoc()){
    $labels[] = date("M Y", strtotime($row['ym'].'-01'));

    $memberCount += $row['members'];
    $trainerCount += $row['trainers'];

    $membersData[] = $memberCount;
    $trainersData[] = $trainerCount;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Sasin Elite Gym</title>
<link rel="stylesheet" href="admin_dashboard.css">
<link rel="icon" type="image/png" href="images/fav.png">
<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>GYM ADMIN</h2>
    <a class="active" onclick="show('dashboard')">Dashboard</a>
    <a onclick="show('members')">Members</a>
    <a onclick="show('trainers')">Trainers</a>
    <a href="logout.php"> <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:6px;">
        <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.104 0 2-.896 2-2V5c0-1.104-.896-2-2-2z"/>
    </svg>Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <span>Welcome, <?= $_SESSION['admin_username'] ?></span>
    <button onclick="window.location.href='logout.php'">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:6px;">
        <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.104 0 2-.896 2-2V5c0-1.104-.896-2-2-2z"/>
    </svg>
    Logout
</button>

</div>

<!-- CONTENT -->
<div class="content">

<!-- ================= DASHBOARD ================= -->
<div id="dashboard">
    <h2>Dashboard</h2>
    <div class="card card1" onclick="show('members')">
        Total Members<br><?= $totalMembers ?>
    </div>
    <div class="card card2" onclick="show('trainers')">
        Total Trainers<br><?= $totalTrainers ?>
    </div>
    <div class="card card3">
        Revenue<br>Rs <?= $totalRevenue ?>
    </div>
    <div class="card card4">
        New Members This Month<br><?= $newMembers ?>
    </div>


</div>

<!-- ================= MEMBERS ================= -->
<div id="members" style="display:none;">
    <h2>Members List</h2>
    <a href="add_user.php?role=user" class="btn">Add Member</a>
    <input type="text" id="memberSearch" placeholder="Search Member..." class="searchBox">

    <table id="membersTable">
        <tr>
            <th>S.N</th>
            <th>Username</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        $members = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY username ASC");
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
                   class="btn delete" onclick="return confirm('Delete this member?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- ================= TRAINERS ================= -->
<div id="trainers" style="display:none;">
    <h2>Trainers List</h2>
    <a href="add_user.php?role=trainer" class="btn">Add Trainer</a>
    <input type="text" id="trainerSearch" placeholder="Search Trainer..." class="searchBox">

    <table id="trainersTable">
        <tr>
            <th>S.N</th>
            <th>Username</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        $trainers = $conn->query("SELECT * FROM users WHERE role='trainer' ORDER BY username ASC");
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
                   class="btn delete" onclick="return confirm('Delete this trainer?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

  <!-- Charts -->
        <div class="charts">
            <div class="chart-container">
                <h3>Members & Trainers Growth</h3>
                <canvas id="growthChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Active Classes</h3>
                <canvas id="classesChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h3>Recent Activity Feed</h3>
            <ul>
                <li><span class="purple">Jane Doe joined</span> - 1</li>
                <li><span class="cyan">Yoga class update</span> - 5</li>
            </ul>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Members & Trainers Growth

        const ctxGrowth = document.getElementById('growthChart').getContext('2d');

new Chart(ctxGrowth, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Members',
                data: <?= json_encode($membersData) ?>,
                borderColor: '#9b59b6',
                backgroundColor: 'rgba(155,89,182,0.2)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Trainers',
                data: <?= json_encode($trainersData) ?>,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,0.2)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#fff' } }
        },
        scales: {
            x: {
                ticks: { color: '#fff' },
                grid: { color: '#333' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#fff' },
                grid: { color: '#333' }
            }
        }
    }
});
      
        // Active Classes
        const ctxClasses = document.getElementById('classesChart').getContext('2d');
        const classesChart = new Chart(ctxClasses, {
            type: 'bar',
            data: {
                labels: ['Yoga','Cardio','Strength','Zumba'],
                datasets: [
                    {
                        label: 'Class 1',
                        data: [12, 36, 30, 75],
                        backgroundColor: '#3498db'
                    },
                    {
                        label: 'Class 2',
                        data: [8, 55, 24, 83],
                        backgroundColor: '#9b59b6'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#fff' } } },
                scales: {
                    x: { ticks: { color: '#fff' }, grid: { color: '#333' } },
                    y: { ticks: { color: '#fff' }, grid: { color: '#333' } }
                }
            }
        });
    </script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function show(id){
    // Hide all sections first
    $('#dashboard, #members, #trainers').hide();
    // Show the requested section
    $('#' + id).show();
}

// Optional: Member search
$('#memberSearch').on('keyup', function(){
    var value = $(this).val().toLowerCase();
    $("#membersTable tr").filter(function(index){
        if(index === 0) return; // skip header
        $(this).toggle($(this).find('td:eq(1)').text().toLowerCase().indexOf(value) > -1);
    });
});

// Optional: Trainer search
$('#trainerSearch').on('keyup', function(){
    var value = $(this).val().toLowerCase();
    $("#trainersTable tr").filter(function(index){
        if(index === 0) return;
        $(this).toggle($(this).find('td:eq(1)').text().toLowerCase().indexOf(value) > -1);
    });
});

</script>







</body>
</html>
