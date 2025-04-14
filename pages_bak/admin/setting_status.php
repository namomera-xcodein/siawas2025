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
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard default</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Aug 19</option>
                        <option value="1">July 19</option>
                        <option value="2">Jun 19</option>
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
                        <!-- <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">236</h2>
                                <span
                                    class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+18.33%</span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">New Clients</h6>
                        </div> -->
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
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

        <!-- *************************************************************** -->

        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Data Status Permohonan</h4>
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
                        <div class="table-responsive table-striped">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0">
                                        <th class="text-dark mb-0 font-16 font-weight-medium text-center">No.</th>
                                        <th class="text-dark mb-0 font-16 font-weight-medium text-center">Nama Status
                                        </th>
                                        <th class="text-dark mb-0 font-16 font-weight-medium text-center">Deskripsi</th>
                                        <th class="text-dark mb-0 font-16 font-weight-medium text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                                                $no = 1;
                                                                $query = mysqli_query($conn, "SELECT * FROM status_permohonan");
                                                                while ($data = mysqli_fetch_array($query)) {
                                                                ?>
                                    <tr>
                                        <td class="border-top-0 px-2 py-4"><?php echo $no++; ?></td>
                                        <td class="border-top-0 px-2 py-4"><?php echo $data['nama_status']; ?></td>
                                        <td class="border-top-0 px-2 py-4 text-wrap">
                                            <?php echo $data['deskripsi_status']; ?></td>
                                        <td class="border-top-0 px-2 py-4 text-center">
                                            <a href="index.php?page=edit_status&id_status=<?php echo $data['id_status']; ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="proses/proses_level.php?action=delete&id=<?php echo $data['id_status']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
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