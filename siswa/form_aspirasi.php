<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['role'] != 'siswa') {
    header("Location: ../login.php");
}

$nis_user = $_SESSION['user'];

if (isset($_POST['kirim'])) {
    $kategori = $_POST['id_kategori'];
    $lokasi = $_POST['lokasi'];
    $keterangan = $_POST['keterangan'];

    $query = "INSERT INTO aspirasi (nis, id_kategori, lokasi, keterangan, status) 
              VALUES ('$nis_user', '$kategori', '$lokasi', '$keterangan', 'menunggu')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Aspirasi berhasil dikirim!'); window.location='riwayat.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa | Kirim Aspirasi</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="../style/aspirasiSiswa.css">
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
                    <a class="nav-link active" href="form_aspirasi.php">Buat Aduan</a>
                    <a class="nav-link" href="riwayat.php">Riwayat</a>
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
        <div class="row g-4">
            <div class="col-lg-4 d-none d-lg-block">
                <div class="illustration-box shadow-sm">
                    <img src="../assets/images/logo_skarisa.png" alt="Logo SKARISA" class="img-fluid" style="width:40px;">
                    <h5 class="fw-bold">Suara Kamu Berarti!</h5>
                    <p class="text-muted small">Laporkan kerusakan fasilitas sekolah agar segera kami perbaiki untuk kenyamanan belajar bersama.</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Form Pengaduan Sarana</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori Sarana</label>
                                    <select name="id_kategori" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php
                                        $kat = mysqli_query($conn, "SELECT * FROM kategori");
                                        while ($rk = mysqli_fetch_array($kat)) {
                                            echo "<option value='$rk[id_kategori]'>$rk[keterangan_kategori]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lokasi Kejadian</label>
                                    <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lab Komputer 1" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Detail Kerusakan / Masukan</label>
                                <textarea name="keterangan" class="form-control" rows="5" placeholder="Jelaskan secara detail apa yang rusak atau apa masukan kamu..." required></textarea>
                            </div>

                            <div class="d-grid d-md-flex justify-content-md-end">
                                <button type="submit" name="kirim" class="btn btn-primary px-5">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Aduan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-white border-start border-primary border-4 rounded shadow-sm">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1 text-primary"></i>
                        Aduan yang kamu kirim akan segera diperiksa oleh admin dalam waktu 1x24 jam.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>