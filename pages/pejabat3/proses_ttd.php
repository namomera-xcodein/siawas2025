<?php
// Include file konfigurasi dan autoload
include '../../config/config.php';
include '../../vendor/autoload.php';

ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari sesi
$user_id = $_SESSION['user_id'] ?? 0;
$nama_pejabat = $_SESSION['name'] ?? "Unknown";
$jabatan_pejabat = $_SESSION['level_jabatan'] ?? "Unknown";
$nip_pejabat = $_SESSION['nip_nik'] ?? "Unknown";
$level_user = $_SESSION['level_user'] ?? null;

$tanggal_ttd = date('Y-m-d H:i:s');
$tanggal_ttd2 = date('Y-m-d');

// Tentukan kolom dan status berdasarkan level user
if ($level_user == 3) {
    $qr_column = 'qr_code_ppk';
    $new_status = 2;
} elseif ($level_user == 4) {
    $qr_column = 'qr_code_kpa';
    $new_status = 3;
} else {
    echo "<script>alert('Peran pengguna tidak dikenali.'); window.history.back();</script>";
    exit();
}

// Ambil ID permohonan dari parameter GET
$id_permohonan = $_GET['id_permohonan'] ?? null;
if (!$id_permohonan) {
    echo "<script>alert('ID permohonan tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

// Ambil data permohonan
$stmt = $conn->prepare("SELECT * FROM permohonan WHERE id = ?");
$stmt->bind_param("i", $id_permohonan);
$stmt->execute();
$result = $stmt->get_result();
if (!$row = $result->fetch_assoc()) {
    echo "<script>alert('Data permohonan tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}
$nomor_permohonan = $row['nomor_permohonan'];

// Data tanda tangan
$signature_data = $_POST['signature'] ?? '';

// Direktori QR Code
$qrDirectory = realpath(__DIR__ . '/../../') . "/uploads/qrcode/pejabat/";
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}

// QR Code Data
$qrData = "Nomor: $nomor_permohonan\nNama: $nama_pejabat\nJabatan: $jabatan_pejabat\nNIP: $nip_pejabat\nTanggal: $tanggal_ttd";

// Path logo
$logoPath = realpath(__DIR__ . '/../../assets/kkp.png');
if (!$logoPath) {
    exit("Logo tidak ditemukan!");
}

// Fungsi Generate QR
function generateQRCode($data, $logoPath)
{
    return \Endroid\QrCode\Builder\Builder::create()
        ->writer(new \Endroid\QrCode\Writer\PngWriter())
        ->data($data)
        ->encoding(new \Endroid\QrCode\Encoding\Encoding('UTF-8'))
        ->errorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High)
        ->size(200)
        ->margin(10)
        ->roundBlockSizeMode(\Endroid\QrCode\RoundBlockSizeMode::Margin)
        ->logoPath($logoPath)
        ->logoResizeToWidth(80)
        ->logoPunchoutBackground(true)
        ->build();
}

// Buat QR dan simpan
try {
    $qrCode = generateQRCode($qrData, $logoPath);

    $nama_pejabat_sanitized = preg_replace('/[^A-Za-z0-9 _-]/', '', $nama_pejabat);
    $nama_pejabat_sanitized = str_replace(' ', '-', $nama_pejabat_sanitized);
    $filename = "{$nomor_permohonan}_{$tanggal_ttd2}_{$nama_pejabat_sanitized}.png";

    $qrFullPath = $qrDirectory . $filename;
    $qrWebPath = "/uploads/qrcode/pejabat/$filename";

    $qrCode->saveToFile($qrFullPath);
} catch (Exception $e) {
    echo "<script>alert('QR Code gagal dibuat.'); window.history.back();</script>";
    exit();
}

// Simpan tanda tangan dan update status permohonan
try {
    $conn->begin_transaction();

    $stmt_signature = $conn->prepare("INSERT INTO signatures (permohonan_id, signature, pejabat_id, created_at) VALUES (?, ?, ?, ?)");
    $stmt_signature->bind_param("isis", $id_permohonan, $qrWebPath, $user_id, $tanggal_ttd);
    $stmt_signature->execute();

    $stmt_permohonan = $conn->prepare("UPDATE permohonan SET status2 = ?, $qr_column = ?, updated_at = NOW() WHERE id = ?");
    $stmt_permohonan->bind_param("isi", $new_status, $qrWebPath, $id_permohonan);
    $stmt_permohonan->execute();

    $conn->commit();
    echo "<script>alert('Dokumen berhasil ditandatangani oleh $nama_pejabat.'); window.history.back();</script>";
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Proses penandatanganan gagal! Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit();
}