<?php
require '../vendor/autoload.php';

$secretKey = "KunciRahasiaSuperAman"; // Harus sama dengan di script QR Code

// Ambil data dari URL
$id = $_GET['id'] ?? '';
$token = $_GET['token'] ?? '';

// Generate ulang token berdasarkan ID permohonan
$expectedToken = hash_hmac('sha256', $id . "John Doe" . date('Y-m-d_H-i-s'), $secretKey);

// Cek apakah token valid
if ($token === $expectedToken) {
    echo "✅ QR Code Valid: Permohonan No. $id";
} else {
    echo "❌ QR Code Tidak Valid!";
}
?>