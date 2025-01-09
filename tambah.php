<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'includes/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    if (empty($jenis) || empty($jumlah) || empty($tanggal)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $stmt = $conn->prepare("INSERT INTO transaksi (user_id, jenis, jumlah, deskripsi, tanggal) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $jenis, $jumlah, $deskripsi, $tanggal]);
        header("Location: views/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - Kasku</title>
    <link rel="stylesheet" href="assets/tambah_style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Tambah Transaksi</h1>
            <div class="button-group">
    <a href="views/dashboard.php" class="btn">Kembali ke Dashboard</a>
    <button type="submit" class="btn">Tambah Transaksi</button>
</div>

        </header>

        <div class="card">
            <h2>Form Tambah Transaksi</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            <form action="tambah.php" method="POST">
                <div class="form-group">
                    <label for="jenis">Jenis Transaksi</label>
                    <select name="jenis" id="jenis" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" placeholder="Masukkan jumlah" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" placeholder="Masukkan deskripsi transaksi"></textarea>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" required>
                </div>
                <button type="submit" class="btn">Tambah Transaksi</button>
            </form>
        </div>
    </div>
</body>
</html>
