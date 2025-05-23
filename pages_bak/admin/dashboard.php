<?php

// if (isset($_SESSION['success'])) {
//     echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
//     unset($_SESSION['success']); // Hapus notifikasi setelah ditampilkan
// }

// if (isset($_SESSION['error'])) {
//     echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
//     unset($_SESSION['error']);
// }


?>


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row"></div>
        <br><br>

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
                    echo " " . $_SESSION['name'] ."!";
                    //. "! Anda Login sebagai " . $_SESSION['role'];
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.php?page=dashboard"><?php 
                    $time = date('H');
                    if ($time >= 5 && $time <= 11) {
                        echo "Hari Baru Mulai, Bismillah!";
                    } elseif ($time >= 12 && $time <= 15) {
                        echo "Jangan Lupa Makan Siang!";
                    } elseif ($time >= 16 && $time <= 18) {
                        echo "Udah Sore, Belum Pulang";
                    } else {
                        echo "Cie..Lembur ni...";
                    }
                    
                    //. "! Anda Login sebagai " . $_SESSION['role'];
                    ?></a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <!-- <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Aug 19</option>
                        <option value="1">July 19</option>
                        <option value="2">Jun 19</option>
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
                                <h2 class="text-dark mb-1 font-weight-medium"><?php
                                                                $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
                                                                $data = mysqli_fetch_assoc($query);
                                                                echo $data['total'];
                                                            ?></h2>
                                <span
                                    class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none"><?php
                                                                        $currentMonth = date('m');
                                                                        $currentYear = date('Y');
                                                                        $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE MONTH(created_at) = '$currentMonth' AND YEAR(created_at) = '$currentYear'");
                                                                        $data = mysqli_fetch_assoc($query);
                                                                        echo "+" . number_format($data['total']);
                                                                    ?></span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">jumlah User's</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                    class="set-doller">Rp.</sup><?php
                                    $query = mysqli_query($conn, "SELECT SUM(grand_total_harga) as total FROM permohonan");
                                    $data = mysqli_fetch_assoc($query);
                                    echo number_format($data['total']);
                                    ?></h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Nilai Belanja</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium"><?php
                                                                $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM permohonan");
                                                                $data = mysqli_fetch_assoc($query);
                                                                echo $data['total'];
                                                            ?></h2>
                                <!-- <span
                                    class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">-18.33%</span> -->
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Permohonan</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="card">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 font-weight-medium">864</h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Projects</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="globe"></i></span>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- *************************************************************** -->
        <!-- End First Cards -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Sales Charts Section -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Status Permohonan</h4>
                        <div id="campaign-v2" class="mt-2" style="height: 283px; width: 100%"></div>
                        <ul class="list-style-none mb-0">
                            <li>
                                <i class="fas fa-circle text-primary font-10 mr-2"></i>
                                <span class="text-muted">Diajukan</span>
                                <span class="text-dark float-right font-weight-medium"><?php
                                                                $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM permohonan WHERE status='Menunggu Persetujuan SPM'");
                                                                $data = mysqli_fetch_assoc($query);
                                                                echo $data['total'];
                                                            ?></span>
                            </li>
                            <li class="mt-3">
                                <i class="fas fa-circle text-danger font-10 mr-2"></i>
                                <span class="text-muted">Diproses</span>
                                <span class="text-dark float-right font-weight-medium">25</span>
                            </li>
                            <li class="mt-3">
                                <i class="fas fa-circle text-cyan font-10 mr-2"></i>
                                <span class="text-muted">Ditolak</span>
                                <span class="text-dark float-right font-weight-medium">23</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Pengeluaran</h4>
                        <div class="net-income mt-4 position-relative" style="height: 294px"></div>
                        <ul class="list-inline text-center mt-5 mb-2">
                            <li class="list-inline-item text-muted font-italic">Pembelanjaan Bulan ini</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Earning by Location</h4>
                        <div class="" style="height: 180px">
                            <div id="visitbylocate" style="height: 100%"></div>
                        </div>
                        <div class="row mb-3 align-items-center mt-1 mt-5">
                            <div class="col-4 text-right">
                                <span class="text-muted font-14">PNBP</span>
                            </div>
                            <div class="col-5">
                                <div class="progress" style="height: 5px">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-right">
                                <span class="mb-0 font-14 text-dark font-weight-medium">28%</span>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-4 text-right">
                                <span class="text-muted font-14">Program</span>
                            </div>
                            <div class="col-5">
                                <div class="progress" style="height: 5px">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 74%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-right">
                                <span class="mb-0 font-14 text-dark font-weight-medium">21%</span>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-4 text-right">
                                <span class="text-muted font-14">MBG</span>
                            </div>
                            <div class="col-5">
                                <div class="progress" style="height: 5px">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: 60%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-right">
                                <span class="mb-0 font-14 text-dark font-weight-medium">18%</span>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-right">
                                <span class="text-muted font-14">BST</span>
                            </div>
                            <div class="col-5">
                                <div class="progress" style="height: 5px">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 50%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-right">
                                <span class="mb-0 font-14 text-dark font-weight-medium">12%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End Sales Charts Section -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Location and Earnings Charts Section -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <h4 class="card-title mb-0">Outcome Statistics</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                        <a class="dropdown-item" href="#">Insert</a>
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="pl-4 mb-5">
                            <div class="stats ct-charts position-relative" style="height: 315px"></div>
                        </div>
                        <ul class="list-inline text-center mt-4 mb-0">
                            <li class="list-inline-item text-muted font-italic">Permohonan Minggu ini</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recent Activity</h4>
                        <div class="mt-4 activity">

                            <div class="d-flex align-items-start border-left-line">
                                <div>
                                    <a href="javascript:void(0)" class="btn btn-cyan btn-circle mb-2 btn-item">
                                        <i data-feather="bell"></i>
                                    </a>
                                </div>
                                <div class="ml-3 mt-2">
                                    <h5 class="text-dark font-weight-medium mb-2">Notification!</h5>
                                    <p class="font-14 mb-2 text-muted">
                                        One Pending order from Ryne <br />
                                        Doe
                                    </p>
                                    <span class="font-weight-light font-14 mb-1 d-block text-muted">2 Hours Ago</span>
                                    <!-- <a href="javascript:void(0)" class="font-14 border-bottom pb-1 border-info">Load
                                        More</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End Location and Earnings Charts Section -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">5 Permohonan Terakhir</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                        <a class="dropdown-item" href="#">Insert</a>
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
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
                                        <?php 
                                        $query = "SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, p.grand_total_harga, p.status, u.name AS nama_pemohon
                                        FROM permohonan p
                                        JOIN users u ON p.user_id = u.id
                                        ORDER BY p.created_at DESC
                                        LIMIT 5"; 
                                        
                                        $stmt = mysqli_prepare($conn, $query);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        
                                        $no = 1; 
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= date('d F Y H:i:s', strtotime($row['tanggal_permohonan'])); ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['nomor_permohonan']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_pemohon']); ?></td>
                                            <td><?= htmlspecialchars($row['status']); ?></td>
                                            <td>
                                                <a href="index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                    class="btn btn-info btn-sm">
                                                    📑 Detail
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
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
        <!-- *************************************************************** -->
        <!-- End Top Leader Table -->
        <!-- *************************************************************** -->
    </div>