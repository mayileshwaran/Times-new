<?php
include("../config/db.php");
session_name("admin_session");
include('auth.php');

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin')) {
    die("<script>alert('Access Denied. Only Admins and Superadmins can access this page.'); window.history.back();</script>");
}
if (!isset($_SESSION['user_id'])) {
    // redirect to login or set default name
    $username = "Guest";
} else {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}
// Metrics
$total_products = $conn->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetch_row()[0];
$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$total_customers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$total_revenue = $conn->query("SELECT SUM( price ) FROM orders")->fetch_row()[0];
$top_brands = $conn->query("SELECT COUNT(DISTINCT brand) FROM products")->fetch_row()[0];

// Daily revenue data
$dates = [];
$earnings = [];
$day_query = "
  SELECT DATE(created_at) AS sale_date, SUM( price) AS total
  FROM orders
  GROUP BY DATE(created_at)
  ORDER BY sale_date ASC
";
$result = $conn->query($day_query);
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['sale_date'];
    $earnings[] = $row['total'];
}

// Monthly revenue data
$months = [];
$monthly_earnings = [];
$month_query = "
  SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM( price) AS total
  FROM orders
  GROUP BY month
  ORDER BY month ASC
";
$res_month = $conn->query($month_query);
while ($row = $res_month->fetch_assoc()) {
    $months[] = $row['month'];
    $monthly_earnings[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Time's New Admin Dashboard</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/dash.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
  
  </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo">          <a href="./dashboard.php">   <img src="../image/Time’s new.png" alt="" ></a></div>
  <div class="center">Dashboard</div>
  <div class="right">
    <i class="fas fa-user-circle profile-icon"></i>
    <p class="text" style="color: white;">Hello, <?= htmlspecialchars($username) ?></p>
    <div class="dropdown">
      <a href="./order-admin.php">Orders</a>
      <!--  <?php
              if(isset($_POST['logout']))
              {
                //  session_start();
              //  session_unset();
              session_destroy();
              header("Location: ./login.php"); // or dashboard.php if you prefer
              exit();
              }
            ?>

            <!-- <a href="logout.php">Logout</a> -->
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
               <button type="submit" name="logout">Logout</button>
             </form>
         
    </div>
  </div>
</div>

<div class="dashboard-container">
  <h1>Admin Dashboard</h1>

  <!-- Metric Cards -->
  <div class="card-container">
    <div class="card"><h3>Top Brands</h3><p><?= $top_brands ?></p></div>
    <div class="card"><h3>Total Products</h3><p><?= $total_products ?></p></div>
    <div class="card"><h3>Total Customers</h3><p><?= $total_customers ?></p></div>
    <div class="card"><h3>Total Orders</h3><p><?= $total_orders ?></p></div>
    <div class="card"><h3>Total Revenue (₹)</h3><p><?= round($total_revenue, 2) ?></p></div>
  </div>

  <!-- Charts Section -->
  <div class="chart-section">
    <div class="chart-box">
      <h2>Day-by-Day Revenue</h2>
      <canvas id="dailyChart"></canvas>
    </div>
    <div class="chart-box">
      <h2>Monthly Revenue</h2>
      <canvas id="monthlyChart"></canvas>
    </div>
  </div>

  <!-- Navigation Buttons -->
  <div class="btn-box">
    <a href="./products-admin.php">View Products</a>
    <a href="./order-admin.php">Orders</a>
    <a href="./query.php">Queries</a>
    <a href="./manage_users.php">Manage Users</a>
   
  </div>
</div>
<footer>
    <div class="foot-1">
                     <a href="./dashboard.php">   <img src="../image/Time’s new.png" alt="" width="200px"></a>
             <p>Times New is a modern platform delivering fresh insights, trends, and updates across technology
                , lifestyle, and innovation.</p>
    </div>
   
    <div class="foot-3">
        <h3>Coffee with us</h3>
         <div class="fr"><i class="fa-solid fa-location-dot"></i> <p>Madurai</p></div>
         <div class="fr"><a href="tel:+91 9876543210" target="_blank"><i class="fa-solid fa-phone"></i> <span> 9876543210</span>
                    </a></div>
    </div>
   <div class="foot-4">
    <h3>Get into touch</h3>
    <div class="foot-4a">
   <a href="https://www.instagram.com/accounts/login/?hl=en" target="_blank"> <i class="fa-brands fa-square-instagram"></i></a>
    <a href="https://www.facebook.com/login/" target="_blank"><i class="fa-brands fa-square-facebook"></i></a>
  <a href="https://x.com/i/flow/login" target="_blank"><i class="fa-brands fa-square-x-twitter"></i></a>
  <a href="https://www.youtube.com/" target="_blank"><i class="fa-brands fa-youtube"></i></a></div></div>
     <div class="foot-2">
      
                    <p>&copy;2025 All rights reserved by Timesnew</p>

    </div>
    
    </footer>
<!-- Chart.js Script -->
<script>
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyChart = new Chart(dailyCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode($dates) ?>,
    datasets: [{
      label: 'Daily Revenue (₹)',
      data: <?= json_encode($earnings) ?>,
      backgroundColor: 'rgba(0, 123, 255, 0.2)',
      borderColor: '#007bff',
      borderWidth: 2,
      tension: 0.4,
      fill: true,
      pointRadius: 4
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($months) ?>,
    datasets: [{
      label: 'Monthly Revenue (₹)',
      data: <?= json_encode($monthly_earnings) ?>,
      backgroundColor: 'rgba(40, 167, 69, 0.7)',
      borderColor: '#28a745',
      borderWidth: 7
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>

<script src="../js/nav.js"></script>
</body>
</html>
