<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time's New Top Brands</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
  <?php
if (session_status() === PHP_SESSION_NONE) {

}
session_name('user_session');
include('./config/db.php');
include('user.php');
if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin')) {
    echo "<h2 style='color: red;'>Access Denied. Admins and Superadmins cannot access this page.</h2>";
    exit;
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
      <li><a href="./index.php">HOME</a></li>
      <li><a href="./about.php">ABOUT US</a></li>
      <li><a href="./products.php">PRODUCTS </a></li>
      <li class="active"><a href="./topbrands.php">TOP BRANDS</a></li>
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
    <div class="sections">
         
    <div class="cont">
        <img src="./image/rolex.png" alt=""  class="tp">
        <div class="content">
            <img src="./image/rollogo.png" alt="" class="b-logo">
            <p>Rolex is a symbol of prestige and precision, crafting iconic Swiss
                 timepieces since 1905. Renowned for luxury, durability, and timeless
                  design, Rolex defines excellence in watchmaking.</p>
       
        <h2>Featured Collection</h2>
        <div class="brands">
            <div class="brand">
                <img src="./image/roll1 (3).png" alt="">
                <h6>Luxurious  Watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/roll1 (2).png" alt="">
                <h6>woman watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/roll1 (1).png" alt="">
                <h6>Rolex kids watch</h6>
                
            </div>
            </div>
            <a href="products.php?brand=rolex">
            <div class="pri-but">Explore Rolex watch</div></a>
        </div>
    </div>
 <div class="cont">
        <img src="./image/omega.png" alt=""   class="tp">
        <div class="content">
            <img src="./image/omegalogo.png" alt="" class="b-logo">
            <p>Omega is a legendary Swiss watchmaker known for innovation, precision, 
                and space-ready timepieces.Trusted by astronauts and athletes alike, 
                Omega blends cutting-edge classic style.</p>
       
        <h2>Featured Collection</h2>
        <div class="brands">
            <div class="brand">
                <img src="./image/omega (3).png " alt="">
                <h6> Omega men Watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/omega (2).png" alt="">
                <h6> woman  watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/omega (1).png" alt="">
                <h6>omega kids watch</h6>
                
            </div>
            </div>
      <a href="products.php?brand=omega">
            <div class="pri-but">Explore Omega watch</div></a>
        </div>
    </div>
    <div class="cont">
        <img src="./image/cartier.png" alt=""  class="tp">
        <div class="content">
            <img src="./image/cartier logo.png" alt="" class="b-logo">
            <p>Cartier is a French luxury brand celebrated for its elegant and sophisticated timepieces.
                Blending fine jewelry craftsmanship with timeless watch design, Watch Model, Cartier is a true 
                symbol of prestige .</p>
       
        <h2>Featured Collection</h2>
        <div class="brands">
            <div class="brand">
                <img src="./image/cartier (3).png  " alt="">
                <h6> Cartier men   Watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/cartier (1).png" alt="">
                <h6> woman  watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/cartier (2).png" alt="">
                <h6>Cartier kids watch</h6>
                
            </div>
            </div>
        <a href="products.php?brand=cartier">
            <div class="pri-but">Explore Cartier watch</div></a>
        </div>
    </div>
      <div class="cont">
        <img src="./image/citizen.png" alt="" class="tp">
        <div class="content">
            <img src="./image/citizenlogo.png " alt="" class="b-logo"  >
            <p>Citizen is a globally respected Japanese brand known for its innovative,
                 eco-friendly timepieces.With technologies like Eco-Drive, Citizen combines 
                 precision, sustainability, and modern design.</p>
       
        <h2>Featured Collection</h2>
        <div class="brands">
            <div class="brand">
                <img src="./image/citizen (3).png  " alt="">
                <h6> citizen men   Watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/citizen (2).png" alt="">
                <h6> woman  watch</h6>
               
            </div>
            <div class="brand">
                <img src="./image/citizen (1).png" alt="">
                <h6>citizen kids watch</h6>
                
            </div>
            
            </div>
            <a href="products.php?brand=citizen">
            <div class="pri-but">Explore Citizen watch</div></a>
        </div>
    </div>
</div>
<div class="countdown">
    <div class="countdown-container">
        <h1>The Offers Till</h1>
        <div class="timess">
  <div class="time-box">
    <h2 id="days">00</h2>
    <span>Days</span>
  </div>
  <div class="time-box">
    <h2 id="hours">00</h2>
    <span>Hours</span>
  </div>
  <div class="time-box">
    <h2 id="minutes">00</h2>
    <span>Minutes</span>
  </div>
  <div class="time-box">
    <h2 id="seconds">00</h2>
    <span>Seconds</span>
  </div></div>
</div></div>
<div id="popup">
        <div class="popup-content">
            <h2>Hello</h2>
            <p>Do you want explore these watches</p>
            <div class="popup-button">
                <div type="button" id="confirm">Confirm</div>
                <div type="button" id="cancel">Cancel</div>
            </div>
        </div>
    </div>
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