<?php
include 'config/koneksi.php';

// Hitung statistik untuk dashboard depan
$total   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi"));
$selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='selesai'"));
$proses  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aspirasi WHERE status='proses'"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Aspirasi Sekolah</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="style/index.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fas fa-rocket me-2"></i>E-PENGADUAN
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#statistik">Statistik</a></li>
                    <li class="nav-item"><a class="nav-link" href="#data">Data Terkini</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary rounded-pill px-4" href="login.php">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Admin / Siswa
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- ================= HERO ================= -->
    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-5 mb-3">Suaramu, Perubahan Bagi Sekolah</h1>
            <p class="lead opacity-75 mb-4">
                Laporkan kerusakan sarana prasarana sekolah secara transparan untuk kenyamanan belajar bersama.
            </p>
            <a href="login.php" class="btn btn-cta">Kirim Aduan Sekarang</a>
        </div>
    </header>


    <!-- ================= STATISTIK ================= -->
    <section id="statistik" class="container my-5">
        <div class="row g-4 justify-content-center">

            <div class="col-md-4 col-lg-3">
                <div class="card stat-card shadow text-center p-4">
                    <div class="stat-icon-circle bg-primary-subtle text-primary">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                    <h3 class="fw-bold"><?= $total ?></h3>
                    <small class="text-muted">Total Laporan</small>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="card stat-card shadow text-center p-4">
                    <div class="stat-icon-circle bg-info-subtle text-info">
                        <i class="fas fa-sync"></i>
                    </div>
                    <h3 class="fw-bold"><?= $proses ?></h3>
                    <small class="text-muted">Dalam Proses</small>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="card stat-card shadow text-center p-4">
                    <div class="stat-icon-circle bg-success-subtle text-success">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3 class="fw-bold"><?= $selesai ?></h3>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>

        </div>
    </section>


    <!-- ================= TABLE DATA ================= -->
    <section id="data" class="container my-5">

        <div class="card card-table shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="tabelPublik" class="table table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sql = mysqli_query($conn, "SELECT a.*, k.keterangan_kategori 
                                                        FROM aspirasi a 
                                                        JOIN kategori k ON a.id_kategori = k.id_kategori 
                                                        ORDER BY a.tanggal_pelaporan DESC");

                            while ($row = mysqli_fetch_array($sql)) {
                            ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($row['tanggal_pelaporan'])) ?></td>
                                    <td class="text-primary fw-semibold"><?= $row['keterangan_kategori'] ?></td>
                                    <td><?= $row['lokasi'] ?></td>
                                    <td><?= substr($row['keterangan'], 0, 40) ?>...</td>

                                    <td class="text-center">
                                        <?php if ($row['status'] == 'proses'): ?>
                                            <span class="badge badge-custom bg-info">Proses</span>
                                        <?php elseif ($row['status'] == 'selesai'): ?>
                                            <span class="badge badge-custom bg-success">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge badge-custom bg-warning text-dark">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </section>


    <!-- ================= FOOTER ================= -->
    <footer class="bg-dark text-white text-center py-4">
        <small>&copy; 2026 SMK AL-IRSYAD TEGAL</small>
    </footer>


    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelPublik').DataTable({
                pageLength: 10,
                ordering: false
            });
        });
    </script>

</body>

</html>