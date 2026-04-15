<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM barang WHERE id = '$id'");

if($query){
    echo "<script>
            alert('Data berhasil dihapus!');
            document.location.href = 'tampil_barang.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus data!');
            document.location.href = 'tampil_barang.php';
          </script>";
}
?>