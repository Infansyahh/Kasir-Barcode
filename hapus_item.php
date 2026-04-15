<?php
session_start();

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

unset($_SESSION['cart'][$id]);

$_SESSION['cart'] = array_values($_SESSION['cart']);

header("Location: transaksi.php");
exit;
?>