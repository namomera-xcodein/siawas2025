<?php
// Pastikan koneksi database sudah ada
// Cek apakah 'id' ada di GET sebelum mengambil data
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('ID Status tidak ditemukan!');
            window.location.href = 'index.php?page=set_user';
          </script>";
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($conn, 
    "SELECT users.*, level.level_jabatan 
    FROM users 
    LEFT JOIN level ON users.level_user = level.id_level 
    WHERE users.id='$id'"
);
// kuery ok $query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_array($query);

// Jika data tidak ditemukan, beri peringatan
if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=set_user';
          </script>";
    exit();
}

// Jika form dikirim, proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $nip_nik = $_POST['nip_nik'];
    $jabatan = $_POST['jabatan'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $level_user = $_POST['level_user'];

    // var_dump($_POST);

    // exit();

    // Pastikan input sudah lolos validasi sebelum update ke database
    $updateQuery = "UPDATE users 
                    SET name='$name',nip_nik='$nip_nik', jabatan='$jabatan', password='$password', role='$role', level_user='$level_user' 
                    WHERE id='$id'";
    //$updateQuery = "UPDATE users SET name='$name', jabatan='$jabatan' , password='$password' , role='$role' WHERE id='$id'";

    // old script ok $updateQuery = "UPDATE users SET name='$name', jabatan='$jabatan' , password='$password' , role='$role' WHERE id='$id'";
    $update = mysqli_query($conn, $updateQuery);

    if ($update) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'index.php?page=set_user';
                    });
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan, coba lagi.',
                    });
                });
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
            <div class="col-7 align-self-center">
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
            </div>
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
                                        value="<?php echo $data['password']; ?>" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
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
                            </div>
                            <div class="form-group">
                                <label for="role">Level User</label>
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