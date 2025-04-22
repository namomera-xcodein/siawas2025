<?php
// session_start();
// include '../koneksi.php';

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Cek apakah user sudah login dan level_user = 3 (PPK)
if (!isset($_SESSION['level_user']) || $_SESSION['level_user'] != 3) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Akses ditolak!',
            text: 'Anda tidak memiliki izin untuk melakukan aksi ini.'
        }).then(() => window.location.href = '../index.php?page=dashboard');
    </script>";
    exit;
}

// Validasi input
if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['mata_anggaran'])) {
    $id_permohonan = intval($_POST['id']);
    $mata_anggaran = trim($_POST['mata_anggaran']);

    // Escape string
    $mata_anggaran = $conn->real_escape_string($mata_anggaran);

    // Update ke database
    $sql = "UPDATE permohonan SET mata_anggaran = ?, updated_at = NOW() WHERE id_permohonan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $mata_anggaran, $id_permohonan);

    if ($stmt->execute()) {
        // Inject langsung variabel ID ke dalam JavaScript dengan echo
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Mata anggaran berhasil diperbarui.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'http://localhost/sipatra2025/index.php?page=detail_permohonan&id={$id_permohonan}';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memperbarui data.'
            }).then(() => {
                window.history.back();
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Input tidak valid!',
            text: 'Data yang dikirimkan tidak lengkap atau salah.'
        }).then(() => {
            window.location.href = '../index.php?page=dashboard';
        });
    </script>";
}