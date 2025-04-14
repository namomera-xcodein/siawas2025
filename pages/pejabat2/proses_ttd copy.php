<?php
ob_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../../config/config.php");
require_once("../../config/functions.php");

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if (!class_exists('Endroid\QrCode\Builder\Builder')) {
    exit('Library QR Code tidak ditemukan!');
}

if (!isset($_GET['id_permohonan'])) {
    echo "<script>alert('ID permohonan tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$id_permohonan = intval($_GET['id_permohonan']);
$id_pejabat = $_SESSION['user_id'] ?? 0;
$nama_pejabat = $_SESSION['name'] ?? 'Pejabat';

if ($id_permohonan <= 0 || $id_pejabat <= 0) {
    echo "<script>alert('Data tidak valid.'); window.history.back();</script>";
    exit;
}

// Ambil nomor permohonan
$q = $conn->prepare("SELECT nomor_permohonan FROM permohonan WHERE id = ? LIMIT 1");
$q->bind_param("i", $id_permohonan);
$q->execute();
$result = $q->get_result();
$permohonan = $result->fetch_assoc();

if (!$permohonan) {
    echo "<script>alert('Data permohonan tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$nomor_permohonan = $permohonan['nomor_permohonan'];
$tanggal_now = date('Y-m-d H:i:s');
$tanggal_format_id = date('d F Y');

// Buat direktori penyimpanan QR jika belum ada
$qrDirectory = __DIR__ . "/../../uploads/qrcode/katimja/";
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}

$qrData = "Tanda Tangan Katimja\nNama: $nama_pejabat\nTanggal: $tanggal_format_id\nNomor Permohonan: $nomor_permohonan";
$logoPath = realpath(__DIR__ . '/../../assets/kkp.png');
if (!$logoPath) {
    exit("Logo tidak ditemukan!");
}

try {
    $qrCode = Builder::create()
        ->writer(new PngWriter())
        ->data($qrData)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH))
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeMode(RoundBlockSizeMode::ENLARGE))
        ->logoPath($logoPath)
        ->logoResizeToWidth(50)
        ->build();

    $qrFileName = "ttd_katimja_{$nomor_permohonan}.png";
    $qrFullPath = $qrDirectory . $qrFileName;
    $qrCode->saveToFile($qrFullPath);

    $qrPathDb = "/uploads/qrcode/katimja/$qrFileName";

    // Simpan ke tabel signatures
    $stmt = $conn->prepare("INSERT INTO signatures (permohonan_id, pejabat_id, signature, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $id_permohonan, $id_pejabat, $qrPathDb);
    $stmt->execute();

    // Update status permohonan ke berikutnya (status2 = 1 -> Menunggu PPK)
    $status2_baru = 1;
    $status_baru = "Menunggu Persetujuan Plt.Kasubbag Umum / PPK";

    $updateStmt = $conn->prepare("UPDATE permohonan SET status2 = ?, status = ?, updated_at = NOW() WHERE id = ?");
    $updateStmt->bind_param("isi", $status2_baru, $status_baru, $id_permohonan);
    $updateStmt->execute();

    echo "<script>alert('Tanda tangan berhasil diproses.'); window.location.href='../../index.php?page=pejabat2/permohonan_masuk';</script>";
} catch (Exception $e) {
    echo "<script>alert('Terjadi kesalahan saat memproses tanda tangan: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}