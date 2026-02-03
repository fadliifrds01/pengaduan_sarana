<?php
include '../config/koneksi.php';
session_start();
if ($_SESSION['role'] != 'admin') { header("Location: ../login.php"); }

// Opsional: Fungsi untuk format tanggal Indonesia
function tgl_indo($tanggal){
    $bulan = array (1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengadua Sapras <?= date('d-m-Y') ?></title>
    <link rel="stylesheet" href="/style/laporan.css">
</head>
<body onload="window.print()">

    <div class="header">
        <h2>LAPORAN PENGADUAN SARANA DAN PRASARANA</h2>
        <p>SMK AL-IRSYAD TEGAL</p>
        <p>Jl. Gajah Mada No.123, Kota Tegal, Jawa Tengah</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="10%">NIS</th>
                <th width="15%">Kategori</th>
                <th width="15%">Lokasi</th>
                <th>Keterangan</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $res = mysqli_query($conn, "SELECT a.*, k.keterangan_kategori FROM aspirasi a JOIN kategori k ON a.id_kategori = k.id_kategori ORDER BY a.tanggal_pelaporan DESC");
            while($d = mysqli_fetch_array($res)){
            ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($d['tanggal_pelaporan'])) ?></td>
                <td><?= $d['nis'] ?></td>
                <td><?= $d['keterangan_kategori'] ?></td>
                <td><?= $d['lokasi'] ?></td>
                <td><?= $d['keterangan'] ?></td>
                <td style="text-align: center;"><strong><?= strtoupper($d['status']) ?></strong></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="footer-sign">
        <p>Tegal, <?= tgl_indo(date('Y-m-d')) ?></p>
        <p>Mengetahui, <br>Admin Sarpras</p>
        <br><br><br>
        <p><strong>( ..................................... )</strong></p>
    </div>

</body>
</html>