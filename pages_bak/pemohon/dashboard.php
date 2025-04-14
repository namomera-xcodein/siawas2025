<?php
// session_start();
// // Error reporting
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // Check if session exists
// if(!isset($_SESSION)) {
//     session_start();
// }

// // Check if user is logged in
// if(!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
//     header("Location: ../login.php");
//     exit();
// }

// // Database connection check
// if(!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// Mendapatkan daftar riwayat permohonan
 $requests = getAllRequests($conn);

 
?>
<!-- Preloader -->
<!-- <div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div> -->
<div class="row"></div>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">xx</div>
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    <?php 
                    $time = date('H');
                    if ($time >= 5 && $time <= 11) {
                        echo "Selamat Pagi";
                    } elseif ($time >= 12 && $time <= 15) {
                        echo "Selamat Siang";
                    } elseif ($time >= 16 && $time <= 18) {
                        echo "Selamat Sore";
                    } else {
                        echo "Selamat Malam";
                    }
                    echo " " . $_SESSION['name'] . "! Anda Login sebagai " . $_SESSION['role'];
                    ?>

                    <br>
                    <?php 
                    // setlocale(LC_TIME, 'id_ID');
                    // echo strftime('%A, %d %B %Y %H:%M:%S', time()); 
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard Permohonan</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <!-- <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Pengembangan</option>
                        <option value="1">Opsi A</option>
                        <option value="2">Opsi B</option>
                    </select> -->
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- *************************************************************** -->
        <!-- Start First Cards -->
        <!-- *************************************************************** -->
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">
                                    <?php 
                                    $sql = "SELECT COUNT(*) as total FROM permohonan WHERE status='Diajukan'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row['total'];
                                    ?>
                                </h2>
                                <span
                                    class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">jml</span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Permohonan Diajukan</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                                <?php echo getTotalPermohonanPending($conn); ?></h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Permohonan Proses</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">
                                    <?php echo getTotalPermohonanDitolak($conn); ?></h2>
                                <span
                                    class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">Rejected</span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Permohonan Ditolak</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 font-weight-medium">
                                <?php echo getTotalHargaPermohonanSelesai($conn); ?></h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Harga Permohonan
                            </h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End First Cards -->
        <!-- *************************************************************** -->

        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Riwayat Permohonan</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0">
                                        <th class="border-0 font-14 font-weight-medium text-muted">No</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted px-2">Tanggal
                                            Permohonan</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Nomor permohonan</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">Pemohon
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">Status
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1; 
                                    while ($row = $requests->fetch_assoc()) { 
                                    ?>
                                    <tr>
                                        <td class="border-top-0 px-2 py-4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><?php echo $no++; ?></div>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">
                                            <?= date('d F Y', strtotime($row['tanggal_permohonan'])); ?>
                                        </td>
                                        <td class="font-weight-medium text-dark border-top-0 px-2 py-4">
                                            <div class="popover-icon">
                                                <?= htmlspecialchars($row['nomor_permohonan']); ?>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-center px-2 py-4">
                                            <?= htmlspecialchars($row['nama_pemohon']); ?>

                                            <!-- <i class="fa fa-circle text-primary font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i> -->
                                        </td>
                                        <td class="text-center font-weight-medium text-dark px-2 py-4">
                                            <?= htmlspecialchars($row['status']); ?>
                                        </td>
                                        <td class="font-weight-medium text-dark border-top-0 px-2 py-4">
                                            <a href="<?=$base_url?>/index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                class="btn btn-info btn-sm">📑Detail</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End Top Leader Table -->
        <!-- *************************************************************** -->
    </div>


</div>