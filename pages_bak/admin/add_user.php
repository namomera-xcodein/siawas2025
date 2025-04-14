<?php
$name = $nip_nik = $jabatan =$role = $level_user = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan sanitasi input
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $nip_nik = trim(htmlspecialchars($_POST['nip_nik'] ?? ''));
    $jabatan = trim(htmlspecialchars($_POST['jabatan'] ?? ''));
    $role = trim($_POST['role'] ?? '');
    $level_user = trim($_POST['level_user'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi jika email kosong
    if (empty($email)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Email tidak boleh kosong!'
                });
              </script>";
    } else {
        // Cek apakah NIP/NIK atau Email sudah digunakan
        $query = "SELECT * FROM users WHERE nip_nik = ? OR email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nip_nik, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'NIP/NIK atau Email sudah digunakan!'
                    });
                  </script>";
        } else {
            // Hash password dan atur waktu pembuatan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $created_at = date('Y-m-d H:i:s');

            // Insert data ke tabel users
            $query = "INSERT INTO users (name, nip_nik, jabatan, level_user, email, password, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssss", $name, $nip_nik, $jabatan, $level_user, $email, $hashed_password, $created_at);

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
                    window.location.href = 'index.php?page=set_user';
                });
                </script>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= htmlspecialchars($name) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nip_nik">NIP/NIK</label>
                        <input type="text" class="form-control" id="nip_nik" name="nip_nik"
                            value="<?= htmlspecialchars($nip_nik) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan"
                            value="<?= htmlspecialchars($jabatan) ?>" required>
                    </div>

                    <!-- Form Tambah User -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="default 123456" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown untuk Role -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <?php
                            $enumQuery = mysqli_query($conn, "SHOW COLUMNS FROM users WHERE Field = 'role'");
                            $row = mysqli_fetch_assoc($enumQuery);
                            $enumList = str_replace(["enum(", ")", "'"], "", $row['Type']);
                            $roles = explode(",", $enumList);

                            foreach ($roles as $role_option) {
                                $selected = ($role_option == $role) ? "selected" : "";
                                echo "<option value='$role_option' $selected>$role_option</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Dropdown untuk Level User -->
                    <div class="form-group">
                        <label for="level_user">Set User Sebagai:</label>
                        <select name="level_user" id="level_user" class="form-control" required
                            onchange="updateDeskripsiJabatan()">
                            <?php
                                $levelQuery = mysqli_query($conn, "SELECT * FROM level");
                                while ($level = mysqli_fetch_assoc($levelQuery)) {
                                    $selected = ($level['id_level'] == $level_user || (!isset($level_user) && $level['id_level'] == 1)) ? "selected" : "";
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