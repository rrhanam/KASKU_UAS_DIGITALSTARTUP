<?php
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrasi - Kasku</title>
    <link rel="stylesheet" href="../assets/login_style.css">
</head>
<body>
    <form method="POST">
        <h2>Registrasi</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
