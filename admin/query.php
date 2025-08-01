<?php
session_name("admin_session");
include('./auth.php');
include('../config/db.php');
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin')) {
    die("<script>alert('Access Denied. Only Admins and Superadmins can access this page.'); window.history.back();</script>");
}
// Fetch all queries from the table
$result = $conn->query("SELECT * FROM query ORDER BY created_at DESC");
?>
<?php
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Time's New Customer Queries</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/query.css">
</head>
<body>

<div class="navbar">
  <div class="logo">          <a href="./dashboard.php">   <img src="../image/Time’s new.png" alt="" ></a></div>
  <div class="center">Customer Queries</div>
  <div class="right">
    <i class="fas fa-user-circle profile-icon"></i>
    <p class="text" style="color: white;">Hello, <?= htmlspecialchars($username) ?></p>
    <div class="dropdown">
      <a href="./dashboard.php">Dashboard</a>
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
  <div class="arrow">
    <button onclick="history.back()" class="btn-back"> <i class="fa-solid fa-circle-arrow-left"></i> Go Back</button>
 </div>
<div class="container">
    <h2>All Queries</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="query-card">
                <h3><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['phone']) ?>)</h3>
                <p>Email: <?= htmlspecialchars($row['email']) ?></p>
                <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                <div class="timestamp"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No queries found.</p>
    <?php endif; ?>
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
    <script src="../js/nav.js"></script>
</body>
</html>
