<?php
                                        $no = 1;
                                        $query = mysqli_prepare($conn, "SELECT u.*, l.level_jabatan FROM users u LEFT JOIN level l ON u.level_user = l.id_level");
                                        mysqli_stmt_execute($query);
                                        $result = mysqli_stmt_get_result($query);

                                        if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Pastikan koneksi ke database sudah dibuat
    require 'koneksi.php'; 

    // Cek apakah user dengan ID tersebut ada di database
    $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check_stmt, "i", $id_user);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        // Jika user ditemukan, hapus dari database
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
        mysqli_stmt_bind_param($delete_stmt, "i", $id_user);
        
        if (mysqli_stmt_execute($delete_stmt)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'User berhasil dihapus.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'index.php?page=set_user';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal menghapus user!',
                        icon: 'error'
                    });
                  </script>";
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
    }
}
                                        while ($data = mysqli_fetch_array($result)) {
                                // while ($data = mysqli_fetch_array($query)) {
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
                                echo " " . $_SESSION['name'] . "! Anda Login sebagai " . $_SESSION['role'];
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
                <!-- <div class="customize-input float-right">
                    <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Aug 19</option>
                        <option value="1">July 19</option>
                        <option value="2">Jun 19</option>
                    </select>
                </div> -->
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
            <!--  -->


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

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                                <tr class="border-0">
                                    <th class="border-0 font-14 font-weight-medium text-muted px-2">No.
                                    </th>
                                    <th class="border-0 font-14 font-weight-medium text-muted text-center">User
                                        <!-- <span class="text-muted font-14">Role</span> -->
                                    </th>
                                    <th class="border-0 font-14 font-weight-medium text-muted px-2">NIP/NIK
                                    </th>
                                    <!-- <th class="border-0 font-14 font-weight-medium text-muted">S Permohonan</th> -->
                                    <th class="border-0 font-14 font-weight-medium text-muted text-center">Jabatan
                                    </th>
                                    <!-- <th class="border-0 font-14 font-weight-medium text-muted">Password</th> -->
                                    <th class="border-0 font-14 font-weight-medium text-muted text-center">Aksi
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $query = mysqli_query($conn, "SELECT * FROM users");
                                    while ($data = mysqli_fetch_array($query)) {
                                ?>
                                <tr>
                                    <td class="border-top-0 text-muted px-2 py-4 font-14">
                                        <?php echo $no++; ?>
                                    </td>
                                    <td class="border-top-0 px-2 py-4">
                                        <div class="d-flex no-block align-items-center">
                                            <div class="mr-3"><img src="<?= $base_url ?>/assets/kkp.png" alt="user"
                                                    class="rounded-circle" width="45" height="45" />
                                            </div>
                                            <div class="">
                                                <h5 class="text-dark mb-0 font-16 font-weight-medium">
                                                    <?php echo $data['name']; ?>
                                                </h5>
                                                <span class="text-muted font-14"><?php echo $data['role']; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-top-0 text-muted px-2 py-4 font-14">
                                        <?php echo $data['nip_nik']; ?></td>

                                    <!-- <td class="border-top-0 text-center px-2 py-4"><i
                                                class="fa fa-circle text-primary font-12" data-toggle="tooltip"
                                                data-placement="top" title="In Testing"></i></td> -->
                                    <td class="border-top-0 text-muted px-2 py-4 font-14">
                                        <?php echo $data['jabatan']; ?>
                                    </td>
                                    <!-- <td class="border-top-0 text-muted px-2 py-4 font-14">
                                            <?php echo $data['password']; ?></td> -->
                                    <td class="border-top-0 px-2 py-4 text-center">
                                        <a href="index.php?page=edit_user&id=<?php echo $data['id']; ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>



                                        <button class="btn btn-danger btn-sm delete-user"
                                            data-id="<?= $data['id']; ?>">🗑 Hapus</button>
                                    </td>
                                </tr>


                            </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************************************** -->
    <!-- End Top Leader Table -->
    <!-- *************************************************************** -->