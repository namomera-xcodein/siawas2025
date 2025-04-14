<?php

$userId = $_SESSION['user_id'];
$query = "SELECT u1.*, u2.name as atasan_name FROM users u1 
          LEFT JOIN users u2 ON u1.atasan_id = u2.id 
          WHERE u1.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<!-- <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</head> -->

<bod>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
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
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-body">
                        <div class="card-title">
                            <h4>Profile Pengguna</h4>
                            <span class="text-muted">Silahkan lengkapi data diri Anda, jika menginginkan perubahan data
                                Atasan hubungi Administrator</span>
                        </div>
                        <div class="card-body">
                            <form action="update_profile.php" method="POST">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="<?php echo $user['name']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nip_nik" class="form-label">NIP/NIK</label>
                                    <input type="nip_nik" class="form-control" id="nip_nik" name="nip_nik"
                                        value="<?php echo $user['nip_nik']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nip_nik" class="form-label">Atasan</label>
                                    <input type="nip_nik" class="form-control" id="atasan_id" name="atasan_id" value="<?php 
                                                                        $sql_atasan = "SELECT name, jabatan FROM users WHERE id = " . $user['atasan_id'];
                                                                        $result_atasan = $conn->query($sql_atasan);
                                                                        $atasan = $result_atasan->fetch_assoc();
                                                                        echo $atasan['name'] . ' - ' . $atasan['jabatan']; 
                                                                    ?>" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan"
                                        value="<?php echo $user['jabatan']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo $user['email']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role"
                                        value="<?php echo $user['role']; ?>" required readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru (Kosongkan jika tidak ingin
                                        mengubah)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>