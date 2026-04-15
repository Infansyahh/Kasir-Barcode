<?php
include "koneksi.php";
$id = $_GET['id'];

$trx = mysqli_fetch_assoc(
    mysqli_query($koneksi, 
    "SELECT * FROM transaksi WHERE transaksi_id='$id'")
);

$detail = mysqli_query($koneksi,
"SELECT * FROM detail_transaksi WHERE transaksi_id='$id'");
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="struk.css">
    <title>Struk</title>
</head>
<body onload="window.print()">
    <div class="struk">
        <center>
            <h3>TOKO ANDA</h3>
            Jl. Contoh No.123
            <hr>
            No : <?=$id?><br>
            <?=$trx['tanggal']?>
        </center>

        <hr>

        <table>
            <tr>
                <td>Item</td>
                <td>Qr</td>
                <td>Sub</td>
            </tr>

            <?php while($d=mysqli_fetch_assoc($detail)){
                $sub = $d['harga'] * $d['qty'];
            ?>

            <tr>
                <td colspan="3">
                    <?=$d['nama_barang']?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td><?=$d['qty']?> x <?=number_format($d['harga'])?></td>
                <td align="right"><?=number_format($sub)?></td>
            </tr>

 
            <?php } ?>

        </table>

        <hr>

        <table width="100%">
            <tr>
                <td>Total</td>
                <td align="right"><?=number_format($trx['total'])?></td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td align="right"><?=number_format($trx['bayar'])?></td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td align="right"><?=number_format($trx['kembalian'])?></td>
            </tr>
        </table>

        <hr>

        <center>
            Terimakasih 
        </center>
    </div>
    <script>
        setTimeout(function(){
            window.location="transaksi.php";
        },3000);
    </script>
</body>
</html>