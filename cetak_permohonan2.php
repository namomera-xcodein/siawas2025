<?php
require 'vendor/autoload.php'; // dompdf
require 'config/config.php';  // koneksi database

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'] ?? ($_GET['id_permohonan'] ?? 0);
$id = intval($id);

if (!$id) {
    die('ID Permohonan tidak ditemukan.');
}

$query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die('Data permohonan tidak ditemukan.');
}

// Ambil detail barang dari permohonan_detail
$detail_query = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id = '$id'");

ob_start(); // Start buffering HTML output
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Permohonan Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h3,
        p {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .signature-table {
            margin-top: 50px;
            width: 100%;
        }

        .signature-table td {
            text-align: center;
            height: 100px;
        }
    </style>
</head>

<body>

    <h2>Form Permohonan Barang</h2>

    <table>
        <tr>
            <th>ID</th>
            <td><?= $data['id'] ?></td>
        </tr>
        <tr>
            <th>Nama Pemohon</th>
            <td><?= $data['nama_pemohon'] ?? '-' ?></td>
        </tr>
        <tr>
            <th>Tanggal Permohonan</th>
            <td><?= $data['tanggal_permohonan'] ?? '-' ?></td>
        </tr>
        <tr>
            <th>Keperluan</th>
            <td><?= $data['keperluan'] ?? '-' ?></td>
        </tr>
    </table>

    <h3>Rincian Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            while ($row = mysqli_fetch_assoc($detail_query)) {
                $subtotal = (int)$row['subtotal_harga'];
                $total += $subtotal;
                echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama_barang']}</td>
                <td>{$row['satuan']}</td>
                <td>Rp " . number_format($row['harga_satuan'], 0, ',', '.') . "</td>
                <td>{$row['jumlah_barang']}</td>
                <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
            </tr>";
                $no++;
            }
            ?>
            <tr>
                <th colspan="5" style="text-align:right">Total</th>
                <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>Mengetahui,<br><br>Pejabat Penandatanganan SPM<br><br><br><br>
                <strong>Rachel L. Wattimena, S.Pi, M.Si</strong><br>NIP: 123456
            </td>

            <td>Dibuat Oleh,<br><br><?= $data['nama_pemohon'] ?><br><br><br><br>
                <strong>Nama Pemohon</strong><br>NIP: 123456
            </td>

            <td>Disetujui,<br><br>Kuasa Pengguna Anggaran<br><br><br><br>
                <strong>Nama Pejabat KPA</strong><br>NIP: 123456
            </td>
        </tr>
    </table>

</body>

</html>

<?php
$html = ob_get_clean();

// Konfigurasi DomPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan di browser
$dompdf->stream("permohonan_" . $id . ".pdf", array("Attachment" => false));
exit;
?>