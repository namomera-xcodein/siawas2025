<?php
//require 'koneksi.php'; // Pastikan koneksi database di-include

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>
            alert('ID Status tidak valid!');
            window.location.href = 'index.php?page=set_user';
          </script>";
    exit();
}

$id = (int) $_GET['id']; // Pastikan ID adalah angka

// Gunakan prepared statement untuk keamanan
$stmt = $conn->prepare("SELECT users.*, level.level_jabatan 
                        FROM users 
                        LEFT JOIN level ON users.level_user = level.id_level 
                        WHERE users.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=set_user';
          </script>";
    exit();
}

// Proses update jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST['id'];
    $name = trim($_POST['name']);
    $nip_nik = trim($_POST['nip_nik']);
    $jabatan = trim($_POST['jabatan']);
    $role = trim($_POST['role']);
    $level_user = (int) $_POST['level_user'];
    $atasan_id = !empty($_POST['atasan_id']) ? (int) $_POST['atasan_id'] : null;
    $password = $_POST['password'] ?? '';

    // Hash password jika diisi
    $password_sql = "";
    $params = [$name, $nip_nik, $jabatan, $atasan_id, $role, $level_user, $id];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $password_sql = ", password=?";
        array_splice($params, -1, 0, $hashed_password);
    }

    // Query update dengan prepared statement
    $updateQuery = "UPDATE users 
                    SET name=?, nip_nik=?, jabatan=?, atasan_id=?, role=?, level_user=? $password_sql 
                    WHERE id=?";

    $stmt = $conn->prepare($updateQuery);
    $types = str_repeat("s", count($params) - 1) . "i"; // String kecuali ID terakhir (integer)
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'index.php?page=set_user';
                    });
                };
              </script>";
    } else {
        echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan, coba lagi.',
                    });
                };
              </script>";
    }
}
?>

<!-- ============================================================== -->
<!-- Page wrapper -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <!-- <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    <?php
                    $time = date('H');
                    if ($time >= 5 && $time <= 11) {
                        echo "Selamat Pagi";
                    } elseif ($time >= 12 && $time <= 15) {
                        echo "Selamat Siang";
                    } elseif ($time >= 16 && $time <= 18) {
                        echo "Selamat Sore";
                    } else {
                        echo "Selamat Malam";
                    }
                    echo " " . $_SESSION['name'] . "! Anda Login sebagai " . $_SESSION['role'];
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="#">Edit User</a></li>
                        </ol>
                    </nav>
                </div>
            </div> -->
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Container fluid -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Data User</h4>
                        <form method="POST">

                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            <div class="form-group">
                                <label for="name">Nama User</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo $data['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan"
                                    value="<?php echo $data['jabatan']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nip_nik">NIP/NIK</label>
                                <input type="text" class="form-control" id="nip_nik" name="nip_nik"
                                    value="<?php echo $data['nip_nik']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Kosongkan jika tidak ingin mengubah password">

                                    <!-- <input type="password" class="form-control" id="password" name="password"
                                        value="<?php echo $data['password']; ?>" required> -->
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Tambahkan CSS & JS Select2 -->
                            <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css"
                                rel="stylesheet" />
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

                            <div class="form-group">
                                <label for="atasan_id">Atasan Pejabat 1 (Ketua Tim Kerja)</label><br>
                                <small class="text-danger">Jika User ini adalah Ketua Tim Kerja, tidak perlu memilih
                                    atasan.</small>
                                <select class="form-control select2" id="atasan_id" name="atasan_id">
                                    <option value="">-- Pilih Atasan --</option>
                                    <?php
                                    $atasanQuery = mysqli_query($conn, "SELECT t.id_ketua_tim, u.name 
                                                FROM tim_kerja t 
                                                JOIN users u ON t.id_ketua_tim = u.id");
                                    while ($atasan = mysqli_fetch_assoc($atasanQuery)) {
                                        $selected = ($data['atasan_id'] == $atasan['id_ketua_tim']) ? "selected" : "";
                                        echo "<option value='{$atasan['id_ketua_tim']}' $selected>{$atasan['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <?php
                                    // Ambil daftar enum role dari tabel users
                                    $enumQuery = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
                                    $enumRow = mysqli_fetch_array($enumQuery);
                                    preg_match("/^enum\((.*)\)$/", $enumRow['Type'], $matches);
                                    $enumValues = str_getcsv($matches[1], ",", "'");

                                    foreach ($enumValues as $roleOption) {
                                        $selected = ($data['role'] == $roleOption) ? "selected" : "";
                                        echo "<option value=\"$roleOption\" $selected>$roleOption</option>";
                                    }
                                    ?>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label for="role">Level User</label>
                                <small class="text-danger">Pilih dan Sesuaikan dengan deskripsi dibawah.</small>
                                <select name="level_user" id="level_user" class="form-control"
                                    onchange="updateDeskripsiJabatan()">
                                    <?php
                                    $levelQuery = mysqli_query($conn, "SELECT * FROM level");
                                    $levelData = [];
                                    while ($level = mysqli_fetch_assoc($levelQuery)) {
                                        $selected = ($data['level_user'] == $level['id_level']) ? "selected" : "";
                                        echo "<option value='{$level['id_level']}' data-deskripsi='{$level['deskripsi_jabatan']}' $selected>{$level['level_jabatan']}</option>";
                                        $levelData[$level['id_level']] = $level['deskripsi_jabatan'];
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi_jabatan">Deskripsi Jabatan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="deskripsi_jabatan"
                                        name="deskripsi_jabatan"
                                        value="<?php echo isset($levelData[$data['level_user']]) ? $levelData[$data['level_user']] : ''; ?>"
                                        readonly>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php?page=set_user" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                    <!-- Tambahkan CDN SweetAlert -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <!-- Tambahkan script untuk toggle password visibility -->
                    <script>
                    function updateDeskripsiJabatan() {
                        let levelSelect = document.getElementById("level_user");
                        let deskripsiInput = document.getElementById("deskripsi_jabatan");
                        let selectedOption = levelSelect.options[levelSelect.selectedIndex];

                        deskripsiInput.value = selectedOption.getAttribute("data-deskripsi");
                    }
                    </script>
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
                    <link rel="stylesheet"
                        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

                </div>
            </div>
        </div>
    </div>
</div>