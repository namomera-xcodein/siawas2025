<?php
// Contoh data statis, Anda bisa ganti dengan data dari database
$data = [
    "pemohon" => "CARLO",
    "id_permohonan" => "PB-10042025-0001",
    "tanggal" => "10 April 2025",
    "total_harga" => 45000,
    "detail" => [
        ["nama_barang" => "lakban", "satuan" => "pax", "harga" => 5000, "jumlah" => 5],
        ["nama_barang" => "lem", "satuan" => "pcs", "harga" => 4000, "jumlah" => 5],
    ],
];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cetak Permohonan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .no-border {
            border: none !important;
        }

        .signature-block {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            width: 30%;
            text-align: center;
        }

        .qr {
            text-align: center;
            margin: 20px 0;
        }

        .info {
            margin-bottom: 15px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="info">
        <p><strong>Pemohon:</strong> <?= $data['pemohon'] ?></p>
        <p><strong>Detail Permohonan:</strong> <?= $data['id_permohonan'] ?></p>
        <p><strong>Tanggal Permohonan:</strong> <?= $data['tanggal'] ?></p>
        <p><strong>Total Harga:</strong> Rp. <?= number_format($data['total_harga'], 0, ',', '.') ?></p>
    </div>

    <table>
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
            $grand_total = 0; ?>
            <?php foreach ($data['detail'] as $item):
                $total = $item['harga'] * $item['jumlah'];
                $grand_total += $total;
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    <td><?= $item['satuan'] ?></td>
                    <td>Rp. <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td>Rp. <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" class="right"><strong>Jumlah</strong></td>
                <td><strong>Rp. <?= number_format($grand_total, 0, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>

    <p class="right">Ambon, <?= $data['tanggal'] ?><br>Pemohon.</p>

    <div class="qr">
        <img src="path/to/qr_code.png" width="100" alt="QR Code"><br>
        <?= $data['pemohon'] ?><br>
        054321
    </div>

    <div class="signature-block">
        <div class="signature">
            Mengetahui,<br>
            Ketua Tim Kerja<br><br><br>
            <strong>EKADASA PRIANTARA</strong><br>
            888888
            <p><em>Menunggu Persetujuan</em></p>
        </div>
        <div class="signature">
            Plt. Kasubbag Umum / PPK<br><br><br>
            <strong>Rahmawati Umasugi</strong><br>
            555555
            <p><em>Menunggu Persetujuan</em></p>
        </div>
        <div class="signature">
            Menyetujui,<br>
            Kuasa Pengguna Anggaran<br><br><br>
            <strong>ABUBAKAR, S.STP, M.Si</strong><br>
            666666
            <p><em>Menunggu Persetujuan</em></p>
        </div>
    </div>

</body>

</html>