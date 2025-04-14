<?php
// Include file konfigurasi dan autoload
include '../../config/config.php';
include '../../vendor/autoload.php'; // Pastikan autoload.php ada di path yang benar

// Memulai sesi
ob_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari sesi
$user_id = $_SESSION['user_id'] ?? 0;
$nama_pejabat = $_SESSION['name'] ?? "Unknown";  // Nama pejabat (Katimja, PPK, KPA)
$jabatan_pejabat = $_SESSION['jabatan'] ?? "Unknown"; // Jabatan pejabat (Katimja, PPK, KPA)
$nip_pejabat = $_SESSION['nip_nik'] ?? "Unknown"; // NIP pejabat (Katimja, PPK, KPA)
$tanggal_ttd = date('Y-m-d H:i:s'); // Tanggal tanda tangan
$tanggal_ttd2 = date('Y-m-d'); // format tanggal tanpa jam

// Mendapatkan id_permohonan dari URL atau POST
if (isset($_GET['id_permohonan'])) {
    $id_permohonan = $_GET['id_permohonan'];
} else {
    echo "<script>alert('ID permohonan tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

// Ambil data permohonan dari database berdasarkan id_permohonan
$sql = "SELECT * FROM permohonan WHERE id = ?";  // Menggunakan 'id' bukan 'id_permohonan'
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_permohonan);  // Pastikan variabel yang digunakan sudah sesuai
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $nomor_permohonan = $row['nomor_permohonan'];
    //$nama_pemohon = $row['nama_pemohon'];  // Nama pemohon dari database
    $status_permohonan = $row['status'];  // Status permohonan
} else {
    echo "<script>alert('Data permohonan tidak ditemukan!'); window.location.href='index.php';</script>";
    exit();
}

// Ambil data tanda tangan dari POST (base64 atau path file gambar)
$signature_data = $_POST['signature'] ?? ''; // Pastikan tanda tangan ada di POST, jika tidak berikan nilai default

// Generate QR Code menggunakan Endroid
$qrDirectory = realpath(__DIR__ . '/../../') . "/uploads/qrcode/katimja/";  // Direktori untuk menyimpan QR Code
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}

// Data untuk QR Code
$qrData = "Nomor: $nomor_permohonan\nKatimja: $nama_pejabat\nJabatan: $jabatan_pejabat\nNIP: $nip_pejabat\nTanggal: $tanggal_ttd2";  // Data untuk QR Code

$logoPath = realpath(__DIR__ . '/../../assets/kkp.png');
if (!$logoPath) {
    exit("Logo tidak ditemukan!");
}

// Fungsi untuk generate QR Code
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

// Generate QR Code untuk Penandatanganan
try {
    $qrCode = generateQRCode($qrData, $logoPath);

    $nama_pejabat_sanitized = preg_replace('/[^A-Za-z0-9 _-]/', '', $nama_pejabat); // filter karakter
    $nama_pejabat_sanitized = str_replace(' ', '-', $nama_pejabat_sanitized); // ubah spasi ke strip

    $filename = "{$nomor_permohonan}_{$tanggal_ttd2}_{$nama_pejabat_sanitized}.png";
    $qrFullPath = $qrDirectory . $filename;
    $qrWebPath = "/uploads/qrcode/katimja/$filename";

    $qrCode->saveToFile($qrFullPath);

    // Debug QRCode (preview sebelum disimpan) — hapus bagian ini jika tidak dibutuhkan
    // echo "<h3>Preview QR Code untuk tanda tangan:</h3>";
    // echo "<img src='$qrWebPath' width='200'>";
    // echo "<br><strong>Path File:</strong> $qrFullPath<br>";
    // exit();

} catch (Exception $e) {
    echo "<script>alert('QR Code gagal dibuat.'); window.history.back();</script>";
    exit();
}

// $qrPathDb = "/uploads/qrcode/katimja/{$nomor_permohonan}_{$tanggal_ttd2}_{$nama_pejabat_sanitized}.png";
// $qrPath = __DIR__ . $qrPathDb; // path absolut // digantikan oleh logika di atas

// Simpan tanda tangan ke tabel signatures dan update status permohonan
try {
    // Mulai transaksi
    $conn->begin_transaction();

    // Menyimpan tanda tangan ke tabel signatures
    $stmt_signature = $conn->prepare("INSERT INTO signatures (permohonan_id, signature, pejabat_id, created_at) VALUES (?, ?, ?, ?)");
    $stmt_signature->bind_param("isis", $id_permohonan, $qrWebPath, $user_id, $tanggal_ttd); // Menambahkan pejabat_id yang diambil dari session
    $stmt_signature->execute();


    // Update status pada tabel permohonan menjadi 1 (status 'Disetujui oleh KATIMJA')
    $new_status = 1;  // Status untuk disetujui oleh Katimja
    $stmt_permohonan = $conn->prepare("UPDATE permohonan SET status2 = ?, qr_code_pejabat1 = ? WHERE id = ?");
    $stmt_permohonan->bind_param("isi", $new_status, $qrWebPath, $id_permohonan);
    $stmt_permohonan->execute();
    // // Update status pada tabel permohonan menjadi 1 (status 'Disetujui oleh KATIMJA')
    // $new_status = 1;  // Status untuk disetujui oleh Katimja
    // $stmt_permohonan = $conn->prepare("UPDATE permohonan SET status2 = ?, tanggal_ttd = ?, qr_code_pejabat = ? WHERE id = ?");
    // $stmt_permohonan->bind_param("issi", $new_status, $tanggal_ttd, $qrWebPath, $id_permohonan);
    // $stmt_permohonan->execute();

    // Commit transaksi
    $conn->commit();

    // Beri notifikasi berhasil dan arahkan kembali
    echo "<script>alert('Dokumen berhasil ditandatangani oleh $nama_pejabat.'); window.location.href='index.php?page=riwayat_permohonan';</script>";
    exit();
} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    $conn->rollback();
    echo "<script>alert('Proses penandatanganan gagal! Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit();
}
