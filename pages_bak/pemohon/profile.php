<?php

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Profile Pengguna</h4>
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


</body>

</html>