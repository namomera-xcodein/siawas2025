<?php
//session_start();
//include '../config/config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Pastikan koneksi database tersedia
if (!$conn) {
    die("Koneksi database gagal!");
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
if (isset($_POST['update_mata_anggaran']) && $_SESSION['role'] === 'pejabat_ppk') {
    $mata_anggaran = mysqli_real_escape_string($conn, $_POST['mata_anggaran']);
    mysqli_query($conn, "UPDATE permohonan SET mata_anggaran='$mata_anggaran' WHERE id=$id_permohonan");
    echo "<script>alert('Mata Anggaran diperbarui!'); window.location.reload();</script>";
}

$datenow = date('d F Y'); 
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Permohonan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        padding: 20px;
    }

    @media print {
        .btn-print {
            display: none;
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <h4>Detail Permohonan: <?= htmlspecialchars($permohonan['nomor_permohonan']); ?></h4>
        <p><strong>Mata Anggaran:</strong>
            <?php if ($_SESSION['role'] === 'pejabat_ppk'): ?>
        <form method="post">
            <input type="text" name="mata_anggaran" value="<?= htmlspecialchars($permohonan['mata_anggaran']); ?>">
            <button type="submit" name="update_mata_anggaran" class="btn btn-primary btn-sm">Simpan</button>
        </form>
        <?php else: ?>
        <?= htmlspecialchars($permohonan['mata_anggaran']); ?>
        <?php endif; ?>
        </p>
        <p><strong>Tanggal Permohonan:</strong> <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?></p>
        <p><strong>Total Harga:</strong> Rp. <?= number_format($permohonan['total_harga'], 0, ',', '.'); ?></p>

        <h5>Detail Barang</h5>
        <table class="table table-bordered">
            <thead class="table-light">
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
                <?php $no = 1; while ($barang = mysqli_fetch_assoc($detail_query)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($barang['nama_barang']); ?></td>
                    <td><?= htmlspecialchars($barang['satuan']); ?></td>
                    <td>Rp. <?= number_format($barang['harga_satuan'], 0, ',', '.'); ?></td>
                    <td><?= $barang['jumlah']; ?></td>
                    <td>Rp. <?= number_format($barang['total_harga'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h5>Tanda Tangan</h5>
        <div class="row">
            <div class="col-md-4 text-center">
                <h6>Pemohon</h6>
                <p>Ambon, <?= $datenow; ?></p>
                <img src="<?= htmlspecialchars($permohonan['qr_code_pemohon']); ?>" alt="QR Code Pemohon" width="100">
                <p><?= htmlspecialchars($permohonan['user_id']); ?></p>
                <p>NIP/NIK: 123456789</p>
            </div>
            <div class="col-md-4 text-center">
                <h6>Pejabat Penandatanganan SPM</h6>
                <p>Ambon, <?= $datenow; ?></p>
                <img src="<?= htmlspecialchars($permohonan['qr_code_SPM']); ?>" alt="QR Code SPM" width="100">
                <p><?= htmlspecialchars($permohonan['user_id']); ?></p>
                <p>NIP/NIK: 123456789</p>
            </div>
            <div class="col-md-4 text-center">
                <h6>Pejabat Penandatanganan PPK</h6>
                <p>Ambon, <?= $datenow; ?></p>
                <img src="<?= htmlspecialchars($permohonan['qr_code_ppk']); ?>" alt="QR Code PPK" width="100">
                <p><?= htmlspecialchars($permohonan['user_id']); ?></p>
                <p>NIP/NIK: 123456789</p>
            </div>
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-primary btn-print" onclick="window.print()">Cetak</button>
        </div>
    </div>

</body>

</html>