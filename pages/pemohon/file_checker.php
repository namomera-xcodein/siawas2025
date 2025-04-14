<?php
require '../../vendor/autoload.php'; // Sesuaikan dengan lokasi vendor jika berbeda

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;

// Pastikan direktori penyimpanan QR Code ada
$qrDirectory = "../../uploads/qrcode/";
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}

// Data untuk QR Code
$nomor_permohonan = "PB-20250318-001";
$nama_pemohon = "John Doe";
$tanggal_permohonan = date('Y-m-d_H-i-s');

// Cek keberadaan file logo
$logoPath = realpath(__DIR__ . '/../../assets/kkp.png');
if (!$logoPath) {
    die("❌ Logo tidak ditemukan!");
}

// Nama file QR Code
$qrFileName = "{$nomor_permohonan}_{$tanggal_permohonan}_{$nama_pemohon}.png";
$qrPath = $qrDirectory . $qrFileName;

// Generate QR Code
$result = Builder::create()
    ->writer(new PngWriter())
    ->data("Nomor: $nomor_permohonan\nPemohon: $nama_pemohon\nTanggal: $tanggal_permohonan")
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(ErrorCorrectionLevel::High)
    ->size(300)
    ->margin(10)
    ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
    ->logoPath($logoPath)
    ->logoResizeToWidth(80)
    ->logoPunchoutBackground(true)
    ->labelText('e-Sign SiPatra')
    ->labelFont(new OpenSans(20))
    ->labelAlignment(LabelAlignment::Center)
    ->build();

// Simpan QR Code ke file
$result->saveToFile($qrPath);

// Tampilkan hasil di browser
header('Content-Type: image/png');
echo $result->getString();