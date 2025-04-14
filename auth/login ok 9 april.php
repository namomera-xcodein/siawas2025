<?php
session_start();
require_once("../config/config.php");
include "../config/functions.php";



// Pastikan koneksi database tersedia
if (!isset($conn)) {
    die("Koneksi database tidak ditemukan!");
}

$error = '';

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip_nik = trim($_POST['nip_nik']);
    $password = trim($_POST['password']);

    // Validasi input kosong
    if (empty($nip_nik) || empty($password)) {
        $error = "NIP/NIK dan Password harus diisi!";
    } else {
        // Cek apakah NIP/NIK ada dalam database
        $query = "SELECT users.id, users.nip_nik, users.name, users.password, users.level_user, 
               level.level_jabatan, level.deskripsi_jabatan 
        FROM users 
        LEFT JOIN level ON users.level_user = level.id_level 
        WHERE users.nip_nik = ? LIMIT 1";
        //OK $query = "SELECT id, name, nip_nik, password, level_user, role FROM users WHERE nip_nik = ?";
        //OK $query = "SELECT id, name, nip_nik, password, role FROM users WHERE nip_nik = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nip_nik);
        $stmt->execute();
        $result = $stmt->get_result();

        // Inisialisasi variabel agar tidak undefined
        $user = null;

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nip_nik'] = $user['nip_nik'];
                $_SESSION['name'] = $user['name'];
                //$_SESSION['role'] = $user['role'];
                $_SESSION['level_user'] = (int) $user['level_user']; // Tetap menyimpan level_user dari tabel users
                $_SESSION['level_jabatan'] = $user['level_jabatan']; // Simpan level_jabatan dari tabel level
                $_SESSION['deskripsi_jabatan'] = $user['deskripsi_jabatan']; // Simpan deskripsi jabatan
                // Regenerate session ID untuk keamanan
                session_regenerate_id(true);

                // var_dump($_SESSION); // Cek semua session setelah login
                // exit();
                $redirect_page = $role_redirects[$user['level_user']] ?? "index.php?page=dashboard";

                // echo "<pre>";
                // print_r($_SESSION); // atau var_dump($_SESSION);
                // echo "Redirecting ke: $redirect_page";
                // die();

                //pertahankan ini
                // Log aktivitas jika fungsi tersedia

                if (function_exists('logActivity')) {
                    logActivity($conn, $user['id'], "User login ke sistem.");
                }
                $role_redirects = [
                    1 => "pemohon/dashboard",
                    2 => "katimja/dashboard",
                    3 => "ppk/dashboard",
                    4 => "kpa/dashboard",
                    5 => "pengawas/dashboard",
                    6 => "keuangan/dashboard",
                    7 => "admin/dashboard",
                    8 => "spm/dashboard",
                ];

                $page = $role_redirects[$user['level_user']] ?? "default/dashboard";

                header("Location: {$base_url}/index.php?page={$page}");

                echo "<pre>";
                print_r($_SESSION);
                echo "Redirecting ke: index.php?page=$page";
                exit;



                // $role_redirects = [
                //     1 => "index.php?page=dashboard",       // Pemohon
                //     2 => "index.php?page=dashboard",        // Pejabat 1 (KATIMJA)
                //     3 => "index.php?page=dashboard",        // Pejabat 2 (PPK)
                //     4 => "index.php?page=dashboard",        // Pejabat 3 (KPA)
                //     5 => "index.php?page=dashboard",        // Pengawas
                //     6 => "index.php?page=dashboard",        // Keuangan
                //     7 => "index.php?page=dashboard",       // Administrator
                //     8 => "index.php?page=dashboard",            // Pejabat SPM
                // ];
                //pertahankan ini
                // Cek apakah level_user memiliki redirect yang sesuai
                $redirect_page = $role_redirects[$user['level_user']] ?? "index.php?page=dashboard";

                // Redirect ke halaman sesuai role
                header("Location: {$base_url}/" . $redirect_page);
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "NIP/NIK tidak ditemukan!";
        }
    }
}
?>


<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>SIPATRA – Sistem Pengawasan Akuntabilitas & Transparansi</title>
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(../assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img"
                    style="background-image: url(../assets/images/big/sipatra.png);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="../assets/images/big/icon.png" alt="wrapkit">
                        </div>
                        <h2 class="mt-3 text-center">Sign In</h2>
                        <p class="text-center">Silahkan Gunakan NIK/NIP Anda.</p>
                        <form class="mt-4" method="POST">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">NIK/NIP</label>
                                        <input class="form-control" id="nip_nik" name="nip_nik" type="text"
                                            placeholder="enter your NIP_NIK" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Password</label>
                                        <input class="form-control" id="password" name="password" type="password"
                                            placeholder="enter your password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark">Sign In</button>
                                </div>
                                <div class="col-lg-12 text-center mt-5">
                                    Don't have an account? <a href="#" class="text-danger">Sign Up</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="../assets/libs/jquery/dist/jquery.min.js "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $(".preloader").fadeOut();

    <?php if (!empty($error)): ?>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>',
        confirmButtonColor: '#3085d6'
    });
    <?php endif; ?>
    </script>

</body>


</html>