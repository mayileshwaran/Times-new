<?php
session_name('user_session');
session_start();
include("./config/db.php");

// Sign Up
if (isset($_POST['signup_btn'])) {
    $name = $_POST['signup_name'];
    $email = $_POST['signup_email'];
    $password = $_POST['signup_password'];
    $role = "user";

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
    echo "<script>alert('Signup successful! Please login.'); window.location.href='./login.php';</script>";
    exit;
}

// Login
if (isset($_POST['login_btn'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
    if ($row = $res->fetch_assoc()) {

        if ($row['status'] === 'inactive') {
            echo "<script>alert('You are not able to access. Contact Admin.'); window.history.back();</script>";
            exit;
        }

        // If admin or superadmin: show alert and redirect WITHOUT setting session
        if ($row['role'] === 'admin' || $row['role'] === 'superadmin') {
            echo "<script>
                alert('Only Admin and Superadmin can login at the admin panel. You are redirected.');
                window.location.href = './admin/login.php';
            </script>";
            exit;
        }

        // For user role only: create session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['name'] = $row['name'];

        header("Location: ./index.php");
        exit;

    } else {
        echo "<script>alert('Invalid credentials'); window.history.back();</script>";
        exit;
    }
}
?>
