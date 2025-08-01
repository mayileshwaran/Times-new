<?php
include('../config/db.php');
include('auth.php');
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin') {
    die("Access denied");
}
$id = (int) $_GET['id']; 
$conn->query("UPDATE products SET status = 'inactive' WHERE id = $id");
header("Location: products-admin.php");
exit;
?>