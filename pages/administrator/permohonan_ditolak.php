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
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Daftar Permohonan Ditolak</h4>
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pemohon</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Jenis Permohonan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
            $no = 1;
            $query = mysqli_query($conn, "SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, p.status2, u.name AS nama_pemohon
                FROM permohonan p
                JOIN users u ON p.user_id = u.id
                WHERE p.status2 = '4'
                ORDER BY p.created_at DESC");

            while ($row = mysqli_fetch_array($query)) {
            ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_pemohon']); ?></td>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($row['tanggal_permohonan'])); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['nomor_permohonan']); ?></td>
                                        <!-- Sesuaikan jika ada kolom 'jenis_permohonan' -->
                                        <td>
                                            <span class="badge badge-danger">Ditolak</span>
                                        </td>
                                        <td>
                                            <a href="index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                class="btn btn-info btn-sm">Detail</a>
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
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->