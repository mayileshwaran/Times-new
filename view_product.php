<?php
session_name('user_session');
   include('user.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('./config/db.php');
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

if (!isset($_GET['id'])) {
    echo "Product ID missing.";
    exit;
}

$id = (int) $_GET['id'];
$res = $conn->query("SELECT * FROM products WHERE id = $id AND status = 'active'");
$product = $res->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

$discounted = $product['price'] - ($product['price'] * $product['discount_percent'] / 100);
$final_price = round($discounted, 2);

// Fetch 4 random other products
$randoms = $conn->query("SELECT * FROM products WHERE id != $id AND status = 'active' ORDER BY RAND() LIMIT 4");
?>

<!DOCTYPE html>
<html>
<head>
  <title> Time's New View Product-<?= htmlspecialchars($product['name']) ?></title>
  <link rel="stylesheet" href="./css/prod.css">
  <link rel="stylesheet" href="./css/nav.css">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo"><img src="./image/Time’s new.png" alt=""></div>
  <div class="center"><?= htmlspecialchars($product['name']) ?></div>
  <div class="right">
    <i class="fas fa-user-circle profile-icon"></i>
      <p class="text" style="color: white;">Hello, <?= htmlspecialchars($username) ?></p>
    <div class="dropdown">
      <a href="orders.php">Orders</a>
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
<div class="go">
  <div class="arrow">
    <button onclick="history.back()" class="btn-back"><i class="fa-solid fa-circle-arrow-left"></i> Go Back </button>
 </div></div>
<div class="view-product-container">
  <div class="main-product">
    <?php if (!empty($product['image_path'])): ?>
      <img src="./uploads/<?= $product['image_path'] ?>" alt="Product Image" class="product-image">
    <?php else: ?>
      <div class="no-image">No image available</div>
    <?php endif; ?>
  </div>

  <div class="product-details">
    <h2><?= htmlspecialchars($product['name']) ?></h2> <hr>
    <p><strong>Brand:</strong> <?= $product['brand'] ?></p>
    <p><strong>Type:</strong> <?= $product['type'] ?> Watch</p>

    <?php if ($product['discount_percent'] > 0): ?>
      <p><s>₹<?= $product['price'] ?></s><br></p>
      <p> - <?= $product['discount_percent'] ?>% off</p>
      <p><strong>Discounted Price: ₹<?= $final_price ?></strong></p>
    <?php else: ?>
      <p><strong>Price: ₹<?= $product['price'] ?></strong></p>
    <?php endif; ?>

    <!-- Quantity and Buy Now -->
    <form action="payment.php" method="get">
      <input type="hidden" name="id" value="<?= $product['id'] ?>">
      <label>Quantity:</label>
      <input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity'] ?>" required>
      <p style="font-size: 14px; color: gray;">Only <?= $product['quantity'] ?> in stock</p>
      <br>
      <button type="submit" class="btn">Buy Now</button>
    </form>
  </div>
</div>

<!-- Related Products -->
<h3>Recently added Products</h3>
<div class="related-section">
  <div class="related-grid">
    <?php while ($r = $randoms->fetch_assoc()): 
      $r_discount = $r['price'] - ($r['price'] * $r['discount_percent'] / 100);
    ?>
      <a href="view_product.php?id=<?= $r['id'] ?>" class="related-card-link">
        <div class="related-card">
          <?php if (!empty($r['image_path'])): ?>
            <img src="./uploads/<?= $r['image_path'] ?>" alt="Product" class="related-img">
          <?php else: ?>
            <div class="no-image">No image</div>
          <?php endif; ?>
          <h4><?= htmlspecialchars($r['name']) ?></h4>
          <p><strong>₹<?= round($r_discount, 2) ?></strong></p>
          <div class="btn">view more</div>
        </div>
      </a>
    <?php endwhile; ?>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="foot-1">
    <img src="./image/Time’s new.png" alt="" width="200px">
    <p>Times New is a modern platform delivering fresh insights, trends, and updates across technology, lifestyle, and innovation.</p>
  </div>
  <div class="foot-2">
    <ul>
      <li><a href="./index.php">HOME</a></li>
      <li><a href="./about.php">ABOUT</a></li>
      <li><a href="./products.php">PRODUCTS</a></li>
      <li><a href="./topbrands.php">TOP BRANDS</a></li>
      <li><a href="./contact.php">CONTACT</a></li>
    </ul>
  </div>
  <div class="foot-3">
    <h3>Coffee with us</h3>
    <div class="fr"><i class="fa-solid fa-location-dot"></i> <p>Madurai</p></div>
    <div class="fr"><a href="tel:+91 9876543210" target="_blank"><i class="fa-solid fa-phone"></i> <span>9876543210</span></a></div>
  </div>
  <div class="foot-4">
    <h3>Get into touch</h3>
    <div class="foot-4a">
      <a href="https://www.instagram.com/accounts/login/?hl=en" target="_blank"><i class="fa-brands fa-square-instagram"></i></a>
      <a href="https://www.facebook.com/login/" target="_blank"><i class="fa-brands fa-square-facebook"></i></a>
      <a href="https://x.com/i/flow/login" target="_blank"><i class="fa-brands fa-square-x-twitter"></i></a>
      <a href="https://www.youtube.com/" target="_blank"><i class="fa-brands fa-youtube"></i></a>
    </div>
  </div>
    <div class="copy"><p>2025 All rights reserved by Timesnew</p></div>
</footer>
<script>
  const quantityInput = document.querySelector('input[name="quantity"]');
  const maxQuantity = parseInt(quantityInput.max);

  quantityInput.addEventListener('input', () => {
    if (quantityInput.value > maxQuantity) {
      alert(`Only ${maxQuantity} item(s) in stock.`);
      quantityInput.value = maxQuantity;
    } else if (quantityInput.value < 1) {
      quantityInput.value = 1;
    }
  });
</script>
  <script src="./js/nav.js"></script>

</body>
</html>
