<?php
include_once __DIR__ . '/config/config.php';

// Contoh data yang akan ditampilkan
$mata_anggaran = "XXXX-XXXX-XXXX";
$judul_kegiatan = "Nama Kegiatan Contoh";
$datenow = date('d F Y');
$total_jumlah = "1.000.000";

//session_start();
//include '../config/config.php';

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
if (isset($_POST['update_mata_anggaran']) && $_SESSION['role'] === 'pejabat_ppk') {
    $mata_anggaran = mysqli_real_escape_string($conn, $_POST['mata_anggaran']);
    mysqli_query($conn, "UPDATE permohonan SET mata_anggaran='$mata_anggaran' WHERE id=$id_permohonan");
    echo "<script>alert('Mata Anggaran diperbarui!'); window.location.reload();</script>";
}

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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Mengatur ukuran halaman cetak ke A4 */
    @media print {
        body {
            width: 210mm;
            height: 297mm;
            margin: 1mm auto;
            font-size: 12px;
        }

        .table {
            width: 100%;
            font-size: 12px;
        }

        .table th,
        .table td {
            padding: 5px;
            text-align: left;
        }

        .signature-table {
            margin-top: 40px;
        }

        /* Pastikan tabel tanda tangan tetap dalam satu halaman */
        .signature-table td {
            height: 80px;
            vertical-align: bottom;
        }
    }

    /* Gaya umum */
    .table th,
    .table td {
        text-align: left;
        vertical-align: middle;
    }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="text-center">
            <h3>BLANKO PERMINTAAN</h3>
            <p><i>Form ini dicetak dari aplikasi SIPATRA BPPP Ambon</i></p>
        </div>

        <table class="table mt-4">
            <tr>
                <th class="text-start" width="25%">Mata Anggaran</th>
                <td class="text-start" width="75%">: <?php echo $mata_anggaran; ?></td>
            </tr>
            <tr>
                <th class="text-start">Detil Kegiatan</th>
                <td class="text-start">: <?php echo $judul_kegiatan; ?></td>
            </tr>
            <tr>
                <th class="text-start">Waktu</th>
                <td class="text-start">: <?php echo $datenow; ?></td>
            </tr>
            <tr>
                <th class="text-start">Jumlah</th>
                <td class="text-start">: Rp. <?= number_format($permohonan['grand_total_harga'], 0, ',', '.'); ?></td>
            </tr>
        </table>

        <h4 class="mt-4">Detail Permintaan</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Barang</th>
                    <th width="10%">Satuan</th>
                    <th width="15%">Harga Satuan</th>
                    <th width="10%">Jumlah</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Contoh Barang</td>
                    <td>Unit</td>
                    <td>500.000</td>
                    <td>2</td>
                    <td>1.000.000</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total Keseluruhan</th>
                    <th>Rp. 1.000.000</th>
                </tr>
            </tfoot>
        </table>

        <div class="signature-table text-center">
            <table class="table table-borderless" style="width:100%;">
                <tr>
                    <td></td>
                    <td>Pemohon<br>Ambon, <?php echo $datenow; ?><br><br><br><br><br>Nama Pemohon<br>NIP: 123456</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Mengetahui,<br>Pejabat Penandatanganan SPM,
                        <br><br><br><br><br>Rachel L. Wattimena, S.Pi, M.Si<br>NIP: 123456
                    </td>
                    <td></td>
                    <td>Plt. Kasubbag Umum /PPK,<br><br><br><br><br>Nama Pejabat PPK<br>NIP: 123456</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Menyetujui, <br>Kuasa Pengguna Anggaran,<br><br><br><br><br>Nama Pejabat KPA<br>NIP: 123456</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>