<?php
session_start();

// Redirect ke dashboard jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: views/dashboard.php");
    exit;
} else {
    header("Location: views/login.php");
    exit;
}
?>
