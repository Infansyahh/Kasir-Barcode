<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

if($_SESSION['role']=="admin"){
    $back = "admin/index.php";
}elseif($_SESSION['role']=="owner"){
    $back = "owner/index.php";
}else{
    $back = "index.php";
}

header("Cache-Control: no-store, no-chace, must-revalidate, max-age=0");
header("Pragma: no-cache");

date_default_timezone_set("Asia/Jakarta");

if(!is_dir("barcode")){
    mkdir("barcode");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stok</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <div class="nav-left">Sistem Kasir</div>

        <div class="nav-right">
            <span class="nav-user"><?= $_SESSION['nama']; ?></span>
            <a href="logout.php" class="logout-btn">logout</a>
        </div>
    </div>

    <div class="content-container">
        <div class="content-h2">
            <h2>Tambah Barang</h2>
        </div>

        <div class="form-box">
            <form action="proses_tambah.php" method="POST" onsubmit="return showPopup()">

            <div class="form-group">
                <label>Kode barang</label>
                <input type="text" name="kode_barang" required>
            </div>

            <div class="form-group">
                <label>Nama barang</label>
                <input type="text" name="nama_barang" required>
            </div>

            <div class="form-group">
                <label>Harga barang</label>
                <input type="number" name="harga" required>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="text" name="stok" required>
            </div>

            <button type="submit" class="btn-sumbit">Simpan & Buat Barcode</button>

            <a href="<?= $back ?>" class="btn-back-full">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>

    <div id="popup" class="popup">
        <div class="popup-box">
            <div class="checkmark">✔️</div>
        <p>Barcode ditambahkan</p>
</div>
    </div>

    <script>
        function showPopup(){
            document.getElementById("popup").classList.add("show");

            setTimeout(() => {
                document.forms[0].submit();
            }, 1200);

            return false;
        }
    </script>
</body>
</html>