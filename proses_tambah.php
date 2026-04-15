<?php 
include "koneksi.php";
include "lib/barcode.php";

$kode = $_POST['kode_barang'];
$nama = $_POST['nama_barang'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];

$filename = "barcode/" . $kode . ".png";

generateBarcode($kode, $filename);

$query = "INSERT INTO barang(kode_barang, nama_barang, harga, stok) 
VALUES ('$kode', '$nama', '$harga', '$stok')";
mysqli_query($koneksi, $query);

echo "<script>alert('Barang dan Barcode berhasil di buat!');
 window.location='tambah_barang.php';</script>";
?>