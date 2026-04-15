<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

if($_SESSION['role']=="admin"){
    $back_dashboard = "admin/index.php";
}elseif($_SESSION['role']=="owner"){
    $back_dashboard = "owner/index.php";
}else{
    $back_dashboard = "index.php";
}

$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM barang WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $nama  = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $update = mysqli_query($koneksi, "UPDATE barang SET 
                nama_barang = '$nama', 
                harga = '$harga', 
                stok = '$stok' 
                WHERE id = '$id'");

    if($update){
        echo "<script>
                alert('Data berhasil diupdate!');
                document.location.href = 'tampil_barang.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
</head>
<body>

    <div>
        <h2>Edit Barang</h2>
        
        <div class="card-container">
            <form action="" method="post">
                <div class="input-group">
                    <label>Kode barang</label>
                    <input type="text" value="<?= $data['kode_barang'] ?>" disabled>
                </div>

                <div class="input-group">
                    <label>Nama barang</label>
                    <input type="text" name="nama_barang" value="<?= $data['nama_barang'] ?>" required>
                </div>

                <div class="input-group">
                    <label>Harga barang</label>
                    <input type="number" name="harga" value="<?= $data['harga'] ?>" required>
                </div>

                <div class="input-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
                </div>

                <button type="submit" name="update" class="btn-submit">Update Data</button>
                
                <a href="<?= $back_dashboard ?>" class="btn-back">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>
<style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #a1c4fd, #c2e9fb); 
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-container {
            background-color: #777;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #000;
            margin-top: -60px;
            margin-bottom: 40px;
            font-size: 24px;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #000;
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box; 
            font-size: 16px;
        }

        .input-group input:disabled {
            background-color: #e9e9e9;
            cursor: not-allowed;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background-color: #fff;
            color: #000;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: 500;
        }

        .btn-back {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            background-color: #2ecc71;
            color: #fff;
            font-size: 16px;
            margin-top: 10px;
            box-sizing: border-box;
        }

        .btn-submit:hover { background-color: #f0f0f0; }
        .btn-back:hover { background-color: #27ae60; }
    </style>
</body>
</html>