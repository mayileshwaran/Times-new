<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Time's New Index</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

  <?php

  if (session_status() === PHP_SESSION_NONE) {
    session_name('user_session');
    include('user.php');
    include('./config/db.php');

// Allow only users with role 'user' or 'guest'
if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin')) {
    echo "<h2 style='color: red;'>Access Denied. Admins and Superadmins cannot access this page.</h2>";
    exit;
}


  
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

  ?>
  <nav>
    <div class="logo">
      <img src="./image/Time’s new.png" alt="Logo">
    </div>

    <div class="icon">
      <label for="nav"> <i class="fa-solid fa-bars"></i></label>
      <input type="checkbox" id="nav">
      <ul>
        <li class="active"><a href="./index.php">HOME</a></li>
        <li><a href="./about.php">ABOUT US</a></li>
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


              <?php
              if (isset($_POST['logout'])) {
                //  session_start();
                //  session_unset();

                header("Location: ./login.php");
                session_destroy();
              }
              ?>

              <!-- <a href="logout.php">Logout</a> -->
              <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="logout">Logout</button>
              </form>

            </div>

          </div>
        <?php else: ?>
          <a href="login.php" class="login-btn">Login</a>
        <?php endif; ?>
      </div>
    </div>

  </nav>


  <div class="banner">
    <div class="text">
      <h2>NEW & EXCLUSIVE</h2>
      <h1>Watch</h1>
      <p>A sleek and elegant watch crafted for both style and precision. Perfect for everyday wear or special occasions with timeless design.</p>
      <div class="pri-but">
        <a href="./products.php">View Products</a>
      </div>
    </div>

  </div>


  <ul class="tabs-nav">
    <li class="active" data-tab="tab1"> Fast Track</li>
    <li data-tab="tab2">Titan</li>
    <li data-tab="tab3">Timex</li>
  </ul>
  <div class="tab">
    <div id="tab1" class="tab-content active">
      <div class="tab-text">
        <h2>Fast track</h2>
        <img src="./image/ft.png" alt="" style="width: 200px;height: 200px;">
        <p>Fastrack is a trendy Indian youth brand known for its stylish watches, sunglasses,
          and accessories.</p>
      </div>
    </div>

    <div id="tab2" class="tab-content">
      <div class="tab-text">
        <h2>Titan</h2>
        <img src="./image/tt.png" alt="" style="width: 200px;height: 200px;">
        <p>TTitan Watches, a flagship brand of Titan Company, is India’s leading watchmaker, offering a wide range of
          stylish and reliable timepieces.</p>
      </div>
    </div>


    <div id="tab3" class="tab-content">
      <div class="tab-text">
        <h2>Timex</h2>
        <img src="./image/tx.png" alt="" style="width: 200px;height: 200px;">
        <p>Timex is a globally renowned watch brand, founded in 1854, known for its durable, affordable,
          and stylish timepieces.</p>
      </div>
    </div>
  </div>
  <h4>Top Brands</h4>
  <div class="roll">
    <marquee behavior="" direction=""><img src="./image/rolling.png" alt=""><img src="./image/rolling.png" alt=""><img src="./image/rolling.png" alt=""></marquee>


  </div>

  <h4>Watches Trending Today</h4>
  <div class="ct">
    <div class="carousel-container">
      <div class="carousel-inner">

        <!-- Slide 1: Smart Watch -->
        <div class="carousel-item active">
          <img src="./image/smart.png" alt="">
          <div class="cc">
            <h2>Smart Watch</h2>
            <p>This stylish smartwatch blends modern tech with sleek design, offering fitness tracking,
              notifications, and more. Perfect for staying connected and active on the go.</p>
            <a href="././products.php" class="pri-but">View Products</a>
          </div>
        </div>

        <!-- Slide 2: Kids Watch -->
        <div class="carousel-item">
          <img src="./image/kids.png" alt="">
          <div class="cc">
            <h2>Kids Watch</h2>
            <p>This stylish smartwatch blends modern tech with sleek design, offering fitness tracking,
              notifications, and more. Perfect for staying connected and active on the go.</p>
            <a href="././products.php" class="pri-but">View Products</a>
          </div>
        </div>
        <!-- Slide 3: Couple Watch -->
        <div class="carousel-item">
          <img src="./image/couple.png" alt="">
          <div class="cc">
            <h2>Couple Watch</h2>
            <p>Elegant and timeless, this couple watch set symbolizes love and unity with matching designs.
              Perfect for anniversaries, weddings, or everyday wear to cherish your bond.</p>
            <a href="././products.php" class="pri-but">View Products</a>
          </div>
        </div>

      </div>


      <!-- Controls -->
      <div class="carousel-prev-indicator">
        <i class="fa-solid fa-arrow-left"></i>
      </div>
      <div class="carousel-next-indicator">
        <i class="fa-solid fa-arrow-right"></i>
      </div>

    </div>
  </div>


  </div>
  </div>
  <h4>Watches prefer for you</h4>
  <div class="types">
    <a href="./products.php?type=mens" class="type">
      <img src="./image/image.png" alt="Mens Watch">
      <h2>Mens Watch</h2>
    </a>
    <a href="./products.php?type=womens" class="type">
      <img src="./image/image 66.png" alt="Womens Watch">
      <h2>Womans Watch</h2>
    </a>
    <a href="./products.php?type=smart" class="type">
      <img src="./image/image 67.png" alt="Smart Watch">
      <h2>Smart Watches</h2>
    </a>
    <a href="./products.php?type=couple" class="type">
      <img src="./image/image 68.png" alt="Couple Watch">
      <h2>Couple Watches</h2>
    </a>
    <a href="./products.php?type=kids" class="type">
      <img src="./image/image 69.png" alt="Kids Watch">
      <h2>Kids Watches</h2>
    </a>
    <a href="./products.php?type=boys" class="type">
      <img src="./image/image 70.png" alt="Boys Watch">
      <h2>Boys Watch</h2>
    </a>
  </div>

  <footer>
    <div class="foot-1">
      <img src="./image/Time’s new.png" alt="" width="200px">
      <p>Times New is a modern platform delivering fresh insights, trends, and updates across technology
        , lifestyle, and innovation.</p>
    </div>
    <div class="foot-2">
      <ul>
        <li><a href="./index.php">HOME</a></li>
        <li><a href="./about.php">ABOUT US</a></li>
        <li><a href="./products.php">PRODUCTS</a></li>
        <li><a href="./topbrands.php">TOP BRANDS</a></li>
        <li> <a href="./contact.php">CONTACT</a></li>
      </ul>
    </div>
    <div class="foot-3">
      <h3>Coffee with us</h3>
      <div class="fr"><i class="fa-solid fa-location-dot"></i>
        <p>Madurai</p>
      </div>
      <div class="fr"><a href="tel:+91 9876543210" target="_blank"><i class="fa-solid fa-phone"></i> <span> 9876543210</span>
        </a></div>
    </div>
    <div class="foot-4">
      <h3>Get into touch</h3>
      <div class="foot-4a">
        <a href="https://www.instagram.com/accounts/login/?hl=en" target="_blank"> <i class="fa-brands fa-square-instagram"></i></a>
        <a href="https://www.facebook.com/login/" target="_blank"><i class="fa-brands fa-square-facebook"></i></a>
        <a href="https://x.com/i/flow/login" target="_blank"><i class="fa-brands fa-square-x-twitter"></i></a>
        <a href="https://www.youtube.com/" target="_blank"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>
    <div class="copy">
      <p>2025 All rights reserved by Timesnew</p>
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="./js/script.js"></script>
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