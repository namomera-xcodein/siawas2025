<?php
session_start();
include '../koneksi.php'; // atau sesuaikan path

if (!isset($_GET['id'])) {
    echo "ID permohonan tidak ditemukan!";
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;

// Ambil data permohonan
$permohonan_query = mysqli_query($conn, "
    SELECT p.*, s.nama_status 
    FROM permohonan p 
    LEFT JOIN status_permohonan s ON p.status2 = s.id_status 
    WHERE p.id = '$id'
");
$permohonan = mysqli_fetch_assoc($permohonan_query);
if (!$permohonan) {
    echo "Permohonan tidak ditemukan.";
    exit;
}

// Hitung progres
$status_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM status_permohonan");
$status_data = mysqli_fetch_assoc($status_result);
$total_status = (int) $status_data['total'];
$current_status_id = (int) $permohonan['status2'];
$progress = ($current_status_id > 0 && $total_status > 0) ? ($current_status_id / $total_status) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">

    <h3>Detail Permohonan: <?= htmlspecialchars($permohonan['nomor_permohonan']); ?></h3>

    <div class="progress my-3">
        <div class="progress-bar progress-bar-striped bg-info" role="progressbar"
            style="width: <?= round($progress) ?>%;" aria-valuenow="<?= round($progress) ?>" aria-valuemin="0"
            aria-valuemax="100"></div>
    </div>
    <p>Progres Permohonan: <strong><?= round($progress) ?>%</strong></p>
    <p>Status Saat Ini: <strong><?= htmlspecialchars($permohonan['nama_status']) ?></strong></p>

    <h5>Mata Anggaran:</h5>
    <?php if ($_SESSION['level_user'] == 3): ?>
        <form method="post">
            <input type="text" name="mata_anggaran" value="<?= htmlspecialchars($permohonan['mata_anggaran']); ?>">
            <button class="btn btn-sm btn-primary" type="submit" name="update_mata_anggaran">Simpan</button>
        </form>
    <?php else: ?>
        <?= htmlspecialchars($permohonan['mata_anggaran']); ?>
    <?php endif; ?>

    <h5 class="mt-3">Tanggal Permohonan: <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?></h5>
    <h5>Total Harga: Rp. <?= number_format($permohonan['grand_total_harga'], 0, ',', '.'); ?></h5>

    <hr>

    <h5>Detail Barang:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $detail_query = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id = '$id'");
            while ($barang = mysqli_fetch_assoc($detail_query)):
            ?>
                <tr>
                    <td><?= htmlspecialchars($barang['nama_barang']); ?></td>
                    <td><?= $barang['jumlah']; ?></td>
                    <td><?= $barang['satuan']; ?></td>
                    <td>Rp. <?= number_format($barang['harga_satuan'], 0, ',', '.'); ?></td>
                    <td>Rp. <?= number_format($barang['total_harga'], 0, ',', '.'); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <hr>

    <!-- Informasi Tanda Tangan -->
    <div class="row text-center">
        <?php
        $user_login_id = $_SESSION['user_id'];

        // Atasan
        $stmt = $conn->prepare("SELECT u1.id AS user_id, u1.name AS user_name, u1.atasan_id, u2.name AS atasan_name, u2.nip_nik AS atasan_nip_nik, u2.jabatan AS jabatan_atasan FROM users u1 LEFT JOIN users u2 ON u1.atasan_id = u2.id WHERE u1.id = ?");
        $stmt->bind_param("i", $user_login_id);
        $stmt->execute();
        $atasan = $stmt->get_result()->fetch_assoc();

        $atasan_name = $atasan['atasan_name'] ?? 'Tidak Ditemukan';
        $atasan_nip = $atasan['atasan_nip_nik'] ?? '-';
        $jabatan_atasan = $atasan['jabatan_atasan'] ?? '-';

        $qr_katimja = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM signatures WHERE permohonan_id = '$id' AND pejabat_id = '" . $atasan['atasan_id'] . "'"));
        ?>

        <div class="col-lg-4">
            <h5>Mengetahui,<br><?= htmlspecialchars($jabatan_atasan); ?></h5>
            <?php if (!empty($qr_katimja['signature'])): ?>
                <img src="<?= $qr_katimja['signature']; ?>" width="100">
            <?php else: ?><br><em>Menunggu Persetujuan</em><?php endif; ?>
            <br>
            <h5><?= htmlspecialchars($atasan_name); ?></h5>
            <h6>NIP: <?= htmlspecialchars($atasan_nip); ?></h6>
        </div>

        <!-- PPK -->
        <?php
        $ppk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE level_user = 3 LIMIT 1"));
        $sig_ppk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM signatures WHERE permohonan_id = '$id' AND pejabat_id = '" . $ppk['id'] . "'"));
        ?>
        <div class="col-lg-4">
            <h5><?= htmlspecialchars($ppk['jabatan']); ?></h5>
            <?php if (!empty($sig_ppk['signature'])): ?>
                <img src="<?= $sig_ppk['signature']; ?>" width="100">
            <?php else: ?><br><em>Menunggu Persetujuan</em><?php endif; ?>
            <br>
            <h5><?= htmlspecialchars($ppk['name']); ?></h5>
            <h6>NIP: <?= htmlspecialchars($ppk['nip_nik']); ?></h6>
        </div>

        <!-- KPA -->
        <?php
        $kpa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE level_user = 4 LIMIT 1"));
        $sig_kpa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM signatures WHERE permohonan_id = '$id' AND pejabat_id = '" . $kpa['id'] . "'"));
        ?>
        <div class="col-lg-4">
            <h5>Menyetujui,<br><?= htmlspecialchars($kpa['jabatan']); ?></h5>
            <?php if (!empty($sig_kpa['signature'])): ?>
                <img src="<?= $sig_kpa['signature']; ?>" width="100">
            <?php else: ?><br><em>Menunggu Persetujuan</em><?php endif; ?>
            <br>
            <h5><?= htmlspecialchars($kpa['name']); ?></h5>
            <h6>NIP: <?= htmlspecialchars($kpa['nip_nik']); ?></h6>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="javascript:history.back()" class="btn btn-secondary">← Kembali</a>
        <a href="cetak_permohonan.php?id=<?= $id ?>" class="btn btn-primary" target="_blank">🖨️ Cetak</a>
    </div>

</body>

</html>