<?php
session_start();
require "config.php";

if(!isset($_SESSION['admin_username'])){
    header("Location: adminlogin.php");
    exit();
}

/* ===================== DELETE MEMBER ===================== */
if(isset($_GET['delete_member'])){
    $id = (int)$_GET['delete_member'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='user'");
    header("Location: admin_dashboard.php"); 
    exit();
}

/* ===================== DELETE TRAINER ===================== */
if(isset($_GET['delete_trainer'])){
    $id = (int)$_GET['delete_trainer'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='trainer'");
    header("Location: admin_dashboard.php"); 
    exit();
}

/* ===================== STATS ===================== */
$totalMembers  = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'")->fetch_assoc()['total'];
$totalTrainers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='trainer'")->fetch_assoc()['total'];
$totalRevenue  = 280000; // demo
$newMembers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user' AND MONTH(created_at)=MONTH(CURDATE())")->fetch_assoc()['total'];

/* ===================== GRAPH DATA ===================== */
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
<link rel="icon" type="image/png" href="images/fav.png">
<link rel="stylesheet" href="admin_dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>GYM ADMIN</h2>
    <a class="active" onclick="show('dashboard')">Dashboard</a>
    <a onclick="show('members')">Members</a>
    <a onclick="show('trainers')">Trainers</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <span>Welcome, <?= $_SESSION['admin_username'] ?></span>
    <button onclick="window.location.href='logout.php'">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </button>
</div>

<!-- CONTENT -->
<div class="content">

<!-- DASHBOARD HEADER -->
<div class="dashboard-header">
    <h1>Admin <span>Dashboard</span></h1>
    <p>Overview of members, trainers & gym performance</p>
</div>

<!-- DASHBOARD CARDS -->
<div id="dashboard" class="dashboard-cards">
    <div class="card card1" onclick="show('members')">
        <div class="card-icon"><i class="fa-solid fa-users"></i></div>
        <div class="card-text">
            <div class="card-title">Total Members</div>
            <div class="card-value"><?= $totalMembers ?></div>
        </div>
    </div>
    <div class="card card2" onclick="show('trainers')">
        <div class="card-icon"><i class="fa-solid fa-user-tie"></i></div>
        <div class="card-text">
            <div class="card-title">Total Trainers</div>
            <div class="card-value"><?= $totalTrainers ?></div>
        </div>
    </div>
    <div class="card card3">
        <div class="card-icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="card-text">
            <div class="card-title">Revenue</div>
            <div class="card-value">Rs <?= $totalRevenue ?></div>
        </div>
    </div>
    <div class="card card4">
        <div class="card-icon"><i class="fa-solid fa-user-plus"></i></div>
        <div class="card-text">
            <div class="card-title">New Members This Month</div>
            <div class="card-value"><?= $newMembers ?></div>
        </div>
    </div>
</div>

<!-- MEMBERS LIST -->
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
            <th>Subscription</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        $today = date('Y-m-d');
        $members = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY username ASC");
        while($m = $members->fetch_assoc()):
           $status = (isset($m['subscription_end_date']) && $m['subscription_end_date'] < $today) ? "Expired" : "Active";
            $color = ($status=="Expired") ? "#ff4444" : "#17e744";

        ?>
        <tr>
            <td><?= $sn++ ?></td>
            <td><?= $m['username'] ?></td>
            <td><?= $m['email'] ?></td>
            <td><?= $m['contact'] ?></td>
            <td style="color:<?= $color ?>"><?= $status ?></td>
            <td>
                <a href="edit_user.php?id=<?= $m['id'] ?>" class="btn">Edit</a>
                <a href="admin_dashboard.php?delete_member=<?= $m['id'] ?>" class="btn delete" onclick="return confirm('Delete this member?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- TRAINERS LIST -->
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
                <a href="admin_dashboard.php?delete_trainer=<?= $t['id'] ?>" class="btn delete" onclick="return confirm('Delete this trainer?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- CHARTS -->
<div id="chartsSection">
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
</div>

<script>
function show(id){
    $('#dashboard, #members, #trainers').hide();
    if(id === 'dashboard') $('#chartsSection').show();
    else $('#chartsSection').hide();
    $('#' + id).show();
}

// Member search
$('#memberSearch').on('keyup', function(){
    var value = $(this).val().toLowerCase();
    $("#membersTable tr").filter(function(index){
        if(index===0) return;
        $(this).toggle($(this).find('td:eq(1)').text().toLowerCase().indexOf(value) > -1);
    });
});

// Show expired only
$('#showExpired').on('change', function(){
    var show = $(this).is(':checked');
    $("#membersTable tr").each(function(index){
        if(index===0) return;
        var status = $(this).find('td:eq(4)').text().trim();
        if(show) $(this).toggle(status === "Expired");
        else $(this).show();
    });
});

// Trainer search
$('#trainerSearch').on('keyup', function(){
    var value = $(this).val().toLowerCase();
    $("#trainersTable tr").filter(function(index){
        if(index===0) return;
        $(this).toggle($(this).find('td:eq(1)').text().toLowerCase().indexOf(value) > -1);
    });
});

// Charts
const ctxGrowth = document.getElementById('growthChart').getContext('2d');
new Chart(ctxGrowth, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {label:'Members', data: <?= json_encode($membersData) ?>, borderColor:'#9b59b6', backgroundColor:'rgba(155,89,182,0.2)', fill:true, tension:0.4},
            {label:'Trainers', data: <?= json_encode($trainersData) ?>, borderColor:'#3498db', backgroundColor:'rgba(52,152,219,0.2)', fill:true, tension:0.4}
        ]
    },
    options: {
        responsive:true,
        plugins:{ legend:{ labels:{ color:'#fff' } } },
        scales:{ x:{ ticks:{ color:'#fff' }, grid:{ color:'#333' } }, y:{ beginAtZero:true, ticks:{ color:'#fff' }, grid:{ color:'#333' } } }
    }
});

const ctxClasses = document.getElementById('classesChart').getContext('2d');
new Chart(ctxClasses,{
    type:'bar',
    data:{
        labels:['Yoga','Cardio','Strength','Zumba'],
        datasets:[
            {label:'Class 1', data:[12,36,30,75], backgroundColor:'#3498db'},
            {label:'Class 2', data:[8,55,24,83], backgroundColor:'#9b59b6'}
        ]
    },
    options:{
        responsive:true,
        plugins:{ legend:{ labels:{ color:'#fff' } } },
        scales:{ x:{ ticks:{ color:'#fff' }, grid:{ color:'#333' } }, y:{ ticks:{ color:'#fff' }, grid:{ color:'#333' } } }
    }
});
</script>

</body>
</html>
