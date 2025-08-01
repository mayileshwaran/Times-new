<?php
include('../config/db.php');

session_name("admin_session");
session_start();

// LOGIN HANDLER
if (isset($_POST['login_btn'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
          if ($row['status'] === 'inactive') {
            echo "<script>alert('You are not able to access. Contact Admin.'); window.history.back();</script>";
            exit;
        }
        // Use password_verify if using hashed passwords
        // For now assuming plain text for your case (you can improve it later)
        if ($row['password'] === $password) {
            $role = $row['role'];
            if ($role === 'admin' || $role === 'superadmin') {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $row['name'];
                header("Location: dashboard.php");
                exit;
            } else {
                echo "<script>
                    alert('Only user can login at the user panel. You are redirected.');
                    window.location.href = '../login.php';
                </script>";
                exit;
            }
        } else {
            echo "<script>
                alert('Incorrect password.');
                window.location.href = 'login.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Email not found.');
            window.location.href = 'login.php';
        </script>";
        exit;
    }
}
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

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
    echo "<script>alert('Signup successful! Please login.'); window.location.href='../dashboard.php';</script>";
    exit;
}
?>
