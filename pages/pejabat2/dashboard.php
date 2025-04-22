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
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Halo,<?php echo $_SESSION['name']; ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <!--  -->
                            <br><?php echo "Deskripsi Level:  "  . ($_SESSION['deskripsi_jabatan']); ?><br>
                        </ol>
                    </nav>



                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard Ketua Tim Kerja</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected><?php echo date('D, F Y') ?></option>

                    </select>
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
                                    include 'config/config.php';
                                    $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
                                    $data = mysqli_fetch_assoc($query);
                                    echo $data['total'];
                                    ?>
                                </h2>
                                <span
                                    class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">10%</span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total User</h6>
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
                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup>Rp. <?php
                                                                                                        include 'config/config.php';
                                                                                                        $query = mysqli_query($conn, "SELECT COALESCE(SUM(grand_total_harga), 0) as total FROM permohonan");
                                                                                                        $data = mysqli_fetch_assoc($query);
                                                                                                        echo number_format($data['total'], 0, ',', '.');
                                                                                                        ?></sup></h2>
                            <h6 class="text-muted text-wrap font-weight-normal mb-0 w-100 text-truncate">Total
                                Permohonan
                                Diajukan
                            </h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="credit-card"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">Rp. <?php
                                                                                    include 'config/config.php';
                                                                                    $query = mysqli_query($conn, "SELECT COALESCE(SUM(grand_total_harga), 0) as total FROM permohonan WHERE status2 = 8");
                                                                                    $data = mysqli_fetch_assoc($query);
                                                                                    echo number_format($data['total'], 0, ',', '.');
                                                                                    ?></h2>
                                <span
                                    class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block"></span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Realisasi</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="credit-card"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 font-weight-medium"><?php
                                                                            include 'config/config.php';
                                                                            $query = mysqli_query($conn, "SELECT COUNT(*) as total FROM permohonan");
                                                                            $data = mysqli_fetch_assoc($query);
                                                                            echo $data['total'];
                                                                            ?></h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Permohonan</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End First Cards -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Sales Charts Section -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Total Penggunaan Anggaran</h4>
                        <div id="campaign-v2" class="mt-2" style="height: 283px; width: 100%"></div>
                        <ul class="list-style-none mb-0">
                            <li>
                                <i class="fas fa-circle text-primary font-10 mr-2"></i>
                                <span class="text-muted">Pengajuan</span>
                                <span class="text-dark float-right font-weight-medium">Rp. <?php
                                                                                            include 'config/config.php';
                                                                                            $query = mysqli_query($conn, "SELECT COALESCE(SUM(grand_total_harga), 0) as total FROM permohonan");
                                                                                            $data = mysqli_fetch_assoc($query);
                                                                                            echo number_format($data['total'], 0, ',', '.');
                                                                                            ?></span>
                            </li>
                            <li class="mt-3">
                                <i class="fas fa-circle text-danger font-10 mr-2"></i>
                                <span class="text-muted">Realisasi</span>
                                <span class="text-dark float-right font-weight-medium">Rp. <?php
                                                                                            include 'config/config.php';
                                                                                            $query = mysqli_query($conn, "SELECT COALESCE(SUM(grand_total_harga), 0) as total FROM permohonan WHERE status2 = 8");
                                                                                            $data = mysqli_fetch_assoc($query);
                                                                                            echo number_format($data['total'], 0, ',', '.');
                                                                                            ?></span>
                            </li>
                            <li class="mt-3">
                                <i class="fas fa-circle text-cyan font-10 mr-2"></i>
                                <span class="text-muted">Proses</span>
                                <span class="text-dark float-right font-weight-medium">Rp. <?php
                                                                                            include 'config/config.php';
                                                                                            $query = mysqli_query($conn, "SELECT COALESCE(SUM(grand_total_harga), 0) as total FROM permohonan WHERE status2 != 8");
                                                                                            $data = mysqli_fetch_assoc($query);
                                                                                            echo number_format($data['total'], 0, ',', '.');
                                                                                            ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Pengeluaran Bulanan</h4>
                        <div id="monthly-chart" style="height: 294px"></div>
                        <?php
                        include 'config/config.php';
                        $query = mysqli_query($conn, "SELECT MONTH(tanggal_permohonan) as bulan, COALESCE(SUM(grand_total_harga), 0) as total 
                                                    FROM (
                                                        SELECT 1 as bulan UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
                                                        UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 
                                                        UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
                                                    ) months 
                                                    LEFT JOIN permohonan ON MONTH(tanggal_permohonan) = months.bulan 
                                                    AND status2 = 8
                                                    GROUP BY months.bulan 
                                                    ORDER BY months.bulan");
                        $data_bulan = array();
                        $data_total = array();
                        while ($row = mysqli_fetch_assoc($query)) {
                            $nama_bulan = date('F', mktime(0, 0, 0, $row['bulan'], 1));
                            $data_bulan[] = $nama_bulan;
                            $data_total[] = (float)$row['total'];
                        }
                        ?>
                        <script>
                        $(function() {
                            var options = {
                                series: [{
                                    name: 'Total Pengeluaran',
                                    data: <?php echo json_encode($data_total); ?>
                                }],
                                chart: {
                                    type: 'bar',
                                    height: 294
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '55%',
                                        endingShape: 'rounded'
                                    },
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                xaxis: {
                                    categories: <?php echo json_encode($data_bulan); ?>
                                },
                                yaxis: {
                                    labels: {
                                        formatter: function(value) {
                                            return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(value) {
                                            return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                }
                            };
                            var chart = new ApexCharts(document.querySelector("#monthly-chart"), options);
                            chart.render();
                        });
                        </script>
                        <ul class="list-inline text-center mt-5 mb-2">
                            <li class="list-inline-item text-muted font-italic">Total Pengeluaran per Bulan</li>
                        </ul>
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
                            <h4 class="card-title mb-0">Earning Statistics</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                        <a class="dropdown-item" href="#">Insert</a>
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pl-4 mb-5">
                            <div class="stats ct-charts position-relative" style="height: 315px"></div>
                        </div>
                        <ul class="list-inline text-center mt-4 mb-0">
                            <li class="list-inline-item text-muted font-italic">Earnings for this month</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recent Activity</h4>
                        <div class="mt-4 activity">
                            <div class="d-flex align-items-start border-left-line pb-3">
                                <div>
                                    <a href="javascript:void(0)" class="btn btn-info btn-circle mb-2 btn-item">
                                        <i data-feather="bell"></i>
                                    </a>
                                </div>
                                <div class="ml-3 mt-2">
                                    <h5 class="text-dark font-weight-medium mb-2">New Product Sold!</h5>
                                    <p class="font-14 mb-2 text-muted">
                                        John Musa just purchased <br />
                                        Cannon 5M Camera.
                                    </p>
                                    <span class="font-weight-light font-14 text-muted">10 Minutes Ago</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start border-left-line pb-3">
                                <div>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-circle mb-2 btn-item">
                                        <i data-feather="bell"></i>
                                    </a>
                                </div>
                                <div class="ml-3 mt-2">
                                    <h5 class="text-dark font-weight-medium mb-2">New Support Ticket</h5>
                                    <p class="font-14 mb-2 text-muted">
                                        Richardson just create support <br />
                                        ticket
                                    </p>
                                    <span class="font-weight-light font-14 text-muted">25 Minutes Ago</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start border-left-line">
                                <div>
                                    <a href="javascript:void(0)" class="btn btn-cyan btn-circle mb-2 btn-item">
                                        <i data-feather="bell"></i>
                                    </a>
                                </div>
                                <div class="ml-3 mt-2">
                                    <h5 class="text-dark font-weight-medium mb-2">Notification Pending Order!</h5>
                                    <p class="font-14 mb-2 text-muted">
                                        One Pending order from Ryne <br />
                                        Doe
                                    </p>
                                    <span class="font-weight-light font-14 mb-1 d-block text-muted">2 Hours Ago</span>
                                    <a href="javascript:void(0)" class="font-14 border-bottom pb-1 border-info">Load
                                        More</a>
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
                            <h4 class="card-title">Top Leaders</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                        <a class="dropdown-item" href="#">Insert</a>
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0">
                                        <th class="border-0 font-14 font-weight-medium text-muted">Team Lead</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted px-2">Project</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Team</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">Status
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">Weeks
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Budget</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-top-0 px-2 py-4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><img src="assets/images/users/widget-table-pic1.jpg"
                                                        alt="user" class="rounded-circle" width="45" height="45" />
                                                </div>
                                                <div class="">
                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">Hanna Gover
                                                    </h5>
                                                    <span class="text-muted font-14">hgover@gmail.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">Elite Admin</td>
                                        <td class="border-top-0 px-2 py-4">
                                            <div class="popover-icon">
                                                <a class="btn btn-primary rounded-circle btn-circle font-12"
                                                    href="javascript:void(0)">DS</a>
                                                <a class="btn btn-danger rounded-circle btn-circle font-12 popover-item"
                                                    href="javascript:void(0)">SS</a>
                                                <a class="btn btn-cyan rounded-circle btn-circle font-12 popover-item"
                                                    href="javascript:void(0)">RP</a>
                                                <a class="btn btn-success text-white rounded-circle btn-circle font-20"
                                                    href="javascript:void(0)">+</a>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-center px-2 py-4"><i
                                                class="fa fa-circle text-primary font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i></td>
                                        <td class="border-top-0 text-center font-weight-medium text-muted px-2 py-4">35
                                        </td>
                                        <td class="font-weight-medium text-dark border-top-0 px-2 py-4">$96K</td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><img src="assets/images/users/widget-table-pic2.jpg"
                                                        alt="user" class="rounded-circle" width="45" height="45" />
                                                </div>
                                                <div class="">
                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">Daniel
                                                        Kristeen</h5>
                                                    <span class="text-muted font-14">Kristeen@gmail.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted px-2 py-4 font-14">Real Homes WP Theme</td>
                                        <td class="px-2 py-4">
                                            <div class="popover-icon">
                                                <a class="btn btn-primary rounded-circle btn-circle font-12"
                                                    href="javascript:void(0)">DS</a>
                                                <a class="btn btn-danger rounded-circle btn-circle font-12 popover-item"
                                                    href="javascript:void(0)">SS</a>
                                                <a class="btn btn-success text-white rounded-circle btn-circle font-20"
                                                    href="javascript:void(0)">+</a>
                                            </div>
                                        </td>
                                        <td class="text-center px-2 py-4"><i class="fa fa-circle text-success font-12"
                                                data-toggle="tooltip" data-placement="top" title="Done"></i></td>
                                        <td class="text-center text-muted font-weight-medium px-2 py-4">32</td>
                                        <td class="font-weight-medium text-dark px-2 py-4">$85K</td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><img src="assets/images/users/widget-table-pic3.jpg"
                                                        alt="user" class="rounded-circle" width="45" height="45" />
                                                </div>
                                                <div class="">
                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">Julian Josephs
                                                    </h5>
                                                    <span class="text-muted font-14">Josephs@gmail.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted px-2 py-4 font-14">MedicalPro WP Theme</td>
                                        <td class="px-2 py-4">
                                            <div class="popover-icon">
                                                <a class="btn btn-primary rounded-circle btn-circle font-12"
                                                    href="javascript:void(0)">DS</a>
                                                <a class="btn btn-danger rounded-circle btn-circle font-12 popover-item"
                                                    href="javascript:void(0)">SS</a>
                                                <a class="btn btn-cyan rounded-circle btn-circle font-12 popover-item"
                                                    href="javascript:void(0)">RP</a>
                                                <a class="btn btn-success text-white rounded-circle btn-circle font-20"
                                                    href="javascript:void(0)">+</a>
                                            </div>
                                        </td>
                                        <td class="text-center px-2 py-4"><i class="fa fa-circle text-primary font-12"
                                                data-toggle="tooltip" data-placement="top" title="Done"></i></td>
                                        <td class="text-center text-muted font-weight-medium px-2 py-4">29</td>
                                        <td class="font-weight-medium text-dark px-2 py-4">$81K</td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><img src="assets/images/users/widget-table-pic4.jpg"
                                                        alt="user" class="rounded-circle" width="45" height="45" />
                                                </div>
                                                <div class="">
                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">Jan Petrovic
                                                    </h5>
                                                    <span class="text-muted font-14">hgover@gmail.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted px-2 py-4 font-14">Hosting Press HTML</td>
                                        <td class="px-2 py-4">
                                            <div class="popover-icon">
                                                <a class="btn btn-primary rounded-circle btn-circle font-12"
                                                    href="javascript:void(0)">DS</a>
                                                <a class="btn btn-success text-white font-20 rounded-circle btn-circle"
                                                    href="javascript:void(0)">+</a>
                                            </div>
                                        </td>
                                        <td class="text-center px-2 py-4"><i class="fa fa-circle text-danger font-12"
                                                data-toggle="tooltip" data-placement="top" title="In Progress"></i></td>
                                        <td class="text-center text-muted font-weight-medium px-2 py-4">23</td>
                                        <td class="font-weight-medium text-dark px-2 py-4">$80K</td>
                                    </tr>
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
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <!-- themejs -->
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!--This page plugins -->
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
    </body>
    <script>
    $(function() {
        $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            html: true
        });
    });
    </script>


    </html>