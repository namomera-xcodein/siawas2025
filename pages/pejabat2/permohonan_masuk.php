<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$id_katimja = $_SESSION['user_id']; // Katimja yang sedang login

$query = "
    SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, p.grand_total_harga,
           u.name AS nama_pemohon, s.nama_status, s.deskripsi_status
    FROM permohonan p
    JOIN users u ON p.user_id = u.id
    JOIN status_permohonan s ON p.status2 = s.id_status
    WHERE p.status2 = 0 AND u.atasan_id = ?
    ORDER BY p.tanggal_permohonan DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_katimja);
$stmt->execute();
$requests = $stmt->get_result();

?>


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <?php
    $page = $_GET['page'] ?? 'dashboard';
    $breadcrumbs = explode('/', $page);

    function formatBreadcrumb($text)
    {
        // Ubah menjadi kapital di awal kata dan ganti tanda "-" dengan spasi
        return ucwords(str_replace(['-', '_'], ' ', $text));
    }
    ?>

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    <!--  -->
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <?php
                            $url = '';
                            foreach ($breadcrumbs as $i => $crumb) {
                                $url .= ($i === 0 ? '' : '/') . $crumb;
                                $is_last = $i === array_key_last($breadcrumbs);
                                $label = formatBreadcrumb($crumb);

                                if ($is_last) {
                                    echo "<li class='breadcrumb-item active' aria-current='page'>$label</li>";
                                } else {
                                    echo "<li class='breadcrumb-item'><a href='index.php?page=$url'>$label</a></li>";
                                }
                            }
                            ?>
                            </li>
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
        <!-- <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <a href="#">Permohonan Menunggu
                            Approval</a>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
                        </div>
                    </div>
                </div>
            </div>


        </div> -->

        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Permohonan Perlu Persetujuan</h4>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Permohonan Masuk</h4>
                                        <h6 class="card-subtitle">
                                            Daftar permohonan yang diajukan oleh pemohon dan menunggu persetujuan Anda
                                        </h6>
                                        <div class="table-responsive">
                                            <?php if ($requests->num_rows == 0): ?>
                                                <div class="alert alert-info">
                                                    Belum ada permohonan baru
                                                </div>
                                            <?php else: ?>
                                                <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal Permohonan</th>
                                                            <th>Nomor Permohonan</th>
                                                            <th>Nama Pemohon</th>
                                                            <th>Total Harga</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1;
                                                        while ($row = $requests->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><?= $no++; ?></td>
                                                                <td>
                                                                    <?= date('d F Y', strtotime($row['tanggal_permohonan'])); ?><br>
                                                                    <span
                                                                        class="text-muted"><?= date('H:i:s', strtotime($row['tanggal_permohonan'])); ?></span>
                                                                </td>
                                                                <td><?= htmlspecialchars($row['nomor_permohonan']); ?></td>
                                                                <td><?= htmlspecialchars($row['nama_pemohon']); ?></td>
                                                                <td>Rp
                                                                    <?= number_format($row['grand_total_harga'], 0, ',', '.'); ?>
                                                                </td>

                                                                <td class="card-subtitle text-wrap ">
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        data-container="body" title="Status Permohonan"
                                                                        data-toggle="popover" data-placement="top"
                                                                        data-content="<?= htmlspecialchars($row['deskripsi_status']); ?>">
                                                                        <?= htmlspecialchars($row['nama_status']); ?>
                                                                    </button>
                                                                </td>

                                                                <td>
                                                                    <a href="index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                                        class="btn btn-info btn-sm">
                                                                        📑 Detail
                                                                    </a>
                                                                    <a href="pages/pejabat2/proses_ttd.php?id_permohonan=<?= $row['id']; ?>"
                                                                        class="btn btn-success btn-sm"
                                                                        onclick="return confirm('Proses tanda tangan sekarang?')">
                                                                        ✍️ Tanda Tangan
                                                                    </a>
                                                                </td>


                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal Permohonan</th>
                                                            <th>Nomor Permohonan</th>
                                                            <th>Nama Pemohon</th>
                                                            <th>Total Harga</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </tfoot>

                                                </table>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
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
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#zero_config').DataTable({
                "searching": true,
                "paging": true,
                "pageLength": 10,
                "language": {
                    "search": "Pencarian:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>