<?php
require 'config/config.php';
require_once 'config/functions.php';

$id_permohonan = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id_permohonan) {
    echo "<script>alert('ID permohonan tidak valid'); window.location='index.php?page=riwayat_permohonan';</script>";
    exit;
}

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

// Ambil data utama
$permohonan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM permohonan WHERE id = '$id_permohonan'"));

// Ambil detail barang
$details = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id = '$id_permohonan'");

// Ambil nama pemohon dari tabel users
$user_id = $permohonan['user_id'];
$query_user = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'");
$data_user = mysqli_fetch_assoc($query_user);
$nama_pemohon = $data_user['name'] ?? '--Tidak ditemukan--';

// Ambil semua pejabat berdasarkan user pemohon
$pejabat = getAllPejabatByUser($conn, $permohonan['user_id']);

// Katimja
$name_pejabat1 = $pejabat['katimja']['name'] ?? '';
$nama_tim = $pejabat['tim_kerja'] ?? '-';
$nip_nik_pejabat_katimja = $pejabat['katimja']['nip_nik'] ?? '';

// PPK
$nama_ppk = $pejabat['ppk']['name'] ?? '';
$nip_ppk = $pejabat['ppk']['nip_nik'] ?? '';

// KPA
$nama_kpa = $pejabat['kpa']['name'] ?? '';
$nip_kpa = $pejabat['kpa']['nip_nik'] ?? '';

$qr_code_pejabat1 = 'uploads/qrcodes/katimja/' . ($pejabat['katimja']['id_user'] ?? '') . '.png';
$ttd_ppk = 'uploads/qrcodes/ppk/' . ($pejabat['ppk']['id_user'] ?? '') . '.png';
$ttd_kpa = 'uploads/qrcodes/kpa/' . ($pejabat['kpa']['id_user'] ?? '') . '.png';

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        .signature-table td {
            height: 80px;
            vertical-align: bottom;
        }
    }

    .table th,
    .table td {
        text-align: left;
        vertical-align: middle;
    }
    </style>
</head>

<!-- <body onload="window.print()"> -->

<body>
    <div class="container mt-4">
        <div class="text-center">
            <h3>BLANKO PERMINTAAN</h3>
            <p><i>Form ini dicetak dari aplikasi SIPATRA BPPP Ambon</i></p>
        </div>

        <table class="table mt-4">
            <tr>
                <th width="25%">No. Permohonan</th>
                <td>: <?= $permohonan['nomor_permohonan'] ?></td>
            </tr>
            <tr>
                <th>Tanggal Permohonan</th>
                <td>: <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])) ?></td>
            </tr>
            <tr>
                <th>Nama Pemohon</th>
                <td>: <?= $nama_pemohon ?></td>
            </tr>
            <tr>
                <th>Mata Anggaran</th>
                <td>: <?= !empty($permohonan['mata_anggaran']) ? $permohonan['mata_anggaran'] : '--Belum diisi PPK--' ?>
                </td>
            </tr>
            <tr>
                <th>Detil Kegiatan</th>
                <td>: <?= $permohonan['detail_kegiatan'] ?></td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>: Rp. <?= number_format($permohonan['grand_total_harga'], 0, ',', '.') ?></td>
            </tr>
        </table>

        <h4 class="mt-4">Detail Permintaan Barang</h4>
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
                <?php
                $no = 1;
                $total = 0;
                while ($row = mysqli_fetch_assoc($details)) {
                    $subTotal = $row['harga_satuan'] * $row['jumlah_barang'];
                    $total += $subTotal;
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_barang']}</td>
                        <td>{$row['satuan']}</td>
                        <td>Rp. " . number_format($row['harga_satuan'], 0, ',', '.') . "</td>
                        <td>{$row['jumlah_barang']}</td>
                        <td>Rp. " . number_format($subTotal, 0, ',', '.') . "</td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total Keseluruhan</th>
                    <th>Rp. <?= number_format($total, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="signature-table text-center">
            <table class="table table-borderless" style="width:100%;">
                <tbody>
                    <tr>
                        <td></td>
                        <td>Pemohon,<br>Ambon,
                            <?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])); ?><br><img
                                src="<?= $base_url ?>/pages/pemohon/<?= htmlspecialchars($permohonan['qr_code_pemohon']); ?>"
                                alt="QR Code" width="100"><br>
                            <?= $nama_pemohon ?><br>NIP/NIK:
                            <?= htmlspecialchars($nip_nik_pemohon); ?>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <!-- Nama Tim Kerja -->
                        <?php $katimja = getKatimjaPermohonan($conn, $id_permohonan); ?>
                        <td>Mengetahui,<br>Ketua Tim Kerja,
                            <br>
                            <?php if (!empty($katimja['qr_code_pejabat1'])): ?>
                            <img src="<?= $base_url . $katimja['qr_code_pejabat1'] ?>" alt="QR Katimja" width="100">
                            <?php else: ?>
                            <br><br><br>
                            <div class="card-title">Menunggu Persetujuan</div><?php endif; ?>
                            <!-- Nama & NIP Katimja -->
                            <br><?= $katimja['katimja_name'] ?><br>NIP:
                            <?= $katimja['katimja_nip'] ?>
                        </td>
                        <td></td>
                        <?php $ppkData = getDataPPK($conn, $id_permohonan);
                        ?>


                        <td>Mengetahui,
                            <br>
                            <?= $ppkData['jabatan'] ?? '-' ?><br><br>
                            <?php if (file_exists($ppkData['qr_code'])): ?>
                            <img src="<?= $base_url . $ppkData['qr_code'] ?>" width="120">
                            <?php else: ?>
                            <em><?= $ppkData['qr_code'] ?></em>
                            <?php endif; ?>
                            <br><br><br>
                            <?= $ppkData['name'] ?? '-' ?><br>
                            NIP: <?= $ppkData['nip_nik'] ?? '-' ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <?php $kpaData = getDataKPA($conn, $id_permohonan);
                        ?>
                        <td>Menyetujui,
                            <br><?= $kpaData['jabatan'] ?? '-' ?>,<br><br>
                            <?php if (file_exists($kpaData['qr_code'])): ?>
                            <img src="<?= $base_url . $kpaData['qr_code'] ?>" width="120">
                            <?php else: ?>
                            <em><?= $kpaData['qr_code'] ?></em>
                            <?php endif; ?>
                            <br><br><br><?= $kpaData['name'] ?? '-' ?>
                            <br>NIP:
                            <?= $kpaData['nip_nik'] ?? '-' ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<!-- 
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Permohonan</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="p-4">
    <div class="container">
        <h3>Detail Permohonan</h3>
        <p><i>Ditampilkan dari aplikasi SIPATRA BPPP Ambon</i></p>

        <table class="table table-bordered">
            <tr>
                <th>No Permohonan</th>
                <td><?= $permohonan['nomor_permohonan'] ?></td>
            </tr>
            <tr>
                <th>Tanggal Permohonan</th>
                <td><?= date('d F Y', strtotime($permohonan['tanggal_permohonan'])) ?></td>
            </tr>
            <tr>
                <th>Nama Pemohon</th>
                <td><?= $nama_pemohon ?></td>
            </tr>
            <tr>
                <th>Mata Anggaran</th>
                <td><?= !empty($permohonan['mata_anggaran']) ? $permohonan['mata_anggaran'] : '--Belum diisi PPK--' ?>
                </td>
            </tr>
            <tr>
                <th>Judul Kegiatan</th>
                <td><?= $permohonan['detail_kegiatan'] ?></td>
            </tr>
            <tr>
                <th>Total Harga</th>
                <td>Rp. <?= number_format($permohonan['grand_total_harga'], 0, ',', '.') ?></td>
            </tr>
        </table>

        <h4>Rincian Permintaan Barang</h4>
        <table class="table table-bordered">
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
                <?php
                $no = 1;
                $total = 0;
                while ($row = mysqli_fetch_assoc($details)) {
                    $subTotal = $row['harga_satuan'] * $row['jumlah_barang'];
                    $total += $subTotal;
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_barang']}</td>
                        <td>{$row['satuan']}</td>
                        <td>Rp. " . number_format($row['harga_satuan'], 0, ',', '.') . "</td>
                        <td>{$row['jumlah_barang']}</td>
                        <td>Rp. " . number_format($subTotal, 0, ',', '.') . "</td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total</th>
                    <th>Rp. <?= number_format($total, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="signature-table text-center">
            <table class="table table-borderless" style="width:100%;">
                <tbody>
                    <tr>
                        <td></td>
                        <td>Pemohon<br>Ambon, 11 April 2025<br><br><br><br><br><?= $nama_pemohon ?><br>NIP: 123456</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Mengetahui,<br>Pejabat Penandatanganan SPM,
                            <br><br><br><br><br>Rachel L. Wattimena, S.Pi, M.Si<br>NIP: 123456
                        </td>
                        <td></td>
                        <td>Plt. Kasubbag Umum /PPK,<br><br><br><br><br> <?= $nama_ppk ?><br>NIP: <?= $nip_ppk ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Menyetujui, <br>Kuasa Pengguna Anggaran,<br><br><br><br><br><?= $nama_kpa ?><br>NIP:
                            <?= $nip_kpa ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <hr>
        <a href="cetak_permohonan_tes.php?id=<?= $id_permohonan ?>" target="_blank" class="btn btn-primary mt-3">🖨️
            Cetak Permohonan (PDF)</a>
    </div>
</body>

</html> -->