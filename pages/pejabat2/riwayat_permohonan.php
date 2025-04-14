<?php
//include '../config/config.php';
//include '$baseconfig/config.php'; 
//include '../../config/functions.php';

// Mendapatkan daftar riwayat permohonan
//$requests = getAllRequestsAdmin($conn);
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']); // Hapus notifikasi setelah ditampilkan
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
$id_katimja = $_SESSION['user_id']; // ID Katimja yang sedang login

$query = "
    SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, 
           p.grand_total_harga,
           u.name AS nama_pemohon,
           s.nama_status,
           s.deskripsi_status
    FROM permohonan p
    JOIN users u ON p.user_id = u.id
    LEFT JOIN status_permohonan s ON p.status2 = s.id_status
    WHERE p.status2 = ? AND u.atasan_id = ?
    ORDER BY p.tanggal_permohonan DESC
";

$stmt = $conn->prepare($query);

// Ganti angka ini sesuai status penolakan Katimja, contoh 8
$status = 1;
$stmt->bind_param("ii", $status, $id_katimja);
$stmt->execute();
$requests = $stmt->get_result();
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
                                                <?php
                                                $id_katimja = $_SESSION['user_id'];

                                                $query_menunggu = "
                                                    SELECT COUNT(*) AS total_menunggu
                                                    FROM permohonan p
                                                    JOIN users u ON p.user_id = u.id
                                                    WHERE p.status2 = 0 AND u.atasan_id = ?
                                                ";

                                                $stmt_menunggu = $conn->prepare($query_menunggu);
                                                $stmt_menunggu->bind_param("i", $id_katimja);
                                                $stmt_menunggu->execute();
                                                $result_menunggu = $stmt_menunggu->get_result();
                                                $row_menunggu = $result_menunggu->fetch_assoc();
                                                $total_menunggu = $row_menunggu['total_menunggu'] ?? 0;
                                                ?>
                                                <h1 class="font-light text-white"><?php echo $total_menunggu ?></h1>
                                                <h6 class="text-white">Menunggu</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->

                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-success text-center">

                                                <?php
                                                $id_katimja = $_SESSION['user_id']; // ID Katimja dari session

                                                $query = "
                                                    SELECT COUNT(*) AS total_disetujui
                                                    FROM permohonan p
                                                    INNER JOIN signatures sig ON p.id = sig.permohonan_id
                                                    WHERE sig.pejabat_id = ? AND p.status2 = 1
                                                ";

                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $id_katimja);
                                                $stmt->execute();

                                                $result = $stmt->get_result();
                                                $data = $result->fetch_assoc();

                                                $total_disetujui = $data['total_disetujui'] ?? 0;
                                                ?>



                                                <h1 class="font-light text-white">
                                                    <?php echo  $total_disetujui; ?>
                                                </h1>
                                                <h6 class="text-white">Disetujui</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-danger text-center">
                                                <?php
                                                $query_ditolak = "
                                                    SELECT COUNT(*) AS total_ditolak
                                                    FROM permohonan p
                                                    JOIN users u ON p.user_id = u.id
                                                    WHERE p.status2 = 8 AND u.atasan_id = ?
                                                ";

                                                $stmt_ditolak = $conn->prepare($query_ditolak);
                                                $stmt_ditolak->bind_param("i", $id_katimja);
                                                $stmt_ditolak->execute();
                                                $result_ditolak = $stmt_ditolak->get_result();
                                                $row_ditolak = $result_ditolak->fetch_assoc();
                                                $total_ditolak = $row_ditolak['total_ditolak'] ?? 0;
                                                ?>

                                                <h1 class="font-light text-white"><?php echo $total_ditolak ?></h1>
                                                <h6 class="text-white">Ditolak</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">

                                            <div class="p-2 bg-cyan text-center">
                                                <h1 class="font-light text-white">1,738</h1>
                                                <h6 class="text-white">Permohonan Proses</h6>
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
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                                <?php
                                                //var_dump($row);
                                                $status = 1;

                                                $query_approve = "
                                                        SELECT 
                                                            p.id, 
                                                            p.nomor_permohonan, 
                                                            p.tanggal_permohonan, 
                                                            p.grand_total_harga,
                                                            u.name AS nama_pemohon,
                                                            s.nama_status,
                                                            s.deskripsi_status
                                                        FROM permohonan p
                                                        JOIN users u ON p.user_id = u.id
                                                        LEFT JOIN status_permohonan s ON p.status2 = s.id_status
                                                        WHERE p.status2 = ? AND u.atasan_id = ?
                                                        ORDER BY p.tanggal_permohonan DESC
                                                    ";

                                                $stmt = $conn->prepare($query_approve);
                                                $stmt->bind_param("ii", $status, $id_katimja);
                                                $stmt->execute();
                                                $requests = $stmt->get_result();
                                                $no = 1;
                                                while ($row = $requests->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= date('d F Y', strtotime($row['tanggal_permohonan'])); ?></td>
                                                <td><?= htmlspecialchars($row['nomor_permohonan']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_pemohon']); ?></td>
                                                <td class="card-subtitle text-wrap ">
                                                    <?php if (isset($row['nama_status'])): ?>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-container="body" title="Status Permohonan"
                                                            data-toggle="popover" data-placement="top"
                                                            data-content="<?= htmlspecialchars($row['deskripsi_status'] ?? '-'); ?>">
                                                            <?= htmlspecialchars($row['nama_status'] ?? 'Status Tidak Diketahui'); ?>
                                                        </button>
                                                    <?php else: ?>
                                                        <span>Status tidak tersedia</span>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- <td>"<?= htmlspecialchars($row['status2']); ?>"</td> -->
                                                <td>
                                                    <a href="<?= $base_url ?>/index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                        class="btn btn-info btn-sm">📑Detail</a>

                                                </td>
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