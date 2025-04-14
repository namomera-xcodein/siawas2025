promt perbaikan erro proses_ttd.php
masih ada error // Update status pada tabel permohonan (misalnya 'Ditandatangani oleh Katimja')
$new_status = 'Disetujui oleh KATIMJA';  // Status level yang sesuai dengan level pejabat saat ini
    $stmt_permohonan = $conn->prepare("UPDATE permohonan SET status2 = ?, tanggal_penandatanganan = ?, qr_code_pejabat = ? WHERE id = ?");
    $stmt_permohonan->bind_param("sssi", $new_status, $tanggal_ttd, $qrPathDb, $id_permohonan);
    $stmt_permohonan->execute(); seharusnya kan hanya update status2 pada tabel permohonan dengan angka 1 saja tidak ada tambahan laindan juga pada line ini // Rollback transaksi jika terjadi error 
    $conn->rollback();
    echo "<script>alert('Proses penandatanganan gagal! Error: " . addslashes($e->getMessage()) . "'); window.location.href='index.php?page=permohonan_new';</script>";
exit(); jangan direct ke permohonan_new kembali kan ke window.history.back();
