<?php
// Ambil level_jabatan dari tabel level
$query = $conn->prepare("SELECT level_jabatan FROM level WHERE id_level = ?");
$query->bind_param("i", $level_user);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $level_jabatan = $row['level_jabatan'];
} else {
    $level_jabatan = "Tidak diketahui"; // Jika ID tidak ditemukan
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
                    // echo " " . $_SESSION['name'] . "! Anda Login sebagai " . $_SESSION['role'];
                    echo " " . htmlspecialchars($_SESSION['name']) . "</br>! Anda Login sebagai " . htmlspecialchars($level_jabatan);
                    // htmlspecialchars digunakan untuk menghindari XSS (Cross-Site Scripting)
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.html">Setting Level Pejabat</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Aug 19</option>
                        <option value="1">July 19</option>
                        <option value="2">Jun 19</option>
                    </select>
                </div>
            </div> -->
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
                    <h4 class="card-title">Setting Level Jabatan</h4>
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
                        </div>
                    </div>
                    <div class="table-responsive table-striped">
                        <table class="table no-wrap v-middle mb-0">
                            <thead>
                                <tr class="border-0">
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">No.</th>
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">Nama Status
                                    </th>
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">Deskripsi</th>
                                    <th class="text-dark mb-0 font-16 font-weight-medium text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM level");
                                while ($data = mysqli_fetch_array($query)) {
                                ?>
                                    <tr>
                                        <td class="border-top-0 px-2 py-4  text-center">
                                            <?php echo $no++; ?></td>
                                        <td class="border-top-0 px-2 py-4 text-center">
                                            <?php echo $data['level_jabatan']; ?></td>
                                        <td class="border-top-0 px-2 py-4 text-wrap text-center">
                                            <?php echo $data['deskripsi_jabatan']; ?></td>

                                        <td class="border-top-0 px-2 py-4 text-center">
                                            <a href="index.php?page=edit_level&id_level=<?php echo urlencode($data['id_level']); ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="proses/proses_level.php?action=delete&id_level=<?php echo $data['id_level']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
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