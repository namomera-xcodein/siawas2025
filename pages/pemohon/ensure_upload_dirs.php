<?php
// Pastikan direktori upload tersedia

$baseUploadPath = realpath(__DIR__ . '/../../') . "/uploads/";

$uploadDirs = [
    'nota' => $baseUploadPath . 'nota/',
    'foto' => $baseUploadPath . 'foto/',
    'dok'  => $baseUploadPath . 'dok/',
];

foreach ($uploadDirs as $label => $dir) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            echo "<p style='color:red'>Gagal membuat folder uploads/$label. Periksa izin folder server Anda.</p>";
        }
    }
}