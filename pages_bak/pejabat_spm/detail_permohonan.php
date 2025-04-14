<?php
//session_start();
//include '../config/config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Dapatkan ID permohonan dari URL
$id_permohonan = isset($_GET['id']) ? intval($_GET['id']) : 0;

//panggil user pejabat


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
?>

<body>

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> -->
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
                                                if($permohonan['status'] == 'Menunggu Persetujuan SPM') {
                                                    echo '50';
                                                } else if($permohonan['status'] == 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK') {
                                                    echo '75';
                                                } else if($permohonan['status'] == 'Menunggu Persetujuan KPA') {
                                                    echo '100';
                                                }
                                                ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php 
                                                if($permohonan['status'] == 'Menunggu Persetujuan SPM') {
                                                    echo '50%';
                                                } else if($permohonan['status'] == 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK') {
                                                    echo '75%';
                                                } else if($permohonan['status'] == 'Menunggu Persetujuan KPA') {
                                                    echo '100%';
                                                }
                                            ?>">

                                        </div>
                                        <p>"Proses Permohonan
                                            <?php 
                                                if($permohonan['status'] == 'Menunggu Persetujuan SPM') {
                                                    echo '50%';
                                                } else if($permohonan['status'] == 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK') {
                                                    echo '75%';
                                                } else if($permohonan['status'] == 'Menunggu Persetujuan KPA') {
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
                                                <?php $no = 1; while ($barang = mysqli_fetch_assoc($detail_query)): ?>
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
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Mengetahui,<br>
                                                        Pejabat Penandatanganan SPM,
                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_spm'])): ?>
                                                    <img src="<?= htmlspecialchars($permohonan['qr_code_spm']); ?>"
                                                        alt="QR Code SPM" width="100">
                                                    <?php else: ?>
                                                    <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_spm); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pejabat_spm); ?></h5>

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
                                                        alt="QR Code SPM" width="100">
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
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="../../dist/js/app-style-switcher.js"></script>
    <script src="../../dist/js/feather.min.js"></script>
    <script src="../../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>