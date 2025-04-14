<?php
require_once 'config/functions.php';
require_once 'config/config.php';

//session_start(); // Mulai sesi jika belum dimulai

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>



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
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="#">Page / Permohonan Baru</a></li>
                        </ol>
                    </nav>
                </div>
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    <?php 
                    $time = date('H');
                    if ($time >= 5 && $time <= 11) {
                        echo "Selamat Pagi,";
                    } elseif ($time >= 12 && $time <= 15) {
                        echo "Tetap Semangat!,";
                    } elseif ($time >= 16 && $time <= 18) {
                        echo "Lembur Ya?,";
                    } else {
                        echo "Semangat ya,";
                    }
                    echo " " . $_SESSION['name'] . "!<br><h5>Anda Login sebagai " . $_SESSION['role'] . "</h5>";
                    ?>

                    <br>
                    <?php 
                    // setlocale(LC_TIME, 'id_ID');
                    // echo strftime('%A, %d %B %Y %H:%M:%S', time()); 
                    ?>
                </h3>

            </div>
            <div class="col-5 align-self-center">

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
        <!-- ============================================================== -->
        <!-- Start First Grid -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Permohonan Baru</h4>
                        <form method="post">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="card-title">Mata Anggaran</h6>
                                        <div class="form-group">
                                            <input type="text" name="mata_anggaran" class="form-control"
                                                placeholder="untuk diisi ppk" value="-- untuk diisi PPK --" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 class="card-title">Judul Kegiatan</h6>
                                        <div class="form-group">
                                            <input type="text" name="detail_kegiatan" class="form-control"
                                                placeholder="isi dengan judul kegiatan permohonan" />
                                        </div>
                                    </div>
                                    <div class="col-12"><br></div>
                                    <div class="col-md-12">
                                        <h6 class="card-title">Input Data Barang</h6>
                                    </div>
                                    <table class="table" id="permohonanTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Nama Barang</th>
                                                <th class="text-center">Satuan<bR><small class="text-muted">(contoh:
                                                        pax,buah,pcs)</small></th>
                                                <th class="text-center">Harga Satuan<br><small class="text-muted">(harga
                                                        satuan barang)</small>
                                                </th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-center">Total Harga</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemRows"></tbody>
                                    </table>
                                </div>

                                <div class="form-actions">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info" id="addItem">Tambah</button>
                                        <button type="reset" class="btn btn-dark">Reset</button>
                                    </div>
                                </div>
                                <h4 class="mt-3">Grand Total: Rp <span id="grandTotal">0</span></h4>
                                <button type="submit" class="btn btn-success" id="savePermohonan" disabled>Simpan
                                    Permohonan</button>
                        </form>




                    </div>
                </div>
            </div>
        </div>
        <?       
// Ambil data user yang sedang login
$user_id = $_SESSION['user_id'];
$queryUser = $conn->prepare("SELECT name, nip_nik, jabatan FROM users WHERE id = ?");
$queryUser->bind_param("s", $user_id);
$queryUser->execute();
$resultUser = $queryUser->get_result();
$userData = $resultUser->fetch_assoc();
$queryUser->close();

// Pastikan data user ditemukan
if (!$userData) {
    die("User tidak ditemukan.");
}

// Ambil nama, NIP/NIK, dan jabatan user
$nama_pemohon = htmlspecialchars($userData['name']);
$nip_nik = htmlspecialchars($userData['nip_nik']);
$jabatan = htmlspecialchars($userData['jabatan']);

// Ambil data dari form
$mata_anggaran = htmlspecialchars($_POST['mata_anggaran']);
$detail_kegiatan = htmlspecialchars($_POST['detail_kegiatan']);
$total_harga = htmlspecialchars($_POST['total_harga']);
$tanggal_permohonan = date('Y-m-d H:i'); // Set tanggal otomatis

// Generate nomor permohonan otomatis
$query = "SELECT MAX(nomor_permohonan) AS last_id FROM permohonan WHERE DATE(tanggal_permohonan) = CURDATE()";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$lastId = $row['last_id'] ? intval(substr($row['last_id'], -3)) : 0;
$nextId = $lastId + 1;
$nomor_permohonan = date('Ymd') . sprintf('%03d', $nextId);

// Status awal permohonan
$status = "Menunggu Persetujuan SPM";

// Buat QR Code
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;

$qrCodeData = "Nomor: $nomor_permohonan\nPemohon: $nama_pemohon\nNIP/NIK: $nip_nik\nJabatan: $jabatan\nTanggal: $tanggal_permohonan";
$result = Builder::create()
    ->writer(new PngWriter())
    ->data($qrCodeData)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(ErrorCorrectionLevel::High)
    ->size(300)
    ->margin(10)
    ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
    ->logoPath(realpath(__DIR__ . '/../assets/kkp.png')) // Pastikan path benar
    ->logoResizeToWidth(80)
    ->logoPunchoutBackground(true)
    ->labelText('e-Sign SiMola')
    ->labelFont(new OpenSans(20))
    ->labelAlignment(LabelAlignment::Center)
    ->build();

// Pastikan folder qrcodes memiliki izin tulis
if (!file_exists('qrcodes')) {
    mkdir('qrcodes', 0777, true);
}

$qrPath = "qrcodes/qr_$nomor_permohonan.png";
$result->saveToFile($qrPath);

// Simpan ke tabel permohonan
$stmt = $conn->prepare("INSERT INTO permohonan (id, user_id, mata_anggaran, detail_kegiatan, nomor_permohonan, tanggal_permohonan, total_harga, status, qr_code_pemohon, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssssssss", $nomor_permohonan, $user_id, $mata_anggaran, $detail_kegiatan, $nomor_permohonan, $tanggal_permohonan, $total_harga, $status, $qrPath);

if ($stmt->execute()) {
    // Ambil data detail barang
    if (!empty($_POST['nama_barang']) && is_array($_POST['nama_barang'])) {
        foreach ($_POST['nama_barang'] as $index => $nama_barang) {
            $satuan = $_POST['satuan'][$index] ?? '';
            $harga_satuan = $_POST['harga_satuan'][$index] ?? 0;
            $jumlah = $_POST['jumlah'][$index] ?? 0;
            $total_harga_barang = $_POST['total_harga_barang'][$index] ?? 0;

            // Simpan ke tabel permohonan_detail
            $stmtDetail = $conn->prepare("INSERT INTO permohonan_detail (permohonan_id, nama_barang, satuan, harga_satuan, jumlah, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtDetail->bind_param("ssssss", $nomor_permohonan, $nama_barang, $satuan, $harga_satuan, $jumlah, $total_harga_barang);
            $stmtDetail->execute();
            $stmtDetail->close();
        }
    }

    echo "Data berhasil disimpan dengan ID: $nomor_permohonan dan QR Code berhasil dibuat.";
} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}

$stmt->close();?>

        <!-- end first grid -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Riwayat Permohonan</h4>

                        </div>
                        <div class="table">
                            <table class="">
                                <thead class="bg-success text-white">

                                    <tr class="border-0">
                                        <th class="">No.</th>
                                        <th class="">Tanggal
                                            Permohonan</th>
                                        <th class="">Nomor Permohonan</th>
                                        <th class="">Pemohon
                                        </th>
                                        <th class="">Status
                                        </th>
                                        <th class="">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php 
                                    $no = 1; 
                                    while ($row = $requests->fetch_assoc()) { 
                                    ?>
                                    <tr>
                                        <td class="border-top-0 px-2 py-4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><?php echo $no++; ?></div>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">

                                            <div class="">
                                                <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                    <?= date('d F Y', strtotime($row['tanggal_permohonan'])); ?>
                                                </h5>
                                                <span class="text-muted font-14"> Jam
                                                    :<?= date('h:i', strtotime($row['tanggal_permohonan'])); ?></span>
                                            </div>
                                        </td>
                                        <td class="font-weight-medium text-dark border-top-0 px-2 py-4">
                                            <div class="popover-icon">
                                                <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                    <?= htmlspecialchars($row['nomor_permohonan']); ?>
                                                </h5>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-center px-2 py-4">
                                            <?= htmlspecialchars($row['nama_pemohon']); ?>

                                            <!-- <i class="fa fa-circle text-primary font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i> -->
                                        </td>
                                        <td class="text-center font-weight-medium text-dark px-2 py-4">
                                            <small
                                                class="text-muted"><?= nl2br(htmlspecialchars($row['status'])); ?></small>

                                        </td>
                                        <td class="font-weight-medium text-dark border-top-0 px-2 py-4">
                                            <a href="/index.php?page=detail_permohonan&id=<?= $row['id']; ?>"
                                                class="btn btn-info btn-sm">Lihat</a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
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

<!-- End First Cards -->
<!-- *************************************************************** -->






</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let itemCount = 0;

    $("#addItem").click(function() {
        itemCount++;
        let row = `<tr id="row${itemCount}">
             <td class="text-center">${itemCount}</td>            <td><input type="text" name="nama_barang[]" class="form-control" required></td>
            <td><input type="text" name="satuan[]" class="form-control" required></td>
            <td><input type="number" name="harga_satuan[]" class="form-control harga_satuan" required></td>
            <td><input type="number" name="jumlah[]" class="form-control jumlah" required></td>
            <td><input type="text" name="total_harga[]" class="form-control total_harga" readonly></td>
            <td><button type="button" class="btn btn-danger removeItem" data-id="${itemCount}">Hapus</button></td>
        </tr>`;
        $("#itemRows").append(row);
        updateGrandTotal();
    });

    $(document).on("input", ".harga_satuan, .jumlah", function() {
        let row = $(this).closest("tr");
        let harga = parseFloat(row.find(".harga_satuan").val()) || 0;
        let jumlah = parseInt(row.find(".jumlah").val()) || 0;
        let total = harga * jumlah;
        row.find(".total_harga").val(total);
        updateGrandTotal();
    });

    $(document).on("click", ".removeItem", function() {
        $(this).closest("tr").remove();
        updateGrandTotal();
    });

    function updateGrandTotal() {
        let grandTotal = 0;
        $(".total_harga").each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $("#grandTotal").text(new Intl.NumberFormat("id-ID").format(grandTotal));
        $("#savePermohonan").prop("disabled", $(".total_harga").length === 0);
    }
    // SweetAlert2 Konfirmasi Submit
    $("form").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Konfirmasi Pengajuan",
            text: "Apakah Anda yakin ingin mengajukan permohonan ini?Permohonan tidak dapat dibatalkan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, ajukan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    });
});
</script>