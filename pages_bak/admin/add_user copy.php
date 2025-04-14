<?php
//session_start();
//include 'config.php'; // Pastikan ini sesuai dengan file koneksi database Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan sanitasi input
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $nip_nik = trim(htmlspecialchars($_POST['nip_nik'] ?? ''));
    $jabatan = trim(htmlspecialchars($_POST['jabatan'] ?? ''));
    $level_user = trim($_POST['level_user'] ?? '');
    $password = $_POST['password'] ?? '';

    // Cek apakah NIP/NIK sudah digunakan
    $query = "SELECT * FROM users WHERE nip_nik = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nip_nik);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'NIP/NIK sudah digunakan!'
                });
              </script>";
    } else {
        // Hash password dan atur waktu pembuatan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        // Insert data ke tabel users
        $query = "INSERT INTO users (name, nip_nik, jabatan, level_user, password, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $name, $nip_nik, $jabatan, $level_user, $hashed_password, $created_at);

        if ($stmt->execute()) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'User berhasil ditambahkan!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'index.php?page=set_user';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat registrasi.'
                    });
                  </script>";
        }
    }
}
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambahkan User</h4>
                <form method="POST">

                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="form-group">
                        <label for="nip_nik">NIP/NIK</label>
                        <input type="text" class="form-control" id="nip_nik" name="nip_nik" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown baru untuk Role -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <?php
                            // Ambil daftar ENUM dari tabel users
                            $enumQuery = mysqli_query($conn, "SHOW COLUMNS FROM users WHERE Field = 'role'");
                            $row = mysqli_fetch_assoc($enumQuery);
                            $enumList = str_replace(["enum(", ")", "'"], "", $row['Type']);
                            $roles = explode(",", $enumList);

                            foreach ($roles as $role) {
                                $selected = ($role == "Pemohon") ? "selected" : "";
                                echo "<option value='$role' $selected>$role</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level_user">Set User Sebagai:</label>
                        <select name="level_user" id="level_user" class="form-control" required
                            onchange="updateDeskripsiJabatan()">
                            <?php
                                $levelQuery = mysqli_query($conn, "SELECT * FROM level");
                                while ($level = mysqli_fetch_assoc($levelQuery)) {
                                    // Set default ke id_level 1 (Pemohon)
                                    $selected = ($level['id_level'] == 1 || (!isset($data['level_user']) && $level['id_level'] == 1)) ? "selected" : "";
                                    echo "<option value='{$level['id_level']}' data-deskripsi='{$level['deskripsi_jabatan']}' $selected>{$level['level_jabatan']}</option>";
                                }
                                ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_jabatan">*Deskripsi dari set User Sebagai</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="deskripsi_jabatan" name="deskripsi_jabatan"
                                value="" readonly>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php?page=set_user" class="btn btn-secondary">Gak Jadi!</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan CDN SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function updateDeskripsiJabatan() {
    let levelSelect = document.getElementById("level_user");
    let deskripsiInput = document.getElementById("deskripsi_jabatan");
    let selectedOption = levelSelect.options[levelSelect.selectedIndex];

    if (selectedOption.value) {
        deskripsiInput.value = selectedOption.getAttribute("data-deskripsi");
    } else {
        deskripsiInput.value = "";
    }
}

// Panggil fungsi ini saat halaman dimuat untuk mengisi deskripsi sesuai pilihan yang ada
document.addEventListener("DOMContentLoaded", function() {
    updateDeskripsiJabatan();
});
</script>
<!-- Tambahkan script untuk toggle password visibility -->
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    var passwordField = document.getElementById('password');
    var icon = this.querySelector('i');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
});
</script>
<!-- Tambahkan FontAwesome untuk ikon mata -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">