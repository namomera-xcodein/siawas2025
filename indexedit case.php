<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php'); // Redirect ke halaman login jika belum login
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session
$level_user = isset($_SESSION['level_user']) ? (int) $_SESSION['level_user'] : 1; // Pastikan dalam bentuk integer

include(__DIR__ . '/config/config.php');
include(__DIR__ . '/config/functions.php');
include(__DIR__ . '/vendor/autoload.php');

// Debugging: Periksa apakah session level_user benar
// echo "Level User: " . $level_user; exit();

// Daftar level yang diizinkan dalam sistem
$allowed_levels = [1, 2, 3, 4, 5, 6, 7, 8]; // 1 = Pemohon, 2 = Pejabat 1, 3 = Pejabat 2, dst.

if (!in_array($level_user, $allowed_levels)) {
    die("Akses tidak diizinkan!");
}

// Tentukan folder berdasarkan level_user
switch ($level_user) {
    case 8:
        $folder_pages = 'administrator';
        $sidebar_file = 'sidebar_admin.php';
        break;
    case 2:
    case 3:
    case 4:
    case 7:
        $folder_pages = "pejabat$level_user"; // Pejabat sesuai level_user
        $sidebar_file = 'sidebar_pejabat.php';
        break;
    case 5:
        $folder_pages = "pejabat$level_user"; // Pejabat sesuai level_user
        $sidebar_file = 'sidebar_pengawas.php';
        break;
    case 6:
        $folder_pages = "pejabat$level_user"; // Pejabat sesuai level_user
        $sidebar_file = 'sidebar_keuangan.php';
        break;
    case 1:
    default:
        $folder_pages = 'pemohon';
        $sidebar_file = 'sidebar.php';
        break;
}

// Tentukan halaman default
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

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
// Debugging: Periksa apakah sidebar benar-benar sesuai level_user
// echo "Memuat sidebar: " . $sidebar_file; exit();

// Load sidebar sesuai level_user
$sidebar_path = __DIR__ . "/includes/$sidebar_file";

if (file_exists($sidebar_path)) {
    include($sidebar_path);
} else {
    echo "<h4>Sidebar tidak ditemukan!</h4>";
}
?>

<?php include(__DIR__ . '/includes/footer.php'); ?>