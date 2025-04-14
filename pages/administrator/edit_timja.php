<?php


// Ambil ID tim_kerja yang akan diedit dari parameter URL
$id_tim_kerja = $_GET['id_tim_kerja'];

// Query untuk mengambil data tim_kerja yang akan diedit
$query = mysqli_query($conn, "SELECT * FROM tim_kerja WHERE id_tim_kerja = '$id_tim_kerja'");
$data = mysqli_fetch_assoc($query);

// $id_user = $_GET['id_user']; // Ambil ID user yang sedang diedit
// Ambil id_ketua_tim dari data tim kerja sebagai id_user
$id_user = $data['id_ketua_tim'];


// Cek apakah user adalah Ketua Tim Kerja
$cekKetua = mysqli_query($conn, "SELECT id_tim_kerja FROM tim_kerja WHERE id_ketua_tim = '$id_user'");
$isKetua = mysqli_num_rows($cekKetua) > 0;

// Ambil daftar atasan yang merupakan Ketua Tim Kerja
$atasanQuery = mysqli_query($conn, "SELECT u.id, u.name 
                                    FROM users u 
                                    JOIN tim_kerja t ON u.id = t.id_ketua_tim 
                                    WHERE u.id != '$id_user'"); // Hindari Ketua Tim Kerja memilih dirinya sendiri

?>

<!-- ============================================================== -->
<!-- Page wrapper -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">

                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="#">Edit Tim Kerja</a></li>
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
                        <h4 class="card-title">Edit Data Tim Kerja</h4>
                        <form method="POST">
                            <input type="hidden" name="id_tim_kerja" value="<?= $data['id_tim_kerja']; ?>">

                            <div class="form-group text-wrap">
                                <label for="nama_tim">Nama Tim Kerja</label>
                                <input type="text" class="form-control" id="nama_tim" name="nama_tim"
                                    value="<?= $data['nama_tim']; ?>" required>
                            </div>


                            <div class="form-group">
                                <label for="atasan_id">Atasan Pejabat 1 (Ketua Tim Kerja)</label>
                                <select class="form-control select2" id="atasan_id" name="atasan_id" <!-- -->
                                    <option value="">-- Pilih Ketua Tim Kerja --</option>
                                    <?php
                                    while ($atasan = mysqli_fetch_assoc($atasanQuery)) {
                                        $selected = ($data['atasan_id'] == $atasan['id']) ? "selected" : "";
                                        echo "<option value='{$atasan['id']}' $selected>{$atasan['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <?php if ($isKetua): ?>

                                <?php endif; ?>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="index.php?page=set_timja" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </form>
                    </div>

                    <!-- Tambahkan FontAwesome untuk ikon -->
                    <link rel="stylesheet"
                        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Proses Update Data Tim Kerja
if (isset($_POST['submit'])) {
    $id_tim_kerja = $_POST['id_tim_kerja'];
    $nama_tim = $_POST['nama_tim'];
    $atasan_id = $_POST['atasan_id']; // ID Ketua Tim Kerja baru

    $updateQuery = "UPDATE tim_kerja SET 
                    nama_tim = '$nama_tim', 
                    id_ketua_tim = '$atasan_id' 
                    WHERE id_tim_kerja = '$id_tim_kerja'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data tim kerja berhasil diperbarui!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = 'index.php?page=set_timja';
                    }
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui data!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>