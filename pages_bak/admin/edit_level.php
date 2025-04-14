<?php
// Pastikan koneksi database sudah ada
// Cek apakah 'id' ada di GET sebelum mengambil data
if (!isset($_GET['id_level']) || empty($_GET['id_level'])) 
 {
    echo "<script>
            alert('ID Status tidak ditemukan!');
            window.location.href = 'index.php?page=setting_status';
          </script>";
    exit();
}

$id_level = $_GET['id_level'];
$query = mysqli_query($conn, "SELECT * FROM level WHERE id_level='$id_level'");
$data = mysqli_fetch_array($query);

// Jika data tidak ditemukan, beri peringatan
if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=setting_status';
          </script>";
    exit();
}

// Jika form dikirim, proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_level = $_POST['id_level'];
    $level_jabatan = $_POST['level_jabatan'];
    $deskripsi_jabatan = $_POST['deskripsi_jabatan'];

    $updateQuery = "UPDATE level SET level_jabatan='$level_jabatan', deskripsi_jabatan='$deskripsi_jabatan' WHERE id_level='$id_level'";
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
                        window.location.href = 'index.php?page=set_level';
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
                            <li class="breadcrumb-item"><a href="#">Edit Status</a></li>
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
                        <h4 class="card-title">Edit Status Permohonan</h4>
                        <form method="POST">
                            <input type="hidden" name="id_level" value="<?php echo $data['id_level']; ?>">

                            <div class="form-group">
                                <label for="level_jabatan">Nama Status</label>
                                <input type="text" class="form-control" id="level_jabatan" name="level_jabatan"
                                    value="<?php echo $data['level_jabatan']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi_jabatan">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi_jabatan" name="deskripsi_jabatan" rows="3"
                                    required><?php echo $data['deskripsi_jabatan']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php?page=setting_status" class="btn btn-secondary">Kembali</a>
                        </form>

                        <!-- Tambahkan CDN SweetAlert -->
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>