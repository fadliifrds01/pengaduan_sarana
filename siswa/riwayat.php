<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['role'] != 'siswa') {
    header("Location: ../login.php");
}

$nis_user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa | Riwayat Aspirasi</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style/riwayat.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-edit me-2"></i>E-PENGADUAN
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link" href="form_aspirasi.php">Buat Aduan</a>
                    <a class="nav-link active" href="riwayat.php">Riwayat</a>
                    <div class="ms-lg-3">
                        <a class="nav-link btn-logout px-3" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-bold mb-0">Riwayat Pengaduan</h4>
                    <span class="text-muted small">Total: <strong><?= mysqli_num_rows(mysqli_query($conn, "SELECT id_aspirasi FROM aspirasi WHERE nis='$nis_user'")); ?></strong> Laporan</span>
                </div>

                <?php
                $query = mysqli_query($conn, "SELECT a.*, k.keterangan_kategori FROM aspirasi a 
                                              JOIN kategori k ON a.id_kategori = k.id_kategori 
                                              WHERE a.nis = '$nis_user' ORDER BY a.id_aspirasi DESC");

                if (mysqli_num_rows($query) == 0) {
                    echo "<div class='card p-5 text-center text-muted border-0 shadow-sm rounded-4'>
                            <i class='fas fa-folder-open fa-3x mb-3 text-light'></i>
                            <p>Belum ada riwayat pengaduan yang ditemukan.</p>
                          </div>";
                }

                while ($d = mysqli_fetch_array($query)) {
                    $statusClass = 'status-' . $d['status'];
                ?>
                    <div class="card card-aspirasi">
                        <div class="card-aspirasi-header">
                            <div>
                                <span class="text-primary fw-bold me-2"><?= $d['keterangan_kategori'] ?></span>
                                <span class="text-muted small">#<?= $d['id_aspirasi'] ?></span>
                            </div>
                            <span class="badge-status <?= $statusClass ?>">
                                <?= strtoupper($d['status']) ?>
                            </span>
                        </div>
                        <div class="card-body px-4 py-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="small text-muted mb-1">
                                        <i class="far fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($d['tanggal_pelaporan'])) ?>
                                        <span class="mx-2">|</span>
                                        <i class="far fa-clock me-1"></i> <?= date('H:i', strtotime($d['tanggal_pelaporan'])) ?> WIB
                                    </div>
                                    <div class="location-tag mt-2">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= $d['lokasi'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="content-text mb-3">
                                <h6 class="fw-bold small text-uppercase mb-2 text-secondary">Isi Laporan:</h6>
                                <p class="mb-0"><?= nl2br($d['keterangan']) ?></p>
                            </div>

                            <?php if ($d['feedback']): ?>
                                <div class="feedback-box">
                                    <div class="d-flex align-items-center mb-1 text-primary">
                                        <i class="fas fa-reply me-2"></i>
                                        <span class="fw-bold small">Tanggapan Admin:</span>
                                    </div>
                                    <p class="mb-0 small text-dark"><?= $d['feedback'] ?></p>
                                </div>
                            <?php else: ?>
                                <div class="mt-3 py-2 border-top text-center">
                                    <small class="text-muted italic">Laporan sedang ditinjau oleh admin...</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="text-center mt-4">
                    <a href="form_aspirasi.php" class="btn btn-primary rounded-pill px-4 shadow">
                        <i class="fas fa-plus me-2"></i>Buat Laporan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>