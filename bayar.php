<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0){
    header("Location: transaksi.php");
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) { 
    $total += $item['harga'] * $item['qty'];
}

$bayar = (int)$_POST['bayar'];
$kembalian = $bayar - $total;

if($bayar < $total){
    echo "<script>
    alert('❌ Uang Kurang!');
    window.location='transaksi.php';
    </script>";
    exit;
}

mysqli_begin_transaction($koneksi);

try{
    foreach ($_SESSION['cart'] as $item) {
        $kode = $item['kode_barang'];
        $qty = $item['qty'];

        $cek = mysqli_fetch_assoc(
            mysqli_query($koneksi, 
            "SELECT stok,nama_barang FROM barang WHERE kode_barang='$kode'")
        );

        if($cek['stok'] < $qty){
            throw new Exception("Stok ".$cek['nama_barang']."Tidak Cukup!");
            
        }
    }

    mysqli_query($koneksi, 
    "INSERT INTO transaksi(total,bayar,kembalian,tanggal)
    VALUES('$total','$bayar','$kembalian',NOW())");

    $trx_id = mysqli_insert_id($koneksi);

    foreach ($_SESSION['cart'] as $item) {
        $kode = $item['kode_barang'];
        $qty = $item['qty'];

        mysqli_query($koneksi, 
        "INSERT INTO detail_transaksi(transaksi_id,kode_barang,nama_barang,harga,qty)
        VALUES 
        ('$trx_id', 
        '{$item['kode_barang']}',
        '{$item['nama']}',
        '{$item['harga']}',
        '$qty')");

        mysqli_query($koneksi, "UPDATE barang SET stok = stok - $qty
        WHERE kode_barang='$kode'");
    }

    mysqli_commit($koneksi);

    unset($_SESSION['cart']);

    header("Location: struk.php?id=".$trx_id);
    exit;

}catch(Exception $e){
    mysqli_rollback($koneksi);

    echo "<script>
    alert('".$e->getMessage()."');
    window.location='transaksi.php';
    </script>";
}
?>