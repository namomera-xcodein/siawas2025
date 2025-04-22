<?php
$base_url = "http://localhost/sipatra2025";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tentukan waktu maksimum sesi dalam detik (3 jam = 10800 detik)
$session_lifetime = 10800;

// Periksa apakah sesi sudah ada
if (isset($_SESSION['last_activity'])) {
    // Hitung selisih waktu sekarang dengan last_activity
    if ((time() - $_SESSION['last_activity']) > $session_lifetime) {
        session_unset();  // Hapus semua data sesi
        session_destroy(); // Hancurkan sesi
        header("Location: $base_url/index.php"); // Redirect ke login dengan pesan expired
        exit();
    }
}

// Set timezone ke WIT (Waktu Indonesia Timur)
date_default_timezone_set('Asia/Jayapura');

// Perbarui last_activity setiap ada aktivitas pengguna
$_SESSION['last_activity'] = time();
$SITE_NAME  = ' SIPATRA – Sistem Pengawasan Akuntabilitas & Transparansi';
$SITE_TITLE = 'SIPATRA';
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "monitoring_belanja";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}



// Pastikan konstanta hanya didefisikan jika belum ada sebelumnya
if (!defined('SITE_TITLE')) {
    define('SITE_TITLE', 'SIAWAS');
}
// Pastikan konstanta hanya didefinisikan jika belum ada sebelumnya
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Si-AWAS – Sistem Pengawasan Akuntabilitas & Transparansi');
}

if (!defined('QRCODE_DIR')) {
    define('QRCODE_DIR', 'uploads/qrcode/');
}