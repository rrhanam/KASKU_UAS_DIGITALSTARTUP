<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Ambil total pemasukan dan pengeluaran
$stmt = $conn->prepare("
    SELECT 
        SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE 0 END) AS total_pemasukan,
        SUM(CASE WHEN jenis = 'pengeluaran' THEN jumlah ELSE 0 END) AS total_pengeluaran
    FROM transaksi
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

// Menggunakan hasil dari query pertama untuk total_pemasukan dan total_pengeluaran
$total_pemasukan = $data['total_pemasukan'] ?? 0;
$total_pengeluaran = $data['total_pengeluaran'] ?? 0;

// Hitung saldo
$total_saldo = $total_pemasukan - $total_pengeluaran;

// Ambil semua transaksi
$stmt = $conn->prepare("SELECT * FROM transaksi WHERE user_id = ? ORDER BY tanggal DESC");
$stmt->execute([$user_id]);
$transaksi = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kasku</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Dashboard Kasku</h1>
            <div class="button-group">
    <a href="../tambah.php" class="btn">Tambah Transaksi</a>
    <a href="../logout.php" class="btn">Logout</a>
</div>

        </header>

        <div class="grid-container">
            <!-- Ringkasan Keuangan -->
            <div class="card">
                <h2>Ringkasan Keuangan</h2>
                <canvas id="pieChart" width="300" height="300"></canvas>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="card">
                <h2>Riwayat Transaksi</h2>
                <!-- Menampilkan Total Saldo di atas Riwayat Transaksi -->
<div class="total-saldo">
    <h2>Total Saldo: Rp <?php echo number_format($total_saldo, 0, ',', '.'); ?></h2>
</div>
                <table>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Deskripsi</th>
                    </tr>
                    <?php foreach ($transaksi as $t): ?>
                    <tr>
                        <td><?= $t['tanggal']; ?></td>
                        <td><?= ucfirst($t['jenis']); ?></td>
                        <td>Rp<?= number_format($t['jumlah'], 2); ?></td>
                        <td><?= $t['deskripsi']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>


        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                
                <div class="footer-center">
                <p>&copy; 2025 Kasku. All Rights Reserved.</p>
                </div>
                
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [<?= $total_pemasukan ?>, <?= $total_pengeluaran ?>],
                    backgroundColor: ['#4CAF50', '#F44336'], // Warna pie chart
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw || 0;
                                return `${label}: Rp${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
