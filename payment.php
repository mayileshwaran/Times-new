<?php
session_name('user_session');
include('user.php');
if (!isset($_SESSION['user_id'])) {
  echo "User ID: " . $_SESSION['user_id'];
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

if (!isset($_GET['id']) || !isset($_GET['quantity'])) {
    echo "Product ID or Quantity missing.";
    exit;
}

$id = (int)$_GET['id'];
$quantity = max(1, (int)$_GET['quantity']);

$res = $conn->query("SELECT * FROM products WHERE id = $id");
$row = $res->fetch_assoc();

if (!$row) {
    echo "Product not found.";
    exit;
}

if ($quantity > $row['quantity']) {
    echo "<h3 style='color:red;text-align:center;margin-top:30px'>
             Not enough stock available. Only <b>{$row['quantity']}</b> in stock.
          </h3>";
    exit;
}

$discounted = $row['price'] - ($row['price'] * $row['discount_percent'] / 100);
$final_price = round($discounted, 2);
$total = $final_price * $quantity;

$errors = [];
$name = $address = $city = $pin = $phone = $method = '';
$upi_id = $card_number = $expiry = $cvv = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pin = trim($_POST['pin']);
    $phone = trim($_POST['phone']);
    $method = $_POST['method'] ?? '';

    if (!preg_match("/^[a-zA-Z\s]+$/",$name)) $errors['name'] = "Name is required.";
    if (empty($address)) $errors['address'] = "Address is required.";
    if (empty($city)) $errors['city'] = "City is required.";
    if (!preg_match("/^\d{6}$/", $pin)) $errors['pin'] = "Valid 6-digit pin code required.";
    if (!preg_match("/^[6-9][0-9]{9}$/", $phone)) $errors['phone'] = "Valid 10-digit phone required its start(6-9).";
    if (empty($method)) $errors['method'] = "Please select payment method.";

    // Validate UPI
    if ($method == 'upi') {
        $upi_id = trim($_POST['upi_id'] ?? '');
        if (!preg_match("/^[\w.-]+@[\w.-]+$/", $upi_id)) {
            $errors['upi_id'] = "Enter a valid UPI ID (e.g., name@bankname).";
        }
    }

    // Validate Card
    if ($method == 'card') {
        $card_number = trim($_POST['card_number'] ?? '');
        $expiry = $_POST['expiry'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        if (!preg_match("/^\d{16}$/", $card_number)) {
            $errors['card_number'] = "Card number must be 16 digits.";
        }
        if (!preg_match("/^\d{3,4}$/", $cvv)) {
            $errors['cvv'] = "CVV must be 3 or 4 digits.";
        }

        if ($expiry) {
            $current = date('Y-m');
            if ($expiry < $current) {
                $errors['expiry'] = "Card has expired.";
            }
        } else {
            $errors['expiry'] = "Expiry date is required.";
        }
    }

    if (empty($errors)) {
       $user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO orders 
(user_id, product_id, quantity, price, payment_method, fullname, address, city, pincode, phone) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "iiidssssss",   // <-- Correct: no space
    $user_id,       // i
    $id,            // i (product_id)
    $quantity,      // i
    $total,         // d (price)
    $method,        // s
    $name,          // s
    $address,       // s
    $city,          // s
    $pin,           // s (you can use s because pincode is stored as string)
    $phone          // s (you can use s because phone may start with 0)
);



        $stmt->execute();
        $stmt->close();

        $conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = $id");

        $days = rand(5, 10);
        $start = date('d M Y', strtotime("+$days days"));
        $end = date('d M Y', strtotime("+".($days + 2)." days"));

        echo "<script>
          alert('Your order is placed!\\nDelivery expected between $start and $end.');
          window.location.href = './products.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/prod.css">
  <link rel="stylesheet" href="./css/nav.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
  <title>Time's New Payment</title>
</head>
<body>
<div class="navbar">
  <div class="logo"><img src="./image/Time’s new.png" alt=""></div>
  <div class="center">Payment</div>
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

<div class="payment-container">
  <h2><?= htmlspecialchars($row['name']) ?> - Checkout</h2>

  <?php if (!empty($row['image_path'])): ?>
    <img src="./uploads/<?= $row['image_path'] ?>" alt="Product Image" class="product-preview">
  <?php endif; ?>

  <div class="price-section">
    <p>Quantity: <strong><?= $quantity ?></strong></p>
    <p>Unit Price: ₹<?= $final_price ?></p>
    <p><strong>Total: ₹<?= $total ?></strong></p>
  </div>

  <form method="post">
    <h3>Enter Address Details</h3>

    <div class="input-box">
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Name">
      <?php if (isset($errors['name'])) echo "<div class='error'>{$errors['name']}</div>"; ?>
    </div>

    <div class="input-box">
      <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" placeholder="Address">
      <?php if (isset($errors['address'])) echo "<div class='error'>{$errors['address']}</div>"; ?>
    </div>

    <div class="input-box">
      <input type="text" name="city" value="<?= htmlspecialchars($city) ?>" placeholder="City">
      <?php if (isset($errors['city'])) echo "<div class='error'>{$errors['city']}</div>"; ?>
    </div>

    <div class="input-box">
      <input type="text" name="pin" value="<?= htmlspecialchars($pin) ?>" placeholder="Pin Code">
      <?php if (isset($errors['pin'])) echo "<div class='error'>{$errors['pin']}</div>"; ?>
    </div>

    <div class="input-box">
      <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="Phone Number">
      <?php if (isset($errors['phone'])) echo "<div class='error'>{$errors['phone']}</div>"; ?>
    </div>

    <h3>Select Payment Method:</h3>

    <label><input type="radio" name="method" value="upi" <?= ($method == 'upi') ? 'checked' : '' ?> onclick="showSection('upi')"> UPI</label><br>
    <label><input type="radio" name="method" value="card" <?= ($method == 'card') ? 'checked' : '' ?> onclick="showSection('card')"> Card</label><br>
    <label><input type="radio" name="method" value="Cash on Delivery" <?= ($method == 'Cash on Delivery') ? 'checked' : '' ?> onclick="showSection('Cash on Delivery')"> Cash on Delivery</label><br>
    <?php if (isset($errors['method'])) echo "<div class='error'>{$errors['method']}</div>"; ?>

    <!-- UPI -->
    <div id="upi" class="payment-section" style="display: <?= ($method == 'upi') ? 'block' : 'none' ?>;">
      <input type="text" name="upi_id" value="<?= htmlspecialchars($upi_id ?? '') ?>" placeholder="Enter your UPI ID">
      <?php if (isset($errors['upi_id'])) echo "<div class='error'>{$errors['upi_id']}</div>"; ?>
    </div>

    <!-- Card -->
    <div id="card" class="payment-section" style="display: <?= ($method == 'card') ? 'block' : 'none' ?>;">
      <input type="text" name="card_number" value="<?= htmlspecialchars($card_number ?? '') ?>" placeholder="Card Number">
      <?php if (isset($errors['card_number'])) echo "<div class='error'>{$errors['card_number']}</div>"; ?><br><br>

      <input type="month" name="expiry" value="<?= htmlspecialchars($expiry ?? '') ?>">
      <?php if (isset($errors['expiry'])) echo "<div class='error'>{$errors['expiry']}</div>"; ?><br><br>

      <input type="password" name="cvv" maxlength="4" placeholder="CVV">
      <?php if (isset($errors['cvv'])) echo "<div class='error'>{$errors['cvv']}</div>"; ?>
    </div>

    <!-- Cash on Delivery -->
    <div id="Cash on Delivery" class="payment-section" style="display: <?= ($method == 'Cash on Delivery') ? 'block' : 'none' ?>;">
      <p><i>No details needed for Cash on Delivery</i></p>
    </div>

    <br>
    <button type="submit" class="btn">Confirm Payment</button>
  </form>
</div>

<script>
function showSection(method) {
  document.getElementById('upi').style.display = 'none';
  document.getElementById('card').style.display = 'none';
  document.getElementById('Cash on Delivery').style.display = 'none';

  document.getElementById(method).style.display = 'block';
}
</script>
   <footer>
    <div class="foot-1">
             <img src="./image/Time’s new.png" alt="" width="200px">
             <p>Times New is a modern platform delivering fresh insights, trends, and updates across technology
                , lifestyle, and innovation.</p>
    </div>
    <div class="foot-2">
        <ul>
             <li><a href="./index.php" >HOME</a></li>
             <li><a href="./about.php">ABOUT</a></li>
             <li><a href="./products.php">PRODUCTS</a></li>
             <li><a href="./topbrands.php">TOP BRANDS</a></li>
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
  <script src="./js/nav.js"></script>

</body>
</html>
