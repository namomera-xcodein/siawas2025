<?php
//include '../config/config.php';
//include '$baseconfig/config.php'; 
//include $_SERVER['DOCUMENT_ROOT'] . '/config/functions.php';

function hitungPermohonanBerjalan($conn, $user_id)
{
    $sql = "SELECT COUNT(*) AS total_permintaan FROM permohonan WHERE user_id = ? AND status2 IN (0,1,2,3,5,6)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_permintaan'] ?? 0;
}


function getCountRequestsByUser($conn, $user_id)
{
    $sql = "SELECT COUNT(*) as total FROM permohonan WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}
function hitungPermohonanSelesai($conn, $user_id)
{
    $sql = "SELECT COUNT(*) AS total FROM permohonan WHERE user_id = ? AND status2 = 7";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Query error: " . $conn->error);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

function hitungPermohonanDitolak($conn, $user_id)
{
    $sql = "SELECT COUNT(*) AS total FROM permohonan WHERE user_id = ? AND status2 = 4";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Query error: " . $conn->error);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}
// Mendapatkan daftar riwayat permohonan
// $requests = getAllRequests($conn);
// if (isset($_SESSION['success'])) {
//     echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
//     unset($_SESSION['success']); // Hapus notifikasi setelah ditampilkan
// }

// if (isset($_SESSION['error'])) {
//     echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
//     unset($_SESSION['error']);
// }


function getAllRequests2($conn)
{
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session

    // Query untuk mengambil data permohonan
    $query = "SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, 
                     u.name AS nama_pemohon, s.nama_status 
              FROM permohonan p
              JOIN users u ON p.user_id = u.id
              JOIN status_permohonan s ON p.status2 = s.id_status
              WHERE p.user_id = ?
              ORDER BY p.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
<!-- jQuery (wajib duluan) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

<!-- Inisialisasi -->
<script>
$(document).ready(function() {
    $('#dataTablePermohonan').DataTable({
        responsive: true,
        columnDefs: [{
            orderable: false,
            targets: -1
        }],
        language: {
            search: "🔍 Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: {
                previous: "←",
                next: "→"
            }
        }
    });
});
</script>
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
                                            <h1 class="font-light text-white">

                                                <?php echo getCountRequestsByUser($conn, $_SESSION['user_id']); ?>
                                            </h1>
                                            <h6 class="text-white">Permohonan Diajukan</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-xlg-3">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-cyan text-center">
                                            <h1 class="font-light text-white">
                                                <?php echo hitungPermohonanBerjalan($conn, $_SESSION['user_id']); ?>
                                            </h1>
                                            <h6 class="text-white">Permohonan Proses</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-xlg-3">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-success text-center">
                                            <h1 class="font-light text-white">
                                                <?php echo hitungPermohonanSelesai($conn, $_SESSION['user_id']); ?>
                                            </h1>
                                            <h6 class="text-white">Disetujui</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 col-xlg-3">
                                    <div class="card card-hover">
                                        <div class="p-2 bg-danger text-center">
                                            <h1 class="font-light text-white">
                                                <?php echo hitungPermohonanDitolak($conn, $_SESSION['user_id']); ?>
                                            </h1>
                                            <h6 class="text-white">Ditolak</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <!-- <table id="dataTablePermohonan" class="table table-striped table-bordered"> -->
                                <table id="dataTablePermohonan"
                                    class="table table-striped table-bordered display responsive nowrap"
                                    style="width:100%">

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
                                        $requests = getAllRequests2($conn); // Mengambil data permohonan berdasarkan user yang login
                                        $no = 1;
                                        if ($requests->num_rows > 0) {
                                            while ($row = $requests->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= date('d F Y H:i:s', strtotime($row['tanggal_permohonan'])); ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['nomor_permohonan']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_pemohon']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_status']); ?></td>
                                            <td>
                                                <a href="<?= $base_url ?>/index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                    class="btn btn-info btn-sm">📑Detail</a>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>Tidak ada data permohonan</td></tr>";
                                        }
                                        ?>
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
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="<?php echo $base_url ?>/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?php echo $base_url ?>/assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="<?php echo $base_url ?>/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- apps -->
<!-- apps -->
<script src="<?php echo $base_url ?>/dist/js/app-style-switcher.js"></script>
<script src="<?php echo $base_url ?>/dist/js/feather.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo $base_url ?>/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="<?php echo $base_url ?>/assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<!-- themejs -->
<!--Menu sidebar -->
<script src="<?php echo $base_url ?>/dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="<?php echo $base_url ?>/dist/js/custom.min.js"></script>
<!--This page plugins -->
<script src="<?php echo $base_url ?>/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>/dist/js/pages/datatable/datatable-basic.init.js"></script>

<script>
$(document).ready(function() {
    $('#dataTablePermohonan').DataTable({
        responsive: true,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "Semua"]
        ],
        language: {
            search: "🔍 Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "→",
                previous: "←"
            }
        },
        columnDefs: [{
            orderable: false,
            targets: -1
        }]
    });
});
</script>


<!-- <script>
    $(document).ready(function() {
        $('#dataTablePermohonan').DataTable({
            responsive: true, // Membuat tabel lebih responsif
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ], // Tambah opsi jumlah data yang ditampilkan
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                }
            }
        });
    });
    </script> -->