<?php
//session_start();
//require_once '../../config/conn.php'; // sesuaikan dengan struktur kamu

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID permohonan tidak valid'); window.location='index.php?page=riwayat_permohonan';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data permohonan yang dimiliki user login
$permohonan_query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id='$id' AND user_id='$user_id'");
$permohonan = mysqli_fetch_assoc($permohonan_query);

if (!$permohonan) {
    echo "<script>alert('Data tidak ditemukan atau tidak berhak mengakses'); window.location='index.php?page=riwayat_permohonan';</script>";
    exit;
}

// Ambil detail barang
//$detail_query = mysqli_query($conn, "SELECT * FROM detail_permohonan WHERE id_permohonan='$id'");

$detail_query = mysqli_query($conn, "SELECT * FROM detail_permohonan WHERE permohonan_id='$id'");
$detail_query2 = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id = '$id'");

// Ambil data atasan
$atasan_query = mysqli_query($conn, "
    SELECT u1.id AS user_id, u1.name AS user_name, u1.atasan_id, 
           u2.name AS atasan_name, u2.nip_nik AS atasan_nip_nik
    FROM users u1
    LEFT JOIN users u2 ON u1.atasan_id = u2.id
    WHERE u1.id = $user_id
");
$atasan = mysqli_fetch_assoc($atasan_query);
$atasan_name = $atasan['atasan_name'] ?? 'Belum Diset';
$atasan_nip_nik = $atasan['atasan_nip_nik'] ?? '-';

// Ambil data pejabat PPK (id_level = 3)
$ppk_query = mysqli_query($conn, "SELECT name, nip_nik FROM users WHERE level_user = 3 LIMIT 1");
$ppk = mysqli_fetch_assoc($ppk_query);
$name_pejabat_ppk = $ppk['name'] ?? 'Belum Diset';
$nip_nik_pejabat_ppk = $ppk['nip_nik'] ?? '-';

// Ambil data pejabat KPA (id_level = 4)
$kpa_query = mysqli_query($conn, "SELECT name, nip_nik FROM users WHERE level_user = 4 LIMIT 1");
$kpa = mysqli_fetch_assoc($kpa_query);
$name_pejabat_kpa = $kpa['name'] ?? 'Belum Diset';
$nip_nik_pejabat_kpa = $kpa['nip_nik'] ?? '-';

// Tentukan base_url jika belum
$base_url = "http://localhost/sipatra2025"; // sesuaikan jika beda
?>



<body>

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Detail Permohonan</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="3" class="text-muted">Pages</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Proses Permohonan
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!-- <div class="col-5 align-self-center">
                        <div class="customize-input float-right">
                            <select
                                class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                                <option selected>Aug 19</option>
                                <option value="1">July 19</option>
                                <option value="2">Jun 19</option>
                            </select>
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- basic table -->
                <div class="row" id="print-area">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Column -->

                                </div>
                                <div class="table-responsive">
                                    <?php
                                    // Level saat ini dari permohonan
                                    $level = $permohonan['status2']; // asumsi status_id disimpan di kolom ini

                                    // Level yang dihitung untuk progress
                                    $progress_levels = [0, 1, 2, 3, 6, 7, 8];
                                    $total_steps = count($progress_levels); // 7
                                    $progress_percent = 0;

                                    // Hitung progres hanya jika level termasuk dalam progress_levels
                                    if (in_array($level, $progress_levels)) {
                                        $current_index = array_search($level, $progress_levels); // indeks ke-nya dalam array progress
                                        $progress_percent = round((($current_index + 1) / $total_steps) * 100);
                                    }

                                    // Label status (opsional)
                                    $status_labels = [
                                        0 => 'Diajukan',
                                        1 => 'Disetujui oleh KATIMJA',
                                        2 => 'Disetujui Pejabat Kasubbag Umum / PPK',
                                        3 => 'Disetujui Pejabat KPA',
                                        4 => 'Ditolak',
                                        5 => 'Revisi',
                                        6 => 'Menunggu Pencairan',
                                        7 => 'Proses Belanja',
                                        8 => 'Selesai'
                                    ];
                                    ?>
                                    <div class="table-responsive">
                                        <div class="progress mt-4">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" aria-valuenow="<?= $progress_percent ?>"
                                                aria-valuemin="0" aria-valuemax="100"
                                                style="width: <?= $progress_percent ?>%;">
                                            </div>
                                        </div>
                                        <p class="mt-2">
                                            Proses Permohonan: <strong><?= $progress_percent ?>%</strong> —
                                            <em>Status: <?= $status_labels[$level] ?? 'Tidak Diketahui' ?> </em>
                                        </p>

                                    </div>
                                    <!-- <div class="row">
                                    <div class="col-12">
                                        <h4 class="mb-0">Detail Permohonan</h4> 
                                <div class="card">
                                    <div class="card-body collapse show">



                                        <p class="card-text">With supporting text below as a natural lead-in
                                                        to additional content.</p> 
                                    </div>
                                </div>
                            </div>
                        </div> -->
                                    <h5 class="card-title">Detail Permohonan
                                        : <?= htmlspecialchars($permohonan['nomor_permohonan']); ?></h5>
                                    <h5 class="card-title"><strong>Mata Anggaran:</strong>
                                        <?php if ($_SESSION['level_user'] === 3): ?>
                                            <form method="post">
                                                <input type="text" name="mata_anggaran"
                                                    value="<?= htmlspecialchars($permohonan['mata_anggaran']); ?>">
                                                <button type="submit" name="update_mata_anggaran">Simpan</button>
                                            </form>
                                        <?php else: ?>
                                            <?= htmlspecialchars($permohonan['mata_anggaran']); ?>
                                        <?php endif; ?>
                                    </h5>
                                    <h5 class="card-title"><strong>Tanggal Permohonan:</strong>
                                        <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?></h4>
                                        <h5 class="card-title"><strong>Total Harga:</strong> Rp.
                                            <?= number_format($permohonan['grand_total_harga'], 0, ',', '.'); ?></h5>
                                        <h5 class="card-title">Detail Barang:</h5>
                                        <!-- tabel detail permohonan -->
                                        <div class="table">
                                            <!-- END NEW TABLE -->
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Satuan</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <?php $no = 1;
                                                while ($barang = mysqli_fetch_assoc($detail_query2)): ?>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td><?= htmlspecialchars($barang['nama_barang']); ?></td>
                                                            <td><?= htmlspecialchars($barang['satuan']); ?></td>
                                                            <td>Rp.
                                                                <?= number_format($barang['harga_satuan'], 0, ',', '.'); ?>
                                                            </td>
                                                            <td><?= $barang['jumlah_barang']; ?></td>
                                                            <td>Rp.
                                                                <?= number_format($barang['subtotal_harga'], 0, ',', '.'); ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-end">Jumlah</th>
                                                            <th>Rp.
                                                                <?= number_format($permohonan['grand_total_harga'], 0, ',', '.'); ?>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                <?php endwhile; ?>
                                            </table>
                                        </div>


                                        <div class="row"> </div><br>
                                        <!-- ============================================================== -->
                                        <div class="row mt-4">

                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Ambon,
                                                        <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?>
                                                        Pemohon,
                                                    </h5>
                                                    <img src="<?= $base_url ?>/pages/pemohon/<?= htmlspecialchars($permohonan['qr_code_pemohon']); ?>"
                                                        alt="QR Code" width="100">
                                                    <br>
                                                    <h5><?= htmlspecialchars($_SESSION['name']); ?></h5>
                                                    <h5>NIP | NIK : <?= htmlspecialchars($_SESSION['nip_nik']); ?></h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>

                                        </div>
                                        <div class="row">
                                            <?php
                                            $user_login_id = $_SESSION['user_id'];

                                            $query = "
                                            SELECT 
                                                u1.id AS user_id, 
                                                u1.name AS user_name, 
                                                u1.atasan_id, 
                                                u2.name AS atasan_name, 
                                                u2.nip_nik AS atasan_nip_nik,
                                                u2.jabatan AS jabatan_atasan
                                            FROM users u1
                                            LEFT JOIN users u2 ON u1.atasan_id = u2.id
                                            WHERE u1.id = ?
                                        ";

                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("i", $user_login_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $atasan = $result->fetch_assoc();

                                            // Cek data
                                            $atasan_name = $atasan['atasan_name'] ?? 'Tidak Ditemukan';
                                            $atasan_nip_nik = $atasan['atasan_nip_nik'] ?? '-';
                                            $jabatan_atasan = $atasan['jabatan_atasan'] ?? '-';
                                            ?>

                                            <div class="col-lg-4 col-md-12">
                                                <br>
                                                <div class="text-center">
                                                    <h5>Mengetahui,<br><?= htmlspecialchars($jabatan_atasan); ?></h5>

                                                    <?php if (!empty($qr_katimja['signature'])): ?>
                                                        <img src="<?= htmlspecialchars($qr_katimja['signature']); ?>"
                                                            width="100">
                                                    <?php else: ?>
                                                        <BR>
                                                        <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>

                                                    <br>
                                                    <h5><?= htmlspecialchars($atasan_name); ?></h5>
                                                    <h5>NIP: <?= htmlspecialchars($atasan_nip_nik); ?></h5>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <br>
                                                <?php
                                                // Ambil pejabat PPK dari tabel users dengan level_user = 3
                                                $ppk_query = mysqli_query($conn, "SELECT id, name, nip_nik, jabatan FROM users WHERE level_user = 3 LIMIT 1");
                                                $ppk = mysqli_fetch_assoc($ppk_query);
                                                $ppk_id = $ppk['id'] ?? 0;
                                                $name_pejabat_ppk = $ppk['name'] ?? 'Belum Diset';
                                                $nip_nik_pejabat_ppk = $ppk['nip_nik'] ?? '-';
                                                $jabatan_pejabat_ppk = $ppk['jabatan'] ?? 'Plt. Kasubbag Umum / PPK';

                                                // Ambil QR code dari tabel signatures
                                                $qrsig_ppk_query = mysqli_query($conn, "SELECT signature FROM signatures WHERE permohonan_id = '$id' AND pejabat_id = '$ppk_id' LIMIT 1");
                                                $qrsig_ppk = mysqli_fetch_assoc($qrsig_ppk_query);
                                                $qr_code_ppk = $qrsig_ppk['signature'] ?? '';
                                                ?>
                                                <div class="text-center">
                                                    <h5><?= htmlspecialchars($jabatan_pejabat_ppk); ?></h5>

                                                    <?php if (!empty($qr_code_ppk)): ?>
                                                        <img src="<?= htmlspecialchars($qr_code_ppk); ?>" alt="QR Code PPK"
                                                            width="100">
                                                    <?php else: ?><br>
                                                        <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>

                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_ppk); ?></h5>
                                                    <h5>NIP: <?= htmlspecialchars($nip_nik_pejabat_ppk); ?></h5>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <?php
                                                // Ambil data pejabat KPA dari tabel users (level_user = 4)
                                                $kpa_query = mysqli_query($conn, "SELECT id, name, nip_nik, jabatan FROM users WHERE level_user = 4 LIMIT 1");
                                                $kpa = mysqli_fetch_assoc($kpa_query);
                                                $kpa_id = $kpa['id'] ?? 0;
                                                $name_pejabat_kpa = $kpa['name'] ?? 'Belum Diset';
                                                $nip_nik_pejabat_kpa = $kpa['nip_nik'] ?? '-';
                                                $jabatan_pejabat_kpa = $kpa['jabatan'] ?? 'Kuasa Pengguna Anggaran';

                                                // Cek QR code dari tabel signatures
                                                $qrsig_kpa_query = mysqli_query($conn, "SELECT signature FROM signatures WHERE permohonan_id = '$id' AND pejabat_id = '$kpa_id' LIMIT 1");
                                                $qrsig_kpa = mysqli_fetch_assoc($qrsig_kpa_query);
                                                $qr_code_kpa = $qrsig_kpa['signature'] ?? '';

                                                ?>
                                                <br>

                                                <div class="text-center">
                                                    <h5>Menyetujui,<br><?= htmlspecialchars($jabatan_pejabat_kpa); ?>
                                                    </h5>

                                                    <?php if (!empty($qr_code_kpa)): ?>
                                                        <img src="<?= htmlspecialchars($qr_code_kpa); ?>" alt="QR Code KPA"
                                                            width="100"><br><br>
                                                    <?php else: ?>
                                                        <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>

                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_kpa); ?></h5>
                                                    <h5>NIP: <?= htmlspecialchars($nip_nik_pejabat_kpa); ?></h5>
                                                </div>


                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                        </div>



                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- <div class="row mt-4">
                    <div class="col-12 text-center">
                        <div class="d-flex justify-content-center flex-wrap gap-3">
                            <button><a href="index.php?page=riwayat_permohonan" class="btn btn-dark btn-lg px-4">
                                    <i class="mdi mdi-arrow-left"></i> Kembali</a>
                            </button>
                            <button onclick="window.print()" class="btn btn-primary btn-lg px-4">
                                <i class="mdi mdi-printer"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div> -->
                    <div class="form-actions">
                        <div class="text-right">
                            <!-- <button type="submit" class="btn btn-info" id="addItem">Tambah</button> -->
                            <!-- <button onclick="window.print()" class="btn btn-primary">Cetak</button> -->
                            <a href="index.php?page=riwayat_permohonan" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>


                    <br>

                    <!-- ============================================================== -->
                    <!-- End Page wrapper  -->
                    <!-- ============================================================== -->
                    <?php
                    // Ambil data permohonan dan status_permohonan
                    $id = $_GET['id'] ?? 0;

                    $query = "SELECT p.*, sp.nama_status, sp.id_status
                            FROM permohonan p
                            JOIN status_permohonan sp ON p.status2 = sp.id_status
                            WHERE p.id = ? AND p.user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $permohonan = $result->fetch_assoc();

                    ?>
                    <?php if ($permohonan['id_status'] == 7 || $permohonan['nama_status'] === 'Proses Belanja'): ?>
                        <!-- ============================================================== -->
                        <div class="card-group">
                            <div class="card border-right">
                                <div class="card-body">
                                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                                        <h4 class="card-title">Upload Data Dukung Pembelian Barang</h4>
                                        <div class="ml-auto mt-md-3 mt-lg-0">
                                            <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <h4 class="card-title">Kelengkapan</h4>
                                    <form action="proses_upload.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Nota/Kwitansi Pembelian</label>
                                            <input type="file" class="form-control" name="nota"
                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                            <small class="form-text text-muted">Format: PDF, JPG, PNG (Max 2MB)</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto Geotagging Barang</label>
                                            <input type="file" class="form-control" name="foto_barang"
                                                accept=".jpg,.jpeg,.png" required>
                                            <small class="form-text text-muted">Format: JPG, PNG (Max 2MB)</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Dokumen Pendukung Lainnya (Opsional)</label>
                                            <input type="file" class="form-control" name="dokumen_lain"
                                                accept=".pdf,.doc,.docx">
                                            <small class="form-text text-muted">Format: PDF, DOC (Max 5MB)</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea class="form-control" name="keterangan" rows="3"
                                                placeholder="Masukkan keterangan tambahan jika ada"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- <div class="card-group">
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <h4 class="card-title">
                                    Upload Data Dukung Pembelian Barang
                                </h4>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Kelengkapan</h4>
                            <form action="proses_upload.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Nota/Kwitansi Pembelian</label>
                                    <input type="file" class="form-control" name="nota" accept=".pdf,.jpg,.jpeg,.png"
                                        required>
                                    <small class="form-text text-muted">Format file: PDF, JPG, JPEG, PNG (Max.
                                        2MB)</small>
                                </div>
                                <div class="form-group">
                                    <label>Foto Geotagging Barang</label>
                                    <input type="file" class="form-control" name="foto_barang" accept=".jpg,.jpeg,.png"
                                        required>
                                    <small class="form-text text-muted">Format file: JPG, JPEG, PNG (Max. 2MB)</small>
                                </div>
                                <div class="form-group">
                                    <label>Dokumen Pendukung Lainnya (Optional)</label>
                                    <input type="file" class="form-control" name="dokumen_lain"
                                        accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Format file: PDF, DOC, DOCX (Max. 5MB)</small>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="3"
                                        placeholder="Masukkan keterangan tambahan jika ada"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div> -->


                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Wrapper -->
            <!-- ============================================================== -->
            <!-- End Wrapper -->
            <!-- ============================================================== -->
            <!-- All Jquery -->
            <!-- ============================================================== -->

            <script src="<?php echo $base_url ?>/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js">
            </script>
            <script src="<?php echo $base_url ?>/dist/js/sidebarmenu.js"></script>
            <!--Custom JavaScript -->
            <script src="<?php echo $base_url ?>/dist/js/custom.min.js"></script>

            <!-- All Jquery -->
            <!-- ============================================================== -->
            <script src="<?php echo $base_url ?>/assets/libs/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap tether Core JavaScript -->
            <script src="<?php echo $base_url ?>/assets/libs/popper.js/dist/umd/popper.min.js"></script>
            <script src="<?php echo $base_url ?>/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
            <!-- apps -->
            <!-- apps -->
            <script src="<?php echo $base_url ?>/dist/js/app-style-switcher.js"></script>
            <script src="<?php echo $base_url ?>/dist/js/feather.min.js"></script>
            <!-- slimscrollbar scrollbar JavaScript -->
            <script src="<?php echo $base_url ?>/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js">
            </script>
            <script src="<?php echo $base_url ?>/assets/extra-libs/sparkline/sparkline.js"></script>
            <!--Wave Effects -->
            <!-- themejs -->
            <!--Menu sidebar -->
            <script src="<?php echo $base_url ?>/dist/js/sidebarmenu.js"></script>
            <!--Custom JavaScript -->
            <script src="<?php echo $base_url ?>/dist/js/custom.min.js"></script>
            <!--This page plugins -->
            <script src="<?php echo $base_url ?>/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
            <script src="<?php echo $base_url ?>/dist/js/pages/datatable/datatable-basic.init.js"></script>
            <script>
                function printSelected() {
                    var printContents = document.getElementById('print-area').innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;
                    window.print();

                    document.body.innerHTML = originalContents;
                    location.reload(); // reload ulang agar kembali normal setelah print
                }
            </script>