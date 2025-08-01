<?php 
session_name('user_session');
include('./config/db.php'); 
include('user.php');
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
if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin')) {
    echo "<h2 style='color: red;'>Access Denied. Admins and Superadmins cannot access this page.</h2>";
    exit;
}
?>

<?php
$filter = "";
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $filter = "WHERE type = '$type'";
    $displayTitle = ucfirst(strtolower($type)) . " Watches";
} elseif (isset($_GET['brand'])) {
    $brand = $_GET['brand'];
    $filter = "WHERE brand = '$brand'";
    $displayTitle = ucfirst(strtolower($brand)) . " Watches";
}

$sql = "SELECT * FROM products $filter";
?>
<!DOCTYPE html>
<html>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link rel="stylesheet" href="./css/show.css">
  <link rel="stylesheet" href="./css/style.css">
  <!-- <link rel="stylesheet" href="./css/nav.css"> -->
<head>
  <title>Time's New Product List</title>
  
</head>
<body>
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
      <li class="active"><a href="./products.php">PRODUCTS </a></li>
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
        <?php else: ?>
          <a href="login.php" class="login-btn">Login</a>
        <?php endif; ?>
      </div>
  </div>
</nav>
  

  <div class="center">
    <?php
    $type = $_GET['type'] ?? '';
if ($type) {
   $filter = "WHERE type = '$type'";
}
      // Adjust according to your URL parameters or logic
      if (isset($_GET['type'])) {
         echo htmlspecialchars($displayTitle) ;
      } elseif (isset($_GET['brand'])) {
          echo htmlspecialchars($displayTitle) ;
      } else {
          echo "Our Watches";
      }
    ?>
  </div>


<!-- Product Cards -->
<div class="product-grid">
  



  <?php
$filter = "";
if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $filter = "WHERE type = '$type'";
} elseif (isset($_GET['brand'])) {
    $brand = $_GET['brand'];
    $filter = "WHERE brand = '$brand'";
}
$statusFilter = "status = 'active'";
$sql = "SELECT * FROM products " . ($filter ? "$filter AND $statusFilter" : "WHERE $statusFilter") . " ORDER BY id DESC";


$result = $conn->query($sql);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $discounted = $row['price'] - ($row['price'] * $row['discount_percent'] / 100);
        
        echo "<div class='product-card' onclick=\"window.location='view_product.php?id={$row['id']}'\">";

        // Product Image
        if (!empty($row['image_path'])) {
            echo "<img src='./uploads/{$row['image_path']}' alt='Product Image'>";
        } else {
            echo "<div class='no-image'>No Image</div>";
        }

        // Product Info
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3><hr>";
        echo "<p>Type: " . htmlspecialchars($row['type']) . "</p>";
        echo "<p>Brand: " . htmlspecialchars($row['brand']) . "</p>";
        
        // Price
        if ($row['discount_percent'] > 0) {
            echo "<p class='price'><s>₹" . $row['price'] . "</s> ₹" . number_format($discounted, 2) . "</p>";
        } else {
            echo "<p class='price'>₹" . $row['price'] . "</p>";
        }

 {
    echo "<div class='btn-buy'>Buy Now</div>";
}




        echo "</div>"; 
    }
}
 else {
    echo "<p>No products found.</p>";
}
?>
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
             <li><a href="./topbrands.php">TOP BRANDS</a></li>
             <li><a href="./about.php">ABOUT</a></li>
             <li> <a href="./contact.php">CONTACT</a></li></ul>
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
<script src="./js/prod.js"></script>
  <script src="./js/nav.js"></script>

</body>
</html>
