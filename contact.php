<?php
$success = '';
$errors = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];
$name = $email = $phone = $message = '';
$errors = [];
$success = '';
include('user.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get values
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

 // Validation
if ($name === '') {
    $errors[] = "Name is required.";
} else {
    // Allow only letters and spaces
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name must contain only alphabets and spaces.";
    }
}

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!preg_match("/^[a-zA-Z]/", $email)) {
        $errors[] = "Email must start with a letter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($phone === '') {
        $errors[] = "Phone is required.";
    } elseif (!preg_match("/^[6-9][0-9]{9}$/", $phone)) {
        $errors[] = "Phone must start with 6-9 and be 10 digits.";
    }

if ($message === '') {
    $errors[] = "Message is required.";
} elseif (!preg_match("/^[a-zA-Z0-9\s.,?-]+$/", $message)) {
    $errors[] = "Message can only contain letters, numbers, spaces, and . , ? - symbols.";
}


    // Save to DB only if no errors
    if (empty($errors)) {
        include './config/db.php';
        $stmt = $conn->prepare("INSERT INTO query (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        $stmt->execute();
        $stmt->close();
        $success = "Your message has been sent successfully!";
        $name = $email = $phone = $message = ''; // Clear form values
    }
}
   
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time's New contact</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
    <style>
      form textarea {
  min-height: 40px;
  box-sizing: border-box;
}
form .error-box {
  color: #a94442;

  border-radius: 4px;
 
}

form .success-box {
  color: #3c763d;

  border-radius: 4px;
  box-shadow: 0 0 5px rgba(0, 128, 0, 0.1);
}

  </style>
</head>
<body>
  <?php
if (session_status() === PHP_SESSION_NONE) {

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
      <li><a href="./topbrands.php">TOP BRANDS</a></li>
      <li class="active"><a href="./contact.php">CONTACT</a></li>
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
                  // session_start();
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
    <div class="conc-banner">
      <h2>Have a question? Time to talk!</h2>
      <p>We're here to help with any questions about our watches, your orders, or our services. 
        Whether you're looking for product details or need support, our team is ready to assist you. 
        Feel free to reach out through the contact form or email us directly for a prompt response.</p>
      </div>
      <div class="form">
      
        <div class="left-form">

  <form method="POST" onsubmit="return validateForm();">
  <h3>Ask your question</h3>
  <input type="text" name="name" id="name" placeholder="Your Name">
  <input type="text" name="email" id="email" placeholder="Your Email">
  <input type="text" name="phone" id="phone" placeholder="Your Phone">
  <textarea name="message" id="message" placeholder="Your Message"></textarea>



  <button type="submit" class="btn">Submit</button>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <strong>Please fix the following errors:</strong>
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php elseif ($success): ?>
      <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
  <?php endif; ?>
</form>


        </div>
        <div class="right-form">
              <div class="form-content">
                <h2>Get into touch</h2>
                <div class="rf"><a href="mailto:timesnew@gmail.com" target="_blank"><i class="fa-solid fa-envelope"></i>
                    <span>timesnew@gmail.com</span></a></div>
                    <div class="rf">  <a href="tel:+91 9876543210" target="_blank"><i class="fa-solid fa-phone"></i> <span> 9876543210</span>
                    </a></div>   
                    <div class="rf"><i class="fa-solid fa-business-time"></i><p>Mon-Sat|10.00Am-7.00Pm(IST)</p></div>
                    <div class="rf"><i class="fa-solid fa-location-dot"></i> <p>Madurai</p></div>
              </div>
              <div class="form-content2">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26240.737647341208!2d78.10959317291803!3d9.936448353220094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b00c5b8f7f59129%3A0xeebe1c5f4f461423!2sKK%20Nagar%2C%20Tamil%20Nadu%20625020!5e1!3m2!1sen!2sin!4v1751268582310!5m2!1sen!2sin"
  width="100%" height="200px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>              
<p>Step into our world of timeless elegance and craftsmanship.Visit our store to explore premium watches up close and experience true luxury.</p>      
</div></div>
    </div>

   
       <div class="accordion-container">
        <h4>People also ask about that</h4>
        <div class="set">
            <div class="set-heading">
                <span>Are your watches water-resistant?</span>
                <span><i class="fa-solid fa-plus"></i></span>
            </div>
            <div class="set-container">
                <p>Yes, most of our watches are water-resistant. Please check the product specifications for the 
                    exact depth rating (e.g., 30m, 50m, 100m). Water-resistance does not mean waterproof.
                </p>
            </div>
        </div>
        <div class="set">
            <div class="set-heading">
                <span> Do your watches come with a warranty?</span>
                <span><i class="fa-solid fa-plus"></i></span>
            </div>
            <div class="set-container">
                <p>Absolutely. All our watches come with a standard 2-year international warranty covering 
                    manufacturing defects. Accidental damage is not included.
                </p>
            </div>
        </div>
         <div class="set">
            <div class="set-heading">
                <span>How long does delivery take?</span>
                <span><i class="fa-solid fa-plus"></i></span>
            </div>
            <div class="set-container">
                <p>Standard delivery usually takes 3–7 business days depending on your location. Express shipping options are also available during checkout
                </p>
            </div>
        </div>
         <div class="set">
            <div class="set-heading">
                <span>Can I return or exchange a watch?</span>
                <span><i class="fa-solid fa-plus"></i></span>
            </div>
            <div class="set-container">
                <p>Yes, we offer a 15-day return and exchange policy as long as the watch is unused and in its original packaging.
                </p>
            </div>
        </div>
         <div class="set">
            <div class="set-heading">
                <span>Can It is a online shoping or offline shopping watch?</span>
                <span><i class="fa-solid fa-plus"></i></span>
            </div>
            <div class="set-container">
                <p>No its Contains both online and Offline shopping can provide based on the availability
                </p>
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