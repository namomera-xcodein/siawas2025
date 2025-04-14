<?php
require_once __DIR__ . '/../../config/config.php';
// Jika butuh koneksi ke database

// Ambil data permohonan berdasarkan ID
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID permohonan tidak ditemukan!");
}

$query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id = '$id'");
$permohonan = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Permohonan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .container {
        width: 80%;
        margin: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    .no-border td {
        border: none;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>Detail Permohonan</h2>
        <p>Mata Anggaran: <?= htmlspecialchars($permohonan['mata_anggaran']); ?></p>
        <p>Detail Kegiatan: <?= htmlspecialchars($permohonan['detail_kegiatan']); ?></p>

        <h5>Tanda Tangan</h5>
        <table class="no-border">
            <tr>
                <td>
                    <h6>Pemohon</h6>
                    <p>Ambon, <?= date('d-m-Y'); ?></p>
                    <img src="<?= htmlspecialchars($permohonan['qr_code_pemohon']); ?>" width="100">
                    <p><?= htmlspecialchars($permohonan['user_id']); ?></p>
                </td>
                <td>
                    <h6>Pejabat Penandatanganan SPM</h6>
                    <p>Ambon, <?= date('d-m-Y'); ?></p>
                    <img src="<?= htmlspecialchars($permohonan['qr_code_SPM']); ?>" width="100">
                    <p><?= htmlspecialchars($permohonan['user_id']); ?></p>
                </td>
                <td>
                    <h6>Pejabat Penandatanganan PPK</h6>
                    <p>Ambon, <?= date('d-m-Y'); ?></p>
                    <img src="<?= htmlspecialchars($permohonan['qr_code_ppk']); ?>" width="100">
                    <p><?= htmlspecialchars($permohonan['user_id']); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <script>
    window.print(); // Otomatis mencetak halaman saat dibuka
    </script>

</body>

</html>