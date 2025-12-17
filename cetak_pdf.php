<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Ambil data transaksi sewa
$data = mysqli_query($koneksi, "
    SELECT sewa.*, alat.nama_alat 
    FROM sewa 
    JOIN alat ON sewa.alat_id = alat.id
    ORDER BY sewa.tgl_sewa DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Transaksi</title>
    <link rel="stylesheet" href="assets/css/laporan.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; }
        .btn-print { margin-bottom: 20px; display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px; }
        .btn-print:hover { background: #0056b3; }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<h1>Laporan Transaksi Penyewaan</h1>


<a href="#" class="btn-print" onclick="window.print();return false;">🖨️ Cetak Laporan</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Penyewa</th>
            <th>Nama Alat</th>
            <th>Tanggal Sewa</th>
            <th>Tanggal Kembali</th>
            <th>Total Biaya</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while($d = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['nama_penyewa']) ?></td>
                <td><?= htmlspecialchars($d['nama_alat']) ?></td>
                <td><?= $d['tgl_sewa'] ?></td>
                <td><?= $d['tgl_kembali'] ?></td>
                <td class="total">Rp <?= number_format($d['total'],0,',','.') ?></td>
            </tr>
        <?php } ?>
        <?php if(mysqli_num_rows($data) == 0){ ?>
            <tr>
                <td colspan="6" style="text-align:center;">Belum ada transaksi</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
