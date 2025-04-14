<!-- Add sorting functionality to the table -->
<script src="../../assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<link href="../../assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">

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
// Fungsi untuk mengambil semua data permohonan
function getAllRequestsAdmin($conn) {
    $query = "SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, p.status, u.name AS nama_pemohon
              FROM permohonan p
              JOIN users u ON p.user_id = u.id
              ORDER BY p.created_at DESC";
              
    $result = $conn->query($query);
    return $result;
}

$requests = getAllRequestsAdmin($conn);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Permohonan</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
</head>

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
                                <h4 class="card-title">Data Permohonan</h4>
                                <h6 class="card-subtitle">Berikut adalah data permohonan yang telah diajukan:</h6>
                                <div class="table-responsive">
                                    <div id="zero_config_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <table id="permohonanTable" class="table table-striped table-bordered">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="dataTables_length" id="zero_config_length">
                                                        <label>Show
                                                            <select name="zero_config_length"
                                                                aria-controls="zero_config"
                                                                class="form-select form-select-sm">
                                                                <option value="10">10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select> entries
                                                        </label>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6">
                                                        <div id="zero_config_filter" class="dataTables">
                                                            <label>Search:
                                                                <input type="search"
                                                                    class="form-control form-control-sm" placeholder=""
                                                                    aria-controls="zero_config">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <table id="zero_config"
                                                            class="table table-striped table-bordered no-wrap dataTable"
                                                            role="grid" aria-describedby="zero_config_info">
                                                            <thead>
                                                            <tbody>
                                                        </table>
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-6">
                                                                <div class="dataTables"></div>
                                                            </div>

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
                                                                    <td><?= htmlspecialchars($row['nomor_permohonan']); ?>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($row['nama_pemohon']); ?>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($row['status']); ?></td>
                                                                    <td>
                                                                        <a href="detail_permohonan.php?id=<?= $row['id']; ?>"
                                                                            class="btn btn-info btn-sm">
                                                                            📑 Detail
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

        <!-- jQuery & DataTables CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

        <!-- Inisialisasi DataTables -->
        <script>
        $(document).ready(function() {
            $('#permohonanTable').DataTable({
                "order": [
                    [1, "desc"]
                ], // Urutkan berdasarkan tanggal terbaru
                "paging": true, // Aktifkan pagination
                "searching": true, // Aktifkan pencarian
                "lengthChange": true, // Aktifkan opsi jumlah data per halaman
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" // Bahasa Indonesia
                }
            });
        });
        </script>