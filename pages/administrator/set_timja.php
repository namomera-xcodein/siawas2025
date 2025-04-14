<!-- SweetAlert and jQuery Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page wrapper -->
<div class="page-wrapper">
    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    <?php
                    $time = date('H');
                    $greeting = ($time >= 5 && $time <= 11) ? "Selamat Pagi" : (($time >= 12 && $time <= 15) ? "Selamat Siang" : (($time >= 16 && $time <= 18) ? "Selamat Sore" : "Selamat Malam"));
                    echo "$greeting {$_SESSION['name']}! ";
                    ?>
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="index.html">Setting Level Pejabat</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <!-- Card Group -->
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Setting Level Tim Kerja</h4>
                    <a href="index.php?page=add_timja" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Tim Kerja
                    </a>
                </div>
            </div>
        </div>

        <!-- Table Tim Kerja -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-striped">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0 text-center">
                                        <th>No.</th>
                                        <th>Nama Tim Kerja</th>
                                        <th>Ketua Tim Kerja</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $queryTim = mysqli_query(
                                        $conn,
                                        "SELECT t.id_tim_kerja, t.nama_tim, u.name AS ketua_tim 
                                         FROM tim_kerja t 
                                         LEFT JOIN users u ON t.id_ketua_tim = u.id"
                                    );

                                    // Delete Tim Kerja Handler
                                    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                                        $id_tim_kerja = $_GET['id'];

                                        if (!is_numeric($id_tim_kerja)) {
                                            echo "<script>
                                                Swal.fire('Error!', 'ID tidak valid!', 'error').then(() => {
                                                    window.location.href = 'index.php?page=set_timja';
                                                });
                                            </script>";
                                            exit();
                                        }

                                        $stmt = mysqli_prepare($conn, "SELECT * FROM tim_kerja WHERE id_tim_kerja=?");
                                        mysqli_stmt_bind_param($stmt, "i", $id_tim_kerja);
                                        mysqli_stmt_execute($stmt);
                                        $check = mysqli_stmt_get_result($stmt);

                                        if (mysqli_num_rows($check) > 0) {
                                            $delete_stmt = mysqli_prepare($conn, "DELETE FROM tim_kerja WHERE id_tim_kerja=?");
                                            mysqli_stmt_bind_param($delete_stmt, "i", $id_tim_kerja);
                                            if (mysqli_stmt_execute($delete_stmt)) {
                                                echo "<script>
                                                    Swal.fire('Berhasil!', 'Tim Kerja berhasil dihapus', 'success').then(() => {
                                                        window.location.href = 'index.php?page=set_timja';
                                                    });
                                                </script>";
                                                exit();
                                            } else {
                                                echo "<script>
                                                    Swal.fire('Error!', 'Gagal menghapus Tim Kerja!', 'error').then(() => {
                                                        window.location.href = 'index.php?page=set_timja';
                                                    });
                                                </script>";
                                                exit();
                                            }
                                        } else {
                                            echo "<script>
                                                Swal.fire('Error!', 'Tim Kerja tidak ditemukan!', 'error').then(() => {
                                                    window.location.href = 'index.php?page=set_timja';
                                                });
                                            </script>";
                                            exit();
                                        }
                                    }

                                    if (mysqli_num_rows($queryTim) > 0) {
                                        while ($data = mysqli_fetch_assoc($queryTim)) {
                                    ?>
                                    <tr class="text-center">
                                        <td><?php echo $no++; ?></td>
                                        <td class="text-left"><?php echo htmlspecialchars($data['nama_tim']); ?></td>
                                        <td><?php echo htmlspecialchars($data['ketua_tim'] ?? 'Belum Ditentukan'); ?>
                                        </td>
                                        <td>
                                            <a href="index.php?page=edit_timja&id_tim_kerja=<?= htmlspecialchars(urlencode($data['id_tim_kerja'])); ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="<?php echo $data['id_tim_kerja']; ?>">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data tim kerja.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus tim kerja ini? Perintah tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?page=set_timja&action=delete&id=' + id;
            }
        });
    });
});
</script>