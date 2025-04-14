<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!class_exists('Endroid\QrCode\Builder\Builder')) {
    exit('Library QR Code tidak ditemukan!');
}

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request method');
}

$user_id = $_SESSION['user_id'] ?? 0;
$nama_pemohon = $_SESSION['name'] ?? "Unknown";
$detail_kegiatan = htmlspecialchars($_POST['detail_kegiatan'], ENT_QUOTES, 'UTF-8');
$grand_total_harga = floatval(str_replace(',', '.', str_replace('.', '', $_POST['grand_total_harga'])));
$status = "Menunggu Persetujuan SPM";
$status2 = 0; // Default status "Diajukan"
$tanggal_permohonan = date('Y-m-d H:i:s');

// Generate nomor permohonan
$tanggal = date('dmY');
$nomor_permohonan = generateUniqueNomor($conn, $tanggal);

// Setup QR Code
$qrDirectory = __DIR__ . "/uploads/qrcode/";
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}

$qrData = "Nomor: $nomor_permohonan\nPemohon: $nama_pemohon\nTanggal: " . date('Y-m-d');
$logoPath = realpath(__DIR__ . '/../../assets/kkp.png');
if (!$logoPath) {
    exit("Logo tidak ditemukan!");
}

$qrCode = generateQRCode($qrData, $logoPath);
$qrPath = $qrDirectory . "$nomor_permohonan.png";
$qrCode->saveToFile($qrPath);

$qrPathDb = "/uploads/qrcode/$nomor_permohonan.png";

try {
    $_SESSION['old_input'] = $_POST;
    $conn->begin_transaction();

    // kode sebelumnya Insert permohonan 
    // $stmt = $conn->prepare("INSERT INTO permohonan (user_id, detail_kegiatan, nomor_permohonan, grand_total_harga, status, qr_code_pemohon, tanggal_permohonan, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    // $stmt->bind_param("issssss", $user_id, $detail_kegiatan, $nomor_permohonan, $grand_total_harga, $status, $qrPathDb, $tanggal_permohonan);
    // $stmt->execute();
    // Insert permohonan dengan status2
    $stmt = $conn->prepare("INSERT INTO permohonan (user_id, detail_kegiatan, nomor_permohonan, grand_total_harga, status, status2, qr_code_pemohon, tanggal_permohonan, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("issssiss", $user_id, $detail_kegiatan, $nomor_permohonan, $grand_total_harga, $status, $status2, $qrPathDb, $tanggal_permohonan);
    $stmt->execute();
    $permohonan_id = $conn->insert_id;

    // Insert detail permohonan
    insertPermohonanDetail($conn, $permohonan_id, $_POST);

    $conn->commit();

    // **Tampilkan JavaScript alert setelah sukses**
    echo "<script>
        alert('Permohonan berhasil diajukan dengan nomor: $nomor_permohonan');
        window.location.href='index.php?page=riwayat_permohonan';
    </script>";

    exit();
} catch (Exception $e) {
    $conn->rollback();
    
    // **Tampilkan JavaScript alert jika terjadi error**
    echo "<script>
        alert('Permohonan gagal diajukan! Error: " . addslashes($e->getMessage()) . "');
        window.location.href='../../index.php?page=permohonan_new';
    </script>";

    exit();
}

// Fungsi untuk generate nomor unik
function generateUniqueNomor($conn, $tanggal) {
    $lastNomor = 1;
    $maxAttempts = 1000;
    while ($lastNomor <= $maxAttempts) {
        $nomor = "PB-" . $tanggal . "-" . str_pad($lastNomor, 4, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM permohonan WHERE nomor_permohonan = ?");
        $stmt->bind_param("s", $nomor);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] == 0) {
            return $nomor;
        }
        $lastNomor++;
    }
    exit("Error: Gagal menghasilkan nomor permohonan.");
}
?>