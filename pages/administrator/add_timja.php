<?php
// require 'koneksi.php'; // Pastikan koneksi database sudah disertakan

$queryKetua = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_timja'])) {
    $nama_tim = mysqli_real_escape_string($conn, $_POST['nama_tim']);
    $id_ketua_tim = mysqli_real_escape_string($conn, $_POST['id_ketua_tim']);

    // **Cek apakah nama_tim sudah ada dalam database**
    $cekTim = mysqli_query($conn, "SELECT * FROM tim_kerja WHERE nama_tim = '$nama_tim'");

    if (mysqli_num_rows($cekTim) > 0) {
        echo "<script>alert('Nama Tim sudah ada! Silakan gunakan nama lain.'); window.history.back();</script>";
    } else {
        // Jika tidak ada, lanjut insert
        $query = "INSERT INTO tim_kerja (nama_tim, id_ketua_tim) VALUES ('$nama_tim', '$id_ketua_tim')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Tim Kerja berhasil ditambahkan!'); window.location.href='index.php?page=set_timja';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan Tim Kerja!'); window.history.back();</script>";
        }
    }
}
?>


<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambahkan User</h4>

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
                            window.location.href = 'index.php?page=set_timja';
                        });
                    </script>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Nama Tim Kerja</label>
                        <input type="text" name="nama_tim" class="form-control" required
                            placeholder="Masukkan nama tim kerja">
                    </div>

                    <div class="form-group">
                        <label>Ketua Tim Kerja</label>
                        <select name="id_ketua_tim" class="form-control" required>
                            <option value="">Pilih Ketua Tim</option>
                            <?php while ($row = mysqli_fetch_assoc($queryKetua)) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <button type="submit" name="tambah_timja" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                    <a href="index.php?page=set_timja" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Deskripsi Jabatan -->
<script>
    function updateDeskripsiJabatan() {
        let levelSelect = document.getElementById("level_user");
        let deskripsiInput = document.getElementById("deskripsi_jabatan");
        let selectedOption = levelSelect.options[levelSelect.selectedIndex];

        deskripsiInput.value = selectedOption.getAttribute("data-deskripsi") || "";
    }
    document.addEventListener("DOMContentLoaded", updateDeskripsiJabatan);
</script>

<!-- Toggle Password Visibility -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var icon = this.querySelector('i');

        passwordField.type = passwordField.type === "password" ? "text" : "password";
        icon.classList.toggle("fa-eye-slash");
        icon.classList.toggle("fa-eye");
    });
</script>