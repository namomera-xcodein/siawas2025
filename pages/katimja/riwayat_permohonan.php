<?php
//include '../config/config.php';
//include '$baseconfig/config.php'; 
//include '../../config/functions.php';

// Mendapatkan daftar riwayat permohonan
function getRiwayatPermohonanPejabatSatu($conn) {
    // $sql = "SELECT p.*, u.name AS nama_pemohon, s.nama_status 
    //         FROM permohonan p 
    //         LEFT JOIN users u ON p.user_id = u.id
    //         LEFT JOIN status_permohonan s ON p.status2 = s.id_status 
    //         WHERE s.nama_status = 'Menunggu Persetujuan SPM'";

    $sql = "SELECT p.*, u.name AS nama_pemohon 
            FROM permohonan p
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.status = 'Menunggu Persetujuan KPA';";
            
    $result = mysqli_query($conn, $sql);

    // Cek apakah query berhasil dijalankan
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }

    $riwayat_permohonan = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $riwayat_permohonan[] = $row;
    }
    
    return $riwayat_permohonan;

}
$requests = getRiwayatPermohonanPejabatSatu($conn);

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
                                    <li class="breadcrumb-item"><a href="#" class="text-muted">Pages</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Riwayat Permohonan
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
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-primary text-center">
                                                <h1 class="font-light text-white">2,064</h1>
                                                <h6 class="text-white">Permohonan Diajukan</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-cyan text-center">
                                                <h1 class="font-light text-white">1,738</h1>
                                                <h6 class="text-white">Permohonan Proses</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-success text-center">
                                                <h1 class="font-light text-white">1100</h1>
                                                <h6 class="text-white">Disetujui</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-danger text-center">
                                                <h1 class="font-light text-white">964</h1>
                                                <h6 class="text-white">Ditolak</h6>
                                            </div>
                                        </div>
                                    </div>







                                    <!-- Column -->
                                </div>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Permohonan</th>
                                                <th>Nomor Permohonan</th>
                                                <th>Nama Pemohon</th>
                                                <th>Status</th>
                                                <th>Status2</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php if (!empty($requests)) { ?>
                                                <?php $no = 1; foreach ($requests as $row) { ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= date('d F Y', strtotime($row['tanggal_permohonan'])); ?></td>
                                                <td><?= htmlspecialchars($row['nomor_permohonan']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_pemohon']); ?></td>
                                                <td><?= htmlspecialchars($row['status']); ?></td>
                                                <!-- Nama status dari status_permohonan -->
                                                <td><?= htmlspecialchars($row['status2']); ?></td>
                                                <!-- ID Status dari permohonan -->
                                                <td>
                                                    <a href="<?=$base_url?>/index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                        class="btn btn-info btn-sm">📑Detail</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php } else { ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data ditemukan.</td>
                                            </tr>
                                            <?php } ?>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Permohonan</th>
                                                <th>Nomor Permohonan</th>
                                                <th>Nama Pemohon</th>
                                                <th>Status</th>
                                                <th>Status2</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

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