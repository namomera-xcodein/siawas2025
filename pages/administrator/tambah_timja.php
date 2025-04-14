<?php
// include 'config.php'; // Pastikan file koneksi database di-include
// session_start();

// // Pastikan user sudah login
// if (!isset($_SESSION['id'])) {
//     header("Location: login.php");
//     exit();
// }

// Ambil data Ketua Tim dari database
$queryKetua = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tim Kerja</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Tambah Tim Kerja</h2>
        <div class="card">
            <div class="card-body">
                <form action="proses/proses_timja.php" method="POST">
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
                    <a href="set_timja.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>