<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
}

// Proses Update Status & Feedback (TIDAK DIUBAH)
if (isset($_POST['update_aspirasi'])) {
  $id = $_POST['id_aspirasi'];
  $status = $_POST['status'];
  $feedback = $_POST['feedback'];

  $query = mysqli_query($conn, "UPDATE aspirasi SET status='$status', feedback='$feedback' WHERE id_aspirasi='$id'");
  if ($query) {
    echo "<script>alert('Status berhasil diperbarui!'); window.location='aspirasi_list.php';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | List Aspirasi</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/style/aspirasiAdmin.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <span class="ml-auto pr-3 d-none d-sm-inline-block text-muted">Panel Admin</span>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark elevation-4">
      <a href="#" class="brand-link">
        <span class="brand-text font-weight-bold text-primary pl-3">E-PENGADUAN</span>
      </a>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column">

            <li class="nav-item">
              <a href="dashboard.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="aspirasi_list.php" class="nav-link active">
                <i class="nav-icon fas fa-list"></i>
                <p>Data Aspirasi</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="siswa_add.php" class="nav-link">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>Kelola Siswa</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="cetak_laporan.php" target="_blank" class="nav-link">
                <i class="nav-icon fas fa-print"></i>
                <p>Cetak Laporan</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="../logout.php" class="nav-link bg-danger">
                <i class="fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>

          </ul>
        </nav>
      </div>
    </aside>

    <!-- Content -->
    <div class="content-wrapper p-3">

      <section class="content-header">
        <h1 class="m-0 text-dark">Data Pengaduan</h1>
      </section>

      <section class="content">
        <div class="row">

          <?php
          $sql = "SELECT a.*, s.kelas, k.keterangan_kategori 
                FROM aspirasi a 
                JOIN siswa s ON a.nis = s.nis 
                JOIN kategori k ON a.id_kategori = k.id_kategori";

          $res = mysqli_query($conn, $sql);

          while ($row = mysqli_fetch_array($res)) {

            $c = ($row['status'] == 'menunggu') ? 'warning' : (($row['status'] == 'proses') ? 'info' : 'success');
          ?>

            <!-- CARD -->
            <div class="col-12 col-md-6 col-lg-4 mb-3">
              <div class="card aspirasi-card h-100">

                <div class="card-header d-flex justify-content-between align-items-center">
                  <small><?= date('d/m/Y', strtotime($row['tanggal_pelaporan'])) ?></small>
                  <span class="badge badge-<?= $c ?>">
                    <?= strtoupper($row['status']) ?>
                  </span>
                </div>

                <div class="card-body small">

                  <p><b>NIS:</b> <?= $row['nis'] ?></p>
                  <p><b>Kelas:</b> <?= $row['kelas'] ?></p>
                  <p><b>Kategori:</b> <?= $row['keterangan_kategori'] ?></p>
                  <p><b>Lokasi:</b> <?= $row['lokasi'] ?></p>

                  <hr>

                  <b>Keluhan:</b>
                  <p class="text-muted"><?= $row['keterangan'] ?></p>

                  <?php if ($row['feedback']) { ?>
                    <div class="bg-light p-2 rounded">
                      <b>Feedback:</b><br>
                      <?= $row['feedback'] ?>
                    </div>
                  <?php } ?>

                </div>

                <div class="card-footer text-right bg-white">
                  <button class="btn btn-sm btn-primary"
                    data-toggle="modal"
                    data-target="#modalEdit<?= $row['id_aspirasi'] ?>">
                    <i class="fas fa-edit"></i> Respon
                  </button>
                </div>
              </div>
            </div>


            <!-- MODAL (tetap sama persis backendnya) -->
            <div class="modal fade" id="modalEdit<?= $row['id_aspirasi'] ?>">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title">Respon Pengaduan</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                      <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">

                      <div class="bg-light p-2 mb-3 rounded">
                        <strong>Isi Keluhan:</strong><br>
                        <small><?= $row['keterangan'] ?></small>
                      </div>

                      <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                          <option value="menunggu" <?= $row['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                          <option value="proses" <?= $row['status'] == 'proses' ? 'selected' : '' ?>>Proses</option>
                          <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Feedback</label>
                        <textarea name="feedback" class="form-control" rows="4" required><?= $row['feedback'] ?></textarea>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                      <button type="submit" name="update_aspirasi" class="btn btn-success">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          <?php } ?>

        </div>
      </section>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>

</html>