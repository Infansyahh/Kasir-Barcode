<?php
session_start();
include "koneksi.php";

if(!isset($_SESSION['role'])){
    header("Location: index.php");
    exit;
}

if($_SESSION['role']=="admin"){
    $back = "admin/index.php";
}elseif($_SESSION['role']=="owner"){
    $back = "owner/index.php";
}else{
    $back = "kasir/index.php";
}
?>

<html>
<head>
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #a1c4fd, #c2e9fb);
            min-height: 100vh;
            margin: 0;
            padding: 40px 0;
        }

        .container {
            background: #777;
            width: 95%;
            max-width: 850px;
            margin: 20px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            color: white;
            text-align: center;
        }

        input[type="month"], 
        .container a button, 
        form button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            margin-top: 10px;
        }

        .container a button, form button {
            background: #2ecc71;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="month"] {
            margin: 10px 0;
            border: 1px solid #ddd;
            color: #333;
        }

        table {
            background: white;
            color: #333;
            border-radius: 10px;
            border-collapse: collapse;
            overflow: hidden;
            width: 100%;
            margin: 20px 0;
            text-align: left;
        }

        table th { 
            background: #444; 
            color: white; 
            padding: 15px; 
            text-transform: uppercase; 
            font-size: 13px;
            text-align: center;
        }

        table td { 
            padding: 12px; 
            vertical-align: middle; 
            border-bottom: 1px solid #eee;
        }

        table tr:nth-child(even) { background: #f9f9f9; }

        .total-row {
            background: #f1f1f1 !important;
            font-weight: bold;
        }

        .btn-print {
            background: blue !important;
            margin-top: 20px;
        }

        button:hover {
            filter: brightness(0.9);
            transform: translateY(-1px);
        }

        hr {
            border: 0;
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?= $back?>"><button>Kembali</button></a>

        <h2>LAPORAN BULANAN</h2>

        <form method="GET">
            <label>Pilih Bulan & Tahun:</label>
            <input type="month" name="bulan" required value="<?= isset($_GET['bulan']) ? $_GET['bulan'] : '' ?>">
            <button type="submit">Cari Data</button>
        </form>

        <hr>

        <?php
        if(isset($_GET['bulan'])){
            $bulan = $_GET['bulan'];
            $pecah = explode("-", $bulan);
            $tahun = $pecah[0];
            $bln = $pecah[1];
        
            $q = mysqli_query($koneksi, "
            SELECT 
            transaksi.transaksi_id,
            transaksi.tanggal,
            detail_transaksi.nama_barang,
            detail_transaksi.qty,
            detail_transaksi.harga,
            (detail_transaksi.qty * detail_transaksi.harga) as subtotal
            FROM transaksi
            JOIN detail_transaksi ON transaksi.transaksi_id = detail_transaksi.transaksi_id
            WHERE MONTH(transaksi.tanggal)='$bln' AND YEAR(transaksi.tanggal)='$tahun'
            ORDER BY transaksi.transaksi_id DESC");

            $total_omset = 0;
        ?>

        <h3>Data Periode: <?= $bulan ?></h3>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($d = mysqli_fetch_assoc($q)) {
                    $total_omset += $d['subtotal'];
                    echo "<tr>
                    <td align='center'>{$d['transaksi_id']}</td>
                    <td>".date('d/m/Y', strtotime($d['tanggal']))."</td>
                    <td>{$d['nama_barang']}</td>
                    <td align='center'>{$d['qty']}</td>
                    <td>Rp ".number_format($d['harga'],0,',','.')."</td>
                    <td>Rp ".number_format($d['subtotal'],0,',','.')."</td>
                    </tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" align="right">TOTAL OMSET</td>
                    <td>Rp <?=number_format($total_omset,0,',','.')?></td>
                </tr>
            </tfoot>
        </table>
        
        <a href="cetak_bulanan.php?bulan=<?=$bulan?>" target="_blank">
            <button class="btn-print">Cetak Laporan Bulanan (PDF/Print)</button>
        </a>

        <?php } ?>
    </div>
</body>
</html>