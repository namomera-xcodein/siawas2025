<!-- SweetAlert and jQuery Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page wrapper -->
<div class="page-wrapper">
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">

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
                    <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected><?php echo date('d F y') ?></option>
                        <!-- <option value="1">July 19</option>
                        <option value="2">Jun 19</option> -->
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Status Permohonan Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Data Status Permohonan</h4>
                            <div class="ml-auto">
                                <a href="index.php?page=add_status" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Tambah Status
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive table-striped">
                            <table class="table no-wrap v-middle mb-0">
                                <thead>
                                    <tr class="border-0">
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Nama Status</th>
                                        <th class="text-center">Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = mysqli_query($conn, "SELECT * FROM status_permohonan");
                                    while ($data = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center"><?php echo $data['nama_status']; ?></td>
                                        <td class="text-wrap"><?php echo $data['deskripsi_status']; ?></td>
                                        <td>
                                            <a href="index.php?page=edit_status&id_status=<?= htmlspecialchars(urlencode($data['id_status'])); ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="<?php echo $data['id_status']; ?>">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
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

<!-- Delete Handler Script -->
<?php
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id_status'])) {
    $id_status = $_GET['id_status'];

    if (!is_numeric($id_status)) {
        echo "<script>
            Swal.fire('Error!', 'ID tidak valid!', 'error').then(() => {
                window.location.href = 'index.php?page=set_status';
            });
        </script>";
        exit();
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM status_permohonan WHERE id_status=?");
    mysqli_stmt_bind_param($stmt, "i", $id_status);
    mysqli_stmt_execute($stmt);
    $check = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check) > 0) {
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM status_permohonan WHERE id_status=?");
        mysqli_stmt_bind_param($delete_stmt, "i", $id_status);
        if (mysqli_stmt_execute($delete_stmt)) {
            echo "<script>
                Swal.fire('Berhasil!', 'Status Permohonan berhasil dihapus', 'success').then(() => {
                    window.location.href = 'index.php?page=set_status';
                });
            </script>";
            exit();
        } else {
            echo "<script>
                Swal.fire('Error!', 'Gagal menghapus Status Permohonan!', 'error').then(() => {
                    window.location.href = 'index.php?page=set_status';
                });
            </script>";
            exit();
        }
    } else {
        echo "<script>
            Swal.fire('Error!', 'Status Permohonan tidak ditemukan!', 'error').then(() => {
                window.location.href = 'index.php?page=set_status';
            });
        </script>";
        exit();
    }
}
?>

<!-- Delete Confirmation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.btn-delete').click(function() {
        var id_status = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus status dengan ID: ' + id_status +
                '? Perintah tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?page=set_status&action=delete&id_status=' +
                    id_status;
            }
        });
    });
});
</script>