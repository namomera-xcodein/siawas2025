<?php
session_start();
include '../koneksi.php'; // pastikan koneksi di luar folder ini

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan";
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$level_user = $_SESSION['level_user'];

// Ambil data permohonan
$permohonan_query = mysqli_query($conn, "
    SELECT p.*, s.nama_status
    FROM permohonan p
    LEFT JOIN status_permohonan s ON p.status2 = s.id_status
    WHERE p.id = '$id'
");
$permohonan = mysqli_fetch_assoc($permohonan_query);

if (!$permohonan) {
    echo "Data permohonan tidak ditemukan.";
    exit;
}


// Hitung total status
$status_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM status_permohonan");
$status_data = mysqli_fetch_assoc($status_result);
$total_status = (int) $status_data['total'];

// Status sekarang dari permohonan
$current_status_id = (int) $permohonan['status2'] ?? 0;

// Hitung progres dalam %
$progress = 0;
if ($current_status_id > 0 && $total_status > 0) {
    $progress = ($current_status_id / $total_status) * 100;
}
