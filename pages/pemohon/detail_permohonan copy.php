<?php
//session_start();
//include '../config/config.php';
// var_dump($id_permohonan);
// exit;
// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}



// Dapatkan ID permohonan dari URL
$id_permohonan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data permohonan
// Ambil data permohonan
$permohonan_query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id = $id_permohonan");
$permohonan = mysqli_fetch_assoc($permohonan_query);

if (!$permohonan) {
    echo "<script>alert('Permohonan tidak ditemukan!'); window.location.href='index.php?page=permohonan';</script>";
    exit;
}

// Ambil detail barang
$detail_query = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id = $id_permohonan");

// Update Mata Anggaran jika Pejabat PPK
if (isset($_POST['update_mata_anggaran']) && $_SESSION['role'] === 'pejabat_ppk') {
    $mata_anggaran = mysqli_real_escape_string($conn, $_POST['mata_anggaran']);
    mysqli_query($conn, "UPDATE permohonan SET mata_anggaran='$mata_anggaran' WHERE id=$id_permohonan");
    echo "<script>alert('Mata Anggaran diperbarui!'); window.location.reload();</script>";
}

// Query untuk mengambil data pejabat berdasarkan role
$query = "SELECT id, name, nip_nik, role FROM users WHERE role IN ('pejabat_spm', 'pejabat_ppk', 'pejabat_kpa')";
$result = $conn->query($query);
// Ambil data QR Code SPM dari permohonan
$qr_query = mysqli_query($conn, "SELECT qr_code_spm FROM permohonan WHERE id = $id_permohonan");
$qr_data = mysqli_fetch_assoc($qr_query);
$qr_code_spm = $qr_data['qr_code_spm'];
if ($id_permohonan > 0) {
    $qr_query = mysqli_query($conn, "SELECT qr_code_spm FROM permohonan WHERE id = $id_permohonan");
    $qr_data = mysqli_fetch_assoc($qr_query);
    $qr_code_spm = $qr_data['qr_code_spm'] ?? null;
} else {
    die("Error: ID permohonan tidak valid.");
}
// Inisialisasi variabel kosong
$nip_nik_pejabat_spm = $name_pejabat_spm = null;
$nip_nik_pejabat_ppk = $name_pejabat_ppk = null;
$nip_nik_pejabat_kpa = $name_pejabat_kpa = null;

// Menyimpan data ke variabel berdasarkan role
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['role'] == 'pejabat_spm') {
            $nip_nik_pejabat_spm = $row['nip_nik'];
            $name_pejabat_spm = $row['name'];
        } elseif ($row['role'] == 'pejabat_ppk') {
            $nip_nik_pejabat_ppk = $row['nip_nik'];
            $name_pejabat_ppk = $row['name'];
        } elseif ($row['role'] == 'pejabat_kpa') {
            $nip_nik_pejabat_kpa = $row['nip_nik'];
            $name_pejabat_kpa = $row['name'];
        }
    }
}
// Ambil data pejabat berdasarkan levelnya
$query_pejabat_1 = "SELECT users.name, users.nip_nik, level.level_jabatan FROM users 
                      JOIN level ON users.level_user = level.id_level 
                      WHERE level.level_jabatan = 'Pejabat 1'";
$result_pejabat1 = mysqli_query($conn, $query_pejabat_1);
$pejabat_1 = mysqli_fetch_assoc($result_pejabat1);

$query_pejabat_2 = "SELECT users.name, users.nip_nik, level.level_jabatan FROM users 
                      JOIN level ON users.level_user = level.id_level 
                      WHERE level.level_jabatan = 'Pejabat 2'";
$result_pejabat2 = mysqli_query($conn, $query_pejabat_2);
$pejabat_2 = mysqli_fetch_assoc($result_pejabat2);

$query_pejabat_3 = "SELECT users.name, users.nip_nik, level.level_jabatan FROM users 
                      JOIN level ON users.level_user = level.id_level 
                      WHERE level.level_jabatan = 'Pejabat 3'";
$result_pejabat3 = mysqli_query($conn, $query_pejabat_3);
$pejabat_3 = mysqli_fetch_assoc($result_pejabat3);
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Column -->

                                </div>
                                <div class="table-responsive">
                                    <div class="progress mt-4">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar" aria-valuenow="<?php
                                                                                if ($permohonan['status'] == 'Menunggu Persetujuan SPM') {
                                                                                    echo '50';
                                                                                } else if ($permohonan['status'] == 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK') {
                                                                                    echo '75';
                                                                                } else if ($permohonan['status'] == 'Menunggu Persetujuan KPA') {
                                                                                    echo '100';
                                                                                }
                                                                                ?>" aria-valuemin="0"
                                            aria-valuemax="100" style="width: <?php
                                                                                if ($permohonan['status'] == 'Menunggu Persetujuan SPM') {
                                                                                    echo '50%';
                                                                                } else if ($permohonan['status'] == 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK') {
                                                                                    echo '75%';
                                                                                } else if ($permohonan['status'] == 'Menunggu Persetujuan KPA') {
                                                                                    echo '100%';
                                                                                }
                                                                                ?>">

                                        </div>
                                        <p>"Proses Permohonan
                                            <?php
                                            if ($permohonan['status'] == 'Menunggu Persetujuan SPM') {
                                                echo '50%';
                                            } else if ($permohonan['status'] == 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK') {
                                                echo '75%';
                                            } else if ($permohonan['status'] == 'Menunggu Persetujuan KPA') {
                                                echo '100%';
                                            }
                                            ?>"
                                        </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <h4 class="mb-0">Detail Permohonan</h4> -->
                                            <div class="card">
                                                <div class="card-body collapse show">



                                                    <!-- <p class="card-text">With supporting text below as a natural lead-in
                                                        to additional content.</p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Detail Permohonan
                                        :<?= htmlspecialchars($permohonan['nomor_permohonan']); ?></h5>
                                    <h5 class="card-title"><strong>Mata Anggaran:</strong>
                                        <?php if ($_SESSION['role'] === 'pejabat_ppk'): ?>
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
                                        <!-- NEW Table -->
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
                                                while ($barang = mysqli_fetch_assoc($detail_query)): ?>
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
                                        <div class="row">
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
                                                    <!-- <h5><?= htmlspecialchars($_SESSION['nip_nik']); ?></h5> -->

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>

                                        </div>
                                        <div class="row">
                                            <?php
                                            // Query untuk mendapatkan data user dengan atasan_id
                                            $query = "SELECT 
            u1.id AS user_id, 
            u1.name AS user_name, 
            u1.atasan_id, 
            u2.name AS atasan_name, 
            u2.nip_nik AS atasan_nip_nik
          FROM users u1
          JOIN users u2 ON u1.atasan_id = u2.id
          WHERE u1.id = 2 AND u1.atasan_id = 11";

                                            $result = mysqli_query($conn, $query);
                                            $atasan = mysqli_fetch_assoc($result);

                                            // Pastikan data atasan ada
                                            if ($atasan) {
                                                $atasan_name = $atasan['atasan_name'];
                                                $atasan_nip_nik = $atasan['atasan_nip_nik'];
                                            } else {
                                                $atasan_name = "Tidak Ditemukan";
                                                $atasan_nip_nik = "-";
                                            }
                                            ?>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Mengetahui,<br>
                                                        Ketua Tim Kerja SDM,
                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_spm'])): ?>
                                                        <img src="<?= htmlspecialchars($qr_code_spm); ?>"
                                                            alt="<?php $pejabat1 ?>" width="100">
                                                    <?php else: ?>
                                                        <div class="card-title" text-muted>--Waiting--</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($atasan_name); ?></h5>
                                                    <h5>NIP :<?= htmlspecialchars($atasan_nip_nik); ?></h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Plt. Kasubbag Umum /PPK,<br>
                                                        <br>
                                                        <br>
                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_ppk'])): ?>
                                                        <img src="<?= htmlspecialchars($permohonan['qr_code_ppk']); ?>"
                                                            alt="QR Code PPK" width="100">
                                                    <?php else: ?>
                                                        <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_ppk); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pejabat_ppk); ?></h5>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Menyetujui,<br>Kuasa Pengguna Anggaran,<br>

                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_kpa'])): ?>
                                                        <img src="<?= htmlspecialchars($permohonan['qr_code_kpa']); ?>"
                                                            alt="QR Code SPM" width="100"><br>
                                                    <?php else: ?>
                                                        <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_kpa); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pejabat_kpa); ?></h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                        </div>



                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="button-list">

                        <div class="col-lg-12 text-center">
                            <!-- <button type="submit" class="btn btn-info" id="addItem">Tambah</button> -->
                            <a href="index.php?page=riwayat_permohonan" class="btn btn-dark">Kembali</a>
                        </div>
                    </div>

                    <div class="form-actions alert-danger">
                        <div class="col-lg-12 text-center">

                            <button onclick="window.print()" class="btn btn-primary">Cetak</button>

                        </div>
                    </div>
                </div>
                <br>

                <!-- ============================================================== -->
                <!-- End Page wrapper  -->
                <!-- ============================================================== -->
                <div class="card-group">
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
                </div>


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