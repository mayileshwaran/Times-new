<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Time's New About us</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="./css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
</head>
<body>
     <?php
if (session_status() === PHP_SESSION_NONE) {

}
include('user.php');
include('./config/db.php');
if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin')) {
    echo "<h2 style='color: red;'>Access Denied. Admins and Superadmins cannot access this page.</h2>";
    exit;}
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

<nav>
  <div class="logo">
    <img src="./image/Time’s new.png" alt="Logo">
  </div>

  <div class="icon">
    <label for="nav"> <i class="fa-solid fa-bars"></i></label>
    <input type="checkbox" id="nav">
    <ul>
      <li><a href="./index.php">HOME</a></li>
      <li class="active"><a href="./about.php">ABOUT US</a></li>
      <li><a href="./products.php">PRODUCTS </a></li>
      <li><a href="./topbrands.php">TOP BRANDS</a></li>
      <li><a href="./contact.php">CONTACT</a></li>
    </ul>
    
     <div class="user-profile">
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="dropdown">
            <i class="fa-solid fa-user-circle dropdown-toggle" onclick="toggleDropdown()" style="cursor:pointer;"></i>
              <p class="text" style="color: white;">Hello, <?= htmlspecialchars($username) ?></p>
            <div class="dropdown-menu" id="dropdownMenu" style="display: none; position: absolute; background: #fff; box-shadow: 0 0 5px rgba(0,0,0,0.2); padding: 10px;">
            
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin')): ?>
    <a href="./admin/dashboard.php">Dashboard</a>
  <?php endif; ?>
            <a href="orders.php">Your Orders</a><br>
              <!--  <?php
              if(isset($_POST['logout']))
              {
                //  session_start();
              //  session_unset();
              session_destroy();
              header("Location: ./login.php"); // or index.php if you prefer
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
    <?php else: ?>
      <a href="login.php" class="login-btn">Login</a>
    <?php endif; ?>
  </div>
</nav>
            <div class="time">
  <div class="times">
 <div class="day" id="day">--</div>
<div class="clock" id="clock">--:--:--  --</div>
<div class="date" id="date">-- --- ----</div>
  </div>
</div>
<h4>Our Story</h4>
<div class="os">
<div class="our-story">
    <img src="./image/ourstory.jpg" alt="" class="our-s">
    <div class="ours-cont">
        <p>Founded in 1999, our company has proudly stood at the intersection of timeless craftsmanship and modern design. </p>

<p>Over the years, we’ve continued to evolve — not just in style, but in purpose. </p>

<p>Today, as we move forward with a legacy over two decades strong, our commitment remains the same: to deliver exceptional quality and enduring style.</p>
    </div>
</div></div>
   <footer>
    <div class="foot-1">
             <img src="./image/Time’s new.png" alt="" width="200px">
             <p>Times New is a modern platform delivering fresh insights, trends, and updates across technology
                , lifestyle, and innovation.</p>
    </div>
    <div class="foot-2">
          <ul>
             <li><a href="./index.php" >HOME</a></li>
             <li><a href="./about.php">ABOUT US</a></li>
             <li><a href="./products.php">PRODUCTS</a></li>
             <li><a href="./topbrands.php">TOP BRANDS</a></li>
             <li> <a href="./contact.php">CONTACT</a></li>
             </ul>
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
    <div class="copy"><p>2025 All rights reserved by Timesnew</p></div>
    </footer>
  <script src="./js/about.js"></script>
  <script src="./js/nav.js"></script>
      <script>
        function toggleDropdown() {
          const menu = document.getElementById('dropdownMenu');
          menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        window.onclick = function(event) {
          if (!event.target.matches('.dropdown-toggle')) {
            const dropdown = document.getElementById('dropdownMenu');
            if (dropdown && dropdown.style.display === 'block') {
              dropdown.style.display = 'none';
            }
          }
        }
      </script>
</body>
</html>
