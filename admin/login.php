<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Time's New Login & Signup</title>
  <link rel="stylesheet" href="../css/log.css" />
  <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>
  <div class="container" id="container">
    <!-- Sign Up -->
    <div class="form-container sign-up-container">
      <form method="POST" action="auth.php">
        <h2>Create Account</h2>
        <input type="text" name="signup_name" placeholder="Name" required />
        <input type="email" name="signup_email" placeholder="Email" required />
        <div class="password-field">
          <input type="password" name="signup_password" id="signup_password" placeholder="Password" required />
          <i class="fa fa-eye toggle-password" onclick="togglePassword('signup_password', this)"></i>
        </div>
        <button name="signup_btn" type="submit">Sign Up</button>
      </form>
    </div>

    <!-- Login -->
    <div class="form-container sign-in-container">
      <form id="loginForm" method="POST" action="auth.php" onsubmit="return validateLoginForm()">
        <h2>Admin Login</h2>
        <input type="email" id="login_email" name="login_email" placeholder="Email" required />
        <div id="emailError" class="error-message"></div>

        <div class="password-field">
          <input type="password" id="login_password" name="login_password" placeholder="Password" required />
          <i class="fa fa-eye toggle-password" onclick="togglePassword('login_password', this)"></i>
        </div>
        <div id="passwordError" class="error-message"></div>

        <button name="login_btn" type="submit">Login</button>
      </form>
    </div>

    <!-- Overlay -->
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h2>Welcome Back!</h2>
          <p>To stay connected, please login</p>
          <button class="pribut" id="signIn">Login</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h2>Hello!</h2>
          <p>New here? Click below to sign up</p>
          <button class="pribut" id="signUp">Sign Up</button>
        </div>
      </div>
    </div>
  </div>
<script src="../js/login.js"></script>
</body>
</html>