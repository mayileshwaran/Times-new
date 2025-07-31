<?php
session_name("admin_session");
include '../config/db.php';
include'auth.php';
// Access check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    die("Access denied. Superadmin only.");
}

$errors = [];
$success = "";
$no=1;

$users = [];

if (isset($_GET['role']) && in_array($_GET['role'], ['user', 'admin'])) {
    $selected_role = $_GET['role'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE status='active' AND role = ?");
    $stmt->bind_param("s", $selected_role);
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE status='active' AND role IN ('user', 'admin')");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Handle Add / Update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($name === '') $errors[] = "Name is required.";
    if ($email === '') $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if ($password === '' && $id === '') $errors[] = "Password is required for new users.";
    if (!in_array($role, ['admin', 'user'])) $errors[] = "Invalid role.";

    if (empty($errors)) {
        if ($id) {
            // Update
            if ($password !== '') {
                $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=?, role=? WHERE id=?");
                $stmt->bind_param("ssssi", $name, $email, $password, $role, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
                $stmt->bind_param("sssi", $name, $email, $role, $id);
            }
            $stmt->execute();
            $success = "User updated successfully.";
        } else {
            // Add new
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password, $role);
            $stmt->execute();
            $success = "User added successfully.";
        }
    }
}

// Soft delete
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("UPDATE users SET status='inactive' WHERE id=?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $success = "User marked as inactive.";
}

// Fetch active users
$users = [];

if (isset($_GET['role']) && in_array($_GET['role'], ['user', 'admin'])) {
    $selected_role = $_GET['role'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE status='active' AND role = ?");
    $stmt->bind_param("s", $selected_role);
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE status='active' AND role IN ('user', 'admin')");
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}


// Fetch user for edit
$edit_user = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_user = $result->fetch_assoc();
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
    <title>Time's New Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/user.css">
   <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="navbar">
  <div class="logo"><img src="../image/Time’s new.png" alt=""></div>
  <div class="center">Manage User</div>
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
 <div class="arrow">
    <button onclick="history.back()" class="btn-back"><i class="fa-solid fa-circle-arrow-left"></i>Go Back </button>
 </div>
<h2>Manage Users</h2>

<?php if (!empty($errors)): ?>
    <div class="error"><ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
<?php elseif ($success): ?>
    <div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="form-box">
    <h3><?= $edit_user ? "Edit User" : "Add New User" ?></h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_user['id'] ?? '' ?>">

       
        <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($edit_user['name'] ?? '') ?>" required>

      
        <input type="email" name="email"  placeholder="email" value="<?= htmlspecialchars($edit_user['email'] ?? '') ?>" required>

        <input type="password" name="password" placeholder="<?= $edit_user ? 'Leave blank to keep current password' : 'Enter password' ?>">

        <label>Role</label>
        <select name="role" >
            <option value="user" <?= (isset($edit_user) && $edit_user['role'] === 'user') ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= (isset($edit_user) && $edit_user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit"><?= $edit_user ? "Update" : "Add" ?> User</button>
    </form>
</div>
<div class="filter">
    <!-- Filter Form -->
<form method="GET" style="margin: 10px 0;">
    <label for="role">Filter by Role:</label>
    <select name="role" id="role" onchange="this.form.submit()">
        <option value="">-- All --</option>
        <option value="user" <?= (isset($_GET['role']) && $_GET['role'] === 'user') ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
    </select>
</form>

</div>
<h2>Active Users</h2>
<div class="table">
<table>
    <thead>
        <tr>
           <th>NO.</th> <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                 <td data-label="No."><?= $no++ ?></td>
                <td data-label="ID"><?= $u['id'] ?></td>
                <td data-label="Name"><?= htmlspecialchars($u['name']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($u['email']) ?></td>
                <td data-label="Role"><?= $u['role'] ?></td>
                <td data-label="Action">
                    <a class="btn" href="?edit=<?= $u['id'] ?>">Edit</a>
                    <a class="btn del-btn" href="?delete=<?= $u['id'] ?>" onclick="return confirm('Mark user as inactive?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
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
</body>
</html>
