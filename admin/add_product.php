<?php 
session_name("admin_session");
include('auth.php'); 
include('../config/db.php'); 
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'superadmin'])) {
    die("Access denied.");
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


<!DOCTYPE html>
<html>
<head>
  <title>Add Product</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/addedit.css">
  <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
</head>
<body>
<div class="navbar">
  <div class="logo"><img src="../image/Time’s new.png" alt=""></div>
  <div class="center">Time's New Add Product</div>
  <div class="right">
    <i class="fas fa-user-circle profile-icon"></i>
          <p class="text" style="color: white;">Hello, <?= htmlspecialchars($username) ?></p>
    <div class="dropdown">
      <a href="order-admin.php">Orders</a>
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
<div class="form-container">
  <h2>Add New Product</h2>

  <form method="post" enctype="multipart/form-data">
    <input name="name" type="text" placeholder="Product Name" required>

    <select name="type" required>
      <option value="">Select Type</option>
      <?php foreach (['mens', 'womens', 'kids', 'boys', 'smart', 'couple'] as $type): ?>
        <option value="<?= $type ?>"><?= ucfirst($type) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="brand" required>
      <option value="">Select Brand</option>
      <?php foreach (['rolex', 'omega', 'cartier', 'citizen'] as $brand): ?>
        <option value="<?= $brand ?>"><?= ucfirst($brand) ?></option>
      <?php endforeach; ?>
    </select>

    <input name="price" type="number" step="0.01" placeholder="Price (₹)" required>
    <input name="discount" type="number" placeholder="Discount %"max='50' required>
    <input name="quantity" type="number" placeholder="Quantity" min="1"  required>

    <label class="upload-box">
      Upload Image
      <input type="file" name="image" accept="image/*" required>
    </label>

    <button type="submit" class="btn">Add Product</button>
  </form>
</div>

<?php
if ($_POST) {
    $name     = $_POST['name'];
    $type     = $_POST['type'];
    $brand    = $_POST['brand'];
    $price    = $_POST['price'];
    $discount = $_POST['discount'];
    $quantity = $_POST['quantity'];

    $imagePath = "";
    if ($_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imagePath = uniqid() . "." . strtolower($ext);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$imagePath");
    }

    $stmt = $conn->prepare("INSERT INTO products (name, type, brand, price, discount_percent, quantity, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdiis", $name, $type, $brand, $price, $discount, $quantity, $imagePath);
    $stmt->execute();

    echo "<script>alert(' Product added successfully'); window.location='./products-admin.php';</script>";
}
?>
 <footer>
    <div class="foot-1">
             <img src="../image/Time’s new.png" alt="" width="200px">
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
      
          <p>2025 All rights reserved by Timesnew</p>
    </div>
    
    </footer>
  <script src="../js/nav.js"></script>
</body>
</html>
