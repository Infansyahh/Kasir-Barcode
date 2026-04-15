<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: ../index.php");
    exit;
}
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Kasir</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- NAVBAR -->
     <div class="navbar">
        <div class="nav-left">Sistem Kasir</div>

        <div class="nav-right">
            <span class="nav-user"><?= $_SESSION['nama']; ?></span>
            <a class="logout-btn" href="../logout.php">Logout</a>
        </div>
     </div>

     <!-- DASHBOARD -->
      <div class="dashboard-container">
        <h2 class="dashboard-title">Dashboard Admin</h2>

        <div class="menu-grid">
            <a href="../tambah_barang.php" class="menu-box">Tambah Barang</a>
            <a href="../tampil_barang.php" class="menu-box">Lihat Stok</a>
            <a href="../transaksi.php" class="menu-box">Transaksi</a>
            <a href="../laporan_bulanan.php" class="menu-box">Data Penjualan</a>
        </div>
      </div>
</body>
</html>