<?php
session_start();
include '../config/config.php';

require_once '../vendor/autoload.php'; // autoload Composer

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='../login.php';</script>";
    exit();
}

$user_id         = $_SESSION['user_id'];
$nama_pejabat    = $_SESSION['name'] ?? "Unknown";
$level_user      = $_SESSION['level_user'] ?? 0;
$jabatan_pejabat = $_SESSION['level_jabatan'] ?? "Unknown";
$nip_pejabat     = $_SESSION['nip_nik'] ?? "Unknown";
$tanggal_ttd     = date('Y-m-d H:i:s');
$tanggal_ttd2    = date('Y-m-d');

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

$id_permohonan = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id_permohonan) {
    echo "<script>alert('ID permohonan tidak ditemukan.'); window.history.back();</script>";
    exit();
}

// QR Code content
$qr_text = "Nama: $nama_pejabat\nNIP: $nip_pejabat\nJabatan: $jabatan_pejabat\nTanggal: $tanggal_ttd2";

// Buat QR code pakai Endroid
$qrCode = QrCode::create($qr_text)
    ->setSize(300)
    ->setMargin(10);

$writer = new PngWriter();
$qrImage = $writer->write($qrCode);

// Simpan ke folder qrcode
$folder_qr = '../uploads/qrcode/kpa2/';
if (!file_exists($folder_qr)) {
    mkdir($folder_qr, 0777, true);
}

$nama_file = $qr_column . '_' . $id_permohonan . '_' . time() . '.png';
$path_file = $folder_qr . $nama_file;
$path_db   = '/uploads/qrcode/kpa2/' . $nama_file; // path yang disimpan di database

// Simpan file PNG
file_put_contents($path_file, $qrImage->getString());

// Update ke DB
$sql = "UPDATE permohonan SET 
            status2 = ?, 
            $qr_column = ?, 
            ttd_{$level_user} = ?, 
            updated_at = NOW() 
        WHERE id_permohonan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issi", $new_status, $path_db, $tanggal_ttd, $id_permohonan);

if ($stmt->execute()) {
    echo "<script>alert('Permohonan berhasil ditandatangani oleh Level User: " . $level_user . "'); window.location='dashboard.php';</script>";
} else {
    echo "<script>alert('Gagal menyimpan data.'); window.history.back();</script>";
}