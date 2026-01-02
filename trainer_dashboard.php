<?php session_start();
require "config.php";

if( !isset($_SESSION['username']) || $_SESSION['role'] !='trainer') {
  header("Location: login.php");
  exit();
}

$trainer_id =$_SESSION['user_id'];
$today =date('Y-m-d');

// Stats
$total =$conn->query("SELECT COUNT(*) c FROM trainer_bookings WHERE trainer_id=$trainer_id")->fetch_assoc()['c'];
$approved =$conn->query("SELECT COUNT(*) c FROM trainer_bookings WHERE trainer_id=$trainer_id AND status='Approved'")->fetch_assoc()['c'];
$pending =$conn->query("SELECT COUNT(*) c FROM trainer_bookings WHERE trainer_id=$trainer_id AND status='Pending'")->fetch_assoc()['c'];

// Filters
$search =$_GET['search'] ?? '';
$statusFilter =$_GET['status'] ?? '';

$where ="tb.trainer_id=$trainer_id";

if($search) {
  $where .=" AND u.username LIKE '%$search%'";
}

if($statusFilter) {
  $where .=" AND tb.status='$statusFilter'";
}

$bookings =$conn->query("
 SELECT tb.*, u.username, u.email, u.contact FROM trainer_bookings tb JOIN users u ON tb.user_id=u.id WHERE $where ORDER BY tb.booking_date DESC ");
 ?>
<!DOCTYPE html>
  <html>

  <head>
    <title>Trainer Dashboard | Sasin Elite Gym</title>
     <link rel="icon" type="image/png" href="Images/fav.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Poppins, sans-serif;
      }

      body {
        background: #0f0f0f;
        color: #fff
      }

      /* Navbar */
      .navbar {
        background: #111;
        height: 80px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .8)
      }

      .navbar img {
        width: 170px
      }

      .nav-right {
        display: flex;
        align-items: center;
        gap: 20px
      }

      .nav-right span {
        color: #32cc11;
        font-weight: 600
      }

      /* Home Icon bigger */
      .home-icon {
        padding: 10px 14px;
        border-radius: 10px;
        transition: 0.2s;
        font-weight: 700;
        text-decoration: none;
        color: #ffffffff;
      }

      .logout {
        background: #32cc11;
        color: #fff;
        padding: 8px 22px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 800;
      }

      /* Profile Card */
      .profile {
        max-width: 1200px;
        margin: 40px auto;
        display: flex;
        gap: 30px;
        align-items: center;
        padding: 30px;
        border-radius: 20px;
        background: #161616;
        box-shadow: 0 5px 20px rgba(122, 252, 93, 0.2)
      }

      .avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #32cc11;
        color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700
      }

      .profile h2 {
        margin-bottom: 8px
      }

      .profile p {
        color: #aaa
      }

      /* Stats */
      .stats {
        max-width: 1200px;
        margin: 20px auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px
      }

      .stat {
        background: #161616;
        padding: 22px;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .6)
      }

      .stat h3 {
        font-size: 34px;
        color: #32cc11
      }

      .stat p {
        color: #aaa
      }

      /* Filters */
      .filters {
        max-width: 1200px;
        margin: 30px auto;
        display: flex;
        gap: 15px;
        flex-wrap: wrap
      }

      .filters input,
      .filters select {
        padding: 10px 14px;
        border-radius: 10px;
        border: none;
        background: #1e1e1e;
        color: #fff
      }

      .filters button {
        background: #32cc11;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer
      }

      /* Table */
      .table-wrap {
        max-width: 1200px;
        margin: 30px auto;
        overflow-x: auto
      }

      table {
        width: 100%;
        border-collapse: collapse;
        background: #161616;
        border-radius: 15px;
        overflow: hidden
      }

      th,
      td {
        padding: 14px 16px
      }

      th {
        background: #32cc11;
        color: #000
      }

      td {
        border-bottom: 1px solid #333
      }

      tr.today {
        background: #1f2f1f
      }

      /* Status */
      .badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600
      }

      .pending {
        background: #ff9800;
        color: #000
      }

      .approved {
        background: #32cc11;
        color: #000
      }

      .rejected {
        background: #f44336;
        color: #fff
      }

      /* Buttons */
      .btn {
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600
      }

      .approve {
        background: #32cc11;
        color: #000
      }

      .reject {
        background: #f44336;
        color: #fff
      }

      .empty {
        text-align: center;
        color: #aaa;
        padding: 40px
      }
    </style>
  </head>

  <body>
    <div class="navbar"> <img src="Images/fulllogo.png">
      <div class="nav-right"> <span>Welcome,
          <?=$_SESSION['username'] ?>
        </span> <a href="user_dashboard.php"><i class="fas fa-home fa-xl home-icon"></i></a>
         <a href="logout.php"
          class="logout"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 24 24" style="vertical-align:middle; margin-right:6px;">
        <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8c1.104 0 2-.896 2-2V5c0-1.104-.896-2-2-2z"/>
    </svg>Logout</a> 
  </div>
    </div>
    <div class="profile">
      <div class="avatar">
        <?=strtoupper($_SESSION['username'][0]) ?>
      </div>
      <div>
        <h2>
          <?=$_SESSION['username'] ?>
        </h2>
        <p>Certified Gym Trainer</p>
      </div>
    </div>
    <div class="stats">
      <div class="stat">
        <h3>
          <?=$total ?>
        </h3>
        <p>Total Requests</p>
      </div>
      <div class="stat">
        <h3>
          <?=$approved ?>
        </h3>
        <p>Approved Sessions</p>
      </div>
      <div class="stat">
        <h3>
          <?=$pending ?>
        </h3>
        <p>Pending Requests</p>
      </div>
    </div>
    <form class="filters" method="get"> <input type="text" name="search" placeholder="Search user..."
        value="<?= $search ?>"> <select name="status">
        <option value="">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select> <button>Filter</button> </form>
    <div class="table-wrap">
      <table>
        <tr>
          <th>User</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
        </tr>
        <?php if($bookings->num_rows==0): ?>
        <tr>
          <td colspan="5" class="empty">No bookings found</td>
        </tr>
        <?php endif; ?>
        <?php while($b=$bookings->fetch_assoc()): ?>
        <tr class="<?= $b['booking_date']==$today?'today':'' ?>">
          <td>
            <?=$b['username'] ?>
          </td>
          <td>
            <?=$b['booking_date'] ?>
          </td>
          <td>
            <?=$b['booking_time'] ?>
          </td>
          <td><span class="badge <?= strtolower($b['status']) ?>">
              <?=$b['status'] ?>
            </span></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </body>

  </html>