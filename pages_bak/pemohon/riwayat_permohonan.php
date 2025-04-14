<!-- Add sorting functionality to the table -->
<script src="../../assets/libs/datatables/datatables.min.js"></script>
<link href="../../assets/libs/datatables/datatables.min.css" rel="stylesheet">

<?php
//include '../config/config.php';
//include '$baseconfig/config.php'; 
//include '../../config/functions.php';

// Mendapatkan daftar riwayat permohonan
$requests = getAllRequests($conn);
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']); // Hapus notifikasi setelah ditampilkan
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <div class="page-wrapper">
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
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-primary text-center">
                                                <h1 class="font-light text-white">2,064</h1>
                                                <h6 class="text-white">Permohonan Diajukan</h6>
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
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-success text-center">
                                                <h1 class="font-light text-white">1100</h1>
                                                <h6 class="text-white">Disetujui</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 col-xlg-3">
                                        <div class="card card-hover">
                                            <div class="p-2 bg-danger text-center">
                                                <h1 class="font-light text-white">964</h1>
                                                <h6 class="text-white">Ditolak</h6>
                                            </div>
                                        </div>
                                    </div>
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
                                            <?php $no = 1; while ($row = $requests->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= date('d F Y H:i:s', strtotime($row['tanggal_permohonan'])); ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['nomor_permohonan']); ?></td>
                                                <td><?= htmlspecialchars($row['nama_pemohon']); ?></td>
                                                <td>"<?= htmlspecialchars($row['status']); ?>"</td>
                                                <td>
                                                    <a href="<?=$base_url?>/index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                        class="btn btn-info btn-sm">📑Detail</a>
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
        </div>
    </div>

    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../dist/js/app-style-switcher.js"></script>
    <script src="../../dist/js/feather.min.js"></script>
    <script src="../../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../../dist/js/sidebarmenu.js"></script>
    <script src="../../dist/js/custom.min.js"></script>

    <!-- Initialize DataTables with sorting -->
    <script>
    $(document).ready(function() {
        $('#zero_config').DataTable({
            "order": [
                [1, "desc"]
            ], // Sort by date column (index 1) in descending order
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
    </script>