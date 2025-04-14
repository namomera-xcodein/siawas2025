<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Cek apakah user sudah login
// if (!isset($_SESSION['user_id'])) {
//     header('Location: auth/login.php');
//     exit();
// }

include(__DIR__ . '/config/config.php'); // Pastikan koneksi database tersedia

if (!isset($_SESSION['user_id']) || !isset($_SESSION['level_user'])) {
    header('Location: auth/login.php'); // Redirect jika tidak login
    exit();
}

$user_id = $_SESSION['user_id'];
$level_user = (int) $_SESSION['level_user']; // Pastikan integer

$level_jabatan = "Tidak diketahui"; // Nilai default jika tidak ditemukan

// Ambil level_jabatan dari tabel level
$query = $conn->prepare("SELECT level_jabatan FROM level WHERE id_level = ?");
$query->bind_param("i", $level_user);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $level_jabatan = $row['level_jabatan'];
}
// Ambil data session
$user_id = $_SESSION['user_id'];
$level_user = isset($_SESSION['level_user']) ? (int) $_SESSION['level_user'] : 1;

include(__DIR__ . '/config/config.php');
include(__DIR__ . '/config/functions.php');
include(__DIR__ . '/vendor/autoload.php');

// Daftar level yang diperbolehkan
$allowed_levels = [1, 2, 3, 4, 5, 6, 7, 8];

if (!in_array($level_user, $allowed_levels)) {
    die("Akses tidak diizinkan!");
}

// Tentukan folder halaman berdasarkan level_user
$folder_pages = 'pemohon'; // Default
$sidebar_file = 'sidebar.php';

switch ($level_user) {
    case 8:
        $folder_pages = 'administrator';
        $sidebar_file = 'sidebar_admin.php';
        break;
    case 2:
    case 3:
    case 4:
    case 7:
        $folder_pages = "pejabat$level_user";
        $sidebar_file = 'sidebar_pejabat.php';
        break;
    case 5:
        $folder_pages = "pengawas";
        $sidebar_file = 'sidebar_pengawas.php';
        break;
    case 6:
        $folder_pages = "keuangan";
        $sidebar_file = 'sidebar_keuangan.php';
        break;
}

// Tentukan halaman default dan pastikan hanya karakter yang aman diambil
$page = isset($_GET['page']) ? basename($_GET['page']) : 'dashboard';

// Path file yang akan dimuat berdasarkan level_user
$file_path = __DIR__ . "/pages/$folder_pages/$page.php";
?>

<?php include(__DIR__ . '/includes/header.php'); ?>

<!-- Main Content -->
<main class="main-content">
    <div class="container mt-4">
        <?php
        if (file_exists($file_path)) {
            include($file_path);
        } else {
            echo "<h4>Halaman tidak ditemukan!</h4>";
        }
        ?>
    </div>
</main>

<?php
// Load sidebar sesuai level_user
$sidebar_path = __DIR__ . "/includes/$sidebar_file";

if (file_exists($sidebar_path)) {
    include($sidebar_path);
} else {
    echo "<h4>Sidebar tidak ditemukan!</h4>";
}
?>

<?php include(__DIR__ . '/includes/footer.php'); ?>