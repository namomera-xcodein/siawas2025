<?php
// Ambil data pejabat yang sudah disimpan sebelumnya
// $query = "SELECT * FROM pejabat_settings LIMIT 1";
// $result = mysqli_query($conn, $query);
// $pejabat = mysqli_fetch_assoc($result);

// Ambil daftar user untuk dropdown
$userQuery = "SELECT id, name FROM users";
$userResult = mysqli_query($conn, $userQuery);
$users = [];
while ($row = mysqli_fetch_assoc($userResult)) {
    $users[] = $row;
}

// require 'koneksi.php'; // Pastikan koneksi database tersedia

// Variabel untuk menampilkan pesan
$success_message = $error_message = "";

// Proses penyimpanan pejabat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pejabat2 = $_POST['pejabat2'] ?? '';
    $pejabat3 = $_POST['pejabat3'] ?? '';
    $pejabat_pengawas = $_POST['pejabat_pengawas'] ?? '';
    $pejabat_keuangan = $_POST['pejabat_keuangan'] ?? '';
    $pejabat1 = $_POST['pejabat1'] ?? '';

    if (!empty($pejabat2) && !empty($pejabat3) && !empty($pejabat_pengawas) && !empty($pejabat_keuangan) && !empty($pejabat1)) {
        $query = "INSERT INTO set_pejabat (pejabat2, pejabat3, pejabat_pengawas, pejabat_keuangan, pejabat1) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiii", $pejabat2, $pejabat3, $pejabat_pengawas, $pejabat_keuangan, $pejabat1);

        if ($stmt->execute()) {
            $success_message = "Data pejabat berhasil disimpan!";
        } else {
            $error_message = "Terjadi kesalahan saat menyimpan data!";
        }
    } else {
        $error_message = "Harap pilih semua pejabat sebelum menyimpan!";
    }
}

// Ambil daftar user untuk dropdown
$userQuery = "SELECT id, name FROM users";
$userResult = mysqli_query($conn, $userQuery);
$users = [];
while ($row = mysqli_fetch_assoc($userResult)) {
    $users[] = $row;
}


// Hapus pejabat
if (isset($_GET['hapus'])) {
    $id_set_pejabat = $_GET['hapus'];

    $deleteQuery = "DELETE FROM set_pejabat WHERE id_set_pejabat = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id_set_pejabat);

    if ($stmt->execute()) {
        $success_message = "Pejabat berhasil dihapus!";
    } else {
        $error_message = "Terjadi kesalahan saat menghapus data.";
    }
}

// Ambil daftar pejabat yang sudah ditetapkan
$query = "SELECT sp.id_set_pejabat, u.name, l.deskripsi_jabatan 
          FROM set_pejabat sp
          JOIN users u ON sp.id_user = u.id
          JOIN level l ON sp.id_level = l.id_level";
$pejabatList = $conn->query($query);

// Ambil daftar users dan level untuk form pilihan
$users = $conn->query("SELECT id AS id_user, name FROM users");
$levels = $conn->query("SELECT id_level, deskripsi_jabatan FROM level");

?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
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
                    echo " " . $_SESSION['name'] . "! <br>Anda Login sebagai " . $_SESSION['role'];
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard default</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">

                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- *************************************************************** -->
        <!-- Start First Cards -->
        <!-- *************************************************************** -->
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <!-- <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">236</h2>
                                <span
                                    class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+18.33%</span>
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">New Clients</h6>
                        </div> -->
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- *************************************************************** -->
        <!-- End First Cards -->
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Sales Charts Section -->
        <!-- *************************************************************** -->

        <!-- *************************************************************** -->

        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Pengaturan Pejabat Penandatangan</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                        <a class="dropdown-item" href="#">Insert</a>
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="pejabat2">Pilih Pejabat PPK</label>
                                <select name="pejabat2" id="pejabat2" class="form-control">
                                    <option value="">Pilih Pejabat PPK</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat2']) && $pejabat['pejabat2'] == $user['id_user']) ? 'selected' : '' ?>>
                                            <?= $user['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pejabat3">Pilih Pejabat KPA</label>
                                <select name="pejabat3" id="pejabat3" class="form-control">
                                    <option value="">Pilih Pejabat KPA</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat3']) && $pejabat['pejabat3'] == $user['id_user']) ? 'selected' : '' ?>>
                                            <?= $user['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pejabat_pengawas">Pilih Pejabat Pengawas</label>
                                <select name="pejabat_pengawas" id="pejabat_pengawas" class="form-control">
                                    <option value="">Pilih Pejabat Pengawas</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat_pengawas']) && $pejabat['pejabat_pengawas'] == $user['id_user']) ? 'selected' : '' ?>>
                                            <?= $user['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pejabat_keuangan">Pilih Pejabat Keuangan</label>
                                <select name="pejabat_keuangan" id="pejabat_keuangan" class="form-control">
                                    <option value="">Pilih Pejabat Keuangan</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat_keuangan']) && $pejabat['pejabat_keuangan'] == $user['id_user']) ? 'selected' : '' ?>>
                                            <?= $user['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pejabat1">Pilih Pejabat SPM</label>
                                <select name="pejabat1" id="pejabat1" class="form-control">
                                    <option value="">Pilih Pejabat SPM</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat1']) && $pejabat['pejabat1'] == $user['id_user']) ? 'selected' : '' ?>>
                                            <?= $user['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************************************** -->
    <!-- End Top Leader Table -->
    <!-- *************************************************************** -->
</div>