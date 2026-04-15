<?php 
 session_start();
 include "koneksi.php";

 if($_SESSION['role']=="admin"){
    $back = "admin/index.php";
 }elseif($_SESSION['role']=="kasir"){
    $back = "kasir/index.php";
 }else{
    $back = "index.php";
 }

 $total = 0;
?>

<html>
<head>
    <title>Transaksi</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
    <center>
    
    <a href="<?= $back?>"><button>Kembali</button></a>

    <h2>SCAN BARANG</h2>

    <div id="reader" style="width: 300px;"></div>

    <script>
        function showPopup(kode){
            document.getElementById("popup").classList.add("show");

            setTimeout(()=>{
                window.location="?kode_barang="+kode;
            },1500);
        }

        function onScanSuccess(decodedText){
            showPopup(decodedText);
        }
        
        var html5QrcodeScanner = 
        new Html5QrcodeScanner("reader",{fps:10,qrbox:150});
        html5QrcodeScanner.render(onScanSuccess);
    </script>

    <br>

    <h3>INPUT MANUAL</h3>
    <form method="GET">
        <input type="text" name="kode_barang" required>
        <button>Tambah</button>
    </form>

    <div id="popup" class="popup">
        <div class="popup-box">
            <div class=""checkmark>✔️</div>
            <p>Barang Ditambahkan</p>
        </div>
    </div>
    </center>

    <br>

    <?php 
    if(isset($_GET['kode_barang'])){

    $kode = $_GET['kode_barang'];

    $q = mysqli_query($koneksi,
    "SELECT * FROM barang WHERE kode_barang='$kode'");
    $barang = mysqli_fetch_assoc($q);

    if(!$barang){
        echo "<script>alert('Barang Tidak Ditemukan!');</script>";
        echo "<script>window.location='transaksi.php';</script>";
        exit;
    }

    $found = false;

    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $key=>$item){
          
        if($item['kode_barang']==$barang['kode_barang']){
            if($item['qty'] >= $barang['stok']){
        echo "<script>alert('Stok Tidak Cukup!');</script>";
        echo "<script>window.location='transaksi.php';</script>";
        exit;
            }

            $_SESSION['cart'][$key]['qty'] +=1;
            $found=true;
            break;
        }
        }
        }

        if(!$found){

        if($barang['stok']<=0){
        echo "<script>alert('Stok Habis!');</script>";
        echo "<script>window.location='transaksi.php';</script>";
        exit;
            }

        $_SESSION['cart'][]=[
            "kode_barang"=>$barang['kode_barang'],
            "nama"=>$barang['nama_barang'],
            "harga"=>$barang['harga'],
            "qty"=>1
        ];
        }

    echo "<script>window.location='transaksi.php';</script>";
    exit;
    }
    ?>

    <div class="container">
        <h2>KERANJANG</h2>

        <table border="1" width="100%">
            <tr>
                <th>kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>

            <?php
            if(isset($_SESSION['cart'])){
                foreach($_SESSION['cart'] as $i=>$item){

                $subtotal = $item['harga'] * $item['qty'];
                $total += $subtotal;
               

            echo "<tr>
            <td>{$item['kode_barang']}</td>
            <td>{$item['nama']}</td>
            <td>Rp ".number_format($item['harga'])."</td>

            <td>

            <a href='qty.php?act=min&id=$i'>➖</a>

            {$item['qty']}

            <a href='qty.php?act=plus&id=$i'>➕</a>

            </td>

            <td>Rp ".number_format($subtotal)."</td>

            <td>
            <a href='hapus_item.php?id=$i'>Hapus</a>
            </td>

            </tr>";
            }
            }             
            ?>
        </table>

        <h3>Total : Rp <?=number_format($total)?></h3>

        <a href="kosongkan.php">Kosongkan</a>

        <br><br>

    <form id="formBayar" action="bayar.php" method="POST">
    <input type="hidden" name="total" value="<?=$total?>">
    Uang Bayar: 
    <input type="number" name="bayar" required>
    <button type="submit">Bayar</button>
</form>

<div id="popupBayar" class="popup">
    <div class="popup-box">
        <div class="checkmark">✔️</div>
        <h3>Pembayaran Berhasil</h3>
        <p>Kembalian : </p>
        <h2 id="kembalianText"></h2>
    </div>
</div>

<script>
document.getElementById("formBayar").addEventListener("submit", function(e) {
    
    let total = <?=$total?>;
    let bayar = document.querySelector("input[name='bayar']").value;
    
    if(parseInt(bayar) < total){
        alert("Uang Kurang!");
        e.preventDefault();
        return;
    }

    e.preventDefault();
     
    let kembalian = bayar - total;
    
    document.getElementById("kembalianText").innerHTML = 
    "Rp " + kembalian.toLocaleString("id-ID");

    // Munculkan popup
    document.getElementById("popupBayar").classList.add("show");

    setTimeout(function(){
        document.getElementById("formBayar").submit();
    }, 2000);
});
</script>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to bottom, #a1c4fd, #c2e9fb);
        min-height: 100vh;
        margin: 0;
        padding: 40px 0;
    }

    center, .container {
        background: #777;
        width: 95%;
        max-width: 750px;
        margin: 20px auto;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        color: white;
    }

    input[type="text"], input[type="number"], 
    center > a:first-child button, form[method="GET"] button, #formBayar button {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
    }

    center > a:first-child button, form[method="GET"] button, #formBayar button {
        background: #2ecc71;
        color: white;
        border: none;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    input[type="text"], input[type="number"] {
        margin: 10px 0;
        border: 1px solid #ddd;
    }

    table {
        background: white;
        color: #333;
        border-radius: 10px;
        border-collapse: collapse;
        overflow: hidden;
        width: 100%;
        margin: 20px 0;
    }

    table th { 
        background: #444; color: white; padding: 15px; 
        text-transform: uppercase; font-size: 13px;
    }

    table td { padding: 10px; vertical-align: middle; }
    table tr:nth-child(even) { background: #f9f9f9; }

    table td:nth-child(4) {
        display: flex; align-items: center; justify-content: center;
        gap: 10px; height: 50px;
    }

    table td a[href^="qty.php"] { color: #2ecc71; font-size: 20px; text-decoration:none;}
    table td a[href^="hapus_item.php"], a[href="kosongkan.php"] {
        padding: 8px 15px; border-radius: 5px; text-decoration: none;
        font-size: 12px; color: white; transition: 0.3s;
    }
    table td a[href^="hapus_item.php"] { background: #e74c3c; }
    a[href="kosongkan.php"] { background: #e74c3c; float: right; font-size: 14px; }

    .popup {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.8); justify-content: center;
        align-items: center; z-index: 1000;
    }
    .popup.show { display: flex; }
    .popup-box {
        background: white; padding: 30px; border-radius: 15px;
        text-align: center; color: #000!important; width: 300px;
    }
    .popup-box *, #kembalianText { color: #000!important; margin: 10px 0; }
    .checkmark { font-size: 50px; color: #2ecc71!important; }

    button:hover, table td a:hover, a[href="kosongkan.php"]:hover {
        filter: brightness(0.9); transform: translateY(-1px);
    }
</style>
    <br>
</body>
</html>