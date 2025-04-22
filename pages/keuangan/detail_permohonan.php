<?php
//session_start();
//include '../../config/config.php';
//var_dump($mysqli);
// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}


// Dapatkan ID permohonan dari URL
$id_permohonan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data permohonan
$permohonan_query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id = $id_permohonan");
$permohonan = mysqli_fetch_assoc($permohonan_query);

if (!$permohonan) {
    echo "<script>alert('Permohonan tidak ditemukan!'); window.location.href='index.php?page=permohonan';</script>";
    exit;
}

// Ambil detail barang
$detail_query = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id = $id_permohonan");

// Update Mata Anggaran jika Pejabat PPK
// if (isset($_POST['update_mata_anggaran']) && $_SESSION['role'] === 'pejabat_ppk') {
//     $mata_anggaran = mysqli_real_escape_string($conn, $_POST['mata_anggaran']);
//     mysqli_query($conn, "UPDATE permohonan SET mata_anggaran='$mata_anggaran' WHERE id=$id_permohonan");
//     echo "<script>alert('Mata Anggaran diperbarui!'); window.location.reload();</script>";
// }

// Query untuk mengambil data pejabat berdasarkan role
$query = "SELECT id, name, nip_nik, role FROM users WHERE role IN ('pejabat_spm', 'pejabat_ppk', 'pejabat_kpa')";
$result = $conn->query($query);

// Inisialisasi variabel kosong
$nip_nik_pejabat_spm = $name_pejabat_spm = null;
$nip_nik_pejabat_ppk = $name_pejabat_ppk = null;
$nip_nik_pejabat_kpa = $name_pejabat_kpa = null;

// Menyimpan data ke variabel berdasarkan role
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['role'] == 'pejabat_spm') {
            $nip_nik_pejabat_spm = $row['nip_nik'];
            $name_pejabat_spm = $row['name'];
        } elseif ($row['role'] == 'pejabat_ppk') {
            $nip_nik_pejabat_ppk = $row['nip_nik'];
            $name_pejabat_ppk = $row['name'];
        } elseif ($row['role'] == 'pejabat_kpa') {
            $nip_nik_pejabat_kpa = $row['nip_nik'];
            $name_pejabat_kpa = $row['name'];
        }
    }
}


// Ambil ID permohonan dari parameter GET
// Pastikan parameter `id` tersedia di URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_permohonan = $_GET['id'];

    // Query untuk mendapatkan detail permohonan + data user yang terkait
    $query = "SELECT p.id AS id_permohonan, p.user_id, u.name AS name_pemohon, u.nip_nik AS nip_nik_pemohon 
              FROM permohonan p
              JOIN users u ON p.user_id = u.id
              WHERE p.id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id_permohonan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Ambil data user dari tabel users
            $name_pemohon = $row['name_pemohon'] ?? "Tidak ada data";
            $nip_nik_pemohon = $row['nip_nik_pemohon'] ?? "Tidak ada data";
        } else {
            $name_pemohon = "Data tidak ditemukan";
            $nip_nik_pemohon = "Data tidak ditemukan";
        }
        $stmt->close();
    } else {
        die("Query gagal: " . $conn->error);
    }
} else {
    die("ID permohonan tidak valid.");
}

?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<body>

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Detail Permohonan
                        </h4><?php echo "Level Anda: " . $_SESSION['level_user']; ?>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><a href="3" class="text-muted">Pages</a></li>
                                    <li class="breadcrumb-item text-muted active" aria-current="page">Proses Permohonan
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

                                </div>
                                <?php
                                // Level saat ini dari permohonan
                                $level = $permohonan['status2']; // asumsi status_id disimpan di kolom ini

                                // Level yang dihitung untuk progress
                                $progress_levels = [0, 1, 2, 3, 6, 7, 8];
                                $total_steps = count($progress_levels); // 7
                                $progress_percent = 0;

                                // Hitung progres hanya jika level termasuk dalam progress_levels
                                if (in_array($level, $progress_levels)) {
                                    $current_index = array_search($level, $progress_levels); // indeks ke-nya dalam array progress
                                    $progress_percent = round((($current_index + 1) / $total_steps) * 100);
                                }

                                // Label status (opsional)
                                $status_labels = [
                                    0 => 'Diajukan',
                                    1 => 'Disetujui oleh KATIMJA',
                                    2 => 'Disetujui Pejabat Kasubbag Umum / PPK',
                                    3 => 'Disetujui Pejabat KPA',
                                    4 => 'Ditolak',
                                    5 => 'Revisi',
                                    6 => 'Menunggu Pencairan',
                                    7 => 'Proses Belanja',
                                    8 => 'Selesai'
                                ];
                                ?>
                                <div class="table-responsive">
                                    <div class="progress mt-4">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                            role="progressbar" aria-valuenow="<?= $progress_percent ?>"
                                            aria-valuemin="0" aria-valuemax="100"
                                            style="width: <?= $progress_percent ?>%;">
                                        </div>
                                    </div>
                                    <p class="mt-2">
                                        Proses Permohonan: <strong><?= $progress_percent ?>%</strong> —
                                        <em>Status: <?= $status_labels[$level] ?? 'Tidak Diketahui' ?> </em>
                                    </p>
                                    <!-- Tombol untuk mengubah status -->
                                    <?php if ($_SESSION['level_user'] == 6): ?>
                                    <div class="mt-3">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="update_status" value="6">
                                            <input type="hidden" name="id_permohonan"
                                                value="<?= htmlspecialchars($permohonan['id']); ?>">
                                            <button type="submit" class="btn btn-warning me-2">Menunggu
                                                Pencairan</button>
                                        </form>

                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="update_status" value="7">
                                            <input type="hidden" name="id_permohonan"
                                                value="<?= htmlspecialchars($permohonan['id']); ?>">
                                            <button type="submit" class="btn btn-info">Proses Belanja</button>
                                        </form>
                                    </div>
                                    <?php endif; ?>

                                    <?php
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
                                        if ($_SESSION['level_user'] == 6) {
                                            $id_permohonan = isset($_POST['id_permohonan']) ? intval($_POST['id_permohonan']) : 0;
                                            $new_status = isset($_POST['update_status']) ? intval($_POST['update_status']) : 0;

                                            // Update status di database
                                            $query = "UPDATE permohonan SET status2 = ? WHERE id = ?";
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("ii", $new_status, $id_permohonan);

                                            if ($stmt->execute()) {
                                                echo "<script>
                                                        alert('Status berhasil diperbarui!');
                                                        window.location.href = window.location.href;
                                                     </script>";
                                            } else {
                                                echo "<script>alert('Gagal memperbarui status!');</script>";
                                            }
                                            $stmt->close();
                                        }
                                    }
                                    ?>



                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <h4 class="mb-0">Detail Permohonan</h4> -->
                                            <div class="card">
                                                <div class="card-body collapse show">





                                                    <!-- <p class="card-text">With supporting text below as a natural lead-in
                                                        to additional content.</p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Pemohon
                                        : <?= htmlspecialchars($name_pemohon); ?>
                                    </h5>
                                    <h5 class="card-title">Detail Permohonan
                                        : <?= htmlspecialchars($permohonan['nomor_permohonan']); ?>
                                    </h5>
                                    <!-- <h5 class="card-title">Mata Anggaran
                                        : <?= htmlspecialchars($permohonan['mata_anggaran']); ?>
                                    </h5> -->
                                    <h5 class="card-title">
                                        Mata Anggaran:
                                        <?php if ($_SESSION['level_user'] == 3): ?>
                                        <form method="POST" class="d-flex align-items-center mt-2">
                                            <input type="hidden" name="update_mata_anggaran" value="1">
                                            <input type="hidden" name="id_permohonan"
                                                value="<?= htmlspecialchars($permohonan['id']); ?>">
                                            <input type="text" name="mata_anggaran"
                                                value="<?= htmlspecialchars($permohonan['mata_anggaran']); ?>"
                                                class="form-control form-control-sm me-2" style="width: 250px;"
                                                placeholder="Input nama / kode Mata Anggaran" required>
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <?= empty($permohonan['mata_anggaran']) ? 'Tambahkan' : 'Edit'; ?>
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <?= htmlspecialchars($permohonan['mata_anggaran']); ?>
                                        <?php endif; ?>
                                    </h5>

                                    <?php
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_mata_anggaran'])) {
                                        if ($_SESSION['level_user'] == 3) {
                                            $id_permohonan = isset($_POST['id_permohonan']) ? intval($_POST['id_permohonan']) : 0;
                                            $mata_anggaran = trim($_POST['mata_anggaran'] ?? '');

                                            if ($id_permohonan > 0 && !empty($mata_anggaran)) {
                                                $mata_anggaran = mysqli_real_escape_string($conn, $mata_anggaran);

                                                $query = "UPDATE permohonan SET mata_anggaran = '$mata_anggaran', updated_at = NOW() WHERE id = $id_permohonan";

                                                if (mysqli_query($conn, $query)) {
                                                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Mata anggaran berhasil diperbarui.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'index.php?page=detail_permohonan&id=$id_permohonan';
                    });
                });
                </script>";
                                                } else {
                                                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal memperbarui mata anggaran.'
                    });
                });
                </script>";
                                                }
                                            } else {
                                                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Input tidak valid!',
                    text: 'Silakan isi data dengan benar.'
                });
            });
            </script>";
                                            }
                                        }
                                    }
                                    ?>



                                    <h5 class="card-title"><strong>Tanggal Permohonan:</strong>
                                        <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?></h4>
                                        <h5 class="card-title"><strong>Total Harga:</strong> Rp.
                                            <?= number_format($permohonan['grand_total_harga'], 0, ',', '.'); ?>
                                        </h5>
                                        <h5 class="card-title">Detail Barang:</h5>
                                        <!-- NEW Table -->
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                while ($barang = mysqli_fetch_assoc($detail_query)): ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= htmlspecialchars($barang['nama_barang']); ?></td>
                                                    <td><?= htmlspecialchars($barang['satuan']); ?></td>
                                                    <td>Rp.
                                                        <?= number_format($barang['harga_satuan'], 0, ',', '.'); ?>
                                                    </td>
                                                    <td><?= $barang['jumlah_barang']; ?></td>
                                                    <td>Rp.
                                                        <?= number_format($barang['subtotal_harga'], 0, ',', '.'); ?>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-end">Jumlah</th>
                                                    <th>Rp.
                                                        <?= number_format($permohonan['grand_total_harga'], 0, ',', '.'); ?>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>



                                        <div class="row"> </div><br>
                                        <!-- ============================================================== -->
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Ambon,
                                                        <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?>
                                                        Pemohon,
                                                    </h5>
                                                    <img src="<?= $base_url ?>/pages/pemohon/<?= htmlspecialchars($permohonan['qr_code_pemohon']); ?>"
                                                        alt="QR Code" width="100">
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pemohon); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pemohon); ?></h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Mengetahui,<br>
                                                        Ketua Tim Kerja,
                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_pejabat1'])): ?>
                                                    <img src="<?= $base_url ?>/<?= htmlspecialchars($permohonan['qr_code_pejabat1']); ?>"
                                                        alt="QR Code Katimja" width="100">
                                                    <?php else: ?>
                                                    <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_spm); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pejabat_spm); ?></h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">Mengetahui,<br>
                                                    <h5>Plt. Kasubbag Umum / PPK,<br>

                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_ppk'])): ?>
                                                    <img src="<?= $base_url ?><?= htmlspecialchars($permohonan['qr_code_ppk']); ?>"
                                                        alt="QR Code PPK" width="100">
                                                    <?php else: ?>
                                                    <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_ppk); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pejabat_ppk); ?></h5>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="text-center">
                                                    <h5>Menyetujui,<br>Kuasa Pengguna Anggaran,<br>

                                                    </h5>
                                                    <?php if (!empty($permohonan['qr_code_kpa'])): ?>
                                                    <img src="<?= $base_url ?>/<?= htmlspecialchars($permohonan['qr_code_kpa']); ?>"
                                                        alt="QR Code KPA" width="100"><br>
                                                    <?php else: ?>
                                                    <div class="card-title">Menunggu Persetujuan</div>
                                                    <?php endif; ?>
                                                    <br>
                                                    <h5><?= htmlspecialchars($name_pejabat_kpa); ?></h5>
                                                    <h5><?= htmlspecialchars($nip_nik_pejabat_kpa); ?></h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">

                                            </div>
                                        </div>



                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="form-actions alert-danger">
                        <div class="text-right">
                            <!-- <button type="submit" class="btn btn-info" id="addItem">Tambah</button> -->
                            <button onclick="window.print()" class="btn btn-danger">Cetak</button>
                            <a href="index.php?page=all_permohonan" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->

        </div>
    </div>
    <!-- jQuery dan DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
    $(document).ready(function() {
        $('table.table-striped').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100]
        });
    });
    </script>

</body>

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