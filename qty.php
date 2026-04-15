<?php
session_start();
include "koneksi.php";

$id = $_GET['id'];
$act = $_GET['act'];

$kode = $_SESSION['cart'][$id]['kode_barang'];

$q = mysqli_query($koneksi, "SELECT stok FROM barang WHERE kode_barang='$kode'");
$b = mysqli_fetch_assoc($q);

if($act=="plus"){
    if($_SESSION['cart'][$id]['qty'] < $b['stok']){
        $_SESSION['cart'][$id]['qty']+=1;
    }

}

if($act=="min"){

if($_SESSION['cart'][$id]['qty']>1){
    $_SESSION['cart'][$id]['qty']-=1;
}

}
header("Location: transaksi.php");
?>