<?php
include "koneksi.php";

$bulan = $_GET['bulan'];

$pecah = explode("-", $bulan);
$tahun = $pecah[0];
$bln = $pecah[1];

//JOIN transaksi + detail
$q = mysqli_query($koneksi,"
    SELECT
    transaksi.transaksi_id,
    transaksi.tanggal,
    detail_transaksi.nama_barang,
    detail_transaksi.qty,
    detail_transaksi.harga,
    (detail_transaksi.qty * detail_transaksi.harga) as subtotal
    FROM transaksi
    JOIN detail_transaksi
    ON transaksi.transaksi_id = detail_transaksi.transaksi_id
    WHERE MONTH(transaksi.tanggal)='$bln'
    AND YEAR(transaksi.tanggal)='$tahun'
    ORDER BY transaksi.transaksi_id DESC
    ");

    $total = 0;
    ?>

    <html>
        <head>
            <style>
                body{
                    font-family: monospace;
                    display: flex;
                    justify-content: center;
                    background: white;
                }

                .laporan{
                    width: 320px;
                    padding: 10px;
                }

                h3{
                    margin:5px 0;
                }

                hr{
                    border: 0;
                    border-top: 1px dashed black;
                    margin: 8px 0;
                }

                table{
                    width: 100%;
                    font-size: 13px;
                }

                td{
                    padding: 3px 0 ;
                }

                .right{
                    text-align: right;
                }

                .center{
                    text-align: center;
                }

                @media print{
                    body{margin:0;}
                }
            </style>
        </head>

        <body onload="window.print()">

        <div class="laporan">

        <div class="center">
            <h3>LAPORAN BULANAN</h3>
            <p><?=$bulan?></p>
        </div>

        <hr>

        <?php
        $last_id = null;

        while($d=mysqli_fetch_assoc($q)){

        //tampilkan header transaksi kalo beda id
        if($last_id != $d['transaksi_id']){
            echo "<b>ID : {$d['transaksi_id']}</b><br>";
            echo "<small>{$d['tanggal']}</small><br>";
            $last_id = $d['transaksi_id'];
        }

        //hitung subtotal
        $sub = $d['subtotal'];
        $total += $sub;

        //tampilkan barang
        echo "
        <div style='margin-left:5px'>
        {$d['nama_barang']}<br>
        <span style='font-size:11px'>
        {$d['qty']} x ".number_format($d['harga'],0,',','.')."
        </span>
        <span style='float:right'>
        ".number_format($sub,0,',','.')."
        </span>
        </div>
        ";
        }
        ?>

        <hr>

        <table>
            <tr>
                <td><b>Total Omzet</b></td>
                <td class="right"><b><?=number_format($total,0,',','.')?></b></td>
            </tr>
        </table>

        <hr>

        <div class="center">
            Terimakasih
        </div>

        <script>
        setTimeout(function(){
            window.close();
        },3000);
    </script>

        </div>
        </body>
    </html>