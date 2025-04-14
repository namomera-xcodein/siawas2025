<?php
// Pastikan koneksi database sudah ada
//require_once 'config.php'; // Sesuaikan dengan file konfigurasi koneksi Anda

// Cek apakah 'id_status' ada di GET sebelum mengambil data
if (!isset($_GET['id_status']) || empty($_GET['id_status'])) {
    echo "<script>
            alert('ID Status tidak ditemukan!');
            window.location.href = 'index.php?page=set_status';
          </script>";
    exit();
}

$id_status = $_GET['id_status'];
var_dump($id_status);

$query = mysqli_query($conn, "SELECT * FROM status_permohonan WHERE id_status='$id_status'");
$data = mysqli_fetch_array($query);

// Jika data tidak ditemukan, beri peringatan
if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=set_status';
          </script>";
    exit();
}

// Jika form dikirim, proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_status = mysqli_real_escape_string($conn, $_POST['id_status']);
    $nama_status = mysqli_real_escape_string($conn, $_POST['nama_status']);
    $deskripsi_status = mysqli_real_escape_string($conn, $_POST['deskripsi_status']);

    $updateQuery = "UPDATE status_permohonan SET nama_status='$nama_status', deskripsi_status='$deskripsi_status' WHERE id_status='$id_status'";
    $update = mysqli_query($conn, $updateQuery);

    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.php?page=set_status';
                });
              </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan: " . mysqli_error($conn) . "',
                });
              </script>";
    }
}
?>

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Status Permohonan</h4>
                        <form method="POST">
                            <input type="hidden" name="id_status" value="<?php echo $data['id_status']; ?>">
                            <div class="form-group">
                                <label for="nama_status">Nama Status</label>
                                <input type="text" class="form-control" id="nama_status" name="nama_status"
                                    value="<?php echo $data['nama_status']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi_status">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi_status" name="deskripsi_status" rows="3"
                                    required><?php echo $data['deskripsi_status']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php?page=set_status" class="btn btn-secondary">Kembali</a>
                        </form>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>