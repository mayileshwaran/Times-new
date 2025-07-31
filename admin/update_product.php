
<?php 
session_name("admin_session");
include('auth.php'); 
include('../config/db.php'); 
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'superadmin'])) {
    die("Access denied.");
}
if (!isset($_GET['id'])) die("Product ID not provided.");

$id = $_GET['id'];

// Fetch existing product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) die("Product not found.");

// Handle update on form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $type     = $_POST['type'];
    $brand    = $_POST['brand'];
    $price    = $_POST['price'];
    $discount = $_POST['discount'];
    $quantity = $_POST['quantity'];
    $imagePath = $product['image_path']; // default to old image

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newImage = uniqid() . "." . strtolower($ext);
        $uploadPath = $uploadDir . $newImage;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            if (!empty($imagePath) && file_exists($uploadDir . $imagePath)) {
                unlink($uploadDir . $imagePath);
            }
            $imagePath = $newImage;
        } else {
            echo " Failed to upload new image.";
            exit;
        }
    }

    // Update product with quantity
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, discount_percent=?, type=?, brand=?, quantity=?, image_path=? WHERE id=?");
    $stmt->bind_param("sdsssisi", $name, $price, $discount, $type, $brand, $quantity, $imagePath, $id);
    $stmt->execute();

    echo "<script>alert(' Product updated successfully'); window.location='./products-admin.php';</script>";
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

<!DOCTYPE html>
<html>
<head>
  <title>Time's New Update Product</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/addedit.css">
</head>
<body>
<div class="navbar">
  <div class="logo"><img src="../image/Time’s new.png" alt=""></div>
  <div class="center"><?= htmlspecialchars($product['name']) ?></div>
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
    <button onclick="history.back()" class="btn-back"><i class="fa-solid fa-circle-arrow-left"></i>Go Back </button>
 </div></div>
<div class="form-container">
  <h2>Update Product</h2>

  <form method="post" enctype="multipart/form-data">
    <input name="name" type="text" value="<?= htmlspecialchars($product['name']) ?>" required>

    <select name="type" required>
      <?php foreach (['mens', 'womens', 'kids', 'boys', 'smart', 'couple'] as $type): ?>
        <option value="<?= $type ?>" <?= $product['type'] === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="brand" required>
      <?php foreach (['rolex', 'omega', 'cartier', 'citizen'] as $brand): ?>
        <option value="<?= $brand ?>" <?= $product['brand'] === $brand ? 'selected' : '' ?>><?= ucfirst($brand) ?></option>
      <?php endforeach; ?>
    </select>

    <input name="price" type="number" step="0.01" value="<?= $product['price'] ?>" required>
    <input name="discount" type="number" value="<?= $product['discount_percent'] ?>" required>
    <input name="quantity" type="number" min="0" value="<?= $product['quantity'] ?>" required>

    <div style="margin-bottom: 15px;">
      <strong>Current Image:</strong><br>
      <?php if ($product['image_path']): ?>
        <img src="../uploads/<?= $product['image_path'] ?>" alt="Product Image" width="150">
      <?php else: ?>
        <p>No image available.</p>
      <?php endif; ?>
    </div>

    <label class="upload-box">
      Upload New Image
      <input type="file" name="image" accept="image/*">
    </label>

    <button type="submit" class="btn">Update Product</button>
  </form>
</div>
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
