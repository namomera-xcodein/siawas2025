<?php
//require 'koneksi.php'; // Koneksi ke database

// Menginisialisasi variabel agar tidak null, mencegah error saat digunakan nanti
$nama_status = $deskripsi_status = ""; // Variabel untuk menyimpan input user dari form
$success_message = $error_message = ""; // Variabel untuk menampung pesan notifikasi
// Inisialisasi id_status default (jika tabel kosong)
$new_id_status = 1;

// Cek id_status terakhir di database
$query = "SELECT MAX(id_status) AS last_id FROM status_permohonan";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $last_id = $row['last_id'] ?? 0; // Jika NULL, set 0
    $new_id_status = $last_id + 1;
} else {
    $new_id_status = 1; // Jika query gagal, set default 1
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data input dari form dan membersihkan karakter berbahaya
    $nama_status = trim(htmlspecialchars($_POST['nama_status'] ?? ''));
    $deskripsi_status = trim(htmlspecialchars($_POST['deskripsi_status'] ?? ''));

    // Validasi: Pastikan nama_status tidak kosong
    if (empty($nama_status)) {
        $error_message = "Nama status tidak boleh kosong!";
    } else {
        // Cek apakah nama_status sudah ada di database untuk menghindari duplikasi
        $checkQuery = "SELECT * FROM status_permohonan WHERE nama_status = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $nama_status);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Nama status sudah digunakan!";
        } else {
            // Jika valid, masukkan data ke dalam database
            $insertQuery = "INSERT INTO status_permohonan (id_status, nama_status, deskripsi_status) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iss", $new_id_status, $nama_status, $deskripsi_status);

            if ($stmt->execute()) {
                $success_message = "Status berhasil ditambahkan!";
                // Perbarui id_status untuk form baru
                $new_id_status++;
            } else {
                $error_message = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambahkan Status</h4>

                <!-- SweetAlert Notifikasi -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <?php if (!empty($error_message)) : ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '<?= $error_message ?>'
                        });
                    </script>
                <?php elseif (!empty($success_message)) : ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '<?= $success_message ?>',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'index.php?page=set_status';
                        });
                    </script>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Id Status</label>
                        <input type="text" name="id_status" class="form-control" value="<?php echo $new_id_status ?>"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Status</label>
                        <input type="text" name="nama_status" class="form-control" required placeholder="Nama Status">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Status</label>
                        <input type="text" name="deskripsi_status" class="form-control" required
                            placeholder="Deskripsi Status">
                    </div>

                    <button type="submit" name="tambah_timja" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="index.php?page=set_status" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>