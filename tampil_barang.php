<?php 
session_start();
include "koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

/* arahkan menu kembali berdasarkan role */
if($_SESSION['role']=="admin"){
    $back = "admin/index.php";
}elseif($_SESSION['role']=="owner"){
    $back = "owner/index.php";
}else{
    $back = "index.php";
}

/* anti cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

date_default_timezone_set("Asia/Jakarta");

/* ambil data barang */
$query = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id DESC");
?>

<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
     <!-- NAVBAR -->
    <div class="navbar">
        <div class="nav-left">Sistem Kasir</div>

        <div class="nav-right">
            <span class="nav-user"><?= $_SESSION['nama']; ?></span>
            <a href="logout.php" class="logout-btn">logout</a>
        </div>
    </div>

    <!-- CONTAINER -->
     <div class="container-container">

     <div class="content-header">
        <h2>Lihat Data Barang</h2>
        <a href="<?= $back ?>" class="btn-back">Kembali</a>
     </div>

     <div class="table-box">

     <table class="table">
     <thead>
         <tr>
            <th>no</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
         </tr>
     </thead>

     <tbody>
        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($query)){
        ?>

       <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['kode_barang'] ?></td>
        <td><?= $row['nama_barang'] ?></td>
        <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
        <td><?= $row['stok'] ?></td>
        <td>

        <a href="edit_barang.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>

        <a href="hapus_barang.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Yakin Hapus?')">Hapus</a>
        </td>
       </tr>
<?php } ?>
     </tbody>
     </table>
     </div>
     </div>
</body>
</html>