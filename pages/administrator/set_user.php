<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<!-- ============================================================== -->
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <h4 class="card-title">Setting Data User</h4>
                </div>
            </div>


        </div>


        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card border-right">
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Cari user...">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="searchTable()">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function searchTable() {
                                var input, filter, table, tr, td, i, txtValue;
                                input = document.getElementById("searchInput");
                                filter = input.value.toUpperCase();
                                table = document.querySelector(".table");
                                tr = table.getElementsByTagName("tr");

                                for (i = 1; i < tr.length; i++) {
                                    let found = false;
                                    td = tr[i].getElementsByTagName("td");

                                    for (let j = 0; j < td.length; j++) {
                                        if (td[j]) {
                                            txtValue = td[j].textContent || td[j].innerText;
                                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                                found = true;
                                                break;
                                            }
                                        }
                                    }

                                    if (found) {
                                        tr[i].style.display = "";
                                    } else {
                                        tr[i].style.display = "none";
                                    }
                                }
                            }

                            // Pagination
                            $(document).ready(function() {
                                $('#userTable').DataTable({
                                    "pageLength": 10,
                                    "searching": true,
                                    "ordering": true,
                                    "lengthChange": false,
                                    "language": {
                                        "paginate": {
                                            "next": "Selanjutnya",
                                            "previous": "Sebelumnya"
                                        },
                                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                                        "emptyTable": "Tidak ada data yang tersedia"
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">

                            <h5 class="card-title">Data User</h5>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-md btn-cyan"
                                        onclick="window.location.href='index.php?page=add_user'">
                                        <i class="fas fa-plus"></i> Tambah User Baru
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive table-striped">


                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                                <tr class="border-0">
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">No.</th>
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">Nama User
                                    </th>
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">NIP/NIK</th>
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">Jabatan</th>
                                    <!-- <th class="text-dark mb-0 font-16 font-weight-medium text-center">Password</th> -->
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_prepare($conn, "SELECT u.*, l.level_jabatan FROM users u LEFT JOIN level l ON u.level_user = l.id_level ");
                                mysqli_stmt_execute($query);
                                $result = mysqli_stmt_get_result($query);

                                // Proses Hapus User
                                if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                                    $id_user = $_GET['id'];

                                    // Pastikan ID hanya angka untuk keamanan
                                    if (!is_numeric($id_user)) {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'ID tidak valid!',
                                                    icon: 'error'
                                                }).then(() => {
                                                    window.location.href = 'index.php?page=set_user';
                                                });
                                                </script>";
                                        exit();
                                    }

                                    // Cek apakah user ada sebelum menghapus
                                    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id=?");
                                    mysqli_stmt_bind_param($stmt, "i", $id_user);
                                    mysqli_stmt_execute($stmt);
                                    $check = mysqli_stmt_get_result($stmt);

                                    if (mysqli_num_rows($check) > 0) {
                                        $delete_stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
                                        mysqli_stmt_bind_param($delete_stmt, "i", $id_user);
                                        if (mysqli_stmt_execute($delete_stmt)) {
                                            echo "<script>
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Berhasil!',
                                                            text: 'User berhasil dihapus',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                        }).then(() => {
                                                            window.location.href = 'index.php?page=set_user';
                                                        });
                                                    </script>";
                                            exit();
                                        } else {
                                            echo "<script>
                                                        Swal.fire({
                                                            title: 'Error!',
                                                            text: 'Gagal menghapus user!',
                                                            icon: 'error'
                                                        }).then(() => {
                                                            window.location.href = 'index.php?page=set_user';
                                                        });
                                                    </script>";
                                            exit();
                                        }
                                    } else {
                                        echo "<script>
                                                    Swal.fire({
                                                        title: 'Error!', 
                                                        text: 'User tidak ditemukan!',
                                                        icon: 'error'
                                                    }).then(() => {
                                                        window.location.href = 'index.php?page=set_user';
                                                    });
                                                </script>";
                                        exit();
                                    }
                                }
                                while ($data = mysqli_fetch_array($result)) {
                                ?> <tr>
                                        <td class="border-top-0 px-2 py-4  text-center">
                                            <?php echo $no++; ?>
                                        </td>
                                        <td class="border-top-0 px-2 py-4  text-center4">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="mr-3"><img src="<?= $base_url ?>/assets/kkp.png" alt="user"
                                                        class="rounded-circle" width="45" height="45" />
                                                </div>
                                                <div class="">
                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                        <?php echo $data['name']; ?>
                                                    </h5>
                                                    <span
                                                        class="text-muted font-14"><?php echo $data['level_user']; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">
                                            <?php echo $data['nip_nik']; ?></td>

                                        <td class="text-dark mb-0 font-14 font-weight-medium text-wrap">
                                            <?php echo $data['jabatan']; ?><br>
                                            <span class="text-muted font-14"><?php echo $data['level_jabatan']; ?></span>
                                        </td>



                                        <!-- <td class="border-top-0 text-muted px-2 py-4 font-14">
                                        <?php echo $data['password']; ?></td> -->

                                        <td class="border-top-0 px-2 py-4 text-center">
                                            <a href="index.php?page=edit_user&id=<?php echo urlencode($data['id']); ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <a href="index.php?page=set_user&action=delete&id=<?php echo $data['id']; ?>"
                                                class="btn btn-danger btn-sm btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>

                                            <!-- <a href="index.php?page=set_user&action=delete&id=<?php echo $data['id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                         <a href="index.php?page=delete&id=<?php echo $data['id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a> -->
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
    <!-- *************************************************************** -->
    <!-- End Top Leader Table -->
    <!-- *************************************************************** -->
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const deleteUrl = this.getAttribute('href');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus user ini? Perintah tidak dapat dibatalkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>