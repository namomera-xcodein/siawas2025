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

<body onload="window.print()">
    <div class="container mt-4">
        <div class="text-center">
            <h3>BLANKO PERMINTAAN</h3>
            <p><i>Form ini dicetak dari aplikasi SIPATRA BPPP Ambon</i></p>
        </div>

        <table class="table mt-4">
            <tr>
                <th class="text-start" width="25%">Mata Anggaran</th>
                <td class="text-start" width="75%">: XXXX-XXXX-XXXX</td>
            </tr>
            <tr>
                <th class="text-start">Detil Kegiatan</th>
                <td class="text-start">: Nama Kegiatan Contoh</td>
            </tr>
            <tr>
                <th class="text-start">Waktu</th>
                <td class="text-start">: 12 April 2025</td>
            </tr>
            <tr>
                <th class="text-start">Jumlah</th>
                <td class="text-start">: Rp. 1.000.000</td>
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
                    <td>Rp. 1.000.000</td>
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
                    <td>Pemohon<br>Ambon, 12 April 2025<br><br><br><br><br>Nama Pemohon<br>NIP: 123456</td>
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
        <div class="signature-table text-center">
            <table class="table table-borderless" style="width:100%;">
                <tbody>
                    <tr>
                        <td></td>
                        <td>Pemohon<br>Ambon, 11 April 2025<br><br><br><br><br>Nama Pemohon<br>NIP: 123456</td>
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
                        <td>Menyetujui, <br>Kuasa Pengguna Anggaran,<br><br><br><br><br>Nama Pejabat KPA<br>NIP: 123456
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>