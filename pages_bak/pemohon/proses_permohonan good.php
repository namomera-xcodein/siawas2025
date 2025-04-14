<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
$total_harga = str_replace('.', '', $_POST['total_harga']);
$status = "Menunggu Persetujuan SPM";
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
//var_dump(file_exists($qrPath));

$qrCode->saveToFile($qrPath);

$qrPathDb = "/uploads/qrcode/$nomor_permohonan.png";
// if (!is_dir($qrPathDb)) {
//     mkdir($qrPathDb, 0777, true);
// }
//  var_dump($user_id, $detail_kegiatan, $nomor_permohonan, $total_harga, $status, $qrPathDb, $tanggal_permohonan,$qrCode,);
//  var_dump(file_exists($qrPath));
//  var_dump($qrDirectory, is_writable($qrDirectory));
//  exit();
try {
    $conn->begin_transaction();

    // Insert permohonan
    $stmt = $conn->prepare("INSERT INTO permohonan (user_id, detail_kegiatan, nomor_permohonan, total_harga, status, qr_code_pemohon, tanggal_permohonan, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("issssss", $user_id, $detail_kegiatan, $nomor_permohonan, $total_harga, $status, $qrPathDb, $tanggal_permohonan);
    $stmt->execute();
    $permohonan_id = $conn->insert_id;

    // Insert detail permohonan
    insertPermohonanDetail($conn, $permohonan_id, $_POST);

    $conn->commit();
    
} catch (Exception $e) {
    $conn->rollback();
    ob_end_clean();
    exit("Error: Permohonan gagal disimpan. " . $e->getMessage());
}

ob_end_flush();

function generateUniqueNomor($conn, $tanggal) {
    $lastNomor = 1;
    do {
        $nomor = "PB-" . $tanggal . "-" . str_pad($lastNomor, 4, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM permohonan WHERE nomor_permohonan = ?");
        $stmt->bind_param("s", $nomor);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] == 0) {
            return $nomor;
        }
        $lastNomor++;
    } while(true);
}

function generateQRCode($data, $logoPath) {
    return Builder::create()
        ->writer(new PngWriter())
        ->data($data)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(200)
        ->margin(10)
        ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->logoPath($logoPath)
        ->logoResizeToWidth(80)
        ->logoPunchoutBackground(true)
        ->build();
}

function insertPermohonanDetail($conn, $permohonan_id, $postData) {
    $stmt = $conn->prepare("INSERT INTO permohonan_detail (permohonan_id, nama_barang, satuan, harga_satuan, jumlah, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($postData['nama_barang'] as $key => $nama_barang) {
        $nama_barang = htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8');
        $satuan = htmlspecialchars($postData['satuan'][$key], ENT_QUOTES, 'UTF-8');
        $harga_satuan = floatval($postData['harga_satuan'][$key]);
        $jumlah = floatval($postData['jumlah'][$key]);
        $total_harga = floatval($postData['total_harga'][$key]);
        
        $stmt->bind_param("isssdd", $permohonan_id, $nama_barang, $satuan, $harga_satuan, $jumlah, $total_harga);
        $stmt->execute();
    }
}
?>