<?php
require 'config/config.php'; // koneksi ke database

$success_message = "";
$error_message = "";

// Mapping nama input ke id_level sesuai tabel level
$jabatan_map = [
    'pejabat_ppk' => 3,
    'pejabat_kpa' => 4,
    'pejabat_pengawas' => 5,
    'pejabat_keuangan' => 6,
    'pejabat_spm' => 7,
];

// Proses simpan/update jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($jabatan_map as $field_name => $id_level) {
        $id_user = $_POST[$field_name] ?? '';

        if (!empty($id_user)) {
            // Cek apakah level ini sudah ada di tabel
            $cek = $conn->prepare("SELECT id_set_pejabat FROM set_pejabat WHERE id_level = ?");
            $cek->bind_param("i", $id_level);
            $cek->execute();
            $cek_result = $cek->get_result();

            if ($cek_result->num_rows > 0) {
                // Update pejabat
                $update = $conn->prepare("UPDATE set_pejabat SET id_user = ? WHERE id_level = ?");
                $update->bind_param("ii", $id_user, $id_level);
                $update->execute();
            } else {
                // Insert pejabat baru
                $insert = $conn->prepare("INSERT INTO set_pejabat (id_user, id_level) VALUES (?, ?)");
                $insert->bind_param("ii", $id_user, $id_level);
                $insert->execute();
            }
        }
    }
    $success_message = "Data pejabat berhasil disimpan.";
}

// Ambil data users untuk dropdown
$users = [];
$user_result = $conn->query("SELECT id AS id_user, name FROM users ORDER BY name ASC");
while ($row = $user_result->fetch_assoc()) {
    $users[] = $row;
}

// Ambil data pejabat yang sudah disetel untuk ditampilkan sebagai selected
$pejabat = [];
$set_pejabat = $conn->query("SELECT id_user, id_level FROM set_pejabat");
while ($row = $set_pejabat->fetch_assoc()) {
    foreach ($jabatan_map as $field_name => $id_level) {
        if ($row['id_level'] == $id_level) {
            $pejabat[$field_name] = $row['id_user'];
        }
    }
}
?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row"></div>
        <br><br>

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
                    echo " " . $_SESSION['name'] . "!";
                    //. "! Anda Login sebagai " . $_SESSION['role'];
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="index.php?page=dashboard">
                                    <?php
                                    $time = date('H');
                                    if ($time >= 5 && $time <= 11) {
                                        echo "Hari Baru Mulai, Bismillah!";
                                    } elseif ($time >= 12 && $time <= 15) {
                                        echo "Siang Bos, Jangan Lupa Makan Siang!";
                                    } elseif ($time >= 16 && $time <= 18) {
                                        echo "Udah Sore, Belum Pulang nih?";
                                    } else {
                                        echo "Cie..Lembur ni...";
                                    }

                                    //. "! Anda Login sebagai " . $_SESSION['role'];
                                    echo "<br>Saat ini Anda Login sebagai " . htmlspecialchars($level_jabatan);

                                    ?></a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <!-- <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Aug 19</option>
                        <option value="1">July 19</option>
                        <option value="2">Jun 19</option>
                    </select> -->
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
                                <label for="pejabat_ppk">Pilih Pejabat PPK</label>
                                <select name="pejabat_ppk" id="pejabat_ppk" class="form-control">
                                    <option value="">Pilih Pejabat PPK</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat_ppk']) && $pejabat['pejabat_ppk'] == $user['id_user']) ? 'selected' : '' ?>>
                                            <?= $user['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pejabat_kpa">Pilih Pejabat KPA</label>
                                <select name="pejabat_kpa" id="pejabat_kpa" class="form-control">
                                    <option value="">Pilih Pejabat KPA</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat_kpa']) && $pejabat['pejabat_kpa'] == $user['id_user']) ? 'selected' : '' ?>>
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
                                <label for="pejabat_spm">Pilih Pejabat SPM</label>
                                <select name="pejabat_spm" id="pejabat_spm" class="form-control">
                                    <option value="">Pilih Pejabat SPM</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id_user'] ?>"
                                            <?= (isset($pejabat['pejabat_spm']) && $pejabat['pejabat_spm'] == $user['id_user']) ? 'selected' : '' ?>>
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